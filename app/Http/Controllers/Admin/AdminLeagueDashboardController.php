<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminLeagueDashboardService;
use App\Services\FfbAuth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLeagueDashboardController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminLeagueDashboardService $dashboard,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

        return view('admin.league-dashboard', [
            'data' => $this->dashboard->pagePayload($userId),
            'errors' => session('admin_errors') ?: [],
            'legacyBase' => '/',
        ]);
    }
}
