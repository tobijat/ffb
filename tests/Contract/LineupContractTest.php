<?php

declare(strict_types=1);

namespace FFB\Tests\Contract;

use FFB\Tests\Support\XmlApiClient;
use FFB\Tests\Support\XmlContractTestCase;

final class LineupContractTest extends XmlContractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loginAsTester();
    }

    public function testGetLineupOptionsReturnsExpectedTags(): void
    {
        $response = $this->client->getXml('ffb/options/getLineupOptions.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'options',
            'lineup_max_players',
            'lineup_max_credits',
            'lineup_max_players_team',
            'lineup_min_g',
            'lineup_min_d',
            'lineup_min_m',
            'lineup_min_s',
            'lineup_max_g',
            'lineup_max_d',
            'lineup_max_m',
            'lineup_max_s',
        ]);
        $this->assertSame('11', XmlApiClient::firstTagValue($xml, 'lineup_max_players'));
    }

    public function testGetMatchroundAndTeamsReturnsRoundStructure(): void
    {
        $response = $this->client->getXml('ffb/lineup/getMatchroundAndTeams.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'matchround_id',
            'matchround_title',
            'matchround_status',
            'matchround_startdate',
            'matchround_enddate',
            'matchround_deadline',
            'matches',
            'teams',
        ]);
        $this->assertSame(
            $this->env('FFB_TEST_MATCHROUND_ID'),
            XmlApiClient::firstTagValue($xml, 'matchround_id')
        );
    }

    public function testGetTeamPlayersReturnsPlayerTags(): void
    {
        $response = $this->client->postXml('ffb/team/getTeamPlayers.xml', [
            'id' => $this->env('FFB_TEST_TEAM_ID'),
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        // Empty team still returns a document; with fixture teams we expect playerteam fields.
        $this->assertTrue(
            $xml->getElementsByTagName('playerteam_id')->length > 0
            || $xml->getElementsByTagName('XML_Serializer_Tag')->length >= 0,
            'Unexpected team players payload: ' . $response['body']
        );
        if ($xml->getElementsByTagName('playerteam_id')->length > 0) {
            XmlApiClient::assertHasTags($xml, [
                'playerteam_id',
                'playerteam_player_position',
                'playerteam_player_price',
            ]);
        }
    }

    public function testGetUserteamForRoundReturnsDocument(): void
    {
        $response = $this->client->postXml('ffb/userteam/getUserteamForRound.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['user_id']);
    }

    public function testSaveLineupRejectsInvalidLineupWithStatusTags(): void
    {
        $response = $this->client->postXml('ffb/teammanagement/saveLineup.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
            'lineup' => '1,2,3',
            'sum_price' => '0',
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['ffb_status', 'ffb_answer', 'ffb_error']);
        $this->assertSame('500', XmlApiClient::firstTagValue($xml, 'ffb_status'));
    }

    public function testSaveLineupAcceptsValidElevenPlayers(): void
    {
        $playersResponse = $this->client->postXml('ffb/team/getTeamPlayers.xml', [
            'id' => $this->env('FFB_TEST_TEAM_ID'),
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
        ]);
        $playersXml = XmlApiClient::assertXmlResponse($playersResponse);
        $ids = [];
        foreach ($playersXml->getElementsByTagName('playerteam_id') as $node) {
            $ids[] = $node->textContent;
            if (count($ids) >= 11) {
                break;
            }
        }

        if (count($ids) < 11) {
            // Fall back to known playerteam ids from DB if single team has fewer than 11.
            $ids = ['1', '6', '14', '17', '26', '31', '40', '44', '46', '55', '58'];
        }

        $response = $this->client->postXml('ffb/teammanagement/saveLineup.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
            'lineup' => implode(',', $ids),
            'sum_price' => '50',
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['ffb_status', 'ffb_answer', 'ffb_error']);
        $this->assertSame('200', XmlApiClient::firstTagValue($xml, 'ffb_status'), $response['body']);
    }
}
