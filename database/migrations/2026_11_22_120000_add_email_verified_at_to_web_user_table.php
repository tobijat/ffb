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

        if (! Schema::hasColumn('web_user', 'email_verified_at')) {
            $this->withRelaxedSqlMode(function (): void {
                DB::statement('ALTER TABLE `web_user` ADD COLUMN `email_verified_at` TIMESTAMP NULL DEFAULT NULL');
            });
        }

        // Existing active accounts are treated as already verified at registration time.
        $this->withRelaxedSqlMode(function (): void {
            DB::update(
                "UPDATE `web_user`
                 SET `email_verified_at` = `user_date_register`
                 WHERE `user_status` = 'active'
                   AND `email_verified_at` IS NULL
                   AND `user_date_register` IS NOT NULL
                   AND `user_date_register` <> '0000-00-00 00:00:00'
                   AND `user_date_register` <> '0000-00-00'"
            );

            DB::table('web_user')
                ->where('user_status', 'active')
                ->whereNull('email_verified_at')
                ->update(['email_verified_at' => now()]);
        });

        // Legacy "na" / unverified actives: pending until email is confirmed.
        DB::table('web_user')
            ->where(function ($query): void {
                $query->where('user_status', 'na')
                    ->orWhere(function ($q): void {
                        $q->where('user_status', 'active')
                            ->whereNull('email_verified_at');
                    });
            })
            ->update([
                'user_status' => 'pending',
                'email_verified_at' => null,
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('web_user') || ! Schema::hasColumn('web_user', 'email_verified_at')) {
            return;
        }

        DB::table('web_user')
            ->whereNull('email_verified_at')
            ->where('user_status', 'active')
            ->update(['user_status' => 'na']);

        $this->withRelaxedSqlMode(function (): void {
            DB::statement('ALTER TABLE `web_user` DROP COLUMN `email_verified_at`');
        });
    }

    /**
     * Legacy web_user rows may contain MySQL zero-dates; relaxing sql_mode
     * lets ALTER TABLE succeed without rewriting those values.
     */
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
