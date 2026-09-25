<?php

namespace Tests\Feature;

use App\Services\UefaCompApiClient;
use App\Services\UefaCompetitionApi;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UefaCompetitionApiTest extends TestCase
{
    #[Test]
    public function from_identifier_requires_competition_and_season(): void
    {
        $this->expectException(InvalidArgumentException::class);
        UefaCompetitionApi::fromIdentifier('competitionId=2014');
    }

    #[Test]
    public function from_identifier_parses_phases(): void
    {
        $api = UefaCompetitionApi::fromIdentifier(
            'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT,QUALIFYING'
        );

        $this->assertSame(2014, $api->competitionId);
        $this->assertSame(2027, $api->seasonYear);
        $this->assertSame(['TOURNAMENT', 'QUALIFYING'], $api->phases);
    }

    #[Test]
    public function teams_filters_rounds_by_phase_and_maps_payload(): void
    {
        config(['services.uefa.base_url' => 'https://comp.uefa.test/v2']);
        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                [
                    'phase' => 'QUALIFYING',
                    'orderInCompetition' => 1,
                    'teams' => ['999'],
                ],
                [
                    'phase' => 'TOURNAMENT',
                    'orderInCompetition' => 2,
                    'teams' => ['47', '88'],
                ],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'internationalName' => 'Germany',
                    'translations' => [
                        'countryName' => [
                            'DE' => 'Deutschland',
                            'EN' => 'Germany',
                        ],
                    ],
                ],
                [
                    'id' => '88',
                    'teamCode' => 'MLT',
                    'countryCode' => 'MLT',
                    'internationalName' => 'Malta',
                    'translations' => [
                        'countryName' => [
                            'DE' => 'Malta',
                            'EN' => 'Malta',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $api = UefaCompetitionApi::fromIdentifier(
            'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
            new UefaCompApiClient,
        );

        $teams = $api->teams();

        $this->assertCount(2, $teams);
        $this->assertSame('47', $teams[0]['uefa_id']);
        $this->assertSame('Deutschland', $teams[0]['name_de']);
        $this->assertSame('GER', $teams[0]['team_code']);
        $ids = array_column($teams, 'uefa_id');
        $this->assertNotContains('999', $ids);
    }

    #[Test]
    public function teams_without_phase_includes_all_rounds(): void
    {
        config(['services.uefa.base_url' => 'https://comp.uefa.test/v2']);
        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'QUALIFYING', 'teams' => ['1']],
                ['phase' => 'TOURNAMENT', 'teams' => ['2']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '1',
                    'teamCode' => 'AAA',
                    'countryCode' => 'AAA',
                    'internationalName' => 'A',
                    'translations' => ['countryName' => ['DE' => 'A', 'EN' => 'A']],
                ],
                [
                    'id' => '2',
                    'teamCode' => 'BBB',
                    'countryCode' => 'BBB',
                    'internationalName' => 'B',
                    'translations' => ['countryName' => ['DE' => 'B', 'EN' => 'B']],
                ],
            ], 200),
        ]);

        $api = UefaCompetitionApi::fromIdentifier(
            'competitionId=17&seasonYear=2026',
            new UefaCompApiClient,
        );

        $this->assertCount(2, $api->teams());
    }

    #[Test]
    public function teams_skips_fake_team_type_detail(): void
    {
        config(['services.uefa.base_url' => 'https://comp.uefa.test/v2']);
        Http::preventStrayRequests();
        Http::fake([
            'comp.uefa.test/v2/rounds*' => Http::response([
                ['phase' => 'TOURNAMENT', 'teams' => ['47', '9001']],
            ], 200),
            'comp.uefa.test/v2/teams*' => Http::response([
                [
                    'id' => '47',
                    'teamCode' => 'GER',
                    'countryCode' => 'GER',
                    'teamTypeDetail' => 'NATIONAL_MEN_TEAM_A',
                    'internationalName' => 'Germany',
                    'translations' => ['countryName' => ['DE' => 'Deutschland', 'EN' => 'Germany']],
                ],
                [
                    'id' => '9001',
                    'teamCode' => 'TBD',
                    'countryCode' => 'TBD',
                    'teamTypeDetail' => 'FAKE',
                    'internationalName' => 'Placeholder',
                    'translations' => ['countryName' => ['DE' => 'Platzhalter', 'EN' => 'Placeholder']],
                ],
            ], 200),
        ]);

        $api = UefaCompetitionApi::fromIdentifier(
            'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
            new UefaCompApiClient,
        );

        $teams = $api->teams();

        $this->assertCount(1, $teams);
        $this->assertSame('47', $teams[0]['uefa_id']);
    }

    #[Test]
    public function matches_filters_by_phase_and_maps_payload(): void
    {
        config([
            'services.uefa.base_url' => 'https://comp.uefa.test/v2',
            'services.uefa.match_base_url' => 'https://match.uefa.test/v5',
        ]);
        Http::preventStrayRequests();
        Http::fake([
            'match.uefa.test/v5/matches*' => Http::response([
                [
                    'id' => '10',
                    'competitionPhase' => 'QUALIFYING',
                    'homeTeam' => [
                        'id' => '1',
                        'internationalName' => 'A',
                        'isPlaceHolder' => false,
                        'translations' => ['countryName' => ['DE' => 'A', 'EN' => 'A']],
                    ],
                    'awayTeam' => [
                        'id' => '2',
                        'internationalName' => 'B',
                        'isPlaceHolder' => false,
                        'translations' => ['countryName' => ['DE' => 'B', 'EN' => 'B']],
                    ],
                    'kickOffTime' => ['date' => '2026-03-01'],
                    'matchday' => ['sequenceNumber' => '1', 'phase' => 'QUALIFYING'],
                    'round' => ['phase' => 'QUALIFYING', 'orderInCompetition' => 1],
                ],
                [
                    'id' => '11',
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
                    'kickOffTime' => ['date' => '2026-09-24', 'dateTime' => '2026-09-24T16:00:00Z'],
                    'matchday' => ['sequenceNumber' => '1', 'phase' => 'TOURNAMENT'],
                    'round' => ['phase' => 'TOURNAMENT', 'orderInCompetition' => 1],
                ],
            ], 200),
        ]);

        $api = UefaCompetitionApi::fromIdentifier(
            'competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT',
            new UefaCompApiClient,
        );

        $matches = $api->matches();
        $this->assertCount(1, $matches);
        $this->assertSame('11', $matches[0]['uefa_match_id']);
        $this->assertSame('47', $matches[0]['home_uefa_id']);
        $this->assertSame('88', $matches[0]['away_uefa_id']);
        $this->assertSame('Deutschland', $matches[0]['home_name_de']);
        $this->assertSame('2026-09-24', $matches[0]['date']);
        $this->assertSame(1, $matches[0]['matchday']);
        $this->assertSame('TOURNAMENT', $matches[0]['round_phase']);
    }
}
