<?php

namespace App\Http\Controllers;

use App\Services\FfbAuth;
use App\Services\HelpService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpPageController extends Controller
{
    public function __construct(
        private readonly HelpService $help,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $result = $this->help->pagePayload($userId);

        return view('help', [
            'data' => $result['data'],
            'legacyBase' => '/',
        ]);
    }
}
