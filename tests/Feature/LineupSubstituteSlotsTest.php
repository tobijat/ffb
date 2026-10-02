<?php

namespace Tests\Feature;

use App\Models\Userteam;
use App\Services\LineupService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LineupSubstituteSlotsTest extends TestCase
{
    private int $leagueId = 1;

    private int $roundId = 10;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-09-16 12:00:00'));
        $this->createSchema();
        $this->seedLeagueRound();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        foreach ([
            'ffb_userteam_substitute_slot',
            'ffb_userteam_slot',
            'ffb_userteam',
            'ffb_userscore',
            'ffb_teamprice',
            'ffb_playerprice',
            'ffb_playerstats',
            'ffb_playerteam',
            'ffb_player',
            'ffb_team',
            'ffb_matchround_options',
            'ffb_matchround',
            'ffb_league_options',
            'ffb_league',
            'web_user_details',
            'web_user',
        ] as $table) {
            Schema::dropIfExists($table);
        }
        parent::tearDown();
    }

    #[Test]
    public function save_persists_substitutes_with_null_replaces_and_includes_prices(): void
    {
        [$starterIds, $subIds] = $this->seedPlayersWithBench(minBench: 1, maxBench: 2, maxPerTeam: 4);

        $result = app(LineupService::class)->saveForRound(544, $this->roundId, $starterIds, $subIds);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $userteamId = (int) $result['data']['userteam']['userteam_id'];

        $this->assertSame(11, DB::table('ffb_userteam_slot')->where('userteam_slot_userteam_id', $userteamId)->count());
        $this->assertSame(2, DB::table('ffb_userteam_substitute_slot')->where('substitute_slot_userteam_id', $userteamId)->count());
        $this->assertSame($subIds, array_map(
            'intval',
            DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_userteam_id', $userteamId)
                ->orderBy('substitute_slot_slot')
                ->pluck('substitute_slot_playerteam_id')
                ->all()
        ));
        $this->assertTrue(
            DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_userteam_id', $userteamId)
                ->whereNotNull('substitute_slot_replaces_playerteam_id')
                ->doesntExist()
        );

        $loaded = app(LineupService::class)->getForRound(544, $this->roundId);
        $this->assertCount(11, $loaded['players']);
        $this->assertCount(2, $loaded['substitutes']);
        $this->assertSame($subIds[0], $loaded['substitutes'][0]['playerteam_id']);
        $this->assertSame(13.0, (float) $loaded['userteam']['userteam_price']);
    }

    #[Test]
    public function save_requires_min_bench_and_rejects_over_max_bench(): void
    {
        [$starterIds, $subIds] = $this->seedPlayersWithBench(minBench: 2, maxBench: 2, maxPerTeam: 4);

        $tooFew = app(LineupService::class)->saveForRound(544, $this->roundId, $starterIds, [$subIds[0]]);
        $this->assertFalse($tooFew['ok']);
        $this->assertStringContainsString('substitutes are required', $tooFew['error'] ?? '');

        $ok = app(LineupService::class)->saveForRound(544, $this->roundId, $starterIds, $subIds);
        $this->assertTrue($ok['ok'], $ok['error'] ?? '');
    }

    #[Test]
    public function team_limit_applies_across_starters_and_substitutes(): void
    {
        [$starterIds, $subIds] = $this->seedPlayersWithBench(minBench: 1, maxBench: 2, maxPerTeam: 2, forceSameTeamForSubs: true);

        $result = app(LineupService::class)->saveForRound(544, $this->roundId, $starterIds, $subIds);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('same team', $result['error'] ?? '');
    }

    #[Test]
    public function without_benchmode_substitutes_are_ignored(): void
    {
        DB::table('ffb_league_options')->where('options_league_id', $this->leagueId)->update([
            'options_league_benchmode' => null,
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 0,
        ]);

        [$starterIds, $subIds] = $this->seedPlayersWithBench(minBench: 0, maxBench: 0, maxPerTeam: 4, updateOptions: false);

        $result = app(LineupService::class)->saveForRound(544, $this->roundId, $starterIds, $subIds);

        $this->assertTrue($result['ok'], $result['error'] ?? '');
        $userteamId = (int) $result['data']['userteam']['userteam_id'];
        $this->assertSame(0, DB::table('ffb_userteam_substitute_slot')->where('substitute_slot_userteam_id', $userteamId)->count());
        $this->assertSame([], $result['data']['substitutes']);
    }

    #[Test]
    public function sync_substitute_slots_and_containing_query_include_bench(): void
    {
        $userteam = new Userteam;
        $userteam->userteam_id = 99;
        $userteam->userteam_user_id = 544;
        $userteam->userteam_matchround_id = $this->roundId;
        $userteam->userteam_price = 0;
        $userteam->save();
        $userteam->syncSubstituteSlots([501, 502]);

        $this->assertSame([501, 502], $userteam->fresh()->substitutePlayerteamIdsInSlotOrder());
        $this->assertTrue(Userteam::queryContainingAnyPlayerteam([502])->exists());
        $this->assertContains(501, Userteam::playerteamIdsUsedInLineups());
    }

    /**
     * @return array{0: list<int>, 1: list<int>}
     */
    private function seedPlayersWithBench(
        int $minBench,
        int $maxBench,
        int $maxPerTeam,
        bool $forceSameTeamForSubs = false,
        bool $updateOptions = true,
    ): array {
        if ($updateOptions) {
            DB::table('ffb_league_options')->where('options_league_id', $this->leagueId)->update([
                'options_league_benchmode' => 'cover',
                'options_lineup_min_bench' => $minBench,
                'options_lineup_max_bench' => $maxBench,
                'options_lineup_max_players_team' => $maxPerTeam,
            ]);
        }

        $positions = ['g', 'd', 'd', 'd', 'm', 'm', 'm', 'm', 's', 's', 's'];
        $starterIds = [];
        foreach ($positions as $i => $pos) {
            $teamId = 100 + intdiv($i, 2); // spread across teams
            $ptId = 200 + $i;
            $this->insertPlayer($ptId, $i + 1, $teamId, $pos, 1.0);
            $starterIds[] = $ptId;
        }

        $subIds = [];
        for ($i = 0; $i < max(2, $maxBench); $i++) {
            $ptId = 500 + $i;
            $teamId = $forceSameTeamForSubs ? 100 : (200 + $i);
            $this->insertPlayer($ptId, 100 + $i, $teamId, 'm', 1.0);
            $subIds[] = $ptId;
        }

        return [$starterIds, array_slice($subIds, 0, max(1, $maxBench))];
    }

    private function insertPlayer(int $ptId, int $playerId, int $teamId, string $position, float $price): void
    {
        if (! DB::table('ffb_team')->where('team_id', $teamId)->exists()) {
            DB::table('ffb_team')->insert([
                'team_id' => $teamId,
                'team_name' => 'Team '.$teamId,
                'team_status' => 1,
                'team_num_players' => 0,
                'team_foreign_id' => '',
                'team_nationality' => 'aut',
            ]);
        }

        if (! DB::table('ffb_player')->where('player_id', $playerId)->exists()) {
            DB::table('ffb_player')->insert([
                'player_id' => $playerId,
                'player_fname' => 'P'.$playerId,
                'player_lname' => 'L'.$playerId,
                'player_status' => 1,
                'player_foreign_id' => '',
                'player_nationality' => 'aut',
                'player_status_description' => '',
            ]);
        }

        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => $ptId,
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $this->leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => $position,
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => '2020-01-01 00:00:00',
        ]);

        DB::table('ffb_playerprice')->insert([
            'playerprice_id' => $ptId,
            'playerprice_playerteam_id' => $ptId,
            'playerprice_matchround_id' => $this->roundId,
            'playerprice_price' => $price,
            'playerprice_powers' => 0,
        ]);
    }

    private function seedLeagueRound(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => $this->leagueId,
            'league_title' => 'Testliga',
            'league_visible' => 1,
            'league_archive' => 0,
        ]);
        DB::table('ffb_league_options')->insert([
            'options_id' => 1,
            'options_league_id' => $this->leagueId,
            'options_lineup_max_players' => 11,
            'options_lineup_max_players_team' => 4,
            'options_lineup_max_credits' => 100,
            'options_lineup_min_g' => 1,
            'options_lineup_max_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_max_d' => 5,
            'options_lineup_min_m' => 3,
            'options_lineup_max_m' => 5,
            'options_lineup_min_s' => 1,
            'options_lineup_max_s' => 3,
            'options_lineup_min_bench' => 1,
            'options_lineup_max_bench' => 2,
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_league_benchmode' => 'cover',
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => $this->roundId,
            'matchround_league_id' => $this->leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => now()->addYear()->format('Y-m-d H:i:s'),
            'matchround_enddate' => now()->addYear()->addWeek()->format('Y-m-d H:i:s'),
            'matchround_status' => 1,
        ]);
        DB::table('web_user')->insert([
            'user_id' => 544,
            'user_nickname' => 'tester',
            'user_status' => 'active',
        ]);
        DB::table('web_user_details')->insert([
            'user_id' => 544,
            'user_details_ffb_selected_league' => $this->leagueId,
        ]);
    }

    private function createSchema(): void
    {
        Schema::create('web_user', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('user_nickname')->default('');
            $table->string('user_status')->default('active');
        });
        Schema::create('web_user_details', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_details_ffb_selected_league')->default(0);
        });
        Schema::create('ffb_league', function (Blueprint $table) {
            $table->integer('league_id')->primary();
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
        });
        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->integer('options_id')->primary();
            $table->integer('options_league_id');
            $table->integer('options_lineup_max_players')->default(11);
            $table->integer('options_lineup_max_players_team')->default(3);
            $table->integer('options_lineup_max_credits')->default(100);
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
            $table->string('options_league_pricemode')->default('dynamic');
            $table->string('options_league_pointsmode')->default('new');
            $table->string('options_league_benchmode')->nullable();
        });
        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->integer('matchround_id')->primary();
            $table->integer('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->tinyInteger('matchround_status')->default(1);
        });
        Schema::create('ffb_matchround_options', function (Blueprint $table) {
            $table->increments('matchround_options_id');
            $table->unsignedInteger('matchround_options_matchround_id');
            $table->integer('matchround_options_lineup_max_players')->default(11);
            $table->double('matchround_options_lineup_max_credits')->default(100);
            $table->integer('matchround_options_lineup_max_players_team')->default(3);
            $table->integer('matchround_options_lineup_min_g')->default(1);
            $table->integer('matchround_options_lineup_min_d')->default(3);
            $table->integer('matchround_options_lineup_min_m')->default(3);
            $table->integer('matchround_options_lineup_min_s')->default(1);
            $table->integer('matchround_options_lineup_max_g')->default(1);
            $table->integer('matchround_options_lineup_max_d')->default(5);
            $table->integer('matchround_options_lineup_max_m')->default(5);
            $table->integer('matchround_options_lineup_max_s')->default(3);
            $table->integer('matchround_options_lineup_min_bench')->default(0);
            $table->integer('matchround_options_lineup_max_bench')->default(0);
        });
        Schema::create('ffb_team', function (Blueprint $table) {
            $table->integer('team_id')->primary();
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->string('team_foreign_id')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });
        Schema::create('ffb_player', function (Blueprint $table) {
            $table->integer('player_id')->primary();
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->string('player_foreign_id')->default('');
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
        });
        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->integer('playerteam_id')->primary();
            $table->integer('playerteam_player_id');
            $table->integer('playerteam_team_id');
            $table->integer('playerteam_league_id')->nullable();
            $table->string('playerteam_player_picture')->nullable();
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position')->default('m');
            $table->string('playerteam_player_note')->default('');
            $table->timestamp('playerteam_date_transfer')->nullable();
        });
        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->integer('playerprice_id')->primary();
            $table->integer('playerprice_playerteam_id');
            $table->integer('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
            $table->double('playerprice_powers')->default(0);
        });
        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->integer('teamprice_team_id');
            $table->integer('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);
        });
        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->integer('playerstats_playerteam_id');
            $table->integer('playerstats_matchround_id');
            $table->integer('playerstats_match_id')->nullable();
            $table->integer('playerstats_score')->default(0);
            $table->string('playerstats_cards')->default('n');
        });
        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->increments('userteam_id');
            $table->integer('userteam_user_id')->default(0);
            $table->integer('userteam_matchround_id')->nullable();
            $table->double('userteam_price')->default(0);
            $table->double('userteam_score')->nullable();
            $table->double('userteam_lc_points')->nullable();
            $table->string('userteam_date')->nullable();
        });
        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
            $table->unique(['userteam_slot_userteam_id', 'userteam_slot_slot']);
        });
        Schema::create('ffb_userteam_substitute_slot', function (Blueprint $table) {
            $table->increments('substitute_slot_id');
            $table->unsignedInteger('substitute_slot_userteam_id');
            $table->unsignedTinyInteger('substitute_slot_slot');
            $table->unsignedInteger('substitute_slot_playerteam_id');
            $table->unsignedInteger('substitute_slot_replaces_playerteam_id')->nullable();
            $table->unique(['substitute_slot_userteam_id', 'substitute_slot_slot']);
        });
        Schema::create('ffb_userscore', function (Blueprint $table) {
            $table->increments('userscore_id');
            $table->integer('userscore_user_id');
            $table->integer('userscore_league_id');
            $table->double('userscore_total')->default(0);
            $table->double('userscore_lc_points')->default(0);
        });
    }
}
