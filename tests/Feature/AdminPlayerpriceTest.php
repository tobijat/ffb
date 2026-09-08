<?php

namespace Tests\Feature;

use App\Services\AdminPlayerpriceService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminPlayerpriceTest extends TestCase
{
    public function test_playerprice_redirects_guests(): void
    {
        $this->get('/admin/playerprice')
            ->assertRedirect(route('start', ['destination' => '/admin/playerprice']));
    }

    public function test_playerprice_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice')
            ->assertRedirect(route('start'));
    }

    public function test_playerprice_page_renders(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_prices.png',
                        'name' => 'Preis',
                        'link' => '/admin/playerprice',
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
                'matchrounds' => [
                    ['matchround_id' => 3, 'matchround_title' => 'Runde 1'],
                ],
                'price_margins' => [0.5, 1, 1.5, 2, 2.5, 3],
                'price_options' => range(1, 19),
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice')
            ->assertOk()
            ->assertSee('PlayerPrice Settings', false)
            ->assertSee('Dynamic PlayerPrices v2014', false)
            ->assertSee('ELO BasePrices for Game', false)
            ->assertSee('ELO BasePrices for Matchround', false)
            ->assertSee('Set Player Prices', false)
            ->assertSee('Bundesliga Test', false)
            ->assertSee('Runde 1', false)
            ->assertSee('do only click once!', false);
    }

    public function test_set_matchround_player_prices_posts_and_flashes_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('calculatePlayerPricesForMatchround')
                ->once()
                ->with(544, \Mockery::type('array'))
                ->andReturn([
                    'ok' => true,
                    'message' => 'Dynamic PlayerPrices aktualisiert.',
                    'details' => ['Price updated: 11: 8.5'],
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/matchround-player-prices', [
                'matchround_id' => 3,
                'price_margin' => 2,
            ])
            ->assertRedirect(route('admin.playerprice'))
            ->assertSessionHas('admin_message', 'Dynamic PlayerPrices aktualisiert.')
            ->assertSessionHas('admin_details', ['Price updated: 11: 8.5']);
    }

    public function test_set_game_elo_team_prices_without_league_flashes_errors(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('calculateEloTeamPricesForGame')
                ->once()
                ->with(544, \Mockery::type('array'))
                ->andReturn([
                    'ok' => false,
                    'errors' => ['Bitte zuerst eine Liga auswählen.'],
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/game-elo-team-prices', [
                'max_price' => 10,
                'min_price' => 3,
            ])
            ->assertRedirect(route('admin.playerprice'))
            ->assertSessionHas('admin_errors', ['Bitte zuerst eine Liga auswählen.']);
    }

    public function test_set_matchround_elo_team_prices_posts_and_flashes_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('calculateEloTeamPricesForMatchround')
                ->once()
                ->with(544, \Mockery::type('array'))
                ->andReturn([
                    'ok' => true,
                    'message' => 'ELO BasePrices für Spielrunden-Teams aktualisiert.',
                    'details' => ['Base price updated: Team A: 5.5'],
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/matchround-elo-team-prices', [
                'matchround_id' => 3,
                'max_price' => 10,
                'min_price' => 3,
            ])
            ->assertRedirect(route('admin.playerprice'))
            ->assertSessionHas('admin_message')
            ->assertSessionHas('admin_details', ['Base price updated: Team A: 5.5']);
    }
}
