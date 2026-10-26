<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Fetches random / seeded shirts from the Kit Kitchen random.php endpoint.
 * Optionally POSTs a config SVG body to import an existing shirt design.
 *
 * @phpstan-type KitOptions array{
 *   seed?: string|null,
 *   stars?: int|null,
 *   number?: string|null,
 *   maker?: string|null,
 *   backside?: bool,
 *   text?: string|null,
 *   mcolor?: string|null,
 *   ncolor?: string|null,
 *   tcolor?: string|null,
 *   scolor?: string|null,
 *   svg_body?: string|null
 * }
 * @phpstan-type KitQueryOptions array{
 *   maker?: string,
 *   stars?: int,
 *   number?: string,
 *   text?: string,
 *   mcolor?: string,
 *   ncolor?: string,
 *   tcolor?: string,
 *   scolor?: string
 * }
 * @phpstan-type KitResult array{seed: string, svg: string, query: KitQueryOptions}
 */
final class KitKitchenClient
{
    /**
     * Kit Kitchen maker ids => UI labels (random.php?maker=).
     *
     * @var array<string, string>
     */
    public const MAKERS = [
        'adidas' => 'Adidas',
        'puma' => 'Puma',
        'nike' => 'Nike',
        'reebok' => 'Reebok',
        'hummel' => 'Hummel',
        'jako' => 'Jako',
        'kelme' => 'Kelme',
        'marathon' => 'Marathon',
        'umbro' => 'Umbro',
        'capelli' => 'Capelli Sport',
        'saeta' => 'Saeta',
        'kappa' => 'Kappa',
        'merooj' => 'Merooj',
        'sudu' => 'Sudo',
        'legea' => 'Legea',
        'castore' => 'Castore',
        'joma' => 'Joma',
        'macron' => 'Macron',
    ];

    public static function normalizeMaker(?string $maker): ?string
    {
        $maker = strtolower(trim((string) $maker));
        if ($maker === '' || ! array_key_exists($maker, self::MAKERS)) {
            return null;
        }

        return $maker;
    }

    /**
     * Normalize a Kit Kitchen colour query value to #rrggbb, or null if invalid/empty.
     * Accepts ff0000, #ff0000, f00, #f00 (as random.php does).
     */
    public static function normalizeColor(mixed $color): ?string
    {
        if (! is_string($color) && ! is_numeric($color)) {
            return null;
        }
        $color = strtolower(trim((string) $color));
        if ($color === '' || ! preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/', $color, $match)) {
            return null;
        }
        $hex = $match[1];
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return '#'.$hex;
    }

    /**
     * @param  KitOptions  $options
     * @return KitResult|null
     */
    public function fetch(array $options = []): ?array
    {
        $base = trim((string) config('ffb.kit_kitchen_random_url'));
        if ($base === '') {
            return null;
        }

        $query = $this->buildQuery($options);
        $url = $base;
        if ($query !== []) {
            $url .= (str_contains($base, '?') ? '&' : '?').http_build_query($query);
        }

        $svgBody = trim((string) ($options['svg_body'] ?? ''));

        try {
            $request = Http::timeout(20)->accept('image/svg+xml');
            if (! config('ffb.http.verify_ssl', true)) {
                $request = $request->withOptions(['verify' => false]);
            }
            $response = $svgBody !== ''
                ? $request->withBody($svgBody, 'image/svg+xml')->post($url)
                : $request->get($url);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $svg = trim((string) $response->body());
        if (! CustomAvatarSvg::isAcceptable($svg)) {
            return null;
        }

        $normalizedSeed = CustomAvatarSvg::normalizeSeed($options['seed'] ?? null);
        $headerSeed = CustomAvatarSvg::normalizeSeed($response->header('X-Kit-Seed'));
        if ($headerSeed === null) {
            $headerSeed = $this->seedFromContentDisposition((string) $response->header('Content-Disposition'));
        }
        if ($headerSeed === null) {
            $headerSeed = $normalizedSeed;
        }
        if ($headerSeed === null) {
            return null;
        }

        return [
            'seed' => $headerSeed,
            'svg' => $svg,
            'query' => self::optionsFromQueryHeader($response->header('X-Kit-Query')),
        ];
    }

    /**
     * Parse kit-kitchen's X-Kit-Query response header (echo of the effective request query).
     *
     * @return KitQueryOptions
     */
    public static function optionsFromQueryHeader(?string $header): array
    {
        $header = trim((string) $header);
        if ($header === '') {
            return [];
        }
        if (str_starts_with($header, '?')) {
            $header = substr($header, 1);
        }

        $parsed = [];
        parse_str($header, $parsed);
        if (! is_array($parsed) || $parsed === []) {
            return [];
        }

        $out = [];

        $maker = self::normalizeMaker(isset($parsed['maker']) ? (string) $parsed['maker'] : null);
        if ($maker !== null) {
            $out['maker'] = $maker;
        }

        if (isset($parsed['stars']) && is_numeric($parsed['stars'])) {
            $stars = (int) $parsed['stars'];
            if ($stars >= 0 && $stars <= 9) {
                $out['stars'] = $stars;
            }
        }

        $number = trim((string) ($parsed['number'] ?? ''));
        if (preg_match('/^\d{1,2}$/', $number)) {
            $out['number'] = $number;
        }

        if (array_key_exists('text', $parsed)) {
            $text = preg_replace('/[\x00-\x1F\x7F]+/u', '', trim((string) $parsed['text'])) ?? '';
            if (function_exists('mb_substr')) {
                $text = mb_substr($text, 0, 16);
            } else {
                $text = substr($text, 0, 16);
            }
            $out['text'] = $text;
        }

        foreach (['mcolor', 'ncolor', 'tcolor', 'scolor'] as $colorKey) {
            $normalized = self::normalizeColor($parsed[$colorKey] ?? null);
            if ($normalized !== null) {
                $out[$colorKey] = $normalized;
            }
        }

        return $out;
    }

    /**
     * @param  KitOptions  $options
     * @return array<string, scalar>
     */
    public function buildQuery(array $options): array
    {
        $query = [];

        $seed = CustomAvatarSvg::normalizeSeed($options['seed'] ?? null);
        if ($seed !== null) {
            $query['seed'] = $seed;
        }

        if (array_key_exists('stars', $options) && $options['stars'] !== null) {
            $stars = (int) $options['stars'];
            if ($stars >= 0 && $stars <= 9) {
                $query['stars'] = $stars;
            }
        }

        $number = trim((string) ($options['number'] ?? ''));
        if (preg_match('/^\d{1,2}$/', $number)) {
            $query['number'] = $number;
        }

        $maker = self::normalizeMaker($options['maker'] ?? null);
        if ($maker !== null) {
            $query['maker'] = $maker;
        }

        $backside = (bool) ($options['backside'] ?? false);
        if ($backside) {
            $query['backside'] = 1;
            $text = preg_replace('/[\x00-\x1F\x7F]+/u', '', trim((string) ($options['text'] ?? ''))) ?? '';
            if (function_exists('mb_substr')) {
                $text = mb_substr($text, 0, 16);
            } else {
                $text = substr($text, 0, 16);
            }
            if ($text !== '') {
                $query['text'] = $text;
            }
        }

        foreach (['mcolor', 'ncolor', 'tcolor', 'scolor'] as $colorKey) {
            $normalized = self::normalizeColor($options[$colorKey] ?? null);
            if ($normalized !== null) {
                // random.php accepts with or without #; omit # so query strings stay simple.
                $query[$colorKey] = substr($normalized, 1);
            }
        }

        return $query;
    }

    private function seedFromContentDisposition(string $header): ?string
    {
        if ($header === '') {
            return null;
        }

        if (! preg_match('/filename="?kit-(\d{1,10})\.svg"?/i', $header, $match)) {
            return null;
        }

        return CustomAvatarSvg::normalizeSeed($match[1]);
    }
}
