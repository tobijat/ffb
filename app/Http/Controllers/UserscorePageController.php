<?php

namespace App\Http\Controllers;

use App\Services\UserscoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserscorePageController extends Controller
{
    public function __construct(
        private readonly UserscoreService $userscores,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $result = $this->userscores->pagePayload((int) Auth::id());
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('userscore', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}