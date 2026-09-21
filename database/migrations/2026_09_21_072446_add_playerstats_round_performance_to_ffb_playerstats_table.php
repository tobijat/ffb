<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_playerstats')) {
            return;
        }

        if (! Schema::hasColumn('ffb_playerstats', 'playerstats_round_performance')) {
            Schema::table('ffb_playerstats', function (Blueprint $table) {
                $table->double('playerstats_round_performance')->nullable()->after('playerstats_score');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_playerstats')) {
            return;
        }

        if (Schema::hasColumn('ffb_playerstats', 'playerstats_round_performance')) {
            Schema::table('ffb_playerstats', function (Blueprint $table) {
                $table->dropColumn('playerstats_round_performance');
            });
        }
    }
};
