<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ffb_teamprice')) {
            return;
        }

        Schema::create('ffb_teamprice', function (Blueprint $table) {
            $table->increments('teamprice_id');
            $table->unsignedInteger('teamprice_team_id');
            $table->unsignedInteger('teamprice_matchround_id');
            $table->double('teamprice_price')->default(0);

            $table->unique(
                ['teamprice_team_id', 'teamprice_matchround_id'],
                'teamprice_team_matchround_unique',
            );
            $table->index('teamprice_matchround_id', 'teamprice_matchround_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ffb_teamprice');
    }
};
