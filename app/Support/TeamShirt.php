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
     * Relative path under images/ffb, or null when no file exists.
     */
    public static function relativePath(?string $teamKey, ?string $nationality, ?string $leagueKey = null): ?string
    {
        $nat = self::normalizeNationality($nationality);
        if (! AssetKey::isValid($teamKey) || $nat === '') {
            return null;
        }

        $stem = 'shirts/'.$teamKey.'/'.$nat;
        $hasLeague = AssetKey::isValid($leagueKey);

        $candidates = [];
        if ($hasLeague) {
            $candidates[] = $stem.'-'.$leagueKey.'.svg';
        }
        $candidates[] = $stem.'.svg';
        if ($hasLeague) {
            $candidates[] = $stem.'-'.$leagueKey.'.png';
        }
        $candidates[] = $stem.'.png';

        foreach ($candidates as $relative) {
            if (self::fileExists($relative)) {
                return $relative;
            }
        }

        return null;
    }

    /**
     * Absolute filesystem path for the default (non-league) shirt file.
     * Upload target remains PNG; existence checks use relativePath().
     */
    public static function defaultStoragePath(string $teamKey, ?string $nationality): string
    {
        $nat = self::normalizeNationality($nationality);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamKey
            .DIRECTORY_SEPARATOR.$nat.'.png';
    }

    /**
     * Absolute filesystem path for a league-specific shirt file.
     * Upload target remains PNG; existence checks use relativePath().
     */
    public static function leagueStoragePath(string $teamKey, ?string $nationality, string $leagueKey): string
    {
        $nat = self::normalizeNationality($nationality);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamKey
            .DIRECTORY_SEPARATOR.$nat.'-'.$leagueKey.'.png';
    }

    /**
     * Public URL path for the default shirt (may not exist on disk yet).
     */
    public static function defaultPublicPath(string $teamKey, ?string $nationality): string
    {
        $nat = self::normalizeNationality($nationality);

        return '/images/ffb/shirts/'.$teamKey.'/'.$nat.'.png';
    }

    /**
     * Public URL path for a league shirt (may not exist on disk yet).
     */
    public static function leaguePublicPath(string $teamKey, ?string $nationality, string $leagueKey): string
    {
        $nat = self::normalizeNationality($nationality);

        return '/images/ffb/shirts/'.$teamKey.'/'.$nat.'-'.$leagueKey.'.png';
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

    private static function fileExists(string $relativeUnderFfb): bool
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return is_file($base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativeUnderFfb));
    }
}
