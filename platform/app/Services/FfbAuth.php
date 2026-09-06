<?php

namespace App\Services;

use Illuminate\Http\Request;

/**
 * Platform identity stored in the Laravel session (not PHPSESSID).
 */
class FfbAuth
{
    public const SESSION_USER_ID = 'ffb_user_id';

    public function __construct(
        private readonly LegacyPhpSession $legacySession,
    ) {
    }

    public function userId(?Request $request = null): int
    {
        if ($request !== null && $request->hasSession()) {
            return (int) $request->session()->get(self::SESSION_USER_ID, 0);
        }

        if (session()->isStarted() || app()->bound('session')) {
            return (int) session(self::SESSION_USER_ID, 0);
        }

        return 0;
    }

    public function login(int $userId, Request $request): void
    {
        $request->session()->regenerate();
        $request->session()->put(self::SESSION_USER_ID, $userId);
    }

    public function logout(Request $request): void
    {
        $this->legacySession->forget();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
