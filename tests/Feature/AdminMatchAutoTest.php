<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminMatchService;
use App\Services\AdminTeamService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMatchAutoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function analyze_errors_when_teams_are_missing(): void
    {
        $leagueId = $this->seedLeagueWithRound();
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        $file = $this->jsonFile([
            'spieltage' => [
                [
                    'spieltag' => 1,
                    'spiele' => [
                        ['datum' => '2026-09-24', 'heim' => 'Niederlande', 'gast' => 'Deutschland'],
                    ],
                ],
            ],
        ]);

        $result = $this->service()->analyzeMatchroundsFile($leagueId, $file);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Niederlande', $result['errors'][0]);
        $this->assertStringContainsString('Auto-Teams', $result['errors'][0]);
    }

    #[Test]
    public function analyze_builds_editable_draft_rows(): void
    {
        $leagueId = $this->seedLeagueWithRound();
        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        $file = $this->jsonFile([
            'spieltage' => [
                [
                    'spieltag' => 1,
                    'spiele' => [
                        ['datum' => '2026-09-24', 'heim' => 'Niederlande', 'gast' => 'Deutschland'],
                    ],
                ],
            ],
        ]);

        $result = $this->service()->analyzeMatchroundsFile($leagueId, $file);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto']['matches']);
        $this->assertSame((int) $home->team_id, $result['auto']['matches'][0]['match_hometeam_id']);
        $this->assertSame((int) $guest->team_id, $result['auto']['matches'][0]['match_guestteam_id']);
        $this->assertSame('2026-09-24', $result['auto']['matches'][0]['match_date']);
    }

    #[Test]
    public function analyze_hides_matches_already_in_database(): void
    {
        $leagueId = $this->seedLeagueWithRound();
        $roundId = (int) Matchround::query()->value('matchround_id');
        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $otherHome = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'aut',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $otherGuest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Italien',
            'team_nationality' => 'ita',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2026-09-24 00:00:00',
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_status' => '',
            'match_homescore' => '-1',
            'match_guestscore' => '-1',
            'match_homescore_penalty' => '-1',
            'match_guestscore_penalty' => '-1',
            'match_minutes' => 0,
            'match_url' => '',
        ]);

        $file = $this->jsonFile([
            'spieltage' => [
                [
                    'spieltag' => 1,
                    'spiele' => [
                        ['datum' => '2026-09-24', 'heim' => 'Niederlande', 'gast' => 'Deutschland'],
                        ['datum' => '2026-09-25', 'heim' => 'Österreich', 'gast' => 'Italien'],
                    ],
                ],
            ],
        ]);

        $result = $this->service()->analyzeMatchroundsFile($leagueId, $file);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto']['matches']);
        $this->assertSame((int) $otherHome->team_id, $result['auto']['matches'][0]['match_hometeam_id']);
        $this->assertSame((int) $otherGuest->team_id, $result['auto']['matches'][0]['match_guestteam_id']);
        $this->assertStringContainsString('1 Spiel bereit zum Anlegen', $result['message']);
        $this->assertStringContainsString('1 bereits vorhanden und ausgeblendet', $result['message']);
    }

    #[Test]
    public function analyze_errors_when_a_team_plays_twice_in_same_spieltag(): void
    {
        $leagueId = $this->seedLeagueWithRound();
        foreach (['Niederlande', 'Deutschland', 'Österreich'] as $name) {
            Team::query()->create([
                'team_foreign_id' => '',
                'team_name' => $name,
                'team_nationality' => '',
                'team_avg_price' => 5,
                'team_num_players' => 0,
                'team_status' => 1,
            ]);
        }

        $file = $this->jsonFile([
            'spieltage' => [
                [
                    'spieltag' => 1,
                    'spiele' => [
                        ['datum' => '2026-09-24', 'heim' => 'Niederlande', 'gast' => 'Deutschland'],
                        ['datum' => '2026-09-24', 'heim' => 'Österreich', 'gast' => 'Niederlande'],
                    ],
                ],
            ],
        ]);

        $result = $this->service()->analyzeMatchroundsFile($leagueId, $file);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Spieltag 1', $result['errors'][0]);
        $this->assertStringContainsString('Niederlande', $result['errors'][0]);
        $this->assertStringContainsString('mehrfach', $result['errors'][0]);
    }

    #[Test]
    public function create_rejects_team_already_in_round(): void
    {
        $this->seedLeagueWithRound();
        $roundId = (int) Matchround::query()->value('matchround_id');
        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $other = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'aut',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2026-09-24 00:00:00',
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_status' => '',
            'match_homescore' => '-1',
            'match_guestscore' => '-1',
            'match_homescore_penalty' => '-1',
            'match_guestscore_penalty' => '-1',
            'match_minutes' => 0,
            'match_url' => '',
        ]);

        $result = $this->service()->create([
            'match_round' => $roundId,
            'match_date' => '2026-09-25',
            'match_hometeam_id' => (int) $other->team_id,
            'match_guestteam_id' => (int) $home->team_id,
            'match_status' => '',
        ]);

        $this->assertFalse($result['ok']);
        $this->assertNotEmpty($result['errors']);
        $this->assertStringContainsString('Niederlande', $result['errors'][0]);
        $this->assertStringContainsString('bereits', $result['errors'][0]);
    }

    #[Test]
    public function create_skips_identical_existing_matches(): void
    {
        $leagueId = $this->seedLeagueWithRound();
        $roundId = (int) Matchround::query()->value('matchround_id');
        $home = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $guest = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $other = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Österreich',
            'team_nationality' => 'aut',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $fourth = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Italien',
            'team_nationality' => 'ita',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2026-09-24 00:00:00',
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_status' => '',
            'match_homescore' => '-1',
            'match_guestscore' => '-1',
            'match_homescore_penalty' => '-1',
            'match_guestscore_penalty' => '-1',
            'match_minutes' => 0,
            'match_url' => '',
        ]);

        $result = $this->service()->createMatchesFromDraft([
            [
                'match_round' => $roundId,
                'match_date' => '2026-09-24',
                'match_hometeam_id' => (int) $home->team_id,
                'match_guestteam_id' => (int) $guest->team_id,
                'match_status' => '',
                'home_name' => 'Niederlande',
                'guest_name' => 'Deutschland',
            ],
            [
                'match_round' => $roundId,
                'match_date' => '2026-09-25',
                'match_hometeam_id' => (int) $other->team_id,
                'match_guestteam_id' => (int) $fourth->team_id,
                'match_status' => '',
                'home_name' => 'Österreich',
                'guest_name' => 'Italien',
            ],
        ], $leagueId);

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('1 Spiel hinzugefügt', $result['message']);
        $this->assertStringContainsString('1 bereits vorhanden und übersprungen', $result['message']);
        $this->assertSame(2, MatchGame::query()->count());
        $this->assertDatabaseHas('ffb_match', [
            'match_hometeam_id' => (int) $other->team_id,
            'match_guestteam_id' => (int) $fourth->team_id,
            'match_date' => '2026-09-25 00:00:00',
        ]);
    }

    private function service(): AdminMatchService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);

        return new AdminMatchService($adminCenter, new AdminTeamService($adminCenter));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function jsonFile(array $payload): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'plan.json',
            json_encode($payload, JSON_THROW_ON_ERROR),
        );
    }

    private function seedLeagueWithRound(): int
    {
        $league = League::query()->create([
            'league_title' => 'Nations League',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Runde 1',
            'matchround_startdate' => '2026-09-20 00:00:00',
            'matchround_enddate' => '2026-09-30 00:00:00',
            'matchround_status' => 1,
        ]);

        return (int) $league->league_id;
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->string('league_symbol')->default('');
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
            $table->double('team_avg_price')->default(5);
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round');
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
            $table->string('match_homescore')->default('-1');
            $table->string('match_guestscore')->default('-1');
            $table->string('match_homescore_penalty')->default('-1');
            $table->string('match_guestscore_penalty')->default('-1');
            $table->string('match_date')->nullable();
            $table->integer('match_minutes')->default(0);
            $table->string('match_status')->default('');
            $table->string('match_url')->default('');
        });
    }
}
