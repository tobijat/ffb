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
            $mock->shouldReceive('findActive')->with(544)->andReturn(null);
        });

        $this->actingAs($user)
            ->get('/lineup')
            ->assertRedirect(route('start'));

        $this->assertGuest();
        $this->assertFalse(Auth::check());
    }
}
