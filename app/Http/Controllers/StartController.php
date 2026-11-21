<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\StartPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StartController extends Controller
{
    public function __construct(
        private readonly StartPageService $startPage,
        private readonly DashboardService $dashboard,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();

        if ($userId > 0) {
            $page = max(1, (int) $request->query('news_page', 1));
            $archive = (bool) $request->boolean('archive');
            $data = $this->dashboard->payload($userId, $page, $archive);

            return view('dashboard', [
                'data' => $data,
                'legacyBase' => '/',
            ]);
        }

        $data = $this->startPage->payload();

        return view('start', [
            'stats' => $data['stats'],
            'leagues' => $data['leagues'],
            'results' => $data['results'],
            'legacyBase' => '/',
            'destination' => (string) $request->query('destination', ''),
            'accountMessage' => $request->session()->pull('account_message'),
            'passwordReset' => null,
        ]);
    }

    public function data(): JsonResponse
    {
        return response()->json([
            'status' => 200,
            'data' => $this->startPage->payload(),
        ]);
    }
}
