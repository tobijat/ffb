<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\AwardsPopupService;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use App\Services\MatchPopupService;
use App\Services\PlayerPopupService;
use App\Services\ProfilePopupService;
use Tests\TestCase;

class PopupApiTest extends TestCase
{
    private function actingAsFfbUser(int $userId = 544): void
    {
        $user = new WebUser;
        $user->user_id = $userId;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';

        $this->mock(FfbUserResolver::class, function ($mock) use ($user, $userId) {
            $mock->shouldReceive('findActive')->with($userId)->andReturn($user);
        });
    }

    public function test_profile_popup_requires_auth(): void
    {
        $this->getJson('/api/popups/user/12')->assertStatus(401);
    }

    public function test_profile_popup_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'user' => [
                'user_id' => 12,
                'user_ownprofile' => false,
                'user_nickname' => 'Rival',
                'user_fname' => null,
                'user_lname' => null,
                'user_name' => null,
                'user_gender' => 'm',
                'user_date_llogin' => '01.01.2024',
                'user_date_register' => '01.01.2020',
                'avatar_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                'photo_url' => '/images/ffb/profiles/photo/m_profile_na.png',
                'user_details_city' => 'Wien',
                'user_details_website' => null,
                'user_details_phone' => null,
                'user_perm_profile' => false,
                'favourite_team' => [
                    'id' => 1,
                    'name' => 'Rapid',
                    'nationality' => 'aut',
                ],
                'own_team' => null,
            ],
            'participations' => [
                [
                    'game_id' => 26,
                    'game_title' => 'Testliga',
                    'game_symbol' => 'x.png',
                    'game_archive' => false,
                    'score_rm' => 'wc',
                    'score_wc' => 10,
                    'score_points' => 100,
                    'score_start' => '01.08.25',
                    'score_end' => 'jetzt',
                    'user_rank' => 2,
                ],
            ],
        ];

        $this->mock(ProfilePopupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('forUser')->once()->with(544, 12)->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/user/12')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user.user_nickname', 'Rival')
            ->assertJsonPath('data.participations.0.user_rank', 2);
    }

    public function test_profile_popup_not_found(): void
    {
        $this->actingAsFfbUser();

        $this->mock(ProfilePopupService::class, function ($mock) {
            $mock->shouldReceive('forUser')->once()->with(544, 99999)->andReturn([
                'ok' => false,
                'status' => 404,
                'error' => 'User not found',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/user/99999')
            ->assertStatus(404)
            ->assertJsonPath('error', 'User not found');
    }

    public function test_awards_popup_requires_auth(): void
    {
        $this->getJson('/api/popups/user/12/awards')->assertStatus(401);
    }

    public function test_awards_popup_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'user_id' => 12,
            'groups' => [
                [
                    'id' => 1,
                    'name' => 'Weltmeister',
                    'description' => 'Liga gewinnen',
                    'image_url' => '/images/ffb/awards/wm.png',
                    'ranks' => [
                        [
                            'id' => 10,
                            'name' => 'Bronze',
                            'description' => '1x gewinnen',
                            'rank' => 1,
                            'finished' => true,
                            'image' => 'awards/wm_bronze.png',
                            'image_url' => '/images/ffb/awards/wm_bronze.png',
                        ],
                        [
                            'id' => 11,
                            'name' => 'Silber',
                            'description' => '3x gewinnen',
                            'rank' => 2,
                            'finished' => false,
                            'image' => 'awards/wm_silver.png',
                            'image_url' => '/images/ffb/awards/wm_silver_disabled.png',
                        ],
                    ],
                ],
            ],
        ];

        $this->mock(AwardsPopupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('forUser')->once()->with(12)->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/user/12/awards')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.groups.0.name', 'Weltmeister')
            ->assertJsonPath('data.groups.0.ranks.1.finished', false)
            ->assertJsonPath('data.groups.0.ranks.1.image_url', '/images/ffb/awards/wm_silver_disabled.png');
    }

    public function test_match_popup_requires_auth(): void
    {
        $this->getJson('/api/popups/match/100')->assertStatus(401);
    }

    public function test_match_popup_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'match' => [
                'match_id' => 100,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_hometeam_name' => 'Home',
                'match_guestteam_name' => 'Away',
                'match_hometeam_nationality' => 'aut',
                'match_guestteam_nationality' => 'ger',
                'match_hometeam_score' => 2,
                'match_guestteam_score' => 1,
                'match_hometeam_score_penalty' => null,
                'match_guestteam_score_penalty' => null,
                'match_minutes' => 90,
                'match_date' => '01.09.2025',
                'match_matchround_id' => 10,
                'match_matchround_name' => 'Runde 1',
                'match_game_title' => 'Testliga',
            ],
            'hometeam_players' => [],
            'guestteam_players' => [],
            'goals' => [
                [
                    'goal_minute' => 12,
                    'goal_playerteam_id' => 55,
                    'goal_team_id' => 1,
                    'goal_team_name' => 'Home',
                    'goal_player_name' => 'Max Mustermann',
                    'goal_owngoal' => false,
                    'goal_penalty' => false,
                    'goal_penaltyshootout' => false,
                ],
            ],
            'psgoals' => [],
            'prev_matches' => [],
        ];

        $this->mock(MatchPopupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('forMatch')->once()->with(100)->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/match/100')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.match.match_hometeam_name', 'Home')
            ->assertJsonPath('data.goals.0.goal_minute', 12);
    }

    public function test_match_popup_not_found(): void
    {
        $this->actingAsFfbUser();

        $this->mock(MatchPopupService::class, function ($mock) {
            $mock->shouldReceive('forMatch')->once()->with(99999)->andReturn([
                'ok' => false,
                'status' => 404,
                'error' => 'Match not found',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/match/99999')
            ->assertStatus(404)
            ->assertJsonPath('error', 'Match not found');
    }

    public function test_player_popup_requires_auth(): void
    {
        $this->getJson('/api/popups/player/55')->assertStatus(401);
        $this->getJson('/api/popups/player/55/rounds/10')->assertStatus(401);
    }

    public function test_player_popup_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'player' => [
                'playerteam_id' => 55,
                'player_fname' => 'Max',
                'player_lname' => 'Mustermann',
                'player_name' => 'Max Mustermann',
                'player_nationality' => 'aut',
                'player_team_name' => 'Rapid',
                'player_team_nationality' => 'aut',
                'player_team_id' => 1,
                'player_picture_url' => '/images/ffb/players/image_na.gif',
            ],
            'pricemode' => 'dynamic',
            'stats' => [
                'num_lineups' => 12,
                'sum_score' => 40,
                'sum_goals' => 3,
                'sum_assists' => 1,
                'sum_minutes' => 270,
                'sum_cards_y' => 1,
                'sum_cards_yr' => 0,
                'sum_cards_r' => 0,
                'av_score' => 13.33,
                'av_goals' => 1,
                'av_assists' => 0.33,
                'av_minutes' => 90,
                'match_count_total' => 5,
                'match_count_played' => 3,
                'match_count_percent' => 60,
            ],
            'matchrounds' => [],
            'pastmatches' => [],
        ];

        $this->mock(PlayerPopupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('forPlayerteam')->once()->with(544, 55)->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/player/55')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.player.player_name', 'Max Mustermann')
            ->assertJsonPath('data.stats.num_lineups', 12);
    }

    public function test_player_round_popup_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'playerteam_id' => 55,
            'matchround_id' => 10,
            'pricemode' => 'constant',
            'played' => true,
            'player' => [
                'playerteam_id' => 55,
                'player_fname' => 'Max',
                'player_lname' => 'Mustermann',
                'player_name' => 'Max Mustermann',
                'player_nationality' => 'aut',
                'player_team_name' => 'Rapid',
                'player_team_nationality' => 'aut',
                'player_picture_url' => '/images/ffb/players/image_na.gif',
            ],
            'stats' => [
                'playerstats_goals' => 1,
                'playerstats_assists' => 0,
                'playerstats_minutes' => 90,
                'playerstats_minute_in' => 1,
                'playerstats_minute_out' => 90,
                'playerstats_cards' => 'n',
                'playerstats_owngoals' => 0,
                'playerstats_penaltiessaved' => 0,
                'playerstats_penaltieslost' => 0,
                'playerstats_penaltyshootout_lost' => 0,
                'playerstats_penaltyshootout_hit' => 0,
                'playerstats_penaltyshootout_save' => 0,
                'playerstats_oppgoals' => 1,
                'playerstats_player_oppgoals' => 1,
                'playerstats_player_oppgoals_string' => null,
                'playerstats_score_goals' => 5,
                'playerstats_score_assists' => 0,
                'playerstats_score_minutes' => 2,
                'playerstats_score_cards' => 0,
                'playerstats_score_owngoals' => 0,
                'playerstats_score_penaltiessaved' => 0,
                'playerstats_score_penaltieslost' => 0,
                'playerstats_score_penaltyshootout_lost' => 0,
                'playerstats_score_penaltyshootout_hit' => 0,
                'playerstats_score_penaltyshootout_save' => 0,
                'playerstats_score_oppgoals' => 0,
                'playerstats_score_nooppgoals' => 0,
                'playerstats_score' => 7,
            ],
        ];

        $this->mock(PlayerPopupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('forRound')->once()->with(544, 55, 10)->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/player/55/rounds/10')
            ->assertOk()
            ->assertJsonPath('data.played', true)
            ->assertJsonPath('data.stats.playerstats_score', 7);
    }

    public function test_player_chart_requires_auth(): void
    {
        $this->getJson('/api/popups/player/55/chart')->assertStatus(401);
        $this->getJson('/api/popups/player/55/prices')->assertStatus(401);
    }

    public function test_player_chart_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(PlayerPopupService::class, function ($mock) {
            $mock->shouldReceive('chart')->once()->with(55, 26)->andReturn([
                'ok' => true,
                'data' => [
                    'game_id' => 26,
                    'player' => ['playerteam_id' => 55, 'player_name' => 'Max'],
                    'rounds' => [
                        [
                            'matchround_id' => 1,
                            'matchround_title' => 'R1',
                            'played' => true,
                            'score' => 5,
                            'minutes' => 90,
                            'goals' => 1,
                            'assists' => 0,
                            'cards' => 'n',
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/player/55/chart?game_id=26')
            ->assertOk()
            ->assertJsonPath('data.game_id', 26)
            ->assertJsonPath('data.rounds.0.score', 5);
    }

    public function test_player_chart_requires_game_id(): void
    {
        $this->actingAsFfbUser();

        $this->mock(PlayerPopupService::class, function ($mock) {
            $mock->shouldReceive('chart')->once()->with(55, 0)->andReturn([
                'ok' => false,
                'status' => 422,
                'error' => 'game_id is required',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/player/55/chart')
            ->assertStatus(422)
            ->assertJsonPath('error', 'game_id is required');
    }

    public function test_player_prices_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $this->mock(PlayerPopupService::class, function ($mock) {
            $mock->shouldReceive('prices')->once()->with(55, 26)->andReturn([
                'ok' => true,
                'data' => [
                    'game_id' => 26,
                    'player' => ['playerteam_id' => 55, 'player_name' => 'Max'],
                    'points' => [
                        [
                            'matchround_id' => 1,
                            'matchround_title' => 'R1',
                            'price' => 4.5,
                            'power' => 3.2,
                            'av_power' => 2.1,
                        ],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/player/55/prices?game_id=26')
            ->assertOk()
            ->assertJsonPath('data.points.0.price', 4.5);
    }
}
