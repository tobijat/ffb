<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Userteam;
use App\Models\WebUser;
use App\Support\Flag;
use App\Support\LeagueSymbol;
use Illuminate\Support\Facades\DB;

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
     * Active leagues, ordered by most recent / upcoming match first.
     *
     * @return list<array{league_id: int, league_title: string, symbol_url: string}>
     */
    private function leagues(): array
    {
        $latestMatchDate = MatchGame::query()
            ->select(DB::raw('MAX(ffb_match.match_date)'))
            ->join('ffb_matchround', 'ffb_matchround.matchround_id', '=', 'ffb_match.match_round')
            ->whereColumn('ffb_matchround.matchround_league_id', 'ffb_league.league_id');

        return League::query()
            ->forPlayerApp()
            ->where('league_visible', 1)
            ->where('league_archive', 0)
            ->select([
                'ffb_league.league_id',
                'ffb_league.league_title',
                'ffb_league.asset_key',
            ])
            ->selectSub($latestMatchDate, 'latest_match_date')
            ->orderByRaw('latest_match_date is null')
            ->orderByDesc('latest_match_date')
            ->orderBy('ffb_league.league_title')
            ->get()
            ->map(fn (League $league) => [
                'league_id' => (int) $league->league_id,
                'league_title' => (string) $league->league_title,
                'symbol_url' => LeagueSymbol::url(
                    $league->asset_key !== null ? (string) $league->asset_key : null
                ),
            ])
            ->values()
            ->all();
    }

    private const COMBINED_RESULTS_LIMIT = 26;

    /**
     * Finished matches for the start page: current round first (most recent at top-left),
     * and if that round is only partly finished, continue with the previous round.
     * Combined lists are capped; a single complete round is shown in full.
     *
     * @return list<array<string, mixed>>
     */
    private function lastResults(): array
    {
        $currentRoundId = $this->mostRecentFinishedRoundId();
        if ($currentRoundId === null) {
            return [];
        }

        $currentFinished = $this->finishedMatchesForRound($currentRoundId);
        $roundHasUnfinished = MatchGame::query()
            ->where('match_round', $currentRoundId)
            ->where(function ($query): void {
                $query->where('match_homescore', '<=', -1)
                    ->orWhere('match_guestscore', '<=', -1);
            })
            ->exists();

        if (! $roundHasUnfinished) {
            return $this->mapMatchesToResults($currentFinished);
        }

        $previousRoundId = $this->previousFinishedRoundId($currentRoundId);
        if ($previousRoundId === null) {
            return $this->mapMatchesToResults($currentFinished);
        }

        $previousFinished = $this->finishedMatchesForRound($previousRoundId);
        $combined = $currentFinished->concat($previousFinished)->values();

        if ($combined->count() > self::COMBINED_RESULTS_LIMIT) {
            $combined = $combined->take(self::COMBINED_RESULTS_LIMIT)->values();
        }

        return $this->mapMatchesToResults($combined);
    }

    private function mostRecentFinishedRoundId(): ?int
    {
        $latestFinished = MatchGame::query()
            ->where('match_homescore', '>', -1)
            ->where('match_guestscore', '>', -1)
            ->where('match_round', '>', 0)
            ->orderByDesc('match_date')
            ->orderByDesc('match_id')
            ->first(['match_round']);

        if (! $latestFinished) {
            return null;
        }

        return (int) $latestFinished->match_round;
    }

    private function previousFinishedRoundId(int $currentRoundId): ?int
    {
        $previous = MatchGame::query()
            ->where('match_homescore', '>', -1)
            ->where('match_guestscore', '>', -1)
            ->where('match_round', '>', 0)
            ->where('match_round', '!=', $currentRoundId)
            ->orderByDesc('match_date')
            ->orderByDesc('match_id')
            ->first(['match_round']);

        if (! $previous) {
            return null;
        }

        return (int) $previous->match_round;
    }

    /**
     * @return \Illuminate\Support\Collection<int, MatchGame>
     */
    private function finishedMatchesForRound(int $roundId)
    {
        return MatchGame::query()
            ->with(['homeTeam', 'guestTeam', 'matchround.league'])
            ->where('match_round', $roundId)
            ->where('match_homescore', '>', -1)
            ->where('match_guestscore', '>', -1)
            ->orderByDesc('match_date')
            ->orderByDesc('match_id')
            ->get();
    }

    /**
     * @param  iterable<int, MatchGame>  $matches
     * @return list<array<string, mixed>>
     */
    private function mapMatchesToResults(iterable $matches): array
    {
        $results = [];
        foreach ($matches as $match) {
            if (! $match->homeTeam || ! $match->guestTeam) {
                continue;
            }

            $homeFlag = (string) $match->homeTeam->team_nationality;
            $guestFlag = (string) $match->guestTeam->team_nationality;
            $leagueTitle = trim((string) ($match->matchround?->league?->league_title ?? ''));
            $roundTitle = trim((string) ($match->matchround?->matchround_title ?? ''));
            $roundLabel = match (true) {
                $leagueTitle !== '' && $roundTitle !== '' => $leagueTitle.' - '.$roundTitle,
                $leagueTitle !== '' => $leagueTitle,
                $roundTitle !== '' => $roundTitle,
                default => '',
            };

            $results[] = [
                'home_team' => (string) $match->homeTeam->team_name,
                'home_score' => (string) $match->match_homescore,
                'home_flag' => strtolower($homeFlag),
                'home_flag_css' => $this->flagCssBackground($homeFlag),
                'guest_team' => (string) $match->guestTeam->team_name,
                'guest_score' => (string) $match->match_guestscore,
                'guest_flag' => strtolower($guestFlag),
                'guest_flag_css' => $this->flagCssBackground($guestFlag),
                'score_html' => $this->formatMatchScore($match),
                'round_label' => $roundLabel,
                'date' => date('d.m.Y', strtotime((string) $match->match_date)),
            ];
        }

        return $results;
    }

    private function flagCssBackground(?string $code): string
    {
        $url = Flag::svgUrl($code) ?? Flag::imageUrl($code);

        return "url('".e($url)."')";
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
            $html = e((string) $home).':'.e((string) $guest);
            if ((int) ($match->match_minutes ?? 0) === 120) {
                $html .= ' <span class="score-hint" title="nach Verlängerung">n.V.</span>';
            }

            return $html;
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
