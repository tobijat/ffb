<?php

declare(strict_types=1);

namespace FFB\Tests\Support;

use DOMDocument;
use PHPUnit\Framework\TestCase;

abstract class XmlContractTestCase extends TestCase
{
    protected XmlApiClient $client;
    protected static ?XmlApiClient $sharedAuthedClient = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new XmlApiClient();
    }

    protected function env(string $key, ?string $default = null): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === false || $value === null || $value === '') {
            if ($default === null) {
                $this->fail('Missing required env ' . $key);
            }
            return $default;
        }
        return (string)$value;
    }

    protected function loginAsTester(): DOMDocument
    {
        $response = $this->client->login(
            $this->env('FFB_TEST_USER'),
            $this->env('FFB_TEST_PASSWORD')
        );
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['administration_status']);
        $status = XmlApiClient::firstTagValue($xml, 'administration_status');
        $this->assertSame('200', $status, 'Login failed: ' . $response['body']);
        return $xml;
    }

    protected function loginAsAdminTester(): void
    {
        $this->loginAsTester();
        // Admin game selection required by most administration/*.xml endpoints.
        $response = $this->client->postXml('administration/game/setSelectedGame.xml', [
            'game_id' => $this->env('FFB_TEST_GAME_ID'),
        ]);
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['administration_status']);
        $this->assertSame('200', XmlApiClient::firstTagValue($xml, 'administration_status'), $response['body']);
    }
}
