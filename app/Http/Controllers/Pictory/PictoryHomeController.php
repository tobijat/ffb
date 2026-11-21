<?php

namespace App\Http\Controllers\Pictory;

use App\Http\Controllers\Controller;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PictoryHomeController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly FfbUserResolver $users,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $user = $userId > 0 ? $this->users->findActive($userId) : null;

        return view('pictory.home', [
            'user' => $user ? [
                'user_id' => (int) $user->user_id,
                'user_nickname' => (string) $user->user_nickname,
            ] : null,
        ]);
    }
}
