<?php

namespace Tests\Unit;

use App\Services\FfbAuth;
use App\Services\FfbPassword;
use App\Services\LoginService;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    public function test_resolve_destination_defaults_to_platform(): void
    {
        $service = new LoginService(new FfbPassword, new FfbAuth);

        $this->assertSame(
            (string) config('ffb.home_path'),
            $service->resolveDestination('', false)
        );
    }

    public function test_resolve_destination_forces_admin_path(): void
    {
        $service = new LoginService(new FfbPassword, new FfbAuth);

        $this->assertSame(
            '/administration/start',
            $service->resolveDestination('/ffb/lineup', true)
        );
    }

    public function test_resolve_destination_allows_safe_paths(): void
    {
        $service = new LoginService(new FfbPassword, new FfbAuth);

        $this->assertSame('/ffb/lineup', $service->resolveDestination('/ffb/lineup', false));
    }

    public function test_resolve_destination_rejects_open_redirects(): void
    {
        $service = new LoginService(new FfbPassword, new FfbAuth);
        $home = (string) config('ffb.home_path');

        $this->assertSame($home, $service->resolveDestination('https://evil.example/', false));
        $this->assertSame($home, $service->resolveDestination('//evil.example/', false));
        $this->assertSame($home, $service->resolveDestination('ffb/lineup', false));
    }
}
