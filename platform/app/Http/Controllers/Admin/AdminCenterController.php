<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCenterService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCenterController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

        return view('admin.center', [
            'data' => $this->adminCenter->pagePayload($userId),
            'answer' => session('admin_message'),
            'errors' => session('admin_errors') ?: [],
            'legacyBase' => '/',
        ]);
    }

    public function selectGame(Request $request, int $game): RedirectResponse
    {
        $result = $this->adminCenter->selectGame($game);

        if ($result['ok']) {
            return redirect()
                ->route('admin.center')
                ->with('admin_message', $result['message']);
        }

        return redirect()
            ->route('admin.center')
            ->with('admin_errors', $result['errors'] ?? ['Auswahl fehlgeschlagen.']);
    }
}
