<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ffb_league', function (Blueprint $table) {
            $table->tinyInteger('league_test')->default(0)->after('league_archive');
        });
    }

    public function down(): void
    {
        Schema::table('ffb_league', function (Blueprint $table) {
            $table->dropColumn('league_test');
        });
    }
};
