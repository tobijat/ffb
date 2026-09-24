<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamelo;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerpriceService;
use App\Services\EloRatingClient;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use App\Services\LineupOptionsResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPlayerpricePerformanceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_teamelo');
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function performance_tab_shows_form(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')
                ->once()
                ->with(544, null, 'performance', null, null, null, null)
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                    ],
                    'price_league_id' => 7,
                    'leagues' => [
                        ['league_id' => 7, 'league_title' => 'Bundesliga Test'],
                    ],
                    'tab' => 'performance',
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Runde 1'],
                    ],
                    'matchround_id' => 0,
                    'lineup_max_credits' => 100.0,
                    'lineup_max_players_team' => 3,
                    'lineup_limits_source' => 'league',
                    'elo_exponent' => 2.0,
                    'elo_dream_team_ratio' => 1.5,
                    'elo_min_price' => 1.0,
                    'team_price_preview' => null,
                    'performance_preview' => null,
                    'performance_has_teamelos' => false,
                    'performance_opponent_weight' => 0.25,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice?tab=performance')
            ->assertOk()
            ->assertSee('Spieler-Performance', false)
            ->assertSee('Spielrunde', false)
            ->assertDontSee('Matchround-Performance berechnen', false)
            ->assertDontSee('Spieler-Preis', false)
            ->assertDontSee('ELO Team-Preis', false);
    }

    #[Test]
    public function preview_maps_rank_to_round_performance_without_writing(): void
    {
        [$leagueId, $matchroundId] = $this->seedRoundWithScores();

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
        ]);

        $this->assertTrue($result['ok']);
        $this->assertSame('performance', $result['tab']);
        $this->assertSame($matchroundId, $result['matchround_id']);

        $players = $result['preview']['players'];
        $this->assertCount(3, $players);

        $byName = [];
        foreach ($players as $row) {
            $byName[$row['player_name']] = $row;
        }

        // Position d: 0 → rank 0, 5 → rank 1, 10 → rank 2 (n=3) → −1, 0, +1
        $this->assertSame(0.0, $byName['Ada Alaba']['rank']);
        $this->assertSame(-1.0, $byName['Ada Alaba']['round_performance']);
        $this->assertSame(1.0, $byName['Hans Gast']['rank']);
        $this->assertSame(0.0, $byName['Hans Gast']['round_performance']);
        $this->assertSame(2.0, $byName['Max Muster']['rank']);
        $this->assertSame(1.0, $byName['Max Muster']['round_performance']);
        $this->assertSame('Deutschland', $byName['Hans Gast']['team_name']);
        $this->assertArrayNotHasKey('Bench Warm', $byName);

        $this->assertDatabaseHas('ffb_playerstats', [
            'playerstats_id' => 1,
            'playerstats_round_performance' => null,
        ]);
        $this->assertDatabaseHas('ffb_playerstats', [
            'playerstats_id' => 2,
            'playerstats_round_performance' => null,
        ]);
    }

    #[Test]
    public function preview_averages_ranks_for_tied_points(): void
    {
        $league = League::query()->create(['league_title' => 'Testliga']);
        $leagueId = (int) $league->league_id;
        $round = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
        ]);
        $matchroundId = (int) $round->matchround_id;

        $team = Team::query()->create([
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);

        $seed = [
            ['fname' => 'Low', 'lname' => 'One', 'score' => 1],
            ['fname' => 'Tie', 'lname' => 'A', 'score' => 8],
            ['fname' => 'Tie', 'lname' => 'B', 'score' => 8],
        ];
        foreach ($seed as $index => $row) {
            $player = Player::query()->create([
                'player_fname' => $row['fname'],
                'player_lname' => $row['lname'],
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $team->team_id,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 's',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_id' => $index + 1,
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $matchroundId,
                'playerstats_match_id' => 1,
                'playerstats_minutes' => 90,
                'playerstats_score' => $row['score'],
                'playerstats_round_performance' => null,
            ]);
        }

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
        ]);

        $byName = [];
        foreach ($result['preview']['players'] as $row) {
            $byName[$row['player_name']] = $row;
        }

        // ranks 0 | 1+2 avg 1.5 → −1 | (1.5/2)*2−1 = 0.5
        $this->assertSame(0.0, $byName['Low One']['rank']);
        $this->assertSame(-1.0, $byName['Low One']['round_performance']);
        $this->assertSame(1.5, $byName['Tie A']['rank']);
        $this->assertSame(0.5, $byName['Tie A']['round_performance']);
        $this->assertSame(1.5, $byName['Tie B']['rank']);
        $this->assertSame(0.5, $byName['Tie B']['round_performance']);
    }

    #[Test]
    public function preview_sets_zero_when_only_one_player_at_position(): void
    {
        $league = League::query()->create(['league_title' => 'Testliga']);
        $leagueId = (int) $league->league_id;
        $round = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
        ]);
        $matchroundId = (int) $round->matchround_id;

        $team = Team::query()->create([
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);
        $player = Player::query()->create([
            'player_fname' => 'Only',
            'player_lname' => 'One',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_status_description' => '',
        ]);
        $pt = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $team->team_id,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'g',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $pt->playerteam_id,
            'playerstats_matchround_id' => $matchroundId,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => 90,
            'playerstats_score' => 12,
            'playerstats_round_performance' => null,
        ]);

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
        ]);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['preview']['players']);
        $this->assertSame(0.0, $result['preview']['players'][0]['rank']);
        $this->assertSame(0.0, $result['preview']['players'][0]['round_performance']);
    }

    #[Test]
    public function preview_ranks_against_position_peers_from_prior_and_current_rounds(): void
    {
        $league = League::query()->create(['league_title' => 'Testliga']);
        $leagueId = (int) $league->league_id;

        $group = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Group',
            'matchround_startdate' => '2026-06-01 00:00:00',
        ]);
        $final = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Final',
            'matchround_startdate' => '2026-07-01 00:00:00',
        ]);
        $groupId = (int) $group->matchround_id;
        $finalId = (int) $final->matchround_id;

        $teamA = Team::query()->create([
            'team_name' => 'A',
            'team_nationality' => 'AAA',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);
        $teamB = Team::query()->create([
            'team_name' => 'B',
            'team_nationality' => 'BBB',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);

        // Group: four GK appearances with scores 2, 4, 6, 8
        $groupScores = [2, 4, 6, 8];
        $statsId = 1;
        foreach ($groupScores as $index => $score) {
            $player = Player::query()->create([
                'player_fname' => 'Group',
                'player_lname' => (string) $score,
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => $index % 2 === 0 ? (int) $teamA->team_id : (int) $teamB->team_id,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'g',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_id' => $statsId++,
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $groupId,
                'playerstats_match_id' => 1,
                'playerstats_minutes' => 90,
                'playerstats_score' => $score,
                'playerstats_round_performance' => null,
            ]);
        }

        // Final: only two GKs (scores 5 and 7) — without league peer set they would be −1 / +1
        foreach (
            [
                ['fname' => 'Final', 'lname' => 'Low', 'team' => $teamA, 'score' => 5],
                ['fname' => 'Final', 'lname' => 'High', 'team' => $teamB, 'score' => 7],
            ] as $row
        ) {
            $player = Player::query()->create([
                'player_fname' => $row['fname'],
                'player_lname' => $row['lname'],
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $row['team']->team_id,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'g',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_id' => $statsId++,
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $finalId,
                'playerstats_match_id' => 2,
                'playerstats_minutes' => 90,
                'playerstats_score' => $row['score'],
                'playerstats_round_performance' => null,
            ]);
        }

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $finalId,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame(6, $result['preview']['positions']['g']['sample_size']);
        $this->assertCount(2, $result['preview']['players']);

        $byName = [];
        foreach ($result['preview']['players'] as $row) {
            $byName[$row['player_name']] = $row;
        }

        // Peer scores 2,4,5,6,7,8 → ranks 0..5; Low(5)=2 → (2/5)*2−1=−0.2; High(7)=4 → 0.6
        $this->assertSame(2.0, $byName['Final Low']['rank']);
        $this->assertSame(-0.2, $byName['Final Low']['round_performance']);
        $this->assertSame(4.0, $byName['Final High']['rank']);
        $this->assertSame(0.6, $byName['Final High']['round_performance']);
    }

    #[Test]
    public function preview_applies_opponent_strength_when_requested(): void
    {
        [$leagueId, $matchroundId, $austriaId, $germanyId] = $this->seedRoundWithScoresAndMatch();

        Teamelo::query()->insert([
            [
                'teamelo_team_id' => $austriaId,
                'teamelo_league_id' => $leagueId,
                'teamelo_elo' => 1500.0,
                'teamelo_elo_year' => 2026,
            ],
            [
                'teamelo_team_id' => $germanyId,
                'teamelo_league_id' => $leagueId,
                'teamelo_elo' => 2000.0,
                'teamelo_elo_year' => 2026,
            ],
        ]);

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
            'include_opponent_strength' => '1',
            'opponent_weight' => 0.25,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertTrue($result['preview']['include_opponent_strength']);

        $byName = [];
        foreach ($result['preview']['players'] as $row) {
            $byName[$row['player_name']] = $row;
        }

        // elo span 500 (only two teams in league); Austria vs Germany: factor (2000-1500)/500 = 1.0
        // Ada raw −1 → clamp(−1 + 0.25*1, −1, 1) = −0.75
        $this->assertSame(-1.0, $byName['Ada Alaba']['raw_round_performance']);
        $this->assertSame(1.0, $byName['Ada Alaba']['opponent_factor']);
        $this->assertSame(-0.75, $byName['Ada Alaba']['round_performance']);

        // Hans (Germany) factor (1500-2000)/500 = −1.0; raw 0 → −0.25
        $this->assertSame(0.0, $byName['Hans Gast']['raw_round_performance']);
        $this->assertSame(-1.0, $byName['Hans Gast']['opponent_factor']);
        $this->assertSame(-0.25, $byName['Hans Gast']['round_performance']);
    }

    #[Test]
    public function opponent_factor_uses_full_league_elo_span_not_only_matchround_teams(): void
    {
        [$leagueId, $matchroundId, $austriaId, $germanyId] = $this->seedRoundWithScoresAndMatch();

        $weak = Team::query()->create([
            'team_name' => 'San Marino',
            'team_nationality' => 'SMR',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);
        $weakId = (int) $weak->team_id;

        $earlier = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'Group',
            'matchround_startdate' => now()->subDays(10)->toDateTimeString(),
        ]);
        MatchGame::query()->insert([
            'match_round' => (int) $earlier->matchround_id,
            'match_hometeam_id' => $weakId,
            'match_guestteam_id' => $austriaId,
            'match_date' => '2026-07-01',
            'match_status' => '',
            'match_minutes' => 90,
        ]);

        // Finalists are close (1900 vs 2000); league floor is 1000 → span 1000, factor ±0.1
        Teamelo::query()->insert([
            [
                'teamelo_team_id' => $weakId,
                'teamelo_league_id' => $leagueId,
                'teamelo_elo' => 1000.0,
                'teamelo_elo_year' => 2026,
            ],
            [
                'teamelo_team_id' => $austriaId,
                'teamelo_league_id' => $leagueId,
                'teamelo_elo' => 1900.0,
                'teamelo_elo_year' => 2026,
            ],
            [
                'teamelo_team_id' => $germanyId,
                'teamelo_league_id' => $leagueId,
                'teamelo_elo' => 2000.0,
                'teamelo_elo_year' => 2026,
            ],
        ]);

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
            'include_opponent_strength' => '1',
            'opponent_weight' => 0.25,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));

        $byName = [];
        foreach ($result['preview']['players'] as $row) {
            $byName[$row['player_name']] = $row;
        }

        $this->assertSame(0.1, $byName['Ada Alaba']['opponent_factor']);
        $this->assertSame(-0.975, $byName['Ada Alaba']['round_performance']);
        $this->assertSame(-0.1, $byName['Hans Gast']['opponent_factor']);
        $this->assertSame(-0.025, $byName['Hans Gast']['round_performance']);
    }

    #[Test]
    public function preview_rejects_opponent_strength_without_complete_teamelos(): void
    {
        [$leagueId, $matchroundId] = $this->seedRoundWithScoresAndMatch();

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
            'include_opponent_strength' => '1',
        ]);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('ELO', $result['errors'][0] ?? '');
    }

    #[Test]
    public function preview_rejects_missing_matchround(): void
    {
        $league = League::query()->create(['league_title' => 'Testliga']);

        $result = $this->service((int) $league->league_id)->previewMatchroundPerformance(544, []);

        $this->assertFalse($result['ok']);
        $this->assertSame('performance', $result['tab']);
    }

    #[Test]
    public function preview_rounds_performance_to_thousandths(): void
    {
        $league = League::query()->create(['league_title' => 'Testliga']);
        $leagueId = (int) $league->league_id;
        $round = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
        ]);
        $matchroundId = (int) $round->matchround_id;

        $team = Team::query()->create([
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);

        foreach ([1, 2, 3, 4] as $index => $score) {
            $player = Player::query()->create([
                'player_fname' => 'P',
                'player_lname' => (string) $score,
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $team->team_id,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => 'm',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_id' => $index + 1,
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $matchroundId,
                'playerstats_match_id' => 1,
                'playerstats_minutes' => 90,
                'playerstats_score' => $score,
                'playerstats_round_performance' => null,
            ]);
        }

        $result = $this->service($leagueId)->previewMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
        ]);

        $byName = [];
        foreach ($result['preview']['players'] as $row) {
            $byName[$row['player_name']] = $row;
        }

        // ranks 0..3 → −1, −1/3, +1/3, +1 → rounded to 3 decimals
        $this->assertSame(-1.0, $byName['P 1']['round_performance']);
        $this->assertSame(-0.333, $byName['P 2']['round_performance']);
        $this->assertSame(0.333, $byName['P 3']['round_performance']);
        $this->assertSame(1.0, $byName['P 4']['round_performance']);
    }

    #[Test]
    public function save_writes_round_performance_to_playerstats(): void
    {
        [$leagueId, $matchroundId] = $this->seedRoundWithScores();

        $result = $this->service($leagueId)->saveMatchroundPerformance(544, [
            'matchround_id' => $matchroundId,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertStringContainsString('gespeichert', $result['message'] ?? '');

        $this->assertDatabaseHas('ffb_playerstats', [
            'playerstats_id' => 1,
            'playerstats_round_performance' => -1.0,
        ]);
        $this->assertDatabaseHas('ffb_playerstats', [
            'playerstats_id' => 2,
            'playerstats_round_performance' => 1.0,
        ]);
        $this->assertDatabaseHas('ffb_playerstats', [
            'playerstats_id' => 3,
            'playerstats_round_performance' => 0.0,
        ]);
        $this->assertDatabaseHas('ffb_playerstats', [
            'playerstats_id' => 4,
            'playerstats_round_performance' => null,
        ]);
    }

    #[Test]
    public function performance_tab_disables_save_until_preview_and_checks_opponent_by_default(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminPlayerpriceService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')
                ->once()
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                    ],
                    'price_league_id' => 7,
                    'leagues' => [
                        ['league_id' => 7, 'league_title' => 'Bundesliga Test'],
                    ],
                    'tab' => 'performance',
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Runde 1'],
                    ],
                    'matchround_id' => 3,
                    'lineup_max_credits' => 100.0,
                    'lineup_max_players_team' => 3,
                    'lineup_limits_source' => 'league',
                    'elo_exponent' => 2.0,
                    'elo_dream_team_ratio' => 1.5,
                    'elo_min_price' => 1.0,
                    'team_price_preview' => null,
                    'performance_preview' => null,
                    'performance_has_teamelos' => true,
                    'performance_opponent_weight' => 0.25,
                ]);
        });

        $html = $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice?tab=performance&matchround_id=3')
            ->assertOk()
            ->assertSee('Matchround-Performance berechnen', false)
            ->assertSee('Speichern', false)
            ->assertSee('Gegnerstärke (ELO) einbeziehen', false)
            ->getContent();

        $this->assertSame(1, preg_match(
            '/<button\b[^>]*\bname="save_matchround_performance"[^>]*>/',
            $html,
            $saveButton,
        ));
        $this->assertStringContainsString('disabled', $saveButton[0]);

        $this->assertSame(1, preg_match(
            '/<input\b[^>]*\bname="include_opponent_strength"[^>]*\bvalue="1"[^>]*>/',
            $html,
            $checkbox,
        ));
        $this->assertMatchesRegularExpression('/\bchecked\b/', $checkbox[0]);
    }

    #[Test]
    public function performance_tab_enables_save_after_preview(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $preview = [
            'matchround_id' => 3,
            'include_opponent_strength' => true,
            'opponent_weight' => 0.25,
            'positions' => ['d' => 3],
            'players' => [
                [
                    'playerstats_id' => 1,
                    'player_name' => 'Ada Alaba',
                    'team_name' => 'Österreich',
                    'position' => 'd',
                    'score' => 0,
                    'rank' => 0.0,
                    'raw_round_performance' => -1.0,
                    'opponent_factor' => 1.0,
                    'round_performance' => -0.75,
                ],
            ],
        ];

        $this->mock(AdminPlayerpriceService::class, function ($mock) use ($preview) {
            $mock->shouldReceive('previewMatchroundPerformance')
                ->once()
                ->andReturn([
                    'ok' => true,
                    'message' => 'Matchround-Performance berechnet (Vorschau, nicht gespeichert; inkl. Gegnerstärke).',
                    'price_league_id' => 7,
                    'matchround_id' => 3,
                    'tab' => 'performance',
                    'preview' => $preview,
                ]);

            $mock->shouldReceive('pagePayload')
                ->once()
                ->andReturn([
                    'user' => [
                        'user_id' => 544,
                        'user_nickname' => 'adminuser',
                        'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                        'is_ffb_admin' => true,
                    ],
                    'navigation' => [],
                    'selected_league_id' => 7,
                    'selected_league' => [
                        'league_id' => 7,
                        'league_title' => 'Bundesliga Test',
                    ],
                    'price_league_id' => 7,
                    'leagues' => [
                        ['league_id' => 7, 'league_title' => 'Bundesliga Test'],
                    ],
                    'tab' => 'performance',
                    'matchrounds' => [
                        ['matchround_id' => 3, 'matchround_title' => 'Runde 1'],
                    ],
                    'matchround_id' => 3,
                    'lineup_max_credits' => 100.0,
                    'lineup_max_players_team' => 3,
                    'lineup_limits_source' => 'league',
                    'elo_exponent' => 2.0,
                    'elo_dream_team_ratio' => 1.5,
                    'elo_min_price' => 1.0,
                    'team_price_preview' => null,
                    'performance_preview' => $preview,
                    'performance_has_teamelos' => true,
                    'performance_opponent_weight' => 0.25,
                ]);
        });

        $html = $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/playerprice/matchround-performance/preview', [
                'price_league_id' => 7,
                'tab' => 'performance',
                'matchround_id' => 3,
                'include_opponent_strength' => '1',
            ])
            ->assertOk()
            ->assertSee('Ada Alaba', false)
            ->getContent();

        $this->assertSame(1, preg_match(
            '/<button\b[^>]*\bname="save_matchround_performance"[^>]*>/',
            $html,
            $saveButton,
        ));
        $this->assertStringNotContainsString('disabled', $saveButton[0]);
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedRoundWithScores(): array
    {
        [$leagueId, $matchroundId] = $this->seedRoundWithScoresAndMatch();

        return [$leagueId, $matchroundId];
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int}
     */
    private function seedRoundWithScoresAndMatch(): array
    {
        $league = League::query()->create(['league_title' => 'Testliga']);
        $leagueId = (int) $league->league_id;
        $round = Matchround::query()->create([
            'matchround_league_id' => $leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDay()->toDateTimeString(),
        ]);
        $matchroundId = (int) $round->matchround_id;

        $austria = Team::query()->create([
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);
        $germany = Team::query()->create([
            'team_name' => 'Deutschland',
            'team_nationality' => 'GER',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);
        $austriaId = (int) $austria->team_id;
        $germanyId = (int) $germany->team_id;

        $matchId = (int) MatchGame::query()->insertGetId([
            'match_round' => $matchroundId,
            'match_hometeam_id' => $austriaId,
            'match_guestteam_id' => $germanyId,
            'match_date' => '2026-08-01',
            'match_status' => '',
            'match_minutes' => 90,
        ], 'match_id');

        $players = [
            ['fname' => 'Ada', 'lname' => 'Alaba', 'team' => $austria, 'pos' => 'd', 'score' => 0, 'minutes' => 90],
            ['fname' => 'Max', 'lname' => 'Muster', 'team' => $austria, 'pos' => 'd', 'score' => 10, 'minutes' => 90],
            ['fname' => 'Hans', 'lname' => 'Gast', 'team' => $germany, 'pos' => 'd', 'score' => 5, 'minutes' => 90],
            ['fname' => 'Bench', 'lname' => 'Warm', 'team' => $austria, 'pos' => 'd', 'score' => 99, 'minutes' => 0],
        ];

        $id = 1;
        foreach ($players as $row) {
            $player = Player::query()->create([
                'player_fname' => $row['fname'],
                'player_lname' => $row['lname'],
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $row['team']->team_id,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => $row['pos'],
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_id' => $id,
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $matchroundId,
                'playerstats_match_id' => $matchId,
                'playerstats_minutes' => $row['minutes'],
                'playerstats_score' => $row['score'],
                'playerstats_round_performance' => null,
            ]);
            $id++;
        }

        return [$leagueId, $matchroundId, $austriaId, $germanyId];
    }

    private function service(int $leagueId): AdminPlayerpriceService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('selectedLeagueId')->andReturn($leagueId)->byDefault();
        $adminCenter->shouldReceive('shellPayload')->andReturn([
            'user' => ['user_id' => 544],
            'navigation' => [],
            'selected_league' => ['league_id' => $leagueId],
            'selected_league_id' => $leagueId,
        ])->byDefault();

        $lineup = Mockery::mock(LineupOptionsResolver::class);
        $lineup->shouldReceive('forLeague')->andReturn([
            'lineup_max_credits' => 100,
            'lineup_max_players_team' => 3,
            'source' => 'league',
        ])->byDefault();
        $lineup->shouldReceive('forMatchround')->andReturn([
            'lineup_max_credits' => 100,
            'lineup_max_players_team' => 3,
            'source' => 'matchround',
        ])->byDefault();

        return new AdminPlayerpriceService(
            $adminCenter,
            Mockery::mock(EloRatingClient::class),
            $lineup,
        );
    }

    private function createSchema(): void
    {
        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->string('matchround_startdate')->nullable();
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
            $table->string('playerteam_date_transfer')->default('2008-01-01 00:00:00');
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->integer('match_round');
            $table->integer('match_hometeam_id');
            $table->integer('match_guestteam_id');
            $table->string('match_date')->nullable();
            $table->string('match_status')->default('');
            $table->integer('match_minutes')->default(0);
        });

        Schema::create('ffb_teamelo', function (Blueprint $table) {
            $table->increments('teamelo_id');
            $table->unsignedInteger('teamelo_team_id');
            $table->unsignedInteger('teamelo_league_id');
            $table->double('teamelo_elo');
            $table->unsignedSmallInteger('teamelo_elo_year');
            $table->unique(['teamelo_team_id', 'teamelo_league_id']);
        });

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
            $table->unique(['teamprice_team_id', 'teamprice_matchround_id']);
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->unsignedInteger('playerstats_match_id')->nullable();
            $table->integer('playerstats_minutes')->default(0);
            $table->double('playerstats_score')->default(0);
            $table->double('playerstats_round_performance')->nullable();
        });
    }
}
