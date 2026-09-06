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
                'navigation' => [],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin')
            ->assertOk()
            ->assertSee('Admin Center', false)
            ->assertSee('css/admin.css', false)
            ->assertSee('Soccer Sportsfan', false)
            ->assertSee('href="/platform/"', false)
            ->assertDontSee('href="/platform/admin"', false)
            ->assertDontSee('Regeln', false);
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
