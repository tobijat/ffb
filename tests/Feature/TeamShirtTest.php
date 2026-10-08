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
        $teamKey = 'argentina-a1b2';
        $leagueKey = 'copa-america-c3d4';
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey);
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey.DIRECTORY_SEPARATOR.'arg.png', 'default');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey.DIRECTORY_SEPARATOR.'arg-'.$leagueKey.'.png', 'league');

        $this->assertSame('/images/ffb/shirts/'.$teamKey.'/arg-'.$leagueKey.'.png', TeamShirt::url($teamKey, 'ARG', $leagueKey));
        $this->assertSame('/images/ffb/shirts/'.$teamKey.'/arg.png', TeamShirt::url($teamKey, 'arg', 'other-league-zzzz'));
        $this->assertSame('/images/ffb/shirts/'.$teamKey.'/arg.png', TeamShirt::url($teamKey, 'arg'));
        $this->assertNull(TeamShirt::url('missing-team-zzzz', 'arg', $leagueKey));
    }

    #[Test]
    public function url_prefers_svg_over_png_and_accepts_svg_only_shirts(): void
    {
        $teamKey = 'kosovo-k1k2';
        $leagueKey = 'nations-league-n1n2';
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey);
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey.DIRECTORY_SEPARATOR.'kos.svg', 'default-svg');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey.DIRECTORY_SEPARATOR.'kos-'.$leagueKey.'.svg', 'league-svg');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.$teamKey.DIRECTORY_SEPARATOR.'kos.png', 'default-png');

        $this->assertSame('/images/ffb/shirts/'.$teamKey.'/kos-'.$leagueKey.'.svg', TeamShirt::url($teamKey, 'kos', $leagueKey));
        $this->assertSame('/images/ffb/shirts/'.$teamKey.'/kos.svg', TeamShirt::url($teamKey, 'kos', 'other-league-zzzz'));
        $this->assertSame('shirts/'.$teamKey.'/kos-'.$leagueKey.'.svg', TeamShirt::relativePath($teamKey, 'kos', $leagueKey));
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
