<?php

namespace App\Services;

use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Userteam;
use App\Models\UserteamSubstituteSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubstitutionCalculationService
{
    public function __construct(
        private readonly LineupOptionsResolver $lineupOptions,
    ) {}

    /**
     * @return array{
     *     ok: bool,
     *     errors?: list<string>,
     *     preview?: array{
     *         league_id: int,
     *         matchround_id: int,
     *         matchround_title: string,
     *         benchmode: string,
     *         rows: list<array<string, mixed>>
     *     }
     * }
     */
    public function previewForRound(int $leagueId, int $matchroundId): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }
        if ($matchroundId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte eine Spielrunde auswählen.']];
        }

        $round = Matchround::query()
            ->whereKey($matchroundId)
            ->where('matchround_league_id', $leagueId)
            ->first();
        if (! $round) {
            return ['ok' => false, 'errors' => ['Ungültige Spielrunde für die aktive Liga.']];
        }

        $resolved = $this->lineupOptions->forMatchround($matchroundId);
        $benchmode = $resolved['league_benchmode'] ?? null;
        $maxBench = (int) ($resolved['lineup_max_bench'] ?? 0);
        if ($benchmode === null || $maxBench <= 0) {
            return ['ok' => false, 'errors' => ['Diese Liga hat keine Ersatzbank (Bench-Mode).']];
        }

        $mins = [
            'g' => (int) $resolved['lineup_min_g'],
            'd' => (int) $resolved['lineup_min_d'],
            'm' => (int) $resolved['lineup_min_m'],
            's' => (int) $resolved['lineup_min_s'],
        ];
        $maxs = [
            'g' => (int) $resolved['lineup_max_g'],
            'd' => (int) $resolved['lineup_max_d'],
            'm' => (int) $resolved['lineup_max_m'],
            's' => (int) $resolved['lineup_max_s'],
        ];

        $userteams = Userteam::query()
            ->with(['user', 'slots', 'substituteSlots'])
            ->where('userteam_matchround_id', $matchroundId)
            ->orderBy('userteam_id')
            ->get();

        $allPlayerteamIds = [];
        foreach ($userteams as $userteam) {
            foreach ($userteam->playerteamIdsInSlotOrder() as $id) {
                $allPlayerteamIds[] = $id;
            }
            foreach ($userteam->substitutePlayerteamIdsInSlotOrder() as $id) {
                $allPlayerteamIds[] = $id;
            }
        }
        $allPlayerteamIds = array_values(array_unique(array_filter($allPlayerteamIds)));

        $playerteams = $allPlayerteamIds === []
            ? collect()
            : Playerteam::query()
                ->with('player')
                ->whereIn('playerteam_id', $allPlayerteamIds)
                ->get()
                ->keyBy(fn (Playerteam $pt): int => (int) $pt->playerteam_id);

        $statsByPlayerteam = $this->statsByPlayerteamId($matchroundId, $allPlayerteamIds);

        /** @var list<array<string, mixed>> $rows */
        $rows = [];
        foreach ($userteams as $userteam) {
            $starters = $this->buildPlayers(
                $userteam->playerteamIdsInSlotOrder(),
                $playerteams,
                $statsByPlayerteam,
            );
            $bench = $this->buildPlayers(
                $userteam->substitutePlayerteamIdsInSlotOrder(),
                $playerteams,
                $statsByPlayerteam,
            );

            $substitutions = $benchmode === 'bestof'
                ? $this->calculateBestof($starters, $bench)
                : $this->calculateCover($starters, $bench, $mins, $maxs);

            $previousBySub = [];
            foreach ($userteam->substituteSlots as $slot) {
                $subId = (int) $slot->substitute_slot_playerteam_id;
                if ($subId > 0) {
                    $previousBySub[$subId] = $slot->substitute_slot_replaces_playerteam_id !== null
                        ? (int) $slot->substitute_slot_replaces_playerteam_id
                        : null;
                }
            }

            $rows[] = [
                'userteam_id' => (int) $userteam->userteam_id,
                'user_id' => (int) $userteam->userteam_user_id,
                'user_nickname' => (string) ($userteam->user?->user_nickname ?? ''),
                'substitutions' => $substitutions,
                'substitution_count' => count($substitutions),
                'previous_replaces' => $previousBySub,
            ];
        }

        return [
            'ok' => true,
            'preview' => [
                'league_id' => $leagueId,
                'matchround_id' => $matchroundId,
                'matchround_title' => (string) $round->matchround_title,
                'benchmode' => $benchmode,
                'rows' => $rows,
            ],
        ];
    }

    /**
     * Persist calculated replaces onto substitute slots for the preview round.
     *
     * @param  array{
     *     league_id: int,
     *     matchround_id: int,
     *     rows: list<array<string, mixed>>
     * }  $preview
     */
    public function savePreview(array $preview): void
    {
        $matchroundId = (int) ($preview['matchround_id'] ?? 0);
        $rows = is_array($preview['rows'] ?? null) ? $preview['rows'] : [];
        if ($matchroundId <= 0) {
            return;
        }

        DB::transaction(function () use ($matchroundId, $rows): void {
            $userteamIds = Userteam::query()
                ->where('userteam_matchround_id', $matchroundId)
                ->pluck('userteam_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            if ($userteamIds !== []) {
                UserteamSubstituteSlot::query()
                    ->whereIn('substitute_slot_userteam_id', $userteamIds)
                    ->update(['substitute_slot_replaces_playerteam_id' => null]);
            }

            foreach ($rows as $row) {
                $userteamId = (int) ($row['userteam_id'] ?? 0);
                $subs = is_array($row['substitutions'] ?? null) ? $row['substitutions'] : [];
                foreach ($subs as $sub) {
                    $subPt = (int) ($sub['substitute_playerteam_id'] ?? 0);
                    $outPt = (int) ($sub['out_playerteam_id'] ?? 0);
                    if ($userteamId <= 0 || $subPt <= 0 || $outPt <= 0) {
                        continue;
                    }
                    UserteamSubstituteSlot::query()
                        ->where('substitute_slot_userteam_id', $userteamId)
                        ->where('substitute_slot_playerteam_id', $subPt)
                        ->update(['substitute_slot_replaces_playerteam_id' => $outPt]);
                }
            }
        });
    }

    /**
     * @param  list<array<string, mixed>>  $starters
     * @param  list<array<string, mixed>>  $bench
     * @param  array{g: int, d: int, m: int, s: int}  $mins
     * @param  array{g: int, d: int, m: int, s: int}  $maxs
     * @return list<array<string, mixed>>
     */
    private function calculateCover(array $starters, array $bench, array $mins, array $maxs): array
    {
        $active = $starters;
        $didNotPlay = array_values(array_filter(
            $starters,
            static fn (array $p): bool => ! $p['played']
        ));

        if ($didNotPlay === []) {
            return [];
        }

        $benchPlayed = array_values(array_filter(
            $bench,
            static fn (array $p): bool => $p['played']
        ));
        usort($benchPlayed, static function (array $a, array $b): int {
            if ($a['score'] !== $b['score']) {
                return $b['score'] <=> $a['score'];
            }

            return $a['slot'] <=> $b['slot'];
        });

        $didNotPlayIds = array_fill_keys(array_map(
            static fn (array $p): int => (int) $p['playerteam_id'],
            $didNotPlay
        ), true);

        $substitutions = [];
        foreach ($benchPlayed as $sub) {
            $out = $this->findCoverTarget($active, $didNotPlayIds, $sub, $mins, $maxs);
            if ($out === null) {
                continue;
            }

            $substitutions[] = $this->substitutionRow($out, $sub);
            $active = array_values(array_filter(
                $active,
                static fn (array $p): bool => (int) $p['playerteam_id'] !== (int) $out['playerteam_id']
            ));
            $active[] = $sub;
            unset($didNotPlayIds[(int) $out['playerteam_id']]);
        }

        return $substitutions;
    }

    /**
     * @param  list<array<string, mixed>>  $active
     * @param  array<int, true>  $didNotPlayIds
     * @param  array<string, mixed>  $sub
     * @param  array{g: int, d: int, m: int, s: int}  $mins
     * @param  array{g: int, d: int, m: int, s: int}  $maxs
     * @return array<string, mixed>|null
     */
    private function findCoverTarget(
        array $active,
        array $didNotPlayIds,
        array $sub,
        array $mins,
        array $maxs,
    ): ?array {
        $candidates = array_values(array_filter(
            $active,
            static fn (array $p): bool => isset($didNotPlayIds[(int) $p['playerteam_id']])
        ));
        usort($candidates, static fn (array $a, array $b): int => $a['slot'] <=> $b['slot']);

        foreach ($candidates as $candidate) {
            if ($candidate['position'] === $sub['position']) {
                return $candidate;
            }
        }

        foreach ($candidates as $candidate) {
            if ($this->canCrossPositionSwap($active, $candidate, $sub, $mins, $maxs)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @param  list<array<string, mixed>>  $active
     * @param  array<string, mixed>  $out
     * @param  array<string, mixed>  $inn
     * @param  array{g: int, d: int, m: int, s: int}  $mins
     * @param  array{g: int, d: int, m: int, s: int}  $maxs
     */
    private function canCrossPositionSwap(
        array $active,
        array $out,
        array $inn,
        array $mins,
        array $maxs,
    ): bool {
        $counts = ['g' => 0, 'd' => 0, 'm' => 0, 's' => 0];
        foreach ($active as $player) {
            $pos = (string) $player['position'];
            if (isset($counts[$pos])) {
                $counts[$pos]++;
            }
        }

        $outPos = (string) $out['position'];
        $inPos = (string) $inn['position'];
        if (! isset($counts[$outPos], $counts[$inPos])) {
            return false;
        }

        $counts[$outPos]--;
        $counts[$inPos]++;

        return $counts[$outPos] >= $mins[$outPos] && $counts[$inPos] <= $maxs[$inPos];
    }

    /**
     * @param  list<array<string, mixed>>  $starters
     * @param  list<array<string, mixed>>  $bench
     * @return list<array<string, mixed>>
     */
    private function calculateBestof(array $starters, array $bench): array
    {
        $substitutions = [];
        $usedSubIds = [];

        $startersByScore = $starters;
        usort($startersByScore, static function (array $a, array $b): int {
            if ($a['score'] !== $b['score']) {
                return $a['score'] <=> $b['score'];
            }

            return $a['slot'] <=> $b['slot'];
        });

        foreach ($startersByScore as $starter) {
            $bestSub = null;
            foreach ($bench as $sub) {
                $subId = (int) $sub['playerteam_id'];
                if (isset($usedSubIds[$subId])) {
                    continue;
                }
                if (! $sub['played']) {
                    continue;
                }
                if ($sub['position'] !== $starter['position']) {
                    continue;
                }
                if ($sub['score'] <= $starter['score']) {
                    continue;
                }
                if (
                    $bestSub === null
                    || $sub['score'] > $bestSub['score']
                    || ($sub['score'] === $bestSub['score'] && $sub['slot'] < $bestSub['slot'])
                ) {
                    $bestSub = $sub;
                }
            }

            if ($bestSub === null) {
                continue;
            }

            $usedSubIds[(int) $bestSub['playerteam_id']] = true;
            $substitutions[] = $this->substitutionRow($starter, $bestSub);
        }

        return $substitutions;
    }

    /**
     * @param  array<string, mixed>  $out
     * @param  array<string, mixed>  $inn
     * @return array<string, mixed>
     */
    private function substitutionRow(array $out, array $inn): array
    {
        return [
            'out_playerteam_id' => (int) $out['playerteam_id'],
            'out_name' => (string) $out['name'],
            'out_position' => (string) $out['position'],
            'out_score' => (int) $out['score'],
            'out_played' => (bool) $out['played'],
            'substitute_playerteam_id' => (int) $inn['playerteam_id'],
            'substitute_name' => (string) $inn['name'],
            'substitute_position' => (string) $inn['position'],
            'substitute_score' => (int) $inn['score'],
        ];
    }

    /**
     * @param  list<int>  $playerteamIds
     * @param  Collection<int, Playerteam>  $playerteams
     * @param  array<int, array{played: bool, score: int}>  $statsByPlayerteam
     * @return list<array<string, mixed>>
     */
    private function buildPlayers(array $playerteamIds, Collection $playerteams, array $statsByPlayerteam): array
    {
        $players = [];
        foreach (array_values($playerteamIds) as $index => $playerteamId) {
            $pt = $playerteams->get($playerteamId);
            $stats = $statsByPlayerteam[$playerteamId] ?? ['played' => false, 'score' => 0];
            $fname = (string) ($pt?->player?->player_fname ?? '');
            $lname = (string) ($pt?->player?->player_lname ?? '');
            $name = trim($fname.' '.$lname);
            if ($name === '') {
                $name = '#'.$playerteamId;
            }

            $players[] = [
                'playerteam_id' => $playerteamId,
                'slot' => $index + 1,
                'position' => strtolower((string) ($pt?->playerteam_player_position ?? '')),
                'name' => $name,
                'played' => (bool) $stats['played'],
                'score' => (int) $stats['score'],
            ];
        }

        return $players;
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return array<int, array{played: bool, score: int}>
     */
    private function statsByPlayerteamId(int $matchroundId, array $playerteamIds): array
    {
        if ($playerteamIds === []) {
            return [];
        }

        $rows = Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->whereIn('playerstats_playerteam_id', $playerteamIds)
            ->selectRaw('playerstats_playerteam_id, SUM(playerstats_score) as total_score')
            ->groupBy('playerstats_playerteam_id')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $id = (int) $row->playerstats_playerteam_id;
            $map[$id] = [
                'played' => true,
                'score' => (int) $row->total_score,
            ];
        }

        return $map;
    }
}
