<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerprice;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerpriceService;
use App\Services\EloRatingClient;
use App\Services\LineupOptionsResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPlayerpriceCalculateTest extends TestCase
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
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function calculate_rejects_when_matchround_teams_miss_teamprices(): void
    {
        [$leagueId, $matchroundId, $teamA] = $this->seedLeagueWithMatch();

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamA,
            'teamprice_matchround_id' => $matchroundId,
            'teamprice_price' => 10.0,
        ]);

        $result = $this->service($leagueId)->calculatePlayerPricesForMatchround(544, [
            'matchround_id' => $matchroundId,
            'price_margin' => 2,
        ]);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Teampreis', $result['errors'][0] ?? '');
        $this->assertStringContainsString('Tab Teams', $result['errors'][0] ?? '');
        $this->assertDatabaseCount('ffb_playerprice', 0);
    }

    #[Test]
    public function calculate_uses_teamprice_as_base_not_playerteam_player_price(): void
    {
        [$leagueId, $matchroundId, $teamA, $teamB] = $this->seedLeagueWithMatch();

        Teamprice::query()->insert([
            [
                'teamprice_team_id' => $teamA,
                'teamprice_matchround_id' => $matchroundId,
                'teamprice_price' => 10.0,
            ],
            [
                'teamprice_team_id' => $teamB,
                'teamprice_matchround_id' => $matchroundId,
                'teamprice_price' => 7.0,
            ],
        ]);

        $playerId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Test',
            'player_lname' => 'Player',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $playerteamId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamA,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ], 'playerteam_id');

        MatchGame::query()->insert([
            'match_round' => $matchroundId,
            'match_hometeam_id' => $teamA,
            'match_guestteam_id' => $teamB,
            'match_date' => '2026-08-01',
            'match_status' => '',
            'match_minutes' => 90,
        ]);

        $elo = Mockery::mock(EloRatingClient::class);
        $elo->shouldReceive('ratingsForTeamList')
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 1800.0],
                ['team_id' => $teamB, 'elo_rating' => 1500.0],
            ]);

        $result = $this->service($leagueId, $elo)->calculatePlayerPricesForMatchround(544, [
            'matchround_id' => $matchroundId,
            'price_margin' => 2,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseHas('ffb_playerprice', [
            'playerprice_playerteam_id' => $playerteamId,
            'playerprice_matchround_id' => $matchroundId,
        ]);

        $price = (float) Playerprice::query()
            ->where('playerprice_playerteam_id', $playerteamId)
            ->where('playerprice_matchround_id', $matchroundId)
            ->value('playerprice_price');

        $this->assertSame(10.0, $price);
    }

    private function service(int $leagueId, ?EloRatingClient $elo = null): AdminPlayerpriceService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('selectedLeagueId')->andReturn($leagueId);

        return new AdminPlayerpriceService(
            $adminCenter,
            $elo ?? Mockery::mock(EloRatingClient::class),
            new LineupOptionsResolver,
        );
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int}
     */
    private function seedLeagueWithMatch(): array
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_symbol' => '',
            'league_archive' => 0,
        ], 'league_id');

        $matchroundId = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');

        $teamA = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Team A',
            'team_nationality' => 'aaa',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $teamB = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Team B',
            'team_nationality' => 'bbb',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        MatchGame::query()->insert([
            'match_round' => $matchroundId,
            'match_hometeam_id' => $teamA,
            'match_guestteam_id' => $teamB,
            'match_date' => '2026-10-01',
            'match_status' => '',
            'match_minutes' => 0,
        ]);

        return [$leagueId, $matchroundId, $teamA, $teamB];
    }

    private function createSchema(): void
    {
        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
            $table->string('league_symbol')->default('');
            $table->integer('league_archive')->default(0);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->integer('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->integer('matchround_status')->default(1);
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
            $table->integer('match_minutes')->default(0);
        });

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
            $table->unique(['teamprice_team_id', 'teamprice_matchround_id']);
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
            $table->unsignedInteger('playerstats_match_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->double('playerstats_score')->default(0);
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
