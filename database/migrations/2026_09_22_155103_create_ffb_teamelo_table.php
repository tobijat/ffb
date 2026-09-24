<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ffb_teamelo')) {
            return;
        }

        Schema::create('ffb_teamelo', function (Blueprint $table) {
            $table->increments('teamelo_id');
            $table->unsignedInteger('teamelo_team_id');
            $table->unsignedInteger('teamelo_league_id');
            $table->double('teamelo_elo');
            $table->unsignedSmallInteger('teamelo_elo_year');

            $table->unique(
                ['teamelo_team_id', 'teamelo_league_id'],
                'teamelo_team_league_unique',
            );
            $table->index('teamelo_league_id', 'teamelo_league_index');
            $table->index('teamelo_elo_year', 'teamelo_elo_year_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ffb_teamelo');
    }
};
