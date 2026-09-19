<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_extremeteam')) {
            Schema::create('ffb_extremeteam', function (Blueprint $table) {
                $table->increments('extremeteam_id');
                $table->string('extremeteam_top_or_flop', 8);
                $table->decimal('extremeteam_price', 9, 2)->default(0);
                $table->unsignedInteger('extremeteam_matchround_id');
                $table->integer('extremeteam_score')->default(-1);

                $table->unique(
                    ['extremeteam_matchround_id', 'extremeteam_top_or_flop'],
                    'extremeteam_round_type_unique',
                );
                $table->index('extremeteam_matchround_id', 'extremeteam_matchround_index');
            });
        }

        if (! Schema::hasTable('ffb_extremeteam_slot')) {
            Schema::create('ffb_extremeteam_slot', function (Blueprint $table) {
                $table->increments('extremeteam_slot_id');
                $table->unsignedInteger('extremeteam_slot_extremeteam_id');
                $table->unsignedTinyInteger('extremeteam_slot_slot');
                $table->unsignedInteger('extremeteam_slot_playerteam_id');

                $table->unique(
                    ['extremeteam_slot_extremeteam_id', 'extremeteam_slot_slot'],
                    'extremeteam_slot_team_slot_unique',
                );
                $table->index('extremeteam_slot_playerteam_id', 'extremeteam_slot_playerteam_index');
                $table->index('extremeteam_slot_extremeteam_id', 'extremeteam_slot_extremeteam_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ffb_extremeteam_slot');
        Schema::dropIfExists('ffb_extremeteam');
    }
};
