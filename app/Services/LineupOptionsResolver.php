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
            'lineup_min_bench',
            'lineup_max_bench',
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
     *     lineup_min_bench: int,
     *     lineup_max_bench: int,
     *     league_benchmode: ?string,
     *     league_lineup_min_bench: int,
     *     league_lineup_max_bench: int,
     *     source: 'matchround'|'league'|'fallback'
     * }
     */
    public function forMatchround(int $matchroundId): array
    {
        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return $this->fallback();
        }

        $leagueId = (int) $matchround->matchround_league_id;

        $override = MatchroundOptions::query()
            ->where('matchround_options_matchround_id', $matchroundId)
            ->first();

        if ($override) {
            return $this->withLeagueBenchSettings(
                $this->fromMatchroundOptions($override) + ['source' => 'matchround'],
                $leagueId,
            );
        }

        return $this->forLeague($leagueId);
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
     *     lineup_min_bench: int,
     *     lineup_max_bench: int,
     *     league_benchmode: ?string,
     *     league_lineup_min_bench: int,
     *     league_lineup_max_bench: int,
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
     *     lineup_max_s: int,
     *     lineup_min_bench: int,
     *     lineup_max_bench: int,
     *     league_benchmode: ?string,
     *     league_lineup_min_bench: int,
     *     league_lineup_max_bench: int
     * }
     */
    private function fromLeagueOptions(LeagueOptions $options): array
    {
        $minBench = (int) ($options->options_lineup_min_bench ?? 0);
        $maxBench = (int) ($options->options_lineup_max_bench ?? 0);

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
            'lineup_min_bench' => $minBench,
            'lineup_max_bench' => $maxBench,
            'league_benchmode' => $this->normalizeBenchmode($options->options_league_benchmode ?? null),
            'league_lineup_min_bench' => $minBench,
            'league_lineup_max_bench' => $maxBench,
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
     *     lineup_min_bench: int,
     *     lineup_max_bench: int
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
            'lineup_min_bench' => (int) ($options->matchround_options_lineup_min_bench ?? 0),
            'lineup_max_bench' => (int) ($options->matchround_options_lineup_max_bench ?? 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $resolved
     * @return array<string, mixed>
     */
    private function withLeagueBenchSettings(array $resolved, int $leagueId): array
    {
        $league = $leagueId > 0
            ? LeagueOptions::query()->where('options_league_id', $leagueId)->first()
            : null;

        $resolved['league_benchmode'] = $this->normalizeBenchmode($league?->options_league_benchmode);
        $resolved['league_lineup_min_bench'] = (int) ($league?->options_lineup_min_bench ?? 0);
        $resolved['league_lineup_max_bench'] = (int) ($league?->options_lineup_max_bench ?? 0);

        return $resolved;
    }

    private function normalizeBenchmode(mixed $mode): ?string
    {
        if ($mode === null || $mode === '') {
            return null;
        }

        $mode = (string) $mode;

        return in_array($mode, ['cover', 'bestof'], true) ? $mode : null;
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
     *     lineup_min_bench: int,
     *     lineup_max_bench: int,
     *     league_benchmode: null,
     *     league_lineup_min_bench: int,
     *     league_lineup_max_bench: int,
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
            'lineup_min_bench' => 0,
            'lineup_max_bench' => 0,
            'league_benchmode' => null,
            'league_lineup_min_bench' => 0,
            'league_lineup_max_bench' => 0,
            'source' => 'fallback',
        ];
    }
}
