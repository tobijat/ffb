<?php

namespace App\Support;

/**
 * Resolves public URLs for stored user avatar values.
 *
 * Legacy uploads / custom kit SVGs: basename under profiles/avatar/
 * Shirt avatars: relative path under images/ffb (shirts/{team_key}/{nat}.svg)
 */
final class UserAvatar
{
    public static function isShirtPath(string $stored): bool
    {
        return (bool) preg_match('#^shirts/[a-z0-9]+(?:-[a-z0-9]+)*/[a-z0-9_.-]+\.svg$#', $stored);
    }

    public static function isDefault(string $stored): bool
    {
        return $stored === '' || $stored === 'avatar_na.png';
    }

    public static function publicUrl(?string $stored): string
    {
        $value = trim((string) $stored);
        if ($value === '') {
            $value = 'avatar_na.png';
        }

        if (self::isShirtPath($value) && TeamShirt::relativeExists($value)) {
            return '/images/ffb/'.$value;
        }

        // Legacy custom uploads / default — basename only.
        $basename = basename(str_replace('\\', '/', $value));
        if ($basename === '' || $basename === '.' || $basename === '..') {
            $basename = 'avatar_na.png';
        }

        return '/images/ffb/profiles/avatar/'.$basename;
    }
}
