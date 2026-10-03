<?php

namespace Tests\Feature;

use App\Services\AdminScoreService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Mockery;
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

    public function test_score_page_renders_userteam_tab_with_matchround_selector(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'userteam', null, null, 0, null)
                ->andReturn([
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Spieltag 1'],
                        ['matchround_id' => 4, 'matchround_title' => 'Spieltag 2'],
                    ],
                    'matchround_id' => 0,
                    'userteam_preview' => null,
                    'user_preview' => null,
                    'subs_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score')
            ->assertOk()
            ->assertSee('UserScore Settings', false)
            ->assertSee('Userteam Score', false)
            ->assertSee('User Score', false)
            ->assertDontSee('Auswechslungen', false)
            ->assertSee('Alle Spielrunden', false)
            ->assertSee('Spieltag 1', false)
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
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'user', null, null, 0, null)
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [],
                    'matchround_id' => 0,
                    'userteam_preview' => null,
                    'user_preview' => null,
                    'subs_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score?tab=user')
            ->assertOk()
            ->assertSee('ffb_userscore', false)
            ->assertSee('Score berechnen', false);
    }

    public function test_score_page_shows_subs_tab_when_league_has_bench(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'subs', null, null, 0, null)
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
                    'tab' => 'subs',
                    'league_has_substitutions' => true,
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Spieltag 1'],
                    ],
                    'matchround_id' => 0,
                    'userteam_preview' => null,
                    'user_preview' => null,
                    'subs_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score?tab=subs')
            ->assertOk()
            ->assertSee('Auswechslungen', false)
            ->assertSee('Auswechslungen berechnen', false);
    }

    public function test_calculate_userteam_scores_passes_selected_matchround(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'matchround_id' => 3,
            'rows' => [
                [
                    'userteam_id' => 11,
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'matchround_id' => 3,
                    'matchround_title' => 'Spieltag 1',
                    'score' => 42,
                    'lc_points' => 5,
                    'previous_score' => 10,
                    'previous_lc_points' => 0,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('calculateUserteamScores')
                ->once()
                ->with(544, Mockery::on(static fn (array $input): bool => (int) ($input['matchround_id'] ?? 0) === 3))
                ->andReturn([
                    'ok' => true,
                    'message' => 'Userteam-Scores für die gewählte Spielrunde berechnet (noch nicht gespeichert).',
                    'details' => ['userteam_id: 11 score: 42 lc: 5'],
                    'tab' => 'userteam',
                    'matchround_id' => 3,
                    'preview' => $preview,
                ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'userteam', $preview, null, 3, null)
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Spieltag 1'],
                    ],
                    'matchround_id' => 3,
                    'userteam_preview' => $preview,
                    'user_preview' => null,
                    'subs_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores/calculate', ['matchround_id' => 3])
            ->assertOk()
            ->assertSee('gewählte Spielrunde', false)
            ->assertSee('alice', false)
            ->assertSee('Spieltag 1', false)
            ->assertSee('42', false);
    }

    public function test_calculate_userteam_scores_all_rounds(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'matchround_id' => 0,
            'rows' => [
                [
                    'userteam_id' => 11,
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'matchround_id' => 3,
                    'matchround_title' => 'Spieltag 1',
                    'score' => 42,
                    'lc_points' => 5,
                    'previous_score' => 10,
                    'previous_lc_points' => 0,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('calculateUserteamScores')
                ->once()
                ->with(544, Mockery::type('array'))
                ->andReturn([
                    'ok' => true,
                    'message' => 'Userteam-Scores für alle Spielrunden berechnet (noch nicht gespeichert).',
                    'details' => ['userteam_id: 11 score: 42 lc: 5'],
                    'tab' => 'userteam',
                    'matchround_id' => 0,
                    'preview' => $preview,
                ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'userteam', $preview, null, 0, null)
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Spieltag 1'],
                    ],
                    'matchround_id' => 0,
                    'userteam_preview' => $preview,
                    'user_preview' => null,
                    'subs_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores/calculate', ['matchround_id' => 0])
            ->assertOk()
            ->assertSee('alle Spielrunden', false)
            ->assertSee('alice', false);
    }

    public function test_save_userteam_scores_persists_and_shows_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'league_id' => 7,
            'matchround_id' => 3,
            'rows' => [
                [
                    'userteam_id' => 11,
                    'user_id' => 9,
                    'user_nickname' => 'alice',
                    'matchround_id' => 3,
                    'matchround_title' => 'Spieltag 1',
                    'score' => 42,
                    'lc_points' => 5,
                    'previous_score' => 10,
                    'previous_lc_points' => 0,
                ],
            ],
        ];

        $this->mock(AdminScoreService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('saveUserteamScores')
                ->once()
                ->with(544, Mockery::on(static fn (array $input): bool => (int) ($input['matchround_id'] ?? 0) === 3))
                ->andReturn([
                    'ok' => true,
                    'message' => 'Userteam-Scores für die gewählte Spielrunde erfolgreich gespeichert (inkl. LC-Punkte für beendete Runden).',
                    'details' => ['userteam_id: 11 score: 42 lc: 5'],
                    'tab' => 'userteam',
                    'matchround_id' => 3,
                    'preview' => $preview,
                ]);
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 'userteam', $preview, null, 3, null)
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Spieltag 1'],
                    ],
                    'matchround_id' => 3,
                    'userteam_preview' => $preview,
                    'user_preview' => null,
                    'subs_preview' => null,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores/save', ['matchround_id' => 3])
            ->assertOk()
            ->assertSee('Userteam-Scores für die gewählte Spielrunde erfolgreich gespeichert', false);
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
                ->with(544, 'user', null, $preview, null, null)
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [],
                    'matchround_id' => 0,
                    'userteam_preview' => null,
                    'user_preview' => $preview,
                    'subs_preview' => null,
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
                ->with(544, 'user', null, $preview, null, null)
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
                    'league_has_substitutions' => false,
                    'matchrounds' => [],
                    'matchround_id' => 0,
                    'userteam_preview' => null,
                    'user_preview' => $preview,
                    'subs_preview' => null,
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
            $mock->shouldReceive('calculateUserteamScores')
                ->once()
                ->with(544, Mockery::type('array'))
                ->andReturn([
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
            $mock->shouldReceive('saveUserteamScores')
                ->once()
                ->with(544, Mockery::type('array'))
                ->andReturn([
                    'ok' => true,
                    'message' => 'Userteam-Scores für alle Spielrunden erfolgreich gespeichert (inkl. LC-Punkte für beendete Runden).',
                    'details' => [],
                    'tab' => 'userteam',
                    'matchround_id' => 0,
                    'preview' => ['league_id' => 7, 'matchround_id' => 0, 'rows' => []],
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
                'matchrounds' => [],
                'matchround_id' => 0,
                'userteam_preview' => ['league_id' => 7, 'matchround_id' => 0, 'rows' => []],
                'user_preview' => null,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores')
            ->assertOk()
            ->assertSee('Userteam-Scores für alle Spielrunden erfolgreich gespeichert', false);
    }
}
