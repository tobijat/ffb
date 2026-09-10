<?php

namespace Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreatesLegacyFfbSchema
{
    protected function createLegacyFfbSchema(bool $withLeagueId = false): void
    {
        $this->dropLegacyFfbSchema();

        Schema::create('ffb_game', function (Blueprint $table) {
            $table->integer('game_id')->primary();
            $table->string('game_title')->default('');
            $table->tinyInteger('game_visible')->default(1);
            $table->tinyInteger('game_archive')->default(0);
            $table->tinyInteger('game_countdown')->default(0);
            $table->tinyInteger('game_status')->default(1);
            $table->mediumText('game_description')->nullable();
            $table->string('game_symbol')->default('');
        });

        Schema::create('ffb_options', function (Blueprint $table) {
            $table->integer('options_id')->primary();
            $table->integer('options_game_id');
            $table->integer('options_lineup_max_players')->default(11);
            $table->integer('options_lineup_max_players_team')->default(3);
            $table->integer('options_lineup_max_credits')->default(100);
            $table->integer('options_lineup_min_goalies')->default(1);
            $table->integer('options_lineup_max_goalies')->default(1);
            $table->integer('options_lineup_min_defence')->default(3);
            $table->integer('options_lineup_max_defence')->default(5);
            $table->integer('options_lineup_min_midfield')->default(3);
            $table->integer('options_lineup_max_midfield')->default(5);
            $table->integer('options_lineup_min_striker')->default(1);
            $table->integer('options_lineup_max_striker')->default(3);
            $table->string('options_game_pricemode')->default('constant');
            $table->string('options_game_pointsmode')->default('new');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->integer('matchround_id')->primary();
            $table->integer('matchround_game_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->tinyInteger('matchround_status')->default(1);
            $table->integer('matchround_credits')->default(100);
            $table->integer('matchround_max_players_from_team')->default(3);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->integer('match_id')->primary();
            $table->integer('match_round');
            $table->integer('match_hometeam_id')->default(0);
            $table->integer('match_guestteam_id')->default(0);
            $table->integer('match_homescore')->default(-1);
            $table->integer('match_guestscore')->default(-1);
            $table->integer('match_homescore_penalty')->default(-1);
            $table->integer('match_guestscore_penalty')->default(-1);
            $table->string('match_date')->nullable();
            $table->string('match_status')->default('');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->integer('team_id')->primary();
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->double('team_avg_price')->default(5);
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->integer('player_id')->primary();
            $table->string('player_foreign_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) use ($withLeagueId) {
            $table->integer('playerteam_id')->primary();
            $table->integer('playerteam_player_id');
            $table->integer('playerteam_team_id');
            if ($withLeagueId) {
                $table->integer('playerteam_league_id')->nullable();
            }
            $table->string('playerteam_player_picture')->nullable();
            $table->tinyInteger('playerteam_status')->default(1);
            $table->double('playerteam_player_price')->default(0);
            $table->string('playerteam_player_position')->default('m');
            $table->timestamp('playerteam_date_transfer')->nullable();
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->integer('playerstats_id')->primary();
            $table->integer('playerstats_playerteam_id');
            $table->integer('playerstats_matchround_id');
            $table->integer('playerstats_match_id')->nullable();
            $table->integer('playerstats_minutes')->default(0);
            $table->integer('playerstats_goals')->default(0);
            $table->integer('playerstats_assists')->default(0);
            $table->integer('playerstats_score')->default(0);
            $table->string('playerstats_cards')->default('n');
        });

        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->integer('playerprice_id')->primary();
            $table->integer('playerprice_playerteam_id');
            $table->integer('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
            $table->double('playerprice_powers')->default(0);
        });

        Schema::create('ffb_goal', function (Blueprint $table) {
            $table->integer('goal_id')->primary();
            $table->integer('goal_playerteam_id');
            $table->integer('goal_match_id');
        });

        Schema::create('ffb_psgoal', function (Blueprint $table) {
            $table->integer('psgoal_id')->primary();
            $table->integer('psgoal_playerteam_id');
            $table->integer('psgoal_match_id');
        });

        Schema::create('ffb_playerfid', function (Blueprint $table) {
            $table->integer('playerfid_id')->primary();
            $table->integer('playerfid_playerteam_id');
            $table->string('playerfid_name_wf')->default('');
        });

        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->integer('userteam_id')->primary();
            $table->integer('userteam_user_id')->default(0);
            $table->integer('userteam_matchround_id')->nullable();
            $table->double('userteam_price')->default(0);
            $table->double('userteam_score')->default(0);
            $table->double('userteam_wc_points')->default(0);
            $table->string('userteam_date')->nullable();
        });

        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
            $table->unique(['userteam_slot_userteam_id', 'userteam_slot_slot']);
            $table->index('userteam_slot_playerteam_id');
        });

        Schema::create('web_user_details', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_details_ffb_selected_game')->default(0);
        });
    }

    protected function dropLegacyFfbSchema(): void
    {
        foreach ([
            'ffb_userteam_slot',
            'ffb_userteam',
            'ffb_playerfid',
            'ffb_psgoal',
            'ffb_goal',
            'ffb_playerprice',
            'ffb_playerstats',
            'ffb_playerteam',
            'ffb_player',
            'ffb_team',
            'ffb_match',
            'ffb_matchround',
            'ffb_options',
            'ffb_game',
            'web_user_details',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
}
