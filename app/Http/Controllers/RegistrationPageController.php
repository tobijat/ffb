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
            return redirect()->route('profile', ['tab' => 'account']);
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
            return redirect()->route('profile', ['tab' => 'account']);
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
        if ($request->query->has('signature')) {
            if (! $request->hasValidSignature()) {
                return $this->invalidPasswordResetRedirect();
            }

            $request->session()->put('password_reset', [
                'user_id' => $user,
                'expires_at' => (int) $request->query('expires', 0),
            ]);

            return redirect()->route('password.reset', ['user' => $user]);
        }

        if (! $this->hasPasswordResetSession($request, $user)) {
            return $this->invalidPasswordResetRedirect();
        }

        $webUser = \App\Models\WebUser::query()->find($user);
        if (! $webUser) {
            return $this->invalidPasswordResetRedirect();
        }

        return view('password-reset', [
            'nickname' => (string) $webUser->user_nickname,
            'formAction' => route('password.reset.update', ['user' => $user]),
            'errors' => [],
            'legacyBase' => '/',
            'data' => [
                'navigation' => HelpService::guestNavigation(),
            ],
        ]);
    }

    public function updatePasswordReset(Request $request, int $user): View|RedirectResponse
    {
        if (! $this->hasPasswordResetSession($request, $user)) {
            return $this->invalidPasswordResetRedirect();
        }

        $webUser = \App\Models\WebUser::query()->find($user);
        if (! $webUser) {
            return $this->invalidPasswordResetRedirect();
        }

        $result = $this->registration->completePasswordReset($webUser, $request->all());

        if ($result['ok']) {
            $request->session()->forget('password_reset');

            return redirect()->route('start')->with('account_message', $result['message']);
        }

        return view('password-reset', [
            'nickname' => (string) $webUser->user_nickname,
            'formAction' => route('password.reset.update', ['user' => $user]),
            'errors' => $result['errors'] ?? [],
            'legacyBase' => '/',
            'data' => [
                'navigation' => HelpService::guestNavigation(),
            ],
        ]);
    }

    private function hasPasswordResetSession(Request $request, int $user): bool
    {
        $payload = $request->session()->get('password_reset');
        if (! is_array($payload)) {
            return false;
        }

        if ((int) ($payload['user_id'] ?? 0) !== $user) {
            return false;
        }

        return (int) ($payload['expires_at'] ?? 0) > time();
    }

    private function invalidPasswordResetRedirect(): RedirectResponse
    {
        return redirect()->route('start')->with(
            'account_message',
            '<strong>Es sind Fehler aufgetreten:</strong><br>Der Link ist ungültig oder abgelaufen.'
        );
    }
}
