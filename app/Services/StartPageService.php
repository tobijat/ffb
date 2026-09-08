<?php

namespace App\Services;

use App\Models\Game;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Userteam;
use App\Models\WebUser;

class StartPageService
{
    /**
     * Public start-page payload (logged-out landing).
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return [
            'stats' => $this->stats(),
            'leagues' => $this->leagues(),
            'results' => $this->lastResults(),
        ];
    }

    /**
     * @return array<string, int|float>
     */
    private function stats(): array
    {
        $usersTotal = (int) WebUser::query()->count();
        $usersToday = (int) WebUser::query()
            ->where('user_date_llogin', '>=', now()->startOfDay()->format('Y-m-d H:i:s'))
            ->count();
        $lineups = (int) Userteam::query()->count();
        $scoreSum = (int) Userteam::query()->sum('userteam_score');
        $matchroundsPlayed = (int) Matchround::query()
            ->where('matchround_status', 1)
            ->count();

        $avg = $lineups > 0 ? round($scoreSum / $lineups, 2) : 0.0;

        return [
            'users_total' => $usersTotal,
            'users_today' => $usersToday,
            'lineups' => $lineups,
            'score_sum' => $scoreSum,
            'score_avg' => $avg,
            'matchrounds_played' => $matchroundsPlayed,
        ];
    }

    /**
     * @return list<array{game_id: int, game_title: string}>
     */
    private function leagues(): array
    {
        return Game::query()
            ->where('game_visible', 1)
            ->where('game_archive', 0)
            ->where('game_countdown', 1)
            ->where('game_status', 1)
            ->orderBy('game_title')
            ->get(['game_id', 'game_title'])
            ->map(fn (Game $game) => [
                'game_id' => (int) $game->game_id,
                'game_title' => (string) $game->game_title,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function lastResults(): array
    {
        $matches = MatchGame::query()
            ->with(['homeTeam', 'guestTeam'])
            ->where('match_homescore', '>', -1)
            ->where('match_guestscore', '>', -1)
            ->orderByDesc('match_date')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($matches as $match) {
            if (! $match->homeTeam || ! $match->guestTeam) {
                continue;
            }

            $results[] = [
                'home_team' => (string) $match->homeTeam->team_name,
                'home_score' => (string) $match->match_homescore,
                'home_flag' => strtolower((string) $match->homeTeam->team_nationality),
                'guest_team' => (string) $match->guestTeam->team_name,
                'guest_score' => (string) $match->match_guestscore,
                'guest_flag' => strtolower((string) $match->guestTeam->team_nationality),
                'score_html' => $this->formatMatchScore($match),
                'date' => date('d.m.Y', strtotime((string) $match->match_date)),
            ];
        }

        return $results;
    }

    private function formatMatchScore(MatchGame $match): string
    {
        $homePen = $this->nullableIntScore($match->match_homescore_penalty ?? null);
        $guestPen = $this->nullableIntScore($match->match_guestscore_penalty ?? null);
        $home = $this->nullableIntScore($match->match_homescore ?? null);
        $guest = $this->nullableIntScore($match->match_guestscore ?? null);

        if ($homePen !== null && $guestPen !== null) {
            $html = '<span class="score-final">'
                .e((string) $homePen).':'
                .e((string) $guestPen)
                .' <span class="score-hint" title="nach Elfmeterschießen">n.E.</span></span>';

            if ($home !== null && $guest !== null) {
                $html .= '<span class="score-reg">('
                    .e((string) $home).':'
                    .e((string) $guest)
                    .' <span class="score-hint" title="nach Verlängerung">n.V.</span>)</span>';
            }

            return $html;
        }

        if ($home !== null && $guest !== null) {
            return e((string) $home).':'.e((string) $guest);
        }

        return '-:-';
    }

    private function nullableIntScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        $score = (int) $value;

        return $score > -1 ? $score : null;
    }
}
