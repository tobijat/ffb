<?php

namespace App\Http\Controllers;

use App\Services\LineupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LineupPageController extends Controller
{
    public function __construct(
        private readonly LineupService $lineups,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => 'lineup',
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
