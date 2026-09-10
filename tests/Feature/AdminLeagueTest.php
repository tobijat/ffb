<?php

namespace Tests\Feature;

use App\Services\AdminLeagueService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminLeagueTest extends TestCase
{
    public function test_leagues_admin_redirects_guests(): void
    {
        $this->get('/admin/leagues')
            ->assertRedirect(route('start', ['destination' => '/admin/leagues']));
    }

    public function test_leagues_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/leagues')
            ->assertRedirect(route('start'));
    }

    public function test_leagues_admin_renders_for_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminLeagueService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_config.png',
                        'name' => 'Ligen',
                        'link' => '/admin/leagues',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_league' => null,
                'items' => [
                    [
                        'league_id' => 26,
                        'league_title' => 'Testliga',
                        'league_status' => 1,
                        'league_visible' => 1,
                        'league_archive' => 0,
                        'league_countdown' => 0,
                        'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
                    ],
                ],
                'form' => array_merge([
                    'league_id' => '',
                    'league_title' => '',
                    'league_description' => '',
                    'league_status' => 1,
                    'league_visible' => 1,
                    'league_archive' => 0,
                    'league_countdown' => 0,
                    'league_symbol' => 'symbol_game_na.png',
                    'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
                ], $this->defaultOptions()),
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/leagues')
            ->assertOk()
            ->assertSee('Ligen', false)
            ->assertSee('Testliga', false)
            ->assertSee('name="league_title"', false)
            ->assertSee('name="league_symbol_file"', false)
            ->assertSee('Hinzufügen', false);
    }

    public function test_leagues_store_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminLeagueService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => true,
                'message' => 'Liga erfolgreich angelegt.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/leagues', [
                'league_title' => 'Neue Liga',
                'league_status' => 1,
                'league_visible' => 1,
                'league_archive' => 0,
                'league_countdown' => 0,
            ])
            ->assertRedirect(route('admin.leagues'))
            ->assertSessionHas('admin_message', 'Liga erfolgreich angelegt.');
    }

    public function test_leagues_delete_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminLeagueService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(26)->andReturn([
                'ok' => true,
                'message' => 'Liga erfolgreich gelöscht.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/leagues/26')
            ->assertRedirect(route('admin.leagues'))
            ->assertSessionHas('admin_message', 'Liga erfolgreich gelöscht.');
    }

    /**
     * @return array<string, int|string>
     */
    private function defaultOptions(): array
    {
        return [
            'options_league_rankmode' => 'wc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_league_wcpoints' => 'new',
            'options_league_remind_hours_before' => 0,
            'options_score_minutes' => 60,
            'options_score_minutes_treshold' => 30,
            'options_score_minutes_gt' => 3,
            'options_score_minutes_lt' => 2,
            'options_score_minutes_lt30' => 1,
            'options_score_goals_g' => 6,
            'options_score_goals_d' => 5,
            'options_score_goals_m' => 4,
            'options_score_goals_s' => 4,
            'options_score_assists' => 3,
            'options_score_owngoals' => -2,
            'options_score_no_oppgoals_g' => 4,
            'options_score_no_oppgoals_d' => 3,
            'options_score_no_oppgoals_m' => 1,
            'options_score_oppgoals_g' => -1,
            'options_score_oppgoals_d' => -1,
            'options_score_card_y' => -2,
            'options_score_card_yr' => -4,
            'options_score_card_r' => -5,
            'options_score_penalty_saved' => 2,
            'options_score_penalty_lost' => -2,
            'options_score_penaltyshootout_save' => 2,
            'options_score_penaltyshootout_lost' => -2,
            'options_score_penaltyshootout_hit' => 2,
            'options_score_high_loss' => 0,
            'options_score_high_win' => 0,
            'options_score_high_win_loss_treshold' => 0,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 2,
            'options_lineup_max_g' => 1,
            'options_lineup_max_d' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_max_s' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_min_m' => 3,
            'options_lineup_min_s' => 1,
            'options_status_error' => 500,
            'options_status_error_validation' => 501,
            'options_status_success' => 200,
            'options_status_success_insert' => 201,
            'options_status_success_update' => 202,
            'options_status_success_delete' => 203,
        ];
    }
}
