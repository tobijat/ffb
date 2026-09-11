<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Drop obsolete league_status. Preserve "inactive" by forcing those rows invisible first.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_league') || ! Schema::hasColumn('ffb_league', 'league_status')) {
            return;
        }

        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            DB::table('ffb_league')
                ->where('league_status', 0)
                ->update(['league_visible' => 0]);

            DB::statement('ALTER TABLE `ffb_league` DROP COLUMN `league_status`');
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_league') || Schema::hasColumn('ffb_league', 'league_status')) {
            return;
        }

        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            DB::statement('ALTER TABLE `ffb_league` ADD COLUMN `league_status` tinyint(4) NOT NULL DEFAULT 1');
            DB::table('ffb_league')
                ->where('league_visible', 0)
                ->update(['league_status' => 0]);
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }
};
