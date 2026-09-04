<?php

declare(strict_types=1);

namespace FFB\Tests\Contract;

use FFB\Tests\Support\XmlApiClient;
use FFB\Tests\Support\XmlContractTestCase;

final class MyTeamAndScoreContractTest extends XmlContractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loginAsTester();
    }

    public function testGetPastAndRunningMatchrounds(): void
    {
        $response = $this->client->getXml('ffb/matchround/getPastAndRunningMatchrounds.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'matchround_id',
            'matchround_title',
            'matchround_startdate',
            'matchround_enddate',
            'matchround_actual',
            'matchround_running',
            'matches',
        ]);
    }

    public function testGetUsersWithTeams(): void
    {
        $response = $this->client->postXml('ffb/user/getUsersWithTeams.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        // May be empty if nobody saved a lineup yet; document must still parse.
        $this->assertNotNull($xml->documentElement);
    }

    public function testGetUserscoresForRound(): void
    {
        $response = $this->client->postXml('ffb/userscore/getUserscoresForRound.xml', [
            'matchround_id' => $this->env('FFB_TEST_MATCHROUND_ID'),
            'sort_flag' => 'score',
            'sort_dir' => 'desc',
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testGetUserscore(): void
    {
        $response = $this->client->getXml('ffb/userscore/getUserscore.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testGetPastMatchroundsV2(): void
    {
        $response = $this->client->getXml('ffb/matchround/getPastMatchrounds_v2.xml');
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }

    public function testGetBestTeam(): void
    {
        $response = $this->client->postXml('ffb/bestteam/getBestTeam.xml', [
            'matchround_id' => $this->env('FFB_TEST_PAST_MATCHROUND_ID'),
            'type' => 'top',
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        $this->assertNotNull($xml->documentElement);
    }
}
