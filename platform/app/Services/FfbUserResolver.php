<?php

namespace App\Services;

use App\Models\WebUser;

class FfbUserResolver
{
    public function findActive(int $userId): ?WebUser
    {
        if ($userId <= 0) {
            return null;
        }

        $user = WebUser::query()->find($userId);
        if (! $user || $user->user_status !== 'active') {
            return null;
        }

        return $user;
    }
}
