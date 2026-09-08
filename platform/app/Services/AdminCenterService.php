<?php

namespace App\Services;

use App\Models\Game;
use App\Models\UserDetails;
use App\Models\WebUser;

class AdminCenterService
{
    public function __construct(
        private readonly FfbAdminAccess $admins,
        private readonly LegacyPhpSession $legacySession,
        private readonly GameBrand $gameBrand,
    ) {
    }

    /**
     * Shared chrome for all admin pages (no full league grid).
     *
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>,
     *     selected_game_id: int,
     *     selected_game: array{game_id: int, game_title: string, symbol_url: string}|null
     * }
     */
    public function shellPayload(int $userId): array
    {
        $webUser = WebUser::query()->with('details')->find($userId);
        $photo = (string) ($webUser?->details?->user_details_photo ?: 'profile_na.png');
        $selectedGameId = $this->selectedGameId($userId);

        return [
            'user' => [
                'user_id' => $userId,
                'user_nickname' => (string) ($webUser?->user_nickname ?? ''),
                'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                'is_ffb_admin' => $this->admins->isAdmin($userId),
            ],
            'navigation' => $this->navigation(),
            'selected_game_id' => $selectedGameId,
            'selected_game' => $this->gameBrand->forGameId($selectedGameId),
        ];
    }

    /**
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>,
     *     games: list<array<string, mixed>>,
     *     selected_game_id: int,
     *     selected_game: array{game_id: int, game_title: string, symbol_url: string}|null
     * }
     */
    public function pagePayload(int $userId): array
    {
        return [
            ...$this->shellPayload($userId),
            'games' => $this->games(),
        ];
    }

    /**
     * League already chosen elsewhere (legacy admin session, else player selected game).
     */
    public function selectedGameId(int $userId): int
    {
        $adminGameId = (int) $this->legacySession->get('game_id_admin', 0);
        if ($adminGameId > 0 && Game::query()->whereKey($adminGameId)->exists()) {
            return $adminGameId;
        }

        $selected = (int) (UserDetails::query()
            ->whereKey($userId)
            ->value('user_details_ffb_selected_game') ?? 0);

        if ($selected > 0 && Game::query()->whereKey($selected)->exists()) {
            return $selected;
        }

        return 0;
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, game_id?: int}
     */
    public function selectGame(int $gameId): array
    {
        $game = Game::query()->find($gameId);
        if (! $game) {
            return [
                'ok' => false,
                'errors' => ['Liga nicht gefunden.'],
            ];
        }

        $this->legacySession->put([
            'game_id_admin' => (int) $game->game_id,
            'game_title_admin' => (string) $game->game_title,
        ]);

        return [
            'ok' => true,
            'message' => 'Liga „'.$game->game_title.'“ ausgewählt.',
            'game_id' => (int) $game->game_id,
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
                'link' => '/platform/admin/leagues',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_matchround.png',
                'name' => 'Spielrunden',
                'link' => '/platform/admin/matchrounds',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_match.png',
                'name' => 'Spiele',
                'link' => '/platform/admin/matches',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_team.png',
                'name' => 'Teams',
                'link' => '/platform/admin/teams',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_player.png',
                'name' => 'Spieler',
                'link' => '/platform/admin/players',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_coach.png',
                'name' => 'Kader',
                'link' => '/platform/admin/squad',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_config.png',
                'name' => 'DB Cleanup',
                'link' => '/platform/admin/db-cleanup',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_results.png',
                'name' => 'UserScore',
                'link' => '/platform/admin/matchpoints/config',
                'style' => 'big',
                'image_dir' => 'images/admin/navigation/',
            ],
            [
                'symbol' => 'nav_news.png',
                'name' => 'News',
                'link' => '/platform/admin/news',
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
    private function games(): array
    {
        return Game::query()
            ->orderBy('game_archive')
            ->orderBy('game_title')
            ->get()
            ->map(function (Game $game) {
                $status = (int) (bool) $game->game_status;
                $archive = (int) (bool) $game->game_archive;
                $visible = (int) (bool) $game->game_visible;
                $countdown = (int) (bool) $game->game_countdown;

                return [
                    'game_id' => (int) $game->game_id,
                    'game_title' => (string) $game->game_title,
                    'game_symbol' => (string) ($game->game_symbol ?: 'symbol_game_na.png'),
                    'symbol_url' => '/images/ffb/symbols/'.($game->game_symbol ?: 'symbol_game_na.png'),
                    'game_status' => $status,
                    'game_archive' => $archive,
                    'game_visible' => $visible,
                    'game_countdown' => $countdown,
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
