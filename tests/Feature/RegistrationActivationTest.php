<?php

namespace Tests\Feature;

use App\Mail\AccountActivationMail;
use App\Models\WebUser;
use App\Services\FfbPassword;
use App\Services\FfbUserResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationActivationTest extends TestCase
{
    private FfbPassword $passwords;

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        config(['mail.default' => 'array']);
        $this->passwords = new FfbPassword;

        Schema::dropIfExists('ffb_user_permissions');
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
            $table->string('user_email_pending')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_password')->default('');
            $table->string('user_status')->default('active');
            $table->string('user_activation_code')->nullable();
            $table->string('user_lip')->nullable();
            $table->string('user_date_register')->nullable();
            $table->string('user_date_llogin')->nullable();
            $table->string('user_date_laction')->nullable();
        });

        Schema::create('ffb_user_details', function (Blueprint $table) {
            $table->increments('user_details_id');
            $table->unsignedInteger('user_id');
            $table->string('user_fname')->nullable();
            $table->string('user_lname')->nullable();
            $table->string('user_gender')->nullable();
            $table->string('user_nationality')->nullable();
            $table->string('user_date_birth')->nullable();
            $table->integer('user_details_ffb_selected_league')->default(0);
            $table->integer('user_details_ffb_favourite_team')->default(0);
            $table->string('user_details_last_update')->nullable();
        });

        Schema::create('ffb_user_permissions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->string('user_permissions_ffb_mailservice_reminder')->nullable();
            $table->string('user_permissions_ffb_mailservice_info')->nullable();
            $table->boolean('user_permissions_ffb_visible_profile')->default(false);
            $table->boolean('user_permissions_pictory_visible_profile')->default(false);
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_user_permissions');
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    public function test_register_logs_in_sends_signed_activation_mail_and_redirects(): void
    {
        Mail::fake();

        $this->post('/registration', [
            'user_nickname' => 'newbie',
            'user_password' => 'secret12ab',
            'user_password_val' => 'secret12ab',
            'user_email' => 'newbie@example.com',
            'user_email_val' => 'newbie@example.com',
            'user_tos' => 'user_tos_yes',
        ])
            ->assertRedirect(route('start'))
            ->assertSessionHas('show_activation_modal', true);

        $this->assertAuthenticated();
        $user = WebUser::query()->where('user_nickname', 'newbie')->first();
        $this->assertNotNull($user);
        $this->assertSame('pending', $user->user_status);
        $this->assertNull($user->email_verified_at);
        $this->assertSame(0, (int) DB::table('ffb_user_details')->where('user_id', $user->user_id)->value('user_details_ffb_selected_league'));

        Mail::assertSent(AccountActivationMail::class, function (AccountActivationMail $mail) use ($user) {
            return $mail->nickname === 'newbie'
                && str_contains($mail->activationUrl, 'signature=')
                && str_contains($mail->activationUrl, 'id='.$user->user_id)
                && str_contains($mail->activationUrl, 'hash='.sha1('newbie@example.com'));
        });
    }

    public function test_signed_activation_activates_and_keeps_session(): void
    {
        $userId = (int) DB::table('web_user')->insertGetId([
            'user_nickname' => 'pending',
            'user_email' => 'pending@example.com',
            'user_password' => $this->passwords->hash('secret12ab'),
            'user_status' => 'pending',
            'email_verified_at' => null,
            'user_activation_code' => '',
        ], 'user_id');

        $url = URL::temporarySignedRoute(
            'registration.activate',
            now()->addHour(),
            ['id' => $userId, 'hash' => sha1('pending@example.com')]
        );

        $this->get($url)
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message');

        $this->assertAuthenticated();
        $this->assertSame(Auth::id(), $userId);
        $this->assertSame('active', (string) DB::table('web_user')->where('user_id', $userId)->value('user_status'));
        $this->assertNotNull(DB::table('web_user')->where('user_id', $userId)->value('email_verified_at'));
    }

    public function test_expired_or_tampered_activation_fails(): void
    {
        $userId = (int) DB::table('web_user')->insertGetId([
            'user_nickname' => 'pending',
            'user_email' => 'pending@example.com',
            'user_password' => $this->passwords->hash('secret12ab'),
            'user_status' => 'pending',
            'email_verified_at' => null,
            'user_activation_code' => '',
        ], 'user_id');

        $bad = URL::temporarySignedRoute(
            'registration.activate',
            now()->addHour(),
            ['id' => $userId, 'hash' => sha1('other@example.com')]
        );

        $this->get($bad)
            ->assertRedirect(route('start'));

        $this->assertNull(DB::table('web_user')->where('user_id', $userId)->value('email_verified_at'));
    }

    public function test_unverified_user_can_login_and_gets_activation_flash(): void
    {
        DB::table('web_user')->insert([
            'user_id' => 9,
            'user_nickname' => 'pending',
            'user_email' => 'pending@example.com',
            'user_password' => $this->passwords->hash('secret12ab'),
            'user_status' => 'pending',
            'email_verified_at' => null,
            'user_activation_code' => '',
        ]);

        $this->postJson('/login', [
            'user_nickname' => 'pending',
            'user_password' => 'secret12ab',
        ])
            ->assertOk()
            ->assertJsonPath('status', 200);

        $this->assertAuthenticated();
        $this->assertTrue(session()->has('show_activation_modal'));
    }

    public function test_resend_activation_is_throttled(): void
    {
        Mail::fake();

        $user = new WebUser;
        $user->user_id = 11;
        $user->user_nickname = 'pending';
        $user->user_email = 'pending@example.com';
        $user->user_password = $this->passwords->hash('secret12ab');
        $user->user_status = 'pending';
        $user->email_verified_at = null;
        $user->exists = true;

        DB::table('web_user')->insert([
            'user_id' => 11,
            'user_nickname' => 'pending',
            'user_email' => 'pending@example.com',
            'user_password' => $user->user_password,
            'user_status' => 'pending',
            'email_verified_at' => null,
            'user_activation_code' => '',
        ]);

        $this->mock(FfbUserResolver::class, function ($mock) use ($user) {
            $mock->shouldReceive('findPresent')->with(11)->andReturn($user);
            $mock->shouldReceive('findActive')->with(11)->andReturn($user);
            $mock->shouldReceive('findVerified')->with(11)->andReturn(null);
        });

        $this->actingAs($user)
            ->postJson('/registration/activation/resend')
            ->assertOk();

        Mail::assertSent(AccountActivationMail::class);

        $this->actingAs($user)
            ->postJson('/registration/activation/resend')
            ->assertStatus(429);
    }
}
