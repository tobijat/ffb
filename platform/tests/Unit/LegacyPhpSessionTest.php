<?php

namespace Tests\Unit;

use App\Services\LegacyPhpSession;
use Illuminate\Http\Request;
use Tests\TestCase;

class LegacyPhpSessionTest extends TestCase
{
    public function test_returns_zero_without_cookie(): void
    {
        $request = Request::create('/api/lineup', 'GET');

        $this->assertSame(0, (new LegacyPhpSession)->userId($request));
    }

    public function test_reads_user_id_from_legacy_session_cookie(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        session_id('');
        $started = session_start();
        $this->assertTrue($started);

        $sessionId = session_id();
        $_SESSION['user_id'] = 544;
        session_write_close();

        $request = Request::create('/api/lineup', 'GET', [], [
            session_name() => $sessionId,
        ]);

        $this->assertSame(544, (new LegacyPhpSession)->userId($request));
    }

    public function test_rejects_unknown_session_id(): void
    {
        $request = Request::create('/api/lineup', 'GET', [], [
            session_name() => str_repeat('a', 32),
        ]);

        $this->assertSame(0, (new LegacyPhpSession)->userId($request));
    }
}
