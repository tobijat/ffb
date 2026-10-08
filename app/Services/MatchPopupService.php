<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\MatchGame;
use App\Models\Playerstats;
use App\Models\Psgoal;

class MatchPopupService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function forMatch(int $matchId): array
    {
        if ($matchId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'match_id is required'];
        }

        $match = MatchGame::query()
            ->with(['homeTeam', 'guestTeam', 'matchround.league'])
            ->find($matchId);

        if (! $match || ! $match->homeTeam || ! $match->guestTeam || ! $match->matchround) {
            return ['ok' => false, 'status' => 404, 'error' => 'Match not found'];
        }

        $homeTeamId = (int) $match->match_hometeam_id;
        $guestTeamId = (int) $match->match_guestteam_id;

        return [
            'ok' => true,
            'data' => [
                'match' => [
                    'match_id' => (int) $match->match_id,
                    'match_hometeam_id' => $homeTeamId,
                    'match_guestteam_id' => $guestTeamId,
                    'match_hometeam_name' => (string) $match->homeTeam->team_name,
                    'match_guestteam_name' => (string) $match->guestTeam->team_name,
                    'match_hometeam_nationality' => (string) ($match->homeTeam->team_nationality ?: ''),
                    'match_guestteam_nationality' => (string) ($match->guestTeam->team_nationality ?: ''),
                    'match_hometeam_score' => $this->nullableScore($match->match_homescore),
                    'match_guestteam_score' => $this->nullableScore($match->match_guestscore),
                    'match_hometeam_score_penalty' => $this->nullableScore($match->match_homescore_penalty),
                    'match_guestteam_score_penalty' => $this->nullableScore($match->match_guestscore_penalty),
                    'match_minutes' => (int) ($match->match_minutes ?? 0),
                    'match_date' => MatchGame::formatDisplayDate(
                        $match->match_date !== null ? (string) $match->match_date : null
                    ),
                    'match_matchround_id' => (int) $match->match_round,
                    'match_matchround_name' => (string) $match->matchround->matchround_title,
                    'match_league_title' => (string) ($match->matchround->league?->league_title ?? ''),
                ],
                'hometeam_players' => $this->playersForTeam($matchId, $homeTeamId),
                'guestteam_players' => $this->playersForTeam($matchId, $guestTeamId),
                'goals' => $this->goals($matchId),
                'psgoals' => $this->psgoals($matchId),
                'prev_matches' => $this->previousMatches($matchId, $homeTeamId, $guestTeamId),
            ],
        ];
    }

    private function nullableScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function playersForTeam(int $matchId, int $teamId): array
    {
        $positionOrder = ['g' => 0, 'd' => 1, 'm' => 2, 's' => 3];

        $stats = Playerstats::query()
            ->with(['playerteam.player'])
            ->where('playerstats_match_id', $matchId)
            ->whereHas('playerteam', fn ($q) => $q->where('playerteam_team_id', $teamId))
            ->get()
            ->sortBy(function (Playerstats $s) use ($positionOrder) {
                $pos = strtolower((string) ($s->playerteam?->playerteam_player_position ?? ''));

                return $positionOrder[$pos] ?? 99;
            })
            ->values();

        return $stats->map(function (Playerstats $item) {
            $pt = $item->playerteam;
            $player = $pt?->player;
            $name = trim(($player->player_fname ?? '').' '.($player->player_lname ?? ''));

            return [
                'player_playerteam_id' => (int) $item->playerstats_playerteam_id,
                'player_name' => $name !== '' ? $name : 'Unbekannt',
                'player_playerteam_position' => (string) ($pt->playerteam_player_position ?? ''),
                'player_playerstats_minute_in' => (int) ($item->playerstats_minute_in ?? 0),
                'player_playerstats_minute_out' => (int) ($item->playerstats_minute_out ?? 0),
                'player_playerstats_minutes' => (int) ($item->playerstats_minutes ?? 0),
                'player_playerstats_cards' => (string) ($item->playerstats_cards ?: 'n'),
                'player_playerstats_goals' => (int) ($item->playerstats_goals ?? 0),
                'player_playerstats_assists' => (int) ($item->playerstats_assists ?? 0),
                'player_playerstats_owngoals' => (int) ($item->playerstats_owngoals ?? 0),
                'player_playerstats_penaltiessaved' => (int) ($item->playerstats_penaltiessaved ?? 0),
                'player_playerstats_penaltieslost' => (int) ($item->playerstats_penaltieslost ?? 0),
                'player_playerstats_penaltyshootout_hit' => (int) ($item->playerstats_penaltyshootout_hit ?? 0),
                'player_playerstats_penaltyshootout_lost' => (int) ($item->playerstats_penaltyshootout_lost ?? 0),
                'player_playerstats_penaltyshootout_save' => (int) ($item->playerstats_penaltyshootout_save ?? 0),
            ];
        })->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function goals(int $matchId): array
    {
        $goals = Goal::query()
            ->with(['playerteam.player', 'playerteam.team'])
            ->where('goal_match_id', $matchId)
            ->orderBy('goal_minute')
            ->get();

        return $goals->map(function (Goal $item) {
            $pt = $item->playerteam;
            $player = $pt?->player;
            $name = trim(($player->player_fname ?? '').' '.($player->player_lname ?? ''));

            return [
                'goal_minute' => (int) $item->goal_minute,
                'goal_playerteam_id' => (int) $item->goal_playerteam_id,
                'goal_team_id' => (int) ($pt->playerteam_team_id ?? 0),
                'goal_team_name' => (string) ($pt?->team?->team_name ?? ''),
                'goal_player_name' => $name !== '' ? $name : 'Unbekannt',
                'goal_owngoal' => (bool) $item->goal_owngoal,
                'goal_penalty' => (bool) $item->goal_penalty,
                'goal_penaltyshootout' => (bool) $item->goal_penaltyshootout,
            ];
        })->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function psgoals(int $matchId): array
    {
        $items = Psgoal::query()
            ->with(['playerteam.player', 'playerteam.team'])
            ->where('psgoal_match_id', $matchId)
            ->get()
            ->sortBy(fn (Psgoal $g) => (int) ($g->playerteam?->playerteam_team_id ?? 0))
            ->values();

        return $items->map(function (Psgoal $item) {
            $pt = $item->playerteam;
            $player = $pt?->player;
            $team = $pt?->team;
            $name = trim(($player->player_fname ?? '').' '.($player->player_lname ?? ''));

            return [
                'psgoal_minute' => (int) $item->psgoal_minute,
                'psgoal_playerteam_id' => (int) $item->psgoal_playerteam_id,
                'psgoal_team_id' => (int) ($pt->playerteam_team_id ?? 0),
                'psgoal_team_name' => (string) ($team->team_name ?? ''),
                'psgoal_team_nationality' => (string) ($team->team_nationality ?? ''),
                'psgoal_player_name' => $name !== '' ? $name : 'Unbekannt',
                'psgoal_hit' => (bool) $item->psgoal_hit,
                'psgoal_fail' => (bool) $item->psgoal_fail,
            ];
        })->all();
    }

    /**
     * Finished meetings between the two teams (either home/away), newest first,
     * excluding the match currently shown in the modal.
     *
     * @return list<array<string, mixed>>
     */
    private function previousMatches(int $matchId, int $homeTeamId, int $guestTeamId): array
    {
        $matches = MatchGame::query()
            ->with(['homeTeam', 'guestTeam', 'matchround.league'])
            ->where('match_id', '!=', $matchId)
            ->where('match_homescore', '>=', 0)
            ->where('match_guestscore', '>=', 0)
            ->whereHas('matchround.league', fn ($q) => $q->forPlayerApp())
            ->where(function ($q) use ($homeTeamId, $guestTeamId) {
                $q->where(function ($inner) use ($homeTeamId, $guestTeamId) {
                    $inner->where('match_hometeam_id', $homeTeamId)
                        ->where('match_guestteam_id', $guestTeamId);
                })->orWhere(function ($inner) use ($homeTeamId, $guestTeamId) {
                    $inner->where('match_hometeam_id', $guestTeamId)
                        ->where('match_guestteam_id', $homeTeamId);
                });
            })
            ->orderByDesc('match_date')
            ->orderByDesc('match_id')
            ->get();

        return $matches->map(function (MatchGame $item) {
            $leagueTitle = (string) ($item->matchround?->league?->league_title ?? '');
            $roundTitle = (string) ($item->matchround?->matchround_title ?? '');

            return [
                'match_id' => (int) $item->match_id,
                'match_date' => MatchGame::formatDisplayDateOnly(
                    $item->match_date !== null ? (string) $item->match_date : null
                ),
                'match_hometeam_id' => (int) $item->match_hometeam_id,
                'match_guestteam_id' => (int) $item->match_guestteam_id,
                'match_hometeam_name' => (string) ($item->homeTeam?->team_name ?? ''),
                'match_guestteam_name' => (string) ($item->guestTeam?->team_name ?? ''),
                'match_hometeam_nationality' => (string) ($item->homeTeam?->team_nationality ?? ''),
                'match_guestteam_nationality' => (string) ($item->guestTeam?->team_nationality ?? ''),
                'match_hometeam_score' => $this->nullableScore($item->match_homescore),
                'match_guestteam_score' => $this->nullableScore($item->match_guestscore),
                'match_hometeam_score_penalty' => $this->nullableScore($item->match_homescore_penalty),
                'match_guestteam_score_penalty' => $this->nullableScore($item->match_guestscore_penalty),
                'match_minutes' => (int) ($item->match_minutes ?? 0),
                'match_matchround_id' => (int) $item->match_round,
                'match_matchround_name' => $this->formatRoundDisplayTitle($leagueTitle, $roundTitle),
                'match_league_title' => $leagueTitle,
            ];
        })->all();
    }

    private function formatRoundDisplayTitle(string $leagueTitle, string $roundTitle): string
    {
        $league = trim($leagueTitle);
        $round = trim($roundTitle);

        if ($league === '') {
            return $round;
        }

        if ($round === '') {
            return $league;
        }

        return $league.' - '.$round;
    }
}
