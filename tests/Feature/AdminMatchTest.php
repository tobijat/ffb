<?php

namespace Tests\Feature;

use App\Services\AdminMatchService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminMatchTest extends TestCase
{
    public function test_matches_admin_redirects_guests(): void
    {
        $this->get('/admin/matches')
            ->assertRedirect(route('start', ['destination' => '/admin/matches']));
    }

    public function test_matches_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matches')
            ->assertRedirect(route('start'));
    }

    public function test_matches_prompts_for_league_without_game(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchService::class, function ($mock) {
            $mock->shouldReceive('defaultGameId')->once()->with(544)->andReturn(0);
            $mock->shouldReceive('pagePayload')->once()->with(544, 0, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_match.png',
                        'name' => 'Spiele',
                        'link' => '/admin/matches',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game' => null,
                'games' => [
                    ['game_id' => 26, 'game_title' => 'Testliga', 'game_archive' => 0],
                ],
                'selected_game_id' => 0,
                'selected_game_title' => null,
                'matchrounds' => [],
                'teams' => [],
                'items' => [],
                'form' => [
                    'match_id' => '',
                    'match_round' => '',
                    'match_date' => '',
                    'match_hometeam_id' => '',
                    'match_guestteam_id' => '',
                    'match_status' => '',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matches')
            ->assertOk()
            ->assertSee('Spiele', false)
            ->assertSee('Liga wählen', false)
            ->assertSee('Wähle oben eine Liga', false)
            ->assertDontSee('name="match_hometeam_id"', false);
    }

    public function test_matches_lists_for_selected_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, 26, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_game' => null,
                'games' => [
                    ['game_id' => 26, 'game_title' => 'Testliga', 'game_archive' => 0],
                ],
                'selected_game_id' => 26,
                'selected_game_title' => 'Testliga',
                'matchrounds' => [
                    ['matchround_id' => 12, 'matchround_title' => 'Runde 1'],
                ],
                'teams' => [
                    ['team_id' => 1, 'team_label' => 'Heim FC'],
                    ['team_id' => 2, 'team_label' => 'Gast United'],
                ],
                'items' => [
                    [
                        'match_id' => 99,
                        'match_date' => '6.9.2026',
                        'match_round_title' => 'Runde 1',
                        'home_name' => 'Heim FC',
                        'guest_name' => 'Gast United',
                        'home_flag_url' => null,
                        'guest_flag_url' => null,
                        'match_status' => '',
                        'status_ok' => true,
                    ],
                ],
                'form' => [
                    'match_id' => '',
                    'match_round' => '',
                    'match_date' => '',
                    'match_hometeam_id' => '',
                    'match_guestteam_id' => '',
                    'match_status' => '',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matches?game_id=26')
            ->assertOk()
            ->assertSee('Testliga', false)
            ->assertSee('Heim FC', false)
            ->assertSee('Gast United', false)
            ->assertSee('type="date"', false)
            ->assertSee('name="match_hometeam_id"', false)
            ->assertDontSee('match_homescore', false)
            ->assertSee('Hinzufügen', false);
    }

    public function test_matches_store_redirects_with_game_and_prefill(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $nextForm = [
            'match_id' => '',
            'match_round' => 12,
            'match_date' => '2026-09-06',
            'match_hometeam_id' => '',
            'match_guestteam_id' => '',
            'match_status' => '',
        ];

        $this->mock(AdminMatchService::class, function ($mock) use ($nextForm) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => true,
                'message' => 'Spiel erfolgreich hinzugefügt.',
                'game_id' => 26,
                'next_form' => $nextForm,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/matches', [
                'match_round' => 12,
                'match_date' => '2026-09-06',
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_status' => '',
            ])
            ->assertRedirect(route('admin.matches', ['game_id' => 26]))
            ->assertSessionHas('admin_message', 'Spiel erfolgreich hinzugefügt.')
            ->assertSessionHas('admin_match_prefill', $nextForm);
    }

    public function test_matches_delete_redirects_with_game(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(99)->andReturn([
                'ok' => true,
                'message' => 'Spiel erfolgreich gelöscht.',
                'game_id' => 26,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/matches/99', ['game_id' => 26])
            ->assertRedirect(route('admin.matches', ['game_id' => 26]))
            ->assertSessionHas('admin_message', 'Spiel erfolgreich gelöscht.');
    }
}
