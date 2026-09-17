<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_team') || ! Schema::hasColumn('ffb_team', 'team_avg_price')) {
            return;
        }

        Schema::table('ffb_team', function (Blueprint $table) {
            $table->dropColumn('team_avg_price');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_team') || Schema::hasColumn('ffb_team', 'team_avg_price')) {
            return;
        }

        Schema::table('ffb_team', function (Blueprint $table) {
            $table->double('team_avg_price')->default(5);
        });
    }
};
