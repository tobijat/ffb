<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

/**
 * Loads world Elo ratings from eloratings.net and maps team names to FFB team IDs
 * via the local resources/data/elo/teams.csv file, with a fallback to exact
 * local ffb_team.team_name matches.
 *
 * The public site is a JS shell; ratings are published as TSV:
 * - World.tsv (rank + team code + rating …)
 * - en.teams.tsv (team code → English name / aliases)
 */
class EloRatingClient
{
    private const DEFAULT_RATINGS_URL = 'https://www.eloratings.net/World.tsv';

    private const DEFAULT_TEAMS_URL = 'https://www.eloratings.net/en.teams.tsv';

    /** @var array<int, float|string>|null team_id => elo */
    private ?array $ratings = null;

    public function __construct(
        private readonly ?string $eloUrl = null,
        private readonly ?string $teamMapPath = null,
        private readonly ?string $teamsUrl = null,
    ) {}

    /**
     * Historical / year snapshot feed: http://www.eloratings.net/<year>.tsv
     */
    public static function ratingsUrlForYear(int $year): string
    {
        return 'http://www.eloratings.net/'.$year.'.tsv';
    }

    /**
     * Client that loads ratings from a specific year TSV (isolated cache).
     */
    public function forYear(int $year): self
    {
        return new self(
            self::ratingsUrlForYear($year),
            $this->teamMapPath,
            $this->teamsUrl,
        );
    }

    public function getEloRatingForTeam(int $teamId): ?float
    {
        $ratings = $this->ratings();
        if (! array_key_exists($teamId, $ratings)) {
            return null;
        }

        return (float) $ratings[$teamId];
    }

    /**
     * @return array<int, float|string>
     */
    public function ratings(): array
    {
        if ($this->ratings === null) {
            $this->ratings = $this->loadRatings();
        }

        return $this->ratings;
    }

    /**
     * @param  list<int>  $teamIdList
     * @return list<array{team_id: int, elo_rating: float}>
     */
    public function ratingsForTeamList(array $teamIdList): array
    {
        $rows = [];
        foreach ($teamIdList as $teamId) {
            $teamId = (int) $teamId;
            $elo = $this->getEloRatingForTeam($teamId);
            if ($elo === null) {
                continue;
            }
            $rows[] = [
                'team_id' => $teamId,
                'elo_rating' => $elo,
            ];
        }

        usort($rows, static fn (array $a, array $b): int => $a['elo_rating'] <=> $b['elo_rating']);

        return $rows;
    }

    /**
     * @return array<int, float|string>
     */
    private function loadRatings(): array
    {
        $mapPath = $this->teamMapPath
            ?? (string) config('ffb.elo.team_map_path', resource_path('data/elo/teams.csv'));
        $ratingsUrl = $this->resolveRatingsUrl(
            $this->eloUrl ?? (string) config('ffb.elo.url', self::DEFAULT_RATINGS_URL),
        );
        $teamsUrl = $this->teamsUrl
            ?? (string) config('ffb.elo.teams_url', self::DEFAULT_TEAMS_URL);

        $teamNameMap = $this->getTeamNameMap($mapPath);
        $codeToNames = $this->getTeamCodeNames($teamsUrl);

        return $this->getEloRatingsFromTsv($ratingsUrl, $codeToNames, $teamNameMap);
    }

    /**
     * Accept legacy world.html config values and map them to the TSV feed.
     */
    private function resolveRatingsUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return self::DEFAULT_RATINGS_URL;
        }

        $path = strtolower((string) (parse_url($url, PHP_URL_PATH) ?? ''));
        if (str_ends_with($path, '.tsv')) {
            return $url;
        }

        if (str_contains($path, 'world')) {
            $scheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';
            $host = parse_url($url, PHP_URL_HOST) ?: 'www.eloratings.net';

            return $scheme.'://'.$host.'/World.tsv';
        }

        return $url;
    }

    /**
     * @param  array<string, list<string>>  $codeToNames
     * @param  array<string, int>  $teamNameMap  md5(name) => ffb team_id
     * @return array<int, float|string>
     */
    private function getEloRatingsFromTsv(string $url, array $codeToNames, array $teamNameMap): array
    {
        $content = $this->fetchRemote($url);
        $lines = preg_split('/\R/', $content) ?: [];
        $ratings = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = explode("\t", $line);
            if (count($parts) < 4) {
                continue;
            }

            $code = trim($parts[2]);
            $elo = trim($parts[3]);
            if ($code === '' || ! is_numeric($elo)) {
                continue;
            }

            foreach ($codeToNames[$code] ?? [] as $name) {
                $key = md5($name);
                if (! array_key_exists($key, $teamNameMap)) {
                    continue;
                }
                $ratings[(int) $teamNameMap[$key]] = $elo;
                break;
            }
        }

        if ($ratings === []) {
            throw new RuntimeException('No ELO ratings could be mapped from '.$url);
        }

        return $ratings;
    }

    /**
     * @return array<string, list<string>> team code => English names / aliases
     */
    private function getTeamCodeNames(string $url): array
    {
        $content = $this->fetchRemote($url);
        $lines = preg_split('/\R/', $content) ?: [];
        $map = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = explode("\t", $line);
            $code = trim((string) ($parts[0] ?? ''));
            if ($code === '' || str_contains($code, '_')) {
                continue;
            }

            $names = [];
            for ($i = 1; $i < count($parts); $i++) {
                $name = trim($parts[$i]);
                if ($name !== '') {
                    $names[] = $name;
                }
            }
            if ($names !== []) {
                $map[$code] = $names;
            }
        }

        if ($map === []) {
            throw new RuntimeException('ELO team dictionary empty at '.$url);
        }

        return $map;
    }

    /**
     * @return array<string, int>
     */
    private function getTeamNameMap(string $path): array
    {
        // Local FFB names first so exact English team_name matches still work if
        // teams.csv lags; CSV aliases overwrite on conflict.
        $teamList = $this->localTeamNameMap();

        $content = $this->readLocalFile($path);
        $teams = array_filter(explode(';;', $content));
        foreach ($teams as $team) {
            $parts = explode(';', $team);
            if (count($parts) < 4) {
                continue;
            }
            $id = (int) trim($parts[0]);
            $eloName = $parts[3];
            $teamList[md5($eloName)] = $id;
        }

        return $teamList;
    }

    /**
     * Exact team_name → team_id for names that already match eloratings English labels.
     *
     * @return array<string, int>
     */
    private function localTeamNameMap(): array
    {
        if (! Schema::hasTable('ffb_team')) {
            return [];
        }

        $map = [];
        foreach (Team::query()->get(['team_id', 'team_name']) as $team) {
            $name = trim((string) $team->team_name);
            if ($name === '') {
                continue;
            }
            $map[md5($name)] = (int) $team->team_id;
        }

        return $map;
    }

    private function readLocalFile(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            throw new RuntimeException('ELO team map path is empty.');
        }

        $contents = @file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException('Failed to read local ELO map at '.$path);
        }

        return $contents;
    }

    private function fetchRemote(string $url): string
    {
        $request = Http::withHeaders([
            'User-Agent' => 'SoccerSportsfan',
            'Accept' => 'text/plain,text/tab-separated-values,text/html,*/*',
        ])->timeout(60);

        if (! config('ffb.http.verify_ssl', true)) {
            $request = $request->withOptions(['verify' => false]);
        }

        $response = $request->get($url);
        if (! $response->successful()) {
            throw new RuntimeException('Failed to fetch '.$url.' (HTTP '.$response->status().')');
        }

        return (string) $response->body();
    }
}
