<?php

namespace App\Services;

/**
 * Maps FIFA live match (+ optional timeline) into the Weltfussball scrape player shape
 * consumed by AdminMatchdataService::mapScrapedSide.
 */
class FifaMatchStatsMapper
{
    private const PLAYER_STATUS_STARTER = 1;

    private const GOAL_TYPE_PENALTY = 1;

    private const GOAL_TYPE_OPEN_PLAY = 2;

    private const GOAL_TYPE_OWN = 3;

    private const CARD_YELLOW = 1;

    /** Straight red card (FIFA Bookings.Card). */
    private const CARD_RED = 2;

    /** Second yellow / yellow-red (FIFA Bookings.Card). */
    private const CARD_SECOND_YELLOW = 3;

    private const PERIOD_PENALTY_SHOOTOUT = 11;

    private const EVENT_PENALTY_AWARDED = 6;

    private const EVENT_PENALTY_GOAL = 41;

    private const EVENT_GOAL_PREVENTION = 57;

    /** Open-play / shootout miss that was saved by the goalkeeper. */
    private const EVENT_PENALTY_MISSED_SAVED = 60;

    /** Open-play miss off target (no save credited). */
    private const EVENT_PENALTY_MISSED_OFF_TARGET = 65;

    private const EVENT_PS_MISS_A = 51;

    /**
     * @param  array<string, mixed>  $live
     * @param  array<string, mixed>|null  $timeline
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
     *     fifa_match_id: string
     * }
     */
    public function map(array $live, ?array $timeline = null): array
    {
        $home = is_array($live['HomeTeam'] ?? null) ? $live['HomeTeam'] : [];
        $away = is_array($live['AwayTeam'] ?? null) ? $live['AwayTeam'] : [];
        $homeGoals = is_array($home['Goals'] ?? null) ? $home['Goals'] : [];
        $awayGoals = is_array($away['Goals'] ?? null) ? $away['Goals'] : [];
        $matchMinutes = $this->resolveMatchMinutes($live, $homeGoals, $awayGoals);
        $events = is_array($timeline['Event'] ?? null) ? $timeline['Event'] : [];

        $homePlayers = $this->buildSidePlayers(
            $home,
            $homeGoals,
            $awayGoals,
            $events,
            $matchMinutes,
        );
        $guestPlayers = $this->buildSidePlayers(
            $away,
            $awayGoals,
            $homeGoals,
            $events,
            $matchMinutes,
        );

        [$homePs, $awayPs] = $this->resolvePenaltyScores($live);

        return [
            'match_minutes' => $matchMinutes,
            'home' => $homePlayers,
            'guest' => $guestPlayers,
            'result' => [
                'homescore' => (int) ($home['Score'] ?? 0),
                'guestscore' => (int) ($away['Score'] ?? 0),
                'homescore_penalty' => $homePs,
                'guestscore_penalty' => $awayPs,
            ],
            'fifa_match_id' => trim((string) ($live['IdMatch'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $live
     * @return array{0: int, 1: int}
     */
    private function resolvePenaltyScores(array $live): array
    {
        $resultType = (int) ($live['ResultType'] ?? 0);
        $homePs = (int) ($live['HomeTeamPenaltyScore'] ?? 0);
        $awayPs = (int) ($live['AwayTeamPenaltyScore'] ?? 0);
        // ResultType 2 = decided on penalties (possibly after ET).
        if ($resultType === 2 || $homePs > 0 || $awayPs > 0) {
            return [$homePs, $awayPs];
        }

        return [-1, -1];
    }

    /**
     * @param  array<string, mixed>  $live
     * @param  list<array<string, mixed>>  $homeGoals
     * @param  list<array<string, mixed>>  $awayGoals
     */
    private function resolveMatchMinutes(array $live, array $homeGoals, array $awayGoals): int
    {
        if ((int) ($live['ResultType'] ?? 0) === 2) {
            return 120;
        }
        if (
            ($live['FirstHalfExtraTime'] ?? null) !== null
            || ($live['SecondHalfExtraTime'] ?? null) !== null
        ) {
            return 120;
        }

        foreach (array_merge($homeGoals, $awayGoals) as $goal) {
            if (! is_array($goal)) {
                continue;
            }
            $period = (int) ($goal['Period'] ?? 0);
            // Period 9 = extra time; 11 = penalty shootout (implies ET was played).
            if ($period === 9 || $period === self::PERIOD_PENALTY_SHOOTOUT) {
                return 120;
            }
            if ($this->parseMinute((string) ($goal['Minute'] ?? '')) > 90) {
                return 120;
            }
        }

        return 90;
    }

    /**
     * @param  array<string, mixed>  $side
     * @param  list<array<string, mixed>>  $sideGoals
     * @param  list<array<string, mixed>>  $opponentGoals
     * @param  list<array<string, mixed>>  $events
     * @return list<array<string, mixed>>
     */
    private function buildSidePlayers(
        array $side,
        array $sideGoals,
        array $opponentGoals,
        array $events,
        int $matchMinutes,
    ): array {
        $teamId = trim((string) ($side['IdTeam'] ?? ''));
        $players = is_array($side['Players'] ?? null) ? $side['Players'] : [];
        $bookings = is_array($side['Bookings'] ?? null) ? $side['Bookings'] : [];
        $subs = is_array($side['Substitutions'] ?? null) ? $side['Substitutions'] : [];

        /** @var array<string, array{name: string, starter: bool}> $roster */
        $roster = [];
        foreach ($players as $player) {
            if (! is_array($player)) {
                continue;
            }
            $id = trim((string) ($player['IdPlayer'] ?? ''));
            if ($id === '') {
                continue;
            }
            $roster[$id] = [
                'name' => $this->playerName($player),
                'starter' => (int) ($player['Status'] ?? 0) === self::PLAYER_STATUS_STARTER,
            ];
        }

        /** @var array<string, list<int>> $subOut */
        $subOut = [];
        /** @var array<string, list<int>> $subIn */
        $subIn = [];
        foreach ($subs as $sub) {
            if (! is_array($sub)) {
                continue;
            }
            $minute = $this->parseMinute((string) ($sub['Minute'] ?? ''));
            $off = trim((string) ($sub['IdPlayerOff'] ?? ''));
            $on = trim((string) ($sub['IdPlayerOn'] ?? ''));
            if ($off !== '' && isset($roster[$off])) {
                $subOut[$off][] = $minute;
            }
            if ($on !== '' && isset($roster[$on])) {
                $subIn[$on][] = $minute;
            }
        }

        /** @var array<string, list<int>> $goals */
        $goals = [];
        /** @var array<string, list<int>> $owngoals */
        $owngoals = [];
        /** @var array<string, int> $assists */
        $assists = [];
        /** @var array<string, int> $psHit */
        $psHit = [];
        /** @var array<string, int> $psLost */
        $psLost = [];
        /** @var array<string, int> $penaltiesLost */
        $penaltiesLost = [];
        /** @var array<string, int> $penaltiesSaved */
        $penaltiesSaved = [];

        [$openPlayLost, $openPlaySaved] = $this->resolveOpenPlayPenaltyOutcomes($events);
        foreach ($openPlayLost as $pid => $count) {
            if (isset($roster[$pid])) {
                $penaltiesLost[$pid] = ($penaltiesLost[$pid] ?? 0) + $count;
            }
        }
        foreach ($openPlaySaved as $pid => $count) {
            if (isset($roster[$pid])) {
                $penaltiesSaved[$pid] = ($penaltiesSaved[$pid] ?? 0) + $count;
            }
        }

        foreach ($sideGoals as $goal) {
            if (! is_array($goal)) {
                continue;
            }
            $type = (int) ($goal['Type'] ?? 0);
            $period = (int) ($goal['Period'] ?? 0);
            $pid = trim((string) ($goal['IdPlayer'] ?? ''));
            $minute = $this->parseMinute((string) ($goal['Minute'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            if ($type === self::GOAL_TYPE_OWN) {
                continue;
            }
            if ($period === self::PERIOD_PENALTY_SHOOTOUT) {
                $psHit[$pid] = ($psHit[$pid] ?? 0) + 1;

                continue;
            }
            if ($type === self::GOAL_TYPE_PENALTY || $type === self::GOAL_TYPE_OPEN_PLAY || $type === 0) {
                $goals[$pid][] = $minute;
                $assistId = trim((string) ($goal['IdAssistPlayer'] ?? ''));
                if ($assistId !== '' && isset($roster[$assistId]) && $assistId !== $pid) {
                    $assists[$assistId] = ($assists[$assistId] ?? 0) + 1;
                }
            }
        }

        // Own goals are listed on the benefiting side; IdPlayer belongs to the conceding team.
        foreach (array_merge($sideGoals, $opponentGoals) as $goal) {
            if (! is_array($goal)) {
                continue;
            }
            if ((int) ($goal['Type'] ?? 0) !== self::GOAL_TYPE_OWN) {
                continue;
            }
            $pid = trim((string) ($goal['IdPlayer'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            $owngoals[$pid][] = $this->parseMinute((string) ($goal['Minute'] ?? ''));
        }

        /** @var array<string, list<string>> $cardEvents */
        $cardEvents = [];
        /** @var array<string, int> $dismissedAt */
        $dismissedAt = [];
        foreach ($bookings as $booking) {
            if (! is_array($booking)) {
                continue;
            }
            $pid = trim((string) ($booking['IdPlayer'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            $card = (int) ($booking['Card'] ?? 0);
            $minute = $this->parseMinute((string) ($booking['Minute'] ?? ''));
            if ($card === self::CARD_YELLOW) {
                $cardEvents[$pid][] = 'YELLOW_CARD';
            } elseif ($card === self::CARD_SECOND_YELLOW) {
                $cardEvents[$pid][] = 'SECOND_YELLOW';
                if ($minute > 0) {
                    $dismissedAt[$pid] = $this->earliestPositiveMinute($dismissedAt[$pid] ?? 0, $minute);
                }
            } elseif ($card === self::CARD_RED) {
                $cardEvents[$pid][] = 'RED_CARD';
                if ($minute > 0) {
                    $dismissedAt[$pid] = $this->earliestPositiveMinute($dismissedAt[$pid] ?? 0, $minute);
                }
            }
        }

        foreach ($events as $event) {
            if (! is_array($event)) {
                continue;
            }
            if ((int) ($event['Period'] ?? 0) !== self::PERIOD_PENALTY_SHOOTOUT) {
                continue;
            }
            $eventTeam = trim((string) ($event['IdTeam'] ?? ''));
            if ($teamId !== '' && $eventTeam !== '' && $eventTeam !== $teamId) {
                continue;
            }
            $pid = trim((string) ($event['IdPlayer'] ?? ''));
            if ($pid === '' || ! isset($roster[$pid])) {
                continue;
            }
            $type = (int) ($event['Type'] ?? 0);
            if ($type === self::EVENT_PS_MISS_A || $type === self::EVENT_PENALTY_MISSED_SAVED) {
                $psLost[$pid] = ($psLost[$pid] ?? 0) + 1;
            }
        }

        $playersOut = [];
        foreach ($roster as $fifaId => $meta) {
            $rawIn = 0;
            $rawOut = 0;
            if ($meta['starter']) {
                $rawIn = 1;
                if (isset($subOut[$fifaId][0])) {
                    $rawOut = (int) $subOut[$fifaId][0];
                }
            } else {
                if (! isset($subIn[$fifaId][0])) {
                    continue;
                }
                $rawIn = (int) $subIn[$fifaId][0];
                if (isset($subOut[$fifaId][0])) {
                    $rawOut = (int) $subOut[$fifaId][0];
                }
            }

            if (isset($dismissedAt[$fifaId])) {
                $rawOut = $this->earliestPositiveMinute($rawOut, $dismissedAt[$fifaId]);
            }

            [$in, $out, $minutes] = $this->finalizePlayingWindow($rawIn, $rawOut, $matchMinutes);
            $goalMinutes = array_values(array_unique($goals[$fifaId] ?? []));
            sort($goalMinutes);
            $ownMinutes = array_values(array_unique($owngoals[$fifaId] ?? []));
            sort($ownMinutes);

            $playersOut[] = [
                'player_name' => $meta['name'],
                'player_fifa_id' => $fifaId,
                'player_uefa_id' => '',
                'player_change_in' => $in,
                'player_change_out' => $out,
                'player_cards' => $this->cardCode($cardEvents[$fifaId] ?? []),
                'player_num_goals' => count($goalMinutes),
                'player_goal' => $goalMinutes === [] ? '0' : implode(';', $goalMinutes),
                'player_num_owngoals' => count($ownMinutes),
                'player_owngoal' => $ownMinutes === [] ? '0' : implode(';', $ownMinutes),
                'player_num_assists' => (int) ($assists[$fifaId] ?? 0),
                'player_penalties_lost' => (int) ($penaltiesLost[$fifaId] ?? 0),
                'player_penalties_saved' => (int) ($penaltiesSaved[$fifaId] ?? 0),
                'player_penalties_hit' => (int) ($psHit[$fifaId] ?? 0),
                'player_penalties_fail' => (int) ($psLost[$fifaId] ?? 0),
                'player_minutes' => $minutes,
            ];
        }

        return $playersOut;
    }

    /**
     * Detect open-play (non-shootout) penalty outcomes from the timeline.
     *
     * - Type 6 (awarded) then Type 41 (penalty goal) → scored (ignored here)
     * - Type 6 then Type 57 (goal prevention) → taker missed, preventing player saved
     * - Type 6 without Type 41 → taker missed
     * - Type 60 (penalty missed / saved) → taker missed, IdSubPlayer saved
     * - Type 65 (penalty missed off target) → taker missed only
     *
     * @param  list<array<string, mixed>>  $events
     * @return array{0: array<string, int>, 1: array<string, int>}
     */
    private function resolveOpenPlayPenaltyOutcomes(array $events): array
    {
        /** @var array<string, int> $lost */
        $lost = [];
        /** @var array<string, int> $saved */
        $saved = [];
        /** @var array<string, true> $creditedMissKeys */
        $creditedMissKeys = [];

        $count = count($events);
        for ($i = 0; $i < $count; $i++) {
            $event = $events[$i];
            if (! is_array($event)) {
                continue;
            }
            if ((int) ($event['Period'] ?? 0) === self::PERIOD_PENALTY_SHOOTOUT) {
                continue;
            }
            if ((int) ($event['Type'] ?? 0) !== self::EVENT_PENALTY_AWARDED) {
                continue;
            }

            $taker = trim((string) ($event['IdPlayer'] ?? ''));
            $gkHint = trim((string) ($event['IdSubPlayer'] ?? ''));
            $minute = $this->parseMinute((string) ($event['MatchMinute'] ?? ''));
            $outcome = 'miss';
            $saver = '';

            for ($j = $i + 1; $j < min($i + 40, $count); $j++) {
                $next = $events[$j];
                if (! is_array($next)) {
                    continue;
                }
                if ((int) ($next['Period'] ?? 0) === self::PERIOD_PENALTY_SHOOTOUT) {
                    continue;
                }
                $nextType = (int) ($next['Type'] ?? 0);
                if ($nextType === self::EVENT_PENALTY_AWARDED) {
                    break;
                }
                if ($nextType === self::EVENT_PENALTY_GOAL) {
                    $outcome = 'goal';
                    break;
                }
                if ($nextType === self::EVENT_GOAL_PREVENTION) {
                    $outcome = 'save';
                    $saver = trim((string) ($next['IdPlayer'] ?? ''));
                    if ($saver === '') {
                        $saver = $gkHint;
                    }
                    break;
                }
            }

            if ($outcome === 'goal') {
                continue;
            }
            if ($taker !== '') {
                $lost[$taker] = ($lost[$taker] ?? 0) + 1;
                $creditedMissKeys[$taker.'|'.$minute] = true;
            }
            if ($outcome === 'save' && $saver !== '') {
                $saved[$saver] = ($saved[$saver] ?? 0) + 1;
            }
        }

        for ($i = 0; $i < $count; $i++) {
            $event = $events[$i];
            if (! is_array($event)) {
                continue;
            }
            if ((int) ($event['Period'] ?? 0) === self::PERIOD_PENALTY_SHOOTOUT) {
                continue;
            }
            $type = (int) ($event['Type'] ?? 0);
            if ($type !== self::EVENT_PENALTY_MISSED_SAVED && $type !== self::EVENT_PENALTY_MISSED_OFF_TARGET) {
                continue;
            }

            $taker = trim((string) ($event['IdPlayer'] ?? ''));
            $minute = $this->parseMinute((string) ($event['MatchMinute'] ?? ''));
            $key = $taker.'|'.$minute;
            $alreadyCredited = $taker !== '' && isset($creditedMissKeys[$key]);

            if (! $alreadyCredited && $taker !== '') {
                $lost[$taker] = ($lost[$taker] ?? 0) + 1;
                $creditedMissKeys[$key] = true;
            }

            if ($type === self::EVENT_PENALTY_MISSED_SAVED) {
                $saver = trim((string) ($event['IdSubPlayer'] ?? ''));
                if ($saver !== '') {
                    $saved[$saver] = ($saved[$saver] ?? 0) + 1;
                }
            }
        }

        return [$lost, $saved];
    }

    /**
     * @param  array<string, mixed>  $player
     */
    private function playerName(array $player): string
    {
        $short = $this->localizedText($player['ShortName'] ?? null);
        $full = $this->localizedText($player['PlayerName'] ?? null);
        if ($short !== '') {
            return $short;
        }

        return $full;
    }

    private function localizedText(mixed $entries): string
    {
        if (! is_array($entries) || $entries === []) {
            return '';
        }

        $prefer = ['de-DE', 'de', 'en-GB', 'en'];
        $byLocale = [];
        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            $locale = trim((string) ($entry['Locale'] ?? ''));
            $description = trim((string) ($entry['Description'] ?? ''));
            if ($description === '') {
                continue;
            }
            if ($locale !== '') {
                $byLocale[$locale] = $description;
            } else {
                $byLocale[''] = $description;
            }
        }

        foreach ($prefer as $locale) {
            if (isset($byLocale[$locale])) {
                return $byLocale[$locale];
            }
        }

        return (string) (reset($byLocale) ?: '');
    }

    private function parseMinute(string $raw): int
    {
        $raw = trim($raw);
        if ($raw === '') {
            return 0;
        }
        if (preg_match('/^(\d+)/', $raw, $m) === 1) {
            return max(0, (int) $m[1]);
        }

        return 0;
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
