<?php

namespace Tests\Feature;

use App\Mail\PasswordResetMail;
use App\Models\WebUser;
use App\Services\FfbPassword;
use App\Services\StartPageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        config([
            'auth.passwords.users.table' => 'web_user_password_reset_tokens',
            'auth.passwords.users.throttle' => 0,
        ]);

        Schema::dropIfExists('web_user_password_reset_tokens');
        Schema::dropIfExists('web_user');

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
            $table->string('user_password')->default('');
            $table->string('user_status')->default('active');
            $table->string('user_date_laction')->nullable();
        });

        Schema::create('web_user_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('web_user_password_reset_tokens');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    public function test_request_sends_mail_for_known_user_and_stays_generic(): void
    {
        Mail::fake();

        DB::table('web_user')->insert([
            'user_id' => 7,
            'user_nickname' => 'resetter',
            'user_email' => 'resetter@example.com',
            'user_password' => 'old',
            'user_status' => 'active',
        ]);

        $this->postJson('/registration/password', [
            'identifier' => 'resetter',
        ])
            ->assertOk()
            ->assertJsonPath(
                'message',
                'Wenn ein Account mit diesen Angaben existiert, hast du eine E-Mail mit einem Link zum Zurücksetzen des Passworts erhalten.'
            );

        Mail::assertSent(PasswordResetMail::class, function (PasswordResetMail $mail) {
            return $mail->nickname === 'resetter'
                && str_contains($mail->resetUrl, '/password/reset/')
                && str_contains($mail->resetUrl, 'email=');
        });
    }

    public function test_request_is_generic_for_unknown_identifier(): void
    {
        Mail::fake();

        $this->postJson('/registration/password', [
            'identifier' => 'nobody@example.com',
        ])
            ->assertOk()
            ->assertJsonPath(
                'message',
                'Wenn ein Account mit diesen Angaben existiert, hast du eine E-Mail mit einem Link zum Zurücksetzen des Passworts erhalten.'
            );

        Mail::assertNothingSent();
    }

    public function test_invalid_token_redirects_to_start(): void
    {
        $this->get(route('password.reset', [
            'token' => 'invalid-token',
            'email' => 'resetter@example.com',
        ]))
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message');
    }

    public function test_valid_token_shows_dedicated_form(): void
    {
        $user = $this->seedUser();
        $token = Password::broker()->createToken($user);

        $this->mock(StartPageService::class, function ($mock) {
            $mock->shouldReceive('payload')->andReturn([
                'stats' => [
                    'users_total' => 1,
                    'users_today' => 0,
                    'lineups' => 0,
                    'matchrounds_played' => 0,
                    'score_avg' => 0.0,
                ],
                'leagues' => [
                    [
                        'league_id' => 1,
                        'league_title' => 'Demo Liga',
                        'symbol_url' => '/images/ffb/leagues/demo.png',
                    ],
                ],
                'results' => [],
            ]);
        });

        $this->get(route('password.reset', [
            'token' => $token,
            'email' => 'resetter@example.com',
        ]))
            ->assertOk()
            ->assertSee('Passwort zurücksetzen', false)
            ->assertSee('Fantasy Football mit echten Ligen', false)
            ->assertSee('user-card-login', false)
            ->assertSee('resetter', false)
            ->assertSee('name="user_password"', false)
            ->assertSee('name="user_password_val"', false)
            ->assertSee(route('password.reset.update', ['token' => $token]), false)
            ->assertDontSee('id="login"', false);
    }

    public function test_opening_reset_link_logs_out_existing_session(): void
    {
        $user = $this->seedUser();
        $token = Password::broker()->createToken($user);

        $this->mock(StartPageService::class, function ($mock) {
            $mock->shouldReceive('payload')->andReturn([
                'stats' => [
                    'users_total' => 1,
                    'users_today' => 0,
                    'lineups' => 0,
                    'matchrounds_played' => 0,
                    'score_avg' => 0.0,
                ],
                'leagues' => [],
                'results' => [],
            ]);
        });

        $this->actingAs($user)
            ->get(route('password.reset', [
                'token' => $token,
                'email' => 'resetter@example.com',
            ]))
            ->assertOk()
            ->assertSee('Passwort zurücksetzen: ', false)
            ->assertSee('resetter', false);

        $this->assertGuest();
    }

    public function test_reset_updates_password_logs_in_and_consumes_token(): void
    {
        $user = $this->seedUser();
        $token = Password::broker()->createToken($user);

        $this->post(route('password.reset.update', ['token' => $token]), [
            'email' => 'resetter@example.com',
            'user_password' => 'secret1',
            'user_password_val' => 'secret1',
        ])
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', 'Dein Passwort wurde geändert.');

        $this->assertAuthenticated();
        $this->assertSame(7, (int) Auth::id());

        $stored = (string) DB::table('web_user')->where('user_id', 7)->value('user_password');
        $this->assertTrue(app(FfbPassword::class)->verify('secret1', $stored));

        $this->assertSame(0, DB::table('web_user_password_reset_tokens')->count());

        $this->get(route('password.reset', [
            'token' => $token,
            'email' => 'resetter@example.com',
        ]))
            ->assertRedirect(route('start'));
    }

    public function test_reset_validation_errors_rerender_form(): void
    {
        $user = $this->seedUser();
        $token = Password::broker()->createToken($user);

        $this->mock(StartPageService::class, function ($mock) {
            $mock->shouldReceive('payload')->andReturn([
                'stats' => [
                    'users_total' => 1,
                    'users_today' => 0,
                    'lineups' => 0,
                    'matchrounds_played' => 0,
                    'score_avg' => 0.0,
                ],
                'leagues' => [],
                'results' => [],
            ]);
        });

        $this->from(route('password.reset', [
            'token' => $token,
            'email' => 'resetter@example.com',
        ]))->post(route('password.reset.update', ['token' => $token]), [
            'email' => 'resetter@example.com',
            'user_password' => 'abc',
            'user_password_val' => 'abc',
        ])
            ->assertOk()
            ->assertSee('Passwort: min. Länge ist 5, max. Länge ist 32!', false)
            ->assertSee('user-card-login', false);

        $this->assertGuest();
        $this->assertSame(1, DB::table('web_user_password_reset_tokens')->count());
    }

    public function test_password_reset_mail_keeps_plain_ampersands_in_url(): void
    {
        $resetUrl = 'http://example.com/password/reset/abc123?email=resetter%40example.com';
        $body = (new PasswordResetMail('tester', $resetUrl, 'example.com'))->render();

        $this->assertStringContainsString($resetUrl, $body);
        $this->assertStringNotContainsString('&amp;', $body);
        $this->assertStringContainsString('60 Minuten', $body);
    }

    private function seedUser(): WebUser
    {
        DB::table('web_user')->insert([
            'user_id' => 7,
            'user_nickname' => 'resetter',
            'user_email' => 'resetter@example.com',
            'user_password' => 'old-hash',
            'user_status' => 'active',
        ]);

        return WebUser::query()->findOrFail(7);
    }
}
