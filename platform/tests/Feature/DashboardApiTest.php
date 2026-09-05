<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\DashboardService;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    public function test_dashboard_requires_auth(): void
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(401);
    }

    public function test_dashboard_returns_payload(): void
    {
        $user = new WebUser;
        $user->user_id = 544;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';

        $payload = [
            'user' => ['user_id' => 544, 'user_nickname' => 'tester'],
            'selected_game_id' => 26,
            'games' => [],
            'archive' => false,
            'news' => ['items' => [], 'page' => 1, 'pages' => 0],
            'polls' => ['text' => null, 'select' => null],
            'navigation' => [],
        ];

        $this->mock(FfbUserResolver::class, function ($mock) use ($user) {
            $mock->shouldReceive('findActive')->with(544)->andReturn($user);
        });

        $this->mock(DashboardService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('payload')->once()->with(544, 1, false)->andReturn($payload);
        });

        $response = $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/dashboard');

        $response->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user.user_id', 544);
    }

    public function test_logged_in_start_renders_dashboard(): void
    {
        $this->mock(DashboardService::class, function ($mock) {
            $mock->shouldReceive('payload')->once()->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'tester',
                    'user_name' => 'Test User',
                    'user_photo' => 'profile_na.png',
                    'user_avatar' => 'avatar_na.png',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'update_profile_nag' => false,
                ],
                'selected_game_id' => 26,
                'games' => [
                    [
                        'game_id' => 26,
                        'game_title' => 'Testliga',
                        'game_symbol' => 'x.png',
                        'game_archive' => 0,
                        'game_visible' => 1,
                        'game_countdown' => 1,
                        'game_status' => 1,
                        'symbol_url' => '/images/ffb/symbols/x.png',
                    ],
                ],
                'archive' => false,
                'news' => [
                    'items' => [
                        [
                            'news_id' => 1,
                            'news_title' => 'Hallo News',
                            'news_date' => '01.01.2026 12:00',
                            'news_text' => 'Text',
                            'news_symbol' => null,
                        ],
                    ],
                    'page' => 1,
                    'pages' => 1,
                ],
                'polls' => ['text' => null, 'select' => null],
                'navigation' => [
                    ['symbol' => 'nav_start.png', 'name' => 'Start', 'link' => '/platform/', 'style' => 'big'],
                ],
            ]);
        });

        $response = $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/');

        $response->assertOk()
            ->assertSee('Hallo', false)
            ->assertSee('tester', false)
            ->assertSee('Testliga', false)
            ->assertSee('Hallo News', false)
            ->assertSee('Start', false)
            ->assertSee('href="logout"', false);
    }
}
