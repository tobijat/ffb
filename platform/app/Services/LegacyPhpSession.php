<?php

namespace App\Services;

use Illuminate\Http\Request;

/**
 * Reads the legacy FFB PHP session (FFB_Session / $_SESSION['user_id']).
 *
 * Does not use Laravel's session cookie — the strangler APIs share the same
 * native PHPSESSID the old app already sets on login.
 */
class LegacyPhpSession
{
    /**
     * @return int Logged-in user id, or 0 if absent / unreadable
     */
    public function userId(Request $request): int
    {
        $cookieName = session_name();
        $sessionId = $request->cookies->get($cookieName);

        if (! is_string($sessionId) || $sessionId === '') {
            return 0;
        }

        // PHP session ids are alphanumeric with , and - (see session.sid_bits_per_character).
        if (! preg_match('/^[A-Za-z0-9,-]{16,128}$/', $sessionId)) {
            return 0;
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            return session_id() === $sessionId
                ? (int) ($_SESSION['user_id'] ?? 0)
                : 0;
        }

        if (! $this->sessionFileExists($sessionId)) {
            return 0;
        }

        session_id($sessionId);
        $started = session_start([
            'read_and_close' => true,
            'use_strict_mode' => true,
        ]);

        if (! $started) {
            return 0;
        }

        return (int) ($_SESSION['user_id'] ?? 0);
    }

    private function sessionFileExists(string $sessionId): bool
    {
        $savePath = session_save_path();
        if ($savePath === '') {
            $savePath = (string) ini_get('session.save_path');
        }
        if ($savePath === '') {
            // PHP default when save_path is empty (Windows/Linux temp).
            $savePath = sys_get_temp_dir();
        }

        // Formats like "N;/path" or "N;MODE;/path"
        if (str_contains($savePath, ';')) {
            $parts = explode(';', $savePath);
            $savePath = (string) end($parts);
        }

        $savePath = rtrim($savePath, DIRECTORY_SEPARATOR.'/\\');
        if ($savePath === '' || ! is_dir($savePath)) {
            return false;
        }

        return is_file($savePath.DIRECTORY_SEPARATOR.'sess_'.$sessionId);
    }
}
