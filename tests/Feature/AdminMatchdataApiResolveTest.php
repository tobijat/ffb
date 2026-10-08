<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Services\AdminMatchdataService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

class AdminMatchdataApiResolveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        config([
            'services.uefa.base_url' => 'https://comp.uefa.test/v2',
            'services.uefa.match_base_url' => 'https://match.uefa.test/v5',
            'services.fifa.base_url' => 'https://api.fifa.test/api/v3',
        ]);
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
    public function resolves_uefa_match_when_stored_kickoff_datetime_shares_berlin_calendar_day(): void
    {
        [, $roundId] = $this->seedLeagueWithUefa();
        $home = $this->createTeam('Deutschland', '47', 'GER');
        $guest = $this->createTeam('Malta', '88', 'MLT');

        $match = MatchGame::query()->create([
            'match_round' => $roundId,
            // Europe/Berlin kickoff stored after UEFA import (18:00 local = 16:00Z).
            'match_date' => '2026-09-24 18:00:00.000',
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
        $match->load(['matchround', 'homeTeam', 'guestTeam']);

        Http::preventStrayRequests();
        Http::fake([
            'match.uefa.test/v5/matches*' => Http::response([
                [
                    'id' => 'uefa-99',
                    'competitionPhase' => 'TOURNAMENT',
                    'homeTeam' => [
                        'id' => '47',
                        'internationalName' => 'Germany',
                        'isPlaceHolder' => false,
                        'translations' => ['countryName' => ['DE' => 'Deutschland', 'EN' => 'Germany']],
                    ],
                    'awayTeam' => [
                        'id' => '88',
                        'internationalName' => 'Malta',
                        'isPlaceHolder' => false,
                        'translations' => ['countryName' => ['DE' => 'Malta', 'EN' => 'Malta']],
                    ],
                    'kickOffTime' => [
                        'date' => '2026-09-24',
                        'dateTime' => '2026-09-24T16:00:00Z',
                    ],
                    'matchday' => ['sequenceNumber' => '1', 'phase' => 'TOURNAMENT'],
                    'round' => ['phase' => 'TOURNAMENT', 'orderInCompetition' => 1],
                ],
            ], 200),
        ]);

        $resolved = $this->invokeResolve($match, 'resolveUefaMatchId');

        $this->assertTrue($resolved['ok'] ?? false);
        $this->assertSame('uefa-99', $resolved['uefa_match_id'] ?? null);
    }

    #[Test]
    public function resolves_fifa_match_when_stored_kickoff_datetime_shares_berlin_calendar_day(): void
    {
        [, $roundId] = $this->seedLeagueWithFifa();
        $home = $this->createTeam('Katar', '', 'QAT', '43834');
        $guest = $this->createTeam('Ecuador', '', 'ECU', '43927');

        $match = MatchGame::query()->create([
            'match_round' => $roundId,
            'match_date' => '2022-11-20 17:00:00.000',
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
        $match->load(['matchround', 'homeTeam', 'guestTeam']);

        Http::preventStrayRequests();
        Http::fake([
            'api.fifa.test/api/v3/calendar/matches*' => Http::response([
                'Results' => [
                    [
                        'IdMatch' => '400128082',
                        'IdStage' => '285063',
                        'Date' => '2022-11-20T16:00:00Z',
                        'Home' => [
                            'IdTeam' => '43834',
                            'Abbreviation' => 'QAT',
                            'IdCountry' => 'QAT',
                            'TeamName' => [['Locale' => 'de-DE', 'Description' => 'Katar']],
                        ],
                        'Away' => [
                            'IdTeam' => '43927',
                            'Abbreviation' => 'ECU',
                            'IdCountry' => 'ECU',
                            'TeamName' => [['Locale' => 'de-DE', 'Description' => 'Ecuador']],
                        ],
                    ],
                ],
            ]),
        ]);

        $resolved = $this->invokeResolve($match, 'resolveFifaMatch');

        $this->assertTrue($resolved['ok'] ?? false);
        $this->assertSame('400128082', $resolved['fifa_match_id'] ?? null);
    }

    /**
     * @return array{ok: bool, uefa_match_id?: string, fifa_match_id?: string, errors?: list<string>}
     */
    private function invokeResolve(MatchGame $match, string $method): array
    {
        $service = app(AdminMatchdataService::class);
        $reflection = new ReflectionMethod(AdminMatchdataService::class, $method);

        /** @var array{ok: bool, uefa_match_id?: string, fifa_match_id?: string, errors?: list<string>} $result */
        $result = $reflection->invoke($service, $match);

        return $result;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedLeagueWithUefa(): array
    {
        $league = League::query()->create([
            'league_title' => 'UEFA Test',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_uefa_competition_identifier' => 'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
            'league_fifa_competition_identifier' => '',
        ]);

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => '2026-09-01 00:00:00',
            'matchround_enddate' => '2026-09-30 23:59:59',
            'matchround_status' => 1,
        ]);

        return [(int) $league->league_id, (int) $round->matchround_id];
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedLeagueWithFifa(): array
    {
        $league = League::query()->create([
            'league_title' => 'FIFA Test',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_uefa_competition_identifier' => '',
            'league_fifa_competition_identifier' => 'idCompetition=17&idSeason=255711',
        ]);

        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'R1',
            'matchround_startdate' => '2022-11-01 00:00:00',
            'matchround_enddate' => '2022-11-30 23:59:59',
            'matchround_status' => 1,
        ]);

        return [(int) $league->league_id, (int) $round->matchround_id];
    }

    private function createTeam(
        string $name,
        string $uefaId = '',
        string $code = '',
        string $fifaId = '',
    ): Team {
        return Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => $name,
            'team_nationality' => strtolower($code),
            'team_num_players' => 0,
            'team_status' => 1,
            'team_uefa_id' => $uefaId,
            'team_fifa_id' => $fifaId,
            'team_team_code' => $code,
        ]);
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
            $table->string('league_uefa_competition_identifier')->default('');
            $table->string('league_fifa_competition_identifier')->default('');
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
            $table->string('team_uefa_id')->default('');
            $table->string('team_fifa_id')->default('');
            $table->string('team_team_code')->default('');
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
