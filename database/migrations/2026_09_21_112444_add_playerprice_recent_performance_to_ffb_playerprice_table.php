<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_playerprice')) {
            return;
        }

        if (! Schema::hasColumn('ffb_playerprice', 'playerprice_recent_performance')) {
            Schema::table('ffb_playerprice', function (Blueprint $table) {
                $table->double('playerprice_recent_performance')->nullable()->after('playerprice_av_power');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_playerprice')) {
            return;
        }

        if (Schema::hasColumn('ffb_playerprice', 'playerprice_recent_performance')) {
            Schema::table('ffb_playerprice', function (Blueprint $table) {
                $table->dropColumn('playerprice_recent_performance');
            });
        }
    }
};
