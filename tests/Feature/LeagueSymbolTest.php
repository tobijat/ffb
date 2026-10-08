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
    public function url_uses_league_id_filename_when_file_exists(): void
    {
        file_put_contents($this->leaguesDir.DIRECTORY_SEPARATOR.'12.webp', 'x');

        $this->assertSame('/images/ffb/leagues/12.webp', LeagueSymbol::url(12));
        $this->assertTrue(LeagueSymbol::exists(12));
    }

    #[Test]
    public function url_defaults_to_na_when_missing(): void
    {
        $this->assertSame('/images/ffb/leagues/na.png', LeagueSymbol::url(99));
        $this->assertFalse(LeagueSymbol::exists(99));
        $this->assertSame('/images/ffb/leagues/na.png', LeagueSymbol::url(0));
    }

    #[Test]
    public function store_writes_id_based_filename_and_replaces_prior_extension(): void
    {
        file_put_contents($this->leaguesDir.DIRECTORY_SEPARATOR.'5.png', 'old');

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

        $this->assertTrue(LeagueSymbol::store(5, $upload));
        $this->assertFileDoesNotExist($this->leaguesDir.DIRECTORY_SEPARATOR.'5.png');
        $this->assertFileExists($this->leaguesDir.DIRECTORY_SEPARATOR.'5.webp');
        $this->assertSame('/images/ffb/leagues/5.webp', LeagueSymbol::url(5));
    }
}
