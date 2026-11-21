<?php

namespace App\Services;

/**
 * Password hashing with legacy MD5 compatibility (port of FFB_Password).
 *
 * Existing accounts store 32-char MD5 digests. New writes use password_hash().
 * On successful login with a legacy hash, callers should rehash and persist.
 *
 * New / changed passwords must meet {@see validatePlain()} rules; existing
 * stored hashes are never re-validated on login.
 */
class FfbPassword
{
    public const MIN_LENGTH = 10;

    public const MAX_LENGTH = 32;

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

    /**
     * Validate a new plaintext password (registration, change, reset).
     *
     * @return list<string>
     */
    public function validatePlain(string $password, string $label = 'Passwort'): array
    {
        $errors = [];
        $length = strlen($password);

        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            $errors[] = $label.': min. Länge ist '.self::MIN_LENGTH.', max. Länge ist '.self::MAX_LENGTH.'!';
        }

        if ($password !== '' && ! $this->hasDigitOrSpecial($password)) {
            $errors[] = $label.': muss mindestens eine Zahl oder ein Sonderzeichen enthalten!';
        }

        return $errors;
    }

    private function hasDigitOrSpecial(string $password): bool
    {
        return (bool) preg_match('/\d/', $password)
            || (bool) preg_match('/[^a-zA-Z0-9]/', $password);
    }
}
