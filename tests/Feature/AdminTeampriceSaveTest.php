<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Models\Teamprice;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerpriceService;
use App\Services\EloRatingClient;
use App\Services\LineupOptionsResolver;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTeampriceSaveTest extends TestCase
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
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function save_writes_prices_only_for_future_matchrounds_when_none_selected(): void
    {
        [$leagueId, $pastId, $futureA, $futureB, $teamA, $teamB] = $this->seedLeague();

        $elo = Mockery::mock(EloRatingClient::class);
        $elo->shouldReceive('ratingsForTeamList')
            ->once()
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 2000.0],
                ['team_id' => $teamB, 'elo_rating' => 1500.0],
            ]);

        $limits = Mockery::mock(LineupOptionsResolver::class);
        $limits->shouldReceive('forLeague')->andReturn($this->lineupLimits());
        $limits->shouldReceive('forMatchround')->andReturn($this->lineupLimits());

        $service = new AdminPlayerpriceService(
            Mockery::mock(AdminCenterService::class),
            $elo,
            $limits,
        );

        $result = $service->saveEloTeamPrices(544, [
            'price_league_id' => $leagueId,
            'matchround_id' => '',
            'max_credits' => 100,
            'max_players_team' => 3,
            'exponent' => 2,
            'dream_team_ratio' => 1.5,
            'min_price' => 1,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseCount('ffb_teamprice', 4);
        $this->assertDatabaseMissing('ffb_teamprice', ['teamprice_matchround_id' => $pastId]);
        $this->assertDatabaseHas('ffb_teamprice', [
            'teamprice_team_id' => $teamA,
            'teamprice_matchround_id' => $futureA,
        ]);
        $this->assertDatabaseHas('ffb_teamprice', [
            'teamprice_team_id' => $teamB,
            'teamprice_matchround_id' => $futureB,
        ]);

        $priceA = (float) Teamprice::query()
            ->where('teamprice_team_id', $teamA)
            ->where('teamprice_matchround_id', $futureA)
            ->value('teamprice_price');
        $priceB = (float) Teamprice::query()
            ->where('teamprice_team_id', $teamB)
            ->where('teamprice_matchround_id', $futureA)
            ->value('teamprice_price');
        $this->assertGreaterThan($priceB, $priceA);
    }

    #[Test]
    public function save_for_selected_future_round_does_not_touch_other_rounds(): void
    {
        [$leagueId, $pastId, $futureA, $futureB, $teamA, $teamB] = $this->seedLeague();

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamA,
            'teamprice_matchround_id' => $pastId,
            'teamprice_price' => 9.9,
        ]);

        $elo = Mockery::mock(EloRatingClient::class);
        $elo->shouldReceive('ratingsForTeamList')
            ->once()
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 2000.0],
                ['team_id' => $teamB, 'elo_rating' => 1500.0],
            ]);

        $limits = Mockery::mock(LineupOptionsResolver::class);
        $limits->shouldReceive('forLeague')->andReturn($this->lineupLimits());
        $limits->shouldReceive('forMatchround')->andReturn($this->lineupLimits());

        $service = new AdminPlayerpriceService(
            Mockery::mock(AdminCenterService::class),
            $elo,
            $limits,
        );

        $result = $service->saveEloTeamPrices(544, [
            'price_league_id' => $leagueId,
            'matchround_id' => $futureA,
            'max_credits' => 100,
            'max_players_team' => 3,
            'exponent' => 2,
            'dream_team_ratio' => 1.5,
            'min_price' => 1,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame(9.9, (float) Teamprice::query()
            ->where('teamprice_team_id', $teamA)
            ->where('teamprice_matchround_id', $pastId)
            ->value('teamprice_price'));
        $this->assertDatabaseHas('ffb_teamprice', [
            'teamprice_team_id' => $teamA,
            'teamprice_matchround_id' => $futureA,
        ]);
        $this->assertDatabaseMissing('ffb_teamprice', [
            'teamprice_team_id' => $teamA,
            'teamprice_matchround_id' => $futureB,
        ]);
    }

    #[Test]
    public function save_rejects_past_matchround(): void
    {
        [$leagueId, $pastId] = $this->seedLeague();

        $service = new AdminPlayerpriceService(
            Mockery::mock(AdminCenterService::class),
            Mockery::mock(EloRatingClient::class),
            new LineupOptionsResolver,
        );

        $result = $service->saveEloTeamPrices(544, [
            'price_league_id' => $leagueId,
            'matchround_id' => $pastId,
            'max_credits' => 100,
            'max_players_team' => 3,
            'exponent' => 2,
            'dream_team_ratio' => 1.5,
            'min_price' => 1,
        ]);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Zukunft', $result['errors'][0] ?? '');
        $this->assertDatabaseCount('ffb_teamprice', 0);
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int, 4: int, 5: int}
     */
    private function seedLeague(): array
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_symbol' => '',
            'league_archive' => 0,
        ], 'league_id');

        $pastId = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Past',
            'matchround_startdate' => '2026-09-01 00:00:00',
            'matchround_enddate' => '2026-09-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');

        $futureA = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Future A',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');

        $futureB = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Future B',
            'matchround_startdate' => '2026-11-01 00:00:00',
            'matchround_enddate' => '2026-11-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');

        $teamA = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Top',
            'team_nationality' => 'aaa',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $teamB = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Low',
            'team_nationality' => 'bbb',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        MatchGame::query()->insert([
            'match_round' => $futureA,
            'match_hometeam_id' => $teamA,
            'match_guestteam_id' => $teamB,
            'match_date' => '2026-10-01',
            'match_status' => '',
        ]);
        MatchGame::query()->insert([
            'match_round' => $futureB,
            'match_hometeam_id' => $teamA,
            'match_guestteam_id' => $teamB,
            'match_date' => '2026-11-01',
            'match_status' => '',
        ]);
        MatchGame::query()->insert([
            'match_round' => $pastId,
            'match_hometeam_id' => $teamA,
            'match_guestteam_id' => $teamB,
            'match_date' => '2026-09-01',
            'match_status' => '',
        ]);

        return [$leagueId, $pastId, $futureA, $futureB, $teamA, $teamB];
    }

    /**
     * @return array<string, mixed>
     */
    private function lineupLimits(): array
    {
        return [
            'lineup_max_players' => 11,
            'lineup_max_credits' => 100.0,
            'lineup_max_players_team' => 3,
            'lineup_min_g' => 1,
            'lineup_min_d' => 3,
            'lineup_min_m' => 3,
            'lineup_min_s' => 1,
            'lineup_max_g' => 1,
            'lineup_max_d' => 5,
            'lineup_max_m' => 5,
            'lineup_max_s' => 3,
            'source' => 'fallback',
        ];
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
            $table->double('team_avg_price')->default(5);
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
            $table->unique(['teamprice_team_id', 'teamprice_matchround_id']);
        });
    }
}
