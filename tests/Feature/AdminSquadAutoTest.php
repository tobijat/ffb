<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\WikimediaPlayerImageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminSquadAutoTest extends TestCase
{
    /** @var list<string> */
    private array $tempSquadFiles = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        foreach ($this->tempSquadFiles as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
        $this->tempSquadFiles = [];

        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function analyze_errors_when_fifa_code_is_missing_in_json(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $file = $this->jsonFile([
            [
                'name' => 'Germany',
                'fifa_code' => 'GER',
                'players' => [
                    ['number' => 1, 'pos' => 'GK', 'name' => 'Manuel Neuer'],
                ],
            ],
        ]);

        $result = $this->service()->analyzeSquadsFile($teamId, $leagueId, $file);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('CZE', $result['errors'][0]);
    }

    #[Test]
    public function analyze_builds_draft_and_marks_existing_players(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Tomáš',
            'player_lname' => 'Holeš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $file = $this->jsonFile([
            [
                'name' => 'Czech Republic',
                'fifa_code' => 'CZE',
                'players' => [
                    ['number' => 1, 'pos' => 'GK', 'name' => 'Matěj Kovář'],
                    ['number' => 3, 'pos' => 'DF', 'name' => 'Tomáš Holeš'],
                    ['number' => 10, 'pos' => 'FW', 'name' => 'Patrik Schick'],
                ],
            ],
        ]);

        $result = $this->service()->analyzeSquadsFile($teamId, $leagueId, $file);

        $this->assertTrue($result['ok']);
        $this->assertCount(3, $result['auto']['players']);
        $this->assertSame('CZE', $result['auto']['fifa_code']);

        $byName = [];
        foreach ($result['auto']['players'] as $row) {
            $byName[$row['json_name']] = $row;
        }

        $this->assertTrue($byName['Matěj Kovář']['is_new']);
        $this->assertSame('g', $byName['Matěj Kovář']['playerteam_player_position']);
        $this->assertSame('Matěj', $byName['Matěj Kovář']['player_fname']);
        $this->assertSame('Kovář', $byName['Matěj Kovář']['player_lname']);

        $this->assertFalse($byName['Tomáš Holeš']['is_new']);
        $this->assertSame((int) $existing->player_id, $byName['Tomáš Holeš']['player_id']);
        $this->assertSame('d', $byName['Tomáš Holeš']['playerteam_player_position']);

        $this->assertTrue($byName['Patrik Schick']['is_new']);
        $this->assertSame('s', $byName['Patrik Schick']['playerteam_player_position']);
    }

    #[Test]
    public function analyze_includes_players_already_on_squad(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Tomáš',
            'player_lname' => 'Holeš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        Playerteam::query()->create([
            'playerteam_player_id' => (int) $existing->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $file = $this->jsonFile([
            [
                'name' => 'Czech Republic',
                'fifa_code' => 'CZE',
                'players' => [
                    ['number' => 1, 'pos' => 'GK', 'name' => 'Matěj Kovář'],
                    ['number' => 3, 'pos' => 'DF', 'name' => 'Tomáš Holeš'],
                ],
            ],
        ]);

        $result = $this->service()->analyzeSquadsFile($teamId, $leagueId, $file);

        $this->assertTrue($result['ok']);
        $this->assertCount(2, $result['auto']['players']);
        $this->assertStringContainsString('2 Spieler bereit zum Übernehmen', $result['message']);
        $this->assertStringContainsString('davon 1 bereits im Kader', $result['message']);

        $byName = [];
        foreach ($result['auto']['players'] as $row) {
            $byName[$row['json_name']] = $row;
        }

        $this->assertFalse($byName['Matěj Kovář']['on_squad']);
        $this->assertTrue($byName['Tomáš Holeš']['on_squad']);
        $this->assertSame('m', $byName['Tomáš Holeš']['playerteam_player_position']);
        $this->assertArrayNotHasKey('playerteam_player_price', $byName['Tomáš Holeš']);
    }

    #[Test]
    public function analyze_lists_active_squad_players_missing_from_json_as_inactive(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $kept = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Tomáš',
            'player_lname' => 'Holeš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $surplus = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Old',
            'player_lname' => 'Starter',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $alreadyInactive = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Already',
            'player_lname' => 'Inactive',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        Playerteam::query()->create([
            'playerteam_player_id' => (int) $kept->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);
        $surplusRow = Playerteam::query()->create([
            'playerteam_player_id' => (int) $surplus->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);
        Playerteam::query()->create([
            'playerteam_player_id' => (int) $alreadyInactive->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 0,
            'playerteam_player_position' => 's',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $file = $this->jsonFile([
            [
                'name' => 'Czech Republic',
                'fifa_code' => 'CZE',
                'players' => [
                    ['number' => 3, 'pos' => 'DF', 'name' => 'Tomáš Holeš'],
                ],
            ],
        ]);

        $result = $this->service()->analyzeSquadsFile($teamId, $leagueId, $file);

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertCount(2, $result['auto']['players']);
        $this->assertStringContainsString('1 aktiver Kader-Spieler nicht in JSON', $result['message']);

        $byPlayerId = [];
        foreach ($result['auto']['players'] as $row) {
            $byPlayerId[(int) $row['player_id']] = $row;
        }

        $this->assertTrue($byPlayerId[(int) $kept->player_id]['on_squad']);
        $this->assertFalse($byPlayerId[(int) $kept->player_id]['not_in_json'] ?? false);
        $this->assertSame(1, (int) $byPlayerId[(int) $kept->player_id]['playerteam_status']);

        $this->assertTrue($byPlayerId[(int) $surplus->player_id]['not_in_json']);
        $this->assertTrue($byPlayerId[(int) $surplus->player_id]['on_squad']);
        $this->assertSame(0, (int) $byPlayerId[(int) $surplus->player_id]['playerteam_status']);
        $this->assertSame((int) $surplusRow->playerteam_id, (int) $byPlayerId[(int) $surplus->player_id]['playerteam_id']);
        $this->assertArrayNotHasKey((int) $alreadyInactive->player_id, $byPlayerId);
    }

    #[Test]
    public function create_deactivates_surplus_active_squad_players_from_draft(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $kept = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Tomáš',
            'player_lname' => 'Holeš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $surplus = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Old',
            'player_lname' => 'Starter',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $keptRow = Playerteam::query()->create([
            'playerteam_player_id' => (int) $kept->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);
        $surplusRow = Playerteam::query()->create([
            'playerteam_player_id' => (int) $surplus->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $result = $this->service()->createSquadFromDraft([
            [
                'player_id' => (int) $kept->player_id,
                'playerteam_id' => (int) $keptRow->playerteam_id,
                'is_new' => false,
                'on_squad' => true,
                'not_in_json' => false,
                'player_fname' => 'Tomáš',
                'player_lname' => 'Holeš',
                'player_nationality' => 'CZE',
                'playerteam_player_position' => 'd',
                'playerteam_status' => 1,
                'playerteam_date_transfer' => '2008-01-01',
                'json_name' => 'Tomáš Holeš',
            ],
            [
                'player_id' => (int) $surplus->player_id,
                'playerteam_id' => (int) $surplusRow->playerteam_id,
                'is_new' => false,
                'on_squad' => true,
                'not_in_json' => true,
                'player_fname' => 'Old',
                'player_lname' => 'Starter',
                'player_nationality' => 'CZE',
                'playerteam_player_position' => 'm',
                'playerteam_status' => 0,
                'playerteam_date_transfer' => '2008-01-01',
                'json_name' => 'Old Starter',
            ],
        ], $teamId, $leagueId, 'worldcup.squads.json', 'CZE');

        $this->assertTrue($result['ok'], implode('; ', $result['errors'] ?? []));
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_id' => (int) $keptRow->playerteam_id,
            'playerteam_status' => 1,
        ]);
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_id' => (int) $surplusRow->playerteam_id,
            'playerteam_status' => 0,
        ]);
    }

    #[Test]
    public function analyze_sorts_players_by_position(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $file = $this->jsonFile([
            [
                'name' => 'Czech Republic',
                'fifa_code' => 'CZE',
                'players' => [
                    ['number' => 10, 'pos' => 'FW', 'name' => 'Patrik Schick'],
                    ['number' => 8, 'pos' => 'MF', 'name' => 'Tomáš Souček'],
                    ['number' => 1, 'pos' => 'GK', 'name' => 'Matěj Kovář'],
                    ['number' => 3, 'pos' => 'DF', 'name' => 'Tomáš Holeš'],
                ],
            ],
        ]);

        $result = $this->service()->analyzeSquadsFile($teamId, $leagueId, $file);

        $this->assertTrue($result['ok']);
        $positions = array_column($result['auto']['players'], 'playerteam_player_position');
        $this->assertSame(['g', 'd', 'm', 's'], $positions);
    }

    #[Test]
    public function create_adds_new_and_existing_players_to_squad(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Tomáš',
            'player_lname' => 'Holeš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $result = $this->service()->createSquadFromDraft([
            [
                'player_id' => 0,
                'is_new' => true,
                'player_fname' => 'Matěj',
                'player_lname' => 'Kovář',
                'player_nationality' => 'CZE',
                'player_status' => 1,
                'player_status_description' => '',
                'player_foreign_id' => '',
                'playerteam_player_position' => 'g',
                'playerteam_status' => 1,
                'playerteam_date_transfer' => '2008-01-01',
                'json_name' => 'Matěj Kovář',
            ],
            [
                'player_id' => (int) $existing->player_id,
                'is_new' => false,
                'player_fname' => 'Tomáš',
                'player_lname' => 'Holeš',
                'player_nationality' => 'CZE',
                'player_status' => 1,
                'player_status_description' => '',
                'player_foreign_id' => '',
                'playerteam_player_position' => 'd',
                'playerteam_status' => 1,
                'playerteam_date_transfer' => '2008-01-01',
                'json_name' => 'Tomáš Holeš',
            ],
        ], $teamId, $leagueId, 'worldcup.squads.json', 'CZE');

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('1 Spieler neu angelegt', $result['message']);
        $this->assertStringContainsString('2 Spieler zum Kader hinzugefügt', $result['message']);

        $this->assertDatabaseHas('ffb_player', [
            'player_fname' => 'Matěj',
            'player_lname' => 'Kovář',
            'player_nationality' => 'CZE',
        ]);

        $newPlayerId = (int) Player::query()
            ->where('player_fname', 'Matěj')
            ->where('player_lname', 'Kovář')
            ->value('player_id');

        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_player_id' => $newPlayerId,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_position' => 'g',
        ]);
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_player_id' => (int) $existing->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_position' => 'd',
        ]);
    }

    #[Test]
    public function create_updates_players_already_on_squad(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Tomáš',
            'player_lname' => 'Holeš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $playerteam = Playerteam::query()->create([
            'playerteam_player_id' => (int) $existing->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'm',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $result = $this->service()->createSquadFromDraft([
            [
                'player_id' => 0,
                'is_new' => true,
                'on_squad' => false,
                'player_fname' => 'Matěj',
                'player_lname' => 'Kovář',
                'player_nationality' => 'CZE',
                'player_status' => 1,
                'player_status_description' => '',
                'player_foreign_id' => '',
                'playerteam_player_position' => 'g',
                'playerteam_status' => 1,
                'playerteam_date_transfer' => '2008-01-01',
                'json_name' => 'Matěj Kovář',
            ],
            [
                'player_id' => (int) $existing->player_id,
                'playerteam_id' => (int) $playerteam->playerteam_id,
                'is_new' => false,
                'on_squad' => true,
                'player_fname' => 'Tomáš',
                'player_lname' => 'Holeš',
                'player_nationality' => 'CZE',
                'player_status' => 1,
                'player_status_description' => '',
                'player_foreign_id' => '',
                'playerteam_player_position' => 'd',
                'playerteam_status' => 0,
                'playerteam_date_transfer' => '2008-01-01',
                'json_name' => 'Tomáš Holeš',
            ],
        ], $teamId, $leagueId, 'worldcup.squads.json', 'CZE');

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('1 Spieler neu angelegt', $result['message']);
        $this->assertStringContainsString('1 Spieler zum Kader hinzugefügt', $result['message']);
        $this->assertStringContainsString('1 Kader-Eintrag aktualisiert', $result['message']);
        $this->assertSame(2, Playerteam::query()->where('playerteam_team_id', $teamId)->count());
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_id' => (int) $playerteam->playerteam_id,
            'playerteam_player_position' => 'd',
            'playerteam_status' => 0,
        ]);
    }

    #[Test]
    public function analyze_puts_accent_and_switched_names_into_almost_matches(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $accent = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Matej',
            'player_lname' => 'Kovar',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $switched = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Holeš',
            'player_lname' => 'Tomáš',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);
        $single = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Pele',
            'player_lname' => 'Pele',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        Playerteam::query()->create([
            'playerteam_player_id' => (int) $accent->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_position' => 'g',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $file = $this->jsonFile([
            [
                'name' => 'Czech Republic',
                'fifa_code' => 'CZE',
                'players' => [
                    ['number' => 1, 'pos' => 'GK', 'name' => 'Matěj Kovář'],
                    ['number' => 3, 'pos' => 'DF', 'name' => 'Tomáš Holeš'],
                    ['number' => 9, 'pos' => 'FW', 'name' => 'Pelé'],
                ],
            ],
        ]);

        $result = $this->service()->analyzeSquadsFile($teamId, $leagueId, $file);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto']['players']);
        $this->assertSame('Matěj Kovář', $result['auto']['players'][0]['json_name']);
        $this->assertTrue($result['auto']['players'][0]['on_squad']);
        $this->assertCount(2, $result['auto']['almost']);

        $byJson = [];
        foreach ($result['auto']['almost'] as $row) {
            $byJson[$row['json_name']] = $row;
        }

        $this->assertSame((int) $switched->player_id, $byJson['Tomáš Holeš']['db_player_id']);
        $this->assertSame('Vor-/Nachname vertauscht', $byJson['Tomáš Holeš']['match_reason']);
        $this->assertSame((int) $single->player_id, $byJson['Pelé']['db_player_id']);
        $this->assertStringContainsString('Einzelnamen', $byJson['Pelé']['match_reason']);
        $this->assertStringContainsString('Namens-Ähnlichkeiten zur Prüfung', $result['message']);
    }

    #[Test]
    public function create_almost_match_can_reuse_existing_or_create_new(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $existing = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Matej',
            'player_lname' => 'Kovar',
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        $reuse = $this->service()->createSquadFromDraft([], $teamId, $leagueId, 'squads.json', 'CZE', [
            [
                'use_existing' => true,
                'json_number' => 1,
                'json_name' => 'Matěj Kovář',
                'json_fname' => 'Matěj',
                'json_lname' => 'Kovář',
                'json_nationality' => 'CZE',
                'json_position' => 'g',
                'db_player_id' => (int) $existing->player_id,
                'db_fname' => 'Matej',
                'db_lname' => 'Kovar',
                'db_nationality' => 'CZE',
                'db_position' => '',
                'db_squads' => [],
                'db_foreign_id' => '',
                'playerteam_player_position' => 'g',
                'playerteam_status' => 1,
                'playerteam_date_transfer' => '2008-01-01',
            ],
        ]);

        $this->assertTrue($reuse['ok']);
        $this->assertSame(1, Player::query()->count());
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_player_id' => (int) $existing->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_player_position' => 'g',
        ]);

        Playerteam::query()->delete();

        $createNew = $this->service()->createSquadFromDraft([], $teamId, $leagueId, 'squads.json', 'CZE', [
            [
                'use_existing' => false,
                'json_number' => 1,
                'json_name' => 'Matěj Kovář',
                'json_fname' => 'Matěj',
                'json_lname' => 'Kovář',
                'json_nationality' => 'CZE',
                'json_position' => 'g',
                'db_player_id' => (int) $existing->player_id,
                'db_fname' => 'Matej',
                'db_lname' => 'Kovar',
                'db_nationality' => 'CZE',
                'db_position' => '',
                'db_squads' => [],
                'db_foreign_id' => '',
                'playerteam_player_position' => 'g',
                'playerteam_status' => 1,
                'playerteam_date_transfer' => '2008-01-01',
            ],
        ]);

        $this->assertTrue($createNew['ok']);
        $this->assertSame(2, Player::query()->count());
        $this->assertDatabaseHas('ffb_player', [
            'player_fname' => 'Matěj',
            'player_lname' => 'Kovář',
            'player_nationality' => 'CZE',
        ]);
    }

    #[Test]
    public function team_options_include_active_squad_player_counts(): void
    {
        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $emptyTeam = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Empty Squad',
            'team_nationality' => 'ger',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        $this->ensureMatchTables();

        foreach ([['Active', 'One'], ['Active', 'Two'], ['Inactive', 'Three']] as [$fname, $lname]) {
            $player = Player::query()->create([
                'player_foreign_id' => '',
                'player_fname' => $fname,
                'player_lname' => $lname,
                'player_nationality' => 'CZE',
                'player_status' => 1,
                'player_status_description' => '',
            ]);

            Playerteam::query()->create([
                'playerteam_player_id' => (int) $player->player_id,
                'playerteam_team_id' => $teamId,
                'playerteam_league_id' => $leagueId,
                'playerteam_player_picture' => '',
                'playerteam_status' => $fname === 'Inactive' ? 0 : 1,
                'playerteam_player_position' => 'd',
                'playerteam_date_transfer' => '2008-01-01 00:00:00',
            ]);
        }

        $inactiveOnly = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Bench',
            'player_lname' => 'Only',
            'player_nationality' => 'GER',
            'player_status' => 1,
            'player_status_description' => '',
        ]);

        Playerteam::query()->create([
            'playerteam_player_id' => (int) $inactiveOnly->player_id,
            'playerteam_team_id' => (int) $emptyTeam->team_id,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => '',
            'playerteam_status' => 0,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $adminCenter = Mockery::mock(AdminCenterService::class);
        $adminCenter->shouldReceive('shellPayload')->andReturn([
            'user' => ['user_id' => 544],
            'navigation' => [],
            'selected_league' => ['league_id' => $leagueId],
            'selected_league_id' => $leagueId,
        ]);
        $adminCenter->shouldReceive('selectedLeagueId')->andReturn($leagueId);

        $service = new AdminSquadService(
            $adminCenter,
            new AdminPlayerService($adminCenter),
            new WikimediaPlayerImageService
        );

        $payload = $service->pagePayload(544, $teamId, $leagueId, 'auto');

        $byId = [];
        foreach ($payload['teams'] as $team) {
            $byId[(int) $team['team_id']] = $team;
        }

        $this->assertSame(2, $byId[$teamId]['active_count']);
        $this->assertSame(0, $byId[(int) $emptyTeam->team_id]['active_count']);
    }

    private function service(): AdminSquadService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $players = new AdminPlayerService($adminCenter);

        return new AdminSquadService($adminCenter, $players, new WikimediaPlayerImageService);
    }

    /**
     * @param  list<array<string, mixed>>  $payload
     */
    private function jsonFile(array $payload): string
    {
        $dir = public_path('data/squad');
        File::ensureDirectoryExists($dir);

        $name = '_test_squad_'.uniqid('', true).'.json';
        $path = $dir.DIRECTORY_SEPARATOR.$name;
        file_put_contents($path, json_encode($payload, JSON_THROW_ON_ERROR));
        $this->tempSquadFiles[] = $path;

        return $name;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedTeamAndLeague(string $nationality): array
    {
        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Czech Republic',
            'team_nationality' => $nationality,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        return [(int) $team->team_id, (int) $league->league_id];
    }

    private function ensureMatchTables(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id')->default(0);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round')->default(0);
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
        });
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->string('league_symbol')->default('');
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
            $table->string('player_commons_image')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id');
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_date_transfer')->nullable();
        });
    }
}
