<?php

namespace App\Http\Controllers;

use App\Services\MyteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MyteamPageController extends Controller
{
    public function __construct(
        private readonly MyteamService $myteam,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => 'myteam',
            ]);
        }

        $result = $this->myteam->pagePayload($userId);
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('myteam', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
