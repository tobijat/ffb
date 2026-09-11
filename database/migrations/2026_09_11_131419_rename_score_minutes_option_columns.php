<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rename playing-time score option columns to clearer threshold/band names.
 */
return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private array $renames = [
        'options_score_minutes_treshold' => 'options_score_minutes_threshold_lower',
        'options_score_minutes_lt30' => 'options_score_minutes_low',
        'options_score_minutes_gt' => 'options_score_minutes_high',
        'options_score_minutes_lt' => 'options_score_minutes_middle',
        'options_score_minutes' => 'options_score_minutes_threshold_upper',
    ];

    public function up(): void
    {
        $this->renameColumns($this->renames);
    }

    public function down(): void
    {
        $this->renameColumns(array_flip($this->renames));
    }

    /**
     * @param  array<string, string>  $map
     */
    private function renameColumns(array $map): void
    {
        if (! Schema::hasTable('ffb_league_options')) {
            return;
        }

        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            foreach ($map as $from => $to) {
                if (! Schema::hasColumn('ffb_league_options', $from) || Schema::hasColumn('ffb_league_options', $to)) {
                    continue;
                }

                DB::statement(
                    'ALTER TABLE `ffb_league_options` CHANGE COLUMN `'.$from.'` `'.$to.'` int(11) NOT NULL DEFAULT 0'
                );
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }
};
