<?php

namespace App\Support;

/**
 * Team shirt paths: per team folder, nationality default + optional league override.
 *
 * Prefers SVG over PNG (same order as public/js/ffb-shirts.js):
 *   shirts/{team_key}/{nationality}-{league_key}.svg
 *   shirts/{team_key}/{nationality}.svg
 *   shirts/{team_key}/{nationality}-{league_key}.png
 *   shirts/{team_key}/{nationality}.png
 */
final class TeamShirt
{
    /** @var list<string> */
    private const EXTENSIONS = ['svg', 'png'];

    public static function normalizeNationality(?string $nationality): string
    {
        $value = strtolower(trim((string) $nationality));
        $value = str_replace([' ', '.'], ['_', ''], $value);
        $value = preg_replace('/[^a-z0-9_-]+/', '', $value) ?? '';

        return $value;
    }

    /**
     * Public URL for a team shirt, preferring a league override when present.
     */
    public static function url(?string $teamKey, ?string $nationality, ?string $leagueKey = null): ?string
    {
        $relative = self::relativePath($teamKey, $nationality, $leagueKey);
        if ($relative === null) {
            return null;
        }

        return '/images/ffb/'.$relative;
    }

    /**
     * Public URL for an SVG team shirt only (league override, then default).
     */
    public static function svgUrl(?string $teamKey, ?string $nationality, ?string $leagueKey = null): ?string
    {
        $relative = self::svgRelativePath($teamKey, $nationality, $leagueKey);
        if ($relative === null) {
            return null;
        }

        return '/images/ffb/'.$relative;
    }

    /**
     * Relative path under images/ffb, or null when no file exists.
     */
    public static function relativePath(?string $teamKey, ?string $nationality, ?string $leagueKey = null): ?string
    {
        return self::firstExistingPath($teamKey, $nationality, $leagueKey, self::EXTENSIONS);
    }

    /**
     * Relative SVG path under images/ffb, or null when no SVG exists.
     */
    public static function svgRelativePath(?string $teamKey, ?string $nationality, ?string $leagueKey = null): ?string
    {
        return self::firstExistingPath($teamKey, $nationality, $leagueKey, ['svg']);
    }

    /**
     * @param  list<string>  $extensions
     */
    private static function firstExistingPath(
        ?string $teamKey,
        ?string $nationality,
        ?string $leagueKey,
        array $extensions,
    ): ?string {
        $nat = self::normalizeNationality($nationality);
        if (! AssetKey::isValid($teamKey) || $nat === '') {
            return null;
        }

        $stem = 'shirts/'.$teamKey.'/'.$nat;
        $hasLeague = AssetKey::isValid($leagueKey);

        $candidates = [];
        foreach ($extensions as $ext) {
            if ($hasLeague) {
                $candidates[] = $stem.'-'.$leagueKey.'.'.$ext;
            }
            $candidates[] = $stem.'.'.$ext;
        }

        foreach ($candidates as $relative) {
            if (self::fileExists($relative)) {
                return $relative;
            }
        }

        return null;
    }

    /**
     * Absolute filesystem path for the default (non-league) shirt file.
     *
     * @param  'svg'|'png'  $extension
     */
    public static function defaultStoragePath(string $teamKey, ?string $nationality, string $extension = 'png'): string
    {
        $nat = self::normalizeNationality($nationality);
        $ext = self::normalizeExtension($extension);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamKey
            .DIRECTORY_SEPARATOR.$nat.'.'.$ext;
    }

    /**
     * Absolute filesystem path for a league-specific shirt file.
     *
     * @param  'svg'|'png'  $extension
     */
    public static function leagueStoragePath(
        string $teamKey,
        ?string $nationality,
        string $leagueKey,
        string $extension = 'png',
    ): string {
        $nat = self::normalizeNationality($nationality);
        $ext = self::normalizeExtension($extension);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamKey
            .DIRECTORY_SEPARATOR.$nat.'-'.$leagueKey.'.'.$ext;
    }

    /**
     * Public URL path for the default shirt (may not exist on disk yet).
     *
     * @param  'svg'|'png'  $extension
     */
    public static function defaultPublicPath(string $teamKey, ?string $nationality, string $extension = 'png'): string
    {
        $nat = self::normalizeNationality($nationality);
        $ext = self::normalizeExtension($extension);

        return '/images/ffb/shirts/'.$teamKey.'/'.$nat.'.'.$ext;
    }

    /**
     * Public URL path for a league shirt (may not exist on disk yet).
     *
     * @param  'svg'|'png'  $extension
     */
    public static function leaguePublicPath(
        string $teamKey,
        ?string $nationality,
        string $leagueKey,
        string $extension = 'png',
    ): string {
        $nat = self::normalizeNationality($nationality);
        $ext = self::normalizeExtension($extension);

        return '/images/ffb/shirts/'.$teamKey.'/'.$nat.'-'.$leagueKey.'.'.$ext;
    }

    /**
     * @param  string  $extension
     * @return 'svg'|'png'
     */
    private static function normalizeExtension(string $extension): string
    {
        return strtolower(trim($extension)) === 'svg' ? 'svg' : 'png';
    }

    public static function blankUrl(bool $red = false): string
    {
        $stem = $red ? 'shirt_BLANK_RED' : 'shirt_BLANK';
        foreach (self::EXTENSIONS as $ext) {
            $relative = 'shirts/'.$stem.'.'.$ext;
            if (self::fileExists($relative)) {
                return '/images/ffb/'.$relative;
            }
        }

        return '/images/ffb/shirts/'.$stem.'.png';
    }

    /**
     * Legacy flat filename candidates for a nationality key.
     *
     * @return list<string>
     */
    public static function legacyFilenames(?string $nationality): array
    {
        $nat = self::normalizeNationality($nationality);
        if ($nat === '') {
            return [];
        }

        $names = [];
        foreach ([strtoupper($nat), $nat] as $variant) {
            foreach (self::EXTENSIONS as $ext) {
                $names[] = 'shirt_'.$variant.'.'.$ext;
            }
        }

        return array_values(array_unique($names));
    }

    public static function legacyPath(?string $nationality): ?string
    {
        $dir = self::shirtsDir();
        foreach (self::legacyFilenames($nationality) as $name) {
            $path = $dir.DIRECTORY_SEPARATOR.$name;
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function shirtsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'shirts';
    }

    /**
     * Absolute path to the kit-kitchen config SVG for a stored shirt avatar
     * (`shirts/{team}/{nat}[-league].svg` → `shirts-config/{nat}.svg`).
     */
    public static function configSvgPathFromShirtRelative(string $shirtRelative): ?string
    {
        $relative = str_replace('\\', '/', trim($shirtRelative));
        if (! UserAvatar::isShirtPath($relative)) {
            return null;
        }

        if (! preg_match('#^shirts/[^/]+/([a-z0-9_]+)(?:-[a-z0-9_-]+)?\.svg$#', $relative, $match)) {
            return null;
        }

        $nat = $match[1];
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');
        $path = $base.DIRECTORY_SEPARATOR.'shirts-config'.DIRECTORY_SEPARATOR.$nat.'.svg';

        return is_file($path) ? $path : null;
    }

    /**
     * Whether a relative path under images/ffb exists on disk.
     */
    public static function relativeExists(string $relativeUnderFfb): bool
    {
        $relative = str_replace('\\', '/', trim($relativeUnderFfb));
        if ($relative === '' || str_contains($relative, '..') || str_starts_with($relative, '/')) {
            return false;
        }

        return self::fileExists($relative);
    }

    private static function fileExists(string $relativeUnderFfb): bool
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return is_file($base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativeUnderFfb));
    }
}
