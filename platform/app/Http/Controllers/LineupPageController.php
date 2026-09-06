<?php

namespace App\Http\Controllers;

use App\Services\FfbAuth;
use App\Services\LineupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LineupPageController extends Controller
{
    public function __construct(
        private readonly LineupService $lineups,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/lineup',
            ]);
        }

        $result = $this->lineups->pagePayload($userId);
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('lineup', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
