<?php

namespace App\Http\Middleware;

use App\Services\FfbUserResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensure the authenticated WebUser is still active (re-checked from DB).
 *
 * When unauthenticated, passes through (pair with auth middleware where login is required).
 * Inactive sessions are cleared; API/JSON gets 401, web gets redirect to start.
 */
class EnsureActiveFfbUser
{
    public function __construct(
        private readonly FfbUserResolver $users,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return $next($request);
        }

        if ($this->users->findActive($userId)) {
            return $next($request);
        }

        Auth::logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'status' => 401,
                'error' => 'Unknown or inactive user',
            ], 401);
        }

        return redirect()->route('start');
    }
}
