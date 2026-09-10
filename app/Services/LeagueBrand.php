<?php

namespace App\Services;

use App\Models\League;

class LeagueBrand
{
    /**
     * @return array{league_id: int, league_title: string, symbol_url: string}|null
     */
    public function forLeagueId(int $leagueId): ?array
    {
        if ($leagueId <= 0) {
            return null;
        }

        $league = League::query()->find($leagueId);
        if (! $league) {
            return null;
        }

        return [
            'league_id' => (int) $league->league_id,
            'league_title' => (string) $league->league_title,
            'symbol_url' => '/images/ffb/symbols/'.($league->league_symbol ?: 'symbol_game_na.png'),
        ];
    }
}
