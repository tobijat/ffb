<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ffb_league_options', function (Blueprint $table) {
            $table->string('options_league_benchmode')->nullable()->after('options_league_remind_hours_before');
        });
    }

    public function down(): void
    {
        Schema::table('ffb_league_options', function (Blueprint $table) {
            $table->dropColumn('options_league_benchmode');
        });
    }
};
