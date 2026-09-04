<?php

/**
 * Minimal .env loader (no Composer dependency).
 *
 * Loads KEY=VALUE pairs into putenv(), $_ENV, and $_SERVER.
 * Does not override variables that are already set in the environment.
 */
class FFB_Env
{
    /** @var bool */
    private static $loaded = false;

    /**
     * Load a .env file if it exists.
     */
    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }
        self::$loaded = true;

        if ($path === '' || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if ($name === '') {
                continue;
            }

            // Strip optional single/double quotes.
            if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
                $quote = $value[0];
                if (substr($value, -1) === $quote) {
                    $value = substr($value, 1, -1);
                }
            }

            // Existing process env wins (CI, phpunit.xml, system env).
            $existing = getenv($name);
            if ($existing !== false && $existing !== '') {
                continue;
            }

            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }

    /**
     * Read an env value with optional default.
     */
    public static function get(string $name, ?string $default = null): ?string
    {
        $value = getenv($name);
        if ($value === false || $value === '') {
            return $default;
        }
        return $value;
    }

    /**
     * Require an env value or throw.
     */
    public static function require(string $name): string
    {
        $value = self::get($name);
        if ($value === null) {
            throw new RuntimeException('Missing required environment variable: ' . $name);
        }
        return $value;
    }
}
