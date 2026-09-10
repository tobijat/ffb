<?php

namespace App\Services;

use App\Models\Playerteam;
use App\Models\Userteam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Read-only inventory for the playerteam → league_id migration (Phase 0).
 */
class PlayerteamLeagueInventoryService
{
    public function __construct(
        private readonly AdminDbCleanupService $dbCleanup,
    ) {}

    /**
     * Soft-FK tables/columns that store playerteam_id.
     *
     * @return list<array{table: string, column: string, via: string}>
     */
    public function referenceSources(): array
    {
        return [
            ['table' => 'ffb_playerstats', 'column' => 'playerstats_playerteam_id', 'via' => 'stats'],
            ['table' => 'ffb_playerprice', 'column' => 'playerprice_playerteam_id', 'via' => 'prices'],
            ['table' => 'ffb_goal', 'column' => 'goal_playerteam_id', 'via' => 'goals'],
            ['table' => 'ffb_psgoal', 'column' => 'psgoal_playerteam_id', 'via' => 'psgoals'],
            ['table' => 'ffb_playerfid', 'column' => 'playerfid_playerteam_id', 'via' => 'playerfid'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function report(): array
    {
        $hasLeagueColumn = Schema::hasColumn('ffb_playerteam', 'playerteam_league_id');
        $total = (int) Playerteam::query()->count();
        $referencedIds = $this->referencedPlayerteamIds();
        $allIds = Playerteam::query()->pluck('playerteam_id')->map(fn ($id): int => (int) $id)->all();
        $unreferencedIds = array_values(array_diff($allIds, $referencedIds));
        sort($unreferencedIds);

        $leagueMembership = $this->leagueMembershipByPlayerteam();
        $leagueCountHistogram = [];
        $multiLeague = [];
        foreach ($leagueMembership as $playerteamId => $leagueIds) {
            $count = count($leagueIds);
            $leagueCountHistogram[$count] = ($leagueCountHistogram[$count] ?? 0) + 1;
            if ($count > 1) {
                $multiLeague[] = [
                    'playerteam_id' => $playerteamId,
                    'league_count' => $count,
                    'league_ids' => $leagueIds,
                ];
            }
        }
        ksort($leagueCountHistogram);
        usort(
            $multiLeague,
            static fn (array $a, array $b): int => $b['league_count'] <=> $a['league_count']
                ?: $a['playerteam_id'] <=> $b['playerteam_id']
        );

        $withNoDerivedLeague = [];
        foreach ($allIds as $id) {
            if (! isset($leagueMembership[$id]) || $leagueMembership[$id] === []) {
                $withNoDerivedLeague[] = $id;
            }
        }

        $noLeagueBreakdown = $this->classifyPlayerteamIdsWithoutLeague($withNoDerivedLeague, $referencedIds);

        $duplicates = $this->dbCleanup->duplicatePlayerteamGroups();
        $pictureReport = $this->pictureInventory();

        $referenceCounts = [];
        foreach ($this->referenceSources() as $source) {
            $referenceCounts[$source['via']] = (int) DB::table($source['table'])
                ->where($source['column'], '>', 0)
                ->distinct()
                ->count($source['column']);
        }
        $referenceCounts['userteam_slots'] = count($this->playerteamIdsUsedInUserteams());

        return [
            'generated_at' => now()->toIso8601String(),
            'schema' => [
                'has_playerteam_league_id' => $hasLeagueColumn,
            ],
            'counts' => [
                'playerteam_total' => $total,
                'playerteam_referenced' => count($referencedIds),
                'playerteam_unreferenced' => count($unreferencedIds),
                'playerteam_with_derived_leagues' => count($leagueMembership),
                'playerteam_with_no_derived_league' => count($withNoDerivedLeague),
                'playerteam_in_multiple_leagues' => count($multiLeague),
                'duplicate_player_team_groups' => count($duplicates),
                'duplicate_player_team_entries' => array_sum(array_map(
                    static fn (array $group): int => count($group['entries']),
                    $duplicates,
                )),
            ],
            'reference_distinct_playerteam_ids' => $referenceCounts,
            'league_count_histogram' => $leagueCountHistogram,
            'unreferenced_playerteam_ids' => $unreferencedIds,
            'playerteam_ids_with_no_derived_league' => $withNoDerivedLeague,
            'no_derived_league_breakdown' => $noLeagueBreakdown,
            'multi_league_playerteams_sample' => array_slice($multiLeague, 0, 50),
            'multi_league_playerteams_total' => count($multiLeague),
            'duplicate_player_team_groups' => $duplicates,
            'pictures' => $pictureReport,
            'notes' => [
                'Derived leagues = union of games from playerstats, userteam slots, playerprice, goals, and psgoals via matchround/match.',
                'Unreferenced rows are safe delete candidates for Phase 1 (ffb_player rows are kept).',
                'Duplicate (player, team) groups must be resolved before backfill “keep one PK” logic.',
            ],
        ];
    }

    /**
     * @return list<int>
     */
    public function unreferencedPlayerteamIds(): array
    {
        $referenced = $this->referencedPlayerteamIds();
        $all = Playerteam::query()->pluck('playerteam_id')->map(fn ($id): int => (int) $id)->all();
        $unreferenced = array_values(array_diff($all, $referenced));
        sort($unreferenced);

        return $unreferenced;
    }

    /**
     * @return list<int>
     */
    public function referencedPlayerteamIds(): array
    {
        $ids = [];

        foreach ($this->referenceSources() as $source) {
            foreach (DB::table($source['table'])->where($source['column'], '>', 0)->distinct()->pluck($source['column']) as $id) {
                $id = (int) $id;
                if ($id > 0) {
                    $ids[$id] = $id;
                }
            }
        }

        foreach ($this->playerteamIdsUsedInUserteams() as $id) {
            $ids[$id] = $id;
        }

        return array_values($ids);
    }

    /**
     * @return array<int, list<int>> playerteam_id => sorted unique league_ids
     */
    public function leagueMembershipByPlayerteam(): array
    {
        /** @var array<int, array<int, int>> $map */
        $map = [];

        $add = static function (array &$map, int $playerteamId, int $leagueId): void {
            if ($playerteamId <= 0 || $leagueId <= 0) {
                return;
            }
            $map[$playerteamId][$leagueId] = $leagueId;
        };

        foreach (DB::table('ffb_playerstats as ps')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'ps.playerstats_matchround_id')
            ->where('ps.playerstats_playerteam_id', '>', 0)
            ->select('ps.playerstats_playerteam_id', 'mr.matchround_league_id')
            ->distinct()
            ->cursor() as $row) {
            $add($map, (int) $row->playerstats_playerteam_id, (int) $row->matchround_league_id);
        }

        foreach (DB::table('ffb_playerprice as pp')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'pp.playerprice_matchround_id')
            ->where('pp.playerprice_playerteam_id', '>', 0)
            ->select('pp.playerprice_playerteam_id', 'mr.matchround_league_id')
            ->distinct()
            ->cursor() as $row) {
            $add($map, (int) $row->playerprice_playerteam_id, (int) $row->matchround_league_id);
        }

        foreach (DB::table('ffb_goal as g')
            ->join('ffb_match as m', 'm.match_id', '=', 'g.goal_match_id')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
            ->where('g.goal_playerteam_id', '>', 0)
            ->select('g.goal_playerteam_id', 'mr.matchround_league_id')
            ->distinct()
            ->cursor() as $row) {
            $add($map, (int) $row->goal_playerteam_id, (int) $row->matchround_league_id);
        }

        foreach (DB::table('ffb_psgoal as g')
            ->join('ffb_match as m', 'm.match_id', '=', 'g.psgoal_match_id')
            ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
            ->where('g.psgoal_playerteam_id', '>', 0)
            ->select('g.psgoal_playerteam_id', 'mr.matchround_league_id')
            ->distinct()
            ->cursor() as $row) {
            $add($map, (int) $row->psgoal_playerteam_id, (int) $row->matchround_league_id);
        }

        if (Userteam::hasWideSlotColumns()) {
            foreach (Userteam::playerSlotColumns() as $column) {
                foreach (DB::table('ffb_userteam as ut')
                    ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'ut.userteam_matchround_id')
                    ->where("ut.{$column}", '>', 0)
                    ->select("ut.{$column} as playerteam_id", 'mr.matchround_league_id')
                    ->distinct()
                    ->cursor() as $row) {
                    $add($map, (int) $row->playerteam_id, (int) $row->matchround_league_id);
                }
            }
        }

        if (Userteam::hasSlotTable()) {
            foreach (DB::table('ffb_userteam_slot as us')
                ->join('ffb_userteam as ut', 'ut.userteam_id', '=', 'us.userteam_slot_userteam_id')
                ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'ut.userteam_matchround_id')
                ->where('us.userteam_slot_playerteam_id', '>', 0)
                ->select('us.userteam_slot_playerteam_id as playerteam_id', 'mr.matchround_league_id')
                ->distinct()
                ->cursor() as $row) {
                $add($map, (int) $row->playerteam_id, (int) $row->matchround_league_id);
            }
        }

        $normalized = [];
        foreach ($map as $playerteamId => $leagueIds) {
            $ids = array_values($leagueIds);
            sort($ids);
            $normalized[$playerteamId] = $ids;
        }

        return $normalized;
    }

    /**
     * @return array<string, mixed>
     */
    public function pictureInventory(): array
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');
        $playersDir = $base.DIRECTORY_SEPARATOR.'players';

        $rows = Playerteam::query()
            ->select([
                'playerteam_id',
                'playerteam_player_id',
                'playerteam_team_id',
                'playerteam_player_picture',
            ])
            ->get();

        $flagged = 0;
        $legacyFileExists = 0;
        $legacyFileMissing = 0;
        $targetPathSeen = [];
        $targetCollisions = 0;

        foreach ($rows as $row) {
            $picture = trim((string) ($row->playerteam_player_picture ?? ''));
            if ($picture === '') {
                continue;
            }
            $flagged++;

            $teamId = (int) $row->playerteam_team_id;
            $playerId = (int) $row->playerteam_player_id;
            $playerteamId = (int) $row->playerteam_id;
            $legacyPath = $playersDir.DIRECTORY_SEPARATOR.$teamId.DIRECTORY_SEPARATOR.$playerteamId.'.jpg';
            if (is_file($legacyPath)) {
                $legacyFileExists++;
            } else {
                $legacyFileMissing++;
            }

            $targetKey = $teamId.'|'.$playerId;
            if (isset($targetPathSeen[$targetKey]) && $targetPathSeen[$targetKey] !== $playerteamId) {
                $targetCollisions++;
            } else {
                $targetPathSeen[$targetKey] = $playerteamId;
            }
        }

        return [
            'rows_with_picture_flag' => $flagged,
            'legacy_files_exist' => $legacyFileExists,
            'legacy_files_missing' => $legacyFileMissing,
            'unique_team_player_picture_targets' => count($targetPathSeen),
            'extra_flags_sharing_same_team_player_target' => $targetCollisions,
            'proposed_path_pattern' => '/images/ffb/players/{team_id}/{team_id}-{player_id}.jpg',
        ];
    }

    /**
     * @return list<string>
     */
    public function backupTableNames(): array
    {
        return [
            'ffb_playerteam',
            'ffb_userteam',
            'ffb_userteam_slot',
            'ffb_playerstats',
            'ffb_playerprice',
            'ffb_goal',
            'ffb_psgoal',
            'ffb_playerfid',
            'ffb_match',
            'ffb_matchround',
            'ffb_league',
        ];
    }

    /**
     * @param  list<int>  $playerteamIds
     * @param  list<int>  $referencedIds
     * @return array{
     *     unreferenced: int,
     *     playerfid_only: int,
     *     other_references_without_league: int,
     *     playerfid_only_sample: list<int>
     * }
     */
    public function classifyPlayerteamIdsWithoutLeague(array $playerteamIds, array $referencedIds): array
    {
        $referencedLookup = array_fill_keys($referencedIds, true);
        $fidOnlyLookup = array_fill_keys($this->playerteamIdsReferencedOnlyByPlayerfid(), true);

        $unreferenced = 0;
        $playerfidOnly = 0;
        $other = 0;
        $fidSample = [];

        foreach ($playerteamIds as $id) {
            if (! isset($referencedLookup[$id])) {
                $unreferenced++;

                continue;
            }

            if (isset($fidOnlyLookup[$id])) {
                $playerfidOnly++;
                if (count($fidSample) < 30) {
                    $fidSample[] = $id;
                }
            } else {
                $other++;
            }
        }

        return [
            'unreferenced' => $unreferenced,
            'playerfid_only' => $playerfidOnly,
            'other_references_without_league' => $other,
            'playerfid_only_sample' => $fidSample,
        ];
    }

    /**
     * @return list<int>
     */
    public function playerteamIdsReferencedOnlyByPlayerfid(): array
    {
        $fidIds = [];
        foreach (DB::table('ffb_playerfid')->where('playerfid_playerteam_id', '>', 0)->distinct()->pluck('playerfid_playerteam_id') as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $fidIds[$id] = $id;
            }
        }

        foreach ($this->referenceSources() as $source) {
            if ($source['via'] === 'playerfid') {
                continue;
            }
            foreach (DB::table($source['table'])->where($source['column'], '>', 0)->distinct()->pluck($source['column']) as $id) {
                unset($fidIds[(int) $id]);
            }
        }

        foreach ($this->playerteamIdsUsedInUserteams() as $id) {
            unset($fidIds[$id]);
        }

        $ids = array_values($fidIds);
        sort($ids);

        return $ids;
    }

    /**
     * @return list<int>
     */
    private function playerteamIdsUsedInUserteams(): array
    {
        return Userteam::playerteamIdsUsedInLineups();
    }
}
