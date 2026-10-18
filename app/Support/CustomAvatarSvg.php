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
     * Persist SVG and return the stored basename, or null on failure.
     */
    public static function store(string $svg): ?string
    {
        $svg = trim($svg);
        if (! self::isAcceptable($svg)) {
            return null;
        }

        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');
        $dir = $base.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return null;
        }

        $filename = md5($svg).'.svg';
        $path = $dir.DIRECTORY_SEPARATOR.$filename;
        if (! is_file($path) && @file_put_contents($path, $svg) === false) {
            return null;
        }

        return $filename;
    }
}
