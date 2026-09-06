<?php

namespace App\Services;

use App\Models\UserAward;
use App\Models\UserAwardFinished;
use App\Models\WebUser;

class AwardsPopupService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function forUser(int $userId): array
    {
        if ($userId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'user_id is required'];
        }

        if (! WebUser::query()->where('user_id', $userId)->exists()) {
            return ['ok' => false, 'status' => 404, 'error' => 'User not found'];
        }

        $finishedIds = UserAwardFinished::query()
            ->where('user_award_finished_user_id', $userId)
            ->pluck('user_award_finished_award_defines_id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $finishedSet = array_fill_keys($finishedIds, true);

        $awards = UserAward::query()
            ->with(['defines' => fn ($q) => $q->orderBy('user_award_defines_rank')])
            ->orderByDesc('user_award_sortflag')
            ->orderBy('user_award_id')
            ->get();

        $groups = [];
        foreach ($awards as $award) {
            $ranks = [];
            foreach ($award->defines as $define) {
                $finished = isset($finishedSet[(int) $define->user_award_defines_id]);
                $image = (string) $define->user_award_defines_image;
                $ranks[] = [
                    'id' => (int) $define->user_award_defines_id,
                    'name' => (string) $define->user_award_defines_rank_name,
                    'description' => (string) $define->user_award_defines_description,
                    'rank' => (int) $define->user_award_defines_rank,
                    'finished' => $finished,
                    'image' => $image,
                    'image_url' => '/images/ffb/'.$this->displayImage($image, $finished),
                ];
            }

            $groups[] = [
                'id' => (int) $award->user_award_id,
                'name' => (string) $award->user_award_name,
                'description' => (string) $award->user_award_description,
                'image_url' => '/images/ffb/'.ltrim((string) $award->user_award_image, '/'),
                'ranks' => $ranks,
            ];
        }

        return [
            'ok' => true,
            'data' => [
                'user_id' => $userId,
                'groups' => $groups,
            ],
        ];
    }

    private function displayImage(string $image, bool $finished): string
    {
        $image = ltrim($image, '/');
        if ($finished || $image === '') {
            return $image;
        }

        $dot = strrpos($image, '.');
        if ($dot === false) {
            return $image.'_disabled';
        }

        return substr($image, 0, $dot).'_disabled'.substr($image, $dot);
    }
}
