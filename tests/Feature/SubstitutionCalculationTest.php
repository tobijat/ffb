<?php

namespace Tests\Feature;

use App\Models\Userteam;
use App\Services\SubstitutionCalculationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SubstitutionCalculationTest extends TestCase
{
    private int $leagueId = 1;

    private int $roundId = 10;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        $this->seedLeagueRound('cover');
    }

    protected function tearDown(): void
    {
        foreach ([
            'ffb_userteam_substitute_slot',
            'ffb_userteam_slot',
            'ffb_userteam',
            'ffb_playerstats',
            'ffb_playerteam',
            'ffb_player',
            'ffb_team',
            'ffb_matchround_options',
            'ffb_matchround',
            'ffb_league_options',
            'ffb_league',
            'web_user',
        ] as $table) {
            Schema::dropIfExists($table);
        }
        parent::tearDown();
    }

    #[Test]
    public function cover_replaces_same_position_no_play_starter_with_highest_scoring_bench(): void
    {
        [$starters, $subs] = $this->seedLineup();
        // One defender did not play; midfielder sub played with high score.
        $this->markPlayed(array_diff($starters, [$starters[1]]), score: 5);
        $this->markPlayed([$subs[0]], score: 9); // m
        $this->markPlayed([$subs[1]], score: 3); // d — lower than mid but same pos as out

        $preview = app(SubstitutionCalculationService::class)->previewForRound($this->leagueId, $this->roundId);
        $this->assertTrue($preview['ok'], implode('; ', $preview['errors'] ?? []));

        $subsRows = $preview['preview']['rows'][0]['substitutions'];
        $this->assertCount(1, $subsRows);
        $this->assertSame($starters[1], $subsRows[0]['out_playerteam_id']);
        $this->assertSame($subs[1], $subsRows[0]['substitute_playerteam_id']);
        $this->assertSame('d', $subsRows[0]['substitute_position']);
    }

    #[Test]
    public function cover_allows_cross_position_when_mins_maxs_allow(): void
    {
        [$starters, $subs] = $this->seedLineup();
        // Starter striker (index 8 in 1g+3d+4m+3s) did not play; only mid bench played.
        $outStriker = $starters[8];
        $this->markPlayed(array_diff($starters, [$outStriker]), score: 4);
        $this->markPlayed([$subs[0]], score: 8); // m

        $preview = app(SubstitutionCalculationService::class)->previewForRound($this->leagueId, $this->roundId);
        $this->assertTrue($preview['ok']);
        $subsRows = $preview['preview']['rows'][0]['substitutions'];
        $this->assertCount(1, $subsRows);
        $this->assertSame($outStriker, $subsRows[0]['out_playerteam_id']);
        $this->assertSame($subs[0], $subsRows[0]['substitute_playerteam_id']);
        $this->assertSame('s', $subsRows[0]['out_position']);
        $this->assertSame('m', $subsRows[0]['substitute_position']);
    }

    #[Test]
    public function cover_skips_when_all_starters_played(): void
    {
        [$starters, $subs] = $this->seedLineup();
        $this->markPlayed($starters, score: 2);
        $this->markPlayed($subs, score: 10);

        $preview = app(SubstitutionCalculationService::class)->previewForRound($this->leagueId, $this->roundId);
        $this->assertTrue($preview['ok']);
        $this->assertSame([], $preview['preview']['rows'][0]['substitutions']);
    }

    #[Test]
    public function bestof_replaces_worse_same_position_starters_only(): void
    {
        DB::table('ffb_league_options')->where('options_league_id', $this->leagueId)->update([
            'options_league_benchmode' => 'bestof',
        ]);

        [$starters, $subs] = $this->seedLineup();
        // All starters played; one mid scored 1, bench mid scored 10.
        foreach ($starters as $i => $ptId) {
            $this->markPlayed([$ptId], score: $i === 4 ? 1 : 6);
        }
        $this->markPlayed([$subs[0]], score: 10); // m
        $this->markPlayed([$subs[1]], score: 5); // d — not better than starter d (6)

        $preview = app(SubstitutionCalculationService::class)->previewForRound($this->leagueId, $this->roundId);
        $this->assertTrue($preview['ok']);
        $subsRows = $preview['preview']['rows'][0]['substitutions'];
        $this->assertCount(1, $subsRows);
        $this->assertSame($starters[4], $subsRows[0]['out_playerteam_id']);
        $this->assertSame($subs[0], $subsRows[0]['substitute_playerteam_id']);
        $this->assertSame('m', $subsRows[0]['out_position']);
        $this->assertSame('m', $subsRows[0]['substitute_position']);
    }

    #[Test]
    public function save_persists_replaces_playerteam_ids(): void
    {
        [$starters, $subs] = $this->seedLineup();
        $this->markPlayed(array_diff($starters, [$starters[1]]), score: 5);
        $this->markPlayed([$subs[1]], score: 7);

        $service = app(SubstitutionCalculationService::class);
        $preview = $service->previewForRound($this->leagueId, $this->roundId);
        $this->assertTrue($preview['ok']);
        $service->savePreview($preview['preview']);

        $this->assertSame(
            $starters[1],
            (int) DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_playerteam_id', $subs[1])
                ->value('substitute_slot_replaces_playerteam_id')
        );
        $this->assertNull(
            DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_playerteam_id', $subs[0])
                ->value('substitute_slot_replaces_playerteam_id')
        );
    }

    /**
     * @return array{0: list<int>, 1: list<int>}
     */
    private function seedLineup(): array
    {
        $positions = ['g', 'd', 'd', 'd', 'm', 'm', 'm', 'm', 's', 's', 's'];
        $starterIds = [];
        foreach ($positions as $i => $pos) {
            $ptId = 200 + $i;
            $this->insertPlayer($ptId, $i + 1, 100 + $i, $pos);
            $starterIds[] = $ptId;
        }

        $subIds = [];
        $this->insertPlayer(500, 100, 300, 'm');
        $this->insertPlayer(501, 101, 301, 'd');
        $subIds = [500, 501];

        $userteam = new Userteam;
        $userteam->userteam_user_id = 544;
        $userteam->userteam_matchround_id = $this->roundId;
        $userteam->userteam_price = 50;
        $userteam->save();
        $userteam->syncSlots($starterIds);
        $userteam->syncSubstituteSlots($subIds);

        return [$starterIds, $subIds];
    }

    /**
     * @param  list<int>  $playerteamIds
     */
    private function markPlayed(array $playerteamIds, int $score): void
    {
        foreach ($playerteamIds as $ptId) {
            DB::table('ffb_playerstats')->insert([
                'playerstats_playerteam_id' => $ptId,
                'playerstats_matchround_id' => $this->roundId,
                'playerstats_match_id' => 1,
                'playerstats_score' => $score,
                'playerstats_cards' => 'n',
            ]);
        }
    }

    private function insertPlayer(int $ptId, int $playerId, int $teamId, string $position): void
    {
        if (! DB::table('ffb_team')->where('team_id', $teamId)->exists()) {
            DB::table('ffb_team')->insert([
                'team_id' => $teamId,
                'team_name' => 'Team '.$teamId,
                'team_status' => 1,
                'team_nationality' => 'aut',
            ]);
        }
        if (! DB::table('ffb_player')->where('player_id', $playerId)->exists()) {
            DB::table('ffb_player')->insert([
                'player_id' => $playerId,
                'player_fname' => 'P'.$playerId,
                'player_lname' => 'L'.$playerId,
                'player_status' => 1,
                'player_nationality' => 'aut',
            ]);
        }
        DB::table('ffb_playerteam')->insert([
            'playerteam_id' => $ptId,
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $this->leagueId,
            'playerteam_status' => 1,
            'playerteam_player_position' => $position,
            'playerteam_player_note' => '',
        ]);
    }

    private function seedLeagueRound(string $benchmode): void
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
            'options_league_benchmode' => $benchmode,
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => $this->roundId,
            'matchround_league_id' => $this->leagueId,
            'matchround_title' => 'R1',
            'matchround_startdate' => '2026-01-01 00:00:00',
            'matchround_enddate' => '2026-01-02 00:00:00',
            'matchround_status' => 1,
        ]);
        DB::table('web_user')->insert([
            'user_id' => 544,
            'user_nickname' => 'tester',
            'user_status' => 'active',
        ]);
    }

    private function createSchema(): void
    {
        Schema::create('web_user', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('user_nickname')->default('');
            $table->string('user_status')->default('active');
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
        });
        Schema::create('ffb_team', function (Blueprint $table) {
            $table->integer('team_id')->primary();
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->tinyInteger('team_status')->default(1);
        });
        Schema::create('ffb_player', function (Blueprint $table) {
            $table->integer('player_id')->primary();
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->tinyInteger('player_status')->default(1);
        });
        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->integer('playerteam_id')->primary();
            $table->integer('playerteam_player_id');
            $table->integer('playerteam_team_id');
            $table->integer('playerteam_league_id')->nullable();
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position')->default('m');
            $table->string('playerteam_player_note')->default('');
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
        });
        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
        });
        Schema::create('ffb_userteam_substitute_slot', function (Blueprint $table) {
            $table->increments('substitute_slot_id');
            $table->unsignedInteger('substitute_slot_userteam_id');
            $table->unsignedTinyInteger('substitute_slot_slot');
            $table->unsignedInteger('substitute_slot_playerteam_id');
            $table->unsignedInteger('substitute_slot_replaces_playerteam_id')->nullable();
        });
    }
}
