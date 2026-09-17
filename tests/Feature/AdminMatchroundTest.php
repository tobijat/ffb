<?php

namespace Tests\Feature;

use App\Services\AdminMatchroundService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminMatchroundTest extends TestCase
{
    public function test_matchrounds_admin_redirects_guests(): void
    {
        $this->get('/admin/matchrounds')
            ->assertRedirect(route('start', ['destination' => '/admin/matchrounds']));
    }

    public function test_matchrounds_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matchrounds')
            ->assertRedirect(route('start'));
    }

    public function test_matchrounds_prompts_for_league_without_game(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchroundService::class, function ($mock) {
            $mock->shouldReceive('defaultLeagueId')->once()->with(544)->andReturn(0);
            $mock->shouldReceive('pagePayload')->once()->with(544, 0, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_matchround.png',
                        'name' => 'Spielrunden',
                        'link' => '/admin/matchrounds',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'leagues' => [
                    ['league_id' => 26, 'league_title' => 'Testliga', 'league_archive' => 0],
                ],
                'selected_league_id' => 0,
                'selected_league_title' => null,
                'items' => [],
                'form' => [
                    'matchround_id' => '',
                    'matchround_league_id' => 0,
                    'matchround_title' => '',
                    'matchround_status' => 1,
                    'matchround_startdate' => '',
                    'matchround_enddate' => '',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matchrounds')
            ->assertOk()
            ->assertSee('Spielrunden', false)
            ->assertSee('Bitte zuerst unter', false)
            ->assertDontSee('name="matchround_title"', false);
    }

    public function test_matchrounds_defaults_to_preselected_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchroundService::class, function ($mock) {
            $mock->shouldReceive('defaultLeagueId')->once()->with(544)->andReturn(26);
            $mock->shouldReceive('pagePayload')->once()->with(544, 26, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'leagues' => [
                    ['league_id' => 26, 'league_title' => 'Testliga', 'league_archive' => 0],
                ],
                'selected_league_id' => 26,
                'selected_league_title' => 'Testliga',
                'items' => [],
                'form' => [
                    'matchround_id' => '',
                    'matchround_league_id' => 26,
                    'matchround_title' => '',
                    'matchround_status' => 1,
                    'matchround_startdate' => '',
                    'matchround_enddate' => '',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matchrounds')
            ->assertOk()
            ->assertSee('Testliga', false)
            ->assertSee('name="matchround_title"', false)
            ->assertDontSee('Wähle oben eine Liga', false);
    }

    public function test_matchrounds_lists_rounds_for_selected_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchroundService::class, function ($mock) {
            $mock->shouldReceive('defaultLeagueId')->once()->with(544)->andReturn(26);
            $mock->shouldReceive('pagePayload')->once()->with(544, 26, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'leagues' => [
                    ['league_id' => 26, 'league_title' => 'Testliga', 'league_archive' => 0],
                ],
                'selected_league_id' => 26,
                'selected_league_title' => 'Testliga',
                'items' => [
                    [
                        'matchround_id' => 12,
                        'matchround_title' => 'Runde 1',
                        'matchround_status' => 1,
                        'matchround_startdate' => '1.9.2026 18:00',
                        'matchround_enddate' => '7.9.2026 22:00',
                    ],
                ],
                'form' => [
                    'matchround_id' => '',
                    'matchround_league_id' => 26,
                    'matchround_title' => '',
                    'matchround_status' => 1,
                    'matchround_startdate' => '',
                    'matchround_enddate' => '',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matchrounds')
            ->assertOk()
            ->assertSee('Testliga', false)
            ->assertSee('Runde 1', false)
            ->assertSee('type="datetime-local"', false)
            ->assertSee('name="matchround_startdate"', false)
            ->assertSee('Hinzufügen', false)
            ->assertSee('Max. Spieler / Aufstellung', false)
            ->assertSee('Max. Spieler vom selben Team', false)
            ->assertSee('Min. Spieler als Goalie', false)
            ->assertSee('name="lineup_options_enabled"', false)
            ->assertSee('Liga: Testliga', false);
    }

    public function test_matchrounds_store_redirects_with_game_and_prefill(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $nextForm = [
            'matchround_id' => '',
            'matchround_league_id' => 26,
            'matchround_title' => 'Runde 3',
            'matchround_status' => 1,
            'matchround_startdate' => '2026-09-07T22:00',
            'matchround_enddate' => '2026-09-14T02:00',
        ];

        $this->mock(AdminMatchroundService::class, function ($mock) use ($nextForm) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => true,
                'message' => 'Spielrunde erfolgreich hinzugefügt.',
                'league_id' => 26,
                'next_form' => $nextForm,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/matchrounds', [
                'matchround_league_id' => 26,
                'matchround_title' => 'Runde 2',
                'matchround_status' => 1,
                'matchround_startdate' => '2026-09-01T18:00',
                'matchround_enddate' => '2026-09-07T22:00',
            ])
            ->assertRedirect(route('admin.matchrounds', ['league_id' => 26]))
            ->assertSessionHas('admin_message', 'Spielrunde erfolgreich hinzugefügt.')
            ->assertSessionHas('admin_matchround_prefill', $nextForm);
    }

    public function test_matchrounds_delete_redirects_with_game(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchroundService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(12)->andReturn([
                'ok' => true,
                'message' => 'Spielrunde erfolgreich gelöscht.',
                'league_id' => 26,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/matchrounds/12', ['league_id' => 26])
            ->assertRedirect(route('admin.matchrounds', ['league_id' => 26]))
            ->assertSessionHas('admin_message', 'Spielrunde erfolgreich gelöscht.');
    }
}
