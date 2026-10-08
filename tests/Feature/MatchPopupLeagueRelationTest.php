<?php

namespace Tests\Feature;

use App\Models\MatchGame;
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
        ]);

        DB::table('ffb_matchround')->insert([
            'matchround_id' => 3,
            'matchround_league_id' => 9,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
            'matchround_enddate' => now()->addDay()->toDateTimeString(),
            'matchround_status' => 1,
        ]);

        DB::table('ffb_team')->insert([
            [
                'team_id' => 1,
                'team_name' => 'Home FC',
                'team_status' => 1,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ],
            [
                'team_id' => 2,
                'team_name' => 'Away FC',
                'team_status' => 1,
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
            'match_date' => '2026-09-24 18:00:00.000',
            'match_status' => 'finished',
        ]);

        DB::table('ffb_match')->insert([
            'match_id' => 51,
            'match_round' => 3,
            'match_hometeam_id' => 2,
            'match_guestteam_id' => 1,
            'match_homescore' => 0,
            'match_guestscore' => 1,
            'match_homescore_penalty' => -1,
            'match_guestscore_penalty' => -1,
            'match_date' => '2026-08-10 20:30:00.000',
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
        $this->assertSame('24.09.2026 18:00', $result['data']['match']['match_date']);
    }

    #[Test]
    public function previous_matches_use_league_round_title_and_date_only(): void
    {
        $result = app(MatchPopupService::class)->forMatch(50);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['data']['prev_matches']);
        $this->assertSame(51, (int) $result['data']['prev_matches'][0]['match_id']);
        $this->assertSame('Bundesliga Test - Runde 1', $result['data']['prev_matches'][0]['match_matchround_name']);
        $this->assertSame('10.08.2026', $result['data']['prev_matches'][0]['match_date']);
    }

    #[Test]
    public function unfinished_meetings_are_excluded_from_all_encounters(): void
    {
        DB::table('ffb_match')->insert([
            'match_id' => 52,
            'match_round' => 3,
            'match_hometeam_id' => 1,
            'match_guestteam_id' => 2,
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_homescore_penalty' => -1,
            'match_guestscore_penalty' => -1,
            'match_date' => '2026-10-01 18:00:00.000',
            'match_status' => 'scheduled',
        ]);

        $result = app(MatchPopupService::class)->forMatch(50);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['data']['prev_matches']);
        $this->assertSame(51, (int) $result['data']['prev_matches'][0]['match_id']);
    }

    #[Test]
    public function previous_matches_omit_test_league_encounters(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 10,
            'league_title' => 'Sandbox',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_test' => 1,
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 4,
            'matchround_league_id' => 10,
            'matchround_title' => 'Test R1',
            'matchround_startdate' => now()->subDays(3)->toDateTimeString(),
            'matchround_enddate' => now()->subDays(2)->toDateTimeString(),
            'matchround_status' => 1,
        ]);
        DB::table('ffb_match')->insert([
            'match_id' => 53,
            'match_round' => 4,
            'match_hometeam_id' => 1,
            'match_guestteam_id' => 2,
            'match_homescore' => 3,
            'match_guestscore' => 0,
            'match_homescore_penalty' => -1,
            'match_guestscore_penalty' => -1,
            'match_date' => '2026-09-01 18:00:00.000',
            'match_status' => 'finished',
        ]);

        $result = app(MatchPopupService::class)->forMatch(50);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['data']['prev_matches']);
        $this->assertSame(51, (int) $result['data']['prev_matches'][0]['match_id']);
    }

    #[Test]
    public function match_popup_hides_sentinel_kickoff_time(): void
    {
        DB::table('ffb_match')->where('match_id', 50)->update([
            'match_date' => '2026-09-24 '.MatchGame::DEFAULT_TIME,
        ]);

        $result = app(MatchPopupService::class)->forMatch(50);

        $this->assertTrue($result['ok']);
        $this->assertSame('24.09.2026', $result['data']['match']['match_date']);
    }
}
