<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\FfbAuth;
use App\Services\FfbUserResolver;
use App\Services\ProfilePopupService;
use Tests\TestCase;

class PopupApiTest extends TestCase
{
    private function actingAsFfbUser(int $userId = 544): void
    {
        $user = new WebUser;
        $user->user_id = $userId;
        $user->user_status = 'active';
        $user->user_nickname = 'tester';

        $this->mock(FfbUserResolver::class, function ($mock) use ($user, $userId) {
            $mock->shouldReceive('findActive')->with($userId)->andReturn($user);
        });
    }

    public function test_profile_popup_requires_auth(): void
    {
        $this->getJson('/api/popups/user/12')->assertStatus(401);
    }

    public function test_profile_popup_returns_payload(): void
    {
        $this->actingAsFfbUser();

        $payload = [
            'user' => [
                'user_id' => 12,
                'user_ownprofile' => false,
                'user_nickname' => 'Rival',
                'user_fname' => null,
                'user_lname' => null,
                'user_name' => null,
                'user_gender' => 'm',
                'user_date_llogin' => '01.01.2024',
                'user_date_register' => '01.01.2020',
                'avatar_url' => '/images/ffb/profiles/avatar/avatar_na.png',
                'photo_url' => '/images/ffb/profiles/photo/m_profile_na.png',
                'user_details_city' => 'Wien',
                'user_details_website' => null,
                'user_details_phone' => null,
                'user_perm_profile' => false,
                'favourite_team' => [
                    'id' => 1,
                    'name' => 'Rapid',
                    'nationality' => 'aut',
                ],
                'own_team' => null,
            ],
            'participations' => [
                [
                    'game_id' => 26,
                    'game_title' => 'Testliga',
                    'game_symbol' => 'x.png',
                    'game_archive' => false,
                    'score_rm' => 'wc',
                    'score_wc' => 10,
                    'score_points' => 100,
                    'score_start' => '01.08.25',
                    'score_end' => 'jetzt',
                    'user_rank' => 2,
                ],
            ],
        ];

        $this->mock(ProfilePopupService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('forUser')->once()->with(544, 12)->andReturn([
                'ok' => true,
                'data' => $payload,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/user/12')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user.user_nickname', 'Rival')
            ->assertJsonPath('data.participations.0.user_rank', 2);
    }

    public function test_profile_popup_not_found(): void
    {
        $this->actingAsFfbUser();

        $this->mock(ProfilePopupService::class, function ($mock) {
            $mock->shouldReceive('forUser')->once()->with(544, 99999)->andReturn([
                'ok' => false,
                'status' => 404,
                'error' => 'User not found',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/api/popups/user/99999')
            ->assertStatus(404)
            ->assertJsonPath('error', 'User not found');
    }
}
