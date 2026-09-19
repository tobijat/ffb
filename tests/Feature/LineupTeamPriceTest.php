<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Models\Teamprice;
use App\Models\UserDetails;
use App\Services\LineupService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LineupTeamPriceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-09-16 12:00:00'));
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('web_user_details');
        parent::tearDown();
    }

    #[Test]
    public function matchround_teams_use_teamprice_and_omit_missing_prices(): void
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_symbol' => '',
            'league_archive' => 0,
        ], 'league_id');

        LeagueOptions::query()->insert([
            'options_league_id' => $leagueId,
            'options_league_pricemode' => 'dynamic',
        ]);

        UserDetails::query()->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => $leagueId,
        ]);

        $roundId = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Future',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');

        $pricedTeam = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $unpricedTeam = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Belgium',
            'team_nationality' => 'bel',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        MatchGame::query()->insert([
            'match_round' => $roundId,
            'match_hometeam_id' => $pricedTeam,
            'match_guestteam_id' => $unpricedTeam,
            'match_date' => '2026-10-01',
            'match_status' => '',
        ]);

        Teamprice::query()->insert([
            'teamprice_team_id' => $pricedTeam,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 7.5,
        ]);

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertTrue($result['ok']);
        $teams = collect($result['data']['matchround']['teams'])->keyBy('team_id');

        $this->assertSame(7.5, $teams[$pricedTeam]['team_price']);
        $this->assertNull($teams[$unpricedTeam]['team_price']);
    }

    private function createSchema(): void
    {
        Schema::create('web_user_details', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_details_ffb_selected_league')->default(0);
        });

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
            $table->string('league_symbol')->default('');
            $table->integer('league_archive')->default(0);
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->integer('options_league_id')->default(0);
            $table->string('options_league_pricemode')->default('dynamic');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->integer('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->integer('matchround_status')->default(1);
        });

        Schema::create('ffb_matchround_options', function (Blueprint $table) {
            $table->increments('matchround_options_id');
            $table->integer('matchround_options_matchround_id');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->integer('match_round');
            $table->integer('match_hometeam_id');
            $table->integer('match_guestteam_id');
            $table->string('match_date')->nullable();
            $table->string('match_status')->default('');
        });

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
        });
    }
}
