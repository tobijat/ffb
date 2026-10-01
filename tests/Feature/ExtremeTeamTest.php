<?php

namespace Tests\Feature;

use App\Models\Extremeteam;
use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
use App\Services\ExtremeTeamService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExtremeTeamTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function compute_and_store_persists_eleven_slots(): void
    {
        $roundId = $this->seedRoundWithPlayers();

        $result = app(ExtremeTeamService::class)->computeAndStore($roundId, 'top');

        $this->assertTrue($result['ok']);
        $this->assertSame('stored', $result['status']);

        $team = Extremeteam::query()
            ->where('extremeteam_matchround_id', $roundId)
            ->where('extremeteam_top_or_flop', 'top')
            ->first();

        $this->assertNotNull($team);
        $this->assertCount(11, $team->playerteamIdsInSlotOrder());
        $this->assertGreaterThan(0, (int) $team->extremeteam_score);
    }

    #[Test]
    public function compute_and_store_skips_when_no_playerprices(): void
    {
        $league = League::query()->create([
            'league_title' => 'Thin',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
            'options_league_pricemode' => 'dynamic',
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDays(10),
            'matchround_enddate' => now()->subDays(3),
            'matchround_status' => 1,
        ]);
        $roundId = (int) $round->matchround_id;
        $club = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Club',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $player = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'A',
            'player_lname' => 'B',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $pt = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $club->team_id,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01',
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $pt->playerteam_id,
            'playerstats_matchround_id' => $roundId,
            'playerstats_match_id' => 0,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_assists' => 0,
            'playerstats_score' => 5,
            'playerstats_cards' => 'n',
        ]);

        $result = app(ExtremeTeamService::class)->computeAndStore($roundId, 'top');

        $this->assertTrue($result['ok']);
        $this->assertSame('skipped', $result['status']);
        $this->assertStringContainsString('Keine Spieler- oder Teampreise', (string) $result['message']);
        $this->assertSame(0, Extremeteam::query()->count());
    }

    #[Test]
    public function compute_and_store_for_matchrounds_includes_skip_reason_in_details(): void
    {
        $league = League::query()->create([
            'league_title' => 'Thin',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
            'options_league_pricemode' => 'dynamic',
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDays(10),
            'matchround_enddate' => now()->subDays(3),
            'matchround_status' => 1,
        ]);
        $roundId = (int) $round->matchround_id;
        $club = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Club',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $player = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'A',
            'player_lname' => 'B',
            'player_nationality' => 'AUT',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $pt = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $club->team_id,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01',
        ]);
        Playerstats::query()->forceCreate([
            'playerstats_playerteam_id' => (int) $pt->playerteam_id,
            'playerstats_matchround_id' => $roundId,
            'playerstats_match_id' => 0,
            'playerstats_minutes' => 90,
            'playerstats_goals' => 0,
            'playerstats_assists' => 0,
            'playerstats_score' => 5,
            'playerstats_cards' => 'n',
        ]);

        $result = app(ExtremeTeamService::class)->computeAndStoreForMatchrounds(
            [$roundId],
            top: true,
            flop: false,
        );

        $this->assertSame(0, $result['stored']);
        $this->assertSame(1, $result['skipped']);
        $this->assertNotEmpty($result['details']);
        $this->assertStringContainsString('übersprungen', $result['details'][0]);
        $this->assertStringContainsString('Keine Spieler- oder Teampreise', $result['details'][0]);
    }

    #[Test]
    public function compute_falls_back_to_teamprice_when_playerprice_missing(): void
    {
        $league = League::query()->create([
            'league_title' => 'Teamprice Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
            'options_league_pricemode' => 'dynamic',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 11,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 4,
            'options_lineup_max_d' => 4,
            'options_lineup_min_m' => 4,
            'options_lineup_max_m' => 4,
            'options_lineup_min_s' => 2,
            'options_lineup_max_s' => 2,
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDays(10),
            'matchround_enddate' => now()->subDays(2),
            'matchround_status' => 1,
        ]);
        $roundId = (int) $round->matchround_id;
        $club = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Only',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        Teamprice::query()->create([
            'teamprice_team_id' => (int) $club->team_id,
            'teamprice_matchround_id' => $roundId,
            'teamprice_price' => 4.5,
        ]);

        $positions = array_merge(
            ['g'],
            array_fill(0, 4, 'd'),
            array_fill(0, 4, 'm'),
            array_fill(0, 2, 's'),
        );
        foreach ($positions as $index => $pos) {
            $player = Player::query()->create([
                'player_foreign_id' => '',
                'player_fname' => 'F'.$index,
                'player_lname' => 'L'.$index,
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $club->team_id,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => $pos,
                'playerteam_date_transfer' => '2008-01-01',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $roundId,
                'playerstats_match_id' => 0,
                'playerstats_minutes' => 90,
                'playerstats_goals' => 0,
                'playerstats_assists' => 0,
                'playerstats_score' => 8,
                'playerstats_cards' => 'n',
            ]);
        }

        $result = app(ExtremeTeamService::class)->computeAndStore($roundId, 'top');

        $this->assertTrue($result['ok']);
        $this->assertSame('stored', $result['status']);

        $team = Extremeteam::query()->first();
        $this->assertNotNull($team);
        $this->assertSame(49.5, (float) $team->extremeteam_price);
        $this->assertSame([], app(ExtremeTeamService::class)->complianceIssues($team));
    }

    #[Test]
    public function load_for_api_returns_unavailable_when_missing(): void
    {
        $league = League::query()->create([
            'league_title' => 'L',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDays(10),
            'matchround_enddate' => now()->subDays(3),
            'matchround_status' => 1,
        ]);

        $result = app(ExtremeTeamService::class)->loadForApi((int) $round->matchround_id, 'flop');

        $this->assertTrue($result['ok']);
        $this->assertFalse($result['data']['available']);
        $this->assertSame('flop', $result['data']['type']);
        $this->assertSame([], $result['data']['players']);
    }

    #[Test]
    public function load_for_api_returns_stored_team(): void
    {
        $roundId = $this->seedRoundWithPlayers();
        app(ExtremeTeamService::class)->computeAndStore($roundId, 'top');

        $result = app(ExtremeTeamService::class)->loadForApi($roundId, 'top');

        $this->assertTrue($result['ok']);
        $this->assertTrue($result['data']['available']);
        $this->assertCount(11, $result['data']['players']);
        $this->assertArrayHasKey('userteam_score', $result['data']['userteam']);
    }

    #[Test]
    public function upsert_replaces_slots(): void
    {
        $roundId = $this->seedRoundWithPlayers();
        $service = app(ExtremeTeamService::class);

        $service->computeAndStore($roundId, 'top');
        $firstIds = Extremeteam::query()->first()->playerteamIdsInSlotOrder();

        $service->computeAndStore($roundId, 'top');
        $team = Extremeteam::query()->first();

        $this->assertSame(1, Extremeteam::query()->count());
        $this->assertCount(11, $team->playerteamIdsInSlotOrder());
        $this->assertSame($firstIds, $team->playerteamIdsInSlotOrder());
        $this->assertSame(11, $team->slots()->count());
    }

    #[Test]
    public function backfill_visible_leagues_processes_past_rounds(): void
    {
        $this->seedRoundWithPlayers(visible: true, ended: true);
        $this->seedRoundWithPlayers(visible: false, ended: true, titleSuffix: ' hidden');

        $result = app(ExtremeTeamService::class)->backfillVisibleLeagues();

        $this->assertSame(1, $result['leagues']);
        $this->assertSame(1, $result['matchrounds']);
        $this->assertSame(2, $result['stored']);
        $this->assertSame(2, Extremeteam::query()->count());
    }

    private function seedRoundWithPlayers(bool $visible = true, bool $ended = true, string $titleSuffix = ''): int
    {
        $league = League::query()->create([
            'league_title' => 'Testliga'.$titleSuffix,
            'league_visible' => $visible ? 1 : 0,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
            'options_league_pricemode' => 'dynamic',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_max_d' => 5,
            'options_lineup_min_m' => 3,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 1,
            'options_lineup_max_s' => 3,
        ]);

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1'.$titleSuffix,
            'matchround_startdate' => now()->subDays(14),
            'matchround_enddate' => $ended ? now()->subDays(2) : now()->addDays(5),
            'matchround_status' => 1,
        ]);
        $roundId = (int) $round->matchround_id;

        $clubs = [];
        for ($c = 0; $c < 4; $c++) {
            $clubs[] = Team::query()->create([
                'team_foreign_id' => '',
                'team_name' => 'Club'.$c.$titleSuffix,
                'team_nationality' => 'aut',
                'team_num_players' => 0,
                'team_status' => 1,
            ]);
        }

        $positions = array_merge(
            array_fill(0, 2, 'g'),
            array_fill(0, 6, 'd'),
            array_fill(0, 6, 'm'),
            array_fill(0, 4, 's'),
        );

        foreach ($positions as $index => $pos) {
            $club = $clubs[$index % count($clubs)];
            $player = Player::query()->create([
                'player_foreign_id' => '',
                'player_fname' => 'F'.$index,
                'player_lname' => 'L'.$index,
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $club->team_id,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => $pos,
                'playerteam_date_transfer' => '2008-01-01',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $roundId,
                'playerstats_match_id' => 0,
                'playerstats_minutes' => 90,
                'playerstats_goals' => 0,
                'playerstats_assists' => 0,
                'playerstats_score' => 10 - ($index % 5),
                'playerstats_cards' => 'n',
            ]);
            Playerprice::query()->create([
                'playerprice_playerteam_id' => (int) $pt->playerteam_id,
                'playerprice_matchround_id' => $roundId,
                'playerprice_price' => 5.0 + ($index % 3),
                'playerprice_player_power' => 1,
                'playerprice_av_power' => 1,
            ]);
        }

        return $roundId;
    }

    #[Test]
    public function compute_top_team_stays_within_credit_limit_and_prefers_cheaper_tie(): void
    {
        $league = League::query()->create([
            'league_title' => 'Budget',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
            'options_league_pricemode' => 'dynamic',
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 55,
            'options_lineup_max_players_team' => 11,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 4,
            'options_lineup_max_d' => 4,
            'options_lineup_min_m' => 4,
            'options_lineup_max_m' => 4,
            'options_lineup_min_s' => 2,
            'options_lineup_max_s' => 2,
        ]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->subDays(10),
            'matchround_enddate' => now()->subDays(2),
            'matchround_status' => 1,
        ]);
        $roundId = (int) $round->matchround_id;
        $club = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Only',
            'team_nationality' => 'aut',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        $spec = [
            ['g', 10, 5.0],
            ['d', 10, 5.0], ['d', 10, 5.0], ['d', 10, 5.0], ['d', 10, 5.0],
            ['m', 10, 5.0], ['m', 10, 5.0], ['m', 10, 5.0], ['m', 10, 5.0],
            ['s', 10, 5.0], ['s', 10, 5.0],
            ['s', 10, 20.0],
        ];
        foreach ($spec as $index => [$pos, $score, $price]) {
            $player = Player::query()->create([
                'player_foreign_id' => '',
                'player_fname' => 'F'.$index,
                'player_lname' => 'L'.$index,
                'player_nationality' => 'AUT',
                'player_status' => 1,
                'player_status_description' => '',
            ]);
            $pt = Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => (int) $club->team_id,
                'playerteam_player_picture' => '',
                'playerteam_status' => 1,
                'playerteam_player_position' => $pos,
                'playerteam_date_transfer' => '2008-01-01',
            ]);
            Playerstats::query()->forceCreate([
                'playerstats_playerteam_id' => (int) $pt->playerteam_id,
                'playerstats_matchround_id' => $roundId,
                'playerstats_match_id' => 0,
                'playerstats_minutes' => 90,
                'playerstats_goals' => 0,
                'playerstats_assists' => 0,
                'playerstats_score' => $score,
                'playerstats_cards' => 'n',
            ]);
            Playerprice::query()->create([
                'playerprice_playerteam_id' => (int) $pt->playerteam_id,
                'playerprice_matchround_id' => $roundId,
                'playerprice_price' => $price,
                'playerprice_player_power' => 1,
                'playerprice_av_power' => 1,
            ]);
        }

        $result = app(ExtremeTeamService::class)->computeAndStore($roundId, 'top');

        $this->assertTrue($result['ok']);
        $this->assertSame('stored', $result['status']);
        $team = Extremeteam::query()->first();
        $this->assertNotNull($team);
        $this->assertLessThanOrEqual(55.0, (float) $team->extremeteam_price);
        $this->assertSame([], app(ExtremeTeamService::class)->complianceIssues($team));
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_teamprice');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_matchround_options');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->string('league_symbol')->default('');
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->unsignedInteger('options_league_id');
            $table->string('options_league_pricemode')->default('static');
            $table->string('options_league_pointsmode')->default('new');
            $table->integer('options_lineup_max_players')->default(11);
            $table->double('options_lineup_max_credits')->default(100);
            $table->integer('options_lineup_max_players_team')->default(3);
            $table->integer('options_lineup_min_g')->default(1);
            $table->integer('options_lineup_max_g')->default(1);
            $table->integer('options_lineup_min_d')->default(3);
            $table->integer('options_lineup_max_d')->default(5);
            $table->integer('options_lineup_min_m')->default(3);
            $table->integer('options_lineup_max_m')->default(5);
            $table->integer('options_lineup_min_s')->default(1);
            $table->integer('options_lineup_max_s')->default(3);
            $table->integer('options_lineup_min_bench')->default(0);
            $table->integer('options_lineup_max_bench')->default(0);
        });

        Schema::create('ffb_matchround_options', function (Blueprint $table) {
            $table->increments('matchround_options_id');
            $table->unsignedInteger('matchround_options_matchround_id');
            $table->integer('matchround_options_lineup_max_players')->default(11);
            $table->double('matchround_options_lineup_max_credits')->default(100);
            $table->integer('matchround_options_lineup_max_players_team')->default(3);
            $table->integer('matchround_options_lineup_min_g')->default(1);
            $table->integer('matchround_options_lineup_max_g')->default(1);
            $table->integer('matchround_options_lineup_min_d')->default(3);
            $table->integer('matchround_options_lineup_max_d')->default(5);
            $table->integer('matchround_options_lineup_min_m')->default(3);
            $table->integer('matchround_options_lineup_max_m')->default(5);
            $table->integer('matchround_options_lineup_min_s')->default(1);
            $table->integer('matchround_options_lineup_max_s')->default(3);
            $table->integer('matchround_options_lineup_min_bench')->default(0);
            $table->integer('matchround_options_lineup_max_bench')->default(0);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->tinyInteger('matchround_status')->default(1);
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
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position')->default('m');
            $table->string('playerteam_date_transfer')->default('2008-01-01');
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->integer('playerstats_match_id')->default(0);
            $table->integer('playerstats_minutes')->default(0);
            $table->integer('playerstats_goals')->default(0);
            $table->integer('playerstats_assists')->default(0);
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
        });

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
        });

        Schema::create('ffb_extremeteam', function (Blueprint $table) {
            $table->increments('extremeteam_id');
            $table->string('extremeteam_top_or_flop', 8);
            $table->decimal('extremeteam_price', 9, 2)->default(0);
            $table->unsignedInteger('extremeteam_matchround_id');
            $table->integer('extremeteam_score')->default(-1);
            $table->unique(['extremeteam_matchround_id', 'extremeteam_top_or_flop']);
        });

        Schema::create('ffb_extremeteam_slot', function (Blueprint $table) {
            $table->increments('extremeteam_slot_id');
            $table->unsignedInteger('extremeteam_slot_extremeteam_id');
            $table->unsignedTinyInteger('extremeteam_slot_slot');
            $table->unsignedInteger('extremeteam_slot_playerteam_id');
            $table->unique(['extremeteam_slot_extremeteam_id', 'extremeteam_slot_slot']);
        });
    }
}
