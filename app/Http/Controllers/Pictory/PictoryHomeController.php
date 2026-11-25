<?php

namespace App\Http\Controllers\Pictory;

use App\Http\Controllers\Controller;
use App\Services\FfbUserResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PictoryHomeController extends Controller
{
    public function __construct(
        private readonly FfbUserResolver $users,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();
        $user = $userId > 0 ? $this->users->findPresent($userId) : null;

        return view('pictory.home', [
            'user' => $user ? [
                'user_id' => (int) $user->user_id,
                'user_nickname' => (string) $user->user_nickname,
            ] : null,
        ]);
    }
}
