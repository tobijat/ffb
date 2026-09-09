<?php

namespace Tests\Unit;

use App\Support\Flag;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FlagTest extends TestCase
{
    #[Test]
    public function html_uses_svg_for_mapped_nationalities(): void
    {
        $nation = Flag::html('AUT');
        $this->assertStringContainsString('ffb-flag-svg', $nation);
        $this->assertStringContainsString('vendor/flag-icons/flags/4x3/at.svg', $nation);
        $this->assertStringNotContainsString('ffb-flag-img', $nation);
        $this->assertSame('at', Flag::iso('AUT'));
        $this->assertSame('gb-eng', Flag::iso('ENG'));
        $this->assertSame('si', Flag::iso('SLO'));
        $this->assertSame('si', Flag::iso('SVN'));
    }

    #[Test]
    public function html_falls_back_to_gif_for_club_emblems(): void
    {
        $club = Flag::html('wernberg');
        $this->assertStringContainsString('ffb-flag-img', $club);
        $this->assertStringContainsString('/images/ffb/flags/wernberg.gif', $club);
        $this->assertNull(Flag::iso('wernberg'));
        $this->assertNull(Flag::svgUrl('wernberg'));
    }

    #[Test]
    public function image_url_still_points_at_gif_assets(): void
    {
        $this->assertSame('/images/ffb/flags/na.gif', Flag::imageUrl(''));
        $this->assertSame('/images/ffb/flags/aut.gif', Flag::imageUrl('AUT'));
        $this->assertSame('AUT', Flag::normalize('aut'));
        $this->assertSame('', Flag::normalize('0'));
    }

    #[Test]
    public function every_iso_mapping_has_a_vendored_svg(): void
    {
        /** @var array<string, string> $map */
        $map = config('flag_iso');
        $this->assertNotEmpty($map);

        foreach ($map as $fifa => $iso) {
            $path = public_path('vendor/flag-icons/flags/4x3/'.$iso.'.svg');
            $this->assertFileExists($path, "Missing SVG for {$fifa} → {$iso}");
        }
    }
}
