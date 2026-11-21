<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminLeagueDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminLeagueDashboardController extends Controller
{
    public function __construct(
        private readonly AdminLeagueDashboardService $dashboard,
    ) {}

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();

        return view('admin.league-dashboard', [
            'data' => $this->dashboard->pagePayload($userId),
            'errors' => session('admin_errors') ?: [],
            'legacyBase' => '/',
        ]);
    }
}
