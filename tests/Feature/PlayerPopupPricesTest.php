<?php

namespace Tests\Feature;

use App\Services\PlayerPopupService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PlayerPopupPricesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function prices_uses_round_performance_not_playerprice_power(): void
    {
        $this->seedPlayerWithPriceAndPerformance(
            price: 8.5,
            playerPower: 99.0,
            avPower: 50.0,
            roundPerformance: -1.0,
        );

        $result = (new PlayerPopupService)->prices(100, 1);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['data']['points']);
        $this->assertSame(8.5, $result['data']['points'][0]['price']);
        $this->assertSame(-1.0, $result['data']['points'][0]['round_performance']);
        $this->assertArrayNotHasKey('power', $result['data']['points'][0]);
        $this->assertArrayNotHasKey('av_power', $result['data']['points'][0]);
    }

    #[Test]
    public function prices_maps_mid_and_top_round_performance(): void
    {
        $this->seedPlayerWithPriceAndPerformance(
            price: 7.0,
            playerPower: 1.0,
            avPower: 1.0,
            roundPerformance: 0.5,
        );

        $mid = (new PlayerPopupService)->prices(100, 1);
        $this->assertSame(0.5, $mid['data']['points'][0]['round_performance']);

        DB::table('ffb_playerstats')->where('playerstats_id', 1)->update([
            'playerstats_round_performance' => 1.0,
        ]);

        $top = (new PlayerPopupService)->prices(100, 1);
        $this->assertSame(1.0, $top['data']['points'][0]['round_performance']);
    }

    #[Test]
    public function prices_returns_null_round_performance_when_missing(): void
    {
        $this->seedPlayerWithPriceAndPerformance(
            price: 6.0,
            playerPower: 3.0,
            avPower: 2.0,
            roundPerformance: null,
        );

        $result = (new PlayerPopupService)->prices(100, 1);

        $this->assertTrue($result['ok']);
        $this->assertNull($result['data']['points'][0]['round_performance']);
    }

    private function seedPlayerWithPriceAndPerformance(
        float $price,
        float $playerPower,
        float $avPower,
        ?float $roundPerformance,
    ): void {
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'league_title' => 'Test League',
        ]);
        DB::table('ffb_team')->insert([
            'team_id' => 10,
            'team_name' => 'Home',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
            'team_nationality' => 'aut',
        ]);
        DB::table('ffb_player')->insert([
            'player_id' => 1,
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_nationality' => 'aut',
            'player_status_description' => '',
        ]);
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => 100,
            'playerteam_player_id' => 1,
            'playerteam_team_id' => 10,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 1,
            'matchround_league_id' => 1,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
            'matchround_status' => 1,
        ]);
        DB::table('ffb_playerprice')->insert([
            'playerprice_id' => 1,
            'playerprice_playerteam_id' => 100,
            'playerprice_matchround_id' => 1,
            'playerprice_price' => $price,
            'playerprice_player_power' => $playerPower,
            'playerprice_av_power' => $avPower,
        ]);
        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 1,
            'playerstats_playerteam_id' => 100,
            'playerstats_matchround_id' => 1,
            'playerstats_match_id' => null,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_assists' => 0,
            'playerstats_score' => 5,
            'playerstats_cards' => 'n',
            'playerstats_round_performance' => $roundPerformance,
        ]);
    }

    private function createSchema(): void
    {
        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
        });
        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_name')->default('');
            $table->integer('team_status')->default(1);
            $table->integer('team_num_players')->default(0);
            $table->string('team_foreign_id')->default('');
            $table->string('team_nationality')->default('');
        });
        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->integer('player_status')->default(1);
            $table->string('player_foreign_id')->default('');
            $table->string('player_nationality')->default('');
            $table->string('player_status_description')->default('');
        });
        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id')->nullable();
            $table->string('playerteam_player_picture')->default('');
            $table->integer('playerteam_status')->default(1);
            $table->string('playerteam_player_position')->default('m');
            $table->string('playerteam_date_transfer')->nullable();
        });
        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->string('matchround_startdate')->nullable();
            $table->integer('matchround_status')->default(0);
        });
        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->unsignedInteger('playerstats_match_id')->nullable();
            $table->integer('playerstats_minutes')->default(0);
            $table->integer('playerstats_goals')->default(0);
            $table->integer('playerstats_assists')->default(0);
            $table->double('playerstats_score')->default(0);
            $table->string('playerstats_cards')->default('n');
            $table->double('playerstats_round_performance')->nullable();
        });
        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
            $table->double('playerprice_player_power')->default(0);
            $table->double('playerprice_av_power')->default(0);
        });
    }
}
