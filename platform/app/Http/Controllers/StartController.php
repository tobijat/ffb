<?php

namespace App\Http\Controllers;

use App\Services\LegacyPhpSession;
use App\Services\StartPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartController extends Controller
{
    public function __construct(
        private readonly StartPageService $startPage,
        private readonly LegacyPhpSession $legacySession,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        if ($this->legacySession->userId($request) > 0) {
            return redirect('/ffb');
        }

        $data = $this->startPage->payload();

        return view('start', [
            'stats' => $data['stats'],
            'leagues' => $data['leagues'],
            'results' => $data['results'],
            'legacyBase' => '/',
            'destination' => (string) $request->query('destination', ''),
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
