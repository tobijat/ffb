<?php

namespace Tests\Feature;

use App\Services\FfbAuth;
use App\Services\RegistrationService;
use App\Services\StartPageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationPageTest extends TestCase
{
    public function test_registration_page_renders_for_guests(): void
    {
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->withNoArgs()->andReturn([
                'ok' => true,
                'data' => [
                    'form' => [
                        'user_nickname' => '',
                        'user_email' => '',
                        'user_email_val' => '',
                        'user_fname' => '',
                        'user_lname' => '',
                        'user_birth_day' => 0,
                        'user_birth_month' => 0,
                        'user_birth_year' => 0,
                        'user_nationality' => '',
                    ],
                    'countries' => ['AUT' => 'Österreich'],
                    'birth_years' => [2010, 1990],
                    'navigation' => [
                        ['symbol' => 'nav_user.png', 'name' => 'Registrieren', 'link' => '/registration', 'style' => 'big'],
                    ],
                    'tos_url' => '/resource/Registrierung.pdf',
                ],
            ]);
        });

        $this->get('/registration')
            ->assertOk()
            ->assertSee('Account anlegen', false)
            ->assertSee('Benutzername', false)
            ->assertSee('js/registration.js', false)
            ->assertSee('user-card-login', false)
            ->assertSee('Fantasy Football mit echten Ligen', false)
            ->assertSee('js/start.js', false);
    }

    public function test_registration_page_redirects_logged_in_users(): void
    {
        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/registration')
            ->assertRedirect(route('profile', ['tab' => 'account']));
    }

    public function test_registration_store_shows_validation_errors(): void
    {
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('register')->once()->andReturn([
                'ok' => false,
                'status' => 422,
                'errors' => ['Du musst die Bedingungen gelesen und akzeptiert haben!'],
                'form' => [
                    'user_nickname' => 'neu',
                    'user_email' => 'neu@example.com',
                    'user_email_val' => 'neu@example.com',
                    'user_fname' => '',
                    'user_lname' => '',
                    'user_birth_day' => 0,
                    'user_birth_month' => 0,
                    'user_birth_year' => 0,
                    'user_nationality' => '',
                ],
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'ok' => true,
                'data' => [
                    'form' => [
                        'user_nickname' => 'neu',
                        'user_email' => 'neu@example.com',
                        'user_email_val' => 'neu@example.com',
                        'user_fname' => '',
                        'user_lname' => '',
                        'user_birth_day' => 0,
                        'user_birth_month' => 0,
                        'user_birth_year' => 0,
                        'user_nationality' => '',
                    ],
                    'countries' => [],
                    'birth_years' => [],
                    'navigation' => [],
                    'tos_url' => '/resource/Registrierung.pdf',
                ],
            ]);
        });

        $this->post('/registration', [
            'user_nickname' => 'neu',
            'user_email' => 'neu@example.com',
        ])
            ->assertOk()
            ->assertSee('Du musst die Bedingungen gelesen und akzeptiert haben!', false);
    }

    public function test_activation_redirects_to_start_with_message(): void
    {
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('activate')->once()->with('abc-1', 'registration')->andReturn([
                'ok' => true,
                'message' => 'Account aktiviert.',
            ]);
        });

        $this->get('/registration/activate?id=abc-1')
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', 'Account aktiviert.');
    }

    public function test_password_reset_returns_json(): void
    {
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('requestPasswordReset')->once()->andReturn([
                'ok' => true,
                'message' => 'Wenn ein Account mit diesen Angaben existiert, hast du eine E-Mail mit einem Link zum Zurücksetzen des Passworts erhalten.',
            ]);
        });

        $this->postJson('/registration/password', [
            'identifier' => 'tester@example.com',
        ])
            ->assertOk()
            ->assertJson([
                'status' => 200,
            ]);
    }

    public function test_invalid_password_reset_signature_redirects_to_start_with_message(): void
    {
        $this->get('/password/reset/1?expires=1&signature=invalid')
            ->assertRedirect(route('start'))
            ->assertSessionHas(
                'account_message',
                '<strong>Es sind Fehler aufgetreten:</strong><br>Der Link ist ungültig oder abgelaufen.'
            );
    }

    public function test_password_reset_link_redirects_to_clean_url(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        Schema::dropIfExists('web_user');
        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
            $table->string('user_password')->default('');
        });
        DB::table('web_user')->insert([
            'user_id' => 7,
            'user_nickname' => 'resetter',
            'user_email' => 'resetter@example.com',
            'user_password' => 'hash',
        ]);

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

        $signedUrl = URL::temporarySignedRoute(
            'password.reset',
            now()->addDay(),
            ['user' => 7],
        );

        $this->get($signedUrl)
            ->assertRedirect(route('password.reset', ['user' => 7]));

        $this->get(route('password.reset', ['user' => 7]))
            ->assertOk()
            ->assertSee('Passwort zurücksetzen', false)
            ->assertSee('Fantasy Football mit echten Ligen', false)
            ->assertSee('user-card-login', false)
            ->assertSee('nav_login.png', false)
            ->assertDontSee('Community', false)
            ->assertSee('resetter', false)
            ->assertSee(route('password.reset.update', ['user' => 7]), false)
            ->assertDontSee('id="login"', false);

        Schema::dropIfExists('web_user');
    }

    public function test_password_reset_clean_url_without_session_is_rejected(): void
    {
        $this->get(route('password.reset', ['user' => 1]))
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message');
    }

    public function test_password_reset_mail_keeps_plain_ampersands_in_url(): void
    {
        $resetUrl = 'http://example.com/password/reset/1?expires=1791825974&signature=abc123';
        $body = (new \App\Mail\PasswordResetMail('tester', $resetUrl, 'example.com'))->render();

        $this->assertStringContainsString($resetUrl, $body);
        $this->assertStringNotContainsString('&amp;', $body);
    }
}
