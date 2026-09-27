<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_player')) {
            return;
        }

        Schema::table('ffb_player', function (Blueprint $table) {
            if (! Schema::hasColumn('ffb_player', 'player_uefa_id')) {
                $table->string('player_uefa_id', 64)->default('')->after('player_foreign_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_player')) {
            return;
        }

        Schema::table('ffb_player', function (Blueprint $table) {
            if (Schema::hasColumn('ffb_player', 'player_uefa_id')) {
                $table->dropColumn('player_uefa_id');
            }
        });
    }
};
