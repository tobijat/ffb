<?php

namespace App\Services;

use App\Models\Extremeteam;
use App\Models\Goal;
use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\MatchroundOptions;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Psgoal;
use App\Models\Team;
use App\Models\Teamprice;
use App\Models\Userscore;
use App\Models\Userteam;
use App\Support\FfbDateTime;
use App\Support\Flag;
use App\Support\TeamShirt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminLeagueDashboardService
{
    private const DEFAULT_SYMBOL = 'symbol_game_na.png';

    private const AVERAGE_LINEUP_BUDGET_RATIO = 0.9;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly ExtremeTeamService $extremeTeams,
        private readonly LineupOptionsResolver $lineupOptions,
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
            $this->playerpriceSection($leagueId),
            $this->matchdataSection($leagueId),
            $this->extremeteamSection($leagueId),
            $this->scoreSection($leagueId),
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
        $now = Carbon::now();

        foreach ($matches as $match) {
            $round = $rounds->get((int) $match->match_round);
            if (! $this->matchDateWithinMatchround($match, $round)) {
                $outsideRoundDates[] = $this->matchListEntry($match, $round, $this->matchOutsideRoundDetail($match, $round));
            }

            if (! $this->matchIsPast($match, $now)
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
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool, match_list?: list<array{label: string, detail: string}>, match_list_summary?: string}>
     * }
     */
    private function playerpriceSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'playerprice',
                'title' => 'Preis/Performance',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $priceMode = (string) (LeagueOptions::query()
            ->where('options_league_id', $leagueId)
            ->value('options_league_pricemode') ?: '');
        $isDynamic = $priceMode === 'dynamic';

        $teamIds = $this->leagueMatchTeamIds($leagueId);
        $teams = $teamIds === []
            ? collect()
            : Team::query()
                ->whereIn('team_id', $teamIds)
                ->orderBy('team_name')
                ->orderBy('team_id')
                ->get(['team_id', 'team_name'])
                ->keyBy(fn (Team $team): int => (int) $team->team_id);

        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $teamsWithPrice = $roundIds === []
            ? []
            : Teamprice::query()
                ->whereIn('teamprice_matchround_id', $roundIds)
                ->whereIn('teamprice_team_id', $teamIds ?: [0])
                ->pluck('teamprice_team_id')
                ->map(fn ($id): int => (int) $id)
                ->unique()
                ->all();

        $teamsWithPriceLookup = array_fill_keys($teamsWithPrice, true);
        $missingTeamPrices = [];
        foreach ($teamIds as $teamId) {
            if (isset($teamsWithPriceLookup[$teamId])) {
                continue;
            }

            $missingTeamPrices[] = [
                'label' => $this->teamLabel($teams->get($teamId), $teamId),
                'detail' => 'kein Teampreis in dieser Liga',
            ];
        }

        $missingPerformance = [];
        $missingPlayerPrices = [];

        if ($isDynamic) {
            $missingPerformance = $this->missingRoundPerformanceEntries($roundIds);
            $missingPlayerPrices = $this->missingPlayerpriceEntries($leagueId, $teams);
        }

        $teamPricesOk = $missingTeamPrices === [];
        $performanceOk = $missingPerformance === [];
        $playerPricesOk = $missingPlayerPrices === [];
        $averageLineup = $this->averageLineupBudgetEntries($leagueId, $isDynamic, $roundIds);
        $averageLineupOk = (bool) ($averageLineup['ok'] ?? false);

        return [
            'key' => 'playerprice',
            'title' => 'Preis/Performance',
            'ok' => $teamPricesOk && $performanceOk && $playerPricesOk && $averageLineupOk,
            'checklist' => [
                [
                    'key' => 'team-prices',
                    'label' => 'Jede Mannschaft hat einen Teampreis',
                    'ok' => $teamPricesOk,
                    'match_list' => $missingTeamPrices,
                    'match_list_summary' => 'Mannschaften ohne Teampreis',
                ],
                [
                    'key' => 'round-performance',
                    'label' => 'Dynamisch: Round-Performance für vergangene Spiele gesetzt',
                    'ok' => $performanceOk,
                    'match_list' => $missingPerformance,
                    'match_list_summary' => 'Stats ohne Round-Performance',
                ],
                [
                    'key' => 'player-prices',
                    'label' => 'Dynamisch: Spielerpreise für nächste Spielrunde gesetzt',
                    'ok' => $playerPricesOk,
                    'match_list' => $missingPlayerPrices,
                    'match_list_summary' => 'Aktive Spieler ohne Spielerpreis',
                ],
                [
                    'key' => 'average-lineup-budget',
                    'label' => 'Durchschnitts-Aufstellung ≤ 90% des Budgets',
                    'ok' => $averageLineupOk,
                    'info_list' => $averageLineup['entries'],
                    'info_list_summary' => 'Anteil am Budget je Spielrunde',
                ],
            ],
        ];
    }

    /**
     * @param  list<int>  $roundIds
     * @return array{
     *     ok: bool,
     *     entries: list<array{
     *         label: string,
     *         detail: string,
     *         lineup?: list<array{label: string, detail: string}>
     *     }>
     * }
     */
    private function averageLineupBudgetEntries(int $leagueId, bool $isDynamic, array $roundIds): array
    {
        if ($leagueId <= 0 || $roundIds === []) {
            return ['ok' => true, 'entries' => []];
        }

        $checkRoundIds = $isDynamic
            ? $this->matchroundIdsWithPlayerprices($roundIds)
            : $roundIds;

        if ($checkRoundIds === []) {
            return ['ok' => true, 'entries' => []];
        }

        $rounds = Matchround::query()
            ->whereIn('matchround_id', $checkRoundIds)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get(['matchround_id', 'matchround_title', 'matchround_startdate']);

        $allOk = true;
        $entries = [];

        foreach ($rounds as $round) {
            $roundId = (int) $round->matchround_id;
            $roundTitle = trim((string) $round->matchround_title);
            $label = $roundTitle !== '' ? $roundTitle : 'Spielrunde #'.$roundId;

            $result = $this->averageLineupForMatchround($leagueId, $roundId);
            if ($result === null) {
                $allOk = false;
                $entries[] = [
                    'label' => $label,
                    'detail' => 'keine gültige Durchschnitts-Aufstellung möglich',
                ];

                continue;
            }

            $budget = (float) $result['budget'];
            $cost = (float) $result['cost'];
            $ratio = $budget > 0.0 ? ($cost / $budget) : 0.0;
            $percent = round($ratio * 100, 1);
            $withinBudget = $ratio <= self::AVERAGE_LINEUP_BUDGET_RATIO;
            if (! $withinBudget) {
                $allOk = false;
            }

            $entry = [
                'label' => $label,
                'detail' => $this->formatCredits($percent).'% des Budgets ('
                    .$this->formatCredits($cost).' / '.$this->formatCredits($budget).')',
            ];

            if (! $withinBudget) {
                $entry['lineup'] = array_map(
                    static fn (array $player): array => [
                        'label' => (string) $player['name'],
                        'detail' => (string) $player['team'].' · '.$player['price_label'],
                    ],
                    $result['players'],
                );
            }

            $entries[] = $entry;
        }

        return [
            'ok' => $allOk,
            'entries' => $entries,
        ];
    }

    /**
     * @param  list<int>  $roundIds
     * @return list<int>
     */
    private function matchroundIdsWithPlayerprices(array $roundIds): array
    {
        if ($roundIds === []) {
            return [];
        }

        return Playerprice::query()
            ->whereIn('playerprice_matchround_id', $roundIds)
            ->distinct()
            ->pluck('playerprice_matchround_id')
            ->map(static fn ($id): int => (int) $id)
            ->values()
            ->all();
    }

    /**
     * @return array{
     *     budget: float,
     *     cost: float,
     *     players: list<array{name: string, team: string, price: float, price_label: string}>
     * }|null
     */
    private function averageLineupForMatchround(int $leagueId, int $matchroundId): ?array
    {
        $options = $this->lineupOptions->forMatchround($matchroundId);
        $budget = (float) $options['lineup_max_credits'];
        $formations = $this->lineupFormationsFromOptions($options);
        if ($formations === [] || $budget <= 0.0) {
            return null;
        }

        $participatingTeamIds = MatchGame::query()
            ->where('match_round', $matchroundId)
            ->get(['match_hometeam_id', 'match_guestteam_id'])
            ->flatMap(static fn (MatchGame $match): array => [
                (int) $match->match_hometeam_id,
                (int) $match->match_guestteam_id,
            ])
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($participatingTeamIds === []) {
            return null;
        }

        $playerteams = Playerteam::query()
            ->with([
                'player:player_id,player_fname,player_lname',
                'team:team_id,team_name',
            ])
            ->where('playerteam_league_id', $leagueId)
            ->where('playerteam_status', 1)
            ->whereIn('playerteam_team_id', $participatingTeamIds)
            ->orderBy('playerteam_id')
            ->get([
                'playerteam_id',
                'playerteam_player_id',
                'playerteam_team_id',
                'playerteam_player_position',
                'playerteam_status',
            ])
            ->keyBy(static fn (Playerteam $pt): int => (int) $pt->playerteam_id);

        if ($playerteams->isEmpty()) {
            return null;
        }

        $playerteamIds = $playerteams->keys()->map(static fn ($id): int => (int) $id)->all();
        $prices = $this->resolvePricesForPlayerteams(
            $matchroundId,
            $playerteamIds,
            $playerteams,
        );

        /** @var array<string, list<array{playerteam_id: int, team_id: int, name: string, team: string, price: float}>> $byPosition */
        $byPosition = ['g' => [], 'd' => [], 'm' => [], 's' => []];
        foreach ($playerteams as $playerteam) {
            $playerteamId = (int) $playerteam->playerteam_id;
            if (! $prices->has($playerteamId)) {
                continue;
            }

            $pos = strtolower((string) $playerteam->playerteam_player_position);
            if (! isset($byPosition[$pos])) {
                continue;
            }

            $byPosition[$pos][] = [
                'playerteam_id' => $playerteamId,
                'team_id' => (int) $playerteam->playerteam_team_id,
                'name' => $this->playerLabel($playerteam, (int) $playerteam->playerteam_player_id),
                'team' => $this->teamLabel($playerteam->team, (int) $playerteam->playerteam_team_id),
                'price' => (float) $prices->get($playerteamId),
            ];
        }

        foreach ($byPosition as $pos => $rows) {
            usort($rows, static function (array $a, array $b): int {
                $byPrice = $a['price'] <=> $b['price'];
                if ($byPrice !== 0) {
                    return $byPrice;
                }

                return $a['playerteam_id'] <=> $b['playerteam_id'];
            });
            $byPosition[$pos] = $rows;
        }

        $maxPerTeam = max(1, (int) $options['lineup_max_players_team']);
        $candidates = [];
        foreach ($formations as $formation) {
            if (! $this->formationHasEnoughCandidates($formation, $byPosition)) {
                continue;
            }

            $picks = $this->pickMedianLineupPlayers($formation, $byPosition, $maxPerTeam);
            if ($picks === null) {
                continue;
            }

            $cost = 0.0;
            foreach ($picks as $pick) {
                $cost += (float) $pick['price'];
            }

            $candidates[] = [
                'cost' => round($cost, 1),
                'players' => $picks,
            ];
        }

        if ($candidates === []) {
            return null;
        }

        usort($candidates, static fn (array $a, array $b): int => $a['cost'] <=> $b['cost']);
        $chosen = $candidates[(int) floor((count($candidates) - 1) / 2)];

        $players = array_map(
            fn (array $player): array => [
                'name' => (string) $player['name'],
                'team' => (string) $player['team'],
                'price' => (float) $player['price'],
                'price_label' => $this->formatCredits((float) $player['price']),
            ],
            $chosen['players'],
        );

        return [
            'budget' => $budget,
            'cost' => (float) $chosen['cost'],
            'players' => $players,
        ];
    }

    /**
     * @param  array{
     *     lineup_max_players: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int
     * }  $options
     * @return list<array{g: int, d: int, m: int, s: int}>
     */
    private function lineupFormationsFromOptions(array $options): array
    {
        $maxPlayers = (int) $options['lineup_max_players'];
        $formations = [];

        for ($g = (int) $options['lineup_min_g']; $g <= (int) $options['lineup_max_g']; $g++) {
            for ($d = (int) $options['lineup_min_d']; $d <= (int) $options['lineup_max_d']; $d++) {
                for ($m = (int) $options['lineup_min_m']; $m <= (int) $options['lineup_max_m']; $m++) {
                    for ($s = (int) $options['lineup_min_s']; $s <= (int) $options['lineup_max_s']; $s++) {
                        if ($g + $d + $m + $s === $maxPlayers) {
                            $formations[] = ['g' => $g, 'd' => $d, 'm' => $m, 's' => $s];
                        }
                    }
                }
            }
        }

        return $formations;
    }

    /**
     * @param  array{g: int, d: int, m: int, s: int}  $formation
     * @param  array<string, list<array<string, mixed>>>  $byPosition
     */
    private function formationHasEnoughCandidates(array $formation, array $byPosition): bool
    {
        foreach ($formation as $pos => $need) {
            if (count($byPosition[$pos] ?? []) < $need) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array{g: int, d: int, m: int, s: int}  $formation
     * @param  array<string, list<array{playerteam_id: int, team_id: int, name: string, team: string, price: float}>>  $byPosition
     * @return list<array{playerteam_id: int, team_id: int, name: string, team: string, price: float}>|null
     */
    private function pickMedianLineupPlayers(array $formation, array $byPosition, int $maxPerTeam): ?array
    {
        $picks = [];
        $used = [];
        $teamCounts = [];

        foreach (['g', 'd', 'm', 's'] as $pos) {
            $need = (int) $formation[$pos];
            $pool = $byPosition[$pos] ?? [];
            if ($need <= 0) {
                continue;
            }

            $order = $this->medianOutwardIndexes(count($pool));
            $taken = 0;
            foreach ($order as $index) {
                $player = $pool[$index];
                $playerteamId = (int) $player['playerteam_id'];
                if (isset($used[$playerteamId])) {
                    continue;
                }

                $teamId = (int) $player['team_id'];
                if (($teamCounts[$teamId] ?? 0) >= $maxPerTeam) {
                    continue;
                }

                $picks[] = $player;
                $used[$playerteamId] = true;
                $teamCounts[$teamId] = ($teamCounts[$teamId] ?? 0) + 1;
                $taken++;
                if ($taken >= $need) {
                    break;
                }
            }

            if ($taken < $need) {
                return null;
            }
        }

        return $picks;
    }

    /**
     * @return list<int>
     */
    private function medianOutwardIndexes(int $count): array
    {
        if ($count <= 0) {
            return [];
        }

        $start = (int) floor(($count - 1) / 2);
        $indexes = [];
        for ($offset = 0; count($indexes) < $count; $offset++) {
            $right = $start + $offset;
            if ($right >= 0 && $right < $count) {
                $indexes[] = $right;
            }
            if ($offset === 0) {
                continue;
            }
            $left = $start - $offset;
            if ($left >= 0 && $left < $count) {
                $indexes[] = $left;
            }
        }

        return $indexes;
    }

    /**
     * Resolve credits: ffb_playerprice, else ffb_teamprice for the player's team.
     *
     * @param  list<int>  $playerteamIds
     * @param  Collection<int, Playerteam>  $playerteams
     * @return Collection<int, float>
     */
    private function resolvePricesForPlayerteams(int $matchroundId, array $playerteamIds, Collection $playerteams): Collection
    {
        if ($playerteamIds === [] || $matchroundId <= 0) {
            return collect();
        }

        $fromPlayer = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereIn('playerprice_playerteam_id', $playerteamIds)
            ->get()
            ->mapWithKeys(static fn (Playerprice $row): array => [
                (int) $row->playerprice_playerteam_id => (float) $row->playerprice_price,
            ]);

        $teamIds = $playerteams
            ->map(static fn (Playerteam $pt): int => (int) $pt->playerteam_team_id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        $fromTeam = $teamIds === []
            ? collect()
            : Teamprice::query()
                ->where('teamprice_matchround_id', $matchroundId)
                ->whereIn('teamprice_team_id', $teamIds)
                ->get()
                ->mapWithKeys(static fn (Teamprice $row): array => [
                    (int) $row->teamprice_team_id => (float) $row->teamprice_price,
                ]);

        $resolved = collect();
        foreach ($playerteamIds as $ptId) {
            if ($fromPlayer->has($ptId)) {
                $resolved->put($ptId, (float) $fromPlayer->get($ptId));

                continue;
            }

            $teamId = (int) ($playerteams->get($ptId)?->playerteam_team_id ?? 0);
            if ($teamId > 0 && $fromTeam->has($teamId)) {
                $resolved->put($ptId, (float) $fromTeam->get($teamId));
            }
        }

        return $resolved;
    }

    private function formatCredits(float $value): string
    {
        $formatted = number_format($value, 1, '.', '');

        return rtrim(rtrim($formatted, '0'), '.') ?: '0';
    }

    /**
     * @param  list<int>  $roundIds
     * @return list<array{label: string, detail: string}>
     */
    private function missingRoundPerformanceEntries(array $roundIds): array
    {
        if ($roundIds === []) {
            return [];
        }

        $now = Carbon::now();
        $pastMatchIds = MatchGame::query()
            ->whereIn('match_round', $roundIds)
            ->get(['match_id', 'match_date'])
            ->filter(fn (MatchGame $match): bool => $this->matchIsPast($match, $now))
            ->map(fn (MatchGame $match): int => (int) $match->match_id)
            ->values()
            ->all();

        if ($pastMatchIds === []) {
            return [];
        }

        $rows = Playerstats::query()
            ->with([
                'playerteam.player:player_id,player_fname,player_lname',
                'playerteam.team:team_id,team_name',
                'match:match_id,match_date,match_round',
            ])
            ->whereIn('playerstats_match_id', $pastMatchIds)
            ->whereNull('playerstats_round_performance')
            ->orderBy('playerstats_match_id')
            ->orderBy('playerstats_id')
            ->get([
                'playerstats_id',
                'playerstats_playerteam_id',
                'playerstats_match_id',
                'playerstats_round_performance',
            ]);

        $entries = [];
        foreach ($rows as $row) {
            /** @var Playerteam|null $playerteam */
            $playerteam = $row->playerteam;
            $playerLabel = $playerteam !== null
                ? $this->playerLabel($playerteam, (int) $playerteam->playerteam_player_id)
                : 'Spielerteam #'.(int) $row->playerstats_playerteam_id;
            $teamLabel = $playerteam !== null
                ? $this->teamLabel($playerteam->team, (int) $playerteam->playerteam_team_id)
                : '';
            $matchDate = MatchGame::formatDisplayDate(
                $row->match?->match_date !== null ? (string) $row->match->match_date : null
            ) ?? 'ohne Datum';

            $entries[] = [
                'label' => $playerLabel.($teamLabel !== '' ? ' · '.$teamLabel : ''),
                'detail' => 'Spiel '.$matchDate.' · Round-Performance fehlt',
            ];
        }

        return $entries;
    }

    /**
     * @param  Collection<int, Team>  $teams
     * @return list<array{label: string, detail: string}>
     */
    private function missingPlayerpriceEntries(int $leagueId, $teams): array
    {
        $targetRound = $this->nextUpcomingMatchround($leagueId);
        if ($targetRound === null) {
            return [];
        }

        $roundId = (int) $targetRound->matchround_id;
        $participatingTeamIds = MatchGame::query()
            ->where('match_round', $roundId)
            ->get(['match_hometeam_id', 'match_guestteam_id'])
            ->flatMap(fn (MatchGame $match): array => [
                (int) $match->match_hometeam_id,
                (int) $match->match_guestteam_id,
            ])
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($participatingTeamIds === []) {
            return [];
        }

        $activePlayers = Playerteam::query()
            ->with('player:player_id,player_fname,player_lname')
            ->where('playerteam_league_id', $leagueId)
            ->where('playerteam_status', 1)
            ->whereIn('playerteam_team_id', $participatingTeamIds)
            ->orderBy('playerteam_team_id')
            ->orderBy('playerteam_id')
            ->get([
                'playerteam_id',
                'playerteam_player_id',
                'playerteam_team_id',
                'playerteam_status',
            ]);

        if ($activePlayers->isEmpty()) {
            return [];
        }

        $pricedIds = Playerprice::query()
            ->where('playerprice_matchround_id', $roundId)
            ->whereIn('playerprice_playerteam_id', $activePlayers->modelKeys())
            ->pluck('playerprice_playerteam_id')
            ->map(fn ($id): int => (int) $id)
            ->all();
        $pricedLookup = array_fill_keys($pricedIds, true);

        $roundTitle = trim((string) $targetRound->matchround_title);
        $entries = [];
        foreach ($activePlayers as $playerteam) {
            $playerteamId = (int) $playerteam->playerteam_id;
            if (isset($pricedLookup[$playerteamId])) {
                continue;
            }

            $teamId = (int) $playerteam->playerteam_team_id;
            $entries[] = [
                'label' => $this->playerLabel($playerteam, (int) $playerteam->playerteam_player_id)
                    .' · '.$this->teamLabel($teams->get($teamId), $teamId),
                'detail' => 'kein Spielerpreis'
                    .($roundTitle !== '' ? ' · '.$roundTitle : ''),
            ];
        }

        return $entries;
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool, match_list?: list<array{label: string, detail: string}>, match_list_summary?: string}>
     * }
     */
    private function matchdataSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'matchdata',
                'title' => 'Spieldaten',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $pointsMode = (string) (LeagueOptions::query()
            ->where('options_league_id', $leagueId)
            ->value('options_league_pointsmode') ?: 'new');
        $isNewPointsMode = $pointsMode === 'new';

        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $matches = $roundIds === []
            ? collect()
            : MatchGame::query()
                ->with([
                    'homeTeam:team_id,team_name',
                    'guestTeam:team_id,team_name',
                ])
                ->whereIn('match_round', $roundIds)
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
                    'match_homescore_penalty',
                    'match_guestscore_penalty',
                ]);

        $matchesWithResult = $matches->filter(
            fn (MatchGame $match): bool => $this->matchHasResult($match)
        );

        $matchIds = $matchesWithResult
            ->map(fn (MatchGame $match): int => (int) $match->match_id)
            ->values()
            ->all();

        $statsByMatch = $matchIds === []
            ? collect()
            : Playerstats::query()
                ->with('playerteam:playerteam_id,playerteam_team_id')
                ->whereIn('playerstats_match_id', $matchIds)
                ->get([
                    'playerstats_id',
                    'playerstats_match_id',
                    'playerstats_playerteam_id',
                    'playerstats_goals',
                    'playerstats_owngoals',
                    'playerstats_penaltyshootout_hit',
                ])
                ->groupBy(fn (Playerstats $row): int => (int) $row->playerstats_match_id);

        $goalsByMatch = ($isNewPointsMode && $matchIds !== [])
            ? Goal::query()
                ->with('playerteam:playerteam_id,playerteam_team_id')
                ->whereIn('goal_match_id', $matchIds)
                ->get(['goal_id', 'goal_match_id', 'goal_playerteam_id', 'goal_owngoal'])
                ->groupBy(fn (Goal $goal): int => (int) $goal->goal_match_id)
            : collect();

        $psGoalsByMatch = ($isNewPointsMode && $matchIds !== [])
            ? Psgoal::query()
                ->with('playerteam:playerteam_id,playerteam_team_id')
                ->whereIn('psgoal_match_id', $matchIds)
                ->where('psgoal_hit', 1)
                ->get(['psgoal_id', 'psgoal_match_id', 'psgoal_playerteam_id', 'psgoal_hit'])
                ->groupBy(fn (Psgoal $goal): int => (int) $goal->psgoal_match_id)
            : collect();

        $missingStats = [];
        $missingPlayerstatsGoals = [];
        $missingPsHits = [];
        $missingTableGoals = [];
        $missingTablePsGoals = [];

        foreach ($matchesWithResult as $match) {
            $matchId = (int) $match->match_id;
            $homeId = (int) $match->match_hometeam_id;
            $guestId = (int) $match->match_guestteam_id;
            $label = $this->matchFixtureLabel($match);

            $stats = $statsByMatch->get($matchId) ?? collect();
            $countsByTeam = [];
            $goalsByTeam = [];
            $owngoalsByTeam = [];
            $psHitsByTeam = [];
            foreach ($stats as $stat) {
                $teamId = (int) ($stat->playerteam?->playerteam_team_id ?? 0);
                if ($teamId <= 0) {
                    continue;
                }
                $countsByTeam[$teamId] = ($countsByTeam[$teamId] ?? 0) + 1;
                $goalsByTeam[$teamId] = ($goalsByTeam[$teamId] ?? 0) + (int) ($stat->playerstats_goals ?? 0);
                $owngoalsByTeam[$teamId] = ($owngoalsByTeam[$teamId] ?? 0) + (int) ($stat->playerstats_owngoals ?? 0);
                $psHitsByTeam[$teamId] = ($psHitsByTeam[$teamId] ?? 0) + (int) ($stat->playerstats_penaltyshootout_hit ?? 0);
            }

            $homeStats = $countsByTeam[$homeId] ?? 0;
            $guestStats = $countsByTeam[$guestId] ?? 0;
            if ($homeStats < 11 || $guestStats < 11) {
                $missingStats[] = [
                    'label' => $label,
                    'detail' => sprintf(
                        'Playerstats Heim %d / Gast %d (je ≥ 11 nötig)',
                        $homeStats,
                        $guestStats,
                    ),
                ];
            }

            $homeScore = (int) $match->match_homescore;
            $guestScore = (int) $match->match_guestscore;
            if ($homeScore !== 0 || $guestScore !== 0) {
                $homeFromStats = ($goalsByTeam[$homeId] ?? 0) + ($owngoalsByTeam[$guestId] ?? 0);
                $guestFromStats = ($goalsByTeam[$guestId] ?? 0) + ($owngoalsByTeam[$homeId] ?? 0);
                if ($homeFromStats !== $homeScore || $guestFromStats !== $guestScore) {
                    $missingPlayerstatsGoals[] = [
                        'label' => $label,
                        'detail' => sprintf(
                            'Ergebnis %d:%d · Tore in playerstats Heim %d / Gast %d (erwartet %d / %d)',
                            $homeScore,
                            $guestScore,
                            $homeFromStats,
                            $guestFromStats,
                            $homeScore,
                            $guestScore,
                        ),
                    ];
                }
            }

            if ($this->matchHasPenaltyShootoutResult($match)) {
                $homePs = (int) $match->match_homescore_penalty;
                $guestPs = (int) $match->match_guestscore_penalty;
                $homeHits = $psHitsByTeam[$homeId] ?? 0;
                $guestHits = $psHitsByTeam[$guestId] ?? 0;
                if ($homeHits !== $homePs || $guestHits !== $guestPs) {
                    $missingPsHits[] = [
                        'label' => $label,
                        'detail' => sprintf(
                            'Elfmeter %d:%d · Treffer in playerstats Heim %d / Gast %d (erwartet %d / %d)',
                            $homePs,
                            $guestPs,
                            $homeHits,
                            $guestHits,
                            $homePs,
                            $guestPs,
                        ),
                    ];
                }
            }

            if (! $isNewPointsMode) {
                continue;
            }

            if ($homeScore !== 0 || $guestScore !== 0) {
                /** @var Collection<int, Goal> $goals */
                $goals = $goalsByMatch->get($matchId) ?? collect();
                $tableGoalsByTeam = [];
                $tableOwngoalsByTeam = [];
                foreach ($goals as $goal) {
                    $teamId = (int) ($goal->playerteam?->playerteam_team_id ?? 0);
                    if ($teamId <= 0) {
                        continue;
                    }
                    if ((int) ($goal->goal_owngoal ?? 0) === 1) {
                        $tableOwngoalsByTeam[$teamId] = ($tableOwngoalsByTeam[$teamId] ?? 0) + 1;
                    } else {
                        $tableGoalsByTeam[$teamId] = ($tableGoalsByTeam[$teamId] ?? 0) + 1;
                    }
                }
                $homeFromTable = ($tableGoalsByTeam[$homeId] ?? 0) + ($tableOwngoalsByTeam[$guestId] ?? 0);
                $guestFromTable = ($tableGoalsByTeam[$guestId] ?? 0) + ($tableOwngoalsByTeam[$homeId] ?? 0);
                if ($homeFromTable !== $homeScore || $guestFromTable !== $guestScore) {
                    $missingTableGoals[] = [
                        'label' => $label,
                        'detail' => sprintf(
                            'Ergebnis %d:%d · Tore in ffb_goal Heim %d / Gast %d (erwartet %d / %d)',
                            $homeScore,
                            $guestScore,
                            $homeFromTable,
                            $guestFromTable,
                            $homeScore,
                            $guestScore,
                        ),
                    ];
                }
            }

            if ($this->matchHasPenaltyShootoutResult($match)) {
                $homePs = (int) $match->match_homescore_penalty;
                $guestPs = (int) $match->match_guestscore_penalty;
                /** @var Collection<int, Psgoal> $psGoals */
                $psGoals = $psGoalsByMatch->get($matchId) ?? collect();
                $tableHitsByTeam = [];
                foreach ($psGoals as $psGoal) {
                    $teamId = (int) ($psGoal->playerteam?->playerteam_team_id ?? 0);
                    if ($teamId <= 0) {
                        continue;
                    }
                    $tableHitsByTeam[$teamId] = ($tableHitsByTeam[$teamId] ?? 0) + 1;
                }
                $homeTableHits = $tableHitsByTeam[$homeId] ?? 0;
                $guestTableHits = $tableHitsByTeam[$guestId] ?? 0;
                if ($homeTableHits !== $homePs || $guestTableHits !== $guestPs) {
                    $missingTablePsGoals[] = [
                        'label' => $label,
                        'detail' => sprintf(
                            'Elfmeter %d:%d · Treffer in ffb_psgoal Heim %d / Gast %d (erwartet %d / %d)',
                            $homePs,
                            $guestPs,
                            $homeTableHits,
                            $guestTableHits,
                            $homePs,
                            $guestPs,
                        ),
                    ];
                }
            }
        }

        $statsOk = $missingStats === [];
        $playerstatsGoalsOk = $missingPlayerstatsGoals === [];
        $psHitsOk = $missingPsHits === [];
        $tableGoalsOk = $missingTableGoals === [];
        $tablePsGoalsOk = $missingTablePsGoals === [];

        $checklist = [
            [
                'key' => 'match-playerstats',
                'label' => 'Spiele mit Ergebnis haben ≥11 Playerstats je Mannschaft',
                'ok' => $statsOk,
                'match_list' => $missingStats,
                'match_list_summary' => 'Spiele mit unzureichenden Playerstats',
            ],
            [
                'key' => 'match-playerstats-goals',
                'label' => 'Tore in Playerstats passen zum Ergebnis',
                'ok' => $playerstatsGoalsOk,
                'match_list' => $missingPlayerstatsGoals,
                'match_list_summary' => 'Spiele mit fehlenden/abweichenden Toren in Playerstats',
            ],
            [
                'key' => 'match-playerstats-ps-hits',
                'label' => 'Elfmeterschießen-Treffer in Playerstats passen zum Elfmeter-Ergebnis',
                'ok' => $psHitsOk,
                'match_list' => $missingPsHits,
                'match_list_summary' => 'Spiele mit fehlenden/abweichenden Elfmeter-Treffern in Playerstats',
            ],
        ];

        if ($isNewPointsMode) {
            $checklist[] = [
                'key' => 'match-ffb-goal',
                'label' => 'Neu: Tore in ffb_goal passen zum Ergebnis',
                'ok' => $tableGoalsOk,
                'match_list' => $missingTableGoals,
                'match_list_summary' => 'Spiele mit fehlenden/abweichenden Toren in ffb_goal',
            ];
            $checklist[] = [
                'key' => 'match-ffb-psgoal',
                'label' => 'Neu: Elfmeterschießen-Treffer in ffb_psgoal passen zum Elfmeter-Ergebnis',
                'ok' => $tablePsGoalsOk,
                'match_list' => $missingTablePsGoals,
                'match_list_summary' => 'Spiele mit fehlenden/abweichenden Elfmeter-Treffern in ffb_psgoal',
            ];
        }

        return [
            'key' => 'matchdata',
            'title' => 'Spieldaten',
            'ok' => $statsOk
                && $playerstatsGoalsOk
                && $psHitsOk
                && $tableGoalsOk
                && $tablePsGoalsOk,
            'checklist' => $checklist,
        ];
    }

    private function extremeteamSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'extremeteam',
                'title' => 'Top&Flop',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $now = Carbon::now();
        $pastRounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get(['matchround_id', 'matchround_title', 'matchround_startdate', 'matchround_enddate'])
            ->filter(fn (Matchround $round): bool => $this->matchroundPeriod($round, $now) === 'past')
            ->values();

        $pastRoundIds = $pastRounds
            ->map(fn (Matchround $round): int => (int) $round->matchround_id)
            ->all();

        $existingByRound = [];
        if ($pastRoundIds !== []) {
            $rows = Extremeteam::query()
                ->whereIn('extremeteam_matchround_id', $pastRoundIds)
                ->get([
                    'extremeteam_id',
                    'extremeteam_matchround_id',
                    'extremeteam_top_or_flop',
                    'extremeteam_price',
                    'extremeteam_score',
                ]);

            foreach ($rows as $row) {
                $roundId = (int) $row->extremeteam_matchround_id;
                $type = (string) $row->extremeteam_top_or_flop;
                $existingByRound[$roundId][$type] = $row;
            }
        }

        $missing = [];
        $invalid = [];
        foreach ($pastRounds as $round) {
            $roundId = (int) $round->matchround_id;
            $roundTitle = (string) $round->matchround_title;
            $hasTop = isset($existingByRound[$roundId]['top']);
            $hasFlop = isset($existingByRound[$roundId]['flop']);
            if (! $hasTop || ! $hasFlop) {
                $parts = [];
                if (! $hasTop) {
                    $parts[] = 'Top fehlt';
                }
                if (! $hasFlop) {
                    $parts[] = 'Flop fehlt';
                }

                $missing[] = [
                    'label' => $roundTitle,
                    'detail' => implode(' · ', $parts),
                ];
            }

            foreach (['top', 'flop'] as $type) {
                $team = $existingByRound[$roundId][$type] ?? null;
                if (! $team instanceof Extremeteam) {
                    continue;
                }

                $issues = $this->extremeTeams->complianceIssues($team);
                if ($issues === []) {
                    continue;
                }

                $invalid[] = [
                    'label' => $roundTitle.' · '.ucfirst($type),
                    'detail' => implode(' · ', $issues),
                ];
            }
        }

        $presenceOk = $missing === [];
        $complianceOk = $invalid === [];

        return [
            'key' => 'extremeteam',
            'title' => 'Top&Flop',
            'ok' => $presenceOk && $complianceOk,
            'checklist' => [
                [
                    'key' => 'extremeteam-past-rounds',
                    'label' => 'Vergangene Spielrunden haben Top- und Flop-Team',
                    'ok' => $presenceOk,
                    'match_list' => $missing,
                    'match_list_summary' => 'Spielrunden ohne Top/Flop',
                ],
                [
                    'key' => 'extremeteam-options',
                    'label' => 'Top/Flop-Teams erfüllen Limits und Credit-Rahmen',
                    'ok' => $complianceOk,
                    'match_list' => $invalid,
                    'match_list_summary' => 'Top/Flop außerhalb der Limits',
                ],
            ],
        ];
    }

    private function scoreSection(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return [
                'key' => 'score',
                'title' => 'Rangliste: 0 Mitspieler',
                'ok' => false,
                'checklist' => [],
            ];
        }

        $isLcMode = (string) (LeagueOptions::query()
            ->where('options_league_id', $leagueId)
            ->value('options_league_rankmode') ?: '') === 'lc';

        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $participantCount = $roundIds === []
            ? 0
            : (int) Userteam::query()
                ->whereIn('userteam_matchround_id', $roundIds)
                ->distinct()
                ->count('userteam_user_id');

        $title = 'Rangliste: '.$participantCount.' Mitspieler';

        $now = Carbon::now();
        $dueRoundIds = $this->scoreDueMatchroundIds($leagueId, $now);
        $dueRounds = $dueRoundIds === []
            ? collect()
            : Matchround::query()
                ->whereIn('matchround_id', $dueRoundIds)
                ->orderBy('matchround_startdate')
                ->orderBy('matchround_id')
                ->get(['matchround_id', 'matchround_title'])
                ->keyBy(fn (Matchround $round): int => (int) $round->matchround_id);

        $missingLineupScores = [];
        if ($dueRoundIds !== []) {
            $lineups = Userteam::query()
                ->with('user:user_id,user_nickname')
                ->whereIn('userteam_matchround_id', $dueRoundIds)
                ->orderBy('userteam_matchround_id')
                ->orderBy('userteam_user_id')
                ->get([
                    'userteam_id',
                    'userteam_user_id',
                    'userteam_matchround_id',
                    'userteam_score',
                    'userteam_lc_points',
                ]);

            foreach ($lineups as $lineup) {
                $missing = [];
                if ($lineup->userteam_score === null) {
                    $missing[] = 'Score fehlt';
                }
                if ($isLcMode && $lineup->userteam_lc_points === null) {
                    $missing[] = 'LC-Punkte fehlen';
                }
                if ($missing === []) {
                    continue;
                }

                $roundId = (int) $lineup->userteam_matchround_id;
                $roundTitle = (string) ($dueRounds->get($roundId)?->matchround_title ?? 'Runde #'.$roundId);
                $nickname = trim((string) ($lineup->user?->user_nickname ?? ''));
                $userLabel = $nickname !== ''
                    ? $nickname
                    : 'User #'.(int) $lineup->userteam_user_id;

                $missingLineupScores[] = [
                    'label' => $roundTitle.' · '.$userLabel,
                    'detail' => implode(' · ', $missing),
                ];
            }
        }

        $lineupScoresOk = $missingLineupScores === [];
        $lineupScoresLabel = $isLcMode
            ? 'Aufstellungen fälliger Spielrunden haben Score und LC-Punkte'
            : 'Aufstellungen fälliger Spielrunden haben Score';

        $userscoreMismatches = [];
        if ($roundIds !== []) {
            $sums = Userteam::query()
                ->whereIn('userteam_matchround_id', $roundIds)
                ->selectRaw('userteam_user_id, COALESCE(SUM(userteam_score), 0) as total_score, COALESCE(SUM(userteam_lc_points), 0) as total_lc')
                ->groupBy('userteam_user_id')
                ->orderBy('userteam_user_id')
                ->get()
                ->keyBy(fn ($row): int => (int) $row->userteam_user_id);

            $userscores = Userscore::query()
                ->with('user:user_id,user_nickname')
                ->where('userscore_league_id', $leagueId)
                ->get()
                ->keyBy(fn (Userscore $row): int => (int) $row->userscore_user_id);

            $nicknameByUserId = Userteam::query()
                ->with('user:user_id,user_nickname')
                ->whereIn('userteam_matchround_id', $roundIds)
                ->get(['userteam_id', 'userteam_user_id'])
                ->groupBy(fn (Userteam $row): int => (int) $row->userteam_user_id)
                ->map(static fn (Collection $group): string => trim((string) ($group->first()?->user?->user_nickname ?? '')));

            $userIds = $sums->keys()->merge($userscores->keys())->unique()->sort()->values();

            foreach ($userIds as $userId) {
                $uid = (int) $userId;
                $sumRow = $sums->get($uid);
                $expectedTotal = $sumRow !== null ? (int) $sumRow->total_score : 0;
                $expectedLc = $sumRow !== null ? (int) $sumRow->total_lc : 0;
                $scoreRow = $userscores->get($uid);
                $actualTotal = $scoreRow !== null ? (int) $scoreRow->userscore_total : null;
                $actualLc = $scoreRow !== null ? (int) $scoreRow->userscore_lc_points : null;

                $parts = [];
                if ($actualTotal === null) {
                    $parts[] = sprintf('userscore fehlt (erwartet Summe %d)', $expectedTotal);
                } elseif ($actualTotal !== $expectedTotal) {
                    $parts[] = sprintf('Total %d ≠ Summe userteam %d', $actualTotal, $expectedTotal);
                }

                if ($isLcMode) {
                    if ($actualLc === null) {
                        $parts[] = sprintf('LC fehlt (erwartet Summe %d)', $expectedLc);
                    } elseif ($actualLc !== $expectedLc) {
                        $parts[] = sprintf('LC %d ≠ Summe userteam %d', $actualLc, $expectedLc);
                    }
                }

                if ($parts === []) {
                    continue;
                }

                $nickname = trim((string) ($scoreRow?->user?->user_nickname ?? ''));
                if ($nickname === '') {
                    $nickname = (string) ($nicknameByUserId->get($uid) ?? '');
                }
                $userLabel = $nickname !== '' ? $nickname : 'User #'.$uid;

                $userscoreMismatches[] = [
                    'label' => $userLabel,
                    'detail' => implode(' · ', $parts),
                ];
            }
        }

        $userscoreOk = $userscoreMismatches === [];
        $userscoreLabel = $isLcMode
            ? 'Userscore Total/LC entspricht Summe der Aufstellungen'
            : 'Userscore Total entspricht Summe der Aufstellungen';

        return [
            'key' => 'score',
            'title' => $title,
            'ok' => $lineupScoresOk && $userscoreOk,
            'checklist' => [
                [
                    'key' => 'lineup-scores',
                    'label' => $lineupScoresLabel,
                    'ok' => $lineupScoresOk,
                    'match_list' => $missingLineupScores,
                    'match_list_summary' => 'Aufstellungen ohne Score',
                ],
                [
                    'key' => 'userscore-sums',
                    'label' => $userscoreLabel,
                    'ok' => $userscoreOk,
                    'match_list' => $userscoreMismatches,
                    'match_list_summary' => 'Userscores mit Abweichung',
                ],
            ],
        ];
    }

    /**
     * Past matchrounds whose matches are all past (day-only / timed grace).
     *
     * @return list<int>
     */
    private function scoreDueMatchroundIds(int $leagueId, Carbon $now): array
    {
        $rounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get(['matchround_id', 'matchround_startdate', 'matchround_enddate']);

        $pastRoundIds = $rounds
            ->filter(fn (Matchround $round): bool => $this->matchroundPeriod($round, $now) === 'past')
            ->map(fn (Matchround $round): int => (int) $round->matchround_id)
            ->values()
            ->all();

        if ($pastRoundIds === []) {
            return [];
        }

        $matchesByRound = MatchGame::query()
            ->whereIn('match_round', $pastRoundIds)
            ->get(['match_id', 'match_round', 'match_date'])
            ->groupBy(fn (MatchGame $match): int => (int) $match->match_round);

        $due = [];
        foreach ($pastRoundIds as $roundId) {
            /** @var Collection<int, MatchGame> $matches */
            $matches = $matchesByRound->get($roundId) ?? collect();
            if ($matches->isEmpty()) {
                continue;
            }

            $allReady = true;
            foreach ($matches as $match) {
                if (! $this->matchIsPast($match, $now)) {
                    $allReady = false;
                    break;
                }
            }

            if ($allReady) {
                $due[] = $roundId;
            }
        }

        return $due;
    }

    /**
     * A match is past once unknown-time fixtures are at least one calendar day old,
     * or timed fixtures are at least two hours past kickoff.
     */
    private function matchIsPast(MatchGame $match, Carbon $now): bool
    {
        $raw = $match->match_date;
        if ($raw === null || trim((string) $raw) === '') {
            return false;
        }

        $value = (string) $raw;
        if (! MatchGame::hasKnownKickoffTime($value)) {
            $calendar = MatchGame::calendarDate($value);
            if ($calendar === '') {
                return false;
            }

            return Carbon::parse($calendar)->startOfDay()->lt($now->copy()->startOfDay());
        }

        return Carbon::parse($value)->addHours(2)->lte($now);
    }

    private function matchHasResult(MatchGame $match): bool
    {
        return (int) ($match->match_homescore ?? -1) >= 0
            && (int) ($match->match_guestscore ?? -1) >= 0;
    }

    private function matchHasPenaltyShootoutResult(MatchGame $match): bool
    {
        return (int) ($match->match_homescore_penalty ?? -1) >= 0
            && (int) ($match->match_guestscore_penalty ?? -1) >= 0;
    }

    private function matchFixtureLabel(MatchGame $match): string
    {
        $home = trim((string) ($match->homeTeam?->team_name ?? ''));
        $guest = trim((string) ($match->guestTeam?->team_name ?? ''));
        $fixture = ($home !== '' ? $home : '?').' – '.($guest !== '' ? $guest : '?');
        $date = MatchGame::formatDisplayDate(
            $match->match_date !== null ? (string) $match->match_date : null
        );

        return $date !== null ? $fixture.' · '.$date : $fixture;
    }

    private function nextUpcomingMatchround(int $leagueId): ?Matchround
    {
        $now = Carbon::now();
        $rounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get();

        foreach ($rounds as $round) {
            if ($this->matchroundPeriod($round, $now) === 'future') {
                return $round;
            }
        }

        return null;
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

        return FfbDateTime::utcDbToDisplay((string) $value);
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
                    ['label' => 'Bank (min/max)', 'value' => (int) ($options->matchround_options_lineup_min_bench ?? 0).' / '.(int) ($options->matchround_options_lineup_max_bench ?? 0)],
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
                    ['label' => 'Bank (min/max)', 'value' => (int) ($options->options_lineup_min_bench ?? 0).' / '.(int) ($options->options_lineup_max_bench ?? 0)],
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
