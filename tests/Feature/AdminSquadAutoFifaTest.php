<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\FifaCompApiClient;
use App\Services\UefaCompApiClient;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminSquadAutoFifaTest extends TestCase
{
    private const IDENTIFIER = 'idCompetition=17&idSeason=285023';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config(['services.fifa.base_url' => 'https://api.fifa.test/api/v3']);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function analyze_builds_draft_from_fifa_players_for_selected_team(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('ger');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fifa_id' => '',
            'player_fname' => 'Manuel',
            'player_lname' => 'Neuer',
            'player_nationality' => 'GER',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        Playerteam::query()->create([
            'playerteam_player_id' => (int) $existing->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'g',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $this->fakeFifaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromFifa($leagueId, '43948');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame('fifa', $result['auto']['source_kind']);
        $this->assertSame('43948', $result['auto']['fifa_team_id']);
        $this->assertSame($teamId, $result['auto']['team_id']);
        $this->assertSame('GER', $result['auto']['fifa_code']);
        $this->assertCount(2, $result['auto']['players']);

        $byName = [];
        foreach ($result['auto']['players'] as $row) {
            $byName[$row['json_name']] = $row;
        }

        $this->assertFalse($byName['Manuel Neuer']['is_new']);
        $this->assertTrue($byName['Manuel Neuer']['on_squad']);
        $this->assertSame('g', $byName['Manuel Neuer']['playerteam_player_position']);
        $this->assertSame('228912', $byName['Manuel Neuer']['player_fifa_id']);

        $this->assertTrue($byName['Antonio Ruediger']['is_new']);
        $this->assertSame('d', $byName['Antonio Ruediger']['playerteam_player_position']);
        $this->assertSame(2, $byName['Antonio Ruediger']['json_number']);
        $this->assertSame('379955', $byName['Antonio Ruediger']['player_fifa_id']);
    }

    #[Test]
    public function create_squad_from_fifa_draft_persists_player_fifa_id(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('ger');

        $result = $this->service()->createSquadFromDraft(
            [
                [
                    'player_id' => 0,
                    'playerteam_id' => 0,
                    'is_new' => true,
                    'on_squad' => false,
                    'player_fname' => 'Antonio',
                    'player_lname' => 'Ruediger',
                    'player_nationality' => 'GER',
                    'player_foreign_id' => '',
                    'player_uefa_id' => '',
                    'player_fifa_id' => '379955',
                    'playerteam_player_position' => 'd',
                    'playerteam_status' => 1,
                    'playerteam_date_transfer' => '2008-01-01',
                    'json_number' => 2,
                    'json_name' => 'Antonio Ruediger',
                ],
            ],
            $teamId,
            $leagueId,
            'idCompetition=17 · Deutschland',
            'GER',
            [],
            'fifa',
            '',
            '43948',
        );

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseHas('ffb_player', [
            'player_fname' => 'Antonio',
            'player_lname' => 'Ruediger',
            'player_fifa_id' => '379955',
        ]);
    }

    #[Test]
    public function analyze_prefers_player_fifa_id_over_name_match(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('ger');

        $linked = Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fifa_id' => '379955',
            'player_fname' => 'Old',
            'player_lname' => 'Label',
            'player_nationality' => 'GER',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fifa_id' => '',
            'player_fname' => 'Antonio',
            'player_lname' => 'Ruediger',
            'player_nationality' => 'GER',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $this->fakeFifaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromFifa($leagueId, '43948');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));

        $byName = [];
        foreach ($result['auto']['players'] as $row) {
            $byName[$row['json_name']] = $row;
        }

        $this->assertArrayHasKey('Antonio Ruediger', $byName);
        $this->assertFalse($byName['Antonio Ruediger']['is_new']);
        $this->assertSame((int) $linked->player_id, $byName['Antonio Ruediger']['player_id']);
        $this->assertSame('Old', $byName['Antonio Ruediger']['player_fname']);
        $this->assertSame('379955', $byName['Antonio Ruediger']['player_fifa_id']);
        $this->assertSame([], $result['auto']['almost']);
    }

    #[Test]
    public function analyze_errors_when_fifa_team_is_missing(): void
    {
        [, $leagueId] = $this->seedTeamAndLeague('ger');

        $result = $this->service()->analyzeSquadsFromFifa($leagueId, '');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('FIFA-Team', $result['errors'][0]);
    }

    #[Test]
    public function analyze_errors_when_league_identifier_is_missing(): void
    {
        [, $leagueId] = $this->seedTeamAndLeague('ger', '');

        $result = $this->service()->analyzeSquadsFromFifa($leagueId, '43948');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('FIFA-Competition-Identifier', $result['errors'][0]);
    }

    #[Test]
    public function analyze_errors_when_ffb_team_cannot_be_resolved(): void
    {
        [, $leagueId] = $this->seedTeamAndLeague('xyz');

        $this->fakeFifaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromFifa($leagueId, '43948');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Kein FFB-Team', $result['errors'][0]);
    }

    #[Test]
    public function page_payload_loads_fifa_team_selector_for_league(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('ger');

        $this->fakeFifaCompetitionHttp(includeSquad: false);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->andReturn([
            'user' => ['user_id' => 1, 'user_name' => 'Admin'],
            'navigation' => [],
            'selected_league' => ['league_id' => $leagueId, 'league_title' => 'WM 2026'],
        ]);
        $adminCenter->shouldReceive('selectedLeagueId')->with(1)->andReturn($leagueId);

        $service = new AdminSquadService(
            $adminCenter,
            new AdminPlayerService($adminCenter),
            new WikimediaPlayerImageService,
            new UefaCompApiClient,
            new FifaCompApiClient,
        );

        $payload = $service->pagePayload(1, $teamId, $leagueId, 'auto-fifa', null, null, null, '43948');

        $this->assertSame(self::IDENTIFIER, $payload['fifa_competition_identifier']);
        $this->assertSame('43948', $payload['fifa_team_id']);
        $this->assertCount(2, $payload['fifa_teams']);

        $byId = [];
        foreach ($payload['fifa_teams'] as $option) {
            $byId[$option['fifa_id']] = $option;
        }

        $this->assertTrue($byId['43948']['matched']);
        $this->assertSame($teamId, $byId['43948']['ffb_team_id']);
        $this->assertFalse($byId['43922']['matched']);
        $this->assertSame($teamId, $payload['selected_team_id']);
    }

    private function service(): AdminSquadService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $players = new AdminPlayerService($adminCenter);

        return new AdminSquadService(
            $adminCenter,
            $players,
            new WikimediaPlayerImageService,
            new UefaCompApiClient,
            new FifaCompApiClient,
        );
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedTeamAndLeague(string $nationality, string $identifier = self::IDENTIFIER): array
    {
        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => '',
            'league_fifa_competition_identifier' => $identifier,
        ]);

        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => $nationality === 'ger' ? 'Deutschland' : 'Other',
            'team_nationality' => strtoupper($nationality),
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => strtoupper($nationality),
        ]);

        return [(int) $team->team_id, (int) $league->league_id];
    }

    private function fakeFifaCompetitionHttp(bool $includeSquad = true): void
    {
        Http::preventStrayRequests();

        $fakes = [
            'api.fifa.test/api/v3/competitions/teams/285023*' => Http::response([
                'Results' => [
                    [
                        'IdTeam' => '43948',
                        'Abbreviation' => 'GER',
                        'IdCountry' => 'GER',
                        'ShortClubName' => 'Germany',
                        'Name' => [
                            ['Locale' => 'de-DE', 'Description' => 'Deutschland'],
                        ],
                    ],
                    [
                        'IdTeam' => '43922',
                        'Abbreviation' => 'ARG',
                        'IdCountry' => 'ARG',
                        'ShortClubName' => 'Argentina',
                        'Name' => [
                            ['Locale' => 'de-DE', 'Description' => 'Argentinien'],
                        ],
                    ],
                ],
            ], 200),
        ];

        if ($includeSquad) {
            $fakes['api.fifa.test/api/v3/teams/43948/squad*'] = Http::response([
                'IdCompetition' => '17',
                'IdSeason' => '285023',
                'IdTeam' => '43948',
                'Players' => [
                    [
                        'IdTeam' => '43948',
                        'IdPlayer' => '228912',
                        'JerseyNum' => 1,
                        'Position' => 0,
                        'PlayerName' => [
                            ['Locale' => 'de-DE', 'Description' => 'Manuel NEUER'],
                        ],
                        'ShortName' => [
                            ['Locale' => 'en-GB', 'Description' => 'NEUER'],
                        ],
                    ],
                    [
                        'IdTeam' => '43948',
                        'IdPlayer' => '379955',
                        'JerseyNum' => 2,
                        'Position' => 1,
                        'PlayerName' => [
                            ['Locale' => 'de-DE', 'Description' => 'Antonio RUEDIGER'],
                        ],
                        'ShortName' => [
                            ['Locale' => 'en-GB', 'Description' => 'RUEDIGER'],
                        ],
                    ],
                ],
            ], 200);
        }

        Http::fake($fakes);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title');
            $table->integer('league_visible')->default(1);
            $table->integer('league_archive')->default(0);
            $table->string('league_symbol')->default('');
            $table->string('league_uefa_competition_identifier')->default('');
            $table->string('league_fifa_competition_identifier')->default('');
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

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_foreign_id')->default('');
            $table->string('player_uefa_id')->default('');
            $table->string('player_fifa_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
            $table->string('player_commons_image')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id');
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_date_transfer')->nullable();
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id')->default(0);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round')->default(0);
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
        });
    }
}
