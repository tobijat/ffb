<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Fetches random / seeded shirts from the Kit Kitchen random.php endpoint.
 *
 * @phpstan-type KitOptions array{
 *   seed?: string|null,
 *   stars?: int|null,
 *   number?: string|null,
 *   maker?: string|null,
 *   backside?: bool,
 *   text?: string|null
 * }
 * @phpstan-type KitResult array{seed: string, svg: string}
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

        try {
            $request = Http::timeout(20)->accept('image/svg+xml');
            if (! config('ffb.http.verify_ssl', true)) {
                $request = $request->withOptions(['verify' => false]);
            }
            $response = $request->get($url);
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
        ];
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
        if (preg_match('/^\d{2}$/', $number)) {
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
