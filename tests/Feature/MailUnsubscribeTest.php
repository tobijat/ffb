<?php

namespace Tests\Feature;

use App\Services\MailUnsubscribeService;
use Tests\TestCase;

class MailUnsubscribeTest extends TestCase
{
    public function test_cancel_redirects_with_message(): void
    {
        $this->mock(MailUnsubscribeService::class, function ($mock) {
            $mock->shouldReceive('cancel')->once()->andReturn([
                'ok' => true,
                'message' => 'Du bekommst in Zukunft keine Erinnerungsmails mehr.',
            ]);
        });

        $this->get('/mailservice/cancel?t=r&id=abc-1')
            ->assertRedirect(route('start'))
            ->assertSessionHas('account_message');
    }
}
