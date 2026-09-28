<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_team')) {
            return;
        }

        if (! Schema::hasColumn('ffb_team', 'team_fifa_id')) {
            Schema::table('ffb_team', function (Blueprint $table) {
                $table->string('team_fifa_id', 64)->default('')->after('team_uefa_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_team')) {
            return;
        }

        if (Schema::hasColumn('ffb_team', 'team_fifa_id')) {
            Schema::table('ffb_team', function (Blueprint $table) {
                $table->dropColumn('team_fifa_id');
            });
        }
    }
};
