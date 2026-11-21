<?php

namespace Tests\Feature;

use App\Models\WebUser;
use App\Services\FfbAuth;
use App\Services\FfbPassword;
use App\Services\FfbUserResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PictoryHostStubTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::dropIfExists('ffb_user_permissions');
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
            $table->string('user_activation_code')->default('');
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
        });

        Schema::create('ffb_user_permissions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->string('user_permissions_ffb_mailservice_reminder')->nullable();
            $table->string('user_permissions_ffb_mailservice_info')->nullable();
            $table->tinyInteger('user_permissions_ffb_visible_profile')->default(0);
            $table->tinyInteger('user_permissions_pictory_visible_profile')->default(0);
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
        Schema::dropIfExists('ffb_user_permissions');
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_admin');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    #[Test]
    public function pictory_host_shows_stub_home(): void
    {
        $this->get('http://pictory.test/')
            ->assertOk()
            ->assertSee('Pictory', false)
            ->assertSee('Anmelden', false)
            ->assertDontSee('Aktuelle Ligen', false);
    }

    #[Test]
    public function localhost_maps_to_ffb_service(): void
    {
        $hosts = app(\App\Services\AppHostService::class);

        $this->assertSame('ffb', $hosts->serviceFor(
            \Illuminate\Http\Request::create('http://localhost/', 'GET')
        ));
        $this->assertSame('pictory', $hosts->serviceFor(
            \Illuminate\Http\Request::create('http://pictory.test/', 'GET')
        ));
    }

    #[Test]
    public function pictory_register_form_posts_tos_value_expected_by_registration_service(): void
    {
        $this->get('http://pictory.test/registration')
            ->assertOk()
            ->assertSee('name="user_tos"', false)
            ->assertSee('value="user_tos_yes"', false)
            ->assertDontSee('user_fname', false)
            ->assertDontSee('user_lname', false)
            ->assertDontSee('user_nationality', false)
            ->assertDontSee('Geburtsdatum', false);

        $this->post('http://pictory.test/registration', [
            'user_nickname' => 'newpic',
            'user_password' => 'secret1',
            'user_password_val' => 'secret1',
            'user_email' => 'newpic@example.test',
            'user_email_val' => 'newpic@example.test',
            'user_tos' => '1',
        ])
            ->assertOk()
            ->assertSee('Du musst die Bedingungen gelesen und akzeptiert haben!', false);
    }

    #[Test]
    public function pictory_login_uses_shared_web_user(): void
    {
        $hash = app(FfbPassword::class)->hash('secret1');
        $id = (int) WebUser::query()->insertGetId([
            'user_nickname' => 'picuser',
            'user_email' => 'pic@example.test',
            'user_password' => $hash,
            'user_status' => 'active',
            'user_activation_code' => '',
            'user_date_register' => now()->format('Y-m-d H:i:s'),
            'user_date_llogin' => now()->format('Y-m-d H:i:s'),
            'user_date_laction' => now()->format('Y-m-d H:i:s'),
            'user_lip' => '127.0.0.1',
        ], 'user_id');

        $this->mock(FfbUserResolver::class, function ($mock) use ($id) {
            $user = WebUser::query()->find($id);
            $mock->shouldReceive('findActive')->with($id)->andReturn($user);
        });

        $this->post('http://pictory.test/login', [
            'user_nickname' => 'picuser',
            'user_password' => 'secret1',
        ])->assertRedirect();

        $this->get('http://pictory.test/')
            ->assertOk()
            ->assertSee('picuser', false)
            ->assertSee('Abmelden', false);
    }
}
