<?php

namespace App\Services;

use App\Models\League;
use App\Support\LeagueSymbol;

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
            'symbol_url' => LeagueSymbol::url((int) $league->league_id),
        ];
    }
}
