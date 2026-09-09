<?php

namespace App\Support;

/**
 * Unified nationality / club emblem icon rendering.
 *
 * National teams (FIFA / legacy 3-letter codes) use vendored flag-icons SVGs.
 * Club / custom emblems fall back to images/ffb/flags/{code}.gif.
 */
final class Flag
{
    /**
     * Normalize a nationality / emblem code to uppercase FIFA-style form.
     */
    public static function normalize(?string $code): string
    {
        $code = strtoupper(trim((string) $code));

        if ($code === '' || $code === '0' || $code === 'NA') {
            return '';
        }

        return $code;
    }

    /**
     * ISO / regional code used by flag-icons, or null when GIF fallback applies.
     */
    public static function iso(?string $code): ?string
    {
        $normalized = self::normalize($code);
        if ($normalized === '') {
            return null;
        }

        /** @var array<string, string> $map */
        $map = config('flag_iso', []);
        $iso = $map[$normalized] ?? null;

        return is_string($iso) && $iso !== '' ? $iso : null;
    }

    /**
     * Root-relative SVG URL for a mapped nationality, or null for GIF fallback.
     */
    public static function svgUrl(?string $code, ?string $base = null): ?string
    {
        $iso = self::iso($code);
        if ($iso === null) {
            return null;
        }

        $prefix = $base ?? '/';
        $prefix = rtrim($prefix, '/').'/';

        return $prefix.'vendor/flag-icons/flags/4x3/'.$iso.'.svg';
    }

    /**
     * Absolute or root-relative GIF path for a nationality / club emblem code.
     *
     * Prefer {@see html()} for display; this remains for admin icon pickers and assets.
     */
    public static function imageUrl(?string $code, ?string $base = null): string
    {
        $raw = strtolower(trim((string) $code));
        if ($raw === '' || $raw === '0') {
            $raw = 'na';
        }

        $prefix = $base ?? '/';
        $prefix = rtrim($prefix, '/').'/';

        return $prefix.'images/ffb/flags/'.$raw.'.gif';
    }

    /**
     * Safe HTML for a flag / emblem icon (SVG via CSS when mapped, else GIF).
     *
     * @param  array{title?: string, base?: string, class?: string}  $options
     */
    public static function html(?string $code, array $options = []): string
    {
        $title = (string) ($options['title'] ?? '');
        $extraClass = trim((string) ($options['class'] ?? ''));
        $titleAttr = $title !== '' ? ' title="'.e($title).'"' : '';
        $base = $options['base'] ?? null;

        $svgUrl = self::svgUrl($code, $base);
        if ($svgUrl !== null) {
            $class = trim('ffb-flag ffb-flag-svg '.$extraClass);

            return '<span class="'.e($class).'" style="--ffb-flag:url(\''.e($svgUrl).'\')" role="img" aria-hidden="true"'.$titleAttr.'></span>';
        }

        $class = trim('ffb-flag ffb-flag-img '.$extraClass);
        $url = self::imageUrl($code, $base);

        return '<img class="'.e($class).'" src="'.e($url).'" alt="" width="16" height="11" loading="lazy"'.$titleAttr.'>';
    }
}
