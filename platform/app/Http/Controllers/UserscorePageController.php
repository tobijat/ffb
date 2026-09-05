<?php

namespace App\Http\Controllers;

use App\Services\FfbAuth;
use App\Services\UserscoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserscorePageController extends Controller
{
    public function __construct(
        private readonly UserscoreService $userscores,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/userscore',
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
