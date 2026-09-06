<?php

namespace App\Http\Middleware;

use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFfbAdmin
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly FfbAdminAccess $admins,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $userId = $this->auth->userId($request);

        if ($userId <= 0) {
            $path = trim($request->path(), '/');
            $destination = $path === ''
                ? '/platform/admin'
                : '/platform/'.$path;

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
