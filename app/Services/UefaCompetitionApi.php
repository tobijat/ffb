<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Competition-scoped UEFA API facade.
 *
 * Instantiated from ffb_league.league_uefa_competition_identifier, e.g.
 * competitionId=2014&seasonYear=2027&competitionPhase=TOURNAMENT
 * or competitionPhase=TOURNAMENT,QUALIFYING
 *
 * competitionId and seasonYear are required. When competitionPhase is omitted
 * (or empty), all round phases of that competition/season are included.
 */
class UefaCompetitionApi
{
    /**
     * @param  list<string>  $phases  Uppercase phase names; empty = all phases
     */
    public function __construct(
        public readonly int $competitionId,
        public readonly int $seasonYear,
        public readonly array $phases = [],
        private readonly UefaCompApiClient $client = new UefaCompApiClient,
        private readonly string $rawIdentifier = '',
    ) {
        if ($this->competitionId <= 0) {
            throw new InvalidArgumentException('competitionId ist erforderlich.');
        }
        if ($this->seasonYear <= 0) {
            throw new InvalidArgumentException('seasonYear ist erforderlich.');
        }
    }

    public static function fromIdentifier(string $identifier, ?UefaCompApiClient $client = null): self
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            throw new InvalidArgumentException('UEFA-Competition-Identifier ist leer.');
        }

        $params = [];
        parse_str($identifier, $params);

        $competitionId = (int) ($params['competitionId'] ?? 0);
        $seasonYear = (int) ($params['seasonYear'] ?? 0);
        $phaseRaw = trim((string) ($params['competitionPhase'] ?? ''));

        $phases = [];
        if ($phaseRaw !== '') {
            foreach (explode(',', $phaseRaw) as $phase) {
                $phase = strtoupper(trim($phase));
                if ($phase !== '') {
                    $phases[$phase] = true;
                }
            }
        }

        return new self(
            competitionId: $competitionId,
            seasonYear: $seasonYear,
            phases: array_keys($phases),
            client: $client ?? new UefaCompApiClient,
            rawIdentifier: $identifier,
        );
    }

    public function identifier(): string
    {
        if ($this->rawIdentifier !== '') {
            return $this->rawIdentifier;
        }

        $parts = [
            'competitionId='.$this->competitionId,
            'seasonYear='.$this->seasonYear,
        ];
        if ($this->phases !== []) {
            $parts[] = 'competitionPhase='.implode(',', $this->phases);
        }

        return implode('&', $parts);
    }

    /**
     * Team IDs enrolled in rounds matching the configured phases (union).
     *
     * @return list<string>
     */
    public function enrolledTeamIds(): array
    {
        $ids = [];
        foreach ($this->client->rounds($this->competitionId, $this->seasonYear) as $round) {
            if (! $this->roundMatchesPhases($round)) {
                continue;
            }
            $teams = $round['teams'] ?? [];
            if (! is_array($teams)) {
                continue;
            }
            foreach ($teams as $teamId) {
                $teamId = trim((string) $teamId);
                if ($teamId !== '') {
                    $ids[$teamId] = true;
                }
            }
        }

        return array_map(static fn (string|int $id): string => (string) $id, array_keys($ids));
    }

    /**
     * Full team payloads for the configured competition subset.
     *
     * @return list<array{
     *     uefa_id: string,
     *     team_code: string,
     *     country_code: string,
     *     name_de: string,
     *     name_en: string,
     *     international_name: string
     * }>
     */
    public function teams(): array
    {
        $enrolledIds = $this->enrolledTeamIds();
        if ($enrolledIds === []) {
            return [];
        }

        $rows = [];
        foreach ($this->client->teams($enrolledIds) as $team) {
            $mapped = $this->mapTeam($team);
            if ($mapped !== null) {
                $rows[] = $mapped;
            }
        }

        usort(
            $rows,
            static fn (array $a, array $b): int => strcasecmp($a['name_de'] !== '' ? $a['name_de'] : $a['name_en'], $b['name_de'] !== '' ? $b['name_de'] : $b['name_en'])
        );

        return $rows;
    }

    /**
     * Mapped matches for the configured competition / phase subset.
     *
     * @return list<array{
     *     uefa_match_id: string,
     *     home_uefa_id: string,
     *     away_uefa_id: string,
     *     home_name_de: string,
     *     away_name_de: string,
     *     date: string,
     *     matchday: int,
     *     round_phase: string,
     *     round_order: int
     * }>
     */
    public function matches(): array
    {
        $rows = [];
        foreach ($this->client->matches($this->competitionId, $this->seasonYear) as $match) {
            $mapped = $this->mapMatch($match);
            if ($mapped === null) {
                continue;
            }
            if ($this->phases !== [] && ! in_array($mapped['round_phase'], $this->phases, true)) {
                continue;
            }
            $rows[] = $mapped;
        }

        usort(
            $rows,
            static function (array $a, array $b): int {
                $dateCmp = strcmp($a['date'], $b['date']);
                if ($dateCmp !== 0) {
                    return $dateCmp;
                }

                return strcmp($a['uefa_match_id'], $b['uefa_match_id']);
            }
        );

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $round
     */
    private function roundMatchesPhases(array $round): bool
    {
        if ($this->phases === []) {
            return true;
        }

        $phase = strtoupper(trim((string) ($round['phase'] ?? '')));

        return $phase !== '' && in_array($phase, $this->phases, true);
    }

    /**
     * @param  array<string, mixed>  $team
     * @return array{
     *     uefa_id: string,
     *     team_code: string,
     *     country_code: string,
     *     name_de: string,
     *     name_en: string,
     *     international_name: string
     * }|null
     */
    private function mapTeam(array $team): ?array
    {
        $uefaId = trim((string) ($team['id'] ?? ''));
        if ($uefaId === '') {
            return null;
        }

        if (strtoupper(trim((string) ($team['teamTypeDetail'] ?? ''))) === 'FAKE') {
            return null;
        }

        $translations = is_array($team['translations'] ?? null) ? $team['translations'] : [];
        $countryName = is_array($translations['countryName'] ?? null) ? $translations['countryName'] : [];
        $displayName = is_array($translations['displayName'] ?? null) ? $translations['displayName'] : [];

        $nameDe = trim((string) ($countryName['DE'] ?? ($displayName['DE'] ?? '')));
        $nameEn = trim((string) ($countryName['EN'] ?? ($displayName['EN'] ?? '')));
        $international = trim((string) ($team['internationalName'] ?? ''));

        return [
            'uefa_id' => $uefaId,
            'team_code' => strtoupper(trim((string) ($team['teamCode'] ?? ''))),
            'country_code' => strtoupper(trim((string) ($team['countryCode'] ?? ''))),
            'name_de' => $nameDe !== '' ? $nameDe : ($international !== '' ? $international : $nameEn),
            'name_en' => $nameEn !== '' ? $nameEn : $international,
            'international_name' => $international,
        ];
    }

    /**
     * @param  array<string, mixed>  $match
     * @return array{
     *     uefa_match_id: string,
     *     home_uefa_id: string,
     *     away_uefa_id: string,
     *     home_name_de: string,
     *     away_name_de: string,
     *     date: string,
     *     matchday: int,
     *     round_phase: string,
     *     round_order: int
     * }|null
     */
    private function mapMatch(array $match): ?array
    {
        $uefaMatchId = trim((string) ($match['id'] ?? ''));
        if ($uefaMatchId === '') {
            return null;
        }

        $home = is_array($match['homeTeam'] ?? null) ? $match['homeTeam'] : [];
        $away = is_array($match['awayTeam'] ?? null) ? $match['awayTeam'] : [];
        if (($home['isPlaceHolder'] ?? false) || ($away['isPlaceHolder'] ?? false)) {
            return null;
        }
        if (strtoupper(trim((string) ($home['teamTypeDetail'] ?? ''))) === 'FAKE'
            || strtoupper(trim((string) ($away['teamTypeDetail'] ?? ''))) === 'FAKE') {
            return null;
        }

        $homeId = trim((string) ($home['id'] ?? ''));
        $awayId = trim((string) ($away['id'] ?? ''));
        if ($homeId === '' || $awayId === '') {
            return null;
        }

        $kickOff = is_array($match['kickOffTime'] ?? null) ? $match['kickOffTime'] : [];
        $date = trim((string) ($kickOff['date'] ?? ''));
        if ($date === '' && isset($kickOff['dateTime'])) {
            $dateTime = trim((string) $kickOff['dateTime']);
            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $dateTime, $m) === 1) {
                $date = $m[1];
            }
        }
        if ($date === '' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) !== 1) {
            return null;
        }

        $round = is_array($match['round'] ?? null) ? $match['round'] : [];
        $matchday = is_array($match['matchday'] ?? null) ? $match['matchday'] : [];
        $phase = strtoupper(trim((string) ($match['competitionPhase']
            ?? ($matchday['phase'] ?? ($round['phase'] ?? '')))));

        return [
            'uefa_match_id' => $uefaMatchId,
            'home_uefa_id' => $homeId,
            'away_uefa_id' => $awayId,
            'home_name_de' => $this->teamDisplayName($home),
            'away_name_de' => $this->teamDisplayName($away),
            'date' => $date,
            'matchday' => (int) ($matchday['sequenceNumber'] ?? 0),
            'round_phase' => $phase,
            'round_order' => (int) ($round['orderInCompetition'] ?? 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $team
     */
    private function teamDisplayName(array $team): string
    {
        $translations = is_array($team['translations'] ?? null) ? $team['translations'] : [];
        $countryName = is_array($translations['countryName'] ?? null) ? $translations['countryName'] : [];
        $displayName = is_array($translations['displayName'] ?? null) ? $translations['displayName'] : [];

        $nameDe = trim((string) ($countryName['DE'] ?? ($displayName['DE'] ?? '')));
        if ($nameDe !== '') {
            return $nameDe;
        }

        $international = trim((string) ($team['internationalName'] ?? ''));
        if ($international !== '') {
            return $international;
        }

        return trim((string) ($countryName['EN'] ?? ($displayName['EN'] ?? '')));
    }
}
