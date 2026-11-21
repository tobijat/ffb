<?php

namespace App\Services;

use App\Models\WebAdmin;

class FfbAdminAccess
{
    public const SECTION = 'ffb';

    /** Minimum admin_level required to view other users' lineups before the deadline. */
    public const LINEUP_PRIVACY_BYPASS_LEVEL = 200;

    public function isAdmin(int $userId, int $minLevel = 0): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $query = WebAdmin::query()
            ->where('admin_user_id', $userId)
            ->where('admin_section', self::SECTION)
            ->where('admin_status', 'active');

        if ($minLevel > 0) {
            $query->where('admin_level', '>=', $minLevel);
        }

        return $query->exists();
    }

    public function canBypassLineupPrivacy(int $userId): bool
    {
        return $this->isAdmin($userId, self::LINEUP_PRIVACY_BYPASS_LEVEL);
    }
}
