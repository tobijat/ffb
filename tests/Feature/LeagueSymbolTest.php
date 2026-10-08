<?php

namespace Tests\Feature;

use App\Support\LeagueSymbol;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeagueSymbolTest extends TestCase
{
    private string $imagesRoot = '';

    private string $leaguesDir = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->imagesRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-league-symbol-'.uniqid('', true);
        $this->leaguesDir = $this->imagesRoot.DIRECTORY_SEPARATOR.'leagues';
        mkdir($this->leaguesDir, 0775, true);
        config(['ffb.legacy_images_path' => $this->imagesRoot]);
    }

    protected function tearDown(): void
    {
        if ($this->leaguesDir !== '' && is_dir($this->leaguesDir)) {
            foreach (glob($this->leaguesDir.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
                @unlink($file);
            }
            @rmdir($this->leaguesDir);
        }
        if ($this->imagesRoot !== '' && is_dir($this->imagesRoot)) {
            @rmdir($this->imagesRoot);
        }

        parent::tearDown();
    }

    #[Test]
    public function url_uses_asset_key_filename_when_file_exists(): void
    {
        file_put_contents($this->leaguesDir.DIRECTORY_SEPARATOR.'nations-league-a1b2.webp', 'x');

        $this->assertSame('/images/ffb/leagues/nations-league-a1b2.webp', LeagueSymbol::url('nations-league-a1b2'));
        $this->assertTrue(LeagueSymbol::exists('nations-league-a1b2'));
    }

    #[Test]
    public function url_defaults_to_na_when_missing(): void
    {
        $this->assertSame('/images/ffb/leagues/na.png', LeagueSymbol::url('missing-zzzz'));
        $this->assertFalse(LeagueSymbol::exists('missing-zzzz'));
        $this->assertSame('/images/ffb/leagues/na.png', LeagueSymbol::url(null));
        $this->assertSame('/images/ffb/leagues/na.png', LeagueSymbol::url(''));
    }

    #[Test]
    public function store_writes_key_based_filename_and_replaces_prior_extension(): void
    {
        $key = 'wm-2026-x9k2';
        file_put_contents($this->leaguesDir.DIRECTORY_SEPARATOR.$key.'.png', 'old');

        $tmp = tempnam(sys_get_temp_dir(), 'ffb-logo-');
        $this->assertNotFalse($tmp);
        file_put_contents($tmp, 'new');

        $upload = new UploadedFile(
            $tmp,
            'logo.webp',
            'image/webp',
            null,
            true,
        );

        $this->assertTrue(LeagueSymbol::store($key, $upload));
        $this->assertFileDoesNotExist($this->leaguesDir.DIRECTORY_SEPARATOR.$key.'.png');
        $this->assertFileExists($this->leaguesDir.DIRECTORY_SEPARATOR.$key.'.webp');
        $this->assertSame('/images/ffb/leagues/'.$key.'.webp', LeagueSymbol::url($key));
    }
}
