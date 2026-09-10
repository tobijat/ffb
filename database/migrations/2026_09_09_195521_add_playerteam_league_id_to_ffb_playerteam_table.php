<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds nullable playerteam_league_id. Population + NOT NULL/unique indexes
 * are applied by `php artisan ffb:playerteam-league-backfill --execute --finalize`
 * so historical remapping can run safely in one controlled step.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_playerteam')) {
            return;
        }

        if (! Schema::hasColumn('ffb_playerteam', 'playerteam_league_id')) {
            Schema::table('ffb_playerteam', function (Blueprint $table) {
                $table->integer('playerteam_league_id')->nullable()->after('playerteam_team_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_playerteam')) {
            return;
        }

        if (Schema::hasColumn('ffb_playerteam', 'playerteam_league_id')) {
            Schema::table('ffb_playerteam', function (Blueprint $table) {
                $table->dropColumn('playerteam_league_id');
            });
        }
    }
};
