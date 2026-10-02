<?php

namespace Tests\Feature;

use App\Models\Matchround;
use App\Services\AdminCenterService;
use App\Services\AdminLeagueService;
use App\Services\AdminMatchroundService;
use App\Services\LineupOptionsResolver;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class LineupBenchOptionsTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);
    }

    #[Test]
    public function league_defaults_and_fallback_use_zero_bench_limits(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'league_title' => 'Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
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

        $resolver = app(LineupOptionsResolver::class);

        $league = $resolver->forLeague(1);
        $this->assertSame(0, $league['lineup_min_bench']);
        $this->assertSame(0, $league['lineup_max_bench']);
        $this->assertNull($league['league_benchmode']);

        $fallback = $resolver->forLeague(0);
        $this->assertSame(0, $fallback['lineup_min_bench']);
        $this->assertSame(0, $fallback['lineup_max_bench']);
        $this->assertNull($fallback['league_benchmode']);
    }

    #[Test]
    public function matchround_override_can_change_bench_limits(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'league_title' => 'Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
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
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 0,
            'options_league_pricemode' => 'constant',
            'options_league_pointsmode' => 'new',
        ]);

        Matchround::query()->insert([
            'matchround_id' => 20,
            'matchround_league_id' => 1,
            'matchround_title' => 'Runde',
            'matchround_startdate' => '2026-10-01 18:00:00',
            'matchround_enddate' => '2026-10-07 22:00:00',
            'matchround_status' => 1,
        ]);

        DB::table('ffb_matchround_options')->insert([
            'matchround_options_matchround_id' => 20,
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
            'matchround_options_lineup_min_bench' => 1,
            'matchround_options_lineup_max_bench' => 4,
        ]);

        $resolved = app(LineupOptionsResolver::class)->forMatchround(20);

        $this->assertSame('matchround', $resolved['source']);
        $this->assertSame(1, $resolved['lineup_min_bench']);
        $this->assertSame(4, $resolved['lineup_max_bench']);
    }

    #[Test]
    public function admin_league_empty_form_defaults_bench_to_zero(): void
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $service = new AdminLeagueService($adminCenter);
        $form = $service->emptyForm();

        $this->assertSame(0, $form['options_lineup_min_bench']);
        $this->assertSame(0, $form['options_lineup_max_bench']);
    }

    #[Test]
    public function admin_matchround_form_prefills_bench_from_league(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'league_title' => 'Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
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
            'options_lineup_min_bench' => 2,
            'options_lineup_max_bench' => 5,
            'options_league_pricemode' => 'constant',
            'options_league_pointsmode' => 'new',
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $service = new AdminMatchroundService($adminCenter, app(LineupOptionsResolver::class));
        $form = $service->emptyForm(1);

        $this->assertSame(2, $form['matchround_options_lineup_min_bench']);
        $this->assertSame(5, $form['matchround_options_lineup_max_bench']);
    }
}
