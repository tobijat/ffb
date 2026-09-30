<?php

namespace Tests\Feature;

use App\Models\Extremeteam;
use App\Models\League;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Userteam;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminDeleteBlockerTest extends TestCase
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
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function squad_delete_is_blocked_when_playerteam_is_in_userteam_with_ids(): void
    {
        [$teamId, $ptId] = $this->seedSquadPlayer();
        $userteamId = (int) Userteam::query()->insertGetId([
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
            'userteam_score' => 0,
            'userteam_price' => 0,
            'userteam_lc_points' => 0,
        ], 'userteam_id');
        $this->insertUserteamSlot($userteamId, 1, $ptId);

        $result = $this->squadService()->delete($ptId);

        $this->assertFalse($result['ok']);
        $this->assertSame(
            ['Löschen nicht möglich: Spieler ist in Userteams eingesetzt (Userteam-IDs: '.$userteamId.').'],
            $result['errors'],
        );
        $this->assertDatabaseHas('ffb_playerteam', ['playerteam_id' => $ptId]);
        unset($teamId);
    }

    #[Test]
    public function squad_delete_is_blocked_when_playerteam_is_in_extreme_team_with_refs(): void
    {
        [$teamId, $ptId] = $this->seedSquadPlayer();
        $roundId = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => 1,
            'matchround_title' => 'Runde 3',
            'matchround_startdate' => '2026-09-01 00:00:00',
            'matchround_enddate' => '2026-09-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');
        $extremeId = (int) Extremeteam::query()->insertGetId([
            'extremeteam_top_or_flop' => 'top',
            'extremeteam_price' => 50,
            'extremeteam_matchround_id' => $roundId,
            'extremeteam_score' => 10,
        ], 'extremeteam_id');
        $this->insertExtremeSlot($extremeId, 1, $ptId);

        $result = $this->squadService()->delete($ptId);

        $this->assertFalse($result['ok']);
        $this->assertSame(
            ['Löschen nicht möglich: Spieler ist in Top/Flop-Teams eingesetzt (Extremeteam-ID '.$extremeId.' TOP (Runde 3)).'],
            $result['errors'],
        );
        $this->assertDatabaseHas('ffb_playerteam', ['playerteam_id' => $ptId]);
        unset($teamId);
    }

    #[Test]
    public function squad_delete_is_blocked_when_playerstats_exist_with_ids(): void
    {
        [$teamId, $ptId] = $this->seedSquadPlayer();
        $statsId = (int) Playerstats::query()->insertGetId([
            'playerstats_playerteam_id' => $ptId,
            'playerstats_matchround_id' => 1,
            'playerstats_score' => 0,
            'playerstats_cards' => 'n',
        ], 'playerstats_id');

        $result = $this->squadService()->delete($ptId);

        $this->assertFalse($result['ok']);
        $this->assertSame(
            ['Löschen nicht möglich: Es gibt zugehörige Spielstatistiken (Playerstats-IDs: '.$statsId.').'],
            $result['errors'],
        );
        unset($teamId);
    }

    #[Test]
    public function player_delete_lists_playerteam_ids_when_still_on_squad(): void
    {
        [, $ptId, $playerId] = $this->seedSquadPlayerWithPlayerId();

        $result = $this->playerService()->delete($playerId);

        $this->assertFalse($result['ok']);
        $this->assertSame(
            ['Löschen nicht möglich: Spieler ist noch einem oder mehreren Teams zugeordnet (PT-IDs: '.$ptId.').'],
            $result['errors'],
        );
    }

    #[Test]
    public function player_delete_prefers_userteam_blocker_with_ids(): void
    {
        [, $ptId, $playerId] = $this->seedSquadPlayerWithPlayerId();
        $userteamId = (int) Userteam::query()->insertGetId([
            'userteam_user_id' => 1,
            'userteam_matchround_id' => 1,
            'userteam_score' => 0,
            'userteam_price' => 0,
            'userteam_lc_points' => 0,
        ], 'userteam_id');
        $this->insertUserteamSlot($userteamId, 1, $ptId);

        $result = $this->playerService()->delete($playerId);

        $this->assertFalse($result['ok']);
        $this->assertSame(
            ['Löschen nicht möglich: Spieler ist in Userteams eingesetzt (Userteam-IDs: '.$userteamId.'; PT-IDs: '.$ptId.').'],
            $result['errors'],
        );
    }

    #[Test]
    public function player_delete_prefers_extreme_team_blocker_with_refs(): void
    {
        [, $ptId, $playerId] = $this->seedSquadPlayerWithPlayerId();
        $roundId = (int) Matchround::query()->insertGetId([
            'matchround_league_id' => 1,
            'matchround_title' => 'Finale',
            'matchround_startdate' => '2026-09-01 00:00:00',
            'matchround_enddate' => '2026-09-02 00:00:00',
            'matchround_status' => 1,
        ], 'matchround_id');
        $extremeId = (int) Extremeteam::query()->insertGetId([
            'extremeteam_top_or_flop' => 'flop',
            'extremeteam_price' => 40,
            'extremeteam_matchround_id' => $roundId,
            'extremeteam_score' => -5,
        ], 'extremeteam_id');
        $this->insertExtremeSlot($extremeId, 1, $ptId);

        $result = $this->playerService()->delete($playerId);

        $this->assertFalse($result['ok']);
        $this->assertSame(
            ['Löschen nicht möglich: Spieler ist in Top/Flop-Teams eingesetzt (Extremeteam-ID '.$extremeId.' FLOP (Finale); PT-IDs: '.$ptId.').'],
            $result['errors'],
        );
    }

    private function squadService(): AdminSquadService
    {
        return new AdminSquadService(
            Mockery::mock(AdminCenterService::class),
            Mockery::mock(AdminPlayerService::class),
            new WikimediaPlayerImageService,
        );
    }

    private function playerService(): AdminPlayerService
    {
        return new AdminPlayerService(Mockery::mock(AdminCenterService::class));
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedSquadPlayer(): array
    {
        [$teamId, $ptId] = $this->seedSquadPlayerWithPlayerId();

        return [$teamId, $ptId];
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private function seedSquadPlayerWithPlayerId(): array
    {
        $leagueId = (int) League::query()->insertGetId([
            'league_title' => 'Testliga',
            'league_type' => 'nation',
            'league_symbol' => '',
            'league_archive' => 0,
        ], 'league_id');

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
            'playerteam_player_note' => '',
            'playerteam_date_transfer' => null,
        ], 'playerteam_id');

        return [$teamId, $ptId, $playerId];
    }

    private function insertUserteamSlot(int $userteamId, int $slot, int $playerteamId): void
    {
        Schema::getConnection()->table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => $userteamId,
            'userteam_slot_slot' => $slot,
            'userteam_slot_playerteam_id' => $playerteamId,
        ]);
    }

    private function insertExtremeSlot(int $extremeId, int $slot, int $playerteamId): void
    {
        Schema::getConnection()->table('ffb_extremeteam_slot')->insert([
            'extremeteam_slot_extremeteam_id' => $extremeId,
            'extremeteam_slot_slot' => $slot,
            'extremeteam_slot_playerteam_id' => $playerteamId,
        ]);
    }

    private function createSchema(): void
    {
        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('league_type')->default('');
            $table->string('league_symbol')->default('');
            $table->integer('league_archive')->default(0);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->integer('matchround_league_id')->default(0);
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->integer('matchround_status')->default(1);
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
            $table->string('player_uefa_id')->default('');
            $table->string('player_fifa_id')->default('');
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
            $table->unsignedInteger('playerteam_league_id');
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
            $table->string('playerstats_cards')->default('n');
        });

        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->increments('userteam_id');
            $table->unsignedInteger('userteam_user_id');
            $table->unsignedInteger('userteam_matchround_id');
            $table->integer('userteam_score')->default(0);
            $table->double('userteam_price')->default(0);
            $table->integer('userteam_lc_points')->default(0);
        });

        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
        });

        Schema::create('ffb_extremeteam', function (Blueprint $table) {
            $table->increments('extremeteam_id');
            $table->string('extremeteam_top_or_flop', 8);
            $table->decimal('extremeteam_price', 9, 2)->default(0);
            $table->unsignedInteger('extremeteam_matchround_id');
            $table->integer('extremeteam_score')->default(-1);
        });

        Schema::create('ffb_extremeteam_slot', function (Blueprint $table) {
            $table->increments('extremeteam_slot_id');
            $table->unsignedInteger('extremeteam_slot_extremeteam_id');
            $table->unsignedTinyInteger('extremeteam_slot_slot');
            $table->unsignedInteger('extremeteam_slot_playerteam_id');
        });
    }
}
