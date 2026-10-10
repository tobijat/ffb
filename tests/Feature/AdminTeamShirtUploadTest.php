<?php

namespace Tests\Feature;

use App\Services\AdminTeamService;
use App\Support\TeamShirt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

class AdminTeamShirtUploadTest extends TestCase
{
    private string $shirtsDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shirtsDir = storage_path('framework/testing/ffb-shirt-upload-'.uniqid('', true).DIRECTORY_SEPARATOR.'shirts');
        File::ensureDirectoryExists($this->shirtsDir);
        config(['ffb.legacy_images_path' => dirname($this->shirtsDir)]);
    }

    protected function tearDown(): void
    {
        $root = dirname($this->shirtsDir);
        if (is_dir($root)) {
            File::deleteDirectory($root);
        }

        parent::tearDown();
    }

    #[Test]
    public function validate_shirt_upload_accepts_svg(): void
    {
        $file = $this->svgUpload('<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"></svg>');

        $errors = $this->invokePrivate('validateShirtUpload', [$file, 'aut']);

        $this->assertSame([], $errors);
    }

    #[Test]
    public function validate_shirt_upload_rejects_non_svg_content_with_svg_extension(): void
    {
        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-fake-'.uniqid('', true).'.svg';
        file_put_contents($path, 'not an svg at all');
        $file = new UploadedFile($path, 'shirt.svg', 'image/svg+xml', null, true);

        $errors = $this->invokePrivate('validateShirtUpload', [$file, 'aut']);

        $this->assertNotEmpty($errors);
    }

    #[Test]
    public function store_shirt_file_keeps_svg_extension_and_removes_png_sibling(): void
    {
        $teamKey = 'rapid-a1b2';
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey);
        $png = TeamShirt::defaultStoragePath($teamKey, 'aut', 'png');
        File::put($png, 'old-png');

        $file = $this->svgUpload('<svg xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1"/></svg>');
        $ok = $this->invokePrivate('storeShirtFile', [$file, $teamKey, 'aut']);

        $this->assertTrue($ok);
        $svg = TeamShirt::defaultStoragePath($teamKey, 'aut', 'svg');
        $this->assertFileExists($svg);
        $this->assertStringContainsString('<svg', (string) File::get($svg));
        $this->assertFileDoesNotExist($png);
        $this->assertSame('/images/ffb/shirts/'.$teamKey.'/aut.svg', TeamShirt::url($teamKey, 'aut'));
    }

    #[Test]
    public function store_shirt_file_png_replaces_existing_svg(): void
    {
        $teamKey = 'austria-c3d4';
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey);
        $svg = TeamShirt::defaultStoragePath($teamKey, 'aut', 'svg');
        File::put($svg, '<svg xmlns="http://www.w3.org/2000/svg"></svg>');

        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-png-'.uniqid('', true).'.png';
        // Minimal valid 1x1 PNG
        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
        ));
        $file = new UploadedFile($path, 'shirt.png', 'image/png', null, true);

        $ok = $this->invokePrivate('storeShirtFile', [$file, $teamKey, 'aut']);

        $this->assertTrue($ok);
        $this->assertFileExists(TeamShirt::defaultStoragePath($teamKey, 'aut', 'png'));
        $this->assertFileDoesNotExist($svg);
    }

    /**
     * @param  list<mixed>  $args
     */
    private function invokePrivate(string $method, array $args): mixed
    {
        $reflection = new ReflectionMethod(AdminTeamService::class, $method);
        $reflection->setAccessible(true);

        return $reflection->invoke(app(AdminTeamService::class), ...$args);
    }

    private function svgUpload(string $contents): UploadedFile
    {
        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-svg-'.uniqid('', true).'.svg';
        file_put_contents($path, $contents);

        return new UploadedFile($path, 'shirt.svg', 'image/svg+xml', null, true);
    }
}
