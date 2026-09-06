<?php

namespace App\Services;

use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Playerteam;
use Illuminate\Support\Facades\DB;

/**
 * Ports legacy playerRanking::calculatePlayerGrade_v2 for lineup list stars/trend.
 */
class PlayerGradeService
{
    /**
     * @return array{player_grade: int, player_trend: int}
     */
    public function gradeForPlayerteam(int $playerteamId): array
    {
        $limit = 5;

        $playerteam = Playerteam::query()->with('player')->find($playerteamId);
        if (! $playerteam || ! $playerteam->player) {
            return ['player_grade' => 0, 'player_trend' => 0];
        }

        $pos = (string) $playerteam->playerteam_player_position;
        $teamId = (int) $playerteam->playerteam_team_id;

        $ptIds = Playerteam::query()
            ->where('playerteam_player_id', $playerteam->playerteam_player_id)
            ->pluck('playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $gameIds = $this->gameIdsForPlayerteams($ptIds);
        if ($gameIds === []) {
            return ['player_grade' => 0, 'player_trend' => 0];
        }

        $numPlayers = Playerstats::query()
            ->join('ffb_matchround', 'ffb_playerstats.playerstats_matchround_id', '=', 'ffb_matchround.matchround_id')
            ->join('ffb_playerteam', 'ffb_playerstats.playerstats_playerteam_id', '=', 'ffb_playerteam.playerteam_id')
            ->whereIn('ffb_matchround.matchround_game_id', $gameIds)
            ->where('ffb_playerteam.playerteam_player_position', $pos)
            ->count();

        $sumPointsAll = (float) (Playerstats::query()
            ->join('ffb_matchround', 'ffb_playerstats.playerstats_matchround_id', '=', 'ffb_matchround.matchround_id')
            ->join('ffb_playerteam', 'ffb_playerstats.playerstats_playerteam_id', '=', 'ffb_playerteam.playerteam_id')
            ->whereIn('ffb_matchround.matchround_game_id', $gameIds)
            ->where('ffb_playerteam.playerteam_player_position', $pos)
            ->sum('ffb_playerstats.playerstats_score') ?? 0);

        $avgPointsPerPlayerPerMatch = $numPlayers > 0 ? $sumPointsAll / $numPlayers : 0.0;

        $mrIds = Matchround::query()
            ->select('ffb_matchround.matchround_id')
            ->join('ffb_match', 'ffb_match.match_round', '=', 'ffb_matchround.matchround_id')
            ->whereIn('ffb_matchround.matchround_game_id', $gameIds)
            ->where(function ($q) use ($teamId) {
                $q->where('ffb_match.match_hometeam_id', $teamId)
                    ->orWhere('ffb_match.match_guestteam_id', $teamId);
            })
            ->where('ffb_match.match_homescore', '>', -1)
            ->orderByDesc('ffb_match.match_date')
            ->limit($limit)
            ->pluck('matchround_id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $sumPointsPlayer = (float) (Playerstats::query()
            ->whereIn('playerstats_playerteam_id', $ptIds)
            ->sum('playerstats_score') ?? 0);
        $sumStatsPlayer = Playerstats::query()
            ->whereIn('playerstats_playerteam_id', $ptIds)
            ->count();
        $avgPlayerPointsPerMatch = $sumStatsPlayer > 0 ? $sumPointsPlayer / $sumStatsPlayer : 0.0;

        $effectiveLimit = min($limit, count($mrIds));
        $matchesPlayed = $mrIds === [] ? 0 : Playerstats::query()
            ->whereIn('playerstats_matchround_id', $mrIds)
            ->whereIn('playerstats_playerteam_id', $ptIds)
            ->count();
        $percPlayed = $effectiveLimit > 0 ? $matchesPlayed / $effectiveLimit : 0.0;

        $workingMrIds = $mrIds;
        $b1 = 0.0;
        $b2 = 0.0;
        $mult = 1;
        for ($i = $effectiveLimit; $i > 0; $i--) {
            if ($workingMrIds === []) {
                break;
            }
            $sumLimit = (float) (Playerstats::query()
                ->whereIn('playerstats_matchround_id', $workingMrIds)
                ->whereIn('playerstats_playerteam_id', $ptIds)
                ->sum('playerstats_score') ?? 0);
            $avg = $sumLimit / $i;
            array_pop($workingMrIds);
            $b1 += $mult * $avg;
            $b2 += $i * $avg;
            $mult++;
        }

        if ($b2 != 0.0) {
            $playerTrend = abs($b1 / $b2) * $percPlayed;
            $playerTrendPerc = (($b1 / $b2) - 1) * 100 * $percPlayed;
            if ($b1 < 0 && $b2 < 0) {
                $playerTrend *= -1;
                $playerTrendPerc *= -1;
            }
        } else {
            $playerTrend = 0.0;
            $playerTrendPerc = 0.0;
        }

        if ($avgPointsPerPlayerPerMatch > 0) {
            $percPlayer = (50 / $avgPointsPerPlayerPerMatch) * $avgPlayerPointsPerMatch;
        } else {
            $percPlayer = 0.0;
        }
        $percPlayer *= $playerTrend;

        $percPlayer = max(0.0, min(100.0, $percPlayer));
        $playerTrendPerc = max(-100.0, min(100.0, $playerTrendPerc));

        return [
            'player_grade' => (int) round($percPlayer),
            'player_trend' => (int) round($playerTrendPerc),
        ];
    }

    /**
     * @param  list<int>  $ptIds
     * @return list<int>
     */
    private function gameIdsForPlayerteams(array $ptIds): array
    {
        if ($ptIds === []) {
            return [];
        }

        return DB::table('ffb_playerstats')
            ->join('ffb_matchround', 'ffb_playerstats.playerstats_matchround_id', '=', 'ffb_matchround.matchround_id')
            ->whereIn('ffb_playerstats.playerstats_playerteam_id', $ptIds)
            ->distinct()
            ->pluck('ffb_matchround.matchround_game_id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values()
            ->all();
    }
}
