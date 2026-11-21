<?php

namespace App\Http\Middleware;

use App\Services\FfbAdminAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Require an FFB admin. Pair with auth + ffb.active on admin route groups.
 */
class EnsureFfbAdmin
{
    public function __construct(
        private readonly FfbAdminAccess $admins,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $userId = (int) Auth::id();

        if ($userId <= 0) {
            $path = trim($request->path(), '/');
            $destination = $path === '' ? 'admin' : $path;

            return redirect()->route('start', [
                'destination' => $destination,
            ]);
        }

        if (! $this->admins->isAdmin($userId)) {
            return redirect()->route('start');
        }

        return $next($request);
    }
}
