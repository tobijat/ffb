<?php

namespace App\Services;

use App\Models\LeagueOptions;
use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Userscore;
use App\Models\Userteam;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminScoreService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly LineupOptionsResolver $lineupOptions,
        private readonly SubstitutionCalculationService $substitutions,
    ) {}

    /**
     * @param  array<string, mixed>|null  $userteamPreview
     * @param  array<string, mixed>|null  $userPreview
     * @param  array<string, mixed>|null  $subsPreview
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        string $tab = 'userteam',
        ?array $userteamPreview = null,
        ?array $userPreview = null,
        ?int $matchroundId = null,
        ?array $subsPreview = null,
    ): array {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagueId = (int) ($shell['selected_league_id'] ?? 0);
        $leagueHasSubstitutions = $this->leagueHasSubstitutions($leagueId);
        $tab = $this->normalizeTab($tab, $leagueHasSubstitutions);

        $candidateMatchroundId = (int) ($matchroundId ?? 0);
        if ($candidateMatchroundId <= 0 && is_array($subsPreview)) {
            $candidateMatchroundId = (int) ($subsPreview['matchround_id'] ?? 0);
        }
        if ($candidateMatchroundId <= 0 && is_array($userteamPreview)) {
            $candidateMatchroundId = (int) ($userteamPreview['matchround_id'] ?? 0);
        }
        $resolvedMatchroundId = $this->resolveMatchroundId($leagueId, $candidateMatchroundId);
        if ($resolvedMatchroundId < 0) {
            $resolvedMatchroundId = 0;
        }

        $needsRounds = ($tab === 'userteam' || $tab === 'subs') && $leagueId > 0;

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league_id' => $shell['selected_league_id'],
            'selected_league' => $shell['selected_league'],
            'tab' => $tab,
            'league_has_substitutions' => $leagueHasSubstitutions,
            'matchrounds' => $needsRounds
                ? $this->matchroundsForLeague($leagueId)
                : [],
            'matchround_id' => $resolvedMatchroundId,
            'userteam_preview' => $tab === 'userteam' ? $userteamPreview : null,
            'user_preview' => $tab === 'user' ? $userPreview : null,
            'subs_preview' => $tab === 'subs' ? $subsPreview : null,
        ];
    }

    public function normalizeTab(mixed $tab, bool $leagueHasSubstitutions = false): string
    {
        if ($tab === 'user') {
            return 'user';
        }
        if ($tab === 'subs' && $leagueHasSubstitutions) {
            return 'subs';
        }

        return 'userteam';
    }

    public function leagueHasSubstitutions(int $leagueId): bool
    {
        if ($leagueId <= 0) {
            return false;
        }

        $resolved = $this->lineupOptions->forLeague($leagueId);

        return ($resolved['league_benchmode'] ?? null) !== null
            && (int) ($resolved['lineup_max_bench'] ?? 0) > 0;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     tab?: string,
     *     matchround_id?: int,
     *     preview?: array<string, mixed>
     * }
     */
    public function calculateSubstitutions(int $userId, array $input = []): array
    {
        $leagueId = $this->adminCenter->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.'], 'tab' => 'subs'];
        }
        if (! $this->leagueHasSubstitutions($leagueId)) {
            return ['ok' => false, 'errors' => ['Diese Liga hat keine Ersatzbank (Bench-Mode).'], 'tab' => 'userteam'];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        $built = $this->substitutions->previewForRound($leagueId, $matchroundId);
        if (! ($built['ok'] ?? false)) {
            return $built + ['tab' => 'subs', 'matchround_id' => $matchroundId];
        }

        /** @var array<string, mixed> $preview */
        $preview = $built['preview'];
        $details = [];
        $total = 0;
        foreach ($preview['rows'] as $row) {
            $count = (int) ($row['substitution_count'] ?? 0);
            $total += $count;
            $details[] = 'userteam_id: '.(int) $row['userteam_id']
                .' substitutions: '.$count;
        }

        return [
            'ok' => true,
            'message' => 'Auswechslungen berechnet (noch nicht gespeichert): '.$total.' Wechsel.',
            'details' => $details,
            'tab' => 'subs',
            'matchround_id' => (int) $preview['matchround_id'],
            'preview' => $preview,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     tab?: string,
     *     matchround_id?: int,
     *     preview?: array<string, mixed>
     * }
     */
    public function saveSubstitutions(int $userId, array $input = []): array
    {
        $built = $this->calculateSubstitutions($userId, $input);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        /** @var array<string, mixed> $preview */
        $preview = $built['preview'];
        $this->substitutions->savePreview($preview);

        $total = 0;
        foreach ($preview['rows'] as $row) {
            $total += (int) ($row['substitution_count'] ?? 0);
        }

        return [
            'ok' => true,
            'message' => 'Auswechslungen gespeichert: '.$total.' Wechsel.',
            'details' => $built['details'] ?? [],
            'tab' => 'subs',
            'matchround_id' => (int) ($preview['matchround_id'] ?? 0),
            'preview' => $preview,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     tab?: string,
     *     matchround_id?: int,
     *     preview?: array{league_id: int, matchround_id: int, rows: list<array<string, mixed>>}
     * }
     */
    public function calculateUserteamScores(int $userId, array $input = []): array
    {
        $leagueId = $this->adminCenter->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.'], 'tab' => 'userteam'];
        }

        $matchroundId = $this->resolveMatchroundId(
            $leagueId,
            (int) ($input['matchround_id'] ?? 0),
        );
        if ($matchroundId < 0) {
            return [
                'ok' => false,
                'errors' => ['Ungültige Spielrunde für die aktive Liga.'],
                'tab' => 'userteam',
                'matchround_id' => 0,
            ];
        }

        $built = $this->buildUserteamScorePreview($leagueId, $matchroundId);
        if (! ($built['ok'] ?? false)) {
            return $built + ['tab' => 'userteam', 'matchround_id' => $matchroundId];
        }

        /** @var array{league_id: int, matchround_id: int, rows: list<array<string, mixed>>} $preview */
        $preview = $built['preview'];
        $details = [];
        foreach ($preview['rows'] as $row) {
            $details[] = 'userteam_id: '.(int) $row['userteam_id']
                .' score: '.(int) $row['score']
                .' lc: '.(int) $row['lc_points'];
        }

        $scope = $matchroundId > 0
            ? 'für die gewählte Spielrunde'
            : 'für alle Spielrunden';

        return [
            'ok' => true,
            'message' => 'Userteam-Scores '.$scope.' berechnet (noch nicht gespeichert).',
            'details' => $details,
            'tab' => 'userteam',
            'matchround_id' => $matchroundId,
            'preview' => $preview,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     tab?: string,
     *     matchround_id?: int,
     *     preview?: array{league_id: int, matchround_id: int, rows: list<array<string, mixed>>}
     * }
     */
    public function saveUserteamScores(int $userId, array $input = []): array
    {
        $built = $this->calculateUserteamScores($userId, $input);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        /** @var array{league_id: int, matchround_id: int, rows: list<array<string, mixed>>} $preview */
        $preview = $built['preview'];
        $matchroundId = (int) ($preview['matchround_id'] ?? 0);
        $details = [];

        DB::transaction(function () use ($preview, &$details): void {
            foreach ($preview['rows'] as $row) {
                $userteamId = (int) ($row['userteam_id'] ?? 0);
                if ($userteamId <= 0) {
                    continue;
                }

                Userteam::query()->whereKey($userteamId)->update([
                    'userteam_score' => (int) ($row['score'] ?? 0),
                    'userteam_lc_points' => (int) ($row['lc_points'] ?? 0),
                ]);

                $details[] = 'userteam_id: '.$userteamId
                    .' score: '.(int) ($row['score'] ?? 0)
                    .' lc: '.(int) ($row['lc_points'] ?? 0);
            }
        });

        $scope = $matchroundId > 0
            ? 'für die gewählte Spielrunde'
            : 'für alle Spielrunden';

        return [
            'ok' => true,
            'message' => 'Userteam-Scores '.$scope.' erfolgreich gespeichert (inkl. LC-Punkte für beendete Runden).',
            'details' => $details,
            'tab' => 'userteam',
            'matchround_id' => $matchroundId,
            'preview' => $preview,
        ];
    }

    /**
     * Aggregate current userteam scores into userscore totals without writing.
     *
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     tab?: string,
     *     preview?: array{league_id: int, rows: list<array<string, mixed>>}
     * }
     */
    public function calculateUserScores(int $userId): array
    {
        $leagueId = $this->adminCenter->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.'], 'tab' => 'user'];
        }

        $built = $this->buildUserScorePreview($leagueId);
        if (! ($built['ok'] ?? false)) {
            return $built + ['tab' => 'user'];
        }

        /** @var array{league_id: int, rows: list<array<string, mixed>>} $preview */
        $preview = $built['preview'];
        $details = [];
        foreach ($preview['rows'] as $row) {
            $suffix = ! empty($row['is_new']) ? ' (neu)' : '';
            $details[] = 'user_id: '.(int) $row['user_id'].' score: '.(int) $row['score'].$suffix;
            $details[] = 'user_id: '.(int) $row['user_id'].' lc_score: '.(int) $row['lc_points'];
        }

        return [
            'ok' => true,
            'message' => 'User-Scores berechnet (noch nicht gespeichert).',
            'details' => $details,
            'tab' => 'user',
            'preview' => $preview,
        ];
    }

    /**
     * Recalculate and persist aggregated userscore rows for the selected league.
     *
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     details?: list<string>,
     *     tab?: string,
     *     preview?: array{league_id: int, rows: list<array<string, mixed>>}
     * }
     */
    public function saveUserScores(int $userId): array
    {
        $built = $this->calculateUserScores($userId);
        if (! ($built['ok'] ?? false)) {
            return $built;
        }

        /** @var array{league_id: int, rows: list<array<string, mixed>>} $preview */
        $preview = $built['preview'];
        $leagueId = (int) $preview['league_id'];
        $details = [];

        DB::transaction(function () use ($preview, $leagueId, &$details): void {
            foreach ($preview['rows'] as $row) {
                $uid = (int) ($row['user_id'] ?? 0);
                if ($uid <= 0) {
                    continue;
                }

                $total = (int) ($row['score'] ?? 0);
                $lc = (int) ($row['lc_points'] ?? 0);
                $isNew = (bool) ($row['is_new'] ?? false);

                if ($isNew) {
                    Userscore::query()->create([
                        'userscore_user_id' => $uid,
                        'userscore_league_id' => $leagueId,
                        'userscore_total' => $total,
                        'userscore_lc_points' => $lc,
                    ]);
                    $details[] = 'user_id: '.$uid.' score: '.$total.' (new entry created!)';
                    $details[] = 'user_id: '.$uid.' lc_score: '.$lc;
                } else {
                    Userscore::query()
                        ->where('userscore_user_id', $uid)
                        ->where('userscore_league_id', $leagueId)
                        ->update([
                            'userscore_total' => $total,
                            'userscore_lc_points' => $lc,
                        ]);
                    $details[] = 'user_id: '.$uid.' score: '.$total;
                    $details[] = 'user_id: '.$uid.' lc_score: '.$lc;
                }
            }
        });

        return [
            'ok' => true,
            'message' => 'User-Scores erfolgreich gespeichert.',
            'details' => $details,
            'tab' => 'user',
            'preview' => $preview,
        ];
    }

    /**
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     preview?: array{league_id: int, matchround_id: int, rows: list<array<string, mixed>>}
     * }
     */
    private function buildUserteamScorePreview(int $leagueId, int $matchroundId = 0): array
    {
        $roundsQuery = Matchround::query()->where('matchround_league_id', $leagueId);
        if ($matchroundId > 0) {
            $roundsQuery->whereKey($matchroundId);
        }

        $rounds = $roundsQuery
            ->orderBy('matchround_startdate')
            ->get(['matchround_id', 'matchround_title']);

        $matchroundIds = $rounds
            ->map(static fn (Matchround $round): int => (int) $round->matchround_id)
            ->all();
        $titlesById = $rounds
            ->mapWithKeys(static fn (Matchround $round): array => [
                (int) $round->matchround_id => (string) $round->matchround_title,
            ])
            ->all();

        if ($matchroundIds === []) {
            return [
                'ok' => true,
                'message' => $matchroundId > 0
                    ? 'Keine Userteams für diese Spielrunde.'
                    : 'Keine Spielrunden in dieser Liga.',
                'preview' => [
                    'league_id' => $leagueId,
                    'matchround_id' => $matchroundId,
                    'rows' => [],
                ],
            ];
        }

        $userteams = Userteam::query()
            ->with('user')
            ->whereIn('userteam_matchround_id', $matchroundIds)
            ->orderBy('userteam_matchround_id')
            ->orderBy('userteam_id')
            ->get();

        /** @var array<int, int> $scoresByUserteamId */
        $scoresByUserteamId = [];
        /** @var list<array<string, mixed>> $rows */
        $rows = [];

        foreach ($userteams as $userteam) {
            $userteamId = (int) $userteam->userteam_id;
            $roundId = (int) $userteam->userteam_matchround_id;
            $playerteamIds = $userteam->playerteamIdsInSlotOrder();
            $score = 0;
            if ($playerteamIds !== []) {
                $score = (int) Playerstats::query()
                    ->where('playerstats_matchround_id', $roundId)
                    ->whereIn('playerstats_playerteam_id', $playerteamIds)
                    ->sum('playerstats_score');
            }

            $scoresByUserteamId[$userteamId] = $score;
            $rows[] = [
                'userteam_id' => $userteamId,
                'user_id' => (int) $userteam->userteam_user_id,
                'user_nickname' => (string) ($userteam->user?->user_nickname ?? ''),
                'matchround_id' => $roundId,
                'matchround_title' => (string) ($titlesById[$roundId] ?? ''),
                'score' => $score,
                'lc_points' => (int) ($userteam->userteam_lc_points ?? 0),
                'previous_score' => (int) ($userteam->userteam_score ?? 0),
                'previous_lc_points' => (int) ($userteam->userteam_lc_points ?? 0),
            ];
        }

        $lcByUserteamId = $this->computeLcPointsByUserteamId($leagueId, $scoresByUserteamId, $userteams);
        foreach ($rows as $index => $row) {
            $id = (int) $row['userteam_id'];
            if (array_key_exists($id, $lcByUserteamId)) {
                $rows[$index]['lc_points'] = $lcByUserteamId[$id];
            }
        }

        return [
            'ok' => true,
            'preview' => [
                'league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'rows' => $rows,
            ],
        ];
    }

    /**
     * 0 = all rounds; positive id must belong to the league; -1 = invalid.
     */
    private function resolveMatchroundId(int $leagueId, int $matchroundId): int
    {
        if ($matchroundId <= 0) {
            return 0;
        }
        if ($leagueId <= 0) {
            return -1;
        }

        $exists = Matchround::query()
            ->whereKey($matchroundId)
            ->where('matchround_league_id', $leagueId)
            ->exists();

        return $exists ? $matchroundId : -1;
    }

    /**
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    private function matchroundsForLeague(int $leagueId): array
    {
        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->get(['matchround_id', 'matchround_title'])
            ->map(static fn (Matchround $round): array => [
                'matchround_id' => (int) $round->matchround_id,
                'matchround_title' => (string) $round->matchround_title,
            ])
            ->all();
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, preview?: array{league_id: int, rows: list<array<string, mixed>>}}
     */
    private function buildUserScorePreview(int $leagueId): array
    {
        $matchroundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($matchroundIds === []) {
            return [
                'ok' => true,
                'message' => 'Keine Spielrunden in dieser Liga.',
                'preview' => ['league_id' => $leagueId, 'rows' => []],
            ];
        }

        $totals = Userteam::query()
            ->whereIn('userteam_matchround_id', $matchroundIds)
            ->selectRaw('userteam_user_id, SUM(userteam_score) as total_score, SUM(userteam_lc_points) as total_lc')
            ->groupBy('userteam_user_id')
            ->orderBy('userteam_user_id')
            ->get();

        $existing = Userscore::query()
            ->where('userscore_league_id', $leagueId)
            ->get()
            ->keyBy(fn (Userscore $row): int => (int) $row->userscore_user_id);

        $nicknames = Userteam::query()
            ->with('user')
            ->whereIn('userteam_matchround_id', $matchroundIds)
            ->get()
            ->groupBy(fn (Userteam $row): int => (int) $row->userteam_user_id)
            ->map(static fn (Collection $group): string => (string) ($group->first()?->user?->user_nickname ?? ''));

        /** @var list<array<string, mixed>> $rows */
        $rows = [];
        foreach ($totals as $row) {
            $uid = (int) $row->userteam_user_id;
            $total = (int) $row->total_score;
            $lc = (int) $row->total_lc;
            $current = $existing->get($uid);

            $rows[] = [
                'user_id' => $uid,
                'user_nickname' => (string) ($nicknames->get($uid) ?? ''),
                'score' => $total,
                'lc_points' => $lc,
                'previous_score' => $current ? (int) $current->userscore_total : null,
                'previous_lc_points' => $current ? (int) $current->userscore_lc_points : null,
                'is_new' => $current === null,
            ];
        }

        return [
            'ok' => true,
            'preview' => [
                'league_id' => $leagueId,
                'rows' => $rows,
            ],
        ];
    }

    /**
     * LC points for finished matchrounds only, keyed by userteam_id.
     *
     * @param  array<int, int>  $scoresByUserteamId
     * @param  Collection<int, Userteam>  $userteams
     * @return array<int, int>
     */
    private function computeLcPointsByUserteamId(int $leagueId, array $scoresByUserteamId, Collection $userteams): array
    {
        $options = LeagueOptions::query()->where('options_league_id', $leagueId)->first()
            ?? LeagueOptions::query()->where('options_league_id', 0)->first();

        $raw = trim((string) ($options?->options_league_lcpoints ?? ''));
        if ($raw === '') {
            return [];
        }

        $lcPoints = array_map(
            static fn (string $v): int => (int) trim($v),
            explode(',', $raw)
        );
        if ($lcPoints === []) {
            return [];
        }

        $now = date('Y-m-d H:i:s');
        $finishedRoundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_enddate', '<', $now)
            ->pluck('matchround_id')
            ->map(fn ($id) => (int) $id)
            ->all();
        if ($finishedRoundIds === []) {
            return [];
        }

        $finishedLookup = array_fill_keys($finishedRoundIds, true);

        /** @var array<int, list<Userteam>> $byRound */
        $byRound = [];
        foreach ($userteams as $userteam) {
            $roundId = (int) $userteam->userteam_matchround_id;
            if (! isset($finishedLookup[$roundId])) {
                continue;
            }
            $byRound[$roundId][] = $userteam;
        }

        /** @var array<int, int> $result */
        $result = [];

        foreach ($byRound as $roundUserteams) {
            $users = [];
            foreach ($roundUserteams as $userteam) {
                $userteamId = (int) $userteam->userteam_id;
                $users[] = [
                    'user_nickname' => strtolower((string) ($userteam->user?->user_nickname ?? '')),
                    'user_userteam_id' => $userteamId,
                    'user_score' => (int) ($scoresByUserteamId[$userteamId] ?? 0),
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
            foreach ($users as $item) {
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

                $result[$item['user_userteam_id']] = $lc;
                $lastScore = $currScore;
            }
        }

        return $result;
    }
}
