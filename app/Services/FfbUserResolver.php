<?php

namespace App\Services;

use App\Models\WebUser;

class FfbUserResolver
{
    /**
     * User allowed to keep a web session (pending or active).
     * Pending = registered, email not confirmed yet.
     */
    public function findPresent(int $userId): ?WebUser
    {
        if ($userId <= 0) {
            return null;
        }

        $user = WebUser::query()->find($userId);
        if (! $user || ! in_array($user->user_status, ['active', 'pending'], true)) {
            return null;
        }

        return $user;
    }

    /**
     * Sessionable account (pending or active). Alias of findPresent.
     */
    public function findActive(int $userId): ?WebUser
    {
        return $this->findPresent($userId);
    }

    /**
     * Fully confirmed account: status active and email_verified_at set.
     */
    public function findVerified(int $userId): ?WebUser
    {
        $user = $this->findPresent($userId);
        if (! $user || $user->user_status !== 'active' || ! $user->hasVerifiedEmail()) {
            return null;
        }

        return $user;
    }
}
