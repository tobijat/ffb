<?php

namespace App\Services;

use App\Models\Team;
use App\Models\UserPermissions;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountService
{
    public function __construct(
        private readonly FfbPassword $passwords,
        private readonly FfbAuth $auth,
    ) {
    }

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
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');

        $form = $formOverride ?? $this->formFromUser($user);

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'update_profile_nag' => empty($details?->user_details_photo) || $details?->user_details_photo === 'profile_na.png',
                ],
                'form' => $form,
                'countries' => config('countries', []),
                'birth_years' => $this->birthYears(),
                'navigation' => app(DashboardService::class)->navigation(),
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

        $user->user_fname = trim((string) ($input['user_fname'] ?? ''));
        $user->user_lname = trim((string) ($input['user_lname'] ?? ''));
        $user->user_nationality = (string) ($input['user_nationality'] ?? '');
        $user->user_date_birth = $this->birthDateFromInput($input);
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
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'update_profile_nag' => empty($details?->user_details_photo) || $details?->user_details_photo === 'profile_na.png',
                ],
                'form' => $formOverride ?? $this->profileFormFromUser($user),
                'teams' => $this->teams(),
                'navigation' => app(DashboardService::class)->navigation(),
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
        $oldPhoto = (string) ($details->user_details_photo ?: 'profile_na.png');
        $oldAvatar = (string) ($details->user_details_avatar ?: 'avatar_na.png');

        $photoFile = $request->file('user_details_photo');
        $avatarFile = $request->file('user_details_avatar');
        $deletePhoto = (int) ($input['user_details_photo_delete'] ?? 0) === 1;
        $deleteAvatar = (int) ($input['user_details_avatar_delete'] ?? 0) === 1;

        if (! $photoFile && $deletePhoto && $oldPhoto !== 'profile_na.png') {
            $this->deleteProfileImage('photo', $oldPhoto);
            $details->user_details_photo = 'profile_na.png';
            $oldPhoto = 'profile_na.png';
        }

        if (! $avatarFile && $deleteAvatar && $oldAvatar !== 'avatar_na.png') {
            $this->deleteProfileImage('avatar', $oldAvatar);
            $details->user_details_avatar = 'avatar_na.png';
            $oldAvatar = 'avatar_na.png';
        }

        if ($photoFile instanceof UploadedFile) {
            $stored = $this->storeProfileImage($photoFile, 'photo');
            if ($stored === null) {
                return [
                    'ok' => false,
                    'status' => 422,
                    'errors' => ['Problem beim Speichern des Profilfotos. Probier ein anderes.'],
                    'form' => $form,
                ];
            }
            if ($oldPhoto !== 'profile_na.png') {
                $this->deleteProfileImage('photo', $oldPhoto);
            }
            $details->user_details_photo = $stored;
        }

        if ($avatarFile instanceof UploadedFile) {
            $stored = $this->storeProfileImage($avatarFile, 'avatar');
            if ($stored === null) {
                return [
                    'ok' => false,
                    'status' => 422,
                    'errors' => ['Problem beim Speichern des Avatarbildes. Probier ein anderes.'],
                    'form' => $form,
                ];
            }
            if ($oldAvatar !== 'avatar_na.png') {
                $this->deleteProfileImage('avatar', $oldAvatar);
            }
            $details->user_details_avatar = $stored;
        }

        $details->user_details_zip = trim((string) ($input['user_details_zip'] ?? ''));
        $details->user_details_city = trim((string) ($input['user_details_city'] ?? ''));
        $details->user_details_street = trim((string) ($input['user_details_street'] ?? ''));
        $details->user_details_phone = trim((string) ($input['user_details_phone'] ?? ''));
        $details->user_details_website = $website;
        $details->user_details_ffb_favourite_team = (int) ($input['user_details_ffb_favourite_team'] ?? 0);
        // Do not touch own_team / own_player / selected_game (legacy POST often wiped these).
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

        $day = (int) ($input['user_birth_day'] ?? 0);
        $month = (int) ($input['user_birth_month'] ?? 0);
        $year = (int) ($input['user_birth_year'] ?? 0);
        if ($day > 0 || $month > 0 || $year > 0) {
            if ($day <= 0 || $month <= 0 || $year <= 0 || ! checkdate($month, $day, $year)) {
                $errors[] = 'Das Geburtsdatum ist nicht gültig!';
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
        $nickname = (string) $user->user_nickname;
        $userId = (int) $user->user_id;
        $actLink = 'http://'.$serverName.'/users/registration/activateEmail.html?id='.$activationCode.'-'.$userId;

        $message = "Hallo {$nickname}!\n\n";
        $message .= "Du hast auf http://{$serverName} deine E-Mail Adresse geändert.\n";
        $message .= "Um die Änderung abzuschließen, musst du nur noch folgenden Link anklicken oder ihn in die Adresszeile deines Browsers kopieren. ";
        $message .= "Anschließend kannst du dich wie gewohnt mit deinem Benutzernamen und Passwort anmelden.\n\n";
        $message .= $actLink."\n";

        Mail::raw($message, function ($mail) use ($user): void {
            $mail->to((string) $user->user_email)
                ->subject('E-Mail Änderung');
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function profileFormFromUser(WebUser $user): array
    {
        $details = $user->details;
        $permissions = UserPermissions::query()->find($user->user_id);
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
        $avatar = (string) ($details?->user_details_avatar ?: 'avatar_na.png');

        return [
            'user_details_city' => (string) ($details?->user_details_city ?? ''),
            'user_details_zip' => (string) ($details?->user_details_zip ?? ''),
            'user_details_street' => (string) ($details?->user_details_street ?? ''),
            'user_details_phone' => (string) ($details?->user_details_phone ?? ''),
            'user_details_website' => (string) ($details?->user_details_website ?? ''),
            'user_details_ffb_favourite_team' => (int) ($details?->user_details_ffb_favourite_team ?? 0),
            'user_details_photo' => $photo,
            'user_details_avatar' => $avatar,
            'user_details_photo_url' => '/images/ffb/profiles/photo/'.$photo,
            'user_details_avatar_url' => '/images/ffb/profiles/avatar/'.$avatar,
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
        $photo = (string) ($input['user_details_photo_name'] ?? 'profile_na.png');
        $avatar = (string) ($input['user_details_avatar_name'] ?? 'avatar_na.png');

        return [
            'user_details_city' => (string) ($input['user_details_city'] ?? ''),
            'user_details_zip' => (string) ($input['user_details_zip'] ?? ''),
            'user_details_street' => (string) ($input['user_details_street'] ?? ''),
            'user_details_phone' => (string) ($input['user_details_phone'] ?? ''),
            'user_details_website' => (string) ($input['user_details_website'] ?? ''),
            'user_details_ffb_favourite_team' => (int) ($input['user_details_ffb_favourite_team'] ?? 0),
            'user_details_photo' => $photo,
            'user_details_avatar' => $avatar,
            'user_details_photo_url' => '/images/ffb/profiles/photo/'.$photo,
            'user_details_avatar_url' => '/images/ffb/profiles/avatar/'.$avatar,
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

        return array_merge($base, [
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
    }

    /**
     * @return list<string>
     */
    private function validateProfile(Request $request): array
    {
        $errors = [];

        $photo = $request->file('user_details_photo');
        if ($photo instanceof UploadedFile && $photo->isValid()) {
            $image = @getimagesize($photo->getRealPath() ?: '');
            if ($image === false) {
                $errors[] = 'Problem beim Lesen des Profilfotos. Probier ein anderes.';
            } else {
                if ($image[0] > 1024 || $image[1] > 1024) {
                    $errors[] = 'Das Profilfoto darf maximal 1024x1024 Pixel groß sein.';
                }
                if ($photo->getSize() > 512000) {
                    $errors[] = 'Das Profilfoto darf maximal 500 Kilobyte groß sein.';
                }
            }
        }

        $avatar = $request->file('user_details_avatar');
        if ($avatar instanceof UploadedFile && $avatar->isValid()) {
            $image = @getimagesize($avatar->getRealPath() ?: '');
            if ($image === false) {
                $errors[] = 'Problem beim Lesen des Avatarbildes. Probier ein anderes.';
            } else {
                if ($image[0] > 90 || $image[1] > 90) {
                    $errors[] = 'Das Avatarbild darf maximal 90x90 Pixel groß sein.';
                }
                if ($avatar->getSize() > 102400) {
                    $errors[] = 'Das Avatarbild darf maximal 100 Kilobyte groß sein.';
                }
            }
        }

        return $errors;
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

    private function storeProfileImage(UploadedFile $file, string $kind): ?string
    {
        $dir = $this->profileImageDir($kind);
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return null;
        }

        $ext = strtolower((string) $file->getClientOriginalExtension());
        if ($ext === '' || ! preg_match('/^[a-z0-9]{1,5}$/', $ext)) {
            $ext = 'jpg';
        }

        $name = md5(uniqid((string) time(), true)).'.'.$ext;
        try {
            $file->move($dir, $name);
        } catch (\Throwable) {
            return null;
        }

        return $name;
    }

    private function deleteProfileImage(string $kind, string $filename): void
    {
        if ($filename === '' || str_contains($filename, '/') || str_contains($filename, '\\')) {
            return;
        }

        $path = $this->profileImageDir($kind).DIRECTORY_SEPARATOR.$filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function profileImageDir(string $kind): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$kind;
    }
}
