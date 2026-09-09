<?php

namespace Tests\Unit;

use App\Services\FfbPassword;
use App\Services\RegistrationService;
use Tests\TestCase;

class RegistrationServiceValidationTest extends TestCase
{
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
}
