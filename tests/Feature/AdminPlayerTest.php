<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\FfbAdminAccess;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPlayerTest extends TestCase
{
    public function test_players_admin_redirects_guests(): void
    {
        $this->get('/admin/players')
            ->assertRedirect(route('start', ['destination' => 'admin/players']));
    }

    public function test_players_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->actingAsFfbUser(544)
            ->get('/admin/players')
            ->assertRedirect(route('start'));
    }

    public function test_players_admin_renders_for_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_player.png',
                        'name' => 'Spieler',
                        'link' => '/admin/players',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_league' => null,
                'countries' => ['AUT' => 'Österreich', 'GER' => 'Deutschland'],
                'form' => [
                    'player_id' => '',
                    'player_fname' => '',
                    'player_lname' => '',
                    'player_nationality' => '',
                    'player_status' => 1,
                    'player_status_description' => '',
                    'player_foreign_id' => '',
                    'player_uefa_id' => '',
                    'player_fifa_id' => '',
                ],
                'mode' => 'create',
                'per_page' => 100,
                'tab' => 'manual',
                'teams' => [],
                'duplicates_prepare_url' => '/admin/players/duplicates/prepare',
                'duplicates_check_url' => '/admin/players/duplicates/check',
                'duplicates_merge_url' => '/admin/players/duplicates/merge',
                'player_merge_prepare_url' => '/admin/players/merge/prepare',
                'player_merge_preview_url' => '/admin/players/merge/preview',
                'player_merge_execute_url' => '/admin/players/merge/execute',
            ]);
        });

        $this->actingAsFfbUser(544)
            ->get('/admin/players')
            ->assertOk()
            ->assertSee('Spieler', false)
            ->assertSee('Duplikate', false)
            ->assertSee('name="player_fname"', false)
            ->assertSee('name="player_uefa_id"', false)
            ->assertSee('name="player_fifa_id"', false)
            ->assertSee('UEFA-ID', false)
            ->assertSee('FIFA-ID', false)
            ->assertDontSee('TM-ID', false)
            ->assertDontSee('player_foreign_id', false)
            ->assertSee('Name oder ID', false)
            ->assertSee('/admin/players/search', false)
            ->assertSee('/admin/players/batch-update', false)
            ->assertSee('id="player-list-pager"', false)
            ->assertSee('id="player-save-all"', false)
            ->assertSee('data-list-mode="pending"', false)
            ->assertSee('Änderungen speichern', false)
            ->assertSee('Hinzufügen', false)
            ->assertDontSee('Filtern</button>', false)
            ->assertDontSee('playerteam', false);
    }

    public function test_players_duplicates_tab_renders_team_selector(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, null, 'create', 'duplicates')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_league' => null,
                'countries' => [],
                'form' => [
                    'player_id' => '',
                    'player_fname' => '',
                    'player_lname' => '',
                    'player_nationality' => '',
                    'player_status' => 1,
                    'player_status_description' => '',
                    'player_foreign_id' => '',
                    'player_uefa_id' => '',
                    'player_fifa_id' => '',
                ],
                'mode' => 'create',
                'per_page' => 100,
                'tab' => 'duplicates',
                'teams' => [
                    ['team_id' => 7, 'team_name' => 'Rapid'],
                ],
                'duplicates_prepare_url' => '/admin/players/duplicates/prepare',
                'duplicates_check_url' => '/admin/players/duplicates/check',
                'duplicates_merge_url' => '/admin/players/duplicates/merge',
                'player_merge_prepare_url' => '/admin/players/merge/prepare',
                'player_merge_preview_url' => '/admin/players/merge/preview',
                'player_merge_execute_url' => '/admin/players/merge/execute',
            ]);
        });

        $this->actingAsFfbUser(544)
            ->get('/admin/players?tab=duplicates')
            ->assertOk()
            ->assertSee('Duplikate', false)
            ->assertSee('Rapid', false)
            ->assertSee('id="admin-players-duplicates-run"', false)
            ->assertSee('id="admin-players-duplicates-scope"', false)
            ->assertSee('komplette DB', false)
            ->assertSee('nur gleiche Nationalität', false)
            ->assertSee('nur innerhalb des Teams', false)
            ->assertSee('admin-players-duplicates-progress', false)
            ->assertSee('admin-players-duplicates-merge-panel', false)
            ->assertSee('/admin/players/duplicates/prepare', false)
            ->assertSee('/admin/players/duplicates/check', false)
            ->assertSee('/admin/players/duplicates/merge', false)
            ->assertSee('/admin/players/merge/prepare', false)
            ->assertSee('/admin/players/merge/preview', false)
            ->assertSee('/admin/players/merge/execute', false)
            ->assertDontSee('name="player_fname"', false);
    }

    public function test_players_search_returns_json(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('search')->once()->with([
                'q' => '12',
                'nationality' => 'AUT',
                'page' => 2,
                'exclude_team_id' => 0,
                'exclude_league_id' => 0,
            ])->andReturn([
                'items' => [
                    [
                        'player_id' => 12,
                        'player_fname' => 'Marko',
                        'player_lname' => 'Arnautovic',
                        'player_nationality' => 'AUT',
                        'player_nationality_label' => 'Österreich',
                        'player_status' => 1,
                        'player_status_description' => '',
                        'player_uefa_id' => '25001',
                        'player_fifa_id' => '12345',
                        'picture_url' => '/images/ffb/players/7/1.jpg',
                        'flag_url' => '/images/ffb/flags/aut.gif',
                    ],
                ],
                'total' => 101,
                'page' => 2,
                'per_page' => 100,
                'last_page' => 2,
                'q' => '12',
                'nationality' => 'AUT',
            ]);
        });

        $this->actingAsFfbUser(544)
            ->getJson('/admin/players/search?q=12&nationality=AUT&page=2')
            ->assertOk()
            ->assertJsonPath('items.0.player_lname', 'Arnautovic')
            ->assertJsonPath('items.0.player_uefa_id', '25001')
            ->assertJsonPath('items.0.player_fifa_id', '12345')
            ->assertJsonPath('items.0.picture_url', '/images/ffb/players/7/1.jpg')
            ->assertJsonPath('page', 2)
            ->assertJsonPath('per_page', 100)
            ->assertJsonPath('total', 101);
    }

    public function test_players_store_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => true,
                'message' => 'Spieler erfolgreich hinzugefügt.',
            ]);
        });

        $this->actingAsFfbUser(544)
            ->post('/admin/players', [
                'player_fname' => 'David',
                'player_lname' => 'Alaba',
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_status_description' => '',
                'player_uefa_id' => '25001',
                'player_fifa_id' => '43948',
            ])
            ->assertRedirect(route('admin.players'))
            ->assertSessionHas('admin_message', 'Spieler erfolgreich hinzugefügt.');
    }

    public function test_players_batch_update_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('batchUpdate')->once()->andReturn([
                'ok' => true,
                'message' => '1 Spieler gespeichert, 1 Spieler gelöscht.',
            ]);
        });

        $this->actingAsFfbUser(544)
            ->post('/admin/players/batch-update', [
                'items' => [
                    12 => [
                        'player_fname' => 'Marko',
                        'player_lname' => 'Arnautovic',
                        'player_nationality' => 'AUT',
                        'player_status' => 1,
                        'player_status_description' => '',
                        'player_uefa_id' => '25001',
                        'player_fifa_id' => '12345',
                    ],
                ],
                'delete_ids' => [99],
            ])
            ->assertRedirect(route('admin.players'))
            ->assertSessionHas('admin_message', '1 Spieler gespeichert, 1 Spieler gelöscht.');
    }

    public function test_players_delete_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(12)->andReturn([
                'ok' => true,
                'message' => 'Spieler erfolgreich gelöscht.',
            ]);
        });

        $this->actingAsFfbUser(544)
            ->delete('/admin/players/12')
            ->assertRedirect(route('admin.players'))
            ->assertSessionHas('admin_message', 'Spieler erfolgreich gelöscht.');
    }

    #[Test]
    public function duplicate_names_compares_team_players_against_database_with_progress_chunks(): void
    {
        $this->createPlayerDuplicatesSchema();

        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Bundesliga',
            'league_type' => 'nation',
            'league_archive' => 0,
        ], 'league_id');

        $teamId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Rapid',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $otherTeamId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $p1 = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Phillipp',
            'player_lname' => 'Mwene',
            'player_nationality' => 'AUT',
            'player_uefa_id' => 'uefa-p1',
            'player_fifa_id' => '',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');
        $p2 = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Phillip',
            'player_lname' => 'Mwene',
            'player_nationality' => 'AUT',
            'player_uefa_id' => '',
            'player_fifa_id' => 'fifa-p2',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');
        $p3 = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Max',
            'player_lname' => 'Mustermann',
            'player_nationality' => 'AUT',
            'player_uefa_id' => '',
            'player_fifa_id' => '',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');
        $pOther = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Phillipp',
            'player_lname' => 'Mwene',
            'player_nationality' => 'AUT',
            'player_uefa_id' => 'uefa-other',
            'player_fifa_id' => 'fifa-other',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');
        $pOtherGer = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Phillipp',
            'player_lname' => 'Mwene',
            'player_nationality' => 'GER',
            'player_uefa_id' => '',
            'player_fifa_id' => '',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        foreach ([$p1, $p2, $p3] as $playerId) {
            Playerteam::query()->insert([
                'playerteam_player_id' => $playerId,
                'playerteam_team_id' => $teamId,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'd',
                'playerteam_player_note' => '',
                'playerteam_date_transfer' => null,
            ]);
        }
        Playerteam::query()->insert([
            'playerteam_player_id' => $pOther,
            'playerteam_team_id' => $otherTeamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ]);
        Playerteam::query()->insert([
            'playerteam_player_id' => $pOtherGer,
            'playerteam_team_id' => $otherTeamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ]);

        $service = new AdminPlayerService(Mockery::mock(AdminCenterService::class));

        $preparedSame = $service->prepareDuplicateNames($teamId, AdminPlayerService::DUPLICATE_SCOPE_NATIONALITY);
        $this->assertTrue($preparedSame['ok']);
        $this->assertSame(3, count($preparedSame['players']));
        // 3 AUT teammates + 1 AUT other (GER excluded); each of 3 team players checks 3 others
        $this->assertSame(9, $preparedSame['total_checks']);

        $preparedAll = $service->prepareDuplicateNames($teamId, AdminPlayerService::DUPLICATE_SCOPE_DB);
        // 4 other players in DB for each of 3 team players
        $this->assertSame(12, $preparedAll['total_checks']);

        $preparedTeam = $service->prepareDuplicateNames($teamId, AdminPlayerService::DUPLICATE_SCOPE_TEAM);
        // each of 3 team players checks the other 2
        $this->assertSame(6, $preparedTeam['total_checks']);

        $rawGroups = [];
        foreach ($preparedSame['players'] as $player) {
            $result = $service->checkDuplicateNamePlayer(
                $teamId,
                (int) $player['player_id'],
                AdminPlayerService::DUPLICATE_SCOPE_NATIONALITY,
            );
            $this->assertTrue($result['ok']);
            $this->assertSame((int) $player['checks'], (int) $result['checks']);
            if ($result['has_matches'] && is_array($result['group'] ?? null)) {
                $rawGroups[] = $result['group'];
            }
        }

        // Both Rapid Mwene variants match each other and the Austria entry → one merged group.
        $this->assertSame(2, count($rawGroups));
        $merged = $service->mergeDuplicateNameGroups($teamId, $rawGroups);
        $this->assertTrue($merged['ok']);
        $this->assertFalse($merged['clean']);
        $this->assertSame(1, $merged['count']);
        $this->assertSame(1, $merged['match_count']);
        $this->assertCount(2, $merged['groups'][0]['players']);
        $this->assertStringContainsString('Mwene', $merged['html']);
        $this->assertStringContainsString('Phillipp', $merged['html']);
        $this->assertStringContainsString('Phillip', $merged['html']);
        $this->assertStringContainsString('2 Team-Spieler', $merged['html']);
        $this->assertStringContainsString('Austria', $merged['html']);
        $this->assertStringContainsString('UEFA uefa-p1', $merged['html']);
        $this->assertStringContainsString('FIFA fifa-p2', $merged['html']);
        $this->assertStringContainsString('UEFA uefa-other', $merged['html']);
        $this->assertStringContainsString('FIFA fifa-other', $merged['html']);
        $this->assertStringContainsString('admin-duplicates-squad-list', $merged['html']);
        $this->assertStringContainsString('Ähnlichkeit im Team', $merged['html']);
        $this->assertStringContainsString('Start merge', $merged['html']);
        $this->assertMatchesRegularExpression('/admin-auto-squad-badge[^>]*>[^<]+/', $merged['html']);
        $this->assertStringNotContainsString('Mustermann', $merged['html']);
        $this->assertStringNotContainsString('#'.$pOtherGer, $merged['html']);

        $teamGroups = [];
        foreach ($preparedTeam['players'] as $player) {
            $result = $service->checkDuplicateNamePlayer(
                $teamId,
                (int) $player['player_id'],
                AdminPlayerService::DUPLICATE_SCOPE_TEAM,
            );
            if ($result['has_matches'] && is_array($result['group'] ?? null)) {
                $teamGroups[] = $result['group'];
            }
        }
        $mergedTeam = $service->mergeDuplicateNameGroups($teamId, $teamGroups);
        $this->assertSame(1, $mergedTeam['count']);
        $this->assertSame(0, $mergedTeam['match_count']);
        $this->assertCount(2, $mergedTeam['groups'][0]['players']);
        $this->assertNotEmpty($mergedTeam['groups'][0]['team_similarities']);
        $this->assertStringContainsString('Ähnlichkeit im Team', $mergedTeam['html']);
        $this->assertStringNotContainsString('Austria', $mergedTeam['html']);

        $p1All = $service->checkDuplicateNamePlayer($teamId, $p1, AdminPlayerService::DUPLICATE_SCOPE_DB);
        $this->assertTrue($p1All['has_matches']);
        $mergedAll = $service->mergeDuplicateNameGroups($teamId, [$p1All['group']]);
        $this->assertStringContainsString('#'.$pOtherGer, $mergedAll['html']);
    }

    private function createPlayerDuplicatesSchema(): void
    {
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
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
            $table->string('player_uefa_id')->default('');
            $table->string('player_fifa_id')->default('');
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
    }
}
