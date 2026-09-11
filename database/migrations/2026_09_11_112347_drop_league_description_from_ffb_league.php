<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ffb_league') && Schema::hasColumn('ffb_league', 'league_description')) {
            $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
            DB::statement("SET SESSION sql_mode = ''");
            try {
                DB::statement('ALTER TABLE `ffb_league` DROP COLUMN `league_description`');
            } finally {
                DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ffb_league') && ! Schema::hasColumn('ffb_league', 'league_description')) {
            $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
            DB::statement("SET SESSION sql_mode = ''");
            try {
                DB::statement('ALTER TABLE `ffb_league` ADD COLUMN `league_description` mediumtext NULL');
            } finally {
                DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
            }
        }
    }
};
