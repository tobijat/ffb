<?php

namespace Tests\Feature;

use App\Models\WebAdmin;
use App\Services\FfbAdminAccess;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FfbAdminAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::dropIfExists('web_admin');
        Schema::create('web_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->unsignedInteger('admin_user_id');
            $table->string('admin_section');
            $table->integer('admin_level');
            $table->string('admin_status');
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('web_admin');
        parent::tearDown();
    }

    #[Test]
    public function is_admin_requires_active_ffb_row(): void
    {
        $access = new FfbAdminAccess;

        $this->assertFalse($access->isAdmin(10));

        WebAdmin::query()->insert([
            'admin_user_id' => 10,
            'admin_section' => 'ffb',
            'admin_level' => 100,
            'admin_status' => 'inactive',
        ]);
        $this->assertFalse($access->isAdmin(10));

        WebAdmin::query()->where('admin_user_id', 10)->update(['admin_status' => 'active']);
        $this->assertTrue($access->isAdmin(10));
        $this->assertFalse($access->canBypassLineupPrivacy(10));
    }

    #[Test]
    public function lineup_privacy_bypass_requires_level_200(): void
    {
        $access = new FfbAdminAccess;

        WebAdmin::query()->insert([
            'admin_user_id' => 11,
            'admin_section' => 'ffb',
            'admin_level' => 199,
            'admin_status' => 'active',
        ]);
        $this->assertTrue($access->isAdmin(11));
        $this->assertFalse($access->canBypassLineupPrivacy(11));

        WebAdmin::query()->where('admin_user_id', 11)->update(['admin_level' => 200]);
        $this->assertTrue($access->isAdmin(11));
        $this->assertTrue($access->canBypassLineupPrivacy(11));
    }

    #[Test]
    public function pictory_section_does_not_grant_ffb_admin(): void
    {
        WebAdmin::query()->insert([
            'admin_user_id' => 12,
            'admin_section' => 'pictory',
            'admin_level' => 200,
            'admin_status' => 'active',
        ]);

        $access = new FfbAdminAccess;
        $this->assertFalse($access->isAdmin(12));
        $this->assertFalse($access->canBypassLineupPrivacy(12));
    }
}
