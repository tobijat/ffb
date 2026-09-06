<?php

namespace App\Services;

use App\Models\GameOptions;
use App\Models\WebUser;

class HelpService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}
     */
    public function pagePayload(int $userId): array
    {
        $gameId = 0;
        $user = null;

        if ($userId > 0) {
            $webUser = WebUser::query()->with('details')->find($userId);
            if ($webUser) {
                $details = $webUser->details;
                $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
                $gameId = (int) ($details?->user_details_ffb_selected_game ?? 0);
                $user = [
                    'user_id' => (int) $webUser->user_id,
                    'user_nickname' => (string) $webUser->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                ];
            }
        }

        $options = $this->optionsForGame($gameId);
        $usingDefaults = $gameId <= 0 || $options['options_game_id'] === 0;

        return [
            'ok' => true,
            'data' => [
                'user' => $user,
                'selected_game_id' => $gameId,
                'using_defaults' => $usingDefaults,
                'options' => $options,
                'wc_points' => $this->parseWcPoints((string) ($options['options_game_wcpoints'] ?? '')),
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
    private function optionsForGame(int $gameId): array
    {
        $options = null;
        if ($gameId > 0) {
            $options = GameOptions::query()->where('options_game_id', $gameId)->first();
        }
        if (! $options) {
            $options = GameOptions::query()->where('options_game_id', 0)->first();
        }

        if (! $options) {
            return [
                'options_game_id' => 0,
                'options_game_pointsmode' => 'new',
                'options_game_wcpoints' => '10,8,6,4,2,1',
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
                'options_score_minutes' => 60,
                'options_score_minutes_treshold' => 30,
                'options_score_minutes_gt' => 2,
                'options_score_minutes_lt' => 1,
                'options_score_minutes_lt30' => 0,
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
            'options_game_id' => (int) $options->options_game_id,
            'options_game_pointsmode' => (string) ($options->options_game_pointsmode ?: 'new'),
            'options_game_wcpoints' => (string) ($options->options_game_wcpoints ?: ''),
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
            'options_score_minutes' => (int) $options->options_score_minutes,
            'options_score_minutes_treshold' => (int) $options->options_score_minutes_treshold,
            'options_score_minutes_gt' => (int) $options->options_score_minutes_gt,
            'options_score_minutes_lt' => (int) $options->options_score_minutes_lt,
            'options_score_minutes_lt30' => (int) $options->options_score_minutes_lt30,
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
    private function parseWcPoints(string $raw): array
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
