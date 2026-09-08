<?php

namespace App\Services;

use App\Models\GameOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\UserDetails;
use App\Models\Userscore;
use App\Models\Userteam;
use App\Models\WebUser;
use Illuminate\Support\Facades\DB;

class UserscoreService
{
    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function pagePayload(int $userId): array
    {
        $gameId = $this->resolveGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return ['ok' => false, 'status' => 401, 'error' => 'Unknown user'];
        }

        $details = $user->details;
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'update_profile_nag' => empty($details?->user_details_photo) || $details?->user_details_photo === 'profile_na.png',
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $user->user_id),
                ],
                'selected_game_id' => $gameId,
                'navigation' => app(DashboardService::class)->navigation(),
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function matchrounds(int $userId): array
    {
        $gameId = $this->resolveGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $now = now();

        $past = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_startdate', '<', $now)
            ->orderByDesc('matchround_startdate')
            ->get();

        $running = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_startdate', '>', $now)
            ->where('matchround_status', 1)
            ->orderBy('matchround_startdate')
            ->limit(1)
            ->get();

        $pastRows = $past->map(fn (Matchround $r) => $this->formatMatchround($r))->values()->all();
        $runningRows = $running->map(fn (Matchround $r) => $this->formatMatchround($r))->values()->all();

        if (count($pastRows) > 0) {
            $pastRows[0]['matchround_actual'] = 1;
            if (strtotime((string) $past->first()->matchround_startdate) > $now->getTimestamp()) {
                $pastRows[0]['matchround_running'] = 1;
            }
        } elseif (count($runningRows) > 0) {
            $runningRows[0]['matchround_actual'] = 1;
        }

        $pastRunning = (int) (($pastRows[0]['matchround_running'] ?? 0));

        $matchrounds = [];
        if (count($runningRows) > 0 && $pastRunning !== 1) {
            $runningRows[0]['matchround_running'] = 1;
            $matchrounds = array_merge($runningRows, $pastRows);
        } elseif (count($runningRows) > 0 && $pastRunning === 1) {
            $runningRows[0]['matchround_future'] = 1;
            $matchrounds = array_merge($runningRows, $pastRows);
        } else {
            $matchrounds = $pastRows;
        }

        // Attach matches for each round
        $ids = array_column($matchrounds, 'matchround_id');
        $matchesByRound = $this->matchesForRounds($ids);

        foreach ($matchrounds as &$row) {
            $row['matches'] = $matchesByRound[(int) $row['matchround_id']] ?? [];
        }
        unset($row);

        return [
            'ok' => true,
            'data' => [
                'selected_game_id' => $gameId,
                'matchrounds' => $matchrounds,
            ],
        ];
    }

    /**
     * Past (ended) matchrounds only — used by bestteam / Top&Flop.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function pastMatchrounds(int $userId): array
    {
        $gameId = $this->resolveGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $past = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_enddate', '<', now())
            ->orderByDesc('matchround_startdate')
            ->get();

        $matchrounds = $past->map(fn (Matchround $r) => $this->formatMatchround($r))->values()->all();
        if ($matchrounds !== []) {
            $matchrounds[0]['matchround_actual'] = 1;
        }

        $ids = array_column($matchrounds, 'matchround_id');
        $matchesByRound = $this->matchesForRounds($ids);

        foreach ($matchrounds as &$row) {
            $row['matches'] = $matchesByRound[(int) $row['matchround_id']] ?? [];
        }
        unset($row);

        return [
            'ok' => true,
            'data' => [
                'selected_game_id' => $gameId,
                'matchrounds' => $matchrounds,
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function overall(int $userId, string $sortFlag = '', string $sortDir = 'desc'): array
    {
        $gameId = $this->resolveGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $rankMode = $this->rankMode($gameId);
        $wins = $this->matchroundWinsByNickname($gameId);
        $participations = $this->participationsByUser($gameId);

        $scores = Userscore::query()
            ->with(['user.details'])
            ->where('userscore_game_id', $gameId)
            ->get();

        $favFlags = $this->favouriteFlagsForUsers(
            $scores->map(fn (Userscore $s) => (int) $s->userscore_user_id)->all()
        );

        $entries = [];
        foreach ($scores as $score) {
            $user = $score->user;
            if (! $user) {
                continue;
            }
            $uid = (int) $user->user_id;
            $nick = (string) $user->user_nickname;
            $parts = (int) ($participations[$uid] ?? 0);
            $total = (int) $score->userscore_total;
            $entries[] = [
                'user_id' => $uid,
                'user_nickname' => $nick,
                'user_favourite_team_nationality' => $favFlags[$uid] ?? '0',
                'user_score' => $total,
                'user_wc_points' => (int) $score->userscore_wc_points,
                'participations' => $parts,
                'matchround_wins' => (int) ($wins[$nick] ?? 0),
            ];
        }

        $entries = $this->rankOverall($entries, $rankMode);
        $entries = $this->applySort($entries, $sortFlag, $sortDir, $rankMode, overall: true);

        return [
            'ok' => true,
            'data' => [
                'selected_game_id' => $gameId,
                'matchround_id' => 0,
                'rank_mode' => $rankMode,
                'display_mode' => $rankMode === 'wc' ? 'wc' : 'points',
                'entries' => array_values($entries),
                'num_results' => count($entries),
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function forRound(int $userId, int $matchroundId, string $sortFlag = '', string $sortDir = 'desc'): array
    {
        $gameId = $this->resolveGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        $round = Matchround::query()->find($matchroundId);
        if (! $round || (int) $round->matchround_game_id !== $gameId) {
            return ['ok' => false, 'status' => 404, 'error' => 'Matchround not found'];
        }

        $rankMode = $this->rankMode($gameId);
        $wins = $this->matchroundWinsByNickname($gameId);
        $participations = $this->participationsByUser($gameId);

        $userteams = Userteam::query()
            ->with(['user.details'])
            ->where('userteam_matchround_id', $matchroundId)
            ->get();

        $favFlags = $this->favouriteFlagsForUsers(
            $userteams->map(fn (Userteam $t) => (int) $t->userteam_user_id)->all()
        );

        $entries = [];
        foreach ($userteams as $team) {
            $user = $team->user;
            if (! $user) {
                continue;
            }
            $uid = (int) $user->user_id;
            $nick = (string) $user->user_nickname;
            $parts = (int) ($participations[$uid] ?? 0);
            $score = (int) $team->userteam_score;
            $entries[] = [
                'user_id' => $uid,
                'user_nickname' => $nick,
                'user_favourite_team_nationality' => $favFlags[$uid] ?? '0',
                'user_score' => $score,
                'user_wc_points' => (int) $team->userteam_wc_points,
                'participations' => $parts,
                'matchround_wins' => (int) ($wins[$nick] ?? 0),
            ];
        }

        $entries = $this->rankRound($entries);
        $entries = $this->applySort($entries, $sortFlag, $sortDir, $rankMode, overall: false);

        return [
            'ok' => true,
            'data' => [
                'selected_game_id' => $gameId,
                'matchround_id' => $matchroundId,
                'rank_mode' => $rankMode,
                'display_mode' => 'points',
                'entries' => array_values($entries),
                'num_results' => count($entries),
            ],
        ];
    }

    private function resolveGameId(int $userId): int
    {
        $details = UserDetails::query()->find($userId);

        return (int) ($details?->user_details_ffb_selected_game ?? 0);
    }

    private function rankMode(int $gameId): string
    {
        $mode = (string) (GameOptions::query()
            ->where('options_game_id', $gameId)
            ->value('options_game_rankmode') ?? 'wc');

        return in_array($mode, ['points', 'wc'], true) ? $mode : 'wc';
    }

    /**
     * @return array<string, int> nickname => wins
     */
    private function matchroundWinsByNickname(int $gameId): array
    {
        $rounds = Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->where('matchround_enddate', '<', now())
            ->pluck('matchround_id');

        if ($rounds->isEmpty()) {
            return [];
        }

        $wins = [];
        foreach ($rounds as $roundId) {
            $teams = Userteam::query()
                ->with('user')
                ->where('userteam_matchround_id', $roundId)
                ->where('userteam_score', '>', 0)
                ->orderByDesc('userteam_score')
                ->get();

            if ($teams->isEmpty()) {
                continue;
            }

            $topScore = (int) $teams->first()->userteam_score;
            foreach ($teams as $team) {
                if ((int) $team->userteam_score !== $topScore) {
                    break;
                }
                $nick = (string) ($team->user?->user_nickname ?? '');
                if ($nick === '') {
                    continue;
                }
                $wins[$nick] = ($wins[$nick] ?? 0) + 1;
            }
        }

        return $wins;
    }

    /**
     * @return array<int, int> user_id => participation count
     */
    private function participationsByUser(int $gameId): array
    {
        $rows = DB::table('ffb_userteam')
            ->join('ffb_matchround', 'ffb_matchround.matchround_id', '=', 'ffb_userteam.userteam_matchround_id')
            ->where('ffb_matchround.matchround_game_id', $gameId)
            ->where('ffb_matchround.matchround_startdate', '<', now())
            ->groupBy('ffb_userteam.userteam_user_id')
            ->selectRaw('ffb_userteam.userteam_user_id as user_id, COUNT(*) as c')
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $out[(int) $row->user_id] = (int) $row->c;
        }

        return $out;
    }

    /**
     * @param  list<int>  $userIds
     * @return array<int, string>
     */
    private function favouriteFlagsForUsers(array $userIds): array
    {
        $userIds = array_values(array_unique(array_filter($userIds)));
        if ($userIds === []) {
            return [];
        }

        $rows = DB::table('web_user_details')
            ->leftJoin('ffb_team', 'ffb_team.team_id', '=', 'web_user_details.user_details_ffb_favourite_team')
            ->whereIn('web_user_details.user_id', $userIds)
            ->select([
                'web_user_details.user_id',
                'ffb_team.team_nationality',
            ])
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $flag = $row->team_nationality;
            $out[(int) $row->user_id] = $flag ? (string) $flag : '0';
        }

        return $out;
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @return list<array<string, mixed>>
     */
    private function rankOverall(array $entries, string $rankMode): array
    {
        usort($entries, function (array $a, array $b) use ($rankMode): int {
            if ($rankMode === 'points') {
                return [$b['user_score'], $b['user_wc_points'], strtolower($a['user_nickname'])]
                    <=> [$a['user_score'], $a['user_wc_points'], strtolower($b['user_nickname'])];
            }

            return [$b['user_wc_points'], $b['user_score'], strtolower($a['user_nickname'])]
                <=> [$a['user_wc_points'], $a['user_score'], strtolower($b['user_nickname'])];
        });

        $rank = 1;
        $j = 1;
        $lastWc = -1;
        $lastPoints = -1;
        foreach ($entries as $i => $item) {
            if ($item['user_wc_points'] < $lastWc || $item['user_score'] < $lastPoints) {
                $rank = $j;
            }
            $j++;
            $lastWc = $item['user_wc_points'];
            $lastPoints = $item['user_score'];
            $entries[$i]['user_rank'] = $rank;
        }

        return $entries;
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @return list<array<string, mixed>>
     */
    private function rankRound(array $entries): array
    {
        usort($entries, function (array $a, array $b): int {
            return [$b['user_score'], strtolower($a['user_nickname'])]
                <=> [$a['user_score'], strtolower($b['user_nickname'])];
        });

        $rank = 1;
        $j = 1;
        $lastPoints = -1;
        foreach ($entries as $i => $item) {
            if ($item['user_score'] < $lastPoints) {
                $rank = $j;
            }
            $j++;
            $lastPoints = $item['user_score'];
            $entries[$i]['user_rank'] = $rank;
        }

        return $entries;
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @return list<array<string, mixed>>
     */
    private function applySort(array $entries, string $sortFlag, string $sortDir, string $rankMode, bool $overall): array
    {
        $asc = strtolower($sortDir) === 'asc';

        $cmp = function ($a, $b) use ($asc): int {
            return $asc ? ($a <=> $b) : ($b <=> $a);
        };

        usort($entries, function (array $a, array $b) use ($sortFlag, $cmp, $rankMode, $overall): int {
            return match ($sortFlag) {
                'n' => $cmp(strtolower($a['user_nickname']), strtolower($b['user_nickname'])),
                'p' => $cmp($a['participations'], $b['participations'])
                    ?: ($overall
                        ? ($cmp($a['user_wc_points'], $b['user_wc_points']) ?: $cmp($a['user_score'], $b['user_score']))
                        : $cmp($a['user_score'], $b['user_score']))
                    ?: (strtolower($a['user_nickname']) <=> strtolower($b['user_nickname'])),
                'w' => $cmp($a['matchround_wins'], $b['matchround_wins'])
                    ?: ($overall
                        ? ($cmp($a['user_wc_points'], $b['user_wc_points']) ?: $cmp($a['user_score'], $b['user_score']))
                        : $cmp($a['user_score'], $b['user_score']))
                    ?: (strtolower($a['user_nickname']) <=> strtolower($b['user_nickname'])),
                'r' => $cmp($a['user_rank'], $b['user_rank'])
                    ?: (strtolower($a['user_nickname']) <=> strtolower($b['user_nickname'])),
                default => $overall
                    ? ($rankMode === 'points'
                        ? ($cmp($a['user_score'], $b['user_score'])
                            ?: $cmp($a['user_wc_points'], $b['user_wc_points'])
                            ?: (strtolower($a['user_nickname']) <=> strtolower($b['user_nickname'])))
                        : ($cmp($a['user_wc_points'], $b['user_wc_points'])
                            ?: $cmp($a['user_score'], $b['user_score'])
                            ?: (strtolower($a['user_nickname']) <=> strtolower($b['user_nickname']))))
                    : ($cmp($a['user_score'], $b['user_score'])
                        ?: (strtolower($a['user_nickname']) <=> strtolower($b['user_nickname']))),
            };
        });

        return $entries;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatMatchround(Matchround $round): array
    {
        return [
            'matchround_id' => (int) $round->matchround_id,
            'matchround_title' => (string) $round->matchround_title,
            'matchround_actual' => 0,
            'matchround_running' => 0,
            'matchround_future' => 0,
            'matchround_status' => (int) $round->matchround_status,
            'matchround_startdate' => date('j.n.Y', strtotime((string) $round->matchround_startdate)),
            'matchround_enddate' => date('j.n.Y', strtotime((string) $round->matchround_enddate)),
            'matchround_startdate_raw' => (string) $round->matchround_startdate,
            'matchround_enddate_raw' => (string) $round->matchround_enddate,
        ];
    }

    /**
     * @param  list<int>  $roundIds
     * @return array<int, list<array<string, mixed>>>
     */
    private function matchesForRounds(array $roundIds): array
    {
        $roundIds = array_values(array_filter($roundIds));
        if ($roundIds === []) {
            return [];
        }

        $matches = MatchGame::query()
            ->with(['homeTeam', 'guestTeam'])
            ->whereIn('match_round', $roundIds)
            ->orderBy('match_date')
            ->get();

        $out = [];
        foreach ($matches as $match) {
            $rid = (int) $match->match_round;
            $out[$rid][] = [
                'match_id' => (int) $match->match_id,
                'match_date' => date('j.n.Y', strtotime((string) $match->match_date)),
                'match_hometeam_name' => (string) ($match->homeTeam?->team_name ?? ''),
                'match_guestteam_name' => (string) ($match->guestTeam?->team_name ?? ''),
                'match_hometeam_nationality' => (string) ($match->homeTeam?->team_nationality ?? ''),
                'match_guestteam_nationality' => (string) ($match->guestTeam?->team_nationality ?? ''),
                'match_homescore' => $match->match_homescore,
                'match_guestscore' => $match->match_guestscore,
                'match_homescore_penalty' => $match->match_homescore_penalty,
                'match_guestscore_penalty' => $match->match_guestscore_penalty,
                'match_status' => (int) $match->match_status,
            ];
        }

        return $out;
    }
}
