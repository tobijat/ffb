<?php

namespace Tests\Unit;

use App\Support\CustomAvatarSvg;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CustomAvatarSvgTest extends TestCase
{
    private string $imagesRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imagesRoot = storage_path('framework/testing/avatar-svg-'.uniqid('', true));
        File::ensureDirectoryExists($this->imagesRoot);
        config(['ffb.legacy_images_path' => $this->imagesRoot]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->imagesRoot);
        parent::tearDown();
    }

    public function test_accepts_plain_svg(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><rect width="10" height="10" fill="#123"/></svg>';
        $this->assertTrue(CustomAvatarSvg::isAcceptable($svg));
    }

    public function test_rejects_script_and_handlers(): void
    {
        $this->assertFalse(CustomAvatarSvg::isAcceptable('<svg><script>alert(1)</script></svg>'));
        $this->assertFalse(CustomAvatarSvg::isAcceptable('<svg onclick="x()"></svg>'));
        $this->assertFalse(CustomAvatarSvg::isAcceptable('<svg><a href="javascript:alert(1)"></a></svg>'));
        $this->assertFalse(CustomAvatarSvg::isAcceptable('not svg'));
    }

    public function test_store_writes_md5_named_file(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="5"/></svg>';
        $name = CustomAvatarSvg::store($svg);
        $this->assertNotNull($name);
        $this->assertSame(md5($svg).'.svg', $name);
        $path = $this->imagesRoot.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$name;
        $this->assertFileExists($path);
        $this->assertSame($svg, file_get_contents($path));
    }
}
