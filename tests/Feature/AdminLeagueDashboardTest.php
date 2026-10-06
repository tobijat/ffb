<?php

namespace Tests\Feature;

use App\Models\Extremeteam;
use App\Models\Goal;
use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\MatchroundOptions;
use App\Models\Player;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Psgoal;
use App\Models\Team;
use App\Models\Teamprice;
use App\Models\Userscore;
use App\Models\Userteam;
use App\Models\WebUser;
use App\Services\AdminCenterService;
use App\Services\AdminLeagueDashboardService;
use App\Services\ExtremeTeamService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use App\Services\LineupOptionsResolver;
use App\Services\SubstitutionCalculationService;
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

        Schema::dropIfExists('ffb_userscore');
        Schema::dropIfExists('ffb_userteam_substitute_slot');
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('web_user');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_teamprice');
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
                            [
                                'key' => 'round-options-1',
                                'label' => 'Runde A: Runden-Optionen konsistent',
                                'ok' => true,
                                'options_overview' => [
                                    [
                                        'title' => 'Aufstellungslimits',
                                        'items' => [
                                            ['label' => 'Max. Spieler', 'value' => '11'],
                                        ],
                                    ],
                                ],
                            ],
                            ['key' => 'round-matches-2', 'label' => 'Runde B: mindestens 1 Spiel', 'ok' => false],
                            ['key' => 'active-round', 'label' => 'Mindestens 1 aktive Spielrunde', 'ok' => true],
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
                    [
                        'key' => 'playerprice',
                        'title' => 'Preis/Performance',
                        'ok' => false,
                        'checklist' => [
                            [
                                'key' => 'team-prices',
                                'label' => 'Jede Mannschaft hat einen Teampreis',
                                'ok' => false,
                                'match_list_summary' => 'Mannschaften ohne Teampreis',
                                'match_list' => [
                                    ['label' => 'Beta', 'detail' => 'kein Teampreis in dieser Liga'],
                                ],
                            ],
                            [
                                'key' => 'round-performance',
                                'label' => 'Dynamisch: Round-Performance für vergangene Spiele gesetzt',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Stats ohne Round-Performance',
                            ],
                            [
                                'key' => 'player-prices',
                                'label' => 'Dynamisch: Spielerpreise für nächste Spielrunde gesetzt',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Aktive Spieler ohne Spielerpreis',
                            ],
                            [
                                'key' => 'average-lineup-budget',
                                'label' => 'Durchschnitts-Aufstellung ≤ 90% des Budgets',
                                'ok' => true,
                                'info_list' => [],
                                'info_list_summary' => 'Anteil am Budget je Spielrunde',
                            ],
                        ],
                    ],
                    [
                        'key' => 'matchdata',
                        'title' => 'Spieldaten',
                        'ok' => false,
                        'checklist' => [
                            [
                                'key' => 'match-playerstats',
                                'label' => 'Spiele mit Ergebnis haben ≥11 Playerstats je Mannschaft',
                                'ok' => false,
                                'match_list_summary' => 'Spiele mit unzureichenden Playerstats',
                                'match_list' => [
                                    [
                                        'label' => 'Alpha – Beta · 20.05.2026',
                                        'detail' => 'Playerstats Heim 8 / Gast 11 (je ≥ 11 nötig)',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'match-goals',
                                'label' => 'Tore aus Ergebnis, Spielerdaten und ffb_goal stimmen überein',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Spiele mit abweichender Tor-Anzahl zwischen Ergebnis, Spielerdaten und ffb_goal',
                            ],
                            [
                                'key' => 'match-ps-goals',
                                'label' => 'Elfmeter-Treffer aus Ergebnis, Spielerdaten und ffb_psgoal stimmen überein',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Spiele mit abweichender Elfer-Treffer-Anzahl zwischen Ergebnis, Spielerdaten und ffb_psgoal',
                            ],
                        ],
                    ],
                    [
                        'key' => 'extremeteam',
                        'title' => 'Top&Flop',
                        'ok' => true,
                        'checklist' => [
                            [
                                'key' => 'extremeteam-past-rounds',
                                'label' => 'Vergangene Spielrunden haben Top- und Flop-Team',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Spielrunden ohne Top/Flop',
                            ],
                            [
                                'key' => 'extremeteam-options',
                                'label' => 'Top/Flop-Teams erfüllen Limits und Credit-Rahmen',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Top/Flop außerhalb der Limits',
                            ],
                        ],
                    ],
                    [
                        'key' => 'score',
                        'title' => 'Rangliste: 12 Mitspieler',
                        'ok' => true,
                        'checklist' => [
                            [
                                'key' => 'lineup-scores',
                                'label' => 'Für Aufstellungen vergangener Runden entspricht der Score der Summe der Spieler-Scores',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Aufstellungen mit Score-Abweichung',
                            ],
                            [
                                'key' => 'lineup-lc-points',
                                'label' => 'LC-Punkte beendeter Runden sind nach Rang korrekt verteilt',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Aufstellungen mit LC-Abweichung',
                            ],
                            [
                                'key' => 'userscore-sums',
                                'label' => 'Userscore Total/LC entspricht Summe der Aufstellungen',
                                'ok' => true,
                                'match_list' => [],
                                'match_list_summary' => 'Userscores mit Abweichung',
                            ],
                        ],
                    ],
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
            ->assertSee('Mannschaften ohne Teampreis', false)
            ->assertSee('Spiele mit unzureichenden Playerstats', false)
            ->assertSee('Vergangene Spielrunden haben Top- und Flop-Team', false)
            ->assertSee('Top/Flop-Teams erfüllen Limits und Credit-Rahmen', false)
            ->assertSee('Rangliste: 12 Mitspieler', false)
            ->assertSee('Runde A', false)
            ->assertSee('Aufstellungslimits', false)
            ->assertDontSee('Aufstellungslimits (Runde)', false)
            ->assertDontSee('admin-dashboard-groups', false)
            ->assertSee('admin-dashboard-details', false)
            ->assertSee('<details class="admin-dashboard-details" open>', false)
            ->assertDontSee('ausgewählt', false);

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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $leagueSection = $payload['sections'][0];

        $this->assertSame('league', $leagueSection['key']);
        $this->assertSame('Liga: WM 2026', $leagueSection['title']);
        $this->assertTrue($leagueSection['ok']);
        $this->assertTrue($leagueSection['checklist'][0]['ok']);
        $this->assertTrue($leagueSection['checklist'][1]['ok']);
        $this->assertTrue($leagueSection['checklist'][2]['ok']);
        $this->assertTrue($leagueSection['checklist'][3]['ok']);
        $this->assertNotEmpty($leagueSection['checklist'][3]['options_overview']);
        $this->assertArrayNotHasKey('match_list', $leagueSection['checklist'][3]);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $leagueSection = $payload['sections'][0];

        $this->assertFalse($leagueSection['ok']);
        $this->assertFalse($leagueSection['checklist'][0]['ok']);
        $this->assertFalse($leagueSection['checklist'][1]['ok']);
        $this->assertFalse($leagueSection['checklist'][2]['ok']);
        $this->assertFalse($leagueSection['checklist'][3]['ok']);
    }

    #[Test]
    public function league_section_fails_options_check_when_options_row_missing(): void
    {
        $this->createSchema();
        file_put_contents($this->symbolsDir.DIRECTORY_SEPARATOR.'logo.webp', 'x');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => 'logo.webp',
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $leagueSection = $payload['sections'][0];

        $this->assertFalse($leagueSection['ok']);
        $this->assertFalse($leagueSection['checklist'][3]['ok']);
        $this->assertSame('Liga-Optionen sind korrekt gesetzt', $leagueSection['checklist'][3]['label']);
        $this->assertArrayNotHasKey('match_list', $leagueSection['checklist'][3]);
    }

    #[Test]
    public function league_section_lists_inconsistent_options(): void
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
            'options_league_benchmode' => '',
            'options_league_lcpoints' => '8,10,12',
            'options_league_remind_hours_before' => 24,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 15,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 5,
            'options_lineup_max_d' => 3,
            'options_lineup_min_m' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 3,
            'options_lineup_max_s' => 3,
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 0,
            'options_score_minutes_threshold_lower' => 30,
            'options_score_minutes_threshold_upper' => 60,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $leagueSection = $payload['sections'][0];
        $optionsItem = $leagueSection['checklist'][3];

        $this->assertFalse($leagueSection['ok']);
        $this->assertFalse($optionsItem['ok']);
        $this->assertSame('Inkonsistente Liga-Optionen', $optionsItem['match_list_summary']);
        $details = array_column($optionsItem['match_list'], 'detail');
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'Max. Spieler/Team')),
        );
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'nicht absteigend')),
        );
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'Max (3) < Min (5)')),
        );
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'Summe der Positions-Mins')),
        );
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][1];
        $optionsItem = collect($section['checklist'])->firstWhere('key', 'round-options-'.(int) $current->matchround_id);

        $this->assertSame('matchrounds', $section['key']);
        $this->assertSame('Spielrunden (aktuell: 1, zukünftig: 1, vergangen: 1)', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertNotNull($optionsItem);
        $this->assertSame('Aktuelle Runde: Runden-Optionen konsistent', $optionsItem['label']);
        $this->assertTrue($optionsItem['ok']);
        $this->assertNotEmpty($optionsItem['options_overview']);
        $this->assertArrayNotHasKey('match_list', $optionsItem);
        $this->assertTrue(collect($section['checklist'])->every(fn (array $item): bool => (bool) $item['ok']));
        $this->assertArrayNotHasKey('groups', $section);
    }

    #[Test]
    public function matchrounds_section_allows_zero_bench_max_to_disable_substitutes_for_round(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_league_benchmode' => 'cover',
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
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 3,
        ]);

        $current = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Aktuelle Runde',
            'matchround_startdate' => '2026-06-10 12:00:00',
            'matchround_enddate' => '2026-06-20 12:00:00',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $current->matchround_id,
            'match_date' => '2026-06-12 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
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
            'matchround_options_lineup_min_bench' => 0,
            'matchround_options_lineup_max_bench' => 0,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][1];
        $optionsItem = collect($section['checklist'])->firstWhere('key', 'round-options-'.(int) $current->matchround_id);

        $this->assertTrue($section['ok']);
        $this->assertNotNull($optionsItem);
        $this->assertTrue($optionsItem['ok']);
        $this->assertArrayNotHasKey('match_list', $optionsItem);
    }

    #[Test]
    public function matchrounds_section_fails_when_round_options_are_inconsistent(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_lcpoints' => '12,10,8',
            'options_league_benchmode' => '',
        ]);

        $current = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Aktuelle Runde',
            'matchround_startdate' => '2026-06-10 12:00:00',
            'matchround_enddate' => '2026-06-20 12:00:00',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $current->matchround_id,
            'match_date' => '2026-06-12 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_minutes' => 0,
        ]);
        MatchroundOptions::query()->create([
            'matchround_options_matchround_id' => (int) $current->matchround_id,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 100,
            'matchround_options_lineup_max_players_team' => 15,
            'matchround_options_lineup_min_g' => 1,
            'matchround_options_lineup_min_d' => 5,
            'matchround_options_lineup_min_m' => 5,
            'matchround_options_lineup_min_s' => 3,
            'matchround_options_lineup_max_g' => 1,
            'matchround_options_lineup_max_d' => 3,
            'matchround_options_lineup_max_m' => 5,
            'matchround_options_lineup_max_s' => 3,
            'matchround_options_lineup_min_bench' => 0,
            'matchround_options_lineup_max_bench' => 0,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][1];
        $optionsItem = collect($section['checklist'])->firstWhere('key', 'round-options-'.(int) $current->matchround_id);

        $this->assertFalse($section['ok']);
        $this->assertNotNull($optionsItem);
        $this->assertSame('Aktuelle Runde: Runden-Optionen konsistent', $optionsItem['label']);
        $this->assertFalse($optionsItem['ok']);
        $this->assertSame('Inkonsistente Runden-Optionen', $optionsItem['match_list_summary']);
        $details = array_column($optionsItem['match_list'], 'detail');
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'Max. Spieler/Team')),
        );
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'Max (3) < Min (5)')),
        );
        $this->assertTrue(
            collect($details)->contains(fn (string $detail): bool => str_contains($detail, 'Summe der Positions-Mins')),
        );
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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
    public function matches_section_treats_timed_matches_as_past_only_after_two_hours(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-10 19:30:00');

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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][2];

        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertSame([], $section['checklist'][2]['match_list']);
        $this->assertSame([], $section['checklist'][2]['info_list']);
    }

    #[Test]
    public function matches_section_treats_day_only_matches_as_past_from_next_calendar_day(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-11 00:30:00');

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
            'match_date' => '2026-06-10 '.MatchGame::DEFAULT_TIME,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][2];

        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertCount(1, $section['checklist'][2]['match_list']);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
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

    #[Test]
    public function playerprice_section_is_ok_for_non_dynamic_league_with_team_prices(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'constant',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $extraA = Team::query()->create(['team_name' => 'Gamma', 'team_status' => 1]);
        $extraB = Team::query()->create(['team_name' => 'Delta', 'team_status' => 1]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => now()->addDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDays(3)->toDateTimeString(),
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => now()->addDays(2)->toDateTimeString(),
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $extraA->team_id,
            'match_guestteam_id' => (int) $extraB->team_id,
            'match_date' => now()->addDays(2)->toDateTimeString(),
        ]);

        foreach ([$home, $guest, $extraA, $extraB] as $index => $team) {
            $this->seedSquadForTeam((int) $league->league_id, (int) $team->team_id, 'T'.$index);
            Teamprice::query()->create([
                'teamprice_team_id' => (int) $team->team_id,
                'teamprice_matchround_id' => (int) $round->matchround_id,
                'teamprice_price' => 5,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][5];

        $this->assertSame('playerprice', $section['key']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertTrue($section['checklist'][3]['ok']);
        $this->assertSame('average-lineup-budget', $section['checklist'][3]['key']);
        $this->assertNotEmpty($section['checklist'][3]['info_list']);
        $this->assertStringContainsString('% des Budgets', $section['checklist'][3]['info_list'][0]['detail']);
        $this->assertFalse($section['checklist'][3]['info_list'][0]['detail_percent_alert']);
        $this->assertArrayNotHasKey('lineup', $section['checklist'][3]['info_list'][0]);
    }

    #[Test]
    public function playerprice_section_lists_missing_team_prices_performance_and_player_prices(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'dynamic',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $pastRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Vergangen',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-10 23:59:59',
            'matchround_status' => 0,
        ]);
        $nextRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Nächste',
            'matchround_startdate' => '2026-06-20 00:00:00',
            'matchround_enddate' => '2026-06-25 23:59:59',
            'matchround_status' => 1,
        ]);
        $pastMatch = MatchGame::query()->create([
            'match_round' => (int) $pastRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-05 18:00:00',
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $nextRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-21 18:00:00',
        ]);

        Teamprice::query()->create([
            'teamprice_team_id' => (int) $home->team_id,
            'teamprice_matchround_id' => (int) $nextRound->matchround_id,
            'teamprice_price' => 10,
        ]);

        $player = Player::query()->create([
            'player_fname' => 'Max',
            'player_lname' => 'Mustermann',
        ]);
        $playerteam = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $playerteam->playerteam_id,
            'playerstats_matchround_id' => (int) $pastRound->matchround_id,
            'playerstats_match_id' => (int) $pastMatch->match_id,
            'playerstats_minutes' => 90,
            'playerstats_round_performance' => null,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][5];

        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertSame('Beta', $section['checklist'][0]['match_list'][0]['label']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertStringContainsString('Max Mustermann', $section['checklist'][1]['match_list'][0]['label']);
        $this->assertStringContainsString('Round-Performance fehlt', $section['checklist'][1]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertStringContainsString('Max Mustermann', $section['checklist'][2]['match_list'][0]['label']);
        $this->assertStringContainsString('Nächste', $section['checklist'][2]['match_list'][0]['detail']);
    }

    #[Test]
    public function playerprice_section_skips_next_round_prices_while_current_round_is_active(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'dynamic',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $currentRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Aktuell',
            'matchround_startdate' => '2026-06-10 00:00:00',
            'matchround_enddate' => '2026-06-20 23:59:59',
            'matchround_status' => 1,
        ]);
        $nextRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Kommende',
            'matchround_startdate' => '2026-06-25 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $currentRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-12 18:00:00',
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $nextRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-26 18:00:00',
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $home->team_id,
            'teamprice_matchround_id' => (int) $currentRound->matchround_id,
            'teamprice_price' => 10,
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $guest->team_id,
            'teamprice_matchround_id' => (int) $currentRound->matchround_id,
            'teamprice_price' => 12,
        ]);

        $player = Player::query()->create([
            'player_fname' => 'Max',
            'player_lname' => 'Mustermann',
        ]);
        $playerteam = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
        ]);
        Playerprice::query()->create([
            'playerprice_playerteam_id' => (int) $playerteam->playerteam_id,
            'playerprice_matchround_id' => (int) $currentRound->matchround_id,
            'playerprice_price' => 5,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][5];

        $this->assertTrue($section['checklist'][2]['ok']);
        $this->assertSame([], $section['checklist'][2]['match_list']);
    }

    #[Test]
    public function playerprice_section_checks_next_round_prices_after_current_round_has_passed(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-22 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'dynamic',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $pastRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Vergangen',
            'matchround_startdate' => '2026-06-10 00:00:00',
            'matchround_enddate' => '2026-06-20 23:59:59',
            'matchround_status' => 0,
        ]);
        $nextRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Kommende',
            'matchround_startdate' => '2026-06-25 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $pastRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-12 18:00:00',
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $nextRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-26 18:00:00',
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $home->team_id,
            'teamprice_matchround_id' => (int) $pastRound->matchround_id,
            'teamprice_price' => 10,
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $guest->team_id,
            'teamprice_matchround_id' => (int) $pastRound->matchround_id,
            'teamprice_price' => 12,
        ]);

        $player = Player::query()->create([
            'player_fname' => 'Max',
            'player_lname' => 'Mustermann',
        ]);
        Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][5];

        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertStringContainsString('Kommende', $section['checklist'][2]['match_list'][0]['detail']);
        $this->assertStringNotContainsString('Vergangen', $section['checklist'][2]['match_list'][0]['detail']);
    }

    #[Test]
    public function playerprice_section_ignores_missing_round_performance_for_current_matchround(): void
    {
        $this->createSchema();
        $this->travelTo('2026-06-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'dynamic',
        ]);
        $home = Team::query()->create(['team_name' => 'Alpha', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Beta', 'team_status' => 1]);
        $currentRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Aktuell',
            'matchround_startdate' => '2026-06-10 00:00:00',
            'matchround_enddate' => '2026-06-20 23:59:59',
            'matchround_status' => 1,
        ]);
        $currentMatch = MatchGame::query()->create([
            'match_round' => (int) $currentRound->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-12 18:00:00',
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $home->team_id,
            'teamprice_matchround_id' => (int) $currentRound->matchround_id,
            'teamprice_price' => 10,
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $guest->team_id,
            'teamprice_matchround_id' => (int) $currentRound->matchround_id,
            'teamprice_price' => 12,
        ]);

        $player = Player::query()->create([
            'player_fname' => 'Max',
            'player_lname' => 'Mustermann',
        ]);
        $playerteam = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $playerteam->playerteam_id,
            'playerstats_matchround_id' => (int) $currentRound->matchround_id,
            'playerstats_match_id' => (int) $currentMatch->match_id,
            'playerstats_minutes' => 90,
            'playerstats_round_performance' => null,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][5];

        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertSame([], $section['checklist'][1]['match_list']);
    }

    #[Test]
    public function playerprice_section_marks_average_lineup_over_budget_and_lists_players(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'constant',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
        ]);

        $teams = [];
        for ($i = 0; $i < 4; $i++) {
            $teams[] = Team::query()->create(['team_name' => 'Club'.$i, 'team_status' => 1]);
        }

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Teure Runde',
            'matchround_startdate' => now()->addDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDays(3)->toDateTimeString(),
            'matchround_status' => 1,
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $teams[0]->team_id,
            'match_guestteam_id' => (int) $teams[1]->team_id,
            'match_date' => now()->addDays(2)->toDateTimeString(),
        ]);
        MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $teams[2]->team_id,
            'match_guestteam_id' => (int) $teams[3]->team_id,
            'match_date' => now()->addDays(2)->toDateTimeString(),
        ]);

        foreach ($teams as $index => $team) {
            $this->seedSquadForTeam((int) $league->league_id, (int) $team->team_id, 'C'.$index);
            Teamprice::query()->create([
                'teamprice_team_id' => (int) $team->team_id,
                'teamprice_matchround_id' => (int) $round->matchround_id,
                'teamprice_price' => 10,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $item = $payload['sections'][5]['checklist'][3];

        $this->assertSame('average-lineup-budget', $item['key']);
        $this->assertFalse($item['ok']);
        $this->assertSame('Teure Runde', $item['info_list'][0]['label']);
        $this->assertStringContainsString('% des Budgets', $item['info_list'][0]['detail']);
        $this->assertTrue($item['info_list'][0]['detail_percent_alert']);
        $this->assertNotSame('', $item['info_list'][0]['detail_percent']);
        $this->assertArrayNotHasKey('lineup', $item['info_list'][0]);
    }

    #[Test]
    public function playerprice_section_checks_average_lineup_only_for_rounds_with_playerprices_in_dynamic_mode(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'dynamic',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
        ]);

        $teams = [];
        for ($i = 0; $i < 4; $i++) {
            $teams[] = Team::query()->create(['team_name' => 'Dyn'.$i, 'team_status' => 1]);
        }

        $pricedRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Mit Preisen',
            'matchround_startdate' => now()->addDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDays(3)->toDateTimeString(),
            'matchround_status' => 1,
        ]);
        $unpricedRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Ohne Preise',
            'matchround_startdate' => now()->addDays(4)->toDateTimeString(),
            'matchround_enddate' => now()->addDays(6)->toDateTimeString(),
            'matchround_status' => 1,
        ]);

        foreach ([$pricedRound, $unpricedRound] as $round) {
            MatchGame::query()->create([
                'match_round' => (int) $round->matchround_id,
                'match_hometeam_id' => (int) $teams[0]->team_id,
                'match_guestteam_id' => (int) $teams[1]->team_id,
                'match_date' => now()->addDays(2)->toDateTimeString(),
            ]);
            MatchGame::query()->create([
                'match_round' => (int) $round->matchround_id,
                'match_hometeam_id' => (int) $teams[2]->team_id,
                'match_guestteam_id' => (int) $teams[3]->team_id,
                'match_date' => now()->addDays(2)->toDateTimeString(),
            ]);
        }

        foreach ($teams as $index => $team) {
            $this->seedSquadForTeam((int) $league->league_id, (int) $team->team_id, 'D'.$index);
            Teamprice::query()->create([
                'teamprice_team_id' => (int) $team->team_id,
                'teamprice_matchround_id' => (int) $pricedRound->matchround_id,
                'teamprice_price' => 5,
            ]);
            Teamprice::query()->create([
                'teamprice_team_id' => (int) $team->team_id,
                'teamprice_matchround_id' => (int) $unpricedRound->matchround_id,
                'teamprice_price' => 5,
            ]);
        }

        $pricedPlayers = Playerteam::query()
            ->where('playerteam_league_id', (int) $league->league_id)
            ->whereIn('playerteam_team_id', array_map(static fn (Team $team): int => (int) $team->team_id, $teams))
            ->get(['playerteam_id']);
        foreach ($pricedPlayers as $playerteam) {
            Playerprice::query()->create([
                'playerprice_playerteam_id' => (int) $playerteam->playerteam_id,
                'playerprice_matchround_id' => (int) $pricedRound->matchround_id,
                'playerprice_price' => 5,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $item = $payload['sections'][5]['checklist'][3];

        $this->assertTrue($item['ok']);
        $this->assertCount(1, $item['info_list']);
        $this->assertSame('Mit Preisen', $item['info_list'][0]['label']);
        $this->assertStringContainsString('% des Budgets', $item['info_list'][0]['detail']);
        $this->assertFalse($item['info_list'][0]['detail_percent_alert']);
    }

    #[Test]
    public function playerprice_section_checks_average_lineup_with_full_bench_against_full_budget(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pricemode' => 'constant',
            'options_league_benchmode' => 'cover',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 3,
        ]);

        $teams = [];
        for ($i = 0; $i < 6; $i++) {
            $teams[] = Team::query()->create(['team_name' => 'Bench'.$i, 'team_status' => 1]);
        }

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Bank-Runde',
            'matchround_startdate' => now()->addDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDays(3)->toDateTimeString(),
            'matchround_status' => 1,
        ]);
        for ($i = 0; $i < 6; $i += 2) {
            MatchGame::query()->create([
                'match_round' => (int) $round->matchround_id,
                'match_hometeam_id' => (int) $teams[$i]->team_id,
                'match_guestteam_id' => (int) $teams[$i + 1]->team_id,
                'match_date' => now()->addDays(2)->toDateTimeString(),
            ]);
        }

        foreach ($teams as $index => $team) {
            $this->seedSquadForTeam((int) $league->league_id, (int) $team->team_id, 'B'.$index);
            Teamprice::query()->create([
                'teamprice_team_id' => (int) $team->team_id,
                'teamprice_matchround_id' => (int) $round->matchround_id,
                'teamprice_price' => 8,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][5];
        $startersItem = $section['checklist'][3];
        $benchItem = $section['checklist'][4];

        $this->assertSame('average-lineup-budget', $startersItem['key']);
        $this->assertTrue($startersItem['ok']);
        $this->assertSame('average-lineup-budget-with-bench', $benchItem['key']);
        $this->assertFalse($benchItem['ok']);
        $this->assertSame('Bank-Runde', $benchItem['info_list'][0]['label']);
        $this->assertStringContainsString('% des Budgets', $benchItem['info_list'][0]['detail']);
        $this->assertTrue($benchItem['info_list'][0]['detail_percent_alert']);
        $this->assertArrayNotHasKey('lineup', $benchItem['info_list'][0]);
    }

    #[Test]
    public function matchdata_section_is_ok_when_stats_and_goals_match_result(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'old',
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
        $match = MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 2,
            'match_guestscore' => 1,
            'match_homescore_penalty' => 4,
            'match_guestscore_penalty' => 3,
        ]);

        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $home->team_id, 11, 'H');
        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $guest->team_id, 11, 'G');
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $home->team_id, 2);
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $guest->team_id, 1);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $home->team_id, 4);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $guest->team_id, 3);

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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][6];

        $this->assertSame('matchdata', $section['key']);
        $this->assertTrue($section['ok']);
        $this->assertCount(3, $section['checklist']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
    }

    #[Test]
    public function matchdata_section_lists_matches_failing_stats_and_goal_checks(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'old',
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
        $match = MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 2,
            'match_guestscore' => 0,
            'match_homescore_penalty' => 5,
            'match_guestscore_penalty' => 4,
        ]);

        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $home->team_id, 8, 'H');
        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $guest->team_id, 11, 'G');
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $home->team_id, 1);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $home->team_id, 1);

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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][6];

        $this->assertFalse($section['ok']);
        $this->assertCount(3, $section['checklist']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertStringContainsString('Playerstats Heim 8 / Gast 11', $section['checklist'][0]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertStringContainsString('Heim 1 / Gast 0 (erwartet 2 / 0)', $section['checklist'][1]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertStringContainsString('Heim 1 / Gast 0 (erwartet 5 / 4)', $section['checklist'][2]['match_list'][0]['detail']);
    }

    #[Test]
    public function matchdata_section_checks_ffb_goal_and_psgoal_in_new_points_mode(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
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
        $match = MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 2,
            'match_guestscore' => 1,
            'match_homescore_penalty' => 4,
            'match_guestscore_penalty' => 3,
        ]);

        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $home->team_id, 11, 'H');
        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $guest->team_id, 11, 'G');
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $home->team_id, 2);
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $guest->team_id, 1);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $home->team_id, 4);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $guest->team_id, 3);

        $homePlayerteamId = (int) Playerteam::query()
            ->where('playerteam_team_id', (int) $home->team_id)
            ->orderBy('playerteam_id')
            ->value('playerteam_id');
        $guestPlayerteamId = (int) Playerteam::query()
            ->where('playerteam_team_id', (int) $guest->team_id)
            ->orderBy('playerteam_id')
            ->value('playerteam_id');

        foreach ([10, 20] as $minute) {
            Goal::query()->forceCreate([
                'goal_match_id' => (int) $match->match_id,
                'goal_playerteam_id' => $homePlayerteamId,
                'goal_minute' => $minute,
                'goal_owngoal' => 0,
                'goal_penalty' => 0,
                'goal_penaltyshootout' => 0,
            ]);
        }
        Goal::query()->forceCreate([
            'goal_match_id' => (int) $match->match_id,
            'goal_playerteam_id' => $guestPlayerteamId,
            'goal_minute' => 55,
            'goal_owngoal' => 0,
            'goal_penalty' => 0,
            'goal_penaltyshootout' => 0,
        ]);
        foreach (range(1, 4) as $_) {
            Psgoal::query()->forceCreate([
                'psgoal_match_id' => (int) $match->match_id,
                'psgoal_playerteam_id' => $homePlayerteamId,
                'psgoal_minute' => 120,
                'psgoal_hit' => 1,
                'psgoal_fail' => 0,
            ]);
        }
        foreach (range(1, 3) as $_) {
            Psgoal::query()->forceCreate([
                'psgoal_match_id' => (int) $match->match_id,
                'psgoal_playerteam_id' => $guestPlayerteamId,
                'psgoal_minute' => 120,
                'psgoal_hit' => 1,
                'psgoal_fail' => 0,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][6];

        $this->assertTrue($section['ok']);
        $this->assertCount(3, $section['checklist']);
        $this->assertSame('match-goals', $section['checklist'][1]['key']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertSame('match-ps-goals', $section['checklist'][2]['key']);
        $this->assertTrue($section['checklist'][2]['ok']);
    }

    #[Test]
    public function matchdata_section_lists_ffb_goal_and_psgoal_failures_in_new_points_mode(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
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
        $match = MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-06-10 18:00:00',
            'match_homescore' => 2,
            'match_guestscore' => 1,
            'match_homescore_penalty' => 4,
            'match_guestscore_penalty' => 3,
        ]);

        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $home->team_id, 11, 'H');
        $this->seedMatchPlayerstats((int) $match->match_id, (int) $league->league_id, (int) $guest->team_id, 11, 'G');
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $home->team_id, 2);
        $this->setTeamPlayerstatsGoals((int) $match->match_id, (int) $guest->team_id, 1);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $home->team_id, 4);
        $this->setTeamPenaltyShootoutHits((int) $match->match_id, (int) $guest->team_id, 3);

        $homePlayerteamId = (int) Playerteam::query()
            ->where('playerteam_team_id', (int) $home->team_id)
            ->orderBy('playerteam_id')
            ->value('playerteam_id');

        Goal::query()->forceCreate([
            'goal_match_id' => (int) $match->match_id,
            'goal_playerteam_id' => $homePlayerteamId,
            'goal_minute' => 10,
            'goal_owngoal' => 0,
            'goal_penalty' => 0,
            'goal_penaltyshootout' => 0,
        ]);
        Psgoal::query()->forceCreate([
            'psgoal_match_id' => (int) $match->match_id,
            'psgoal_playerteam_id' => $homePlayerteamId,
            'psgoal_minute' => 120,
            'psgoal_hit' => 1,
            'psgoal_fail' => 0,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][6];

        $this->assertFalse($section['ok']);
        $this->assertCount(3, $section['checklist']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertSame('match-goals', $section['checklist'][1]['key']);
        $this->assertStringContainsString('Ergebnis 2:1 · Spielerdaten 2:1 · ffb_goal 1:0', $section['checklist'][1]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertSame('match-ps-goals', $section['checklist'][2]['key']);
        $this->assertStringContainsString('Elfmeter 4:3 · Spielerdaten 4:3 · ffb_psgoal 1:0', $section['checklist'][2]['match_list'][0]['detail']);
    }

    #[Test]
    public function substitutions_section_is_absent_when_league_has_no_bench_mode(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_benchmode' => '',
            'options_lineup_max_bench' => 0,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $keys = array_column($payload['sections'], 'key');

        $this->assertNotContains('substitutions', $keys);
        $this->assertSame('matchdata', $keys[6]);
        $this->assertSame('extremeteam', $keys[7]);
    }

    #[Test]
    public function substitutions_section_is_ok_for_past_bench_rounds_without_userteams(): void
    {
        $this->createSchema();
        $this->travelTo('2026-07-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_benchmode' => 'cover',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_max_d' => 5,
            'options_lineup_min_m' => 3,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 1,
            'options_lineup_max_s' => 3,
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 3,
        ]);
        $pastRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde mit Bank',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchroundOptions::query()->create([
            'matchround_options_matchround_id' => (int) $pastRound->matchround_id,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 100,
            'matchround_options_lineup_max_players_team' => 3,
            'matchround_options_lineup_min_g' => 1,
            'matchround_options_lineup_min_d' => 3,
            'matchround_options_lineup_min_m' => 3,
            'matchround_options_lineup_min_s' => 1,
            'matchround_options_lineup_max_g' => 1,
            'matchround_options_lineup_max_d' => 5,
            'matchround_options_lineup_max_m' => 5,
            'matchround_options_lineup_max_s' => 3,
            'matchround_options_lineup_min_bench' => 0,
            'matchround_options_lineup_max_bench' => 3,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = collect($payload['sections'])->firstWhere('key', 'substitutions');

        $this->assertNotNull($section);
        $this->assertSame('Auswechslungen: 1 Spielrunde mit Bank', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertSame([], $section['checklist'][0]['match_list']);
    }

    #[Test]
    public function substitutions_section_lists_past_bench_rounds_without_calculated_substitutions(): void
    {
        $this->createSchema();
        $this->travelTo('2026-07-15 12:00:00');

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_benchmode' => 'cover',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_max_d' => 5,
            'options_lineup_min_m' => 3,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 1,
            'options_lineup_max_s' => 3,
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 3,
        ]);
        $pastRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde ohne Berechnung',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        $disabledRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde ohne Bank',
            'matchround_startdate' => '2026-05-01 00:00:00',
            'matchround_enddate' => '2026-05-30 23:59:59',
            'matchround_status' => 1,
        ]);
        MatchroundOptions::query()->create([
            'matchround_options_matchround_id' => (int) $pastRound->matchround_id,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 100,
            'matchround_options_lineup_max_players_team' => 3,
            'matchround_options_lineup_min_g' => 1,
            'matchround_options_lineup_min_d' => 3,
            'matchround_options_lineup_min_m' => 3,
            'matchround_options_lineup_min_s' => 1,
            'matchround_options_lineup_max_g' => 1,
            'matchround_options_lineup_max_d' => 5,
            'matchround_options_lineup_max_m' => 5,
            'matchround_options_lineup_max_s' => 3,
            'matchround_options_lineup_min_bench' => 0,
            'matchround_options_lineup_max_bench' => 3,
        ]);
        MatchroundOptions::query()->create([
            'matchround_options_matchround_id' => (int) $disabledRound->matchround_id,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 100,
            'matchround_options_lineup_max_players_team' => 3,
            'matchround_options_lineup_min_g' => 1,
            'matchround_options_lineup_min_d' => 3,
            'matchround_options_lineup_min_m' => 3,
            'matchround_options_lineup_min_s' => 1,
            'matchround_options_lineup_max_g' => 1,
            'matchround_options_lineup_max_d' => 5,
            'matchround_options_lineup_max_m' => 5,
            'matchround_options_lineup_max_s' => 3,
            'matchround_options_lineup_min_bench' => 0,
            'matchround_options_lineup_max_bench' => 0,
        ]);

        $substitutions = Mockery::mock(SubstitutionCalculationService::class);
        $substitutions->shouldReceive('previewForRound')
            ->once()
            ->with((int) $league->league_id, (int) $pastRound->matchround_id)
            ->andReturn([
                'ok' => true,
                'preview' => [
                    'rows' => [
                        [
                            'substitutions' => [
                                [
                                    'substitute_playerteam_id' => 20,
                                    'out_playerteam_id' => 10,
                                ],
                            ],
                            'previous_replaces' => [
                                20 => null,
                                21 => null,
                            ],
                        ],
                    ],
                ],
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), $substitutions))->pagePayload(7);
        $section = collect($payload['sections'])->firstWhere('key', 'substitutions');

        $this->assertNotNull($section);
        $this->assertSame('Auswechslungen: 1 Spielrunde mit Bank', $section['title']);
        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertCount(1, $section['checklist'][0]['match_list']);
        $this->assertSame('Runde ohne Berechnung', $section['checklist'][0]['match_list'][0]['label']);
        $this->assertStringContainsString('erwartet 1 Wechsel', $section['checklist'][0]['match_list'][0]['detail']);
    }

    #[Test]
    public function extremeteam_section_is_ok_when_past_rounds_have_top_and_flop(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_max_d' => 5,
            'options_lineup_min_m' => 3,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 1,
            'options_lineup_max_s' => 3,
        ]);
        $pastRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 2',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-31 23:59:59',
            'matchround_status' => 1,
        ]);

        $slotIds = $this->seedCompliantExtremeLineup((int) $pastRound->matchround_id, (int) $league->league_id);
        foreach (['top', 'flop'] as $type) {
            $team = Extremeteam::query()->create([
                'extremeteam_matchround_id' => (int) $pastRound->matchround_id,
                'extremeteam_top_or_flop' => $type,
                'extremeteam_price' => 55,
                'extremeteam_score' => 42,
            ]);
            $team->syncSlots($slotIds);
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][7];

        $this->assertSame('extremeteam', $section['key']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertTrue($section['checklist'][1]['ok']);
    }

    #[Test]
    public function extremeteam_section_lists_past_rounds_missing_top_or_flop(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $pastRound = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-06-01 00:00:00',
            'matchround_enddate' => '2026-06-30 23:59:59',
            'matchround_status' => 1,
        ]);
        Extremeteam::query()->create([
            'extremeteam_matchround_id' => (int) $pastRound->matchround_id,
            'extremeteam_top_or_flop' => 'top',
            'extremeteam_price' => 100,
            'extremeteam_score' => 42,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][7];

        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertSame('Runde 1', $section['checklist'][0]['match_list'][0]['label']);
        $this->assertStringContainsString('Flop fehlt', $section['checklist'][0]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertStringContainsString('Top', $section['checklist'][1]['match_list'][0]['label']);
    }

    /**
     * @return list<int>
     */
    private function seedCompliantExtremeLineup(int $matchroundId, int $leagueId): array
    {
        $clubs = [];
        for ($i = 0; $i < 4; $i++) {
            $clubs[] = Team::query()->create([
                'team_name' => 'Club'.$i,
                'team_status' => 1,
            ]);
        }

        $positions = ['g', 'd', 'd', 'd', 'd', 'm', 'm', 'm', 'm', 's', 's'];
        $ids = [];
        foreach ($positions as $index => $position) {
            $player = Player::query()->create([
                'player_fname' => 'E',
                'player_lname' => 'P'.$index,
            ]);
            $playerteam = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $clubs[$index % 4]->team_id,
                'playerteam_league_id' => $leagueId,
                'playerteam_status' => 1,
                'playerteam_player_position' => $position,
            ]);
            Playerprice::query()->create([
                'playerprice_playerteam_id' => (int) $playerteam->playerteam_id,
                'playerprice_matchround_id' => $matchroundId,
                'playerprice_price' => 5,
            ]);
            $ids[] = (int) $playerteam->playerteam_id;
        }

        return $ids;
    }

    #[Test]
    public function score_section_is_ok_when_lineup_and_userscores_match(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pointsmode' => 'new',
            'options_league_lcpoints' => '8,3',
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
            'match_date' => '2026-06-10 '.MatchGame::DEFAULT_TIME,
            'match_homescore' => 1,
            'match_guestscore' => 0,
        ]);

        $winner = WebUser::query()->forceCreate([
            'user_nickname' => 'AlphaUser',
            'user_email' => 'alpha@example.com',
        ]);
        $loser = WebUser::query()->forceCreate([
            'user_nickname' => 'BetaUser',
            'user_email' => 'beta@example.com',
        ]);
        $winnerTeam = Userteam::query()->forceCreate([
            'userteam_user_id' => (int) $winner->user_id,
            'userteam_matchround_id' => (int) $round->matchround_id,
            'userteam_score' => 42,
            'userteam_lc_points' => 8,
            'userteam_price' => 100,
        ]);
        $loserTeam = Userteam::query()->forceCreate([
            'userteam_user_id' => (int) $loser->user_id,
            'userteam_matchround_id' => (int) $round->matchround_id,
            'userteam_score' => 10,
            'userteam_lc_points' => 3,
            'userteam_price' => 100,
        ]);
        $playerA = Player::query()->create(['player_fname' => 'A', 'player_lname' => 'One']);
        $playerB = Player::query()->create(['player_fname' => 'B', 'player_lname' => 'Two']);
        $playerC = Player::query()->create(['player_fname' => 'C', 'player_lname' => 'Three']);
        $playerteamA = Playerteam::query()->create([
            'playerteam_player_id' => (int) $playerA->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
        ]);
        $playerteamB = Playerteam::query()->create([
            'playerteam_player_id' => (int) $playerB->player_id,
            'playerteam_team_id' => (int) $guest->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 's',
        ]);
        $playerteamC = Playerteam::query()->create([
            'playerteam_player_id' => (int) $playerC->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
        ]);
        $winnerTeam->syncSlots([(int) $playerteamA->playerteam_id, (int) $playerteamB->playerteam_id]);
        $loserTeam->syncSlots([(int) $playerteamC->playerteam_id]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $playerteamA->playerteam_id,
            'playerstats_matchround_id' => (int) $round->matchround_id,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_owngoals' => 0,
            'playerstats_penaltyshootout_hit' => 0,
            'playerstats_score' => 25,
            'playerstats_round_performance' => null,
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $playerteamB->playerteam_id,
            'playerstats_matchround_id' => (int) $round->matchround_id,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_owngoals' => 0,
            'playerstats_penaltyshootout_hit' => 0,
            'playerstats_score' => 17,
            'playerstats_round_performance' => null,
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $playerteamC->playerteam_id,
            'playerstats_matchround_id' => (int) $round->matchround_id,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_owngoals' => 0,
            'playerstats_penaltyshootout_hit' => 0,
            'playerstats_score' => 10,
            'playerstats_round_performance' => null,
        ]);
        Userscore::query()->forceCreate([
            'userscore_user_id' => (int) $winner->user_id,
            'userscore_league_id' => (int) $league->league_id,
            'userscore_total' => 42,
            'userscore_lc_points' => 8,
        ]);
        Userscore::query()->forceCreate([
            'userscore_user_id' => (int) $loser->user_id,
            'userscore_league_id' => (int) $league->league_id,
            'userscore_total' => 10,
            'userscore_lc_points' => 3,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][8];

        $this->assertSame('score', $section['key']);
        $this->assertSame('Rangliste: 2 Mitspieler', $section['title']);
        $this->assertTrue($section['ok']);
        $this->assertTrue($section['checklist'][0]['ok']);
        $this->assertSame('lineup-lc-points', $section['checklist'][1]['key']);
        $this->assertTrue($section['checklist'][1]['ok']);
        $this->assertTrue($section['checklist'][2]['ok']);
    }

    #[Test]
    public function score_section_lists_missing_lineup_scores_and_userscore_mismatches(): void
    {
        $this->createSchema();

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pointsmode' => 'new',
            'options_league_lcpoints' => '8,3',
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
            'match_date' => '2026-06-10 '.MatchGame::DEFAULT_TIME,
            'match_homescore' => 1,
            'match_guestscore' => 0,
        ]);

        $user = WebUser::query()->forceCreate([
            'user_nickname' => 'PlayerOne',
            'user_email' => 'one@example.com',
        ]);
        $userteam = Userteam::query()->forceCreate([
            'userteam_user_id' => (int) $user->user_id,
            'userteam_matchround_id' => (int) $round->matchround_id,
            'userteam_score' => 10,
            'userteam_lc_points' => 3,
            'userteam_price' => 100,
        ]);
        $player = Player::query()->create(['player_fname' => 'A', 'player_lname' => 'One']);
        $playerteam = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
        ]);
        $userteam->syncSlots([(int) $playerteam->playerteam_id]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $playerteam->playerteam_id,
            'playerstats_matchround_id' => (int) $round->matchround_id,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_owngoals' => 0,
            'playerstats_penaltyshootout_hit' => 0,
            'playerstats_score' => 7,
            'playerstats_round_performance' => null,
        ]);
        Userscore::query()->forceCreate([
            'userscore_user_id' => (int) $user->user_id,
            'userscore_league_id' => (int) $league->league_id,
            'userscore_total' => 99,
            'userscore_lc_points' => 2,
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

        $payload = (new AdminLeagueDashboardService($adminCenter, app(ExtremeTeamService::class), app(LineupOptionsResolver::class), app(SubstitutionCalculationService::class)))->pagePayload(7);
        $section = $payload['sections'][8];

        $this->assertFalse($section['ok']);
        $this->assertFalse($section['checklist'][0]['ok']);
        $this->assertStringContainsString('Score 10 ≠ Summe Spieler 7', $section['checklist'][0]['match_list'][0]['detail']);
        $this->assertSame('lineup-lc-points', $section['checklist'][1]['key']);
        $this->assertFalse($section['checklist'][1]['ok']);
        $this->assertStringContainsString('LC 3 ≠ erwartet 8', $section['checklist'][1]['match_list'][0]['detail']);
        $this->assertFalse($section['checklist'][2]['ok']);
        $this->assertStringContainsString('Total 99 ≠ Summe userteam 10', $section['checklist'][2]['match_list'][0]['detail']);
    }

    private function seedMatchPlayerstats(int $matchId, int $leagueId, int $teamId, int $count, string $prefix): void
    {
        for ($i = 0; $i < $count; $i++) {
            $player = Player::query()->create([
                'player_fname' => $prefix,
                'player_lname' => 'P'.($i + 1),
            ]);
            $playerteam = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => $teamId,
                'playerteam_league_id' => $leagueId,
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_playerteam_id' => (int) $playerteam->playerteam_id,
                'playerstats_matchround_id' => 1,
                'playerstats_match_id' => $matchId,
                'playerstats_minutes' => 90,
                'playerstats_goals' => 0,
                'playerstats_owngoals' => 0,
                'playerstats_round_performance' => null,
                'playerstats_penaltyshootout_hit' => 0,
            ]);
        }
    }

    private function setTeamPlayerstatsGoals(int $matchId, int $teamId, int $goals): void
    {
        $stat = Playerstats::query()
            ->where('playerstats_match_id', $matchId)
            ->whereHas('playerteam', function ($query) use ($teamId): void {
                $query->where('playerteam_team_id', $teamId);
            })
            ->orderBy('playerstats_id')
            ->first();

        if ($stat === null) {
            return;
        }

        $stat->playerstats_goals = $goals;
        $stat->save();
    }

    private function setTeamPenaltyShootoutHits(int $matchId, int $teamId, int $hits): void
    {
        $stat = Playerstats::query()
            ->where('playerstats_match_id', $matchId)
            ->whereHas('playerteam', function ($query) use ($teamId): void {
                $query->where('playerteam_team_id', $teamId);
            })
            ->orderBy('playerstats_id')
            ->first();

        if ($stat === null) {
            return;
        }

        $stat->playerstats_penaltyshootout_hit = $hits;
        $stat->save();
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
        Schema::dropIfExists('ffb_userscore');
        Schema::dropIfExists('ffb_userteam_substitute_slot');
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('web_user');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_teamprice');
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
            $table->string('options_league_benchmode')->nullable();
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
            $table->integer('options_lineup_min_bench')->default(0);
            $table->integer('options_lineup_max_bench')->default(0);
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
            $table->integer('matchround_options_lineup_min_bench')->default(0);
            $table->integer('matchround_options_lineup_max_bench')->default(0);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round')->default(0);
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
            $table->string('match_date')->nullable();
            $table->integer('match_homescore')->default(-1);
            $table->integer('match_guestscore')->default(-1);
            $table->integer('match_homescore_penalty')->default(-1);
            $table->integer('match_guestscore_penalty')->default(-1);
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

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->unsignedInteger('playerstats_match_id')->nullable();
            $table->integer('playerstats_minutes')->default(0);
            $table->integer('playerstats_goals')->default(0);
            $table->integer('playerstats_owngoals')->default(0);
            $table->integer('playerstats_penaltyshootout_hit')->default(0);
            $table->integer('playerstats_score')->default(0);
            $table->double('playerstats_round_performance')->nullable();
        });

        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
        });

        Schema::create('ffb_goal', function (Blueprint $table) {
            $table->increments('goal_id');
            $table->unsignedInteger('goal_match_id');
            $table->unsignedInteger('goal_playerteam_id')->default(0);
            $table->integer('goal_minute')->default(0);
            $table->tinyInteger('goal_owngoal')->default(0);
            $table->tinyInteger('goal_penalty')->default(0);
            $table->tinyInteger('goal_penaltyshootout')->default(0);
        });

        Schema::create('ffb_psgoal', function (Blueprint $table) {
            $table->increments('psgoal_id');
            $table->unsignedInteger('psgoal_match_id');
            $table->unsignedInteger('psgoal_playerteam_id')->default(0);
            $table->integer('psgoal_minute')->default(120);
            $table->tinyInteger('psgoal_hit')->default(0);
            $table->tinyInteger('psgoal_fail')->default(0);
        });

        Schema::create('ffb_extremeteam', function (Blueprint $table) {
            $table->increments('extremeteam_id');
            $table->string('extremeteam_top_or_flop', 8);
            $table->decimal('extremeteam_price', 9, 2)->default(0);
            $table->unsignedInteger('extremeteam_matchround_id');
            $table->integer('extremeteam_score')->default(-1);
        });

        Schema::create('ffb_extremeteam_slot', function (Blueprint $table) {
            $table->increments('extremeteam_slot_id');
            $table->unsignedInteger('extremeteam_slot_extremeteam_id');
            $table->unsignedTinyInteger('extremeteam_slot_slot');
            $table->unsignedInteger('extremeteam_slot_playerteam_id');
        });

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
        });

        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->increments('userteam_id');
            $table->unsignedInteger('userteam_user_id')->default(0);
            $table->unsignedInteger('userteam_matchround_id')->nullable();
            $table->double('userteam_price')->default(0);
            $table->double('userteam_score')->nullable();
            $table->double('userteam_lc_points')->nullable();
            $table->string('userteam_date')->nullable();
        });

        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
            $table->unique(['userteam_slot_userteam_id', 'userteam_slot_slot']);
        });

        Schema::create('ffb_userteam_substitute_slot', function (Blueprint $table) {
            $table->increments('substitute_slot_id');
            $table->unsignedInteger('substitute_slot_userteam_id');
            $table->unsignedTinyInteger('substitute_slot_slot');
            $table->unsignedInteger('substitute_slot_playerteam_id');
            $table->unsignedInteger('substitute_slot_replaces_playerteam_id')->nullable();
            $table->unique(['substitute_slot_userteam_id', 'substitute_slot_slot']);
        });

        Schema::create('ffb_userscore', function (Blueprint $table) {
            $table->increments('userscore_id');
            $table->unsignedInteger('userscore_user_id');
            $table->unsignedInteger('userscore_league_id');
            $table->integer('userscore_total')->default(0);
            $table->integer('userscore_lc_points')->default(0);
        });
    }
}
