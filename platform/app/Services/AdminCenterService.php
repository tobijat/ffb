<?php

namespace App\Services;

use App\Models\WebUser;

class AdminCenterService
{
    public function __construct(
        private readonly FfbAdminAccess $admins,
    ) {
    }

    /**
     * @return array{user: array<string, mixed>, navigation: list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>}
     */
    public function pagePayload(int $userId): array
    {
        $webUser = WebUser::query()->with('details')->find($userId);
        $photo = (string) ($webUser?->details?->user_details_photo ?: 'profile_na.png');

        return [
            'user' => [
                'user_id' => $userId,
                'user_nickname' => (string) ($webUser?->user_nickname ?? ''),
                'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                'is_ffb_admin' => $this->admins->isAdmin($userId),
            ],
            'navigation' => $this->navigation(),
        ];
    }

    /**
     * @return list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>
     */
    public function navigation(): array
    {
        return [
            [
                'symbol' => 'nav_news.png',
                'name' => 'News',
                'link' => '/platform/admin/news',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
        ];
    }
}
