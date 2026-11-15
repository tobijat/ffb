<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Playerteam;
use App\Services\AdminPlayerMergeService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPlayerMergeTest extends TestCase
{
    #[Test]
    public function merge_repoints_disjoint_squads_and_applies_fields(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer([
            'player_fname' => 'Keep',
            'player_lname' => 'Alpha',
            'player_nationality' => 'AUT',
            'player_uefa_id' => '',
            'player_fifa_id' => 'fifa-keep',
        ]);
        $dropId = $this->insertPlayer([
            'player_fname' => 'Drop',
            'player_lname' => 'Beta',
            'player_nationality' => 'GER',
            'player_uefa_id' => 'uefa-drop',
            'player_fifa_id' => '',
        ]);

        $keepPt = $this->insertPlayerteam($keepId, 1, 10);
        $dropPt = $this->insertPlayerteam($dropId, 2, 10);

        $service = new AdminPlayerMergeService;
        $preview = $service->preview($keepId, $dropId, [
            '_player_a_id' => $keepId,
            '_player_b_id' => $dropId,
            'player_fname' => 'a',
            'player_lname' => 'a',
            'player_nationality' => 'b',
            'player_uefa_id' => 'b',
            'player_fifa_id' => 'a',
            'player_status' => 'a',
            'player_status_description' => 'a',
        ]);

        $this->assertTrue($preview['ok']);
        $this->assertTrue($preview['can_execute']);
        $this->assertSame(1, $preview['impact']['playerteams_repointed']);
        $this->assertSame(0, $preview['impact']['playerteams_collapsed']);

        $result = $service->execute($keepId, $dropId, [
            '_player_a_id' => $keepId,
            '_player_b_id' => $dropId,
            'player_fname' => 'a',
            'player_lname' => 'a',
            'player_nationality' => 'b',
            'player_uefa_id' => 'b',
            'player_fifa_id' => 'a',
            'player_status' => 'a',
            'player_status_description' => 'a',
        ], true);

        $this->assertTrue($result['ok']);
        $this->assertNull(Player::query()->find($dropId));
        $keep = Player::query()->find($keepId);
        $this->assertNotNull($keep);
        $this->assertSame('Keep', (string) $keep->player_fname);
        $this->assertSame('GER', (string) $keep->player_nationality);
        $this->assertSame('uefa-drop', (string) $keep->player_uefa_id);
        $this->assertSame('fifa-keep', (string) $keep->player_fifa_id);

        $this->assertSame($keepId, (int) Playerteam::query()->find($keepPt)->playerteam_player_id);
        $this->assertSame($keepId, (int) Playerteam::query()->find($dropPt)->playerteam_player_id);
        $this->assertSame(2, Playerteam::query()->where('playerteam_player_id', $keepId)->count());
    }

    #[Test]
    public function merge_collapses_same_team_league_and_remaps_userteam_slot(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer(['player_fname' => 'A', 'player_lname' => 'One']);
        $dropId = $this->insertPlayer(['player_fname' => 'B', 'player_lname' => 'Two']);
        $keepPt = $this->insertPlayerteam($keepId, 1, 10);
        $dropPt = $this->insertPlayerteam($dropId, 1, 10);

        DB::table('web_user')->insert([
            'user_id' => 1,
            'user_nickname' => 'tester',
        ]);
        DB::table('ffb_userteam')->insert([
            'userteam_id' => 50,
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
            'userteam_price' => 0,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 50,
            'userteam_slot_slot' => 1,
            'userteam_slot_playerteam_id' => $dropPt,
        ]);
        DB::table('ffb_playerstats')->insert([
            'playerstats_id' => 1,
            'playerstats_playerteam_id' => $dropPt,
            'playerstats_matchround_id' => 7,
            'playerstats_score' => 5,
        ]);
        DB::table('ffb_playerprice')->insert([
            'playerprice_id' => 1,
            'playerprice_playerteam_id' => $dropPt,
            'playerprice_matchround_id' => 7,
            'playerprice_price' => 1.5,
        ]);

        $service = new AdminPlayerMergeService;
        $preview = $service->preview($keepId, $dropId, []);
        $this->assertTrue($preview['can_execute']);
        $this->assertSame(1, $preview['impact']['playerteams_collapsed']);
        $this->assertSame(0, $preview['impact']['playerteams_repointed']);
        $this->assertSame(1, $preview['impact']['rows_updated_by_table']['ffb_userteam_slot'] ?? 0);
        $this->assertSame(1, $preview['impact']['affected_users_count']);
        $this->assertSame('tester', $preview['impact']['affected_users'][0]['user_nickname']);
        $this->assertSame('collapse', $preview['impact']['kader_changes'][0]['action']);
        $this->assertArrayHasKey('chosen_asset_key', $preview['impact']['image']);

        $service->execute($keepId, $dropId, [], true);

        $this->assertNull(Player::query()->find($dropId));
        $this->assertNull(Playerteam::query()->find($dropPt));
        $this->assertSame(1, Playerteam::query()->where('playerteam_player_id', $keepId)->count());
        $this->assertSame($keepPt, (int) DB::table('ffb_userteam_slot')->where('userteam_slot_id', 1)->value('userteam_slot_playerteam_id'));
        $this->assertSame($keepPt, (int) DB::table('ffb_playerstats')->where('playerstats_id', 1)->value('playerstats_playerteam_id'));
        $this->assertSame($keepPt, (int) DB::table('ffb_playerprice')->where('playerprice_id', 1)->value('playerprice_playerteam_id'));
    }

    #[Test]
    public function merge_blocks_when_collapse_would_change_userteam_kader_position(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer(['player_fname' => 'A', 'player_lname' => 'One']);
        $dropId = $this->insertPlayer(['player_fname' => 'B', 'player_lname' => 'Two']);
        $keepPt = $this->insertPlayerteam($keepId, 1, 10, 'd');
        $dropPt = $this->insertPlayerteam($dropId, 1, 10, 'm');

        DB::table('web_user')->insert([
            'user_id' => 2,
            'user_nickname' => 'miduser',
        ]);
        DB::table('ffb_userteam')->insert([
            'userteam_id' => 70,
            'userteam_user_id' => 2,
            'userteam_matchround_id' => 1,
            'userteam_price' => 0,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 70,
            'userteam_slot_slot' => 7,
            'userteam_slot_playerteam_id' => $dropPt,
        ]);

        $service = new AdminPlayerMergeService;
        $preview = $service->preview($keepId, $dropId, []);
        $this->assertFalse($preview['can_execute']);
        $this->assertTrue(collect($preview['blockers'])->contains(
            fn (string $msg): bool => str_contains($msg, 'Kader-Position')
        ));

        try {
            $service->execute($keepId, $dropId, [], true);
            $this->fail('Expected InvalidArgumentException');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('Position', $e->getMessage());
        }

        $this->assertNotNull(Player::query()->find($dropId));
        $this->assertSame($dropPt, (int) DB::table('ffb_userteam_slot')
            ->where('userteam_slot_userteam_id', 70)
            ->value('userteam_slot_playerteam_id'));
        $this->assertSame($keepPt, (int) Playerteam::query()->find($keepPt)->playerteam_id);
    }

    #[Test]
    public function merge_applies_chosen_playerteam_fields_and_child_conflicts(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer(['player_fname' => 'A', 'player_lname' => 'One']);
        $dropId = $this->insertPlayer(['player_fname' => 'B', 'player_lname' => 'Two']);
        $keepPt = $this->insertPlayerteam($keepId, 1, 10, 'd');
        $dropPt = $this->insertPlayerteam($dropId, 1, 10, 'm');
        Playerteam::query()->where('playerteam_id', $keepPt)->update([
            'playerteam_status' => 1,
            'playerteam_player_note' => 'keep-note',
        ]);
        Playerteam::query()->where('playerteam_id', $dropPt)->update([
            'playerteam_status' => 0,
            'playerteam_player_note' => 'drop-note',
        ]);

        DB::table('ffb_playerprice')->insert([
            [
                'playerprice_id' => 1,
                'playerprice_playerteam_id' => $keepPt,
                'playerprice_matchround_id' => 3,
                'playerprice_price' => 10,
            ],
            [
                'playerprice_id' => 2,
                'playerprice_playerteam_id' => $dropPt,
                'playerprice_matchround_id' => 3,
                'playerprice_price' => 11,
            ],
        ]);
        DB::table('ffb_playerfid')->insert([
            [
                'playerfid_id' => 1,
                'playerfid_playerteam_id' => $keepPt,
                'playerfid_name_wf' => 'KeepFid',
            ],
            [
                'playerfid_id' => 2,
                'playerfid_playerteam_id' => $dropPt,
                'playerfid_name_wf' => 'DropFid',
            ],
        ]);

        $prepared = (new AdminPlayerMergeService)->prepare($keepId, $dropId);
        $this->assertCount(1, $prepared['collapses_if_a_kept']);
        $collapse = $prepared['collapses_if_a_kept'][0];
        $this->assertSame($keepPt, $collapse['keep_pt']);
        $this->assertSame($dropPt, $collapse['drop_pt']);
        $this->assertNotEmpty($collapse['fields']);
        $this->assertCount(1, $collapse['child_conflicts']['playerprice']);
        $this->assertCount(1, $collapse['child_conflicts']['playerfid']);

        $service = new AdminPlayerMergeService;
        $playerteamMerges = [[
            'keep_pt' => $keepPt,
            'drop_pt' => $dropPt,
            'fields' => [
                'playerteam_player_position' => 'drop',
                'playerteam_status' => 'drop',
                'playerteam_player_note' => 'drop',
            ],
            'children' => [
                'playerprice' => ['3' => 'drop'],
                'playerstats' => [],
                'playerfid' => ['name' => 'drop'],
            ],
        ]];

        $preview = $service->preview($keepId, $dropId, [], $playerteamMerges);
        $this->assertTrue($preview['can_execute']);

        $service->execute($keepId, $dropId, [], true, $playerteamMerges);

        $kept = Playerteam::query()->find($keepPt);
        $this->assertNotNull($kept);
        $this->assertSame('m', (string) $kept->playerteam_player_position);
        $this->assertSame(0, (int) $kept->playerteam_status);
        $this->assertSame('drop-note', (string) $kept->playerteam_player_note);
        $this->assertNull(Playerteam::query()->find($dropPt));

        $this->assertSame(1, DB::table('ffb_playerprice')->count());
        $this->assertSame(11.0, (float) DB::table('ffb_playerprice')->value('playerprice_price'));
        $this->assertSame($keepPt, (int) DB::table('ffb_playerprice')->value('playerprice_playerteam_id'));
        $this->assertSame(1, DB::table('ffb_playerfid')->count());
        $this->assertSame('DropFid', (string) DB::table('ffb_playerfid')->value('playerfid_name_wf'));
        $this->assertSame($keepPt, (int) DB::table('ffb_playerfid')->value('playerfid_playerteam_id'));
    }

    #[Test]
    public function merge_allows_position_choice_matching_drop_userteam_slots(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer(['player_fname' => 'A', 'player_lname' => 'One']);
        $dropId = $this->insertPlayer(['player_fname' => 'B', 'player_lname' => 'Two']);
        $keepPt = $this->insertPlayerteam($keepId, 1, 10, 'd');
        $dropPt = $this->insertPlayerteam($dropId, 1, 10, 'm');

        DB::table('web_user')->insert([
            'user_id' => 3,
            'user_nickname' => 'midpicker',
        ]);
        DB::table('ffb_userteam')->insert([
            'userteam_id' => 80,
            'userteam_user_id' => 3,
            'userteam_matchround_id' => 1,
            'userteam_price' => 0,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 80,
            'userteam_slot_slot' => 7,
            'userteam_slot_playerteam_id' => $dropPt,
        ]);

        $playerteamMerges = [[
            'keep_pt' => $keepPt,
            'drop_pt' => $dropPt,
            'fields' => [
                'playerteam_player_position' => 'drop',
            ],
            'children' => [],
        ]];

        $service = new AdminPlayerMergeService;
        $preview = $service->preview($keepId, $dropId, [], $playerteamMerges);
        $this->assertTrue($preview['can_execute']);
        $this->assertSame([], $preview['blockers']);

        $service->execute($keepId, $dropId, [], true, $playerteamMerges);

        $this->assertSame('m', (string) Playerteam::query()->find($keepPt)->playerteam_player_position);
        $this->assertSame($keepPt, (int) DB::table('ffb_userteam_slot')
            ->where('userteam_slot_userteam_id', 80)
            ->value('userteam_slot_playerteam_id'));
    }

    #[Test]
    public function merge_blocks_when_userteam_would_have_duplicate_slot(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer(['player_fname' => 'A', 'player_lname' => 'One']);
        $dropId = $this->insertPlayer(['player_fname' => 'B', 'player_lname' => 'Two']);
        $keepPt = $this->insertPlayerteam($keepId, 1, 10);
        $dropPt = $this->insertPlayerteam($dropId, 1, 10);

        DB::table('ffb_userteam')->insert([
            'userteam_id' => 60,
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
            'userteam_price' => 0,
        ]);
        DB::table('ffb_userteam_slot')->insert([
            [
                'userteam_slot_userteam_id' => 60,
                'userteam_slot_slot' => 1,
                'userteam_slot_playerteam_id' => $keepPt,
            ],
            [
                'userteam_slot_userteam_id' => 60,
                'userteam_slot_slot' => 2,
                'userteam_slot_playerteam_id' => $dropPt,
            ],
        ]);

        $service = new AdminPlayerMergeService;
        $preview = $service->preview($keepId, $dropId, []);
        $this->assertFalse($preview['can_execute']);
        $this->assertNotEmpty($preview['blockers']);

        try {
            $service->execute($keepId, $dropId, [], true);
            $this->fail('Expected InvalidArgumentException');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('Userteam', $e->getMessage());
        }

        $this->assertNotNull(Player::query()->find($dropId));
        $this->assertNotNull(Playerteam::query()->find($dropPt));
        $this->assertSame($dropPt, (int) DB::table('ffb_userteam_slot')
            ->where('userteam_slot_userteam_id', 60)
            ->where('userteam_slot_slot', 2)
            ->value('userteam_slot_playerteam_id'));
    }

    #[Test]
    public function merge_can_take_drop_player_asset_key(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer([
            'player_fname' => 'A',
            'player_lname' => 'One',
            'asset_key' => 'keep-key-aaaa',
        ]);
        $dropId = $this->insertPlayer([
            'player_fname' => 'B',
            'player_lname' => 'Two',
            'asset_key' => 'drop-key-bbbb',
        ]);
        $this->insertPlayerteam($keepId, 1, 10);
        $this->insertPlayerteam($dropId, 2, 10);

        $service = new AdminPlayerMergeService;
        $prepared = $service->prepare($keepId, $dropId);
        $assetField = collect($prepared['fields'])->firstWhere('key', 'asset_key');
        $this->assertNotNull($assetField);
        $this->assertSame('Bild / Asset-Key', $assetField['label']);

        $service->execute($keepId, $dropId, [
            '_player_a_id' => $keepId,
            '_player_b_id' => $dropId,
            'asset_key' => 'b',
        ], true);

        $keep = Player::query()->find($keepId);
        $this->assertSame('drop-key-bbbb', (string) $keep->asset_key);
        $this->assertNull(Player::query()->find($dropId));
    }

    #[Test]
    public function merge_discards_duplicate_playerprice_on_same_matchround(): void
    {
        $this->createMergeSchema();

        $keepId = $this->insertPlayer(['player_fname' => 'A', 'player_lname' => 'One']);
        $dropId = $this->insertPlayer(['player_fname' => 'B', 'player_lname' => 'Two']);
        $keepPt = $this->insertPlayerteam($keepId, 1, 10);
        $dropPt = $this->insertPlayerteam($dropId, 1, 10);

        DB::table('ffb_playerprice')->insert([
            [
                'playerprice_id' => 1,
                'playerprice_playerteam_id' => $keepPt,
                'playerprice_matchround_id' => 3,
                'playerprice_price' => 10,
            ],
            [
                'playerprice_id' => 2,
                'playerprice_playerteam_id' => $dropPt,
                'playerprice_matchround_id' => 3,
                'playerprice_price' => 11,
            ],
        ]);

        $service = new AdminPlayerMergeService;
        $preview = $service->preview($keepId, $dropId, []);
        $this->assertSame(1, $preview['impact']['rows_discarded_by_table']['ffb_playerprice'] ?? 0);

        $service->execute($keepId, $dropId, [], true);

        $this->assertSame(1, DB::table('ffb_playerprice')->count());
        $this->assertSame(10.0, (float) DB::table('ffb_playerprice')->where('playerprice_id', 1)->value('playerprice_price'));
        $this->assertNull(DB::table('ffb_playerprice')->find(2));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function insertPlayer(array $overrides): int
    {
        return (int) Player::query()->insertGetId(array_merge([
            'player_foreign_id' => '',
            'player_fname' => 'Test',
            'player_lname' => 'Player',
            'player_nationality' => 'AUT',
            'player_uefa_id' => '',
            'player_fifa_id' => '',
            'player_status' => 1,
            'player_status_description' => '',
            'player_commons_image' => '',
            'asset_key' => '',
        ], $overrides), 'player_id');
    }

    private function insertPlayerteam(int $playerId, int $teamId, int $leagueId, string $position = 'd'): int
    {
        return (int) Playerteam::query()->insertGetId([
            'playerteam_player_id' => $playerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => $position,
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ], 'playerteam_id');
    }

    private function createMergeSchema(): void
    {
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam_substitute_slot');
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerprice');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_playerfid');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('web_user');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('asset_key')->default('');
        });
        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_name')->default('');
            $table->string('asset_key')->default('');
        });
        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('asset_key')->default('');
            $table->string('player_foreign_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_display_name')->default('');
            $table->string('player_nationality')->default('');
            $table->string('player_uefa_id')->default('');
            $table->string('player_fifa_id')->default('');
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
            $table->string('player_commons_image')->default('');
        });
        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
        });
        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id')->default(0);
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_player_note')->default('');
            $table->string('playerteam_date_transfer')->nullable();
        });
        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->integer('playerstats_score')->default(0);
        });
        Schema::create('ffb_playerprice', function (Blueprint $table) {
            $table->increments('playerprice_id');
            $table->unsignedInteger('playerprice_playerteam_id');
            $table->unsignedInteger('playerprice_matchround_id');
            $table->double('playerprice_price')->default(0);
        });
        Schema::create('ffb_goal', function (Blueprint $table) {
            $table->increments('goal_id');
            $table->unsignedInteger('goal_playerteam_id');
            $table->unsignedInteger('goal_match_id')->default(0);
        });
        Schema::create('ffb_psgoal', function (Blueprint $table) {
            $table->increments('psgoal_id');
            $table->unsignedInteger('psgoal_playerteam_id');
            $table->unsignedInteger('psgoal_match_id')->default(0);
        });
        Schema::create('ffb_playerfid', function (Blueprint $table) {
            $table->increments('playerfid_id');
            $table->unsignedInteger('playerfid_playerteam_id');
            $table->string('playerfid_name_wf')->default('');
        });
        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->increments('userteam_id');
            $table->unsignedInteger('userteam_user_id')->default(0);
            $table->unsignedInteger('userteam_matchround_id')->nullable();
            $table->double('userteam_price')->default(0);
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
        });
        Schema::create('ffb_extremeteam_slot', function (Blueprint $table) {
            $table->increments('extremeteam_slot_id');
            $table->unsignedInteger('extremeteam_slot_extremeteam_id');
            $table->unsignedTinyInteger('extremeteam_slot_slot');
            $table->unsignedInteger('extremeteam_slot_playerteam_id');
        });
    }
}
