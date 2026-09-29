<?php

namespace App\Services;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\MatchroundOptions;
use App\Models\Playerteam;
use App\Models\Team;
use App\Support\Flag;
use App\Support\TeamShirt;
use Illuminate\Support\Carbon;

class AdminLeagueDashboardService
{
    private const DEFAULT_SYMBOL = 'symbol_game_na.png';

    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    /**
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array{symbol: string, name: string, link: string, style: string, image_dir: string}>,
     *     selected_league_id: int,
     *     selected_league: array{league_id: int, league_title: string, symbol_url: string}|null,
     *     sections: list<array<string, mixed>>
     * }
     */
    public function pagePayload(int $userId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagueId = (int) ($shell['selected_league_id'] ?? 0);

        return [
            ...$shell,
            'sections' => $this->sections($leagueId),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function sections(int $leagueId): array
    {
        return [
            $this->leagueSection($leagueId),
            $this->matchroundsSection($leagueId),
            $this->matchesSection($leagueId),
            $this->teamsSection($leagueId),
            $this->squadSection($leagueId),
            ['key' => 'playerprice', 'title' => 'Preis/Performance', 'ok' => false],
            ['key' => 'matchdata', 'title' => 'Spieldaten', 'ok' => false],
            ['key' => 'extremeteam', 'title' => 'Top&Flop', 'ok' => false],
            ['key' => 'score', 'title' => 'Score', 'ok' => false],
        ];
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool, options_overview?: list<array{title: string, items: list<array{label: string, value: string}>}>}>
     * }
     */
    private function leagueSection(int $leagueId): array
    {
        $league = $leagueId > 0
            ? League::query()->with('options')->find($leagueId)
            : null;

        $title = $league !== null
            ? 'Liga: '.$league->league_title
            : 'Liga';

        if ($league === null) {
            return [
                'key' => 'league',
                'title' => $title,
                'ok' => false,
                'checklist' => [],
            ];
        }

        $hasLogo = $this->leagueHasLogoFile($league);
        $isVisible = (bool) $league->league_visible;
        $scheduleOk = $this->leagueScheduleMatchesArchiveState($league);
        $hasOptions = $league->options !== null;
        $optionsOverview = $hasOptions
            ? $this->optionsOverview($league->options)
            : [];

        $checklist = [
            [
                'key' => 'logo',
                'label' => 'Logo vorhanden',
                'ok' => $hasLogo,
            ],
            [
                'key' => 'visible',
                'label' => 'Liga sichtbar',
                'ok' => $isVisible,
            ],
            [
                'key' => 'schedule',
                'label' => (bool) $league->league_archive
                    ? 'Archiviert und nur vergangene Spielrunden'
                    : 'Aktiv und aktuelle/zukünftige Spielrunden vorhanden',
                'ok' => $scheduleOk,
            ],
            [
                'key' => 'options',
                'label' => 'Liga-Optionen gesetzt',
                'ok' => $hasOptions,
                'options_overview' => $optionsOverview,
            ],
        ];

        $ok = $hasLogo && $isVisible && $scheduleOk && $hasOptions;

        return [
            'key' => 'league',
            'title' => $title,
            'ok' => $ok,
            'checklist' => $checklist,
        ];
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool}>,
     *     groups: list<array{key: string, title: string, rounds: list<array<string, mixed>>}>
     * }
     */
    private function matchroundsSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'matchrounds',
                'title' => 'Spielrunden',
                'ok' => false,
                'checklist' => [],
                'groups' => [],
            ];
        }

        $now = Carbon::now();
        $rounds = Matchround::query()
            ->with('options')
            ->withCount('matches')
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get();

        $grouped = [
            'current' => [],
            'future' => [],
            'past' => [],
        ];

        $checklist = [];
        $allRoundsHaveMatches = true;
        $hasActiveRound = false;

        foreach ($rounds as $round) {
            $period = $this->matchroundPeriod($round, $now);
            $matchCount = (int) ($round->matches_count ?? 0);
            $hasMatches = $matchCount > 0;
            $isActive = (int) $round->matchround_status === 1;
            $options = $round->options;
            $hasLineupOptions = $options !== null;

            if (! $hasMatches) {
                $allRoundsHaveMatches = false;
            }
            if ($isActive) {
                $hasActiveRound = true;
            }

            $title = (string) $round->matchround_title;
            $checklist[] = [
                'key' => 'round-matches-'.(int) $round->matchround_id,
                'label' => $title.': mindestens 1 Spiel',
                'ok' => $hasMatches,
            ];

            $grouped[$period][] = [
                'matchround_id' => (int) $round->matchround_id,
                'title' => $title,
                'startdate' => $this->formatMatchroundDate($round->matchround_startdate),
                'enddate' => $this->formatMatchroundDate($round->matchround_enddate),
                'match_count' => $matchCount,
                'has_matches' => $hasMatches,
                'active' => $isActive,
                'has_lineup_options' => $hasLineupOptions,
                'lineup_options' => $hasLineupOptions
                    ? $this->matchroundLineupOptionsOverview($options)
                    : [],
            ];
        }

        if ($rounds->isEmpty()) {
            $allRoundsHaveMatches = false;
        }

        $checklist[] = [
            'key' => 'active-round',
            'label' => 'Mindestens 1 aktive Spielrunde',
            'ok' => $hasActiveRound,
        ];

        $counts = [
            'current' => count($grouped['current']),
            'future' => count($grouped['future']),
            'past' => count($grouped['past']),
        ];

        $groups = [];
        foreach ([
            'current' => 'Aktuell',
            'future' => 'Zukünftig',
            'past' => 'Vergangen',
        ] as $key => $label) {
            $groupRounds = $grouped[$key];
            if ($key === 'past') {
                $groupRounds = array_reverse($groupRounds);
            }

            $groups[] = [
                'key' => $key,
                'title' => $label,
                'rounds' => $groupRounds,
            ];
        }

        $ok = $allRoundsHaveMatches && $hasActiveRound && $rounds->isNotEmpty();

        return [
            'key' => 'matchrounds',
            'title' => sprintf(
                'Spielrunden (aktuell: %d, zukünftig: %d, vergangen: %d)',
                $counts['current'],
                $counts['future'],
                $counts['past'],
            ),
            'ok' => $ok,
            'checklist' => $checklist,
            'groups' => $groups,
        ];
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool}>
     * }
     */
    private function matchesSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'matches',
                'title' => 'Spiele',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $roundCount = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->count();

        $rounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->get(['matchround_id', 'matchround_title', 'matchround_startdate', 'matchround_enddate'])
            ->keyBy(fn (Matchround $round): int => (int) $round->matchround_id);

        $matches = $rounds->isEmpty()
            ? collect()
            : MatchGame::query()
                ->with([
                    'homeTeam:team_id,team_name',
                    'guestTeam:team_id,team_name',
                ])
                ->whereIn('match_round', $rounds->keys()->all())
                ->orderBy('match_date')
                ->orderBy('match_id')
                ->get([
                    'match_id',
                    'match_round',
                    'match_hometeam_id',
                    'match_guestteam_id',
                    'match_date',
                    'match_homescore',
                    'match_guestscore',
                    'match_minutes',
                    'match_status',
                ]);

        $matchCount = $matches->count();
        $hasMatches = $matchCount > 0;
        $outsideRoundDates = [];
        $incompletePastMatches = [];
        $incompletePastWithStatus = [];
        $today = Carbon::now()->startOfDay();

        foreach ($matches as $match) {
            $round = $rounds->get((int) $match->match_round);
            if (! $this->matchDateWithinMatchround($match, $round)) {
                $outsideRoundDates[] = $this->matchListEntry($match, $round, $this->matchOutsideRoundDetail($match, $round));
            }

            if (! $this->matchIsAtLeastOneDayOld($match, $today)
                || $this->matchHasResultAndDuration($match)) {
                continue;
            }

            $missing = $this->matchIncompleteDetail($match);
            $status = trim((string) ($match->match_status ?? ''));

            if ($status !== '') {
                $incompletePastWithStatus[] = $this->matchListEntry(
                    $match,
                    $round,
                    ($missing !== '' ? $missing.' · ' : '').'Status: '.$status,
                );

                continue;
            }

            $incompletePastMatches[] = $this->matchListEntry($match, $round, $missing);
        }

        $datesWithinRounds = $outsideRoundDates === [];
        $pastMatchesComplete = $incompletePastMatches === [];

        $checklist = [
            [
                'key' => 'has-matches',
                'label' => 'Spiele vorhanden',
                'ok' => $hasMatches,
            ],
            [
                'key' => 'dates-within-rounds',
                'label' => 'Alle Spieldaten innerhalb der Spielrunden',
                'ok' => $datesWithinRounds,
                'match_list' => $outsideRoundDates,
                'match_list_summary' => 'Spiele außerhalb der Spielrunde',
            ],
            [
                'key' => 'past-results',
                'label' => 'Vergangene Spiele mit Ergebnis und Spieldauer',
                'ok' => $pastMatchesComplete,
                'match_list' => $incompletePastMatches,
                'match_list_summary' => 'Vergangene Spiele ohne Ergebnis/Dauer',
                'info_list' => $incompletePastWithStatus,
                'info_list_summary' => 'Vergangene Spiele mit Status-Hinweis',
            ],
        ];

        return [
            'key' => 'matches',
            'title' => sprintf(
                'Spiele: %d Spiele in %d Spielrunden',
                $matchCount,
                $roundCount,
            ),
            'ok' => $hasMatches && $datesWithinRounds && $pastMatchesComplete,
            'checklist' => $checklist,
        ];
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool, match_list?: list<array{team_id?: int, label: string, detail: string}>, match_list_summary?: string}>
     * }
     */
    private function teamsSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'teams',
                'title' => 'Teams',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $teamIds = $this->leagueMatchTeamIds($leagueId);

        $teams = $teamIds === []
            ? collect()
            : Team::query()
                ->whereIn('team_id', $teamIds)
                ->orderBy('team_name')
                ->orderBy('team_id')
                ->get(['team_id', 'team_name', 'team_nationality', 'team_status']);

        $inactive = [];
        $missingFlag = [];
        $missingJersey = [];

        foreach ($teams as $team) {
            $name = trim((string) $team->team_name);
            $label = $name !== '' ? $name : 'Team #'.(int) $team->team_id;
            $nationality = TeamShirt::normalizeNationality((string) ($team->team_nationality ?? ''));

            if ((int) $team->team_status !== 1) {
                $inactive[] = [
                    'team_id' => (int) $team->team_id,
                    'label' => $label,
                    'detail' => 'inaktiv',
                ];
            }

            if (! $this->teamHasFlagOrLogo($nationality)) {
                $missingFlag[] = [
                    'team_id' => (int) $team->team_id,
                    'label' => $label,
                    'detail' => $nationality !== ''
                        ? 'Logo/Flagge fehlt ('.$nationality.')'
                        : 'keine Nationalität/Logo-Kennung',
                ];
            }

            if (! $this->teamHasJersey((int) $team->team_id, $nationality, $leagueId)) {
                $missingJersey[] = [
                    'team_id' => (int) $team->team_id,
                    'label' => $label,
                    'detail' => $nationality !== ''
                        ? 'Trikot fehlt ('.$nationality.')'
                        : 'keine Nationalität für Trikot',
                ];
            }
        }

        $allActive = $inactive === [];
        $allHaveFlag = $missingFlag === [];
        $allHaveJersey = $missingJersey === [];

        return [
            'key' => 'teams',
            'title' => $teams->count().' Mannschaften',
            'ok' => $allActive && $allHaveFlag && $allHaveJersey,
            'checklist' => [
                [
                    'key' => 'teams-active',
                    'label' => 'Alle Teams aktiv',
                    'ok' => $allActive,
                    'match_list' => $inactive,
                    'match_list_summary' => 'Inaktive Teams',
                ],
                [
                    'key' => 'teams-flag',
                    'label' => 'Alle Teams mit Logo/Flagge',
                    'ok' => $allHaveFlag,
                    'match_list' => $missingFlag,
                    'match_list_summary' => 'Teams ohne Logo/Flagge',
                ],
                [
                    'key' => 'teams-jersey',
                    'label' => 'Alle Teams mit Trikot',
                    'ok' => $allHaveJersey,
                    'match_list' => $missingJersey,
                    'match_list_summary' => 'Teams ohne Trikot',
                ],
            ],
        ];
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool, match_list?: list<array{label: string, detail: string}>, match_list_summary?: string}>
     * }
     */
    private function squadSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'squad',
                'title' => 'Kader',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $teamIds = $this->leagueMatchTeamIds($leagueId);
        $teamCount = count($teamIds);

        $teams = $teamIds === []
            ? collect()
            : Team::query()
                ->whereIn('team_id', $teamIds)
                ->orderBy('team_name')
                ->orderBy('team_id')
                ->get(['team_id', 'team_name'])
                ->keyBy(fn (Team $team): int => (int) $team->team_id);

        $playerteams = $teamIds === []
            ? collect()
            : Playerteam::query()
                ->with('player:player_id,player_fname,player_lname')
                ->where('playerteam_league_id', $leagueId)
                ->whereIn('playerteam_team_id', $teamIds)
                ->get([
                    'playerteam_id',
                    'playerteam_player_id',
                    'playerteam_team_id',
                    'playerteam_status',
                    'playerteam_player_position',
                ]);

        $playerCount = $playerteams
            ->filter(fn (Playerteam $row): bool => (int) $row->playerteam_status === 1)
            ->count();
        $activeByTeam = [];
        foreach ($teamIds as $teamId) {
            $activeByTeam[$teamId] = [];
        }

        foreach ($playerteams as $row) {
            if ((int) $row->playerteam_status !== 1) {
                continue;
            }

            $teamId = (int) $row->playerteam_team_id;
            if (! array_key_exists($teamId, $activeByTeam)) {
                continue;
            }

            $activeByTeam[$teamId][] = $row;
        }

        $tooFewPlayers = [];
        $missingPositions = [];
        $requiredPositions = ['g', 'd', 'm', 's'];

        foreach ($teamIds as $teamId) {
            $team = $teams->get($teamId);
            $teamLabel = $this->teamLabel($team, $teamId);
            $active = $activeByTeam[$teamId];
            $activeCount = count($active);

            if ($activeCount < 11) {
                $tooFewPlayers[] = [
                    'label' => $teamLabel,
                    'detail' => $activeCount.' aktive Spieler',
                ];
            }

            $present = [];
            foreach ($active as $row) {
                $pos = strtolower(trim((string) ($row->playerteam_player_position ?? '')));
                if ($pos !== '') {
                    $present[$pos] = true;
                }
            }

            $missing = [];
            foreach ($requiredPositions as $pos) {
                if (! isset($present[$pos])) {
                    $missing[] = strtoupper($pos);
                }
            }

            if ($missing !== []) {
                $missingPositions[] = [
                    'label' => $teamLabel,
                    'detail' => 'fehlt: '.implode(', ', $missing),
                ];
            }
        }

        $multiTeamPlayers = [];
        $activeRows = $playerteams->filter(
            fn (Playerteam $row): bool => (int) $row->playerteam_status === 1
        );

        $byPlayer = $activeRows->groupBy(fn (Playerteam $row): int => (int) $row->playerteam_player_id);
        foreach ($byPlayer as $playerId => $rows) {
            $teamNames = $rows
                ->map(function (Playerteam $row) use ($teams): string {
                    $teamId = (int) $row->playerteam_team_id;

                    return $this->teamLabel($teams->get($teamId), $teamId);
                })
                ->unique()
                ->values()
                ->all();

            if (count($teamNames) < 2) {
                continue;
            }

            /** @var Playerteam $first */
            $first = $rows->first();
            $multiTeamPlayers[] = [
                'label' => $this->playerLabel($first, (int) $playerId),
                'detail' => 'aktiv in: '.implode(', ', $teamNames),
            ];
        }

        $enoughPlayers = $tooFewPlayers === [];
        $allPositions = $missingPositions === [];
        $noMultiTeam = $multiTeamPlayers === [];

        return [
            'key' => 'squad',
            'title' => sprintf(
                'Kader: %d aktive Spieler in %d Mannschaften',
                $playerCount,
                $teamCount,
            ),
            'ok' => $enoughPlayers && $allPositions && $noMultiTeam,
            'checklist' => [
                [
                    'key' => 'squad-min-players',
                    'label' => 'Jede Mannschaft hat mindestens 11 aktive Spieler',
                    'ok' => $enoughPlayers,
                    'match_list' => $tooFewPlayers,
                    'match_list_summary' => 'Mannschaften mit zu wenigen aktiven Spielern',
                ],
                [
                    'key' => 'squad-positions',
                    'label' => 'Jede Mannschaft hat alle Positionen (G/D/M/S)',
                    'ok' => $allPositions,
                    'match_list' => $missingPositions,
                    'match_list_summary' => 'Mannschaften mit fehlenden Positionen',
                ],
                [
                    'key' => 'squad-unique-players',
                    'label' => 'Kein Spieler aktiv in mehr als einer Mannschaft',
                    'ok' => $noMultiTeam,
                    'match_list' => $multiTeamPlayers,
                    'match_list_summary' => 'Spieler in mehreren Mannschaften',
                ],
            ],
        ];
    }

    /**
     * @return list<int>
     */
    private function leagueMatchTeamIds(int $leagueId): array
    {
        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        if ($roundIds === []) {
            return [];
        }

        return MatchGame::query()
            ->whereIn('match_round', $roundIds)
            ->get(['match_hometeam_id', 'match_guestteam_id'])
            ->flatMap(fn (MatchGame $match): array => [
                (int) $match->match_hometeam_id,
                (int) $match->match_guestteam_id,
            ])
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function teamLabel(?Team $team, int $teamId): string
    {
        $name = trim((string) ($team?->team_name ?? ''));

        return $name !== '' ? $name : 'Team #'.$teamId;
    }

    private function playerLabel(Playerteam $row, int $playerId): string
    {
        $fname = trim((string) ($row->player?->player_fname ?? ''));
        $lname = trim((string) ($row->player?->player_lname ?? ''));
        $name = trim($fname.' '.$lname);

        return $name !== '' ? $name : 'Spieler #'.$playerId;
    }

    private function teamHasFlagOrLogo(string $nationality): bool
    {
        if ($nationality === '') {
            return false;
        }

        if ($nationality === 'rks') {
            $nationality = 'kos';
        }

        if (Flag::iso($nationality) !== null) {
            return true;
        }

        return is_file($this->flagsDir().DIRECTORY_SEPARATOR.$nationality.'.gif');
    }

    private function teamHasJersey(int $teamId, string $nationality, int $leagueId): bool
    {
        return TeamShirt::relativePath($teamId, $nationality, $leagueId) !== null;
    }

    private function flagsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'flags';
    }

    /**
     * @return array{match_id: int, label: string, detail: string}
     */
    private function matchListEntry(MatchGame $match, ?Matchround $round, string $detail): array
    {
        $home = trim((string) ($match->homeTeam?->team_name ?? ''));
        $guest = trim((string) ($match->guestTeam?->team_name ?? ''));
        $fixture = ($home !== '' ? $home : '?').' – '.($guest !== '' ? $guest : '?');
        $date = MatchGame::formatDisplayDate(
            $match->match_date !== null ? (string) $match->match_date : null
        ) ?? 'ohne Datum';
        $roundTitle = trim((string) ($round?->matchround_title ?? ''));
        $roundPart = $roundTitle !== '' ? ' · '.$roundTitle : '';

        return [
            'match_id' => (int) $match->match_id,
            'label' => $fixture.' · '.$date.$roundPart,
            'detail' => $detail,
        ];
    }

    private function matchOutsideRoundDetail(MatchGame $match, ?Matchround $round): string
    {
        if ($round === null) {
            return 'keine Spielrunde zugeordnet';
        }

        $start = $this->formatMatchroundDate($round->matchround_startdate);
        $end = $this->formatMatchroundDate($round->matchround_enddate);
        if ($start === '' && $end === '') {
            return 'Spielrunde ohne Zeitraum';
        }

        return 'Spielrunde: '.$start.($end !== '' ? ' – '.$end : '');
    }

    private function matchIncompleteDetail(MatchGame $match): string
    {
        $home = (int) ($match->match_homescore ?? -1);
        $guest = (int) ($match->match_guestscore ?? -1);
        $minutes = (int) ($match->match_minutes ?? 0);
        $parts = [];

        if ($home < 0 || $guest < 0) {
            $parts[] = 'kein Ergebnis';
        }
        if ($minutes <= 0) {
            $parts[] = 'keine Spieldauer';
        }

        return $parts !== [] ? implode(', ', $parts) : '';
    }

    private function matchDateWithinMatchround(MatchGame $match, ?Matchround $round): bool
    {
        if ($round === null) {
            return false;
        }

        $matchRaw = $match->match_date;
        $startRaw = $round->matchround_startdate;
        $endRaw = $round->matchround_enddate;

        if ($matchRaw === null || trim((string) $matchRaw) === '') {
            return false;
        }
        if ($startRaw === null || trim((string) $startRaw) === '') {
            return false;
        }
        if ($endRaw === null || trim((string) $endRaw) === '') {
            return false;
        }

        $matchRawString = (string) $matchRaw;
        $startAt = Carbon::parse((string) $startRaw);
        $endAt = Carbon::parse((string) $endRaw);

        if (! MatchGame::hasKnownKickoffTime($matchRawString)) {
            $matchDay = MatchGame::calendarDate($matchRawString);
            if ($matchDay === '') {
                return false;
            }

            $day = Carbon::parse($matchDay)->startOfDay();

            return $day->betweenIncluded($startAt->copy()->startOfDay(), $endAt->copy()->startOfDay());
        }

        $matchAt = Carbon::parse($matchRawString);

        return $matchAt->betweenIncluded($startAt, $endAt);
    }

    private function matchIsAtLeastOneDayOld(MatchGame $match, Carbon $today): bool
    {
        $raw = $match->match_date;
        if ($raw === null || trim((string) $raw) === '') {
            return false;
        }

        $calendar = MatchGame::calendarDate((string) $raw);
        if ($calendar === '') {
            return false;
        }

        return Carbon::parse($calendar)->startOfDay()->lt($today);
    }

    private function matchHasResultAndDuration(MatchGame $match): bool
    {
        $home = (int) ($match->match_homescore ?? -1);
        $guest = (int) ($match->match_guestscore ?? -1);
        $minutes = (int) ($match->match_minutes ?? 0);

        return $home >= 0 && $guest >= 0 && $minutes > 0;
    }

    private function leagueHasLogoFile(League $league): bool
    {
        $symbol = trim((string) ($league->league_symbol ?: ''));
        if ($symbol === '' || $symbol === self::DEFAULT_SYMBOL) {
            return false;
        }

        $path = $this->symbolsDir().DIRECTORY_SEPARATOR.$symbol;

        return is_file($path);
    }

    private function leagueScheduleMatchesArchiveState(League $league): bool
    {
        $now = Carbon::now();
        $rounds = Matchround::query()
            ->where('matchround_league_id', (int) $league->league_id)
            ->get(['matchround_startdate', 'matchround_enddate']);

        $archived = (bool) $league->league_archive;

        if ($rounds->isEmpty()) {
            return false;
        }

        $hasCurrentOrFuture = false;

        foreach ($rounds as $round) {
            if ($this->matchroundPeriod($round, $now) === 'past') {
                continue;
            }

            $hasCurrentOrFuture = true;
        }

        if ($archived) {
            return ! $hasCurrentOrFuture;
        }

        return $hasCurrentOrFuture;
    }

    /**
     * @return 'past'|'current'|'future'
     */
    private function matchroundPeriod(Matchround $round, Carbon $now): string
    {
        $endRaw = $round->matchround_enddate;
        if ($endRaw !== null && trim((string) $endRaw) !== '') {
            if (Carbon::parse((string) $endRaw)->lt($now)) {
                return 'past';
            }
        } else {
            $startRaw = $round->matchround_startdate;
            if ($startRaw !== null && trim((string) $startRaw) !== ''
                && Carbon::parse((string) $startRaw)->lt($now)) {
                return 'past';
            }
        }

        $startRaw = $round->matchround_startdate;
        if ($startRaw !== null && trim((string) $startRaw) !== ''
            && Carbon::parse((string) $startRaw)->gt($now)) {
            return 'future';
        }

        return 'current';
    }

    private function formatMatchroundDate(mixed $value): string
    {
        if ($value === null || trim((string) $value) === '') {
            return '';
        }

        $timestamp = strtotime((string) $value);

        return $timestamp ? date('j.n.Y G:i', $timestamp) : '';
    }

    /**
     * @return list<array{title: string, items: list<array{label: string, value: string}>}>
     */
    private function matchroundLineupOptionsOverview(MatchroundOptions $options): array
    {
        return [
            [
                'title' => 'Aufstellungslimits',
                'items' => [
                    ['label' => 'Max. Spieler', 'value' => (string) (int) ($options->matchround_options_lineup_max_players ?? 0)],
                    ['label' => 'Max. Credits', 'value' => (string) (int) ($options->matchround_options_lineup_max_credits ?? 0)],
                    ['label' => 'Max. Spieler / Team', 'value' => (string) (int) ($options->matchround_options_lineup_max_players_team ?? 0)],
                    ['label' => 'Tor (min/max)', 'value' => (int) ($options->matchround_options_lineup_min_g ?? 0).' / '.(int) ($options->matchround_options_lineup_max_g ?? 0)],
                    ['label' => 'Abwehr (min/max)', 'value' => (int) ($options->matchround_options_lineup_min_d ?? 0).' / '.(int) ($options->matchround_options_lineup_max_d ?? 0)],
                    ['label' => 'Mittelfeld (min/max)', 'value' => (int) ($options->matchround_options_lineup_min_m ?? 0).' / '.(int) ($options->matchround_options_lineup_max_m ?? 0)],
                    ['label' => 'Angriff (min/max)', 'value' => (int) ($options->matchround_options_lineup_min_s ?? 0).' / '.(int) ($options->matchround_options_lineup_max_s ?? 0)],
                ],
            ],
        ];
    }

    /**
     * @return list<array{title: string, items: list<array{label: string, value: string}>}>
     */
    private function optionsOverview(LeagueOptions $options): array
    {
        return [
            [
                'title' => 'Spielmodi',
                'items' => [
                    ['label' => 'Rangliste', 'value' => $this->rankModeLabel((string) ($options->options_league_rankmode ?? ''))],
                    ['label' => 'Preisberechnung', 'value' => $this->priceModeLabel((string) ($options->options_league_pricemode ?? ''))],
                    ['label' => 'Punktewertung', 'value' => $this->pointsModeLabel((string) ($options->options_league_pointsmode ?? ''))],
                    ['label' => 'LigaCup Punkte', 'value' => (string) ($options->options_league_lcpoints ?? '')],
                    ['label' => 'Erinnerung (h)', 'value' => (string) (int) ($options->options_league_remind_hours_before ?? 0)],
                ],
            ],
            [
                'title' => 'Aufstellungslimits',
                'items' => [
                    ['label' => 'Max. Spieler', 'value' => (string) (int) ($options->options_lineup_max_players ?? 0)],
                    ['label' => 'Max. Credits', 'value' => (string) (int) ($options->options_lineup_max_credits ?? 0)],
                    ['label' => 'Max. Spieler / Team', 'value' => (string) (int) ($options->options_lineup_max_players_team ?? 0)],
                    ['label' => 'Tor (min/max)', 'value' => (int) ($options->options_lineup_min_g ?? 0).' / '.(int) ($options->options_lineup_max_g ?? 0)],
                    ['label' => 'Abwehr (min/max)', 'value' => (int) ($options->options_lineup_min_d ?? 0).' / '.(int) ($options->options_lineup_max_d ?? 0)],
                    ['label' => 'Mittelfeld (min/max)', 'value' => (int) ($options->options_lineup_min_m ?? 0).' / '.(int) ($options->options_lineup_max_m ?? 0)],
                    ['label' => 'Angriff (min/max)', 'value' => (int) ($options->options_lineup_min_s ?? 0).' / '.(int) ($options->options_lineup_max_s ?? 0)],
                ],
            ],
            [
                'title' => 'Punktewertung',
                'items' => [
                    ['label' => 'Minuten-Schwellen', 'value' => (int) ($options->options_score_minutes_threshold_lower ?? 0).' / '.(int) ($options->options_score_minutes_threshold_upper ?? 0)],
                    ['label' => 'Minuten-Punkte', 'value' => (int) ($options->options_score_minutes_low ?? 0).' / '.(int) ($options->options_score_minutes_middle ?? 0).' / '.(int) ($options->options_score_minutes_high ?? 0)],
                    ['label' => 'Tore (G/D/M/S)', 'value' => (int) ($options->options_score_goals_g ?? 0).' / '.(int) ($options->options_score_goals_d ?? 0).' / '.(int) ($options->options_score_goals_m ?? 0).' / '.(int) ($options->options_score_goals_s ?? 0)],
                    ['label' => 'Assist / Eigentor', 'value' => (int) ($options->options_score_assists ?? 0).' / '.(int) ($options->options_score_owngoals ?? 0)],
                    ['label' => 'Kein Gegentor (G/D/M)', 'value' => (int) ($options->options_score_no_oppgoals_g ?? 0).' / '.(int) ($options->options_score_no_oppgoals_d ?? 0).' / '.(int) ($options->options_score_no_oppgoals_m ?? 0)],
                    ['label' => 'Gegentor (G/D)', 'value' => (int) ($options->options_score_oppgoals_g ?? 0).' / '.(int) ($options->options_score_oppgoals_d ?? 0)],
                    ['label' => 'Karten (G/GR/R)', 'value' => (int) ($options->options_score_card_y ?? 0).' / '.(int) ($options->options_score_card_yr ?? 0).' / '.(int) ($options->options_score_card_r ?? 0)],
                    ['label' => 'Elfmeter (geh./ver.)', 'value' => (int) ($options->options_score_penalty_saved ?? 0).' / '.(int) ($options->options_score_penalty_lost ?? 0)],
                    ['label' => 'Elfmeterschießen', 'value' => (int) ($options->options_score_penaltyshootout_save ?? 0).' / '.(int) ($options->options_score_penaltyshootout_lost ?? 0).' / '.(int) ($options->options_score_penaltyshootout_hit ?? 0)],
                ],
            ],
        ];
    }

    private function rankModeLabel(string $mode): string
    {
        return match ($mode) {
            'lc' => 'LC',
            'points' => 'Punkte',
            default => $mode !== '' ? $mode : '—',
        };
    }

    private function priceModeLabel(string $mode): string
    {
        return match ($mode) {
            'dynamic' => 'dynamisch',
            'static' => 'statisch',
            'constant' => 'konstant',
            default => $mode !== '' ? $mode : '—',
        };
    }

    private function pointsModeLabel(string $mode): string
    {
        return match ($mode) {
            'new' => 'neu',
            'old' => 'alt',
            default => $mode !== '' ? $mode : '—',
        };
    }

    private function symbolsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'symbols';
    }
}
