<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * League logos live at images/ffb/leagues/<asset_key>.<ext>.
 */
final class LeagueSymbol
{
    public const DEFAULT = 'na.png';

    /** @var list<string> */
    private const EXTENSIONS = ['png', 'webp', 'jpg', 'jpeg', 'gif'];

    /**
     * Public URL for a league logo (falls back to na.png).
     */
    public static function url(?string $leagueKey): string
    {
        $relative = self::relativePath($leagueKey);
        if ($relative !== null) {
            return '/images/ffb/'.$relative;
        }

        return '/images/ffb/leagues/'.self::DEFAULT;
    }

    /**
     * Whether a custom (non-default) logo file exists for the league.
     */
    public static function exists(?string $leagueKey): bool
    {
        return self::relativePath($leagueKey) !== null;
    }

    /**
     * Absolute path of the current logo file for a league, if any.
     */
    public static function path(?string $leagueKey): ?string
    {
        $relative = self::relativePath($leagueKey);
        if ($relative === null) {
            return null;
        }

        return self::absolutePath($relative);
    }

    /**
     * Store an uploaded logo as leagues/<asset_key>.<ext>, replacing any prior extension.
     */
    public static function store(?string $leagueKey, UploadedFile $file): bool
    {
        if (! AssetKey::isValid($leagueKey)) {
            return false;
        }

        $dir = self::leaguesDir();
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return false;
        }

        $ext = strtolower((string) $file->getClientOriginalExtension());
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }
        if (! in_array($ext, self::EXTENSIONS, true)) {
            $ext = 'png';
        }

        self::deleteForLeague($leagueKey);

        try {
            $file->move($dir, $leagueKey.'.'.$ext);
        } catch (\Throwable) {
            return false;
        }

        return is_file($dir.DIRECTORY_SEPARATOR.$leagueKey.'.'.$ext);
    }

    /**
     * Delete all leagues/<asset_key>.* logo variants.
     */
    public static function deleteForLeague(?string $leagueKey): void
    {
        if (! AssetKey::isValid($leagueKey)) {
            return;
        }

        $dir = self::leaguesDir();
        if (! is_dir($dir)) {
            return;
        }

        foreach (self::EXTENSIONS as $ext) {
            $path = $dir.DIRECTORY_SEPARATOR.$leagueKey.'.'.$ext;
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    public static function leaguesDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'leagues';
    }

    private static function relativePath(?string $leagueKey): ?string
    {
        if (! AssetKey::isValid($leagueKey)) {
            return null;
        }

        foreach (self::EXTENSIONS as $ext) {
            $relative = 'leagues/'.$leagueKey.'.'.$ext;
            if (self::fileExists($relative)) {
                return $relative;
            }
        }

        return null;
    }

    private static function fileExists(string $relativeUnderFfb): bool
    {
        return is_file(self::absolutePath($relativeUnderFfb));
    }

    private static function absolutePath(string $relativeUnderFfb): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativeUnderFfb);
    }
}
