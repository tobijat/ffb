<?php

namespace Tests\Feature;

use App\Services\AdminScoreService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminScoreTest extends TestCase
{
    public function test_score_redirects_guests(): void
    {
        $this->get('/admin/score')
            ->assertRedirect(route('start', ['destination' => '/admin/score']));
    }

    public function test_score_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score')
            ->assertRedirect(route('start'));
    }

    public function test_score_page_renders_userteam_tab(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('normalizeTab')->once()->with(null)->andReturn('userteam');
            $mock->shouldReceive('pagePayload')->once()->with(544, 'userteam', null, null)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_results.png',
                        'name' => 'Score',
                        'link' => '/admin/score',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_league_id' => 7,
                'selected_league' => [
                    'league_id' => 7,
                    'league_title' => 'Bundesliga Test',
                    'symbol_url' => '/images/ffb/games/na.png',
                ],
                'tab' => 'userteam',
                'userteam_preview' => null,
                'user_preview' => null,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score')
            ->assertOk()
            ->assertSee('UserScore Settings', false)
            ->assertSee('Userteam Score', false)
            ->assertSee('User Score', false)
            ->assertSee('Score berechnen', false)
            ->assertSee('Speichern', false)
            ->assertSee('Bundesliga Test', false);
    }

    public function test_score_page_renders_user_tab(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('normalizeTab')->once()->with('user')->andReturn('user');
            $mock->shouldReceive('pagePayload')->once()->with(544, 'user', null, null)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_league_id' => 7,
                'selected_league' => [
                    'league_id' => 7,
                    'league_title' => 'Bundesliga Test',
                    'symbol_url' => '/images/ffb/games/na.png',
                ],
                'tab' => 'user',
                'userteam_preview' => null,
                'user_preview' => null,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score?tab=user')
            ->assertOk()
            ->assertSee('ffb_userscore', false)
            ->assertSee('Score berechnen', false);
    }

    public function test_calculate_userteam_scores_shows_preview_without_redirect_message_only(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'rows' => [
                [
                    'userteam_id' => 11,
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'matchround_id' => 3,
                    'score' => 42,
                    'lc_points' => 5,
                    'previous_score' => 10,
                    'previous_lc_points' => 0,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('calculateUserteamScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'Userteam-Scores berechnet (noch nicht gespeichert).',
                'details' => ['userteam_id: 11 score: 42 lc: 5'],
                'tab' => 'userteam',
                'preview' => $preview,
            ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'userteam', $preview, null)
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                        'symbol_url' => '/images/ffb/games/na.png',
                    ],
                    'tab' => 'userteam',
                    'userteam_preview' => $preview,
                    'user_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores/calculate')
            ->assertOk()
            ->assertSee('Userteam-Scores berechnet (noch nicht gespeichert).', false)
            ->assertSee('alice', false)
            ->assertSee('42', false)
            ->assertSee('10', false);
    }

    public function test_save_userteam_scores_persists_and_shows_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'rows' => [
                [
                    'userteam_id' => 11,
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'matchround_id' => 3,
                    'score' => 42,
                    'lc_points' => 5,
                    'previous_score' => 10,
                    'previous_lc_points' => 0,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('saveUserteamScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'Userteam-Scores erfolgreich gespeichert (inkl. LC-Punkte für beendete Runden).',
                'details' => ['userteam_id: 11 score: 42 lc: 5'],
                'tab' => 'userteam',
                'preview' => $preview,
            ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'userteam', $preview, null)
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                        'symbol_url' => '/images/ffb/games/na.png',
                    ],
                    'tab' => 'userteam',
                    'userteam_preview' => $preview,
                    'user_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores/save')
            ->assertOk()
            ->assertSee('Userteam-Scores erfolgreich gespeichert', false);
    }

    public function test_calculate_user_scores_shows_preview(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'rows' => [
                [
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'score' => 100,
                    'lc_points' => 8,
                    'previous_score' => 80,
                    'previous_lc_points' => 3,
                    'is_new' => false,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('calculateUserScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'User-Scores berechnet (noch nicht gespeichert).',
                'details' => ['user_id: 9 score: 100'],
                'tab' => 'user',
                'preview' => $preview,
            ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'user', null, $preview)
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                        'symbol_url' => '/images/ffb/games/na.png',
                    ],
                    'tab' => 'user',
                    'userteam_preview' => null,
                    'user_preview' => $preview,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/user-scores/calculate')
            ->assertOk()
            ->assertSee('User-Scores berechnet (noch nicht gespeichert).', false)
            ->assertSee('alice', false)
            ->assertSee('100', false);
    }

    public function test_save_user_scores_persists(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'rows' => [
                [
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'score' => 100,
                    'lc_points' => 8,
                    'previous_score' => 80,
                    'previous_lc_points' => 3,
                    'is_new' => false,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('saveUserScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'User-Scores erfolgreich gespeichert.',
                'details' => ['user_id: 9 score: 100'],
                'tab' => 'user',
                'preview' => $preview,
            ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'user', null, $preview)
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                        'symbol_url' => '/images/ffb/games/na.png',
                    ],
                    'tab' => 'user',
                    'userteam_preview' => null,
                    'user_preview' => $preview,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/user-scores/save')
            ->assertOk()
            ->assertSee('User-Scores erfolgreich gespeichert.', false);
    }

    public function test_calculate_userteam_scores_without_league_flashes_errors(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('calculateUserteamScores')->once()->with(544)->andReturn([
                'ok' => false,
                'errors' => ['Bitte zuerst eine Liga auswählen.'],
                'tab' => 'userteam',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores/calculate')
            ->assertRedirect(route('admin.score', ['tab' => 'userteam']))
            ->assertSessionHas('admin_errors', ['Bitte zuerst eine Liga auswählen.']);
    }

    public function test_legacy_userteam_scores_route_still_saves(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('saveUserteamScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'Userteam-Scores erfolgreich gespeichert (inkl. LC-Punkte für beendete Runden).',
                'details' => [],
                'tab' => 'userteam',
                'preview' => ['league_id' => 7, 'rows' => []],
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_league_id' => 7,
                'selected_league' => [
                    'league_id' => 7,
                    'league_title' => 'Bundesliga Test',
                    'symbol_url' => '/images/ffb/games/na.png',
                ],
                'tab' => 'userteam',
                'userteam_preview' => ['league_id' => 7, 'rows' => []],
                'user_preview' => null,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores')
            ->assertOk()
            ->assertSee('Userteam-Scores erfolgreich gespeichert', false);
    }
}
