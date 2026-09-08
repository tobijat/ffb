<?php

namespace Tests\Feature;

use App\Services\AdminAwardsService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminAwardsTest extends TestCase
{
    public function test_awards_redirects_guests(): void
    {
        $this->get('/admin/awards')
            ->assertRedirect(route('start', ['destination' => '/admin/awards']));
    }

    public function test_awards_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/awards')
            ->assertRedirect(route('start'));
    }

    public function test_awards_page_renders(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminAwardsService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_award.png',
                        'name' => 'Awards',
                        'link' => '/admin/awards',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game_id' => 7,
                'selected_game' => [
                    'game_id' => 7,
                    'game_title' => 'Bundesliga Test',
                    'symbol_url' => '/images/ffb/games/na.png',
                ],
                'groups' => [
                    ['user_award_id' => 1, 'user_award_name' => 'Tabellenführer'],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/awards')
            ->assertOk()
            ->assertSee('Auszeichnungen', false)
            ->assertSee('Tabellenführer', false)
            ->assertSee('alle Auszeichnungen berechnen', false)
            ->assertSee('admin-awards.js', false);
    }

    public function test_group_details_endpoint(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminAwardsService::class, function ($mock) {
            $mock->shouldReceive('groupDetails')->once()->with(1)->andReturn([
                'userAward' => [
                    'id' => 1,
                    'name' => 'Tabellenführer',
                    'description' => 'Desc',
                    'image' => 'awards/x.png',
                ],
                'userAwardDefines' => [
                    [
                        'id' => 10,
                        'rank' => '1',
                        'name' => 'Gold',
                        'aim' => '1',
                        'dbtable' => ' ',
                        'operator' => ' ',
                        'count' => 20,
                        'auto' => 0,
                        'function_name' => 'calcAwardRoundWins',
                        'image' => 'awards/g.png',
                        'descr' => 'Gold desc',
                    ],
                ],
                'userAwardCounts' => 1,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/admin/awards/groups/1')
            ->assertOk()
            ->assertJsonPath('userAward.name', 'Tabellenführer')
            ->assertJsonPath('userAwardDefines.0.name', 'Gold');
    }

    public function test_calculate_define_endpoint(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminAwardsService::class, function ($mock) {
            $mock->shouldReceive('calculateDefine')->once()->with(10, 544)->andReturn([
                'userUpdates' => 2,
                'newAwardUser' => [
                    ['uid' => 9, 'aid' => 10, 'usernick' => 'player1'],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->postJson('/admin/awards/defines/10/calculate')
            ->assertOk()
            ->assertJsonPath('userUpdates', 2)
            ->assertJsonPath('newAwardUser.0.usernick', 'player1');
    }
}
