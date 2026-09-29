<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Transfer dates are only kept for the few same-player/same-league duplicate
 * roster pairs; everyone else stores NULL. Admin no longer edits this column.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ffb_playerteam') || ! Schema::hasColumn('ffb_playerteam', 'playerteam_date_transfer')) {
            return;
        }

        DB::statement('ALTER TABLE ffb_playerteam MODIFY playerteam_date_transfer TIMESTAMP NULL DEFAULT NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('ffb_playerteam') || ! Schema::hasColumn('ffb_playerteam', 'playerteam_date_transfer')) {
            return;
        }

        DB::statement('ALTER TABLE ffb_playerteam MODIFY playerteam_date_transfer TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }
};
