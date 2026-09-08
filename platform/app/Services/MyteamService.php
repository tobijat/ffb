<?php

namespace App\Services;

use App\Models\GameOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Userteam;
use App\Models\WebUser;

class MyteamService
{
    public function __construct(
        private readonly UserscoreService $userscores,
        private readonly LineupService $lineups,
    ) {
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function pagePayload(int $userId): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return ['ok' => false, 'status' => 401, 'error' => 'Unknown user'];
        }

        $details = $user->details;
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
        $gameId = (int) ($details?->user_details_ffb_selected_game ?? 0);

        if ($gameId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'is_admin' => (bool) ($user->user_admin ?? false),
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
        return $this->userscores->matchrounds($userId);
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function usersWithTeams(int $matchroundId): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        $userIds = Userteam::query()
            ->where('userteam_matchround_id', $matchroundId)
            ->pluck('userteam_user_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($userIds === []) {
            return [
                'ok' => true,
                'data' => [
                    'matchround_id' => $matchroundId,
                    'users' => [],
                ],
            ];
        }

        $users = WebUser::query()
            ->whereIn('user_id', $userIds)
            ->orderBy('user_nickname')
            ->get(['user_id', 'user_nickname'])
            ->map(fn (WebUser $u) => [
                'user_id' => (int) $u->user_id,
                'user_nickname' => (string) $u->user_nickname,
            ])
            ->all();

        return [
            'ok' => true,
            'data' => [
                'matchround_id' => $matchroundId,
                'users' => $users,
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function teamForRound(int $viewerId, int $targetUserId, int $matchroundId, bool $viewerIsAdmin): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        if ($targetUserId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'user_id is required'];
        }

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'status' => 404, 'error' => 'Matchround not found'];
        }

        $deadlineOpen = strtotime((string) $matchround->matchround_startdate) > time();
        if ($deadlineOpen && $targetUserId !== $viewerId && ! $viewerIsAdmin) {
            return [
                'ok' => false,
                'status' => 403,
                'error' => 'Du kannst fremde Mannschaften erst ansehen wenn die Deadline vorüber ist!',
            ];
        }

        $payload = $this->lineups->getForRound($targetUserId, $matchroundId);
        $payload['matchround_running'] = $deadlineOpen ? 1 : 0;
        $payload['matchround_title'] = (string) $matchround->matchround_title;
        $payload['matchround_startdate'] = date('j.n.Y', strtotime((string) $matchround->matchround_startdate));
        $payload['matchround_enddate'] = date('j.n.Y', strtotime((string) $matchround->matchround_enddate));

        return [
            'ok' => true,
            'data' => $payload,
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function userStats(int $viewerId, int $targetUserId, int $matchroundId, bool $viewerIsAdmin): array
    {
        if ($matchroundId <= 0 || $targetUserId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id and userteam_user_id are required'];
        }

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'status' => 404, 'error' => 'Matchround not found'];
        }

        $deadlineOpen = strtotime((string) $matchround->matchround_startdate) > time();
        if ($deadlineOpen && $targetUserId !== $viewerId && ! $viewerIsAdmin) {
            return [
                'ok' => false,
                'status' => 403,
                'error' => 'Du kannst fremde Mannschaften erst ansehen wenn die Deadline vorüber ist!',
            ];
        }

        $userteam = Userteam::query()
            ->where('userteam_matchround_id', $matchroundId)
            ->where('userteam_user_id', $targetUserId)
            ->first();

        if (! $userteam) {
            return [
                'ok' => true,
                'data' => [
                    'matchround_id' => $matchroundId,
                    'user_id' => $targetUserId,
                    'stats' => null,
                ],
            ];
        }

        $slotIds = $userteam->playerteamIdsInSlotOrder();
        $stats = $this->aggregateUserteamStats($slotIds, $matchroundId);
        $price = (float) $userteam->userteam_price;
        $score = (int) $userteam->userteam_score;
        $stats['price'] = $price;
        $stats['score'] = $score;
        $stats['score_per_player'] = round($score / 11, 2);
        $stats['credits_per_point'] = $score > 0 ? round($price / $score, 1) : 0.0;

        return [
            'ok' => true,
            'data' => [
                'matchround_id' => $matchroundId,
                'user_id' => $targetUserId,
                'stats' => $stats,
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function roundStats(int $matchroundId): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'status' => 404, 'error' => 'Matchround not found'];
        }

        $userteams = Userteam::query()
            ->where('userteam_matchround_id', $matchroundId)
            ->get(['userteam_price']);

        $playerstats = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->get([
                'playerstats_goals',
                'playerstats_owngoals',
                'playerstats_cards',
                'playerstats_minutes',
                'playerstats_score',
            ]);

        $goals = 0;
        $owngoals = 0;
        $cardsR = 0;
        $cardsYr = 0;
        $cardsY = 0;
        $minutes = 0;
        $score = 0;
        foreach ($playerstats as $row) {
            $goals += (int) $row->playerstats_goals;
            $owngoals += (int) $row->playerstats_owngoals;
            $card = (string) ($row->playerstats_cards ?: '');
            if ($card === 'r') {
                $cardsR++;
            } elseif ($card === 'yr') {
                $cardsYr++;
            } elseif ($card === 'y') {
                $cardsY++;
            }
            $minutes += (int) $row->playerstats_minutes;
            $score += (int) $row->playerstats_score;
        }

        $credits = (float) $userteams->sum(fn (Userteam $t) => (float) $t->userteam_price);
        $numPlayers = $playerstats->count();
        $numUsers = $userteams->count();
        $numMatches = MatchGame::query()->where('match_round', $matchroundId)->count();

        return [
            'ok' => true,
            'data' => [
                'matchround_id' => $matchroundId,
                'stats' => [
                    'goals' => $goals,
                    'owngoals' => $owngoals,
                    'cards_r' => $cardsR,
                    'cards_yr' => $cardsYr,
                    'cards_y' => $cardsY,
                    'minutes' => $minutes,
                    'score' => $score,
                    'credits' => $credits,
                    'num_users' => $numUsers,
                    'num_players' => $numPlayers,
                    'num_matches' => $numMatches,
                    'score_per_player' => $numPlayers > 0 ? round($score / $numPlayers, 2) : 0.0,
                    'credits_per_point' => $score > 0 ? round($credits / $score, 1) : 0.0,
                    'top_of_round' => $this->extremeOfRound($matchroundId, (int) $matchround->matchround_game_id, top: true),
                    'flop_of_round' => $this->extremeOfRound($matchroundId, (int) $matchround->matchround_game_id, top: false),
                ],
            ],
        ];
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return array<string, mixed>
     */
    private function aggregateUserteamStats(array $playerteamIds, int $matchroundId): array
    {
        $goals = 0;
        $owngoals = 0;
        $cardsR = 0;
        $cardsYr = 0;
        $cardsY = 0;
        $minutes = 0;
        $numG = 0;
        $numD = 0;
        $numM = 0;
        $numS = 0;
        $scoreG = 0;
        $scoreD = 0;
        $scoreM = 0;
        $scoreS = 0;

        $playerteams = Playerteam::query()
            ->whereIn('playerteam_id', $playerteamIds)
            ->get()
            ->keyBy('playerteam_id');

        $statsByPt = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->whereIn('playerstats_playerteam_id', $playerteamIds)
            ->get()
            ->keyBy(fn (Playerstats $s) => (int) $s->playerstats_playerteam_id);

        foreach ($playerteamIds as $ptId) {
            /** @var Playerteam|null $pt */
            $pt = $playerteams->get($ptId);
            if (! $pt) {
                continue;
            }
            $pos = (string) $pt->playerteam_player_position;
            if ($pos === 'g') {
                $numG++;
            } elseif ($pos === 'd') {
                $numD++;
            } elseif ($pos === 'm') {
                $numM++;
            } elseif ($pos === 's') {
                $numS++;
            }

            /** @var Playerstats|null $stat */
            $stat = $statsByPt->get($ptId);
            if (! $stat) {
                continue;
            }

            $goals += (int) $stat->playerstats_goals;
            $owngoals += (int) $stat->playerstats_owngoals;
            $card = (string) ($stat->playerstats_cards ?: '');
            if ($card === 'r') {
                $cardsR++;
            } elseif ($card === 'yr') {
                $cardsYr++;
            } elseif ($card === 'y') {
                $cardsY++;
            }
            $minutes += (int) $stat->playerstats_minutes;
            $score = (int) $stat->playerstats_score;
            if ($pos === 'g') {
                $scoreG += $score;
            } elseif ($pos === 'd') {
                $scoreD += $score;
            } elseif ($pos === 'm') {
                $scoreM += $score;
            } elseif ($pos === 's') {
                $scoreS += $score;
            }
        }

        return [
            'goals' => $goals,
            'owngoals' => $owngoals,
            'cards_r' => $cardsR,
            'cards_yr' => $cardsYr,
            'cards_y' => $cardsY,
            'minutes' => $minutes,
            'system' => $numD.'-'.$numM.'-'.$numS,
            'score_g' => $scoreG,
            'score_d' => $scoreD,
            'score_m' => $scoreM,
            'score_s' => $scoreS,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function extremeOfRound(int $matchroundId, int $gameId, bool $top): ?array
    {
        $options = GameOptions::query()->where('options_game_id', $gameId)->first();
        $pointsMode = (string) ($options?->options_game_pointsmode ?: 'new');
        $hasPrices = Playerprice::query()->where('playerprice_matchround_id', $matchroundId)->exists();
        $useDynamic = $pointsMode !== 'old' && $hasPrices;

        if ($useDynamic) {
            $query = Playerstats::query()
                ->select('ffb_playerstats.*')
                ->join(
                    'ffb_playerprice',
                    'ffb_playerstats.playerstats_playerteam_id',
                    '=',
                    'ffb_playerprice.playerprice_playerteam_id'
                )
                ->where('ffb_playerstats.playerstats_matchround_id', $matchroundId)
                ->where('ffb_playerprice.playerprice_matchround_id', $matchroundId)
                ->with(['playerteam.player', 'playerteam.team']);

            if ($top) {
                $query->orderByDesc('ffb_playerstats.playerstats_score')
                    ->orderBy('ffb_playerprice.playerprice_price');
            } else {
                $query->orderBy('ffb_playerstats.playerstats_score')
                    ->orderByDesc('ffb_playerprice.playerprice_price');
            }

            $stat = $query->first();
        } else {
            $query = Playerstats::query()
                ->select('ffb_playerstats.*')
                ->join(
                    'ffb_playerteam',
                    'ffb_playerstats.playerstats_playerteam_id',
                    '=',
                    'ffb_playerteam.playerteam_id'
                )
                ->where('ffb_playerstats.playerstats_matchround_id', $matchroundId)
                ->with(['playerteam.player', 'playerteam.team']);

            if ($top) {
                $query->orderByDesc('ffb_playerstats.playerstats_score')
                    ->orderBy('ffb_playerteam.playerteam_player_price');
            } else {
                $query->orderBy('ffb_playerstats.playerstats_score')
                    ->orderByDesc('ffb_playerteam.playerteam_player_price');
            }

            $stat = $query->first();
        }

        if (! $stat || ! $stat->playerteam || ! $stat->playerteam->player || ! $stat->playerteam->team) {
            return null;
        }

        $player = $stat->playerteam->player;
        $team = $stat->playerteam->team;
        $prefix = $top ? 'top' : 'flop';

        return [
            $prefix.'_player_name' => trim($player->player_fname.' '.$player->player_lname),
            $prefix.'_playerteam_id' => (int) $stat->playerstats_playerteam_id,
            $prefix.'_team_id' => (int) $stat->playerteam->playerteam_team_id,
            $prefix.'_team_name' => (string) $team->team_name,
            $prefix.'_team_nationality' => (string) $team->team_nationality,
            $prefix.'_score' => (int) $stat->playerstats_score,
        ];
    }
}
