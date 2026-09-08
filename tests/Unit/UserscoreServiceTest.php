<?php

namespace Tests\Unit;

use App\Services\UserscoreService;
use ReflectionMethod;
use Tests\TestCase;

class UserscoreServiceTest extends TestCase
{
    public function test_rank_overall_assigns_tied_ranks(): void
    {
        $service = new UserscoreService;
        $method = new ReflectionMethod(UserscoreService::class, 'rankOverall');
        $method->setAccessible(true);

        $entries = [
            ['user_nickname' => 'b', 'user_score' => 10, 'user_wc_points' => 5],
            ['user_nickname' => 'a', 'user_score' => 10, 'user_wc_points' => 5],
            ['user_nickname' => 'c', 'user_score' => 8, 'user_wc_points' => 4],
        ];

        $ranked = $method->invoke($service, $entries, 'points');

        $this->assertSame(1, $ranked[0]['user_rank']);
        $this->assertSame(1, $ranked[1]['user_rank']);
        $this->assertSame(3, $ranked[2]['user_rank']);
        $this->assertSame('a', $ranked[0]['user_nickname']);
        $this->assertSame('b', $ranked[1]['user_nickname']);
    }

    public function test_rank_round_orders_by_points(): void
    {
        $service = new UserscoreService;
        $method = new ReflectionMethod(UserscoreService::class, 'rankRound');
        $method->setAccessible(true);

        $entries = [
            ['user_nickname' => 'x', 'user_score' => 2],
            ['user_nickname' => 'y', 'user_score' => 9],
        ];

        $ranked = $method->invoke($service, $entries);

        $this->assertSame('y', $ranked[0]['user_nickname']);
        $this->assertSame(1, $ranked[0]['user_rank']);
        $this->assertSame(2, $ranked[1]['user_rank']);
    }
}
