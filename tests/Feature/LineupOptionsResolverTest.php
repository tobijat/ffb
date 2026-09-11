<?php

namespace Tests\Feature;

use App\Services\LineupOptionsResolver;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class LineupOptionsResolverTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);

        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'league_title' => 'Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_countdown' => 0,
            'league_status' => 1,
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

        DB::table('ffb_matchround')->insert([
            [
                'matchround_id' => 10,
                'matchround_league_id' => 1,
                'matchround_title' => 'Default Round',
                'matchround_startdate' => now()->addDay()->toDateTimeString(),
                'matchround_enddate' => now()->addDays(2)->toDateTimeString(),
                'matchround_status' => 1,
            ],
            [
                'matchround_id' => 11,
                'matchround_league_id' => 1,
                'matchround_title' => 'Override Round',
                'matchround_startdate' => now()->addDays(3)->toDateTimeString(),
                'matchround_enddate' => now()->addDays(4)->toDateTimeString(),
                'matchround_status' => 1,
            ],
        ]);

        DB::table('ffb_matchround_options')->insert([
            'matchround_options_matchround_id' => 11,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 80,
            'matchround_options_lineup_max_players_team' => 6,
            'matchround_options_lineup_min_g' => 1,
            'matchround_options_lineup_min_d' => 2,
            'matchround_options_lineup_min_m' => 3,
            'matchround_options_lineup_min_s' => 1,
            'matchround_options_lineup_max_g' => 1,
            'matchround_options_lineup_max_d' => 5,
            'matchround_options_lineup_max_m' => 5,
            'matchround_options_lineup_max_s' => 4,
        ]);
    }

    #[Test]
    public function uses_league_defaults_when_matchround_has_no_override(): void
    {
        $resolved = app(LineupOptionsResolver::class)->forMatchround(10);

        $this->assertSame('league', $resolved['source']);
        $this->assertSame(3, $resolved['lineup_max_players_team']);
        $this->assertSame(100.0, $resolved['lineup_max_credits']);
    }

    #[Test]
    public function uses_matchround_override_when_present(): void
    {
        $resolved = app(LineupOptionsResolver::class)->forMatchround(11);

        $this->assertSame('matchround', $resolved['source']);
        $this->assertSame(6, $resolved['lineup_max_players_team']);
        $this->assertSame(80.0, $resolved['lineup_max_credits']);
        $this->assertSame(2, $resolved['lineup_min_d']);
    }
}
