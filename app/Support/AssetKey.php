<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Immutable filesystem-facing keys: {normalized-name}-{4 base36 chars}.
 */
final class AssetKey
{
    public const MAX_LENGTH = 64;

    private const SUFFIX_LENGTH = 4;

    private const MAX_SLUG_LENGTH = 40;

    /**
     * Build a unique key from a display name for the given table/column.
     */
    public static function generate(string $table, string $sourceName, string $column = 'asset_key'): string
    {
        $slug = self::slug($sourceName);
        if ($slug === '') {
            $slug = 'item';
        }

        for ($attempt = 0; $attempt < 32; $attempt++) {
            $key = $slug.'-'.self::randomSuffix();
            if (strlen($key) > self::MAX_LENGTH) {
                $key = substr($slug, 0, self::MAX_LENGTH - 1 - self::SUFFIX_LENGTH).'-'.self::randomSuffix();
            }
            if (! self::exists($table, $key, $column)) {
                return $key;
            }
        }

        throw new RuntimeException("Unable to allocate unique asset_key on {$table}");
    }

    public static function slug(string $sourceName): string
    {
        $value = trim($sourceName);
        if ($value === '') {
            return '';
        }

        if (class_exists(\Transliterator::class)) {
            $trans = \Transliterator::create('Any-Latin; Latin-ASCII; Lower()');
            if ($trans !== null) {
                $folded = $trans->transliterate($value);
                if (is_string($folded) && $folded !== '') {
                    $value = $folded;
                }
            }
        } else {
            $value = strtolower($value);
            $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if (is_string($ascii) && $ascii !== '') {
                $value = $ascii;
            }
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');
        if (strlen($value) > self::MAX_SLUG_LENGTH) {
            $value = rtrim(substr($value, 0, self::MAX_SLUG_LENGTH), '-');
        }

        return $value;
    }

    public static function isValid(?string $key): bool
    {
        if ($key === null || $key === '') {
            return false;
        }

        return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $key)
            && strlen($key) <= self::MAX_LENGTH;
    }

    public static function playerSourceName(string $fname, string $lname): string
    {
        return trim($fname.' '.$lname);
    }

    private static function randomSuffix(): string
    {
        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyz';
        $out = '';
        for ($i = 0; $i < self::SUFFIX_LENGTH; $i++) {
            $out .= $alphabet[random_int(0, 35)];
        }

        return $out;
    }

    private static function exists(string $table, string $key, string $column): bool
    {
        return DB::table($table)->where($column, $key)->exists();
    }
}
