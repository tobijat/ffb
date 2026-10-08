<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
use App\Models\UserDetails;
use App\Services\LineupService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LineupDynamicPriceModeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-09-16 12:00:00'));
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('web_user_details');
        parent::tearDown();
    }

    #[Test]
    public function matchround_rejects_non_dynamic_pricemode(): void
    {
        $leagueId = $this->seedLeague('constant');

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['status']);
        $this->assertStringContainsString('dynamische Preismodell', $result['error'] ?? '');
    }

    #[Test]
    public function team_players_use_playerprice_not_playerteam_player_price(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(9.5, $result['data']['players'][0]['playerteam_player_price']);
        $this->assertSame(0.0, $result['data']['players'][0]['recent_performance']);
        $this->assertArrayNotHasKey('player_grade', $result['data']['players'][0]);
        $this->assertArrayNotHasKey('player_trend', $result['data']['players'][0]);
    }

    #[Test]
    public function team_players_expose_clamped_recent_performance(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => 0.8,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(0.8, $result['data']['players'][0]['recent_performance']);
    }

    #[Test]
    public function team_players_treat_null_recent_performance_as_zero(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => null,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(0.0, $result['data']['players'][0]['recent_performance']);
    }

    #[Test]
    public function team_players_fall_back_to_previous_recent_performance(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$newerPastRoundId, $olderRoundId] = $this->seedPastRounds($leagueId, 2);

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => null,
        ]);
        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $olderRoundId,
            'playerprice_price' => 4.0,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => -0.5,
        ]);
        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $newerPastRoundId,
            'playerprice_price' => 5.0,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => 0.6,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(0.6, $result['data']['players'][0]['recent_performance']);
    }

    #[Test]
    public function team_players_fall_back_to_previous_matchround_playerprice_before_teamprice(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        // seedPastRounds returns newest-past first (later calendar dates first).
        [$newerPastRoundId, $olderRoundId] = $this->seedPastRounds($leagueId, 2);

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamId,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 6.0,
        ]);
        $this->seedPlayerprice($ptId, $olderRoundId, 4.0);
        $this->seedPlayerprice($ptId, $newerPastRoundId, 8.5);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(8.5, $result['data']['players'][0]['playerteam_player_price']);
    }

    #[Test]
    public function team_players_skip_zero_previous_playerprice_when_falling_back(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$newerPastRoundId, $olderRoundId] = $this->seedPastRounds($leagueId, 2);

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamId,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 6.0,
        ]);
        $this->seedPlayerprice($ptId, $olderRoundId, 7.0);
        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $newerPastRoundId,
            'playerprice_price' => 0,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(7.0, $result['data']['players'][0]['playerteam_player_price']);
    }

    #[Test]
    public function team_players_fall_back_to_teamprice_when_playerprice_missing(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamId,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 6.0,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(6.0, $result['data']['players'][0]['playerteam_player_price']);
        $this->assertSame(0.0, $result['data']['players'][0]['recent_performance']);
        $this->assertDatabaseMissing('ffb_playerprice', [
            'playerprice_playerteam_id' => $ptId,
        ]);
    }

    #[Test]
    public function team_players_fall_back_to_teamprice_when_playerprice_is_zero(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();

        Teamprice::query()->insert([
            'teamprice_team_id' => $teamId,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 6.5,
        ]);

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 0,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => 0.4,
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(6.5, $result['data']['players'][0]['playerteam_player_price']);
        $this->assertSame(0.4, $result['data']['players'][0]['recent_performance']);
    }

    #[Test]
    public function matchround_hides_recent_performance_without_stored_values(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        $this->seedMatchForRound($roundId, $teamId);

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => null,
        ]);

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertFalse($result['data']['show_recent_performance']);
    }

    #[Test]
    public function matchround_shows_recent_performance_when_any_value_exists(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        $this->seedMatchForRound($roundId, $teamId);

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => 0.0,
        ]);

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertTrue($result['data']['show_recent_performance']);
    }

    #[Test]
    public function matchround_shows_recent_performance_when_only_previous_round_has_values(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        $this->seedMatchForRound($roundId, $teamId);
        [$pastRoundId] = $this->seedPastRounds($leagueId, 1);

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => null,
        ]);
        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $pastRoundId,
            'playerprice_price' => 8.0,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => 0.25,
        ]);

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertTrue($result['data']['show_recent_performance']);
    }

    #[Test]
    public function matchround_includes_cheapest_player_hints_per_team(): void
    {
        [$leagueId, $roundId, $teamId, $midPtId] = $this->seedDynamicSquad();
        $this->seedMatchForRound($roundId, $teamId);

        $defenderId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Alex',
            'player_lname' => 'Abwehr',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $defPtId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $defenderId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ], 'playerteam_id');

        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $midPtId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 9.5,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => null,
        ]);
        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $defPtId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => 4.0,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
            'playerprice_recent_performance' => null,
        ]);

        $result = $this->app->make(LineupService::class)->matchroundAndTeams(544);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertCount(1, $result['data']['matchround']['teams']);

        $team = $result['data']['matchround']['teams'][0];
        $this->assertSame($teamId, $team['team_id']);
        $this->assertSame(4.0, $team['min_player_price']);
        $this->assertSame(
            ['playerteam_id' => $defPtId, 'price' => 4.0],
            $team['cheapest_by_position']['d'],
        );
        $this->assertSame(
            ['playerteam_id' => $midPtId, 'price' => 9.5],
            $team['cheapest_by_position']['m'],
        );
        $this->assertNull($team['cheapest_by_position']['g']);
        $this->assertNull($team['cheapest_by_position']['s']);
    }

    #[Test]
    public function team_players_are_ordered_by_lastname_within_position(): void
    {
        [$leagueId, $roundId, $teamId] = $this->seedDynamicSquad();

        $secondPlayerId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Anna',
            'player_lname' => 'Alaba',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');
        $secondPtId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $secondPlayerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ], 'playerteam_id');

        $firstPtId = (int) Playerteam::query()
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_player_position', 'm')
            ->where('playerteam_id', '!=', $secondPtId)
            ->value('playerteam_id');

        Playerprice::query()->insert([
            [
                'playerprice_playerteam_id' => $firstPtId,
                'playerprice_matchround_id' => $roundId,
                'playerprice_price' => 12.0,
                'playerprice_player_power' => 1,
                'playerprice_av_power' => 1,
            ],
            [
                'playerprice_playerteam_id' => $secondPtId,
                'playerprice_matchround_id' => $roundId,
                'playerprice_price' => 3.0,
                'playerprice_player_power' => 1,
                'playerprice_av_power' => 1,
            ],
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $midfield = array_values(array_filter(
            $result['data']['players'],
            static fn (array $p): bool => $p['playerteam_player_position'] === 'm',
        ));
        $this->assertCount(2, $midfield);
        $this->assertSame('Alaba', $midfield[0]['player_lname']);
        $this->assertSame('Muster', $midfield[1]['player_lname']);
    }

    #[Test]
    public function team_players_warn_for_two_yellows_in_previous_two_rounds(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$prev1, $prev2] = $this->seedPastRounds($leagueId, 2);
        $this->seedPlayerprice($ptId, $roundId, 5.0);
        $this->seedCard($ptId, $prev1, 'y');
        $this->seedCard($ptId, $prev2, 'y');

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(
            '2 gelbe Karten in den beiden vorhergehenden Spielen.',
            $result['data']['players'][0]['card_warning'],
        );
    }

    #[Test]
    public function team_players_warn_for_yellow_red_in_previous_round(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$prev1] = $this->seedPastRounds($leagueId, 1);
        $this->seedPlayerprice($ptId, $roundId, 5.0);
        $this->seedCard($ptId, $prev1, 'yr');

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(
            'Gelb-Rot im vorhergehenden Spiel.',
            $result['data']['players'][0]['card_warning'],
        );
    }

    #[Test]
    public function team_players_warn_for_red_in_previous_three_rounds(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$prev1, $prev2, $prev3] = $this->seedPastRounds($leagueId, 3);
        $this->seedPlayerprice($ptId, $roundId, 5.0);
        $this->seedCard($ptId, $prev3, 'r');

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(
            'Rot in Past 3.',
            $result['data']['players'][0]['card_warning'],
        );
        unset($prev1, $prev2);
    }

    #[Test]
    public function team_players_prefer_red_warning_over_two_yellows(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$prev1, $prev2] = $this->seedPastRounds($leagueId, 2);
        $this->seedPlayerprice($ptId, $roundId, 5.0);
        $this->seedCard($ptId, $prev1, 'y');
        $this->seedCard($ptId, $prev2, 'r');

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame(
            'Rot in Past 2.',
            $result['data']['players'][0]['card_warning'],
        );
    }

    #[Test]
    public function team_players_include_player_note(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        $this->seedPlayerprice($ptId, $roundId, 5.0);

        Playerteam::query()->whereKey($ptId)->update([
            'playerteam_player_note' => 'Knieprobleme',
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame('Knieprobleme', $result['data']['players'][0]['playerteam_player_note']);
        $this->assertNull($result['data']['players'][0]['card_warning']);
    }

    #[Test]
    public function team_players_keep_card_warning_alongside_player_note(): void
    {
        [$leagueId, $roundId, $teamId, $ptId] = $this->seedDynamicSquad();
        [$prev1] = $this->seedPastRounds($leagueId, 1);
        $this->seedPlayerprice($ptId, $roundId, 5.0);
        $this->seedCard($ptId, $prev1, 'yr');

        Playerteam::query()->whereKey($ptId)->update([
            'playerteam_player_note' => 'Manuell gesetzt',
        ]);

        $result = $this->app->make(LineupService::class)->teamPlayers(544, $teamId, $roundId);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $this->assertSame('Manuell gesetzt', $result['data']['players'][0]['playerteam_player_note']);
        $this->assertSame(
            'Gelb-Rot im vorhergehenden Spiel.',
            $result['data']['players'][0]['card_warning'],
        );
    }

    /**
     * @return list<int>
     */
    private function seedPastRounds(int $leagueId, int $count): array
    {
        $ids = [];
        for ($i = 1; $i <= $count; $i++) {
            $ids[] = (int) Matchround::query()->insertGetId([
                'matchround_league_id' => $leagueId,
                'matchround_title' => 'Past '.$i,
                'matchround_startdate' => sprintf('2026-09-%02d 00:00:00', 28 - (($i - 1) * 7)),
                'matchround_enddate' => sprintf('2026-09-%02d 00:00:00', 29 - (($i - 1) * 7)),
                'matchround_status' => 1,
            ], 'matchround_id');
        }

        return $ids;
    }

    private function seedPlayerprice(int $ptId, int $roundId, float $price): void
    {
        Playerprice::query()->insert([
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $roundId,
            'playerprice_price' => $price,
            'playerprice_player_power' => 1,
            'playerprice_av_power' => 1,
        ]);
    }

    private function seedCard(int $ptId, int $roundId, string $card): void
    {
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => $ptId,
            'playerstats_matchround_id' => $roundId,
            'playerstats_score' => 0,
            'playerstats_cards' => $card,
        ]);
    }

    private function seedLeague(string $priceMode): int
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_archive' => 0,
        ], 'league_id');

        LeagueOptions::query()->insert([
            'options_league_id' => $leagueId,
            'options_league_pricemode' => $priceMode,
        ]);

        UserDetails::query()->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => $leagueId,
        ]);

        Matchround::query()->insert([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Future',
            'matchround_startdate' => '2026-10-01 00:00:00',
            'matchround_enddate' => '2026-10-02 00:00:00',
            'matchround_status' => 1,
        ]);

        return $leagueId;
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int}
     */
    private function seedDynamicSquad(): array
    {
        $leagueId = $this->seedLeague('dynamic');

        $roundId = (int) Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->value('matchround_id');

        $teamId = (int) Team::query()->insertGetId([
            'team_foreign_id' => '',
            'team_name' => 'Austria',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ], 'team_id');

        $playerId = (int) Player::query()->insertGetId([
            'player_foreign_id' => '',
            'player_fname' => 'Max',
            'player_lname' => 'Muster',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ], 'player_id');

        $ptId = (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ], 'playerteam_id');

        return [$leagueId, $roundId, $teamId, $ptId];
    }

    private function seedMatchForRound(int $roundId, int $teamId): void
    {
        MatchGame::query()->insert([
            'match_round' => $roundId,
            'match_hometeam_id' => $teamId,
            'match_guestteam_id' => $teamId,
            'match_date' => '2026-10-01 18:00:00',
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_homescore_penalty' => -1,
            'match_guestscore_penalty' => -1,
            'match_status' => '',
        ]);
    }

    private function createSchema(): void
    {
        Schema::create('web_user_details', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_details_ffb_selected_league')->default(0);
        });

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
            $table->integer('league_archive')->default(0);
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->integer('options_league_id')->default(0);
            $table->string('options_league_pricemode')->default('dynamic');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->integer('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->integer('matchround_status')->default(1);
        });

        Schema::create('ffb_matchround_options', function (Blueprint $table) {
            $table->increments('matchround_options_id');
            $table->integer('matchround_options_matchround_id');
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->integer('match_round');
            $table->integer('match_hometeam_id');
            $table->integer('match_guestteam_id');
            $table->string('match_date')->nullable();
            $table->integer('match_homescore')->default(-1);
            $table->integer('match_guestscore')->default(-1);
            $table->integer('match_homescore_penalty')->default(-1);
            $table->integer('match_guestscore_penalty')->default(-1);
            $table->string('match_status')->default('');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_foreign_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->integer('player_status')->default(1);
            $table->string('player_status_description')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id')->default(0);
            $table->string('playerteam_player_picture')->default('');
            $table->integer('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_player_note')->default('');
            $table->string('playerteam_date_transfer')->default('2008-01-01 00:00:00');
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->integer('playerstats_score')->default(0);
            $table->string('playerstats_cards')->default('n');
        });

        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
            $table->double('playerprice_player_power')->default(0);
            $table->double('playerprice_av_power')->default(0);
            $table->double('playerprice_recent_performance')->nullable();
        });

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
        });
    }
}
