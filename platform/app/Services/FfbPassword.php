<?php

namespace App\Services;

/**
 * Password hashing with legacy MD5 compatibility (port of FFB_Password).
 *
 * Existing accounts store 32-char MD5 digests. New writes use password_hash().
 * On successful login with a legacy hash, callers should rehash and persist.
 */
class FfbPassword
{
    public function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public function verify(string $plainPassword, ?string $storedHash): bool
    {
        if ($storedHash === null || $storedHash === '') {
            return false;
        }

        if ($this->isLegacyMd5($storedHash)) {
            return hash_equals($storedHash, md5($plainPassword));
        }

        return password_verify($plainPassword, $storedHash);
    }

    public function needsRehash(?string $storedHash): bool
    {
        if ($storedHash === null || $storedHash === '') {
            return true;
        }

        if ($this->isLegacyMd5($storedHash)) {
            return true;
        }

        return password_needs_rehash($storedHash, PASSWORD_DEFAULT);
    }

    public function isLegacyMd5(?string $storedHash): bool
    {
        if ($storedHash === null) {
            return false;
        }

        return (bool) preg_match('/^[a-f0-9]{32}$/i', $storedHash);
    }
}
