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

    public function test_normalize_seed_accepts_digits_and_opaque_tokens(): void
    {
        $this->assertSame('706317194', CustomAvatarSvg::normalizeSeed('706317194'));
        $this->assertSame('12345678901', CustomAvatarSvg::normalizeSeed('12345678901'));
        $opaque = 'k1RYxBDsIgEEXvMm6JYagxKVuP4MI17QyWhAABWhfGuwuNqZnVvP_ff8';
        $this->assertSame($opaque, CustomAvatarSvg::normalizeSeed($opaque));
        $this->assertNull(CustomAvatarSvg::normalizeSeed(''));
        $this->assertNull(CustomAvatarSvg::normalizeSeed('bad seed!'));
    }

    public function test_store_writes_front_and_back_files(): void
    {
        $front = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="5"/></svg>';
        $back = '<svg xmlns="http://www.w3.org/2000/svg"><rect width="5" height="5"/></svg>';
        $name = CustomAvatarSvg::store($front, '706317194', $back);
        $this->assertNotNull($name);
        $this->assertMatchesRegularExpression('/^706317194_[a-f0-9]{8}\.svg$/', $name);

        $dir = $this->imagesRoot.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        $this->assertFileExists($dir.DIRECTORY_SEPARATOR.$name);
        $this->assertSame($front, file_get_contents($dir.DIRECTORY_SEPARATOR.$name));

        $backName = CustomAvatarSvg::backFilename($name);
        $this->assertNotNull($backName);
        $this->assertSame(preg_replace('/\.svg$/', '_back.svg', $name), $backName);
        $this->assertFileExists($dir.DIRECTORY_SEPARATOR.$backName);
        $this->assertSame($back, file_get_contents($dir.DIRECTORY_SEPARATOR.$backName));
    }

    public function test_store_rejects_invalid_seed_or_back(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="5"/></svg>';
        $this->assertNull(CustomAvatarSvg::store($svg, 'bad seed!', $svg));
        $this->assertNull(CustomAvatarSvg::store($svg, '123', 'not-svg'));
    }

    public function test_store_opaque_seed_writes_companion_seed_file(): void
    {
        $front = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="5"/></svg>';
        $back = '<svg xmlns="http://www.w3.org/2000/svg"><rect width="5" height="5"/></svg>';
        $opaque = 'k1RYxBDsIgEEXvMm6JYagxKVuP4MI17QyWhAABWhfGuwuNqZnVvP_ff8';
        $name = CustomAvatarSvg::store($front, $opaque, $back);
        $this->assertNotNull($name);
        $this->assertMatchesRegularExpression('/^k[a-f0-9]{16}_[a-f0-9]{8}\.svg$/', $name);
        $this->assertSame($opaque, CustomAvatarSvg::seedFromFilename($name));

        $dir = $this->imagesRoot.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        $stem = preg_replace('/\.svg$/', '', $name);
        $this->assertFileExists($dir.DIRECTORY_SEPARATOR.$stem.'.seed');

        CustomAvatarSvg::deleteStored($name);
        $this->assertFileDoesNotExist($dir.DIRECTORY_SEPARATOR.$name);
        $this->assertFileDoesNotExist($dir.DIRECTORY_SEPARATOR.$stem.'.seed');
    }

    public function test_seed_from_filename(): void
    {
        $this->assertSame('706317194', CustomAvatarSvg::seedFromFilename('706317194_aabbccdd.svg'));
        $this->assertNull(CustomAvatarSvg::seedFromFilename('shirts/rapid-abcd/aut.svg'));
        $this->assertNull(CustomAvatarSvg::seedFromFilename('avatar_na.png'));
    }

    public function test_public_back_url_for_custom_kit(): void
    {
        $front = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="5"/></svg>';
        $back = '<svg xmlns="http://www.w3.org/2000/svg"><rect width="5" height="5"/></svg>';
        $name = CustomAvatarSvg::store($front, '222', $back);
        $this->assertNotNull($name);
        $backName = CustomAvatarSvg::backFilename($name);
        $this->assertNotNull($backName);
        $this->assertSame('/images/ffb/profiles/avatar/'.$backName, CustomAvatarSvg::publicBackUrl($name));
        $this->assertNull(CustomAvatarSvg::publicBackUrl('shirts/rapid-abcd/aut.svg'));
        $this->assertNull(CustomAvatarSvg::publicBackUrl('avatar_na.png'));
    }

    public function test_delete_stored_removes_front_and_back(): void
    {
        $front = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="5"/></svg>';
        $back = '<svg xmlns="http://www.w3.org/2000/svg"><rect width="5" height="5"/></svg>';
        $name = CustomAvatarSvg::store($front, '111', $back);
        $this->assertNotNull($name);
        $backName = CustomAvatarSvg::backFilename($name);
        $this->assertNotNull($backName);

        $dir = $this->imagesRoot.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        $this->assertFileExists($dir.DIRECTORY_SEPARATOR.$name);
        $this->assertFileExists($dir.DIRECTORY_SEPARATOR.$backName);

        CustomAvatarSvg::deleteStored($name);
        $this->assertFileDoesNotExist($dir.DIRECTORY_SEPARATOR.$name);
        $this->assertFileDoesNotExist($dir.DIRECTORY_SEPARATOR.$backName);
    }

    public function test_delete_stored_ignores_shirts_default_and_legacy(): void
    {
        $dir = $this->imagesRoot.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'avatar';
        File::ensureDirectoryExists($dir);
        $legacy = $dir.DIRECTORY_SEPARATOR.'abcdef0123456789abcdef0123456789.jpg';
        File::put($legacy, 'x');

        $shirtsDir = $this->imagesRoot.DIRECTORY_SEPARATOR.'shirts'.DIRECTORY_SEPARATOR.'rapid-abcd';
        File::ensureDirectoryExists($shirtsDir);
        $teamShirt = $shirtsDir.DIRECTORY_SEPARATOR.'aut.svg';
        File::put($teamShirt, '<svg/>');

        CustomAvatarSvg::deleteStored('avatar_na.png');
        CustomAvatarSvg::deleteStored('shirts/rapid-abcd/aut.svg');
        CustomAvatarSvg::deleteStored('abcdef0123456789abcdef0123456789.jpg');

        $this->assertFileExists($legacy);
        $this->assertFileExists($teamShirt);
        $this->assertFalse(CustomAvatarSvg::isCustomKitFilename('shirts/rapid-abcd/aut.svg'));
        $this->assertFalse(CustomAvatarSvg::isCustomKitFilename('avatar_na.png'));
        $this->assertTrue(CustomAvatarSvg::isCustomKitFilename('111_aabbccdd.svg'));
    }
}
