<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\FfbUserResolver;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class WebAuthRouteGroupsTest extends TestCase
{
    public function test_protected_pages_redirect_guests_with_destination(): void
    {
        $this->get('/lineup')
            ->assertRedirect(route('start', ['destination' => 'lineup']));

        $this->get('/myteam')
            ->assertRedirect(route('start', ['destination' => 'myteam']));

        $this->get('/profile')
            ->assertRedirect(route('start', ['destination' => 'profile']));

        $this->get('/account')
            ->assertRedirect(route('start', ['destination' => 'profile?tab=account']));
    }

    public function test_registration_redirects_authenticated_users(): void
    {
        $this->actingAsFfbUser(544)
            ->get('/registration')
            ->assertRedirect(route('profile', ['tab' => 'account']));
    }

    public function test_inactive_user_is_logged_out_on_protected_page(): void
    {
        $user = new WebUser;
        $user->user_id = 544;
        $user->user_nickname = 'inactive';
        $user->user_status = 'inactive';
        $user->exists = true;

        $this->mock(FfbUserResolver::class, function ($mock) {
            $mock->shouldReceive('findPresent')->with(544)->andReturn(null);
            $mock->shouldReceive('findActive')->with(544)->andReturn(null);
            $mock->shouldReceive('findVerified')->with(544)->andReturn(null);
        });

        $this->actingAs($user)
            ->get('/lineup')
            ->assertRedirect(route('start'));

        $this->assertGuest();
        $this->assertFalse(Auth::check());
    }

    public function test_unverified_user_can_open_profile_but_not_lineup(): void
    {
        $user = new WebUser;
        $user->user_id = 544;
        $user->user_nickname = 'pending';
        $user->user_status = 'pending';
        $user->email_verified_at = null;
        $user->exists = true;

        $this->mock(FfbUserResolver::class, function ($mock) use ($user) {
            $mock->shouldReceive('findPresent')->with(544)->andReturn($user);
            $mock->shouldReceive('findActive')->with(544)->andReturn($user);
            $mock->shouldReceive('findVerified')->with(544)->andReturn(null);
        });

        $this->mock(\App\Services\AccountService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'pending',
                        'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                        'update_profile_nag' => false,
                    ],
                    'form' => [
                        'user_nickname' => 'pending',
                        'user_email' => 'pending@example.com',
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
                    'birth_years' => [],
                    'navigation' => [],
                ],
            ]);
            $mock->shouldReceive('profilePayload')->andReturn([
                'ok' => true,
                'data' => [
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'pending',
                        'photo_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                        'update_profile_nag' => false,
                    ],
                    'form' => [
                        'user_fname' => '',
                        'user_lname' => '',
                        'user_nationality' => '',
                        'user_birth_day' => 0,
                        'user_birth_month' => 0,
                        'user_birth_year' => 0,
                        'user_details_city' => '',
                        'user_details_zip' => '',
                        'user_details_street' => '',
                        'user_details_phone' => '',
                        'user_details_website' => '',
                        'user_details_ffb_favourite_team' => 0,
                        'user_details_avatar' => 'avatar_na.png',
                        'user_details_avatar_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                        'user_permissions_ffb_mailservice_reminder' => 0,
                        'user_permissions_ffb_mailservice_info' => 0,
                        'user_permissions_ffb_visible_profile' => 0,
                    ],
                    'teams' => [],
                    'avatar_shirts' => [],
                    'navigation' => [],
                ],
            ]);
        });

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk();

        $this->actingAs($user)
            ->get('/lineup')
            ->assertRedirect(route('start'))
            ->assertSessionHas('show_activation_modal', true);

        $this->assertAuthenticated();
    }
}
