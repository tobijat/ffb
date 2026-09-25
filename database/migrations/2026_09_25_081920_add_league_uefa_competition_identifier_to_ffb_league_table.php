<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_league')) {
            return;
        }

        if (! Schema::hasColumn('ffb_league', 'league_uefa_competition_identifier')) {
            Schema::table('ffb_league', function (Blueprint $table) {
                $table->string('league_uefa_competition_identifier', 255)->default('')->after('league_symbol');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_league')) {
            return;
        }

        if (Schema::hasColumn('ffb_league', 'league_uefa_competition_identifier')) {
            Schema::table('ffb_league', function (Blueprint $table) {
                $table->dropColumn('league_uefa_competition_identifier');
            });
        }
    }
};
