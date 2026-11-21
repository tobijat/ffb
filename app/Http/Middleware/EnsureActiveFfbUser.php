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
 * Use after Laravel's auth middleware on JSON API routes.
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
        if ($userId <= 0 || ! $this->users->findActive($userId)) {
            return response()->json([
                'status' => 401,
                'error' => 'Unknown or inactive user',
            ], 401);
        }

        return $next($request);
    }
}
