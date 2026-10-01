<?php

namespace App\Http\Controllers;

use App\Models\WebUser;
use App\Services\DashboardService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use App\Services\HelpService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferencePageController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $user = null;

        if ($userId > 0) {
            $webUser = WebUser::query()->with('details')->find($userId);
            if ($webUser) {
                $photo = (string) ($webUser->details?->user_details_photo ?: 'profile_na.png');
                $user = [
                    'user_id' => (int) $webUser->user_id,
                    'user_nickname' => (string) $webUser->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $webUser->user_id),
                ];
            }
        }

        return view('reference', [
            'data' => [
                'user' => $user,
                'navigation' => $user
                    ? app(DashboardService::class)->navigation((int) $user['user_id'])
                    : HelpService::guestNavigation(),
            ],
            'legacyBase' => '/',
        ]);
    }
}
