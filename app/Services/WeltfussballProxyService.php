<?php

namespace App\Services;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Session-backed reverse proxy so Cloudflare challenges can be solved in an iframe
 * while cookies accumulate for the subsequent scrape.
 */
class WeltfussballProxyService
{
    public const SESSION_JAR_KEY = 'wf_proxy_cookie_jar';

    public const SESSION_LAST_HTML = 'wf_proxy_last_html';

    public const SESSION_LAST_URL = 'wf_proxy_last_url';

    private const ALLOWED_HOSTS = [
        'www.weltfussball.at',
        'weltfussball.at',
        'www.weltfussball.de',
        'weltfussball.de',
    ];

    public function assertAllowedUrl(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        if ($url === '' || ! preg_match('#^https?://#i', $url) || ! in_array($host, self::ALLOWED_HOSTS, true)) {
            throw new RuntimeException('Nur weltfussball.at/.de URLs sind erlaubt.');
        }

        return $url;
    }

    public function proxyUrl(string $targetUrl): string
    {
        return url('/admin/matchdata/wf-proxy').'?u='.rawurlencode($this->assertAllowedUrl($targetUrl));
    }

    public function loadJar(): CookieJar
    {
        $jar = new CookieJar();
        $stored = session(self::SESSION_JAR_KEY, []);
        if (! is_array($stored)) {
            return $jar;
        }
        foreach ($stored as $cookie) {
            if (! is_array($cookie) || empty($cookie['Name'])) {
                continue;
            }
            $jar->setCookie(new SetCookie($cookie));
        }

        return $jar;
    }

    public function saveJar(CookieJar $jar): void
    {
        $rows = [];
        foreach ($jar->toArray() as $cookie) {
            $rows[] = $cookie;
        }
        session([self::SESSION_JAR_KEY => $rows]);
    }

    public function mergeCookieHeader(CookieJar $jar, ?string $cookieHeader, string $host): void
    {
        $cookieHeader = trim((string) $cookieHeader);
        if ($cookieHeader === '') {
            return;
        }
        $domain = ltrim($host, '.');
        foreach (explode(';', $cookieHeader) as $part) {
            $part = trim($part);
            if ($part === '' || ! str_contains($part, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $part, 2);
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $jar->setCookie(new SetCookie([
                'Name' => $name,
                'Value' => trim($value),
                'Domain' => $domain,
                'Path' => '/',
                'Secure' => true,
            ]));
        }
    }

    /**
     * @return array{status: int, body: string, content_type: string, final_url: string}
     */
    public function upstream(string $url, string $method = 'GET', ?string $body = null, array $headers = []): array
    {
        $url = $this->assertAllowedUrl($url);
        $parts = parse_url($url);
        $origin = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '');
        $jar = $this->loadJar();

        $options = [
            'version' => 2.0,
            'allow_redirects' => false,
            'cookies' => $jar,
            'decode_content' => true,
            'http_errors' => false,
        ];
        if (! config('ffb.http.verify_ssl', true)) {
            $options['verify'] = false;
        }

        $reqHeaders = array_merge($this->browserHeaders(), $headers);
        $pending = Http::timeout(45)->withOptions($options)->withHeaders($reqHeaders);

        $method = strtoupper($method);
        $response = match ($method) {
            'POST' => $pending->withBody((string) $body, $headers['Content-Type'] ?? 'application/octet-stream')->post($url),
            'PUT' => $pending->withBody((string) $body, $headers['Content-Type'] ?? 'application/octet-stream')->put($url),
            default => $pending->get($url),
        };

        // Follow redirects manually so cookies stick in our jar.
        $hops = 0;
        while ($response->status() >= 300 && $response->status() < 400 && $hops < 8) {
            $location = (string) $response->header('Location');
            if ($location === '') {
                break;
            }
            $next = $this->absolutize($location, $origin, $url);
            $this->assertAllowedUrl($next);
            $response = Http::timeout(45)
                ->withOptions($options)
                ->withHeaders(array_merge($reqHeaders, ['Referer' => $url]))
                ->get($next);
            $url = $next;
            $hops++;
        }

        $this->saveJar($jar);

        return [
            'status' => $response->status(),
            'body' => (string) $response->body(),
            'content_type' => (string) ($response->header('Content-Type') ?: 'text/html; charset=UTF-8'),
            'final_url' => $url,
        ];
    }

    public function looksLikeMatchReport(string $html): bool
    {
        return str_contains($html, 'hs-lineup--starter')
            || str_contains($html, 'data-template="lineup-graphical"')
            || (
                str_contains($html, 'event playing lineup none_home')
                && str_contains($html, 'event playing lineup none_away')
            );
    }

    public function rememberHtmlIfMatchReport(string $url, string $html): void
    {
        if (! $this->looksLikeMatchReport($html) || $this->looksLikeChallenge($html)) {
            return;
        }

        session([
            self::SESSION_LAST_HTML => $html,
            self::SESSION_LAST_URL => $url,
        ]);
    }

    public function cachedMatchHtml(?string $expectedUrl = null): ?string
    {
        $html = session(self::SESSION_LAST_HTML);
        if (! is_string($html) || trim($html) === '') {
            return null;
        }
        if (! $this->looksLikeMatchReport($html) || $this->looksLikeChallenge($html)) {
            return null;
        }

        // URL is advisory only — frame may land on a redirected/canonical spielbericht URL.
        return $html;
    }

    public function handleBrowserRequest(Request $request): SymfonyResponse
    {
        $target = (string) $request->query('u', '');
        $target = $this->assertAllowedUrl($target);

        $forwardHeaders = [];
        if ($request->headers->has('Content-Type')) {
            $forwardHeaders['Content-Type'] = (string) $request->headers->get('Content-Type');
        }
        if ($request->headers->has('Accept')) {
            $forwardHeaders['Accept'] = (string) $request->headers->get('Accept');
        }

        $upstream = $this->upstream(
            $target,
            $request->method(),
            $request->getContent() !== false && $request->getContent() !== '' ? $request->getContent() : null,
            $forwardHeaders
        );

        $contentType = strtolower($upstream['content_type']);
        $rawBody = $upstream['body'];
        $body = $rawBody;

        if (str_contains($contentType, 'text/html') || str_contains($contentType, 'application/xhtml')) {
            $this->rememberHtmlIfMatchReport($upstream['final_url'], $rawBody);
            $body = $this->rewriteHtml($rawBody, $upstream['final_url']);
        } elseif (str_contains($contentType, 'javascript') || str_contains($contentType, 'ecmascript')) {
            $body = $this->rewriteJavascript($rawBody, $upstream['final_url']);
        }

        return response($body, $upstream['status'], [
            'Content-Type' => $upstream['content_type'],
            'Cache-Control' => 'no-store',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    }

    public function looksLikeChallenge(string $html): bool
    {
        // Real match pages must never be treated as a challenge,
        // even if they embed Cloudflare assets/scripts.
        if ($this->looksLikeMatchReport($html)) {
            return false;
        }

        return str_contains($html, 'Just a moment...')
            || str_contains($html, 'Enable JavaScript and cookies to continue')
            || str_contains($html, 'cf-browser-verification')
            || str_contains($html, 'Attention Required! | Cloudflare')
            || str_contains($html, 'id="challenge-form"')
            || str_contains($html, 'cf-challenge-running')
            || (str_contains($html, 'challenge-platform') && str_contains($html, 'cdn-cgi'));
    }

    /**
     * @return array<string, string>
     */
    public function browserHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language' => 'de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7',
            'Accept-Encoding' => 'gzip, deflate',
            'Upgrade-Insecure-Requests' => '1',
            'Sec-CH-UA' => '"Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
            'Sec-CH-UA-Mobile' => '?0',
            'Sec-CH-UA-Platform' => '"Windows"',
            'Connection' => 'keep-alive',
        ];
    }

    private function rewriteHtml(string $html, string $pageUrl): string
    {
        $parts = parse_url($pageUrl);
        $origin = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '');
        $proxyBase = url('/admin/matchdata/wf-proxy');

        $rewriteAttr = function (string $attr, string $value) use ($origin, $pageUrl, $proxyBase): string {
            $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
            if ($value === '' || str_starts_with($value, 'data:') || str_starts_with($value, 'javascript:') || str_starts_with($value, '#')) {
                return $attr.'="'.e($value).'"';
            }
            $absolute = $this->absolutize($value, $origin, $pageUrl);
            $host = strtolower((string) (parse_url($absolute, PHP_URL_HOST) ?? ''));
            if (in_array($host, self::ALLOWED_HOSTS, true)) {
                return $attr.'="'.e($proxyBase.'?u='.rawurlencode($absolute)).'"';
            }

            return $attr.'="'.e($value).'"';
        };

        $html = preg_replace_callback(
            '/\b(href|src|action)=([\'"])([^\'"]*)\2/i',
            static function (array $m) use ($rewriteAttr): string {
                $tmp = $rewriteAttr($m[1], $m[3]);

                // rewriteAttr returns attr="...", keep original quote style loosely
                return $tmp;
            },
            $html
        ) ?? $html;

        $inject = $this->bridgeScript($origin, $proxyBase, $pageUrl);
        if (stripos($html, '<head') !== false) {
            $html = preg_replace('/<head([^>]*)>/i', '<head$1>'.$inject, $html, 1) ?? ($inject.$html);
        } else {
            $html = $inject.$html;
        }

        return $html;
    }

    private function rewriteJavascript(string $js, string $pageUrl): string
    {
        // Leave third-party CF scripts alone; bridge handles same-origin fetches in HTML pages.
        return $js;
    }

    private function bridgeScript(string $origin, string $proxyBase, string $pageUrl): string
    {
        $originJson = json_encode($origin, JSON_UNESCAPED_SLASHES);
        $proxyJson = json_encode($proxyBase, JSON_UNESCAPED_SLASHES);
        $pageJson = json_encode($pageUrl, JSON_UNESCAPED_SLASHES);

        return <<<HTML
<script>
(function(){
  var ORIGIN = {$originJson};
  var PROXY = {$proxyJson};
  var PAGE = {$pageJson};
  function abs(url) {
    try { return new URL(url, PAGE).href; } catch (e) { return url; }
  }
  function wrap(url) {
    var full = abs(url);
    try {
      var u = new URL(full);
      if (u.origin === ORIGIN) return PROXY + '?u=' + encodeURIComponent(u.href);
    } catch (e) {}
    return full;
  }
  var xsrf = document.cookie.match(/(?:^|;\\s*)XSRF-TOKEN=([^;]+)/);
  var xsrfToken = xsrf ? decodeURIComponent(xsrf[1]) : '';
  var rawFetch = window.fetch;
  window.fetch = function(input, init) {
    init = init || {};
    init.credentials = init.credentials || 'same-origin';
    init.headers = init.headers || {};
    if (xsrfToken) {
      if (init.headers instanceof Headers) {
        init.headers.set('X-XSRF-TOKEN', xsrfToken);
        init.headers.set('X-Requested-With', 'XMLHttpRequest');
      } else {
        init.headers['X-XSRF-TOKEN'] = xsrfToken;
        init.headers['X-Requested-With'] = 'XMLHttpRequest';
      }
    }
    if (typeof input === 'string') input = wrap(input);
    else if (input && typeof Request !== 'undefined' && input instanceof Request) {
      input = new Request(wrap(input.url), input);
    }
    return rawFetch.call(this, input, init);
  };
  var open = XMLHttpRequest.prototype.open;
  XMLHttpRequest.prototype.open = function(method, url) {
    this.__ffbUrl = wrap(url);
    return open.apply(this, [method, this.__ffbUrl].concat([].slice.call(arguments, 2)));
  };
  var send = XMLHttpRequest.prototype.send;
  XMLHttpRequest.prototype.send = function(body) {
    if (xsrfToken) {
      try {
        this.setRequestHeader('X-XSRF-TOKEN', xsrfToken);
        this.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      } catch (e) {}
    }
    return send.apply(this, arguments);
  };
  function notifyParent() {
    try {
      var root = document.documentElement;
      var html = root ? root.outerHTML : '';
      var ready = html.indexOf('hs-lineup--starter') !== -1
        || html.indexOf('data-template="lineup-graphical"') !== -1
        || (html.indexOf('event playing lineup none_home') !== -1 && html.indexOf('event playing lineup none_away') !== -1);
      var challenge = !ready && (
        html.indexOf('Just a moment') !== -1 ||
        html.indexOf('cf-browser-verification') !== -1 ||
        html.indexOf('challenge-platform') !== -1
      );
      if (!ready && !challenge) return;
      if (ready) {
        if (window.__ffbWfReadySent) return;
        window.__ffbWfReadySent = true;
      } else {
        if (window.__ffbWfChallengeSent) return;
        window.__ffbWfChallengeSent = true;
      }
      if (window.parent && window.parent !== window) {
        window.parent.postMessage({
          type: 'ffb-wf-proxy',
          ready: !!ready,
          challenge: !!challenge,
          html: html
        }, '*');
      }
    } catch (e) {}
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', notifyParent);
  } else {
    notifyParent();
  }
  setTimeout(notifyParent, 800);
})();
</script>
HTML;
    }

    private function absolutize(string $url, string $origin, string $basePage): string
    {
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        if (str_starts_with($url, '//')) {
            $scheme = parse_url($origin, PHP_URL_SCHEME) ?: 'https';

            return $scheme.':'.$url;
        }
        if (str_starts_with($url, '/')) {
            return rtrim($origin, '/').$url;
        }
        $dir = preg_replace('#/[^/]*$#', '/', $basePage) ?: ($origin.'/');

        return $dir.$url;
    }
}
