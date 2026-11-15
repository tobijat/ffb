<?php

namespace App\Support;

/**
 * Default / resolved on-pitch display names for ffb_player.player_display_name.
 */
final class PlayerDisplayName
{
    /**
     * Backfill / create default:
     * - fname == lname → fname
     * - otherwise → "fname lname" (trimmed; empty parts omitted)
     */
    public static function default(?string $fname, ?string $lname): string
    {
        $fname = trim((string) $fname);
        $lname = trim((string) $lname);

        if ($fname === '' && $lname === '') {
            return '';
        }

        if ($fname === $lname) {
            return $fname !== '' ? $fname : $lname;
        }

        if ($fname === '') {
            return $lname;
        }
        if ($lname === '') {
            return $fname;
        }

        return $fname.' '.$lname;
    }

    /**
     * Prefer stored display name; fall back to default from parts.
     */
    public static function resolve(?string $displayName, ?string $fname, ?string $lname): string
    {
        $stored = trim((string) $displayName);
        if ($stored !== '') {
            return $stored;
        }

        return self::default($fname, $lname);
    }
}
