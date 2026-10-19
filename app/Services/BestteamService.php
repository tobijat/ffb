<?php

namespace App\Services;

use App\Models\Extremeteam;
use App\Models\League;
use App\Models\WebUser;
use App\Support\UserAvatar;

class BestteamService
{
    public function __construct(
        private readonly UserscoreService $userscores,
        private readonly MyteamService $myteam,
        private readonly ExtremeTeamService $extremeTeams,
    ) {}

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function pagePayload(int $userId): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return ['ok' => false, 'status' => 401, 'error' => 'Unknown user'];
        }

        $details = $user->details;
        $avatar = (string) ($details?->user_details_avatar ?: 'avatar_na.png');
        $leagueId = (int) ($details?->user_details_ffb_selected_league ?? 0);

        if ($leagueId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => UserAvatar::publicUrl($avatar),
                    'is_admin' => (bool) ($user->user_admin ?? false),
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $user->user_id),
                ],
                'selected_league_id' => $leagueId,
                'selected_league_asset_key' => (string) (League::query()->whereKey($leagueId)->value('asset_key') ?? ''),
                'navigation' => app(DashboardService::class)->navigation($userId),
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function matchrounds(int $userId): array
    {
        return $this->userscores->pastMatchrounds($userId);
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function roundStats(int $matchroundId): array
    {
        return $this->myteam->roundStats($matchroundId);
    }

    /**
     * Team statistics for the stored top or flop XI of a matchround.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function teamStats(int $matchroundId, string $type): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        $type = $type === 'flop' ? 'flop' : 'top';

        $team = Extremeteam::query()
            ->where('extremeteam_matchround_id', $matchroundId)
            ->where('extremeteam_top_or_flop', $type)
            ->first();

        if (! $team) {
            return [
                'ok' => true,
                'data' => [
                    'matchround_id' => $matchroundId,
                    'type' => $type,
                    'stats' => null,
                ],
            ];
        }

        $slotIds = $team->playerteamIdsInSlotOrder();
        $stats = $this->myteam->aggregateTeamStats($slotIds, $matchroundId);
        $price = (float) $team->extremeteam_price;
        $score = (int) $team->extremeteam_score;
        $stats['price'] = $price;
        $stats['score'] = $score;
        $stats['score_per_player'] = round($score / 11, 2);
        $stats['credits_per_point'] = $score > 0 ? round($price / $score, 1) : 0.0;

        return [
            'ok' => true,
            'data' => [
                'matchround_id' => $matchroundId,
                'type' => $type,
                'stats' => $stats,
            ],
        ];
    }

    /**
     * Load the persisted top or flop XI for a matchround.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function bestTeam(int $matchroundId, string $type): array
    {
        return $this->extremeTeams->loadForApi($matchroundId, $type);
    }
}
