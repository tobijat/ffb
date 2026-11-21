<?php

namespace App\Http\Controllers;

use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationPageController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registration,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $payload = $this->registration->pagePayload();

        return view('registration', [
            'data' => $payload['data'],
            'legacyBase' => '/',
            'errors' => [],
            'answer' => null,
            'destination' => (string) $request->query('destination', ''),
            'accountMessage' => $request->session()->pull('account_message'),
            'passwordReset' => null,
        ]);
    }

    public function store(Request $request): View|RedirectResponse
    {
        $result = $this->registration->register($request->all(), $request);
        $payload = $this->registration->pagePayload($result['form'] ?? null);

        return view('registration', [
            'data' => $payload['data'],
            'legacyBase' => '/',
            'errors' => $result['ok'] ? [] : ($result['errors'] ?? []),
            'answer' => $result['ok'] ? ($result['message'] ?? null) : null,
            'destination' => (string) $request->query('destination', ''),
            'accountMessage' => $request->session()->pull('account_message'),
            'passwordReset' => null,
        ]);
    }

    public function activate(Request $request): RedirectResponse
    {
        $result = $this->registration->activate((string) $request->query('id', ''), 'registration');

        if ($result['ok']) {
            return redirect()->route('start')->with('account_message', $result['message']);
        }

        return redirect()->route('start')->with(
            'account_message',
            '<strong>Es sind Fehler aufgetreten:</strong><br>'.implode('<br>', $result['errors'] ?? [])
        );
    }

    public function activateEmail(Request $request): RedirectResponse
    {
        $result = $this->registration->activate((string) $request->query('id', ''), 'email');

        if ($result['ok']) {
            return redirect()->route('start')->with('account_message', $result['message']);
        }

        return redirect()->route('start')->with(
            'account_message',
            '<strong>Es sind Fehler aufgetreten:</strong><br>'.implode('<br>', $result['errors'] ?? [])
        );
    }

    public function requestPasswordReset(Request $request): JsonResponse
    {
        $result = $this->registration->requestPasswordReset($request->all(), $request);

        if ($result['ok']) {
            return response()->json([
                'status' => 200,
                'message' => $result['message'],
            ]);
        }

        return response()->json([
            'status' => 422,
            'errors' => $result['errors'] ?? ['Unbekannter Fehler.'],
        ], 422);
    }
}
