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
}
