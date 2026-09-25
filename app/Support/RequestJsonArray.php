<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Prefer a single JSON field for large admin bulk forms (avoids PHP max_input_vars truncation).
 */
final class RequestJsonArray
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function pull(Request $request, string $jsonKey, string $arrayKey): array
    {
        $json = $request->input($jsonKey);
        if (is_string($json) && $json !== '') {
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                return array_values(array_filter(
                    $decoded,
                    static fn (mixed $row): bool => is_array($row),
                ));
            }
        }

        $rows = $request->input($arrayKey, []);
        if (! is_array($rows)) {
            return [];
        }

        return array_values(array_filter(
            $rows,
            static fn (mixed $row): bool => is_array($row),
        ));
    }
}
