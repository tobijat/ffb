<?php

namespace App\Services;

use App\Models\GameOptions;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\WebUser;

class BestteamService
{
    /** @var list<list<int>> g,d,m,s counts per formation */
    private const SYSTEMS = [
        [1, 3, 4, 3],
        [1, 3, 5, 2],
        [1, 4, 3, 3],
        [1, 4, 4, 2],
        [1, 4, 5, 1],
        [1, 5, 3, 2],
        [1, 5, 4, 1],
    ];

    public function __construct(
        private readonly UserscoreService $userscores,
        private readonly MyteamService $myteam,
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
        return $this->userscores->pastMatchrounds($userId);
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function roundStats(int $matchroundId): array
    {
        return $this->myteam->roundStats($matchroundId);
    }

    /**
     * Build the optimal top or flop XI across legal formations.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function bestTeam(int $matchroundId, string $type): array
    {
        if ($matchroundId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'matchround_id is required'];
        }

        if ($type !== 'top' && $type !== 'flop') {
            $type = 'top';
        }

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return ['ok' => false, 'status' => 404, 'error' => 'Matchround not found'];
        }

        $gameId = (int) $matchround->matchround_game_id;
        $options = GameOptions::query()->where('options_game_id', $gameId)->first();
        $pointsMode = (string) ($options?->options_game_pointsmode ?: 'new');
        $pricesByPt = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->get()
            ->keyBy(fn (Playerprice $p) => (int) $p->playerprice_playerteam_id);
        $useDynamic = $pointsMode !== 'old' && $pricesByPt->isNotEmpty();

        $stats = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->with(['playerteam.player', 'playerteam.team'])
            ->get();

        /** @var array<string, list<array<string, mixed>>> $byPosition */
        $byPosition = ['g' => [], 'd' => [], 'm' => [], 's' => []];

        foreach ($stats as $stat) {
            $pt = $stat->playerteam;
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }
            $pos = (string) $pt->playerteam_player_position;
            if (! isset($byPosition[$pos])) {
                continue;
            }

            $ptId = (int) $stat->playerstats_playerteam_id;
            if ($useDynamic && $pricesByPt->has($ptId)) {
                $price = (float) $pricesByPt->get($ptId)->playerprice_price;
            } else {
                $price = (float) $pt->playerteam_player_price;
            }

            $player = $pt->player;
            $team = $pt->team;
            $byPosition[$pos][] = [
                'player_fname' => (string) $player->player_fname,
                'player_lname' => (string) $player->player_lname,
                'player_nationality' => (string) ($player->player_nationality ?: ''),
                'player_status' => (int) ($player->player_status ?: 0),
                'player_status_description' => (string) ($player->player_status_description ?: '0'),
                'playerteam_id' => $ptId,
                'playerteam_team_id' => (int) $pt->playerteam_team_id,
                'playerteam_team' => (string) $team->team_name,
                'playerteam_team_nationality' => (string) $team->team_nationality,
                'playerteam_player_position' => $pos,
                'playerteam_player_price' => $price,
                'playerteam_status' => (int) ($pt->playerteam_status ?: 0),
                'playerstats_score' => (int) $stat->playerstats_score,
            ];
        }

        foreach ($byPosition as $pos => $rows) {
            usort($rows, function (array $a, array $b) use ($type): int {
                if ($type === 'top') {
                    return [$b['playerstats_score'], $a['playerteam_player_price']]
                        <=> [$a['playerstats_score'], $b['playerteam_player_price']];
                }

                return [$a['playerstats_score'], $b['playerteam_player_price']]
                    <=> [$b['playerstats_score'], $a['playerteam_player_price']];
            });
            $byPosition[$pos] = $rows;
        }

        $bestPlayers = [];
        $teamScore = $type === 'top' ? -100000 : 100000;
        $teamPrice = 0.0;

        foreach (self::SYSTEMS as $system) {
            $picked = [];
            $sumScore = 0;
            $sumPrice = 0.0;
            $limits = [
                'g' => $system[0],
                'd' => $system[1],
                'm' => $system[2],
                's' => $system[3],
            ];

            foreach ($limits as $pos => $limit) {
                $slice = array_slice($byPosition[$pos], 0, $limit);
                foreach ($slice as $row) {
                    $picked[] = $row;
                    $sumScore += (int) $row['playerstats_score'];
                    $sumPrice += (float) $row['playerteam_player_price'];
                }
            }

            $better = $type === 'top'
                ? $sumScore >= $teamScore
                : $sumScore < $teamScore;

            if ($better) {
                $bestPlayers = $picked;
                $teamScore = $sumScore;
                $teamPrice = $sumPrice;
            }
        }

        return [
            'ok' => true,
            'data' => [
                'matchround_id' => $matchroundId,
                'type' => $type,
                'userteam' => [
                    'userteam_score' => $teamScore === -100000 || $teamScore === 100000 ? 0 : $teamScore,
                    'userteam_price' => round($teamPrice, 1),
                ],
                'players' => $bestPlayers,
            ],
        ];
    }
}
