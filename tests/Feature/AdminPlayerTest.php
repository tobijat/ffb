<?php

namespace Tests\Feature;

use App\Services\AdminPlayerService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminPlayerTest extends TestCase
{
    public function test_players_admin_redirects_guests(): void
    {
        $this->get('/admin/players')
            ->assertRedirect(route('start', ['destination' => '/admin/players']));
    }

    public function test_players_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
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
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
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
                'selected_game' => null,
                'countries' => ['AUT' => 'Österreich', 'GER' => 'Deutschland'],
                'form' => [
                    'player_id' => '',
                    'player_fname' => '',
                    'player_lname' => '',
                    'player_nationality' => '',
                    'player_status' => 1,
                    'player_status_description' => '',
                    'player_foreign_id' => '',
                ],
                'mode' => 'create',
                'per_page' => 100,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/players')
            ->assertOk()
            ->assertSee('Spieler', false)
            ->assertSee('name="player_fname"', false)
            ->assertSee('player_foreign_id', false)
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
                        'player_foreign_id' => '232454/nadiem-amiri',
                        'tm_url' => 'https://www.transfermarkt.at/nadiem-amiri/profil/spieler/232454',
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

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/admin/players/search?q=12&nationality=AUT&page=2')
            ->assertOk()
            ->assertJsonPath('items.0.player_lname', 'Arnautovic')
            ->assertJsonPath('items.0.tm_url', 'https://www.transfermarkt.at/nadiem-amiri/profil/spieler/232454')
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

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/players', [
                'player_fname' => 'David',
                'player_lname' => 'Alaba',
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_status_description' => '',
                'player_foreign_id' => '59016',
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

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/players/batch-update', [
                'items' => [
                    12 => [
                        'player_fname' => 'Marko',
                        'player_lname' => 'Arnautovic',
                        'player_nationality' => 'AUT',
                        'player_status' => 1,
                        'player_status_description' => '',
                        'player_foreign_id' => '123/marko',
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

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/players/12')
            ->assertRedirect(route('admin.players'))
            ->assertSessionHas('admin_message', 'Spieler erfolgreich gelöscht.');
    }
}
