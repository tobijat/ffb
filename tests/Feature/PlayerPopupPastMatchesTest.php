<?php

namespace Tests\Feature;

use App\Services\PlayerPopupService;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class PlayerPopupPastMatchesTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);

        DB::table('ffb_league')->insert([
            ['league_id' => 1, 'league_title' => 'Current League'],
            ['league_id' => 2, 'league_title' => 'Past League'],
        ]);
        DB::table('ffb_options')->insert([
            'options_id' => 1,
            'options_league_id' => 1,
            'options_league_pricemode' => 'constant',
        ]);
        DB::table('ffb_matchround')->insert([
            [
                'matchround_id' => 1,
                'matchround_league_id' => 1,
                'matchround_title' => 'Current R1',
                'matchround_startdate' => now()->subDay()->toDateTimeString(),
                'matchround_status' => 1,
            ],
            [
                'matchround_id' => 2,
                'matchround_league_id' => 2,
                'matchround_title' => 'Past R1',
                'matchround_startdate' => now()->subYear()->toDateTimeString(),
                'matchround_status' => 1,
            ],
        ]);
        DB::table('ffb_team')->insert([
            [
                'team_id' => 10,
                'team_name' => 'Feffernitz',
                'team_status' => 1,
                'team_avg_price' => 5,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ],
            [
                'team_id' => 11,
                'team_name' => 'Opponent',
                'team_status' => 1,
                'team_avg_price' => 5,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ],
        ]);
        DB::table('ffb_player')->insert([
            'player_id' => 1,
            'player_fname' => 'Igor',
            'player_lname' => 'Stojanov',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_nationality' => 'aut',
            'player_status_description' => '',
        ]);
        DB::table('ffb_playerteam')->insert([
            [
                'playerteam_id' => 100,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 10,
                'playerteam_league_id' => 1,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_price' => 5,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ],
            [
                'playerteam_id' => 200,
                'playerteam_player_id' => 1,
                'playerteam_team_id' => 10,
                'playerteam_league_id' => 2,
                'playerteam_player_picture' => '',
                'playerteam_status' => 0,
                'playerteam_player_price' => 5,
                'playerteam_player_position' => 's',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ],
        ]);
        DB::table('ffb_match')->insert([
            'match_id' => 20,
            'match_round' => 2,
            'match_hometeam_id' => 10,
            'match_guestteam_id' => 11,
            'match_homescore' => 2,
            'match_guestscore' => 1,
            'match_date' => now()->subMonths(6)->toDateTimeString(),
        ]);
        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 1,
            'playerstats_playerteam_id' => 200,
            'playerstats_matchround_id' => 2,
            'playerstats_match_id' => 20,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 1,
            'playerstats_assists' => 0,
            'playerstats_score' => 5,
            'playerstats_cards' => 'n',
        ]);
        DB::table('web_user_details')->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => 1,
        ]);
    }

    #[Test]
    public function player_popup_includes_same_team_matches_from_other_leagues(): void
    {
        $result = (new PlayerPopupService)->forPlayerteam(544, 100);

        $this->assertTrue($result['ok']);
        $this->assertNotEmpty($result['data']['pastmatches']);
        $this->assertSame(2, (int) $result['data']['pastmatches'][0]['matchround_id']);
        $this->assertSame('Past R1', $result['data']['pastmatches'][0]['matchround_title']);
        $this->assertSame('Opponent', $result['data']['pastmatches'][0]['matchround_opponent_name']);
    }

    #[Test]
    public function player_round_popup_resolves_stats_via_sibling_league_playerteam(): void
    {
        $result = (new PlayerPopupService)->forRound(544, 100, 2);

        $this->assertTrue($result['ok']);
        $this->assertTrue($result['data']['played']);
        $this->assertNotNull($result['data']['stats']);
        $this->assertSame(90, (int) $result['data']['stats']['playerstats_minutes']);
        $this->assertSame(1, (int) $result['data']['stats']['playerstats_goals']);
        $this->assertSame(5, (int) $result['data']['stats']['playerstats_score']);
    }
}
