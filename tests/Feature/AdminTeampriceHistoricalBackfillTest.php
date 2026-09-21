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

class AdminTeampriceHistoricalBackfillTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-09-21 12:00:00'));
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
    public function dry_run_does_not_write_teamprices(): void
    {
        [$leagueId, $pastId, $futureId, $teamA, $teamB] = $this->seedLeague();
        $elo = $this->eloMock($teamA, $teamB);
        $service = $this->service($elo);

        $result = $service->backfillHistoricalLeagueTeamPrices($leagueId, $elo, [
            'max_credits' => 100,
            'max_players_team' => 3,
            'exponent' => 2,
            'dream_team_ratio' => 1.5,
            'min_price' => 1,
        ], false);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertTrue($result['dry_run']);
        $this->assertSame([$pastId, $futureId], $result['matchround_ids']);
        $this->assertDatabaseCount('ffb_teamprice', 0);
    }

    #[Test]
    public function execute_writes_teamprices_for_past_and_future_rounds(): void
    {
        [$leagueId, $pastId, $futureId, $teamA, $teamB] = $this->seedLeague();
        $elo = $this->eloMock($teamA, $teamB);
        $service = $this->service($elo);

        $result = $service->backfillHistoricalLeagueTeamPrices($leagueId, $elo, [
            'max_credits' => 100,
            'max_players_team' => 3,
            'exponent' => 2,
            'dream_team_ratio' => 1.5,
            'min_price' => 1,
        ], true);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertFalse($result['dry_run']);
        $this->assertDatabaseCount('ffb_teamprice', 4);

        $pricePast = (float) Teamprice::query()
            ->where('teamprice_team_id', $teamA)
            ->where('teamprice_matchround_id', $pastId)
            ->value('teamprice_price');
        $priceFuture = (float) Teamprice::query()
            ->where('teamprice_team_id', $teamA)
            ->where('teamprice_matchround_id', $futureId)
            ->value('teamprice_price');
        $this->assertGreaterThan(0.0, $pricePast);
        $this->assertSame($pricePast, $priceFuture);

        $priceB = (float) Teamprice::query()
            ->where('teamprice_team_id', $teamB)
            ->where('teamprice_matchround_id', $pastId)
            ->value('teamprice_price');
        $this->assertGreaterThan($priceB, $pricePast);
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int, 4: int}
     */
    private function seedLeague(): array
    {
        $league = League::query()->create(['league_title' => 'EM Test']);
        $leagueId = (int) $league->league_id;

        $past = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Past',
            'matchround_startdate' => '2016-06-10 21:00:00',
        ]);
        $future = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Future',
            'matchround_startdate' => '2026-10-01 18:00:00',
        ]);

        $teamA = Team::query()->create([
            'team_name' => 'France',
            'team_nationality' => 'FRA',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);
        $teamB = Team::query()->create([
            'team_name' => 'Austria',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);

        MatchGame::query()->insert([
            'match_round' => (int) $past->matchround_id,
            'match_hometeam_id' => (int) $teamA->team_id,
            'match_guestteam_id' => (int) $teamB->team_id,
            'match_date' => '2016-06-10',
            'match_status' => '',
            'match_minutes' => 90,
        ]);
        MatchGame::query()->insert([
            'match_round' => (int) $future->matchround_id,
            'match_hometeam_id' => (int) $teamA->team_id,
            'match_guestteam_id' => (int) $teamB->team_id,
            'match_date' => '2026-10-01',
            'match_status' => '',
            'match_minutes' => 0,
        ]);

        return [
            $leagueId,
            (int) $past->matchround_id,
            (int) $future->matchround_id,
            (int) $teamA->team_id,
            (int) $teamB->team_id,
        ];
    }

    private function eloMock(int $teamA, int $teamB): EloRatingClient
    {
        $elo = Mockery::mock(EloRatingClient::class);
        $elo->shouldReceive('ratingsForTeamList')
            ->once()
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 2000.0],
                ['team_id' => $teamB, 'elo_rating' => 1500.0],
            ]);

        return $elo;
    }

    private function service(EloRatingClient $elo): AdminPlayerpriceService
    {
        $limits = Mockery::mock(LineupOptionsResolver::class);
        $limits->shouldReceive('forLeague')->andReturn([
            'lineup_max_credits' => 100.0,
            'lineup_max_players_team' => 3,
            'lineup_max_players' => 11,
            'source' => 'league',
        ]);

        return new AdminPlayerpriceService(
            Mockery::mock(AdminCenterService::class),
            Mockery::mock(EloRatingClient::class),
            $limits,
        );
    }

    private function createSchema(): void
    {
        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->string('matchround_startdate')->nullable();
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
    }
}
