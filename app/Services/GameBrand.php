<?php

namespace App\Services;

use App\Models\Game;

class GameBrand
{
    /**
     * @return array{game_id: int, game_title: string, symbol_url: string}|null
     */
    public function forGameId(int $gameId): ?array
    {
        if ($gameId <= 0) {
            return null;
        }

        $game = Game::query()->find($gameId);
        if (! $game) {
            return null;
        }

        return [
            'game_id' => (int) $game->game_id,
            'game_title' => (string) $game->game_title,
            'symbol_url' => '/images/ffb/symbols/'.($game->game_symbol ?: 'symbol_game_na.png'),
        ];
    }
}
