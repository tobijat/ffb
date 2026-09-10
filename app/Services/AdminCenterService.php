<?php

namespace App\Services;

use App\Models\League;
use App\Models\UserDetails;
use App\Models\WebUser;

class AdminCenterService
{
    public function __construct(
        private readonly FfbAdminAccess $admins,
        private readonly LegacyPhpSession $legacySession,
        private readonly LeagueBrand $leagueBrand,
    ) {}

    /**
     * Shared chrome for all admin pages (no full league grid).
     *
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>,
     *     selected_league_id: int,
     *     selected_league: array{league_id: int, league_title: string, symbol_url: string}|null
     * }
     */
    public function shellPayload(int $userId): array
    {
        $webUser = WebUser::query()->with('details')->find($userId);
        $photo = (string) ($webUser?->details?->user_details_photo ?: 'profile_na.png');
        $selectedLeagueId = $this->selectedLeagueId($userId);

        return [
            'user' => [
                'user_id' => $userId,
                'user_nickname' => (string) ($webUser?->user_nickname ?? ''),
                'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                'is_ffb_admin' => $this->admins->isAdmin($userId),
            ],
            'navigation' => $this->navigation(),
            'selected_league_id' => $selectedLeagueId,
            'selected_league' => $this->leagueBrand->forLeagueId($selectedLeagueId),
        ];
    }

    /**
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>,
     *     leagues: list<array<string, mixed>>,
     *     selected_league_id: int,
     *     selected_league: array{league_id: int, league_title: string, symbol_url: string}|null
     * }
     */
    public function pagePayload(int $userId): array
    {
        return [
            ...$this->shellPayload($userId),
            'leagues' => $this->leagues(),
        ];
    }

    /**
     * League already chosen elsewhere (legacy admin session, else player selected league).
     */
    public function selectedLeagueId(int $userId): int
    {
        $adminLeagueId = (int) $this->legacySession->get('league_id_admin', 0);
        if ($adminLeagueId > 0 && League::query()->whereKey($adminLeagueId)->exists()) {
            return $adminLeagueId;
        }

        $selected = (int) (UserDetails::query()
            ->whereKey($userId)
            ->value('user_details_ffb_selected_league') ?? 0);

        if ($selected > 0 && League::query()->whereKey($selected)->exists()) {
            return $selected;
        }

        return 0;
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, league_id?: int}
     */
    public function selectLeague(int $leagueId): array
    {
        $league = League::query()->find($leagueId);
        if (! $league) {
            return [
                'ok' => false,
                'errors' => ['Liga nicht gefunden.'],
            ];
        }

        $this->legacySession->put([
            'league_id_admin' => (int) $league->league_id,
            'league_title_admin' => (string) $league->league_title,
        ]);

        return [
            'ok' => true,
            'message' => 'Liga „'.$league->league_title.'“ ausgewählt.',
            'league_id' => (int) $league->league_id,
        ];
    }

    /**
     * @return list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>
     */
    public function navigation(): array
    {
        return [
            [
                'symbol' => 'nav_config.png',
                'name' => 'Ligen',
                'link' => '/admin/leagues',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_matchround.png',
                'name' => 'Runden',
                'link' => '/admin/matchrounds',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_match.png',
                'name' => 'Spiele',
                'link' => '/admin/matches',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_team.png',
                'name' => 'Teams',
                'link' => '/admin/teams',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_player.png',
                'name' => 'Spieler',
                'link' => '/admin/players',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_coach.png',
                'name' => 'Kader',
                'link' => '/admin/squad',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_config.png',
                'name' => 'Datenbank',
                'link' => '/admin/db-cleanup',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_score.png',
                'name' => 'Spieldaten',
                'link' => '/admin/matchdata',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_results.png',
                'name' => 'Score',
                'link' => '/admin/score',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_prices.png',
                'name' => 'Preis',
                'link' => '/admin/playerprice',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_mail.png',
                'name' => 'Mail',
                'link' => '/admin/mailservice',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_award.png',
                'name' => 'Awards',
                'link' => '/admin/awards',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_news.png',
                'name' => 'News',
                'link' => '/admin/news',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
        ];
    }

    /**
     * All leagues in the database for admin overview/selection.
     *
     * @return list<array<string, mixed>>
     */
    private function leagues(): array
    {
        return League::query()
            ->orderBy('league_archive')
            ->orderBy('league_title')
            ->get()
            ->map(function (League $league) {
                $status = (int) (bool) $league->league_status;
                $archive = (int) (bool) $league->league_archive;
                $visible = (int) (bool) $league->league_visible;
                $countdown = (int) (bool) $league->league_countdown;

                return [
                    'league_id' => (int) $league->league_id,
                    'league_title' => (string) $league->league_title,
                    'league_symbol' => (string) ($league->league_symbol ?: 'symbol_game_na.png'),
                    'symbol_url' => '/images/ffb/symbols/'.($league->league_symbol ?: 'symbol_game_na.png'),
                    'league_status' => $status,
                    'league_archive' => $archive,
                    'league_visible' => $visible,
                    'league_countdown' => $countdown,
                    'flags' => [
                        [
                            'label' => $status ? 'aktiv' : 'inaktiv',
                            'tone' => $status ? 'ok' : 'off',
                        ],
                        [
                            'label' => $archive ? 'archiviert' : 'aktuell',
                            'tone' => $archive ? 'warn' : 'ok',
                        ],
                        [
                            'label' => $visible ? 'sichtbar' : 'unsichtbar',
                            'tone' => $visible ? 'ok' : 'off',
                        ],
                        [
                            'label' => $countdown ? 'Countdown an' : 'Countdown aus',
                            'tone' => $countdown ? 'ok' : 'muted',
                        ],
                    ],
                ];
            })
            ->values()
            ->all();
    }
}
