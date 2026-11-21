<?php

namespace Tests\Unit;

use App\Services\FfbPassword;
use Tests\TestCase;

class FfbPasswordTest extends TestCase
{
    public function test_verifies_legacy_md5(): void
    {
        $passwords = new FfbPassword;
        $hash = md5('secret');

        $this->assertTrue($passwords->isLegacyMd5($hash));
        $this->assertTrue($passwords->verify('secret', $hash));
        $this->assertFalse($passwords->verify('wrong', $hash));
        $this->assertTrue($passwords->needsRehash($hash));
    }

    public function test_verifies_modern_hash(): void
    {
        $passwords = new FfbPassword;
        $hash = $passwords->hash('secret');

        $this->assertFalse($passwords->isLegacyMd5($hash));
        $this->assertTrue($passwords->verify('secret', $hash));
        $this->assertFalse($passwords->verify('wrong', $hash));
    }

    public function test_rejects_empty_hash(): void
    {
        $passwords = new FfbPassword;

        $this->assertFalse($passwords->verify('secret', null));
        $this->assertFalse($passwords->verify('secret', ''));
        $this->assertTrue($passwords->needsRehash(null));
    }

    public function test_validate_plain_enforces_length_and_digit_or_special(): void
    {
        $passwords = new FfbPassword;

        $this->assertNotEmpty($passwords->validatePlain('short1'));
        $this->assertNotEmpty($passwords->validatePlain('abcdefghij'));
        $this->assertSame([], $passwords->validatePlain('abcdefghij1'));
        $this->assertSame([], $passwords->validatePlain('abcdefghij!'));
        $this->assertContains(
            'Passwortänderung: muss mindestens eine Zahl oder ein Sonderzeichen enthalten!',
            $passwords->validatePlain('abcdefghij', 'Passwortänderung')
        );
    }
}
