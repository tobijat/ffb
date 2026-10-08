<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminMatchService;
use App\Services\AdminTeamService;
use App\Services\FifaCompApiClient;
use App\Services\UefaCompApiClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMatchAutoFifaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config([
            'services.fifa.base_url' => 'https://api.fifa.test/api/v3',
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function analyze_matches_existing_by_teams_and_date_and_marks_new(): void
    {
        [$leagueId] = $this->seedLeague();
        $knockout = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => '16tel-Finale',
            'matchround_startdate' => '2026-07-01 00:00:00',
            'matchround_enddate' => '2026-07-05 00:00:00',
            'matchround_status' => 1,
        ]);

        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Schweiz',
            'team_nationality' => 'SUI',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'SUI',
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Algerien',
            'team_nationality' => 'ALG',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'ALG',
        ]);
        $otherHome = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Spanien',
            'team_nationality' => 'ESP',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'ESP',
        ]);
        $otherGuest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'AUT',
        ]);

        $existing = MatchGame::query()->create([
            'match_round' => (int) $knockout->matchround_id,
            'match_date' => '2026-07-03 00:00:00',
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_status' => '',
            'match_homescore' => '-1',
            'match_guestscore' => '-1',
            'match_homescore_penalty' => '-1',
            'match_guestscore_penalty' => '-1',
            'match_minutes' => 0,
            'match_url' => '',
        ]);

        $this->fakeFifaMatches([
            $this->fifaMatch('1', '43971', '43843', 'SUI', 'ALG', 'Schweiz', 'Algerien', '2026-07-03T03:00:00Z', 'Sechzehntelfinale'),
            $this->fifaMatch('2', '43969', '43922', 'ESP', 'AUT', 'Spanien', 'Österreich', '2026-07-02T19:00:00Z', 'Sechzehntelfinale'),
        ]);

        $result = $this->service()->analyzeFifaMatches($leagueId);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertCount(2, $result['auto_fifa']['rows']);

        $byFifaId = [];
        foreach ($result['auto_fifa']['rows'] as $row) {
            $byFifaId[$row['fifa_match_id']] = $row;
        }

        $this->assertSame('matched', $byFifaId['1']['row_status']);
        $this->assertSame((int) $existing->match_id, $byFifaId['1']['match_id']);
        $this->assertSame((int) $knockout->matchround_id, $byFifaId['1']['match_round']);
        $this->assertSame('2026-07-03 05:00:00.000', $byFifaId['1']['match_date']);

        $this->assertSame('new', $byFifaId['2']['row_status']);
        $this->assertSame(0, $byFifaId['2']['match_id']);
        $this->assertSame((int) $otherHome->team_id, $byFifaId['2']['match_hometeam_id']);
        $this->assertSame((int) $otherGuest->team_id, $byFifaId['2']['match_guestteam_id']);
        $this->assertSame((int) $knockout->matchround_id, $byFifaId['2']['match_round']);
        $this->assertSame('2026-07-02 21:00:00.000', $byFifaId['2']['match_date']);
    }

    #[Test]
    public function analyze_marks_unmapped_when_team_code_missing(): void
    {
        [$leagueId] = $this->seedLeague();
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Schweiz',
            'team_nationality' => 'SUI',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'SUI',
        ]);

        $this->fakeFifaMatches([
            $this->fifaMatch('1', '43971', '43843', 'SUI', 'ALG', 'Schweiz', 'Algerien', '2026-07-03T03:00:00Z', 'Sechzehntelfinale'),
        ]);

        $result = $this->service()->analyzeFifaMatches($leagueId);

        $this->assertTrue($result['ok']);
        $this->assertSame('unmapped', $result['auto_fifa']['rows'][0]['row_status']);
        $this->assertSame(0, $result['auto_fifa']['rows'][0]['match_guestteam_id']);
    }

    #[Test]
    public function analyze_uses_berlin_calendar_day_for_late_utc_kickoff(): void
    {
        [$leagueId] = $this->seedLeague();
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Brasilien',
            'team_nationality' => 'BRA',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'BRA',
        ]);
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Marokko',
            'team_nationality' => 'MAR',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'MAR',
        ]);

        $this->fakeFifaMatches([
            $this->fifaMatch('9', '43924', '43872', 'BRA', 'MAR', 'Brasilien', 'Marokko', '2026-06-13T22:00:00Z', 'Erste Phase'),
        ]);

        $result = $this->service()->analyzeFifaMatches($leagueId);

        $this->assertTrue($result['ok']);
        $this->assertSame('2026-06-14 00:00:00.000', $result['auto_fifa']['rows'][0]['match_date']);
        $this->assertSame('new', $result['auto_fifa']['rows'][0]['row_status']);
    }

    #[Test]
    public function save_updates_matched_and_creates_new_with_round_selector(): void
    {
        [$leagueId, $roundId] = $this->seedLeague();
        $round2 = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => '16tel-Finale',
            'matchround_startdate' => '2026-07-01 00:00:00',
            'matchround_enddate' => '2026-07-05 00:00:00',
            'matchround_status' => 1,
        ]);

        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Schweiz',
            'team_nationality' => 'SUI',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'SUI',
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Algerien',
            'team_nationality' => 'ALG',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'ALG',
        ]);
        $otherHome = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Spanien',
            'team_nationality' => 'ESP',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'ESP',
        ]);
        $otherGuest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'AUT',
        ]);

        $existing = MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2026-07-03 00:00:00',
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_status' => '',
            'match_homescore' => '-1',
            'match_guestscore' => '-1',
            'match_homescore_penalty' => '-1',
            'match_guestscore_penalty' => '-1',
            'match_minutes' => 0,
            'match_url' => '',
        ]);

        $result = $this->service()->saveFifaMatches([
            [
                'row_status' => 'matched',
                'match_id' => (int) $existing->match_id,
                'match_round' => (int) $round2->matchround_id,
                'match_date' => '2026-07-03',
                'match_hometeam_id' => (int) $home->team_id,
                'match_guestteam_id' => (int) $guest->team_id,
                'match_status' => '',
                'home_name' => 'Schweiz',
                'guest_name' => 'Algerien',
            ],
            [
                'row_status' => 'new',
                'match_id' => 0,
                'match_round' => (int) $round2->matchround_id,
                'match_date' => '2026-07-02',
                'match_hometeam_id' => (int) $otherHome->team_id,
                'match_guestteam_id' => (int) $otherGuest->team_id,
                'match_status' => '',
                'home_name' => 'Spanien',
                'guest_name' => 'Österreich',
            ],
            [
                'row_status' => 'unmapped',
                'match_id' => 0,
                'match_round' => '',
                'match_date' => '2026-07-04',
                'match_hometeam_id' => 0,
                'match_guestteam_id' => 0,
                'match_status' => '',
                'home_name' => 'Missing',
                'guest_name' => 'Also',
            ],
        ], $leagueId);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));

        $existing->refresh();
        $this->assertSame((int) $round2->matchround_id, (int) $existing->match_round);

        $this->assertDatabaseHas('ffb_match', [
            'match_hometeam_id' => (int) $otherHome->team_id,
            'match_guestteam_id' => (int) $otherGuest->team_id,
            'match_date' => '2026-07-02 11:11:11.111',
            'match_round' => (int) $round2->matchround_id,
        ]);
        $this->assertSame(2, MatchGame::query()->count());
    }

    #[Test]
    public function save_stores_kickoff_time_from_fifa_datetime(): void
    {
        [$leagueId, $roundId] = $this->seedLeague();
        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Schweiz',
            'team_nationality' => 'sui',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_fifa_id' => '43969',
            'team_team_code' => 'SUI',
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Algerien',
            'team_nationality' => 'alg',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_fifa_id' => '43843',
            'team_team_code' => 'ALG',
        ]);

        $result = $this->service()->saveFifaMatches([
            [
                'row_status' => 'new',
                'match_id' => 0,
                'match_round' => $roundId,
                'match_date' => '2026-07-03 18:00:00.000',
                'match_hometeam_id' => (int) $home->team_id,
                'match_guestteam_id' => (int) $guest->team_id,
                'match_status' => '',
                'home_name' => 'Schweiz',
                'guest_name' => 'Algerien',
            ],
        ], $leagueId);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseHas('ffb_match', [
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-07-03 18:00:00.000',
        ]);
    }

    #[Test]
    public function analyze_suggests_finale_round_without_matching_earlier_knockouts(): void
    {
        [$leagueId] = $this->seedLeague();
        Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => '16tel-Finale',
            'matchround_startdate' => '2026-07-01 00:00:00',
            'matchround_enddate' => '2026-07-05 00:00:00',
            'matchround_status' => 1,
        ]);
        $finale = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Finale',
            'matchround_startdate' => '2026-07-19 00:00:00',
            'matchround_enddate' => '2026-07-19 00:00:00',
            'matchround_status' => 1,
        ]);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Brasilien',
            'team_nationality' => 'BRA',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'BRA',
        ]);
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Argentinien',
            'team_nationality' => 'ARG',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'ARG',
        ]);

        $this->fakeFifaMatches([
            $this->fifaMatch('99', '43924', '43922', 'BRA', 'ARG', 'Brasilien', 'Argentinien', '2026-07-19T19:00:00Z', 'Finale'),
        ]);

        $result = $this->service()->analyzeFifaMatches($leagueId);

        $this->assertTrue($result['ok']);
        $this->assertSame((int) $finale->matchround_id, $result['auto_fifa']['rows'][0]['match_round']);
    }

    private function service(): AdminMatchService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);

        return new AdminMatchService(
            $adminCenter,
            new AdminTeamService($adminCenter),
            new UefaCompApiClient,
            new FifaCompApiClient('https://api.fifa.test/api/v3'),
        );
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedLeague(): array
    {
        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_uefa_competition_identifier' => '',
            'league_fifa_competition_identifier' => 'idCompetition=17&idSeason=285023',
        ]);

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Spieltag 1',
            'matchround_startdate' => '2026-06-11 00:00:00',
            'matchround_enddate' => '2026-06-16 00:00:00',
            'matchround_status' => 1,
        ]);

        return [(int) $league->league_id, (int) $round->matchround_id];
    }

    /**
     * @param  list<array<string, mixed>>  $matches
     */
    private function fakeFifaMatches(array $matches): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.fifa.test/api/v3/calendar/matches*' => Http::response([
                'Results' => $matches,
            ], 200),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function fifaMatch(
        string $id,
        string $homeId,
        string $awayId,
        string $homeAbbr,
        string $awayAbbr,
        string $homeName,
        string $awayName,
        string $dateUtc,
        string $stageName,
    ): array {
        return [
            'IdMatch' => $id,
            'IdStage' => '289287',
            'Date' => $dateUtc,
            'StageName' => [['Locale' => 'de-DE', 'Description' => $stageName]],
            'Home' => [
                'IdTeam' => $homeId,
                'Abbreviation' => $homeAbbr,
                'IdCountry' => $homeAbbr,
                'TeamName' => [['Locale' => 'de-DE', 'Description' => $homeName]],
            ],
            'Away' => [
                'IdTeam' => $awayId,
                'Abbreviation' => $awayAbbr,
                'IdCountry' => $awayAbbr,
                'TeamName' => [['Locale' => 'de-DE', 'Description' => $awayName]],
            ],
        ];
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->string('league_uefa_competition_identifier')->default('');
            $table->string('league_fifa_competition_identifier')->default('');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->tinyInteger('matchround_status')->default(1);
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
            $table->string('team_uefa_id')->default('');
            $table->string('team_fifa_id')->default('');
            $table->string('team_team_code')->default('');
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round');
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
            $table->string('match_homescore')->default('-1');
            $table->string('match_guestscore')->default('-1');
            $table->string('match_homescore_penalty')->default('-1');
            $table->string('match_guestscore_penalty')->default('-1');
            $table->string('match_date')->nullable();
            $table->integer('match_minutes')->default(0);
            $table->string('match_status')->default('');
            $table->string('match_url')->default('');
        });
    }
}
