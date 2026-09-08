<?php

namespace App\Services;

use App\Models\GameOptions;
use App\Models\Matchround;
use App\Models\Team;
use App\Models\UserDetails;
use App\Models\UserPermissions;
use App\Models\Userscore;
use App\Models\WebUser;

class ProfilePopupService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function forUser(int $viewerId, int $profileUserId): array
    {
        if ($profileUserId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'user_id is required'];
        }

        $user = WebUser::query()->find($profileUserId);
        $details = UserDetails::query()->find($profileUserId);
        $perms = UserPermissions::query()->find($profileUserId);

        if (! $user || ! $details) {
            return ['ok' => false, 'status' => 404, 'error' => 'User not found'];
        }

        $visibleProfile = (bool) ($perms?->user_permissions_ffb_visible_profile ?? false);
        $avatar = (string) ($details->user_details_avatar ?: 'avatar_na.png');
        $photo = (string) ($details->user_details_photo ?: 'profile_na.png');
        if ($photo === 'profile_na.png' && $user->user_gender) {
            $photoUrl = '/images/ffb/profiles/photo/'.$user->user_gender.'_'.$photo;
        } else {
            $photoUrl = '/images/ffb/profiles/photo/'.$photo;
        }

        $favTeam = $this->teamInfo((int) ($details->user_details_ffb_favourite_team ?? 0));
        $ownTeam = $this->teamInfo((int) ($details->user_details_ffb_own_team ?? 0));

        $fname = $user->user_fname ?: null;
        $lname = $user->user_lname ?: null;

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_ownprofile' => $viewerId === (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'user_fname' => $visibleProfile ? $fname : null,
                    'user_lname' => $visibleProfile ? $lname : null,
                    'user_name' => ($visibleProfile && ($fname || $lname))
                        ? trim(($fname ?? '').' '.($lname ?? ''))
                        : null,
                    'user_gender' => $user->user_gender ?: null,
                    'user_date_llogin' => $user->user_date_llogin
                        ? date('d.m.Y', strtotime((string) $user->user_date_llogin))
                        : null,
                    'user_date_register' => $user->user_date_register
                        ? date('d.m.Y', strtotime((string) $user->user_date_register))
                        : null,
                    'avatar_url' => '/images/ffb/profiles/avatar/'.$avatar,
                    'photo_url' => $photoUrl,
                    'user_details_city' => $details->user_details_city ?: null,
                    'user_details_website' => $details->user_details_website ?: null,
                    'user_details_phone' => $visibleProfile ? ($details->user_details_phone ?: null) : null,
                    'user_perm_profile' => $visibleProfile,
                    'favourite_team' => $favTeam,
                    'own_team' => $ownTeam,
                ],
                'participations' => $this->participations($profileUserId),
            ],
        ];
    }

    /**
     * @return array{id: int, name: string, nationality: string}|null
     */
    private function teamInfo(int $teamId): ?array
    {
        if ($teamId <= 0) {
            return null;
        }

        $team = Team::query()->find($teamId);
        if (! $team) {
            return null;
        }

        return [
            'id' => (int) $team->team_id,
            'name' => (string) $team->team_name,
            'nationality' => (string) ($team->team_nationality ?: ''),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function participations(int $userId): array
    {
        $scores = Userscore::query()
            ->with('game')
            ->where('userscore_user_id', $userId)
            ->whereHas('game', fn ($q) => $q->where('game_status', 1))
            ->orderByDesc('userscore_game_id')
            ->get();

        $out = [];
        foreach ($scores as $score) {
            $game = $score->game;
            if (! $game) {
                continue;
            }

            $gameId = (int) $game->game_id;
            $rankMode = (string) (GameOptions::query()
                ->where('options_game_id', $gameId)
                ->value('options_game_rankmode') ?? 'wc');
            if (! in_array($rankMode, ['points', 'wc'], true)) {
                $rankMode = 'wc';
            }

            $archive = (int) ($game->game_archive ?? 0) === 1;
            [$start, $end] = $this->gameDateRange($gameId, $archive);

            $out[] = [
                'game_id' => $gameId,
                'game_title' => (string) $game->game_title,
                'game_symbol' => $game->game_symbol ?: null,
                'game_archive' => $archive,
                'score_rm' => $rankMode,
                'score_wc' => (int) $score->userscore_wc_points,
                'score_points' => (int) $score->userscore_total,
                'score_start' => $start,
                'score_end' => $end,
                'user_rank' => $this->calculateUserRank($userId, $gameId, $rankMode),
            ];
        }

        return $out;
    }

    /**
     * @return array{0: string|null, 1: string|null}
     */
    private function gameDateRange(int $gameId, bool $archive): array
    {
        $first = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->orderBy('matchround_startdate')
            ->value('matchround_startdate');

        if (! $first) {
            return [null, null];
        }

        $start = date('d.m.y', strtotime((string) $first));

        if (! $archive) {
            return [$start, 'jetzt'];
        }

        $last = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->orderByDesc('matchround_enddate')
            ->value('matchround_enddate');

        return [$start, $last ? date('d.m.y', strtotime((string) $last)) : null];
    }

    private function calculateUserRank(int $userId, int $gameId, string $rankMode): int
    {
        $query = Userscore::query()->where('userscore_game_id', $gameId);
        if ($rankMode === 'wc') {
            $query->orderByDesc('userscore_wc_points')->orderByDesc('userscore_total');
        } else {
            $query->orderByDesc('userscore_total');
        }

        $items = $query->get();
        if ($items->isEmpty()) {
            return 0;
        }

        $lastScore = 10000;
        $lastPoints = 10000;
        $rank = 1;
        $i = 0;

        foreach ($items as $item) {
            $i++;
            if ($rankMode === 'wc') {
                $wc = (int) $item->userscore_wc_points;
                $pts = (int) $item->userscore_total;
                if ($wc < $lastScore) {
                    $lastScore = $wc;
                    $lastPoints = $pts;
                    $rank = $i;
                } elseif ($pts < $lastPoints) {
                    $lastPoints = $pts;
                    $rank = $i;
                }
            } else {
                $pts = (int) $item->userscore_total;
                if ($pts < $lastScore) {
                    $lastScore = $pts;
                    $rank = $i;
                }
            }

            if ((int) $item->userscore_user_id === $userId) {
                return $rank;
            }
        }

        return 0;
    }
}
