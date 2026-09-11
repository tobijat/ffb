<?php

namespace App\Services;

use App\Models\LeagueOptions;
use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Userscore;
use App\Models\Userteam;
use Illuminate\Support\Facades\DB;

class AdminScoreService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league_id' => $shell['selected_league_id'],
            'selected_league' => $shell['selected_league'],
        ];
    }

    /**
     * Recalculate every userteam score for the selected league from playerstats,
     * then recompute finished-round LC points on those userteams.
     *
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function setUserteamScores(int $userId): array
    {
        $leagueId = $this->adminCenter->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $matchroundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($matchroundIds === []) {
            return [
                'ok' => true,
                'message' => 'Userteam-Scores aktualisiert (keine Spielrunden in dieser Liga).',
                'details' => [],
            ];
        }

        $userteams = Userteam::query()
            ->whereIn('userteam_matchround_id', $matchroundIds)
            ->orderBy('userteam_id')
            ->get();

        $details = [];

        DB::transaction(function () use ($userteams, &$details) {
            foreach ($userteams as $userteam) {
                $playerteamIds = $userteam->playerteamIdsInSlotOrder();
                $score = 0;
                if ($playerteamIds !== []) {
                    $score = (int) Playerstats::query()
                        ->where('playerstats_matchround_id', (int) $userteam->userteam_matchround_id)
                        ->whereIn('playerstats_playerteam_id', $playerteamIds)
                        ->sum('playerstats_score');
                }

                $userteam->userteam_score = $score;
                $userteam->save();

                $details[] = 'userteam_id: '.(int) $userteam->userteam_id.' score: '.$score;
            }
        });

        $this->setLcPointsForGame($leagueId);

        return [
            'ok' => true,
            'message' => 'Userteam-Scores erfolgreich aktualisiert (inkl. LC-Punkte für beendete Runden).',
            'details' => $details,
        ];
    }

    /**
     * Aggregate userteam scores / LC points into ffb_userscore for the selected league.
     *
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function setUserScores(int $userId): array
    {
        $leagueId = $this->adminCenter->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $matchroundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($matchroundIds === []) {
            return [
                'ok' => true,
                'message' => 'User-Scores aktualisiert (keine Spielrunden in dieser Liga).',
                'details' => [],
            ];
        }

        $totals = Userteam::query()
            ->whereIn('userteam_matchround_id', $matchroundIds)
            ->selectRaw('userteam_user_id, SUM(userteam_score) as total_score, SUM(userteam_lc_points) as total_lc')
            ->groupBy('userteam_user_id')
            ->get();

        $details = [];

        DB::transaction(function () use ($totals, $leagueId, &$details) {
            foreach ($totals as $row) {
                $uid = (int) $row->userteam_user_id;
                $total = (int) $row->total_score;
                $lc = (int) $row->total_lc;

                $userscore = Userscore::query()
                    ->where('userscore_user_id', $uid)
                    ->where('userscore_league_id', $leagueId)
                    ->first();

                if ($userscore) {
                    $userscore->userscore_total = $total;
                    $userscore->userscore_lc_points = $lc;
                    $userscore->save();
                    $details[] = 'user_id: '.$uid.' score: '.$total;
                    $details[] = 'user_id: '.$uid.' lc_score: '.$lc;
                } else {
                    Userscore::query()->create([
                        'userscore_user_id' => $uid,
                        'userscore_league_id' => $leagueId,
                        'userscore_total' => $total,
                        'userscore_lc_points' => $lc,
                    ]);
                    $details[] = 'user_id: '.$uid.' score: '.$total.' (new entry created!)';
                    $details[] = 'user_id: '.$uid.' lc_score: '.$lc;
                }
            }
        });

        return [
            'ok' => true,
            'message' => 'User-Scores erfolgreich aktualisiert.',
            'details' => $details,
        ];
    }

    /**
     * Assign LC points on finished matchrounds (enddate in the past), matching legacy ranking.
     */
    private function setLcPointsForGame(int $leagueId): void
    {
        $options = LeagueOptions::query()->where('options_league_id', $leagueId)->first()
            ?? LeagueOptions::query()->where('options_league_id', 0)->first();

        // Legacy: explode comma-separated options_league_lcpoints (index = rank - 1).
        $lcPoints = array_map(
            static fn (string $v): int => (int) trim($v),
            explode(',', (string) ($options?->options_league_lcpoints ?? ''))
        );
        if ($lcPoints === []) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $matchrounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_enddate', '<', $now)
            ->orderBy('matchround_id')
            ->get();

        foreach ($matchrounds as $matchround) {
            $userteams = Userteam::query()
                ->with('user')
                ->where('userteam_matchround_id', (int) $matchround->matchround_id)
                ->get();

            if ($userteams->isEmpty()) {
                continue;
            }

            $users = [];
            foreach ($userteams as $userteam) {
                $users[] = [
                    'user_id' => (int) $userteam->userteam_user_id,
                    'user_nickname' => strtolower((string) ($userteam->user?->user_nickname ?? '')),
                    'user_userteam_id' => (int) $userteam->userteam_id,
                    'user_score' => (int) $userteam->userteam_score,
                ];
            }

            usort($users, static function (array $a, array $b): int {
                if ($a['user_score'] !== $b['user_score']) {
                    return $b['user_score'] <=> $a['user_score'];
                }

                return strcmp($a['user_nickname'], $b['user_nickname']);
            });

            $rank = 0;
            $tieSpan = 1;
            $lastScore = 100000;
            foreach ($users as $index => $item) {
                $currScore = $item['user_score'];
                if ($currScore < $lastScore) {
                    $rank += $tieSpan;
                    $tieSpan = 1;
                } else {
                    $tieSpan++;
                }

                if ($rank < count($lcPoints)) {
                    $lc = $lcPoints[$rank - 1];
                } else {
                    $lc = $lcPoints[count($lcPoints) - 1];
                }

                $users[$index]['user_lc_points'] = $lc;
                $lastScore = $currScore;
            }

            foreach ($users as $item) {
                Userteam::query()
                    ->whereKey($item['user_userteam_id'])
                    ->update(['userteam_lc_points' => $item['user_lc_points']]);
            }
        }
    }
}
