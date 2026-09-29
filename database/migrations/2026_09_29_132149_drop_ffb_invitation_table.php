<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Legacy email-invite feature; no application code references this table anymore.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ffb_invitation');
    }

    public function down(): void
    {
        if (Schema::hasTable('ffb_invitation')) {
            return;
        }

        Schema::create('ffb_invitation', function (Blueprint $table) {
            $table->increments('invitation_id');
            $table->integer('invitation_sender_id');
            $table->string('invitation_email');
            $table->dateTime('invitation_date');
        });
    }
};
