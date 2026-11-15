<?php

namespace App\Services;

use App\Models\Extremeteam;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Userteam;
use App\Support\AssetKey;
use App\Support\Flag;
use App\Support\PlayerPicture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class AdminPlayerService
{
    public const PER_PAGE = 100;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    /**
     * @param  array<string, mixed>|null  $form
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId, ?array $form = null, string $mode = 'create', string $tab = 'manual'): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $tab = $tab === 'duplicates' ? 'duplicates' : 'manual';

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'countries' => $this->countryOptions(),
            'form' => $form ?? $this->emptyForm(),
            'mode' => $mode === 'update' ? 'update' : 'create',
            'per_page' => self::PER_PAGE,
            'tab' => $tab,
            'teams' => $tab === 'duplicates' ? $this->teamOptions() : [],
            'duplicates_prepare_url' => route('admin.players.duplicates.prepare'),
            'duplicates_check_url' => route('admin.players.duplicates.check'),
            'duplicates_merge_url' => route('admin.players.duplicates.merge'),
            'player_merge_prepare_url' => route('admin.players.merge.prepare'),
            'player_merge_preview_url' => route('admin.players.merge.preview'),
            'player_merge_execute_url' => route('admin.players.merge.execute'),
        ];
    }

    /**
     * @return list<array{team_id: int, team_name: string}>
     */
    private function teamOptions(): array
    {
        return Team::query()
            ->orderBy('team_name')
            ->get(['team_id', 'team_name'])
            ->map(static fn (Team $team): array => [
                'team_id' => (int) $team->team_id,
                'team_name' => (string) $team->team_name,
            ])
            ->all();
    }

    private function nameMatcher(): AdminSquadService
    {
        return app(AdminSquadService::class);
    }

    public const DUPLICATE_SCOPE_DB = 'db';

    public const DUPLICATE_SCOPE_NATIONALITY = 'nationality';

    public const DUPLICATE_SCOPE_TEAM = 'team';

    /**
     * Prepare a chunked duplicate-name scan for a team.
     *
     * @param  self::DUPLICATE_SCOPE_*|string  $scope
     * @return array{
     *     ok: true,
     *     team_id: int,
     *     team_name: string,
     *     scope: string,
     *     total_checks: int,
     *     players: list<array{player_id: int, checks: int}>
     * }
     */
    public function prepareDuplicateNames(int $teamId, string $scope = self::DUPLICATE_SCOPE_NATIONALITY): array
    {
        $scope = $this->normalizeDuplicateScope($scope);
        [$team, $teamPlayers] = $this->teamPlayersForDuplicateScan($teamId);
        $teamName = trim((string) $team->team_name);
        $teamPlayerIds = array_map('intval', array_keys($teamPlayers));

        $players = [];
        $totalChecks = 0;
        foreach ($teamPlayers as $teamPlayerId => $bundle) {
            $player = $bundle['player'];
            $fname = (string) ($player->player_fname ?? '');
            $lname = (string) ($player->player_lname ?? '');
            if (trim($fname.' '.$lname) === '') {
                continue;
            }
            $checks = $this->candidateCountForPlayer($player, $scope, $teamPlayerIds);
            $players[] = [
                'player_id' => $teamPlayerId,
                'checks' => $checks,
            ];
            $totalChecks += $checks;
        }

        return [
            'ok' => true,
            'team_id' => $teamId,
            'team_name' => $teamName,
            'scope' => $scope,
            'total_checks' => $totalChecks,
            'players' => $players,
        ];
    }

    /**
     * Run name matching for one team player against the DB (optionally same nationality).
     *
     * @return array{
     *     ok: true,
     *     player_id: int,
     *     checks: int,
     *     has_matches: bool,
     *     match_count: int,
     *     group: array{
     *         players: list<array{
     *             player_id: int,
     *             player_fname: string,
     *             player_lname: string,
     *             player_uefa_id: string,
     *             player_fifa_id: string,
     *             entries: list<array{
     *                 playerteam_id: int,
     *                 playerteam_league_id: int,
     *                 league_title: string,
     *                 playerteam_player_position: string,
     *                 playerteam_status: int
     *             }>
     *         }>,
     *         matches: list<array{
     *             reason: string,
     *             player_id: int,
     *             player_fname: string,
     *             player_lname: string,
     *             player_uefa_id: string,
     *             player_fifa_id: string,
     *             in_selected_team: bool,
     *             squads: list<array{
     *                 playerteam_id: int,
     *                 team_id: int,
     *                 team_name: string,
     *                 league_id: int,
     *                 league_title: string,
     *                 playerteam_player_position: string,
     *                 playerteam_status: int
     *             }>
     *         }>
     *     }|null
     * }
     * @param  self::DUPLICATE_SCOPE_*|string  $scope
     */
    public function checkDuplicateNamePlayer(
        int $teamId,
        int $playerId,
        string $scope = self::DUPLICATE_SCOPE_NATIONALITY,
    ): array {
        $scope = $this->normalizeDuplicateScope($scope);
        [$team, $teamPlayers] = $this->teamPlayersForDuplicateScan($teamId);
        if (! isset($teamPlayers[$playerId])) {
            throw new InvalidArgumentException('Spieler gehört nicht zum gewählten Team.');
        }

        $bundle = $teamPlayers[$playerId];
        $teamPlayer = $bundle['player'];
        $fname = (string) ($teamPlayer->player_fname ?? '');
        $lname = (string) ($teamPlayer->player_lname ?? '');
        if (trim($fname.' '.$lname) === '') {
            return [
                'ok' => true,
                'player_id' => $playerId,
                'checks' => 0,
                'has_matches' => false,
                'match_count' => 0,
                'group' => null,
            ];
        }

        $teamPlayerIds = array_map('intval', array_keys($teamPlayers));
        $candidates = $this->candidatePlayersFor($teamPlayer, $scope, $teamPlayerIds);
        $checks = 0;
        $matches = [];
        foreach ($candidates as $other) {
            $otherId = (int) $other->player_id;
            if ($otherId === $playerId) {
                continue;
            }
            $checks++;
            $otherFname = (string) ($other->player_fname ?? '');
            $otherLname = (string) ($other->player_lname ?? '');
            if (trim($otherFname.' '.$otherLname) === '') {
                continue;
            }
            if (! $this->nameMatcher()->namesAlmostMatch($fname, $lname, $otherFname, $otherLname)) {
                continue;
            }
            $matches[] = [
                'reason' => $this->nameMatcher()->namesAlmostMatchReason($fname, $lname, $otherFname, $otherLname),
                'player_id' => $otherId,
                'player_fname' => $otherFname,
                'player_lname' => $otherLname,
                'player_uefa_id' => trim((string) ($other->player_uefa_id ?? '')),
                'player_fifa_id' => trim((string) ($other->player_fifa_id ?? '')),
                'in_selected_team' => isset($teamPlayers[$otherId]),
                'compared_from_player_id' => $playerId,
                'squads' => [],
            ];
        }

        if ($matches === []) {
            return [
                'ok' => true,
                'player_id' => $playerId,
                'checks' => $checks,
                'has_matches' => false,
                'match_count' => 0,
                'group' => null,
            ];
        }

        usort($matches, static function (array $a, array $b): int {
            $last = strcasecmp($a['player_lname'], $b['player_lname']);
            if ($last !== 0) {
                return $last;
            }

            return strcasecmp($a['player_fname'], $b['player_fname']);
        });

        $matchIds = array_map(static fn (array $m): int => (int) $m['player_id'], $matches);
        $squadsByPlayer = $this->squadsByPlayerIds($matchIds);
        foreach ($matches as $index => $match) {
            $matches[$index]['squads'] = $squadsByPlayer[$match['player_id']] ?? [];
        }

        $entries = [];
        foreach ($bundle['entries'] as $entry) {
            $entries[] = [
                'playerteam_id' => (int) $entry->playerteam_id,
                'playerteam_league_id' => (int) $entry->playerteam_league_id,
                'league_title' => (string) ($entry->league?->league_title ?? ''),
                'playerteam_player_position' => (string) ($entry->playerteam_player_position ?: ''),
                'playerteam_status' => (int) ($entry->playerteam_status ? 1 : 0),
            ];
        }

        $group = [
            'players' => [[
                'player_id' => $playerId,
                'player_fname' => $fname,
                'player_lname' => $lname,
                'player_uefa_id' => trim((string) ($teamPlayer->player_uefa_id ?? '')),
                'player_fifa_id' => trim((string) ($teamPlayer->player_fifa_id ?? '')),
                'entries' => $entries,
            ]],
            'matches' => $matches,
        ];

        return [
            'ok' => true,
            'player_id' => $playerId,
            'checks' => $checks,
            'has_matches' => true,
            'match_count' => count($matches),
            'group' => $group,
        ];
    }

    /**
     * Merge overlapping duplicate groups (shared team/external players) into one result each.
     *
     * @param  list<array{players: list<array<string, mixed>>, matches: list<array<string, mixed>>}>  $groups
     * @return array{
     *     ok: true,
     *     clean: bool,
     *     count: int,
     *     match_count: int,
     *     summary: string,
     *     html: string,
     *     groups: list<array{players: list<array<string, mixed>>, matches: list<array<string, mixed>>}>
     * }
     */
    public function mergeDuplicateNameGroups(int $teamId, array $groups): array
    {
        [$team, $teamPlayers] = $this->teamPlayersForDuplicateScan($teamId);
        $teamName = trim((string) $team->team_name);

        $normalized = [];
        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }
            $players = is_array($group['players'] ?? null) ? $group['players'] : [];
            $matches = is_array($group['matches'] ?? null) ? $group['matches'] : [];
            if ($players === [] || $matches === []) {
                continue;
            }
            $normalized[] = [
                'players' => array_values($players),
                'matches' => array_values($matches),
            ];
        }

        $merged = $this->mergeConnectedDuplicateGroups($normalized);
        foreach ($merged as $index => $group) {
            $merged[$index]['players'] = $this->enrichDuplicateGroupPlayers(
                $group['players'],
                $teamPlayers,
            );
        }
        $merged = array_values(array_filter(
            $merged,
            static function (array $group): bool {
                return count($group['players']) >= 2 || count($group['matches']) >= 1;
            }
        ));
        $matchCount = array_sum(array_map(
            static fn (array $group): int => count($group['matches']),
            $merged
        ));
        $count = count($merged);
        $clean = $count === 0;
        $summary = $clean
            ? 'Keine ähnlichen Namen für Spieler von '.$teamName.' gefunden.'
            : $count.' Gruppe'.($count === 1 ? '' : 'n').' · '.$matchCount.' Treffer';

        return [
            'ok' => true,
            'clean' => $clean,
            'count' => $count,
            'match_count' => $matchCount,
            'summary' => $summary,
            'html' => view('admin.partials.players-duplicate-names', [
                'groups' => $merged,
                'groupCount' => $count,
                'matchCount' => $matchCount,
                'summary' => $summary,
                'teamName' => $teamName,
                'positions' => ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'],
            ])->render(),
            'groups' => $merged,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $players
     * @param  array<int, array{player: Player, entries: list<Playerteam>}>  $teamPlayers
     * @return list<array<string, mixed>>
     */
    private function enrichDuplicateGroupPlayers(array $players, array $teamPlayers): array
    {
        foreach ($players as $index => $player) {
            $id = (int) ($player['player_id'] ?? 0);
            if ($id <= 0 || ! isset($teamPlayers[$id])) {
                continue;
            }
            $bundle = $teamPlayers[$id];
            $source = $bundle['player'];
            $entries = [];
            foreach ($bundle['entries'] as $entry) {
                $entries[] = [
                    'playerteam_id' => (int) $entry->playerteam_id,
                    'playerteam_league_id' => (int) $entry->playerteam_league_id,
                    'league_title' => (string) ($entry->league?->league_title ?? ''),
                    'playerteam_player_position' => (string) ($entry->playerteam_player_position ?: ''),
                    'playerteam_status' => (int) ($entry->playerteam_status ? 1 : 0),
                ];
            }
            $players[$index] = [
                'player_id' => $id,
                'player_fname' => (string) ($source->player_fname ?? ($player['player_fname'] ?? '')),
                'player_lname' => (string) ($source->player_lname ?? ($player['player_lname'] ?? '')),
                'player_uefa_id' => trim((string) ($source->player_uefa_id ?? ($player['player_uefa_id'] ?? ''))),
                'player_fifa_id' => trim((string) ($source->player_fifa_id ?? ($player['player_fifa_id'] ?? ''))),
                'entries' => $entries,
            ];
        }

        return $players;
    }

    /**
     * @param  list<array{players: list<array<string, mixed>>, matches: list<array<string, mixed>>}>  $groups
     * @return list<array{players: list<array<string, mixed>>, matches: list<array<string, mixed>>}>
     */
    private function mergeConnectedDuplicateGroups(array $groups): array
    {
        $n = count($groups);
        if ($n <= 1) {
            return array_map(fn (array $group): array => $this->finalizeMergedDuplicateGroup($group), $groups);
        }

        $parent = [];
        for ($i = 0; $i < $n; $i++) {
            $parent[$i] = $i;
        }
        $find = static function (int $x) use (&$parent, &$find): int {
            if ($parent[$x] !== $x) {
                $parent[$x] = $find($parent[$x]);
            }

            return $parent[$x];
        };
        $union = static function (int $a, int $b) use (&$parent, $find): void {
            $ra = $find($a);
            $rb = $find($b);
            if ($ra !== $rb) {
                $parent[$rb] = $ra;
            }
        };

        /** @var array<int, list<int>> $groupsByPlayerId */
        $groupsByPlayerId = [];
        foreach ($groups as $index => $group) {
            foreach ($group['players'] as $player) {
                $id = (int) ($player['player_id'] ?? 0);
                if ($id > 0) {
                    $groupsByPlayerId[$id][] = $index;
                }
            }
            foreach ($group['matches'] as $match) {
                $id = (int) ($match['player_id'] ?? 0);
                if ($id > 0) {
                    $groupsByPlayerId[$id][] = $index;
                }
            }
        }
        foreach ($groupsByPlayerId as $indexes) {
            $first = $indexes[0];
            for ($i = 1, $len = count($indexes); $i < $len; $i++) {
                $union($first, $indexes[$i]);
            }
        }

        /** @var array<int, array{players: list<array<string, mixed>>, matches: list<array<string, mixed>>}> $buckets */
        $buckets = [];
        foreach ($groups as $index => $group) {
            $root = $find($index);
            if (! isset($buckets[$root])) {
                $buckets[$root] = ['players' => [], 'matches' => []];
            }
            foreach ($group['players'] as $player) {
                $buckets[$root]['players'][] = $player;
            }
            foreach ($group['matches'] as $match) {
                $buckets[$root]['matches'][] = $match;
            }
        }

        $merged = [];
        foreach ($buckets as $bucket) {
            $merged[] = $this->finalizeMergedDuplicateGroup($bucket);
        }

        usort($merged, static function (array $a, array $b): int {
            $cmp = count($b['matches']) <=> count($a['matches']);
            if ($cmp !== 0) {
                return $cmp;
            }
            $aName = (string) (($a['players'][0]['player_lname'] ?? '').'|'.($a['players'][0]['player_fname'] ?? ''));
            $bName = (string) (($b['players'][0]['player_lname'] ?? '').'|'.($b['players'][0]['player_fname'] ?? ''));

            return strcasecmp($aName, $bName);
        });

        return $merged;
    }

    /**
     * @param  array{players: list<array<string, mixed>>, matches: list<array<string, mixed>>}  $group
     * @return array{
     *     players: list<array<string, mixed>>,
     *     matches: list<array<string, mixed>>,
     *     team_similarities: list<array{
     *         left_player_id: int,
     *         right_player_id: int,
     *         left_fname: string,
     *         left_lname: string,
     *         right_fname: string,
     *         right_lname: string,
     *         reason: string
     *     }>
     * }
     */
    private function finalizeMergedDuplicateGroup(array $group): array
    {
        $playersById = [];
        foreach ($group['players'] as $player) {
            $id = (int) ($player['player_id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $playersById[$id] = $player;
        }

        // Teammates that only appeared as matches become co-anchors of the group.
        foreach ($group['matches'] as $match) {
            if (empty($match['in_selected_team'])) {
                continue;
            }
            $id = (int) ($match['player_id'] ?? 0);
            if ($id <= 0 || isset($playersById[$id])) {
                continue;
            }
            $playersById[$id] = [
                'player_id' => $id,
                'player_fname' => (string) ($match['player_fname'] ?? ''),
                'player_lname' => (string) ($match['player_lname'] ?? ''),
                'player_uefa_id' => (string) ($match['player_uefa_id'] ?? ''),
                'player_fifa_id' => (string) ($match['player_fifa_id'] ?? ''),
                'entries' => [],
            ];
        }

        $teamSimilarities = [];
        $seenPairs = [];
        foreach ($group['matches'] as $match) {
            if (empty($match['in_selected_team'])) {
                continue;
            }
            $rightId = (int) ($match['player_id'] ?? 0);
            $leftId = (int) ($match['compared_from_player_id'] ?? 0);
            if ($leftId <= 0 && $group['players'] !== []) {
                $leftId = (int) ($group['players'][0]['player_id'] ?? 0);
            }
            if ($leftId <= 0 || $rightId <= 0 || $leftId === $rightId) {
                continue;
            }
            $pairKey = min($leftId, $rightId).'-'.max($leftId, $rightId);
            if (isset($seenPairs[$pairKey])) {
                continue;
            }
            $seenPairs[$pairKey] = true;

            $left = $playersById[$leftId] ?? null;
            $right = $playersById[$rightId] ?? null;
            $teamSimilarities[] = [
                'left_player_id' => $leftId,
                'right_player_id' => $rightId,
                'left_fname' => (string) ($left['player_fname'] ?? ''),
                'left_lname' => (string) ($left['player_lname'] ?? ''),
                'right_fname' => (string) (($right['player_fname'] ?? null) ?? ($match['player_fname'] ?? '')),
                'right_lname' => (string) (($right['player_lname'] ?? null) ?? ($match['player_lname'] ?? '')),
                'reason' => (string) ($match['reason'] ?? ''),
            ];
        }

        $anchorIds = array_fill_keys(array_keys($playersById), true);
        $matchesById = [];
        foreach ($group['matches'] as $match) {
            $id = (int) ($match['player_id'] ?? 0);
            if ($id <= 0 || isset($anchorIds[$id])) {
                continue;
            }
            if (! isset($matchesById[$id])) {
                $matchesById[$id] = $match;
                continue;
            }
            // Keep first reason; prefer non-empty squads if the later copy has them.
            if (($matchesById[$id]['squads'] ?? []) === [] && ($match['squads'] ?? []) !== []) {
                $matchesById[$id]['squads'] = $match['squads'];
            }
        }

        $players = array_values($playersById);
        usort($players, static function (array $a, array $b): int {
            $last = strcasecmp((string) ($a['player_lname'] ?? ''), (string) ($b['player_lname'] ?? ''));
            if ($last !== 0) {
                return $last;
            }
            $first = strcasecmp((string) ($a['player_fname'] ?? ''), (string) ($b['player_fname'] ?? ''));
            if ($first !== 0) {
                return $first;
            }

            return ((int) ($a['player_id'] ?? 0)) <=> ((int) ($b['player_id'] ?? 0));
        });

        $matches = array_values($matchesById);
        usort($matches, static function (array $a, array $b): int {
            $last = strcasecmp((string) ($a['player_lname'] ?? ''), (string) ($b['player_lname'] ?? ''));
            if ($last !== 0) {
                return $last;
            }

            return strcasecmp((string) ($a['player_fname'] ?? ''), (string) ($b['player_fname'] ?? ''));
        });

        return [
            'players' => $players,
            'matches' => $matches,
            'team_similarities' => $teamSimilarities,
        ];
    }

    /**
     * @return array{0: Team, 1: array<int, array{player: Player, entries: list<Playerteam>}>}
     */
    private function teamPlayersForDuplicateScan(int $teamId): array
    {
        if ($teamId <= 0) {
            throw new InvalidArgumentException('Bitte ein Team auswählen.');
        }

        $team = Team::query()->find($teamId);
        if (! $team) {
            throw new InvalidArgumentException('Team nicht gefunden.');
        }

        $teamRows = Playerteam::query()
            ->with(['player', 'league'])
            ->where('playerteam_team_id', $teamId)
            ->orderBy('playerteam_id')
            ->get();

        /** @var array<int, array{player: Player, entries: list<Playerteam>}> $teamPlayers */
        $teamPlayers = [];
        foreach ($teamRows as $row) {
            $player = $row->player;
            if (! $player) {
                continue;
            }
            $playerId = (int) $player->player_id;
            if ($playerId <= 0) {
                continue;
            }
            if (! isset($teamPlayers[$playerId])) {
                $teamPlayers[$playerId] = [
                    'player' => $player,
                    'entries' => [],
                ];
            }
            $teamPlayers[$playerId]['entries'][] = $row;
        }

        return [$team, $teamPlayers];
    }

    /**
     * @param  list<int>  $teamPlayerIds
     */
    private function candidateCountForPlayer(Player $player, string $scope, array $teamPlayerIds): int
    {
        $playerId = (int) $player->player_id;
        if ($scope === self::DUPLICATE_SCOPE_TEAM) {
            return count(array_filter(
                $teamPlayerIds,
                static fn (int $id): bool => $id !== $playerId,
            ));
        }

        $query = Player::query()->where('player_id', '!=', $playerId);
        $nationality = strtoupper(trim((string) ($player->player_nationality ?? '')));
        if ($scope === self::DUPLICATE_SCOPE_NATIONALITY && $nationality !== '') {
            $query->whereRaw('UPPER(TRIM(player_nationality)) = ?', [$nationality]);
        }

        return (int) $query->count();
    }

    /**
     * @param  list<int>  $teamPlayerIds
     * @return \Illuminate\Support\Collection<int, Player>
     */
    private function candidatePlayersFor(Player $player, string $scope, array $teamPlayerIds)
    {
        $playerId = (int) $player->player_id;
        $query = Player::query()
            ->where('player_id', '!=', $playerId)
            ->orderBy('player_id')
            ->select([
                'player_id',
                'player_fname',
                'player_lname',
                'player_nationality',
                'player_uefa_id',
                'player_fifa_id',
            ]);

        if ($scope === self::DUPLICATE_SCOPE_TEAM) {
            $otherIds = array_values(array_filter(
                $teamPlayerIds,
                static fn (int $id): bool => $id !== $playerId,
            ));
            if ($otherIds === []) {
                return $query->whereRaw('0 = 1')->get();
            }

            return $query->whereIn('player_id', $otherIds)->get();
        }

        $nationality = strtoupper(trim((string) ($player->player_nationality ?? '')));
        if ($scope === self::DUPLICATE_SCOPE_NATIONALITY && $nationality !== '') {
            $query->whereRaw('UPPER(TRIM(player_nationality)) = ?', [$nationality]);
        }

        return $query->get();
    }

    /**
     * @return self::DUPLICATE_SCOPE_DB|self::DUPLICATE_SCOPE_NATIONALITY|self::DUPLICATE_SCOPE_TEAM
     */
    private function normalizeDuplicateScope(string $scope): string
    {
        $scope = strtolower(trim($scope));
        if (in_array($scope, [
            self::DUPLICATE_SCOPE_DB,
            self::DUPLICATE_SCOPE_NATIONALITY,
            self::DUPLICATE_SCOPE_TEAM,
        ], true)) {
            return $scope;
        }

        return self::DUPLICATE_SCOPE_NATIONALITY;
    }

    /**
     * @param  list<int>  $playerIds
     * @return array<int, list<array{
     *     playerteam_id: int,
     *     team_id: int,
     *     team_name: string,
     *     league_id: int,
     *     league_title: string,
     *     playerteam_player_position: string,
     *     playerteam_status: int
     * }>>
     */
    private function squadsByPlayerIds(array $playerIds): array
    {
        $playerIds = array_values(array_unique(array_filter(
            array_map(static fn ($id): int => (int) $id, $playerIds),
            static fn (int $id): bool => $id > 0,
        )));
        if ($playerIds === []) {
            return [];
        }

        $rows = Playerteam::query()
            ->with(['team', 'league'])
            ->whereIn('playerteam_player_id', $playerIds)
            ->orderBy('playerteam_id')
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $playerId = (int) $row->playerteam_player_id;
            $out[$playerId][] = [
                'playerteam_id' => (int) $row->playerteam_id,
                'team_id' => (int) $row->playerteam_team_id,
                'team_name' => (string) ($row->team?->team_name ?? ''),
                'league_id' => (int) $row->playerteam_league_id,
                'league_title' => (string) ($row->league?->league_title ?? ''),
                'playerteam_player_position' => (string) ($row->playerteam_player_position ?: ''),
                'playerteam_status' => (int) ($row->playerteam_status ? 1 : 0),
            ];
        }

        return $out;
    }

    /**
     * @param  array{q?: string, nationality?: string, page?: int, exclude_team_id?: int, exclude_league_id?: int}  $filters
     * @return array{
     *     items: list<array<string, mixed>>,
     *     total: int,
     *     page: int,
     *     per_page: int,
     *     last_page: int,
     *     q: string,
     *     nationality: string
     * }
     */
    public function search(array $filters = []): array
    {
        $filters = $this->normalizeFilters($filters);
        $paginator = $this->listItems($filters);
        /** @var list<array<string, mixed>> $items */
        $items = array_values($paginator->items());
        $items = $this->attachPictureUrls($items);

        return [
            'items' => $items,
            'total' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
            'q' => $filters['q'],
            'nationality' => $filters['nationality'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(): array
    {
        return [
            'player_id' => '',
            'player_fname' => '',
            'player_lname' => '',
            'player_display_name' => '',
            'player_nationality' => '',
            'player_status' => 1,
            'player_status_description' => '',
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fifa_id' => '',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function formForEdit(int $playerId): ?array
    {
        $item = Player::query()->find($playerId);
        if (! $item) {
            return null;
        }

        $desc = (string) ($item->player_status_description ?? '');
        if (strtoupper($desc) === 'NULL') {
            $desc = '';
        }

        return [
            'player_id' => (int) $item->player_id,
            'player_fname' => (string) $item->player_fname,
            'player_lname' => (string) $item->player_lname,
            'player_display_name' => $item->resolvedDisplayName(),
            'player_nationality' => strtoupper(trim((string) ($item->player_nationality ?? ''))),
            'player_status' => (int) $item->player_status ? 1 : 0,
            'player_status_description' => $desc,
            'player_foreign_id' => (string) ($item->player_foreign_id ?? ''),
            'player_uefa_id' => (string) ($item->player_uefa_id ?? ''),
            'player_fifa_id' => (string) ($item->player_fifa_id ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, player_id?: int}
     */
    public function create(array $input): array
    {
        $form = $this->normalizeInput($input);
        $errors = $this->validate($form, true);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        $attrs = [
            'asset_key' => AssetKey::generate(
                'ffb_player',
                AssetKey::playerSourceName((string) $form['player_fname'], (string) $form['player_lname'])
            ),
            'player_foreign_id' => $form['player_foreign_id'],
            'player_uefa_id' => $form['player_uefa_id'],
            'player_fifa_id' => $form['player_fifa_id'],
            'player_fname' => $form['player_fname'],
            'player_lname' => $form['player_lname'],
            'player_nationality' => $form['player_nationality'],
            'player_status' => (int) $form['player_status'],
            'player_status_description' => $form['player_status_description'],
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('ffb_player', 'player_display_name')) {
            $displayName = (string) ($form['player_display_name'] ?? '');
            if ($displayName === '') {
                $displayName = \App\Support\PlayerDisplayName::default(
                    (string) $form['player_fname'],
                    (string) $form['player_lname'],
                );
            }
            $attrs['player_display_name'] = $displayName;
        }

        $player = Player::query()->create($attrs);

        return [
            'ok' => true,
            'message' => 'Spieler erfolgreich hinzugefügt.',
            'player_id' => (int) $player->player_id,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return list<string>
     */
    public function validateCreateInput(array $input): array
    {
        return $this->validate($this->normalizeInput($input), true);
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function update(int $playerId, array $input): array
    {
        $item = Player::query()->find($playerId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Spieler nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['player_id' => $playerId]),
            ];
        }

        $form = $this->normalizeInput($input + ['player_id' => $playerId]);
        $errors = $this->validate($form, false);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        $this->applyPlayerFields($item, $form);

        return ['ok' => true, 'message' => 'Spieler erfolgreich aktualisiert.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function batchUpdate(array $input): array
    {
        $itemsInput = is_array($input['items'] ?? null) ? $input['items'] : [];
        $deleteIds = $input['delete_ids'] ?? [];
        if (! is_array($deleteIds)) {
            $deleteIds = [];
        }
        $deleteIds = array_values(array_unique(array_filter(
            array_map('intval', $deleteIds),
            static fn (int $id): bool => $id > 0
        )));
        $deleteLookup = array_fill_keys($deleteIds, true);

        if ($itemsInput === [] && $deleteIds === []) {
            return ['ok' => false, 'errors' => ['Keine Änderungen zum Speichern.']];
        }

        /** @var list<array{item: Player, form: array<string, mixed>}> $prepared */
        $prepared = [];
        /** @var list<Player> $toDelete */
        $toDelete = [];
        $errors = [];

        foreach ($deleteIds as $playerId) {
            $item = Player::query()->find($playerId);
            if (! $item) {
                $errors[] = 'Spieler #'.$playerId.' nicht gefunden.';

                continue;
            }

            $blocker = $this->deletionBlocker($playerId);
            if ($blocker !== null) {
                $label = trim((string) $item->player_lname).' #'.$playerId;
                $errors[] = $label.': '.$blocker;

                continue;
            }

            $toDelete[] = $item;
        }

        foreach ($itemsInput as $rawId => $rowInput) {
            if (! is_array($rowInput)) {
                continue;
            }

            $playerId = (int) $rawId;
            if ($playerId <= 0 || isset($deleteLookup[$playerId])) {
                continue;
            }

            $item = Player::query()->find($playerId);
            if (! $item) {
                $errors[] = 'Spieler #'.$playerId.' nicht gefunden.';

                continue;
            }

            $form = $this->normalizeInput($rowInput + ['player_id' => $playerId]);
            $rowErrors = $this->validate($form, false);
            if ($rowErrors !== []) {
                $label = trim($form['player_lname'] !== '' ? $form['player_lname'] : (string) $item->player_lname).' #'.$playerId;
                foreach ($rowErrors as $rowError) {
                    $errors[] = $label.': '.$rowError;
                }

                continue;
            }

            $prepared[] = [
                'item' => $item,
                'form' => $form,
            ];
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        if ($prepared === [] && $toDelete === []) {
            return ['ok' => false, 'errors' => ['Keine Änderungen zum Speichern.']];
        }

        foreach ($prepared as $row) {
            $this->applyPlayerFields($row['item'], $row['form']);
        }
        foreach ($toDelete as $item) {
            $item->delete();
        }

        $updated = count($prepared);
        $deleted = count($toDelete);
        $parts = [];
        if ($updated === 1) {
            $parts[] = '1 Spieler gespeichert';
        } elseif ($updated > 1) {
            $parts[] = $updated.' Spieler gespeichert';
        }
        if ($deleted === 1) {
            $parts[] = '1 Spieler gelöscht';
        } elseif ($deleted > 1) {
            $parts[] = $deleted.' Spieler gelöscht';
        }

        return [
            'ok' => true,
            'message' => implode(', ', $parts).'.',
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function applyPlayerFields(Player $item, array $form): void
    {
        // player_foreign_id (TM) is no longer edited on admin/players; keep existing value.
        $item->player_uefa_id = $form['player_uefa_id'];
        $item->player_fifa_id = $form['player_fifa_id'];
        $item->player_fname = $form['player_fname'];
        $item->player_lname = $form['player_lname'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('ffb_player', 'player_display_name')) {
            $displayName = (string) ($form['player_display_name'] ?? '');
            if ($displayName === '') {
                $displayName = \App\Support\PlayerDisplayName::default(
                    (string) $form['player_fname'],
                    (string) $form['player_lname'],
                );
            }
            $item->player_display_name = $displayName;
        }
        $item->player_nationality = $form['player_nationality'];
        $item->player_status = (int) $form['player_status'];
        $item->player_status_description = $form['player_status_description'];
        $item->save();
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function delete(int $playerId): array
    {
        $item = Player::query()->find($playerId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Spieler nicht gefunden! Falsche ID oder Seite neu geladen?']];
        }

        $blocker = $this->deletionBlocker($playerId);
        if ($blocker !== null) {
            return ['ok' => false, 'errors' => [$blocker]];
        }

        $item->delete();

        return ['ok' => true, 'message' => 'Spieler erfolgreich gelöscht.'];
    }

    private function deletionBlocker(int $playerId): ?string
    {
        $playerteamIds = Playerteam::query()
            ->where('playerteam_player_id', $playerId)
            ->orderBy('playerteam_id')
            ->pluck('playerteam_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        if ($playerteamIds !== []) {
            $userteamIds = $this->userteamIdsContainingPlayerteams($playerteamIds);
            if ($userteamIds !== []) {
                return 'Löschen nicht möglich: Spieler ist in Userteams eingesetzt (Userteam-IDs: '.$this->formatIdList($userteamIds).'; PT-IDs: '.$this->formatIdList($playerteamIds).').';
            }

            $extremeRefs = $this->extremeTeamReferences($playerteamIds);
            if ($extremeRefs !== []) {
                return 'Löschen nicht möglich: Spieler ist in Top/Flop-Teams eingesetzt ('.$this->formatLabelList($extremeRefs).'; PT-IDs: '.$this->formatIdList($playerteamIds).').';
            }

            return 'Löschen nicht möglich: Spieler ist noch einem oder mehreren Teams zugeordnet (PT-IDs: '.$this->formatIdList($playerteamIds).').';
        }

        return null;
    }

    /**
     * @param  array{q?: string, nationality?: string, page?: int, exclude_team_id?: int, exclude_league_id?: int}  $filters
     * @return array{q: string, nationality: string, page: int, exclude_team_id: int, exclude_league_id: int}
     */
    private function normalizeFilters(array $filters): array
    {
        return [
            'q' => trim((string) ($filters['q'] ?? '')),
            'nationality' => strtoupper(trim((string) ($filters['nationality'] ?? ''))),
            'page' => max(1, (int) ($filters['page'] ?? 1)),
            'exclude_team_id' => max(0, (int) ($filters['exclude_team_id'] ?? 0)),
            'exclude_league_id' => max(0, (int) ($filters['exclude_league_id'] ?? 0)),
        ];
    }

    /**
     * @param  array{q: string, nationality: string, page: int, exclude_team_id: int, exclude_league_id: int}  $filters
     */
    private function listItems(array $filters): LengthAwarePaginator
    {
        $countries = $this->countryOptions();

        $query = Player::query()
            ->orderBy('player_lname')
            ->orderBy('player_fname')
            ->orderBy('player_id');

        if ($filters['exclude_team_id'] > 0) {
            $onTeam = Playerteam::query()
                ->where('playerteam_team_id', $filters['exclude_team_id']);
            if ($filters['exclude_league_id'] > 0) {
                $onTeam->where('playerteam_league_id', $filters['exclude_league_id']);
            }
            $onTeamIds = $onTeam->pluck('playerteam_player_id')->all();
            if ($onTeamIds !== []) {
                $query->whereNotIn('player_id', $onTeamIds);
            }
        }

        if ($filters['q'] !== '') {
            $q = $filters['q'];
            $like = '%'.$q.'%';
            $query->where(function ($builder) use ($q, $like) {
                $builder->where('player_fname', 'like', $like)
                    ->orWhere('player_lname', 'like', $like)
                    ->orWhereRaw("CONCAT(player_fname, ' ', player_lname) LIKE ?", [$like])
                    ->orWhereRaw("CONCAT(player_lname, ' ', player_fname) LIKE ?", [$like]);
                if (\Illuminate\Support\Facades\Schema::hasColumn('ffb_player', 'player_display_name')) {
                    $builder->orWhere('player_display_name', 'like', $like);
                }

                if (ctype_digit($q)) {
                    $builder->orWhere('player_id', (int) $q)
                        ->orWhereRaw('CAST(player_id AS CHAR) LIKE ?', [$q.'%']);
                }
            });
        }

        if ($filters['nationality'] !== '') {
            $query->whereRaw('UPPER(player_nationality) = ?', [$filters['nationality']]);
        }

        return $query
            ->paginate(self::PER_PAGE, ['*'], 'page', $filters['page'])
            ->through(function (Player $item) use ($countries) {
                $nat = strtoupper(trim((string) ($item->player_nationality ?? '')));
                $desc = (string) ($item->player_status_description ?? '');
                if (strtoupper($desc) === 'NULL') {
                    $desc = '';
                }

                return [
                    'player_id' => (int) $item->player_id,
                    'player_asset_key' => (string) ($item->asset_key ?? ''),
                    'player_fname' => (string) $item->player_fname,
                    'player_lname' => (string) $item->player_lname,
                    'player_display_name' => $item->resolvedDisplayName(),
                    'player_nationality' => $nat,
                    'player_nationality_label' => $nat !== '' ? ($countries[$nat] ?? $nat) : '',
                    'player_status' => (int) $item->player_status ? 1 : 0,
                    'player_status_description' => $desc,
                    'player_uefa_id' => (string) ($item->player_uefa_id ?? ''),
                    'player_fifa_id' => (string) ($item->player_fifa_id ?? ''),
                    'picture_url' => null,
                    'flag_url' => $nat !== '' ? Flag::imageUrl($nat) : null,
                    'flag_html' => $nat !== '' ? Flag::html($nat) : '',
                ];
            });
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private function attachPictureUrls(array $items): array
    {
        if ($items === []) {
            return $items;
        }

        $playerIds = array_values(array_unique(array_map(
            static fn (array $item): int => (int) $item['player_id'],
            $items
        )));

        $playerKeys = [];
        foreach ($items as $item) {
            $playerKeys[(int) $item['player_id']] = (string) ($item['player_asset_key'] ?? '');
        }

        $pictures = Playerteam::query()
            ->join('ffb_team', 'ffb_team.team_id', '=', 'ffb_playerteam.playerteam_team_id')
            ->whereIn('ffb_playerteam.playerteam_player_id', $playerIds)
            ->orderByDesc('ffb_playerteam.playerteam_id')
            ->get([
                'ffb_playerteam.playerteam_player_id',
                'ffb_playerteam.playerteam_team_id',
                'ffb_playerteam.playerteam_id',
                'ffb_team.asset_key as team_asset_key',
            ]);

        $byPlayer = [];
        foreach ($pictures as $row) {
            $playerId = (int) $row->playerteam_player_id;
            if (isset($byPlayer[$playerId])) {
                continue;
            }
            $url = PlayerPicture::url(
                (string) ($row->team_asset_key ?? ''),
                $playerKeys[$playerId] ?? ''
            );
            if (! str_ends_with($url, 'image_na.gif')) {
                $byPlayer[$playerId] = $url;
            }
        }

        foreach ($items as &$item) {
            $item['picture_url'] = $byPlayer[(int) $item['player_id']] ?? null;
        }
        unset($item);

        return $items;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeInput(array $input): array
    {
        $nat = strtoupper(trim((string) ($input['player_nationality'] ?? '')));

        return [
            'player_id' => (string) ($input['player_id'] ?? ''),
            'player_fname' => trim((string) ($input['player_fname'] ?? '')),
            'player_lname' => trim((string) ($input['player_lname'] ?? '')),
            'player_display_name' => trim((string) ($input['player_display_name'] ?? '')),
            'player_nationality' => $nat,
            'player_status' => ((string) ($input['player_status'] ?? '1') === '0') ? 0 : 1,
            'player_status_description' => trim((string) ($input['player_status_description'] ?? '')),
            'player_foreign_id' => trim((string) ($input['player_foreign_id'] ?? '')),
            'player_uefa_id' => trim((string) ($input['player_uefa_id'] ?? '')),
            'player_fifa_id' => trim((string) ($input['player_fifa_id'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validate(array $form, bool $isCreate): array
    {
        $errors = [];

        if ($form['player_fname'] === '' || $form['player_lname'] === '') {
            $errors[] = 'Bitte Vor- und Nachname ausfüllen.';
        }

        $countries = $this->countryOptions();
        if ($form['player_nationality'] !== '' && ! isset($countries[$form['player_nationality']])) {
            $errors[] = 'Die Nationalität ist ungültig.';
        }

        if ($isCreate && $form['player_fname'] !== '' && $form['player_lname'] !== '') {
            $exists = Player::query()
                ->where('player_fname', $form['player_fname'])
                ->where('player_lname', $form['player_lname'])
                ->whereRaw('UPPER(player_nationality) = ?', [$form['player_nationality']])
                ->exists();
            if ($exists) {
                $errors[] = 'Ein Spieler mit diesem Namen und dieser Nationalität existiert bereits.';
            }
        }

        return $errors;
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return list<int>
     */
    private function userteamIdsContainingPlayerteams(array $playerteamIds): array
    {
        if ($playerteamIds === []) {
            return [];
        }

        return Userteam::queryContainingAnyPlayerteam($playerteamIds)
            ->orderBy('userteam_id')
            ->pluck('userteam_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return list<string>
     */
    private function extremeTeamReferences(array $playerteamIds): array
    {
        if ($playerteamIds === []) {
            return [];
        }

        return Extremeteam::queryContainingAnyPlayerteam($playerteamIds)
            ->with('matchround:matchround_id,matchround_title')
            ->orderBy('extremeteam_id')
            ->get()
            ->map(function (Extremeteam $team): string {
                $type = strtoupper((string) $team->extremeteam_top_or_flop);
                $roundTitle = trim((string) ($team->matchround?->matchround_title ?? ''));
                $label = 'Extremeteam-ID '.(int) $team->extremeteam_id.' '.$type;
                if ($roundTitle !== '') {
                    $label .= ' ('.$roundTitle.')';
                }

                return $label;
            })
            ->all();
    }

    /**
     * @param  list<int>  $ids
     */
    private function formatIdList(array $ids, int $limit = 12): string
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        sort($ids);

        if ($ids === []) {
            return '';
        }

        if (count($ids) <= $limit) {
            return implode(', ', $ids);
        }

        return implode(', ', array_slice($ids, 0, $limit)).', …';
    }

    /**
     * @param  list<string>  $labels
     */
    private function formatLabelList(array $labels, int $limit = 8): string
    {
        $labels = array_values(array_filter(array_map('strval', $labels), static fn (string $label): bool => $label !== ''));
        if ($labels === []) {
            return '';
        }

        if (count($labels) <= $limit) {
            return implode('; ', $labels);
        }

        return implode('; ', array_slice($labels, 0, $limit)).'; …';
    }

    /**
     * @return array<string, string>
     */
    private function countryOptions(): array
    {
        $countries = config('countries', []);
        if (! is_array($countries)) {
            return [];
        }

        /** @var array<string, string> $countries */
        asort($countries, SORT_STRING | SORT_FLAG_CASE);

        return $countries;
    }
}
