<?php

namespace App\Http\Controllers\Pictory;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PictoryAuthController extends Controller
{
    public function __construct(
        private readonly LoginService $login,
        private readonly RegistrationService $registration,
    ) {
    }

    public function showLogin(Request $request): View|RedirectResponse
    {
        return view('pictory.login', [
            'errors' => [],
            'nickname' => old('user_nickname', ''),
            'message' => $request->session()->pull('pictory_message'),
        ]);
    }

    public function login(Request $request): View|RedirectResponse
    {
        $result = $this->login->attempt(
            (string) $request->input('user_nickname', ''),
            (string) $request->input('user_password', ''),
            $request,
            '',
        );

        if (! $result['ok']) {
            return view('pictory.login', [
                'errors' => $result['errors'] ?? ['Anmeldung fehlgeschlagen.'],
                'nickname' => (string) $request->input('user_nickname', ''),
                'message' => null,
            ]);
        }

        $redirect = redirect()->route('pictory.home');
        if (! empty($result['email_unverified'])) {
            $redirect->with('show_activation_modal', true);
        }

        return $redirect;
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        $payload = $this->registration->pagePayload();

        return view('pictory.register', [
            'form' => $payload['data']['form'],
            'errors' => [],
            'answer' => null,
        ]);
    }

    public function register(Request $request): View|RedirectResponse
    {
        $result = $this->registration->register($request->all(), $request);

        if ($result['ok'] && ! empty($result['logged_in'])) {
            return redirect()
                ->route('pictory.home')
                ->with('pictory_message', $result['message'])
                ->with('show_activation_modal', true);
        }

        $payload = $this->registration->pagePayload($result['form'] ?? null);

        return view('pictory.register', [
            'form' => $payload['data']['form'],
            'errors' => $result['ok'] ? [] : ($result['errors'] ?? []),
            'answer' => $result['ok'] ? ($result['message'] ?? null) : null,
        ]);
    }

    public function activate(Request $request): RedirectResponse
    {
        $result = $this->registration->activateRegistration($request);

        if ($result['ok']) {
            return redirect()
                ->route('pictory.home')
                ->with('pictory_message', $result['message'] ?? 'Account aktiviert.');
        }

        return redirect()
            ->route('pictory.home')
            ->with('pictory_message', '<strong>Fehler:</strong> '.implode(' ', $result['errors'] ?? ['Aktivierung fehlgeschlagen.']));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pictory.home');
    }
}
