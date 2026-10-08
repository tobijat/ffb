<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_league') || ! Schema::hasColumn('ffb_league', 'league_symbol')) {
            return;
        }

        Schema::table('ffb_league', function (Blueprint $table) {
            $table->dropColumn('league_symbol');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_league') || Schema::hasColumn('ffb_league', 'league_symbol')) {
            return;
        }

        Schema::table('ffb_league', function (Blueprint $table) {
            $table->string('league_symbol')->default('')->after('league_test');
        });
    }
};
