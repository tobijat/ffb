<?php

use App\Support\FfbDateTime;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Existing matchround_startdate / enddate values were entered as Europe/Vienna
     * (or FFB_DISPLAY_TIMEZONE) wall clocks but stored/compared as naive UTC.
     * Convert them to real UTC so deadline checks match local kickoff times.
     */
    public function up(): void
    {
        if (! Schema::hasTable('ffb_matchround')) {
            return;
        }

        $this->convertColumn('matchround_startdate', fn (string $value): ?string => FfbDateTime::legacyLocalWallToUtcDb($value));
        $this->convertColumn('matchround_enddate', fn (string $value): ?string => FfbDateTime::legacyLocalWallToUtcDb($value));
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_matchround')) {
            return;
        }

        $this->convertColumn('matchround_startdate', fn (string $value): ?string => FfbDateTime::utcDbToLegacyLocalWall($value));
        $this->convertColumn('matchround_enddate', fn (string $value): ?string => FfbDateTime::utcDbToLegacyLocalWall($value));
    }

    /**
     * @param  callable(string): (?string)  $converter
     */
    private function convertColumn(string $column, callable $converter): void
    {
        DB::table('ffb_matchround')
            ->orderBy('matchround_id')
            ->chunkById(100, function ($rows) use ($column, $converter): void {
                foreach ($rows as $row) {
                    $raw = $row->{$column} ?? null;
                    if ($raw === null || trim((string) $raw) === '') {
                        continue;
                    }

                    $converted = $converter((string) $raw);
                    if ($converted === null || $converted === (string) $raw) {
                        continue;
                    }

                    DB::table('ffb_matchround')
                        ->where('matchround_id', $row->matchround_id)
                        ->update([$column => $converted]);
                }
            }, 'matchround_id');
    }
};
