<?php

namespace Tests\Feature;

use App\Services\AdminCenterService;
use App\Services\AdminPlayerpriceService;
use App\Services\EloRatingClient;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use App\Services\LineupOptionsResolver;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
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

    public function test_playerprice_page_renders_with_league_and_tabs(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 7, 'players', null, null)
                ->andReturn($this->payload([
                    'tab' => 'players',
                    'matchround_id' => 0,
                ]));
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice?price_league_id=7')
            ->assertOk()
            ->assertSee('Preise', false)
            ->assertSee('Spieler-Preis', false)
            ->assertSee('Team-Preis', false)
            ->assertSee('Set Player Prices', false)
            ->assertSee('Bundesliga Test', false)
            ->assertDontSee('ELO Team-Preis', false);
    }

    public function test_playerprice_teams_tab_shows_elo_preview_form(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 7, 'teams', null, null)
                ->andReturn($this->payload([
                    'tab' => 'teams',
                    'matchround_id' => 0,
                    'lineup_max_credits' => 100.0,
                    'lineup_max_players_team' => 3,
                    'lineup_limits_source' => 'league',
                ]));
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice?price_league_id=7&tab=teams')
            ->assertOk()
            ->assertSee('ELO Team-Preis', false)
            ->assertSee('Max. Credits / Aufstellung', false)
            ->assertSee('Max. Spieler / Team', false)
            ->assertSee('Exponent', false)
            ->assertSee('Dream-Team-Ratio', false)
            ->assertSee('Mindestpreis', false)
            ->assertSee('Preise berechnen', false)
            ->assertDontSee('Set Player Prices', false)
            ->assertDontSee('ELO BasePrices for League', false);
    }

    public function test_set_matchround_player_prices_posts_and_flashes_result(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('calculatePlayerPricesForMatchround')
                ->once()
                ->with(544, Mockery::type('array'))
                ->andReturn([
                    'ok' => true,
                    'message' => 'Dynamic PlayerPrices aktualisiert.',
                    'details' => ['Price updated: 11: 8.5'],
                    'price_league_id' => 7,
                    'tab' => 'players',
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/matchround-player-prices', [
                'price_league_id' => 7,
                'matchround_id' => 3,
                'price_margin' => 2,
            ])
            ->assertRedirect(route('admin.playerprice', ['price_league_id' => 7]))
            ->assertSessionHas('admin_message', 'Dynamic PlayerPrices aktualisiert.')
            ->assertSessionHas('admin_details', ['Price updated: 11: 8.5']);
    }

    public function test_preview_elo_team_prices_renders_result_table(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'teams' => [
                [
                    'team_id' => 1,
                    'team_name' => 'Brazil',
                    'elo_rating' => 2100,
                    'normalized' => 1.0,
                    'raw_weight' => 1.0,
                    'price' => 18.5,
                ],
                [
                    'team_id' => 2,
                    'team_name' => 'Austria',
                    'elo_rating' => 1600,
                    'normalized' => 0.0,
                    'raw_weight' => 0.0,
                    'price' => 1.0,
                ],
            ],
            'checks' => [
                [
                    'id' => 'dream_team',
                    'ok' => true,
                    'cost' => 150.0,
                    'target' => '> 100',
                    'message' => 'Dream-Team ist nicht leistbar.',
                ],
            ],
            'params' => [
                'exponent' => 2.0,
                'dream_team_ratio' => 1.5,
                'min_price' => 1.0,
                'budget' => 100.0,
            ],
            'form' => [
                'exponent' => 2.0,
                'dream_team_ratio' => 1.5,
                'min_price' => 1.0,
            ],
            'teams_without_elo' => 0,
            'teams_skipped' => [],
            'lineup_max_credits' => 100.0,
            'lineup_max_players_team' => 3,
        ];

        $this->mock(AdminPlayerpriceService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('previewEloTeamPrices')
                ->once()
                ->with(544, Mockery::type('array'))
                ->andReturn([
                    'ok' => true,
                    'message' => 'ELO Team-Preise berechnet (Vorschau, nicht gespeichert).',
                    'price_league_id' => 7,
                    'matchround_id' => 0,
                    'tab' => 'teams',
                    'preview' => $preview,
                ]);

            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, 7, 'teams', null, $preview)
                ->andReturn($this->payload([
                    'tab' => 'teams',
                    'matchround_id' => 0,
                    'lineup_max_credits' => 100.0,
                    'lineup_max_players_team' => 3,
                    'lineup_limits_source' => 'league',
                    'team_price_preview' => $preview,
                ]));
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/elo-team-prices/preview', [
                'price_league_id' => 7,
                'tab' => 'teams',
                'matchround_id' => '',
            ])
            ->assertOk()
            ->assertSee('Brazil', false)
            ->assertSee('18.5', false)
            ->assertSee('Dream-Team ist nicht leistbar.', false)
            ->assertSee('ELO Team-Preise berechnet (Vorschau, nicht gespeichert).', false);
    }

    #[Test]
    public function preview_elo_team_prices_lists_skipped_teams_by_name(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'teams' => [
                [
                    'team_id' => 1,
                    'team_name' => 'Brazil',
                    'elo_rating' => 2100,
                    'normalized' => 1.0,
                    'raw_weight' => 1.0,
                    'price' => 18.5,
                ],
            ],
            'checks' => [],
            'params' => [
                'exponent' => 2.0,
                'dream_team_ratio' => 1.5,
                'min_price' => 1.0,
                'budget' => 100.0,
            ],
            'form' => [
                'exponent' => 2.0,
                'dream_team_ratio' => 1.5,
                'min_price' => 1.0,
            ],
            'teams_without_elo' => 2,
            'teams_skipped' => [
                ['team_id' => 90, 'team_name' => 'Admira Villach'],
                ['team_id' => 86, 'team_name' => 'FC Bayern München'],
            ],
            'lineup_max_credits' => 100.0,
            'lineup_max_players_team' => 3,
        ];

        $this->mock(AdminPlayerpriceService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('previewEloTeamPrices')
                ->once()
                ->andReturn([
                    'ok' => true,
                    'message' => 'ELO Team-Preise berechnet (Vorschau, nicht gespeichert).',
                    'price_league_id' => 7,
                    'matchround_id' => 0,
                    'tab' => 'teams',
                    'preview' => $preview,
                ]);

            $mock->shouldReceive('pagePayload')
                ->once()
                ->andReturn($this->payload([
                    'tab' => 'teams',
                    'team_price_preview' => $preview,
                ]));
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/elo-team-prices/preview', [
                'price_league_id' => 7,
                'tab' => 'teams',
            ])
            ->assertOk()
            ->assertSee('2 Team(s) ohne ELO-Zuordnung wurden übersprungen', false)
            ->assertSee('Admira Villach', false)
            ->assertSee('FC Bayern München', false);
    }

    #[Test]
    public function compute_elo_team_price_preview_ranks_stronger_teams_higher(): void
    {
        $service = new AdminPlayerpriceService(
            Mockery::mock(AdminCenterService::class),
            Mockery::mock(EloRatingClient::class),
            new LineupOptionsResolver,
        );

        $preview = $service->computeEloTeamPricePreview([
            ['team_id' => 1, 'team_name' => 'Top', 'elo_rating' => 2000],
            ['team_id' => 2, 'team_name' => 'Mid', 'elo_rating' => 1700],
            ['team_id' => 3, 'team_name' => 'Low', 'elo_rating' => 1400],
            ['team_id' => 4, 'team_name' => 'Mid2', 'elo_rating' => 1650],
            ['team_id' => 5, 'team_name' => 'Mid3', 'elo_rating' => 1550],
        ], 100.0, 3, 11, 2.0, 1.5);

        $this->assertNotEmpty($preview['teams']);
        $this->assertSame('Top', $preview['teams'][0]['team_name']);
        $this->assertGreaterThanOrEqual(1.0, $preview['teams'][array_key_last($preview['teams'])]['price']);
        $this->assertSame(2.0, $preview['params']['exponent']);
        $this->assertSame(1.5, $preview['params']['dream_team_ratio']);
        $this->assertSame(1.0, $preview['params']['min_price']);

        $steeper = $service->computeEloTeamPricePreview([
            ['team_id' => 1, 'team_name' => 'Top', 'elo_rating' => 2000],
            ['team_id' => 2, 'team_name' => 'Mid', 'elo_rating' => 1700],
            ['team_id' => 3, 'team_name' => 'Low', 'elo_rating' => 1400],
        ], 100.0, 3, 11, 3.0, 2.0, 2.5);
        $this->assertSame(3.0, $steeper['params']['exponent']);
        $this->assertSame(2.0, $steeper['params']['dream_team_ratio']);
        $this->assertSame(2.5, $steeper['params']['min_price']);
        $this->assertGreaterThanOrEqual(2.5, min(array_column($steeper['teams'], 'price')));

        $withFloor = $service->computeEloTeamPricePreview([
            ['team_id' => 1, 'team_name' => 'Top', 'elo_rating' => 2000],
            ['team_id' => 2, 'team_name' => 'High', 'elo_rating' => 1850],
            ['team_id' => 3, 'team_name' => 'Mid', 'elo_rating' => 1700],
            ['team_id' => 4, 'team_name' => 'LowMid', 'elo_rating' => 1550],
            ['team_id' => 5, 'team_name' => 'Low', 'elo_rating' => 1400],
        ], 100.0, 3, 11, 2.0, 1.5, 5.0);

        $pricesByName = collect($withFloor['teams'])->keyBy('team_name')->map->price;
        $this->assertSame(5.0, $pricesByName['Low']);
        $this->assertGreaterThan(5.0, $pricesByName['LowMid']);
        $this->assertGreaterThan($pricesByName['LowMid'], $pricesByName['Mid']);
        $this->assertSame(1, collect($withFloor['teams'])->where('price', 5.0)->count());
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
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
            'selected_league_id' => 7,
            'selected_league' => [
                'league_id' => 7,
                'league_title' => 'Bundesliga Test',
            ],
            'price_league_id' => 7,
            'leagues' => [
                ['league_id' => 7, 'league_title' => 'Bundesliga Test'],
            ],
            'tab' => 'players',
            'matchrounds' => [
                ['matchround_id' => 3, 'matchround_title' => 'Runde 1'],
            ],
            'matchround_id' => 0,
            'lineup_max_credits' => 100.0,
            'lineup_max_players_team' => 3,
            'lineup_limits_source' => 'league',
            'elo_exponent' => 2.0,
            'elo_dream_team_ratio' => 1.5,
            'elo_min_price' => 1.0,
            'price_margins' => [0.5, 1, 1.5, 2, 2.5, 3],
            'team_price_preview' => null,
        ], $overrides);
    }
}
