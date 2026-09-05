<?php

namespace Tests\Feature;

use App\Services\LegacyPhpSession;
use App\Services\StartPageService;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->mock(LegacyPhpSession::class, function ($mock) {
            $mock->shouldReceive('userId')->andReturn(0);
        });

        $this->mock(StartPageService::class, function ($mock) {
            $mock->shouldReceive('payload')->andReturn([
                'stats' => [
                    'users_total' => 0,
                    'users_today' => 0,
                    'lineups' => 0,
                    'score_sum' => 0,
                    'score_avg' => 0.0,
                    'matchrounds_played' => 0,
                ],
                'leagues' => [],
                'results' => [],
            ]);
        });

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
