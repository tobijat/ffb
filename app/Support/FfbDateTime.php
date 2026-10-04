<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Matchround deadlines: admin/UI wall clock in the display timezone, UTC in the DB.
 */
final class FfbDateTime
{
    public static function displayTimezone(): string
    {
        return (string) config('ffb.display_timezone', 'Europe/Vienna');
    }

    /**
     * Parse admin/form wall-clock input in the display timezone (hour precision).
     */
    public static function parseLocalInput(string $value): ?CarbonImmutable
    {
        $value = trim(str_replace(' ', 'T', $value));
        if ($value === '') {
            return null;
        }

        $tz = new DateTimeZone(self::displayTimezone());
        $formats = ['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', 'Y-m-d H:i'];

        foreach ($formats as $format) {
            $dt = DateTimeImmutable::createFromFormat($format, $value, $tz);
            if (! $dt instanceof DateTimeImmutable) {
                continue;
            }

            $errors = DateTimeImmutable::getLastErrors();
            if (is_array($errors) && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0)) {
                continue;
            }

            return CarbonImmutable::instance($dt)->setTime((int) $dt->format('G'), 0, 0);
        }

        try {
            $dt = new DateTimeImmutable($value, $tz);

            return CarbonImmutable::instance($dt)->setTime((int) $dt->format('G'), 0, 0);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Local form input → UTC Y-m-d H:i:s for DB storage.
     */
    public static function localInputToUtcDb(string $value): string
    {
        $parsed = self::parseLocalInput($value);
        if ($parsed === null) {
            return $value;
        }

        return $parsed->utc()->format('Y-m-d H:i:s');
    }

    /**
     * UTC DB value → datetime-local form value in the display timezone.
     */
    public static function utcDbToLocalInput(string $dbValue): string
    {
        $utc = self::parseUtcDb($dbValue);
        if ($utc === null) {
            return '';
        }

        return $utc->timezone(self::displayTimezone())->format('Y-m-d\TH:00');
    }

    /**
     * UTC DB value → display string in the display timezone.
     */
    public static function utcDbToDisplay(string $dbValue, string $format = 'j.n.Y G:i'): string
    {
        $utc = self::parseUtcDb($dbValue);
        if ($utc === null) {
            return '';
        }

        return $utc->timezone(self::displayTimezone())->format($format);
    }

    public static function isFutureUtc(?string $dbValue): bool
    {
        $utc = self::parseUtcDb((string) $dbValue);
        if ($utc === null) {
            return false;
        }

        return $utc->greaterThan(CarbonImmutable::now('UTC'));
    }

    public static function parseUtcDb(string $dbValue): ?CarbonImmutable
    {
        $dbValue = trim($dbValue);
        if ($dbValue === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($dbValue, 'UTC');
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Convert a historically stored display-timezone wall clock to UTC (one-time migration).
     */
    public static function legacyLocalWallToUtcDb(string $dbValue): ?string
    {
        $dbValue = trim($dbValue);
        if ($dbValue === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($dbValue, self::displayTimezone())
                ->utc()
                ->format('Y-m-d H:i:s');
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Convert a UTC DB value back to a display-timezone wall clock string (migration down).
     */
    public static function utcDbToLegacyLocalWall(string $dbValue): ?string
    {
        $utc = self::parseUtcDb($dbValue);
        if ($utc === null) {
            return null;
        }

        return $utc->timezone(self::displayTimezone())->format('Y-m-d H:i:s');
    }
}
