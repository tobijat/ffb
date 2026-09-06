<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\LineupController;
use App\Http\Middleware\ResolveFfbUser;
use App\Models\WebUser;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use App\Services\LineupService;
use Illuminate\Http\Request;
use Tests\TestCase;

class LineupApiTest extends TestCase
{
    public function test_requires_authentication(): void
    {
        $response = $this->getJson('/api/lineup?matchround_id=1');

        $response->assertStatus(401)
            ->assertJsonPath('status', 401)
            ->assertJsonPath('error', 'Authentication required (login session)');
    }

    public function test_accepts_laravel_session(): void
    {
        $user = new WebUser;
        $user->user_id = 544;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';

        $this->mock(FfbUserResolver::class, function ($mock) use ($user) {
            $mock->shouldReceive('findActive')->once()->with(544)->andReturn($user);
        });

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

        $response = $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/lineup?matchround_id=280');

        $response->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user_id', 544);
    }

    public function test_requires_matchround_id(): void
    {
        $this->withoutMiddleware(ResolveFfbUser::class);

        $request = Request::create('/api/lineup', 'GET');
        $request->attributes->set('ffb_user_id', 1);

        $response = $this->app->make(LineupController::class)->show($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            'status' => 422,
            'error' => 'matchround_id is required',
        ], $response->getData(true));
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

        $request = Request::create('/api/lineup?matchround_id=280', 'GET');
        $request->attributes->set('ffb_user_id', 544);

        $response = $this->app->make(LineupController::class)->show($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'status' => 200,
            'data' => $payload,
        ], $response->getData(true));
    }

    public function test_store_requires_matchround_id(): void
    {
        $this->withoutMiddleware(ResolveFfbUser::class);

        $request = Request::create('/api/lineup', 'POST', [
            'playerteam_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        ]);
        $request->attributes->set('ffb_user_id', 544);

        $response = $this->app->make(LineupController::class)->store($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            'status' => 422,
            'error' => 'matchround_id is required',
        ], $response->getData(true));
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
                ->with(544, 280, $ids)
                ->andReturn([
                    'ok' => true,
                    'created' => false,
                    'message' => 'Deine Aufstellung wurde aktualisiert!',
                    'data' => $payload,
                ]);
        });

        $request = Request::create('/api/lineup', 'POST', [
            'matchround_id' => 280,
            'playerteam_ids' => $ids,
        ]);
        $request->attributes->set('ffb_user_id', 544);

        $response = $this->app->make(LineupController::class)->store($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'status' => 200,
            'message' => 'Deine Aufstellung wurde aktualisiert!',
            'data' => $payload,
        ], $response->getData(true));
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

        $request = Request::create('/api/lineup', 'POST', [
            'matchround_id' => 280,
            'lineup' => '1,2,3',
        ]);
        $request->attributes->set('ffb_user_id', 544);

        $response = $this->app->make(LineupController::class)->store($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            'status' => 422,
            'error' => 'Invalid lineup: exactly 11 players are required',
        ], $response->getData(true));
    }

    public function test_options_and_matchround_require_auth(): void
    {
        $this->getJson('/api/lineup/options')->assertStatus(401);
        $this->getJson('/api/lineup/matchround')->assertStatus(401);
        $this->getJson('/api/lineup/teams/1/players?matchround_id=1')->assertStatus(401);
    }

    public function test_options_returns_payload(): void
    {
        $user = new WebUser;
        $user->user_id = 544;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';

        $this->mock(FfbUserResolver::class, function ($mock) use ($user) {
            $mock->shouldReceive('findActive')->with(544)->andReturn($user);
        });

        $this->mock(LineupService::class, function ($mock) {
            $mock->shouldReceive('options')->once()->with(544)->andReturn([
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

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/lineup/options')
            ->assertOk()
            ->assertJsonPath('data.lineup_max_players', 11);
    }

    public function test_page_redirects_guests(): void
    {
        $this->get('/lineup')
            ->assertRedirect(route('start', ['destination' => '/platform/lineup']));
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
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_admin' => false,
                    ],
                    'selected_game_id' => 26,
                    'game_over' => false,
                    'navigation' => [
                        ['symbol' => 'nav_team.png', 'name' => 'Aufstellung', 'link' => '/platform/lineup', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/lineup')
            ->assertOk()
            ->assertSee('js/lineup.js', false)
            ->assertSee('team_selection', false)
            ->assertSee('soccer-field', false);
    }
}
