<?php

namespace Tests\Feature;

use App\Services\AdminDbCleanupService;
use App\Services\PlayerteamLeagueInventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PlayerteamLeagueInventoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required for in-memory legacy schema fixtures.');
        }

        $this->createLegacyTables();
    }

    #[Test]
    public function it_flags_unreferenced_playerteams_and_derives_leagues_from_stats(): void
    {
        DB::table('ffb_league')->insert(['league_id' => 1, 'league_title' => 'Euro']);
        DB::table('ffb_league')->insert(['league_id' => 2, 'league_title' => 'WC']);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 10,
            'matchround_league_id' => 1,
            'matchround_title' => 'R1',
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 20,
            'matchround_league_id' => 2,
            'matchround_title' => 'R1',
        ]);

        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => 100,
            'playerteam_player_id' => 1,
            'playerteam_team_id' => 5,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_price' => 5,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => 200,
            'playerteam_player_id' => 2,
            'playerteam_team_id' => 5,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_price' => 5,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);

        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 1,
            'playerstats_playerteam_id' => 100,
            'playerstats_matchround_id' => 10,
            'playerstats_match_id' => 1,
        ]);
        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 2,
            'playerstats_playerteam_id' => 100,
            'playerstats_matchround_id' => 20,
            'playerstats_match_id' => 2,
        ]);

        $cleanup = Mockery::mock(AdminDbCleanupService::class);
        $cleanup->shouldReceive('duplicatePlayerteamGroups')->andReturn([]);
        $this->app->instance(AdminDbCleanupService::class, $cleanup);

        $report = $this->app->make(PlayerteamLeagueInventoryService::class)->report();

        $this->assertSame(2, $report['counts']['playerteam_total']);
        $this->assertSame(1, $report['counts']['playerteam_referenced']);
        $this->assertSame(1, $report['counts']['playerteam_unreferenced']);
        $this->assertSame([200], $report['unreferenced_playerteam_ids']);
        $this->assertSame(1, $report['counts']['playerteam_in_multiple_leagues']);
        $this->assertSame(100, $report['multi_league_playerteams_sample'][0]['playerteam_id']);
        $this->assertSame([1, 2], $report['multi_league_playerteams_sample'][0]['league_ids']);
        $this->assertFalse($report['schema']['has_playerteam_league_id']);
    }

    private function createLegacyTables(): void
    {
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_playerfid');
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function ($table) {
            $table->integer('league_id')->primary();
            $table->string('league_title');
        });
        Schema::create('ffb_matchround', function ($table) {
            $table->integer('matchround_id')->primary();
            $table->integer('matchround_league_id');
            $table->string('matchround_title')->nullable();
        });
        Schema::create('ffb_match', function ($table) {
            $table->integer('match_id')->primary();
            $table->integer('match_round');
        });
        Schema::create('ffb_playerteam', function ($table) {
            $table->integer('playerteam_id')->primary();
            $table->integer('playerteam_player_id');
            $table->integer('playerteam_team_id');
            $table->string('playerteam_player_picture')->nullable();
            $table->integer('playerteam_status')->default(1);
            $table->double('playerteam_player_price')->default(0);
            $table->string('playerteam_player_position')->nullable();
            $table->timestamp('playerteam_date_transfer')->nullable();
        });
        Schema::create('ffb_playerstats', function ($table) {
            $table->integer('playerstats_id')->primary();
            $table->integer('playerstats_playerteam_id');
            $table->integer('playerstats_matchround_id');
            $table->integer('playerstats_match_id')->nullable();
        });
        Schema::create('ffb_playerprice', function ($table) {
            $table->integer('playerprice_id')->primary();
            $table->integer('playerprice_playerteam_id');
            $table->integer('playerprice_matchround_id');
        });
        Schema::create('ffb_goal', function ($table) {
            $table->integer('goal_id')->primary();
            $table->integer('goal_playerteam_id');
            $table->integer('goal_match_id');
        });
        Schema::create('ffb_psgoal', function ($table) {
            $table->integer('psgoal_id')->primary();
            $table->integer('psgoal_playerteam_id');
            $table->integer('psgoal_match_id');
        });
        Schema::create('ffb_playerfid', function ($table) {
            $table->integer('playerfid_id')->primary();
            $table->integer('playerfid_playerteam_id');
        });
        Schema::create('ffb_userteam', function ($table) {
            $table->integer('userteam_id')->primary();
            $table->integer('userteam_matchround_id')->nullable();
        });
        Schema::create('ffb_userteam_slot', function ($table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
        });
    }
}
