<?php

namespace Tests\Feature;

use App\Mail\AccountActivationMail;
use App\Mail\EmailChangeMail;
use App\Models\WebUser;
use App\Services\AccountService;
use App\Services\FfbPassword;
use App\Services\FfbUserResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailChangePendingTest extends TestCase
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
        RateLimiter::clear('email-change-resend:7');
        RateLimiter::clear('email-change-resend:9');

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
            $table->string('user_date_laction')->nullable();
        });

        Schema::create('ffb_user_details', function (Blueprint $table) {
            $table->increments('user_details_id');
            $table->unsignedInteger('user_id');
            $table->string('user_fname')->nullable();
            $table->string('user_lname')->nullable();
            $table->string('user_nationality')->nullable();
            $table->string('user_date_birth')->nullable();
            $table->string('user_details_avatar')->nullable();
            $table->string('user_details_zip')->nullable();
            $table->string('user_details_city')->nullable();
            $table->string('user_details_street')->nullable();
            $table->string('user_details_phone')->nullable();
            $table->string('user_details_website')->nullable();
        });

        DB::table('web_user')->insert([
            'user_id' => 7,
            'user_nickname' => 'changer',
            'user_email' => 'old@example.com',
            'user_email_pending' => null,
            'email_verified_at' => now(),
            'user_password' => $this->passwords->hash('secret12ab'),
            'user_status' => 'active',
        ]);
        DB::table('ffb_user_details')->insert(['user_id' => 7]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    public function test_email_change_stores_pending_and_keeps_current_email(): void
    {
        Mail::fake();

        $user = WebUser::query()->find(7);
        $this->actingAs($user);

        $result = app(AccountService::class)->update(7, [
            'user_email_chg' => 'new@example.com',
            'user_email_val_chg' => 'new@example.com',
        ], Request::create('/account', 'POST'));

        $this->assertTrue($result['ok']);
        $this->assertAuthenticated();
        $this->assertStringContainsString('Bestätigungs-Mail', $result['message']);
        $this->assertStringNotContainsString('zuvor angeforderter Wechsel', $result['message']);

        $row = DB::table('web_user')->where('user_id', 7)->first();
        $this->assertSame('old@example.com', $row->user_email);
        $this->assertSame('new@example.com', $row->user_email_pending);
        $this->assertSame('active', $row->user_status);
        $this->assertNotNull($row->email_verified_at);

        Mail::assertSent(EmailChangeMail::class, function (EmailChangeMail $mail) {
            return str_contains($mail->activationUrl, 'signature=')
                && str_contains($mail->activationUrl, 'hash='.sha1('new@example.com'));
        });
    }

    public function test_second_email_change_overwrites_pending_and_invalidates_old_link(): void
    {
        Mail::fake();

        $user = WebUser::query()->find(7);
        $this->actingAs($user);

        $service = app(AccountService::class);
        $request = Request::create('/account', 'POST');

        $first = $service->update(7, [
            'user_email_chg' => 'first@example.com',
            'user_email_val_chg' => 'first@example.com',
        ], $request);
        $this->assertTrue($first['ok']);

        $second = $service->update(7, [
            'user_email_chg' => 'second@example.com',
            'user_email_val_chg' => 'second@example.com',
        ], $request);
        $this->assertTrue($second['ok']);
        $this->assertStringContainsString('zuvor angeforderter Wechsel', $second['message']);

        $row = DB::table('web_user')->where('user_id', 7)->first();
        $this->assertSame('old@example.com', $row->user_email);
        $this->assertSame('second@example.com', $row->user_email_pending);

        Mail::assertSent(EmailChangeMail::class, 2);

        $staleUrl = URL::temporarySignedRoute(
            'registration.activate-email',
            now()->addHour(),
            ['id' => 7, 'hash' => sha1('first@example.com')]
        );
        $this->get($staleUrl)
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', function (string $message): bool {
                return str_contains($message, 'ungültig');
            });

        $row = DB::table('web_user')->where('user_id', 7)->first();
        $this->assertSame('old@example.com', $row->user_email);
        $this->assertSame('second@example.com', $row->user_email_pending);

        $freshUrl = URL::temporarySignedRoute(
            'registration.activate-email',
            now()->addHour(),
            ['id' => 7, 'hash' => sha1('second@example.com')]
        );
        $this->get($freshUrl)
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', function (string $message): bool {
                return str_contains($message, 'bestätigt');
            });

        $row = DB::table('web_user')->where('user_id', 7)->first();
        $this->assertSame('second@example.com', $row->user_email);
        $this->assertNull($row->user_email_pending);
    }

    public function test_same_pending_email_resends_and_throttles(): void
    {
        Mail::fake();

        DB::table('web_user')->where('user_id', 7)->update([
            'user_email_pending' => 'new@example.com',
        ]);

        $user = WebUser::query()->find(7);
        $this->actingAs($user);
        $service = app(AccountService::class);
        $request = Request::create('/account', 'POST');
        $payload = [
            'user_email_chg' => 'new@example.com',
            'user_email_val_chg' => 'new@example.com',
        ];

        $first = $service->update(7, $payload, $request);
        $this->assertTrue($first['ok']);
        $this->assertStringContainsString('erneut', $first['message']);
        Mail::assertSent(EmailChangeMail::class, 1);

        $throttled = $service->update(7, $payload, $request);
        $this->assertFalse($throttled['ok']);
        $this->assertSame(429, $throttled['status']);
        Mail::assertSent(EmailChangeMail::class, 1);
    }

    public function test_unverified_email_change_updates_email_and_sends_activation_mail(): void
    {
        Mail::fake();

        DB::table('web_user')->insert([
            'user_id' => 9,
            'user_nickname' => 'newbie',
            'user_email' => 'typo@example.com',
            'user_email_pending' => 'stale@example.com',
            'email_verified_at' => null,
            'user_password' => $this->passwords->hash('secret12ab'),
            'user_status' => 'pending',
        ]);
        DB::table('ffb_user_details')->insert(['user_id' => 9]);

        $user = WebUser::query()->find(9);
        $this->actingAs($user);

        $result = app(AccountService::class)->update(9, [
            'user_email_chg' => 'fixed@example.com',
            'user_email_val_chg' => 'fixed@example.com',
        ], Request::create('/account', 'POST'));

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('Aktivierungs-Link', $result['message']);

        $row = DB::table('web_user')->where('user_id', 9)->first();
        $this->assertSame('fixed@example.com', $row->user_email);
        $this->assertNull($row->user_email_pending);
        $this->assertSame('pending', $row->user_status);
        $this->assertNull($row->email_verified_at);

        Mail::assertSent(AccountActivationMail::class, function (AccountActivationMail $mail) {
            return str_contains($mail->activationUrl, 'signature=')
                && str_contains($mail->activationUrl, 'hash='.sha1('fixed@example.com'));
        });
        Mail::assertNotSent(EmailChangeMail::class);

        $oldUrl = URL::temporarySignedRoute(
            'registration.activate',
            now()->addHour(),
            ['id' => 9, 'hash' => sha1('typo@example.com')]
        );
        $this->get($oldUrl)
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', function (string $message): bool {
                return str_contains($message, 'ungültig');
            });

        $newUrl = URL::temporarySignedRoute(
            'registration.activate',
            now()->addHour(),
            ['id' => 9, 'hash' => sha1('fixed@example.com')]
        );
        $this->get($newUrl)
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message', function (string $message): bool {
                return str_contains($message, 'aktiviert');
            });

        $row = DB::table('web_user')->where('user_id', 9)->first();
        $this->assertSame('active', $row->user_status);
        $this->assertNotNull($row->email_verified_at);
        $this->assertNull($row->user_email_pending);
    }

    public function test_signed_email_change_applies_pending_address(): void
    {
        DB::table('web_user')->where('user_id', 7)->update([
            'user_email_pending' => 'new@example.com',
        ]);

        $url = URL::temporarySignedRoute(
            'registration.activate-email',
            now()->addHour(),
            ['id' => 7, 'hash' => sha1('new@example.com')]
        );

        $this->get($url)
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message');

        $row = DB::table('web_user')->where('user_id', 7)->first();
        $this->assertSame('new@example.com', $row->user_email);
        $this->assertNull($row->user_email_pending);
        $this->assertSame('active', $row->user_status);
        $this->assertNotNull($row->email_verified_at);
        $this->assertAuthenticated();
        $this->assertSame(7, (int) Auth::id());
    }

    public function test_email_change_rejects_taken_address(): void
    {
        DB::table('web_user')->insert([
            'user_id' => 8,
            'user_nickname' => 'other',
            'user_email' => 'taken@example.com',
            'email_verified_at' => now(),
            'user_password' => $this->passwords->hash('secret12ab'),
            'user_status' => 'active',
        ]);

        $user = WebUser::query()->find(7);
        $this->mock(FfbUserResolver::class, function ($mock) use ($user) {
            $mock->shouldReceive('findPresent')->with(7)->andReturn($user);
            $mock->shouldReceive('findActive')->with(7)->andReturn($user);
            $mock->shouldReceive('findVerified')->with(7)->andReturn($user);
        });
        $this->actingAs($user);

        $result = app(AccountService::class)->update(7, [
            'user_email_chg' => 'taken@example.com',
            'user_email_val_chg' => 'taken@example.com',
        ], Request::create('/account', 'POST'));

        $this->assertFalse($result['ok']);
        $this->assertContains('Die von dir gewählte neue E-Mail Adresse existiert bereits!', $result['errors']);
    }
}
