<?php

namespace App\Http\Controllers;

use App\Services\LineupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LineupPageController extends Controller
{
    public function __construct(
        private readonly LineupService $lineups,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $result = $this->lineups->pagePayload((int) Auth::id());
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('lineup', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
