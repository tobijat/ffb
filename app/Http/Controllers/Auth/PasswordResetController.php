<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use App\Services\FfbPassword;
use App\Services\StartPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(
        private readonly FfbPassword $passwords,
        private readonly StartPageService $startPage,
    ) {
    }

    public function show(Request $request, string $token): View|RedirectResponse
    {
        // Reset UI is the guest start page; drop any active session so brand/header
        // don't mix logged-in league chrome with the guest reset form.
        $this->logoutIfAuthenticated($request);

        $email = trim((string) $request->query('email', $request->input('email', '')));
        $user = $this->userForToken($email, $token);
        if (! $user) {
            return $this->invalidLinkRedirect();
        }

        return $this->formView($token, $email, (string) $user->user_nickname, []);
    }

    public function update(Request $request, string $token): View|RedirectResponse
    {
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('user_password', $request->input('password', ''));
        $passwordConfirmation = (string) $request->input('user_password_val', $request->input('password_confirmation', ''));

        $errors = $this->validatePasswords($password, $passwordConfirmation);
        if ($errors !== []) {
            $this->logoutIfAuthenticated($request);
            $nickname = (string) (WebUser::query()->where('user_email', $email)->value('user_nickname') ?? '');

            return $this->formView($token, $email, $nickname, $errors);
        }

        $status = Password::reset(
            [
                'user_email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
                'token' => $token,
            ],
            function (WebUser $user, string $plain) use ($request): void {
                $user->user_password = $this->passwords->hash($plain);
                $user->save();

                Auth::login($user);
                $request->session()->regenerate();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('start')->with(
                'account_message',
                'Dein Passwort wurde geändert.'
            );
        }

        return $this->invalidLinkRedirect();
    }

    /**
     * @return list<string>
     */
    private function validatePasswords(string $password, string $passwordConfirmation): array
    {
        $errors = [];

        if ($password === '' || $passwordConfirmation === '') {
            $errors[] = 'Bitte beide Passwort-Felder ausfüllen.';
        } elseif (strlen($password) < 5 || strlen($password) > 32) {
            $errors[] = 'Passwort: min. Länge ist 5, max. Länge ist 32!';
        } elseif (strcmp($password, $passwordConfirmation) !== 0) {
            $errors[] = 'Die Passwörter stimmen nicht überein!';
        }

        return $errors;
    }

    private function userForToken(string $email, string $token): ?WebUser
    {
        if ($email === '' || $token === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $user = WebUser::query()->where('user_email', $email)->first();
        if (! $user) {
            return null;
        }

        if (! Password::broker()->tokenExists($user, $token)) {
            return null;
        }

        return $user;
    }

    /**
     * @param  list<string>  $errors
     */
    private function formView(string $token, string $email, string $nickname, array $errors): View
    {
        $data = $this->startPage->payload();

        return view('start', [
            'stats' => $data['stats'],
            'leagues' => $data['leagues'],
            'results' => $data['results'],
            'legacyBase' => '/',
            'destination' => '',
            'accountMessage' => null,
            'passwordReset' => [
                'nickname' => $nickname,
                'email' => $email,
                'formAction' => route('password.reset.update', ['token' => $token]),
                'errors' => $errors,
            ],
        ]);
    }

    private function invalidLinkRedirect(): RedirectResponse
    {
        return redirect()->route('start')->with(
            'account_message',
            '<strong>Es sind Fehler aufgetreten:</strong><br>Der Link ist ungültig oder abgelaufen.'
        );
    }

    private function logoutIfAuthenticated(Request $request): void
    {
        if (! Auth::check()) {
            return;
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
