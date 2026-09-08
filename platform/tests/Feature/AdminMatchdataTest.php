<?php

namespace Tests\Feature;

use App\Services\AdminMatchdataService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminMatchdataTest extends TestCase
{
    public function test_matchdata_redirects_guests(): void
    {
        $this->get('/admin/matchdata')
            ->assertRedirect(route('start', ['destination' => '/platform/admin/matchdata']));
    }

    public function test_matchdata_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/matchdata')
            ->assertRedirect(route('start'));
    }

    public function test_matchdata_page_renders(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchdataService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(7)->andReturn([
                'user' => [
                    'user_id' => 7,
                    'user_nickname' => 'admin',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_score.png',
                        'name' => 'Spieldaten',
                        'link' => '/platform/admin/matchdata',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game_id' => 1,
                'selected_game' => [
                    'game_id' => 1,
                    'game_title' => 'Testliga',
                    'symbol_url' => '/images/ffb/symbols/x.png',
                ],
                'games' => [
                    [
                        'game_id' => 1,
                        'game_title' => 'Testliga',
                        'game_archive' => 0,
                    ],
                ],
                'pointsmode' => 'new',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 7])
            ->get('/admin/matchdata')
            ->assertOk()
            ->assertSee('Spieldaten')
            ->assertSee('Liga')
            ->assertSee('Änderungen speichern (0)');
    }

    public function test_rounds_json(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchdataService::class, function ($mock) {
            $mock->shouldReceive('rounds')->once()->with(7)->andReturn([
                [
                    'matchround_id' => 3,
                    'matchround_title' => 'R1',
                    'matchround_startdate' => '1.1.2026 12:00',
                    'matchround_enddate' => '2.1.2026 12:00',
                    'started' => true,
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 7])
            ->getJson('/admin/matchdata/rounds')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('rounds.0.matchround_id', 3);
    }

    public function test_save_player_json(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMatchdataService::class, function ($mock) {
            $mock->shouldReceive('savePlayerStats')->once()->with(9, 11, \Mockery::type('array'))->andReturn([
                'ok' => true,
                'message' => 'Existing Playerstats successfully updated!',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 7])
            ->postJson('/admin/matchdata/matches/9/players/11', [
                'minutes' => 90,
                'goals' => '12',
                'assists' => 1,
                'cards' => 'n',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);
    }
}
