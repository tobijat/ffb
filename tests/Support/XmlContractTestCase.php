<?php

declare(strict_types=1);

namespace FFB\Tests\Support;

use PHPUnit\Framework\TestCase;

abstract class XmlContractTestCase extends TestCase
{
    protected XmlApiClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new XmlApiClient;
    }

    protected function env(string $key, ?string $default = null): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === false || $value === null || $value === '') {
            if ($default === null) {
                $this->fail('Missing required env '.$key);
            }

            return $default;
        }

        return (string) $value;
    }

    protected function loginAsTester(): void
    {
        $response = $this->client->login(
            $this->env('FFB_TEST_USER'),
            $this->env('FFB_TEST_PASSWORD')
        );

        $this->assertSame(200, $response['status'], 'Platform login HTTP failed: '.$response['body']);
        $this->assertIsArray($response['json'], 'Platform login did not return JSON: '.$response['body']);
        $this->assertSame(200, (int) ($response['json']['status'] ?? 0), 'Platform login failed: '.$response['body']);
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
