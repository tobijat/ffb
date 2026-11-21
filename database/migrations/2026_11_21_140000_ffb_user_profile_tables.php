<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('web_user_details') && ! Schema::hasTable('ffb_user_details')) {
            Schema::rename('web_user_details', 'ffb_user_details');
        }

        if (Schema::hasTable('web_user_permissions') && ! Schema::hasTable('ffb_user_permissions')) {
            Schema::rename('web_user_permissions', 'ffb_user_permissions');
        }

        if (Schema::hasTable('ffb_user_details') && ! Schema::hasColumn('ffb_user_details', 'user_fname')) {
            $this->withRelaxedSqlMode(function (): void {
                Schema::table('ffb_user_details', function (Blueprint $table) {
                    $table->string('user_fname')->default('')->after('user_id');
                    $table->string('user_lname')->default('')->after('user_fname');
                    $table->string('user_gender')->default('')->after('user_lname');
                    $table->string('user_nationality')->nullable()->after('user_gender');
                    $table->dateTime('user_date_birth')->nullable()->after('user_nationality');
                });
            });
        }

        if (
            Schema::hasTable('web_user')
            && Schema::hasTable('ffb_user_details')
            && Schema::hasColumn('web_user', 'user_fname')
        ) {
            $this->ensureDetailsRowsForAllUsers();

            DB::statement('
                UPDATE ffb_user_details AS d
                INNER JOIN web_user AS u ON u.user_id = d.user_id
                SET
                    d.user_fname = COALESCE(u.user_fname, \'\'),
                    d.user_lname = COALESCE(u.user_lname, \'\'),
                    d.user_gender = COALESCE(u.user_gender, \'\'),
                    d.user_nationality = u.user_nationality,
                    d.user_date_birth = NULLIF(u.user_date_birth, \'0000-00-00 00:00:00\')
            ');

            $this->withRelaxedSqlMode(function (): void {
                Schema::table('web_user', function (Blueprint $table) {
                    $table->dropColumn([
                        'user_fname',
                        'user_lname',
                        'user_gender',
                        'user_nationality',
                        'user_date_birth',
                    ]);
                });
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('web_user')
            && Schema::hasTable('ffb_user_details')
            && ! Schema::hasColumn('web_user', 'user_fname')
            && Schema::hasColumn('ffb_user_details', 'user_fname')
        ) {
            $this->withRelaxedSqlMode(function (): void {
                Schema::table('web_user', function (Blueprint $table) {
                    $table->string('user_fname')->default('')->after('user_email');
                    $table->string('user_lname')->default('')->after('user_fname');
                    $table->string('user_gender')->default('')->after('user_lname');
                    $table->string('user_nationality')->nullable()->after('user_status');
                    $table->dateTime('user_date_birth')->nullable()->after('user_nationality');
                });
            });

            DB::statement('
                UPDATE web_user AS u
                INNER JOIN ffb_user_details AS d ON d.user_id = u.user_id
                SET
                    u.user_fname = COALESCE(d.user_fname, \'\'),
                    u.user_lname = COALESCE(d.user_lname, \'\'),
                    u.user_gender = COALESCE(d.user_gender, \'\'),
                    u.user_nationality = d.user_nationality,
                    u.user_date_birth = d.user_date_birth
            ');

            $this->withRelaxedSqlMode(function (): void {
                Schema::table('ffb_user_details', function (Blueprint $table) {
                    $table->dropColumn([
                        'user_fname',
                        'user_lname',
                        'user_gender',
                        'user_nationality',
                        'user_date_birth',
                    ]);
                });
            });
        }

        if (Schema::hasTable('ffb_user_details') && ! Schema::hasTable('web_user_details')) {
            Schema::rename('ffb_user_details', 'web_user_details');
        }

        if (Schema::hasTable('ffb_user_permissions') && ! Schema::hasTable('web_user_permissions')) {
            Schema::rename('ffb_user_permissions', 'web_user_permissions');
        }
    }

    private function ensureDetailsRowsForAllUsers(): void
    {
        $now = now()->format('Y-m-d H:i:s');
        $missingIds = DB::table('web_user')
            ->leftJoin('ffb_user_details', 'ffb_user_details.user_id', '=', 'web_user.user_id')
            ->whereNull('ffb_user_details.user_id')
            ->pluck('web_user.user_id');

        foreach ($missingIds as $userId) {
            DB::table('ffb_user_details')->insert([
                'user_id' => (int) $userId,
                'user_details_last_update' => $now,
                'user_details_ffb_selected_league' => 0,
                'user_fname' => '',
                'user_lname' => '',
                'user_gender' => '',
                'user_nationality' => null,
                'user_date_birth' => null,
            ]);
        }
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
