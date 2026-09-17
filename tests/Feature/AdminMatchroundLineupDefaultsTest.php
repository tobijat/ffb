<?php

namespace Tests\Feature;

use App\Models\Matchround;
use App\Services\AdminCenterService;
use App\Services\AdminMatchroundService;
use App\Services\LineupOptionsResolver;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class AdminMatchroundLineupDefaultsTest extends TestCase
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
    public function empty_form_fills_lineup_fields_from_selected_league(): void
    {
        $form = $this->service()->emptyForm(1);

        $this->assertSame(0, $form['lineup_options_enabled']);
        $this->assertSame(77.0, $form['matchround_options_lineup_max_credits']);
        $this->assertSame(4, $form['matchround_options_lineup_max_players_team']);
        $this->assertSame(2, $form['matchround_options_lineup_min_d']);
    }

    #[Test]
    public function edit_form_without_override_uses_league_defaults(): void
    {
        Matchround::query()->insert([
            'matchround_id' => 10,
            'matchround_league_id' => 1,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-09-01 18:00:00',
            'matchround_enddate' => '2026-09-07 22:00:00',
            'matchround_status' => 1,
        ]);

        $edit = $this->service()->formForEdit(10);

        $this->assertNotNull($edit);
        $this->assertSame(0, $edit['form']['lineup_options_enabled']);
        $this->assertSame(77.0, $edit['form']['matchround_options_lineup_max_credits']);
        $this->assertSame(4, $edit['form']['matchround_options_lineup_max_players_team']);
    }

    #[Test]
    public function edit_form_with_override_keeps_matchround_values(): void
    {
        Matchround::query()->insert([
            'matchround_id' => 11,
            'matchround_league_id' => 1,
            'matchround_title' => 'Runde 2',
            'matchround_startdate' => '2026-09-08 18:00:00',
            'matchround_enddate' => '2026-09-14 22:00:00',
            'matchround_status' => 1,
        ]);

        DB::table('ffb_matchround_options')->insert([
            'matchround_options_matchround_id' => 11,
            'matchround_options_lineup_max_players' => 11,
            'matchround_options_lineup_max_credits' => 55,
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

        $edit = $this->service()->formForEdit(11);

        $this->assertNotNull($edit);
        $this->assertSame(1, $edit['form']['lineup_options_enabled']);
        $this->assertSame(55.0, (float) $edit['form']['matchround_options_lineup_max_credits']);
        $this->assertSame(6, (int) $edit['form']['matchround_options_lineup_max_players_team']);
    }

    #[Test]
    public function page_payload_exposes_league_defaults_and_prefills_disabled_form(): void
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->once()->with(544)->andReturn([
            'user' => ['user_id' => 544],
            'navigation' => [],
            'selected_league' => null,
        ]);

        $service = new AdminMatchroundService($adminCenter, app(LineupOptionsResolver::class));
        $payload = $service->pagePayload(544, 1);

        $this->assertSame(77.0, $payload['league_lineup_defaults']['matchround_options_lineup_max_credits']);
        $this->assertSame(0, $payload['form']['lineup_options_enabled']);
        $this->assertSame(77.0, $payload['form']['matchround_options_lineup_max_credits']);
        $this->assertSame(4, $payload['form']['matchround_options_lineup_max_players_team']);
    }

    private function service(): AdminMatchroundService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);

        return new AdminMatchroundService($adminCenter, app(LineupOptionsResolver::class));
    }
}
