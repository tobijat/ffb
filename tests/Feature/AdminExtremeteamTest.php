<?php

namespace Tests\Feature;

use App\Services\AdminExtremeteamService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminExtremeteamTest extends TestCase
{
    public function test_extremeteam_redirects_guests(): void
    {
        $this->get('/admin/extremeteam')
            ->assertRedirect(route('start', ['destination' => '/admin/extremeteam']));
    }

    public function test_extremeteam_page_renders(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminExtremeteamService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
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
                'past_matchrounds' => [
                    [
                        'matchround_id' => 12,
                        'matchround_title' => 'Runde 1',
                        'matchround_startdate' => '2026-01-01 00:00:00',
                        'matchround_enddate' => '2026-01-07 00:00:00',
                    ],
                ],
                'existing' => [
                    12 => ['top' => 88],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/extremeteam')
            ->assertOk()
            ->assertSee('Top &amp; Flop Teams', false)
            ->assertSee('Bundesliga Test', false)
            ->assertSee('Runde 1', false)
            ->assertSee('Ausgewählte Runden berechnen', false)
            ->assertDontSee('Sichtbare Ligen backfillen', false);
    }

    public function test_populate_posts_and_flashes_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminExtremeteamService::class, function ($mock) {
            $mock->shouldReceive('populate')->once()->andReturn([
                'ok' => true,
                'message' => 'Extreme Teams: 2 gespeichert · 0 übersprungen.',
                'details' => ['round 12 top: stored'],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/extremeteam/populate', [
                'scope' => 'selected',
                'matchround_ids' => [12],
                'include_top' => '1',
                'include_flop' => '1',
            ])
            ->assertRedirect(route('admin.extremeteam'))
            ->assertSessionHas('admin_message', 'Extreme Teams: 2 gespeichert · 0 übersprungen.');
    }
}
