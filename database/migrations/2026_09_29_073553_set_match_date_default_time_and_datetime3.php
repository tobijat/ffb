<?php

use App\Models\MatchGame;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_match') || ! Schema::hasColumn('ffb_match', 'match_date')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        $defaultTime = MatchGame::DEFAULT_TIME;

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `ffb_match` MODIFY `match_date` DATETIME(3) NOT NULL');
            DB::update(
                "UPDATE `ffb_match` SET `match_date` = CONCAT(DATE(`match_date`), ' ".$defaultTime."')"
            );

            return;
        }

        // SQLite / other drivers used in tests: rewrite time portion in-place.
        foreach (DB::table('ffb_match')->select(['match_id', 'match_date'])->cursor() as $row) {
            $calendar = MatchGame::calendarDate((string) $row->match_date);
            if ($calendar === '') {
                continue;
            }
            DB::table('ffb_match')
                ->where('match_id', $row->match_id)
                ->update(['match_date' => $calendar.' '.$defaultTime]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_match') || ! Schema::hasColumn('ffb_match', 'match_date')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        // Fractional seconds cannot be restored; collapse back to second precision at midnight.
        DB::update("UPDATE `ffb_match` SET `match_date` = CONCAT(DATE(`match_date`), ' 00:00:00')");
        DB::statement('ALTER TABLE `ffb_match` MODIFY `match_date` DATETIME NOT NULL');
    }
};
