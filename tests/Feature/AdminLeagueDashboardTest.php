<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\MatchroundOptions;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminLeagueDashboardService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminLeagueDashboardTest extends TestCase
{
    private string $imagesRoot = '';

    private string $symbolsDir = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->imagesRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-league-dash-'.uniqid('', true);
        $this->symbolsDir = $this->imagesRoot.DIRECTORY_SEPARATOR.'symbols';
        mkdir($this->symbolsDir, 0775, true);
        config(['ffb.legacy_images_path' => $this->imagesRoot]);
    }

    protected function tearDown(): void
    {
        if ($this->symbolsDir !== '' && is_dir($this->symbolsDir)) {
            foreach (glob($this->symbolsDir.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
                @unlink($file);
            }
            @rmdir($this->symbolsDir);
        }
        if ($this->imagesRoot !== '' && is_dir($this->imagesRoot)) {
            $this->removeDirectory($this->imagesRoot);
        }

        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('ffb_team');
        parent::tearDown();
    }

    private function removeDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $dir.DIRECTORY_SEPARATOR.$entry;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }

    public function test_league_dashboard_redirects_guests(): void
    {
        $this->get('/admin/league-dashboard')
            ->assertRedirect(route('start', ['destination' => '/admin/league-dashboard']));
    }

    public function test_league_dashboard_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/league-dashboard')
            ->assertRedirect(route('start'));
    }

    public function test_league_dashboard_prompts_without_selected_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminLeagueDashboardService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(7)->andReturn([
                'user' => [
                    'user_id' => 7,
                    'user_nickname' => 'admin',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_league_id' => 0,
                'selected_league' => null,
                'sections' => [
                    ['key' => 'league', 'title' => 'Liga', 'ok' => false, 'checklist' => []],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 7])
            ->get('/admin/league-dashboard')
            ->assertOk()
            ->assertDontSee('id="admin-league-dashboard-title"', false)
            ->assertSee('eine Liga auswählen', false)
            ->assertDontSee('data-section="league"', false)
            ->assertSee('Dashboard', false)
            ->assertSee('/admin/league-dashboard', false);
    }

    public function test_league_dashboard_renders_liga_and_matchrounds_sections(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminLeagueDashboardService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(7)->andReturn([
                'user' => [
                    'user_id' => 7,
                    'user_nickname' => 'admin',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_league_id' => 1,
                'selected_league' => [
                    'league_id' => 1,
                    'league_title' => 'Testliga',
                    'symbol_url' => '/images/ffb/symbols/x.png',
                ],
                'sections' => [
                    [
                        'key' => 'league',
                        'title' => 'Liga: Testliga',
                        'ok' => false,
                        'checklist' => [
                            ['key' => 'logo', 'label' => 'Logo vorhanden', 'ok' => false],
                            ['key' => 'visible', 'label' => 'Liga sichtbar', 'ok' => true],
                            [
                                'key' => 'schedule',
                                'label' => 'Aktiv und aktuelle/zukünftige Spielrunden vorhanden',
                                'ok' => false,
                            ],
                            [
                                'key' => 'options',
                                'label' => 'Liga-Optionen gesetzt',
                                'ok' => true,
                                'options_overview' => [
                                    [
                                        'title' => 'Spielmodi',
                                        'items' => [
                                            ['label' => 'Rangliste', 'value' => 'LC'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'matchrounds',
                        'title' => 'Spielrunden (aktuell: 1, zukünftig: 1, vergangen: 0)',
                        'ok' => false,
                        'checklist' => [
                            ['key' => 'round-matches-1', 'label' => 'Runde A: mindestens 1 Spiel', 'ok' => true],
                            ['key' => 'round-matches-2', 'label' => 'Runde B: mindestens 1 Spiel', 'ok' => false],
                            ['key' => 'active-round', 'label' => 'Mindestens 1 aktive Spielrunde', 'ok' => true],
                        ],
                        'groups' => [
                            [
                                'key' => 'current',
                                'title' => 'Aktuell',
                                'rounds' => [
                                    [
                                        'matchround_id' => 1,
                                        'title' => 'Runde A',
                                        'startdate' => '1.1.2026 12:00',
                                        'enddate' => '8.1.2026 12:00',
                                        'match_count' => 2,
                                        'has_matches' => true,
                                        'active' => true,
                                        'has_lineup_options' => true,
                                        'lineup_options' => [
                                            [
                                                'title' => 'Aufstellungslimits',
                                                'items' => [
                                                    ['label' => 'Max. Spieler', 'value' => '11'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'future',
                                'title' => 'Zukünftig',
                                'rounds' => [
                                    [
                                        'matchround_id' => 2,
                                        'title' => 'Runde B',
                                        'startdate' => '1.2.2026 12:00',
                                        'enddate' => '8.2.2026 12:00',
                                        'match_count' => 0,
                                        'has_matches' => false,
                                        'active' => false,
                                        'has_lineup_options' => false,
                                        'lineup_options' => [],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'past',
                                'title' => 'Vergangen',
                                'rounds' => [],
                            ],
                        ],
                    ],
                    [
                        'key' => 'matches',
                        'title' => 'Spiele: 3 Spiele in 2 Spielrunden',
                        'ok' => false,
                        'checklist' => [
                            ['key' => 'has-matches', 'label' => 'Spiele vorhanden', 'ok' => true],
                            [
                                'key' => 'dates-within-rounds',
                                'label' => 'Alle Spieldaten innerhalb der Spielrunden',
                                'ok' => false,
                                'match_list_summary' => 'Spiele außerhalb der Spielrunde',
                                'match_list' => [
                                    [
                                        'match_id' => 9,
                                        'label' => 'Alpha – Beta · 20.05.2026 · Runde A',
                                        'detail' => 'Spielrunde: 1.6.2026 0:00 – 30.6.2026 23:59',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'past-results',
                                'label' => 'Vergangene Spiele mit Ergebnis und Spieldauer',
                                'ok' => false,
                                'match_list_summary' => 'Vergangene Spiele ohne Ergebnis/Dauer',
                                'match_list' => [
                                    [
                                        'match_id' => 9,
                                        'label' => 'Alpha – Beta · 20.05.2026 · Runde A',
                                        'detail' => 'kein Ergebnis, keine Spieldauer',
                                    ],
                                ],
                                'info_list_summary' => 'Vergangene Spiele mit Status-Hinweis',
                                'info_list' => [
                                    [
                                        'match_id' => 10,
                                        'label' => 'Gamma – Delta · 21.05.2026 · Runde A',
                                        'detail' => 'kein Ergebnis · Status: abgesagt',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'teams',
                        'title' => '2 Teams',
                        'ok' => false,
                        'checklist' => [
                            [
                                'key' => 'teams-active',
                                'label' => 'Alle Teams aktiv',
                                'ok' => false,
                                'match_list_summary' => 'Inaktive Teams',
                                'match_list' => [
                                    [
                                        'team_id' => 2,
                                        'label' => 'Beta',
                                        'detail' => 'inaktiv',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'teams-flag',
                                'label' => 'Alle Teams mit Logo/Flagge',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Teams ohne Logo/Flagge',
                            ],
                            [
                                'key' => 'teams-jersey',
                                'label' => 'Alle Teams mit Trikot',
                                'ok' => false,
                                'match_list_summary' => 'Teams ohne Trikot',
                                'match_list' => [
                                    [
                                        'team_id' => 2,
                                        'label' => 'Beta',
                                        'detail' => 'Trikot fehlt (ger)',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'squad',
                        'title' => 'Kader: 24 aktive Spieler in 2',
                        'ok' => false,
                        'checklist' => [
                            [
                                'key' => 'squad-min-players',
                                'label' => 'Jede Mannschaft hat mindestens 11 aktive Spieler',
                                'ok' => false,
                                'match_list_summary' => 'Mannschaften mit zu wenigen aktiven Spielern',
                                'match_list' => [
                                    ['label' => 'Beta', 'detail' => '8 aktive Spieler'],
                                ],
                            ],
                            [
                                'key' => 'squad-positions',
                                'label' => 'Jede Mannschaft hat alle Positionen (G/D/M/S)',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Mannschaften mit fehlenden Positionen',
                            ],
                            [
                                'key' => 'squad-unique-players',
                                'label' => 'Kein Spieler aktiv in mehr als einer Mannschaft',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Spieler in mehreren Mannschaften',
                            ],
                        ],
                    ],
                    ['key' => 'playerprice', 'title' => 'Preis/Performance', 'ok' => false],
                    ['key' => 'matchdata', 'title' => 'Spieldaten', 'ok' => false],
                    ['key' => 'extremeteam', 'title' => 'Top&Flop', 'ok' => false],
                    ['key' => 'score', 'title' => 'Score', 'ok' => false],
                ],
            ]);
        });

        $response = $this->withSession([FfbAuth::SESSION_USER_ID => 7])
            ->get('/admin/league-dashboard')
            ->assertOk()
            ->assertSee('Liga: Testliga', false)
            ->assertSee('Logo vorhanden:', false)
            ->assertSee('Optionen-Übersicht', false)
            ->assertSee('Spielmodi', false)
            ->assertSee('Spielrunden (aktuell: 1, zukünftig: 1, vergangen: 0)', false)
            ->assertSee('Mindestens 1 aktive Spielrunde', false)
            ->assertSee('Spiele: 3 Spiele in 2 Spielrunden', false)
            ->assertSee('Spiele vorhanden', false)
            ->assertSee('Alle Spieldaten innerhalb der Spielrunden', false)
            ->assertSee('Vergangene Spiele mit Ergebnis und Spieldauer', false)
            ->assertSee('Spiele außerhalb der Spielrunde', false)
            ->assertSee('Vergangene Spiele ohne Ergebnis/Dauer', false)
            ->assertSee('Vergangene Spiele mit Status-Hinweis', false)
            ->assertSee('Status: abgesagt', false)
            ->assertSee('Alpha – Beta', false)
            ->assertSee('2 Teams', false)
            ->assertSee('Alle Teams aktiv', false)
            ->assertSee('Inaktive Teams', false)
            ->assertSee('Teams ohne Trikot', false)
            ->assertSee('Kader: 24 aktive Spieler in 2', false)
            ->assertSee('Mannschaften mit zu wenigen aktiven Spielern', false)
            ->assertSee('Runde A', false)
            ->assertSee('Aufstellungslimits (Runde)', false)
            ->assertSee('Aktuell', false)
            ->assertSee('Zukünftig', false)
            ->assertSee('Vergangen', false)
            ->assertSee('admin-dashboard-details', false)
            ->assertDontSee('<details class="admin-dashboard-details" open', false)
            ->assertDontSee('ausgewählt', false);

        foreach ([
            'Preis/Performance',
            'Spieldaten',
            'Score',
        ] as $title) {
            $response->assertSee($title, false);
        }

        $response->assertSee('Top&Flop');
    }

    #[Test]
    public function league_section_is_ok_when_all_checks_pass(): void
    {
        $this->createSchema();
        file_put_contents($this->symbolsDir.DIRECTORY_SEPARATOR.'logo.webp', 'x');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => 'logo.webp',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_league_lcpoints' => '12,10,8',
            'options_league_remind_hours_before' => 24,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 2,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_max_d' => 5,
            'options_lineup_min_m' => 3,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 1,
            'options_lineup_max_s' => 3,
            'options_score_minutes_threshold_lower' => 30,
            'options_score_minutes_threshold_upper' => 60,
            'options_score_minutes_low' => 1,
            'options_score_minutes_middle' => 2,
            'options_score_minutes_high' => 3,
            'options_score_goals_g' => 6,
            'options_score_goals_d' => 5,
            'options_score_goals_m' => 4,
            'options_score_goals_s' => 4,
            'options_score_assists' => 3,
            'options_score_owngoals' => -2,
            'options_score_no_oppgoals_g' => 4,
            'options_score_no_oppgoals_d' => 3,
            'options_score_no_oppgoals_m' => 1,
            'options_score_oppgoals_g' => -1,
            'options_score_oppgoals_d' => -1,
            'options_score_card_y' => -2,
            'options_score_card_yr' => -4,
            'options_score_card_r' => -5,
            'options_score_penalty_saved' => 2,
            'options_score_penalty_lost' => -2,
            'options_score_penaltyshootout_save' => 2,
            'options_score_penaltyshootout_lost' => -2,
            'options_score_penaltyshootout_hit' => 2,
        ]);
        Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->addDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDays(2)->toDateTimeString(),
            'matchround_status' => 1,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/logo.webp',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $leagueSection = $payload['sections'][0];

        $this->assertSame('league', $leagueSection['key']);
        $this->assertSame('Liga: WM 2026', $leagueSection['title']);
        $this->assertTrue($leagueSection['ok']);
        $this->assertTrue($leagueSection['checklist'][0]['ok']);
        $this->assertTrue($leagueSection['checklist'][1]['ok']);
        $this->assertTrue($leagueSection['checklist'][2]['ok']);
        $this->assertTrue($leagueSection['checklist'][3]['ok']);
        $this->assertNotEmpty($leagueSection['checklist'][3]['options_overview']);
    }

    #[Test]
    public function league_section_fails_when_logo_missing_or_schedule_wrong(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'Archiv-Liga',
            'league_visible' => 0,
            'league_archive' => 1,
            'league_symbol' => 'missing.webp',
        ]);
        Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Future',
            'matchround_startdate' => now()->addWeek()->toDateTimeString(),
            'matchround_enddate' => now()->addWeeks(2)->toDateTimeString(),
            'matchround_status' => 1,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'Archiv-Liga',
                'symbol_url' => '/images/ffb/symbols/missing.webp',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $leagueSection = $payload['sections'][0];

        $this->assertFalse($leagueSection['ok']);
        $this->assertFalse($leagueSection['checklist'][0]['ok']);
        $this->assertFalse($leagueSection['checklist'][1]['ok']);
        $this->assertFalse($leagueSection['checklist'][2]['ok']);
        $this->assertFalse($leagueSection['checklist'][3]['ok']);
    }

    #[Test]
    public function matchrounds_section_is_ok_when_rounds_have_matches_and_one_is_active(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        $current = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Aktuelle Runde',
            'matchround_startdate' => '2026-06-10 12:00:00',
            'matchround_enddate' => '2026-06-20 12:00:00',
            'matchround_status' => 1,
        ]);
        $future = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Nächste Runde',
            'matchround_startdate' => '2026-07-01 12:00:00',
            'matchround_enddate' => '2026-07-10 12:00:00',
            'matchround_status' => 0,
        ]);
        $past = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Alte Runde',
            'matchround_startdate' => '2026-05-01 12:00:00',
            'matchround_enddate' => '2026-05-10 12:00:00',
            'matchround_status' => 0,
        ]);

        MatchGame::query()->create([
            'match_round' => (int) $current->matchround_id,
            'match_date' => '2026-06-12 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $future->matchround_id,
            'match_date' => '2026-07-02 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $past->matchround_id,
            'match_date' => '2026-05-05 18:00:00',
            'match_homescore' => 2,
            'match_guestscore' => 1,
            'match_minutes' => 90,
        ]);

        MatchroundOptions::query()->create([
            'matchround_options_matchround_id' => (int) $current->matchround_id,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 100,
            'matchround_options_lineup_max_players_team' => 2,
            'matchround_options_lineup_min_g' => 1,
            'matchround_options_lineup_min_d' => 3,
            'matchround_options_lineup_min_m' => 3,
            'matchround_options_lineup_min_s' => 1,
            'matchround_options_lineup_max_g' => 1,
            'matchround_options_lineup_max_d' => 5,
            'matchround_options_lineup_max_m' => 5,
            'matchround_options_lineup_max_s' => 3,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][1];

        $this->assertSame('matchrounds', $section['key']);
        $this->assertSame('Spielrunden (aktuell: 1, zukünftig: 1, vergangen: 1)', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertTrue($section['checklist'][3]['ok']);
        $this->assertSame('Aktuell', $section['groups'][0]['title']);
        $this->assertSame('Aktuelle Runde', $section['groups'][0]['rounds'][0]['title']);
        $this->assertTrue($section['groups'][0]['rounds'][0]['has_lineup_options']);
        $this->assertNotEmpty($section['groups'][0]['rounds'][0]['lineup_options']);
        $this->assertSame('Nächste Runde', $section['groups'][1]['rounds'][0]['title']);
        $this->assertSame('Alte Runde', $section['groups'][2]['rounds'][0]['title']);
    }

    #[Test]
    public function matchrounds_section_fails_when_matches_or_active_round_missing(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Leere Runde',
            'matchround_startdate' => '2026-07-01 12:00:00',
            'matchround_enddate' => '2026-07-10 12:00:00',
            'matchround_status' => 0,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][1];

        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertSame('Spielrunden (aktuell: 0, zukünftig: 1, vergangen: 0)', $section['title']);
    }

    #[Test]
    public function matches_section_is_ok_when_all_checks_pass(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 1,
            'match_guestscore' => 0,
            'match_minutes' => 90,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_date' => '2026-06-16 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][2];

        $this->assertSame('matches', $section['key']);
        $this->assertSame('Spiele: 2 Spiele in 1 Spielrunden', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertSame([], $section['checklist'][1]['match_list']);
        $this->assertSame([], $section['checklist'][2]['match_list']);
    }

    #[Test]
    public function matches_section_treats_unknown_kickoff_time_as_day_only_within_round(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-10 21:00:00',
            'matchround_enddate' => '2026-06-14 23:00:00',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_date' => '2026-06-10 '.MatchGame::DEFAULT_TIME,
            'match_homescore' => 1,
            'match_guestscore' => 0,
            'match_minutes' => 90,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][2];

        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertSame([], $section['checklist'][1]['match_list']);
    }

    #[Test]
    public function matches_section_fails_when_dates_or_past_results_are_invalid(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha']);
        $guest = Team::query()->create(['team_name' => 'Beta']);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-05-20 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][2];

        $this->assertFalse($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertCount(1, $section['checklist'][1]['match_list']);
        $this->assertSame('Spiele außerhalb der Spielrunde', $section['checklist'][1]['match_list_summary']);
        $this->assertStringContainsString('Alpha – Beta', $section['checklist'][1]['match_list'][0]['label']);
        $this->assertStringContainsString('Runde 1', $section['checklist'][1]['match_list'][0]['label']);
        $this->assertCount(1, $section['checklist'][2]['match_list']);
        $this->assertSame('Vergangene Spiele ohne Ergebnis/Dauer', $section['checklist'][2]['match_list_summary']);
        $this->assertSame('kein Ergebnis, keine Spieldauer', $section['checklist'][2]['match_list'][0]['detail']);
    }

    #[Test]
    public function matches_section_accepts_incomplete_past_matches_with_status_message(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha']);
        $guest = Team::query()->create(['team_name' => 'Beta']);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
            'match_status' => 'abgesagt',
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][2];

        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertSame([], $section['checklist'][2]['match_list']);
        $this->assertCount(1, $section['checklist'][2]['info_list']);
        $this->assertSame('Vergangene Spiele mit Status-Hinweis', $section['checklist'][2]['info_list_summary']);
        $this->assertStringContainsString('Alpha – Beta', $section['checklist'][2]['info_list'][0]['label']);
        $this->assertSame(
            'kein Ergebnis, keine Spieldauer · Status: abgesagt',
            $section['checklist'][2]['info_list'][0]['detail'],
        );
    }

    #[Test]
    public function teams_section_is_ok_when_all_checks_pass(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $flagsDir = $this->imagesRoot.DIRECTORY_SEPARATOR.'flags';
        mkdir($flagsDir, 0775, true);
        file_put_contents($flagsDir.DIRECTORY_SEPARATOR.'fcb.gif', 'x');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $home = Team::query()->create([
            'team_name' => 'Bayern',
            'team_nationality' => 'fcb',
            'team_status' => 1,
        ]);
        $guest = Team::query()->create([
            'team_name' => 'Germany',
            'team_nationality' => 'ger',
            'team_status' => 1,
        ]);

        $shirtsHome = $this->imagesRoot.DIRECTORY_SEPARATOR.'shirts'.DIRECTORY_SEPARATOR.$home->team_id;
        $shirtsGuest = $this->imagesRoot.DIRECTORY_SEPARATOR.'shirts'.DIRECTORY_SEPARATOR.$guest->team_id;
        mkdir($shirtsHome, 0775, true);
        mkdir($shirtsGuest, 0775, true);
        file_put_contents($shirtsHome.DIRECTORY_SEPARATOR.'fcb.png', 'x');
        file_put_contents($shirtsGuest.DIRECTORY_SEPARATOR.'ger.png', 'x');

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 1,
            'match_guestscore' => 0,
            'match_minutes' => 90,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][3];

        $this->assertSame('teams', $section['key']);
        $this->assertSame('2 Teams', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertSame([], $section['checklist'][0]['match_list']);
        $this->assertSame([], $section['checklist'][1]['match_list']);
        $this->assertSame([], $section['checklist'][2]['match_list']);
    }

    #[Test]
    public function teams_section_lists_teams_failing_checks(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $home = Team::query()->create([
            'team_name' => 'Ghosts',
            'team_nationality' => '',
            'team_status' => 0,
        ]);
        $guest = Team::query()->create([
            'team_name' => 'Germany',
            'team_nationality' => 'ger',
            'team_status' => 1,
        ]);

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 1,
            'match_guestscore' => 0,
            'match_minutes' => 90,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][3];

        $this->assertSame('2 Teams', $section['title']);
        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertSame('Ghosts', $section['checklist'][0]['match_list'][0]['label']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertSame('Ghosts', $section['checklist'][1]['match_list'][0]['label']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $labels = array_column($section['checklist'][2]['match_list'], 'label');
        $this->assertContains('Ghosts', $labels);
        $this->assertContains('Germany', $labels);
    }

    #[Test]
    public function squad_section_is_ok_when_all_checks_pass(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
        ]);

        $this->seedSquadForTeam((int) $league->league_id, (int) $home->team_id, 'A');
        $this->seedSquadForTeam((int) $league->league_id, (int) $guest->team_id, 'B');

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][4];

        $this->assertSame('squad', $section['key']);
        $this->assertSame('Kader: 22 aktive Spieler in 2', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
    }

    #[Test]
    public function squad_section_lists_teams_and_players_failing_checks(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
        ]);

        $shared = Player::query()->create([
            'player_fname' => 'Max',
            'player_lname' => 'Mustermann',
        ]);
        foreach ([$home, $guest] as $team) {
            Playerteam::query()->create([
                'playerteam_player_id' => (int) $shared->player_id,
                'playerteam_team_id' => (int) $team->team_id,
                'playerteam_league_id' => (int) $league->league_id,
                'playerteam_status' => 1,
                'playerteam_player_position' => 'g',
            ]);
        }

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(7)->andReturn([
            'user' => ['user_id' => 7, 'user_nickname' => 'admin', 'photo_url' => '', 'is_ffb_admin' => true],
            'navigation' => [],
            'selected_league_id' => (int) $league->league_id,
            'selected_league' => [
                'league_id' => (int) $league->league_id,
                'league_title' => 'WM 2026',
                'symbol_url' => '/images/ffb/symbols/symbol_game_na.png',
            ],
        ]);

        $payload = (new AdminLeagueDashboardService($adminCenter))->pagePayload(7);
        $section = $payload['sections'][4];

        $this->assertSame('Kader: 2 aktive Spieler in 2', $section['title']);
        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertCount(2, $section['checklist'][0]['match_list']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertStringContainsString('fehlt: D, M, S', $section['checklist'][1]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertSame('Max Mustermann', $section['checklist'][2]['match_list'][0]['label']);
        $this->assertStringContainsString('Alpha', $section['checklist'][2]['match_list'][0]['detail']);
        $this->assertStringContainsString('Beta', $section['checklist'][2]['match_list'][0]['detail']);
    }

    private function seedSquadForTeam(int $leagueId, int $teamId, string $prefix): void
    {
        $positions = ['g', 'd', 'd', 'd', 'd', 'm', 'm', 'm', 'm', 's', 's'];
        foreach ($positions as $index => $position) {
            $player = Player::query()->create([
                'player_fname' => $prefix,
                'player_lname' => 'Player'.($index + 1),
            ]);
            Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => $teamId,
                'playerteam_league_id' => $leagueId,
                'playerteam_status' => 1,
                'playerteam_player_position' => $position,
            ]);
        }
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('ffb_team');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->string('league_symbol')->default('');
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->unsignedInteger('options_league_id');
            $table->string('options_league_rankmode')->default('lc');
            $table->string('options_league_pricemode')->default('dynamic');
            $table->string('options_league_pointsmode')->default('new');
            $table->string('options_league_lcpoints')->default('');
            $table->integer('options_league_remind_hours_before')->default(0);
            $table->integer('options_lineup_max_players')->default(11);
            $table->integer('options_lineup_max_credits')->default(100);
            $table->integer('options_lineup_max_players_team')->default(2);
            $table->integer('options_lineup_min_g')->default(1);
            $table->integer('options_lineup_max_g')->default(1);
            $table->integer('options_lineup_min_d')->default(3);
            $table->integer('options_lineup_max_d')->default(5);
            $table->integer('options_lineup_min_m')->default(3);
            $table->integer('options_lineup_max_m')->default(5);
            $table->integer('options_lineup_min_s')->default(1);
            $table->integer('options_lineup_max_s')->default(3);
            $table->integer('options_score_minutes_threshold_lower')->default(30);
            $table->integer('options_score_minutes_threshold_upper')->default(60);
            $table->integer('options_score_minutes_low')->default(1);
            $table->integer('options_score_minutes_middle')->default(2);
            $table->integer('options_score_minutes_high')->default(3);
            $table->integer('options_score_goals_g')->default(6);
            $table->integer('options_score_goals_d')->default(5);
            $table->integer('options_score_goals_m')->default(4);
            $table->integer('options_score_goals_s')->default(4);
            $table->integer('options_score_assists')->default(3);
            $table->integer('options_score_owngoals')->default(-2);
            $table->integer('options_score_no_oppgoals_g')->default(4);
            $table->integer('options_score_no_oppgoals_d')->default(3);
            $table->integer('options_score_no_oppgoals_m')->default(1);
            $table->integer('options_score_oppgoals_g')->default(-1);
            $table->integer('options_score_oppgoals_d')->default(-1);
            $table->integer('options_score_card_y')->default(-2);
            $table->integer('options_score_card_yr')->default(-4);
            $table->integer('options_score_card_r')->default(-5);
            $table->integer('options_score_penalty_saved')->default(2);
            $table->integer('options_score_penalty_lost')->default(-2);
            $table->integer('options_score_penaltyshootout_save')->default(2);
            $table->integer('options_score_penaltyshootout_lost')->default(-2);
            $table->integer('options_score_penaltyshootout_hit')->default(2);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->tinyInteger('matchround_status')->default(1);
        });

        Schema::create('ffb_matchround_options', function (Blueprint $table) {
            $table->increments('matchround_options_id');
            $table->unsignedInteger('matchround_options_matchround_id');
            $table->integer('matchround_options_lineup_max_players')->default(11);
            $table->float('matchround_options_lineup_max_credits')->default(100);
            $table->integer('matchround_options_lineup_max_players_team')->default(2);
            $table->integer('matchround_options_lineup_min_g')->default(1);
            $table->integer('matchround_options_lineup_min_d')->default(3);
            $table->integer('matchround_options_lineup_min_m')->default(3);
            $table->integer('matchround_options_lineup_min_s')->default(1);
            $table->integer('matchround_options_lineup_max_g')->default(1);
            $table->integer('matchround_options_lineup_max_d')->default(5);
            $table->integer('matchround_options_lineup_max_m')->default(5);
            $table->integer('matchround_options_lineup_max_s')->default(3);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round')->default(0);
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
            $table->string('match_date')->nullable();
            $table->integer('match_homescore')->default(-1);
            $table->integer('match_guestscore')->default(-1);
            $table->integer('match_minutes')->default(0);
            $table->string('match_status')->default('');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id')->default(0);
            $table->unsignedInteger('playerteam_team_id')->default(0);
            $table->unsignedInteger('playerteam_league_id')->default(0);
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 8)->default('');
        });
    }
}
