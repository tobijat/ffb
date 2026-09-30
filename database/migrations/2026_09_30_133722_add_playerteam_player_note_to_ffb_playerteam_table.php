<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_playerteam') || Schema::hasColumn('ffb_playerteam', 'playerteam_player_note')) {
            return;
        }

        Schema::table('ffb_playerteam', function (Blueprint $table) {
            $table->string('playerteam_player_note')->default('');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_playerteam') || ! Schema::hasColumn('ffb_playerteam', 'playerteam_player_note')) {
            return;
        }

        Schema::table('ffb_playerteam', function (Blueprint $table) {
            $table->dropColumn('playerteam_player_note');
        });
    }
};
