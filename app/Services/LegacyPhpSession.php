<?php

namespace App\Services;

/**
 * Bridge to the legacy FFB_Session (native PHPSESSID / $_SESSION).
 *
 * Laravel uses its own session store/cookie; admin auth still reads
 * $_SESSION via classes/FFB_Session.php. This helper opens the native
 * session briefly to write or clear those keys without disturbing Laravel.
 */
class LegacyPhpSession
{
    public function sessionName(): string
    {
        return (string) config('ffb.legacy_session_name', 'PHPSESSID');
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function put(array $values): void
    {
        $this->withNativeSession(function () use ($values): void {
            foreach ($values as $key => $value) {
                $_SESSION[$key] = $value;
            }
        });
    }

    public function forget(): void
    {
        $this->withNativeSession(function (): void {
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    $this->sessionName(),
                    '',
                    [
                        'expires' => time() - 42000,
                        'path' => $params['path'],
                        'domain' => $params['domain'],
                        'secure' => (bool) $params['secure'],
                        'httponly' => (bool) $params['httponly'],
                        'samesite' => $params['samesite'] ?? 'Lax',
                    ]
                );
            }

            session_destroy();
        }, destroyAfter: true);
    }

    /**
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $value = $default;

        $this->withNativeSession(function () use ($key, &$value, $default): void {
            $value = array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
        });

        return $value;
    }

    /**
     * @param  callable(): void  $callback
     */
    private function withNativeSession(callable $callback, bool $destroyAfter = false): void
    {
        $name = $this->sessionName();
        $previousName = session_name();
        $previousId = session_id();
        $wasActive = session_status() === PHP_SESSION_ACTIVE;

        if ($wasActive) {
            session_write_close();
        }

        session_name($name);

        if (! empty($_COOKIE[$name]) && is_string($_COOKIE[$name])) {
            session_id($_COOKIE[$name]);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        try {
            $callback();
        } finally {
            if (! $destroyAfter && session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            session_name($previousName !== '' ? $previousName : $name);

            if ($wasActive) {
                if ($previousId !== '') {
                    session_id($previousId);
                }
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
            }
        }
    }
}
