<?php

namespace App\Services;

use App\Exceptions\CloudflareChallengeException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Scrapes weltfussball.at/.de spielbericht pages (lineup-graphical / hs-lineup markup).
 */
class WeltfussballMatchScraper
{
    public function __construct(
        private readonly WeltfussballProxyService $proxy,
    ) {
    }

    /**
     * @return array{
     *     match_minutes: int,
     *     result_text: string,
     *     home: list<array<string, mixed>>,
     *     guest: list<array<string, mixed>>
     * }
     */
    public function fetchAndParse(string $url, ?string $cookieHeader = null): array
    {
        $html = $this->fetch($url, $cookieHeader);

        return $this->parse($html);
    }

    /**
     * @return array{
     *     match_minutes: int,
     *     result_text: string,
     *     home: list<array<string, mixed>>,
     *     guest: list<array<string, mixed>>
     * }
     */
    public function parse(string $html): array
    {
        $content = $this->normalizeString($html);

        if ($this->proxy->looksLikeChallenge($content)) {
            throw new CloudflareChallengeException(
                '',
                'Cloudflare-Challenge noch nicht gelöst. Bitte die Challenge im Frame lösen und danach erneut laden.'
            );
        }

        if (! $this->proxy->looksLikeMatchReport($content)) {
            throw new RuntimeException('Kein gültiger Weltfussball-Spielbericht erkannt.');
        }

        $dom = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$content);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new \DOMXPath($dom);

        $resultText = $this->parseResultText($xpath, $content);
        $matchMinutes = (str_contains($resultText, 'n.V.') || str_contains($resultText, 'i.E.') || str_contains($content, 'n.V.') || str_contains($content, 'i.E.'))
            ? 120
            : 90;

        [$assistList, $goalListFromEvents, $owngoalListFromEvents] = $this->parseGoalEvents($xpath);

        $home = [];
        $guest = [];

        foreach ($xpath->query('//div[contains(@class,"event") and contains(@class,"playing") and (contains(@class,"lineup") or contains(@class,"bench"))]') as $node) {
            if (! $node instanceof \DOMElement) {
                continue;
            }
            $class = ' '.$node->getAttribute('class').' ';
            $isHome = str_contains($class, ' none_home ');
            $isAway = str_contains($class, ' none_away ');
            if (! $isHome && ! $isAway) {
                continue;
            }
            $isBench = str_contains($class, ' bench ');
            $isLineup = str_contains($class, ' lineup ');
            if (! $isBench && ! $isLineup) {
                continue;
            }

            $player = $this->parsePlayerEvent(
                $xpath,
                $node,
                $matchMinutes,
                $isLineup,
                $assistList,
                $goalListFromEvents,
                $owngoalListFromEvents
            );
            if ($player === null) {
                continue;
            }

            if ($isHome) {
                $home[] = $player;
            } else {
                $guest[] = $player;
            }
        }

        if ($home === [] && $guest === []) {
            throw new RuntimeException('Kein gültiger Weltfussball-Spielbericht erkannt.');
        }

        return [
            'match_minutes' => $matchMinutes,
            'result_text' => $resultText,
            'home' => $home,
            'guest' => $guest,
        ];
    }

    private function parseResultText(\DOMXPath $xpath, string $content): string
    {
        $nodes = $xpath->query('//*[contains(@class,"match-result")]');
        $score = '';
        if ($nodes !== false && $nodes->length > 0) {
            $score = trim(preg_replace('/\s+/', '', $nodes->item(0)?->textContent ?? '') ?? '');
        }
        if ($score === '' && preg_match('/class="[^"]*match-result[^"]*"[^>]*>([^<]+)/', $content, $m)) {
            $score = trim($m[1]);
        }

        $extra = '';
        if (str_contains($content, 'n.V.')) {
            $extra = ' n.V.';
        } elseif (str_contains($content, 'i.E.')) {
            $extra = ' i.E.';
        }

        return trim($score.$extra);
    }

    /**
     * @return array{
     *     0: array<string, array{num: int}>,
     *     1: array<string, array{num: int, minutes: string}>,
     *     2: array<string, array{num: int, minutes: string}>
     * }
     */
    private function parseGoalEvents(\DOMXPath $xpath): array
    {
        $assistList = [];
        $goalList = [];
        $owngoalList = [];

        $items = $xpath->query('//li[contains(@class,"event") and contains(@class,"goal")]');
        if ($items === false) {
            return [$assistList, $goalList, $owngoalList];
        }

        foreach ($items as $item) {
            if (! $item instanceof \DOMElement) {
                continue;
            }
            $class = ' '.$item->getAttribute('class').' ';
            $isOwn = str_contains($class, 'own-goal') || str_contains($class, 'own_goal') || str_contains($class, 'eigentor');

            $scorerNodes = $xpath->query('.//div[contains(@class,"person-name")]//a', $item);
            $scorer = trim((string) ($scorerNodes->item(0)?->textContent ?? ''));
            if ($scorer === '') {
                continue;
            }
            $minuteNodes = $xpath->query('.//*[contains(@class,"match_event-minute")]', $item);
            $minute = $this->parseMinuteToken((string) ($minuteNodes->item(0)?->textContent ?? ''));

            $key = md5($scorer);
            if (! $isOwn) {
                if (! isset($goalList[$key])) {
                    $goalList[$key] = ['num' => 1, 'minutes' => $minute !== null ? (string) $minute : ''];
                } else {
                    $goalList[$key]['num']++;
                    if ($minute !== null) {
                        $goalList[$key]['minutes'] = trim($goalList[$key]['minutes'].';'.$minute, ';');
                    }
                }
            } else {
                if (! isset($owngoalList[$key])) {
                    $owngoalList[$key] = ['num' => 1, 'minutes' => $minute !== null ? (string) $minute : ''];
                } else {
                    $owngoalList[$key]['num']++;
                    if ($minute !== null) {
                        $owngoalList[$key]['minutes'] = trim($owngoalList[$key]['minutes'].';'.$minute, ';');
                    }
                }
            }

            $assistNodes = $xpath->query('.//*[contains(@class,"person-additional")]//a | .//div[contains(@class,"person-shortname") and contains(@class,"person-additional")]//a', $item);
            if ($assistNodes !== false && $assistNodes->length > 0) {
                $assistName = trim((string) $assistNodes->item(0)->textContent);
                if ($assistName !== '') {
                    $assistKey = md5($assistName);
                    if (! isset($assistList[$assistKey])) {
                        $assistList[$assistKey] = ['num' => 1];
                    } else {
                        $assistList[$assistKey]['num']++;
                    }
                }
            }
        }

        return [$assistList, $goalList, $owngoalList];
    }

    /**
     * @param  array<string, array{num: int}>  $assistList
     * @param  array<string, array{num: int, minutes: string}>  $goalListFromEvents
     * @param  array<string, array{num: int, minutes: string}>  $owngoalListFromEvents
     * @return array<string, mixed>|null
     */
    private function parsePlayerEvent(
        \DOMXPath $xpath,
        \DOMElement $node,
        int $matchMinutes,
        bool $isLineup,
        array $assistList,
        array $goalListFromEvents,
        array $owngoalListFromEvents,
    ): ?array {
        $nameNodes = $xpath->query('.//div[contains(@class,"person-name")]//a', $node);
        $name = trim((string) ($nameNodes->item(0)?->textContent ?? ''));
        if ($name === '') {
            return null;
        }
        $indexName = md5($name);

        $rawIn = 0;
        $rawOut = 0;
        $goalMinutes = [];
        $owngoalMinutes = [];
        $cards = '0';

        foreach ($xpath->query('.//div[contains(@class,"match_event")]', $node) as $event) {
            if (! $event instanceof \DOMElement) {
                continue;
            }
            $eventClass = ' '.$event->getAttribute('class').' ';
            $inner = $xpath->query('.//div', $event)->item(0);
            $innerClass = $inner instanceof \DOMElement ? (' '.$inner->getAttribute('class').' ') : '';
            $token = trim((string) ($inner?->textContent ?? $event->textContent));
            $minute = $this->parseMinuteToken($token);

            if (str_contains($eventClass, 'match_event-playing') || str_contains($innerClass, 'substitute-')) {
                if (str_contains($innerClass, 'substitute-in') && $minute !== null) {
                    $rawIn = $minute;
                }
                if (str_contains($innerClass, 'substitute-out') && $minute !== null) {
                    $rawOut = $minute;
                }
            }

            if (str_contains($eventClass, 'match_event-goal') || str_contains($innerClass, ' goal ')) {
                $isOwn = str_contains($innerClass, 'own-goal') || str_contains($innerClass, 'own_goal') || str_contains($innerClass, 'eigentor');
                if ($minute !== null) {
                    if ($isOwn) {
                        $owngoalMinutes[] = (string) $minute;
                    } else {
                        $goalMinutes[] = (string) $minute;
                    }
                }
            }

            if (str_contains($eventClass, 'match_event-card') || str_contains($innerClass, ' card ')) {
                if (str_contains($innerClass, 'yellow-red') || str_contains($innerClass, 'yellow_red') || str_contains($innerClass, 'gelbrot')) {
                    $cards = 'YR';
                    if ($minute !== null) {
                        $rawOut = $rawOut ?: $minute;
                    }
                } elseif (str_contains($innerClass, ' red') || str_contains($innerClass, 'rote')) {
                    $cards = 'R';
                    if ($minute !== null) {
                        $rawOut = $rawOut ?: $minute;
                    }
                } elseif (str_contains($innerClass, 'yellow') || str_contains($innerClass, 'gelb')) {
                    if ($cards === '0') {
                        $cards = 'Y';
                    }
                }
            }
        }

        // Unused bench players have no substitute-in.
        if (! $isLineup && $rawIn <= 0) {
            return null;
        }

        if ($goalMinutes === [] && isset($goalListFromEvents[$indexName])) {
            $goalMinutes = array_values(array_filter(explode(';', $goalListFromEvents[$indexName]['minutes'])));
        }
        if ($owngoalMinutes === [] && isset($owngoalListFromEvents[$indexName])) {
            $owngoalMinutes = array_values(array_filter(explode(';', $owngoalListFromEvents[$indexName]['minutes'])));
        }

        [$in, $out, $minutes] = $this->finalizePlayingWindow($rawIn, $rawOut, $matchMinutes);

        return [
            'player_name' => $name,
            'player_change_in' => $in,
            'player_change_out' => $out,
            'player_cards' => $cards,
            'player_num_goals' => count($goalMinutes),
            'player_goal' => $goalMinutes === [] ? '0' : implode(';', $goalMinutes),
            'player_num_owngoals' => count($owngoalMinutes),
            'player_owngoal' => $owngoalMinutes === [] ? '0' : implode(';', $owngoalMinutes),
            'player_num_assists' => isset($assistList[$indexName]) ? (int) $assistList[$indexName]['num'] : 0,
            'player_penalties_hit' => 0,
            'player_penalties_fail' => 0,
            'player_minutes' => $minutes,
        ];
    }

    /**
     * Starters begin at minute 1; anyone not substituted out ends at full time.
     * Played minutes are inclusive: out - in + 1.
     *
     * @return array{0: int, 1: int, 2: int}
     */
    private function finalizePlayingWindow(int $rawIn, int $rawOut, int $matchMinutes): array
    {
        $in = $rawIn > 0 ? $rawIn : 1;
        $out = $rawOut > 0 ? $rawOut : $matchMinutes;
        if ($out < $in) {
            $out = $in;
        }
        $minutes = $out - $in + 1;
        if ($minutes > $matchMinutes) {
            $minutes = $matchMinutes;
        }

        return [$in, $out, $minutes];
    }

    private function parseMinuteToken(string $token): ?int
    {
        if (preg_match('/(\d{1,3})/', $token, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    private function fetch(string $url, ?string $cookieHeader = null): string
    {
        $url = $this->proxy->assertAllowedUrl($url);
        $parts = parse_url($url);
        $host = (string) ($parts['host'] ?? '');
        $origin = ($parts['scheme'] ?? 'https').'://'.$host;

        $jar = $this->proxy->loadJar();
        $this->proxy->mergeCookieHeader($jar, $cookieHeader, $host);
        $this->proxy->saveJar($jar);

        $headers = $this->proxy->browserHeaders();
        $options = [
            'version' => 2.0,
            'allow_redirects' => [
                'max' => 8,
                'track_redirects' => true,
            ],
            'cookies' => $jar,
            'decode_content' => true,
            'http_errors' => false,
        ];
        if (! config('ffb.http.verify_ssl', true)) {
            $options['verify'] = false;
        }

        $client = Http::timeout(45)->withOptions($options);

        // Warm-up only establishes cookies; never treat the homepage as the challenge signal.
        $client->withHeaders(array_merge($headers, [
            'Sec-Fetch-Site' => 'none',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-User' => '?1',
            'Sec-Fetch-Dest' => 'document',
        ]))->get($origin.'/');

        $response = $client->withHeaders(array_merge($headers, [
            'Sec-Fetch-Site' => 'same-origin',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-User' => '?1',
            'Sec-Fetch-Dest' => 'document',
            'Referer' => $origin.'/',
            'Cache-Control' => 'max-age=0',
        ]))->get($url);

        $this->proxy->saveJar($jar);

        $body = (string) $response->body();
        if ($this->proxy->looksLikeChallenge($body)) {
            throw new CloudflareChallengeException(
                $url,
                'Cloudflare-Challenge erforderlich. Bitte im Frame lösen und danach „Spieldaten laden“ erneut klicken.'
            );
        }

        if (! $response->successful()) {
            throw new RuntimeException('Externe Seite nicht erreichbar (HTTP '.$response->status().').');
        }

        return $body;
    }

    private function normalizeString(string $string): string
    {
        $string = str_replace("\t", '', trim($string));
        $string = str_replace("\r", '', trim($string));

        return str_replace("\n", '', trim($string));
    }
}
