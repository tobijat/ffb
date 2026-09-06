<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\BestteamService;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use Tests\TestCase;

class BestteamApiTest extends TestCase
{
    private function actingAsFfbUser(int $userId = 544): void
    {
        $user = new WebUser;
        $user->user_id = $userId;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';
        $user->user_admin = false;

        $this->mock(FfbUserResolver::class, function ($mock) use ($user, $userId) {
            $mock->shouldReceive('findActive')->with($userId)->andReturn($user);
        });
    }

    public function test_bestteam_apis_require_auth(): void
    {
        $this->getJson('/api/bestteam/matchrounds')->assertStatus(401);
        $this->getJson('/api/bestteam/team?matchround_id=1&type=top')->assertStatus(401);
        $this->getJson('/api/bestteam/stats/round?matchround_id=1')->assertStatus(401);
    }

    public function test_matchrounds_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(BestteamService::class, function ($mock) {
            $mock->shouldReceive('matchrounds')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'selected_game_id' => 26,
                    'matchrounds' => [
                        [
                            'matchround_id' => 280,
                            'matchround_title' => 'Runde 1',
                            'matchround_actual' => 1,
                            'matchround_running' => 0,
                            'matches' => [],
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/bestteam/matchrounds')
            ->assertOk()
            ->assertJsonPath('data.matchrounds.0.matchround_title', 'Runde 1');
    }

    public function test_team_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(BestteamService::class, function ($mock) {
            $mock->shouldReceive('bestTeam')->once()->with(280, 'top')->andReturn([
                'ok' => true,
                'data' => [
                    'matchround_id' => 280,
                    'type' => 'top',
                    'userteam' => [
                        'userteam_score' => 88,
                        'userteam_price' => 95.5,
                    ],
                    'players' => [
                        [
                            'playerteam_id' => 1,
                            'player_fname' => 'Max',
                            'player_lname' => 'Muster',
                            'playerteam_player_position' => 'g',
                            'playerstats_score' => 8,
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/bestteam/team?matchround_id=280&type=top')
            ->assertOk()
            ->assertJsonPath('data.userteam.userteam_score', 88)
            ->assertJsonPath('data.players.0.player_lname', 'Muster');
    }

    public function test_round_stats_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(BestteamService::class, function ($mock) {
            $mock->shouldReceive('roundStats')->once()->with(280)->andReturn([
                'ok' => true,
                'data' => [
                    'matchround_id' => 280,
                    'stats' => [
                        'num_users' => 10,
                        'goals' => 22,
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/bestteam/stats/round?matchround_id=280')
            ->assertOk()
            ->assertJsonPath('data.stats.num_users', 10);
    }

    public function test_page_redirects_guests(): void
    {
        $this->get('/bestteam')
            ->assertRedirect(route('start', ['destination' => '/platform/bestteam']));
    }

    public function test_page_renders_for_logged_in_user(): void
    {
        $this->mock(BestteamService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_admin' => false,
                    ],
                    'selected_game_id' => 26,
                    'navigation' => [
                        ['symbol' => 'nav_topflop.png', 'name' => 'Top&Flop', 'link' => '/platform/bestteam', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/bestteam')
            ->assertOk()
            ->assertSee('js/bestteam.js', false)
            ->assertSee('Top-Team der Runde', false)
            ->assertSee('Statistiken anzeigen', false);
    }
}
