<?php

namespace App\Services;

use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Platform identity: Laravel guard first, legacy session key as fallback.
 *
 * Call sites still use this helper; a later PR can replace them with Auth::id() /
 * $request->user() and remove the ffb_user_id dual-write.
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
        $authId = (int) Auth::id();
        if ($authId > 0) {
            return $authId;
        }

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
        if ($userId <= 0) {
            return;
        }

        $user = Schema::hasTable('web_user')
            ? WebUser::query()->find($userId)
            : null;

        if ($user instanceof WebUser) {
            Auth::login($user);
        } else {
            // No row (or schema) yet: keep legacy session identity only.
            $request->session()->regenerate();
        }

        $request->session()->put(self::SESSION_USER_ID, $userId);
    }

    public function logout(Request $request): void
    {
        $this->legacySession->forget();
        Auth::logout();
        $request->session()->forget(self::SESSION_USER_ID);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
