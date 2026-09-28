<?php

namespace Tests\Feature;

use App\Services\FifaCompApiClient;
use App\Services\FifaCompetitionApi;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FifaCompetitionApiMatchesTest extends TestCase
{
    #[Test]
    public function matches_maps_calendar_payload(): void
    {
        config(['services.fifa.base_url' => 'https://api.fifa.test/api/v3']);
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
                    [
                        'IdMatch' => '',
                        'IdStage' => '1',
                        'Date' => '2022-11-21T16:00:00Z',
                        'Home' => ['IdTeam' => '1'],
                        'Away' => ['IdTeam' => '2'],
                    ],
                ],
            ]),
        ]);

        $api = FifaCompetitionApi::fromIdentifier(
            'idCompetition=17&idSeason=255711',
            new FifaCompApiClient('https://api.fifa.test/api/v3'),
        );
        $matches = $api->matches();

        $this->assertCount(1, $matches);
        $this->assertSame('400128082', $matches[0]['fifa_match_id']);
        $this->assertSame('285063', $matches[0]['fifa_stage_id']);
        $this->assertSame('43834', $matches[0]['home_fifa_id']);
        $this->assertSame('43927', $matches[0]['away_fifa_id']);
        $this->assertSame('QAT', $matches[0]['home_abbr']);
        $this->assertSame('ECU', $matches[0]['away_abbr']);
        $this->assertSame('2022-11-20', $matches[0]['date']);
        $this->assertSame('Katar', $matches[0]['home_name_de']);
    }
}
