<?php

namespace App\Services;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\UserAward;
use App\Models\UserAwardDefines;
use App\Models\UserAwardFinished;
use App\Models\Userscore;
use App\Models\Userteam;
use App\Models\WebUser;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAwardsService
{
    private int $localUserUpdates = 0;

    /** @var list<array{uid: int, aid: int, usernick: string}> */
    private array $goalFinishers = [];

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
            ...$shell,
            'groups' => $this->groups(),
        ];
    }

    /**
     * @return list<array{user_award_id: int, user_award_name: string}>
     */
    public function groups(): array
    {
        return UserAward::query()
            ->orderBy('user_award_name')
            ->get(['user_award_id', 'user_award_name'])
            ->map(static fn (UserAward $g): array => [
                'user_award_id' => (int) $g->user_award_id,
                'user_award_name' => (string) $g->user_award_name,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function createGroup(array $input): array
    {
        $name = trim((string) ($input['newgroupawardname'] ?? ''));
        if ($name === '') {
            return ['ok' => false, 'errors' => ['Kein Gruppenname angegeben, Abbruch.']];
        }

        if (UserAward::query()->where('user_award_name', $name)->exists()) {
            return ['ok' => false, 'errors' => ["Gruppe '$name' existiert bereits. Abbruch."]];
        }

        UserAward::query()->create([
            'user_award_name' => $name,
            'user_award_image' => '',
            'user_award_description' => '',
            'user_award_sortflag' => 0,
        ]);

        return ['ok' => true, 'message' => "Neue Gruppe: '$name' erfolgreich angelegt."];
    }

    /**
     * @return array{userAward: array<string, mixed>, userAwardDefines: list<array<string, mixed>>, userAwardCounts: int}|null
     */
    public function groupDetails(int $groupId): ?array
    {
        $group = UserAward::query()->find($groupId);
        if (! $group) {
            return null;
        }

        $defines = UserAwardDefines::query()
            ->where('user_award_defines_award_id', $groupId)
            ->orderBy('user_award_defines_rank')
            ->get()
            ->map(static fn (UserAwardDefines $d): array => [
                'id' => (int) $d->user_award_defines_id,
                'rank' => (string) ((int) $d->user_award_defines_rank ?: ' '),
                'name' => (string) ($d->user_award_defines_rank_name ?: ' '),
                'aim' => (string) ($d->user_award_defines_aim ?: ' '),
                'dbtable' => (string) ($d->user_award_defines_aim_dbtable ?: ' '),
                'operator' => (string) ($d->user_award_defines_aim_operator ?: ' '),
                'count' => (int) ($d->user_award_defines_aim_count ?: 0),
                'auto' => (int) ($d->user_award_defines_aim_automatic ? 1 : 0),
                'function_name' => (string) ($d->user_award_defines_aim_function_name ?: ''),
                'image' => (string) ($d->user_award_defines_image ?: ' '),
                'descr' => (string) ($d->user_award_defines_description ?: ' '),
            ])
            ->values()
            ->all();

        return [
            'userAward' => [
                'id' => (int) $group->user_award_id,
                'name' => (string) $group->user_award_name,
                'description' => (string) ($group->user_award_description ?: ' '),
                'image' => (string) ($group->user_award_image ?: ' '),
            ],
            'userAwardDefines' => $defines,
            'userAwardCounts' => count($defines),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, status: int, text: string}
     */
    public function updateGroup(array $input): array
    {
        $groupId = (int) ($input['award_group_id'] ?? 0);
        $group = UserAward::query()->find($groupId);
        if (! $group) {
            return ['ok' => false, 'status' => 500, 'text' => 'Auszeichnungsgruppe nicht gefunden.'];
        }

        $group->user_award_description = trim((string) ($input['award_group_description'] ?? ''));
        $group->user_award_image = trim((string) ($input['award_group_image'] ?? ''));
        $group->save();

        return ['ok' => true, 'status' => 201, 'text' => 'Auszeichnungsgruppe aktualisiert.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, status: int, text: string}
     */
    public function createDefine(array $input): array
    {
        $name = trim((string) ($input['award_name'] ?? ''));
        $groupId = (int) ($input['group_award_id'] ?? 0);
        $rank = trim((string) ($input['award_rank'] ?? ''));
        $aim = trim((string) ($input['award_aim'] ?? ''));
        $count = (int) trim((string) ($input['award_count'] ?? '0'));
        $auto = (int) ($input['award_auto'] ?? 0) === 1 ? 1 : 0;
        $dbTable = trim((string) ($input['award_dbtable'] ?? ''));
        $operator = trim((string) ($input['award_operator'] ?? ''));
        $descr = trim((string) ($input['award_description'] ?? ''));
        $image = trim((string) ($input['award_image'] ?? ''));
        $functionName = trim((string) ($input['award_function_name'] ?? ''));

        if ($name !== '' && UserAwardDefines::query()
            ->where('user_award_defines_award_id', $groupId)
            ->where('user_award_defines_rank_name', $name)
            ->exists()) {
            return [
                'ok' => false,
                'status' => 500,
                'text' => "Auszeichnung '$name' existiert bereits.",
            ];
        }

        if ($name === '' || $groupId <= 0 || $rank === '' || $aim === '') {
            return [
                'ok' => false,
                'status' => 500,
                'text' => "Auszeichnung '$name' konnte nicht angelegt werden fehlerhafte (leere) Eingabe.",
            ];
        }

        if ($auto === 1 && ($dbTable === '' || $operator === '')) {
            return [
                'ok' => false,
                'status' => 500,
                'text' => "Auszeichnung '$name' (Auto): DB Table und Operator sind erforderlich.",
            ];
        }

        UserAwardDefines::query()->create([
            'user_award_defines_award_id' => $groupId,
            'user_award_defines_rank' => (int) $rank,
            'user_award_defines_rank_name' => $name,
            'user_award_defines_aim' => $aim,
            'user_award_defines_aim_count' => $count,
            'user_award_defines_aim_automatic' => $auto,
            'user_award_defines_aim_dbtable' => $dbTable,
            'user_award_defines_aim_operator' => $operator,
            'user_award_defines_aim_function_name' => $functionName,
            'user_award_defines_description' => $descr,
            'user_award_defines_image' => $image,
        ]);

        return ['ok' => true, 'status' => 201, 'text' => "Auszeichnung '$name' angelegt."];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, status: int, text: string}
     */
    public function updateDefine(array $input): array
    {
        $name = trim((string) ($input['award_name'] ?? ''));
        $awardId = (int) ($input['award_defines_id'] ?? 0);
        $rank = trim((string) ($input['award_rank'] ?? ''));
        $aim = trim((string) ($input['award_aim'] ?? ''));
        $count = (int) trim((string) ($input['award_count'] ?? '0'));
        $auto = (int) ($input['award_auto'] ?? 0) === 1 ? 1 : 0;
        $dbTable = trim((string) ($input['award_dbtable'] ?? ''));
        $operator = trim((string) ($input['award_operator'] ?? ''));
        $descr = trim((string) ($input['award_description'] ?? ''));
        $image = trim((string) ($input['award_image'] ?? ''));
        $functionName = array_key_exists('award_function_name', $input)
            ? trim((string) $input['award_function_name'])
            : null;

        if ($name === '' || $awardId <= 0 || $rank === '' || $aim === '') {
            return [
                'ok' => false,
                'status' => 500,
                'text' => "Auszeichnung '$name' konnte nicht aktualisiert werden fehlerhafte (leere) Eingabe.",
            ];
        }

        $define = UserAwardDefines::query()->find($awardId);
        if (! $define) {
            return ['ok' => false, 'status' => 500, 'text' => "Auszeichnung '$name' nicht gefunden."];
        }

        $define->user_award_defines_rank = (int) $rank;
        $define->user_award_defines_rank_name = $name;
        $define->user_award_defines_aim = $aim;
        $define->user_award_defines_aim_count = $count;
        $define->user_award_defines_aim_automatic = $auto;
        $define->user_award_defines_aim_dbtable = $dbTable;
        $define->user_award_defines_aim_operator = $operator;
        $define->user_award_defines_description = $descr;
        $define->user_award_defines_image = $image;
        if ($functionName !== null) {
            $define->user_award_defines_aim_function_name = $functionName;
        }
        $define->save();

        return ['ok' => true, 'status' => 201, 'text' => "Auszeichnung '$name' aktualisiert."];
    }

    /**
     * @return array{numWinners: int, awardInfos?: array<string, mixed>, awardWinners: list<array{nick: string, date: string, fid: int}>}
     */
    public function finishedForDefine(int $defineId): array
    {
        if ($defineId <= 0) {
            return ['numWinners' => 0, 'awardWinners' => []];
        }

        $define = UserAwardDefines::query()->with('award')->find($defineId);
        $finished = UserAwardFinished::query()
            ->with('user')
            ->where('user_award_finished_award_defines_id', $defineId)
            ->orderBy('user_award_finished_date')
            ->get();

        $winners = [];
        foreach ($finished as $row) {
            $winners[] = [
                'nick' => (string) ($row->user?->user_nickname ?? ''),
                'date' => (string) ($row->user_award_finished_date ?? ''),
                'fid' => (int) $row->user_award_finished_id,
            ];
        }

        $payload = [
            'numWinners' => count($winners),
            'awardWinners' => $winners,
        ];

        if ($define) {
            $payload['awardInfos'] = [
                'award_descr' => (string) ($define->user_award_defines_description ?: ' '),
                'award_image' => (string) ($define->user_award_defines_image ?: '-'),
                'group_image' => (string) ($define->award?->user_award_image ?: '-'),
                'title' => (string) ($define->award?->user_award_name ?? '').', '.(string) $define->user_award_defines_rank_name,
            ];
        }

        return $payload;
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function deleteFinished(int $finishedId): array
    {
        $row = UserAwardFinished::query()->find($finishedId);
        if (! $row) {
            return ['ok' => false, 'message' => 'Eintrag nicht gefunden.'];
        }
        $row->delete();

        return ['ok' => true, 'message' => 'Fertige Auszeichnung gelöscht.'];
    }

    /**
     * @return array{userUpdates: int, newAwardUser: list<array{uid: int, aid: int, usernick: string}>}
     */
    public function calculateDefine(int $defineId, int $adminUserId): array
    {
        $this->localUserUpdates = 0;
        $this->goalFinishers = [];
        $this->runCalculate($defineId, $adminUserId);

        return [
            'userUpdates' => $this->localUserUpdates,
            'newAwardUser' => $this->goalFinishers,
        ];
    }

    /**
     * @return array{userUpdates: int, newAwardUser: list<array{uid: int, aid: int, usernick: string}>, duration: list<array{award: string, duration: float|string}>}
     */
    public function calculateAll(int $adminUserId): array
    {
        $this->localUserUpdates = 0;
        $this->goalFinishers = [];
        $start = microtime(true);
        $duration = [];

        $defines = UserAwardDefines::query()->orderBy('user_award_defines_id')->get();
        foreach ($defines as $define) {
            $loopIn = microtime(true);
            $this->runCalculate((int) $define->user_award_defines_id, $adminUserId);
            $duration[] = [
                'award' => (string) ($define->user_award_defines_description ?: $define->user_award_defines_rank_name),
                'duration' => round(microtime(true) - $loopIn, 4),
            ];
        }

        $duration[] = [
            'award' => 'Gesamt',
            'duration' => round(microtime(true) - $start, 4).' Sekunden',
        ];

        return [
            'userUpdates' => $this->localUserUpdates,
            'newAwardUser' => $this->goalFinishers,
            'duration' => $duration,
        ];
    }

    private function runCalculate(int $defineId, int $adminUserId): void
    {
        if ($defineId <= 0) {
            return;
        }

        $define = UserAwardDefines::query()->find($defineId);
        if (! $define) {
            return;
        }

        if ((int) $define->user_award_defines_aim_automatic === 1) {
            $this->calculateAutomatic($define);
        } else {
            $this->calculateByFunction($define, $adminUserId);
        }
    }

    private function calculateAutomatic(UserAwardDefines $define): void
    {
        $parsed = $this->parsePeerColumn((string) $define->user_award_defines_aim_dbtable);
        if ($parsed === null) {
            return;
        }

        [$table, $column, $userIdColumn] = $parsed;
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column) || ! Schema::hasColumn($table, $userIdColumn)) {
            return;
        }

        $operator = $this->normalizeOperator((string) $define->user_award_defines_aim_operator);
        $aim = $define->user_award_defines_aim;
        $loops = max(1, (int) $define->user_award_defines_aim_count);
        $defineId = (int) $define->user_award_defines_id;

        $already = UserAwardFinished::query()
            ->where('user_award_finished_award_defines_id', $defineId)
            ->pluck('user_award_finished_user_id')
            ->map(static fn ($id) => (int) $id)
            ->all();

        $query = DB::table($table)->orderBy($userIdColumn);
        if ($already !== []) {
            $query->whereNotIn($userIdColumn, $already);
        }
        $this->applyOperator($query, $column, $operator, $aim);

        $rows = $query->get([$userIdColumn]);
        $counts = [];
        foreach ($rows as $row) {
            $uid = (int) $row->{$userIdColumn};
            if ($uid <= 0) {
                continue;
            }
            $counts[$uid] = ($counts[$uid] ?? 0) + 1;
        }

        foreach ($counts as $uid => $count) {
            if ($count < $loops) {
                continue;
            }
            $this->grantAward($uid, $defineId);
        }
    }

    private function calculateByFunction(UserAwardDefines $define, int $adminUserId): void
    {
        $function = trim((string) $define->user_award_defines_aim_function_name);
        $allowed = [
            'calcAwardGameWins',
            'calcAwardRoundWins',
            'calcAwardBeer',
            'calcAwardTopscorer',
        ];
        if (! in_array($function, $allowed, true)) {
            return;
        }

        $leagueId = $this->adminCenter->selectedLeagueId($adminUserId);
        $usersQuery = WebUser::query()
            ->where('user_status', 'active')
            ->orderBy('user_id');

        if ($leagueId > 0) {
            $usersQuery->whereIn('user_id', Userscore::query()
                ->where('userscore_league_id', $leagueId)
                ->select('userscore_user_id'));
        }

        $defineId = (int) $define->user_award_defines_id;
        foreach ($usersQuery->get(['user_id', 'user_nickname']) as $user) {
            $uid = (int) $user->user_id;
            $ok = $this->{$function}($define, $uid);
            $existing = UserAwardFinished::query()
                ->where('user_award_finished_user_id', $uid)
                ->where('user_award_finished_award_defines_id', $defineId)
                ->first();

            if ($ok) {
                if (! $existing) {
                    $this->grantAward($uid, $defineId, (string) $user->user_nickname);
                }
            } elseif ($existing) {
                $existing->delete();
            }
        }
    }

    private function grantAward(int $userId, int $defineId, ?string $nickname = null): void
    {
        $exists = UserAwardFinished::query()
            ->where('user_award_finished_user_id', $userId)
            ->where('user_award_finished_award_defines_id', $defineId)
            ->exists();
        if ($exists) {
            return;
        }

        UserAwardFinished::query()->create([
            'user_award_finished_user_id' => $userId,
            'user_award_finished_award_defines_id' => $defineId,
            'user_award_finished_date' => date('Y-m-d H:i:s'),
        ]);

        if ($nickname === null) {
            $nickname = (string) (WebUser::query()->whereKey($userId)->value('user_nickname') ?? '');
        }

        $this->goalFinishers[] = [
            'uid' => $userId,
            'aid' => $defineId,
            'usernick' => $nickname,
        ];
        $this->localUserUpdates++;
    }

    private function calcAwardGameWins(UserAwardDefines $define, int $userId): bool
    {
        $aimCount = (int) $define->user_award_defines_aim_count;
        $aim = (int) $define->user_award_defines_aim;
        $leagues = League::query()
            ->where('league_archive', 1)
            ->get();

        $count = 0;
        foreach ($leagues as $league) {
            $rm = (string) (LeagueOptions::query()
                ->where('options_league_id', (int) $league->league_id)
                ->value('options_league_rankmode') ?? 'points');
            if ($this->calculateUserRank($userId, (int) $league->league_id, $rm) === $aim) {
                $count++;
            }
        }

        return $count >= $aimCount;
    }

    private function calcAwardRoundWins(UserAwardDefines $define, int $userId): bool
    {
        $aimCount = (int) $define->user_award_defines_aim_count;
        $aim = (int) $define->user_award_defines_aim;
        $now = date('Y-m-d H:i:s');

        $userteams = Userteam::query()
            ->join('ffb_matchround', 'ffb_matchround.matchround_id', '=', 'ffb_userteam.userteam_matchround_id')
            ->where('ffb_matchround.matchround_enddate', '<', $now)
            ->where('ffb_userteam.userteam_user_id', $userId)
            ->get(['ffb_userteam.userteam_id', 'ffb_userteam.userteam_matchround_id']);

        $count = 0;
        foreach ($userteams as $ut) {
            if ($this->calculateUserteamRank((int) $ut->userteam_id, (int) $ut->userteam_matchround_id) === $aim) {
                $count++;
            }
        }

        return $count >= $aimCount;
    }

    private function calcAwardBeer(UserAwardDefines $define, int $userId): bool
    {
        $leagueId = (int) $define->user_award_defines_aim;
        $league = League::query()->find($leagueId);
        if (! $league || (int) $league->league_archive !== 1) {
            return false;
        }

        $rm = (string) (LeagueOptions::query()
            ->where('options_league_id', $leagueId)
            ->value('options_league_rankmode') ?? 'points');

        return $this->calculateUserRank($userId, $leagueId, $rm) === 1;
    }

    private function calcAwardTopscorer(UserAwardDefines $define, int $userId): bool
    {
        $points = (int) $define->user_award_defines_aim;

        return Userteam::query()
            ->where('userteam_user_id', $userId)
            ->where('userteam_score', '>=', $points)
            ->exists();
    }

    private function calculateUserRank(int $userId, int $leagueId, string $rm): int
    {
        $query = Userscore::query()->where('userscore_league_id', $leagueId);
        if ($rm === 'wc') {
            $query->orderByDesc('userscore_wc_points')->orderByDesc('userscore_total');
        } else {
            $query->orderByDesc('userscore_total');
        }

        $items = $query->get();
        $lastScore = 10000;
        $lastPoints = 10000;
        $rank = 1;
        $i = 0;
        foreach ($items as $item) {
            $i++;
            if ($rm === 'wc') {
                $wc = (int) $item->userscore_wc_points;
                $pts = (int) $item->userscore_total;
                if ($wc < $lastScore) {
                    $lastScore = $wc;
                    $lastPoints = $pts;
                    $rank = $i;
                } elseif ($pts < $lastPoints) {
                    $lastPoints = $pts;
                    $rank = $i;
                }
            } else {
                $pts = (int) $item->userscore_total;
                if ($pts < $lastScore) {
                    $lastScore = $pts;
                    $rank = $i;
                }
            }
            if ((int) $item->userscore_user_id === $userId) {
                return $rank;
            }
        }

        return 0;
    }

    private function calculateUserteamRank(int $userteamId, int $matchroundId): int
    {
        $items = Userteam::query()
            ->where('userteam_matchround_id', $matchroundId)
            ->orderByDesc('userteam_score')
            ->get(['userteam_id', 'userteam_score']);

        $lastScore = 10000;
        $rank = 1;
        $i = 0;
        foreach ($items as $item) {
            $i++;
            $score = (int) $item->userteam_score;
            if ($score < $lastScore) {
                $lastScore = $score;
                $rank = $i;
            }
            if ((int) $item->userteam_id === $userteamId) {
                return $rank;
            }
        }

        return 0;
    }

    /**
     * @return array{0: string, 1: string, 2: string}|null
     */
    private function parsePeerColumn(string $peerColumn): ?array
    {
        $peerColumn = trim($peerColumn);
        if ($peerColumn === '' || ! preg_match('/^Ffb([A-Za-z0-9]+)Peer::([A-Z0-9_]+)$/', $peerColumn, $m)) {
            return null;
        }

        $entity = $m[1];
        $const = $m[2];
        $snakeEntity = strtolower((string) preg_replace('/([a-z])([A-Z])/', '$1_$2', $entity));
        $table = 'ffb_'.$snakeEntity;
        $column = strtolower($const);
        $userIdColumn = $snakeEntity.'_user_id';

        return [$table, $column, $userIdColumn];
    }

    private function normalizeOperator(string $operator): string
    {
        $operator = trim($operator);
        $map = [
            'EQUAL' => '=',
            'Criteria::EQUAL' => '=',
            'GREATER_THAN' => '>',
            'Criteria::GREATER_THAN' => '>',
            'GREATER_EQUAL' => '>=',
            'Criteria::GREATER_EQUAL' => '>=',
            'LESS_THAN' => '<',
            'Criteria::LESS_THAN' => '<',
            'LESS_EQUAL' => '<=',
            'Criteria::LESS_EQUAL' => '<=',
            'NOT_EQUAL' => '!=',
            'Criteria::NOT_EQUAL' => '!=',
        ];

        return $map[$operator] ?? ($operator !== '' ? $operator : '=');
    }

    /**
     * @param  Builder  $query
     */
    private function applyOperator($query, string $column, string $operator, mixed $aim): void
    {
        $allowed = ['=', '!=', '<>', '>', '>=', '<', '<='];
        if (! in_array($operator, $allowed, true)) {
            $operator = '=';
        }
        $query->where($column, $operator, $aim);
    }
}
