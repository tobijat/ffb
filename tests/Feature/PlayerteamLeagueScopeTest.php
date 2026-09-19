<?php

namespace Tests\Feature;

use App\Models\Playerteam;
use App\Services\AdminCenterService;
use App\Services\AdminDbCleanupService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

/**
 * League-scoped playerteam behavior after schema + backfill semantics.
 */
class PlayerteamLeagueScopeTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);
        $this->seedBaseEntities();
    }

    #[Test]
    public function squad_allows_same_player_team_in_different_leagues(): void
    {
        DB::table('ffb_league')->insert(['league_id' => 2, 'league_title' => 'Liga B']);
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
            'team_id' => 10,
            'squad_league_id' => 2,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2020-01-01',
        ])['ok']);

        $this->assertSame(2, Playerteam::query()->where('playerteam_player_id', 1)->count());
        $this->assertSame('d', Playerteam::query()->forLeague(1)->value('playerteam_player_position'));
        $this->assertSame('m', Playerteam::query()->forLeague(2)->value('playerteam_player_position'));
    }

    #[Test]
    public function squad_rejects_duplicate_in_same_league(): void
    {
        $squad = $this->squadService();
        $this->assertTrue($squad->batchAdd([
            'team_id' => 10,
            'squad_league_id' => 1,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01',
        ])['ok']);

        $second = $squad->batchAdd([
            'team_id' => 10,
            'squad_league_id' => 1,
            'player_ids' => [1],
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2020-01-01',
        ]);
        $this->assertFalse($second['ok']);
    }

    #[Test]
    public function cleanup_duplicate_groups_are_league_aware(): void
    {
        DB::table('ffb_playerteam')->insert([
            [
                'playerteam_id' => 100,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 10,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'd',
                'playerteam_date_transfer' => '2020-01-01 00:00:00',
            ],
            [
                'playerteam_id' => 101,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 10,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => '2020-01-01 00:00:00',
            ],
            [
                'playerteam_id' => 102,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 10,
                'playerteam_league_id' => 2,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 's',
                'playerteam_date_transfer' => '2020-01-01 00:00:00',
            ],
        ]);
        DB::table('ffb_league')->insert(['league_id' => 2, 'league_title' => 'Liga B']);

        $groups = (new AdminDbCleanupService(Mockery::mock(AdminCenterService::class)))->duplicatePlayerteamGroups();
        $this->assertCount(1, $groups);
        $this->assertSame(1, $groups[0]['league_id']);
        $this->assertSame(2, $groups[0]['entry_count']);
    }

    #[Test]
    public function player_search_excludes_only_same_league_squad_members(): void
    {
        DB::table('ffb_league')->insert(['league_id' => 2, 'league_title' => 'Liga B']);
        DB::table('ffb_player')->insert([
            [
                'player_id' => 3,
                'player_fname' => 'Free',
                'player_lname' => 'Agent',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_nationality' => 'arg',
                'player_status_description' => '',
            ],
        ]);
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => 100,
            'playerteam_player_id' => 1,
            'playerteam_team_id' => 10,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $players = new AdminPlayerService($adminCenter);

        $otherLeague = $players->search([
            'exclude_team_id' => 10,
            'exclude_league_id' => 2,
        ]);
        $idsOther = array_column($otherLeague['items'], 'player_id');
        $this->assertContains(1, $idsOther);
        $this->assertContains(3, $idsOther);

        $sameLeague = $players->search([
            'exclude_team_id' => 10,
            'exclude_league_id' => 1,
        ]);
        $idsSame = array_column($sameLeague['items'], 'player_id');
        $this->assertNotContains(1, $idsSame);
        $this->assertContains(3, $idsSame);
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
        DB::table('ffb_match')->insert([
            'match_id' => 1,
            'match_round' => 1,
            'match_hometeam_id' => 10,
            'match_guestteam_id' => 11,
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
            'player_id' => 1,
            'player_fname' => 'Ada',
            'player_lname' => 'Alaba',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_nationality' => 'aut',
            'player_status_description' => '',
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
        $adminCenter->shouldReceive('selectedLeagueId')->andReturn(1)->byDefault();

        $players = new AdminPlayerService($adminCenter);

        return new AdminSquadService($adminCenter, $players, new WikimediaPlayerImageService);
    }
}
