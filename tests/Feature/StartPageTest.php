<?php

namespace Tests\Feature;

use App\Services\StartPageService;
use Tests\TestCase;

class StartPageTest extends TestCase
{
    public function test_start_page_renders(): void
    {
        $this->mock(StartPageService::class, function ($mock) {
            $mock->shouldReceive('payload')->once()->andReturn([
                'stats' => [
                    'users_total' => 10,
                    'users_today' => 2,
                    'lineups' => 5,
                    'score_sum' => 100,
                    'score_avg' => 20.0,
                    'matchrounds_played' => 3,
                ],
                'leagues' => [
                    [
                        'league_id' => 1,
                        'league_title' => 'Testliga',
                        'symbol_url' => '/images/ffb/leagues/testliga.png',
                    ],
                ],
                'results' => [
                    [
                        'home_team' => 'Alpha',
                        'home_score' => '2',
                        'home_flag' => 'aut',
                        'home_flag_css' => "url('/vendor/flag-icons/flags/4x3/at.svg')",
                        'guest_team' => 'Beta',
                        'guest_score' => '1',
                        'guest_flag' => 'ger',
                        'guest_flag_css' => "url('/vendor/flag-icons/flags/4x3/de.svg')",
                        'score_html' => '2:1',
                        'round_label' => 'Testliga - Spieltag 1',
                        'date' => '01.01.2026',
                    ],
                ],
            ]);
        });

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('SoccerSportsfan', false)
            ->assertSee('brand brand-stacked', false)
            ->assertSee('nav_start.png', false)
            ->assertSee('nav_login.png', false)
            ->assertSee('class="dash-top"', false)
            ->assertSee('user-card-login', false)
            ->assertSee('css/start.css?v=30', false)
            ->assertSee('Aktuelle Ligen', false)
            ->assertSee('start-leagues', false)
            ->assertSee('Testliga', false)
            ->assertSee('Testliga - Spieltag 1', false)
            ->assertSee('result-meta', false)
            ->assertSee('brand-tagline', false)
            ->assertSee('result-card', false)
            ->assertSee('--ffb-flag-home', false)
            ->assertSee('vendor/flag-icons/flags/4x3/at.svg', false)
            ->assertSee('Fantasy Football mit echten Ligen', false)
            ->assertDontSee('Stell dein Team auf.', false)
            ->assertDontSee('brand-mark', false)
            ->assertSee('Registrieren', false)
            ->assertSee('Passwort vergessen?', false)
            ->assertDontSee('Kostenlos registrieren', false)
            ->assertDontSee('Community', false)
            ->assertDontSee('<time>', false)
            ->assertSee('Alpha', false)
            ->assertSee('action="login"', false)
            ->assertSee('id="register-link"', false)
            ->assertDontSee('btn-block', false)
            ->assertSee('js/start.js?v=9', false);
    }

    public function test_start_api_returns_payload(): void
    {
        $payload = [
            'stats' => [
                'users_total' => 1,
                'users_today' => 0,
                'lineups' => 0,
                'score_sum' => 0,
                'score_avg' => 0.0,
                'matchrounds_played' => 0,
            ],
            'leagues' => [],
            'results' => [],
        ];

        $this->mock(StartPageService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('payload')->once()->andReturn($payload);
        });

        $response = $this->getJson('/api/start');

        $response->assertOk()
            ->assertJson([
                'status' => 200,
                'data' => $payload,
            ]);
    }
}
