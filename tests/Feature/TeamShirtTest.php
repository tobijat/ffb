<?php

namespace Tests\Feature;

use App\Support\TeamShirt;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TeamShirtTest extends TestCase
{
    private string $shirtsDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shirtsDir = storage_path('framework/testing/shirts-'.uniqid('', true));
        File::ensureDirectoryExists($this->shirtsDir);
        config(['ffb.legacy_images_path' => dirname($this->shirtsDir)]);
        // TeamShirt looks for {legacy}/shirts — rename so dir is .../ffb/shirts
        $base = $this->shirtsDir;
        File::deleteDirectory($base);
        $this->shirtsDir = storage_path('framework/testing/ffb-'.uniqid('', true).DIRECTORY_SEPARATOR.'shirts');
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
    public function url_prefers_league_override_then_default(): void
    {
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'76');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'76'.DIRECTORY_SEPARATOR.'arg.png', 'default');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'76'.DIRECTORY_SEPARATOR.'arg-20.png', 'league');

        $this->assertSame('/images/ffb/shirts/76/arg-20.png', TeamShirt::url(76, 'ARG', 20));
        $this->assertSame('/images/ffb/shirts/76/arg.png', TeamShirt::url(76, 'arg', 99));
        $this->assertSame('/images/ffb/shirts/76/arg.png', TeamShirt::url(76, 'arg'));
        $this->assertNull(TeamShirt::url(99, 'arg', 20));
    }

    #[Test]
    public function url_prefers_svg_over_png_and_accepts_svg_only_shirts(): void
    {
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'163');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'163'.DIRECTORY_SEPARATOR.'kos.svg', 'default-svg');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'163'.DIRECTORY_SEPARATOR.'kos-38.svg', 'league-svg');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'163'.DIRECTORY_SEPARATOR.'kos.png', 'default-png');

        $this->assertSame('/images/ffb/shirts/163/kos-38.svg', TeamShirt::url(163, 'kos', 38));
        $this->assertSame('/images/ffb/shirts/163/kos.svg', TeamShirt::url(163, 'kos', 99));
        $this->assertSame('shirts/163/kos-38.svg', TeamShirt::relativePath(163, 'kos', 38));
    }

    #[Test]
    public function blank_urls_prefer_existing_svg(): void
    {
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_BLANK.svg', 'blank');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_BLANK_RED.svg', 'blank-red');

        $this->assertSame('/images/ffb/shirts/shirt_BLANK.svg', TeamShirt::blankUrl());
        $this->assertSame('/images/ffb/shirts/shirt_BLANK_RED.svg', TeamShirt::blankUrl(true));
    }

    #[Test]
    public function legacy_path_finds_uppercase_flat_files(): void
    {
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_AUT.png', 'legacy');

        $this->assertNotNull(TeamShirt::legacyPath('aut'));
        $this->assertSame(
            $this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_AUT.png',
            TeamShirt::legacyPath('aut')
        );
    }
}
