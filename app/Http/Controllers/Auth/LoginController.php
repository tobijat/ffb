<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AppHostService;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginService $login,
        private readonly AppHostService $hosts,
    ) {
    }

    public function login(Request $request): JsonResponse
    {
        $result = $this->login->attempt(
            (string) $request->input('user_nickname', ''),
            (string) $request->input('user_password', ''),
            $request,
            (string) $request->input('destination', ''),
        );

        if (! $result['ok']) {
            return response()->json([
                'status' => 500,
                'errors' => $result['errors'],
            ], 422);
        }

        return response()->json([
            'status' => 200,
            'destination' => $result['destination'],
            'user_id' => $result['user_id'],
        ]);
    }

    public function logout(Request $request): RedirectResponse|JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($this->hosts->isPictory($request)) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'status' => 200,
                    'destination' => route('pictory.home', absolute: false),
                ]);
            }

            return redirect()->route('pictory.home');
        }

        $home = (string) config('ffb.home_path', '/');

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'destination' => $home,
            ]);
        }

        // Prefer the named start route over redirect('/') when APP_URL includes a path.
        return redirect()->route('start');
    }
}
