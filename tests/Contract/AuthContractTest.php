<?php

declare(strict_types=1);

namespace FFB\Tests\Contract;

use FFB\Tests\Support\XmlApiClient;
use FFB\Tests\Support\XmlContractTestCase;

final class AuthContractTest extends XmlContractTestCase
{
    public function testLoginAjaxSuccessReturnsDestination(): void
    {
        $xml = $this->loginAsTester();
        XmlApiClient::assertHasTags($xml, ['administration_destination']);
        $dest = XmlApiClient::firstTagValue($xml, 'administration_destination');
        $this->assertNotEmpty($dest);
    }

    public function testLoginAjaxRejectsBadPassword(): void
    {
        $response = $this->client->login($this->env('FFB_TEST_USER'), 'definitely-wrong-password');
        $xml = XmlApiClient::assertXmlResponse($response);
        XmlApiClient::assertHasTags($xml, ['administration_status', 'errors']);
        $this->assertSame('500', XmlApiClient::firstTagValue($xml, 'administration_status'));
        $this->assertGreaterThan(0, $xml->getElementsByTagName('XML_Serializer_Tag')->length);
    }

    public function testProtectedEndpointRedirectsWhenUnauthenticated(): void
    {
        $this->client->setFollowRedirects(false);
        $response = $this->client->getXml('ffb/options/getLineupOptions.xml');
        $this->assertContains($response['status'], [302, 301, 303], 'Expected redirect, body: ' . substr($response['body'], 0, 300));
    }
}
