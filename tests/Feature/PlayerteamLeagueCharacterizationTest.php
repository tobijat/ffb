<?php

namespace Tests\Feature;

use App\Models\Playerteam;
use App\Services\AdminCenterService;
use App\Services\AdminDbCleanupService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\PlayerteamLeagueInventoryService;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

/**
 * Phase −1 characterization: current (pre-league) playerteam semantics.
 */
class PlayerteamLeagueCharacterizationTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);
        $this->seedBaseEntities();
    }

    #[Test]
    public function squad_batch_add_rejects_duplicate_player_on_same_team(): void
    {
        $squad = $this->squadService();

        $first = $squad->batchAdd([
            'team_id' => 10,
            'squad_league_id' => 1,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01',
        ]);
        $this->assertTrue($first['ok']);

        $second = $squad->batchAdd([
            'team_id' => 10,
            'squad_league_id' => 1,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2020-01-01',
        ]);
        $this->assertFalse($second['ok']);
        $this->assertStringContainsString('bereits zugeordnet', $second['errors'][0]);
    }

    #[Test]
    public function squad_batch_add_allows_same_player_on_different_team(): void
    {
        DB::table('ffb_team')->insert([
            'team_id' => 11,
            'team_name' => 'Other',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
            'team_nationality' => 'aut',
        ]);

        $squad = $this->squadService();
        $this->assertTrue($squad->batchAdd([
            'team_id' => 10,
            'squad_league_id' => 1,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01',
        ])['ok']);

        $this->assertTrue($squad->batchAdd([
            'team_id' => 11,
            'squad_league_id' => 1,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01',
        ])['ok']);
    }

    #[Test]
    public function squad_delete_blocked_by_userteam_or_stats_otherwise_allowed(): void
    {
        $pt = $this->insertPlayerteam(100, 1, 10);
        $squad = $this->squadService();

        DB::table('ffb_userteam')->insert([
            'userteam_id' => 1,
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 1,
            'userteam_slot_slot' => 1,
            'userteam_slot_playerteam_id' => 100,
        ]);
        $blockedLineup = $squad->delete(100);
        $this->assertFalse($blockedLineup['ok']);
        $this->assertStringContainsString('Userteams', $blockedLineup['errors'][0]);

        DB::table('ffb_userteam_slot')->delete();
        DB::table('ffb_userteam')->delete();
        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 1,
            'playerstats_playerteam_id' => 100,
            'playerstats_matchround_id' => 1,
            'playerstats_match_id' => 1,
        ]);
        $blockedStats = $squad->delete(100);
        $this->assertFalse($blockedStats['ok']);
        $this->assertStringContainsString('Spielstatistiken', $blockedStats['errors'][0]);

        DB::table('ffb_playerstats')->delete();
        $ok = $squad->delete(100);
        $this->assertTrue($ok['ok']);
        $this->assertNull(Playerteam::query()->find(100));
        $this->assertNotNull($pt);
    }

    #[Test]
    public function db_cleanup_groups_duplicate_player_team_pairs(): void
    {
        $this->insertPlayerteam(100, 1, 10);
        $this->insertPlayerteam(101, 1, 10);
        $this->insertPlayerteam(102, 1, 11);

        DB::table('ffb_team')->insert([
            'team_id' => 11,
            'team_name' => 'B',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
            'team_nationality' => 'ger',
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $groups = (new AdminDbCleanupService($adminCenter))->duplicatePlayerteamGroups();

        $this->assertCount(1, $groups);
        $this->assertSame(1, $groups[0]['player_id']);
        $this->assertSame(10, $groups[0]['team_id']);
        $this->assertSame(2, $groups[0]['entry_count']);
    }

    #[Test]
    public function inventory_flags_unreferenced_playerteams(): void
    {
        $this->insertPlayerteam(100, 1, 10);
        $this->insertPlayerteam(200, 2, 10);
        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 1,
            'playerstats_playerteam_id' => 100,
            'playerstats_matchround_id' => 1,
            'playerstats_match_id' => 1,
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $cleanup = new AdminDbCleanupService($adminCenter);
        $report = (new PlayerteamLeagueInventoryService($cleanup))->report();

        $this->assertSame([200], $report['unreferenced_playerteam_ids']);
        $this->assertSame(1, $report['counts']['playerteam_unreferenced']);
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
            'team_num_players' => 0,
            'team_foreign_id' => '',
            'team_nationality' => 'aut',
        ]);
        DB::table('ffb_player')->insert([
            [
                'player_id' => 1,
                'player_fname' => 'Ada',
                'player_lname' => 'Alaba',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_nationality' => 'aut',
                'player_status_description' => '',
            ],
            [
                'player_id' => 2,
                'player_fname' => 'Bob',
                'player_lname' => 'Bauer',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_nationality' => 'ger',
                'player_status_description' => '',
            ],
        ]);
    }

    private function insertPlayerteam(int $id, int $playerId, int $teamId): Playerteam
    {
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => $id,
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);

        return Playerteam::query()->findOrFail($id);
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
        $adminCenter->shouldReceive('selectedLeagueId')->andReturn(1)->byDefault();

        $players = new AdminPlayerService($adminCenter);

        return new AdminSquadService($adminCenter, $players, new WikimediaPlayerImageService);
    }
}
