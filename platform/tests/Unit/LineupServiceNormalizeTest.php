<?php

namespace Tests\Unit;

use App\Services\LineupService;
use Tests\TestCase;

class LineupServiceNormalizeTest extends TestCase
{
    public function test_normalizes_comma_string_and_arrays(): void
    {
        $service = $this->app->make(LineupService::class);

        $this->assertSame(
            [1, 2, 3],
            $service->normalizePlayerteamIds(['1,2,3'])
        );

        $this->assertSame(
            [10, 20, 30],
            $service->normalizePlayerteamIds([10, '20', 30])
        );
    }
}
