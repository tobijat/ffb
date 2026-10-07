<?php

namespace App\Support;

/**
 * Team shirt paths: per team folder, nationality default + optional league override.
 *
 * Prefers SVG over PNG (same order as public/js/ffb-shirts.js):
 *   shirts/{team_id}/{nationality}-{league_id}.svg
 *   shirts/{team_id}/{nationality}.svg
 *   shirts/{team_id}/{nationality}-{league_id}.png
 *   shirts/{team_id}/{nationality}.png
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
    public static function url(int $teamId, ?string $nationality, ?int $leagueId = null): ?string
    {
        $relative = self::relativePath($teamId, $nationality, $leagueId);
        if ($relative === null) {
            return null;
        }

        return '/images/ffb/'.$relative;
    }

    /**
     * Relative path under images/ffb, or null when no file exists.
     */
    public static function relativePath(int $teamId, ?string $nationality, ?int $leagueId = null): ?string
    {
        $nat = self::normalizeNationality($nationality);
        if ($teamId <= 0 || $nat === '') {
            return null;
        }

        $stem = 'shirts/'.$teamId.'/'.$nat;
        $hasLeague = $leagueId !== null && $leagueId > 0;

        // Same order as public/js/ffb-shirts.js candidates().
        $candidates = [];
        if ($hasLeague) {
            $candidates[] = $stem.'-'.$leagueId.'.svg';
        }
        $candidates[] = $stem.'.svg';
        if ($hasLeague) {
            $candidates[] = $stem.'-'.$leagueId.'.png';
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
    public static function defaultStoragePath(int $teamId, ?string $nationality): string
    {
        $nat = self::normalizeNationality($nationality);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamId
            .DIRECTORY_SEPARATOR.$nat.'.png';
    }

    /**
     * Absolute filesystem path for a league-specific shirt file.
     * Upload target remains PNG; existence checks use relativePath().
     */
    public static function leagueStoragePath(int $teamId, ?string $nationality, int $leagueId): string
    {
        $nat = self::normalizeNationality($nationality);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamId
            .DIRECTORY_SEPARATOR.$nat.'-'.$leagueId.'.png';
    }

    /**
     * Public URL path for the default shirt (may not exist on disk yet).
     */
    public static function defaultPublicPath(int $teamId, ?string $nationality): string
    {
        $nat = self::normalizeNationality($nationality);

        return '/images/ffb/shirts/'.$teamId.'/'.$nat.'.png';
    }

    /**
     * Public URL path for a league shirt (may not exist on disk yet).
     */
    public static function leaguePublicPath(int $teamId, ?string $nationality, int $leagueId): string
    {
        $nat = self::normalizeNationality($nationality);

        return '/images/ffb/shirts/'.$teamId.'/'.$nat.'-'.$leagueId.'.png';
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
