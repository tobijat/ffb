<?php

namespace App\Services;

use App\Mail\AdminBulkMail;
use App\Models\Game;
use App\Models\Matchround;
use App\Models\UserPermissions;
use App\Models\WebMail;
use App\Models\WebUser;
use Illuminate\Support\Facades\Mail;

class AdminMailserviceService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);

        return [
            ...$shell,
            'games' => $this->activeGames(),
            'mails' => $this->mailHistory(),
        ];
    }

    /**
     * @return list<array{game_id: int, game_title: string}>
     */
    public function activeGames(): array
    {
        return Game::query()
            ->where('game_status', 1)
            ->orderByDesc('game_id')
            ->get(['game_id', 'game_title'])
            ->map(static fn (Game $game): array => [
                'game_id' => (int) $game->game_id,
                'game_title' => (string) $game->game_title,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    public function matchroundsForGame(int $gameId): array
    {
        if ($gameId <= 0) {
            return [];
        }

        return Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->orderByDesc('matchround_startdate')
            ->get(['matchround_id', 'matchround_title'])
            ->map(static fn (Matchround $round): array => [
                'matchround_id' => (int) $round->matchround_id,
                'matchround_title' => (string) $round->matchround_title,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return list<array{user_id: int, user_nickname: string, user_email: string}>
     */
    public function users(array $filters): array
    {
        $gameId = (int) ($filters['game_id'] ?? 0);
        $matchroundId = (int) ($filters['matchround_id'] ?? 0);
        $mailservice = trim((string) ($filters['mailservice'] ?? ''));
        $userstatus = trim((string) ($filters['userstatus'] ?? ''));

        $query = WebUser::query()->orderBy('user_nickname');

        if ($userstatus === 'no_lineup') {
            $this->applyNoLineupFilter($query, $gameId, $matchroundId);
        } else {
            if ($userstatus !== '') {
                $query->where('user_status', $userstatus);
            }

            if ($matchroundId > 0) {
                $query->join('ffb_userteam', 'web_user.user_id', '=', 'ffb_userteam.userteam_user_id')
                    ->where('ffb_userteam.userteam_matchround_id', $matchroundId);
            } elseif ($gameId > 0) {
                $query->join('ffb_userscore', 'web_user.user_id', '=', 'ffb_userscore.userscore_user_id')
                    ->where('ffb_userscore.userscore_game_id', $gameId);
            }
        }

        if ($mailservice !== '') {
            $query->leftJoin('web_user_permissions', 'web_user.user_id', '=', 'web_user_permissions.user_id');
            if ($mailservice === 'opted_out') {
                $query->where(function ($q): void {
                    $q->whereNull('web_user_permissions.user_id')
                        ->orWhere(function ($q2): void {
                            $q2->where(function ($info): void {
                                $info->whereNull('web_user_permissions.user_permissions_ffb_mailservice_info')
                                    ->orWhere('web_user_permissions.user_permissions_ffb_mailservice_info', '')
                                    ->orWhere('web_user_permissions.user_permissions_ffb_mailservice_info', '0');
                            })->where(function ($reminder): void {
                                $reminder->whereNull('web_user_permissions.user_permissions_ffb_mailservice_reminder')
                                    ->orWhere('web_user_permissions.user_permissions_ffb_mailservice_reminder', '')
                                    ->orWhere('web_user_permissions.user_permissions_ffb_mailservice_reminder', '0');
                            });
                        });
                });
            } else {
                $query->whereNotNull('web_user_permissions.user_id');
                if ($mailservice === 'info' || $mailservice === 'info_reminder') {
                    $query->where('web_user_permissions.user_permissions_ffb_mailservice_info', '!=', '0')
                        ->where('web_user_permissions.user_permissions_ffb_mailservice_info', '!=', '')
                        ->whereNotNull('web_user_permissions.user_permissions_ffb_mailservice_info');
                }
                if ($mailservice === 'reminder' || $mailservice === 'info_reminder') {
                    $query->where('web_user_permissions.user_permissions_ffb_mailservice_reminder', '!=', '0')
                        ->where('web_user_permissions.user_permissions_ffb_mailservice_reminder', '!=', '')
                        ->whereNotNull('web_user_permissions.user_permissions_ffb_mailservice_reminder');
                }
            }
        }

        return $query
            ->select('web_user.user_id', 'web_user.user_nickname', 'web_user.user_email')
            ->distinct()
            ->get()
            ->map(static fn (WebUser $user): array => [
                'user_id' => (int) $user->user_id,
                'user_nickname' => (string) $user->user_nickname,
                'user_email' => (string) $user->user_email,
            ])
            ->values()
            ->all();
    }

    /**
     * Users who selected the given game (profile) but have no ffb_userteam in scope.
     * With no game selected: users with no lineup anywhere.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<\App\Models\WebUser>  $query
     */
    private function applyNoLineupFilter($query, int $gameId, int $matchroundId): void
    {
        if ($gameId > 0) {
            $query->join('web_user_details', 'web_user.user_id', '=', 'web_user_details.user_id')
                ->where('web_user_details.user_details_ffb_selected_game', $gameId);

            if ($matchroundId > 0) {
                $query->whereNotExists(function ($sub) use ($matchroundId): void {
                    $sub->selectRaw('1')
                        ->from('ffb_userteam')
                        ->whereColumn('ffb_userteam.userteam_user_id', 'web_user.user_id')
                        ->where('ffb_userteam.userteam_matchround_id', $matchroundId);
                });

                return;
            }

            $query->whereNotExists(function ($sub) use ($gameId): void {
                $sub->selectRaw('1')
                    ->from('ffb_userteam')
                    ->join('ffb_matchround', 'ffb_matchround.matchround_id', '=', 'ffb_userteam.userteam_matchround_id')
                    ->whereColumn('ffb_userteam.userteam_user_id', 'web_user.user_id')
                    ->where('ffb_matchround.matchround_game_id', $gameId);
            });

            return;
        }

        $query->whereNotExists(function ($sub): void {
            $sub->selectRaw('1')
                ->from('ffb_userteam')
                ->whereColumn('ffb_userteam.userteam_user_id', 'web_user.user_id');
        });
    }

    /**
     * @return array{mail: array<string, mixed>, users: list<array{user_id: int, user_nickname: string, user_email: string}>}|null
     */
    public function mailById(int $mailId): ?array
    {
        $item = WebMail::query()->find($mailId);
        if (! $item) {
            return null;
        }

        $userIds = array_values(array_filter(array_map(
            static fn (string $id): int => (int) trim($id),
            explode(',', (string) $item->mail_to)
        ), static fn (int $id): bool => $id > 0));

        $users = [];
        if ($userIds !== []) {
            $users = WebUser::query()
                ->whereIn('user_id', $userIds)
                ->orderBy('user_nickname')
                ->get(['user_id', 'user_nickname', 'user_email'])
                ->map(static fn (WebUser $user): array => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'user_email' => (string) $user->user_email,
                ])
                ->values()
                ->all();
        }

        return [
            'mail' => [
                'mail_id' => (int) $item->mail_id,
                'mail_sender' => (string) $item->mail_sender,
                'mail_date' => (string) $item->mail_date,
                'mail_subject' => (string) $item->mail_subject,
                'mail_text' => (string) $item->mail_text,
                'mail_num_reciepients' => (int) $item->mail_num_reciepients,
                'mail_criteria' => (string) $item->mail_criteria,
            ],
            'users' => $users,
        ];
    }

    /**
     * @param  list<int|string>  $userIds
     * @return array{ok: bool, message: string, num_send?: int}
     */
    public function send(array $userIds, string $subject, string $message, string $type, int $adminUserId, string $siteHost): array
    {
        $subject = trim($subject);
        $message = trim($message);
        $type = trim($type);
        $adminNickname = (string) (WebUser::query()->find($adminUserId)?->user_nickname ?: 'admin');
        $ids = array_values(array_unique(array_filter(array_map(
            static fn ($id): int => (int) $id,
            $userIds
        ), static fn (int $id): bool => $id > 0)));

        if ($ids === [] || $subject === '' || $message === '' || $type === '' || $adminNickname === '') {
            return [
                'ok' => false,
                'message' => 'The email could not be send to any user.',
            ];
        }

        if (! in_array($type, ['info', 'reminder', 'force'], true)) {
            return [
                'ok' => false,
                'message' => 'The email could not be send to any user.',
            ];
        }

        $prepared = $this->prepareMails($ids, $subject, $message, $type, $siteHost);
        if ($prepared === []) {
            return [
                'ok' => false,
                'message' => 'The email could not be send to any user.',
            ];
        }

        foreach ($prepared as $email) {
            Mail::to($email['mail_to'])->send(new AdminBulkMail(
                $email['mail_subject'],
                $email['mail_message'],
            ));
        }

        $this->logEmail($prepared, $subject, $message, $type, 'admin/'.$adminNickname);

        return [
            'ok' => true,
            'message' => 'The email was sent to '.count($prepared).' Users.',
            'num_send' => count($prepared),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function mailHistory(): array
    {
        return WebMail::query()
            ->orderByDesc('mail_date')
            ->limit(200)
            ->get()
            ->map(function (WebMail $item): array {
                $num = (int) $item->mail_num_reciepients;
                $mailTo = (string) $item->mail_to;
                if ($num === 1) {
                    $user = WebUser::query()->find((int) $mailTo);
                    $mailTo = $user ? (string) $user->user_email : $mailTo;
                }

                return [
                    'mail_id' => (int) $item->mail_id,
                    'mail_sender' => (string) $item->mail_sender,
                    'mail_date' => (string) $item->mail_date,
                    'mail_subject' => (string) $item->mail_subject,
                    'mail_text' => (string) $item->mail_text,
                    'mail_num_reciepients' => $num,
                    'mail_to' => $mailTo,
                    'mail_criteria' => (string) $item->mail_criteria,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<int>  $userIds
     * @return list<array{mail_user_id: int, mail_to: string, mail_subject: string, mail_message: string}>
     */
    private function prepareMails(array $userIds, string $subject, string $message, string $type, string $siteHost): array
    {
        $prefix = (string) config('ffb.mail.subject_prefix', '');
        $greez = (string) config('ffb.mail.greez', '');
        $url = (string) config('ffb.mail.url', '');
        $footerBase = "-- \nDu kannst auf dieses E-Mail nicht antworten. Wende dich bei Fragen oder Problemen an die auf der Website angegebene Adresse!\n";

        $out = [];
        foreach ($userIds as $userId) {
            $user = WebUser::query()->find($userId);
            if (! $user) {
                continue;
            }

            $unsubscribe = '';
            if ($type !== 'force') {
                $unsubscribe = $this->unsubscribeFooter($userId, $type, $siteHost);
                if ($unsubscribe === null) {
                    continue;
                }
            }

            $personal = str_replace('{*nickname*}', (string) $user->user_nickname, $message);
            $body = $personal."\n\n".$greez."\n".$url."\n\n".$footerBase.$unsubscribe."\n";

            $out[] = [
                'mail_user_id' => $userId,
                'mail_to' => (string) $user->user_email,
                'mail_subject' => $prefix.$subject,
                'mail_message' => wordwrap($body),
            ];
        }

        return $out;
    }

    private function unsubscribeFooter(int $userId, string $type, string $siteHost): ?string
    {
        $permissions = UserPermissions::query()->find($userId);
        if (! $permissions) {
            return null;
        }

        if ($type === 'reminder') {
            $mailtypeName = 'Erinnerungsmails';
            $code = (string) ($permissions->user_permissions_ffb_mailservice_reminder ?? '');
            $cancelType = 'r';
        } elseif ($type === 'info') {
            $mailtypeName = 'Infomails';
            $code = (string) ($permissions->user_permissions_ffb_mailservice_info ?? '');
            $cancelType = 'i';
        } else {
            return null;
        }

        if ($code === '' || $code === '0') {
            return null;
        }

        $link = 'http://'.$siteHost.'/mailservice/cancel?t='.$cancelType.'&id='.$code.'-'.$userId;

        $text = "Wenn du keine $mailtypeName mehr bekommen möchtest, kannst du sie in deinem Profil deaktivieren oder auf folgenden Link klicken um sie zu deaktivieren:\n";
        $text .= $link."\n";
        $text .= 'ACHTUNG: Wenn du diesen Link anklickst, wirst du bei SoccerSportsfan ausgeloggt und musst dich neu einloggen.';

        return $text;
    }

    /**
     * @param  list<array{mail_user_id: int, mail_to: string, mail_subject: string, mail_message: string}>  $mailArray
     */
    private function logEmail(array $mailArray, string $subject, string $message, string $type, string $sender): void
    {
        $ids = array_map(static fn (array $row): int => $row['mail_user_id'], $mailArray);

        WebMail::query()->create([
            'mail_date' => date('Y-m-d H:i:s'),
            'mail_sender' => $sender,
            'mail_to' => implode(',', $ids),
            'mail_cc' => '',
            'mail_bc' => '',
            'mail_subject' => $subject,
            'mail_text' => $message,
            'mail_num_reciepients' => count($mailArray),
            'mail_criteria' => $type,
        ]);
    }
}
