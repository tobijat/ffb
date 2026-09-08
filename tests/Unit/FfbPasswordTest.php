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
}
