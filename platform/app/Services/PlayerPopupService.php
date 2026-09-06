<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameOptions;
use App\Models\Goal;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\UserDetails;
use App\Models\Userteam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PlayerPopupService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function forPlayerteam(int $viewerId, int $playerteamId): array
    {
        if ($playerteamId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'playerteam_id is required'];
        }

        $playerteam = Playerteam::query()->with(['player', 'team'])->find($playerteamId);
        if (! $playerteam || ! $playerteam->player || ! $playerteam->team) {
            return ['ok' => false, 'status' => 404, 'error' => 'Player not found'];
        }

        $gameId = $this->selectedGameId($viewerId);
        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'No game selected'];
        }

        $options = GameOptions::query()->where('options_game_id', $gameId)->first();
        $priceMode = (string) ($options?->options_game_pricemode ?: 'constant');

        $ptIds = Playerteam::query()
            ->where('playerteam_player_id', $playerteam->playerteam_player_id)
            ->orderByDesc('playerteam_id')
            ->pluck('playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($ptIds === []) {
            $ptIds = [$playerteamId];
        }

        $now = date('Y-m-d H:i:s');
        $matchCountTotal = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_startdate', '<', $now)
            ->count();

        $numLineups = $this->countLineups($ptIds, $gameId);
        $matchCountPlayed = Playerstats::query()
            ->whereIn('playerstats_playerteam_id', $ptIds)
            ->whereHas('matchround', fn (Builder $q) => $q->where('matchround_game_id', $gameId))
            ->count();

        $rounds = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_status', 1)
            ->orderByDesc('matchround_startdate')
            ->get();

        $roundIds = $rounds->pluck('matchround_id')->map(fn ($id) => (int) $id)->all();
        $statsByRound = $this->statsByRound($ptIds, $roundIds);
        $lineupsByRound = $this->lineupsByRound($ptIds, $roundIds);

        $score = 0;
        $goals = 0;
        $assists = 0;
        $minutes = 0;
        $cardsY = 0;
        $cardsR = 0;
        $cardsYr = 0;
        $matchrounds = [];

        foreach ($rounds as $round) {
            $roundId = (int) $round->matchround_id;
            $row = [
                'matchround_id' => $roundId,
                'matchround_title' => (string) $round->matchround_title,
                'matchround_num_lineups' => $lineupsByRound[$roundId] ?? 0,
                'matchround_running' => strtotime((string) $round->matchround_startdate) > time() ? 1 : 0,
            ];

            $stat = $statsByRound[$roundId] ?? null;
            if ($stat) {
                $score += (int) $stat->playerstats_score;
                $goals += (int) $stat->playerstats_goals;
                $assists += (int) $stat->playerstats_assists;
                $minutes += (int) $stat->playerstats_minutes;
                $card = (string) ($stat->playerstats_cards ?: 'n');
                if ($card === 'y') {
                    $cardsY++;
                } elseif ($card === 'r') {
                    $cardsR++;
                } elseif ($card === 'yr') {
                    $cardsYr++;
                }

                $row['matchround_minutes_played'] = (int) $stat->playerstats_minutes;
                $row['matchround_score'] = (int) $stat->playerstats_score;
                $row['matchround_goals'] = (int) $stat->playerstats_goals;
                $row['matchround_assists'] = (int) $stat->playerstats_assists;
                $row['matchround_cards'] = $card;

                $teamId = (int) ($stat->playerteam?->playerteam_team_id
                    ?? $playerteam->playerteam_team_id);
                $row = array_merge($row, $this->matchFieldsForTeamRound($teamId, $roundId));
            } else {
                $row['matchround_minutes_played'] = '-';
                $row['matchround_score'] = '-';
                $row['matchround_goals'] = '-';
                $row['matchround_assists'] = '-';
                $row['matchround_cards'] = 'n';

                $ptNear = $this->teamForPlayerAndRound($round, $ptIds);
                if ($ptNear) {
                    $row = array_merge(
                        $row,
                        $this->matchFieldsForTeamRound((int) $ptNear->playerteam_team_id, $roundId)
                    );
                } else {
                    $row = array_merge($row, $this->emptyMatchFields());
                }
            }

            $matchrounds[] = $row;
        }

        $played = max(0, $matchCountPlayed);
        $pastMatches = [];
        if (count($matchrounds) < 10) {
            $pastMatches = $this->pastMatches($playerteamId, $gameId, 10 - count($matchrounds));
        }

        $teamId = (int) $playerteam->playerteam_team_id;
        $hasPicture = (bool) $playerteam->playerteam_player_picture;

        return [
            'ok' => true,
            'data' => [
                'player' => [
                    'playerteam_id' => $playerteamId,
                    'player_fname' => (string) $playerteam->player->player_fname,
                    'player_lname' => (string) $playerteam->player->player_lname,
                    'player_name' => trim($playerteam->player->player_fname.' '.$playerteam->player->player_lname),
                    'player_nationality' => (string) ($playerteam->player->player_nationality ?: ''),
                    'player_team_name' => (string) $playerteam->team->team_name,
                    'player_team_nationality' => (string) ($playerteam->team->team_nationality ?: ''),
                    'player_team_id' => $teamId,
                    'player_picture_url' => $hasPicture
                        ? '/images/ffb/players/'.$teamId.'/'.$playerteamId.'.jpg'
                        : '/images/ffb/players/image_na.gif',
                ],
                'pricemode' => $priceMode,
                'stats' => [
                    'num_lineups' => $numLineups,
                    'sum_score' => $score,
                    'sum_goals' => $goals,
                    'sum_assists' => $assists,
                    'sum_minutes' => $minutes,
                    'sum_cards_y' => $cardsY,
                    'sum_cards_yr' => $cardsYr,
                    'sum_cards_r' => $cardsR,
                    'av_score' => $played > 0 ? round($score / $played, 2) : 0,
                    'av_goals' => $played > 0 ? round($goals / $played, 2) : 0,
                    'av_assists' => $played > 0 ? round($assists / $played, 2) : 0,
                    'av_minutes' => $played > 0 ? round($minutes / $played, 2) : 0,
                    'match_count_total' => $matchCountTotal,
                    'match_count_played' => $played,
                    'match_count_percent' => $matchCountTotal > 0
                        ? round($played / $matchCountTotal * 100, 2)
                        : 0,
                ],
                'matchrounds' => $matchrounds,
                'pastmatches' => $pastMatches,
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function forRound(int $viewerId, int $playerteamId, int $matchroundId): array
    {
        if ($playerteamId <= 0 || $matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'playerteam_id and matchround_id are required'];
        }

        $playerteam = Playerteam::query()->with(['player', 'team'])->find($playerteamId);
        if (! $playerteam || ! $playerteam->player || ! $playerteam->team) {
            return ['ok' => false, 'status' => 404, 'error' => 'Player not found'];
        }

        $gameId = $this->selectedGameId($viewerId);
        $options = GameOptions::query()->where('options_game_id', $gameId)->first();
        $pointsMode = (string) ($options?->options_game_pointsmode ?: 'new');
        $priceMode = (string) ($options?->options_game_pricemode ?: 'constant');

        $teamId = (int) $playerteam->playerteam_team_id;
        $hasPicture = (bool) $playerteam->playerteam_player_picture;

        $base = [
            'playerteam_id' => $playerteamId,
            'matchround_id' => $matchroundId,
            'pricemode' => $priceMode,
            'player' => [
                'playerteam_id' => $playerteamId,
                'player_fname' => (string) $playerteam->player->player_fname,
                'player_lname' => (string) $playerteam->player->player_lname,
                'player_name' => trim($playerteam->player->player_fname.' '.$playerteam->player->player_lname),
                'player_nationality' => (string) ($playerteam->player->player_nationality ?: ''),
                'player_team_name' => (string) $playerteam->team->team_name,
                'player_team_nationality' => (string) ($playerteam->team->team_nationality ?: ''),
                'player_picture_url' => $hasPicture
                    ? '/images/ffb/players/'.$teamId.'/'.$playerteamId.'.jpg'
                    : '/images/ffb/players/image_na.gif',
            ],
        ];

        $stat = Playerstats::query()
            ->with(['playerteam'])
            ->where('playerstats_playerteam_id', $playerteamId)
            ->where('playerstats_matchround_id', $matchroundId)
            ->first();

        if (! $stat) {
            return [
                'ok' => true,
                'data' => array_merge($base, [
                    'played' => false,
                    'stats' => null,
                ]),
            ];
        }

        $match = MatchGame::query()->find((int) $stat->playerstats_match_id);
        $oppGoals = 0;
        $playerOppGoals = 0;
        $playerOppGoalsString = null;

        if ($match) {
            $ownTeamId = (int) $playerteam->playerteam_team_id;
            if ($ownTeamId === (int) $match->match_hometeam_id) {
                $oppGoals = (int) $match->match_guestscore;
                $oppositeTeamId = (int) $match->match_guestteam_id;
            } elseif ($ownTeamId === (int) $match->match_guestteam_id) {
                $oppGoals = (int) $match->match_homescore;
                $oppositeTeamId = (int) $match->match_hometeam_id;
            } else {
                $oppositeTeamId = 0;
            }

            if ($pointsMode === 'new' && $oppositeTeamId > 0) {
                [$playerOppGoals, $playerOppGoalsString] = $this->oppGoalsWhileOnPitch(
                    $stat,
                    $match,
                    $oppositeTeamId,
                    $ownTeamId
                );
            } else {
                $playerOppGoals = $oppGoals;
            }
        }

        return [
            'ok' => true,
            'data' => array_merge($base, [
                'played' => true,
                'stats' => [
                    'playerstats_goals' => (int) $stat->playerstats_goals,
                    'playerstats_assists' => (int) $stat->playerstats_assists,
                    'playerstats_minutes' => (int) $stat->playerstats_minutes,
                    'playerstats_minute_in' => (int) $stat->playerstats_minute_in,
                    'playerstats_minute_out' => (int) $stat->playerstats_minute_out,
                    'playerstats_cards' => (string) ($stat->playerstats_cards ?: 'n'),
                    'playerstats_owngoals' => (int) $stat->playerstats_owngoals,
                    'playerstats_penaltiessaved' => (int) $stat->playerstats_penaltiessaved,
                    'playerstats_penaltieslost' => (int) $stat->playerstats_penaltieslost,
                    'playerstats_penaltyshootout_lost' => (int) $stat->playerstats_penaltyshootout_lost,
                    'playerstats_penaltyshootout_hit' => (int) $stat->playerstats_penaltyshootout_hit,
                    'playerstats_penaltyshootout_save' => (int) $stat->playerstats_penaltyshootout_save,
                    'playerstats_oppgoals' => $oppGoals,
                    'playerstats_player_oppgoals' => $playerOppGoals,
                    'playerstats_player_oppgoals_string' => $playerOppGoalsString,
                    'playerstats_score_goals' => (int) $stat->playerstats_score_goals,
                    'playerstats_score_assists' => (int) $stat->playerstats_score_assists,
                    'playerstats_score_minutes' => (int) $stat->playerstats_score_minutes,
                    'playerstats_score_cards' => (int) $stat->playerstats_score_cards,
                    'playerstats_score_owngoals' => (int) $stat->playerstats_score_owngoals,
                    'playerstats_score_penaltiessaved' => (int) $stat->playerstats_score_penaltiessaved,
                    'playerstats_score_penaltieslost' => (int) $stat->playerstats_score_penaltieslost,
                    'playerstats_score_penaltyshootout_lost' => (int) $stat->playerstats_score_penaltyshootout_lost,
                    'playerstats_score_penaltyshootout_hit' => (int) $stat->playerstats_score_penaltyshootout_hit,
                    'playerstats_score_penaltyshootout_save' => (int) $stat->playerstats_score_penaltyshootout_save,
                    'playerstats_score_oppgoals' => (int) $stat->playerstats_score_oppgoals,
                    'playerstats_score_nooppgoals' => (int) $stat->playerstats_score_nooppgoals,
                    'playerstats_score' => (int) $stat->playerstats_score,
                ],
            ]),
        ];
    }

    private function selectedGameId(int $userId): int
    {
        return (int) (UserDetails::query()
            ->where('user_id', $userId)
            ->value('user_details_ffb_selected_game') ?? 0);
    }

    /**
     * @param  list<int>  $ptIds
     */
    private function countLineups(array $ptIds, int $gameId): int
    {
        return Userteam::query()
            ->whereHas('matchround', fn (Builder $q) => $q->where('matchround_game_id', $gameId))
            ->where(function (Builder $q) use ($ptIds) {
                foreach (Userteam::playerSlotColumns() as $col) {
                    $q->orWhereIn($col, $ptIds);
                }
            })
            ->count();
    }

    /**
     * @param  list<int>  $ptIds
     * @param  list<int>  $roundIds
     * @return array<int, Playerstats>
     */
    private function statsByRound(array $ptIds, array $roundIds): array
    {
        if ($roundIds === []) {
            return [];
        }

        $stats = Playerstats::query()
            ->with(['playerteam'])
            ->whereIn('playerstats_playerteam_id', $ptIds)
            ->whereIn('playerstats_matchround_id', $roundIds)
            ->get();

        $byRound = [];
        foreach ($stats as $stat) {
            $rid = (int) $stat->playerstats_matchround_id;
            if (! isset($byRound[$rid])) {
                $byRound[$rid] = $stat;
            }
        }

        return $byRound;
    }

    /**
     * @param  list<int>  $ptIds
     * @param  list<int>  $roundIds
     * @return array<int, int>
     */
    private function lineupsByRound(array $ptIds, array $roundIds): array
    {
        if ($roundIds === []) {
            return [];
        }

        $rows = Userteam::query()
            ->selectRaw('userteam_matchround_id, COUNT(*) as cnt')
            ->whereIn('userteam_matchround_id', $roundIds)
            ->where(function (Builder $q) use ($ptIds) {
                foreach (Userteam::playerSlotColumns() as $col) {
                    $q->orWhereIn($col, $ptIds);
                }
            })
            ->groupBy('userteam_matchround_id')
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $out[(int) $row->userteam_matchround_id] = (int) $row->cnt;
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    private function matchFieldsForTeamRound(int $teamId, int $matchroundId): array
    {
        $match = MatchGame::query()
            ->with(['homeTeam', 'guestTeam'])
            ->where('match_round', $matchroundId)
            ->where(function (Builder $q) use ($teamId) {
                $q->where('match_hometeam_id', $teamId)
                    ->orWhere('match_guestteam_id', $teamId);
            })
            ->first();

        if (! $match) {
            return $this->emptyMatchFields();
        }

        $homeId = (int) $match->match_hometeam_id;
        $guestId = (int) $match->match_guestteam_id;
        $homeName = (string) ($match->homeTeam?->team_name ?? '');
        $guestName = (string) ($match->guestTeam?->team_name ?? '');

        return [
            'matchround_opponent_name' => $teamId === $homeId ? $guestName : $homeName,
            'matchround_hometeam_name' => $homeName,
            'matchround_guestteam_name' => $guestName,
            'matchround_hometeam_score' => $this->nullableScore($match->match_homescore),
            'matchround_guestteam_score' => $this->nullableScore($match->match_guestscore),
            'matchround_hometeam_score_penalty' => $this->nullableScore($match->match_homescore_penalty),
            'matchround_guestteam_score_penalty' => $this->nullableScore($match->match_guestscore_penalty),
            'matchround_opponent_id' => $teamId === $homeId ? $guestId : $homeId,
            'match_id' => (int) $match->match_id,
            'match_date' => $match->match_date
                ? date('d.m.Y', strtotime((string) $match->match_date))
                : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyMatchFields(): array
    {
        return [
            'matchround_opponent_name' => null,
            'matchround_hometeam_name' => null,
            'matchround_guestteam_name' => null,
            'matchround_hometeam_score' => null,
            'matchround_guestteam_score' => null,
            'matchround_hometeam_score_penalty' => null,
            'matchround_guestteam_score_penalty' => null,
            'matchround_opponent_id' => null,
            'match_id' => 0,
            'match_date' => null,
        ];
    }

    /**
     * @param  list<int>  $ptIds
     */
    private function teamForPlayerAndRound(Matchround $matchround, array $ptIds): ?Playerteam
    {
        $playerteams = Playerteam::query()->whereIn('playerteam_id', $ptIds)->get();
        $mrTime = strtotime((string) $matchround->matchround_startdate);
        $dist = 1000000000;
        $ptNear = null;

        foreach ($playerteams as $pt) {
            $ptTime = strtotime((string) $pt->playerteam_date_transfer);
            $d = $mrTime - $ptTime;
            if ($d < $dist && $d >= 0) {
                $ptNear = $pt;
                $dist = $d;
            }
        }

        if ($ptNear === null) {
            $dist = 1000000000;
            foreach ($playerteams as $pt) {
                $ptTime = strtotime((string) $pt->playerteam_date_transfer);
                $d = -1 * ($mrTime - $ptTime);
                if ($d < $dist) {
                    $ptNear = $pt;
                    $dist = $d;
                }
            }
        }

        return $ptNear;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function pastMatches(int $playerteamId, int $gameId, int $limit): array
    {
        if ($limit <= 0) {
            return [];
        }

        $playerteam = Playerteam::query()->with('team')->find($playerteamId);
        if (! $playerteam) {
            return [];
        }

        $teamId = (int) $playerteam->playerteam_team_id;
        $now = date('Y-m-d H:i:s');

        $stats = Playerstats::query()
            ->join('ffb_match', 'ffb_match.match_id', '=', 'ffb_playerstats.playerstats_match_id')
            ->join('ffb_matchround', 'ffb_matchround.matchround_id', '=', 'ffb_playerstats.playerstats_matchround_id')
            ->where('playerstats_playerteam_id', $playerteamId)
            ->where('ffb_match.match_date', '<', $now)
            ->where('ffb_match.match_homescore', '>', -1)
            ->where('ffb_matchround.matchround_game_id', '!=', $gameId)
            ->orderByDesc('ffb_match.match_date')
            ->orderByDesc('ffb_match.match_id')
            ->select('ffb_playerstats.*')
            ->with(['matchround', 'match.homeTeam', 'match.guestTeam'])
            ->limit($limit * 3)
            ->get();

        $seenRounds = [];
        $out = [];
        foreach ($stats as $item) {
            $match = $item->relationLoaded('match') ? $item->getRelation('match') : null;
            if (! $match) {
                $match = MatchGame::query()
                    ->with(['homeTeam', 'guestTeam'])
                    ->find((int) $item->playerstats_match_id);
            }
            $round = $item->matchround;
            if (! $match || ! $round) {
                continue;
            }
            $roundId = (int) $round->matchround_id;
            if (isset($seenRounds[$roundId])) {
                continue;
            }
            $seenRounds[$roundId] = true;

            $homeId = (int) $match->match_hometeam_id;
            $guestId = (int) $match->match_guestteam_id;
            $homeName = (string) ($match->homeTeam?->team_name ?? '');
            $guestName = (string) ($match->guestTeam?->team_name ?? '');

            $out[] = [
                'matchround_id' => $roundId,
                'matchround_title' => (string) $round->matchround_title,
                'matchround_running' => 0,
                'matchround_num_lineups' => $this->countLineupsForRound($playerteamId, $roundId),
                'matchround_minutes_played' => (int) $item->playerstats_minutes,
                'matchround_score' => (int) $item->playerstats_score,
                'matchround_goals' => (int) $item->playerstats_goals,
                'matchround_assists' => (int) $item->playerstats_assists,
                'matchround_cards' => (string) ($item->playerstats_cards ?: 'n'),
                'matchround_opponent_name' => $teamId === $homeId ? $guestName : $homeName,
                'matchround_hometeam_name' => $homeName,
                'matchround_guestteam_name' => $guestName,
                'matchround_hometeam_score' => $this->nullableScore($match->match_homescore),
                'matchround_guestteam_score' => $this->nullableScore($match->match_guestscore),
                'matchround_hometeam_score_penalty' => $this->nullableScore($match->match_homescore_penalty),
                'matchround_guestteam_score_penalty' => $this->nullableScore($match->match_guestscore_penalty),
                'matchround_opponent_id' => $teamId === $homeId ? $guestId : $homeId,
                'match_id' => (int) $match->match_id,
                'match_date' => $match->match_date
                    ? date('d.m.Y', strtotime((string) $match->match_date))
                    : null,
            ];

            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    private function countLineupsForRound(int $playerteamId, int $matchroundId): int
    {
        return Userteam::query()
            ->where('userteam_matchround_id', $matchroundId)
            ->where(function (Builder $q) use ($playerteamId) {
                foreach (Userteam::playerSlotColumns() as $col) {
                    $q->orWhere($col, $playerteamId);
                }
            })
            ->count();
    }

    /**
     * @return array{0: int, 1: string|null}
     */
    private function oppGoalsWhileOnPitch(
        Playerstats $stat,
        MatchGame $match,
        int $oppositeTeamId,
        int $ownTeamId
    ): array {
        $goals = Goal::query()
            ->with('playerteam')
            ->where('goal_match_id', $match->match_id)
            ->orderBy('goal_minute')
            ->get()
            ->filter(function (Goal $g) use ($oppositeTeamId, $ownTeamId) {
                $teamId = (int) ($g->playerteam?->playerteam_team_id ?? 0);
                // Goals scored by opposite team (or own goals by own team count for opposite)
                if ((bool) $g->goal_owngoal) {
                    return $teamId === $ownTeamId;
                }

                return $teamId === $oppositeTeamId;
            });

        $minuteIn = (int) $stat->playerstats_minute_in;
        $minuteOut = (int) $stat->playerstats_minute_out;
        $matchMinutes = (int) ($match->match_minutes ?? 90);
        $count = 0;
        $parts = [];

        foreach ($goals as $goal) {
            $m = (int) $goal->goal_minute;
            $onPitch = ($m >= $minuteIn && $m <= $minuteOut)
                || ($m >= $matchMinutes && $minuteOut >= $matchMinutes);
            if ($onPitch) {
                $count++;
                $parts[] = $m.'.';
            }
        }

        return [$count, $parts !== [] ? implode('; ', $parts).' ' : null];
    }

    private function nullableScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * Chart series for the Grafik tab (chronological, scoped to a game/league).
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function chart(int $playerteamId, int $gameId): array
    {
        $ctx = $this->resolvePlayerContext($playerteamId, $gameId);
        if (! $ctx['ok']) {
            return $ctx;
        }

        /** @var Playerteam $playerteam */
        $playerteam = $ctx['playerteam'];
        $ptIds = $ctx['pt_ids'];

        $rounds = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_status', 1)
            ->orderBy('matchround_startdate')
            ->get();

        $roundIds = $rounds->pluck('matchround_id')->map(fn ($id) => (int) $id)->all();
        $statsByRound = $this->statsByRound($ptIds, $roundIds);

        $series = [];
        foreach ($rounds as $round) {
            $roundId = (int) $round->matchround_id;
            $stat = $statsByRound[$roundId] ?? null;
            $series[] = [
                'matchround_id' => $roundId,
                'matchround_title' => (string) $round->matchround_title,
                'played' => (bool) $stat,
                'score' => $stat ? (int) $stat->playerstats_score : null,
                'minutes' => $stat ? (int) $stat->playerstats_minutes : null,
                'goals' => $stat ? (int) $stat->playerstats_goals : 0,
                'assists' => $stat ? (int) $stat->playerstats_assists : 0,
                'cards' => $stat ? (string) ($stat->playerstats_cards ?: 'n') : 'n',
            ];
        }

        return [
            'ok' => true,
            'data' => [
                'game_id' => $gameId,
                'player' => $this->playerSummary($playerteam),
                'rounds' => $series,
            ],
        ];
    }

    /**
     * Price / power series for Preisverlauf (dynamic pricing games).
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function prices(int $playerteamId, int $gameId): array
    {
        $ctx = $this->resolvePlayerContext($playerteamId, $gameId);
        if (! $ctx['ok']) {
            return $ctx;
        }

        /** @var Playerteam $playerteam */
        $playerteam = $ctx['playerteam'];
        $ptIds = $ctx['pt_ids'];

        $now = date('Y-m-d H:i:s');
        $past = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_startdate', '<', $now)
            ->orderBy('matchround_startdate')
            ->get();
        $running = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_startdate', '>', $now)
            ->orderBy('matchround_startdate')
            ->limit(1)
            ->get();

        /** @var Collection<int, Matchround> $allRounds */
        $allRounds = $past->concat($running)->values();
        if ($allRounds->isEmpty()) {
            return [
                'ok' => true,
                'data' => [
                    'game_id' => $gameId,
                    'player' => $this->playerSummary($playerteam),
                    'points' => [],
                ],
            ];
        }

        $ptNear = $this->teamForPlayerAndRound($allRounds->first(), $ptIds);
        $lastPrice = (float) ($ptNear?->playerteam_player_price ?? $playerteam->playerteam_player_price ?? 0);

        $roundIds = $allRounds->pluck('matchround_id')->map(fn ($id) => (int) $id)->all();
        $prices = Playerprice::query()
            ->whereIn('playerprice_matchround_id', $roundIds)
            ->whereIn('playerprice_playerteam_id', $ptIds)
            ->get()
            ->groupBy(fn (Playerprice $p) => (int) $p->playerprice_matchround_id);

        $points = [];
        foreach ($allRounds as $round) {
            $roundId = (int) $round->matchround_id;
            $row = $prices->get($roundId)?->first();
            if ($row) {
                $lastPrice = (float) $row->playerprice_price;
                $points[] = [
                    'matchround_id' => $roundId,
                    'matchround_title' => (string) $round->matchround_title,
                    'price' => (float) $row->playerprice_price,
                    'power' => (float) $row->playerprice_player_power,
                    'av_power' => (float) $row->playerprice_av_power,
                ];
            } else {
                $points[] = [
                    'matchround_id' => $roundId,
                    'matchround_title' => (string) $round->matchround_title,
                    'price' => $lastPrice,
                    'power' => 0.0,
                    'av_power' => 0.0,
                ];
            }
        }

        return [
            'ok' => true,
            'data' => [
                'game_id' => $gameId,
                'player' => $this->playerSummary($playerteam),
                'points' => $points,
            ],
        ];
    }

    /**
     * @return array{ok: true, playerteam: Playerteam, game_id: int, pt_ids: list<int>}|array{ok: false, status: int, error: string}
     */
    private function resolvePlayerContext(int $playerteamId, int $gameId): array
    {
        if ($playerteamId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'playerteam_id is required'];
        }

        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'game_id is required'];
        }

        $playerteam = Playerteam::query()->with(['player', 'team'])->find($playerteamId);
        if (! $playerteam || ! $playerteam->player || ! $playerteam->team) {
            return ['ok' => false, 'status' => 404, 'error' => 'Player not found'];
        }

        if (! Game::query()->where('game_id', $gameId)->exists()) {
            return ['ok' => false, 'status' => 404, 'error' => 'Game not found'];
        }

        $ptIds = Playerteam::query()
            ->where('playerteam_player_id', $playerteam->playerteam_player_id)
            ->orderByDesc('playerteam_id')
            ->pluck('playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($ptIds === []) {
            $ptIds = [$playerteamId];
        }

        return [
            'ok' => true,
            'playerteam' => $playerteam,
            'game_id' => $gameId,
            'pt_ids' => $ptIds,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function playerSummary(Playerteam $playerteam): array
    {
        $teamId = (int) $playerteam->playerteam_team_id;
        $hasPicture = (bool) $playerteam->playerteam_player_picture;

        return [
            'playerteam_id' => (int) $playerteam->playerteam_id,
            'player_fname' => (string) $playerteam->player->player_fname,
            'player_lname' => (string) $playerteam->player->player_lname,
            'player_name' => trim($playerteam->player->player_fname.' '.$playerteam->player->player_lname),
            'player_nationality' => (string) ($playerteam->player->player_nationality ?: ''),
            'player_team_name' => (string) $playerteam->team->team_name,
            'player_team_nationality' => (string) ($playerteam->team->team_nationality ?: ''),
            'player_picture_url' => $hasPicture
                ? '/images/ffb/players/'.$teamId.'/'.$playerteam->playerteam_id.'.jpg'
                : '/images/ffb/players/image_na.gif',
        ];
    }
}
