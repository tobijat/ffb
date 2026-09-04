<?php

/**
 * Password hashing with legacy MD5 compatibility.
 *
 * Existing accounts store 32-char MD5 digests. New writes use password_hash().
 * On successful login with a legacy hash, callers should rehash and persist.
 */
class FFB_Password
{
    /**
     * Hash a plaintext password for storage.
     */
    public static function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    /**
     * Verify plaintext against a stored hash (modern or legacy MD5).
     */
    public static function verify(string $plainPassword, ?string $storedHash): bool
    {
        if ($storedHash === null || $storedHash === '') {
            return false;
        }

        if (self::isLegacyMd5($storedHash)) {
            return hash_equals($storedHash, md5($plainPassword));
        }

        return password_verify($plainPassword, $storedHash);
    }

    /**
     * Whether the stored value should be upgraded to password_hash().
     */
    public static function needsRehash(?string $storedHash): bool
    {
        if ($storedHash === null || $storedHash === '') {
            return true;
        }

        if (self::isLegacyMd5($storedHash)) {
            return true;
        }

        return password_needs_rehash($storedHash, PASSWORD_DEFAULT);
    }

    /**
     * Legacy FFB passwords are unsalted 32-char hex MD5 digests.
     */
    public static function isLegacyMd5(?string $storedHash): bool
    {
        if ($storedHash === null) {
            return false;
        }

        return (bool) preg_match('/^[a-f0-9]{32}$/i', $storedHash);
    }
}
