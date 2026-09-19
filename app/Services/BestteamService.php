<?php

namespace App\Services;

use App\Models\WebUser;

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
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
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
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'is_admin' => (bool) ($user->user_admin ?? false),
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $user->user_id),
                ],
                'selected_league_id' => $leagueId,
                'navigation' => app(DashboardService::class)->navigation(),
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
     * Load the persisted top or flop XI for a matchround.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function bestTeam(int $matchroundId, string $type): array
    {
        return $this->extremeTeams->loadForApi($matchroundId, $type);
    }
}
