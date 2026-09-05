<?php

namespace App\Services;

use App\Models\WebAdmin;
use App\Models\WebUser;
use Illuminate\Http\Request;

class LoginService
{
    public const AREA_PREFIX = 'ffb';

    public function __construct(
        private readonly FfbPassword $passwords,
        private readonly FfbAuth $auth,
    ) {
    }

    /**
     * @return array{ok: true, destination: string, user_id: int}|array{ok: false, errors: list<string>}
     */
    public function attempt(string $nickname, string $plainPassword, Request $request, string $destination = ''): array
    {
        $nickname = trim($nickname);
        if ($nickname === '' || $plainPassword === '') {
            return ['ok' => false, 'errors' => ['Bitte Nickname und Passwort eingeben.']];
        }

        $user = WebUser::query()->where('user_nickname', $nickname)->first();
        if (! $user) {
            return ['ok' => false, 'errors' => ["Benutzername '{$nickname}' existiert nicht."]];
        }

        if ($user->user_status === 'inactive') {
            return [
                'ok' => false,
                'errors' => ['Dein Account ist inaktiv.<br>Bitte wende dich an einen Administrator!'],
            ];
        }

        if ($user->user_status === 'na') {
            return [
                'ok' => false,
                'errors' => [
                    'Dein Account wurde noch nicht aktiviert.<br>'
                    .'Klick auf den Aktivierungs-Link in der Email, die dir nach der Registrierung zugeschickt wurde!<br>'
                    .'Prüf bitte auch deinen Spam-Folder!',
                ],
            ];
        }

        if ($user->user_status !== 'active') {
            return ['ok' => false, 'errors' => ['Anmeldung nicht möglich.']];
        }

        $storedHash = (string) $user->user_password;
        if (! $this->passwords->verify($plainPassword, $storedHash)) {
            return ['ok' => false, 'errors' => ['Das Passwort ist falsch.']];
        }

        $isAdmin = WebAdmin::query()
            ->where('admin_user_id', $user->user_id)
            ->where('admin_section', self::AREA_PREFIX)
            ->exists();

        if ($this->passwords->needsRehash($storedHash)) {
            $user->user_password = $this->passwords->hash($plainPassword);
        }

        $user->user_date_llogin = now()->format('Y-m-d H:i:s');
        $user->user_date_laction = now()->format('Y-m-d H:i:s');
        $user->user_lip = (string) $request->ip();
        $user->save();

        $this->auth->login((int) $user->user_id, $request);

        return [
            'ok' => true,
            'user_id' => (int) $user->user_id,
            'destination' => $this->resolveDestination($destination, $isAdmin),
        ];
    }

    public function resolveDestination(string $destination, bool $isAdmin): string
    {
        if ($isAdmin) {
            return '/administration/start';
        }

        $path = $this->safePath($destination);
        if ($path !== null) {
            return $path;
        }

        return (string) config('ffb.home_path', '/platform/public/');
    }

    /**
     * Allow only same-site relative paths (no protocol-relative or absolute URLs).
     */
    private function safePath(string $destination): ?string
    {
        $destination = trim($destination);
        if ($destination === '') {
            return null;
        }

        // Legacy login sometimes sent a path; reject anything that looks absolute.
        if (preg_match('#^(?:[a-z][a-z0-9+.-]*:)?//#i', $destination)) {
            return null;
        }

        if (! str_starts_with($destination, '/')) {
            return null;
        }

        if (str_starts_with($destination, '//')) {
            return null;
        }

        if (str_contains($destination, "\n") || str_contains($destination, "\r")) {
            return null;
        }

        return $destination;
    }
}
