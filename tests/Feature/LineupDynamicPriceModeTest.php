<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerprice;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
use App\Models\UserDetails;
use App\Services\LineupService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LineupDynamicPriceModeTest extends TestCase
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
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('web_user_details');
        parent::tearDown();
    }

    #[Test]
    public function matchround_rejects_non_dynamic_pricemode(): void
    {
        $leagueId = $this->seedLeague('constant');

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['status']);
        $this->assertStringContainsString('dynamische Preismodell', $result['error'] ?? '');
    }

    #[Test]
    public function team_players_use_playerprice_not_playerteam_player_price(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(9.5, $result['data']['players'][0]['playerteam_player_price']);
    }

    #[Test]
    public function team_players_fall_back_to_teamprice_when_playerprice_missing(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamId,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 6.0,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(6.0, $result['data']['players'][0]['playerteam_player_price']);
        $this->assertDatabaseMissing('ffb_playerprice', [
            'playerprice_playerteam_id' => $ptId,
        ]);
    }

    private function seedLeague(string $priceMode): int
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_symbol' => '',
            'league_archive' => 0,
        ], 'league_id');

        LeagueOptions::query()->insert([
            'options_league_id' => $leagueId,
            'options_league_pricemode' => $priceMode,
        ]);

        UserDetails::query()->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => $leagueId,
        ]);

        Matchround::query()->insert([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Future',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-02 00:00:00',
            'matchround_status' => 1,
        ]);

        return $leagueId;
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int}
     */
    private function seedDynamicSquad(): array
    {
        $leagueId = $this->seedLeague('dynamic');

        $roundId = (int) Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->value('matchround_id');

        $teamId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $playerId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $ptId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ], 'playerteam_id');

        return [$leagueId, $roundId, $teamId, $ptId];
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

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_foreign_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->integer('player_status')->default(1);
            $table->string('player_status_description')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id')->default(0);
            $table->string('playerteam_player_picture')->default('');
            $table->integer('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_date_transfer')->default('2008-01-01 00:00:00');
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->integer('playerstats_score')->default(0);
        });

        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
            $table->double('playerprice_player_power')->default(0);
            $table->double('playerprice_av_power')->default(0);
        });

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
        });
    }
}
