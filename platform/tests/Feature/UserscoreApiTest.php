<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use App\Services\UserscoreService;
use Tests\TestCase;

class UserscoreApiTest extends TestCase
{
    private function actingAsFfbUser(int $userId = 544): void
    {
        $user = new WebUser;
        $user->user_id = $userId;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';

        $this->mock(FfbUserResolver::class, function ($mock) use ($user, $userId) {
            $mock->shouldReceive('findActive')->with($userId)->andReturn($user);
        });
    }

    public function test_userscore_requires_auth(): void
    {
        $this->getJson('/api/userscore')->assertStatus(401);
        $this->getJson('/api/userscore/matchrounds')->assertStatus(401);
        $this->getJson('/api/userscore/rounds/1')->assertStatus(401);
    }

    public function test_overall_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'selected_game_id' => 26,
            'matchround_id' => 0,
            'rank_mode' => 'wc',
            'display_mode' => 'wc',
            'entries' => [
                [
                    'user_id' => 544,
                    'user_nickname' => 'tester',
                    'user_favourite_team_nationality' => 'aut',
                    'user_score' => 100,
                    'user_wc_points' => 12,
                    'participations' => 3,
                    'matchround_wins' => 1,
                    'user_rank' => 1,
                ],
            ],
            'num_results' => 1,
        ];

        $this->mock(UserscoreService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('overall')->once()->with(544, '', 'desc')->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/userscore')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.entries.0.user_nickname', 'tester');
    }

    public function test_round_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(UserscoreService::class, function ($mock) {
            $mock->shouldReceive('forRound')->once()->with(544, 280, 'n', 'asc')->andReturn([
                'ok' => true,
                'data' => [
                    'selected_game_id' => 26,
                    'matchround_id' => 280,
                    'rank_mode' => 'points',
                    'display_mode' => 'points',
                    'entries' => [],
                    'num_results' => 0,
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/userscore/rounds/280?sort=n&dir=asc')
            ->assertOk()
            ->assertJsonPath('data.matchround_id', 280);
    }

    public function test_matchrounds_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(UserscoreService::class, function ($mock) {
            $mock->shouldReceive('matchrounds')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'selected_game_id' => 26,
                    'matchrounds' => [
                        ['matchround_id' => 1, 'matchround_title' => 'Runde 1', 'matches' => []],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/userscore/matchrounds')
            ->assertOk()
            ->assertJsonPath('data.matchrounds.0.matchround_title', 'Runde 1');
    }

    public function test_userscore_page_redirects_guests(): void
    {
        $this->get('/userscore')
            ->assertRedirect(route('start', ['destination' => '/platform/userscore']));
    }

    public function test_userscore_page_renders_for_logged_in_user(): void
    {
        $this->mock(UserscoreService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'update_profile_nag' => false,
                    ],
                    'selected_game_id' => 26,
                    'navigation' => [
                        ['symbol' => 'nav_results.png', 'name' => 'Rangliste', 'link' => '/platform/userscore', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/userscore')
            ->assertOk()
            ->assertSee('Rangliste', false)
            ->assertSee('tester', false)
            ->assertSee('js/userscore.js', false);
    }
}
