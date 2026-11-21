<?php

namespace App\Http\Controllers;

use App\Services\MyteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyteamPageController extends Controller
{
    public function __construct(
        private readonly MyteamService $myteam,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $result = $this->myteam->pagePayload((int) Auth::id());
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('myteam', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
