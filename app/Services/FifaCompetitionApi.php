<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Competition-scoped FIFA API facade.
 *
 * Instantiated from ffb_league.league_fifa_competition_identifier, e.g.
 * idCompetition=17&idSeason=285023
 */
class FifaCompetitionApi
{
    public function __construct(
        public readonly int $competitionId,
        public readonly int $seasonId,
        private readonly FifaCompApiClient $client = new FifaCompApiClient,
        private readonly string $rawIdentifier = '',
        private readonly string $language = 'de',
    ) {
        if ($this->competitionId <= 0) {
            throw new InvalidArgumentException('idCompetition ist erforderlich.');
        }
        if ($this->seasonId <= 0) {
            throw new InvalidArgumentException('idSeason ist erforderlich.');
        }
    }

    public static function fromIdentifier(string $identifier, ?FifaCompApiClient $client = null): self
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            throw new InvalidArgumentException('FIFA-Competition-Identifier ist leer.');
        }

        $params = [];
        parse_str($identifier, $params);

        $competitionId = (int) ($params['idCompetition'] ?? ($params['competitionId'] ?? 0));
        $seasonId = (int) ($params['idSeason'] ?? ($params['seasonId'] ?? 0));

        return new self(
            competitionId: $competitionId,
            seasonId: $seasonId,
            client: $client ?? new FifaCompApiClient,
            rawIdentifier: $identifier,
        );
    }

    public function identifier(): string
    {
        if ($this->rawIdentifier !== '') {
            return $this->rawIdentifier;
        }

        return 'idCompetition='.$this->competitionId.'&idSeason='.$this->seasonId;
    }

    /**
     * @return list<array{
     *     fifa_id: string,
     *     team_code: string,
     *     country_code: string,
     *     name_de: string,
     *     name_en: string,
     *     international_name: string
     * }>
     */
    public function teams(): array
    {
        $rows = [];
        foreach ($this->client->competitionTeams($this->seasonId, $this->language) as $team) {
            $mapped = $this->mapTeam($team);
            if ($mapped !== null) {
                $rows[] = $mapped;
            }
        }

        usort(
            $rows,
            static fn (array $a, array $b): int => strcasecmp(
                $a['name_de'] !== '' ? $a['name_de'] : $a['name_en'],
                $b['name_de'] !== '' ? $b['name_de'] : $b['name_en'],
            )
        );

        return $rows;
    }

    /**
     * @return list<array{
     *     fifa_match_id: string,
     *     fifa_stage_id: string,
     *     home_fifa_id: string,
     *     away_fifa_id: string,
     *     home_abbr: string,
     *     away_abbr: string,
     *     home_name_de: string,
     *     away_name_de: string,
     *     date: string
     * }>
     */
    public function matches(): array
    {
        $rows = [];
        foreach ($this->client->calendarMatches(
            $this->competitionId,
            $this->seasonId,
            500,
            $this->language,
        ) as $match) {
            $mapped = $this->mapMatch($match);
            if ($mapped !== null) {
                $rows[] = $mapped;
            }
        }

        return $rows;
    }

    /**
     * @return list<array{
     *     fifa_player_id: string,
     *     fifa_team_id: string,
     *     name: string,
     *     first_name: string,
     *     last_name: string,
     *     position: string,
     *     number: int
     * }>
     */
    public function players(string $fifaTeamId): array
    {
        $fifaTeamId = trim($fifaTeamId);
        if ($fifaTeamId === '' || ! ctype_digit($fifaTeamId)) {
            return [];
        }

        $rows = [];
        foreach ($this->client->teamSquad(
            (int) $fifaTeamId,
            $this->competitionId,
            $this->seasonId,
            $this->language,
        ) as $player) {
            $mapped = $this->mapPlayer($player, $fifaTeamId);
            if ($mapped !== null) {
                $rows[] = $mapped;
            }
        }

        usort(
            $rows,
            static function (array $a, array $b): int {
                $numCmp = $a['number'] <=> $b['number'];
                if ($numCmp !== 0) {
                    return $numCmp;
                }

                return strcasecmp($a['name'], $b['name']);
            }
        );

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $match
     * @return array{
     *     fifa_match_id: string,
     *     fifa_stage_id: string,
     *     home_fifa_id: string,
     *     away_fifa_id: string,
     *     home_abbr: string,
     *     away_abbr: string,
     *     home_name_de: string,
     *     away_name_de: string,
     *     date: string
     * }|null
     */
    private function mapMatch(array $match): ?array
    {
        $matchId = trim((string) ($match['IdMatch'] ?? ''));
        $stageId = trim((string) ($match['IdStage'] ?? ''));
        $home = is_array($match['Home'] ?? null) ? $match['Home'] : [];
        $away = is_array($match['Away'] ?? null) ? $match['Away'] : [];
        $homeId = trim((string) ($home['IdTeam'] ?? ''));
        $awayId = trim((string) ($away['IdTeam'] ?? ''));
        if ($matchId === '' || $stageId === '' || $homeId === '' || $awayId === '') {
            return null;
        }

        $dateRaw = trim((string) ($match['Date'] ?? ''));
        $dateTs = $dateRaw !== '' ? strtotime($dateRaw) : false;
        $date = $dateTs ? date('Y-m-d', $dateTs) : '';
        if ($date === '') {
            return null;
        }

        return [
            'fifa_match_id' => $matchId,
            'fifa_stage_id' => $stageId,
            'home_fifa_id' => $homeId,
            'away_fifa_id' => $awayId,
            'home_abbr' => strtoupper(trim((string) ($home['Abbreviation'] ?? ($home['IdCountry'] ?? '')))),
            'away_abbr' => strtoupper(trim((string) ($away['Abbreviation'] ?? ($away['IdCountry'] ?? '')))),
            'home_name_de' => $this->localizedText($home['TeamName'] ?? null),
            'away_name_de' => $this->localizedText($away['TeamName'] ?? null),
            'date' => $date,
        ];
    }

    /**
     * @param  array<string, mixed>  $team
     * @return array{
     *     fifa_id: string,
     *     team_code: string,
     *     country_code: string,
     *     name_de: string,
     *     name_en: string,
     *     international_name: string
     * }|null
     */
    private function mapTeam(array $team): ?array
    {
        $fifaId = trim((string) ($team['IdTeam'] ?? ''));
        if ($fifaId === '') {
            return null;
        }

        $name = $this->localizedText($team['Name'] ?? null);
        $short = trim((string) ($team['ShortClubName'] ?? ''));
        $code = strtoupper(trim((string) ($team['Abbreviation'] ?? '')));
        $country = strtoupper(trim((string) ($team['IdCountry'] ?? '')));
        if ($code === '') {
            $code = $country;
        }

        $display = $name !== '' ? $name : $short;

        return [
            'fifa_id' => $fifaId,
            'team_code' => $code,
            'country_code' => $country !== '' ? $country : $code,
            'name_de' => $display,
            'name_en' => $short !== '' ? $short : $display,
            'international_name' => $short !== '' ? $short : $display,
        ];
    }

    /**
     * @param  array<string, mixed>  $player
     * @return array{
     *     fifa_player_id: string,
     *     fifa_team_id: string,
     *     name: string,
     *     first_name: string,
     *     last_name: string,
     *     position: string,
     *     number: int
     * }|null
     */
    private function mapPlayer(array $player, string $fallbackTeamId): ?array
    {
        $fifaPlayerId = trim((string) ($player['IdPlayer'] ?? ''));
        if ($fifaPlayerId === '') {
            return null;
        }

        $fifaTeamId = trim((string) ($player['IdTeam'] ?? $fallbackTeamId));
        $fullRaw = $this->localizedText($player['PlayerName'] ?? null);
        $shortRaw = $this->localizedText($player['ShortName'] ?? null);
        if ($fullRaw === '' && $shortRaw === '') {
            return null;
        }

        [$firstName, $lastName] = $this->splitFifaPlayerName($fullRaw, $shortRaw);
        $fullName = trim($firstName.' '.$lastName);
        if ($fullName === '') {
            return null;
        }

        $position = match ((int) ($player['Position'] ?? -1)) {
            0 => 'GK',
            1 => 'DF',
            2 => 'MF',
            3 => 'FW',
            default => match (strtoupper(trim((string) (
                $this->localizedText($player['PositionLocalized'] ?? null)
            )))) {
                'GOALKEEPER' => 'GK',
                'DEFENDER' => 'DF',
                'MIDFIELDER' => 'MF',
                'FORWARD', 'STRIKER' => 'FW',
                default => 'DF',
            },
        };

        return [
            'fifa_player_id' => $fifaPlayerId,
            'fifa_team_id' => $fifaTeamId,
            'name' => $fullName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'position' => $position,
            'number' => (int) ($player['JerseyNum'] ?? 0),
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitFifaPlayerName(string $fullRaw, string $shortRaw): array
    {
        $short = trim($shortRaw);
        $full = trim($fullRaw);

        if ($short !== '') {
            $lastName = $this->titleCasePersonName($short);
            $first = $full;
            if ($full !== '') {
                $parts = preg_split('/\s+/u', $full) ?: [];
                $parts = array_values(array_filter($parts, static fn (string $p): bool => $p !== ''));
                if ($parts !== []) {
                    $lastToken = (string) end($parts);
                    if ($this->foldAscii($lastToken) === $this->foldAscii($short)
                        || $this->isAllCapsToken($lastToken)) {
                        array_pop($parts);
                    }
                    $first = implode(' ', $parts);
                }
            }
            $firstName = $this->titleCasePersonName($first !== '' ? $first : $short);

            return [$firstName, $lastName];
        }

        $parts = preg_split('/\s+/u', $full) ?: [];
        $parts = array_values(array_filter($parts, static fn (string $p): bool => $p !== ''));
        if ($parts === []) {
            return ['', ''];
        }
        if (count($parts) === 1) {
            $single = $this->titleCasePersonName($parts[0]);

            return [$single, $single];
        }

        $last = (string) array_pop($parts);

        return [
            $this->titleCasePersonName(implode(' ', $parts)),
            $this->titleCasePersonName($last),
        ];
    }

    private function titleCasePersonName(string $value): string
    {
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
        if ($value === '') {
            return '';
        }

        return mb_convert_case(mb_strtolower($value), MB_CASE_TITLE, 'UTF-8');
    }

    private function isAllCapsToken(string $value): bool
    {
        $letters = preg_replace('/[^\p{L}]+/u', '', $value) ?? '';
        if ($letters === '') {
            return false;
        }

        return mb_strtoupper($letters) === $letters && mb_strtolower($letters) !== $letters;
    }

    private function foldAscii(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $map = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', 'æ' => 'ae', 'ø' => 'oe'];
        $value = strtr($value, $map);
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if (is_string($converted) && $converted !== '') {
            $value = $converted;
        }

        return preg_replace('/[^a-z0-9]+/i', '', $value) ?? $value;
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
}
