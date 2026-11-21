<?php

namespace App\Http\Controllers;

use App\Services\UserscoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserscorePageController extends Controller
{
    public function __construct(
        private readonly UserscoreService $userscores,
    ) {
    }

    public function show(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => 'userscore',
            ]);
        }

        $result = $this->userscores->pagePayload($userId);
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('userscore', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
