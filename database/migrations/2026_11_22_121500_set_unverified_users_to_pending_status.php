<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('web_user') || ! Schema::hasColumn('web_user', 'email_verified_at')) {
            return;
        }

        DB::table('web_user')
            ->whereNull('email_verified_at')
            ->whereIn('user_status', ['active', 'na'])
            ->update(['user_status' => 'pending']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('web_user') || ! Schema::hasColumn('web_user', 'email_verified_at')) {
            return;
        }

        DB::table('web_user')
            ->where('user_status', 'pending')
            ->whereNull('email_verified_at')
            ->update(['user_status' => 'active']);
    }
};
