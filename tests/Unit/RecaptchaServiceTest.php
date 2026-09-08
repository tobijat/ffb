<?php

namespace Tests\Unit;

use App\Services\RecaptchaService;
use Tests\TestCase;

class RecaptchaServiceTest extends TestCase
{
    public function test_disabled_when_flag_off(): void
    {
        config([
            'ffb.recaptcha.enabled' => false,
            'ffb.recaptcha.site_key' => 'site',
            'ffb.recaptcha.secret_key' => 'secret',
        ]);

        $service = new RecaptchaService;

        $this->assertFalse($service->enabled());
        $this->assertTrue($service->verify(null));
    }

    public function test_enabled_requires_keys_and_flag(): void
    {
        config([
            'ffb.recaptcha.enabled' => true,
            'ffb.recaptcha.site_key' => 'site',
            'ffb.recaptcha.secret_key' => 'secret',
        ]);

        $this->assertTrue((new RecaptchaService)->enabled());

        config(['ffb.recaptcha.secret_key' => '']);
        $this->assertFalse((new RecaptchaService)->enabled());
    }
}
