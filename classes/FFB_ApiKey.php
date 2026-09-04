<?php

/**
 * API key helpers for ffbapi authentication.
 *
 * Keys may be stored as legacy plaintext (historical 32-char tokens) or
 * SHA-256 hex digests. Successful legacy matches are upgraded in place.
 */
class FFB_ApiKey
{
    public const HEADER_NAME = 'X-API-Key';

    /**
     * Resolve the presented API key from headers first, then POST/GET pin (legacy).
     */
    public static function extractFromRequest(): string
    {
        $headerKey = self::headerValue(self::HEADER_NAME);
        if ($headerKey !== '') {
            return $headerKey;
        }

        $auth = self::headerValue('Authorization');
        if ($auth !== '' && preg_match('/^\s*Bearer\s+(\S+)\s*$/i', $auth, $m)) {
            return $m[1];
        }

        if (isset($_POST['pin']) && is_string($_POST['pin']) && $_POST['pin'] !== '') {
            return $_POST['pin'];
        }

        // Legacy query-string transport — still accepted, but prefer headers.
        if (isset($_GET['pin']) && is_string($_GET['pin']) && $_GET['pin'] !== '') {
            return $_GET['pin'];
        }

        return '';
    }

    /**
     * SHA-256 hex digest used for at-rest storage.
     */
    public static function digest(string $plainKey): string
    {
        return hash('sha256', $plainKey);
    }

    public static function isDigested(?string $stored): bool
    {
        return is_string($stored) && (bool) preg_match('/^[a-f0-9]{64}$/i', $stored);
    }

    /**
     * Whether REMOTE_ADDR is allowed for this key.
     * Empty / "*" = any IP (legacy). Comma-separated list supported.
     */
    public static function ipAllowed(?string $allowedList, ?string $remoteAddr): bool
    {
        $allowedList = trim((string) $allowedList);
        $remoteAddr = trim((string) $remoteAddr);

        if ($allowedList === '' || $allowedList === '*') {
            if (self::requireIpRestriction()) {
                return false;
            }
            return true;
        }

        if ($remoteAddr === '') {
            return false;
        }

        foreach (preg_split('/\s*,\s*/', $allowedList) as $allowed) {
            if ($allowed !== '' && hash_equals($allowed, $remoteAddr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * When true, keys without an IP allowlist are rejected.
     */
    public static function requireIpRestriction(): bool
    {
        if (class_exists('FFB_Env', false)) {
            $value = FFB_Env::get('FFB_API_REQUIRE_IP', '0');
        } else {
            $value = getenv('FFB_API_REQUIRE_IP') ?: '0';
        }
        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }

    private static function headerValue(string $name): string
    {
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (!empty($_SERVER[$serverKey]) && is_string($_SERVER[$serverKey])) {
            return trim($_SERVER[$serverKey]);
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (is_array($headers)) {
                foreach ($headers as $headerName => $headerValue) {
                    if (strcasecmp((string) $headerName, $name) === 0 && is_string($headerValue)) {
                        return trim($headerValue);
                    }
                }
            }
        }

        return '';
    }
}
