<?php

namespace App\Services;

use App\Models\Extremeteam;
use App\Models\League;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use Illuminate\Support\Facades\DB;

class ExtremeTeamService
{
    /** @var list<list<int>> g,d,m,s counts per formation */
    private const SYSTEMS = [
        [1, 3, 4, 3],
        [1, 3, 5, 2],
        [1, 4, 3, 3],
        [1, 4, 4, 2],
        [1, 4, 5, 1],
        [1, 5, 3, 2],
        [1, 5, 4, 1],
    ];

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
        if ($computed === null) {
            return [
                'ok' => true,
                'status' => 'skipped',
                'message' => 'Nicht genug Spieler mit Punkten für ein vollständiges '.$type.'-Team.',
                'matchround_id' => $matchroundId,
                'type' => $type,
            ];
        }

        $extremeteamId = 0;

        DB::transaction(function () use ($matchroundId, $type, $computed, &$extremeteamId) {
            $team = Extremeteam::query()
                ->where('extremeteam_matchround_id', $matchroundId)
                ->where('extremeteam_top_or_flop', $type)
                ->first();

            if (! $team) {
                $team = new Extremeteam;
                $team->extremeteam_matchround_id = $matchroundId;
                $team->extremeteam_top_or_flop = $type;
            }

            $team->extremeteam_score = $computed['score'];
            $team->extremeteam_price = $computed['price'];
            $team->save();

            $team->syncSlots($computed['playerteam_ids']);
            $extremeteamId = (int) $team->extremeteam_id;
        });

        return [
            'ok' => true,
            'status' => 'stored',
            'message' => ucfirst($type).'-Team gespeichert.',
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
                    $details[] = sprintf(
                        'league %d round %d %s: %s',
                        $leagueId,
                        $roundId,
                        $result['type'] ?? '?',
                        $result['status'] ?? (($result['ok'] ?? false) ? 'ok' : 'error'),
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
                    $details[] = "round {$matchroundId} {$type}: error";

                    continue;
                }

                if (($result['status'] ?? '') === 'stored') {
                    $stored++;
                    $details[] = "round {$matchroundId} {$type}: stored";
                } else {
                    $skipped++;
                    $details[] = "round {$matchroundId} {$type}: skipped";
                }
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
     * @return array{score: int, price: float, playerteam_ids: list<int>, players: list<array<string, mixed>>}|null
     */
    public function computeTeam(int $matchroundId, string $type): ?array
    {
        $type = $this->normalizeType($type);

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return null;
        }

        $pricesByPt = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->get()
            ->keyBy(fn (Playerprice $p) => (int) $p->playerprice_playerteam_id);

        if ($pricesByPt->isEmpty()) {
            return null;
        }

        $stats = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->with(['playerteam.player', 'playerteam.team'])
            ->get();

        /** @var array<string, list<array<string, mixed>>> $byPosition */
        $byPosition = ['g' => [], 'd' => [], 'm' => [], 's' => []];

        foreach ($stats as $stat) {
            $pt = $stat->playerteam;
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }
            $pos = (string) $pt->playerteam_player_position;
            if (! isset($byPosition[$pos])) {
                continue;
            }

            $ptId = (int) $stat->playerstats_playerteam_id;
            if (! $pricesByPt->has($ptId)) {
                continue;
            }

            $price = (float) $pricesByPt->get($ptId)->playerprice_price;

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
                'playerteam_team' => (string) $team->team_name,
                'playerteam_team_nationality' => (string) $team->team_nationality,
                'playerteam_player_position' => $pos,
                'playerteam_player_price' => $price,
                'playerteam_status' => (int) ($pt->playerteam_status ?: 0),
                'playerstats_score' => (int) $stat->playerstats_score,
            ];
        }

        foreach ($byPosition as $pos => $rows) {
            usort($rows, function (array $a, array $b) use ($type): int {
                if ($type === 'top') {
                    return [$b['playerstats_score'], $a['playerteam_player_price']]
                        <=> [$a['playerstats_score'], $b['playerteam_player_price']];
                }

                return [$a['playerstats_score'], $b['playerteam_player_price']]
                    <=> [$b['playerstats_score'], $a['playerteam_player_price']];
            });
            $byPosition[$pos] = $rows;
        }

        $bestPlayers = [];
        $teamScore = $type === 'top' ? -100000 : 100000;
        $teamPrice = 0.0;

        foreach (self::SYSTEMS as $system) {
            $picked = [];
            $sumScore = 0;
            $sumPrice = 0.0;
            $limits = [
                'g' => $system[0],
                'd' => $system[1],
                'm' => $system[2],
                's' => $system[3],
            ];

            $enough = true;
            foreach ($limits as $pos => $limit) {
                if (count($byPosition[$pos]) < $limit) {
                    $enough = false;
                    break;
                }
            }
            if (! $enough) {
                continue;
            }

            foreach ($limits as $pos => $limit) {
                $slice = array_slice($byPosition[$pos], 0, $limit);
                foreach ($slice as $row) {
                    $picked[] = $row;
                    $sumScore += (int) $row['playerstats_score'];
                    $sumPrice += (float) $row['playerteam_player_price'];
                }
            }

            $better = $type === 'top'
                ? $sumScore >= $teamScore
                : $sumScore < $teamScore;

            if ($better) {
                $bestPlayers = $picked;
                $teamScore = $sumScore;
                $teamPrice = $sumPrice;
            }
        }

        if (count($bestPlayers) < 11) {
            return null;
        }

        return [
            'score' => $teamScore,
            'price' => round($teamPrice, 1),
            'playerteam_ids' => array_map(
                static fn (array $row): int => (int) $row['playerteam_id'],
                $bestPlayers,
            ),
            'players' => $bestPlayers,
        ];
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

        $pricesByPt = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereIn('playerprice_playerteam_id', $slotIds)
            ->get()
            ->keyBy(fn (Playerprice $p) => (int) $p->playerprice_playerteam_id);

        $players = [];
        foreach ($slotIds as $ptId) {
            $stat = $stats->get($ptId);
            $pt = $stat?->playerteam;
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }

            $price = $pricesByPt->has($ptId)
                ? (float) $pricesByPt->get($ptId)->playerprice_price
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

    private function normalizeType(string $type): string
    {
        return $type === 'flop' ? 'flop' : 'top';
    }
}
