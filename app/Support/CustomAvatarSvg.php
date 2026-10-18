<?php

namespace App\Support;

/**
 * Validates and stores kit-kitchen (or similar) SVG avatars under profiles/avatar/.
 */
final class CustomAvatarSvg
{
    public const MAX_BYTES = 512 * 1024;

    public static function isAcceptable(string $svg): bool
    {
        $svg = trim($svg);
        if ($svg === '' || strlen($svg) > self::MAX_BYTES) {
            return false;
        }

        if (! preg_match('/<svg\b/i', $svg)) {
            return false;
        }

        if (preg_match('/<script\b/i', $svg)) {
            return false;
        }

        if (preg_match('/\bjavascript\s*:/i', $svg)) {
            return false;
        }

        if (preg_match('/\bon[a-z]+\s*=/i', $svg)) {
            return false;
        }

        return true;
    }

    /**
     * Kit Kitchen seeds are unsigned 32-bit integers (1–10 digits).
     */
    public static function normalizeSeed(?string $seed): ?string
    {
        $seed = trim((string) $seed);
        if ($seed === '' || ! preg_match('/^\d{1,10}$/', $seed)) {
            return null;
        }

        return $seed;
    }

    /**
     * Persist front as `{seed}_{shortHash}.svg` and back as `{seed}_{shortHash}_back.svg`.
     * Returns the front basename, or null on failure.
     */
    public static function store(string $svg, string $seed, string $svgBack): ?string
    {
        $svg = trim($svg);
        $svgBack = trim($svgBack);
        if (! self::isAcceptable($svg) || ! self::isAcceptable($svgBack)) {
            return null;
        }

        $normalizedSeed = self::normalizeSeed($seed);
        if ($normalizedSeed === null) {
            return null;
        }

        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');
        $dir = $base.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return null;
        }

        $hash = bin2hex(random_bytes(4));
        $filename = $normalizedSeed.'_'.$hash.'.svg';
        $filenameBack = $normalizedSeed.'_'.$hash.'_back.svg';
        $path = $dir.DIRECTORY_SEPARATOR.$filename;
        $pathBack = $dir.DIRECTORY_SEPARATOR.$filenameBack;

        if (@file_put_contents($path, $svg) === false) {
            return null;
        }

        if (@file_put_contents($pathBack, $svgBack) === false) {
            @unlink($path);

            return null;
        }

        return $filename;
    }

    /**
     * Companion backside basename for a stored front avatar filename.
     */
    public static function backFilename(string $frontBasename): ?string
    {
        $frontBasename = basename(str_replace('\\', '/', $frontBasename));
        if (! preg_match('/^(\d{1,10}_[a-f0-9]{8})\.svg$/i', $frontBasename, $match)) {
            return null;
        }

        return $match[1].'_back.svg';
    }

    /**
     * Public URL for the backside SVG of a stored custom kit, or null if none.
     */
    public static function publicBackUrl(?string $stored): ?string
    {
        if (! self::isCustomKitFilename($stored)) {
            return null;
        }

        $back = self::backFilename((string) $stored);
        if ($back === null) {
            return null;
        }

        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');
        $path = $base.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$back;
        if (! is_file($path)) {
            return null;
        }

        return '/images/ffb/profiles/avatar/'.$back;
    }

    /**
     * Whether the stored avatar value is a generated custom kit front file
     * (`{seed}_{hash}.svg` under profiles/avatar/), not a team shirt path.
     */
    public static function isCustomKitFilename(?string $stored): bool
    {
        $stored = trim((string) $stored);
        if ($stored === '' || UserAvatar::isDefault($stored) || UserAvatar::isShirtPath($stored)) {
            return false;
        }

        // Must be a plain basename matching the custom kit naming scheme.
        $basename = basename(str_replace('\\', '/', $stored));

        return $basename === $stored && (bool) preg_match('/^\d{1,10}_[a-f0-9]{8}\.svg$/i', $basename);
    }

    /**
     * Seed portion of a custom kit filename, or null.
     */
    public static function seedFromFilename(?string $stored): ?string
    {
        if (! self::isCustomKitFilename($stored)) {
            return null;
        }

        if (! preg_match('/^(\d{1,10})_/i', (string) $stored, $match)) {
            return null;
        }

        return $match[1];
    }

    /**
     * Delete a previously stored custom kit avatar under profiles/avatar/
     * (front + `_back` sibling). Never deletes team shirts or defaults.
     */
    public static function deleteStored(?string $stored): void
    {
        if (! self::isCustomKitFilename($stored)) {
            return;
        }

        $basename = trim((string) $stored);
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');
        $dir = $base.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        $path = $dir.DIRECTORY_SEPARATOR.$basename;
        if (is_file($path)) {
            @unlink($path);
        }

        $back = self::backFilename($basename);
        if ($back !== null) {
            $backPath = $dir.DIRECTORY_SEPARATOR.$back;
            if (is_file($backPath)) {
                @unlink($backPath);
            }
        }
    }
}
