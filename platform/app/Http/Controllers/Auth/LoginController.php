<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FfbAuth;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginService $login,
        private readonly FfbAuth $auth,
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
        $this->auth->logout($request);

        $home = (string) config('ffb.home_path', '/platform/public/');

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'destination' => $home,
            ]);
        }

        // Use the named route ("/") — redirect('/platform/…') would be prefixed
        // again with APP_URL and become /platform/public/platform/….
        return redirect()->route('start');
    }
}
