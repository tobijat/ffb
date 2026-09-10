<?php

namespace App\Services;

use App\Models\Playerteam;
use App\Models\Userteam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 1–2: cleanup, schema, duplicate merge, league backfill + FK remap.
 */
class PlayerteamLeagueBackfillService
{
    public function __construct(
        private readonly PlayerteamLeagueInventoryService $inventory,
        private readonly AdminDbCleanupService $dbCleanup,
    ) {}

    /**
     * @return array{deleted: int, ids: list<int>}
     */
    public function deleteUnreferenced(bool $execute): array
    {
        $ids = $this->inventory->unreferencedPlayerteamIds();
        if ($ids === [] || ! $execute) {
            return ['deleted' => $execute ? 0 : count($ids), 'ids' => $ids];
        }

        $deleted = Playerteam::query()->whereIn('playerteam_id', $ids)->delete();

        return ['deleted' => (int) $deleted, 'ids' => $ids];
    }

    /**
     * Merge duplicate (player, team) pairs onto the lowest playerteam_id.
     * Skips groups whose entries have different transfer dates — those are
     * successive club spells (often different positions / leagues), not true dupes.
     *
     * @return array{
     *     merged_groups: int,
     *     remapped_ids: array<int, int>,
     *     skipped_transfer_spell_groups: int
     * }
     */
    public function mergeDuplicatePlayerTeamPairs(bool $execute): array
    {
        $groups = $this->dbCleanup->duplicatePlayerteamGroups();
        $remapped = [];
        $merged = 0;
        $skipped = 0;

        foreach ($groups as $group) {
            $transfers = [];
            foreach ($group['entries'] as $entry) {
                $transfers[substr((string) ($entry['playerteam_date_transfer'] ?? ''), 0, 10)] = true;
            }
            if (count($transfers) > 1) {
                $skipped++;

                continue;
            }

            $entryIds = array_map(
                static fn (array $e): int => (int) $e['playerteam_id'],
                $group['entries'],
            );
            sort($entryIds);
            $keeper = $entryIds[0];
            $dupes = array_slice($entryIds, 1);

            foreach ($dupes as $dupe) {
                $remapped[$dupe] = $keeper;
                if ($execute) {
                    $this->remapAllReferences($dupe, $keeper);
                    Playerteam::query()->whereKey($dupe)->delete();
                }
            }
            $merged++;
        }

        return [
            'merged_groups' => $merged,
            'remapped_ids' => $remapped,
            'skipped_transfer_spell_groups' => $skipped,
        ];
    }

    /**
     * Assign each team league to the latest transfer spell whose transfer date
     * is on or before that league's first match date for the team.
     *
     * @param  list<array{id: int, transfer: string}>  $spells
     * @return array<int, list<int>> spell id => league ids
     */
    public function assignLeaguesToTransferSpells(int $teamId, array $spells): array
    {
        $assignment = [];
        foreach ($spells as $spell) {
            $assignment[(int) $spell['id']] = [];
        }

        $leagueFirstMatches = DB::table('ffb_match as m')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
            ->where(function ($q) use ($teamId) {
                $q->where('m.match_hometeam_id', $teamId)
                    ->orWhere('m.match_guestteam_id', $teamId);
            })
            ->groupBy('mr.matchround_league_id')
            ->orderBy(DB::raw('MIN(m.match_date)'))
            ->get([
                'mr.matchround_league_id as league_id',
                DB::raw('MIN(m.match_date) as first_match'),
            ]);

        $sorted = $spells;
        usort(
            $sorted,
            static fn (array $a, array $b): int => strcmp(
                substr((string) $a['transfer'], 0, 10),
                substr((string) $b['transfer'], 0, 10),
            ) ?: ((int) $a['id'] <=> (int) $b['id']),
        );

        foreach ($leagueFirstMatches as $league) {
            $leagueId = (int) $league->league_id;
            $firstMatch = substr((string) $league->first_match, 0, 10);
            if ($leagueId <= 0 || $firstMatch === '') {
                continue;
            }

            $chosenId = null;
            foreach ($sorted as $spell) {
                $transfer = substr((string) $spell['transfer'], 0, 10);
                if ($transfer !== '' && $transfer <= $firstMatch) {
                    $chosenId = (int) $spell['id'];
                }
            }

            if ($chosenId !== null) {
                $assignment[$chosenId][] = $leagueId;
            }
        }

        return $assignment;
    }

    /**
     * Re-create wrongly merged transfer-spell rows and move league-scoped FKs
     * from incorrect copies onto the restored IDs.
     *
     * @param  list<array{
     *     restored_id: int,
     *     sibling_id: int,
     *     player_id: int,
     *     team_id: int,
     *     status: int,
     *     price: float|int,
     *     position: string,
     *     transfer: string,
     *     picture?: string
     * }>  $spells
     * @return array{restored: list<array<string, mixed>>, skipped: list<string>}
     */
    public function restoreTransferSpellRows(array $spells, bool $execute): array
    {
        $restored = [];
        $skipped = [];

        foreach ($spells as $spell) {
            $restoredId = (int) $spell['restored_id'];
            $siblingId = (int) $spell['sibling_id'];
            $teamId = (int) $spell['team_id'];
            $playerId = (int) $spell['player_id'];
            $transfer = (string) $spell['transfer'];

            if (Playerteam::query()->whereKey($restoredId)->exists()) {
                $skipped[] = "restored_id {$restoredId} already exists";

                continue;
            }

            $sibling = Playerteam::query()->find($siblingId);
            if (! $sibling) {
                $skipped[] = "sibling {$siblingId} missing for restored {$restoredId}";

                continue;
            }

            $assignment = $this->assignLeaguesToTransferSpells($teamId, [
                [
                    'id' => $siblingId,
                    'transfer' => (string) $sibling->playerteam_date_transfer,
                ],
                [
                    'id' => $restoredId,
                    'transfer' => $transfer,
                ],
            ]);

            $targetLeagues = $assignment[$restoredId] ?? [];
            if ($targetLeagues === []) {
                $skipped[] = "no leagues assigned to restored {$restoredId} by transfer {$transfer}";

                continue;
            }

            foreach ($targetLeagues as $leagueId) {
                $existingCopy = Playerteam::query()
                    ->where('playerteam_player_id', $playerId)
                    ->where('playerteam_team_id', $teamId)
                    ->where('playerteam_league_id', $leagueId)
                    ->where('playerteam_id', '!=', $siblingId)
                    ->orderBy('playerteam_id')
                    ->first();

                $fromId = $existingCopy ? (int) $existingCopy->playerteam_id : null;

                $row = [
                    'restored_id' => $restoredId,
                    'from_id' => $fromId,
                    'league_id' => $leagueId,
                    'player_id' => $playerId,
                    'team_id' => $teamId,
                    'position' => (string) $spell['position'],
                    'status' => (int) $spell['status'],
                    'transfer' => $transfer,
                ];

                if ($execute) {
                    if ($fromId !== null && $fromId !== $restoredId) {
                        $this->remapAllReferences($fromId, $restoredId);
                        Playerteam::query()->whereKey($fromId)->delete();
                    }

                    DB::table('ffb_playerteam')->insert([
                        'playerteam_id' => $restoredId,
                        'playerteam_player_id' => $playerId,
                        'playerteam_team_id' => $teamId,
                        'playerteam_league_id' => $leagueId,
                        'playerteam_player_picture' => (string) ($spell['picture'] ?? ''),
                        'playerteam_status' => (int) $spell['status'],
                        'playerteam_player_price' => (float) $spell['price'],
                        'playerteam_player_position' => (string) $spell['position'],
                        'playerteam_date_transfer' => strlen($transfer) === 10
                            ? $transfer.' 00:00:00'
                            : $transfer,
                    ]);
                }

                $restored[] = $row;
            }
        }

        return ['restored' => $restored, 'skipped' => $skipped];
    }

    /**
     * Known Feffernitz transfer-spell rows incorrectly collapsed before backfill.
     *
     * @return list<array<string, mixed>>
     */
    public function feffernitzTransferSpellRestores(): array
    {
        return [
            [
                'restored_id' => 4656,
                'sibling_id' => 3790,
                'player_id' => 3745,
                'team_id' => 27,
                'status' => 1,
                'price' => 9,
                'position' => 's',
                'transfer' => '2010-03-20',
                'picture' => '',
            ],
            [
                'restored_id' => 4657,
                'sibling_id' => 3791,
                'player_id' => 3746,
                'team_id' => 27,
                'status' => 1,
                'price' => 9,
                'position' => 'm',
                'transfer' => '2010-03-20',
                'picture' => '',
            ],
        ];
    }

    public function ensureLeagueColumn(): void
    {
        if (Schema::hasColumn('ffb_playerteam', 'playerteam_league_id')) {
            return;
        }

        Schema::table('ffb_playerteam', function ($table) {
            $table->integer('playerteam_league_id')->nullable()->after('playerteam_team_id');
        });
    }

    /**
     * @return array{
     *     processed: int,
     *     copies_created: int,
     *     remapped_fk_rows: int,
     *     fallback_assigned: int,
     *     mapping: list<array{old_id: int, league_id: int, new_id: int}>
     * }
     */
    public function backfill(bool $execute): array
    {
        $this->ensureLeagueColumn();

        $membership = $this->inventory->leagueMembershipByPlayerteam();
        $defaultLeagueId = (int) config('ffb.registration_default_league_id', 1);
        $fallbackAssigned = 0;
        $copies = 0;
        $remappedFk = 0;
        $mapping = [];
        $processed = 0;

        $rows = Playerteam::query()->orderBy('playerteam_id')->get();

        foreach ($rows as $row) {
            $processed++;
            $oldId = (int) $row->playerteam_id;
            $leagues = $membership[$oldId] ?? [];

            if ($leagues === []) {
                $leagues = $this->fallbackLeaguesForTeam((int) $row->playerteam_team_id, $defaultLeagueId);
                $fallbackAssigned++;
            }

            sort($leagues);
            $primary = $leagues[0];
            $extras = array_slice($leagues, 1);

            $existingLeague = (int) ($row->playerteam_league_id ?? 0);
            if ($existingLeague > 0) {
                $primary = $existingLeague;
                $extras = array_values(array_filter(
                    $leagues,
                    static fn (int $id): bool => $id !== $primary,
                ));
            }

            if ($execute) {
                if ($existingLeague !== $primary) {
                    $row->playerteam_league_id = $primary;
                    $row->save();
                }
            }
            $mapping[] = ['old_id' => $oldId, 'league_id' => $primary, 'new_id' => $oldId];

            foreach ($extras as $leagueId) {
                $existingCopyId = (int) (Playerteam::query()
                    ->where('playerteam_player_id', (int) $row->playerteam_player_id)
                    ->where('playerteam_team_id', (int) $row->playerteam_team_id)
                    ->where('playerteam_league_id', $leagueId)
                    ->value('playerteam_id') ?? 0);

                if ($existingCopyId > 0 && $existingCopyId !== $oldId) {
                    $newId = $existingCopyId;
                } else {
                    $newId = $execute ? $this->copyPlayerteamForLeague($row, $leagueId) : -1;
                    $copies++;
                }

                $mapping[] = ['old_id' => $oldId, 'league_id' => $leagueId, 'new_id' => $newId];
                if ($execute && $newId > 0) {
                    $remappedFk += $this->remapReferencesForLeague($oldId, $newId, $leagueId);
                }
            }
        }

        return [
            'processed' => $processed,
            'copies_created' => $copies,
            'remapped_fk_rows' => $remappedFk,
            'fallback_assigned' => $fallbackAssigned,
            'mapping' => $mapping,
        ];
    }

    public function finalizeConstraints(): void
    {
        if (! Schema::hasColumn('ffb_playerteam', 'playerteam_league_id')) {
            throw new \RuntimeException('playerteam_league_id column missing');
        }

        $nulls = Playerteam::query()->whereNull('playerteam_league_id')->count();
        if ($nulls > 0) {
            throw new \RuntimeException("Cannot finalize: {$nulls} rows still have NULL playerteam_league_id");
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            // SQLite test schema: skip ALTER MODIFY; uniqueness enforced in app + optional index.
            try {
                Schema::table('ffb_playerteam', function ($table) {
                    $table->unique(
                        ['playerteam_player_id', 'playerteam_team_id', 'playerteam_league_id'],
                        'playerteam_player_team_league_unique',
                    );
                });
            } catch (\Throwable) {
                // index may already exist
            }

            return;
        }

        DB::statement('ALTER TABLE ffb_playerteam MODIFY playerteam_league_id INT NOT NULL');

        $sm = Schema::getConnection()->getSchemaBuilder();
        $indexes = collect(DB::select('SHOW INDEX FROM ffb_playerteam'))->pluck('Key_name')->all();
        if (! in_array('playerteam_player_team_league_unique', $indexes, true)) {
            DB::statement(
                'ALTER TABLE ffb_playerteam ADD UNIQUE INDEX playerteam_player_team_league_unique (playerteam_player_id, playerteam_team_id, playerteam_league_id)'
            );
        }
        if (! in_array('playerteam_league_team_index', $indexes, true)) {
            DB::statement(
                'ALTER TABLE ffb_playerteam ADD INDEX playerteam_league_team_index (playerteam_league_id, playerteam_team_id)'
            );
        }

        unset($sm);
    }

    /**
     * @return array{ok: bool, issues: list<string>}
     */
    public function verifyConsistency(): array
    {
        $issues = [];

        $nulls = (int) Playerteam::query()->whereNull('playerteam_league_id')->count();
        if ($nulls > 0) {
            $issues[] = "{$nulls} playerteams with NULL league_id";
        }

        $dupPairs = $this->dbCleanup->duplicatePlayerteamGroups();
        // After league scoping, duplicate (player,team) without league is expected if different leagues —
        // check natural key duplicates instead:
        $naturalDupes = DB::table('ffb_playerteam')
            ->select('playerteam_player_id', 'playerteam_team_id', 'playerteam_league_id', DB::raw('COUNT(*) as c'))
            ->groupBy('playerteam_player_id', 'playerteam_team_id', 'playerteam_league_id')
            ->having('c', '>', 1)
            ->count();
        if ($naturalDupes > 0) {
            $issues[] = "{$naturalDupes} duplicate (player, team, league) groups";
        }

        foreach ([
            ['ffb_playerstats', 'playerstats_playerteam_id', 'playerstats_matchround_id', 'round'],
            ['ffb_playerprice', 'playerprice_playerteam_id', 'playerprice_matchround_id', 'round'],
        ] as [$table, $ptCol, $roundCol]) {
            $bad = DB::table($table.' as t')
                ->join('ffb_playerteam as pt', 'pt.playerteam_id', '=', "t.{$ptCol}")
                ->join('ffb_matchround as mr', 'mr.matchround_id', '=', "t.{$roundCol}")
                ->whereColumn('pt.playerteam_league_id', '!=', 'mr.matchround_league_id')
                ->count();
            if ($bad > 0) {
                $issues[] = "{$bad} {$table} rows point at wrong league playerteam";
            }
        }

        return ['ok' => $issues === [], 'issues' => $issues];
    }

    /**
     * @return list<int>
     */
    private function fallbackLeaguesForTeam(int $teamId, int $defaultLeagueId): array
    {
        $fromMatches = DB::table('ffb_match as m')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
            ->where(function ($q) use ($teamId) {
                $q->where('m.match_hometeam_id', $teamId)
                    ->orWhere('m.match_guestteam_id', $teamId);
            })
            ->distinct()
            ->pluck('mr.matchround_league_id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->values()
            ->all();

        if ($fromMatches !== []) {
            sort($fromMatches);

            // playerfid-only / unused roster: one league is enough (team's earliest game).
            return [$fromMatches[0]];
        }

        return [$defaultLeagueId > 0 ? $defaultLeagueId : 1];
    }

    private function copyPlayerteamForLeague(Playerteam $source, int $leagueId): int
    {
        return (int) DB::table('ffb_playerteam')->insertGetId([
            'playerteam_player_id' => (int) $source->playerteam_player_id,
            'playerteam_team_id' => (int) $source->playerteam_team_id,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => (string) ($source->playerteam_player_picture ?? ''),
            'playerteam_status' => (int) $source->playerteam_status,
            'playerteam_player_price' => (float) $source->playerteam_player_price,
            'playerteam_player_position' => (string) $source->playerteam_player_position,
            'playerteam_date_transfer' => $source->playerteam_date_transfer,
        ], 'playerteam_id');
    }

    private function remapReferencesForLeague(int $oldId, int $newId, int $leagueId): int
    {
        $count = 0;

        $statIds = DB::table('ffb_playerstats as ps')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'ps.playerstats_matchround_id')
            ->where('ps.playerstats_playerteam_id', $oldId)
            ->where('mr.matchround_league_id', $leagueId)
            ->pluck('ps.playerstats_id');
        if ($statIds->isNotEmpty()) {
            $count += DB::table('ffb_playerstats')->whereIn('playerstats_id', $statIds)->update([
                'playerstats_playerteam_id' => $newId,
            ]);
        }

        $priceIds = DB::table('ffb_playerprice as pp')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'pp.playerprice_matchround_id')
            ->where('pp.playerprice_playerteam_id', $oldId)
            ->where('mr.matchround_league_id', $leagueId)
            ->pluck('pp.playerprice_id');
        if ($priceIds->isNotEmpty()) {
            $count += DB::table('ffb_playerprice')->whereIn('playerprice_id', $priceIds)->update([
                'playerprice_playerteam_id' => $newId,
            ]);
        }

        $goalIds = DB::table('ffb_goal as g')
            ->join('ffb_match as m', 'm.match_id', '=', 'g.goal_match_id')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
            ->where('g.goal_playerteam_id', $oldId)
            ->where('mr.matchround_league_id', $leagueId)
            ->pluck('g.goal_id');
        if ($goalIds->isNotEmpty()) {
            $count += DB::table('ffb_goal')->whereIn('goal_id', $goalIds)->update([
                'goal_playerteam_id' => $newId,
            ]);
        }

        $psgoalIds = DB::table('ffb_psgoal as g')
            ->join('ffb_match as m', 'm.match_id', '=', 'g.psgoal_match_id')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
            ->where('g.psgoal_playerteam_id', $oldId)
            ->where('mr.matchround_league_id', $leagueId)
            ->pluck('g.psgoal_id');
        if ($psgoalIds->isNotEmpty()) {
            $count += DB::table('ffb_psgoal')->whereIn('psgoal_id', $psgoalIds)->update([
                'psgoal_playerteam_id' => $newId,
            ]);
        }

        if (Userteam::hasWideSlotColumns()) {
            foreach (Userteam::playerSlotColumns() as $column) {
                $userteamIds = DB::table('ffb_userteam as ut')
                    ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'ut.userteam_matchround_id')
                    ->where("ut.{$column}", $oldId)
                    ->where('mr.matchround_league_id', $leagueId)
                    ->pluck('ut.userteam_id');
                if ($userteamIds->isNotEmpty()) {
                    $count += DB::table('ffb_userteam')->whereIn('userteam_id', $userteamIds)->update([
                        $column => $newId,
                    ]);
                }
            }
        }

        if (Userteam::hasSlotTable()) {
            $slotIds = DB::table('ffb_userteam_slot as us')
                ->join('ffb_userteam as ut', 'ut.userteam_id', '=', 'us.userteam_slot_userteam_id')
                ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'ut.userteam_matchround_id')
                ->where('us.userteam_slot_playerteam_id', $oldId)
                ->where('mr.matchround_league_id', $leagueId)
                ->pluck('us.userteam_slot_id');
            if ($slotIds->isNotEmpty()) {
                $count += DB::table('ffb_userteam_slot')->whereIn('userteam_slot_id', $slotIds)->update([
                    'userteam_slot_playerteam_id' => $newId,
                ]);
            }
        }

        return $count;
    }

    private function remapAllReferences(int $fromId, int $toId): void
    {
        foreach ($this->inventory->referenceSources() as $source) {
            DB::table($source['table'])->where($source['column'], $fromId)->update([
                $source['column'] => $toId,
            ]);
        }
        if (Userteam::hasWideSlotColumns()) {
            foreach (Userteam::playerSlotColumns() as $column) {
                DB::table('ffb_userteam')->where($column, $fromId)->update([$column => $toId]);
            }
        }
        if (Userteam::hasSlotTable()) {
            DB::table('ffb_userteam_slot')->where('userteam_slot_playerteam_id', $fromId)->update([
                'userteam_slot_playerteam_id' => $toId,
            ]);
        }
    }
}
