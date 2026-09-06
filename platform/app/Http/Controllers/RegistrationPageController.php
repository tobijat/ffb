<?php

namespace App\Http\Controllers;

use App\Services\FfbAuth;
use App\Services\HelpService;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationPageController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registration,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        if ($this->auth->userId($request) > 0) {
            return redirect()->route('account');
        }

        $payload = $this->registration->pagePayload();

        return view('registration', [
            'data' => $payload['data'],
            'legacyBase' => '/',
            'errors' => [],
            'answer' => null,
        ]);
    }

    public function store(Request $request): View|RedirectResponse
    {
        if ($this->auth->userId($request) > 0) {
            return redirect()->route('account');
        }

        $result = $this->registration->register($request->all(), $request);
        $payload = $this->registration->pagePayload($result['form'] ?? null);

        return view('registration', [
            'data' => $payload['data'],
            'legacyBase' => '/',
            'errors' => $result['ok'] ? [] : ($result['errors'] ?? []),
            'answer' => $result['ok'] ? ($result['message'] ?? null) : null,
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

    public function showPasswordReset(Request $request, int $user): View|RedirectResponse
    {
        $webUser = \App\Models\WebUser::query()->find($user);
        if (! $webUser) {
            return redirect()->route('start')->with(
                'account_message',
                '<strong>Es sind Fehler aufgetreten:</strong><br>Der Link ist ungültig oder abgelaufen.'
            );
        }

        return view('password-reset', [
            'nickname' => (string) $webUser->user_nickname,
            'formAction' => $request->fullUrl(),
            'errors' => [],
            'legacyBase' => '/',
            'data' => [
                'navigation' => HelpService::guestNavigation(),
            ],
        ]);
    }

    public function updatePasswordReset(Request $request, int $user): View|RedirectResponse
    {
        $webUser = \App\Models\WebUser::query()->find($user);
        if (! $webUser) {
            return redirect()->route('start')->with(
                'account_message',
                '<strong>Es sind Fehler aufgetreten:</strong><br>Der Link ist ungültig oder abgelaufen.'
            );
        }

        $result = $this->registration->completePasswordReset($webUser, $request->all());

        if ($result['ok']) {
            return redirect()->route('start')->with('account_message', $result['message']);
        }

        return view('password-reset', [
            'nickname' => (string) $webUser->user_nickname,
            'formAction' => $request->fullUrl(),
            'errors' => $result['errors'] ?? [],
            'legacyBase' => '/',
            'data' => [
                'navigation' => HelpService::guestNavigation(),
            ],
        ]);
    }
}
