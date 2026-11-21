<?php

namespace App\Http\Controllers;

use App\Services\BestteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BestteamPageController extends Controller
{
    public function __construct(
        private readonly BestteamService $bestteam,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $result = $this->bestteam->pagePayload((int) Auth::id());
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('bestteam', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
