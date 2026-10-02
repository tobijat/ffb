<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ffb_userteam_substitute_slot', function (Blueprint $table) {
            $table->increments('substitute_slot_id');
            $table->unsignedInteger('substitute_slot_userteam_id');
            $table->unsignedTinyInteger('substitute_slot_slot');
            $table->unsignedInteger('substitute_slot_playerteam_id');
            $table->unsignedInteger('substitute_slot_replaces_playerteam_id')->nullable();
            $table->unique(['substitute_slot_userteam_id', 'substitute_slot_slot'], 'userteam_sub_slot_unique');
            $table->index('substitute_slot_playerteam_id', 'userteam_sub_playerteam_idx');
            $table->index('substitute_slot_replaces_playerteam_id', 'userteam_sub_replaces_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ffb_userteam_substitute_slot');
    }
};
