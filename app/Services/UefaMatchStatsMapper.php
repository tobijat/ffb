<?php

namespace App\Services;

/**
 * Maps UEFA match / lineups / events into the Weltfussball scrape player shape
 * consumed by AdminMatchdataService::mapScrapedSide.
 */
class UefaMatchStatsMapper
{
    /**
     * @param  array<string, mixed>  $match
     * @param  array<string, mixed>  $lineups
     * @param  list<array<string, mixed>>  $events
     * @return array{
     *     match_minutes: int,
     *     home: list<array<string, mixed>>,
     *     guest: list<array<string, mixed>>,
     *     result: array{
     *         homescore: int,
     *         guestscore: int,
     *         homescore_penalty: int,
     *         guestscore_penalty: int
     *     },
     *     uefa_match_id: string
     * }
     */
    public function map(array $match, array $lineups, array $events): array
    {
        $matchMinutes = $this->resolveMatchMinutes($match, $events);
        $homeTeamId = trim((string) ($lineups['homeTeam']['team']['id']
            ?? $match['homeTeam']['id']
            ?? ''));
        $awayTeamId = trim((string) ($lineups['awayTeam']['team']['id']
            ?? $match['awayTeam']['id']
            ?? ''));

        $homePlayers = $this->buildSidePlayers(
            is_array($lineups['homeTeam'] ?? null) ? $lineups['homeTeam'] : [],
            $homeTeamId,
            $events,
            is_array($match['playerEvents'] ?? null) ? $match['playerEvents'] : [],
            $matchMinutes,
        );
        $guestPlayers = $this->buildSidePlayers(
            is_array($lineups['awayTeam'] ?? null) ? $lineups['awayTeam'] : [],
            $awayTeamId,
            $events,
            is_array($match['playerEvents'] ?? null) ? $match['playerEvents'] : [],
            $matchMinutes,
        );

        $score = is_array($match['score'] ?? null) ? $match['score'] : [];
        [$homeScore, $guestScore] = $this->resolveFullTimeScore($score, $matchMinutes === 120);
        $penalty = is_array($score['penalty'] ?? null) ? $score['penalty'] : null;
        $homePs = is_array($penalty) ? (int) ($penalty['home'] ?? 0) : -1;
        $awayPs = is_array($penalty) ? (int) ($penalty['away'] ?? 0) : -1;
        if ($penalty === null) {
            $homePs = -1;
            $awayPs = -1;
        }

        return [
            'match_minutes' => $matchMinutes,
            'home' => $homePlayers,
            'guest' => $guestPlayers,
            'result' => [
                'homescore' => $homeScore,
                'guestscore' => $guestScore,
                'homescore_penalty' => $homePs,
                'guestscore_penalty' => $awayPs,
            ],
            'uefa_match_id' => trim((string) ($match['id'] ?? '')),
        ];
    }

    /**
     * Full-time score excluding penalty shootout.
     * Use score.total only after overtime (includes ET goals); otherwise score.regular.
     *
     * @param  array<string, mixed>  $score
     * @return array{0: int, 1: int}
     */
    private function resolveFullTimeScore(array $score, bool $hadOvertime): array
    {
        $regular = is_array($score['regular'] ?? null) ? $score['regular'] : null;
        $total = is_array($score['total'] ?? null) ? $score['total'] : null;

        if ($hadOvertime && $total !== null) {
            return [
                (int) ($total['home'] ?? 0),
                (int) ($total['away'] ?? 0),
            ];
        }

        if ($regular !== null) {
            return [
                (int) ($regular['home'] ?? 0),
                (int) ($regular['away'] ?? 0),
            ];
        }

        if ($total !== null) {
            return [
                (int) ($total['home'] ?? 0),
                (int) ($total['away'] ?? 0),
            ];
        }

        return [0, 0];
    }

    /**
     * @param  array<string, mixed>  $match
     * @param  list<array<string, mixed>>  $events
     */
    private function resolveMatchMinutes(array $match, array $events): int
    {
        $reason = strtoupper((string) ($match['winner']['match']['reason'] ?? ''));
        if (str_contains($reason, 'PENALT') || str_contains($reason, 'EXTRA')) {
            return 120;
        }
        if (is_array($match['score']['penalty'] ?? null) || is_array($match['score']['extraTime'] ?? null)) {
            return 120;
        }

        $regular = is_array($match['score']['regular'] ?? null) ? $match['score']['regular'] : null;
        $total = is_array($match['score']['total'] ?? null) ? $match['score']['total'] : null;
        if ($regular !== null && $total !== null) {
            $regularSum = (int) ($regular['home'] ?? 0) + (int) ($regular['away'] ?? 0);
            $totalSum = (int) ($total['home'] ?? 0) + (int) ($total['away'] ?? 0);
            // Extra-time goals increase total above regular; ignore bogus total 0:0 payloads.
            if ($totalSum > $regularSum) {
                return 120;
            }
        }

        foreach ($events as $event) {
            $phase = strtoupper((string) ($event['phase'] ?? ''));
            if (str_starts_with($phase, 'EXTRA_TIME') || $phase === 'PENALTY') {
                return 120;
            }
        }

        $playerEvents = is_array($match['playerEvents'] ?? null) ? $match['playerEvents'] : [];
        foreach (['scorers', 'redCards', 'penaltiesMissed', 'penaltyScorers'] as $key) {
            foreach (is_array($playerEvents[$key] ?? null) ? $playerEvents[$key] : [] as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $phase = strtoupper((string) ($row['phase'] ?? ''));
                if (str_starts_with($phase, 'EXTRA_TIME') || $phase === 'PENALTY') {
                    return 120;
                }
            }
        }

        return 90;
    }

    /**
     * @param  array<string, mixed>  $side
     * @param  list<array<string, mixed>>  $events
     * @param  array<string, mixed>  $playerEvents
     * @return list<array<string, mixed>>
     */
    private function buildSidePlayers(
        array $side,
        string $teamUefaId,
        array $events,
        array $playerEvents,
        int $matchMinutes,
    ): array {
        $field = is_array($side['field'] ?? null) ? $side['field'] : [];
        $bench = is_array($side['bench'] ?? null) ? $side['bench'] : [];

        /** @var array<string, array{name: string, starter: bool}> $roster */
        $roster = [];
        foreach ($field as $slot) {
            if (! is_array($slot)) {
                continue;
            }
            $player = is_array($slot['player'] ?? null) ? $slot['player'] : [];
            $id = trim((string) ($player['id'] ?? ''));
            if ($id === '') {
                continue;
            }
            $roster[$id] = [
                'name' => $this->playerName($player),
                'starter' => true,
            ];
        }
        foreach ($bench as $slot) {
            if (! is_array($slot)) {
                continue;
            }
            $player = is_array($slot['player'] ?? null) ? $slot['player'] : [];
            $id = trim((string) ($player['id'] ?? ''));
            if ($id === '' || isset($roster[$id])) {
                continue;
            }
            $roster[$id] = [
                'name' => $this->playerName($player),
                'starter' => false,
            ];
        }

        /** @var array<string, list<int>> $subOut */
        $subOut = [];
        /** @var array<string, list<int>> $subIn */
        $subIn = [];
        /** @var array<string, list<int>> $goals */
        $goals = [];
        /** @var array<string, list<int>> $owngoals */
        $owngoals = [];
        /** @var array<string, int> $assists */
        $assists = [];
        /** @var array<string, list<string>> $cardEvents */
        $cardEvents = [];
        /** @var array<string, int> $dismissedAt */
        $dismissedAt = [];
        /** @var array<string, int> $penaltiesLost */
        $penaltiesLost = [];
        /** @var array<string, int> $penaltiesSaved */
        $penaltiesSaved = [];
        /** @var array<string, int> $psHit */
        $psHit = [];
        /** @var array<string, int> $psLost */
        $psLost = [];

        foreach ($events as $event) {
            if (! is_array($event)) {
                continue;
            }
            $type = strtoupper((string) ($event['type'] ?? ''));
            $minute = $this->eventMinute($event);
            $primaryId = trim((string) ($event['primaryActor']['person']['id'] ?? ''));
            $secondaryId = trim((string) ($event['secondaryActor']['person']['id'] ?? ''));
            $eventTeamId = trim((string) (
                $event['primaryActor']['team']['id']
                ?? $event['team']['id']
                ?? ''
            ));

            if ($type === 'SUBSTITUTION') {
                // UEFA: primary = player leaving (field), secondary = player entering (bench).
                if ($primaryId !== '' && isset($roster[$primaryId])) {
                    $subOut[$primaryId][] = $minute;
                }
                if ($secondaryId !== '' && isset($roster[$secondaryId])) {
                    $subIn[$secondaryId][] = $minute;
                }

                continue;
            }

            if ($type === 'GOAL') {
                $goalType = $this->inferGoalType($event, $playerEvents, $primaryId);
                if ($goalType === 'OWN') {
                    if ($primaryId !== '' && isset($roster[$primaryId])) {
                        $owngoals[$primaryId][] = $minute;
                    }
                } elseif ($primaryId !== '' && isset($roster[$primaryId])) {
                    $goals[$primaryId][] = $minute;
                }
                if ($goalType !== 'OWN' && $secondaryId !== '' && isset($roster[$secondaryId])) {
                    $assists[$secondaryId] = ($assists[$secondaryId] ?? 0) + 1;
                }

                continue;
            }

            if ($type === 'YELLOW_CARD' || $type === 'RED_CARD' || $type === 'SECOND_YELLOW' || $type === 'SECOND_YELLOW_CARD') {
                if ($primaryId !== '' && isset($roster[$primaryId])) {
                    $cardEvents[$primaryId][] = $type;
                    if ($this->isDismissalCard($type) && $minute > 0) {
                        $dismissedAt[$primaryId] = $this->earliestPositiveMinute(
                            $dismissedAt[$primaryId] ?? 0,
                            $minute,
                        );
                    }
                }

                continue;
            }

            if ($type === 'PENALTY') {
                // Missed/saved spot-kick in open play; primary is usually the taker.
                if ($primaryId !== '' && isset($roster[$primaryId]) && ($eventTeamId === '' || $eventTeamId === $teamUefaId)) {
                    $penaltiesLost[$primaryId] = ($penaltiesLost[$primaryId] ?? 0) + 1;
                }
                if ($secondaryId !== '' && isset($roster[$secondaryId])) {
                    $penaltiesSaved[$secondaryId] = ($penaltiesSaved[$secondaryId] ?? 0) + 1;
                }
            }
        }

        foreach (is_array($playerEvents['scorers'] ?? null) ? $playerEvents['scorers'] : [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $pid = trim((string) ($row['player']['id'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            $gt = strtoupper((string) ($row['goalType'] ?? 'SCORED'));
            $minute = (int) ($row['time']['minute'] ?? 0);
            if ($minute <= 0) {
                continue;
            }
            if ($gt === 'OWN') {
                if (! isset($owngoals[$pid]) || ! in_array($minute, $owngoals[$pid], true)) {
                    $owngoals[$pid][] = $minute;
                }
            } elseif ($gt === 'SCORED' || $gt === 'PENALTY') {
                if (! isset($goals[$pid]) || ! in_array($minute, $goals[$pid], true)) {
                    $goals[$pid][] = $minute;
                }
            }
        }

        foreach (is_array($playerEvents['penaltiesMissed'] ?? null) ? $playerEvents['penaltiesMissed'] : [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $pid = trim((string) ($row['player']['id'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            $penaltiesLost[$pid] = ($penaltiesLost[$pid] ?? 0) + 1;
        }

        foreach (is_array($playerEvents['redCards'] ?? null) ? $playerEvents['redCards'] : [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $pid = trim((string) ($row['player']['id'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            $cardEvents[$pid][] = 'RED_CARD';
            $minute = (int) ($row['time']['minute'] ?? 0);
            if ($minute > 0) {
                $dismissedAt[$pid] = $this->earliestPositiveMinute(
                    $dismissedAt[$pid] ?? 0,
                    $minute,
                );
            }
        }

        foreach (is_array($playerEvents['penaltyScorers'] ?? null) ? $playerEvents['penaltyScorers'] : [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $pid = trim((string) ($row['player']['id'] ?? ''));
            $rowTeam = trim((string) ($row['teamId'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            if ($rowTeam !== '' && $teamUefaId !== '' && $rowTeam !== $teamUefaId) {
                continue;
            }
            $ptype = strtoupper((string) ($row['penaltyType'] ?? ''));
            if ($ptype === 'SCORED') {
                $psHit[$pid] = ($psHit[$pid] ?? 0) + 1;
            } elseif ($ptype === 'MISSED' || $ptype === 'SAVED') {
                $psLost[$pid] = ($psLost[$pid] ?? 0) + 1;
            }
        }

        $players = [];
        foreach ($roster as $uefaId => $meta) {
            $rawIn = 0;
            $rawOut = 0;
            if ($meta['starter']) {
                $rawIn = 1;
                if (isset($subOut[$uefaId][0])) {
                    $rawOut = (int) $subOut[$uefaId][0];
                }
            } else {
                if (! isset($subIn[$uefaId][0])) {
                    // Unused bench — omit like WF scraper.
                    continue;
                }
                $rawIn = (int) $subIn[$uefaId][0];
                if (isset($subOut[$uefaId][0])) {
                    $rawOut = (int) $subOut[$uefaId][0];
                }
            }

            if (isset($dismissedAt[$uefaId])) {
                $rawOut = $this->earliestPositiveMinute($rawOut, $dismissedAt[$uefaId]);
            }

            [$in, $out, $minutes] = $this->finalizePlayingWindow($rawIn, $rawOut, $matchMinutes);
            $goalMinutes = array_values(array_unique($goals[$uefaId] ?? []));
            sort($goalMinutes);
            $ownMinutes = array_values(array_unique($owngoals[$uefaId] ?? []));
            sort($ownMinutes);

            $players[] = [
                'player_name' => $meta['name'],
                'player_uefa_id' => $uefaId,
                'player_change_in' => $in,
                'player_change_out' => $out,
                'player_cards' => $this->cardCode($cardEvents[$uefaId] ?? []),
                'player_num_goals' => count($goalMinutes),
                'player_goal' => $goalMinutes === [] ? '0' : implode(';', $goalMinutes),
                'player_num_owngoals' => count($ownMinutes),
                'player_owngoal' => $ownMinutes === [] ? '0' : implode(';', $ownMinutes),
                'player_num_assists' => (int) ($assists[$uefaId] ?? 0),
                'player_penalties_lost' => (int) ($penaltiesLost[$uefaId] ?? 0),
                'player_penalties_saved' => (int) ($penaltiesSaved[$uefaId] ?? 0),
                'player_penalties_hit' => (int) ($psHit[$uefaId] ?? 0),
                'player_penalties_fail' => (int) ($psLost[$uefaId] ?? 0),
                'player_minutes' => $minutes,
            ];
        }

        return $players;
    }

    /**
     * @param  array<string, mixed>  $player
     */
    private function playerName(array $player): string
    {
        $translations = is_array($player['translations'] ?? null) ? $player['translations'] : [];
        $first = trim((string) ($translations['firstName']['DE']
            ?? $translations['firstName']['EN']
            ?? ''));
        $last = trim((string) ($translations['lastName']['DE']
            ?? $translations['lastName']['EN']
            ?? ''));
        if ($first !== '' || $last !== '') {
            return trim($first.' '.$last);
        }

        return trim((string) ($player['internationalName']
            ?? $translations['name']['DE']
            ?? $translations['name']['EN']
            ?? ''));
    }

    /**
     * @param  array<string, mixed>  $event
     */
    private function eventMinute(array $event): int
    {
        $minute = (int) ($event['time']['minute'] ?? 0);

        return max(0, $minute);
    }

    /**
     * @param  array<string, mixed>  $event
     * @param  array<string, mixed>  $playerEvents
     */
    private function inferGoalType(array $event, array $playerEvents, string $primaryId): string
    {
        $eventId = trim((string) ($event['id'] ?? ''));
        $minute = $this->eventMinute($event);
        foreach (is_array($playerEvents['scorers'] ?? null) ? $playerEvents['scorers'] : [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $rowId = trim((string) ($row['id'] ?? ''));
            if ($eventId !== '' && $rowId === $eventId) {
                return strtoupper((string) ($row['goalType'] ?? 'SCORED'));
            }
            $pid = trim((string) ($row['player']['id'] ?? ''));
            $rowMinute = (int) ($row['time']['minute'] ?? 0);
            if ($primaryId !== '' && $pid === $primaryId && $rowMinute === $minute) {
                return strtoupper((string) ($row['goalType'] ?? 'SCORED'));
            }
        }

        return 'SCORED';
    }

    /**
     * @param  list<string>  $types
     */
    private function cardCode(array $types): string
    {
        $hasYellow = false;
        $hasRed = false;
        $hasSecondYellow = false;
        foreach ($types as $type) {
            $type = strtoupper($type);
            if ($type === 'YELLOW_CARD') {
                $hasYellow = true;
            } elseif ($type === 'SECOND_YELLOW' || $type === 'SECOND_YELLOW_CARD') {
                $hasSecondYellow = true;
            } elseif ($type === 'RED_CARD') {
                $hasRed = true;
            }
        }
        if ($hasSecondYellow || ($hasYellow && $hasRed)) {
            return 'YR';
        }
        if ($hasRed) {
            return 'R';
        }
        if ($hasYellow) {
            return 'Y';
        }

        return '0';
    }

    private function isDismissalCard(string $type): bool
    {
        return in_array(strtoupper($type), ['RED_CARD', 'SECOND_YELLOW', 'SECOND_YELLOW_CARD'], true);
    }

    private function earliestPositiveMinute(int $current, int $candidate): int
    {
        if ($candidate <= 0) {
            return $current;
        }
        if ($current <= 0) {
            return $candidate;
        }

        return min($current, $candidate);
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private function finalizePlayingWindow(int $rawIn, int $rawOut, int $matchMinutes): array
    {
        $in = $rawIn > 0 ? $rawIn : 1;
        $out = $rawOut > 0 ? $rawOut : $matchMinutes;
        if ($out < $in) {
            $out = $in;
        }
        $minutes = $out - $in + 1;
        if ($minutes > $matchMinutes) {
            $minutes = $matchMinutes;
        }

        return [$in, $out, $minutes];
    }
}
