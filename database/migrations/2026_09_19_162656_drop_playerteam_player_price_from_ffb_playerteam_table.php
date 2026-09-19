<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Legacy static roster price; dynamic pricing uses ffb_playerprice / ffb_teamprice.
 * API JSON still exposes playerteam_player_price as a computed field.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_playerteam')) {
            return;
        }

        if (Schema::hasColumn('ffb_playerteam', 'playerteam_player_price')) {
            Schema::table('ffb_playerteam', function (Blueprint $table) {
                $table->dropColumn('playerteam_player_price');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_playerteam')) {
            return;
        }

        if (! Schema::hasColumn('ffb_playerteam', 'playerteam_player_price')) {
            Schema::table('ffb_playerteam', function (Blueprint $table) {
                $table->double('playerteam_player_price')->default(5)->after('playerteam_status');
            });
        }
    }
};
