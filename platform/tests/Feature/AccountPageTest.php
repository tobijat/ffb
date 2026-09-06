<?php

namespace Tests\Feature;

use App\Services\AccountService;
use App\Services\FfbAuth;
use Tests\TestCase;

class AccountPageTest extends TestCase
{
    public function test_account_page_redirects_guests(): void
    {
        $this->get('/account')
            ->assertRedirect(route('start', ['destination' => '/platform/account']));
    }

    public function test_account_page_renders_for_logged_in_user(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
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
                        ['symbol' => 'nav_user.png', 'name' => 'Account', 'link' => '/platform/account', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/account')
            ->assertOk()
            ->assertSee('Profildaten bearbeiten', false)
            ->assertSee('tester', false)
            ->assertSee('tester@example.com', false)
            ->assertSee('js/account.js', false);
    }

    public function test_account_update_requires_auth(): void
    {
        $this->post('/account', [
            'user_tos' => 'user_tos_yes',
        ])->assertRedirect(route('start', ['destination' => '/platform/account']));
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
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'update_profile_nag' => false,
                    ],
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
                    'countries' => [],
                    'birth_years' => [1990],
                    'navigation' => [],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/account', [
                'user_fname' => 'Max',
            ])
            ->assertOk()
            ->assertSee('Es sind Fehler aufgetreten', false)
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
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'update_profile_nag' => false,
                    ],
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
                    'countries' => ['AUT' => 'Österreich'],
                    'birth_years' => [1990],
                    'navigation' => [],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/account', [
                'user_fname' => 'Max',
                'user_tos' => 'user_tos_yes',
            ])
            ->assertOk()
            ->assertSee('Deine Daten wurden aktualisiert', false);
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
            ->assertRedirect(route('start', ['destination' => '/platform/profile']));
    }

    public function test_profile_page_renders_for_logged_in_user(): void
    {
        $this->mock(AccountService::class, function ($mock) {
            $mock->shouldReceive('profilePayload')->once()->with(544)->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'update_profile_nag' => false,
                    ],
                    'form' => [
                        'user_details_city' => 'Wien',
                        'user_details_zip' => '1010',
                        'user_details_street' => '',
                        'user_details_phone' => '',
                        'user_details_website' => '',
                        'user_details_ffb_favourite_team' => 3,
                        'user_details_photo' => 'profile_na.png',
                        'user_details_avatar' => 'avatar_na.png',
                        'user_details_photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'user_details_avatar_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                        'user_permissions_ffb_mailservice_reminder' => 1,
                        'user_permissions_ffb_mailservice_info' => 0,
                        'user_permissions_ffb_visible_profile' => 0,
                    ],
                    'teams' => [
                        ['team_id' => 3, 'team_name' => 'Rapid', 'team_nationality' => 'AUT'],
                    ],
                    'navigation' => [
                        ['symbol' => 'nav_profile.png', 'name' => 'Profil', 'link' => '/platform/profile', 'style' => 'big'],
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/profile')
            ->assertOk()
            ->assertSee('Profildetails bearbeiten', false)
            ->assertSee('Wien', false)
            ->assertSee('Rapid', false)
            ->assertSee('js/account.js', false);
    }

    public function test_profile_update_shows_success(): void
    {
        $form = [
            'user_details_city' => 'Graz',
            'user_details_zip' => '8010',
            'user_details_street' => '',
            'user_details_phone' => '',
            'user_details_website' => '',
            'user_details_ffb_favourite_team' => 3,
            'user_details_photo' => 'profile_na.png',
            'user_details_avatar' => 'avatar_na.png',
            'user_details_photo_url' => '/images/ffb/profiles/photo/profile_na.png',
            'user_details_avatar_url' => '/images/ffb/profiles/avatar/avatar_na.png',
            'user_permissions_ffb_mailservice_reminder' => 0,
            'user_permissions_ffb_mailservice_info' => 0,
            'user_permissions_ffb_visible_profile' => 1,
        ];

        $this->mock(AccountService::class, function ($mock) use ($form) {
            $mock->shouldReceive('updateProfile')->once()->andReturn([
                'ok' => true,
                'message' => '<b>Deine Daten wurden aktualisiert!</b>',
                'form' => $form,
            ]);
            $mock->shouldReceive('profilePayload')->once()->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'tester',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'update_profile_nag' => false,
                    ],
                    'form' => $form,
                    'teams' => [
                        ['team_id' => 3, 'team_name' => 'Rapid', 'team_nationality' => 'AUT'],
                    ],
                    'navigation' => [],
                ],
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
