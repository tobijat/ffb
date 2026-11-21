<?php

namespace App\Services;

use App\Mail\AccountActivationMail;
use App\Models\UserDetails;
use App\Models\UserPermissions;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class RegistrationService
{
    public function __construct(
        private readonly FfbPassword $passwords,
    ) {}

    /**
     * @return array{ok: true, data: array<string, mixed>}
     */
    public function pagePayload(?array $formOverride = null): array
    {
        return [
            'ok' => true,
            'data' => [
                'form' => $formOverride ?? $this->emptyForm(),
                'navigation' => HelpService::guestNavigation(),
                'tos_url' => (string) config('ffb.registration_tos_url'),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *   ok: true,
     *   message: string,
     *   form: array<string, mixed>
     * }|array{
     *   ok: false,
     *   status: int,
     *   errors: list<string>,
     *   form: array<string, mixed>
     * }
     */
    public function register(array $input, Request $request): array
    {
        $form = $this->formFromInput($input);
        $errors = $this->validate($input);

        if ($errors !== []) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => $errors,
                'form' => $form,
            ];
        }

        $activationCode = md5(uniqid((string) time(), true));
        $now = now()->format('Y-m-d H:i:s');
        $ip = (string) $request->ip();

        $user = DB::transaction(function () use ($input, $activationCode, $now, $ip) {
            $user = new WebUser;
            $user->user_nickname = trim((string) $input['user_nickname']);
            $user->user_password = $this->passwords->hash((string) $input['user_password']);
            $user->user_email = trim((string) $input['user_email']);
            $user->user_status = 'na';
            $user->user_lip = $ip;
            $user->user_date_register = $now;
            $user->user_date_llogin = $now;
            $user->user_date_laction = $now;
            $user->user_activation_code = $activationCode;
            $user->save();

            $details = new UserDetails;
            $details->user_id = (int) $user->user_id;
            $details->user_fname = '';
            $details->user_lname = '';
            $details->user_gender = '';
            $details->user_nationality = '';
            $details->user_date_birth = null;
            $details->user_details_ffb_selected_league = (int) config('ffb.registration_default_league_id', 25);
            $details->user_details_ffb_favourite_team = 1;
            $details->user_details_last_update = $now;
            $details->save();

            $permissions = new UserPermissions;
            $permissions->user_id = (int) $user->user_id;
            $permissions->user_permissions_ffb_mailservice_reminder = md5(uniqid((string) time(), true));
            $permissions->user_permissions_ffb_mailservice_info = md5(uniqid((string) time(), true));
            $permissions->user_permissions_ffb_visible_profile = false;
            $permissions->user_permissions_pictory_visible_profile = false;
            $permissions->save();

            return $user;
        });

        $activationUrl = $this->sendActivationMail($user, $activationCode, (string) $request->getHost());

        $message = '<b>Dein Account wurde angelegt!</b><br>'
            .'Ein Aktivierungs-Mail wurde an deine E-Mail Adresse geschickt.<br>'
            .'Bitte klick den Aktivierungs-Link in dieser E-Mail an, um deinen Account zu aktivieren!<br>'
            .'Danach kannst du dich auf der Startseite mit deinem Benutzernamen und Passwort anmelden.<br>'
            .'Nach dem Anmelden kannst du weitere Informationen über dich eintragen indem du auf "Account" klickst.<br>'
            .'Bitte prüf auch den Spam-Ordner deiner Mailbox, es kann vorkommen, dass die Mail dort gelandet ist!';

        if ($this->shouldExposeActivationLink()) {
            $message .= '<br><br><b>Lokal (MAIL_MAILER=log):</b> Es wurde keine echte Mail versendet. '
                .'Aktivierungs-Link: <a href="'.e($activationUrl).'">'.e($activationUrl).'</a>';
        }

        return [
            'ok' => true,
            'message' => $message,
            'form' => $this->emptyForm(),
        ];
    }

    /**
     * @return array{ok: true, message: string}|array{ok: false, errors: list<string>}
     */
    public function activate(string $id, string $kind = 'registration'): array
    {
        $parts = explode('-', $id, 2);
        $code = $parts[0] ?? '';
        $userId = (int) ($parts[1] ?? 0);

        if ($code === '' || $userId <= 0) {
            return [
                'ok' => false,
                'errors' => [
                    'Der Aktivierungs-Link ist ungültig oder wurde bereits verwendet. Eventuell wurde dieser Account bereits aktiviert.',
                ],
            ];
        }

        $user = WebUser::query()
            ->where('user_id', $userId)
            ->where('user_activation_code', $code)
            ->first();

        if (! $user) {
            return [
                'ok' => false,
                'errors' => [
                    'Der Aktivierungs-Link ist ungültig oder wurde bereits verwendet. Eventuell wurde dieser Account bereits aktiviert.',
                ],
            ];
        }

        $user->user_status = 'active';
        $user->user_activation_code = 'done';
        $user->save();

        if ($kind === 'email') {
            return [
                'ok' => true,
                'message' => 'Die E-Mail Änderung wurde abgeschlossen und dein Account wurde aktiviert. Du kannst dich jetzt mit deinem Benutzernamen und Passwort einloggen!',
            ];
        }

        return [
            'ok' => true,
            'message' => 'Die Registrierung wurde abgeschlossen und dein Account wurde aktiviert. Du kannst dich jetzt mit deinem Benutzernamen und Passwort einloggen!',
        ];
    }

    /**
     * Request a password-reset link by nickname OR email.
     * Always returns the same success message (no account enumeration).
     *
     * @param  array<string, mixed>  $input
     * @return array{ok: true, message: string}|array{ok: false, errors: list<string>}
     */
    public function requestPasswordReset(array $input, Request $request): array
    {
        $identifier = trim((string) (
            $input['identifier']
            ?? $input['user_nickname']
            ?? $input['user_email']
            ?? ''
        ));

        if ($identifier === '') {
            return [
                'ok' => false,
                'errors' => ['Bitte Benutzername oder E-Mail eingeben.'],
            ];
        }

        $user = WebUser::query()
            ->where(function ($query) use ($identifier): void {
                $query->where('user_nickname', $identifier)
                    ->orWhere('user_email', $identifier);
            })
            ->first();

        if ($user && filter_var((string) $user->user_email, FILTER_VALIDATE_EMAIL)) {
            // Credential key must match the web_user column (not Laravel's default "email").
            Password::broker()->sendResetLink([
                'user_email' => (string) $user->user_email,
            ]);
        }

        return [
            'ok' => true,
            'message' => 'Wenn ein Account mit diesen Angaben existiert, hast du eine E-Mail mit einem Link zum Zurücksetzen des Passworts erhalten.',
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return list<string>
     */
    public function validate(array $input): array
    {
        $errors = [];

        $nickname = (string) ($input['user_nickname'] ?? '');
        $password = (string) ($input['user_password'] ?? '');
        $passwordVal = (string) ($input['user_password_val'] ?? '');
        $email = trim((string) ($input['user_email'] ?? ''));
        $emailVal = trim((string) ($input['user_email_val'] ?? ''));

        if (
            $nickname === ''
            || $password === ''
            || $passwordVal === ''
            || $email === ''
            || $emailVal === ''
        ) {
            $errors[] = 'Du musst alle Felder ausfüllen, die mit einem * markiert sind!';

            return $errors;
        }

        if ($nickname !== trim($nickname)) {
            $errors[] = "Benutzernamen bitte ohne Leerzeichen ' ' am Beginn und Ende!";
        }

        $nickname = trim($nickname);
        $nicknameLength = mb_strlen($nickname, 'UTF-8');
        if ($nicknameLength < 3 || $nicknameLength > 16) {
            $errors[] = 'Benutzername: min. Länge ist 3, max. Länge ist 16!';
        }

        // Special chars (anything that is not a Unicode letter or number) may not repeat in a row.
        // Letters in any script (e.g. Japanese) are allowed; existing nicknames are not re-validated on login.
        if ($nickname !== '' && preg_match('/[^\p{L}\p{N}]{2,}/u', $nickname) === 1) {
            $errors[] = 'Sonderzeichen dürfen im Benutzernamen nicht zweimal hintereinander stehen!';
        }

        $errors = array_merge($errors, $this->passwords->validatePlain($password));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Deine Email-Adresse ist nicht gültig!';
        }

        if (strcmp($password, $passwordVal) !== 0) {
            $errors[] = 'Die Passwörter stimmen nicht überein!';
        }

        if (strcmp($email, $emailVal) !== 0) {
            $errors[] = 'Die Email-Adressen stimmen nicht überein!';
        }

        if (($input['user_tos'] ?? '') !== 'user_tos_yes') {
            $errors[] = 'Du musst die Bedingungen gelesen und akzeptiert haben!';
        }

        if ($errors !== []) {
            return $errors;
        }

        if (WebUser::query()->where('user_nickname', $nickname)->exists()) {
            $errors[] = 'Dieser Benutzername existiert bereits!';
        }

        if (WebUser::query()->where('user_email', $email)->exists()) {
            $errors[] = 'Diese Email-Adresse existiert bereits!';
        }

        return $errors;
    }

    private function sendActivationMail(WebUser $user, string $activationCode, string $siteHost): string
    {
        $activateRoute = app(AppHostService::class)->isPictory()
            ? 'pictory.activate'
            : 'registration.activate';

        $activationUrl = route($activateRoute, [
            'id' => $activationCode.'-'.(int) $user->user_id,
        ]);

        Mail::to((string) $user->user_email)->send(new AccountActivationMail(
            (string) $user->user_nickname,
            $activationUrl,
            $siteHost,
        ));

        return $activationUrl;
    }

    private function shouldExposeActivationLink(): bool
    {
        $mailer = (string) config('mail.default', 'log');

        return app()->environment('local', 'testing')
            && in_array($mailer, ['log', 'array'], true);
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyForm(): array
    {
        return [
            'user_nickname' => '',
            'user_email' => '',
            'user_email_val' => '',
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function formFromInput(array $input): array
    {
        return [
            'user_nickname' => (string) ($input['user_nickname'] ?? ''),
            'user_email' => (string) ($input['user_email'] ?? ''),
            'user_email_val' => (string) ($input['user_email_val'] ?? ''),
        ];
    }
}
