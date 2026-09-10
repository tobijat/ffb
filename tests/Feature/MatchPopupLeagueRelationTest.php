<?php

namespace Tests\Feature;

use App\Services\MatchPopupService;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\CreatesLegacyFfbSchema;
use Tests\TestCase;

class MatchPopupLeagueRelationTest extends TestCase
{
    use CreatesLegacyFfbSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createLegacyFfbSchema(true);

        DB::table('ffb_league')->insert([
            'league_id' => 9,
            'league_title' => 'Bundesliga Test',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_countdown' => 0,
            'league_status' => 1,
            'league_symbol' => '',
        ]);

        DB::table('ffb_matchround')->insert([
            'matchround_id' => 3,
            'matchround_league_id' => 9,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDay()->toDateTimeString(),
            'matchround_status' => 1,
            'matchround_credits' => 100,
            'matchround_max_players_from_team' => 3,
        ]);

        DB::table('ffb_team')->insert([
            [
                'team_id' => 1,
                'team_name' => 'Home FC',
                'team_status' => 1,
                'team_avg_price' => 5,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ],
            [
                'team_id' => 2,
                'team_name' => 'Away FC',
                'team_status' => 1,
                'team_avg_price' => 5,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'ger',
            ],
        ]);

        DB::table('ffb_match')->insert([
            'match_id' => 50,
            'match_round' => 3,
            'match_hometeam_id' => 1,
            'match_guestteam_id' => 2,
            'match_homescore' => 2,
            'match_guestscore' => 1,
            'match_homescore_penalty' => -1,
            'match_guestscore_penalty' => -1,
            'match_date' => now()->subHour()->toDateTimeString(),
            'match_status' => 'finished',
        ]);
    }

    #[Test]
    public function match_popup_loads_league_via_matchround_relationship(): void
    {
        $result = app(MatchPopupService::class)->forMatch(50);

        $this->assertTrue($result['ok']);
        $this->assertSame('Bundesliga Test', $result['data']['match']['match_league_title']);
        $this->assertSame('Home FC', $result['data']['match']['match_hometeam_name']);
        $this->assertSame('Away FC', $result['data']['match']['match_guestteam_name']);
    }
}
