<?php

namespace App\Services;

use App\Mail\EmailChangeMail;
use App\Models\Team;
use App\Models\UserDetails;
use App\Models\UserPermissions;
use App\Models\WebUser;
use App\Support\CustomAvatarSvg;
use App\Support\TeamShirt;
use App\Support\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountService
{
    public function __construct(
        private readonly FfbPassword $passwords,
        private readonly FfbAuth $auth,
    ) {}

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function pagePayload(int $userId, ?array $formOverride = null): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return ['ok' => false, 'status' => 401, 'error' => 'Unknown user'];
        }

        $details = $user->details;
        $avatar = (string) ($details?->user_details_avatar ?: 'avatar_na.png');

        $form = $formOverride ?? $this->formFromUser($user);

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => UserAvatar::publicUrl($avatar),
                    'update_profile_nag' => UserAvatar::isDefault($avatar)
                        && ! $details?->user_details_zip
                        && ! $details?->user_details_city
                        && ! $details?->user_details_street
                        && ! $details?->user_details_phone
                        && ! $details?->user_details_website
                        && ! $user->user_fname
                        && ! $user->user_lname,
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $user->user_id),
                ],
                'form' => $form,
                'countries' => config('countries', []),
                'birth_years' => $this->birthYears(),
                'navigation' => app(DashboardService::class)->navigation($userId),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *   ok: true,
     *   message: string,
     *   email_changed: bool,
     *   form: array<string, mixed>
     * }|array{
     *   ok: false,
     *   status: int,
     *   errors: list<string>,
     *   form: array<string, mixed>
     * }
     */
    public function update(int $userId, array $input, Request $request): array
    {
        $user = WebUser::query()->find($userId);
        if (! $user) {
            return [
                'ok' => false,
                'status' => 401,
                'errors' => ['Update fehlgeschlagen. Deine Daten können nicht aktualisiert werden!'],
                'form' => $this->formFromInput($input),
            ];
        }

        $form = $this->mergeForm($user, $input);
        $errors = $this->validate($input, $userId);

        if ($errors !== []) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => $errors,
                'form' => $form,
            ];
        }

        $user->user_ip = (string) $request->ip();

        $password = (string) ($input['user_password_chg'] ?? '');
        if ($password !== '') {
            $user->user_password = $this->passwords->hash($password);
        }

        $emailChanged = false;
        $newEmail = trim((string) ($input['user_email_chg'] ?? ''));
        $message = '<b>Deine Daten wurden aktualisiert!</b>';

        if ($newEmail !== '') {
            $activationCode = md5(uniqid((string) time(), true));
            $user->user_email = $newEmail;
            $user->user_activation_code = $activationCode;
            $user->user_status = 'na';
            $user->save();

            $this->sendEmailChangeMail(
                $user,
                $activationCode,
                (string) $request->getHost(),
            );

            $this->auth->logout($request);
            $emailChanged = true;
            $message .= '<br><b>!!</b> Du hast deine E-Mail Adresse geändert. Ein E-Mail wurde an die neue Adresse geschickt. Um die Änderung abzuschließen, musst du den Link in dieser E-Mail anklicken. Du wirst jetzt ausgeloggt und kannst dich erst wieder einloggen, wenn der Link geklickt wurde.';

            return [
                'ok' => true,
                'message' => $message,
                'email_changed' => true,
                'form' => $form,
            ];
        }

        $user->save();

        return [
            'ok' => true,
            'message' => $message,
            'email_changed' => $emailChanged,
            'form' => $this->formFromUser($user->fresh()),
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function profilePayload(int $userId, ?array $formOverride = null): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return ['ok' => false, 'status' => 401, 'error' => 'Unknown user'];
        }

        $details = $user->details;
        $avatar = (string) ($details?->user_details_avatar ?: 'avatar_na.png');

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => UserAvatar::publicUrl($avatar),
                    'update_profile_nag' => UserAvatar::isDefault($avatar)
                        && ! $details?->user_details_zip
                        && ! $details?->user_details_city
                        && ! $details?->user_details_street
                        && ! $details?->user_details_phone
                        && ! $details?->user_details_website
                        && ! $user->user_fname
                        && ! $user->user_lname,
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $user->user_id),
                ],
                'form' => $formOverride ?? $this->profileFormFromUser($user),
                'teams' => $this->teams(),
                'avatar_shirts' => $this->avatarShirtOptions(),
                'navigation' => app(DashboardService::class)->navigation($userId),
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
    public function updateProfile(int $userId, array $input, Request $request): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        $details = $user?->details;
        $permissions = UserPermissions::query()->find($userId);

        if (! $user || ! $details || ! $permissions) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => ['Update fehlgeschlagen. Deine Daten wurden nicht aktualisiert!'],
                'form' => $this->profileFormFromInput($input),
            ];
        }

        $section = (string) ($input['profile_section'] ?? 'profile');
        if ($section === 'avatar') {
            return $this->updateAvatarSection($user, $details, $input, $request);
        }
        if ($section === 'avatar_custom') {
            return $this->updateAvatarCustomSection($user, $details, $input, $request);
        }

        $form = $this->mergeProfileForm($user, $input);
        $errors = $this->validateProfile($request);

        if ($errors !== []) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => $errors,
                'form' => $form,
            ];
        }

        $website = $this->normalizeWebsite((string) ($input['user_details_website'] ?? ''));

        $user->user_fname = trim((string) ($input['user_fname'] ?? ''));
        $user->user_lname = trim((string) ($input['user_lname'] ?? ''));
        $user->user_nationality = (string) ($input['user_nationality'] ?? '');
        $user->user_date_birth = $this->birthDateFromInput($input);

        $details->user_details_zip = trim((string) ($input['user_details_zip'] ?? ''));
        $details->user_details_city = trim((string) ($input['user_details_city'] ?? ''));
        $details->user_details_street = trim((string) ($input['user_details_street'] ?? ''));
        $details->user_details_phone = trim((string) ($input['user_details_phone'] ?? ''));
        $details->user_details_website = $website;
        $details->user_details_ffb_favourite_team = (int) ($input['user_details_ffb_favourite_team'] ?? 0);
        // Do not touch own_team / own_player / selected_league (legacy POST often wiped these).
        $details->user_details_last_update = date('Y-m-d H:i:s');

        $this->applyMailPermission(
            $permissions,
            'user_permissions_ffb_mailservice_reminder',
            (int) ($input['user_permissions_ffb_mailservice_reminder'] ?? 0) === 1,
        );
        $this->applyMailPermission(
            $permissions,
            'user_permissions_ffb_mailservice_info',
            (int) ($input['user_permissions_ffb_mailservice_info'] ?? 0) === 1,
        );
        $permissions->user_permissions_ffb_visible_profile = (int) ($input['user_permissions_ffb_visible_profile'] ?? 0) === 1 ? 1 : 0;

        $user->user_ip = (string) $request->ip();
        $user->save();
        $details->save();
        $permissions->save();

        $fresh = $user->fresh(['details']);

        return [
            'ok' => true,
            'message' => '<b>Deine Daten wurden aktualisiert!</b>',
            'form' => $this->profileFormFromUser($fresh),
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
    private function updateAvatarSection(WebUser $user, UserDetails $details, array $input, Request $request): array
    {
        $form = $this->mergeProfileForm($user, $input);
        $errors = $this->validateAvatar($request);

        if ($errors !== []) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => $errors,
                'form' => $form,
            ];
        }

        $shirtAvatar = trim((string) ($input['user_details_avatar_shirt'] ?? ''));
        // Shirt selection replaces any previous avatar value; legacy custom files are left on disk.
        if ($shirtAvatar !== '') {
            $details->user_details_avatar = $shirtAvatar;
            $details->user_details_last_update = date('Y-m-d H:i:s');
            $details->save();
        }

        $user->user_ip = (string) $request->ip();
        $user->save();

        $fresh = $user->fresh(['details']);

        return [
            'ok' => true,
            'message' => '<b>Deine Daten wurden aktualisiert!</b>',
            'form' => $this->profileFormFromUser($fresh),
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
    private function updateAvatarCustomSection(WebUser $user, UserDetails $details, array $input, Request $request): array
    {
        $form = $this->mergeProfileForm($user, $input);
        $svg = (string) ($input['avatar_svg'] ?? '');
        $errors = $this->validateAvatarCustom($svg);

        if ($errors !== []) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => $errors,
                'form' => $form,
            ];
        }

        $stored = CustomAvatarSvg::store($svg);
        if ($stored === null) {
            return [
                'ok' => false,
                'status' => 422,
                'errors' => ['Das Trikot konnte nicht gespeichert werden.'],
                'form' => $form,
            ];
        }

        $details->user_details_avatar = $stored;
        $details->user_details_last_update = date('Y-m-d H:i:s');
        $details->save();

        $user->user_ip = (string) $request->ip();
        $user->save();

        $fresh = $user->fresh(['details']);

        return [
            'ok' => true,
            'message' => '<b>Dein Avatar wurde gespeichert!</b>',
            'form' => $this->profileFormFromUser($fresh),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formFromUser(WebUser $user): array
    {
        $birth = $this->parseBirth($user->user_date_birth);

        return [
            'user_nickname' => (string) $user->user_nickname,
            'user_email' => (string) $user->user_email,
            'user_fname' => (string) ($user->user_fname ?? ''),
            'user_lname' => (string) ($user->user_lname ?? ''),
            'user_nationality' => (string) ($user->user_nationality ?? ''),
            'user_birth_day' => $birth['day'],
            'user_birth_month' => $birth['month'],
            'user_birth_year' => $birth['year'],
            'user_email_chg' => '',
            'user_email_val_chg' => '',
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
            'user_fname' => (string) ($input['user_fname'] ?? ''),
            'user_lname' => (string) ($input['user_lname'] ?? ''),
            'user_nationality' => (string) ($input['user_nationality'] ?? ''),
            'user_birth_day' => (int) ($input['user_birth_day'] ?? 0),
            'user_birth_month' => (int) ($input['user_birth_month'] ?? 0),
            'user_birth_year' => (int) ($input['user_birth_year'] ?? 0),
            'user_email_chg' => (string) ($input['user_email_chg'] ?? ''),
            'user_email_val_chg' => (string) ($input['user_email_val_chg'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function mergeForm(WebUser $user, array $input): array
    {
        $base = $this->formFromUser($user);

        return array_merge($base, [
            'user_fname' => (string) ($input['user_fname'] ?? $base['user_fname']),
            'user_lname' => (string) ($input['user_lname'] ?? $base['user_lname']),
            'user_nationality' => (string) ($input['user_nationality'] ?? $base['user_nationality']),
            'user_birth_day' => (int) ($input['user_birth_day'] ?? $base['user_birth_day']),
            'user_birth_month' => (int) ($input['user_birth_month'] ?? $base['user_birth_month']),
            'user_birth_year' => (int) ($input['user_birth_year'] ?? $base['user_birth_year']),
            'user_email_chg' => (string) ($input['user_email_chg'] ?? ''),
            'user_email_val_chg' => (string) ($input['user_email_val_chg'] ?? ''),
        ]);
    }

    /**
     * @param  array<string, mixed>  $input
     * @return list<string>
     */
    private function validate(array $input, int $userId): array
    {
        $errors = [];

        $password = (string) ($input['user_password_chg'] ?? '');
        $passwordConfirm = (string) ($input['user_password_val_chg'] ?? '');
        if ($password !== '') {
            $len = Str::length($password);
            if ($len < 5 || $len > 32) {
                $errors[] = 'Passwortänderung: min. Länge ist 5, max. Länge ist 32!';
            }
            if ($password !== $passwordConfirm) {
                $errors[] = 'Die Passwörter bei der Passwortänderung stimmen nicht überein!';
            }
        }

        $email = trim((string) ($input['user_email_chg'] ?? ''));
        $emailConfirm = trim((string) ($input['user_email_val_chg'] ?? ''));
        if ($email !== '') {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Deine neue E-Mail Adresse ist nicht gültig!';
            }
            if ($email !== $emailConfirm) {
                $errors[] = 'Die E-Mail Adressen bei der Adressänderung stimmen nicht überein!';
            }
            $exists = WebUser::query()
                ->where('user_email', $email)
                ->where('user_id', '!=', $userId)
                ->exists();
            if ($exists) {
                $errors[] = 'Die von dir gewählte neue E-Mail Adresse existiert bereits!';
            }
        }

        if (($input['user_tos'] ?? '') !== 'user_tos_yes') {
            $errors[] = 'Du musst die Bedingungen gelesen und akzeptiert haben!';
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function birthDateFromInput(array $input): ?string
    {
        $day = (int) ($input['user_birth_day'] ?? 0);
        $month = (int) ($input['user_birth_month'] ?? 0);
        $year = (int) ($input['user_birth_year'] ?? 0);

        if ($day <= 0 || $month <= 0 || $year <= 0 || ! checkdate($month, $day, $year)) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * @return array{day: int, month: int, year: int}
     */
    private function parseBirth(mixed $raw): array
    {
        if ($raw === null || $raw === '' || $raw === '0000-00-00') {
            return ['day' => 0, 'month' => 0, 'year' => 0];
        }

        $ts = strtotime((string) $raw);
        if ($ts === false) {
            return ['day' => 0, 'month' => 0, 'year' => 0];
        }

        return [
            'day' => (int) date('j', $ts),
            'month' => (int) date('n', $ts),
            'year' => (int) date('Y', $ts),
        ];
    }

    /**
     * @return list<int>
     */
    private function birthYears(): array
    {
        $now = (int) date('Y');
        $years = [];
        for ($y = $now - 11; $y > $now - 101; $y--) {
            $years[] = $y;
        }

        return $years;
    }

    private function sendEmailChangeMail(WebUser $user, string $activationCode, string $serverName): void
    {
        $activationUrl = route('registration.activate-email', [
            'id' => $activationCode.'-'.(int) $user->user_id,
        ]);

        Mail::to((string) $user->user_email)->send(new EmailChangeMail(
            (string) $user->user_nickname,
            $activationUrl,
            $serverName,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function profileFormFromUser(WebUser $user): array
    {
        $details = $user->details;
        $permissions = UserPermissions::query()->find($user->user_id);
        $avatar = (string) ($details?->user_details_avatar ?: 'avatar_na.png');
        $birth = $this->parseBirth($user->user_date_birth);

        return [
            'user_fname' => (string) ($user->user_fname ?? ''),
            'user_lname' => (string) ($user->user_lname ?? ''),
            'user_nationality' => (string) ($user->user_nationality ?? ''),
            'user_birth_day' => $birth['day'],
            'user_birth_month' => $birth['month'],
            'user_birth_year' => $birth['year'],
            'user_details_city' => (string) ($details?->user_details_city ?? ''),
            'user_details_zip' => (string) ($details?->user_details_zip ?? ''),
            'user_details_street' => (string) ($details?->user_details_street ?? ''),
            'user_details_phone' => (string) ($details?->user_details_phone ?? ''),
            'user_details_website' => (string) ($details?->user_details_website ?? ''),
            'user_details_ffb_favourite_team' => (int) ($details?->user_details_ffb_favourite_team ?? 0),
            'user_details_avatar' => $avatar,
            'user_details_avatar_url' => UserAvatar::publicUrl($avatar),
            'user_permissions_ffb_mailservice_reminder' => $this->mailPermissionEnabled(
                $permissions?->user_permissions_ffb_mailservice_reminder
            ) ? 1 : 0,
            'user_permissions_ffb_mailservice_info' => $this->mailPermissionEnabled(
                $permissions?->user_permissions_ffb_mailservice_info
            ) ? 1 : 0,
            'user_permissions_ffb_visible_profile' => (int) ($permissions?->user_permissions_ffb_visible_profile ?? 0) === 1 ? 1 : 0,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function profileFormFromInput(array $input): array
    {
        $shirt = trim((string) ($input['user_details_avatar_shirt'] ?? ''));
        $avatar = $shirt !== ''
            ? $shirt
            : (string) ($input['user_details_avatar_name'] ?? 'avatar_na.png');

        return [
            'user_fname' => (string) ($input['user_fname'] ?? ''),
            'user_lname' => (string) ($input['user_lname'] ?? ''),
            'user_nationality' => (string) ($input['user_nationality'] ?? ''),
            'user_birth_day' => (int) ($input['user_birth_day'] ?? 0),
            'user_birth_month' => (int) ($input['user_birth_month'] ?? 0),
            'user_birth_year' => (int) ($input['user_birth_year'] ?? 0),
            'user_details_city' => (string) ($input['user_details_city'] ?? ''),
            'user_details_zip' => (string) ($input['user_details_zip'] ?? ''),
            'user_details_street' => (string) ($input['user_details_street'] ?? ''),
            'user_details_phone' => (string) ($input['user_details_phone'] ?? ''),
            'user_details_website' => (string) ($input['user_details_website'] ?? ''),
            'user_details_ffb_favourite_team' => (int) ($input['user_details_ffb_favourite_team'] ?? 0),
            'user_details_avatar' => $avatar,
            'user_details_avatar_url' => UserAvatar::publicUrl($avatar),
            'user_permissions_ffb_mailservice_reminder' => (int) ($input['user_permissions_ffb_mailservice_reminder'] ?? 0) === 1 ? 1 : 0,
            'user_permissions_ffb_mailservice_info' => (int) ($input['user_permissions_ffb_mailservice_info'] ?? 0) === 1 ? 1 : 0,
            'user_permissions_ffb_visible_profile' => (int) ($input['user_permissions_ffb_visible_profile'] ?? 0) === 1 ? 1 : 0,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function mergeProfileForm(WebUser $user, array $input): array
    {
        $base = $this->profileFormFromUser($user);
        $shirt = trim((string) ($input['user_details_avatar_shirt'] ?? ''));

        $merged = array_merge($base, [
            'user_fname' => (string) ($input['user_fname'] ?? $base['user_fname']),
            'user_lname' => (string) ($input['user_lname'] ?? $base['user_lname']),
            'user_nationality' => (string) ($input['user_nationality'] ?? $base['user_nationality']),
            'user_birth_day' => (int) ($input['user_birth_day'] ?? $base['user_birth_day']),
            'user_birth_month' => (int) ($input['user_birth_month'] ?? $base['user_birth_month']),
            'user_birth_year' => (int) ($input['user_birth_year'] ?? $base['user_birth_year']),
            'user_details_city' => (string) ($input['user_details_city'] ?? $base['user_details_city']),
            'user_details_zip' => (string) ($input['user_details_zip'] ?? $base['user_details_zip']),
            'user_details_street' => (string) ($input['user_details_street'] ?? $base['user_details_street']),
            'user_details_phone' => (string) ($input['user_details_phone'] ?? $base['user_details_phone']),
            'user_details_website' => (string) ($input['user_details_website'] ?? $base['user_details_website']),
            'user_details_ffb_favourite_team' => (int) ($input['user_details_ffb_favourite_team'] ?? $base['user_details_ffb_favourite_team']),
            'user_permissions_ffb_mailservice_reminder' => (int) ($input['user_permissions_ffb_mailservice_reminder'] ?? $base['user_permissions_ffb_mailservice_reminder']) === 1 ? 1 : 0,
            'user_permissions_ffb_mailservice_info' => (int) ($input['user_permissions_ffb_mailservice_info'] ?? $base['user_permissions_ffb_mailservice_info']) === 1 ? 1 : 0,
            'user_permissions_ffb_visible_profile' => (int) ($input['user_permissions_ffb_visible_profile'] ?? $base['user_permissions_ffb_visible_profile']) === 1 ? 1 : 0,
        ]);

        if ($shirt !== '') {
            $merged['user_details_avatar'] = $shirt;
            $merged['user_details_avatar_url'] = UserAvatar::publicUrl($shirt);
        }

        return $merged;
    }

    /**
     * @return list<string>
     */
    private function validateProfile(Request $request): array
    {
        $errors = [];

        $day = (int) $request->input('user_birth_day', 0);
        $month = (int) $request->input('user_birth_month', 0);
        $year = (int) $request->input('user_birth_year', 0);
        if ($day > 0 || $month > 0 || $year > 0) {
            if ($day <= 0 || $month <= 0 || $year <= 0 || ! checkdate($month, $day, $year)) {
                $errors[] = 'Das Geburtsdatum ist nicht gültig!';
            }
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function validateAvatar(Request $request): array
    {
        $errors = [];
        $shirt = trim((string) $request->input('user_details_avatar_shirt', ''));
        if ($shirt !== '' && (! UserAvatar::isShirtPath($shirt) || ! TeamShirt::relativeExists($shirt))) {
            $errors[] = 'Bitte wähle ein gültiges Trikot als Avatar.';
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function validateAvatarCustom(string $svg): array
    {
        if (trim($svg) === '') {
            return ['Bitte generiere zuerst ein Trikot.'];
        }

        if (! CustomAvatarSvg::isAcceptable($svg)) {
            return ['Das generierte Trikot ist ungültig.'];
        }

        return [];
    }

    private function normalizeWebsite(string $website): string
    {
        $website = trim($website);
        if ($website === '') {
            return '';
        }

        if (! preg_match('#^https?://#i', $website)) {
            return 'http://'.$website;
        }

        return $website;
    }

    private function mailPermissionEnabled(mixed $value): bool
    {
        return $value !== null && (string) $value !== '' && (string) $value !== '0';
    }

    private function applyMailPermission(UserPermissions $permissions, string $column, bool $enabled): void
    {
        $current = (string) ($permissions->{$column} ?? '0');
        if ($enabled && ($current === '0' || $current === '')) {
            $permissions->{$column} = md5(uniqid((string) time(), true));
        } elseif (! $enabled) {
            $permissions->{$column} = '0';
        }
    }

    /**
     * @return list<array{team_id: int, team_name: string, team_nationality: string}>
     */
    private function teams(): array
    {
        return Team::query()
            ->where('team_status', 1)
            ->where('team_nationality', '!=', '')
            ->orderBy('team_name')
            ->get(['team_id', 'team_name', 'team_nationality'])
            ->map(fn (Team $team) => [
                'team_id' => (int) $team->team_id,
                'team_name' => (string) $team->team_name,
                'team_nationality' => (string) $team->team_nationality,
            ])
            ->all();
    }

    /**
     * Teams that have a default SVG shirt available for the avatar picker.
     *
     * @return list<array{team_id: int, team_name: string, path: string, url: string}>
     */
    private function avatarShirtOptions(): array
    {
        $options = [];

        $teams = Team::query()
            ->where('team_status', 1)
            ->whereNotNull('asset_key')
            ->where('asset_key', '!=', '')
            ->where('team_nationality', '!=', '')
            ->orderBy('team_name')
            ->get(['team_id', 'team_name', 'team_nationality', 'asset_key']);

        foreach ($teams as $team) {
            $path = TeamShirt::svgRelativePath(
                (string) $team->asset_key,
                (string) $team->team_nationality,
            );
            if ($path === null) {
                continue;
            }

            $options[] = [
                'team_id' => (int) $team->team_id,
                'team_name' => (string) $team->team_name,
                'path' => $path,
                'url' => '/images/ffb/'.$path,
            ];
        }

        return $options;
    }
}
