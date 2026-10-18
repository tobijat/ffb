<?php

namespace Tests\Feature;

use App\Services\AccountService;
use App\Services\FfbAuth;
use Tests\TestCase;

class AccountPageTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private function accountPayload(): array
    {
        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'tester',
                    'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                    'update_profile_nag' => false,
                ],
                'form' => [
                    'user_nickname' => 'tester',
                    'user_email' => 'tester@example.com',
                    'user_fname' => 'Max',
                    'user_lname' => 'Muster',
                    'user_nationality' => 'AUT',
                    'user_birth_day' => 1,
                    'user_birth_month' => 2,
                    'user_birth_year' => 1990,
                    'user_email_chg' => '',
                    'user_email_val_chg' => '',
                ],
                'countries' => ['AUT' => 'Österreich', 'GER' => 'Deutschland'],
                'birth_years' => [2010, 2009, 1990],
                'navigation' => [
                    ['symbol' => 'nav_help.png', 'name' => 'Regeln', 'link' => '/help', 'style' => 'big'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function profilePayload(): array
    {
        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'tester',
                    'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                    'update_profile_nag' => false,
                ],
                'form' => [
                    'user_fname' => 'Max',
                    'user_lname' => 'Muster',
                    'user_nationality' => 'AUT',
                    'user_birth_day' => 1,
                    'user_birth_month' => 2,
                    'user_birth_year' => 1990,
                    'user_details_city' => 'Wien',
                    'user_details_zip' => '1010',
                    'user_details_street' => '',
                    'user_details_phone' => '',
                    'user_details_website' => '',
                    'user_details_ffb_favourite_team' => 3,
                    'user_details_avatar' => 'avatar_na.png',
                    'user_details_avatar_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                    'user_permissions_ffb_mailservice_reminder' => 1,
                    'user_permissions_ffb_mailservice_info' => 0,
                    'user_permissions_ffb_visible_profile' => 0,
                ],
                'teams' => [
                    ['team_id' => 3, 'team_name' => 'Rapid', 'team_nationality' => 'AUT'],
                ],
                'avatar_shirts' => [
                    [
                        'team_id' => 3,
                        'team_name' => 'Rapid',
                        'path' => 'shirts/rapid-abcd/aut.svg',
                        'url' => '/images/ffb/shirts/rapid-abcd/aut.svg',
                    ],
                ],
                'navigation' => [
                    ['symbol' => 'nav_help.png', 'name' => 'Regeln', 'link' => '/help', 'style' => 'big'],
                ],
            ],
        ];
    }

    public function test_account_page_redirects_guests(): void
    {
        $this->get('/account')
            ->assertRedirect(route('start', ['destination' => 'profile?tab=account']));
    }

    public function test_account_page_redirects_to_profile_tab(): void
    {
        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/account')
            ->assertRedirect(route('profile', ['tab' => 'account']));
    }

    public function test_account_update_requires_auth(): void
    {
        $this->post('/account', [
            'user_tos' => 'user_tos_yes',
        ])->assertRedirect(route('start', ['destination' => 'profile?tab=account']));
    }

    public function test_account_update_shows_validation_errors(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('update')->once()->andReturn([
                'ok' => false,
                'status' => 422,
                'errors' => ['Du musst die Bedingungen gelesen und akzeptiert haben!'],
                'form' => [
                    'user_nickname' => 'tester',
                    'user_email' => 'tester@example.com',
                    'user_fname' => '',
                    'user_lname' => '',
                    'user_nationality' => '',
                    'user_birth_day' => 0,
                    'user_birth_month' => 0,
                    'user_birth_year' => 0,
                    'user_email_chg' => '',
                    'user_email_val_chg' => '',
                ],
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn($this->profilePayload());
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/account', [
                'user_fname' => 'Max',
            ])
            ->assertOk()
            ->assertSee('>Account</a>', false)
            ->assertSee('FFB_ACCOUNT_ERRORS', false)
            ->assertSee('Du musst die Bedingungen gelesen und akzeptiert haben!', false)
            ->assertDontSee('account-flash-error', false)
            ->assertSee('Bedingungen gelesen', false);
    }

    public function test_account_update_success_rerenders_with_message(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('update')->once()->andReturn([
                'ok' => true,
                'message' => '<b>Deine Daten wurden aktualisiert!</b>',
                'email_changed' => false,
                'form' => [
                    'user_nickname' => 'tester',
                    'user_email' => 'tester@example.com',
                    'user_fname' => 'Max',
                    'user_lname' => '',
                    'user_nationality' => 'AUT',
                    'user_birth_day' => 0,
                    'user_birth_month' => 0,
                    'user_birth_year' => 0,
                    'user_email_chg' => '',
                    'user_email_val_chg' => '',
                ],
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn($this->profilePayload());
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/account', [
                'user_fname' => 'Max',
                'user_tos' => 'user_tos_yes',
            ])
            ->assertOk()
            ->assertSee('Deine Daten wurden aktualisiert', false)
            ->assertSee('user-card-logout', false);
    }

    public function test_email_change_redirects_to_start_logged_out(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('update')->once()->andReturn([
                'ok' => true,
                'message' => 'E-Mail geändert',
                'email_changed' => true,
                'form' => [],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/account', [
                'user_email_chg' => 'new@example.com',
                'user_email_val_chg' => 'new@example.com',
                'user_tos' => 'user_tos_yes',
            ])
            ->assertRedirect(route('start'));
    }

    public function test_profile_page_redirects_guests(): void
    {
        $this->get('/profile')
            ->assertRedirect(route('start', ['destination' => 'profile']));
    }

    public function test_profile_page_renders_for_logged_in_user(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn($this->profilePayload());
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/profile')
            ->assertOk()
            ->assertSee('>Profil</a>', false)
            ->assertSee('Avatar', false)
            ->assertSee('>Account</a>', false)
            ->assertSee('Wien', false)
            ->assertSee('Rapid', false)
            ->assertSee('profile?tab=avatar', false)
            ->assertDontSee('user_details_photo', false)
            ->assertDontSee('type="file" name="user_details_avatar"', false)
            ->assertSee('Speichern', false)
            ->assertSee('Ausloggen', false)
            ->assertDontSee('>Account</span>', false)
            ->assertDontSee('>Profil</span>', false)
            ->assertSee('js/account.js', false);
    }

    public function test_profile_account_tab_renders(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn($this->profilePayload());
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/profile?tab=account')
            ->assertOk()
            ->assertSee('Benutzername', false)
            ->assertSee('tester@example.com', false)
            ->assertSee('account-tab ffb-tab is-active', false);
    }

    public function test_profile_avatar_tab_renders(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn($this->profilePayload());
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/profile?tab=avatar')
            ->assertOk()
            ->assertSee('user_details_avatar_shirt', false)
            ->assertSee('profile_section" value="avatar"', false)
            ->assertSee('Fertige Trikots', false)
            ->assertSee('Eigenes Trikot', false)
            ->assertSee('Neu generieren', false)
            ->assertSee('Als Avatar speichern', false)
            ->assertSee('profile_section" value="avatar_custom"', false)
            ->assertSee('tobijat.at/kit-kitchen/random.php', false)
            ->assertSee('account-tab ffb-tab is-active', false);
    }

    public function test_profile_avatar_custom_update_is_forwarded(): void
    {
        $form = $this->profilePayload()['data']['form'];
        $form['user_details_avatar'] = 'abcdef0123456789abcdef0123456789.svg';
        $form['user_details_avatar_url'] = '/images/ffb/profiles/avatar/abcdef0123456789abcdef0123456789.svg';

        $this->mock(AccountService::class, function ($mock) use ($form) {
            $mock->shouldReceive('updateProfile')->once()->andReturn([
                'ok' => true,
                'message' => '<b>Dein Avatar wurde gespeichert!</b>',
                'form' => $form,
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn([
                'ok' => true,
                'data' => array_merge($this->profilePayload()['data'], ['form' => $form]),
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/profile', [
                'profile_section' => 'avatar_custom',
                'avatar_svg' => '<svg xmlns="http://www.w3.org/2000/svg"><rect/></svg>',
            ])
            ->assertOk()
            ->assertSee('Dein Avatar wurde gespeichert', false);
    }

    public function test_profile_update_shows_success(): void
    {
        $form = $this->profilePayload()['data']['form'];
        $form['user_details_city'] = 'Graz';
        $form['user_details_zip'] = '8010';

        $this->mock(AccountService::class, function ($mock) use ($form) {
            $mock->shouldReceive('updateProfile')->once()->andReturn([
                'ok' => true,
                'message' => '<b>Deine Daten wurden aktualisiert!</b>',
                'form' => $form,
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn($this->accountPayload());
            $mock->shouldReceive('profilePayload')->once()->andReturn([
                'ok' => true,
                'data' => array_merge($this->profilePayload()['data'], ['form' => $form]),
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/profile', [
                'user_details_city' => 'Graz',
                'user_details_zip' => '8010',
                'user_details_ffb_favourite_team' => 3,
                'user_permissions_ffb_mailservice_reminder' => 0,
                'user_permissions_ffb_mailservice_info' => 0,
                'user_permissions_ffb_visible_profile' => 1,
            ])
            ->assertOk()
            ->assertSee('Deine Daten wurden aktualisiert', false)
            ->assertSee('Graz', false);
    }
}
