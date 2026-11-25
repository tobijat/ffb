<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('web_user') || ! Schema::hasColumn('web_user', 'user_mailservice')) {
            return;
        }

        $this->withRelaxedSqlMode(function (): void {
            Schema::table('web_user', function (Blueprint $table) {
                $table->dropColumn('user_mailservice');
            });
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('web_user') || Schema::hasColumn('web_user', 'user_mailservice')) {
            return;
        }

        $this->withRelaxedSqlMode(function (): void {
            Schema::table('web_user', function (Blueprint $table) {
                $table->string('user_mailservice')->nullable()->after('user_date_register');
            });
        });
    }

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
