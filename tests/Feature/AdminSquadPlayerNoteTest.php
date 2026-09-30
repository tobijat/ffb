<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminSquadPlayerNoteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function batch_update_persists_player_note(): void
    {
        [$teamId, $ptId] = $this->seedRosterPlayer();

        $result = $this->service()->batchUpdate([
            'team_id' => $teamId,
            'items' => [
                $ptId => [
                    'playerteam_status' => 1,
                    'playerteam_player_position' => 'm',
                    'playerteam_player_note' => '  Knöchel  ',
                ],
            ],
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_id' => $ptId,
            'playerteam_player_note' => 'Knöchel',
            'playerteam_player_position' => 'm',
        ]);
    }

    #[Test]
    public function batch_update_rejects_note_longer_than_255_characters(): void
    {
        [$teamId, $ptId] = $this->seedRosterPlayer();

        $result = $this->service()->batchUpdate([
            'team_id' => $teamId,
            'items' => [
                $ptId => [
                    'playerteam_status' => 1,
                    'playerteam_player_position' => 'm',
                    'playerteam_player_note' => str_repeat('x', 256),
                ],
            ],
        ]);

        $this->assertFalse($result['ok']);
        $this->assertNotEmpty($result['errors'] ?? []);
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_id' => $ptId,
            'playerteam_player_note' => '',
        ]);
    }

    private function service(): AdminSquadService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $players = Mockery::mock(AdminPlayerService::class);

        return new AdminSquadService($adminCenter, $players, new WikimediaPlayerImageService);
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedRosterPlayer(): array
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_symbol' => '',
            'league_archive' => 0,
        ], 'league_id');

        $teamId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $playerId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $ptId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ], 'playerteam_id');

        return [$teamId, $ptId];
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
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id');
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_player_note')->default('');
            $table->string('playerteam_date_transfer')->nullable();
        });
    }
}
