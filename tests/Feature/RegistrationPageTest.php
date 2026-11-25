<?php

namespace Tests\Feature;

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
                    ],
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
            ->assertDontSee('user_fname', false)
            ->assertDontSee('user_lname', false)
            ->assertDontSee('user_nationality', false)
            ->assertDontSee('Geburtsdatum', false)
            ->assertSee('js/registration.js', false)
            ->assertSee('user-card-login', false)
            ->assertSee('Fantasy Football mit echten Ligen', false)
            ->assertSee('js/start.js', false);
    }

    public function test_registration_page_redirects_logged_in_users(): void
    {
        $this->actingAsFfbUser(544)
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
                ],
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'ok' => true,
                'data' => [
                    'form' => [
                        'user_nickname' => 'neu',
                        'user_email' => 'neu@example.com',
                        'user_email_val' => 'neu@example.com',
                    ],
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
            ->assertSee('FFB_REGISTRATION_ERRORS', false)
            ->assertSee('Du musst die Bedingungen gelesen und akzeptiert haben!', false)
            ->assertSee('registration-form', false)
            ->assertDontSee('account-flash-error', false);
    }

    public function test_activation_redirects_to_start_with_message(): void
    {
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('activateRegistration')->once()->andReturn([
                'ok' => true,
                'message' => 'Account aktiviert.',
            ]);
        });

        $this->get('/registration/activate?id=1&hash=abc&expires=1&signature=sig')
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', 'Account aktiviert.');
    }

    public function test_registration_store_success_redirects_logged_in(): void
    {
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('register')->once()->andReturn([
                'ok' => true,
                'message' => 'Account angelegt.',
                'form' => [],
                'logged_in' => true,
            ]);
        });

        $this->post('/registration', [
            'user_nickname' => 'neu',
            'user_email' => 'neu@example.com',
        ])
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', 'Account angelegt.')
            ->assertSessionHas('show_activation_modal', true);
    }

    public function test_password_reset_request_returns_json(): void
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

