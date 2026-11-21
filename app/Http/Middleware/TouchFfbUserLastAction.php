<?php

namespace App\Http\Middleware;

use App\Models\WebUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Bump web_user.user_date_laction on each logged-in web request (page load / form post).
 */
class TouchFfbUserLastAction
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = (int) Auth::id();
        if ($userId > 0 && Schema::hasTable('web_user')) {
            WebUser::query()
                ->where('user_id', $userId)
                ->update([
                    'user_date_laction' => now()->format('Y-m-d H:i:s'),
                ]);
        }

        return $next($request);
    }
}
