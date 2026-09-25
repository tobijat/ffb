<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminTeamService;
use App\Services\UefaCompApiClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTeamAutoUefaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config(['services.uefa.base_url' => 'https://comp.uefa.test/v2']);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function analyze_matches_by_nationality_and_marks_unmatched(): void
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
        ]);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => '',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'teams' => ['47', '88']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'internationalName' => 'Germany',
                    'translations' => ['countryName' => ['DE' => 'Deutschland', 'EN' => 'Germany']],
                ],
                [
                    'id' => '88',
                    'teamCode' => 'MLT',
                    'countryCode' => 'MLT',
                    'internationalName' => 'Malta',
                    'translations' => ['countryName' => ['DE' => 'Malta', 'EN' => 'Malta']],
                ],
            ], 200),
        ]);

        $result = $this->service()->analyzeUefaTeams((int) $league->league_id);

        $this->assertTrue($result['ok']);
        $this->assertCount(2, $result['auto_uefa']['rows']);

        $byCode = [];
        foreach ($result['auto_uefa']['rows'] as $row) {
            $byCode[$row['uefa_team_code']] = $row;
        }

        $this->assertSame('matched', $byCode['GER']['match_status']);
        $this->assertSame('Deutschland', $byCode['GER']['team_name']);
        $this->assertSame('unmatched', $byCode['MLT']['match_status']);
        $this->assertSame(0, $byCode['MLT']['team_id']);
    }

    #[Test]
    public function save_updates_matched_and_creates_new(): void
    {
        $germany = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => '',
        ]);

        $result = $this->service()->saveUefaTeams([
            [
                'team_id' => (int) $germany->team_id,
                'create_new' => 0,
                'team_name' => 'Should Not Overwrite Name',
                'team_nationality' => 'xxx',
                'uefa_name' => 'Deutschland',
                'uefa_id' => '47',
                'uefa_team_code' => 'GER',
                'team_uefa_id' => '47',
                'team_team_code' => 'GER',
            ],
            [
                'team_id' => 0,
                'create_new' => 1,
                'team_name' => 'Malta',
                'team_nationality' => 'mlt',
                'uefa_name' => 'Malta',
                'uefa_id' => '88',
                'uefa_team_code' => 'MLT',
                'team_uefa_id' => '88',
                'team_team_code' => 'MLT',
            ],
        ], 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));

        $germany->refresh();
        $this->assertSame('47', (string) $germany->team_uefa_id);
        $this->assertSame('GER', (string) $germany->team_team_code);
        $this->assertSame('Deutschland', (string) $germany->team_name);
        $this->assertSame('ger', (string) $germany->team_nationality);

        $this->assertDatabaseHas('ffb_team', [
            'team_name' => 'Malta',
            'team_nationality' => 'mlt',
            'team_uefa_id' => '88',
            'team_team_code' => 'MLT',
        ]);
    }

    #[Test]
    public function analyze_falls_back_to_team_code_when_uefa_id_missing(): void
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
        ]);

        $byCode = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Germany By Code',
            'team_nationality' => 'xxx',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => 'GER',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'teams' => ['47']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'internationalName' => 'Germany',
                    'translations' => ['countryName' => ['DE' => 'Deutschland', 'EN' => 'Germany']],
                ],
            ], 200),
        ]);

        $result = $this->service()->analyzeUefaTeams((int) $league->league_id);

        $this->assertTrue($result['ok']);
        $this->assertSame((int) $byCode->team_id, $result['auto_uefa']['rows'][0]['team_id']);
        $this->assertSame('matched', $result['auto_uefa']['rows'][0]['match_status']);
    }

    #[Test]
    public function analyze_prefers_team_uefa_id_over_nationality_fallback(): void
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
        ]);

        $linked = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Malta Linked',
            'team_nationality' => 'xxx',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '88',
            'team_team_code' => 'MLT',
        ]);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Wrong Nat Match',
            'team_nationality' => 'mlt',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => '',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'teams' => ['88']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '88',
                    'teamCode' => 'MLT',
                    'countryCode' => 'MLT',
                    'internationalName' => 'Malta',
                    'translations' => ['countryName' => ['DE' => 'Malta', 'EN' => 'Malta']],
                ],
            ], 200),
        ]);

        $result = $this->service()->analyzeUefaTeams((int) $league->league_id);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto_uefa']['rows']);
        $this->assertSame((int) $linked->team_id, $result['auto_uefa']['rows'][0]['team_id']);
        $this->assertSame('matched', $result['auto_uefa']['rows'][0]['match_status']);
    }

    #[Test]
    public function analyze_matches_austria_by_uefa_id_not_club_with_same_nationality(): void
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
        ]);

        $austria = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '8',
            'team_team_code' => 'AUT',
        ]);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Bad Bleiberg',
            'team_nationality' => 'AUT',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => '',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'teams' => ['8']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '8',
                    'teamCode' => 'AUT',
                    'countryCode' => 'AUT',
                    'internationalName' => 'Austria',
                    'translations' => ['countryName' => ['DE' => 'Österreich', 'EN' => 'Austria']],
                ],
            ], 200),
        ]);

        $result = $this->service()->analyzeUefaTeams((int) $league->league_id);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto_uefa']['rows']);
        $this->assertSame((int) $austria->team_id, $result['auto_uefa']['rows'][0]['team_id']);
        $this->assertSame('Österreich', $result['auto_uefa']['rows'][0]['team_name']);
        $this->assertSame('matched', $result['auto_uefa']['rows'][0]['match_status']);
    }

    private function service(): AdminTeamService
    {
        $center = Mockery::mock(AdminCenterService::class);

        return new AdminTeamService($center, new UefaCompApiClient);
    }

    private function createSchema(): void
    {
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
    }
}
