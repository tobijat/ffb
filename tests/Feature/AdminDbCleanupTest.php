<?php

namespace Tests\Feature;

use App\Models\Extremeteam;
use App\Models\League;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Userteam;
use App\Services\AdminCenterService;
use App\Services\AdminDbCleanupService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminDbCleanupTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('ffb_playerfid');
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    public function test_db_cleanup_redirects_guests(): void
    {
        $this->get('/admin/db-cleanup')
            ->assertRedirect(route('start', ['destination' => '/admin/db-cleanup']));
    }

    public function test_db_cleanup_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/db-cleanup')
            ->assertRedirect(route('start'));
    }

    public function test_db_cleanup_page_lists_collapsed_sections_without_running_tasks(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminDbCleanupService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_config.png',
                        'name' => 'DB Cleanup',
                        'link' => '/admin/db-cleanup',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_league' => null,
                'sections' => [
                    [
                        'key' => 'duplicate-playerteams',
                        'title' => 'Doppelte Kader-Einträge',
                        'hint' => 'hint-dupes',
                    ],
                    [
                        'key' => 'orphan-playerteams',
                        'title' => 'Verwaiste Kader-Einträge',
                        'hint' => 'hint-orphan-pt',
                    ],
                    [
                        'key' => 'orphan-lineup-slots',
                        'title' => 'Verwaiste Userteam- und Top/Flop-Slots',
                        'hint' => 'hint-orphan-slots',
                    ],
                ],
                'run_url' => '/admin/db-cleanup/run',
            ]);
            $mock->shouldReceive('runTask')->never();
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/db-cleanup')
            ->assertOk()
            ->assertSee('DB Cleanup', false)
            ->assertSee('Doppelte Kader-Einträge', false)
            ->assertSee('Verwaiste Kader-Einträge', false)
            ->assertSee('Verwaiste Userteam- und Top/Flop-Slots', false)
            ->assertSee('data-run-task="duplicate-playerteams"', false)
            ->assertSee('data-run-url="/admin/db-cleanup/run"', false)
            ->assertSee('Start', false)
            ->assertDontSee('Arnautovic', false)
            ->assertDontSee('Keine doppelten Spieler–Team-Zuordnungen gefunden.', false);
    }

    public function test_db_cleanup_run_returns_task_html(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminDbCleanupService::class, function ($mock) {
            $mock->shouldReceive('runTask')->once()->with('duplicate-playerteams', 'run')->andReturn([
                'ok' => true,
                'task' => 'duplicate-playerteams',
                'clean' => false,
                'count' => 1,
                'summary' => '1 Doppelgruppe · 2 Einträge insgesamt',
                'html' => '<p class="muted">1 Doppelgruppe · 2 Einträge insgesamt</p><div>Arnautovic</div>',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->postJson('/admin/db-cleanup/run', ['task' => 'duplicate-playerteams'])
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('task', 'duplicate-playerteams')
            ->assertJsonPath('clean', false)
            ->assertJsonPath('count', 1)
            ->assertJsonFragment(['summary' => '1 Doppelgruppe · 2 Einträge insgesamt'])
            ->assertJsonFragment(['html' => '<p class="muted">1 Doppelgruppe · 2 Einträge insgesamt</p><div>Arnautovic</div>']);
    }

    public function test_db_cleanup_run_delete_passes_action_to_service(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminDbCleanupService::class, function ($mock) {
            $mock->shouldReceive('runTask')->once()->with('players-without-playerteam', 'delete')->andReturn([
                'ok' => true,
                'task' => 'players-without-playerteam',
                'clean' => true,
                'count' => 0,
                'summary' => '1 Spieler gelöscht',
                'html' => '<p class="muted">Alle Spieler sind mindestens einem Team zugeordnet.</p>',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->postJson('/admin/db-cleanup/run', [
                'task' => 'players-without-playerteam',
                'action' => 'delete',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('summary', '1 Spieler gelöscht');
    }

    public function test_db_cleanup_run_rejects_unknown_task(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->postJson('/admin/db-cleanup/run', ['task' => 'unknown-task'])
            ->assertStatus(422)
            ->assertJsonPath('ok', false);
    }

    #[Test]
    public function players_without_playerteam_list_includes_delete_button(): void
    {
        $this->createSchema();

        Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Orphan',
            'player_lname' => 'Player',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $result = (new AdminDbCleanupService(Mockery::mock(AdminCenterService::class)))
            ->runTask('players-without-playerteam');

        $this->assertFalse($result['clean']);
        $this->assertStringContainsString('data-delete-task="players-without-playerteam"', $result['html']);
        $this->assertStringContainsString('>Delete</button>', $result['html']);
    }

    #[Test]
    public function delete_players_without_playerteam_removes_players_and_images(): void
    {
        $this->createSchema();

        $imagesRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-db-cleanup-'.uniqid('', true);
        $playersDir = $imagesRoot.DIRECTORY_SEPARATOR.'players';
        $teamDir = $playersDir.DIRECTORY_SEPARATOR.'7';
        mkdir($teamDir, 0775, true);
        config(['ffb.legacy_images_path' => $imagesRoot]);

        $orphanId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Orphan',
            'player_lname' => 'One',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');
        $keptId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Kept',
            'player_lname' => 'Two',
            'player_nationality' => 'GER',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $teamId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');
        Playerteam::query()->insertGetId([
            'playerteam_player_id' => $keptId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ], 'playerteam_id');

        $flatImage = $playersDir.DIRECTORY_SEPARATOR.$orphanId.'.jpg';
        $teamImage = $teamDir.DIRECTORY_SEPARATOR.'7-'.$orphanId.'.jpg';
        $keptImage = $playersDir.DIRECTORY_SEPARATOR.$keptId.'.jpg';
        file_put_contents($flatImage, 'flat');
        file_put_contents($teamImage, 'team');
        file_put_contents($keptImage, 'kept');

        try {
            $result = (new AdminDbCleanupService(Mockery::mock(AdminCenterService::class)))
                ->runTask('players-without-playerteam', 'delete');

            $this->assertTrue($result['ok']);
            $this->assertTrue($result['clean']);
            $this->assertStringContainsString('1 Spieler gelöscht', $result['summary']);
            $this->assertStringContainsString('2 Bilder entfernt', $result['summary']);
            $this->assertDatabaseMissing('ffb_player', ['player_id' => $orphanId]);
            $this->assertDatabaseHas('ffb_player', ['player_id' => $keptId]);
            $this->assertFileDoesNotExist($flatImage);
            $this->assertFileDoesNotExist($teamImage);
            $this->assertFileExists($keptImage);
        } finally {
            foreach ([$flatImage, $teamImage, $keptImage] as $path) {
                if (is_file($path)) {
                    @unlink($path);
                }
            }
            @rmdir($teamDir);
            @rmdir($playersDir);
            @rmdir($imagesRoot);
        }
    }

    #[Test]
    public function delete_action_is_rejected_for_unsupported_tasks(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new AdminDbCleanupService(Mockery::mock(AdminCenterService::class)))
            ->runTask('players-without-playerstats', 'delete');
    }

    #[Test]
    public function players_without_playerstats_include_team_names(): void
    {
        $this->createSchema();

        $austriaId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');
        $germanyId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Germany',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $playerId = (int) Player::query()->insertGetId([
            'player_foreign_id' => 'tm-1',
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        Playerteam::query()->insert([
            [
                'playerteam_player_id' => $playerId,
                'playerteam_team_id' => $germanyId,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
                'playerteam_player_note' => '',
                'playerteam_date_transfer' => null,
            ],
            [
                'playerteam_player_id' => $playerId,
                'playerteam_team_id' => $austriaId,
                'playerteam_league_id' => 2,
                'playerteam_player_picture' => '',
                'playerteam_status' => 0,
                'playerteam_player_position' => 'm',
                'playerteam_player_note' => '',
                'playerteam_date_transfer' => null,
            ],
        ]);

        $service = new AdminDbCleanupService(Mockery::mock(AdminCenterService::class));
        $players = $service->playersWithoutPlayerstats();
        $result = $service->runTask('players-without-playerstats');

        $this->assertCount(1, $players);
        $this->assertSame($playerId, $players[0]['player_id']);
        $this->assertSame(
            [
                ['team_id' => $austriaId, 'team_name' => 'Austria'],
                ['team_id' => $germanyId, 'team_name' => 'Germany'],
            ],
            $players[0]['teams']
        );
        $this->assertStringContainsString('Austria, Germany', $result['html']);
        $this->assertStringContainsString('<th scope="col">Teams</th>', $result['html']);
    }

    #[Test]
    public function orphan_playerteams_lists_missing_player_references(): void
    {
        $this->createSchema();

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

        Playerteam::query()->insertGetId([
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ], 'playerteam_id');

        $orphanPtId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => 99999,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 's',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ], 'playerteam_id');

        $rows = (new AdminDbCleanupService(Mockery::mock(AdminCenterService::class)))->orphanPlayerteams();

        $this->assertCount(1, $rows);
        $this->assertSame($orphanPtId, $rows[0]['playerteam_id']);
        $this->assertSame(99999, $rows[0]['playerteam_player_id']);
    }

    #[Test]
    public function orphan_lineup_slots_list_missing_playerteam_references(): void
    {
        $this->createSchema();

        League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_archive' => 0,
        ], 'league_id');

        $roundId = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => 1,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-09-01 00:00:00',
            'matchround_enddate' => '2026-09-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');

        $userteamId = (int) Userteam::query()->insertGetId([
            'userteam_user_id' => 7,
            'userteam_matchround_id' => $roundId,
            'userteam_score' => 0,
            'userteam_price' => 0,
            'userteam_lc_points' => 0,
        ], 'userteam_id');
        Schema::getConnection()->table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => $userteamId,
            'userteam_slot_slot' => 1,
            'userteam_slot_playerteam_id' => 4242,
        ]);

        $extremeId = (int) Extremeteam::query()->insertGetId([
            'extremeteam_top_or_flop' => 'top',
            'extremeteam_price' => 50,
            'extremeteam_matchround_id' => $roundId,
            'extremeteam_score' => 1,
        ], 'extremeteam_id');
        Schema::getConnection()->table('ffb_extremeteam_slot')->insert([
            'extremeteam_slot_extremeteam_id' => $extremeId,
            'extremeteam_slot_slot' => 2,
            'extremeteam_slot_playerteam_id' => 4343,
        ]);

        $service = new AdminDbCleanupService(Mockery::mock(AdminCenterService::class));
        $userSlots = $service->orphanUserteamSlots();
        $extremeSlots = $service->orphanExtremeteamSlots();

        $this->assertCount(1, $userSlots);
        $this->assertSame($userteamId, $userSlots[0]['userteam_id']);
        $this->assertSame(4242, $userSlots[0]['playerteam_id']);
        $this->assertCount(1, $extremeSlots);
        $this->assertSame($extremeId, $extremeSlots[0]['extremeteam_id']);
        $this->assertSame(4343, $extremeSlots[0]['playerteam_id']);
        $this->assertSame('TOP', $extremeSlots[0]['type']);
    }

    #[Test]
    public function orphan_playerteam_data_refs_list_missing_playerteam_ids(): void
    {
        $this->createSchema();

        Schema::getConnection()->table('ffb_playerstats')->insert([
            [
                'playerstats_playerteam_id' => 100,
                'playerstats_matchround_id' => 1,
                'playerstats_match_id' => 5,
                'playerstats_score' => 3,
            ],
            [
                'playerstats_playerteam_id' => 999,
                'playerstats_matchround_id' => 2,
                'playerstats_match_id' => 6,
                'playerstats_score' => 0,
            ],
        ]);
        Schema::getConnection()->table('ffb_playerprice')->insert([
            'playerprice_playerteam_id' => 999,
            'playerprice_matchround_id' => 2,
            'playerprice_price' => 4.5,
        ]);
        Schema::getConnection()->table('ffb_goal')->insert([
            'goal_playerteam_id' => 999,
            'goal_match_id' => 6,
            'goal_minute' => 12,
        ]);

        Schema::getConnection()->table('ffb_playerteam')->insert([
            'playerteam_id' => 100,
            'playerteam_player_id' => 1,
            'playerteam_team_id' => 1,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ]);

        $groups = (new AdminDbCleanupService(Mockery::mock(AdminCenterService::class)))
            ->orphanPlayerteamDataReferences();

        $byTable = [];
        foreach ($groups as $group) {
            $byTable[$group['table']] = $group['rows'];
        }

        $this->assertCount(1, $byTable['ffb_playerstats']);
        $this->assertSame(999, $byTable['ffb_playerstats'][0]['playerteam_id']);
        $this->assertCount(1, $byTable['ffb_playerprice']);
        $this->assertSame(999, $byTable['ffb_playerprice'][0]['playerteam_id']);
        $this->assertCount(1, $byTable['ffb_goal']);
        $this->assertSame(999, $byTable['ffb_goal'][0]['playerteam_id']);
        $this->assertSame([], $byTable['ffb_psgoal']);
        $this->assertSame([], $byTable['ffb_playerfid']);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('ffb_playerfid');
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
            $table->integer('league_archive')->default(0);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->integer('matchround_league_id')->default(0);
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->integer('matchround_status')->default(1);
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
            $table->unsignedInteger('playerteam_league_id')->default(0);
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_player_note')->default('');
            $table->string('playerteam_date_transfer')->nullable();
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id')->default(0);
            $table->unsignedInteger('playerstats_match_id')->nullable();
            $table->integer('playerstats_score')->default(0);
        });

        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id')->default(0);
            $table->double('playerprice_price')->default(0);
        });

        Schema::create('ffb_goal', function (Blueprint $table) {
            $table->increments('goal_id');
            $table->unsignedInteger('goal_playerteam_id');
            $table->unsignedInteger('goal_match_id')->default(0);
            $table->integer('goal_minute')->default(0);
        });

        Schema::create('ffb_psgoal', function (Blueprint $table) {
            $table->increments('psgoal_id');
            $table->unsignedInteger('psgoal_playerteam_id');
            $table->unsignedInteger('psgoal_match_id')->default(0);
        });

        Schema::create('ffb_playerfid', function (Blueprint $table) {
            $table->increments('playerfid_id');
            $table->unsignedInteger('playerfid_playerteam_id');
            $table->string('playerfid_name_wf')->default('');
        });

        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->increments('userteam_id');
            $table->unsignedInteger('userteam_user_id');
            $table->unsignedInteger('userteam_matchround_id');
            $table->integer('userteam_score')->default(0);
            $table->double('userteam_price')->default(0);
            $table->integer('userteam_lc_points')->default(0);
        });

        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
        });

        Schema::create('ffb_extremeteam', function (Blueprint $table) {
            $table->increments('extremeteam_id');
            $table->string('extremeteam_top_or_flop', 8);
            $table->decimal('extremeteam_price', 9, 2)->default(0);
            $table->unsignedInteger('extremeteam_matchround_id');
            $table->integer('extremeteam_score')->default(-1);
        });

        Schema::create('ffb_extremeteam_slot', function (Blueprint $table) {
            $table->increments('extremeteam_slot_id');
            $table->unsignedInteger('extremeteam_slot_extremeteam_id');
            $table->unsignedTinyInteger('extremeteam_slot_slot');
            $table->unsignedInteger('extremeteam_slot_playerteam_id');
        });
    }
}
