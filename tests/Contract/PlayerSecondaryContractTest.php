<?php

declare(strict_types=1);

namespace FFB\Tests\Contract;

use FFB\Tests\Support\XmlApiClient;
use FFB\Tests\Support\XmlContractTestCase;

final class PlayerSecondaryContractTest extends XmlContractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loginAsTester();
    }

    public function testGetMatchData(): void
    {
        $response = $this->client->postXml('ffb/matchdata/getMatchData.xml', [
            'match_id' => $this->env('FFB_TEST_MATCH_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'match_data',
            'match_id',
            'match_hometeam_id',
            'match_guestteam_id',
            'match_hometeam_name',
            'match_guestteam_name',
            'hometeam_players',
            'guestteam_players',
        ]);
    }

    public function testGetNewsList(): void
    {
        $response = $this->client->postXml('ffb/news/getNewsList.xml', [
            'selected_site' => '0',
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testGetPolls(): void
    {
        $response = $this->client->getXml('ffb/poll/getPolls.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['text_poll', 'select_poll']);
    }

    public function testGetUserDetails(): void
    {
        $response = $this->client->postXml('ffb/user/getUserDetails.xml', [
            'user_id' => $this->env('FFB_TEST_USER_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'user',
            'user_id',
            'user_nickname',
            'user_ownprofile',
            'participations',
        ]);
        $this->assertSame($this->env('FFB_TEST_USER_ID'), XmlApiClient::firstTagValue($xml, 'user_id'));
    }

    public function testGetAllUserAwards(): void
    {
        $response = $this->client->postXml('ffb/awards/getAllUserAwards.xml', [
            'user_id' => $this->env('FFB_TEST_USER_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testGameListAndCheckSelected(): void
    {
        $list = $this->client->getXml('ffb/game/getGameList.xml');
        $listXml = XmlApiClient::assertXmlResponse($list);
        $this->assertGreaterThan(0, $listXml->getElementsByTagName('game_id')->length + $listXml->getElementsByTagName('XML_Serializer_Tag')->length);

        $check = $this->client->getXml('ffb/game/checkSelectedGame.xml');
        $checkXml = XmlApiClient::assertXmlResponse($check);
        XmlApiClient::assertHasTags($checkXml, ['administration_status', 'selected_game_id']);
        $this->assertSame('200', XmlApiClient::firstTagValue($checkXml, 'administration_status'));
    }

    public function testGetPastGames(): void
    {
        $response = $this->client->getXml('ffb/game/getPastGames.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testStatisticsEndpoints(): void
    {
        $userStats = XmlApiClient::assertXmlResponse($this->client->postXml('ffb/statistics/getUserStats.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
            'user_id' => '0',
        ]));
        $this->assertNotNull($userStats->documentElement);

        $roundStats = XmlApiClient::assertXmlResponse($this->client->postXml('ffb/statistics/getRoundStats.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
        ]));
        $this->assertNotNull($roundStats->documentElement);
    }
}
