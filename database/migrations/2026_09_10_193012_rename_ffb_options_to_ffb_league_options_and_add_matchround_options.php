<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rename ffb_options → ffb_league_options, drop unused status columns,
 * add optional per-matchround lineup overrides, and retire matchround_max_players_from_team.
 */
return new class extends Migration
{
    private const LINEUP_COLUMNS = [
        'options_lineup_max_players',
        'options_lineup_max_credits',
        'options_lineup_max_players_team',
        'options_lineup_min_g',
        'options_lineup_min_d',
        'options_lineup_min_m',
        'options_lineup_min_s',
        'options_lineup_max_g',
        'options_lineup_max_d',
        'options_lineup_max_m',
        'options_lineup_max_s',
    ];

    private const STATUS_COLUMNS = [
        'options_status_error',
        'options_status_error_validation',
        'options_status_success',
        'options_status_success_insert',
        'options_status_success_update',
        'options_status_success_delete',
    ];

    public function up(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasTable('ffb_options') && ! Schema::hasTable('ffb_league_options')) {
                DB::statement('RENAME TABLE `ffb_options` TO `ffb_league_options`');
            }

            foreach (self::STATUS_COLUMNS as $column) {
                if (Schema::hasTable('ffb_league_options') && Schema::hasColumn('ffb_league_options', $column)) {
                    DB::statement("ALTER TABLE `ffb_league_options` DROP COLUMN `{$column}`");
                }
            }

            if (! Schema::hasTable('ffb_matchround_options')) {
                DB::statement('
                    CREATE TABLE `ffb_matchround_options` (
                        `matchround_options_id` int(11) NOT NULL AUTO_INCREMENT,
                        `matchround_options_matchround_id` int(11) NOT NULL,
                        `matchround_options_lineup_max_players` int(11) NOT NULL DEFAULT 11,
                        `matchround_options_lineup_max_credits` double NOT NULL DEFAULT 100,
                        `matchround_options_lineup_max_players_team` int(11) NOT NULL DEFAULT 3,
                        `matchround_options_lineup_min_g` int(11) NOT NULL DEFAULT 1,
                        `matchround_options_lineup_min_d` int(11) NOT NULL DEFAULT 3,
                        `matchround_options_lineup_min_m` int(11) NOT NULL DEFAULT 3,
                        `matchround_options_lineup_min_s` int(11) NOT NULL DEFAULT 1,
                        `matchround_options_lineup_max_g` int(11) NOT NULL DEFAULT 1,
                        `matchround_options_lineup_max_d` int(11) NOT NULL DEFAULT 5,
                        `matchround_options_lineup_max_m` int(11) NOT NULL DEFAULT 5,
                        `matchround_options_lineup_max_s` int(11) NOT NULL DEFAULT 3,
                        PRIMARY KEY (`matchround_options_id`),
                        UNIQUE KEY `ffb_matchround_options_matchround_unique` (`matchround_options_matchround_id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
                ');
            }

            $this->backfillFromLegacyMaxPlayersFromTeam();

            if (Schema::hasTable('ffb_matchround') && Schema::hasColumn('ffb_matchround', 'matchround_max_players_from_team')) {
                DB::statement('ALTER TABLE `ffb_matchround` DROP COLUMN `matchround_max_players_from_team`');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    public function down(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasTable('ffb_matchround') && ! Schema::hasColumn('ffb_matchround', 'matchround_max_players_from_team')) {
                DB::statement('ALTER TABLE `ffb_matchround` ADD COLUMN `matchround_max_players_from_team` int(11) NOT NULL DEFAULT 0');
            }

            if (Schema::hasTable('ffb_matchround_options') && Schema::hasColumn('ffb_matchround', 'matchround_max_players_from_team')) {
                $rows = DB::table('ffb_matchround_options')->get([
                    'matchround_options_matchround_id',
                    'matchround_options_lineup_max_players_team',
                ]);
                foreach ($rows as $row) {
                    DB::table('ffb_matchround')
                        ->where('matchround_id', $row->matchround_options_matchround_id)
                        ->update([
                            'matchround_max_players_from_team' => (int) $row->matchround_options_lineup_max_players_team,
                        ]);
                }
            }

            Schema::dropIfExists('ffb_matchround_options');

            foreach ([
                'options_status_error' => 'int(11) NOT NULL DEFAULT 500',
                'options_status_error_validation' => 'int(11) NOT NULL DEFAULT 501',
                'options_status_success' => 'int(11) NOT NULL DEFAULT 200',
                'options_status_success_insert' => 'int(11) NOT NULL DEFAULT 201',
                'options_status_success_update' => 'int(11) NOT NULL DEFAULT 202',
                'options_status_success_delete' => 'int(11) NOT NULL DEFAULT 203',
            ] as $column => $typeSql) {
                if (Schema::hasTable('ffb_league_options') && ! Schema::hasColumn('ffb_league_options', $column)) {
                    DB::statement("ALTER TABLE `ffb_league_options` ADD COLUMN `{$column}` {$typeSql}");
                }
            }

            if (Schema::hasTable('ffb_league_options') && ! Schema::hasTable('ffb_options')) {
                DB::statement('RENAME TABLE `ffb_league_options` TO `ffb_options`');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    private function backfillFromLegacyMaxPlayersFromTeam(): void
    {
        if (! Schema::hasTable('ffb_matchround')
            || ! Schema::hasColumn('ffb_matchround', 'matchround_max_players_from_team')
            || ! Schema::hasTable('ffb_league_options')
            || ! Schema::hasTable('ffb_matchround_options')
        ) {
            return;
        }

        $rounds = DB::table('ffb_matchround')
            ->where('matchround_max_players_from_team', '>', 0)
            ->get([
                'matchround_id',
                'matchround_league_id',
                'matchround_max_players_from_team',
            ]);

        foreach ($rounds as $round) {
            $exists = DB::table('ffb_matchround_options')
                ->where('matchround_options_matchround_id', $round->matchround_id)
                ->exists();
            if ($exists) {
                continue;
            }

            $league = DB::table('ffb_league_options')
                ->where('options_league_id', $round->matchround_league_id)
                ->first(self::LINEUP_COLUMNS);

            $payload = [
                'matchround_options_matchround_id' => (int) $round->matchround_id,
                'matchround_options_lineup_max_players' => (int) ($league->options_lineup_max_players ?? 11),
                'matchround_options_lineup_max_credits' => (float) ($league->options_lineup_max_credits ?? 100),
                'matchround_options_lineup_max_players_team' => (int) $round->matchround_max_players_from_team,
                'matchround_options_lineup_min_g' => (int) ($league->options_lineup_min_g ?? 1),
                'matchround_options_lineup_min_d' => (int) ($league->options_lineup_min_d ?? 3),
                'matchround_options_lineup_min_m' => (int) ($league->options_lineup_min_m ?? 3),
                'matchround_options_lineup_min_s' => (int) ($league->options_lineup_min_s ?? 1),
                'matchround_options_lineup_max_g' => (int) ($league->options_lineup_max_g ?? 1),
                'matchround_options_lineup_max_d' => (int) ($league->options_lineup_max_d ?? 5),
                'matchround_options_lineup_max_m' => (int) ($league->options_lineup_max_m ?? 5),
                'matchround_options_lineup_max_s' => (int) ($league->options_lineup_max_s ?? 3),
            ];

            DB::table('ffb_matchround_options')->insert($payload);
        }
    }
};
