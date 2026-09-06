<?php

declare(strict_types=1);

namespace FFB\Tests\Contract;

use FFB\Tests\Support\XmlApiClient;
use FFB\Tests\Support\XmlContractTestCase;

final class PlayerAndPollContractTest extends XmlContractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loginAsTester();
    }

    public function testGetPlayerInfo(): void
    {
        $response = $this->client->postXml('ffb/player/getPlayerInfo.xml', [
            'playerteam_id' => $this->env('FFB_TEST_PLAYERTEAM_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'player_fname',
            'player_lname',
            'player_team_name',
            'player_team_nationality',
            'player_nationality',
            'player_picture',
            'num_lineups',
            'sum_score',
            'av_score',
            'sum_goals',
            'av_goals',
        ]);
    }

    public function testGetPlayerStats(): void
    {
        $response = $this->client->postXml('ffb/player/getPlayerStats.xml', [
            'playerteam_id' => $this->env('FFB_TEST_PLAYERTEAM_ID'),
            'matchround_id' => $this->env('FFB_TEST_PAST_MATCHROUND_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'played',
            'player_fname',
            'player_lname',
            'player_team_name',
            'player_team_nationality',
            'player_nationality',
            'player_picture',
            'playerstats_goals',
            'playerstats_minutes',
            'playerstats_minute_in',
            'playerstats_minute_out',
            'playerstats_score_minutes',
        ]);
    }

    public function testGetSelectPollById(): void
    {
        $response = $this->client->postXml('ffb/poll/getSelectPollById.xml', [
            'poll_id' => $this->env('FFB_TEST_SELECT_POLL_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, [
            'select_poll',
            'poll_id',
            'poll_title',
            'poll_type',
            'poll_end',
            'poll_answers',
            'poll_answer_id',
            'poll_answer_title',
            'poll_next_poll_id',
            'poll_prev_poll_id',
        ]);
        $this->assertSame(
            $this->env('FFB_TEST_SELECT_POLL_ID'),
            XmlApiClient::firstTagValue($xml, 'poll_id')
        );
    }

    public function testSavePollSelectAnswer(): void
    {
        $response = $this->client->postXml('ffb/poll/savePollSelectAnswer.xml', [
            'poll_id' => $this->env('FFB_TEST_SELECT_POLL_ID'),
            'poll_answer_id' => $this->env('FFB_TEST_SELECT_POLL_ANSWER_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['status']);
        $this->assertSame('200', XmlApiClient::firstTagValue($xml, 'status'));
    }

    public function testSavePollTextAnswer(): void
    {
        $response = $this->client->postXml('ffb/poll/savePollTextAnswer.xml', [
            'poll_id' => $this->env('FFB_TEST_TEXT_POLL_ID'),
            'poll_answer_id' => $this->env('FFB_TEST_TEXT_POLL_ANSWER_ID'),
            'poll_answer' => 'Contract test text answer ' . time(),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['status']);
        $this->assertSame('200', XmlApiClient::firstTagValue($xml, 'status'));
    }
}
