<?php

namespace App\Http\Controllers;

use App\Services\HelpService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HelpPageController extends Controller
{
    public function __construct(
        private readonly HelpService $help,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();
        $result = $this->help->pagePayload($userId);

        return view('help', [
            'data' => $result['data'],
            'legacyBase' => '/',
            'destination' => (string) $request->query('destination', ''),
            'accountMessage' => $request->session()->pull('account_message'),
            'passwordReset' => null,
        ]);
    }
}
