<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ffb_userteam_slot')) {
            return;
        }

        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');

            $table->unique(
                ['userteam_slot_userteam_id', 'userteam_slot_slot'],
                'userteam_slot_userteam_slot_unique',
            );
            $table->index('userteam_slot_playerteam_id', 'userteam_slot_playerteam_index');
            $table->index('userteam_slot_userteam_id', 'userteam_slot_userteam_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ffb_userteam_slot');
    }
};
