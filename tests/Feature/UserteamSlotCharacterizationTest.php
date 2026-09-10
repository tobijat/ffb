<?php

namespace Tests\Feature;

use App\Models\Userteam;
use App\Services\AdminCenterService;
use App\Services\AdminSquadService;
use App\Services\PlayerPopupService;
use App\Services\UserteamSlotBackfillService;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

/**
 * Post-cutover characterization: lineup slots live in ffb_userteam_slot.
 */
class UserteamSlotCharacterizationTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required for in-memory legacy schema fixtures.');
        }

        $this->createLegacyFfbSchema(true);
        $this->seedBaseEntities();
    }

    #[Test]
    public function sync_slots_persists_exactly_eleven_rows_in_pitch_order(): void
    {
        $ids = range(101, 111);
        foreach ($ids as $id) {
            $this->insertPlayerteam($id, 1, 10);
        }

        $userteam = new Userteam;
        $userteam->userteam_user_id = 1;
        $userteam->userteam_matchround_id = 1;
        $userteam->userteam_price = 55;
        $userteam->userteam_score = 0;
        $userteam->save();
        $userteam->syncSlots($ids);

        $this->assertSame(11, DB::table('ffb_userteam_slot')->where('userteam_slot_userteam_id', $userteam->userteam_id)->count());
        $this->assertSame($ids, $userteam->fresh()->playerteamIdsInSlotOrder());
        $this->assertFalse(Userteam::hasWideSlotColumns());
        $this->assertTrue(Userteam::hasSlotTable());
    }

    #[Test]
    public function query_containing_any_playerteam_and_squad_delete_guard_use_slots(): void
    {
        $this->insertPlayerteam(100, 1, 10);

        DB::table('ffb_userteam')->insert([
            'userteam_id' => 1,
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 1,
            'userteam_slot_slot' => 3,
            'userteam_slot_playerteam_id' => 100,
        ]);

        $this->assertTrue(Userteam::queryContainingAnyPlayerteam([100])->exists());
        $this->assertFalse(Userteam::queryContainingAnyPlayerteam([999])->exists());
        $this->assertSame([100], Userteam::playerteamIdsUsedInLineups());

        $blocked = $this->squadService()->delete(100);
        $this->assertFalse($blocked['ok']);
        $this->assertStringContainsString('Userteams', $blocked['errors'][0]);
    }

    #[Test]
    public function inventory_reports_zero_wide_columns_after_cutover(): void
    {
        DB::table('ffb_userteam')->insert([
            'userteam_id' => 1,
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 1,
            'userteam_slot_slot' => 1,
            'userteam_slot_playerteam_id' => 50,
        ]);

        $report = app(UserteamSlotBackfillService::class)->inventory();

        $this->assertSame(1, $report['userteam_count']);
        $this->assertSame(0, $report['wide_nonzero_slots']);
        $this->assertSame(0, $report['empty_wide_slots']);
        $this->assertSame(0, $report['duplicate_user_round_groups']);
        $this->assertSame(1, $report['slot_row_count']);
    }

    #[Test]
    public function popup_lineup_counts_use_slot_rows(): void
    {
        DB::table('ffb_userteam')->insert([
            ['userteam_id' => 1, 'userteam_user_id' => 1, 'userteam_matchround_id' => 1],
            ['userteam_id' => 2, 'userteam_user_id' => 2, 'userteam_matchround_id' => 1],
        ]);
        DB::table('ffb_userteam_slot')->insert([
            [
                'userteam_slot_userteam_id' => 1,
                'userteam_slot_slot' => 1,
                'userteam_slot_playerteam_id' => 50,
            ],
            [
                'userteam_slot_userteam_id' => 2,
                'userteam_slot_slot' => 2,
                'userteam_slot_playerteam_id' => 50,
            ],
        ]);

        $service = app(PlayerPopupService::class);
        $countLineups = new \ReflectionMethod($service, 'countLineups');
        $countForRound = new \ReflectionMethod($service, 'countLineupsForRound');

        $this->assertSame(2, $countLineups->invoke($service, [50], 1));
        $this->assertSame(2, $countForRound->invoke($service, [50], 1));
        $this->assertSame(0, $countForRound->invoke($service, [50], 999));
    }

    private function seedBaseEntities(): void
    {
        DB::table('ffb_league')->insert(['league_id' => 1, 'league_title' => 'Testliga']);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 1,
            'matchround_league_id' => 1,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->addDay()->toDateTimeString(),
        ]);
        DB::table('ffb_team')->insert([
            'team_id' => 10,
            'team_name' => 'Alpha',
            'team_status' => 1,
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_foreign_id' => '',
            'team_nationality' => 'aut',
        ]);
        DB::table('ffb_player')->insert([
            'player_id' => 1,
            'player_fname' => 'Ada',
            'player_lname' => 'Alaba',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_nationality' => 'aut',
            'player_status_description' => '',
        ]);
    }

    private function insertPlayerteam(int $id, int $playerId, int $teamId): void
    {
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => $id,
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_price' => 5,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);
    }

    private function squadService(): AdminSquadService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->andReturn([
            'user' => ['user_id' => 1],
            'navigation' => [],
            'selected_league' => ['league_id' => 1],
            'selected_league_id' => 1,
        ])->byDefault();

        return new AdminSquadService($adminCenter);
    }
}
