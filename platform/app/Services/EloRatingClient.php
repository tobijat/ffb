<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Port of legacy modules/administration/ELORating.php.
 *
 * Loads world Elo ratings and maps Elo team names to FFB team IDs via CSV.
 */
class EloRatingClient
{
    private const TEAM_NAME_MAP_URL = 'http://soccer.sportsfan.at/parserfiles/teams/teams.csv';

    /** @var array<int, float|string>|null team_id => elo */
    private ?array $ratings = null;

    public function __construct(
        private readonly ?string $eloUrl = null,
        private readonly ?string $teamMapUrl = null,
    ) {
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
        $mapUrl = $this->teamMapUrl ?? (string) config('ffb.elo.team_map_url', self::TEAM_NAME_MAP_URL);
        $eloUrl = $this->eloUrl ?? (string) config('ffb.elo.url', 'http://www.eloratings.net/world.html');

        $teamNameMap = $this->getTeamNameMap($mapUrl);

        return $this->getEloRatingsFromUrl($eloUrl, $teamNameMap);
    }

    /**
     * @param  array<string, int>  $teamNameMap
     * @return array<int, float|string>
     */
    private function getEloRatingsFromUrl(string $url, array $teamNameMap): array
    {
        $content = $this->normalizeString($this->fetch($url));

        $eloTableStartPattern = '<table cellspacing="0" border="border" bordercolor="white" rules="groups" frame="void">';
        $eloTableEndPattern = '</table>';
        $eloTableStartPos = strpos($content, $eloTableStartPattern);
        if ($eloTableStartPos === false) {
            throw new RuntimeException('ELO table not found at '.$url);
        }
        $eloTableStartPos += strlen($eloTableStartPattern);

        $startPos = strpos($content, '<tr><td>', $eloTableStartPos);
        $endPos = strpos($content, $eloTableEndPattern, $eloTableStartPos);
        if ($startPos === false || $endPos === false || $endPos <= $startPos) {
            throw new RuntimeException('ELO table body not found at '.$url);
        }

        $eloTableHeadings = '<tr class="sh"><td rowspan="2" class="sh">rank</td><td rowspan="2" class="sh">team</td><td rowspan="2" class="sh">rating</td><td colspan="2" class="sh">highest</td><td colspan="2" class="th">1 yr change</td><td colspan="7" class="sh">matches</td><td colspan="2" class="sh">goals</td></tr><tr class="lh"><td class="lh">rank</td><td class="lh">rating</td><td class="lh">rank</td><td class="lh">rating</td><td class="lh">total</td><td class="lh">home</td><td class="lh">away</td><td class="lh">neutral</td><td class="lh">wins</td><td class="lh">losses</td><td class="lh">draws</td><td class="lh">for</td><td class="lh">against</td></tr>';

        $eloTable = substr($content, $startPos, $endPos - $startPos);
        $eloTable = str_replace($eloTableHeadings, '', $eloTable);

        $teams = array_filter(explode('<tr><td>', $eloTable));
        $ratings = [];

        foreach ($teams as $team) {
            $eloName = $this->getTeamNameFromString($team);
            $key = md5($eloName);
            if (! array_key_exists($key, $teamNameMap)) {
                continue;
            }
            $ratings[(int) $teamNameMap[$key]] = $this->getTeamEloFromString($team);
        }

        return $ratings;
    }

    /**
     * @return array<string, int>
     */
    private function getTeamNameMap(string $url): array
    {
        $content = $this->fetch($url);
        $teams = array_filter(explode(';;', $content));
        $teamList = [];
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

    private function getTeamNameFromString(string $string): string
    {
        $parts = explode('<td>', $string);
        if (! isset($parts[1])) {
            return '';
        }
        $open = strpos($parts[1], '">');
        $close = strpos($parts[1], '</a>');
        if ($open === false || $close === false || $close <= $open + 2) {
            return '';
        }

        return substr($parts[1], $open + 2, $close - ($open + 2));
    }

    private function getTeamEloFromString(string $string): string
    {
        $parts = explode('<td>', $string);
        if (! isset($parts[2])) {
            return '0';
        }
        $close = strpos($parts[2], '</td>');
        if ($close === false) {
            return trim($parts[2]);
        }

        return substr($parts[2], 0, $close);
    }

    private function normalizeString(string $string): string
    {
        $string = str_replace("\t", '', trim($string));
        $string = str_replace("\r", '', trim($string));

        return str_replace("\n", '', trim($string));
    }

    private function fetch(string $url): string
    {
        $response = Http::timeout(60)->get($url);
        if (! $response->successful()) {
            throw new RuntimeException('Failed to fetch '.$url.' (HTTP '.$response->status().')');
        }

        return (string) $response->body();
    }
}
