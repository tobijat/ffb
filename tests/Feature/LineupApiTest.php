<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\FfbUserResolver;
use App\Services\LineupService;
use Tests\TestCase;

class LineupApiTest extends TestCase
{
    protected function actingAsActiveUser(int $userId = 544): static
    {
        $user = new WebUser;
        $user->user_id = $userId;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';
        $user->exists = true;

        $this->mock(FfbUserResolver::class, function ($mock) use ($user, $userId) {
            $mock->shouldReceive('findActive')->with($userId)->andReturn($user);
        });

        return $this->actingAs($user);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->getJson('/api/lineup?matchround_id=1');

        $response->assertStatus(401)
            ->assertJsonPath('status', 401)
            ->assertJsonPath('error', 'Du wurdest ausgeloggt. Bitte Seite neu laden um dich erneut einzuloggen.');
    }

    public function test_accepts_laravel_session(): void
    {
        $this->mock(LineupService::class, function ($mock) {
            $mock->shouldReceive('getForRound')
                ->once()
                ->with(544, 280)
                ->andReturn([
                    'user_id' => 544,
                    'user_nickname' => 'tester',
                    'matchround_id' => 280,
                    'userteam' => null,
                    'players' => [],
                ]);
        });

        $response = $this->actingAsActiveUser(544)
            ->getJson('/api/lineup?matchround_id=280');

        $response->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user_id', 544);
    }

    public function test_rejects_user_id_query_without_session(): void
    {
        $this->getJson('/api/lineup?matchround_id=280&user_id=544')
            ->assertStatus(401)
            ->assertJsonPath('error', 'Du wurdest ausgeloggt. Bitte Seite neu laden um dich erneut einzuloggen.');
    }

    public function test_requires_matchround_id(): void
    {
        $this->actingAsActiveUser(1)
            ->getJson('/api/lineup')
            ->assertStatus(422)
            ->assertExactJson([
                'status' => 422,
                'error' => 'matchround_id is required',
            ]);
    }

    public function test_returns_lineup_payload(): void
    {
        $payload = [
            'user_id' => 544,
            'user_nickname' => 'tester',
            'matchround_id' => 280,
            'userteam' => null,
            'players' => [],
        ];

        $this->mock(LineupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('getForRound')
                ->once()
                ->with(544, 280)
                ->andReturn($payload);
        });

        $this->actingAsActiveUser(544)
            ->getJson('/api/lineup?matchround_id=280')
            ->assertOk()
            ->assertExactJson([
                'status' => 200,
                'data' => $payload,
            ]);
    }

    public function test_store_requires_matchround_id(): void
    {
        $this->actingAsActiveUser(544)
            ->postJson('/api/lineup', [
                'playerteam_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            ])
            ->assertStatus(422)
            ->assertExactJson([
                'status' => 422,
                'error' => 'matchround_id is required',
            ]);
    }

    public function test_store_saves_lineup(): void
    {
        $payload = [
            'user_id' => 544,
            'user_nickname' => 'tester',
            'matchround_id' => 280,
            'userteam' => ['userteam_id' => 1],
            'players' => [],
        ];

        $ids = [324, 6398, 1557, 7328, 940, 3494, 3391, 5106, 8628, 8333, 2648];

        $this->mock(LineupService::class, function ($mock) use ($payload, $ids) {
            $mock->shouldReceive('saveForRound')
                ->once()
                ->with(544, 280, $ids, [])
                ->andReturn([
                    'ok' => true,
                    'created' => false,
                    'message' => 'Deine Aufstellung wurde aktualisiert!',
                    'data' => $payload,
                ]);
        });

        $this->actingAsActiveUser(544)
            ->postJson('/api/lineup', [
                'matchround_id' => 280,
                'playerteam_ids' => $ids,
            ])
            ->assertOk()
            ->assertExactJson([
                'status' => 200,
                'message' => 'Deine Aufstellung wurde aktualisiert!',
                'data' => $payload,
            ]);
    }

    public function test_store_rejects_invalid_lineup(): void
    {
        $this->mock(LineupService::class, function ($mock) {
            $mock->shouldReceive('saveForRound')
                ->once()
                ->andReturn([
                    'ok' => false,
                    'status' => 422,
                    'error' => 'Invalid lineup: exactly 11 players are required',
                ]);
        });

        $this->actingAsActiveUser(544)
            ->postJson('/api/lineup', [
                'matchround_id' => 280,
                'lineup' => '1,2,3',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                'status' => 422,
                'error' => 'Invalid lineup: exactly 11 players are required',
            ]);
    }

    public function test_options_and_matchround_require_auth(): void
    {
        $this->getJson('/api/lineup/options')->assertStatus(401);
        $this->getJson('/api/lineup/matchround')->assertStatus(401);
        $this->getJson('/api/lineup/teams/1/players?matchround_id=1')->assertStatus(401);
    }

    public function test_options_returns_payload(): void
    {
        $this->mock(LineupService::class, function ($mock) {
            $mock->shouldReceive('options')->once()->with(544, 0)->andReturn([
                'ok' => true,
                'data' => [
                    'lineup_max_players' => 11,
                    'lineup_max_credits' => 50.0,
                    'lineup_max_players_team' => 3,
                    'lineup_min_g' => 1,
                    'lineup_max_g' => 1,
                    'lineup_min_d' => 3,
                    'lineup_max_d' => 5,
                    'lineup_min_m' => 3,
                    'lineup_max_m' => 5,
                    'lineup_min_s' => 1,
                    'lineup_max_s' => 3,
                    'game_pricemode' => 'constant',
                ],
            ]);
        });

        $this->actingAsActiveUser(544)
            ->getJson('/api/lineup/options')
            ->assertOk()
            ->assertJsonPath('data.lineup_max_players', 11);
    }

    public function test_page_redirects_guests(): void
    {
        $this->get('/lineup')
            ->assertRedirect(route('start', ['destination' => 'lineup']));
    }

    public function test_page_renders_for_logged_in_user(): void
    {
        $this->mock(LineupService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                        'is_admin' => false,
                    ],
                    'selected_league_id' => 26,
                    'game_over' => false,
                    'navigation' => [
                        ['symbol' => 'nav_team.png', 'name' => 'Aufstellung', 'link' => '/lineup', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->actingAsFfbUser(544)
            ->get('/lineup')
            ->assertOk()
            ->assertSee('js/lineup.js', false)
            ->assertSee('matchlist', false)
            ->assertSee('soccer-field', false)
            ->assertDontSee('team_selection', false)
            ->assertDontSee('Spiele einblenden', false);
    }
}
