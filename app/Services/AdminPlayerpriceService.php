<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Port of legacy modules/administration/playerprice2014.php.
 */
class AdminPlayerpriceService
{
    private const HISTORY_LENGTH = 10;

    private const SQUAD_SIZE = 11;

    private const DEFAULT_EXPONENT = 2.0;

    private const DEFAULT_DREAM_TEAM_RATIO = 1.5;

    private const DEFAULT_MIN_PRICE = 1.0;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly EloRatingClient $eloRating,
        private readonly LineupOptionsResolver $lineupOptions,
    ) {}

    /**
     * @param  array<string, mixed>|null  $teamPricePreview
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        ?int $priceLeagueId = null,
        string $tab = 'teams',
        ?int $matchroundId = null,
        ?array $teamPricePreview = null,
    ): array {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagueId = $this->resolvePriceLeagueId($priceLeagueId, $shell);
        $resolvedTab = $tab === 'players' ? 'players' : 'teams';
        $selectedLeague = $leagueId > 0
            ? $this->leagueOption($leagueId)
            : null;

        $resolvedMatchroundId = 0;
        if ($leagueId > 0 && $matchroundId !== null && $matchroundId > 0) {
            $resolvedMatchroundId = $this->matchroundBelongsToLeague($matchroundId, $leagueId)
                ? $matchroundId
                : 0;
            if ($resolvedTab === 'teams' && $resolvedMatchroundId > 0 && ! $this->matchroundIsFuture($resolvedMatchroundId)) {
                $resolvedMatchroundId = 0;
            }
        }

        $lineupLimits = $leagueId > 0
            ? ($resolvedMatchroundId > 0
                ? $this->lineupOptions->forMatchround($resolvedMatchroundId)
                : $this->lineupOptions->forLeague($leagueId))
            : $this->lineupOptions->forLeague(0);

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
            'price_margins' => $this->priceMargins(),
            'team_price_preview' => $teamPricePreview,
        ];
    }

    /**
     * Resolve league for price actions: explicit form/query id, else admin-center selection.
     *
     * @param  array<string, mixed>  $input
     */
    public function resolveLeagueIdFromInput(int $userId, array $input): int
    {
        $fromInput = (int) ($input['price_league_id'] ?? 0);
        if ($fromInput > 0 && League::query()->whereKey($fromInput)->exists()) {
            return $fromInput;
        }

        return $this->adminCenter->selectedLeagueId($userId);
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function calculatePlayerPricesForMatchround(int $userId, array $input): array
    {
        $leagueId = $this->resolveLeagueIdFromInput($userId, $input);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        $priceMargin = isset($input['price_margin']) && $input['price_margin'] !== ''
            ? (float) $input['price_margin']
            : 0.0;

        if ($matchroundId <= 0) {
            return ['ok' => false, 'errors' => ['Please select a Matchround!']];
        }
        if ($priceMargin <= 0) {
            return ['ok' => false, 'errors' => ['Please select Price Margin!']];
        }
        if (! $this->matchroundBelongsToLeague($matchroundId, $leagueId)) {
            return ['ok' => false, 'errors' => ['Ungültige Spielrunde für die aktive Liga.']];
        }

        try {
            $details = [];
            $teamList = $this->teamIdsForMatchround($matchroundId);
            foreach ($teamList as $teamId) {
                $margins = $this->calculatePlayerPriceMarginsForTeam($teamId, $priceMargin, $leagueId);
                array_push($details, ...$this->updatePlayerPrices($margins, $matchroundId));
            }

            return [
                'ok' => true,
                'message' => 'Dynamic PlayerPrices aktualisiert.',
                'details' => $details,
                'price_league_id' => $leagueId,
                'tab' => 'players',
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()], 'price_league_id' => $leagueId, 'tab' => 'players'];
        }
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
     * Recompute Elo team prices and persist into ffb_teamprice for future matchround(s).
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

        $targetMatchroundIds = $this->futureMatchroundIdsForSave($leagueId, $matchroundId);
        if ($targetMatchroundIds === []) {
            return [
                'ok' => false,
                'errors' => ['Keine zukünftige Spielrunde zum Speichern gefunden.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        }

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
                'errors' => ['Keine Team-Preise zum Speichern vorhanden.'],
                'price_league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'tab' => 'teams',
                'preview' => $preview,
            ];
        }

        try {
            $details = $this->persistTeamPrices($pricesByTeamId, $targetMatchroundIds);
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
                'ELO Team-Preise gespeichert: %d Team(s) × %d Spielrunde(n).',
                count($pricesByTeamId),
                count($targetMatchroundIds),
            ),
            'details' => $details,
            'price_league_id' => $leagueId,
            'matchround_id' => $matchroundId,
            'tab' => 'teams',
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
            if (! $this->matchroundIsFuture($matchroundId)) {
                return [
                    'ok' => false,
                    'errors' => ['Nur Spielrunden mit Start-Datum in der Zukunft sind erlaubt.'],
                    'price_league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'tab' => 'teams',
                ];
            }
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

            $eloRows = $this->eloRating->ratingsForTeamList($teamIds);
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
        if ($priceLeagueId !== null && $priceLeagueId > 0) {
            return League::query()->whereKey($priceLeagueId)->exists() ? $priceLeagueId : 0;
        }

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
     * All league matchrounds for the Team-Preis dropdown (past ones marked non-selectable).
     *
     * @return list<array{matchround_id: int, matchround_title: string, is_future: bool}>
     */
    private function matchroundsForTeamPrice(int $leagueId): array
    {
        $now = now();

        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->get(['matchround_id', 'matchround_title', 'matchround_startdate'])
            ->map(static function (Matchround $r) use ($now): array {
                $start = (string) $r->matchround_startdate;

                return [
                    'matchround_id' => (int) $r->matchround_id,
                    'matchround_title' => (string) $r->matchround_title,
                    'is_future' => $start !== '' && strtotime($start) > $now->getTimestamp(),
                ];
            })
            ->all();
    }

    /**
     * @return list<int>
     */
    private function futureMatchroundIdsForSave(int $leagueId, int $selectedMatchroundId): array
    {
        if ($selectedMatchroundId > 0) {
            if (
                $this->matchroundBelongsToLeague($selectedMatchroundId, $leagueId)
                && $this->matchroundIsFuture($selectedMatchroundId)
            ) {
                return [$selectedMatchroundId];
            }

            return [];
        }

        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_startdate', '>', now())
            ->orderBy('matchround_startdate')
            ->pluck('matchround_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }

    private function matchroundIsFuture(int $matchroundId): bool
    {
        return Matchround::query()
            ->where('matchround_id', $matchroundId)
            ->where('matchround_startdate', '>', now())
            ->exists();
    }

    /**
     * @param  array<int, float>  $pricesByTeamId
     * @param  list<int>  $matchroundIds
     * @return list<string>
     */
    private function persistTeamPrices(array $pricesByTeamId, array $matchroundIds): array
    {
        $details = [];

        DB::transaction(function () use ($pricesByTeamId, $matchroundIds, &$details): void {
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
        });

        return $details;
    }

    /**
     * @return list<float>
     */
    private function priceMargins(): array
    {
        $margins = [];
        for ($i = 0.5; $i <= 3; $i += 0.5) {
            $margins[] = round($i, 1);
        }

        return $margins;
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
     * @param  array<int, float>  $playerPriceMargins
     * @return list<string>
     */
    private function updatePlayerPrices(array $playerPriceMargins, int $matchroundId): array
    {
        $details = [];

        DB::transaction(function () use ($playerPriceMargins, $matchroundId, &$details) {
            foreach ($playerPriceMargins as $playerteamId => $priceMargin) {
                $pt = Playerteam::query()->find($playerteamId);
                if (! $pt) {
                    continue;
                }
                $basePrice = (float) $pt->playerteam_player_price;
                $price = $basePrice + (float) $priceMargin;

                $playerprice = Playerprice::query()
                    ->where('playerprice_playerteam_id', $playerteamId)
                    ->where('playerprice_matchround_id', $matchroundId)
                    ->first();

                if (! $playerprice) {
                    $playerprice = new Playerprice;
                    $playerprice->playerprice_playerteam_id = $playerteamId;
                    $playerprice->playerprice_matchround_id = $matchroundId;
                }

                $playerprice->playerprice_price = $price;
                $playerprice->playerprice_av_power = 1;
                $playerprice->playerprice_player_power = 1;
                $playerprice->save();

                $details[] = 'Price updated: '.$playerteamId.': '.$price;
            }
        });

        return $details;
    }

    /**
     * @return array<int, float> playerteam_id => margin
     */
    private function calculatePlayerPriceMarginsForTeam(int $teamId, float $margin, int $leagueId = 0): array
    {
        $lastMatches = $this->lastMatches($teamId);
        if ($lastMatches === []) {
            return [];
        }

        $opponents = $this->opponents($teamId, $lastMatches);
        $teamsIdList = array_map(static fn (Team $t): int => (int) $t->team_id, $opponents);
        $teamsIdList[] = $teamId;
        $teamPrices = $this->getTeamPrices($teamsIdList, 13, 3);

        $avgPositionPoints = [
            'g' => $this->avgPositionPoints($lastMatches, 'g'),
            'd' => $this->avgPositionPoints($lastMatches, 'd'),
            'm' => $this->avgPositionPoints($lastMatches, 'm'),
            's' => $this->avgPositionPoints($lastMatches, 's'),
        ];

        $players = Playerteam::query()
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_status', 1)
            ->when($leagueId > 0, fn ($q) => $q->forLeague($leagueId))
            ->get();

        $playerPriceMargins = [];
        $matchCount = count($lastMatches);

        foreach ($players as $player) {
            $mpSum = 0.0;
            $position = (string) $player->playerteam_player_position;
            $positionAvg = (float) ($avgPositionPoints[$position] ?? 0);

            foreach ($lastMatches as $match) {
                $opponentId = $this->opponentId($match, $teamId);
                $opponentPrice = (float) ($teamPrices[$opponentId] ?? 0);
                $teamPrice = (float) ($teamPrices[$teamId] ?? 0);
                $priceDiff = $opponentPrice - $teamPrice;
                if ($priceDiff < 0) {
                    $priceDiff = 1 - (($priceDiff * -1) / 10);
                } else {
                    $priceDiff = 1 + ($priceDiff / 10);
                }

                $playerstats = Playerstats::query()
                    ->where('playerstats_match_id', (int) $match->match_id)
                    ->where('playerstats_playerteam_id', (int) $player->playerteam_id)
                    ->first();

                if ($playerstats && $positionAvg != 0.0) {
                    $score = (float) $playerstats->playerstats_score;
                    $percentageScore = ($score / $positionAvg) - 1;
                    $mp = (10 * $percentageScore) * $priceDiff;
                    if ($mp > 10) {
                        $mp = 10;
                    }
                    if ($mp < -10) {
                        $mp = -10;
                    }
                } else {
                    $mp = 0;
                }
                $mpSum += $mp;
            }

            $playerStrength = $mpSum / $matchCount;
            if ($playerStrength > $margin) {
                $playerStrength = $margin;
            }
            if ($playerStrength < (-1 * $margin)) {
                $playerStrength = (-1 * $margin);
            }
            $playerPriceMargins[(int) $player->playerteam_id] = round($playerStrength, 1);
        }

        return $playerPriceMargins;
    }

    private function opponentId(MatchGame $match, int $teamId): int
    {
        if ($teamId === (int) $match->match_hometeam_id) {
            return (int) $match->match_guestteam_id;
        }

        return (int) $match->match_hometeam_id;
    }

    /**
     * @param  list<MatchGame>  $matches
     * @return list<Team>
     */
    private function opponents(int $teamId, array $matches): array
    {
        $opponents = [];
        foreach ($matches as $match) {
            if ((int) $match->match_hometeam_id === $teamId) {
                $guest = $match->guestTeam;
                if ($guest) {
                    $opponents[(int) $guest->team_id] = $guest;
                }
            }
            if ((int) $match->match_guestteam_id === $teamId) {
                $home = $match->homeTeam;
                if ($home) {
                    $opponents[(int) $home->team_id] = $home;
                }
            }
        }

        return array_values($opponents);
    }

    /**
     * @return list<MatchGame>
     */
    private function lastMatches(int $teamId): array
    {
        return MatchGame::query()
            ->with(['homeTeam', 'guestTeam'])
            ->where('match_minutes', '>', 0)
            ->where(function ($q) use ($teamId) {
                $q->where('match_guestteam_id', $teamId)
                    ->orWhere('match_hometeam_id', $teamId);
            })
            ->orderByDesc('match_date')
            ->limit(self::HISTORY_LENGTH)
            ->get()
            ->all();
    }

    /**
     * @param  list<MatchGame>  $matches
     */
    private function avgPositionPoints(array $matches, string $position): float
    {
        if ($matches === []) {
            return 0.0;
        }

        $matchIds = array_map(static fn (MatchGame $m): int => (int) $m->match_id, $matches);

        $query = Playerstats::query()
            ->join('ffb_playerteam', 'ffb_playerteam.playerteam_id', '=', 'ffb_playerstats.playerstats_playerteam_id')
            ->where('ffb_playerteam.playerteam_player_position', $position)
            ->whereIn('ffb_playerstats.playerstats_match_id', $matchIds);

        $numResults = (clone $query)->count();
        if ($numResults <= 0) {
            return 0.0;
        }

        $sum = (float) (clone $query)->sum('ffb_playerstats.playerstats_score');

        return $sum / $numResults;
    }

    /**
     * @param  list<int>  $teamIdList
     * @return array<int, float> team_id => price
     */
    public function getTeamPrices(array $teamIdList, float $maxPrice, float $minPrice): array
    {
        $teams = $this->eloRating->ratingsForTeamList($teamIdList);
        $numTeams = count($teams);
        if ($numTeams === 0) {
            return [];
        }

        $priceDifference = $maxPrice - $minPrice;
        $priceStep = $priceDifference / $numTeams;
        $teamPrices = [];
        $i = 0;
        foreach ($teams as $team) {
            $teamPrices[(int) $team['team_id']] = round($minPrice + ($i * $priceStep), 1);
            $i++;
        }

        return $teamPrices;
    }
}
