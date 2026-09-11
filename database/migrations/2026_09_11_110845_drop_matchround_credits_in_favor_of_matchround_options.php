<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * matchround_credits duplicated lineup max credits; keep only matchround_options
 * (fallback: ffb_league_options.options_lineup_max_credits).
 */
return new class extends Migration
{
    private const LINEUP_COLUMNS = [
        'options_lineup_max_players',
        'options_lineup_max_credits',
        'options_lineup_max_players_team',
        'options_lineup_min_g',
        'options_lineup_min_d',
        'options_lineup_min_m',
        'options_lineup_min_s',
        'options_lineup_max_g',
        'options_lineup_max_d',
        'options_lineup_max_m',
        'options_lineup_max_s',
    ];

    public function up(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            $this->backfillCreditsIntoMatchroundOptions();

            if (Schema::hasTable('ffb_matchround') && Schema::hasColumn('ffb_matchround', 'matchround_credits')) {
                DB::statement('ALTER TABLE `ffb_matchround` DROP COLUMN `matchround_credits`');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    public function down(): void
    {
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            if (Schema::hasTable('ffb_matchround') && ! Schema::hasColumn('ffb_matchround', 'matchround_credits')) {
                DB::statement('ALTER TABLE `ffb_matchround` ADD COLUMN `matchround_credits` double NOT NULL DEFAULT 0');
            }

            if (Schema::hasTable('ffb_matchround_options') && Schema::hasColumn('ffb_matchround', 'matchround_credits')) {
                $rows = DB::table('ffb_matchround_options')->get([
                    'matchround_options_matchround_id',
                    'matchround_options_lineup_max_credits',
                ]);
                foreach ($rows as $row) {
                    DB::table('ffb_matchround')
                        ->where('matchround_id', $row->matchround_options_matchround_id)
                        ->update([
                            'matchround_credits' => (float) $row->matchround_options_lineup_max_credits,
                        ]);
                }
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }
    }

    private function backfillCreditsIntoMatchroundOptions(): void
    {
        if (! Schema::hasTable('ffb_matchround')
            || ! Schema::hasColumn('ffb_matchround', 'matchround_credits')
            || ! Schema::hasTable('ffb_league_options')
            || ! Schema::hasTable('ffb_matchround_options')
        ) {
            return;
        }

        $rounds = DB::table('ffb_matchround')
            ->where('matchround_credits', '>', 0)
            ->get(['matchround_id', 'matchround_league_id', 'matchround_credits']);

        foreach ($rounds as $round) {
            $credits = (float) $round->matchround_credits;
            $league = DB::table('ffb_league_options')
                ->where('options_league_id', $round->matchround_league_id)
                ->first(self::LINEUP_COLUMNS);

            $leagueCredits = (float) ($league->options_lineup_max_credits ?? 100);
            $existing = DB::table('ffb_matchround_options')
                ->where('matchround_options_matchround_id', $round->matchround_id)
                ->first();

            if ($existing) {
                DB::table('ffb_matchround_options')
                    ->where('matchround_options_id', $existing->matchround_options_id)
                    ->update(['matchround_options_lineup_max_credits' => $credits]);

                continue;
            }

            if ($credits === $leagueCredits) {
                continue;
            }

            DB::table('ffb_matchround_options')->insert([
                'matchround_options_matchround_id' => (int) $round->matchround_id,
                'matchround_options_lineup_max_players' => (int) ($league->options_lineup_max_players ?? 11),
                'matchround_options_lineup_max_credits' => $credits,
                'matchround_options_lineup_max_players_team' => (int) ($league->options_lineup_max_players_team ?? 3),
                'matchround_options_lineup_min_g' => (int) ($league->options_lineup_min_g ?? 1),
                'matchround_options_lineup_min_d' => (int) ($league->options_lineup_min_d ?? 3),
                'matchround_options_lineup_min_m' => (int) ($league->options_lineup_min_m ?? 3),
                'matchround_options_lineup_min_s' => (int) ($league->options_lineup_min_s ?? 1),
                'matchround_options_lineup_max_g' => (int) ($league->options_lineup_max_g ?? 1),
                'matchround_options_lineup_max_d' => (int) ($league->options_lineup_max_d ?? 5),
                'matchround_options_lineup_max_m' => (int) ($league->options_lineup_max_m ?? 5),
                'matchround_options_lineup_max_s' => (int) ($league->options_lineup_max_s ?? 3),
            ]);
        }
    }
};
