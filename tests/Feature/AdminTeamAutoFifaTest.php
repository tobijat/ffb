<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminTeamService;
use App\Services\FifaCompApiClient;
use App\Services\UefaCompApiClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTeamAutoFifaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config(['services.fifa.base_url' => 'https://api.fifa.test/api/v3']);
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
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_uefa_competition_identifier' => '',
            'league_fifa_competition_identifier' => 'idCompetition=17&idSeason=285023',
        ]);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Schweiz',
            'team_nationality' => 'sui',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_fifa_id' => '',
            'team_team_code' => '',
        ]);

        $this->fakeFifaTeams([
            $this->fifaTeam('43971', 'SUI', 'Schweiz'),
            $this->fifaTeam('43843', 'ALG', 'Algerien'),
        ]);

        $result = $this->service()->analyzeFifaTeams((int) $league->league_id);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertCount(2, $result['auto_fifa']['rows']);

        $byCode = [];
        foreach ($result['auto_fifa']['rows'] as $row) {
            $byCode[$row['fifa_team_code']] = $row;
        }

        $this->assertSame('matched', $byCode['SUI']['match_status']);
        $this->assertSame('Schweiz', $byCode['SUI']['team_name']);
        $this->assertSame('43971', $byCode['SUI']['team_fifa_id']);
        $this->assertSame('unmatched', $byCode['ALG']['match_status']);
        $this->assertSame(0, $byCode['ALG']['team_id']);
    }

    #[Test]
    public function save_updates_matched_and_creates_new(): void
    {
        $switzerland = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Schweiz',
            'team_nationality' => 'sui',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_fifa_id' => '',
            'team_team_code' => '',
        ]);

        $result = $this->service()->saveFifaTeams([
            [
                'team_id' => (int) $switzerland->team_id,
                'create_new' => 0,
                'team_name' => 'Should Not Overwrite Name',
                'team_nationality' => 'xxx',
                'fifa_name' => 'Schweiz',
                'fifa_id' => '43971',
                'fifa_team_code' => 'SUI',
                'team_fifa_id' => '43971',
                'team_team_code' => 'SUI',
            ],
            [
                'team_id' => 0,
                'create_new' => 1,
                'team_name' => 'Algerien',
                'team_nationality' => 'alg',
                'fifa_name' => 'Algerien',
                'fifa_id' => '43843',
                'fifa_team_code' => 'ALG',
                'team_fifa_id' => '43843',
                'team_team_code' => 'ALG',
            ],
        ], 'idCompetition=17&idSeason=285023');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));

        $switzerland->refresh();
        $this->assertSame('43971', (string) $switzerland->team_fifa_id);
        $this->assertSame('SUI', (string) $switzerland->team_team_code);
        $this->assertSame('Schweiz', (string) $switzerland->team_name);
        $this->assertSame('sui', (string) $switzerland->team_nationality);

        $this->assertDatabaseHas('ffb_team', [
            'team_name' => 'Algerien',
            'team_nationality' => 'alg',
            'team_fifa_id' => '43843',
            'team_team_code' => 'ALG',
        ]);
    }

    #[Test]
    public function analyze_falls_back_to_team_code_when_fifa_id_missing(): void
    {
        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_uefa_competition_identifier' => '',
            'league_fifa_competition_identifier' => 'idCompetition=17&idSeason=285023',
        ]);

        $byCode = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Switzerland By Code',
            'team_nationality' => 'xxx',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_fifa_id' => '',
            'team_team_code' => 'SUI',
        ]);

        $this->fakeFifaTeams([
            $this->fifaTeam('43971', 'SUI', 'Schweiz'),
        ]);

        $result = $this->service()->analyzeFifaTeams((int) $league->league_id);

        $this->assertTrue($result['ok']);
        $this->assertSame((int) $byCode->team_id, $result['auto_fifa']['rows'][0]['team_id']);
        $this->assertSame('matched', $result['auto_fifa']['rows'][0]['match_status']);
    }

    #[Test]
    public function analyze_prefers_team_fifa_id_over_nationality_fallback(): void
    {
        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_uefa_competition_identifier' => '',
            'league_fifa_competition_identifier' => 'idCompetition=17&idSeason=285023',
        ]);

        $linked = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Algerien Linked',
            'team_nationality' => 'xxx',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_fifa_id' => '43843',
            'team_team_code' => 'ALG',
        ]);

        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Wrong Nat Match',
            'team_nationality' => 'alg',
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_fifa_id' => '',
            'team_team_code' => '',
        ]);

        $this->fakeFifaTeams([
            $this->fifaTeam('43843', 'ALG', 'Algerien'),
        ]);

        $result = $this->service()->analyzeFifaTeams((int) $league->league_id);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto_fifa']['rows']);
        $this->assertSame((int) $linked->team_id, $result['auto_fifa']['rows'][0]['team_id']);
        $this->assertSame('matched', $result['auto_fifa']['rows'][0]['match_status']);
    }

    private function service(): AdminTeamService
    {
        $center = Mockery::mock(AdminCenterService::class);

        return new AdminTeamService(
            $center,
            new UefaCompApiClient,
            new FifaCompApiClient('https://api.fifa.test/api/v3'),
        );
    }

    /**
     * @param  list<array<string, mixed>>  $teams
     */
    private function fakeFifaTeams(array $teams): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.fifa.test/api/v3/competitions/teams/*' => Http::response([
                'Results' => $teams,
            ], 200),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function fifaTeam(string $id, string $code, string $nameDe): array
    {
        return [
            'IdTeam' => $id,
            'Abbreviation' => $code,
            'IdCountry' => $code,
            'Name' => [['Locale' => 'de-DE', 'Description' => $nameDe]],
            'ShortClubName' => $nameDe,
        ];
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
            $table->string('team_fifa_id')->default('');
            $table->string('team_team_code')->default('');
        });
    }
}
