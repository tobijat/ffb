<?php

namespace App\Http\Controllers;

use App\Services\BestteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BestteamPageController extends Controller
{
    public function __construct(
        private readonly BestteamService $bestteam,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => 'bestteam',
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
