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

    public function test_score_page_renders(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
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
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/score')
            ->assertOk()
            ->assertSee('UserScore Settings', false)
            ->assertSee('Set Userteam Score', false)
            ->assertSee('Set User Score', false)
            ->assertSee('Bundesliga Test', false)
            ->assertSee('do only click once!', false);
    }

    public function test_set_userteam_scores_posts_and_flashes_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('setUserteamScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'Userteam-Scores erfolgreich aktualisiert (inkl. WC-Punkte für beendete Runden).',
                'details' => ['userteam_id: 11 score: 42'],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores')
            ->assertRedirect(route('admin.score'))
            ->assertSessionHas('admin_message')
            ->assertSessionHas('admin_details', ['userteam_id: 11 score: 42']);
    }

    public function test_set_user_scores_posts_and_flashes_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('setUserScores')->once()->with(544)->andReturn([
                'ok' => true,
                'message' => 'User-Scores erfolgreich aktualisiert.',
                'details' => ['user_id: 9 score: 100'],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/user-scores')
            ->assertRedirect(route('admin.score'))
            ->assertSessionHas('admin_message', 'User-Scores erfolgreich aktualisiert.')
            ->assertSessionHas('admin_details', ['user_id: 9 score: 100']);
    }

    public function test_set_userteam_scores_without_league_flashes_errors(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminScoreService::class, function ($mock) {
            $mock->shouldReceive('setUserteamScores')->once()->with(544)->andReturn([
                'ok' => false,
                'errors' => ['Bitte zuerst eine Liga auswählen.'],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/score/userteam-scores')
            ->assertRedirect(route('admin.score'))
            ->assertSessionHas('admin_errors', ['Bitte zuerst eine Liga auswählen.']);
    }
}
