<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReferencePageTest extends TestCase
{
    public function test_reference_page_renders_for_guests(): void
    {
        $this->get('/reference')
            ->assertOk()
            ->assertSee('Impressum', false)
            ->assertSee('Tobias Gritschacher', false)
            ->assertSee('/platform/reference', false);
    }
}
