<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminMatchService;
use App\Services\AdminTeamService;
use App\Services\UefaCompApiClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMatchAutoUefaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config([
            'services.uefa.base_url' => 'https://comp.uefa.test/v2',
            'services.uefa.match_base_url' => 'https://match.uefa.test/v5',
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
        [$leagueId, $roundId] = $this->seedLeague();
        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '47',
            'team_team_code' => 'GER',
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Malta',
            'team_nationality' => 'mlt',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '88',
            'team_team_code' => 'MLT',
        ]);
        $otherHome = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '8',
            'team_team_code' => 'AUT',
        ]);
        $otherGuest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '95',
            'team_team_code' => 'NED',
        ]);

        $existing = MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2026-09-24 00:00:00',
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

        $this->fakeUefaMatches([
            $this->uefaMatch('1', '47', '88', 'Deutschland', 'Malta', '2026-09-24', 1),
            $this->uefaMatch('2', '8', '95', 'Österreich', 'Niederlande', '2026-09-25', 1),
        ]);

        $result = $this->service()->analyzeUefaMatches($leagueId);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertCount(2, $result['auto_uefa']['rows']);

        $byUefaId = [];
        foreach ($result['auto_uefa']['rows'] as $row) {
            $byUefaId[$row['uefa_match_id']] = $row;
        }

        $this->assertSame('matched', $byUefaId['1']['row_status']);
        $this->assertSame((int) $existing->match_id, $byUefaId['1']['match_id']);
        $this->assertSame($roundId, $byUefaId['1']['match_round']);

        $this->assertSame('new', $byUefaId['2']['row_status']);
        $this->assertSame(0, $byUefaId['2']['match_id']);
        $this->assertSame((int) $otherHome->team_id, $byUefaId['2']['match_hometeam_id']);
        $this->assertSame((int) $otherGuest->team_id, $byUefaId['2']['match_guestteam_id']);
        $this->assertSame($roundId, $byUefaId['2']['match_round']);
    }

    #[Test]
    public function analyze_marks_unmapped_when_team_uefa_id_missing(): void
    {
        [$leagueId] = $this->seedLeague();
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '47',
            'team_team_code' => 'GER',
        ]);

        $this->fakeUefaMatches([
            $this->uefaMatch('1', '47', '88', 'Deutschland', 'Malta', '2026-09-24', 1),
        ]);

        $result = $this->service()->analyzeUefaMatches($leagueId);

        $this->assertTrue($result['ok']);
        $this->assertSame('unmapped', $result['auto_uefa']['rows'][0]['row_status']);
        $this->assertSame(0, $result['auto_uefa']['rows'][0]['match_guestteam_id']);
    }

    #[Test]
    public function save_updates_matched_and_creates_new_with_round_selector(): void
    {
        [$leagueId, $roundId] = $this->seedLeague();
        $round2 = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Runde 2',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-10 00:00:00',
            'matchround_status' => 1,
        ]);

        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '47',
            'team_team_code' => 'GER',
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Malta',
            'team_nationality' => 'mlt',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '88',
            'team_team_code' => 'MLT',
        ]);
        $otherHome = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '8',
            'team_team_code' => 'AUT',
        ]);
        $otherGuest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '95',
            'team_team_code' => 'NED',
        ]);

        $existing = MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2026-09-24 00:00:00',
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

        $result = $this->service()->saveUefaMatches([
            [
                'row_status' => 'matched',
                'match_id' => (int) $existing->match_id,
                'match_round' => (int) $round2->matchround_id,
                'match_date' => '2026-09-24',
                'match_hometeam_id' => (int) $home->team_id,
                'match_guestteam_id' => (int) $guest->team_id,
                'match_status' => '',
                'home_name' => 'Deutschland',
                'guest_name' => 'Malta',
            ],
            [
                'row_status' => 'new',
                'match_id' => 0,
                'match_round' => $roundId,
                'match_date' => '2026-09-25',
                'match_hometeam_id' => (int) $otherHome->team_id,
                'match_guestteam_id' => (int) $otherGuest->team_id,
                'match_status' => '',
                'home_name' => 'Österreich',
                'guest_name' => 'Niederlande',
            ],
            [
                'row_status' => 'unmapped',
                'match_id' => 0,
                'match_round' => '',
                'match_date' => '2026-09-26',
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
            'match_date' => '2026-09-25 00:00:00',
            'match_round' => $roundId,
        ]);
        $this->assertSame(2, MatchGame::query()->count());
    }

    #[Test]
    public function save_accepts_large_row_lists_via_direct_payload(): void
    {
        [$leagueId, $roundId] = $this->seedLeague();

        $rows = [];
        for ($i = 1; $i <= 120; $i++) {
            $home = Team::query()->create([
                'team_foreign_id' => '',
                'team_name' => 'Home '.$i,
                'team_nationality' => 'h'.$i,
                'team_num_players' => 0,
                'team_status' => 1,
                'team_uefa_id' => 'h'.$i,
                'team_team_code' => 'H'.$i,
            ]);
            $guest = Team::query()->create([
                'team_foreign_id' => '',
                'team_name' => 'Guest '.$i,
                'team_nationality' => 'g'.$i,
                'team_num_players' => 0,
                'team_status' => 1,
                'team_uefa_id' => 'g'.$i,
                'team_team_code' => 'G'.$i,
            ]);
            $rows[] = [
                'row_status' => 'new',
                'match_id' => 0,
                'match_round' => $roundId,
                'match_date' => sprintf('2026-06-%02d', ($i % 28) + 1),
                'match_hometeam_id' => (int) $home->team_id,
                'match_guestteam_id' => (int) $guest->team_id,
                'match_status' => '',
                'home_name' => 'Home '.$i,
                'guest_name' => 'Guest '.$i,
            ];
        }

        $result = $this->service()->saveUefaMatches($rows, $leagueId);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame(120, MatchGame::query()->count());
    }

    private function service(): AdminMatchService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);

        return new AdminMatchService(
            $adminCenter,
            new AdminTeamService($adminCenter),
            new UefaCompApiClient,
        );
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedLeague(): array
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
        ]);

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-09-20 00:00:00',
            'matchround_enddate' => '2026-09-30 00:00:00',
            'matchround_status' => 1,
        ]);

        return [(int) $league->league_id, (int) $round->matchround_id];
    }

    /**
     * @param  list<array<string, mixed>>  $matches
     */
    private function fakeUefaMatches(array $matches): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'match.uefa.test/v5/matches*' => Http::response($matches, 200),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function uefaMatch(
        string $id,
        string $homeId,
        string $awayId,
        string $homeName,
        string $awayName,
        string $date,
        int $matchday,
    ): array {
        return [
            'id' => $id,
            'competitionPhase' => 'TOURNAMENT',
            'homeTeam' => [
                'id' => $homeId,
                'internationalName' => $homeName,
                'isPlaceHolder' => false,
                'translations' => ['countryName' => ['DE' => $homeName, 'EN' => $homeName]],
            ],
            'awayTeam' => [
                'id' => $awayId,
                'internationalName' => $awayName,
                'isPlaceHolder' => false,
                'translations' => ['countryName' => ['DE' => $awayName, 'EN' => $awayName]],
            ],
            'kickOffTime' => ['date' => $date, 'dateTime' => $date.'T16:00:00Z'],
            'matchday' => ['sequenceNumber' => (string) $matchday, 'phase' => 'TOURNAMENT'],
            'round' => ['phase' => 'TOURNAMENT', 'orderInCompetition' => 1],
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
            $table->string('league_symbol')->default('');
            $table->string('league_uefa_competition_identifier')->default('');
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
