<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Userteam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDbCleanupService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $duplicateGroups = $this->duplicatePlayerteamGroups();
        $playersWithoutTeam = $this->playersWithoutPlayerteam();
        $playersWithoutStats = $this->playersWithoutPlayerstats();

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'duplicate_playerteam_groups' => $duplicateGroups,
            'duplicate_playerteam_group_count' => count($duplicateGroups),
            'duplicate_playerteam_entry_count' => array_sum(array_map(
                static fn (array $group): int => count($group['entries']),
                $duplicateGroups
            )),
            'players_without_playerteam' => $playersWithoutTeam,
            'players_without_playerteam_count' => count($playersWithoutTeam),
            'players_without_playerstats' => $playersWithoutStats,
            'players_without_playerstats_count' => count($playersWithoutStats),
        ];
    }

    /**
     * Player/team pairs that appear more than once in ffb_playerteam.
     *
     * @return list<array{
     *     player_id: int,
     *     team_id: int,
     *     player_fname: string,
     *     player_lname: string,
     *     team_name: string,
     *     entry_count: int,
     *     entries: list<array<string, mixed>>
     * }>
     */
    public function duplicatePlayerteamGroups(): array
    {
        $withLeague = Schema::hasColumn('ffb_playerteam', 'playerteam_league_id');

        $query = DB::table('ffb_playerteam')
            ->select('playerteam_player_id', 'playerteam_team_id', DB::raw('COUNT(*) as entry_count'));
        if ($withLeague) {
            $query->addSelect('playerteam_league_id')
                ->groupBy('playerteam_player_id', 'playerteam_team_id', 'playerteam_league_id');
        } else {
            $query->groupBy('playerteam_player_id', 'playerteam_team_id');
        }

        $pairs = $query
            ->having('entry_count', '>', 1)
            ->orderBy('playerteam_team_id')
            ->orderBy('playerteam_player_id')
            ->get();

        if ($pairs->isEmpty()) {
            return [];
        }

        $rows = Playerteam::query()
            ->with(['player', 'team'])
            ->where(function ($query) use ($pairs, $withLeague) {
                foreach ($pairs as $pair) {
                    $query->orWhere(function ($inner) use ($pair, $withLeague) {
                        $inner->where('playerteam_player_id', (int) $pair->playerteam_player_id)
                            ->where('playerteam_team_id', (int) $pair->playerteam_team_id);
                        if ($withLeague) {
                            $inner->where('playerteam_league_id', (int) $pair->playerteam_league_id);
                        }
                    });
                }
            })
            ->orderBy('playerteam_team_id')
            ->orderBy('playerteam_player_id')
            ->orderBy('playerteam_id')
            ->get();

        /** @var array<string, array<string, mixed>> $groups */
        $groups = [];
        foreach ($pairs as $pair) {
            $key = (int) $pair->playerteam_player_id.'|'.(int) $pair->playerteam_team_id;
            if ($withLeague) {
                $key .= '|'.(int) $pair->playerteam_league_id;
            }
            $groups[$key] = [
                'player_id' => (int) $pair->playerteam_player_id,
                'team_id' => (int) $pair->playerteam_team_id,
                'league_id' => $withLeague ? (int) $pair->playerteam_league_id : null,
                'player_fname' => '',
                'player_lname' => '',
                'team_name' => '',
                'entry_count' => (int) $pair->entry_count,
                'entries' => [],
            ];
        }

        foreach ($rows as $row) {
            $key = (int) $row->playerteam_player_id.'|'.(int) $row->playerteam_team_id;
            if ($withLeague) {
                $key .= '|'.(int) $row->playerteam_league_id;
            }
            if (! isset($groups[$key])) {
                continue;
            }

            if ($groups[$key]['player_fname'] === '' && $row->player) {
                $groups[$key]['player_fname'] = (string) ($row->player->player_fname ?? '');
                $groups[$key]['player_lname'] = (string) ($row->player->player_lname ?? '');
            }
            if ($groups[$key]['team_name'] === '' && $row->team) {
                $groups[$key]['team_name'] = (string) ($row->team->team_name ?? '');
            }

            $transfer = strtotime((string) $row->playerteam_date_transfer);
            $groups[$key]['entries'][] = [
                'playerteam_id' => (int) $row->playerteam_id,
                'playerteam_status' => (int) $row->playerteam_status ? 1 : 0,
                'playerteam_player_position' => (string) $row->playerteam_player_position,
                'playerteam_date_transfer' => $transfer ? date('Y-m-d', $transfer) : '',
                'playerteam_player_picture' => trim((string) ($row->playerteam_player_picture ?? '')),
            ];
        }

        return array_values($groups);
    }

    /**
     * Players that have no row in ffb_playerteam.
     *
     * @return list<array<string, mixed>>
     */
    public function playersWithoutPlayerteam(): array
    {
        return Player::query()
            ->whereDoesntHave('playerteams')
            ->orderBy('player_lname')
            ->orderBy('player_fname')
            ->orderBy('player_id')
            ->get()
            ->map(fn (Player $player): array => $this->mapPlayerSummary($player))
            ->all();
    }

    /**
     * Players with no ffb_playerstats via any playerteam, and not used in any
     * ffb_userteam lineup slot (slots store playerteam_id).
     *
     * @return list<array<string, mixed>>
     */
    public function playersWithoutPlayerstats(): array
    {
        $usedPlayerteamIds = $this->playerteamIdsUsedInUserteams();

        $query = Player::query()
            ->whereDoesntHave('playerteams.stats');

        if ($usedPlayerteamIds !== []) {
            $query->whereDoesntHave('playerteams', function ($builder) use ($usedPlayerteamIds) {
                $builder->whereIn('playerteam_id', $usedPlayerteamIds);
            });
        }

        return $query
            ->orderBy('player_lname')
            ->orderBy('player_fname')
            ->orderBy('player_id')
            ->get()
            ->map(fn (Player $player): array => $this->mapPlayerSummary($player))
            ->all();
    }

    /**
     * @return list<int>
     */
    private function playerteamIdsUsedInUserteams(): array
    {
        return Userteam::playerteamIdsUsedInLineups();
    }

    /**
     * @return array{
     *     player_id: int,
     *     player_fname: string,
     *     player_lname: string,
     *     player_nationality: string,
     *     player_status: int,
     *     player_foreign_id: string
     * }
     */
    private function mapPlayerSummary(Player $player): array
    {
        return [
            'player_id' => (int) $player->player_id,
            'player_fname' => (string) ($player->player_fname ?? ''),
            'player_lname' => (string) ($player->player_lname ?? ''),
            'player_nationality' => strtoupper(trim((string) ($player->player_nationality ?? ''))),
            'player_status' => (int) $player->player_status ? 1 : 0,
            'player_foreign_id' => (string) ($player->player_foreign_id ?? ''),
        ];
    }
}
