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

        Schema::table('ffb_team', function (Blueprint $table) {
            if (! Schema::hasColumn('ffb_team', 'team_uefa_id')) {
                $table->string('team_uefa_id', 64)->default('')->after('team_status');
            }
            if (! Schema::hasColumn('ffb_team', 'team_team_code')) {
                $table->string('team_team_code', 16)->default('')->after('team_uefa_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_team')) {
            return;
        }

        Schema::table('ffb_team', function (Blueprint $table) {
            if (Schema::hasColumn('ffb_team', 'team_team_code')) {
                $table->dropColumn('team_team_code');
            }
            if (Schema::hasColumn('ffb_team', 'team_uefa_id')) {
                $table->dropColumn('team_uefa_id');
            }
        });
    }
};
