<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('web_user')) {
            return;
        }

        if (! Schema::hasColumn('web_user', 'user_email_pending')) {
            $this->withRelaxedSqlMode(function (): void {
                DB::statement('ALTER TABLE `web_user` ADD COLUMN `user_email_pending` VARCHAR(120) NULL DEFAULT NULL AFTER `user_email`');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('web_user') || ! Schema::hasColumn('web_user', 'user_email_pending')) {
            return;
        }

        $this->withRelaxedSqlMode(function (): void {
            DB::statement('ALTER TABLE `web_user` DROP COLUMN `user_email_pending`');
        });
    }

    private function withRelaxedSqlMode(callable $callback): void
    {
        $previous = DB::selectOne('SELECT @@SESSION.sql_mode AS sql_mode');
        $mode = (string) ($previous->sql_mode ?? '');

        DB::statement("SET SESSION sql_mode = ''");

        try {
            $callback();
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$mode]);
        }
    }
};
