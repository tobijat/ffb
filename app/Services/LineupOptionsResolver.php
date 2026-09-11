<?php

namespace App\Services;

use App\Models\LeagueOptions;
use App\Models\Matchround;
use App\Models\MatchroundOptions;

/**
 * Resolve effective lineup limits: matchround override row, else league defaults.
 */
class LineupOptionsResolver
{
    /**
     * @return list<string>
     */
    public static function lineupKeys(): array
    {
        return [
            'lineup_max_players',
            'lineup_max_credits',
            'lineup_max_players_team',
            'lineup_min_g',
            'lineup_min_d',
            'lineup_min_m',
            'lineup_min_s',
            'lineup_max_g',
            'lineup_max_d',
            'lineup_max_m',
            'lineup_max_s',
        ];
    }

    /**
     * @return array{
     *     lineup_max_players: int,
     *     lineup_max_credits: float,
     *     lineup_max_players_team: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int,
     *     source: 'matchround'|'league'|'fallback'
     * }
     */
    public function forMatchround(int $matchroundId): array
    {
        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return $this->fallback();
        }

        $override = MatchroundOptions::query()
            ->where('matchround_options_matchround_id', $matchroundId)
            ->first();

        if ($override) {
            return $this->fromMatchroundOptions($override) + ['source' => 'matchround'];
        }

        return $this->forLeague((int) $matchround->matchround_league_id);
    }

    /**
     * @return array{
     *     lineup_max_players: int,
     *     lineup_max_credits: float,
     *     lineup_max_players_team: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int,
     *     source: 'league'|'fallback'
     * }
     */
    public function forLeague(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return $this->fallback();
        }

        $options = LeagueOptions::query()->where('options_league_id', $leagueId)->first()
            ?? LeagueOptions::query()->where('options_league_id', 0)->first();

        if (! $options) {
            return $this->fallback();
        }

        return $this->fromLeagueOptions($options) + ['source' => 'league'];
    }

    /**
     * @return array{
     *     lineup_max_players: int,
     *     lineup_max_credits: float,
     *     lineup_max_players_team: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int
     * }
     */
    private function fromLeagueOptions(LeagueOptions $options): array
    {
        return [
            'lineup_max_players' => (int) $options->options_lineup_max_players,
            'lineup_max_credits' => (float) $options->options_lineup_max_credits,
            'lineup_max_players_team' => (int) $options->options_lineup_max_players_team,
            'lineup_min_g' => (int) $options->options_lineup_min_g,
            'lineup_min_d' => (int) $options->options_lineup_min_d,
            'lineup_min_m' => (int) $options->options_lineup_min_m,
            'lineup_min_s' => (int) $options->options_lineup_min_s,
            'lineup_max_g' => (int) $options->options_lineup_max_g,
            'lineup_max_d' => (int) $options->options_lineup_max_d,
            'lineup_max_m' => (int) $options->options_lineup_max_m,
            'lineup_max_s' => (int) $options->options_lineup_max_s,
        ];
    }

    /**
     * @return array{
     *     lineup_max_players: int,
     *     lineup_max_credits: float,
     *     lineup_max_players_team: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int
     * }
     */
    private function fromMatchroundOptions(MatchroundOptions $options): array
    {
        return [
            'lineup_max_players' => (int) $options->matchround_options_lineup_max_players,
            'lineup_max_credits' => (float) $options->matchround_options_lineup_max_credits,
            'lineup_max_players_team' => (int) $options->matchround_options_lineup_max_players_team,
            'lineup_min_g' => (int) $options->matchround_options_lineup_min_g,
            'lineup_min_d' => (int) $options->matchround_options_lineup_min_d,
            'lineup_min_m' => (int) $options->matchround_options_lineup_min_m,
            'lineup_min_s' => (int) $options->matchround_options_lineup_min_s,
            'lineup_max_g' => (int) $options->matchround_options_lineup_max_g,
            'lineup_max_d' => (int) $options->matchround_options_lineup_max_d,
            'lineup_max_m' => (int) $options->matchround_options_lineup_max_m,
            'lineup_max_s' => (int) $options->matchround_options_lineup_max_s,
        ];
    }

    /**
     * @return array{
     *     lineup_max_players: int,
     *     lineup_max_credits: float,
     *     lineup_max_players_team: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int,
     *     source: 'fallback'
     * }
     */
    private function fallback(): array
    {
        return [
            'lineup_max_players' => 11,
            'lineup_max_credits' => 100.0,
            'lineup_max_players_team' => 3,
            'lineup_min_g' => 1,
            'lineup_min_d' => 3,
            'lineup_min_m' => 3,
            'lineup_min_s' => 1,
            'lineup_max_g' => 1,
            'lineup_max_d' => 5,
            'lineup_max_m' => 5,
            'lineup_max_s' => 3,
            'source' => 'fallback',
        ];
    }
}
