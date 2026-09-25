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
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config([
            'services.uefa.base_url' => 'https://comp.uefa.test/v2',
            'services.uefa.competitions' => [
                'nations_league_2027' => [
                    'label' => 'Nations League 2026/2027 (Ligaphase)',
                    'competition_id' => 2014,
                    'season_year' => 2027,
                    'round_orders' => [1],
                ],
            ],
        ]);
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
    public function analyze_builds_draft_from_uefa_players_for_selected_team(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt');

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

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                [
                    'orderInCompetition' => 1,
                    'teams' => ['88', '47'],
                    'metaData' => ['name' => 'League phase'],
                ],
                [
                    'orderInCompetition' => 2,
                    'teams' => ['999'],
                    'metaData' => ['name' => 'Play-offs'],
                ],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '88',
                    'teamCode' => 'MLT',
                    'countryCode' => 'MLT',
                    'internationalName' => 'Malta',
                ],
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'internationalName' => 'Germany',
                ],
            ], 200),
            'comp.uefa.test/v2/players*' => Http::response([
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
            ], 200),
        ]);

        $result = $this->service()->analyzeSquadsFromUefa($teamId, $leagueId, 'nations_league_2027');

        $this->assertTrue($result['ok']);
        $this->assertSame('uefa', $result['auto']['source_kind']);
        $this->assertSame('nations_league_2027', $result['auto']['uefa_competition_key']);
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
        $this->assertArrayNotHasKey('Other Player', $byName);
    }

    #[Test]
    public function compare_uefa_teams_reports_matches_and_mismatches(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt');
        unset($teamId, $leagueId);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Only FFB',
            'team_nationality' => 'xyz',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                [
                    'orderInCompetition' => 1,
                    'teams' => ['88', '47'],
                ],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '88',
                    'teamCode' => 'MLT',
                    'countryCode' => 'MLT',
                    'internationalName' => 'Malta',
                ],
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'internationalName' => 'Germany',
                ],
            ], 200),
        ]);

        $ffbTeams = Team::query()
            ->orderBy('team_name')
            ->get()
            ->map(static fn (Team $team): array => [
                'team_id' => (int) $team->team_id,
                'team_label' => (string) $team->team_name,
                'team_nationality' => strtoupper((string) $team->team_nationality),
                'active_count' => 0,
            ])
            ->all();

        $check = $this->service()->compareUefaTeamsWithFfb('nations_league_2027', $ffbTeams);

        $this->assertTrue($check['ok']);
        $this->assertCount(1, $check['matched']);
        $this->assertSame('MLT', $check['matched'][0]['fifa_code']);
        $this->assertCount(1, $check['only_ffb']);
        $this->assertSame('XYZ', $check['only_ffb'][0]['fifa_code']);
        $this->assertCount(1, $check['only_uefa']);
        $this->assertSame('GER', $check['only_uefa'][0]['fifa_code']);
    }

    #[Test]
    public function analyze_errors_when_uefa_competition_is_missing(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('mlt');

        $result = $this->service()->analyzeSquadsFromUefa($teamId, $leagueId, '');

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('UEFA-Liga', $result['errors'][0]);
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
                ['orderInCompetition' => 1, 'teams' => ['88']],
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
    private function seedTeamAndLeague(string $nationality): array
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Malta',
            'team_nationality' => $nationality,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        return [(int) $team->team_id, (int) $league->league_id];
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
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_foreign_id')->default('');
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
    }
}
