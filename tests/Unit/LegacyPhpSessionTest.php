<?php

namespace Tests\Unit;

use App\Services\LegacyPhpSession;
use Tests\TestCase;

class LegacyPhpSessionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['ffb.legacy_session_name' => 'FFB_TEST_PHPSESSID']);
    }

    protected function tearDown(): void
    {
        $session = new LegacyPhpSession;
        $session->forget();
        parent::tearDown();
    }

    public function test_put_and_get_roundtrip(): void
    {
        $session = new LegacyPhpSession;
        $session->put([
            'user_id' => 544,
            'admin_flag' => 1,
            'admin_section' => 'ffb',
        ]);

        $this->assertSame(544, $session->get('user_id'));
        $this->assertSame(1, $session->get('admin_flag'));
        $this->assertSame('ffb', $session->get('admin_section'));
    }

    public function test_forget_clears_values(): void
    {
        $session = new LegacyPhpSession;
        $session->put(['user_id' => 544, 'admin_flag' => 1]);
        $session->forget();

        $this->assertNull($session->get('user_id'));
        $this->assertNull($session->get('admin_flag'));
    }
}
