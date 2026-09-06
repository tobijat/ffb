<?php

namespace App\Http\Controllers;

use App\Services\BestteamService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BestteamPageController extends Controller
{
    public function __construct(
        private readonly BestteamService $bestteam,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/bestteam',
            ]);
        }

        $result = $this->bestteam->pagePayload($userId);
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('bestteam', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
