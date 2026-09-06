<?php

declare(strict_types=1);

namespace FFB\Tests\Support;

use DOMDocument;
use PHPUnit\Framework\Assert;

final class XmlApiClient
{
    private string $baseUrl;
    private string $cookieFile;
    private bool $followRedirects = false;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? TestServer::baseUrl(), '/').'/';
        $this->cookieFile = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb_xml_api_'.uniqid('', true).'.cookie';
        touch($this->cookieFile);
    }

    public function __destruct()
    {
        if (is_file($this->cookieFile)) {
            @unlink($this->cookieFile);
        }
    }

    public function setFollowRedirects(bool $follow): void
    {
        $this->followRedirects = $follow;
    }

    /**
     * @param  array<string, scalar|null>  $params
     * @return array{status:int, headers:array<string,string>, body:string, xml:?DOMDocument}
     */
    public function request(string $method, string $path, array $params = [], array $extraHeaders = []): array
    {
        $path = ltrim($path, '/');
        $url = $this->baseUrl.$path;

        $ch = curl_init();
        $method = strtoupper($method);

        if ($method === 'GET' && $params) {
            $url .= (str_contains($url, '?') ? '&' : '?').http_build_query($params);
        }

        $headers = array_merge([
            'X-Requested-With: XMLHttpRequest',
            'Accept: text/xml, application/xml, */*',
        ], $extraHeaders);

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_COOKIEJAR => $this->cookieFile,
            CURLOPT_COOKIEFILE => $this->cookieFile,
            CURLOPT_FOLLOWLOCATION => $this->followRedirects,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $raw = curl_exec($ch);
        if ($raw === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('HTTP request failed: '.$err);
        }

        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $headerBlob = substr($raw, 0, $headerSize);
        $body = substr($raw, $headerSize);
        $parsedHeaders = $this->parseHeaders($headerBlob);

        $xml = null;
        if ($body !== '' && (str_contains($parsedHeaders['content-type'] ?? '', 'xml') || str_starts_with(ltrim($body), '<?xml') || str_starts_with(ltrim($body), '<'))) {
            $xml = new DOMDocument;
            $previous = libxml_use_internal_errors(true);
            $loaded = $xml->loadXML($body);
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
            if (! $loaded) {
                $xml = null;
            }
        }

        return [
            'status' => $status,
            'headers' => $parsedHeaders,
            'body' => $body,
            'xml' => $xml,
        ];
    }

    /**
     * @param  array<string, scalar|null>  $params
     * @return array{status:int, headers:array<string,string>, body:string, xml:?DOMDocument}
     */
    public function getXml(string $path, array $params = []): array
    {
        return $this->request('GET', $path, $params);
    }

    /**
     * @param  array<string, scalar|null>  $params
     * @return array{status:int, headers:array<string,string>, body:string, xml:?DOMDocument}
     */
    public function postXml(string $path, array $params = []): array
    {
        return $this->request('POST', $path, $params);
    }

    /**
     * Authenticate via platform login (sets Laravel session + legacy PHPSESSID bridge).
     *
     * @return array{status:int, headers:array<string,string>, body:string, json:?array}
     */
    public function login(string $nickname, string $password): array
    {
        // Establish Laravel session + XSRF cookie.
        $this->request('GET', 'platform/public/', [], [
            'Accept: text/html',
        ]);

        $xsrf = $this->cookieValue('XSRF-TOKEN');
        $headers = [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        ];
        if ($xsrf !== null) {
            $headers[] = 'X-XSRF-TOKEN: '.rawurldecode($xsrf);
        }

        $response = $this->request('POST', 'platform/public/login', [
            'user_nickname' => $nickname,
            'user_password' => $password,
            'destination' => '',
        ], $headers);

        $json = null;
        if ($response['body'] !== '') {
            $decoded = json_decode($response['body'], true);
            if (is_array($decoded)) {
                $json = $decoded;
            }
        }

        return [
            'status' => $response['status'],
            'headers' => $response['headers'],
            'body' => $response['body'],
            'json' => $json,
        ];
    }

    public static function assertXmlResponse(array $response, int $expectedStatus = 200): DOMDocument
    {
        Assert::assertSame($expectedStatus, $response['status'], 'Unexpected HTTP status. Body: '.substr($response['body'], 0, 500));
        Assert::assertNotNull($response['xml'], 'Response was not valid XML. Body: '.substr($response['body'], 0, 500));
        $contentType = $response['headers']['content-type'] ?? '';
        Assert::assertStringContainsString('xml', strtolower($contentType), 'Expected XML Content-Type, got: '.$contentType);

        return $response['xml'];
    }

    /**
     * @param  list<string>  $tags
     */
    public static function assertHasTags(DOMDocument $xml, array $tags): void
    {
        foreach ($tags as $tag) {
            $nodes = $xml->getElementsByTagName($tag);
            Assert::assertGreaterThan(
                0,
                $nodes->length,
                sprintf('Expected XML tag <%s> missing. Document: %s', $tag, $xml->saveXML())
            );
        }
    }

    public static function firstTagValue(DOMDocument $xml, string $tag): ?string
    {
        $nodes = $xml->getElementsByTagName($tag);
        if ($nodes->length === 0 || $nodes->item(0) === null) {
            return null;
        }

        return $nodes->item(0)->textContent;
    }

    private function cookieValue(string $name): ?string
    {
        if (! is_file($this->cookieFile)) {
            return null;
        }

        $lines = file($this->cookieFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return null;
        }

        foreach ($lines as $line) {
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $parts = explode("\t", $line);
            if (count($parts) < 7) {
                continue;
            }
            if ($parts[5] === $name) {
                return $parts[6];
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    private function parseHeaders(string $headerBlob): array
    {
        $headers = [];
        foreach (explode("\r\n", $headerBlob) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }
            [$name, $value] = explode(':', $line, 2);
            $headers[strtolower(trim($name))] = trim($value);
        }

        return $headers;
    }
}
