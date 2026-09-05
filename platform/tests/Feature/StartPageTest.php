<?php

namespace Tests\Feature;

use App\Services\StartPageService;
use Tests\TestCase;

class StartPageTest extends TestCase
{
    public function test_start_page_renders(): void
    {
        $this->mock(StartPageService::class, function ($mock) {
            $mock->shouldReceive('payload')->once()->andReturn([
                'stats' => [
                    'users_total' => 10,
                    'users_today' => 2,
                    'lineups' => 5,
                    'score_sum' => 100,
                    'score_avg' => 20.0,
                    'matchrounds_played' => 3,
                ],
                'leagues' => [
                    ['game_id' => 1, 'game_title' => 'Testliga'],
                ],
                'results' => [
                    [
                        'home_team' => 'Alpha',
                        'home_score' => '2',
                        'home_flag' => 'aut',
                        'guest_team' => 'Beta',
                        'guest_score' => '1',
                        'guest_flag' => 'ger',
                        'date' => '01.01.2026',
                    ],
                ],
            ]);
        });

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('SoccerSportsfan', false)
            ->assertSee('Stell dein Team auf.', false)
            ->assertSee('Testliga', false)
            ->assertSee('Alpha', false)
            ->assertSee('action="login"', false)
            ->assertSee('js/start.js?v=2', false);
    }

    public function test_start_api_returns_payload(): void
    {
        $payload = [
            'stats' => [
                'users_total' => 1,
                'users_today' => 0,
                'lineups' => 0,
                'score_sum' => 0,
                'score_avg' => 0.0,
                'matchrounds_played' => 0,
            ],
            'leagues' => [],
            'results' => [],
        ];

        $this->mock(StartPageService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('payload')->once()->andReturn($payload);
        });

        $response = $this->getJson('/api/start');

        $response->assertOk()
            ->assertJson([
                'status' => 200,
                'data' => $payload,
            ]);
    }
}
