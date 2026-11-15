<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_player')) {
            return;
        }

        if (! Schema::hasColumn('ffb_player', 'player_display_name')) {
            Schema::table('ffb_player', function (Blueprint $table) {
                $table->string('player_display_name', 255)->default('')->after('player_lname');
            });
        }

        // Backfill: fname == lname → fname; else "fname lname".
        DB::update("
            UPDATE ffb_player
            SET player_display_name = CASE
                WHEN TRIM(player_fname) = TRIM(player_lname) THEN TRIM(player_fname)
                WHEN TRIM(player_fname) = '' THEN TRIM(player_lname)
                WHEN TRIM(player_lname) = '' THEN TRIM(player_fname)
                ELSE TRIM(CONCAT(TRIM(player_fname), ' ', TRIM(player_lname)))
            END
            WHERE TRIM(player_display_name) = ''
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_player') || ! Schema::hasColumn('ffb_player', 'player_display_name')) {
            return;
        }

        Schema::table('ffb_player', function (Blueprint $table) {
            $table->dropColumn('player_display_name');
        });
    }
};
