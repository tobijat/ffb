<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\FfbPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LaravelGuardAuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_admin');
        Schema::dropIfExists('web_user');

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname');
            $table->string('user_email');
            $table->string('user_password');
            $table->string('user_status')->default('active');
            $table->string('user_lip')->nullable();
            $table->string('user_date_llogin')->nullable();
            $table->string('user_date_laction')->nullable();
            $table->string('user_date_register')->nullable();
        });

        Schema::create('ffb_user_details', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->string('user_fname')->default('');
            $table->string('user_lname')->default('');
            $table->string('user_gender')->default('');
            $table->string('user_nationality')->nullable();
            $table->dateTime('user_date_birth')->nullable();
            $table->dateTime('user_details_last_update')->nullable();
            $table->integer('user_details_ffb_selected_league')->default(0);
            $table->integer('user_details_ffb_favourite_team')->default(0);
            $table->string('user_details_avatar')->default('avatar_na.png');
        });

        Schema::create('web_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->unsignedInteger('admin_user_id');
            $table->string('admin_section');
            $table->integer('admin_level')->default(100);
            $table->string('admin_status')->default('active');
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_admin');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    #[Test]
    public function login_authenticates_via_laravel_guard(): void
    {
        $userId = $this->seedUser('guarduser', 'secret1');

        $this->postJson('/login', [
            'user_nickname' => 'guarduser',
            'user_password' => 'secret1',
        ])->assertOk();

        $this->assertAuthenticatedAs(WebUser::query()->findOrFail($userId));
        $this->assertSame($userId, (int) Auth::id());
    }

    #[Test]
    public function acting_as_sets_auth_id(): void
    {
        $this->actingAsFfbUser(544);

        $this->assertAuthenticated();
        $this->assertSame(544, (int) Auth::id());
    }

    #[Test]
    public function logout_clears_laravel_guard(): void
    {
        $userId = $this->seedUser('logoutuser', 'secret1');
        $user = WebUser::query()->findOrFail($userId);

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('start'));

        $this->assertGuest();
        $this->assertSame(0, (int) Auth::id());
    }

    private function seedUser(string $nickname, string $password): int
    {
        $id = (int) WebUser::query()->insertGetId([
            'user_nickname' => $nickname,
            'user_email' => $nickname.'@example.test',
            'user_password' => app(FfbPassword::class)->hash($password),
            'user_status' => 'active',
            'user_date_register' => now()->format('Y-m-d H:i:s'),
            'user_date_llogin' => now()->format('Y-m-d H:i:s'),
            'user_date_laction' => now()->format('Y-m-d H:i:s'),
            'user_lip' => '127.0.0.1',
        ], 'user_id');

        DB::table('ffb_user_details')->insert([
            'user_id' => $id,
            'user_fname' => '',
            'user_lname' => '',
            'user_gender' => '',
            'user_nationality' => '',
            'user_date_birth' => null,
            'user_details_last_update' => now()->format('Y-m-d H:i:s'),
            'user_details_ffb_selected_league' => 25,
            'user_details_ffb_favourite_team' => 1,
            'user_details_avatar' => 'avatar_na.png',
        ]);

        return $id;
    }
}
