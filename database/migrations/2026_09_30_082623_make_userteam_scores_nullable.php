<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unscored lineups use NULL so a generated score of 0 remains distinguishable.
     */
    public function up(): void
    {
        if (! Schema::hasTable('ffb_userteam')) {
            return;
        }

        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasColumn('ffb_userteam', 'userteam_score')) {
                DB::statement('ALTER TABLE `ffb_userteam` MODIFY `userteam_score` double NULL DEFAULT NULL');
            }

            if (Schema::hasColumn('ffb_userteam', 'userteam_lc_points')) {
                DB::statement('ALTER TABLE `ffb_userteam` MODIFY `userteam_lc_points` double NULL DEFAULT NULL');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_userteam')) {
            return;
        }

        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasColumn('ffb_userteam', 'userteam_score')) {
                DB::statement('UPDATE `ffb_userteam` SET `userteam_score` = 0 WHERE `userteam_score` IS NULL');
                DB::statement('ALTER TABLE `ffb_userteam` MODIFY `userteam_score` double NOT NULL DEFAULT 0');
            }

            if (Schema::hasColumn('ffb_userteam', 'userteam_lc_points')) {
                DB::statement('UPDATE `ffb_userteam` SET `userteam_lc_points` = 0 WHERE `userteam_lc_points` IS NULL');
                DB::statement('ALTER TABLE `ffb_userteam` MODIFY `userteam_lc_points` double NOT NULL DEFAULT 0');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }
};
