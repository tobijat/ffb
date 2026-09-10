<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Full rename of domain table ffb_game → ffb_league and related columns.
 * Uses raw ALTER to avoid Blueprint rebuilds choking on legacy zero dates.
 */
return new class extends Migration
{
    public function up(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasTable('ffb_game') && ! Schema::hasTable('ffb_league')) {
                DB::statement('RENAME TABLE `ffb_game` TO `ffb_league`');
            }

            $this->renameColumns('ffb_league', [
                'game_id' => ['league_id', 'int(11) NOT NULL AUTO_INCREMENT'],
                'game_title' => ['league_title', "varchar(255) NOT NULL DEFAULT 'Round'"],
                'game_visible' => ['league_visible', 'tinyint(4) NOT NULL DEFAULT 0'],
                'game_archive' => ['league_archive', 'tinyint(4) NOT NULL DEFAULT 0'],
                'game_countdown' => ['league_countdown', 'tinyint(4) NOT NULL DEFAULT 0'],
                'game_status' => ['league_status', 'tinyint(4) NOT NULL DEFAULT 0'],
                'game_description' => ['league_description', 'mediumtext NULL'],
                'game_symbol' => ['league_symbol', "varchar(255) NOT NULL DEFAULT 'game_symbol_na.png'"],
            ]);

            $this->renameColumns('ffb_matchround', [
                'matchround_game_id' => ['matchround_league_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('ffb_options', [
                'options_game_id' => ['options_league_id', 'int(11) NOT NULL'],
                'options_game_rankmode' => ['options_league_rankmode', "varchar(255) NOT NULL DEFAULT 'wc'"],
                'options_game_pricemode' => ['options_league_pricemode', "varchar(255) NOT NULL DEFAULT 'dynamic'"],
                'options_game_pointsmode' => ['options_league_pointsmode', "varchar(255) NOT NULL DEFAULT 'new'"],
                'options_game_wcpoints' => ['options_league_wcpoints', "varchar(255) NOT NULL DEFAULT 'new'"],
                'options_game_remind_hours_before' => ['options_league_remind_hours_before', 'int(11) NOT NULL DEFAULT 0'],
            ]);

            $this->renameColumns('ffb_news', [
                'news_game_id' => ['news_league_id', 'int(11) NOT NULL DEFAULT 0'],
            ]);

            $this->renameColumns('ffb_poll', [
                'poll_game_id' => ['poll_league_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('ffb_userscore', [
                'userscore_game_id' => ['userscore_league_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('web_user_details', [
                'user_details_ffb_selected_game' => ['user_details_ffb_selected_league', 'int(11) NOT NULL DEFAULT 0'],
            ]);

            $this->renameColumns('ffb_admin', [
                'admin_game_id' => ['admin_league_id', 'int(11) NOT NULL'],
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
            $this->renameColumns('ffb_admin', [
                'admin_league_id' => ['admin_game_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('web_user_details', [
                'user_details_ffb_selected_league' => ['user_details_ffb_selected_game', 'int(11) NOT NULL DEFAULT 0'],
            ]);

            $this->renameColumns('ffb_userscore', [
                'userscore_league_id' => ['userscore_game_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('ffb_poll', [
                'poll_league_id' => ['poll_game_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('ffb_news', [
                'news_league_id' => ['news_game_id', 'int(11) NOT NULL DEFAULT 0'],
            ]);

            $this->renameColumns('ffb_options', [
                'options_league_id' => ['options_game_id', 'int(11) NOT NULL'],
                'options_league_rankmode' => ['options_game_rankmode', "varchar(255) NOT NULL DEFAULT 'wc'"],
                'options_league_pricemode' => ['options_game_pricemode', "varchar(255) NOT NULL DEFAULT 'dynamic'"],
                'options_league_pointsmode' => ['options_game_pointsmode', "varchar(255) NOT NULL DEFAULT 'new'"],
                'options_league_wcpoints' => ['options_game_wcpoints', "varchar(255) NOT NULL DEFAULT 'new'"],
                'options_league_remind_hours_before' => ['options_game_remind_hours_before', 'int(11) NOT NULL DEFAULT 0'],
            ]);

            $this->renameColumns('ffb_matchround', [
                'matchround_league_id' => ['matchround_game_id', 'int(11) NOT NULL'],
            ]);

            $this->renameColumns('ffb_league', [
                'league_id' => ['game_id', 'int(11) NOT NULL AUTO_INCREMENT'],
                'league_title' => ['game_title', "varchar(255) NOT NULL DEFAULT 'Round'"],
                'league_visible' => ['game_visible', 'tinyint(4) NOT NULL DEFAULT 0'],
                'league_archive' => ['game_archive', 'tinyint(4) NOT NULL DEFAULT 0'],
                'league_countdown' => ['game_countdown', 'tinyint(4) NOT NULL DEFAULT 0'],
                'league_status' => ['game_status', 'tinyint(4) NOT NULL DEFAULT 0'],
                'league_description' => ['game_description', 'mediumtext NULL'],
                'league_symbol' => ['game_symbol', "varchar(255) NOT NULL DEFAULT 'game_symbol_na.png'"],
            ]);

            if (Schema::hasTable('ffb_league') && ! Schema::hasTable('ffb_game')) {
                DB::statement('RENAME TABLE `ffb_league` TO `ffb_game`');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    /**
     * @param  array<string, array{0: string, 1: string}>  $map  old => [new, typeSql]
     */
    private function renameColumns(string $table, array $map): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($map as $from => [$to, $typeSql]) {
            if (Schema::hasColumn($table, $from) && ! Schema::hasColumn($table, $to)) {
                DB::statement("ALTER TABLE `{$table}` CHANGE COLUMN `{$from}` `{$to}` {$typeSql}");
            }
        }
    }
};
