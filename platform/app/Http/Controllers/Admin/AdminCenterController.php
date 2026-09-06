<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCenterService;
use App\Services\FfbAuth;
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
            'legacyBase' => '/',
        ]);
    }
}
