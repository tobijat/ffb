<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\UserDetails;
use App\Services\DashboardService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardNavigationArchiveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('web_user_details');
        parent::tearDown();
    }

    #[Test]
    public function navigation_disables_lineup_for_archived_selected_league(): void
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Archivliga',
            'league_type' => 'nation',
            'league_archive' => 1,
        ], 'league_id');

        UserDetails::query()->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => $leagueId,
        ]);

        $nav = collect($this->app->make(DashboardService::class)->navigation(544))
            ->keyBy('name');

        $this->assertTrue($nav['Aufstellung']['disabled']);
        $this->assertSame(
            'Archiviertes Spiel — keine Aufstellung möglich',
            $nav['Aufstellung']['disabled_title']
        );
        $this->assertFalse((bool) ($nav['Mannschaft']['disabled'] ?? false));
    }

    #[Test]
    public function navigation_keeps_lineup_enabled_for_active_selected_league(): void
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Aktuelliga',
            'league_type' => 'nation',
            'league_archive' => 0,
        ], 'league_id');

        UserDetails::query()->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => $leagueId,
        ]);

        $nav = collect($this->app->make(DashboardService::class)->navigation(544))
            ->keyBy('name');

        $this->assertFalse($nav['Aufstellung']['disabled']);
    }

    private function createSchema(): void
    {
        Schema::create('web_user_details', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_details_ffb_selected_league')->default(0);
        });

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
            $table->integer('league_archive')->default(0);
        });
    }
}
