<?php

namespace Tests\Feature;

use App\Services\AdminTeamService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminTeamTest extends TestCase
{
    public function test_teams_admin_redirects_guests(): void
    {
        $this->get('/admin/teams')
            ->assertRedirect(route('start', ['destination' => '/admin/teams']));
    }

    public function test_teams_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/teams')
            ->assertRedirect(route('start'));
    }

    public function test_teams_admin_renders_for_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminTeamService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_team.png',
                        'name' => 'Teams',
                        'link' => '/admin/teams',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game' => null,
                'icons' => [
                    [
                        'key' => 'aut',
                        'url' => '/images/ffb/flags/aut.gif',
                        'label' => 'Österreich',
                        'shirt_url' => '/images/ffb/shirts/shirt_AUT.png',
                        'has_shirt' => true,
                    ],
                    [
                        'key' => 'wernberg',
                        'url' => '/images/ffb/flags/wernberg.gif',
                        'label' => 'wernberg',
                        'shirt_url' => null,
                        'has_shirt' => false,
                    ],
                ],
                'prices' => range(1, 15),
                'items' => [
                    [
                        'team_id' => 3,
                        'team_name' => 'Rapid',
                        'team_nationality' => 'aut',
                        'team_icon_label' => 'Österreich',
                        'team_price' => 8,
                        'team_status' => 1,
                        'flag_url' => '/images/ffb/flags/aut.gif',
                        'teamfid_fid_tm' => '170',
                        'teamfid_name_tm' => 'sk-rapid-wien',
                        'teamfid_url_tm' => 'https://www.transfermarkt.at/sk-rapid-wien/startseite/verein/170',
                        'teamfid_name_wf' => 'sk-rapid-wien',
                        'teamfid_url_wf' => 'https://www.weltfussball.de/teams/sk-rapid-wien/',
                        'teamfid_url_foe' => 'https://vereine.oefb.at/123',
                    ],
                ],
                'form' => [
                    'team_id' => '',
                    'team_name' => '',
                    'team_nationality' => '',
                    'team_icon_key' => '',
                    'team_price' => 5,
                    'team_status' => 1,
                    'teamfid_fid_tm' => '',
                    'teamfid_name_tm' => '',
                    'teamfid_name_wf' => '',
                    'teamfid_url_foe' => '',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/teams')
            ->assertOk()
            ->assertSee('Teams', false)
            ->assertSee('Rapid', false)
            ->assertSee('name="team_name"', false)
            ->assertSee('Symbol (Flagge / Logo)', false)
            ->assertSee('Symbol wählen', false)
            ->assertSee('team_icon_file', false)
            ->assertSee('team_shirt_file', false)
            ->assertSee('wernberg', false)
            ->assertDontSee('Wird für Flaggen', false)
            ->assertSee('transfermarkt.at', false)
            ->assertSee('Hinzufügen', false);
    }

    public function test_teams_store_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminTeamService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => true,
                'message' => 'Team erfolgreich hinzugefügt.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/teams', [
                'team_name' => 'Austria Wien',
                'team_nationality' => 'aut',
                'team_price' => 7,
                'team_status' => 1,
                'teamfid_fid_tm' => '14',
                'teamfid_name_tm' => 'fk-austria-wien',
                'teamfid_name_wf' => 'fk-austria-wien',
                'teamfid_url_foe' => '456',
            ])
            ->assertRedirect(route('admin.teams'))
            ->assertSessionHas('admin_message', 'Team erfolgreich hinzugefügt.');
    }

    public function test_teams_delete_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminTeamService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(3)->andReturn([
                'ok' => true,
                'message' => 'Team erfolgreich gelöscht.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/teams/3')
            ->assertRedirect(route('admin.teams'))
            ->assertSessionHas('admin_message', 'Team erfolgreich gelöscht.');
    }
}
