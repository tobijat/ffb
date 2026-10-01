<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ffb_league_options', function (Blueprint $table) {
            $table->integer('options_lineup_min_bench')->default(0)->after('options_lineup_max_s');
            $table->integer('options_lineup_max_bench')->default(0)->after('options_lineup_min_bench');
        });

        Schema::table('ffb_matchround_options', function (Blueprint $table) {
            $table->integer('matchround_options_lineup_min_bench')->default(0)->after('matchround_options_lineup_max_s');
            $table->integer('matchround_options_lineup_max_bench')->default(0)->after('matchround_options_lineup_min_bench');
        });
    }

    public function down(): void
    {
        Schema::table('ffb_league_options', function (Blueprint $table) {
            $table->dropColumn(['options_lineup_min_bench', 'options_lineup_max_bench']);
        });

        Schema::table('ffb_matchround_options', function (Blueprint $table) {
            $table->dropColumn(['matchround_options_lineup_min_bench', 'matchround_options_lineup_max_bench']);
        });
    }
};
