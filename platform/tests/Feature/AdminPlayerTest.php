<?php

namespace Tests\Feature;

use App\Services\AdminPlayerService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminPlayerTest extends TestCase
{
    public function test_players_admin_redirects_guests(): void
    {
        $this->get('/admin/players')
            ->assertRedirect(route('start', ['destination' => '/platform/admin/players']));
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

        $items = new LengthAwarePaginator(
            [
                [
                    'player_id' => 12,
                    'player_fname' => 'Marko',
                    'player_lname' => 'Arnautovic',
                    'player_nationality' => 'AUT',
                    'player_nationality_label' => 'Österreich',
                    'player_status' => 1,
                    'player_status_description' => '',
                    'player_foreign_id' => '4248',
                    'flag_url' => '/images/ffb/flags/aut.gif',
                ],
            ],
            1,
            50,
            1,
            ['path' => '/admin/players']
        );

        $this->mock(AdminPlayerService::class, function ($mock) use ($items) {
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
                        'link' => '/platform/admin/players',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game' => null,
                'countries' => ['AUT' => 'Österreich', 'GER' => 'Deutschland'],
                'filters' => ['q' => '', 'nationality' => '', 'page' => 1],
                'items' => $items,
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
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/players')
            ->assertOk()
            ->assertSee('Spieler', false)
            ->assertSee('Arnautovic', false)
            ->assertSee('name="player_fname"', false)
            ->assertSee('player_foreign_id', false)
            ->assertSee('Filtern', false)
            ->assertSee('Hinzufügen', false)
            ->assertDontSee('playerteam', false);
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
