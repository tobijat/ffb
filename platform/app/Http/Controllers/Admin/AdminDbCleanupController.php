<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDbCleanupService;
use App\Services\FfbAuth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDbCleanupController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminDbCleanupService $cleanup,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

        return view('admin.db-cleanup', [
            'data' => $this->cleanup->pagePayload($userId),
            'errors' => [],
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
