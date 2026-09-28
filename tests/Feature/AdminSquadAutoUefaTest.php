<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\UefaCompApiClient;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminSquadAutoUefaTest extends TestCase
{
    private const IDENTIFIER = 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config(['services.uefa.base_url' => 'https://comp.uefa.test/v2']);
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
    public function analyze_matches_roster_players_with_empty_nationality(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('kos', '2608110', 'KOS');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fname' => 'Arijanet',
            'player_lname' => 'Muric',
            'player_nationality' => '',
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

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'orderInCompetition' => 1, 'teams' => ['2608110']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '2608110',
                    'teamCode' => 'KOS',
                    'countryCode' => 'KOS',
                    'internationalName' => 'Kosovo',
                    'translations' => [
                        'countryName' => ['DE' => 'Kosovo', 'EN' => 'Kosovo'],
                    ],
                ],
            ], 200),
            'comp.uefa.test/v2/players*' => Http::response([
                [
                    'id' => '250102215',
                    'nationalTeamId' => '2608110',
                    'nationalJerseyNumber' => '1',
                    'nationalFieldPosition' => 'GOALKEEPER',
                    'internationalName' => 'Arijanet Muric',
                    'translations' => [
                        'firstName' => ['EN' => 'Arijanet'],
                        'lastName' => ['EN' => 'Muric'],
                        'name' => ['EN' => 'Arijanet Muric'],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '2608110');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertCount(1, $result['auto']['players']);
        $this->assertSame([], $result['auto']['almost']);
        $this->assertFalse($result['auto']['players'][0]['is_new']);
        $this->assertTrue($result['auto']['players'][0]['on_squad']);
        $this->assertSame((int) $existing->player_id, $result['auto']['players'][0]['player_id']);
        $this->assertSame('KOS', $result['auto']['players'][0]['player_nationality']);
        $this->assertSame('250102215', $result['auto']['players'][0]['player_uefa_id']);
    }

    #[Test]
    public function create_squad_fills_empty_nationality_from_draft(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('kos', '2608110', 'KOS');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fname' => 'Visar',
            'player_lname' => 'Bekaj',
            'player_nationality' => '',
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

        $result = $this->service()->createSquadFromDraft(
            [
                [
                    'player_id' => (int) $existing->player_id,
                    'playerteam_id' => 0,
                    'is_new' => false,
                    'on_squad' => true,
                    'player_fname' => 'Visar',
                    'player_lname' => 'Bekaj',
                    'player_nationality' => 'KOS',
                    'player_foreign_id' => '',
                    'player_uefa_id' => '99',
                    'playerteam_player_position' => 'g',
                    'playerteam_status' => 1,
                    'playerteam_date_transfer' => '2008-01-01',
                    'json_number' => 1,
                    'json_name' => 'Visar Bekaj',
                ],
            ],
            $teamId,
            $leagueId,
            'UEFA · Kosovo',
            'KOS',
            [],
            'uefa',
            '2608110',
        );

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $existing->refresh();
        $this->assertSame('KOS', (string) $existing->player_nationality);
        $this->assertSame('99', (string) $existing->player_uefa_id);
    }

    #[Test]
    public function analyze_builds_draft_from_uefa_players_for_selected_team(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt', '88');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Henry',
            'player_lname' => 'Bonello',
            'player_nationality' => 'MLT',
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

        $this->fakeUefaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '88');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame('uefa', $result['auto']['source_kind']);
        $this->assertSame('88', $result['auto']['uefa_team_id']);
        $this->assertSame($teamId, $result['auto']['team_id']);
        $this->assertSame('MLT', $result['auto']['fifa_code']);
        $this->assertCount(2, $result['auto']['players']);

        $byName = [];
        foreach ($result['auto']['players'] as $row) {
            $byName[$row['json_name']] = $row;
        }

        $this->assertFalse($byName['Henry Bonello']['is_new']);
        $this->assertTrue($byName['Henry Bonello']['on_squad']);
        $this->assertSame('g', $byName['Henry Bonello']['playerteam_player_position']);

        $this->assertTrue($byName['New Striker']['is_new']);
        $this->assertSame('s', $byName['New Striker']['playerteam_player_position']);
        $this->assertSame(10, $byName['New Striker']['json_number']);
        $this->assertSame('2', $byName['New Striker']['player_uefa_id']);
        $this->assertSame('1', $byName['Henry Bonello']['player_uefa_id']);
        $this->assertArrayNotHasKey('Other Player', $byName);
    }

    #[Test]
    public function create_squad_from_uefa_draft_persists_player_uefa_id(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt', '88');

        $result = $this->service()->createSquadFromDraft(
            [
                [
                    'player_id' => 0,
                    'playerteam_id' => 0,
                    'is_new' => true,
                    'on_squad' => false,
                    'player_fname' => 'New',
                    'player_lname' => 'Striker',
                    'player_nationality' => 'MLT',
                    'player_foreign_id' => '',
                    'player_uefa_id' => '25001',
                    'playerteam_player_position' => 's',
                    'playerteam_status' => 1,
                    'playerteam_date_transfer' => '2008-01-01',
                    'json_number' => 10,
                    'json_name' => 'New Striker',
                ],
            ],
            $teamId,
            $leagueId,
            'competitionId=2014 · Malta',
            'MLT',
            [],
            'uefa',
            '88',
        );

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseHas('ffb_player', [
            'player_fname' => 'New',
            'player_lname' => 'Striker',
            'player_uefa_id' => '25001',
        ]);
    }

    #[Test]
    public function analyze_prefers_player_uefa_id_over_name_match(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt', '88');

        $linked = Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '2',
            'player_fname' => 'Old',
            'player_lname' => 'Label',
            'player_nationality' => 'MLT',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        // Same name as UEFA "New Striker" but must not win over UEFA-ID match.
        Player::query()->create([
            'player_foreign_id' => '',
            'player_uefa_id' => '',
            'player_fname' => 'New',
            'player_lname' => 'Striker',
            'player_nationality' => 'MLT',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $this->fakeUefaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '88');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));

        $byName = [];
        foreach ($result['auto']['players'] as $row) {
            $byName[$row['json_name']] = $row;
        }

        $this->assertArrayHasKey('New Striker', $byName);
        $this->assertFalse($byName['New Striker']['is_new']);
        $this->assertSame((int) $linked->player_id, $byName['New Striker']['player_id']);
        $this->assertSame('Old', $byName['New Striker']['player_fname']);
        $this->assertSame('Label', $byName['New Striker']['player_lname']);
        $this->assertSame('2', $byName['New Striker']['player_uefa_id']);
        $this->assertSame([], $result['auto']['almost']);
    }

    #[Test]
    public function analyze_resolves_ffb_team_by_team_code_when_uefa_id_missing(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt', '', 'MLT');

        $this->fakeUefaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '88');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame($teamId, $result['auto']['team_id']);
        $this->assertSame('88', $result['auto']['uefa_team_id']);
    }

    #[Test]
    public function analyze_errors_when_uefa_team_is_missing(): void
    {
        [, $leagueId] = $this->seedTeamAndLeague('mlt', '88');

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('UEFA-Team', $result['errors'][0]);
    }

    #[Test]
    public function analyze_errors_when_league_identifier_is_missing(): void
    {
        [, $leagueId] = $this->seedTeamAndLeague('mlt', '88', '', '');

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '88');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('UEFA-Competition-Identifier', $result['errors'][0]);
    }

    #[Test]
    public function analyze_errors_when_ffb_team_cannot_be_resolved(): void
    {
        [, $leagueId] = $this->seedTeamAndLeague('xyz', '', '');

        $this->fakeUefaCompetitionHttp();

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '88');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Kein FFB-Team', $result['errors'][0]);
    }

    #[Test]
    public function analyze_errors_when_uefa_returns_no_players_for_team(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('arg', '6', 'ARG');

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'orderInCompetition' => 1, 'teams' => ['6']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '6',
                    'teamCode' => 'ARG',
                    'countryCode' => 'ARG',
                    'internationalName' => 'Argentina',
                    'translations' => [
                        'countryName' => ['DE' => 'Argentinien', 'EN' => 'Argentina'],
                    ],
                ],
            ], 200),
            'comp.uefa.test/v2/players*' => Http::response([
                [
                    'id' => '99',
                    'nationalTeamId' => '47',
                    'nationalJerseyNumber' => '1',
                    'nationalFieldPosition' => 'GOALKEEPER',
                    'internationalName' => 'Other Keeper',
                    'translations' => [
                        'firstName' => ['EN' => 'Other'],
                        'lastName' => ['EN' => 'Keeper'],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->service()->analyzeSquadsFromUefa($leagueId, '6');

        $this->assertFalse($result['ok']);
        $this->assertSame($teamId, $result['team_id']);
        $this->assertStringContainsString('keine Spieler', $result['errors'][0]);
        $this->assertStringContainsString('Nicht-UEFA', $result['errors'][0]);
    }

    #[Test]
    public function page_payload_loads_uefa_team_selector_for_league(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt', '88');

        $this->fakeUefaCompetitionHttp(includePlayers: false);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->andReturn([
            'user' => ['user_id' => 1, 'user_name' => 'Admin'],
            'navigation' => [],
            'selected_league' => ['league_id' => $leagueId, 'league_title' => 'Nations League'],
        ]);
        $adminCenter->shouldReceive('selectedLeagueId')->with(1)->andReturn($leagueId);

        $service = new AdminSquadService(
            $adminCenter,
            new AdminPlayerService($adminCenter),
            new WikimediaPlayerImageService,
            new UefaCompApiClient,
        );

        $payload = $service->pagePayload(1, $teamId, $leagueId, 'auto-uefa', null, null, '88');

        $this->assertSame(self::IDENTIFIER, $payload['uefa_competition_identifier']);
        $this->assertSame('88', $payload['uefa_team_id']);
        $this->assertCount(2, $payload['uefa_teams']);

        $byId = [];
        foreach ($payload['uefa_teams'] as $option) {
            $byId[$option['uefa_id']] = $option;
        }

        $this->assertTrue($byId['88']['matched']);
        $this->assertSame($teamId, $byId['88']['ffb_team_id']);
        $this->assertFalse($byId['47']['matched']);
        $this->assertSame($teamId, $payload['selected_team_id']);
    }

    #[Test]
    public function client_uses_configured_ca_bundle_when_present(): void
    {
        $bundle = storage_path('certs/cacert.pem');
        $this->assertFileExists($bundle);
        config(['services.uefa.ca_bundle' => $bundle]);

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'orderInCompetition' => 1, 'teams' => ['88']],
            ], 200),
        ]);

        $ids = (new UefaCompApiClient)->enrolledTeamIds(2014, 2027, [1]);

        $this->assertSame(['88'], $ids);
        Http::assertSentCount(1);
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
        );
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedTeamAndLeague(
        string $nationality,
        string $uefaId = '',
        string $teamCode = '',
        string $identifier = self::IDENTIFIER,
    ): array {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
            'league_uefa_competition_identifier' => $identifier,
        ]);

        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Malta',
            'team_nationality' => $nationality,
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => $uefaId,
            'team_team_code' => $teamCode,
        ]);

        return [(int) $team->team_id, (int) $league->league_id];
    }

    private function fakeUefaCompetitionHttp(bool $includePlayers = true): void
    {
        Http::preventStrayRequests();

        $fakes = [
            'comp.uefa.test/v2/rounds*' => Http::response([
                [
                    'phase' => 'TOURNAMENT',
                    'orderInCompetition' => 1,
                    'teams' => ['88', '47'],
                    'metaData' => ['name' => 'League phase'],
                ],
                [
                    'phase' => 'QUALIFYING',
                    'orderInCompetition' => 2,
                    'teams' => ['999'],
                    'metaData' => ['name' => 'Qualifying'],
                ],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '88',
                    'teamCode' => 'MLT',
                    'countryCode' => 'MLT',
                    'internationalName' => 'Malta',
                    'translations' => [
                        'countryName' => ['DE' => 'Malta', 'EN' => 'Malta'],
                    ],
                ],
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'internationalName' => 'Germany',
                    'translations' => [
                        'countryName' => ['DE' => 'Deutschland', 'EN' => 'Germany'],
                    ],
                ],
            ], 200),
        ];

        if ($includePlayers) {
            $fakes['comp.uefa.test/v2/players*'] = Http::response([
                [
                    'id' => '1',
                    'nationalTeamId' => '88',
                    'nationalJerseyNumber' => '1',
                    'nationalFieldPosition' => 'GOALKEEPER',
                    'internationalName' => 'Henry Bonello',
                    'translations' => [
                        'firstName' => ['EN' => 'Henry'],
                        'lastName' => ['EN' => 'Bonello'],
                        'name' => ['EN' => 'Henry Bonello'],
                    ],
                ],
                [
                    'id' => '2',
                    'nationalTeamId' => '88',
                    'nationalJerseyNumber' => '10',
                    'nationalFieldPosition' => 'FORWARD',
                    'internationalName' => 'New Striker',
                    'translations' => [
                        'firstName' => ['EN' => 'New'],
                        'lastName' => ['EN' => 'Striker'],
                        'name' => ['EN' => 'New Striker'],
                    ],
                ],
                [
                    'id' => '3',
                    'nationalTeamId' => '47',
                    'nationalJerseyNumber' => '7',
                    'nationalFieldPosition' => 'FORWARD',
                    'internationalName' => 'Other Player',
                    'translations' => [
                        'firstName' => ['EN' => 'Other'],
                        'lastName' => ['EN' => 'Player'],
                        'name' => ['EN' => 'Other Player'],
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
