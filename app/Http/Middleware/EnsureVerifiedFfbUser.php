<?php

namespace App\Http\Middleware;

use App\Services\FfbUserResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Require an active WebUser with email_verified_at set.
 *
 * Pair with auth + ffb.active. Unverified users keep their session and are
 * sent back to start; missing/inactive users are cleared by ffb.active first.
 */
class EnsureVerifiedFfbUser
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

        if ($this->users->findVerified($userId)) {
            return $next($request);
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'status' => 403,
                'error' => 'Bestätige deine E-Mail Adresse um deinen Account zu aktivieren.',
            ], 403);
        }

        return redirect()
            ->route('start')
            ->with('show_activation_modal', true);
    }
}
