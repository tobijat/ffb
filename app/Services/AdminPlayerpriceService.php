<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamelo;
use App\Models\Teamprice;
use App\Models\Userteam;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Admin pricing: ELO team prices, round performance, and recent-performance player prices.
 */
class AdminPlayerpriceService
{
    private const SQUAD_SIZE = 11;

    private const DEFAULT_EXPONENT = 2.0;

    private const DEFAULT_DREAM_TEAM_RATIO = 1.5;

    private const DEFAULT_MIN_PRICE = 1.0;

    private const DEFAULT_OPPONENT_WEIGHT = 0.25;

    private const DEFAULT_LOOKBACK_ROUNDS = 5;

    private const DEFAULT_DECAY_FACTOR = 0.7;

    private const DEFAULT_MAX_PRICE_ADJUSTMENT = 2.0;

    private const MIN_ELO_YEAR = 2007;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly EloRatingClient $eloRating,
        private readonly LineupOptionsResolver $lineupOptions,
    ) {}

    /**
     * @param  array<string, mixed>|null  $teamPricePreview
     * @param  array<string, mixed>|null  $performancePreview
     * @param  array<string, mixed>|null  $recentPerformancePreview
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        ?int $priceLeagueId = null,
        string $tab = 'teams',
        ?int $matchroundId = null,
        ?array $teamPricePreview = null,
        ?array $performancePreview = null,
        ?array $recentPerformancePreview = null,
    ): array {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagueId = $this->resolvePriceLeagueId($priceLeagueId, $shell);
        $resolvedTab = match ($tab) {
            'performance' => 'performance',
            'recent' => 'recent',
            default => 'teams',
        };
        $selectedLeague = $leagueId > 0
            ? $this->leagueOption($leagueId)
            : null;

        $resolvedMatchroundId = 0;
        if ($leagueId > 0 && $matchroundId !== null && $matchroundId > 0) {
            $resolvedMatchroundId = $this->matchroundBelongsToLeague($matchroundId, $leagueId)
                ? $matchroundId
                : 0;
            if ($resolvedTab === 'teams' && $resolvedMatchroundId > 0 && $this->matchroundHasUserLineups($resolvedMatchroundId)) {
                $resolvedMatchroundId = 0;
            }
        }

        $lineupLimits = $leagueId > 0
            ? ($resolvedMatchroundId > 0
                ? $this->lineupOptions->forMatchround($resolvedMatchroundId)
                : $this->lineupOptions->forLeague($leagueId))
            : $this->lineupOptions->forLeague(0);

        $performanceHasTeamelos = false;
        if ($resolvedTab === 'performance' && $resolvedMatchroundId > 0 && $leagueId > 0) {
            $performanceHasTeamelos = $this->leagueHasCompleteTeamelos($leagueId);
        }

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league_id' => $leagueId,
            'selected_league' => $selectedLeague,
            'price_league_id' => $leagueId,
            'leagues' => $this->leagueOptions(),
            'tab' => $resolvedTab,
            'matchrounds' => $leagueId > 0
                ? ($resolvedTab === 'teams'
                    ? $this->matchroundsForTeamPrice($leagueId)
                    : $this->matchrounds($leagueId))
                : [],
            'matchround_id' => $resolvedMatchroundId,
            'lineup_max_credits' => (float) ($lineupLimits['lineup_max_credits'] ?? 100),
            'lineup_max_players_team' => (int) ($lineupLimits['lineup_max_players_team'] ?? 3),
            'lineup_limits_source' => (string) ($lineupLimits['source'] ?? 'fallback'),
            'elo_exponent' => self::DEFAULT_EXPONENT,
            'elo_dream_team_ratio' => self::DEFAULT_DREAM_TEAM_RATIO,
            'elo_min_price' => self::DEFAULT_MIN_PRICE,
            'elo_year' => (int) now()->year,
            'elo_year_options' => $this->eloYearOptions(),
            'team_price_preview' => $teamPricePreview,
            'performance_preview' => $performancePreview,
            'performance_has_teamelos' => $performanceHasTeamelos,
            'performance_opponent_weight' => self::DEFAULT_OPPONENT_WEIGHT,
            'recent_performance_preview' => $recentPerformancePreview,
            'recent_lookback_rounds' => self::DEFAULT_LOOKBACK_ROUNDS,
            'recent_decay_factor' => self::DEFAULT_DECAY_FACTOR,
            'recent_max_price_adjustment' => self::DEFAULT_MAX_PRICE_ADJUSTMENT,
        ];
    }

    /**
     * Resolve league for price actions from the admin-center / start-page selection.
     *
     * @param  array<string, mixed>  $input
     */
    public function resolveLeagueIdFromInput(int $userId, array $input): int
    {
        return $this->adminCenter->selectedLeagueId($userId);
    }

    /**
     * Preview round_performance for all players who played in a matchround (no DB writes).
     *
     * Per position, players with minutes > 0 are ranked by points (0 = worst, n−1 = best)
     * against the full peer set of that position across all earlier league matchrounds
     * plus the selected one (not only the teams still left in a late knockout round).
     * Ties share the average of the ranks they would occupy. Then
     * round_performance = (rank / (n − 1)) * 2 − 1, or 0 when n = 1.
     *
     * Optionally adjusts by opponent Elo strength when include_opponent_strength is set
     * and complete ffb_teamelo rows exist for every team in the league. The Elo span
     * (min/max) is always the full league spectrum, not only the teams in the selected round.
     *
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     price_league_id?: int,
     *     matchround_id?: int,
     *     tab?: string,
     *     preview?: array{
     *         matchround_id: int,
     *         include_opponent_strength: bool,
     *         opponent_weight: float,
     *         positions: array<string, array{sample_size: int}>,
     *         players: list<array{
     *             playerstats_id: int,
     *             playerteam_id: int,
     *             player_name: string,
     *             team_name: string,
     *             position: string,
     *             points: float,
     *             rank: float,
     *             raw_round_performance: float,
     *             opponent_factor: float|null,
     *             round_performance: float
     *         }>
     *     }
     * }
     */
    public function previewMatchroundPerformance(int $userId, array $input): array
    {
        $leagueId = $this->resolveLeagueIdFromInput($userId, $input);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.'], 'tab' => 'performance'];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        if ($matchroundId <= 0) {
            return [
                'ok' => false,
                'errors' => ['Bitte eine Spielrunde wählen.'],
                'price_league_id' => $leagueId,
                'tab' => 'performance',
            ];
        }
        if (! $this->matchroundBelongsToLeague($matchroundId, $leagueId)) {
            return [
                'ok' => false,
                'errors' => ['Ungültige Spielrunde für die aktive Liga.'],
                'price_league_id' => $leagueId,
                'tab' => 'performance',
            ];
        }

        $includeOpponentStrength = $this->truthyInput($input['include_opponent_strength'] ?? null);
        $opponentWeight = isset($input['opponent_weight']) && $input['opponent_weight'] !== ''
            ? (float) $input['opponent_weight']
            : self::DEFAULT_OPPONENT_WEIGHT;

        /** @var array<int, float> $eloByTeamId */
        $eloByTeamId = [];
        $minElo = 0.0;
        $maxElo = 0.0;
        /** @var array<int, array{home: int, guest: int}> $matchesById */
        $matchesById = [];

        if ($includeOpponentStrength) {
            $leagueTeamIds = $this->teamIdsForGame($leagueId);
            $eloByTeamId = $this->teamElosForLeague($leagueTeamIds, $leagueId);
            if ($leagueTeamIds === [] || count($eloByTeamId) !== count($leagueTeamIds)) {
                return [
                    'ok' => false,
                    'errors' => [
                        'Gegnerstärke benötigt ELO-Werte (ffb_teamelo) für alle Teams der Liga. Bitte zuerst im Tab Team-Preis speichern oder Team-Elo backfüllen.',
                    ],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'performance',
                ];
            }

            $eloValues = array_values($eloByTeamId);
            $minElo = min($eloValues);
            $maxElo = max($eloValues);

            $matchesById = MatchGame::query()
                ->where('match_round', $matchroundId)
                ->get(['match_id', 'match_hometeam_id', 'match_guestteam_id'])
                ->mapWithKeys(static fn (MatchGame $match): array => [
                    (int) $match->match_id => [
                        'home' => (int) $match->match_hometeam_id,
                        'guest' => (int) $match->match_guestteam_id,
                    ],
                ])
                ->all();
        }

        $rows = Playerstats::query()
            ->join('ffb_playerteam', 'ffb_playerteam.playerteam_id', '=', 'ffb_playerstats.playerstats_playerteam_id')
            ->join('ffb_player', 'ffb_player.player_id', '=', 'ffb_playerteam.playerteam_player_id')
            ->join('ffb_team', 'ffb_team.team_id', '=', 'ffb_playerteam.playerteam_team_id')
            ->where('ffb_playerstats.playerstats_matchround_id', $matchroundId)
            ->where('ffb_playerstats.playerstats_minutes', '>', 0)
            ->orderBy('ffb_playerteam.playerteam_player_position')
            ->orderBy('ffb_team.team_name')
            ->orderBy('ffb_player.player_lname')
            ->orderBy('ffb_player.player_fname')
            ->get([
                'ffb_playerstats.playerstats_id',
                'ffb_playerstats.playerstats_playerteam_id',
                'ffb_playerstats.playerstats_match_id',
                'ffb_playerstats.playerstats_score',
                'ffb_playerteam.playerteam_player_position',
                'ffb_playerteam.playerteam_team_id',
                'ffb_player.player_fname',
                'ffb_player.player_lname',
                'ffb_team.team_name',
            ]);

        $rankingRoundIds = $this->matchroundIdsThrough($leagueId, $matchroundId);
        $peerRows = Playerstats::query()
            ->join('ffb_playerteam', 'ffb_playerteam.playerteam_id', '=', 'ffb_playerstats.playerstats_playerteam_id')
            ->whereIn('ffb_playerstats.playerstats_matchround_id', $rankingRoundIds)
            ->where('ffb_playerstats.playerstats_minutes', '>', 0)
            ->get([
                'ffb_playerstats.playerstats_id',
                'ffb_playerstats.playerstats_matchround_id',
                'ffb_playerstats.playerstats_score',
                'ffb_playerteam.playerteam_player_position',
            ]);

        /** @var array<string, list<object>> $peerRowsByPosition */
        $peerRowsByPosition = [];
        foreach ($peerRows as $row) {
            $position = strtolower(trim((string) $row->playerteam_player_position));
            if ($position === '') {
                continue;
            }
            $peerRowsByPosition[$position][] = $row;
        }

        /** @var array<string, array{sample_size: int}> $positions */
        $positions = [];
        /** @var array<int, array{rank: float, round_performance: float}> $byStatsId */
        $byStatsId = [];

        foreach ($peerRowsByPosition as $position => $positionRows) {
            $n = count($positionRows);
            $positions[$position] = ['sample_size' => $n];

            usort(
                $positionRows,
                static fn (object $a, object $b): int => ((float) $a->playerstats_score) <=> ((float) $b->playerstats_score)
            );

            $i = 0;
            while ($i < $n) {
                $j = $i;
                $points = (float) $positionRows[$i]->playerstats_score;
                while ($j + 1 < $n && (float) $positionRows[$j + 1]->playerstats_score === $points) {
                    $j++;
                }

                $tieCount = $j - $i + 1;
                $rankSum = 0.0;
                for ($r = $i; $r <= $j; $r++) {
                    $rankSum += $r;
                }
                $rank = $rankSum / $tieCount;
                $roundPerformance = $n === 1
                    ? 0.0
                    : ($rank / ($n - 1)) * 2 - 1;

                for ($r = $i; $r <= $j; $r++) {
                    if ((int) $positionRows[$r]->playerstats_matchround_id !== $matchroundId) {
                        continue;
                    }
                    $byStatsId[(int) $positionRows[$r]->playerstats_id] = [
                        'rank' => $rank,
                        'round_performance' => $roundPerformance,
                    ];
                }

                $i = $j + 1;
            }
        }

        $eloSpan = $maxElo - $minElo;
        $players = [];
        foreach ($rows as $row) {
            $position = strtolower(trim((string) $row->playerteam_player_position));
            if ($position === '') {
                continue;
            }

            $statsId = (int) $row->playerstats_id;
            $ranked = $byStatsId[$statsId] ?? ['rank' => 0.0, 'round_performance' => 0.0];
            $rawPerformance = (float) $ranked['round_performance'];
            $opponentFactor = null;
            $roundPerformance = $rawPerformance;

            if ($includeOpponentStrength) {
                $opponentFactor = $this->opponentFactorForPlayer(
                    (int) $row->playerteam_team_id,
                    (int) ($row->playerstats_match_id ?? 0),
                    $matchesById,
                    $eloByTeamId,
                    $eloSpan,
                );
                $roundPerformance = max(
                    -1.0,
                    min(1.0, $rawPerformance + ($opponentWeight * $opponentFactor)),
                );
            }

            $fname = trim((string) $row->player_fname);
            $lname = trim((string) $row->player_lname);
            $players[] = [
                'playerstats_id' => $statsId,
                'playerteam_id' => (int) $row->playerstats_playerteam_id,
                'player_name' => trim($fname.' '.$lname),
                'team_name' => (string) $row->team_name,
                'position' => $position,
                'points' => (float) $row->playerstats_score,
                'rank' => round((float) $ranked['rank'], 3),
                'raw_round_performance' => round($rawPerformance, 3),
                'opponent_factor' => $opponentFactor === null ? null : round($opponentFactor, 3),
                'round_performance' => round($roundPerformance, 3),
            ];
        }

        $message = $includeOpponentStrength
            ? 'Matchround-Performance berechnet (Vorschau, nicht gespeichert; inkl. Gegnerstärke).'
            : 'Matchround-Performance berechnet (Vorschau, nicht gespeichert).';

        return [
            'ok' => true,
            'message' => $message,
            'price_league_id' => $leagueId,
            'matchround_id' => $matchroundId,
            'tab' => 'performance',
            'preview' => [
                'matchround_id' => $matchroundId,
                'include_opponent_strength' => $includeOpponentStrength,
                'opponent_weight' => $opponentWeight,
                'positions' => $positions,
                'players' => $players,
            ],
        ];
    }

    /**
     * Recompute matchround performance (same options as preview) and persist to
     * ffb_playerstats.playerstats_round_performance.
     *
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     price_league_id?: int,
     *     matchround_id?: int,
     *     tab?: string,
     *     preview?: array<string, mixed>
     * }
     */
    public function saveMatchroundPerformance(int $userId, array $input): array
    {
        $built = $this->previewMatchroundPerformance($userId, $input);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        $preview = $built['preview'] ?? [];
        /** @var list<array{playerstats_id: int, round_performance: float, player_name?: string}> $players */
        $players = is_array($preview['players'] ?? null) ? $preview['players'] : [];
        $matchroundId = (int) ($built['matchround_id'] ?? 0);
        $leagueId = (int) ($built['price_league_id'] ?? 0);

        if ($players === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spieler-Performance zum Speichern vorhanden.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'performance',
                'preview' => $preview,
            ];
        }

        $details = [];
        try {
            DB::transaction(function () use ($players, &$details): void {
                foreach ($players as $row) {
                    $statsId = (int) ($row['playerstats_id'] ?? 0);
                    if ($statsId <= 0) {
                        continue;
                    }
                    $value = round((float) ($row['round_performance'] ?? 0), 3);
                    Playerstats::query()
                        ->whereKey($statsId)
                        ->update(['playerstats_round_performance' => $value]);
                    $details[] = ($row['player_name'] ?? ('#'.$statsId)).': '.$value;
                }
            });
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'errors' => [$e->getMessage()],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'performance',
                'preview' => $preview,
            ];
        }

        return [
            'ok' => true,
            'message' => sprintf(
                'Matchround-Performance gespeichert: %d Spieler.',
                count($details),
            ),
            'details' => $details,
            'price_league_id' => $leagueId,
            'matchround_id' => $matchroundId,
            'tab' => 'performance',
            'preview' => $preview,
        ];
    }

    /**
     * Preview recent_performance for the full league squad (no DB writes).
     *
     * Uses up to LOOKBACK_ROUNDS matchrounds prior to the selected round.
     * weights[i] = DECAY_FACTOR^i (i=0 newest prior round).
     * weighted_avg = sum(round_performance[i] * weights[i] for played rounds)
     *              / sum(all weights including missed rounds).
     *
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     price_league_id?: int,
     *     matchround_id?: int,
     *     tab?: string,
     *     preview?: array{
     *         matchround_id: int,
     *         lookback_rounds: int,
     *         decay_factor: float,
     *         include_external_rounds: bool,
     *         prior_matchround_ids: list<int>,
     *         prior_matchrounds: list<array{matchround_id: int, matchround_title: string, weight: float, external: bool}>,
     *         weights: list<float>,
     *         players: list<array{
     *             playerteam_id: int,
     *             player_name: string,
     *             team_name: string,
     *             position: string,
     *             round_performance: list<string>,
     *             recent_performance: float,
     *             rounds_played: int
     *         }>
     *     }
     * }
     */
    public function previewRecentPerformance(int $userId, array $input): array
    {
        $leagueId = $this->resolveLeagueIdFromInput($userId, $input);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.'], 'tab' => 'recent'];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        if ($matchroundId <= 0) {
            return [
                'ok' => false,
                'errors' => ['Bitte eine Spielrunde wählen.'],
                'price_league_id' => $leagueId,
                'tab' => 'recent',
            ];
        }
        if (! $this->matchroundBelongsToLeague($matchroundId, $leagueId)) {
            return [
                'ok' => false,
                'errors' => ['Ungültige Spielrunde für die aktive Liga.'],
                'price_league_id' => $leagueId,
                'tab' => 'recent',
            ];
        }

        $lookbackRounds = isset($input['lookback_rounds']) && $input['lookback_rounds'] !== ''
            ? (int) $input['lookback_rounds']
            : self::DEFAULT_LOOKBACK_ROUNDS;
        if ($lookbackRounds < 1) {
            return [
                'ok' => false,
                'errors' => ['LOOKBACK_ROUNDS muss mindestens 1 sein.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        $decayFactor = isset($input['decay_factor']) && $input['decay_factor'] !== ''
            ? (float) $input['decay_factor']
            : self::DEFAULT_DECAY_FACTOR;
        if ($decayFactor < 0.0 || $decayFactor > 1.0) {
            return [
                'ok' => false,
                'errors' => ['DECAY_FACTOR muss zwischen 0 und 1 liegen.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        $includeExternalRounds = $this->truthyInput($input['include_external_rounds'] ?? null);

        $maxPriceAdjustment = isset($input['max_price_adjustment']) && $input['max_price_adjustment'] !== ''
            ? (float) $input['max_price_adjustment']
            : self::DEFAULT_MAX_PRICE_ADJUSTMENT;
        if ($maxPriceAdjustment < 0.0) {
            return [
                'ok' => false,
                'errors' => ['MAX_PRICE_ADJUSTMENT muss ≥ 0 sein.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        $missingSelected = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->where('playerstats_minutes', '>', 0)
            ->whereNull('playerstats_round_performance')
            ->count();
        if ($missingSelected > 0) {
            return [
                'ok' => false,
                'errors' => [
                    sprintf(
                        'Für die gewählte Spielrunde fehlen noch %d playerstats_round_performance-Werte (Spieler mit Minuten > 0). Bitte zuerst im Tab Spieler-Performance berechnen und speichern.',
                        $missingSelected,
                    ),
                ],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        $hasAnySelectedStats = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->where('playerstats_minutes', '>', 0)
            ->exists();
        if (! $hasAnySelectedStats) {
            return [
                'ok' => false,
                'errors' => [
                    'Für die gewählte Spielrunde gibt es keine Spielerstats mit Einsatz (Minuten > 0).',
                ],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        $matchTeamIds = $this->teamIdsForMatchround($matchroundId);
        if ($matchTeamIds === []) {
            return [
                'ok' => false,
                'errors' => [
                    'In der gewählten Spielrunde gibt es keine Teams mit Matches.',
                ],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        if (! $this->matchroundHasCompleteTeamprices($matchroundId)) {
            return [
                'ok' => false,
                'errors' => [
                    'Spielerpreis benötigt vollständige Teampreise für alle Teams dieser Spielrunde (Tab Teams).',
                ],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
            ];
        }

        $teamPricesByTeamId = $this->teamPricesForMatchround($matchTeamIds, $matchroundId);

        $selectedStart = (string) Matchround::query()
            ->where('matchround_id', $matchroundId)
            ->value('matchround_startdate');

        $priorRounds = $this->resolveRecentPerformancePriorRounds(
            $leagueId,
            $selectedStart,
            $lookbackRounds,
            $includeExternalRounds,
            $matchTeamIds,
        );

        /** @var list<int> $priorRoundIds newest-first */
        $priorRoundIds = array_map(
            static fn (array $round): int => $round['matchround_id'],
            $priorRounds,
        );

        $weights = [];
        foreach ($priorRoundIds as $i => $_roundId) {
            $weights[] = $decayFactor ** $i;
        }
        $weightSum = array_sum($weights);

        /** @var list<array{matchround_id: int, matchround_title: string, weight: float, external: bool}> $priorMatchrounds */
        $priorMatchrounds = [];
        foreach ($priorRounds as $i => $round) {
            $priorMatchrounds[] = [
                'matchround_id' => $round['matchround_id'],
                'matchround_title' => $round['matchround_title'],
                'weight' => round($weights[$i], 3),
                'external' => ! empty($round['external']),
            ];
        }

        $squad = Playerteam::query()
            ->join('ffb_player', 'ffb_player.player_id', '=', 'ffb_playerteam.playerteam_player_id')
            ->join('ffb_team', 'ffb_team.team_id', '=', 'ffb_playerteam.playerteam_team_id')
            ->forLeague($leagueId)
            ->where('ffb_playerteam.playerteam_status', 1)
            ->whereIn('ffb_playerteam.playerteam_team_id', $matchTeamIds)
            ->orderBy('ffb_team.team_name')
            ->orderBy('ffb_playerteam.playerteam_player_position')
            ->orderBy('ffb_player.player_lname')
            ->orderBy('ffb_player.player_fname')
            ->get([
                'ffb_playerteam.playerteam_id',
                'ffb_playerteam.playerteam_team_id',
                'ffb_playerteam.playerteam_player_position',
                'ffb_player.player_id',
                'ffb_player.player_fname',
                'ffb_player.player_lname',
                'ffb_team.team_name',
            ]);

        $playerIds = $squad->pluck('player_id')->map(static fn ($id): int => (int) $id)->unique()->values()->all();

        /** @var array<int, array<int, float>> $playedPerformanceByPlayerRound player_id => [matchround_id => round_performance] */
        $playedPerformanceByPlayerRound = [];
        if ($playerIds !== [] && $priorRoundIds !== []) {
            $historyRows = Playerstats::query()
                ->join('ffb_playerteam', 'ffb_playerteam.playerteam_id', '=', 'ffb_playerstats.playerstats_playerteam_id')
                ->whereIn('ffb_playerstats.playerstats_matchround_id', $priorRoundIds)
                ->whereIn('ffb_playerteam.playerteam_player_id', $playerIds)
                ->where('ffb_playerstats.playerstats_minutes', '>', 0)
                ->whereNotNull('ffb_playerstats.playerstats_round_performance')
                ->get([
                    'ffb_playerteam.playerteam_player_id',
                    'ffb_playerstats.playerstats_matchround_id',
                    'ffb_playerstats.playerstats_round_performance',
                ]);
            foreach ($historyRows as $row) {
                $playerId = (int) $row->playerteam_player_id;
                $roundId = (int) $row->playerstats_matchround_id;
                $playedPerformanceByPlayerRound[$playerId][$roundId] = (float) $row->playerstats_round_performance;
            }
        }

        $players = [];
        foreach ($squad as $row) {
            $ptId = (int) $row->playerteam_id;
            $playerId = (int) $row->player_id;
            $teamId = (int) $row->playerteam_team_id;
            $numerator = 0.0;
            $roundsPlayed = 0;
            /** @var list<string> $roundPerformanceParts newest-first (all lookback slots) */
            $roundPerformanceParts = [];

            foreach ($priorRoundIds as $i => $priorRoundId) {
                if (! isset($playedPerformanceByPlayerRound[$playerId][$priorRoundId])) {
                    $roundPerformanceParts[] = '-';

                    continue;
                }
                $value = $playedPerformanceByPlayerRound[$playerId][$priorRoundId];
                $numerator += $value * $weights[$i];
                $rounded = round($value, 3);
                $roundPerformanceParts[] = rtrim(rtrim(number_format($rounded, 3, '.', ''), '0'), '.') ?: '0';
                $roundsPlayed++;
            }

            $recent = ($roundsPlayed === 0 || $weightSum == 0.0)
                ? 0.0
                : $numerator / $weightSum;
            $recentRounded = round($recent, 3);
            $adjustment = round($recentRounded * $maxPriceAdjustment, 3);
            $teamPrice = (float) ($teamPricesByTeamId[$teamId] ?? 0.0);
            $playerPrice = max(self::DEFAULT_MIN_PRICE, round($teamPrice + $adjustment, 1));

            $fname = trim((string) $row->player_fname);
            $lname = trim((string) $row->player_lname);

            $players[] = [
                'playerteam_id' => $ptId,
                'player_name' => trim($fname.' '.$lname),
                'team_name' => (string) $row->team_name,
                'position' => strtolower(trim((string) $row->playerteam_player_position)),
                'round_performance' => $roundPerformanceParts,
                'recent_performance' => $recentRounded,
                'rounds_played' => $roundsPlayed,
                'team_price' => round($teamPrice, 3),
                'price_adjustment' => $adjustment,
                'player_price' => $playerPrice,
            ];
        }

        return [
            'ok' => true,
            'message' => sprintf(
                'Recent-Performance & Spielerpreis berechnet (Vorschau, nicht gespeichert): %d Spieler, %d Lookback-Runden%s.',
                count($players),
                count($priorRoundIds),
                $includeExternalRounds ? ' (inkl. ligaübergreifend falls nötig)' : '',
            ),
            'price_league_id' => $leagueId,
            'matchround_id' => $matchroundId,
            'tab' => 'recent',
            'preview' => [
                'matchround_id' => $matchroundId,
                'lookback_rounds' => $lookbackRounds,
                'decay_factor' => $decayFactor,
                'max_price_adjustment' => $maxPriceAdjustment,
                'include_external_rounds' => $includeExternalRounds,
                'prior_matchround_ids' => $priorRoundIds,
                'prior_matchrounds' => $priorMatchrounds,
                'weights' => array_map(static fn (float $w): float => round($w, 3), $weights),
                'players' => $players,
            ],
        ];
    }

    /**
     * Recompute recent performance (same options as preview) and persist
     * ffb_playerprice.playerprice_recent_performance. Optionally also writes playerprice_price.
     *
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     price_league_id?: int,
     *     matchround_id?: int,
     *     tab?: string,
     *     preview?: array<string, mixed>
     * }
     */
    public function saveRecentPerformance(int $userId, array $input): array
    {
        $built = $this->previewRecentPerformance($userId, $input);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        $preview = $built['preview'] ?? [];
        /** @var list<array{playerteam_id: int, recent_performance: float, player_price?: float, player_name?: string}> $players */
        $players = is_array($preview['players'] ?? null) ? $preview['players'] : [];
        $matchroundId = (int) ($built['matchround_id'] ?? 0);
        $leagueId = (int) ($built['price_league_id'] ?? 0);
        $savePlayerPrices = $this->truthyInput($input['save_player_prices'] ?? null);

        if ($players === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spieler zum Speichern vorhanden.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
                'preview' => $preview,
            ];
        }

        $details = [];
        try {
            DB::transaction(function () use ($players, $matchroundId, $savePlayerPrices, &$details): void {
                foreach ($players as $row) {
                    $playerteamId = (int) ($row['playerteam_id'] ?? 0);
                    if ($playerteamId <= 0) {
                        continue;
                    }

                    $recent = round((float) ($row['recent_performance'] ?? 0), 3);
                    $playerprice = Playerprice::query()
                        ->where('playerprice_playerteam_id', $playerteamId)
                        ->where('playerprice_matchround_id', $matchroundId)
                        ->first();

                    if ($playerprice === null) {
                        $playerprice = new Playerprice;
                        $playerprice->playerprice_playerteam_id = $playerteamId;
                        $playerprice->playerprice_matchround_id = $matchroundId;
                        $playerprice->playerprice_av_power = 1;
                        $playerprice->playerprice_player_power = 1;
                        if (! $savePlayerPrices) {
                            $playerprice->playerprice_price = 0;
                        }
                    }

                    $playerprice->playerprice_recent_performance = $recent;
                    $line = ($row['player_name'] ?? ('#'.$playerteamId)).': recent='.$recent;

                    if ($savePlayerPrices) {
                        $price = round((float) ($row['player_price'] ?? self::DEFAULT_MIN_PRICE), 1);
                        $playerprice->playerprice_price = $price;
                        $line .= ', price='.$price;
                    }

                    $playerprice->save();
                    $details[] = $line;
                }
            });
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'errors' => [$e->getMessage()],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'recent',
                'preview' => $preview,
            ];
        }

        return [
            'ok' => true,
            'message' => sprintf(
                'Recent-Performance gespeichert: %d Spieler%s.',
                count($details),
                $savePlayerPrices ? ' (inkl. Spielerpreise)' : '',
            ),
            'details' => $details,
            'price_league_id' => $leagueId,
            'matchround_id' => $matchroundId,
            'tab' => 'recent',
            'preview' => $preview,
        ];
    }

    /**
     * Prior matchrounds newest-first for recent performance.
     * Optionally fills with other-league rounds of the same teams when the league alone
     * cannot satisfy LOOKBACK_ROUNDS. Always chronological by matchround_startdate.
     *
     * @param  list<int>  $teamIds
     * @return list<array{matchround_id: int, matchround_title: string, startdate: string, external: bool}>
     */
    private function resolveRecentPerformancePriorRounds(
        int $leagueId,
        string $selectedStart,
        int $lookbackRounds,
        bool $includeExternalRounds,
        array $teamIds,
    ): array {
        /** @var list<array{matchround_id: int, matchround_title: string, startdate: string, external: bool}> $leaguePriors */
        $leaguePriors = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_startdate', '<', $selectedStart)
            ->orderByDesc('matchround_startdate')
            ->get(['matchround_id', 'matchround_title', 'matchround_startdate'])
            ->map(static fn (Matchround $round): array => [
                'matchround_id' => (int) $round->matchround_id,
                'matchround_title' => (string) $round->matchround_title,
                'startdate' => (string) $round->matchround_startdate,
                'external' => false,
            ])
            ->values()
            ->all();

        if (! $includeExternalRounds || count($leaguePriors) >= $lookbackRounds) {
            return array_slice($leaguePriors, 0, $lookbackRounds);
        }

        $externalPriors = $this->externalPriorMatchroundsForTeams(
            $leagueId,
            $selectedStart,
            $teamIds,
        );

        $byId = [];
        foreach (array_merge($leaguePriors, $externalPriors) as $round) {
            $byId[$round['matchround_id']] = $round;
        }

        $merged = array_values($byId);
        usort(
            $merged,
            static function (array $a, array $b): int {
                $dateCmp = strcmp($b['startdate'], $a['startdate']);
                if ($dateCmp !== 0) {
                    return $dateCmp;
                }

                return $b['matchround_id'] <=> $a['matchround_id'];
            },
        );

        return array_slice($merged, 0, $lookbackRounds);
    }

    /**
     * Other-league matchrounds before the selected date where any of the teams played.
     * Only rounds where every minutes>0 playerstats row has round_performance set.
     *
     * @param  list<int>  $teamIds
     * @return list<array{matchround_id: int, matchround_title: string, startdate: string, external: bool}>
     */
    private function externalPriorMatchroundsForTeams(
        int $leagueId,
        string $selectedStart,
        array $teamIds,
    ): array {
        if ($teamIds === []) {
            return [];
        }

        $rows = MatchGame::query()
            ->join('ffb_matchround', 'ffb_matchround.matchround_id', '=', 'ffb_match.match_round')
            ->where('ffb_matchround.matchround_league_id', '!=', $leagueId)
            ->where('ffb_matchround.matchround_startdate', '<', $selectedStart)
            ->where(function ($query) use ($teamIds): void {
                $query->whereIn('ffb_match.match_hometeam_id', $teamIds)
                    ->orWhereIn('ffb_match.match_guestteam_id', $teamIds);
            })
            ->orderByDesc('ffb_matchround.matchround_startdate')
            ->orderByDesc('ffb_matchround.matchround_id')
            ->get([
                'ffb_matchround.matchround_id',
                'ffb_matchround.matchround_title',
                'ffb_matchround.matchround_startdate',
            ]);

        $seen = [];
        $candidates = [];
        foreach ($rows as $row) {
            $roundId = (int) $row->matchround_id;
            if (isset($seen[$roundId])) {
                continue;
            }
            $seen[$roundId] = true;
            $candidates[] = [
                'matchround_id' => $roundId,
                'matchround_title' => (string) $row->matchround_title,
                'startdate' => (string) $row->matchround_startdate,
                'external' => true,
            ];
        }

        return array_values(array_filter(
            $candidates,
            fn (array $round): bool => $this->matchroundHasCompleteRoundPerformance($round['matchround_id']),
        ));
    }

    private function matchroundHasCompleteRoundPerformance(int $matchroundId): bool
    {
        $hasPlayed = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->where('playerstats_minutes', '>', 0)
            ->exists();
        if (! $hasPlayed) {
            return false;
        }

        return ! Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->where('playerstats_minutes', '>', 0)
            ->whereNull('playerstats_round_performance')
            ->exists();
    }

    /**
     * @param  array<int, array{home: int, guest: int}>  $matchesById
     * @param  array<int, float>  $eloByTeamId
     */
    private function opponentFactorForPlayer(
        int $playerTeamId,
        int $matchId,
        array $matchesById,
        array $eloByTeamId,
        float $eloSpan,
    ): float {
        if ($matchId <= 0 || $eloSpan == 0.0 || ! isset($matchesById[$matchId])) {
            return 0.0;
        }

        $match = $matchesById[$matchId];
        if ($playerTeamId === $match['home']) {
            $opponentTeamId = $match['guest'];
        } elseif ($playerTeamId === $match['guest']) {
            $opponentTeamId = $match['home'];
        } else {
            return 0.0;
        }

        if (! isset($eloByTeamId[$playerTeamId], $eloByTeamId[$opponentTeamId])) {
            return 0.0;
        }

        return ($eloByTeamId[$opponentTeamId] - $eloByTeamId[$playerTeamId]) / $eloSpan;
    }

    /**
     * Matchround IDs in the league up to and including the selected round
     * (same or earlier matchround_startdate). Used as the ranking peer window.
     *
     * @return list<int>
     */
    private function matchroundIdsThrough(int $leagueId, int $matchroundId): array
    {
        $selectedStart = Matchround::query()
            ->where('matchround_id', $matchroundId)
            ->value('matchround_startdate');

        if ($selectedStart === null || $selectedStart === '') {
            return [$matchroundId];
        }

        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_startdate', '<=', $selectedStart)
            ->orderBy('matchround_startdate')
            ->pluck('matchround_id')
            ->map(static fn (mixed $id): int => (int) $id)
            ->all();
    }

    private function leagueHasCompleteTeamelos(int $leagueId): bool
    {
        $teamIds = $this->teamIdsForGame($leagueId);
        if ($teamIds === []) {
            return false;
        }

        $elos = $this->teamElosForLeague($teamIds, $leagueId);

        return count($elos) === count($teamIds);
    }

    private function matchroundHasCompleteTeamprices(int $matchroundId): bool
    {
        $teamIds = $this->teamIdsForMatchround($matchroundId);
        if ($teamIds === []) {
            return false;
        }

        $prices = $this->teamPricesForMatchround($teamIds, $matchroundId);

        return count($prices) === count($teamIds);
    }

    /**
     * @param  list<int>  $teamIds
     * @return array<int, float> team_id => teamelo_elo
     */
    private function teamElosForLeague(array $teamIds, int $leagueId): array
    {
        if ($teamIds === [] || $leagueId <= 0) {
            return [];
        }

        return Teamelo::query()
            ->where('teamelo_league_id', $leagueId)
            ->whereIn('teamelo_team_id', $teamIds)
            ->get(['teamelo_team_id', 'teamelo_elo'])
            ->mapWithKeys(static fn (Teamelo $row): array => [
                (int) $row->teamelo_team_id => (float) $row->teamelo_elo,
            ])
            ->all();
    }

    private function truthyInput(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'on', 'yes'], true);
    }

    public function previewEloTeamPrices(int $userId, array $input): array
    {
        $built = $this->buildEloTeamPricePreview($userId, $input);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        return [
            'ok' => true,
            'message' => 'ELO Team-Preise berechnet (Vorschau, nicht gespeichert).',
            'price_league_id' => $built['price_league_id'],
            'matchround_id' => $built['matchround_id'],
            'tab' => 'teams',
            'preview' => $built['preview'],
        ];
    }

    /**
     * Recompute Elo team prices and persist into ffb_teamprice for matchround(s)
     * that do not yet have user lineups.
     *
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     price_league_id?: int,
     *     matchround_id?: int,
     *     tab?: string,
     *     preview?: array<string, mixed>
     * }
     */
    public function saveEloTeamPrices(int $userId, array $input): array
    {
        $built = $this->buildEloTeamPricePreview($userId, $input);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        $leagueId = (int) $built['price_league_id'];
        $matchroundId = (int) $built['matchround_id'];
        $preview = $built['preview'];

        $targetMatchroundIds = $this->writableMatchroundIdsForSave($leagueId, $matchroundId);
        if ($targetMatchroundIds === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spielrunde ohne Aufstellungen zum Speichern gefunden.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        }

        /** @var array<int, float> $pricesByTeamId */
        $pricesByTeamId = [];
        /** @var array<int, float> $eloByTeamId */
        $eloByTeamId = [];
        foreach ($preview['teams'] ?? [] as $row) {
            $teamId = (int) ($row['team_id'] ?? 0);
            if ($teamId <= 0) {
                continue;
            }
            $pricesByTeamId[$teamId] = (float) ($row['price'] ?? 0);
            if (array_key_exists('elo_rating', $row)) {
                $eloByTeamId[$teamId] = (float) $row['elo_rating'];
            }
        }

        if ($pricesByTeamId === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Team-Preise zum Speichern vorhanden.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        }

        $eloYear = (int) ($preview['form']['elo_year'] ?? 0);
        if ($eloYear < self::MIN_ELO_YEAR) {
            return [
                'ok' => false,
                'errors' => ['ELO-Jahr fehlt in der Preisberechnung.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        }

        try {
            $details = DB::transaction(function () use ($pricesByTeamId, $targetMatchroundIds, $leagueId, $eloByTeamId, $eloYear): array {
                $details = $this->persistTeamPrices($pricesByTeamId, $targetMatchroundIds);
                $this->persistTeamElos($leagueId, $eloByTeamId, $eloYear);

                return $details;
            });
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'errors' => [$e->getMessage()],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        }

        return [
            'ok' => true,
            'message' => sprintf(
                'ELO Team-Preise gespeichert: %d Team(s) × %d Spielrunde(n); Team-Elo (%d) aktualisiert.',
                count($pricesByTeamId),
                count($targetMatchroundIds),
                $eloYear,
            ),
            'details' => $details,
            'price_league_id' => $leagueId,
            'matchround_id' => $matchroundId,
            'tab' => 'teams',
            'preview' => $preview,
        ];
    }

    /**
     * One-time / historical backfill: compute Elo team prices for a league (same formula as
     * Team-Preis) and write ffb_teamprice for every matchround — including past rounds.
     *
     * @param  array{
     *     max_credits?: float|int|string|null,
     *     max_players_team?: float|int|string|null,
     *     exponent?: float|int|string|null,
     *     dream_team_ratio?: float|int|string|null,
     *     min_price?: float|int|string|null
     * }  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     league_id?: int,
     *     matchround_ids?: list<int>,
     *     team_count?: int,
     *     skipped_teams?: list<array{team_id: int, team_name: string}>,
     *     preview?: array<string, mixed>,
     *     dry_run?: bool
     * }
     */
    public function backfillHistoricalLeagueTeamPrices(
        int $leagueId,
        EloRatingClient $eloRating,
        array $input = [],
        bool $execute = false,
    ): array {
        if ($leagueId <= 0 || ! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.']];
        }

        $matchroundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->pluck('matchround_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        if ($matchroundIds === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spielrunden in dieser Liga.'],
                'league_id' => $leagueId,
            ];
        }

        $teamIds = $this->teamIdsForGame($leagueId);
        if ($teamIds === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Teams für die Liga gefunden.'],
                'league_id' => $leagueId,
            ];
        }

        $limits = $this->lineupOptions->forLeague($leagueId);
        $defaultBudget = (float) $limits['lineup_max_credits'];
        $defaultMaxPerTeam = max(1, (int) $limits['lineup_max_players_team']);
        $squadSize = max(1, (int) ($limits['lineup_max_players'] ?? self::SQUAD_SIZE));

        $budget = $this->resolveFloatInput($input['max_credits'] ?? null, $defaultBudget);
        $maxPerTeam = max(1, $this->resolveIntInput($input['max_players_team'] ?? null, $defaultMaxPerTeam));
        $exponent = $this->resolveFloatInput($input['exponent'] ?? null, self::DEFAULT_EXPONENT);
        $dreamTeamRatio = $this->resolveFloatInput($input['dream_team_ratio'] ?? null, self::DEFAULT_DREAM_TEAM_RATIO);
        $minPrice = $this->resolveFloatInput($input['min_price'] ?? null, self::DEFAULT_MIN_PRICE);

        try {
            $eloRows = $eloRating->ratingsForTeamList($teamIds);
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()], 'league_id' => $leagueId];
        }

        if ($eloRows === []) {
            return [
                'ok' => false,
                'errors' => ['Keine ELO-Ratings für die Teams gefunden.'],
                'league_id' => $leagueId,
            ];
        }

        $mappedIds = array_map('intval', array_column($eloRows, 'team_id'));
        $missingIds = array_values(array_diff(array_map('intval', $teamIds), $mappedIds));
        $names = Team::query()
            ->whereIn('team_id', array_values(array_unique([...$mappedIds, ...$missingIds])))
            ->pluck('team_name', 'team_id')
            ->all();

        $teamsWithElo = [];
        foreach ($eloRows as $row) {
            $teamId = (int) $row['team_id'];
            $teamsWithElo[] = [
                'team_id' => $teamId,
                'team_name' => (string) ($names[$teamId] ?? ('Team #'.$teamId)),
                'elo_rating' => (float) $row['elo_rating'],
            ];
        }

        $skippedTeams = [];
        foreach ($missingIds as $missingId) {
            $skippedTeams[] = [
                'team_id' => $missingId,
                'team_name' => (string) ($names[$missingId] ?? ('Team #'.$missingId)),
            ];
        }
        usort($skippedTeams, static fn (array $a, array $b): int => strcasecmp($a['team_name'], $b['team_name']));

        $preview = $this->computeEloTeamPricePreview(
            $teamsWithElo,
            $budget,
            $maxPerTeam,
            $squadSize,
            $exponent,
            $dreamTeamRatio,
            $minPrice,
        );
        $preview['teams_skipped'] = $skippedTeams;
        $preview['form'] = [
            'max_credits' => $budget,
            'max_players_team' => $maxPerTeam,
            'exponent' => $exponent,
            'dream_team_ratio' => $dreamTeamRatio,
            'min_price' => $minPrice,
        ];

        /** @var array<int, float> $pricesByTeamId */
        $pricesByTeamId = [];
        foreach ($preview['teams'] ?? [] as $row) {
            $teamId = (int) ($row['team_id'] ?? 0);
            if ($teamId <= 0) {
                continue;
            }
            $pricesByTeamId[$teamId] = (float) ($row['price'] ?? 0);
        }

        if ($pricesByTeamId === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Team-Preise berechnet.'],
                'league_id' => $leagueId,
                'preview' => $preview,
            ];
        }

        $details = [];
        if ($execute) {
            try {
                $details = DB::transaction(
                    fn (): array => $this->persistTeamPrices($pricesByTeamId, $matchroundIds),
                );
            } catch (Throwable $e) {
                return [
                    'ok' => false,
                    'errors' => [$e->getMessage()],
                    'league_id' => $leagueId,
                    'preview' => $preview,
                ];
            }
        } else {
            foreach ($matchroundIds as $matchroundId) {
                foreach ($pricesByTeamId as $teamId => $price) {
                    $details[] = 'Team '.$teamId.' / Runde '.$matchroundId.': '.$price;
                }
            }
        }

        return [
            'ok' => true,
            'dry_run' => ! $execute,
            'message' => sprintf(
                '%s: %d Team(s) × %d Spielrunde(n)%s.',
                $execute ? 'ELO Team-Preise gespeichert' : 'Dry-run ELO Team-Preise',
                count($pricesByTeamId),
                count($matchroundIds),
                $skippedTeams === [] ? '' : (', '.count($skippedTeams).' ohne ELO übersprungen'),
            ),
            'details' => $details,
            'league_id' => $leagueId,
            'matchround_ids' => $matchroundIds,
            'team_count' => count($pricesByTeamId),
            'skipped_teams' => $skippedTeams,
            'preview' => $preview,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     errors?: list<string>,
     *     price_league_id?: int,
     *     matchround_id?: int,
     *     tab?: string,
     *     preview?: array<string, mixed>
     * }
     */
    private function buildEloTeamPricePreview(int $userId, array $input): array
    {
        $leagueId = $this->resolveLeagueIdFromInput($userId, $input);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.'], 'tab' => 'teams'];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        if ($matchroundId > 0) {
            if (! $this->matchroundBelongsToLeague($matchroundId, $leagueId)) {
                return [
                    'ok' => false,
                    'errors' => ['Ungültige Spielrunde für die aktive Liga.'],
                    'price_league_id' => $leagueId,
                    'tab' => 'teams',
                ];
            }
            if ($this->matchroundHasUserLineups($matchroundId)) {
                return [
                    'ok' => false,
                    'errors' => ['Spielrunden mit bestehenden Aufstellungen können nicht aktualisiert werden.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }
        }

        $eloYear = $this->resolveEloYearInput($input);
        if ($eloYear === null) {
            return [
                'ok' => false,
                'errors' => [sprintf(
                    'ELO-Jahr muss zwischen %d und %d liegen.',
                    self::MIN_ELO_YEAR,
                    (int) now()->year,
                )],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
            ];
        }

        try {
            $teamIds = $matchroundId > 0
                ? $this->teamIdsForMatchround($matchroundId)
                : $this->teamIdsForGame($leagueId);

            if ($teamIds === []) {
                return [
                    'ok' => false,
                    'errors' => ['Keine Teams für die Berechnung gefunden.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }

            $limits = $matchroundId > 0
                ? $this->lineupOptions->forMatchround($matchroundId)
                : $this->lineupOptions->forLeague($leagueId);

            $defaultBudget = (float) $limits['lineup_max_credits'];
            $defaultMaxPerTeam = max(1, (int) $limits['lineup_max_players_team']);
            $squadSize = max(1, (int) ($limits['lineup_max_players'] ?? self::SQUAD_SIZE));

            $budget = $this->resolveFloatInput($input['max_credits'] ?? null, $defaultBudget);
            $maxPerTeam = $this->resolveIntInput($input['max_players_team'] ?? null, $defaultMaxPerTeam);
            $maxPerTeam = max(1, $maxPerTeam);

            $eloRows = $this->eloRating->forYear($eloYear)->ratingsForTeamList($teamIds);
            if ($eloRows === []) {
                return [
                    'ok' => false,
                    'errors' => ['Keine ELO-Ratings für die Teams gefunden.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }

            $mappedIds = array_map('intval', array_column($eloRows, 'team_id'));
            $missingIds = array_values(array_diff(array_map('intval', $teamIds), $mappedIds));

            $names = Team::query()
                ->whereIn('team_id', array_values(array_unique([...$mappedIds, ...$missingIds])))
                ->pluck('team_name', 'team_id')
                ->all();

            $teamsWithElo = [];
            foreach ($eloRows as $row) {
                $teamId = (int) $row['team_id'];
                $teamsWithElo[] = [
                    'team_id' => $teamId,
                    'team_name' => (string) ($names[$teamId] ?? ('Team #'.$teamId)),
                    'elo_rating' => (float) $row['elo_rating'],
                ];
            }

            $skippedTeams = [];
            foreach ($missingIds as $missingId) {
                $skippedTeams[] = [
                    'team_id' => $missingId,
                    'team_name' => (string) ($names[$missingId] ?? ('Team #'.$missingId)),
                ];
            }
            usort($skippedTeams, static fn (array $a, array $b): int => strcasecmp($a['team_name'], $b['team_name']));

            $exponent = $this->resolveFloatInput($input['exponent'] ?? null, self::DEFAULT_EXPONENT);
            $dreamTeamRatio = $this->resolveFloatInput($input['dream_team_ratio'] ?? null, self::DEFAULT_DREAM_TEAM_RATIO);
            $minPrice = $this->resolveFloatInput($input['min_price'] ?? null, self::DEFAULT_MIN_PRICE);
            if ($budget <= 0) {
                return [
                    'ok' => false,
                    'errors' => ['Max. Credits müssen größer als 0 sein.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }
            if ($exponent <= 0) {
                return [
                    'ok' => false,
                    'errors' => ['Exponent muss größer als 0 sein.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }
            if ($dreamTeamRatio <= 0) {
                return [
                    'ok' => false,
                    'errors' => ['Dream-Team-Ratio muss größer als 0 sein.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }
            if ($minPrice < 0) {
                return [
                    'ok' => false,
                    'errors' => ['Mindestpreis darf nicht negativ sein.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }

            $preview = $this->computeEloTeamPricePreview(
                $teamsWithElo,
                $budget,
                $maxPerTeam,
                $squadSize,
                $exponent,
                $dreamTeamRatio,
                $minPrice,
            );
            $preview['lineup_max_credits'] = $budget;
            $preview['lineup_max_players_team'] = $maxPerTeam;
            $preview['lineup_limits_source'] = (string) ($limits['source'] ?? 'fallback');
            $preview['matchround_id'] = $matchroundId;
            $preview['teams_without_elo'] = count($skippedTeams);
            $preview['teams_skipped'] = $skippedTeams;
            $preview['form'] = [
                'max_credits' => $budget,
                'max_players_team' => $maxPerTeam,
                'exponent' => $exponent,
                'dream_team_ratio' => $dreamTeamRatio,
                'min_price' => $minPrice,
                'elo_year' => $eloYear,
            ];

            return [
                'ok' => true,
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'errors' => [$e->getMessage()],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
            ];
        }
    }

    public function computeEloTeamPricePreview(
        array $teams,
        float $budget,
        int $maxPerTeam,
        int $squadSize = self::SQUAD_SIZE,
        float $exponent = self::DEFAULT_EXPONENT,
        float $dreamTeamRatio = self::DEFAULT_DREAM_TEAM_RATIO,
        float $minPrice = self::DEFAULT_MIN_PRICE,
    ): array {
        if ($teams === []) {
            return [
                'teams' => [],
                'checks' => [],
                'params' => [],
            ];
        }

        $maxPerTeam = max(1, $maxPerTeam);
        $squadSize = max(1, $squadSize);
        $budget = max(1.0, $budget);
        $exponent = max(0.01, $exponent);
        $dreamTeamRatio = max(0.01, $dreamTeamRatio);
        $minPrice = max(0.0, round($minPrice, 1));

        $minElo = min(array_column($teams, 'elo_rating'));
        $maxElo = max(array_column($teams, 'elo_rating'));
        $eloSpan = $maxElo - $minElo;

        $weighted = [];
        foreach ($teams as $team) {
            $normalized = $eloSpan > 0.0
                ? (((float) $team['elo_rating']) - $minElo) / $eloSpan
                : 0.5;
            $rawWeight = $normalized ** $exponent;
            $weighted[] = [
                'team_id' => (int) $team['team_id'],
                'team_name' => (string) $team['team_name'],
                'elo_rating' => (float) $team['elo_rating'],
                'normalized' => round($normalized, 4),
                'raw_weight' => $rawWeight,
            ];
        }

        usort($weighted, static fn (array $a, array $b): int => $b['raw_weight'] <=> $a['raw_weight']);

        $dreamPicks = $this->buildGreedyLineup($weighted, $maxPerTeam, $squadSize);
        $dreamRawCost = array_sum(array_column($dreamPicks, 'raw_weight'));
        if ($dreamRawCost <= 0.0) {
            return [
                'teams' => [],
                'checks' => [['id' => 'error', 'ok' => false, 'message' => 'Dream-Team-Rohkosten sind 0.']],
                'params' => [
                    'exponent' => $exponent,
                    'dream_team_ratio' => $dreamTeamRatio,
                    'min_price' => $minPrice,
                    'budget' => $budget,
                    'squad_size' => $squadSize,
                    'max_per_team' => $maxPerTeam,
                ],
            ];
        }

        // Mindestpreis is a base offset (weakest team = min), not a post-scale clamp.
        // Otherwise high mins flatten many weak teams onto the same price.
        $dreamBaseCost = $squadSize * $minPrice;
        $dreamTargetCost = $budget * $dreamTeamRatio;
        $variableBudget = $dreamTargetCost - $dreamBaseCost;
        if ($variableBudget <= 0.0) {
            return [
                'teams' => [],
                'checks' => [[
                    'id' => 'error',
                    'ok' => false,
                    'message' => 'Mindestpreis ist zu hoch für Budget × Dream-Team-Ratio (Dream-Team-Basis ≥ Zielkosten).',
                ]],
                'params' => [
                    'exponent' => $exponent,
                    'dream_team_ratio' => $dreamTeamRatio,
                    'min_price' => $minPrice,
                    'budget' => $budget,
                    'squad_size' => $squadSize,
                    'max_per_team' => $maxPerTeam,
                ],
            ];
        }

        $scale = $variableBudget / $dreamRawCost;
        $priced = [];
        foreach ($weighted as $row) {
            $price = round($minPrice + ($row['raw_weight'] * $scale), 1);
            $priced[] = [
                'team_id' => $row['team_id'],
                'team_name' => $row['team_name'],
                'elo_rating' => $row['elo_rating'],
                'normalized' => $row['normalized'],
                'raw_weight' => round($row['raw_weight'], 4),
                'price' => $price,
            ];
        }

        usort($priced, static function (array $a, array $b): int {
            $byPrice = $b['price'] <=> $a['price'];
            if ($byPrice !== 0) {
                return $byPrice;
            }

            return $b['elo_rating'] <=> $a['elo_rating'];
        });

        $byId = [];
        foreach ($priced as $row) {
            $byId[$row['team_id']] = $row;
        }

        $dreamCost = 0.0;
        foreach ($dreamPicks as $pick) {
            $dreamCost += (float) ($byId[$pick['team_id']]['price'] ?? 0);
        }

        $medianCost = $this->lineupCost(
            $this->buildMedianLineup($priced, $maxPerTeam, $squadSize),
            $byId,
        );
        $goodCost = $this->lineupCost(
            $this->buildGoodLineup($priced, $maxPerTeam, $squadSize),
            $byId,
        );

        $checkAOk = $dreamCost > $budget;
        $checkBOk = $medianCost >= (0.6 * $budget) && $medianCost <= (0.8 * $budget);
        $checkBTooExpensive = $medianCost > (0.8 * $budget);
        $checkCOk = $goodCost >= (0.9 * $budget) && $goodCost <= (1.0 * $budget);

        $checks = [
            [
                'id' => 'dream_team',
                'ok' => $checkAOk,
                'cost' => round($dreamCost, 1),
                'target' => '> '.$budget,
                'message' => $checkAOk
                    ? 'Dream-Team ist nicht leistbar.'
                    : 'Dream-Team ist noch leistbar — Ratio erhöhen.',
            ],
            [
                'id' => 'median_lineup',
                'ok' => $checkBOk,
                'cost' => round($medianCost, 1),
                'target' => '60–80% von '.$budget,
                'message' => $checkBOk
                    ? 'Median-Lineup im Zielkorridor.'
                    : ($checkBTooExpensive
                        ? 'Median-Lineup zu teuer — Ratio/Exponent senken.'
                        : 'Median-Lineup zu günstig.'),
            ],
            [
                'id' => 'good_lineup',
                'ok' => $checkCOk,
                'cost' => round($goodCost, 1),
                'target' => '90–100% von '.$budget,
                'message' => $checkCOk
                    ? 'Gute Lineup im Zielkorridor.'
                    : 'Gute Lineup außerhalb 90–100% Budget.',
            ],
        ];

        return [
            'teams' => $priced,
            'checks' => $checks,
            'params' => [
                'exponent' => round($exponent, 2),
                'dream_team_ratio' => round($dreamTeamRatio, 2),
                'min_price' => $minPrice,
                'budget' => $budget,
                'squad_size' => $squadSize,
                'max_per_team' => $maxPerTeam,
                'min_elo' => $minElo,
                'max_elo' => $maxElo,
            ],
        ];
    }

    private function resolveFloatInput(mixed $value, float $default): float
    {
        if ($value === null || $value === '') {
            return $default;
        }

        if (is_string($value)) {
            $value = str_replace(',', '.', trim($value));
        }

        return is_numeric($value) ? (float) $value : $default;
    }

    private function resolveIntInput(mixed $value, int $default): int
    {
        if ($value === null || $value === '') {
            return $default;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @param  list<array{team_id: int, raw_weight: float}>  $teamsDesc
     * @return list<array{team_id: int, raw_weight: float}>
     */
    private function buildGreedyLineup(array $teamsDesc, int $maxPerTeam, int $squadSize): array
    {
        $picks = [];
        foreach ($teamsDesc as $team) {
            for ($i = 0; $i < $maxPerTeam && count($picks) < $squadSize; $i++) {
                $picks[] = [
                    'team_id' => (int) $team['team_id'],
                    'raw_weight' => (float) $team['raw_weight'],
                ];
            }
            if (count($picks) >= $squadSize) {
                break;
            }
        }

        return $picks;
    }

    /**
     * @param  list<array{team_id: int, price: float}>  $teamsByPriceDesc
     * @return list<array{team_id: int}>
     */
    private function buildMedianLineup(array $teamsByPriceDesc, int $maxPerTeam, int $squadSize): array
    {
        if ($teamsByPriceDesc === []) {
            return [];
        }

        $ascending = array_reverse($teamsByPriceDesc);
        $start = (int) floor((count($ascending) - 1) / 2);
        $ordered = array_merge(
            array_slice($ascending, $start),
            array_reverse(array_slice($ascending, 0, $start)),
        );

        $picks = [];
        foreach ($ordered as $team) {
            for ($i = 0; $i < $maxPerTeam && count($picks) < $squadSize; $i++) {
                $picks[] = ['team_id' => (int) $team['team_id']];
            }
            if (count($picks) >= $squadSize) {
                break;
            }
        }

        return $picks;
    }

    /**
     * @param  list<array{team_id: int, price: float}>  $teamsByPriceDesc
     * @return list<array{team_id: int}>
     */
    private function buildGoodLineup(array $teamsByPriceDesc, int $maxPerTeam, int $squadSize): array
    {
        if ($teamsByPriceDesc === []) {
            return [];
        }

        $picks = [];
        $top = array_slice($teamsByPriceDesc, 0, 2);
        foreach ($top as $team) {
            $take = min(2, $maxPerTeam, $squadSize - count($picks));
            for ($i = 0; $i < $take; $i++) {
                $picks[] = ['team_id' => (int) $team['team_id']];
            }
        }

        $ascending = array_reverse($teamsByPriceDesc);
        foreach ($ascending as $team) {
            $already = 0;
            foreach ($picks as $pick) {
                if ($pick['team_id'] === (int) $team['team_id']) {
                    $already++;
                }
            }
            $room = max(0, $maxPerTeam - $already);
            for ($i = 0; $i < $room && count($picks) < $squadSize; $i++) {
                $picks[] = ['team_id' => (int) $team['team_id']];
            }
            if (count($picks) >= $squadSize) {
                break;
            }
        }

        return $picks;
    }

    /**
     * @param  list<array{team_id: int}>  $picks
     * @param  array<int, array{price: float}>  $byId
     */
    private function lineupCost(array $picks, array $byId): float
    {
        $cost = 0.0;
        foreach ($picks as $pick) {
            $cost += (float) ($byId[$pick['team_id']]['price'] ?? 0);
        }

        return $cost;
    }

    /**
     * @param  array<string, mixed>  $shell
     */
    private function resolvePriceLeagueId(?int $priceLeagueId, array $shell): int
    {
        $userId = (int) ($shell['user']['user_id'] ?? 0);
        if ($userId > 0) {
            $fromAdmin = $this->adminCenter->selectedLeagueId($userId);
            if ($fromAdmin > 0) {
                return $fromAdmin;
            }
        }

        return (int) ($shell['selected_league']['league_id'] ?? 0);
    }

    /**
     * @return list<array{league_id: int, league_title: string}>
     */
    private function leagueOptions(): array
    {
        return League::query()
            ->orderByDesc('league_id')
            ->get(['league_id', 'league_title'])
            ->map(static fn (League $league): array => [
                'league_id' => (int) $league->league_id,
                'league_title' => (string) $league->league_title,
            ])
            ->all();
    }

    /**
     * @return array{league_id: int, league_title: string}|null
     */
    private function leagueOption(int $leagueId): ?array
    {
        $league = League::query()->find($leagueId);
        if (! $league) {
            return null;
        }

        return [
            'league_id' => (int) $league->league_id,
            'league_title' => (string) $league->league_title,
        ];
    }

    /**
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    private function matchrounds(int $leagueId): array
    {
        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->get(['matchround_id', 'matchround_title'])
            ->map(static fn (Matchround $r): array => [
                'matchround_id' => (int) $r->matchround_id,
                'matchround_title' => (string) $r->matchround_title,
            ])
            ->all();
    }

    /**
     * All league matchrounds for the Team-Preis dropdown
     * (rounds with user lineups marked non-selectable).
     *
     * @return list<array{matchround_id: int, matchround_title: string, can_update: bool}>
     */
    private function matchroundsForTeamPrice(int $leagueId): array
    {
        $rounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->get(['matchround_id', 'matchround_title']);

        if ($rounds->isEmpty()) {
            return [];
        }

        $roundIds = $rounds->map(static fn (Matchround $r): int => (int) $r->matchround_id)->all();
        $lockedIds = $this->matchroundIdsWithUserLineups($roundIds);

        return $rounds
            ->map(static function (Matchround $r) use ($lockedIds): array {
                $id = (int) $r->matchround_id;

                return [
                    'matchround_id' => $id,
                    'matchround_title' => (string) $r->matchround_title,
                    'can_update' => ! isset($lockedIds[$id]),
                ];
            })
            ->all();
    }

    /**
     * @return list<int>
     */
    private function writableMatchroundIdsForSave(int $leagueId, int $selectedMatchroundId): array
    {
        if ($selectedMatchroundId > 0) {
            if (
                $this->matchroundBelongsToLeague($selectedMatchroundId, $leagueId)
                && ! $this->matchroundHasUserLineups($selectedMatchroundId)
            ) {
                return [$selectedMatchroundId];
            }

            return [];
        }

        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->pluck('matchround_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        if ($roundIds === []) {
            return [];
        }

        $lockedIds = $this->matchroundIdsWithUserLineups($roundIds);

        return array_values(array_filter(
            $roundIds,
            static fn (int $id): bool => ! isset($lockedIds[$id]),
        ));
    }

    private function matchroundHasUserLineups(int $matchroundId): bool
    {
        if ($matchroundId <= 0) {
            return false;
        }

        return Userteam::query()
            ->where('userteam_matchround_id', $matchroundId)
            ->exists();
    }

    /**
     * @param  list<int>  $matchroundIds
     * @return array<int, true> matchround_id => true
     */
    private function matchroundIdsWithUserLineups(array $matchroundIds): array
    {
        if ($matchroundIds === []) {
            return [];
        }

        $ids = Userteam::query()
            ->whereIn('userteam_matchround_id', $matchroundIds)
            ->distinct()
            ->pluck('userteam_matchround_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        return array_fill_keys($ids, true);
    }

    /**
     * @param  array<int, float>  $pricesByTeamId
     * @param  list<int>  $matchroundIds
     * @return list<string>
     */
    private function persistTeamPrices(array $pricesByTeamId, array $matchroundIds): array
    {
        $details = [];

        foreach ($matchroundIds as $matchroundId) {
            foreach ($pricesByTeamId as $teamId => $price) {
                $row = Teamprice::query()
                    ->where('teamprice_team_id', $teamId)
                    ->where('teamprice_matchround_id', $matchroundId)
                    ->first();

                if (! $row) {
                    $row = new Teamprice;
                    $row->teamprice_team_id = $teamId;
                    $row->teamprice_matchround_id = $matchroundId;
                }

                $row->teamprice_price = $price;
                $row->save();
                $details[] = 'Team '.$teamId.' / Runde '.$matchroundId.': '.$price;
            }
        }

        return $details;
    }

    /**
     * Upsert ffb_teamelo for the teams whose Elo was used to compute team prices.
     *
     * @param  array<int, float>  $eloByTeamId
     */
    private function persistTeamElos(int $leagueId, array $eloByTeamId, int $eloYear): void
    {
        foreach ($eloByTeamId as $teamId => $elo) {
            $row = Teamelo::query()
                ->where('teamelo_team_id', $teamId)
                ->where('teamelo_league_id', $leagueId)
                ->first();

            if ($row === null) {
                $row = new Teamelo;
                $row->teamelo_team_id = $teamId;
                $row->teamelo_league_id = $leagueId;
            }

            $row->teamelo_elo = $elo;
            $row->teamelo_elo_year = $eloYear;
            $row->save();
        }
    }

    /**
     * Year for admin Team-Preis Elo fetch / ffb_teamelo write.
     * Empty input defaults to the current calendar year.
     */
    private function resolveEloYearInput(array $input): ?int
    {
        $current = (int) now()->year;
        $raw = $input['elo_year'] ?? null;
        if ($raw === null || $raw === '') {
            return $current;
        }

        if (! is_numeric($raw)) {
            return null;
        }

        $year = (int) $raw;
        if ($year < self::MIN_ELO_YEAR || $year > $current) {
            return null;
        }

        return $year;
    }

    /**
     * @return list<int>
     */
    private function eloYearOptions(): array
    {
        $current = (int) now()->year;

        return range($current, self::MIN_ELO_YEAR);
    }

    private function matchroundBelongsToLeague(int $matchroundId, int $leagueId): bool
    {
        return Matchround::query()
            ->where('matchround_id', $matchroundId)
            ->where('matchround_league_id', $leagueId)
            ->exists();
    }

    /**
     * @return list<int>
     */
    private function teamIdsForMatchround(int $matchroundId): array
    {
        $matches = MatchGame::query()
            ->where('match_round', $matchroundId)
            ->get(['match_hometeam_id', 'match_guestteam_id']);

        $ids = [];
        foreach ($matches as $match) {
            $ids[] = (int) $match->match_hometeam_id;
            $ids[] = (int) $match->match_guestteam_id;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * @return list<int>
     */
    private function teamIdsForGame(int $leagueId): array
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

    /**
     * @param  list<int>  $teamIds
     * @return array<int, float> team_id => teamprice_price
     */
    private function teamPricesForMatchround(array $teamIds, int $matchroundId): array
    {
        if ($teamIds === []) {
            return [];
        }

        return Teamprice::query()
            ->where('teamprice_matchround_id', $matchroundId)
            ->whereIn('teamprice_team_id', $teamIds)
            ->get(['teamprice_team_id', 'teamprice_price'])
            ->mapWithKeys(static fn (Teamprice $row): array => [
                (int) $row->teamprice_team_id => (float) $row->teamprice_price,
            ])
            ->all();
    }
}
