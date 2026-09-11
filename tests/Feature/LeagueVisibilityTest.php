<?php

namespace Tests\Feature;

use App\Services\DashboardService;
use App\Services\FfbAdminAccess;
use App\Services\ProfilePopupService;
use App\Services\StartPageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class LeagueVisibilityTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);
        $this->createVisibilityExtraTables();
        $this->seedLeagues();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_userscore');
        Schema::dropIfExists('ffb_poll');
        Schema::dropIfExists('ffb_news');
        Schema::dropIfExists('web_user_permissions');
        Schema::dropIfExists('web_admin');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    #[Test]
    public function start_page_lists_only_visible_non_archived_leagues(): void
    {
        $titles = collect(app(StartPageService::class)->payload()['leagues'])
            ->pluck('league_title')
            ->all();

        $this->assertSame(['Visible Current'], $titles);
    }

    #[Test]
    public function non_admin_dashboard_hides_invisible_leagues_in_current_and_archive(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(false);
        });

        $dashboard = app(DashboardService::class);

        $current = collect($dashboard->payload(10, 1, false)['leagues'])->pluck('league_title')->all();
        $archive = collect($dashboard->payload(10, 1, true)['leagues'])->pluck('league_title')->all();

        $this->assertSame(['Visible Current'], $current);
        $this->assertSame(['Visible Archive'], $archive);
    }

    #[Test]
    public function admin_dashboard_shows_invisible_and_empty_leagues_faded_without_title_hint(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 5,
            'league_title' => 'Empty Current',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $dashboard = app(DashboardService::class);

        $current = collect($dashboard->payload(10, 1, false)['leagues'])
            ->keyBy('league_id');
        $archive = collect($dashboard->payload(10, 1, true)['leagues'])
            ->keyBy('league_id');

        $this->assertSame('Visible Current', $current[1]['league_title']);
        $this->assertFalse($current[1]['is_faded']);
        $this->assertTrue($current[1]['has_matchrounds']);

        $this->assertSame('Hidden Current', $current[2]['league_title']);
        $this->assertSame(0, $current[2]['league_visible']);
        $this->assertTrue($current[2]['is_faded']);

        $this->assertSame('Empty Current', $current[5]['league_title']);
        $this->assertFalse($current[5]['has_matchrounds']);
        $this->assertTrue($current[5]['is_faded']);

        $this->assertSame('Visible Archive', $archive[3]['league_title']);
        $this->assertSame('Hidden Archive', $archive[4]['league_title']);
        $this->assertTrue($archive[4]['is_faded']);
    }

    #[Test]
    public function non_admin_dashboard_hides_empty_leagues(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 5,
            'league_title' => 'Empty Current',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(false);
        });

        $titles = collect(app(DashboardService::class)->payload(10, 1, false)['leagues'])
            ->pluck('league_title')
            ->all();

        $this->assertSame(['Visible Current'], $titles);
    }

    #[Test]
    public function current_dashboard_keeps_selected_archived_league_visible(): void
    {
        DB::table('web_user_details')->where('user_id', 10)->update([
            'user_details_ffb_selected_league' => 3,
        ]);

        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(false);
        });

        $leagues = collect(app(DashboardService::class)->payload(10, 1, false)['leagues'])
            ->keyBy('league_id');

        $this->assertTrue($leagues->has(1));
        $this->assertTrue($leagues->has(3));
        $this->assertSame('Visible Archive', $leagues[3]['league_title']);
        $this->assertSame(1, $leagues[3]['league_archive']);
        $this->assertFalse($leagues->has(4));
    }

    #[Test]
    public function non_admin_cannot_select_invisible_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(false);
        });

        $result = app(DashboardService::class)->selectLeague(10, 2);

        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['status']);
    }

    #[Test]
    public function admin_can_select_invisible_league(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $result = app(DashboardService::class)->selectLeague(10, 2);

        $this->assertTrue($result['ok']);
        $this->assertSame(2, $result['selected_league_id']);
        $this->assertSame('Hidden Current', $result['league_title']);
        $this->assertSame(2, (int) DB::table('web_user_details')->where('user_id', 10)->value('user_details_ffb_selected_league'));
    }

    #[Test]
    public function profile_participations_respect_viewer_admin_and_visibility(): void
    {
        DB::table('ffb_userscore')->insert([
            [
                'userscore_id' => 1,
                'userscore_user_id' => 20,
                'userscore_league_id' => 1,
                'userscore_total' => 10,
                'userscore_lc_points' => 5,
            ],
            [
                'userscore_id' => 2,
                'userscore_user_id' => 20,
                'userscore_league_id' => 2,
                'userscore_total' => 8,
                'userscore_lc_points' => 3,
            ],
        ]);

        DB::table('ffb_league_options')->insert([
            'options_id' => 1,
            'options_league_id' => 1,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_min_m' => 3,
            'options_lineup_min_s' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_max_d' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_max_s' => 3,
            'options_league_pricemode' => 'constant',
            'options_league_pointsmode' => 'new',
        ]);
        DB::table('ffb_league_options')->insert([
            'options_id' => 2,
            'options_league_id' => 2,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_min_m' => 3,
            'options_lineup_min_s' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_max_d' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_max_s' => 3,
            'options_league_pricemode' => 'constant',
            'options_league_pointsmode' => 'new',
        ]);

        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(10)->andReturn(false);
            $mock->shouldReceive('isAdmin')->with(11)->andReturn(true);
        });

        $nonAdmin = app(ProfilePopupService::class)->forUser(10, 20);
        $admin = app(ProfilePopupService::class)->forUser(11, 20);

        $this->assertTrue($nonAdmin['ok']);
        $this->assertTrue($admin['ok']);

        $nonAdminTitles = collect($nonAdmin['data']['participations'])->pluck('league_title')->all();
        $adminTitles = collect($admin['data']['participations'])->pluck('league_title')->all();

        $this->assertSame(['Visible Current'], $nonAdminTitles);
        $this->assertSame(['Hidden Current', 'Visible Current'], $adminTitles);
    }

    private function createVisibilityExtraTables(): void
    {
        Schema::dropIfExists('ffb_userscore');
        Schema::dropIfExists('ffb_poll');
        Schema::dropIfExists('ffb_news');
        Schema::dropIfExists('web_user_permissions');
        Schema::dropIfExists('web_admin');
        Schema::dropIfExists('web_user');

        Schema::create('web_user', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('user_nickname')->default('');
            $table->string('user_fname')->nullable();
            $table->string('user_lname')->nullable();
            $table->string('user_gender')->nullable();
            $table->string('user_status')->default('active');
            $table->string('user_date_llogin')->nullable();
            $table->string('user_date_register')->nullable();
        });

        Schema::create('web_admin', function (Blueprint $table) {
            $table->integer('admin_id')->primary();
            $table->integer('admin_user_id');
            $table->string('admin_section')->default('ffb');
        });

        Schema::create('web_user_permissions', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->tinyInteger('user_permissions_ffb_visible_profile')->default(0);
        });

        Schema::create('ffb_userscore', function (Blueprint $table) {
            $table->integer('userscore_id')->primary();
            $table->integer('userscore_user_id');
            $table->integer('userscore_league_id');
            $table->integer('userscore_total')->default(0);
            $table->integer('userscore_lc_points')->default(0);
        });

        Schema::create('ffb_news', function (Blueprint $table) {
            $table->integer('news_id')->primary();
            $table->integer('news_league_id')->default(0);
            $table->string('news_title')->default('');
            $table->string('news_date')->nullable();
            $table->text('news_text')->nullable();
            $table->string('news_symbol')->nullable();
        });

        Schema::create('ffb_poll', function (Blueprint $table) {
            $table->integer('poll_id')->primary();
            $table->string('poll_type')->default('text');
            $table->tinyInteger('poll_visible')->default(0);
            $table->string('poll_start')->nullable();
            $table->string('poll_end')->nullable();
            $table->integer('poll_league_id')->default(0);
        });

        Schema::table('web_user_details', function (Blueprint $table) {
            $table->string('user_details_avatar')->nullable();
            $table->string('user_details_photo')->nullable();
            $table->integer('user_details_ffb_favourite_team')->default(0);
            $table->integer('user_details_ffb_own_team')->default(0);
            $table->string('user_details_city')->nullable();
            $table->string('user_details_website')->nullable();
            $table->string('user_details_phone')->nullable();
        });

        Schema::table('ffb_league_options', function (Blueprint $table) {
            $table->string('options_league_rankmode')->default('lc');
        });
    }

    private function seedLeagues(): void
    {
        DB::table('ffb_league')->insert([
            [
                'league_id' => 1,
                'league_title' => 'Visible Current',
                'league_visible' => 1,
                'league_archive' => 0,
                'league_symbol' => '',
            ],
            [
                'league_id' => 2,
                'league_title' => 'Hidden Current',
                'league_visible' => 0,
                'league_archive' => 0,
                'league_symbol' => '',
            ],
            [
                'league_id' => 3,
                'league_title' => 'Visible Archive',
                'league_visible' => 1,
                'league_archive' => 1,
                'league_symbol' => '',
            ],
            [
                'league_id' => 4,
                'league_title' => 'Hidden Archive',
                'league_visible' => 0,
                'league_archive' => 1,
                'league_symbol' => '',
            ],
        ]);

        DB::table('ffb_matchround')->insert([
            ['matchround_id' => 1, 'matchround_league_id' => 1, 'matchround_title' => 'R1', 'matchround_startdate' => '2026-01-01 00:00:00', 'matchround_enddate' => '2026-01-02 00:00:00', 'matchround_status' => 1],
            ['matchround_id' => 2, 'matchround_league_id' => 2, 'matchround_title' => 'R1', 'matchround_startdate' => '2026-01-01 00:00:00', 'matchround_enddate' => '2026-01-02 00:00:00', 'matchround_status' => 1],
            ['matchround_id' => 3, 'matchround_league_id' => 3, 'matchround_title' => 'R1', 'matchround_startdate' => '2025-01-01 00:00:00', 'matchround_enddate' => '2025-01-02 00:00:00', 'matchround_status' => 1],
            ['matchround_id' => 4, 'matchround_league_id' => 4, 'matchround_title' => 'R1', 'matchround_startdate' => '2025-01-01 00:00:00', 'matchround_enddate' => '2025-01-02 00:00:00', 'matchround_status' => 1],
        ]);

        DB::table('web_user')->insert([
            ['user_id' => 10, 'user_nickname' => 'player', 'user_status' => 'active'],
            ['user_id' => 11, 'user_nickname' => 'admin', 'user_status' => 'active'],
            ['user_id' => 20, 'user_nickname' => 'profile', 'user_status' => 'active'],
        ]);

        DB::table('web_user_details')->insert([
            ['user_id' => 10, 'user_details_ffb_selected_league' => 1],
            ['user_id' => 11, 'user_details_ffb_selected_league' => 1],
            ['user_id' => 20, 'user_details_ffb_selected_league' => 1],
        ]);

        DB::table('web_user_permissions')->insert([
            ['user_id' => 20, 'user_permissions_ffb_visible_profile' => 0],
        ]);
    }
}
