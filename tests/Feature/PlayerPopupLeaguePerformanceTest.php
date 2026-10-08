<?php

namespace Tests\Feature;

use App\Services\PlayerPopupService;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class PlayerPopupLeaguePerformanceTest extends TestCase
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
            'options_league_pricemode' => 'dynamic',
        ]);
        DB::table('ffb_matchround')->insert([
            [
                'matchround_id' => 1,
                'matchround_league_id' => 1,
                'matchround_title' => 'R1',
                'matchround_startdate' => now()->subDays(3)->toDateTimeString(),
                'matchround_status' => 1,
            ],
            [
                'matchround_id' => 2,
                'matchround_league_id' => 1,
                'matchround_title' => 'R2',
                'matchround_startdate' => now()->subDay()->toDateTimeString(),
                'matchround_status' => 1,
            ],
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
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_nationality' => 'aut',
            'player_status_description' => '',
        ]);
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => 100,
            'playerteam_player_id' => 1,
            'playerteam_team_id' => 10,
            'playerteam_league_id' => 1,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);
        DB::table('web_user_details')->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => 1,
        ]);
    }

    #[Test]
    public function recent_performance_is_null_when_none_stored(): void
    {
        $result = (new PlayerPopupService)->forPlayerteam(544, 100);

        $this->assertTrue($result['ok']);
        $this->assertNull($result['data']['stats']['recent_performance']);
    }

    #[Test]
    public function uses_latest_non_null_recent_performance_in_league(): void
    {
        DB::table('ffb_playerprice')->insert([
            [
                'playerprice_id' => 1,
                'playerprice_playerteam_id' => 100,
                'playerprice_matchround_id' => 1,
                'playerprice_price' => 5,
                'playerprice_powers' => 0,
                'playerprice_recent_performance' => -0.5,
            ],
            [
                'playerprice_id' => 2,
                'playerprice_playerteam_id' => 100,
                'playerprice_matchround_id' => 2,
                'playerprice_price' => 6,
                'playerprice_powers' => 0,
                'playerprice_recent_performance' => 0.6,
            ],
        ]);

        $result = (new PlayerPopupService)->forPlayerteam(544, 100);

        $this->assertTrue($result['ok']);
        $this->assertSame(0.6, $result['data']['stats']['recent_performance']);
    }

    #[Test]
    public function falls_back_to_older_round_when_latest_recent_performance_is_null(): void
    {
        DB::table('ffb_playerprice')->insert([
            [
                'playerprice_id' => 1,
                'playerprice_playerteam_id' => 100,
                'playerprice_matchround_id' => 1,
                'playerprice_price' => 5,
                'playerprice_powers' => 0,
                'playerprice_recent_performance' => 0.25,
            ],
            [
                'playerprice_id' => 2,
                'playerprice_playerteam_id' => 100,
                'playerprice_matchround_id' => 2,
                'playerprice_price' => 6,
                'playerprice_powers' => 0,
                'playerprice_recent_performance' => null,
            ],
        ]);

        $result = (new PlayerPopupService)->forPlayerteam(544, 100);

        $this->assertTrue($result['ok']);
        $this->assertSame(0.25, $result['data']['stats']['recent_performance']);
    }
}
