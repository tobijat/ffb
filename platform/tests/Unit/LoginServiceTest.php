<?php

namespace Tests\Unit;

use App\Services\FfbAuth;
use App\Services\FfbPassword;
use App\Services\LegacyPhpSession;
use App\Services\LoginService;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    private function service(): LoginService
    {
        $legacy = new LegacyPhpSession;

        return new LoginService(new FfbPassword, new FfbAuth($legacy), $legacy);
    }

    public function test_resolve_destination_defaults_to_platform(): void
    {
        $this->assertSame(
            (string) config('ffb.home_path'),
            $this->service()->resolveDestination('', false)
        );
    }

    public function test_resolve_destination_forces_admin_path(): void
    {
        $this->assertSame(
            '/administration/start',
            $this->service()->resolveDestination('/ffb/lineup', true)
        );
    }

    public function test_resolve_destination_allows_safe_paths(): void
    {
        $this->assertSame('/ffb/lineup', $this->service()->resolveDestination('/ffb/lineup', false));
    }

    public function test_resolve_destination_rejects_open_redirects(): void
    {
        $home = (string) config('ffb.home_path');
        $service = $this->service();

        $this->assertSame($home, $service->resolveDestination('https://evil.example/', false));
        $this->assertSame($home, $service->resolveDestination('//evil.example/', false));
        $this->assertSame($home, $service->resolveDestination('ffb/lineup', false));
    }
}
