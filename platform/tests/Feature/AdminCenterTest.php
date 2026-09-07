<?php

namespace Tests\Feature;

use App\Services\AdminCenterService;
use App\Services\DashboardService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminCenterTest extends TestCase
{
    public function test_admin_center_redirects_guests(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('start', ['destination' => '/platform/admin']));
    }

    public function test_admin_center_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin')
            ->assertRedirect(route('start'));
    }

    public function test_admin_center_renders_for_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminCenterService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
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
                        'link' => '/platform/admin/matchrounds',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                    [
                        'symbol' => 'nav_news.png',
                        'name' => 'News',
                        'link' => '/platform/admin/news',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game_id' => 26,
                'selected_game' => [
                    'game_id' => 26,
                    'game_title' => 'Testliga',
                    'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
                ],
                'games' => [
                    [
                        'game_id' => 26,
                        'game_title' => 'Testliga',
                        'game_symbol' => 'symbol_game_na.png',
                        'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
                        'game_status' => 1,
                        'game_archive' => 0,
                        'game_visible' => 1,
                        'game_countdown' => 0,
                        'flags' => [
                            ['label' => 'aktiv', 'tone' => 'ok'],
                            ['label' => 'aktuell', 'tone' => 'ok'],
                            ['label' => 'sichtbar', 'tone' => 'ok'],
                            ['label' => 'Countdown aus', 'tone' => 'muted'],
                        ],
                    ],
                    [
                        'game_id' => 10,
                        'game_title' => 'Alte Liga',
                        'game_symbol' => 'symbol_game_na.png',
                        'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
                        'game_status' => 0,
                        'game_archive' => 1,
                        'game_visible' => 0,
                        'game_countdown' => 0,
                        'flags' => [
                            ['label' => 'inaktiv', 'tone' => 'off'],
                            ['label' => 'archiviert', 'tone' => 'warn'],
                            ['label' => 'unsichtbar', 'tone' => 'off'],
                            ['label' => 'Countdown aus', 'tone' => 'muted'],
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin')
            ->assertOk()
            ->assertSee('Admin Center', false)
            ->assertSee('css/admin.css', false)
            ->assertSee('Spielrunden', false)
            ->assertSee('News', false)
            ->assertSee('Testliga', false)
            ->assertSee('Alte Liga', false)
            ->assertSee('aktiv', false)
            ->assertSee('archiviert', false)
            ->assertSee('unsichtbar', false)
            ->assertSee('href="/platform/admin/matchrounds"', false)
            ->assertSee('href="/platform/admin/news"', false)
            ->assertSee('class="brand" href="/platform/admin"', false)
            ->assertSee('AdminCenter', false)
            ->assertSee('Testliga', false)
            ->assertSee('symbol_game_na.png', false)
            ->assertSee('Soccer Sportsfan', false)
            ->assertSee('href="/platform/"', false);
    }

    public function test_admin_center_selects_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminCenterService::class, function ($mock) {
            $mock->shouldReceive('selectGame')->once()->with(26)->andReturn([
                'ok' => true,
                'message' => 'Liga „Testliga“ ausgewählt.',
                'game_id' => 26,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/games/26/select')
            ->assertRedirect(route('admin.center'))
            ->assertSessionHas('admin_message', 'Liga „Testliga“ ausgewählt.');
    }

    public function test_user_card_shows_admin_center_link_when_flag_set(): void
    {
        $this->mock(DashboardService::class, function ($mock) {
            $mock->shouldReceive('payload')->once()->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'user_name' => 'Admin',
                    'user_photo' => 'profile_na.png',
                    'user_avatar' => 'avatar_na.png',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'update_profile_nag' => false,
                    'is_ffb_admin' => true,
                ],
                'selected_game_id' => 26,
                'games' => [],
                'archive' => false,
                'news' => ['items' => [], 'page' => 1, 'pages' => 0],
                'polls' => ['text' => null, 'select' => null],
                'navigation' => [],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/')
            ->assertOk()
            ->assertSee('Admin Center', false)
            ->assertSee('href="/platform/admin"', false);
    }

    public function test_user_card_hides_admin_center_for_normal_users(): void
    {
        $this->mock(DashboardService::class, function ($mock) {
            $mock->shouldReceive('payload')->once()->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'player',
                    'user_name' => 'Player',
                    'user_photo' => 'profile_na.png',
                    'user_avatar' => 'avatar_na.png',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'update_profile_nag' => false,
                    'is_ffb_admin' => false,
                ],
                'selected_game_id' => 26,
                'games' => [],
                'archive' => false,
                'news' => ['items' => [], 'page' => 1, 'pages' => 0],
                'polls' => ['text' => null, 'select' => null],
                'navigation' => [],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/')
            ->assertOk()
            ->assertDontSee('Admin Center', false);
    }
}
