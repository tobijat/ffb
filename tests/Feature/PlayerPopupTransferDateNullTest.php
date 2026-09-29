<?php

namespace Tests\Feature;

use App\Models\Matchround;
use App\Models\Playerteam;
use App\Services\PlayerPopupService;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class PlayerPopupTransferDateNullTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);

        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'league_title' => 'Test League',
        ]);
        DB::table('ffb_league_options')->insert([
            'options_id' => 1,
            'options_league_id' => 1,
            'options_league_pricemode' => 'constant',
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 10,
            'matchround_league_id' => 1,
            'matchround_title' => 'R1',
            'matchround_startdate' => '2010-06-01 00:00:00',
            'matchround_status' => 1,
        ]);
        DB::table('ffb_team')->insert([
            [
                'team_id' => 1,
                'team_name' => 'Team A',
                'team_status' => 1,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ],
            [
                'team_id' => 2,
                'team_name' => 'Team B',
                'team_status' => 1,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ],
        ]);
        DB::table('ffb_player')->insert([
            'player_id' => 1,
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_nationality' => 'aut',
            'player_status_description' => '',
        ]);
        DB::table('web_user_details')->insert([
            'user_id' => 7,
            'user_details_ffb_selected_league' => 1,
        ]);
    }

    #[Test]
    public function prefers_active_row_when_all_transfer_dates_are_null(): void
    {
        DB::table('ffb_playerteam')->insert([
            [
                'playerteam_id' => 100,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 1,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 0,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => null,
            ],
            [
                'playerteam_id' => 101,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 2,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => null,
            ],
        ]);

        $round = Matchround::query()->findOrFail(10);
        $picked = $this->invokeTeamForPlayerAndRound($round, [100, 101]);

        $this->assertNotNull($picked);
        $this->assertSame(101, (int) $picked->playerteam_id);
    }

    #[Test]
    public function still_picks_nearest_transfer_date_when_present(): void
    {
        DB::table('ffb_playerteam')->insert([
            [
                'playerteam_id' => 200,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 1,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ],
            [
                'playerteam_id' => 201,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 2,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => '2010-03-01 00:00:00',
            ],
        ]);

        $round = Matchround::query()->findOrFail(10);
        $picked = $this->invokeTeamForPlayerAndRound($round, [200, 201]);

        $this->assertNotNull($picked);
        $this->assertSame(201, (int) $picked->playerteam_id);
    }

    #[Test]
    public function popup_loads_when_roster_transfer_date_is_null(): void
    {
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => 300,
            'playerteam_player_id' => 1,
            'playerteam_team_id' => 1,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => null,
        ]);

        $result = app(PlayerPopupService::class)->forPlayerteam(7, 300);

        $this->assertTrue($result['ok'] ?? false);
        $this->assertSame('Max Muster', $result['data']['player']['player_name'] ?? null);
    }

    /**
     * @param  list<int>  $ptIds
     */
    private function invokeTeamForPlayerAndRound(Matchround $round, array $ptIds): ?Playerteam
    {
        $service = app(PlayerPopupService::class);
        $method = new ReflectionMethod(PlayerPopupService::class, 'teamForPlayerAndRound');

        /** @var Playerteam|null $picked */
        $picked = $method->invoke($service, $round, $ptIds);

        return $picked;
    }
}
