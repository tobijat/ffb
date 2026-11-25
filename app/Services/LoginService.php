<?php

namespace App\Services;

use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public const AREA_PREFIX = 'ffb';

    public function __construct(
        private readonly FfbPassword $passwords,
    ) {}

    /**
     * @return array{ok: true, destination: string, user_id: int, email_unverified: bool}|array{ok: false, errors: list<string>}
     */
    public function attempt(string $nickname, string $plainPassword, Request $request, string $destination = ''): array
    {
        $nickname = trim($nickname);
        if ($nickname === '' || $plainPassword === '') {
            return ['ok' => false, 'errors' => ['Bitte Nickname und Passwort eingeben.']];
        }

        $user = WebUser::query()->with('details')->where('user_nickname', $nickname)->first();
        if (! $user) {
            return ['ok' => false, 'errors' => ["Benutzername '{$nickname}' existiert nicht."]];
        }

        if ($user->user_status === 'inactive') {
            return [
                'ok' => false,
                'errors' => ['Dein Account ist inaktiv.<br>Bitte wende dich an einen Administrator!'],
            ];
        }

        if (! in_array($user->user_status, ['active', 'pending'], true)) {
            return ['ok' => false, 'errors' => ['Anmeldung nicht möglich.']];
        }

        $storedHash = (string) $user->user_password;
        if (! $this->passwords->verify($plainPassword, $storedHash)) {
            return ['ok' => false, 'errors' => ['Das Passwort ist falsch.']];
        }

        if ($this->passwords->needsRehash($storedHash)) {
            $user->user_password = $this->passwords->hash($plainPassword);
        }

        $emailUnverified = $user->user_status === 'pending' || ! $user->hasVerifiedEmail();

        $user->user_date_llogin = now()->format('Y-m-d H:i:s');
        $user->user_date_laction = now()->format('Y-m-d H:i:s');
        $user->user_lip = (string) $request->ip();
        $user->save();

        Auth::login($user);

        $destinationPath = $emailUnverified
            ? route('start', absolute: false)
            : $this->resolveDestination($destination);

        return [
            'ok' => true,
            'user_id' => (int) $user->user_id,
            'destination' => $destinationPath,
            'email_unverified' => $emailUnverified,
        ];
    }

    public function resolveDestination(string $destination): string
    {
        $path = $this->safePath($destination);
        if ($path !== null) {
            return $path;
        }

        return (string) config('ffb.home_path', '/');
    }

    /**
     * Allow only same-site relative paths (no protocol-relative or absolute URLs).
     * Query values may omit the leading slash (e.g. destination=myteam); it is added here.
     */
    private function safePath(string $destination): ?string
    {
        $destination = trim($destination);
        if ($destination === '') {
            return null;
        }

        // Reject absolute / protocol-relative URLs.
        if (preg_match('#^(?:[a-z][a-z0-9+.-]*:)?//#i', $destination)) {
            return null;
        }

        if (str_starts_with($destination, '//')) {
            return null;
        }

        if (str_contains($destination, "\n") || str_contains($destination, "\r")) {
            return null;
        }

        // Reject scheme-like values (e.g. javascript:...).
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $destination)) {
            return null;
        }

        if (! str_starts_with($destination, '/')) {
            $destination = '/'.$destination;
        }

        return $destination;
    }
}
