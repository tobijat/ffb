<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
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

class AdminPlayerpriceRecentPerformanceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_playerstats');
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
    public function recent_tab_shows_form(): void
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
                    'tab' => 'recent',
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
                    'recent_performance_preview' => null,
                    'recent_lookback_rounds' => 5,
                    'recent_decay_factor' => 0.7,
                    'recent_max_price_adjustment' => 2.0,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/playerprice?tab=recent')
            ->assertOk()
            ->assertSee('Recent Performance', false)
            ->assertSee('Spielrunde', false)
            ->assertDontSee('Recent-Performance & Spielerpreis berechnen', false)
            ->assertDontSee('Spieler-Preis', false)
            ->assertDontSee('Matchround-Performance berechnen', false);
    }

    #[Test]
    public function preview_rejects_when_selected_round_performance_incomplete(): void
    {
        [$leagueId, $_prior, $selectedId] = $this->seedLeagueWithRounds(2);
        $team = Team::query()->firstOrFail();
        $this->addSquadPlayer($leagueId, (int) $team->team_id, 'Ada', 'Alaba', 'd');
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => 1,
            'playerstats_matchround_id' => $selectedId,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => 90,
            'playerstats_score' => 5,
            'playerstats_round_performance' => null,
        ]);

        $result = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
        ]);

        $this->assertFalse($result['ok']);
        $this->assertSame('recent', $result['tab']);
        $this->assertStringContainsString('round_performance', $result['errors'][0] ?? '');
    }

    #[Test]
    public function preview_computes_weighted_average_with_missed_rounds_diluting(): void
    {
        [$leagueId, $r1, $r2, $r3, $selectedId] = $this->seedFourRounds();
        $team = Team::query()->firstOrFail();
        $teamId = (int) $team->team_id;
        $this->addMatch($selectedId, $teamId, $teamId);
        $this->setTeamprice($teamId, $selectedId, 10.0);

        $full = $this->addSquadPlayer($leagueId, $teamId, 'Full', 'Time', 'd');
        $partial = $this->addSquadPlayer($leagueId, $teamId, 'Part', 'Time', 'm');
        $bench = $this->addSquadPlayer($leagueId, $teamId, 'No', 'Play', 's');

        // Selected round: all starters have round_performance
        $this->stats($full, $selectedId, 90, 1.0);
        $this->stats($partial, $selectedId, 90, 0.5);
        $this->stats($bench, $selectedId, 0, null);

        // Prior newest-first: r3, r2, r1 — lookback 3, decay 0.7 → weights 1, 0.7, 0.49
        $this->stats($full, $r3, 90, 1.0);
        $this->stats($full, $r2, 90, 0.0);
        $this->stats($full, $r1, 90, -1.0);

        $this->stats($partial, $r3, 90, 1.0);
        // partial missed r2 and r1

        $result = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 3,
            'decay_factor' => 0.7,
            'max_price_adjustment' => 2.0,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame([$r3, $r2, $r1], $result['preview']['prior_matchround_ids']);
        $this->assertSame([1.0, 0.7, 0.49], $result['preview']['weights']);
        $this->assertSame(2.0, $result['preview']['max_price_adjustment']);

        $byName = [];
        foreach ($result['preview']['players'] as $row) {
            $byName[$row['player_name']] = $row;
        }

        // Full: (1*1 + 0*0.7 + (-1)*0.49) / (1+0.7+0.49) = 0.51 / 2.19 ≈ 0.233
        $this->assertSame(['1', '0', '-1'], $byName['Full Time']['round_performance']);
        $this->assertSame(3, $byName['Full Time']['rounds_played']);
        $this->assertSame(0.233, $byName['Full Time']['recent_performance']);
        $this->assertSame(0.466, $byName['Full Time']['price_adjustment']);
        $this->assertSame(10.5, $byName['Full Time']['player_price']);

        // Partial: 1.0 / 2.19 ≈ 0.457
        $this->assertSame(['1', '-', '-'], $byName['Part Time']['round_performance']);
        $this->assertSame(1, $byName['Part Time']['rounds_played']);
        $this->assertSame(0.457, $byName['Part Time']['recent_performance']);
        $this->assertSame(0.914, $byName['Part Time']['price_adjustment']);
        $this->assertSame(10.9, $byName['Part Time']['player_price']);

        // No play in lookback
        $this->assertSame(['-', '-', '-'], $byName['No Play']['round_performance']);
        $this->assertSame(0, $byName['No Play']['rounds_played']);
        $this->assertSame(0.0, $byName['No Play']['recent_performance']);
        $this->assertSame(0.0, $byName['No Play']['price_adjustment']);
        $this->assertSame(10.0, $byName['No Play']['player_price']);

        $this->assertCount(3, $result['preview']['prior_matchrounds']);
        $this->assertSame('R3', $result['preview']['prior_matchrounds'][0]['matchround_title']);
    }

    #[Test]
    public function preview_uses_zero_prior_rounds_when_selected_is_first(): void
    {
        [$leagueId, $selectedId] = $this->seedLeagueWithRounds(1);
        $team = Team::query()->firstOrFail();
        $this->addMatch($selectedId, (int) $team->team_id, (int) $team->team_id);
        $this->setTeamprice((int) $team->team_id, $selectedId, 10.0);
        $pt = $this->addSquadPlayer($leagueId, (int) $team->team_id, 'Only', 'One', 'g');
        $this->stats($pt, $selectedId, 90, 0.25);

        $result = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 5,
            'decay_factor' => 0.7,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertSame([], $result['preview']['prior_matchround_ids']);
        $this->assertSame([], $result['preview']['prior_matchrounds']);
        $this->assertSame([], $result['preview']['weights']);
        $this->assertCount(1, $result['preview']['players']);
        $this->assertSame([], $result['preview']['players'][0]['round_performance']);
        $this->assertSame(0.0, $result['preview']['players'][0]['recent_performance']);
    }

    #[Test]
    public function preview_does_not_write_playerprice(): void
    {
        [$leagueId, $r1, $r2, $selectedId] = $this->seedThreeRounds();
        $team = Team::query()->firstOrFail();
        $this->addMatch($selectedId, (int) $team->team_id, (int) $team->team_id);
        $this->setTeamprice((int) $team->team_id, $selectedId, 10.0);
        $pt = $this->addSquadPlayer($leagueId, (int) $team->team_id, 'Ada', 'Alaba', 'd');
        $this->stats($pt, $selectedId, 90, 1.0);
        $this->stats($pt, $r2, 90, 0.5);
        $this->stats($pt, $r1, 90, -0.5);

        $result = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
        ]);

        $this->assertTrue($result['ok']);
        $this->assertDatabaseCount('ffb_playerprice', 0);
    }

    #[Test]
    public function save_writes_recent_performance_without_price_when_unchecked(): void
    {
        [$leagueId, $r1, $selectedId] = $this->seedLeagueWithRounds(2);
        $team = Team::query()->firstOrFail();
        $teamId = (int) $team->team_id;
        $this->addMatch($selectedId, $teamId, $teamId);
        $this->setTeamprice($teamId, $selectedId, 10.0);
        $pt = $this->addSquadPlayer($leagueId, $teamId, 'Ada', 'Alaba', 'd');
        $this->stats($pt, $selectedId, 90, 0.0);
        $this->stats($pt, $r1, 90, 0.5);

        $result = $this->service($leagueId)->saveRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 1,
            'decay_factor' => 0.7,
            'max_price_adjustment' => 2.0,
            'save_player_prices' => '0',
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertStringNotContainsString('inkl. Spielerpreise', $result['message'] ?? '');
        $this->assertDatabaseHas('ffb_playerprice', [
            'playerprice_playerteam_id' => $pt,
            'playerprice_matchround_id' => $selectedId,
            'playerprice_recent_performance' => 0.5,
            'playerprice_price' => 0,
        ]);
    }

    #[Test]
    public function save_writes_recent_performance_and_price_when_checked(): void
    {
        [$leagueId, $r1, $selectedId] = $this->seedLeagueWithRounds(2);
        $team = Team::query()->firstOrFail();
        $teamId = (int) $team->team_id;
        $this->addMatch($selectedId, $teamId, $teamId);
        $this->setTeamprice($teamId, $selectedId, 10.0);
        $pt = $this->addSquadPlayer($leagueId, $teamId, 'Ada', 'Alaba', 'd');
        $this->stats($pt, $selectedId, 90, 0.0);
        $this->stats($pt, $r1, 90, 0.5);

        $result = $this->service($leagueId)->saveRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 1,
            'decay_factor' => 0.7,
            'max_price_adjustment' => 2.0,
            'save_player_prices' => '1',
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertStringContainsString('inkl. Spielerpreise', $result['message'] ?? '');
        // recent=0.5, adj=1.0, price=11.0
        $this->assertDatabaseHas('ffb_playerprice', [
            'playerprice_playerteam_id' => $pt,
            'playerprice_matchround_id' => $selectedId,
            'playerprice_recent_performance' => 0.5,
            'playerprice_price' => 11.0,
        ]);
    }

    #[Test]
    public function preview_excludes_players_from_teams_without_match_in_selected_round(): void
    {
        [$leagueId, $r1, $selectedId] = $this->seedLeagueWithRounds(2);
        $playing = Team::query()->firstOrFail();
        $idle = Team::query()->create([
            'team_name' => 'Idle FC',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);

        $this->addMatch($selectedId, (int) $playing->team_id, (int) $playing->team_id);
        $this->setTeamprice((int) $playing->team_id, $selectedId, 10.0);

        $included = $this->addSquadPlayer($leagueId, (int) $playing->team_id, 'In', 'Squad', 'd');
        $excluded = $this->addSquadPlayer($leagueId, (int) $idle->team_id, 'Out', 'Squad', 'd');

        $this->stats($included, $selectedId, 90, 0.5);
        $this->stats($excluded, $selectedId, 90, 0.8);
        $this->stats($included, $r1, 90, 1.0);
        $this->stats($excluded, $r1, 90, -1.0);

        $result = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 5,
            'decay_factor' => 0.7,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $names = array_column($result['preview']['players'], 'player_name');
        $this->assertSame(['In Squad'], $names);
    }

    #[Test]
    public function preview_fills_lookback_with_external_league_rounds_when_enabled(): void
    {
        [$leagueId, $leaguePriorId, $selectedId] = $this->seedLeagueWithRounds(2);
        $otherLeague = League::query()->create(['league_title' => 'Andere Liga']);
        $otherLeagueId = (int) $otherLeague->league_id;

        $team = Team::query()->firstOrFail();
        $teamId = (int) $team->team_id;
        $this->addMatch($selectedId, $teamId, $teamId);
        $this->setTeamprice($teamId, $selectedId, 8.0);

        $externalNewer = Matchround::query()->create([
            'matchround_league_id' => $otherLeagueId,
            'matchround_title' => 'Ext Neu',
            'matchround_startdate' => '2026-01-15 12:00:00',
        ]);
        $externalNewerId = (int) $externalNewer->matchround_id;
        $externalOlder = Matchround::query()->create([
            'matchround_league_id' => $otherLeagueId,
            'matchround_title' => 'Ext Alt',
            'matchround_startdate' => '2025-12-01 12:00:00',
        ]);
        $externalOlderId = (int) $externalOlder->matchround_id;
        $incompleteExternal = Matchround::query()->create([
            'matchround_league_id' => $otherLeagueId,
            'matchround_title' => 'Ext Incomplete',
            'matchround_startdate' => '2026-01-20 12:00:00',
        ]);
        $incompleteExternalId = (int) $incompleteExternal->matchround_id;

        $this->addMatch($externalNewerId, $teamId, $teamId);
        $this->addMatch($externalOlderId, $teamId, $teamId);
        $this->addMatch($incompleteExternalId, $teamId, $teamId);

        // League playerteam (selected league) + other-league playerteam for same player/team
        $player = Player::query()->create([
            'player_fname' => 'Ada',
            'player_lname' => 'Alaba',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_status_description' => '',
        ]);
        $leaguePt = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);
        $externalPt = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $otherLeagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $this->stats((int) $leaguePt->playerteam_id, $selectedId, 90, 0.1);
        $this->stats((int) $leaguePt->playerteam_id, $leaguePriorId, 90, 0.5);
        $this->stats((int) $externalPt->playerteam_id, $externalNewerId, 90, 1.0);
        $this->stats((int) $externalPt->playerteam_id, $externalOlderId, 90, -1.0);
        $this->stats((int) $externalPt->playerteam_id, $incompleteExternalId, 90, null);

        $without = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 3,
            'decay_factor' => 0.7,
            'include_external_rounds' => '0',
        ]);
        $this->assertTrue($without['ok'], implode('; ', $without['errors'] ?? []));
        $this->assertSame([$leaguePriorId], $without['preview']['prior_matchround_ids']);

        $with = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 3,
            'decay_factor' => 0.7,
            'include_external_rounds' => '1',
        ]);
        $this->assertTrue($with['ok'], implode('; ', $with['errors'] ?? []));
        // Chronological newest-first; incomplete external (Jan 20) excluded
        $this->assertSame(
            [$externalNewerId, $leaguePriorId, $externalOlderId],
            $with['preview']['prior_matchround_ids'],
        );
        $this->assertTrue($with['preview']['prior_matchrounds'][0]['external']);
        $this->assertFalse($with['preview']['prior_matchrounds'][1]['external']);
        $this->assertTrue($with['preview']['prior_matchrounds'][2]['external']);
        $this->assertSame(['1', '0.5', '-1'], $with['preview']['players'][0]['round_performance']);
        // (1*1 + 0.5*0.7 + (-1)*0.49) / 2.19 = 0.86 / 2.19 ≈ 0.393
        $this->assertSame(0.393, $with['preview']['players'][0]['recent_performance']);
        $this->assertSame(0.786, $with['preview']['players'][0]['price_adjustment']);
        $this->assertSame(8.8, $with['preview']['players'][0]['player_price']);
    }

    #[Test]
    public function preview_floors_player_price_at_one_credit(): void
    {
        [$leagueId, $r1, $selectedId] = $this->seedLeagueWithRounds(2);
        $team = Team::query()->firstOrFail();
        $teamId = (int) $team->team_id;
        $this->addMatch($selectedId, $teamId, $teamId);
        $this->setTeamprice($teamId, $selectedId, 1.5);

        $pt = $this->addSquadPlayer($leagueId, $teamId, 'Cheap', 'Player', 'd');
        $this->stats($pt, $selectedId, 90, 0.0);
        $this->stats($pt, $r1, 90, -1.0);

        $result = $this->service($leagueId)->previewRecentPerformance(544, [
            'matchround_id' => $selectedId,
            'lookback_rounds' => 1,
            'decay_factor' => 0.7,
            'max_price_adjustment' => 2.0,
        ]);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $player = $result['preview']['players'][0];
        // recent = -1, adj = -2, raw price = 1.5-2 = -0.5 → floor 1.0
        $this->assertSame(-1.0, $player['recent_performance']);
        $this->assertSame(-2.0, $player['price_adjustment']);
        $this->assertSame(1.0, $player['player_price']);
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedLeagueWithRounds(int $count): array
    {
        $league = League::query()->create(['league_title' => 'Testliga']);
        $leagueId = (int) $league->league_id;
        Team::query()->create([
            'team_name' => 'Österreich',
            'team_nationality' => 'AUT',
            'team_status' => 1,
            'team_num_players' => 0,
            'team_foreign_id' => '',
        ]);

        $ids = [];
        for ($i = 1; $i <= $count; $i++) {
            $round = Matchround::query()->create([
                'matchround_league_id' => $leagueId,
                'matchround_title' => 'R'.$i,
                'matchround_startdate' => sprintf('2026-0%d-01 12:00:00', $i),
            ]);
            $ids[] = (int) $round->matchround_id;
        }

        return [$leagueId, ...$ids];
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int}
     */
    private function seedThreeRounds(): array
    {
        return $this->seedLeagueWithRounds(3);
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int, 4: int}
     */
    private function seedFourRounds(): array
    {
        return $this->seedLeagueWithRounds(4);
    }

    private function addSquadPlayer(int $leagueId, int $teamId, string $fname, string $lname, string $pos): int
    {
        $player = Player::query()->create([
            'player_fname' => $fname,
            'player_lname' => $lname,
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_foreign_id' => '',
            'player_status_description' => '',
        ]);
        $pt = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => $pos,
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        return (int) $pt->playerteam_id;
    }

    private function addMatch(int $matchroundId, int $homeTeamId, int $guestTeamId): int
    {
        return (int) MatchGame::query()->insertGetId([
            'match_round' => $matchroundId,
            'match_hometeam_id' => $homeTeamId,
            'match_guestteam_id' => $guestTeamId,
            'match_date' => '2026-08-01',
            'match_status' => '',
            'match_minutes' => 90,
        ], 'match_id');
    }

    private function setTeamprice(int $teamId, int $matchroundId, float $price): void
    {
        Teamprice::query()->insert([
            'teamprice_team_id' => $teamId,
            'teamprice_matchround_id' => $matchroundId,
            'teamprice_price' => $price,
        ]);
    }

    private function stats(int $playerteamId, int $matchroundId, int $minutes, ?float $roundPerformance): void
    {
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => $playerteamId,
            'playerstats_matchround_id' => $matchroundId,
            'playerstats_match_id' => 1,
            'playerstats_minutes' => $minutes,
            'playerstats_score' => 0,
            'playerstats_round_performance' => $roundPerformance,
        ]);
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

        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
            $table->double('playerprice_player_power')->default(0);
            $table->double('playerprice_av_power')->default(0);
            $table->double('playerprice_recent_performance')->nullable();
        });
    }
}
