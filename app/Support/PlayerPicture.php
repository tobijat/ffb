<?php

namespace App\Support;

/**
 * Player picture paths: per (team, player), not per league/playerteam row.
 */
final class PlayerPicture
{
    /**
     * Public URL for a player image with fallbacks.
     */
    public static function url(?int $teamId, ?int $playerId): string
    {
        $teamId = (int) $teamId;
        $playerId = (int) $playerId;

        if ($teamId > 0 && $playerId > 0) {
            $relative = 'players/'.$teamId.'/'.$teamId.'-'.$playerId.'.jpg';
            if (self::fileExists($relative)) {
                return '/images/ffb/'.$relative;
            }
        }

        if ($playerId > 0) {
            $relative = 'players/'.$playerId.'.jpg';
            if (self::fileExists($relative)) {
                return '/images/ffb/'.$relative;
            }
        }

        return '/images/ffb/players/image_na.gif';
    }

    /**
     * Whether a custom picture exists for this team+player.
     */
    public static function exists(?int $teamId, ?int $playerId): bool
    {
        return ! str_ends_with(self::url($teamId, $playerId), 'image_na.gif');
    }

    /**
     * Absolute filesystem path for the canonical team+player image.
     */
    public static function storagePath(int $teamId, int $playerId): string
    {
        return self::playersDir()
            .DIRECTORY_SEPARATOR.$teamId
            .DIRECTORY_SEPARATOR.$teamId.'-'.$playerId.'.jpg';
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
