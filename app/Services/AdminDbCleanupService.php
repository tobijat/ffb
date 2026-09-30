<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Userteam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

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

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'sections' => $this->sectionDefinitions(),
            'run_url' => route('admin.dbCleanup.run'),
        ];
    }

    /**
     * @return list<array{key: string, title: string, hint: string}>
     */
    public function sectionDefinitions(): array
    {
        return [
            [
                'key' => 'duplicate-playerteams',
                'title' => 'Doppelte Kader-Einträge',
                'hint' => 'Spieler mit mehr als einem ffb_playerteam-Eintrag für dasselbe Team und dieselbe Liga (playerteam_player_id, playerteam_team_id, playerteam_league_id).',
            ],
            [
                'key' => 'players-without-playerteam',
                'title' => 'Spieler ohne Kader-Zuordnung',
                'hint' => 'Einträge in ffb_player, die in keinem ffb_playerteam vorkommen.',
            ],
            [
                'key' => 'players-without-playerstats',
                'title' => 'Spieler ohne Spielstatistiken',
                'hint' => 'Spieler ohne Einträge in ffb_playerstats und ohne Verwendung in ffb_userteam (über playerteam_id-Slots; inkl. Spieler ohne Team).',
            ],
            [
                'key' => 'orphan-playerteams',
                'title' => 'Verwaiste Kader-Einträge',
                'hint' => 'ffb_playerteam-Zeilen, deren playerteam_player_id auf keinen existierenden Spieler zeigt.',
            ],
            [
                'key' => 'orphan-lineup-slots',
                'title' => 'Verwaiste Userteam- und Top/Flop-Slots',
                'hint' => 'Slots in ffb_userteam_slot und ffb_extremeteam_slot, die auf eine nicht existierende playerteam_id zeigen.',
            ],
        ];
    }

    /**
     * @return array{
     *     ok: true,
     *     task: string,
     *     clean: bool,
     *     count: int,
     *     summary: string,
     *     html: string
     * }
     */
    public function runTask(string $task): array
    {
        return match ($task) {
            'duplicate-playerteams' => $this->taskDuplicatePlayerteams(),
            'players-without-playerteam' => $this->taskPlayersWithoutPlayerteam(),
            'players-without-playerstats' => $this->taskPlayersWithoutPlayerstats(),
            'orphan-playerteams' => $this->taskOrphanPlayerteams(),
            'orphan-lineup-slots' => $this->taskOrphanLineupSlots(),
            default => throw new InvalidArgumentException('Unbekannte Cleanup-Aufgabe.'),
        };
    }

    /**
     * @return array{ok: true, task: string, clean: bool, count: int, summary: string, html: string}
     */
    private function taskDuplicatePlayerteams(): array
    {
        $groups = $this->duplicatePlayerteamGroups();
        $entryCount = array_sum(array_map(
            static fn (array $group): int => count($group['entries']),
            $groups
        ));
        $count = count($groups);
        $clean = $count === 0;
        $summary = $clean
            ? 'Keine doppelten Spieler–Team-Zuordnungen gefunden.'
            : $count.' '.($count === 1 ? 'Doppelgruppe' : 'Doppelgruppen').' · '.$entryCount.' Einträge insgesamt';

        return [
            'ok' => true,
            'task' => 'duplicate-playerteams',
            'clean' => $clean,
            'count' => $count,
            'summary' => $summary,
            'html' => view('admin.partials.db-cleanup-duplicate-groups', [
                'groups' => $groups,
                'groupCount' => $count,
                'entryCount' => $entryCount,
                'summary' => $summary,
                'positions' => ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'],
            ])->render(),
        ];
    }

    /**
     * @return array{ok: true, task: string, clean: bool, count: int, summary: string, html: string}
     */
    private function taskPlayersWithoutPlayerteam(): array
    {
        $players = $this->playersWithoutPlayerteam();
        $count = count($players);
        $clean = $count === 0;
        $summary = $clean
            ? 'Alle Spieler sind mindestens einem Team zugeordnet.'
            : $count.' Spieler';

        return [
            'ok' => true,
            'task' => 'players-without-playerteam',
            'clean' => $clean,
            'count' => $count,
            'summary' => $summary,
            'html' => view('admin.partials.db-cleanup-player-list', [
                'players' => $players,
                'count' => $count,
                'summary' => $summary,
                'emptyMessage' => 'Alle Spieler sind mindestens einem Team zugeordnet.',
            ])->render(),
        ];
    }

    /**
     * @return array{ok: true, task: string, clean: bool, count: int, summary: string, html: string}
     */
    private function taskPlayersWithoutPlayerstats(): array
    {
        $players = $this->playersWithoutPlayerstats();
        $count = count($players);
        $clean = $count === 0;
        $summary = $clean
            ? 'Jeder Spieler hat mindestens eine Spielstatistik oder ist in einem Userteam eingesetzt.'
            : $count.' Spieler';

        return [
            'ok' => true,
            'task' => 'players-without-playerstats',
            'clean' => $clean,
            'count' => $count,
            'summary' => $summary,
            'html' => view('admin.partials.db-cleanup-player-list', [
                'players' => $players,
                'count' => $count,
                'summary' => $summary,
                'emptyMessage' => 'Jeder Spieler hat mindestens eine Spielstatistik oder ist in einem Userteam eingesetzt.',
            ])->render(),
        ];
    }

    /**
     * @return array{ok: true, task: string, clean: bool, count: int, summary: string, html: string}
     */
    private function taskOrphanPlayerteams(): array
    {
        $rows = $this->orphanPlayerteams();
        $count = count($rows);
        $clean = $count === 0;
        $summary = $clean
            ? 'Keine Kader-Einträge mit fehlendem Spieler gefunden.'
            : $count.' '.($count === 1 ? 'Kader-Eintrag' : 'Kader-Einträge').' mit fehlendem Spieler';

        return [
            'ok' => true,
            'task' => 'orphan-playerteams',
            'clean' => $clean,
            'count' => $count,
            'summary' => $summary,
            'html' => view('admin.partials.db-cleanup-orphan-playerteams', [
                'rows' => $rows,
                'count' => $count,
                'summary' => $summary,
                'positions' => ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'],
            ])->render(),
        ];
    }

    /**
     * @return array{ok: true, task: string, clean: bool, count: int, summary: string, html: string}
     */
    private function taskOrphanLineupSlots(): array
    {
        $userteamSlots = $this->orphanUserteamSlots();
        $extremeSlots = $this->orphanExtremeteamSlots();
        $count = count($userteamSlots) + count($extremeSlots);
        $clean = $count === 0;
        $summary = $clean
            ? 'Keine verwaisten Userteam- oder Top/Flop-Slots gefunden.'
            : count($userteamSlots).' Userteam-Slot(s), '.count($extremeSlots).' Top/Flop-Slot(s)';

        return [
            'ok' => true,
            'task' => 'orphan-lineup-slots',
            'clean' => $clean,
            'count' => $count,
            'summary' => $summary,
            'html' => view('admin.partials.db-cleanup-orphan-lineup-slots', [
                'userteamSlots' => $userteamSlots,
                'extremeSlots' => $extremeSlots,
                'summary' => $summary,
            ])->render(),
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
     * @return list<array<string, mixed>>
     */
    public function playersWithoutPlayerstats(): array
    {
        $usedPlayerteamIds = Userteam::playerteamIdsUsedInLineups();

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
     * @return list<array<string, mixed>>
     */
    public function orphanPlayerteams(): array
    {
        return Playerteam::query()
            ->with('team')
            ->whereDoesntHave('player')
            ->orderBy('playerteam_id')
            ->get()
            ->map(function (Playerteam $row): array {
                $transfer = strtotime((string) $row->playerteam_date_transfer);

                return [
                    'playerteam_id' => (int) $row->playerteam_id,
                    'playerteam_player_id' => (int) $row->playerteam_player_id,
                    'playerteam_team_id' => (int) $row->playerteam_team_id,
                    'playerteam_league_id' => (int) ($row->playerteam_league_id ?? 0),
                    'team_name' => (string) ($row->team?->team_name ?? ''),
                    'playerteam_status' => (int) $row->playerteam_status ? 1 : 0,
                    'playerteam_player_position' => (string) $row->playerteam_player_position,
                    'playerteam_date_transfer' => $transfer ? date('Y-m-d', $transfer) : '',
                ];
            })
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function orphanUserteamSlots(): array
    {
        if (! Schema::hasTable('ffb_userteam_slot')) {
            return [];
        }

        return DB::table('ffb_userteam_slot as slot')
            ->leftJoin('ffb_playerteam as pt', 'pt.playerteam_id', '=', 'slot.userteam_slot_playerteam_id')
            ->leftJoin('ffb_userteam as ut', 'ut.userteam_id', '=', 'slot.userteam_slot_userteam_id')
            ->where('slot.userteam_slot_playerteam_id', '>', 0)
            ->whereNull('pt.playerteam_id')
            ->orderBy('slot.userteam_slot_userteam_id')
            ->orderBy('slot.userteam_slot_slot')
            ->get([
                'slot.userteam_slot_id',
                'slot.userteam_slot_userteam_id',
                'slot.userteam_slot_slot',
                'slot.userteam_slot_playerteam_id',
                'ut.userteam_user_id',
                'ut.userteam_matchround_id',
            ])
            ->map(static fn ($row): array => [
                'userteam_slot_id' => (int) $row->userteam_slot_id,
                'userteam_id' => (int) $row->userteam_slot_userteam_id,
                'user_id' => (int) ($row->userteam_user_id ?? 0),
                'matchround_id' => (int) ($row->userteam_matchround_id ?? 0),
                'slot' => (int) $row->userteam_slot_slot,
                'playerteam_id' => (int) $row->userteam_slot_playerteam_id,
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function orphanExtremeteamSlots(): array
    {
        if (! Schema::hasTable('ffb_extremeteam_slot') || ! Schema::hasTable('ffb_extremeteam')) {
            return [];
        }

        return DB::table('ffb_extremeteam_slot as slot')
            ->leftJoin('ffb_playerteam as pt', 'pt.playerteam_id', '=', 'slot.extremeteam_slot_playerteam_id')
            ->leftJoin('ffb_extremeteam as et', 'et.extremeteam_id', '=', 'slot.extremeteam_slot_extremeteam_id')
            ->leftJoin('ffb_matchround as mr', 'mr.matchround_id', '=', 'et.extremeteam_matchround_id')
            ->where('slot.extremeteam_slot_playerteam_id', '>', 0)
            ->whereNull('pt.playerteam_id')
            ->orderBy('slot.extremeteam_slot_extremeteam_id')
            ->orderBy('slot.extremeteam_slot_slot')
            ->get([
                'slot.extremeteam_slot_id',
                'slot.extremeteam_slot_extremeteam_id',
                'slot.extremeteam_slot_slot',
                'slot.extremeteam_slot_playerteam_id',
                'et.extremeteam_top_or_flop',
                'et.extremeteam_matchround_id',
                'mr.matchround_title',
            ])
            ->map(static fn ($row): array => [
                'extremeteam_slot_id' => (int) $row->extremeteam_slot_id,
                'extremeteam_id' => (int) $row->extremeteam_slot_extremeteam_id,
                'type' => strtoupper((string) ($row->extremeteam_top_or_flop ?? '')),
                'matchround_id' => (int) ($row->extremeteam_matchround_id ?? 0),
                'matchround_title' => (string) ($row->matchround_title ?? ''),
                'slot' => (int) $row->extremeteam_slot_slot,
                'playerteam_id' => (int) $row->extremeteam_slot_playerteam_id,
            ])
            ->all();
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
