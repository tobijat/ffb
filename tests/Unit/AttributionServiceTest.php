<?php

namespace Tests\Unit;

use App\Services\AttributionService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttributionServiceTest extends TestCase
{
    #[Test]
    public function it_rejects_non_commons_urls(): void
    {
        $service = new AttributionService;

        $result = $service->forCommonsUrl('https://example.com/wiki/File:Foo.jpg');

        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['status']);
    }

    #[Test]
    public function it_rejects_empty_urls(): void
    {
        $service = new AttributionService;

        $result = $service->forCommonsUrl('   ');

        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['status']);
    }

    #[Test]
    public function it_rejects_commons_urls_that_are_not_file_pages(): void
    {
        $service = new AttributionService;

        $result = $service->forCommonsUrl('https://commons.wikimedia.org/wiki/Main_Page');

        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['status']);
    }
}
