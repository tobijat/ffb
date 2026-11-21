<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ffb_admin')) {
            Schema::drop('ffb_admin');
        }

        if (Schema::hasTable('web_user') && Schema::hasColumn('web_user', 'user_admin')) {
            $this->withRelaxedSqlMode(function (): void {
                Schema::table('web_user', function (Blueprint $table) {
                    $table->dropColumn('user_admin');
                });
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('web_user') && ! Schema::hasColumn('web_user', 'user_admin')) {
            $this->withRelaxedSqlMode(function (): void {
                Schema::table('web_user', function (Blueprint $table) {
                    $table->tinyInteger('user_admin')->default(0)->after('user_status');
                });
            });
        }

        if (! Schema::hasTable('ffb_admin')) {
            Schema::create('ffb_admin', function (Blueprint $table) {
                $table->increments('admin_id');
                $table->integer('admin_user_id');
                $table->integer('admin_league_id');
            });
        }
    }

    /**
     * Legacy web_user rows may contain 0000-00-00 dates; MySQL rejects ALTER under strict sql_mode.
     */
    private function withRelaxedSqlMode(callable $callback): void
    {
        $previous = (string) (DB::selectOne('select @@session.sql_mode as m')?->m ?? '');
        DB::statement("set session sql_mode = ''");

        try {
            $callback();
        } finally {
            DB::statement('set session sql_mode = ?', [$previous]);
        }
    }
};
