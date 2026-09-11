<?php

namespace App\Services;

use App\Models\LeagueOptions;
use App\Models\WebUser;

class HelpService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}
     */
    public function pagePayload(int $userId): array
    {
        $leagueId = 0;
        $user = null;

        if ($userId > 0) {
            $webUser = WebUser::query()->with('details')->find($userId);
            if ($webUser) {
                $details = $webUser->details;
                $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
                $leagueId = (int) ($details?->user_details_ffb_selected_league ?? 0);
                $user = [
                    'user_id' => (int) $webUser->user_id,
                    'user_nickname' => (string) $webUser->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $webUser->user_id),
                ];
            }
        }

        $options = $this->optionsForGame($leagueId);
        $usingDefaults = $leagueId <= 0 || $options['options_league_id'] === 0;

        return [
            'ok' => true,
            'data' => [
                'user' => $user,
                'selected_league_id' => $leagueId,
                'using_defaults' => $usingDefaults,
                'options' => $options,
                'lc_points' => $this->parseLcPoints((string) ($options['options_league_lcpoints'] ?? '')),
                'navigation' => $user
                    ? app(DashboardService::class)->navigation()
                    : self::guestNavigation(),
            ],
        ];
    }

    /**
     * @return list<array{symbol: string, name: string, link: string, style: string}>
     */
    public static function guestNavigation(): array
    {
        // Start is the brand link in the header; no other guest nav items.
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function optionsForGame(int $leagueId): array
    {
        $options = null;
        if ($leagueId > 0) {
            $options = LeagueOptions::query()->where('options_league_id', $leagueId)->first();
        }
        if (! $options) {
            $options = LeagueOptions::query()->where('options_league_id', 0)->first();
        }

        if (! $options) {
            return [
                'options_league_id' => 0,
                'options_league_pointsmode' => 'new',
                'options_league_lcpoints' => '10,8,6,4,2,1',
                'options_lineup_max_players' => 11,
                'options_lineup_max_credits' => 50,
                'options_lineup_max_players_team' => 3,
                'options_lineup_min_g' => 1,
                'options_lineup_min_d' => 3,
                'options_lineup_min_m' => 3,
                'options_lineup_min_s' => 1,
                'options_lineup_max_g' => 1,
                'options_lineup_max_d' => 5,
                'options_lineup_max_m' => 5,
                'options_lineup_max_s' => 3,
                'options_score_minutes_threshold_upper' => 60,
                'options_score_minutes_threshold_lower' => 30,
                'options_score_minutes_high' => 2,
                'options_score_minutes_middle' => 1,
                'options_score_minutes_low' => 0,
                'options_score_goals_g' => 6,
                'options_score_goals_d' => 6,
                'options_score_goals_m' => 5,
                'options_score_goals_s' => 4,
                'options_score_assists' => 0,
                'options_score_no_oppgoals_g' => 4,
                'options_score_no_oppgoals_d' => 4,
                'options_score_no_oppgoals_m' => 1,
                'options_score_oppgoals_g' => -1,
                'options_score_oppgoals_d' => -1,
                'options_score_owngoals' => -2,
                'options_score_card_y' => -1,
                'options_score_card_yr' => -3,
                'options_score_card_r' => -3,
                'options_score_penalty_saved' => 0,
                'options_score_penalty_lost' => 0,
                'options_score_penaltyshootout_save' => 0,
                'options_score_penaltyshootout_hit' => 0,
                'options_score_penaltyshootout_lost' => 0,
            ];
        }

        return [
            'options_league_id' => (int) $options->options_league_id,
            'options_league_pointsmode' => (string) ($options->options_league_pointsmode ?: 'new'),
            'options_league_lcpoints' => (string) ($options->options_league_lcpoints ?: ''),
            'options_lineup_max_players' => (int) $options->options_lineup_max_players,
            'options_lineup_max_credits' => (float) $options->options_lineup_max_credits,
            'options_lineup_max_players_team' => (int) $options->options_lineup_max_players_team,
            'options_lineup_min_g' => (int) $options->options_lineup_min_g,
            'options_lineup_min_d' => (int) $options->options_lineup_min_d,
            'options_lineup_min_m' => (int) $options->options_lineup_min_m,
            'options_lineup_min_s' => (int) $options->options_lineup_min_s,
            'options_lineup_max_g' => (int) $options->options_lineup_max_g,
            'options_lineup_max_d' => (int) $options->options_lineup_max_d,
            'options_lineup_max_m' => (int) $options->options_lineup_max_m,
            'options_lineup_max_s' => (int) $options->options_lineup_max_s,
            'options_score_minutes_threshold_upper' => (int) $options->options_score_minutes_threshold_upper,
            'options_score_minutes_threshold_lower' => (int) $options->options_score_minutes_threshold_lower,
            'options_score_minutes_high' => (int) $options->options_score_minutes_high,
            'options_score_minutes_middle' => (int) $options->options_score_minutes_middle,
            'options_score_minutes_low' => (int) $options->options_score_minutes_low,
            'options_score_goals_g' => (int) $options->options_score_goals_g,
            'options_score_goals_d' => (int) $options->options_score_goals_d,
            'options_score_goals_m' => (int) $options->options_score_goals_m,
            'options_score_goals_s' => (int) $options->options_score_goals_s,
            'options_score_assists' => (int) $options->options_score_assists,
            'options_score_no_oppgoals_g' => (int) $options->options_score_no_oppgoals_g,
            'options_score_no_oppgoals_d' => (int) $options->options_score_no_oppgoals_d,
            'options_score_no_oppgoals_m' => (int) $options->options_score_no_oppgoals_m,
            'options_score_oppgoals_g' => (int) $options->options_score_oppgoals_g,
            'options_score_oppgoals_d' => (int) $options->options_score_oppgoals_d,
            'options_score_owngoals' => (int) $options->options_score_owngoals,
            'options_score_card_y' => (int) $options->options_score_card_y,
            'options_score_card_yr' => (int) $options->options_score_card_yr,
            'options_score_card_r' => (int) $options->options_score_card_r,
            'options_score_penalty_saved' => (int) $options->options_score_penalty_saved,
            'options_score_penalty_lost' => (int) $options->options_score_penalty_lost,
            'options_score_penaltyshootout_save' => (int) $options->options_score_penaltyshootout_save,
            'options_score_penaltyshootout_hit' => (int) $options->options_score_penaltyshootout_hit,
            'options_score_penaltyshootout_lost' => (int) $options->options_score_penaltyshootout_lost,
        ];
    }

    /**
     * @return list<int|string>
     */
    private function parseLcPoints(string $raw): array
    {
        if ($raw === '') {
            return [10, 8, 6, 4, 2, 1];
        }

        return array_map(
            static fn ($v) => is_numeric(trim($v)) ? (0 + trim($v)) : trim($v),
            explode(',', $raw)
        );
    }
}
