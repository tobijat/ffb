<?php

namespace Tests\Feature;

use App\Models\Matchround;
use App\Services\AdminCenterService;
use App\Services\AdminMatchroundService;
use App\Services\LineupOptionsResolver;
use App\Services\LineupService;
use App\Support\FfbDateTime;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class MatchroundDeadlineTimezoneTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);

        config(['ffb.display_timezone' => 'Europe/Vienna']);

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
            'options_lineup_max_credits' => 77,
            'options_lineup_max_players_team' => 4,
            'options_lineup_min_g' => 1,
            'options_lineup_min_d' => 2,
            'options_lineup_min_m' => 3,
            'options_lineup_min_s' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_max_d' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_max_s' => 3,
            'options_league_pricemode' => 'constant',
            'options_league_pointsmode' => 'new',
        ]);
    }

    #[Test]
    public function admin_create_stores_local_deadline_as_utc(): void
    {
        $result = $this->matchroundService()->create([
            'matchround_league_id' => 1,
            'matchround_title' => 'Gruppenphase 4',
            'matchround_status' => 1,
            'matchround_startdate' => '2026-10-04T15:00',
            'matchround_enddate' => '2026-10-07T22:00',
        ]);

        $this->assertTrue($result['ok']);

        $round = Matchround::query()->where('matchround_title', 'Gruppenphase 4')->first();
        $this->assertNotNull($round);
        // CEST (UTC+2) on 2026-10-04 → 15:00 local = 13:00 UTC
        $this->assertSame('2026-10-04 13:00:00', (string) $round->matchround_startdate);
        $this->assertSame('2026-10-07 20:00:00', (string) $round->matchround_enddate);

        $edit = $this->matchroundService()->formForEdit((int) $round->matchround_id);
        $this->assertNotNull($edit);
        $this->assertSame('2026-10-04T15:00', $edit['form']['matchround_startdate']);
        $this->assertSame('2026-10-07T22:00', $edit['form']['matchround_enddate']);
    }

    #[Test]
    public function lineup_save_closes_at_utc_instant_of_local_deadline(): void
    {
        Matchround::query()->insert([
            'matchround_id' => 40,
            'matchround_league_id' => 1,
            'matchround_title' => 'GP4',
            'matchround_startdate' => '2026-10-04 13:00:00',
            'matchround_enddate' => '2026-10-07 20:00:00',
            'matchround_status' => 1,
        ]);

        $lineups = app(LineupService::class);

        $this->travelTo('2026-10-04 12:59:00');
        $open = $lineups->saveForRound(544, 40, [], []);
        $this->assertNotSame(409, $open['status'] ?? null);

        $this->travelTo('2026-10-04 13:00:00');
        $closed = $lineups->saveForRound(544, 40, [], []);
        $this->assertFalse($closed['ok']);
        $this->assertSame(409, $closed['status']);
        $this->assertStringContainsString('Deadline', $closed['error']);
    }

    #[Test]
    public function deadline_display_uses_display_timezone(): void
    {
        $this->assertSame(
            '4.10.2026 15:00',
            FfbDateTime::utcDbToDisplay('2026-10-04 13:00:00'),
        );

        $this->travelTo('2026-10-04 12:59:00');
        $this->assertTrue(FfbDateTime::isFutureUtc('2026-10-04 13:00:00'));

        $this->travelTo('2026-10-04 13:00:00');
        $this->assertFalse(FfbDateTime::isFutureUtc('2026-10-04 13:00:00'));
    }

    #[Test]
    public function legacy_wall_clock_migration_helper_shifts_to_utc(): void
    {
        $this->assertSame(
            '2026-10-04 13:00:00',
            FfbDateTime::legacyLocalWallToUtcDb('2026-10-04 15:00:00'),
        );
        $this->assertSame(
            '2026-10-04 15:00:00',
            FfbDateTime::utcDbToLegacyLocalWall('2026-10-04 13:00:00'),
        );
    }

    private function matchroundService(): AdminMatchroundService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);

        return new AdminMatchroundService($adminCenter, app(LineupOptionsResolver::class));
    }
}
