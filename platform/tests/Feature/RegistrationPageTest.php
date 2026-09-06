<?php

namespace Tests\Feature;

use App\Services\FfbAuth;
use App\Services\RegistrationService;
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
                        ['symbol' => 'nav_user.png', 'name' => 'Registrieren', 'link' => '/platform/registration', 'style' => 'big'],
                    ],
                    'recaptcha_enabled' => false,
                    'recaptcha_site_key' => '',
                    'tos_url' => '/resource/Registrierung.pdf',
                ],
            ]);
        });

        $this->get('/registration')
            ->assertOk()
            ->assertSee('Account anlegen', false)
            ->assertSee('Benutzername', false)
            ->assertSee('js/registration.js', false);
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
                    'recaptcha_enabled' => false,
                    'recaptcha_site_key' => '',
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
}
