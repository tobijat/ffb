<?php

namespace App\Support;

/**
 * Player picture paths: per (team_key, player_key), not per league/playerteam row.
 */
final class PlayerPicture
{
    /**
     * Public URL for a player image with fallbacks.
     */
    public static function url(?string $teamKey, ?string $playerKey): string
    {
        if (AssetKey::isValid($teamKey) && AssetKey::isValid($playerKey)) {
            $relative = 'players/'.$teamKey.'/'.$playerKey.'.jpg';
            if (self::fileExists($relative)) {
                return '/images/ffb/'.$relative;
            }
        }

        if (AssetKey::isValid($playerKey)) {
            $relative = 'players/'.$playerKey.'.jpg';
            if (self::fileExists($relative)) {
                return '/images/ffb/'.$relative;
            }
        }

        return '/images/ffb/players/image_na.gif';
    }

    /**
     * Whether a custom picture exists for this team+player.
     */
    public static function exists(?string $teamKey, ?string $playerKey): bool
    {
        return ! str_ends_with(self::url($teamKey, $playerKey), 'image_na.gif');
    }

    /**
     * Absolute filesystem path for the canonical team+player image.
     */
    public static function storagePath(string $teamKey, string $playerKey): string
    {
        return self::playersDir()
            .DIRECTORY_SEPARATOR.$teamKey
            .DIRECTORY_SEPARATOR.$playerKey.'.jpg';
    }

    public static function playersDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'players';
    }

    private static function fileExists(string $relativeUnderFfb): bool
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return is_file($base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativeUnderFfb));
    }
}
