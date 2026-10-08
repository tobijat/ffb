<?php

namespace App\Services\SeedDb;

use App\Services\FfbPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

/**
 * Build a stripped FFB MySQL seed dump without mutating the source DB.
 * Kept rows retain their original primary/foreign key IDs.
 */
class SeedDbBuilder
{
    public const DEFAULT_PASSWORD = 'password';

    /**
     * Tables omitted from the seed dump entirely.
     *
     * @var list<string>
     */
    private const OMIT_TABLES = [
        'cache',
        'cache_locks',
        'failed_jobs',
        'job_batches',
        'jobs',
        'password_reset_tokens',
        'sessions',
        'users',
        'migrations',
        'web_mail',
        'web_log',
        'ffb_apikey',
        'ffb_cronjob',
        'ffb_rss',
        'ffb_rss_category',
        'ffb_user_award',
        'ffb_user_award_defines',
        'ffb_user_award_finished',
        'old_pic_permission',
        'pic_album',
        'pic_albumcollection',
        'pic_category',
        'pic_collection',
        'pic_comment',
        'pic_image',
        'pic_log',
        'pic_metadata',
        'pic_permission_album',
        'pic_permission_mail',
        'pic_session',
        'pic_tempimage',
    ];

    /**
     * Preferred dump table order (parents before children).
     *
     * @var list<string>
     */
    private const TABLE_ORDER = [
        'ffb_league',
        'ffb_league_options',
        'ffb_matchround',
        'ffb_matchround_options',
        'ffb_team',
        'ffb_teamfid',
        'ffb_teamelo',
        'ffb_teamprice',
        'ffb_player',
        'ffb_playerteam',
        'ffb_playerfid',
        'ffb_playerstats',
        'ffb_playerprice',
        'ffb_match',
        'ffb_goal',
        'ffb_psgoal',
        'web_user',
        'web_user_details',
        'web_user_permissions',
        'web_admin',
        'ffb_admin',
        'ffb_userteam',
        'ffb_userteam_slot',
        'ffb_userteam_substitute_slot',
        'ffb_extremeteam',
        'ffb_extremeteam_slot',
        'ffb_news',
        'ffb_poll',
        'ffb_poll_answer',
        'ffb_poll_result',
        'ffb_userscore',
    ];

    /** @var list<int> */
    private array $leagueIds = [];

    /** @var array<string, int> */
    private array $userIdsByNick = [];

    /** @var array<string, list<array<string, mixed>>> */
    private array $rowsByTable = [];

    public function __construct(
        private readonly FfbPassword $passwords,
    ) {}

    /**
     * @param  list<int>  $leagueIds
     * @param  list<string>  $nicknames
     * @return array{counts: array<string, int>, dump: string, league_ids: list<int>}
     */
    public function buildDump(
        array $leagueIds,
        array $nicknames,
        string $plainPassword,
        string $dumpPath,
        callable $log,
    ): array {
        if (config('database.default') !== 'mysql') {
            throw new RuntimeException('Seed build requires the mysql connection.');
        }

        $this->leagueIds = $leagueIds;
        $this->userIdsByNick = $this->resolveUserIds($nicknames);
        $log('Resolved users: '.json_encode($this->userIdsByNick));

        $log('Collecting keep-set rows…');
        $this->collectRows($log);

        $log('Sanitizing users in memory…');
        $this->sanitizeUsersInMemory($plainPassword);

        $log('Writing dump…');
        $written = $this->writeSqlDump($dumpPath, $log);

        $counts = [];
        foreach ($this->rowsByTable as $table => $rows) {
            $counts[$table] = count($rows);
        }
        ksort($counts);

        return [
            'counts' => $counts,
            'dump' => $written,
            'league_ids' => $this->leagueIds,
        ];
    }

    /**
     * @param  list<string>  $nicknames
     * @return array<string, int>
     */
    private function resolveUserIds(array $nicknames): array
    {
        $resolved = [];
        foreach ($nicknames as $nick) {
            $row = DB::table('web_user')
                ->whereRaw('LOWER(user_nickname) = ?', [strtolower($nick)])
                ->first(['user_id', 'user_nickname']);
            if (! $row) {
                throw new RuntimeException("User not found: {$nick}");
            }
            $resolved[strtolower((string) $row->user_nickname)] = (int) $row->user_id;
        }

        return $resolved;
    }

    private function collectRows(callable $log): void
    {
        $userIds = array_values($this->userIdsByNick);
        $leagueIds = $this->leagueIds;

        $this->loadTable('ffb_league', fn ($q) => $q->whereIn('league_id', $leagueIds));
        $this->loadTable('ffb_league_options', fn ($q) => $q->whereIn('options_league_id', $leagueIds));
        $this->loadTable('ffb_matchround', fn ($q) => $q->whereIn('matchround_league_id', $leagueIds));

        $matchroundIds = array_map(
            fn (array $r) => (int) $r['matchround_id'],
            $this->rowsByTable['ffb_matchround'] ?? [],
        );
        $this->loadTable('ffb_matchround_options', fn ($q) => $q->whereIn('matchround_options_matchround_id', $matchroundIds ?: [-1]));

        $this->loadTable('ffb_match', fn ($q) => $q->whereIn('match_round', $matchroundIds ?: [-1]));
        $matchIds = array_map(fn (array $r) => (int) $r['match_id'], $this->rowsByTable['ffb_match'] ?? []);

        $teamIds = [];
        foreach ($this->rowsByTable['ffb_match'] ?? [] as $row) {
            foreach (['match_hometeam_id', 'match_guestteam_id'] as $col) {
                $id = (int) ($row[$col] ?? 0);
                if ($id > 0) {
                    $teamIds[$id] = true;
                }
            }
        }
        $teamIds = array_keys($teamIds);

        $this->loadTable('ffb_team', fn ($q) => $q->whereIn('team_id', $teamIds ?: [-1]));
        $this->loadTable('ffb_teamfid', fn ($q) => $q->whereIn('teamfid_team_id', $teamIds ?: [-1]));
        $this->loadTable('ffb_teamelo', fn ($q) => $q->whereIn('teamelo_league_id', $leagueIds));
        $this->loadTable('ffb_teamprice', fn ($q) => $q->whereIn('teamprice_matchround_id', $matchroundIds ?: [-1]));

        $this->loadTable('ffb_playerteam', fn ($q) => $q->where(function ($q) use ($leagueIds, $teamIds) {
            $q->whereIn('playerteam_league_id', $leagueIds)
                ->orWhere(function ($q) use ($teamIds) {
                    $q->whereIn('playerteam_team_id', $teamIds ?: [-1])
                        ->where(function ($q) {
                            $q->whereNull('playerteam_league_id')
                                ->orWhere('playerteam_league_id', 0);
                        });
                });
        }));
        $playerteamIds = array_map(
            fn (array $r) => (int) $r['playerteam_id'],
            $this->rowsByTable['ffb_playerteam'] ?? [],
        );
        $playerIds = array_values(array_unique(array_map(
            fn (array $r) => (int) $r['playerteam_player_id'],
            $this->rowsByTable['ffb_playerteam'] ?? [],
        )));

        $this->loadTable('ffb_player', fn ($q) => $q->whereIn('player_id', $playerIds ?: [-1]));
        $this->loadTable('ffb_playerfid', fn ($q) => $q->whereIn('playerfid_playerteam_id', $playerteamIds ?: [-1]));
        $this->loadTable('ffb_playerstats', fn ($q) => $q->whereIn('playerstats_matchround_id', $matchroundIds ?: [-1]));
        $this->loadTable('ffb_playerprice', fn ($q) => $q->whereIn('playerprice_matchround_id', $matchroundIds ?: [-1]));

        $this->loadTable('ffb_goal', fn ($q) => $q->whereIn('goal_match_id', $matchIds ?: [-1]));
        $this->loadTable('ffb_psgoal', fn ($q) => $q->whereIn('psgoal_match_id', $matchIds ?: [-1]));

        $this->loadTable('ffb_userteam', fn ($q) => $q->whereIn('userteam_user_id', $userIds)
            ->whereIn('userteam_matchround_id', $matchroundIds ?: [-1]));
        $userteamIds = array_map(
            fn (array $r) => (int) $r['userteam_id'],
            $this->rowsByTable['ffb_userteam'] ?? [],
        );
        $this->loadTable('ffb_userteam_slot', fn ($q) => $q->whereIn('userteam_slot_userteam_id', $userteamIds ?: [-1]));
        $this->loadTable('ffb_userteam_substitute_slot', fn ($q) => $q->whereIn('substitute_slot_userteam_id', $userteamIds ?: [-1]));

        $this->loadTable('ffb_extremeteam', fn ($q) => $q->whereIn('extremeteam_matchround_id', $matchroundIds ?: [-1]));
        $extremeteamIds = array_map(
            fn (array $r) => (int) $r['extremeteam_id'],
            $this->rowsByTable['ffb_extremeteam'] ?? [],
        );
        $this->loadTable('ffb_extremeteam_slot', fn ($q) => $q->whereIn('extremeteam_slot_extremeteam_id', $extremeteamIds ?: [-1]));

        $this->loadTable('ffb_news', fn ($q) => $q->whereIn('news_league_id', $leagueIds));
        $this->loadTable('ffb_poll', fn ($q) => $q->whereIn('poll_league_id', $leagueIds));
        $pollIds = array_map(fn (array $r) => (int) $r['poll_id'], $this->rowsByTable['ffb_poll'] ?? []);
        $this->loadTable('ffb_poll_answer', fn ($q) => $q->whereIn('poll_answer_poll_id', $pollIds ?: [-1]));
        $this->loadTable('ffb_poll_result', fn ($q) => $q->whereIn('poll_result_poll_id', $pollIds ?: [-1])
            ->whereIn('poll_result_user_id', $userIds));

        $this->loadTable('ffb_userscore', fn ($q) => $q->whereIn('userscore_user_id', $userIds)
            ->whereIn('userscore_league_id', $leagueIds));
        $this->loadTable('ffb_admin', fn ($q) => $q->whereIn('admin_user_id', $userIds)
            ->whereIn('admin_league_id', $leagueIds));

        $this->loadTable('web_user', fn ($q) => $q->whereIn('user_id', $userIds));
        $this->loadTable('web_user_details', fn ($q) => $q->whereIn('user_id', $userIds));
        $this->loadTable('web_user_permissions', fn ($q) => $q->whereIn('user_id', $userIds));
        $this->loadTable('web_admin', fn ($q) => $q->whereIn('admin_user_id', $userIds));

        foreach ($this->rowsByTable as $table => $rows) {
            $log('  '.$table.': '.count($rows));
        }
    }

    /**
     * @param  callable(\Illuminate\Database\Query\Builder): mixed  $constrain
     */
    private function loadTable(string $table, callable $constrain): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }
        $q = DB::table($table);
        $constrain($q);
        $this->rowsByTable[$table] = array_map(
            static fn ($row) => (array) $row,
            $q->get()->all(),
        );
    }

    private function sanitizeUsersInMemory(string $plainPassword): void
    {
        $hash = $this->passwords->hash($plainPassword);
        if (! isset($this->rowsByTable['web_user'])) {
            return;
        }
        foreach ($this->rowsByTable['web_user'] as &$row) {
            $nick = strtolower((string) ($row['user_nickname'] ?? ''));
            $row['user_email'] = $nick.'@example.test';
            $row['user_password'] = $hash;
            $row['user_activation_code'] = '';
            $row['user_ip'] = '127.0.0.1';
            $row['user_lip'] = '127.0.0.1';
            $row['user_fname'] = ucfirst($nick);
            $row['user_lname'] = 'Test';
            $row['user_status'] = 'active';
        }
        unset($row);

        if (! isset($this->rowsByTable['web_user_details'])) {
            return;
        }
        foreach ($this->rowsByTable['web_user_details'] as &$row) {
            $row['user_details_ffb_selected_league'] = $this->leagueIds[0];
            $row['user_details_avatar'] = 'avatar_na.png';
            $row['user_details_photo'] = 'profile_na.png';
            $row['user_details_zip'] = '';
            $row['user_details_city'] = '';
            $row['user_details_street'] = '';
            $row['user_details_phone'] = '';
            $row['user_details_website'] = '';
        }
        unset($row);
    }

    private function writeSqlDump(string $dumpPath, callable $log): string
    {
        $dir = dirname($dumpPath);
        if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException("Cannot create {$dir}");
        }

        $sqlPath = str_ends_with($dumpPath, '.gz')
            ? preg_replace('/\.gz$/', '', $dumpPath) ?: ($dumpPath.'.sql')
            : $dumpPath;
        if (! str_ends_with($sqlPath, '.sql')) {
            $sqlPath .= '.sql';
        }

        $fh = fopen($sqlPath, 'wb');
        if ($fh === false) {
            throw new RuntimeException("Cannot write {$sqlPath}");
        }

        $write = static function (string $chunk) use ($fh): void {
            fwrite($fh, $chunk);
        };

        $write("-- FFB seed dump\n");
        $write("SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

        $order = array_values(array_unique(array_merge(
            self::TABLE_ORDER,
            array_keys($this->rowsByTable),
        )));

        foreach ($order as $table) {
            if (! isset($this->rowsByTable[$table]) || in_array($table, self::OMIT_TABLES, true)) {
                continue;
            }
            if (! Schema::hasTable($table)) {
                continue;
            }
            $create = DB::select('SHOW CREATE TABLE `'.$table.'`');
            $createSql = array_values((array) $create[0])[1] ?? null;
            if (! is_string($createSql) || $createSql === '') {
                throw new RuntimeException("SHOW CREATE TABLE failed for {$table}");
            }
            $write("DROP TABLE IF EXISTS `{$table}`;\n");
            $write($createSql.";\n\n");

            $rows = $this->rowsByTable[$table];
            if ($rows === []) {
                $write("-- {$table}: 0 rows\n\n");
                $log("  wrote {$table}: 0 rows");

                continue;
            }

            $columns = array_keys($rows[0]);
            $colList = implode(', ', array_map(fn ($c) => '`'.$c.'`', $columns));
            $chunkSize = 100;
            $written = 0;
            for ($i = 0; $i < count($rows); $i += $chunkSize) {
                $chunk = array_slice($rows, $i, $chunkSize);
                $values = [];
                foreach ($chunk as $row) {
                    $vals = [];
                    foreach ($columns as $col) {
                        $vals[] = $this->sqlValue($row[$col] ?? null);
                    }
                    $values[] = '('.implode(', ', $vals).')';
                }
                $write("INSERT INTO `{$table}` ({$colList}) VALUES\n".implode(",\n", $values).";\n");
                $written += count($chunk);
            }
            $write("\n");
            $log("  wrote {$table}: {$written} rows");
        }

        $write("SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($fh);

        return $sqlPath;
    }

    private function sqlValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return "'".addslashes((string) $value)."'";
    }
}
