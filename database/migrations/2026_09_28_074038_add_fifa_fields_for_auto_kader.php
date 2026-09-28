<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ffb_league') && ! Schema::hasColumn('ffb_league', 'league_fifa_competition_identifier')) {
            Schema::table('ffb_league', function (Blueprint $table) {
                $table->string('league_fifa_competition_identifier', 255)->default('')->after('league_uefa_competition_identifier');
            });
        }

        if (Schema::hasTable('ffb_player') && ! Schema::hasColumn('ffb_player', 'player_fifa_id')) {
            Schema::table('ffb_player', function (Blueprint $table) {
                $table->string('player_fifa_id', 64)->default('')->after('player_uefa_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ffb_league') && Schema::hasColumn('ffb_league', 'league_fifa_competition_identifier')) {
            Schema::table('ffb_league', function (Blueprint $table) {
                $table->dropColumn('league_fifa_competition_identifier');
            });
        }

        if (Schema::hasTable('ffb_player') && Schema::hasColumn('ffb_player', 'player_fifa_id')) {
            Schema::table('ffb_player', function (Blueprint $table) {
                $table->dropColumn('player_fifa_id');
            });
        }
    }
};
