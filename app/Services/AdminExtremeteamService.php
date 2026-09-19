<?php

namespace App\Services;

use App\Models\Extremeteam;
use App\Models\Matchround;

class AdminExtremeteamService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly ExtremeTeamService $extremeTeams,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagueId = (int) ($shell['selected_league_id'] ?? 0);

        $pastRounds = [];
        $existing = [];
        if ($leagueId > 0) {
            $pastRounds = Matchround::query()
                ->where('matchround_league_id', $leagueId)
                ->where('matchround_enddate', '<', now())
                ->orderByDesc('matchround_startdate')
                ->get(['matchround_id', 'matchround_title', 'matchround_startdate', 'matchround_enddate'])
                ->map(static function (Matchround $round): array {
                    return [
                        'matchround_id' => (int) $round->matchround_id,
                        'matchround_title' => (string) $round->matchround_title,
                        'matchround_startdate' => (string) $round->matchround_startdate,
                        'matchround_enddate' => (string) $round->matchround_enddate,
                    ];
                })
                ->all();

            $roundIds = array_column($pastRounds, 'matchround_id');
            if ($roundIds !== []) {
                $rows = Extremeteam::query()
                    ->whereIn('extremeteam_matchround_id', $roundIds)
                    ->get(['extremeteam_matchround_id', 'extremeteam_top_or_flop', 'extremeteam_score']);

                foreach ($rows as $row) {
                    $rid = (int) $row->extremeteam_matchround_id;
                    $type = (string) $row->extremeteam_top_or_flop;
                    $existing[$rid][$type] = (int) $row->extremeteam_score;
                }
            }
        }

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league_id' => $leagueId,
            'selected_league' => $shell['selected_league'],
            'past_matchrounds' => $pastRounds,
            'existing' => $existing,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function populate(int $userId, array $input): array
    {
        $leagueId = $this->adminCenter->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $scope = (string) ($input['scope'] ?? 'selected');
        $top = (string) ($input['include_top'] ?? '0') === '1';
        $flop = (string) ($input['include_flop'] ?? '0') === '1';

        if ($scope === 'all_past') {
            $matchroundIds = Matchround::query()
                ->where('matchround_league_id', $leagueId)
                ->where('matchround_enddate', '<', now())
                ->pluck('matchround_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        } else {
            $raw = $input['matchround_ids'] ?? [];
            if (! is_array($raw)) {
                $raw = [];
            }
            $matchroundIds = array_values(array_unique(array_filter(
                array_map('intval', $raw),
                static fn (int $id): bool => $id > 0,
            )));

            if ($matchroundIds === []) {
                return ['ok' => false, 'errors' => ['Bitte mindestens eine Spielrunde wählen.']];
            }

            $allowed = Matchround::query()
                ->where('matchround_league_id', $leagueId)
                ->whereIn('matchround_id', $matchroundIds)
                ->pluck('matchround_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $matchroundIds = $allowed;
            if ($matchroundIds === []) {
                return ['ok' => false, 'errors' => ['Keine gültigen Spielrunden in dieser Liga.']];
            }
        }

        if (! $top && ! $flop) {
            $top = true;
            $flop = true;
        }

        $result = $this->extremeTeams->computeAndStoreForMatchrounds($matchroundIds, $top, $flop);

        if (! ($result['ok'] ?? false) && ($result['stored'] ?? 0) === 0 && ($result['skipped'] ?? 0) === 0) {
            return [
                'ok' => false,
                'errors' => $result['errors'] !== [] ? $result['errors'] : ['Berechnung fehlgeschlagen.'],
                'details' => $result['details'],
            ];
        }

        return [
            'ok' => true,
            'message' => sprintf(
                'Extreme Teams: %d gespeichert · %d übersprungen.',
                $result['stored'],
                $result['skipped'],
            ),
            'details' => $result['details'],
        ];
    }
}
