<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rename WorldCup/WC scoring columns and rankmode value to LigaCup/LC.
 */
return new class extends Migration
{
    public function up(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasTable('ffb_league_options')) {
                if (Schema::hasColumn('ffb_league_options', 'options_league_wcpoints')
                    && ! Schema::hasColumn('ffb_league_options', 'options_league_lcpoints')) {
                    DB::statement('ALTER TABLE `ffb_league_options` CHANGE COLUMN `options_league_wcpoints` `options_league_lcpoints` varchar(255) NOT NULL DEFAULT \'new\'');
                }

                if (Schema::hasColumn('ffb_league_options', 'options_league_rankmode')) {
                    DB::table('ffb_league_options')
                        ->where('options_league_rankmode', 'wc')
                        ->update(['options_league_rankmode' => 'lc']);
                }
            }

            if (Schema::hasTable('ffb_userscore')
                && Schema::hasColumn('ffb_userscore', 'userscore_wc_points')
                && ! Schema::hasColumn('ffb_userscore', 'userscore_lc_points')) {
                DB::statement('ALTER TABLE `ffb_userscore` CHANGE COLUMN `userscore_wc_points` `userscore_lc_points` int(11) NOT NULL DEFAULT 0');
            }

            if (Schema::hasTable('ffb_userteam')
                && Schema::hasColumn('ffb_userteam', 'userteam_wc_points')
                && ! Schema::hasColumn('ffb_userteam', 'userteam_lc_points')) {
                DB::statement('ALTER TABLE `ffb_userteam` CHANGE COLUMN `userteam_wc_points` `userteam_lc_points` double NOT NULL DEFAULT 0');
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
            if (Schema::hasTable('ffb_userteam')
                && Schema::hasColumn('ffb_userteam', 'userteam_lc_points')
                && ! Schema::hasColumn('ffb_userteam', 'userteam_wc_points')) {
                DB::statement('ALTER TABLE `ffb_userteam` CHANGE COLUMN `userteam_lc_points` `userteam_wc_points` double NOT NULL DEFAULT 0');
            }

            if (Schema::hasTable('ffb_userscore')
                && Schema::hasColumn('ffb_userscore', 'userscore_lc_points')
                && ! Schema::hasColumn('ffb_userscore', 'userscore_wc_points')) {
                DB::statement('ALTER TABLE `ffb_userscore` CHANGE COLUMN `userscore_lc_points` `userscore_wc_points` int(11) NOT NULL DEFAULT 0');
            }

            if (Schema::hasTable('ffb_league_options')) {
                if (Schema::hasColumn('ffb_league_options', 'options_league_rankmode')) {
                    DB::table('ffb_league_options')
                        ->where('options_league_rankmode', 'lc')
                        ->update(['options_league_rankmode' => 'wc']);
                }

                if (Schema::hasColumn('ffb_league_options', 'options_league_lcpoints')
                    && ! Schema::hasColumn('ffb_league_options', 'options_league_wcpoints')) {
                    DB::statement('ALTER TABLE `ffb_league_options` CHANGE COLUMN `options_league_lcpoints` `options_league_wcpoints` varchar(255) NOT NULL DEFAULT \'new\'');
                }
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }
};
