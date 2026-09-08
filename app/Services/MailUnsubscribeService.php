<?php

namespace App\Services;

use App\Models\UserPermissions;
use App\Services\FfbAuth;
use Illuminate\Http\Request;

class MailUnsubscribeService
{
    public function __construct(
        private readonly FfbAuth $auth,
    ) {
    }

    /**
     * @return array{ok: true, message: string}|array{ok: false, errors: list<string>}
     */
    public function cancel(string $id, string $type, Request $request): array
    {
        $this->auth->logout($request);

        $parts = explode('-', $id, 2);
        $code = $parts[0] ?? '';
        $userId = (int) ($parts[1] ?? 0);

        if ($code === '' || $userId <= 0 || ! in_array($type, ['r', 'i'], true)) {
            return [
                'ok' => false,
                'errors' => [
                    'Der Link ist ungültig oder wurde bereits verwendet. Eventuell hast du das Mailservice bereits deaktiviert.',
                ],
            ];
        }

        $permissions = UserPermissions::query()->where('user_id', $userId)->first();
        if (! $permissions) {
            return [
                'ok' => false,
                'errors' => [
                    'Der Link ist ungültig oder wurde bereits verwendet. Eventuell hast du das Mailservice bereits deaktiviert.',
                ],
            ];
        }

        if ($type === 'r') {
            if ((string) $permissions->user_permissions_ffb_mailservice_reminder !== $code) {
                return [
                    'ok' => false,
                    'errors' => [
                        'Der Link ist ungültig oder wurde bereits verwendet. Eventuell hast du das Mailservice bereits deaktiviert.',
                    ],
                ];
            }
            $permissions->user_permissions_ffb_mailservice_reminder = '0';
            $permissions->save();

            return [
                'ok' => true,
                'message' => 'Du bekommst in Zukunft keine Erinnerungsmails mehr. Erinnerungsmails können unter "Profil" wieder aktiviert werden.',
            ];
        }

        if ((string) $permissions->user_permissions_ffb_mailservice_info !== $code) {
            return [
                'ok' => false,
                'errors' => [
                    'Der Link ist ungültig oder wurde bereits verwendet. Eventuell hast du das Mailservice bereits deaktiviert.',
                ],
            ];
        }

        $permissions->user_permissions_ffb_mailservice_info = '0';
        $permissions->save();

        return [
            'ok' => true,
            'message' => 'Du bekommst in Zukunft keine Infomails mehr. Infomails können unter "Profil" wieder aktiviert werden.',
        ];
    }
}
