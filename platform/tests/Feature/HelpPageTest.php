<?php

namespace Tests\Feature;

use App\Services\FfbAuth;
use App\Services\HelpService;
use Tests\TestCase;

class HelpPageTest extends TestCase
{
    public function test_help_page_renders_for_guests(): void
    {
        $this->mock(HelpService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(0)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => null,
                    'selected_game_id' => 0,
                    'using_defaults' => true,
                    'options' => [
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
                    ],
                    'wc_points' => [10, 8, 6, 4, 2, 1],
                    'navigation' => [
                        ['symbol' => 'nav_help.png', 'name' => 'Regeln', 'link' => '/platform/help', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->get('/help')
            ->assertOk()
            ->assertSee('Kapitelauswahl', false)
            ->assertSee('Spieler-Punkte', false)
            ->assertSee('css/help.css', false)
            ->assertSee('Standard-Einstellung', false);
    }

    public function test_help_page_renders_for_logged_in_user(): void
    {
        $this->mock(HelpService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    ],
                    'selected_game_id' => 26,
                    'using_defaults' => false,
                    'options' => [
                        'options_game_id' => 26,
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
                    ],
                    'wc_points' => [10, 8, 6, 4, 2, 1],
                    'navigation' => [
                        ['symbol' => 'nav_help.png', 'name' => 'Regeln', 'link' => '/platform/help', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/help')
            ->assertOk()
            ->assertSee('Hallo', false)
            ->assertSee('tester', false)
            ->assertDontSee('Standard-Einstellung', false);
    }
}
