<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Models\Teamelo;
use App\Services\EloRatingClient;
use App\Services\TeamEloBackfillService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TeamEloBackfillTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_teamelo');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function year_from_elo_url_parses_tsv_filename(): void
    {
        $this->assertSame(2007, TeamEloBackfillService::yearFromEloUrl('http://www.eloratings.net/2007.tsv'));
        $this->assertSame(2015, TeamEloBackfillService::yearFromEloUrl('https://www.eloratings.net/2015.tsv'));
        $this->assertNull(TeamEloBackfillService::yearFromEloUrl('https://www.eloratings.net/World.tsv'));
    }

    #[Test]
    public function dry_run_does_not_write_teamelo_rows(): void
    {
        [$leagueId, $teamA, $teamB] = $this->seedLeague();
        $elo = $this->eloMock($teamA, $teamB);

        $result = (new TeamEloBackfillService)->backfillLeague($leagueId, $elo, 2008, false);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertTrue($result['dry_run']);
        $this->assertSame(2, $result['team_count']);
        $this->assertSame(2008, $result['elo_year']);
        $this->assertDatabaseCount('ffb_teamelo', 0);
    }

    #[Test]
    public function execute_upserts_elo_per_team_and_league(): void
    {
        [$leagueId, $teamA, $teamB] = $this->seedLeague();
        $elo = $this->eloMock($teamA, $teamB);
        $service = new TeamEloBackfillService;

        $result = $service->backfillLeague($leagueId, $elo, 2008, true);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertFalse($result['dry_run']);
        $this->assertDatabaseCount('ffb_teamelo', 2);
        $this->assertDatabaseHas('ffb_teamelo', [
            'teamelo_team_id' => $teamA,
            'teamelo_league_id' => $leagueId,
            'teamelo_elo' => 2100.0,
            'teamelo_elo_year' => 2008,
        ]);
        $this->assertDatabaseHas('ffb_teamelo', [
            'teamelo_team_id' => $teamB,
            'teamelo_league_id' => $leagueId,
            'teamelo_elo' => 1600.0,
            'teamelo_elo_year' => 2008,
        ]);

        $eloUpdate = Mockery::mock(EloRatingClient::class);
        $eloUpdate->shouldReceive('ratingsForTeamList')
            ->once()
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 2111.0],
                ['team_id' => $teamB, 'elo_rating' => 1611.0],
            ]);

        $updated = $service->backfillLeague($leagueId, $eloUpdate, 2008, true);

        $this->assertTrue($updated['ok']);
        $this->assertDatabaseCount('ffb_teamelo', 2);
        $this->assertSame(2111.0, (float) Teamelo::query()
            ->where('teamelo_team_id', $teamA)
            ->where('teamelo_league_id', $leagueId)
            ->value('teamelo_elo'));
    }

    #[Test]
    public function lists_teams_without_elo_as_skipped(): void
    {
        [$leagueId, $teamA, $teamB] = $this->seedLeague();
        $elo = Mockery::mock(EloRatingClient::class);
        $elo->shouldReceive('ratingsForTeamList')
            ->once()
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 2000.0],
            ]);

        $result = (new TeamEloBackfillService)->backfillLeague($leagueId, $elo, 2010, true);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['skipped_teams']);
        $this->assertSame($teamB, $result['skipped_teams'][0]['team_id']);
        $this->assertDatabaseCount('ffb_teamelo', 1);
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private function seedLeague(): array
    {
        $league = League::query()->create(['league_title' => 'Test League']);
        $leagueId = (int) $league->league_id;

        $round = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => '2008-06-01 18:00:00',
        ]);

        $teamA = Team::query()->create([
            'team_name' => 'Spain',
            'team_nationality' => 'ESP',
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

        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $teamA->team_id,
            'match_guestteam_id' => (int) $teamB->team_id,
            'match_date' => '2008-06-01',
            'match_status' => '',
            'match_minutes' => 90,
        ]);

        return [$leagueId, (int) $teamA->team_id, (int) $teamB->team_id];
    }

    private function eloMock(int $teamA, int $teamB): EloRatingClient
    {
        $elo = Mockery::mock(EloRatingClient::class);
        $elo->shouldReceive('ratingsForTeamList')
            ->once()
            ->andReturn([
                ['team_id' => $teamA, 'elo_rating' => 2100.0],
                ['team_id' => $teamB, 'elo_rating' => 1600.0],
            ]);

        return $elo;
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
            $table->string('team_nationality')->default('');
            $table->integer('team_status')->default(1);
            $table->integer('team_num_players')->default(0);
            $table->string('team_foreign_id')->default('');
        });
        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->string('matchround_startdate')->nullable();
            $table->integer('matchround_status')->default(0);
        });
        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round');
            $table->unsignedInteger('match_hometeam_id');
            $table->unsignedInteger('match_guestteam_id');
            $table->string('match_date')->nullable();
            $table->string('match_status')->default('');
            $table->integer('match_minutes')->default(0);
            $table->integer('match_homescore')->nullable();
            $table->integer('match_guestscore')->nullable();
        });
        Schema::create('ffb_teamelo', function (Blueprint $table) {
            $table->increments('teamelo_id');
            $table->unsignedInteger('teamelo_team_id');
            $table->unsignedInteger('teamelo_league_id');
            $table->double('teamelo_elo');
            $table->unsignedSmallInteger('teamelo_elo_year');
            $table->unique(['teamelo_team_id', 'teamelo_league_id'], 'teamelo_team_league_unique');
        });
    }
}
