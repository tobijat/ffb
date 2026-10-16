<?php

namespace Tests\Unit;

use App\Services\FfbPassword;
use App\Services\RegistrationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RegistrationServiceValidationTest extends TestCase
{
    private function withUsersTable(): void
    {
        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
        });
    }

    public function test_validate_requires_required_fields(): void
    {
        $service = new RegistrationService(new FfbPassword);
        $errors = $service->validate([]);

        $this->assertContains(
            'Du musst alle Felder ausfüllen, die mit einem * markiert sind!',
            $errors
        );
    }

    public function test_validate_checks_password_mismatch(): void
    {
        $service = new RegistrationService(new FfbPassword);
        $errors = $service->validate([
            'user_nickname' => 'tester',
            'user_password' => 'secret1',
            'user_password_val' => 'secret2',
            'user_email' => 'a@example.com',
            'user_email_val' => 'a@example.com',
            'user_tos' => 'user_tos_yes',
        ]);

        $this->assertContains('Die Passwörter stimmen nicht überein!', $errors);
    }

    public function test_validate_rejects_consecutive_special_characters_in_nickname(): void
    {
        $this->withUsersTable();
        $service = new RegistrationService(new FfbPassword);
        $base = [
            'user_password' => 'secret1',
            'user_password_val' => 'secret1',
            'user_email' => 'a@example.com',
            'user_email_val' => 'a@example.com',
            'user_tos' => 'user_tos_yes',
        ];

        $this->assertContains(
            'Sonderzeichen dürfen im Benutzernamen nicht zweimal hintereinander stehen!',
            $service->validate($base + ['user_nickname' => 'to##bi'])
        );
        $this->assertContains(
            'Sonderzeichen dürfen im Benutzernamen nicht zweimal hintereinander stehen!',
            $service->validate($base + ['user_nickname' => 'a  b'])
        );

        $ok = $service->validate($base + ['user_nickname' => 'to#b#i']);
        $this->assertNotContains(
            'Sonderzeichen dürfen im Benutzernamen nicht zweimal hintereinander stehen!',
            $ok
        );
    }

    public function test_validate_allows_unicode_letters_in_nickname(): void
    {
        $this->withUsersTable();
        $service = new RegistrationService(new FfbPassword);
        $errors = $service->validate([
            'user_nickname' => '太郎くん',
            'user_password' => 'secret1',
            'user_password_val' => 'secret1',
            'user_email' => 'a@example.com',
            'user_email_val' => 'a@example.com',
            'user_tos' => 'user_tos_yes',
        ]);

        $this->assertNotContains(
            'Benutzername: min. Länge ist 3, max. Länge ist 16!',
            $errors
        );
        $this->assertNotContains(
            'Sonderzeichen dürfen im Benutzernamen nicht zweimal hintereinander stehen!',
            $errors
        );
    }
}
