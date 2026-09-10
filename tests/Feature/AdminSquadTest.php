<?php

namespace Tests\Feature;

use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminSquadTest extends TestCase
{
    public function test_squad_admin_redirects_guests(): void
    {
        $this->get('/admin/squad')
            ->assertRedirect(route('start', ['destination' => '/admin/squad']));
    }

    public function test_squad_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/squad')
            ->assertRedirect(route('start'));
    }

    public function test_squad_prompts_for_team_without_selection(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminSquadService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, 0, null)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_coach.png',
                        'name' => 'Kader',
                        'link' => '/admin/squad',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game' => null,
                'squad_league_id' => 0,
                'leagues' => [
                    ['game_id' => 1, 'game_title' => 'WM 2026'],
                ],
                'hint' => 'Position und Preis gelten pro Liga.',
                'countries' => ['AUT' => 'Österreich'],
                'prices' => range(1, 15),
                'positions' => ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'],
                'teams' => [
                    ['team_id' => 3, 'team_label' => 'Rapid (AUT)'],
                ],
                'selected_team_id' => 0,
                'selected_team' => null,
                'items' => [],
                'roster_active_count' => 0,
                'per_page' => 100,
                'defaults' => [
                    'playerteam_status' => 1,
                    'playerteam_player_price' => 5,
                    'playerteam_player_position' => 'd',
                    'playerteam_date_transfer' => '2008-01-01',
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/squad')
            ->assertOk()
            ->assertSee('Kader', false)
            ->assertSee('Liga', false)
            ->assertSee('Team wählen', false)
            ->assertSee('Wähle oben ein Team', false)
            ->assertDontSee('Kader-Liste', false);
    }

    public function test_squad_lists_roster_for_selected_team(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminSquadService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, 3, null)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_game' => null,
                'squad_league_id' => 1,
                'leagues' => [
                    ['game_id' => 1, 'game_title' => 'WM 2026'],
                ],
                'hint' => 'Position und Preis gelten pro Liga.',
                'countries' => ['AUT' => 'Österreich'],
                'prices' => range(1, 15),
                'positions' => ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'],
                'teams' => [
                    ['team_id' => 3, 'team_label' => 'Rapid (AUT)'],
                ],
                'selected_team_id' => 3,
                'selected_team' => ['team_id' => 3, 'team_label' => 'Rapid (AUT)'],
                'items' => [
                    [
                        'playerteam_id' => 44,
                        'player_id' => 12,
                        'player_fname' => 'Marko',
                        'player_lname' => 'Arnautovic',
                        'player_nationality' => 'AUT',
                        'player_flag_url' => '/images/ffb/flags/aut.gif',
                        'playerteam_status' => 1,
                        'playerteam_player_price' => 8,
                        'playerteam_player_position' => 's',
                        'playerteam_date_transfer' => '2008-01-01',
                        'picture_url' => '/images/ffb/players/image_na.gif',
                        'has_picture' => false,
                    ],
                ],
                'roster_active_count' => 1,
                'per_page' => 100,
                'defaults' => [
                    'playerteam_status' => 1,
                    'playerteam_player_price' => 5,
                    'playerteam_player_position' => 'd',
                    'playerteam_date_transfer' => '2008-01-01',
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/squad?team_id=3')
            ->assertOk()
            ->assertSee('Arnautovic', false)
            ->assertSee('Bestand', false)
            ->assertSee('Angriff', false)
            ->assertSee('data-roster-filter="active"', false)
            ->assertSee('data-roster-filter="all"', false)
            ->assertSee('playerteam_date_transfer', false)
            ->assertSee('Änderungen speichern', false)
            ->assertSee('id="squad-save-all"', false)
            ->assertSee('admin-squad-undo-btn', false)
            ->assertSee('Zum Löschen vormerken', false)
            ->assertDontSee('Auswahl übernehmen', false);
    }

    public function test_squad_add_tab_wires_async_player_search(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminSquadService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, 3, null)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_game' => null,
                'squad_league_id' => 1,
                'leagues' => [
                    ['game_id' => 1, 'game_title' => 'WM 2026'],
                ],
                'hint' => 'Position und Preis gelten pro Liga.',
                'countries' => ['AUT' => 'Österreich'],
                'prices' => range(1, 15),
                'positions' => ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'],
                'teams' => [
                    ['team_id' => 3, 'team_label' => 'Rapid (AUT)'],
                ],
                'selected_team_id' => 3,
                'selected_team' => ['team_id' => 3, 'team_label' => 'Rapid (AUT)'],
                'items' => [],
                'roster_active_count' => 0,
                'per_page' => 100,
                'defaults' => [
                    'playerteam_status' => 1,
                    'playerteam_player_price' => 5,
                    'playerteam_player_position' => 'd',
                    'playerteam_date_transfer' => '2008-01-01',
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/squad?team_id=3&tab=add')
            ->assertOk()
            ->assertSee('Auswahl übernehmen', false)
            ->assertSee('id="squad-defaults-row"', false)
            ->assertSee('id="squad-selected-list"', false)
            ->assertSee('Name oder ID', false)
            ->assertSee('/admin/players/search', false)
            ->assertSee('exclude_team_id', false)
            ->assertSee('data-exclude-team-id="3"', false)
            ->assertSee('id="squad-candidate-pager"', false)
            ->assertSee('Vormerken', false)
            ->assertDontSee('Filtern</button>', false);
    }

    public function test_players_search_accepts_exclude_team_id(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerService::class, function ($mock) {
            $mock->shouldReceive('search')->once()->with([
                'q' => '',
                'nationality' => '',
                'page' => 1,
                'exclude_team_id' => 3,
            ])->andReturn([
                'items' => [],
                'total' => 0,
                'page' => 1,
                'per_page' => 100,
                'last_page' => 1,
                'q' => '',
                'nationality' => '',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/admin/players/search?exclude_team_id=3')
            ->assertOk()
            ->assertJsonPath('total', 0);
    }

    public function test_squad_batch_add_redirects(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminSquadService::class, function ($mock) {
            $mock->shouldReceive('batchAdd')->once()->andReturn([
                'ok' => true,
                'message' => '2 Spieler zum Kader hinzugefügt.',
                'team_id' => 3,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/squad', [
                'team_id' => 3,
                'items' => [
                    99 => [
                        'playerteam_status' => 1,
                        'playerteam_player_price' => 5,
                        'playerteam_player_position' => 'd',
                        'playerteam_date_transfer' => '2008-01-01',
                    ],
                    100 => [
                        'playerteam_status' => 0,
                        'playerteam_player_price' => 8,
                        'playerteam_player_position' => 's',
                        'playerteam_date_transfer' => '2010-01-01',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.squad', ['team_id' => 3]))
            ->assertSessionHas('admin_message', '2 Spieler zum Kader hinzugefügt.');
    }

    public function test_squad_batch_update_redirects(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminSquadService::class, function ($mock) {
            $mock->shouldReceive('batchUpdate')->once()->andReturn([
                'ok' => true,
                'message' => '1 Eintrag gespeichert, 1 Spieler entfernt.',
                'team_id' => 3,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/squad/batch-update', [
                'team_id' => 3,
                'items' => [
                    44 => [
                        'playerteam_status' => 1,
                        'playerteam_player_price' => 9,
                        'playerteam_player_position' => 's',
                        'playerteam_date_transfer' => '2008-01-01',
                    ],
                ],
                'delete_ids' => [45],
            ])
            ->assertRedirect(route('admin.squad', ['team_id' => 3]))
            ->assertSessionHas('admin_message', '1 Eintrag gespeichert, 1 Spieler entfernt.');
    }

    public function test_squad_delete_redirects(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminSquadService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(44)->andReturn([
                'ok' => true,
                'message' => 'Spieler aus dem Kader entfernt.',
                'team_id' => 3,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/squad/44', ['team_id' => 3])
            ->assertRedirect(route('admin.squad', ['team_id' => 3]))
            ->assertSessionHas('admin_message', 'Spieler aus dem Kader entfernt.');
    }
}
