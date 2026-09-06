<?php

namespace App\Services;

use App\Models\WebAdmin;

class FfbAdminAccess
{
    public const SECTION = 'ffb';

    public function isAdmin(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        return WebAdmin::query()
            ->where('admin_user_id', $userId)
            ->where('admin_section', self::SECTION)
            ->exists();
    }
}
