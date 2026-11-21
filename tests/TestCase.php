<?php

namespace Tests;

use App\Models\WebUser;
use App\Services\FfbUserResolver;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Authenticate as a WebUser with the given id without needing a DB row.
     */
    protected function actingAsFfbUser(int $userId = 544): static
    {
        $user = new WebUser;
        $user->user_id = $userId;
        $user->user_nickname = 'user'.$userId;
        $user->user_status = 'active';
        $user->exists = true;

        $this->mock(FfbUserResolver::class, function ($mock) use ($user, $userId) {
            $mock->shouldReceive('findActive')->byDefault()->with($userId)->andReturn($user);
        });

        return $this->actingAs($user);
    }
}
