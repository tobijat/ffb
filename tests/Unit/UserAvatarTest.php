<?php

namespace Tests\Unit;

use App\Support\UserAvatar;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    #[Test]
    public function it_detects_shirt_paths(): void
    {
        $this->assertTrue(UserAvatar::isShirtPath('shirts/osterreich-tnuh/aut.svg'));
        $this->assertFalse(UserAvatar::isShirtPath('avatar_na.png'));
        $this->assertFalse(UserAvatar::isShirtPath('shirts/../secret.svg'));
        $this->assertFalse(UserAvatar::isShirtPath('custom-upload.jpg'));
    }

    #[Test]
    public function it_resolves_legacy_and_default_urls(): void
    {
        $this->assertSame(
            '/images/ffb/profiles/avatar/avatar_na.png',
            UserAvatar::publicUrl(null)
        );
        $this->assertSame(
            '/images/ffb/profiles/avatar/avatar_na.png',
            UserAvatar::publicUrl('avatar_na.png')
        );
        $this->assertSame(
            '/images/ffb/profiles/avatar/abc123.jpg',
            UserAvatar::publicUrl('abc123.jpg')
        );
    }

    #[Test]
    public function it_resolves_shirt_url_when_file_exists(): void
    {
        $path = 'shirts/osterreich-tnuh/aut.svg';
        if (! is_file(public_path('images/ffb/'.$path))) {
            $this->markTestSkipped('Sample shirt SVG not present in public/images/ffb');
        }

        $this->assertSame('/images/ffb/'.$path, UserAvatar::publicUrl($path));
    }
}
