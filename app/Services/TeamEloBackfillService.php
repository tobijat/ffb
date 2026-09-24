<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Team;
use App\Models\Teamelo;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Persist historical Elo ratings per team × league from eloratings.net TSVs.
 */
class TeamEloBackfillService
{
    /**
     * Default league → Elo TSV URL map for historical backfills.
     *
     * @return list<array{league_id: int, elo_url: string, elo_year: int}>
     */
    public static function defaultLeagueSources(): array
    {
        return [
            ['league_id' => 1, 'elo_url' => 'http://www.eloratings.net/2007.tsv', 'elo_year' => 2007],
            ['league_id' => 3, 'elo_url' => 'http://www.eloratings.net/2008.tsv', 'elo_year' => 2008],
            ['league_id' => 8, 'elo_url' => 'http://www.eloratings.net/2008.tsv', 'elo_year' => 2008],
            ['league_id' => 7, 'elo_url' => 'http://www.eloratings.net/2009.tsv', 'elo_year' => 2009],
            ['league_id' => 12, 'elo_url' => 'http://www.eloratings.net/2010.tsv', 'elo_year' => 2010],
            ['league_id' => 19, 'elo_url' => 'http://www.eloratings.net/2012.tsv', 'elo_year' => 2012],
            ['league_id' => 20, 'elo_url' => 'http://www.eloratings.net/2012.tsv', 'elo_year' => 2012],
            ['league_id' => 21, 'elo_url' => 'http://www.eloratings.net/2012.tsv', 'elo_year' => 2012],
            ['league_id' => 22, 'elo_url' => 'http://www.eloratings.net/2012.tsv', 'elo_year' => 2012],
            ['league_id' => 23, 'elo_url' => 'http://www.eloratings.net/2012.tsv', 'elo_year' => 2012],
            ['league_id' => 18, 'elo_url' => 'http://www.eloratings.net/2013.tsv', 'elo_year' => 2013],
            ['league_id' => 26, 'elo_url' => 'http://www.eloratings.net/2014.tsv', 'elo_year' => 2014],
            ['league_id' => 25, 'elo_url' => 'http://www.eloratings.net/2015.tsv', 'elo_year' => 2015],
        ];
    }

    /**
     * Extract YYYY from a path like …/2007.tsv.
     */
    public static function yearFromEloUrl(string $eloUrl): ?int
    {
        $path = (string) (parse_url($eloUrl, PHP_URL_PATH) ?? '');
        if (preg_match('/(\d{4})\.tsv$/i', $path, $matches) === 1) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     dry_run?: bool,
     *     league_id?: int,
     *     elo_year?: int,
     *     elo_url?: string,
     *     team_count?: int,
     *     written?: int,
     *     skipped_teams?: list<array{team_id: int, team_name: string}>,
     *     teams?: list<array{team_id: int, team_name: string, elo_rating: float}>
     * }
     */
    public function backfillLeague(
        int $leagueId,
        EloRatingClient $eloRating,
        int $eloYear,
        bool $execute = false,
    ): array {
        if ($leagueId <= 0 || ! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.']];
        }

        if ($eloYear < 1900 || $eloYear > 2100) {
            return ['ok' => false, 'errors' => ['Ungültiges Elo-Jahr.'], 'league_id' => $leagueId];
        }

        $teamIds = $this->teamIdsForLeague($leagueId);
        if ($teamIds === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Teams mit Matches in dieser Liga gefunden.'],
                'league_id' => $leagueId,
            ];
        }

        try {
            $eloRows = $eloRating->ratingsForTeamList($teamIds);
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()], 'league_id' => $leagueId];
        }

        $mappedIds = array_map('intval', array_column($eloRows, 'team_id'));
        $missingIds = array_values(array_diff(array_map('intval', $teamIds), $mappedIds));
        $names = Team::query()
            ->whereIn('team_id', array_values(array_unique([...$mappedIds, ...$missingIds])))
            ->pluck('team_name', 'team_id')
            ->all();

        $teams = [];
        foreach ($eloRows as $row) {
            $teamId = (int) $row['team_id'];
            $teams[] = [
                'team_id' => $teamId,
                'team_name' => (string) ($names[$teamId] ?? ('Team #'.$teamId)),
                'elo_rating' => (float) $row['elo_rating'],
            ];
        }
        usort($teams, static fn (array $a, array $b): int => $b['elo_rating'] <=> $a['elo_rating']);

        $skippedTeams = [];
        foreach ($missingIds as $missingId) {
            $skippedTeams[] = [
                'team_id' => $missingId,
                'team_name' => (string) ($names[$missingId] ?? ('Team #'.$missingId)),
            ];
        }
        usort($skippedTeams, static fn (array $a, array $b): int => strcasecmp($a['team_name'], $b['team_name']));

        if ($teams === []) {
            return [
                'ok' => false,
                'errors' => ['Keine ELO-Ratings für die Teams gefunden.'],
                'league_id' => $leagueId,
                'elo_year' => $eloYear,
                'skipped_teams' => $skippedTeams,
            ];
        }

        $written = 0;
        if ($execute) {
            try {
                DB::transaction(function () use ($teams, $leagueId, $eloYear, &$written): void {
                    foreach ($teams as $team) {
                        $row = Teamelo::query()
                            ->where('teamelo_team_id', $team['team_id'])
                            ->where('teamelo_league_id', $leagueId)
                            ->first();

                        if ($row === null) {
                            $row = new Teamelo;
                            $row->teamelo_team_id = $team['team_id'];
                            $row->teamelo_league_id = $leagueId;
                        }

                        $row->teamelo_elo = $team['elo_rating'];
                        $row->teamelo_elo_year = $eloYear;
                        $row->save();
                        $written++;
                    }
                });
            } catch (Throwable $e) {
                return [
                    'ok' => false,
                    'errors' => [$e->getMessage()],
                    'league_id' => $leagueId,
                    'elo_year' => $eloYear,
                    'skipped_teams' => $skippedTeams,
                    'teams' => $teams,
                ];
            }
        } else {
            $written = count($teams);
        }

        return [
            'ok' => true,
            'dry_run' => ! $execute,
            'message' => sprintf(
                '%s: %d Team(s) für Liga %d (Elo-Jahr %d)%s.',
                $execute ? 'Team-Elo gespeichert' : 'Dry-run Team-Elo',
                count($teams),
                $leagueId,
                $eloYear,
                $skippedTeams === [] ? '' : (', '.count($skippedTeams).' ohne ELO übersprungen'),
            ),
            'league_id' => $leagueId,
            'elo_year' => $eloYear,
            'team_count' => count($teams),
            'written' => $written,
            'skipped_teams' => $skippedTeams,
            'teams' => $teams,
        ];
    }

    /**
     * @return list<int>
     */
    public function teamIdsForLeague(int $leagueId): array
    {
        $matches = MatchGame::query()
            ->join('ffb_matchround', 'ffb_match.match_round', '=', 'ffb_matchround.matchround_id')
            ->where('ffb_matchround.matchround_league_id', $leagueId)
            ->get(['ffb_match.match_hometeam_id', 'ffb_match.match_guestteam_id']);

        $ids = [];
        foreach ($matches as $match) {
            $ids[] = (int) $match->match_hometeam_id;
            $ids[] = (int) $match->match_guestteam_id;
        }

        return array_values(array_unique(array_filter($ids)));
    }
}
