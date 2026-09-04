<?php

declare(strict_types=1);

namespace FFB\Tests\Contract;

use FFB\Tests\Support\XmlApiClient;
use FFB\Tests\Support\XmlContractTestCase;

final class AdminContractTest extends XmlContractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loginAsAdminTester();
    }

    public function testGetGameListForAdmin(): void
    {
        $response = $this->client->getXml('administration/game/getGameList.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['game_id', 'game_title']);
    }

    public function testGetGamesForAdmin(): void
    {
        $response = $this->client->getXml('administration/game/getGamesForAdmin.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['game_id', 'game_title', 'num_results']);
        $this->assertGreaterThan(0, (int)XmlApiClient::firstTagValue($xml, 'num_results'));
    }

    public function testCheckSelectedGame(): void
    {
        $response = $this->client->getXml('administration/game/checkSelectedGame.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['administration_status']);
        $this->assertSame('200', XmlApiClient::firstTagValue($xml, 'administration_status'));
    }

    public function testMatchroundGetList(): void
    {
        $response = $this->client->getXml('administration/matchround/getList.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['matchround_id', 'matchround_title']);
    }

    public function testGetMatchesForRound(): void
    {
        $response = $this->client->postXml('administration/match/getMatchesForRound.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['match_id']);
    }

    public function testTeamGetList(): void
    {
        $response = $this->client->getXml('administration/team/getList.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['team_id', 'team_name']);
    }

    public function testPlayertoteamGetTeamPlayers(): void
    {
        $response = $this->client->postXml('administration/playertoteam/getTeamPlayers.xml', [
            'team_id' => $this->env('FFB_TEST_TEAM_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testPlayerGetPartList(): void
    {
        $response = $this->client->postXml('administration/player/getPartList.xml', [
            'player_search' => 'a',
            'player_limit' => '20',
            'player_sort' => 'name',
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['player_id', 'player_fname', 'player_lname']);
    }

    public function testMatchpointsGetPlayerStatsForTeam(): void
    {
        $response = $this->client->postXml('administration/matchpoints/getPlayerStatsForTeam.xml', [
            'match_id' => $this->env('FFB_TEST_MATCH_ID'),
            'team_id' => $this->env('FFB_TEST_TEAM_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testAwardsGetAwards(): void
    {
        $response = $this->client->postXml('administration/awards/getAwards.xml', [
            'user_award_id' => $this->env('FFB_TEST_AWARD_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['userAward', 'id', 'name', 'userAwardDefines', 'userAwardCounts']);
    }

    public function testMailserviceGetGameList(): void
    {
        $response = $this->client->getXml('administration/mailservice/getGameList.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['numResults']);
    }

    public function testMailserviceGetMatchroundList(): void
    {
        $response = $this->client->postXml('administration/mailservice/getMatchroundList.xml', [
            'game_id' => $this->env('FFB_TEST_GAME_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['numResults']);
    }
}
