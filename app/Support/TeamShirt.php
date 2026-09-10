<?php

namespace App\Support;

/**
 * Team shirt paths: per team folder, nationality default + optional league override.
 *
 * Default: shirts/{team_id}/{nationality}.png
 * League:  shirts/{team_id}/{nationality}-.{game_id}.png
 */
final class TeamShirt
{
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
    public static function url(int $teamId, ?string $nationality, ?int $gameId = null): ?string
    {
        $relative = self::relativePath($teamId, $nationality, $gameId);
        if ($relative === null) {
            return null;
        }

        return '/images/ffb/'.$relative;
    }

    /**
     * Relative path under images/ffb, or null when no file exists.
     */
    public static function relativePath(int $teamId, ?string $nationality, ?int $gameId = null): ?string
    {
        $nat = self::normalizeNationality($nationality);
        if ($teamId <= 0 || $nat === '') {
            return null;
        }

        if ($gameId !== null && $gameId > 0) {
            $league = 'shirts/'.$teamId.'/'.$nat.'-.'.$gameId.'.png';
            if (self::fileExists($league)) {
                return $league;
            }
        }

        $default = 'shirts/'.$teamId.'/'.$nat.'.png';
        if (self::fileExists($default)) {
            return $default;
        }

        return null;
    }

    /**
     * Absolute filesystem path for the default (non-league) shirt file.
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
     */
    public static function leagueStoragePath(int $teamId, ?string $nationality, int $gameId): string
    {
        $nat = self::normalizeNationality($nationality);

        return self::shirtsDir()
            .DIRECTORY_SEPARATOR.$teamId
            .DIRECTORY_SEPARATOR.$nat.'-.'.$gameId.'.png';
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
    public static function leaguePublicPath(int $teamId, ?string $nationality, int $gameId): string
    {
        $nat = self::normalizeNationality($nationality);

        return '/images/ffb/shirts/'.$teamId.'/'.$nat.'-.'.$gameId.'.png';
    }

    public static function blankUrl(bool $red = false): string
    {
        return '/images/ffb/shirts/'.($red ? 'shirt_BLANK_RED.png' : 'shirt_BLANK.png');
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
            $names[] = 'shirt_'.$variant.'.png';
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
