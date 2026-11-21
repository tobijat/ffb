<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCenterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminCenterController extends Controller
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();

        return view('admin.center', [
            'data' => $this->adminCenter->pagePayload($userId),
            'answer' => session('admin_message'),
            'errors' => session('admin_errors') ?: [],
            'legacyBase' => '/',
        ]);
    }

    public function selectLeague(Request $request, int $league): RedirectResponse
    {
        $result = $this->adminCenter->selectLeague($league);

        if ($result['ok']) {
            return redirect()->route('admin.leagueDashboard');
        }

        return redirect()
            ->route('admin.center')
            ->with('admin_errors', $result['errors'] ?? ['Auswahl fehlgeschlagen.']);
    }
}
