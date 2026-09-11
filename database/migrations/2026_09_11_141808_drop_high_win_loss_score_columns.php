<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Drop unused high-win / high-loss scoring options and playerstats columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            $this->dropColumns('ffb_league_options', [
                'options_score_high_win',
                'options_score_high_loss',
                'options_score_high_win_loss_treshold',
            ]);

            $this->dropColumns('ffb_playerstats', [
                'playerstats_score_high_win',
                'playerstats_score_high_loss',
            ]);
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    public function down(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasTable('ffb_league_options')) {
                if (! Schema::hasColumn('ffb_league_options', 'options_score_high_win')) {
                    DB::statement('ALTER TABLE `ffb_league_options` ADD COLUMN `options_score_high_win` int(11) NOT NULL DEFAULT 0');
                }
                if (! Schema::hasColumn('ffb_league_options', 'options_score_high_loss')) {
                    DB::statement('ALTER TABLE `ffb_league_options` ADD COLUMN `options_score_high_loss` int(11) NOT NULL DEFAULT 0');
                }
                if (! Schema::hasColumn('ffb_league_options', 'options_score_high_win_loss_treshold')) {
                    DB::statement('ALTER TABLE `ffb_league_options` ADD COLUMN `options_score_high_win_loss_treshold` int(11) NOT NULL DEFAULT 0');
                }
            }

            if (Schema::hasTable('ffb_playerstats')) {
                if (! Schema::hasColumn('ffb_playerstats', 'playerstats_score_high_win')) {
                    DB::statement('ALTER TABLE `ffb_playerstats` ADD COLUMN `playerstats_score_high_win` int(11) NOT NULL DEFAULT 0');
                }
                if (! Schema::hasColumn('ffb_playerstats', 'playerstats_score_high_loss')) {
                    DB::statement('ALTER TABLE `ffb_playerstats` ADD COLUMN `playerstats_score_high_loss` int(11) NOT NULL DEFAULT 0');
                }
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    /**
     * @param  list<string>  $columns
     */
    private function dropColumns(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                DB::statement('ALTER TABLE `'.$table.'` DROP COLUMN `'.$column.'`');
            }
        }
    }
};
