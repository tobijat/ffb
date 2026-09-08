<?php

namespace App\Http\Controllers;

use App\Services\MailUnsubscribeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MailUnsubscribeController extends Controller
{
    public function __construct(
        private readonly MailUnsubscribeService $mailservice,
    ) {
    }

    public function cancel(Request $request): RedirectResponse
    {
        $result = $this->mailservice->cancel(
            (string) $request->query('id', ''),
            (string) $request->query('t', ''),
            $request,
        );

        if ($result['ok']) {
            return redirect()->route('start')->with('account_message', $result['message']);
        }

        return redirect()->route('start')->with(
            'account_message',
            '<strong>Es sind Fehler aufgetreten:</strong><br>'.implode('<br>', $result['errors'] ?? [])
        );
    }
}
