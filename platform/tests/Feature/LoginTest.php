<?php

namespace Tests\Feature;

use App\Services\FfbAuth;
use App\Services\LoginService;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_success_sets_session_and_returns_destination(): void
    {
        $this->mock(LoginService::class, function ($mock) {
            $mock->shouldReceive('attempt')
                ->once()
                ->andReturnUsing(function ($nickname, $password, $request, $destination) {
                    app(FfbAuth::class)->login(544, $request);

                    return [
                        'ok' => true,
                        'user_id' => 544,
                        'destination' => '/platform/',
                    ];
                });
        });

        $response = $this->postJson('/login', [
            'user_nickname' => 'player1',
            'user_password' => 'pass123',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 200,
                'destination' => '/platform/',
                'user_id' => 544,
            ]);

        $this->assertSame(544, (int) session(FfbAuth::SESSION_USER_ID));
    }

    public function test_login_rejects_with_errors(): void
    {
        $this->mock(LoginService::class, function ($mock) {
            $mock->shouldReceive('attempt')
                ->once()
                ->andReturn([
                    'ok' => false,
                    'errors' => ['Das Passwort ist falsch.'],
                ]);
        });

        $response = $this->postJson('/login', [
            'user_nickname' => 'player1',
            'user_password' => 'wrong',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('status', 500)
            ->assertJsonPath('errors.0', 'Das Passwort ist falsch.');

        $this->assertSame(0, (int) session(FfbAuth::SESSION_USER_ID, 0));
    }

    public function test_logout_clears_session(): void
    {
        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/logout')
            ->assertRedirect(route('start'));

        $this->assertSame(0, (int) session(FfbAuth::SESSION_USER_ID, 0));
    }
}
