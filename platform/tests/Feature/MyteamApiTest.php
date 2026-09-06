<?php

namespace Tests\Feature;

use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use App\Services\MyteamService;
use App\Models\WebUser;
use Tests\TestCase;

class MyteamApiTest extends TestCase
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

    public function test_myteam_apis_require_auth(): void
    {
        $this->getJson('/api/myteam/matchrounds')->assertStatus(401);
        $this->getJson('/api/myteam/users?matchround_id=1')->assertStatus(401);
        $this->getJson('/api/myteam/team?matchround_id=1')->assertStatus(401);
    }

    public function test_matchrounds_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MyteamService::class, function ($mock) {
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
            ->getJson('/api/myteam/matchrounds')
            ->assertOk()
            ->assertJsonPath('data.matchrounds.0.matchround_title', 'Runde 1');
    }

    public function test_users_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MyteamService::class, function ($mock) {
            $mock->shouldReceive('usersWithTeams')->once()->with(280)->andReturn([
                'ok' => true,
                'data' => [
                    'matchround_id' => 280,
                    'users' => [
                        ['user_id' => 544, 'user_nickname' => 'tester'],
                        ['user_id' => 12, 'user_nickname' => 'alice'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/myteam/users?matchround_id=280')
            ->assertOk()
            ->assertJsonPath('data.users.1.user_nickname', 'alice');
    }

    public function test_team_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MyteamService::class, function ($mock) {
            $mock->shouldReceive('teamForRound')->once()->with(544, 12, 280, false)->andReturn([
                'ok' => true,
                'data' => [
                    'user_id' => 12,
                    'user_nickname' => 'alice',
                    'matchround_id' => 280,
                    'userteam' => [
                        'userteam_id' => 99,
                        'userteam_score' => 42,
                        'userteam_price' => 88.5,
                    ],
                    'players' => [
                        [
                            'playerteam_id' => 55,
                            'player_fname' => 'Max',
                            'player_lname' => 'Muster',
                            'playerteam_player_position' => 'g',
                            'playerstats_score' => 5,
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/myteam/team?matchround_id=280&userteam_user_id=12')
            ->assertOk()
            ->assertJsonPath('data.userteam.userteam_score', 42)
            ->assertJsonPath('data.players.0.playerteam_player_position', 'g');
    }

    public function test_team_forbidden_while_deadline_open(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MyteamService::class, function ($mock) {
            $mock->shouldReceive('teamForRound')->once()->andReturn([
                'ok' => false,
                'status' => 403,
                'error' => 'Du kannst fremde Mannschaften erst ansehen wenn die Deadline vorüber ist!',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/myteam/team?matchround_id=280&userteam_user_id=12')
            ->assertStatus(403)
            ->assertJsonPath('error', 'Du kannst fremde Mannschaften erst ansehen wenn die Deadline vorüber ist!');
    }

    public function test_myteam_page_redirects_guests(): void
    {
        $this->get('/myteam')
            ->assertRedirect(route('start', ['destination' => '/platform/myteam']));
    }

    public function test_myteam_page_renders_for_logged_in_user(): void
    {
        $this->mock(MyteamService::class, function ($mock) {
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
                        ['symbol' => 'nav_player.png', 'name' => 'Mannschaft', 'link' => '/platform/myteam', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/myteam')
            ->assertOk()
            ->assertSee('Mannschaft', false)
            ->assertSee('js/myteam.js', false)
            ->assertSee('soccer-field', false)
            ->assertSee('Statistiken anzeigen', false);
    }

    public function test_user_stats_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MyteamService::class, function ($mock) {
            $mock->shouldReceive('userStats')->once()->with(544, 12, 280, false)->andReturn([
                'ok' => true,
                'data' => [
                    'matchround_id' => 280,
                    'user_id' => 12,
                    'stats' => [
                        'goals' => 3,
                        'system' => '4-4-2',
                        'score_per_player' => 2.5,
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/myteam/stats/user?matchround_id=280&userteam_user_id=12')
            ->assertOk()
            ->assertJsonPath('data.stats.system', '4-4-2');
    }

    public function test_round_stats_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MyteamService::class, function ($mock) {
            $mock->shouldReceive('roundStats')->once()->with(280)->andReturn([
                'ok' => true,
                'data' => [
                    'matchround_id' => 280,
                    'stats' => [
                        'num_users' => 10,
                        'num_matches' => 8,
                        'goals' => 22,
                        'top_of_round' => [
                            'top_player_name' => 'Max Muster',
                            'top_playerteam_id' => 55,
                            'top_score' => 12,
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/myteam/stats/round?matchround_id=280')
            ->assertOk()
            ->assertJsonPath('data.stats.num_users', 10)
            ->assertJsonPath('data.stats.top_of_round.top_player_name', 'Max Muster');
    }
}
