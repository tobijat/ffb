<?php

namespace App\Services;

use App\Models\Extremeteam;
use App\Models\League;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Teamprice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExtremeTeamService
{
    public function __construct(
        private readonly LineupOptionsResolver $lineupOptions,
    ) {}

    /**
     * Load a stored top/flop XI for the player API.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function loadForApi(int $matchroundId, string $type): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        $type = $this->normalizeType($type);

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'status' => 404, 'error' => 'Matchround not found'];
        }

        $team = Extremeteam::query()
            ->where('extremeteam_matchround_id', $matchroundId)
            ->where('extremeteam_top_or_flop', $type)
            ->first();

        if (! $team) {
            return [
                'ok' => true,
                'data' => [
                    'available' => false,
                    'matchround_id' => $matchroundId,
                    'type' => $type,
                    'userteam' => null,
                    'players' => [],
                    'lineup_options' => $this->lineupOptions->forMatchround($matchroundId),
                ],
            ];
        }

        $players = $this->hydratePlayers($team, $matchroundId);

        return [
            'ok' => true,
            'data' => [
                'available' => true,
                'matchround_id' => $matchroundId,
                'type' => $type,
                'userteam' => [
                    'userteam_score' => (int) $team->extremeteam_score,
                    'userteam_price' => round((float) $team->extremeteam_price, 1),
                ],
                'players' => $players,
                'lineup_options' => $this->lineupOptions->forMatchround($matchroundId),
            ],
        ];
    }

    /**
     * Compute and upsert top or flop for a matchround. Skips when fewer than 11 players.
     *
     * @return array{
     *     ok: bool,
     *     status?: string,
     *     message?: string,
     *     errors?: list<string>,
     *     matchround_id?: int,
     *     type?: string,
     *     extremeteam_id?: int
     * }
     */
    public function computeAndStore(int $matchroundId, string $type): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'errors' => ['matchround_id is required']];
        }

        $type = $this->normalizeType($type);

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'errors' => ['Spielrunde nicht gefunden.']];
        }

        $computed = $this->computeTeam($matchroundId, $type);
        if (! ($computed['ok'] ?? false)) {
            $reason = (string) ($computed['reason'] ?? 'Unbekannter Grund.');

            return [
                'ok' => true,
                'status' => 'skipped',
                'message' => $reason,
                'matchround_id' => $matchroundId,
                'type' => $type,
            ];
        }

        $extremeteamId = 0;
        $teamData = $computed['team'];

        DB::transaction(function () use ($matchroundId, $type, $teamData, &$extremeteamId) {
            $team = Extremeteam::query()
                ->where('extremeteam_matchround_id', $matchroundId)
                ->where('extremeteam_top_or_flop', $type)
                ->first();

            if (! $team) {
                $team = new Extremeteam;
                $team->extremeteam_matchround_id = $matchroundId;
                $team->extremeteam_top_or_flop = $type;
            }

            $team->extremeteam_score = $teamData['score'];
            $team->extremeteam_price = $teamData['price'];
            $team->save();

            $team->syncSlots($teamData['playerteam_ids']);
            $extremeteamId = (int) $team->extremeteam_id;
        });

        return [
            'ok' => true,
            'status' => 'stored',
            'message' => ucfirst($type).'-Team gespeichert ('.$teamData['score'].' Pkt · '.$teamData['price'].' Cr).',
            'matchround_id' => $matchroundId,
            'type' => $type,
            'extremeteam_id' => $extremeteamId,
        ];
    }

    /**
     * Store top and flop for one matchround.
     *
     * @return array{ok: bool, results: list<array<string, mixed>>, errors?: list<string>}
     */
    public function computeAndStoreBoth(int $matchroundId): array
    {
        $results = [];
        foreach (['top', 'flop'] as $type) {
            $results[] = $this->computeAndStore($matchroundId, $type);
        }

        $failed = array_values(array_filter(
            $results,
            static fn (array $r): bool => ! ($r['ok'] ?? false),
        ));

        if ($failed !== []) {
            return [
                'ok' => false,
                'results' => $results,
                'errors' => array_merge(...array_map(
                    static fn (array $r): array => $r['errors'] ?? ['Speichern fehlgeschlagen.'],
                    $failed,
                )),
            ];
        }

        return ['ok' => true, 'results' => $results];
    }

    /**
     * Backfill past matchrounds for all visible leagues.
     *
     * @return array{ok: true, leagues: int, matchrounds: int, stored: int, skipped: int, details: list<string>}
     */
    public function backfillVisibleLeagues(): array
    {
        $leagueIds = League::query()
            ->forPlayerApp()
            ->where('league_visible', 1)
            ->orderBy('league_id')
            ->pluck('league_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $matchroundsProcessed = 0;
        $stored = 0;
        $skipped = 0;
        $details = [];

        foreach ($leagueIds as $leagueId) {
            $roundIds = Matchround::query()
                ->where('matchround_league_id', $leagueId)
                ->where('matchround_enddate', '<', now())
                ->orderBy('matchround_startdate')
                ->pluck('matchround_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            foreach ($roundIds as $roundId) {
                $matchroundsProcessed++;
                $both = $this->computeAndStoreBoth($roundId);
                foreach ($both['results'] as $result) {
                    if (($result['status'] ?? '') === 'stored') {
                        $stored++;
                    } else {
                        $skipped++;
                    }
                    $details[] = $this->formatResultDetail(
                        sprintf('league %d round %d', $leagueId, $roundId),
                        $result,
                    );
                }
            }
        }

        return [
            'ok' => true,
            'leagues' => count($leagueIds),
            'matchrounds' => $matchroundsProcessed,
            'stored' => $stored,
            'skipped' => $skipped,
            'details' => $details,
        ];
    }

    /**
     * @param  list<int>  $matchroundIds
     * @return array{ok: bool, stored: int, skipped: int, errors: list<string>, details: list<string>}
     */
    public function computeAndStoreForMatchrounds(array $matchroundIds, bool $top = true, bool $flop = true): array
    {
        $types = [];
        if ($top) {
            $types[] = 'top';
        }
        if ($flop) {
            $types[] = 'flop';
        }

        if ($types === []) {
            return [
                'ok' => false,
                'stored' => 0,
                'skipped' => 0,
                'errors' => ['Bitte Top und/oder Flop wählen.'],
                'details' => [],
            ];
        }

        $stored = 0;
        $skipped = 0;
        $errors = [];
        $details = [];

        foreach ($matchroundIds as $matchroundId) {
            $matchroundId = (int) $matchroundId;
            if ($matchroundId <= 0) {
                continue;
            }

            foreach ($types as $type) {
                $result = $this->computeAndStore($matchroundId, $type);
                if (! ($result['ok'] ?? false)) {
                    $errors = array_merge($errors, $result['errors'] ?? ['Fehler.']);
                    $details[] = $this->formatResultDetail("Runde {$matchroundId}", $result);

                    continue;
                }

                if (($result['status'] ?? '') === 'stored') {
                    $stored++;
                } else {
                    $skipped++;
                }

                $details[] = $this->formatResultDetail("Runde {$matchroundId}", $result);
            }
        }

        return [
            'ok' => $errors === [],
            'stored' => $stored,
            'skipped' => $skipped,
            'errors' => $errors,
            'details' => $details,
        ];
    }

    /**
     * @return array{
     *     ok: true,
     *     team: array{score: int, price: float, playerteam_ids: list<int>, players: list<array<string, mixed>>}
     * }|array{ok: false, reason: string}
     */
    public function computeTeam(int $matchroundId, string $type): array
    {
        $type = $this->normalizeType($type);
        $typeLabel = $type === 'top' ? 'Top' : 'Flop';

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'reason' => 'Spielrunde nicht gefunden.'];
        }

        $options = $this->lineupOptions->forMatchround($matchroundId);
        $formations = $this->formationsFromOptions($options);
        if ($formations === []) {
            return [
                'ok' => false,
                'reason' => sprintf(
                    'Keine gültige Formation aus den Aufstellungs-Optionen (max. %d Spieler, Positions-Min/Max).',
                    (int) $options['lineup_max_players'],
                ),
            ];
        }

        $stats = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->with(['playerteam.player', 'playerteam.team'])
            ->get();

        if ($stats->isEmpty()) {
            return [
                'ok' => false,
                'reason' => 'Keine Spielerstatistiken/Punkte für diese Spielrunde – '.$typeLabel.'-Team nicht berechenbar.',
            ];
        }

        $playerteamsById = collect();
        foreach ($stats as $stat) {
            $pt = $stat->playerteam;
            if ($pt) {
                $playerteamsById->put((int) $pt->playerteam_id, $pt);
            }
        }

        $pricesByPt = $this->resolvePricesForPlayerteams(
            $matchroundId,
            $playerteamsById->keys()->map(static fn ($id): int => (int) $id)->all(),
            $playerteamsById,
        );

        if ($pricesByPt->isEmpty()) {
            return [
                'ok' => false,
                'reason' => 'Keine Spieler- oder Teampreise für diese Spielrunde – '.$typeLabel.'-Team nicht berechenbar.',
            ];
        }

        /** @var array<string, list<array<string, mixed>>> $byPosition */
        $byPosition = ['g' => [], 'd' => [], 'm' => [], 's' => []];

        foreach ($stats as $stat) {
            $pt = $stat->playerteam;
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }
            if ((int) ($pt->playerteam_status ?: 0) !== 1) {
                continue;
            }
            $pos = strtolower((string) $pt->playerteam_player_position);
            if (! isset($byPosition[$pos])) {
                continue;
            }

            $ptId = (int) $stat->playerstats_playerteam_id;
            if (! $pricesByPt->has($ptId)) {
                continue;
            }

            $price = (float) $pricesByPt->get($ptId);
            $player = $pt->player;
            $team = $pt->team;
            $byPosition[$pos][] = [
                'player_fname' => (string) $player->player_fname,
                'player_lname' => (string) $player->player_lname,
                'player_nationality' => (string) ($player->player_nationality ?: ''),
                'player_status' => (int) ($player->player_status ?: 0),
                'player_status_description' => (string) ($player->player_status_description ?: '0'),
                'playerteam_id' => $ptId,
                'playerteam_team_id' => (int) $pt->playerteam_team_id,
                'playerteam_team_asset_key' => (string) ($team->asset_key ?? ''),
                'player_asset_key' => (string) ($player->asset_key ?? ''),
                'playerteam_team' => (string) $team->team_name,
                'playerteam_team_nationality' => (string) $team->team_nationality,
                'playerteam_player_position' => $pos,
                'playerteam_player_price' => $price,
                'playerteam_status' => (int) ($pt->playerteam_status ?: 0),
                'playerstats_score' => (int) $stat->playerstats_score,
            ];
        }

        $eligibleCounts = [
            'g' => count($byPosition['g']),
            'd' => count($byPosition['d']),
            'm' => count($byPosition['m']),
            's' => count($byPosition['s']),
        ];
        $eligibleTotal = array_sum($eligibleCounts);
        if ($eligibleTotal === 0) {
            return [
                'ok' => false,
                'reason' => 'Keine aktiven Spieler mit Punkten und Preis für diese Spielrunde.',
            ];
        }

        $fillableFormations = array_values(array_filter(
            $formations,
            fn (array $formation): bool => $this->formationHasEnoughCandidates($formation, $byPosition),
        ));
        if ($fillableFormations === []) {
            return [
                'ok' => false,
                'reason' => sprintf(
                    'Zu wenig Spieler mit Punkten und Preis für eine vollständige Formation (T:%d Abw:%d Mf:%d St:%d).',
                    $eligibleCounts['g'],
                    $eligibleCounts['d'],
                    $eligibleCounts['m'],
                    $eligibleCounts['s'],
                ),
            ];
        }

        foreach ($byPosition as $pos => $rows) {
            usort($rows, function (array $a, array $b) use ($type): int {
                if ($type === 'top') {
                    return [$b['playerstats_score'], $a['playerteam_player_price'], $a['playerteam_id']]
                        <=> [$a['playerstats_score'], $b['playerteam_player_price'], $b['playerteam_id']];
                }

                return [$a['playerstats_score'], $b['playerteam_player_price'], $a['playerteam_id']]
                    <=> [$b['playerstats_score'], $a['playerteam_player_price'], $b['playerteam_id']];
            });
            $byPosition[$pos] = array_slice($rows, 0, 8);
        }

        $maxCredits = (float) $options['lineup_max_credits'];
        $maxPerTeam = (int) $options['lineup_max_players_team'];
        $maxPlayers = (int) $options['lineup_max_players'];

        $bestPlayers = null;
        $bestScore = 0;
        $bestPrice = 0.0;

        foreach ($fillableFormations as $formation) {
            $result = $this->searchFormation(
                $formation,
                $byPosition,
                $type,
                $maxCredits,
                $maxPerTeam,
            );
            if ($result === null) {
                continue;
            }

            if ($bestPlayers === null
                || $this->isBetterTeam($type, $result['score'], $result['price'], $bestScore, $bestPrice)) {
                $bestPlayers = $result['players'];
                $bestScore = $result['score'];
                $bestPrice = $result['price'];
            }
        }

        if ($bestPlayers === null || count($bestPlayers) !== $maxPlayers) {
            return [
                'ok' => false,
                'reason' => sprintf(
                    'Kein gültiges %s-Team innerhalb der Limits (max. %.1f Credits, max. %d Spieler/Verein).',
                    $typeLabel,
                    $maxCredits,
                    $maxPerTeam,
                ),
            ];
        }

        return [
            'ok' => true,
            'team' => [
                'score' => $bestScore,
                'price' => round($bestPrice, 1),
                'playerteam_ids' => array_map(
                    static fn (array $row): int => (int) $row['playerteam_id'],
                    $bestPlayers,
                ),
                'players' => $bestPlayers,
            ],
        ];
    }

    /**
     * Validate a stored top/flop team against lineup options and credit limit.
     *
     * @return list<string>
     */
    public function complianceIssues(Extremeteam $team): array
    {
        $matchroundId = (int) $team->extremeteam_matchround_id;
        $options = $this->lineupOptions->forMatchround($matchroundId);
        $slotIds = $team->playerteamIdsInSlotOrder();
        $issues = [];

        $maxPlayers = (int) $options['lineup_max_players'];
        if (count($slotIds) !== $maxPlayers) {
            $issues[] = sprintf('%d Spieler (erwartet %d)', count($slotIds), $maxPlayers);
        }

        if ($slotIds === []) {
            return $issues !== [] ? $issues : ['keine Spieler'];
        }

        $playerteams = Playerteam::query()
            ->whereIn('playerteam_id', $slotIds)
            ->get(['playerteam_id', 'playerteam_team_id', 'playerteam_player_position', 'playerteam_status'])
            ->keyBy(fn (Playerteam $pt): int => (int) $pt->playerteam_id);

        $prices = $this->resolvePricesForPlayerteams($matchroundId, $slotIds, $playerteams);

        $counts = ['g' => 0, 'd' => 0, 'm' => 0, 's' => 0];
        $perTeam = [];
        $sumPrice = 0.0;

        foreach ($slotIds as $ptId) {
            $pt = $playerteams->get($ptId);
            if ($pt === null) {
                $issues[] = 'Spielerteam #'.$ptId.' fehlt';

                continue;
            }

            $pos = strtolower((string) $pt->playerteam_player_position);
            if (! isset($counts[$pos])) {
                $issues[] = 'unbekannte Position für Spielerteam #'.$ptId;
            } else {
                $counts[$pos]++;
            }

            $teamId = (int) $pt->playerteam_team_id;
            $perTeam[$teamId] = ($perTeam[$teamId] ?? 0) + 1;

            if ($prices->has($ptId)) {
                $sumPrice += (float) $prices->get($ptId);
            } else {
                $issues[] = 'kein Preis für Spielerteam #'.$ptId;
            }
        }

        $maxPerTeam = (int) $options['lineup_max_players_team'];
        foreach ($perTeam as $count) {
            if ($count > $maxPerTeam) {
                $issues[] = sprintf('mehr als %d Spieler aus einem Team', $maxPerTeam);
                break;
            }
        }

        $rules = [
            'g' => [(int) $options['lineup_min_g'], (int) $options['lineup_max_g']],
            'd' => [(int) $options['lineup_min_d'], (int) $options['lineup_max_d']],
            'm' => [(int) $options['lineup_min_m'], (int) $options['lineup_max_m']],
            's' => [(int) $options['lineup_min_s'], (int) $options['lineup_max_s']],
        ];
        foreach ($rules as $position => [$min, $max]) {
            if ($counts[$position] < $min || $counts[$position] > $max) {
                $issues[] = sprintf(
                    'Position %s: %d (erlaubt %d–%d)',
                    strtoupper($position),
                    $counts[$position],
                    $min,
                    $max,
                );
            }
        }

        $maxCredits = (float) $options['lineup_max_credits'];
        $storedPrice = round((float) $team->extremeteam_price, 1);
        $sumPrice = round($sumPrice, 1);
        if ($sumPrice > $maxCredits || $storedPrice > $maxCredits) {
            $issues[] = sprintf(
                'Credits %.1f / gespeichert %.1f (Limit %.1f)',
                $sumPrice,
                $storedPrice,
                $maxCredits,
            );
        }

        return array_values(array_unique($issues));
    }

    /**
     * @param  array{g: int, d: int, m: int, s: int}  $formation
     * @param  array<string, list<array<string, mixed>>>  $byPosition
     * @return array{score: int, price: float, players: list<array<string, mixed>>}|null
     */
    private function searchFormation(
        array $formation,
        array $byPosition,
        string $type,
        float $maxCredits,
        int $maxPerTeam,
    ): ?array {
        $order = ['g', 'd', 'm', 's'];
        $best = null;
        $bestScore = 0;
        $bestPrice = 0.0;

        /** @var array<string, float> $minCostForNeed */
        $minCostForNeed = [];
        foreach ($order as $pos) {
            $need = $formation[$pos];
            $candidates = $byPosition[$pos];
            if (count($candidates) < $need) {
                return null;
            }

            $prices = array_map(
                static fn (array $row): float => (float) $row['playerteam_player_price'],
                $candidates,
            );
            sort($prices);
            $sum = 0.0;
            for ($k = 0; $k < $need; $k++) {
                $sum += $prices[$k];
            }
            $minCostForNeed[$pos] = $sum;
        }

        $search = function (
            int $posIndex,
            array $picked,
            int $sumScore,
            float $sumPrice,
            array $teamCounts,
        ) use (
            &$search,
            &$best,
            &$bestScore,
            &$bestPrice,
            $order,
            $formation,
            $byPosition,
            $type,
            $maxCredits,
            $maxPerTeam,
            $minCostForNeed,
        ): void {
            if ($posIndex >= count($order)) {
                if ($best === null || $this->isBetterTeam($type, $sumScore, $sumPrice, $bestScore, $bestPrice)) {
                    $best = $picked;
                    $bestScore = $sumScore;
                    $bestPrice = $sumPrice;
                }

                return;
            }

            $minRemaining = 0.0;
            for ($j = $posIndex; $j < count($order); $j++) {
                $minRemaining += $minCostForNeed[$order[$j]];
            }
            if ($sumPrice + $minRemaining > $maxCredits) {
                return;
            }

            $pos = $order[$posIndex];
            $need = $formation[$pos];
            $candidates = $byPosition[$pos];

            $this->choosePlayers(
                $candidates,
                $need,
                0,
                [],
                $picked,
                $sumScore,
                $sumPrice,
                $teamCounts,
                $maxCredits,
                $maxPerTeam,
                function (
                    array $nextPicked,
                    int $nextScore,
                    float $nextPrice,
                    array $nextTeamCounts,
                ) use ($search, $posIndex): void {
                    $search($posIndex + 1, $nextPicked, $nextScore, $nextPrice, $nextTeamCounts);
                },
            );
        };

        $search(0, [], 0, 0.0, []);

        if ($best === null) {
            return null;
        }

        return [
            'score' => $bestScore,
            'price' => $bestPrice,
            'players' => $best,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $candidates
     * @param  list<array<string, mixed>>  $chosen
     * @param  list<array<string, mixed>>  $picked
     * @param  array<int, int>  $teamCounts
     * @param  callable(list<array<string, mixed>>, int, float, array<int, int>): void  $onComplete
     */
    private function choosePlayers(
        array $candidates,
        int $need,
        int $start,
        array $chosen,
        array $picked,
        int $sumScore,
        float $sumPrice,
        array $teamCounts,
        float $maxCredits,
        int $maxPerTeam,
        callable $onComplete,
    ): void {
        if (count($chosen) === $need) {
            $onComplete(
                array_merge($picked, $chosen),
                $sumScore,
                $sumPrice,
                $teamCounts,
            );

            return;
        }

        $remaining = $need - count($chosen);
        $available = count($candidates) - $start;
        if ($available < $remaining) {
            return;
        }

        for ($i = $start; $i < count($candidates); $i++) {
            if ($available - ($i - $start) < $remaining) {
                break;
            }

            $row = $candidates[$i];
            $price = (float) $row['playerteam_player_price'];
            $nextPrice = $sumPrice + $price;
            if ($nextPrice > $maxCredits) {
                continue;
            }

            $teamId = (int) $row['playerteam_team_id'];
            $teamCount = ($teamCounts[$teamId] ?? 0) + 1;
            if ($teamCount > $maxPerTeam) {
                continue;
            }

            $nextTeamCounts = $teamCounts;
            $nextTeamCounts[$teamId] = $teamCount;
            $nextChosen = $chosen;
            $nextChosen[] = $row;

            $this->choosePlayers(
                $candidates,
                $need,
                $i + 1,
                $nextChosen,
                $picked,
                $sumScore + (int) $row['playerstats_score'],
                $nextPrice,
                $nextTeamCounts,
                $maxCredits,
                $maxPerTeam,
                $onComplete,
            );
        }
    }

    /**
     * @param  array{
     *     lineup_max_players: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int
     * }  $options
     * @return list<array{g: int, d: int, m: int, s: int}>
     */
    private function formationsFromOptions(array $options): array
    {
        $maxPlayers = (int) $options['lineup_max_players'];
        $formations = [];

        for ($g = (int) $options['lineup_min_g']; $g <= (int) $options['lineup_max_g']; $g++) {
            for ($d = (int) $options['lineup_min_d']; $d <= (int) $options['lineup_max_d']; $d++) {
                for ($m = (int) $options['lineup_min_m']; $m <= (int) $options['lineup_max_m']; $m++) {
                    for ($s = (int) $options['lineup_min_s']; $s <= (int) $options['lineup_max_s']; $s++) {
                        if ($g + $d + $m + $s === $maxPlayers) {
                            $formations[] = ['g' => $g, 'd' => $d, 'm' => $m, 's' => $s];
                        }
                    }
                }
            }
        }

        return $formations;
    }

    /**
     * @param  array{g: int, d: int, m: int, s: int}  $formation
     * @param  array<string, list<array<string, mixed>>>  $byPosition
     */
    private function formationHasEnoughCandidates(array $formation, array $byPosition): bool
    {
        foreach ($formation as $pos => $need) {
            if (count($byPosition[$pos] ?? []) < $need) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function formatResultDetail(string $prefix, array $result): string
    {
        $type = (string) ($result['type'] ?? '?');
        $status = (string) ($result['status'] ?? ((! ($result['ok'] ?? false)) ? 'error' : 'ok'));
        $message = trim((string) ($result['message'] ?? ''));

        if ($status === 'skipped' && $message !== '') {
            return sprintf('%s %s: übersprungen — %s', $prefix, $type, $message);
        }

        if ($status === 'stored' && $message !== '') {
            return sprintf('%s %s: gespeichert — %s', $prefix, $type, $message);
        }

        if ($status === 'error' || ! ($result['ok'] ?? false)) {
            $error = $message !== ''
                ? $message
                : implode(' ', $result['errors'] ?? ['Fehler.']);

            return sprintf('%s %s: Fehler — %s', $prefix, $type, $error);
        }

        return sprintf('%s %s: %s', $prefix, $type, $status);
    }

    private function isBetterTeam(
        string $type,
        int $score,
        float $price,
        int $bestScore,
        float $bestPrice,
    ): bool {
        if ($type === 'top') {
            if ($score !== $bestScore) {
                return $score > $bestScore;
            }

            return $price < $bestPrice;
        }

        if ($score !== $bestScore) {
            return $score < $bestScore;
        }

        return $price > $bestPrice;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function hydratePlayers(Extremeteam $team, int $matchroundId): array
    {
        $slotIds = $team->playerteamIdsInSlotOrder();
        if ($slotIds === []) {
            return [];
        }

        $stats = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->whereIn('playerstats_playerteam_id', $slotIds)
            ->with(['playerteam.player', 'playerteam.team'])
            ->get()
            ->keyBy(fn (Playerstats $s) => (int) $s->playerstats_playerteam_id);

        $playerteamsById = collect();
        foreach ($slotIds as $ptId) {
            $pt = $stats->get($ptId)?->playerteam;
            if ($pt) {
                $playerteamsById->put($ptId, $pt);
            }
        }

        $pricesByPt = $this->resolvePricesForPlayerteams($matchroundId, $slotIds, $playerteamsById);

        $players = [];
        foreach ($slotIds as $ptId) {
            $stat = $stats->get($ptId);
            $pt = $stat?->playerteam;
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }

            $price = $pricesByPt->has($ptId)
                ? (float) $pricesByPt->get($ptId)
                : 0.0;

            $player = $pt->player;
            $club = $pt->team;
            $players[] = [
                'player_fname' => (string) $player->player_fname,
                'player_lname' => (string) $player->player_lname,
                'player_nationality' => (string) ($player->player_nationality ?: ''),
                'player_status' => (int) ($player->player_status ?: 0),
                'player_status_description' => (string) ($player->player_status_description ?: '0'),
                'playerteam_id' => $ptId,
                'playerteam_team_id' => (int) $pt->playerteam_team_id,
                'playerteam_team_asset_key' => (string) ($club->asset_key ?? ''),
                'player_asset_key' => (string) ($player->asset_key ?? ''),
                'playerteam_team' => (string) $club->team_name,
                'playerteam_team_nationality' => (string) $club->team_nationality,
                'playerteam_player_position' => (string) $pt->playerteam_player_position,
                'playerteam_player_price' => $price,
                'playerteam_status' => (int) ($pt->playerteam_status ?: 0),
                'playerstats_score' => (int) ($stat->playerstats_score ?? 0),
            ];
        }

        return $players;
    }

    /**
     * Resolve credits: ffb_playerprice, else ffb_teamprice for the player's team.
     *
     * @param  list<int>  $playerteamIds
     * @param  Collection<int, Playerteam>  $playerteams
     * @return Collection<int, float>
     */
    private function resolvePricesForPlayerteams(int $matchroundId, array $playerteamIds, Collection $playerteams): Collection
    {
        if ($playerteamIds === [] || $matchroundId <= 0) {
            return collect();
        }

        $fromPlayer = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereIn('playerprice_playerteam_id', $playerteamIds)
            ->get()
            ->mapWithKeys(fn (Playerprice $row): array => [
                (int) $row->playerprice_playerteam_id => (float) $row->playerprice_price,
            ]);

        $teamIds = $playerteams
            ->map(static fn (Playerteam $pt): int => (int) $pt->playerteam_team_id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        $fromTeam = $teamIds === []
            ? collect()
            : Teamprice::query()
                ->where('teamprice_matchround_id', $matchroundId)
                ->whereIn('teamprice_team_id', $teamIds)
                ->get()
                ->mapWithKeys(fn (Teamprice $row): array => [
                    (int) $row->teamprice_team_id => (float) $row->teamprice_price,
                ]);

        $resolved = collect();
        foreach ($playerteamIds as $ptId) {
            $playerPrice = $fromPlayer->has($ptId) ? (float) $fromPlayer->get($ptId) : null;
            if ($playerPrice !== null && $playerPrice > 0) {
                $resolved->put($ptId, $playerPrice);

                continue;
            }

            $teamId = (int) ($playerteams->get($ptId)?->playerteam_team_id ?? 0);
            if ($teamId > 0 && $fromTeam->has($teamId)) {
                $resolved->put($ptId, (float) $fromTeam->get($teamId));
            }
        }

        return $resolved;
    }

    private function normalizeType(string $type): string
    {
        return $type === 'flop' ? 'flop' : 'top';
    }
}
