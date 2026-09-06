<?php

namespace App\Http\Controllers;

use App\Services\FfbAuth;
use App\Services\MyteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyteamPageController extends Controller
{
    public function __construct(
        private readonly MyteamService $myteam,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/myteam',
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
