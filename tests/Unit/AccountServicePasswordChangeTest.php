<?php

namespace Tests\Unit;

use App\Services\AccountService;
use App\Services\FfbPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccountServicePasswordChangeTest extends TestCase
{
    private FfbPassword $passwords;

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        $this->passwords = new FfbPassword;

        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
            $table->string('user_password')->default('');
            $table->string('user_status')->default('active');
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
            'user_email' => 'changer@example.com',
            'user_password' => $this->passwords->hash('oldpass12'),
            'user_status' => 'active',
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    public function test_password_change_requires_current_password(): void
    {
        $result = $this->service()->update(7, [
            'user_password_chg' => 'newpass12ab',
            'user_password_val_chg' => 'newpass12ab',
        ], Request::create('/account', 'POST'));

        $this->assertFalse($result['ok']);
        $this->assertContains('Bitte gib dein aktuelles Passwort ein!', $result['errors']);
    }

    public function test_password_change_rejects_wrong_current_password(): void
    {
        $result = $this->service()->update(7, [
            'user_password_current' => 'wrongpass1',
            'user_password_chg' => 'newpass12ab',
            'user_password_val_chg' => 'newpass12ab',
        ], Request::create('/account', 'POST'));

        $this->assertFalse($result['ok']);
        $this->assertContains('Das aktuelle Passwort ist nicht korrekt!', $result['errors']);
    }

    public function test_password_change_succeeds_with_correct_current_password(): void
    {
        $result = $this->service()->update(7, [
            'user_password_current' => 'oldpass12',
            'user_password_chg' => 'newpass12ab',
            'user_password_val_chg' => 'newpass12ab',
        ], Request::create('/account', 'POST'));

        $this->assertTrue($result['ok']);

        $stored = (string) DB::table('web_user')->where('user_id', 7)->value('user_password');
        $this->assertTrue($this->passwords->verify('newpass12ab', $stored));
    }

    public function test_update_without_password_change_skips_current_password(): void
    {
        $result = $this->service()->update(7, [], Request::create('/account', 'POST'));

        $this->assertTrue($result['ok']);
        $this->assertTrue($this->passwords->verify(
            'oldpass12',
            (string) DB::table('web_user')->where('user_id', 7)->value('user_password')
        ));
    }

    private function service(): AccountService
    {
        return new AccountService($this->passwords);
    }
}
