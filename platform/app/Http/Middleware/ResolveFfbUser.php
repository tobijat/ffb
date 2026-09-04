<?php

namespace App\Http\Middleware;

use App\Services\FfbUserResolver;
use App\Services\LegacyPhpSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolve the FFB user for JSON APIs.
 *
 * Auth order:
 * 1. Legacy PHP session cookie (PHPSESSID → $_SESSION['user_id']) — production path
 * 2. Optional bridge: X-FFB-User-Id / user_id / userteam_user_id when enabled
 *    (local/testing by default; see config/ffb.php)
 */
class ResolveFfbUser
{
    public function __construct(
        private readonly LegacyPhpSession $legacySession,
        private readonly FfbUserResolver $users,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $userId = $this->legacySession->userId($request);

        if ($userId <= 0 && config('ffb.allow_user_id_header')) {
            $bridge = $request->header('X-FFB-User-Id')
                ?? $request->input('user_id')
                ?? $request->input('userteam_user_id');
            $userId = is_numeric($bridge) ? (int) $bridge : 0;
        }

        if ($userId <= 0) {
            return response()->json([
                'status' => 401,
                'error' => 'Authentication required (login session)',
            ], 401);
        }

        $user = $this->users->findActive($userId);
        if (! $user) {
            return response()->json([
                'status' => 401,
                'error' => 'Unknown or inactive user',
            ], 401);
        }

        $request->attributes->set('ffb_user_id', (int) $user->user_id);
        $request->attributes->set('ffb_user', $user);

        return $next($request);
    }
}
