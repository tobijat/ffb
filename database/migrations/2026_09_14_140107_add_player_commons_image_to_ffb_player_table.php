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

        if (! Schema::hasColumn('ffb_player', 'player_commons_image')) {
            Schema::table('ffb_player', function (Blueprint $table) {
                $table->string('player_commons_image', 255)->default('')->after('player_status_description');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_player')) {
            return;
        }

        if (Schema::hasColumn('ffb_player', 'player_commons_image')) {
            Schema::table('ffb_player', function (Blueprint $table) {
                $table->dropColumn('player_commons_image');
            });
        }
    }
};
