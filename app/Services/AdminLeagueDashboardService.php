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
use App\Support\LeagueSymbol;
use App\Support\TeamShirt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminLeagueDashboardService
{
    private const AVERAGE_LINEUP_BUDGET_RATIO = 0.9;

    private const AVERAGE_LINEUP_WITH_BENCH_BUDGET_RATIO = 1.0;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly ExtremeTeamService $extremeTeams,
        private readonly LineupOptionsResolver $lineupOptions,
        private readonly SubstitutionCalculationService $substitutions,
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
        $sections = [
            $this->leagueSection($leagueId),
            $this->matchroundsSection($leagueId),
            $this->matchesSection($leagueId),
            $this->teamsSection($leagueId),
            $this->squadSection($leagueId),
            $this->playerpriceSection($leagueId),
            $this->matchdataSection($leagueId),
        ];

        $substitutions = $this->substitutionsSection($leagueId);
        if ($substitutions !== null) {
            $sections[] = $substitutions;
        }

        $sections[] = $this->extremeteamSection($leagueId);
        $sections[] = $this->scoreSection($leagueId);

        return $sections;
    }

    /**
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array{key: string, label: string, ok: bool, options_overview?: list<array{title: string, items: list<array{label: string, value: string}>}>, match_list?: list<array{label: string, detail: string}>, match_list_summary?: string}>
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
        $consistencyIssues = $hasOptions
            ? $this->leagueOptionsConsistencyEntries($league->options)
            : [];
        $optionsOk = $hasOptions && $consistencyIssues === [];
        $optionsOverview = $hasOptions
            ? $this->optionsOverview($league->options)
            : [];

        $optionsItem = [
            'key' => 'options',
            'label' => 'Liga-Optionen sind korrekt gesetzt',
            'ok' => $optionsOk,
            'options_overview' => $optionsOverview,
        ];
        if ($consistencyIssues !== []) {
            $optionsItem['match_list'] = $consistencyIssues;
            $optionsItem['match_list_summary'] = 'Inkonsistente Liga-Optionen';
        }

        $checklist = [
            [
                'key' => 'logo',
                'label' => 'Ein Liga-Logo ist vorhanden',
                'ok' => $hasLogo,
            ],
            [
                'key' => 'visible',
                'label' => 'Die Liga ist sichtbar',
                'ok' => $isVisible,
            ],
            [
                'key' => 'schedule',
                'label' => (bool) $league->league_archive
                    ? 'Die Liga ist archiviert und es sind nur vergangene Spielrunden vorhanden'
                    : 'Die Liga ist aktiv und es sind aktuelle oder zukünftige Spielrunden vorhanden',
                'ok' => $scheduleOk,
            ],
            $optionsItem,
        ];

        $ok = $hasLogo && $isVisible && $scheduleOk && $optionsOk;

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
     *     checklist: list<array{key: string, label: string, ok: bool, options_overview?: list<array{title: string, items: list<array{label: string, value: string}>}>, match_list?: list<array{label: string, detail: string}>, match_list_summary?: string}>
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
            ];
        }

        $leagueBenchMode = trim((string) (LeagueOptions::query()
            ->where('options_league_id', $leagueId)
            ->value('options_league_benchmode') ?? ''));
        $now = Carbon::now();
        $rounds = Matchround::query()
            ->with('options')
            ->withCount('matches')
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get();

        $checklist = [];
        $allRoundsHaveMatches = true;
        $hasActiveRound = false;
        $allRoundOptionsOk = true;
        $counts = [
            'current' => 0,
            'future' => 0,
            'past' => 0,
        ];

        foreach ($rounds as $round) {
            $period = $this->matchroundPeriod($round, $now);
            $counts[$period]++;

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

            if ($hasLineupOptions) {
                $consistencyIssues = $this->matchroundOptionsConsistencyEntries($options, $leagueBenchMode);
                $optionsOk = $consistencyIssues === [];
                if (! $optionsOk) {
                    $allRoundOptionsOk = false;
                }

                $optionsItem = [
                    'key' => 'round-options-'.(int) $round->matchround_id,
                    'label' => $title.': Runden-Optionen konsistent',
                    'ok' => $optionsOk,
                    'options_overview' => $this->matchroundLineupOptionsOverview($options),
                ];
                if ($consistencyIssues !== []) {
                    $optionsItem['match_list'] = $consistencyIssues;
                    $optionsItem['match_list_summary'] = 'Inkonsistente Runden-Optionen';
                }
                $checklist[] = $optionsItem;
            }
        }

        if ($rounds->isEmpty()) {
            $allRoundsHaveMatches = false;
        }

        $checklist[] = [
            'key' => 'active-round',
            'label' => 'Mindestens 1 aktive Spielrunde',
            'ok' => $hasActiveRound,
        ];

        $ok = $allRoundsHaveMatches && $hasActiveRound && $rounds->isNotEmpty() && $allRoundOptionsOk;

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
                'label' => 'Spiele sind vorhanden',
                'ok' => $hasMatches,
            ],
            [
                'key' => 'dates-within-rounds',
                'label' => 'Alle Anstoßzeiten liegen innerhalb der Spielrunden',
                'ok' => $datesWithinRounds,
                'match_list' => $outsideRoundDates,
                'match_list_summary' => 'Spiele mit Anstoßzeiten außerhalb der Spielrunden',
            ],
            [
                'key' => 'past-results',
                'label' => 'Alle vergangene Spiele haben Ergebnis und Spieldauer gesetzt',
                'ok' => $pastMatchesComplete,
                'match_list' => $incompletePastMatches,
                'match_list_summary' => 'Vergangene Spiele ohne Ergebnis oder Spieldauer',
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
        $leagueKey = League::query()->whereKey($leagueId)->value('asset_key');
        $leagueKey = is_string($leagueKey) && $leagueKey !== '' ? $leagueKey : null;

        $teams = $teamIds === []
            ? collect()
            : Team::query()
                ->whereIn('team_id', $teamIds)
                ->orderBy('team_name')
                ->orderBy('team_id')
                ->get(['team_id', 'team_name', 'team_nationality', 'team_status', 'asset_key']);

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

            if (! $this->teamHasJersey($team->asset_key !== null ? (string) $team->asset_key : null, $nationality, $leagueKey)) {
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
                    'label' => 'Alle teilnehmenden Mannschaften sind aktiv',
                    'ok' => $allActive,
                    'match_list' => $inactive,
                    'match_list_summary' => 'Inaktive Mannschaften',
                ],
                [
                    'key' => 'teams-flag',
                    'label' => 'Alle teilnehmenden Mannschaften haben ein Logo oder Flagge',
                    'ok' => $allHaveFlag,
                    'match_list' => $missingFlag,
                    'match_list_summary' => 'Mannschaften ohne Logo oder Flagge',
                ],
                [
                    'key' => 'teams-jersey',
                    'label' => 'Alle teilnehmenden Mannschaften haben ein Trikot',
                    'ok' => $allHaveJersey,
                    'match_list' => $missingJersey,
                    'match_list_summary' => 'Mannschaften ohne Trikot',
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
                    'label' => 'In jeder Mannschaft sind alle Positionen (G/D/M/S) vorhanden',
                    'ok' => $allPositions,
                    'match_list' => $missingPositions,
                    'match_list_summary' => 'Mannschaften mit fehlenden Positionen',
                ],
                [
                    'key' => 'squad-unique-players',
                    'label' => 'Kein Spieler befindet sich gleichzeitig in mehr als einer Mannschaft',
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
        $averageLineup = $this->averageLineupBudgetEntries($leagueId, $isDynamic, $roundIds, false);
        $averageLineupOk = (bool) ($averageLineup['ok'] ?? false);
        $benchModeOn = $this->leagueHasBenchMode($leagueId);
        $averageLineupWithBench = $benchModeOn
            ? $this->averageLineupBudgetEntries($leagueId, $isDynamic, $roundIds, true)
            : ['ok' => true, 'entries' => []];
        $averageLineupWithBenchOk = (bool) ($averageLineupWithBench['ok'] ?? false);

        $checklist = [
            [
                'key' => 'team-prices',
                'label' => 'Teampreis ist für jede Mannschaft gesetzt',
                'ok' => $teamPricesOk,
                'match_list' => $missingTeamPrices,
                'match_list_summary' => 'Mannschaften ohne Teampreis',
            ],
            [
                'key' => 'round-performance',
                'label' => 'Dynamisch: Runden-Performance für vergangene Spiele sind für alle Spieler gesetzt',
                'ok' => $performanceOk,
                'match_list' => $missingPerformance,
                'match_list_summary' => 'Spieler ohne Runden-Performance',
            ],
            [
                'key' => 'player-prices',
                'label' => 'Dynamisch: Spielerpreise sind für alle aktiven Spieler für die nächste Spielrunde gesetzt',
                'ok' => $playerPricesOk,
                'match_list' => $missingPlayerPrices,
                'match_list_summary' => 'Aktive Spieler ohne Spielerpreis',
            ],
            [
                'key' => 'average-lineup-budget',
                'label' => 'Durchschnittliche Aufstellung kostet ≤ 90% des Budgets',
                'ok' => $averageLineupOk,
                'info_list' => $averageLineup['entries'],
                'info_list_summary' => 'Kosten für eine durchschnittliche Aufstellung',
            ],
        ];

        if ($benchModeOn) {
            $checklist[] = [
                'key' => 'average-lineup-budget-with-bench',
                'label' => 'Durchschnittliche Aufstellung inkl. Ersatzspieler kostet ≤ 100% des Budgets',
                'ok' => $averageLineupWithBenchOk,
                'info_list' => $averageLineupWithBench['entries'],
                'info_list_summary' => 'Kosten für eine durchschnittliche Aufstellung inkl. Ersatzspieler',
            ];
        }

        return [
            'key' => 'playerprice',
            'title' => 'Preis/Performance',
            'ok' => $teamPricesOk && $performanceOk && $playerPricesOk && $averageLineupOk && $averageLineupWithBenchOk,
            'checklist' => $checklist,
        ];
    }

    /**
     * @param  list<int>  $roundIds
     * @return array{
     *     ok: bool,
     *     entries: list<array{
     *         label: string,
     *         detail: string
     *     }>
     * }
     */
    private function averageLineupBudgetEntries(int $leagueId, bool $isDynamic, array $roundIds, bool $withBench): array
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
        $maxRatio = $withBench
            ? self::AVERAGE_LINEUP_WITH_BENCH_BUDGET_RATIO
            : self::AVERAGE_LINEUP_BUDGET_RATIO;

        foreach ($rounds as $round) {
            $roundId = (int) $round->matchround_id;
            $roundTitle = trim((string) $round->matchround_title);
            $label = $roundTitle !== '' ? $roundTitle : 'Spielrunde #'.$roundId;

            $result = $this->averageLineupForMatchround($leagueId, $roundId, $withBench);
            if ($result === null) {
                $allOk = false;
                $entries[] = [
                    'label' => $label,
                    'detail' => $withBench
                        ? 'keine gültige Durchschnitts-Aufstellung inkl. Ersatzspieler möglich'
                        : 'keine gültige Durchschnitts-Aufstellung möglich',
                ];

                continue;
            }

            $budget = (float) $result['budget'];
            $cost = (float) $result['cost'];
            $ratio = $budget > 0.0 ? ($cost / $budget) : 0.0;
            $percent = round($ratio * 100, 1);
            $percentLabel = $this->formatCredits($percent);
            $withinBudget = $ratio <= $maxRatio;
            if (! $withinBudget) {
                $allOk = false;
            }

            $entries[] = [
                'label' => $label,
                'detail' => $percentLabel.'% des Budgets ('
                    .$this->formatCredits($cost).' / '.$this->formatCredits($budget).')',
                'detail_percent' => $percentLabel,
                'detail_percent_alert' => ! $withinBudget,
                'detail_rest' => '% des Budgets ('
                    .$this->formatCredits($cost).' / '.$this->formatCredits($budget).')',
            ];
        }

        return [
            'ok' => $allOk,
            'entries' => $entries,
        ];
    }

    private function leagueHasBenchMode(int $leagueId): bool
    {
        if ($leagueId <= 0) {
            return false;
        }

        $mode = trim((string) (LeagueOptions::query()
            ->where('options_league_id', $leagueId)
            ->value('options_league_benchmode') ?? ''));

        return in_array($mode, ['cover', 'bestof'], true);
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
    private function averageLineupForMatchround(int $leagueId, int $matchroundId, bool $withBench = false): ?array
    {
        $options = $this->lineupOptions->forMatchround($matchroundId);
        $budget = (float) $options['lineup_max_credits'];
        $formations = $this->lineupFormationsFromOptions($options);
        if ($formations === [] || $budget <= 0.0) {
            return null;
        }

        $maxBench = (int) ($options['lineup_max_bench'] ?? 0);
        $benchMode = $options['league_benchmode'] ?? null;
        if ($withBench && ($benchMode === null || $maxBench < 1)) {
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

            if ($withBench) {
                $benchPicks = $this->pickAverageBenchPlayers($picks, $byPosition, $maxBench, $maxPerTeam);
                if ($benchPicks === null) {
                    continue;
                }
                $picks = array_merge($picks, $benchPicks);
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
     * @param  list<array{playerteam_id: int, team_id: int, name: string, team: string, price: float}>  $starters
     * @param  array<string, list<array{playerteam_id: int, team_id: int, name: string, team: string, price: float}>>  $byPosition
     * @return list<array{playerteam_id: int, team_id: int, name: string, team: string, price: float}>|null
     */
    private function pickAverageBenchPlayers(array $starters, array $byPosition, int $maxBench, int $maxPerTeam): ?array
    {
        if ($maxBench < 1) {
            return null;
        }

        $used = [];
        $teamCounts = [];
        foreach ($starters as $starter) {
            $used[(int) $starter['playerteam_id']] = true;
            $teamId = (int) $starter['team_id'];
            $teamCounts[$teamId] = ($teamCounts[$teamId] ?? 0) + 1;
        }

        $pool = [];
        foreach (['g', 'd', 'm', 's'] as $pos) {
            foreach ($byPosition[$pos] ?? [] as $player) {
                $playerteamId = (int) $player['playerteam_id'];
                if (isset($used[$playerteamId])) {
                    continue;
                }
                $pool[] = $player;
            }
        }

        usort($pool, static function (array $a, array $b): int {
            $byPrice = $a['price'] <=> $b['price'];
            if ($byPrice !== 0) {
                return $byPrice;
            }

            return $a['playerteam_id'] <=> $b['playerteam_id'];
        });

        $order = $this->medianOutwardIndexes(count($pool));
        $picks = [];
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
            if (count($picks) >= $maxBench) {
                break;
            }
        }

        if (count($picks) < $maxBench) {
            return null;
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
        $pastRoundIds = Matchround::query()
            ->whereIn('matchround_id', $roundIds)
            ->get(['matchround_id', 'matchround_startdate', 'matchround_enddate'])
            ->filter(fn (Matchround $round): bool => $this->matchroundPeriod($round, $now) === 'past')
            ->map(fn (Matchround $round): int => (int) $round->matchround_id)
            ->values()
            ->all();

        if ($pastRoundIds === []) {
            return [];
        }

        $pastMatchIds = MatchGame::query()
            ->whereIn('match_round', $pastRoundIds)
            ->pluck('match_id')
            ->map(fn ($id): int => (int) $id)
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
        // Prices for the next round are only due once no round is still current.
        if ($this->leagueHasCurrentMatchround($leagueId)) {
            return [];
        }

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
        $missingGoals = [];
        $missingPsGoals = [];

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
                $statsMatchResult = $homeFromStats === $homeScore && $guestFromStats === $guestScore;

                $homeFromTable = null;
                $guestFromTable = null;
                $tableMatchResult = true;
                if ($isNewPointsMode) {
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
                    $tableMatchResult = $homeFromTable === $homeScore && $guestFromTable === $guestScore;
                }

                if (! $statsMatchResult || ! $tableMatchResult) {
                    $detail = $isNewPointsMode
                        ? sprintf(
                            'Ergebnis %d:%d · Spielerdaten %d:%d · ffb_goal %d:%d',
                            $homeScore,
                            $guestScore,
                            $homeFromStats,
                            $guestFromStats,
                            (int) $homeFromTable,
                            (int) $guestFromTable,
                        )
                        : sprintf(
                            'Ergebnis %d:%d · Tore in playerstats Heim %d / Gast %d (erwartet %d / %d)',
                            $homeScore,
                            $guestScore,
                            $homeFromStats,
                            $guestFromStats,
                            $homeScore,
                            $guestScore,
                        );

                    $missingGoals[] = [
                        'label' => $label,
                        'detail' => $detail,
                    ];
                }
            }

            if ($this->matchHasPenaltyShootoutResult($match)) {
                $homePs = (int) $match->match_homescore_penalty;
                $guestPs = (int) $match->match_guestscore_penalty;
                $homeHits = $psHitsByTeam[$homeId] ?? 0;
                $guestHits = $psHitsByTeam[$guestId] ?? 0;
                $statsMatchPs = $homeHits === $homePs && $guestHits === $guestPs;

                $homeTableHits = null;
                $guestTableHits = null;
                $tableMatchPs = true;
                if ($isNewPointsMode) {
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
                    $tableMatchPs = $homeTableHits === $homePs && $guestTableHits === $guestPs;
                }

                if (! $statsMatchPs || ! $tableMatchPs) {
                    $detail = $isNewPointsMode
                        ? sprintf(
                            'Elfmeter %d:%d · Spielerdaten %d:%d · ffb_psgoal %d:%d',
                            $homePs,
                            $guestPs,
                            $homeHits,
                            $guestHits,
                            (int) $homeTableHits,
                            (int) $guestTableHits,
                        )
                        : sprintf(
                            'Elfmeter %d:%d · Treffer in playerstats Heim %d / Gast %d (erwartet %d / %d)',
                            $homePs,
                            $guestPs,
                            $homeHits,
                            $guestHits,
                            $homePs,
                            $guestPs,
                        );

                    $missingPsGoals[] = [
                        'label' => $label,
                        'detail' => $detail,
                    ];
                }
            }
        }

        $statsOk = $missingStats === [];
        $goalsOk = $missingGoals === [];
        $psGoalsOk = $missingPsGoals === [];

        return [
            'key' => 'matchdata',
            'title' => 'Spieldaten',
            'ok' => $statsOk && $goalsOk && $psGoalsOk,
            'checklist' => [
                [
                    'key' => 'match-playerstats',
                    'label' => 'Für jedes Spiel gibt es mindestens 11 Spieler je Mannschaft mit Spielerdaten',
                    'ok' => $statsOk,
                    'match_list' => $missingStats,
                    'match_list_summary' => 'Spiele mit unzureichenden Spielerdaten',
                ],
                [
                    'key' => 'match-goals',
                    'label' => $isNewPointsMode
                        ? 'Tore aus Ergebnis, Spielerdaten und ffb_goal stimmen überein'
                        : 'Die Anzahl der Tore in den Spielerdaten passt zum Ergebnis',
                    'ok' => $goalsOk,
                    'match_list' => $missingGoals,
                    'match_list_summary' => $isNewPointsMode
                        ? 'Spiele mit abweichender Tor-Anzahl zwischen Ergebnis, Spielerdaten und ffb_goal'
                        : 'Spiele mit abweichender Tor-Anzahl zwischen Ergebnis und Spielerdaten',
                ],
                [
                    'key' => 'match-ps-goals',
                    'label' => $isNewPointsMode
                        ? 'Elfmeter-Treffer aus Ergebnis, Spielerdaten und ffb_psgoal stimmen überein'
                        : 'Die Anzahl der Elfer-Treffer in den Spielerdaten passt zum Elfmeterschießen-Ergebnis',
                    'ok' => $psGoalsOk,
                    'match_list' => $missingPsGoals,
                    'match_list_summary' => $isNewPointsMode
                        ? 'Spiele mit abweichender Elfer-Treffer-Anzahl zwischen Ergebnis, Spielerdaten und ffb_psgoal'
                        : 'Spiele mit abweichender Elfer-Treffer-Anzahl zwischen Elfmeterschießen-Ergebnis und Spielerdaten',
                ],
            ],
        ];
    }

    /**
     * Present only when the league has an active bench mode (cover/bestof).
     *
     * @return array{
     *     key: string,
     *     title: string,
     *     ok: bool,
     *     checklist: list<array<string, mixed>>
     * }|null
     */
    private function substitutionsSection(int $leagueId): ?array
    {
        if ($leagueId <= 0 || ! $this->leagueHasBenchMode($leagueId)) {
            return null;
        }

        $now = Carbon::now();
        $pastRounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get(['matchround_id', 'matchround_title', 'matchround_startdate', 'matchround_enddate'])
            ->filter(fn (Matchround $round): bool => $this->matchroundPeriod($round, $now) === 'past')
            ->values();

        $missing = [];
        $applicableCount = 0;

        foreach ($pastRounds as $round) {
            $roundId = (int) $round->matchround_id;
            $resolved = $this->lineupOptions->forMatchround($roundId);
            $maxBench = (int) ($resolved['lineup_max_bench'] ?? 0);
            if ($maxBench <= 0) {
                continue;
            }

            $applicableCount++;
            $roundTitle = trim((string) $round->matchround_title);
            $label = $roundTitle !== '' ? $roundTitle : 'Spielrunde #'.$roundId;

            $built = $this->substitutions->previewForRound($leagueId, $roundId);
            if (! ($built['ok'] ?? false)) {
                $missing[] = [
                    'label' => $label,
                    'detail' => implode(' · ', $built['errors'] ?? ['Auswechslungen nicht prüfbar']),
                ];

                continue;
            }

            /** @var list<array<string, mixed>> $rows */
            $rows = is_array($built['preview']['rows'] ?? null) ? $built['preview']['rows'] : [];
            $mismatchCount = 0;
            $expectedTotal = 0;

            foreach ($rows as $row) {
                $expectedBySub = [];
                $subs = is_array($row['substitutions'] ?? null) ? $row['substitutions'] : [];
                foreach ($subs as $sub) {
                    $subId = (int) ($sub['substitute_playerteam_id'] ?? 0);
                    $outId = (int) ($sub['out_playerteam_id'] ?? 0);
                    if ($subId > 0 && $outId > 0) {
                        $expectedBySub[$subId] = $outId;
                    }
                }
                $expectedTotal += count($expectedBySub);

                $previous = is_array($row['previous_replaces'] ?? null) ? $row['previous_replaces'] : [];
                foreach ($previous as $subId => $actualReplace) {
                    $want = $expectedBySub[(int) $subId] ?? null;
                    $actual = $actualReplace !== null ? (int) $actualReplace : null;
                    if ($actual !== $want) {
                        $mismatchCount++;
                    }
                }

                foreach ($expectedBySub as $subId => $outId) {
                    if (! array_key_exists($subId, $previous)) {
                        $mismatchCount++;
                    }
                }
            }

            if ($mismatchCount === 0) {
                continue;
            }

            $missing[] = [
                'label' => $label,
                'detail' => $expectedTotal > 0
                    ? sprintf('Auswechslungen nicht berechnet (erwartet %d Wechsel)', $expectedTotal)
                    : 'Gespeicherte Auswechslungen weichen von der Berechnung ab',
            ];
        }

        $ok = $missing === [];
        $title = $applicableCount === 1
            ? 'Auswechslungen: 1 vergangene Spielrunde'
            : 'Auswechslungen: '.$applicableCount.' vergangene Spielrunden';

        return [
            'key' => 'substitutions',
            'title' => $title,
            'ok' => $ok,
            'checklist' => [
                [
                    'key' => 'substitutions-calculated',
                    'label' => 'Alle Auswechslungen für vergangene Spielrunden wurden berechnet',
                    'ok' => $ok,
                    'match_list' => $missing,
                    'match_list_summary' => 'Spielrunden ohne berechnete Auswechslungen',
                ],
            ],
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
                    'label' => 'Vergangene Spielrunden haben je ein Top- und Flop-Team gesetzt',
                    'ok' => $presenceOk,
                    'match_list' => $missing,
                    'match_list_summary' => 'Spielrunden ohne Top/Flop',
                ],
                [
                    'key' => 'extremeteam-options',
                    'label' => 'Top/Flop-Teams erfüllen definierte Limits und Budget',
                    'ok' => $complianceOk,
                    'match_list' => $invalid,
                    'match_list_summary' => 'Top/Flop außerhalb der definierten Limits',
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

        $lineupScoreMismatches = [];
        if ($dueRoundIds !== []) {
            $lineups = $this->userteamsWithSlotsForRounds($dueRoundIds);
            $expectedScoresByUserteamId = $this->expectedUserteamScoresFromPlayerstats($lineups, $dueRoundIds);

            foreach ($lineups as $lineup) {
                $userteamId = (int) $lineup->userteam_id;
                $roundId = (int) $lineup->userteam_matchround_id;
                $expectedScore = $expectedScoresByUserteamId[$userteamId] ?? 0;

                $detail = null;
                $actualScore = $lineup->userteam_score;
                if ($actualScore === null) {
                    $detail = sprintf('Score fehlt (erwartet Summe Spieler %d)', $expectedScore);
                } elseif ((int) $actualScore !== $expectedScore) {
                    $detail = sprintf('Score %d ≠ Summe Spieler %d', (int) $actualScore, $expectedScore);
                }

                if ($detail === null) {
                    continue;
                }

                $lineupScoreMismatches[] = [
                    'label' => $this->lineupMatchListLabel($dueRounds, $roundId, $lineup),
                    'detail' => $detail,
                ];
            }
        }

        $lineupScoresOk = $lineupScoreMismatches === [];

        $lineupLcMismatches = [];
        $lineupLcOk = true;
        if ($isLcMode) {
            $lineupLcMismatches = $this->lineupLcPointMismatches($leagueId, $now);
            $lineupLcOk = $lineupLcMismatches === [];
        }

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
            ? 'Gesamtscore und LigaCup-Punkte für alle Mitspieler entsprechen jeweils der Summe ihrer Aufstellungs-Scores'
            : 'Gesamtscore für alle Mitspieler entspricht jeweils der Summe ihrer Aufstellungs-Scores';

        $checklist = [
            [
                'key' => 'lineup-scores',
                'label' => 'Für Aufstellungen vergangener Runden entspricht der Score der Summe der Spieler-Scores',
                'ok' => $lineupScoresOk,
                'match_list' => $lineupScoreMismatches,
                'match_list_summary' => 'Aufstellungen mit Score-Abweichung',
            ],
        ];

        if ($isLcMode) {
            $checklist[] = [
                'key' => 'lineup-lc-points',
                'label' => 'LigaCup-Punkte beendeter Runden sind nach Rang korrekt verteilt',
                'ok' => $lineupLcOk,
                'match_list' => $lineupLcMismatches,
                'match_list_summary' => 'Aufstellungen mit inkorrekten LigaCup-Punkten',
            ];
        }

        $checklist[] = [
            'key' => 'userscore-sums',
            'label' => $userscoreLabel,
            'ok' => $userscoreOk,
            'match_list' => $userscoreMismatches,
            'match_list_summary' => 'Userscores mit Abweichung',
        ];

        return [
            'key' => 'score',
            'title' => $title,
            'ok' => $lineupScoresOk && $lineupLcOk && $userscoreOk,
            'checklist' => $checklist,
        ];
    }

    /**
     * @param  list<int>  $roundIds
     * @return Collection<int, Userteam>
     */
    private function userteamsWithSlotsForRounds(array $roundIds): Collection
    {
        if ($roundIds === []) {
            return collect();
        }

        return Userteam::query()
            ->with([
                'user:user_id,user_nickname',
                'slots' => static fn ($query) => $query
                    ->where('userteam_slot_playerteam_id', '>', 0)
                    ->orderBy('userteam_slot_slot')
                    ->select([
                        'userteam_slot_id',
                        'userteam_slot_userteam_id',
                        'userteam_slot_slot',
                        'userteam_slot_playerteam_id',
                    ]),
            ])
            ->whereIn('userteam_matchround_id', $roundIds)
            ->orderBy('userteam_matchround_id')
            ->orderBy('userteam_user_id')
            ->get([
                'userteam_id',
                'userteam_user_id',
                'userteam_matchround_id',
                'userteam_score',
                'userteam_lc_points',
            ]);
    }

    /**
     * Expected userteam score = sum of playerstats_score for starter slots (same as AdminScoreService).
     *
     * @param  Collection<int, Userteam>  $lineups
     * @param  list<int>  $roundIds
     * @return array<int, int>
     */
    private function expectedUserteamScoresFromPlayerstats(Collection $lineups, array $roundIds): array
    {
        if ($lineups->isEmpty() || $roundIds === []) {
            return [];
        }

        $playerteamIds = $lineups
            ->flatMap(static fn (Userteam $lineup): Collection => $lineup->slots
                ->pluck('userteam_slot_playerteam_id')
                ->map(static fn ($id): int => (int) $id))
            ->unique()
            ->values()
            ->all();

        /** @var array<string, int> $playerScoreByRoundAndPlayerteam */
        $playerScoreByRoundAndPlayerteam = [];
        if ($playerteamIds !== []) {
            $statRows = Playerstats::query()
                ->whereIn('playerstats_matchround_id', $roundIds)
                ->whereIn('playerstats_playerteam_id', $playerteamIds)
                ->selectRaw('playerstats_matchround_id, playerstats_playerteam_id, COALESCE(SUM(playerstats_score), 0) as total_score')
                ->groupBy('playerstats_matchround_id', 'playerstats_playerteam_id')
                ->get();

            foreach ($statRows as $statRow) {
                $key = (int) $statRow->playerstats_matchround_id.':'.(int) $statRow->playerstats_playerteam_id;
                $playerScoreByRoundAndPlayerteam[$key] = (int) $statRow->total_score;
            }
        }

        /** @var array<int, int> $expectedByUserteamId */
        $expectedByUserteamId = [];
        foreach ($lineups as $lineup) {
            $roundId = (int) $lineup->userteam_matchround_id;
            $expectedScore = 0;
            foreach ($lineup->slots as $slot) {
                $playerteamId = (int) $slot->userteam_slot_playerteam_id;
                $key = $roundId.':'.$playerteamId;
                $expectedScore += $playerScoreByRoundAndPlayerteam[$key] ?? 0;
            }
            $expectedByUserteamId[(int) $lineup->userteam_id] = $expectedScore;
        }

        return $expectedByUserteamId;
    }

    /**
     * LC distribution for finished rounds (enddate < now), ranked by expected player-sum scores.
     *
     * @return list<array{label: string, detail: string}>
     */
    private function lineupLcPointMismatches(int $leagueId, Carbon $now): array
    {
        $options = LeagueOptions::query()->where('options_league_id', $leagueId)->first()
            ?? LeagueOptions::query()->where('options_league_id', 0)->first();

        $raw = trim((string) ($options?->options_league_lcpoints ?? ''));
        if ($raw === '') {
            return [];
        }

        $lcPoints = array_map(
            static fn (string $value): int => (int) trim($value),
            explode(',', $raw)
        );
        if ($lcPoints === []) {
            return [];
        }

        $finishedRounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_enddate', '<', $now->format('Y-m-d H:i:s'))
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get(['matchround_id', 'matchround_title'])
            ->keyBy(fn (Matchround $round): int => (int) $round->matchround_id);

        $finishedRoundIds = $finishedRounds->keys()->map(static fn ($id): int => (int) $id)->all();
        if ($finishedRoundIds === []) {
            return [];
        }

        $lineups = $this->userteamsWithSlotsForRounds($finishedRoundIds);
        if ($lineups->isEmpty()) {
            return [];
        }

        $expectedScoresByUserteamId = $this->expectedUserteamScoresFromPlayerstats($lineups, $finishedRoundIds);
        $expectedLcByUserteamId = $this->computeLcPointsByUserteamId(
            $lcPoints,
            $expectedScoresByUserteamId,
            $lineups,
        );

        $mismatches = [];
        foreach ($lineups as $lineup) {
            $userteamId = (int) $lineup->userteam_id;
            if (! array_key_exists($userteamId, $expectedLcByUserteamId)) {
                continue;
            }

            $expectedLc = $expectedLcByUserteamId[$userteamId];
            $actualLc = $lineup->userteam_lc_points;
            if ($actualLc === null) {
                $detail = sprintf('LC fehlt (erwartet %d)', $expectedLc);
            } elseif ((int) $actualLc !== $expectedLc) {
                $detail = sprintf('LC %d ≠ erwartet %d', (int) $actualLc, $expectedLc);
            } else {
                continue;
            }

            $roundId = (int) $lineup->userteam_matchround_id;
            $mismatches[] = [
                'label' => $this->lineupMatchListLabel($finishedRounds, $roundId, $lineup),
                'detail' => $detail,
            ];
        }

        return $mismatches;
    }

    /**
     * @param  list<int>  $lcPoints
     * @param  array<int, int>  $scoresByUserteamId
     * @param  Collection<int, Userteam>  $userteams
     * @return array<int, int>
     */
    private function computeLcPointsByUserteamId(array $lcPoints, array $scoresByUserteamId, Collection $userteams): array
    {
        /** @var array<int, list<Userteam>> $byRound */
        $byRound = [];
        foreach ($userteams as $userteam) {
            $byRound[(int) $userteam->userteam_matchround_id][] = $userteam;
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

    /**
     * @param  Collection<int, Matchround>  $roundsById
     */
    private function lineupMatchListLabel(Collection $roundsById, int $roundId, Userteam $lineup): string
    {
        $roundTitle = (string) ($roundsById->get($roundId)?->matchround_title ?? 'Runde #'.$roundId);
        $nickname = trim((string) ($lineup->user?->user_nickname ?? ''));
        $userLabel = $nickname !== ''
            ? $nickname
            : 'User #'.(int) $lineup->userteam_user_id;

        return $roundTitle.' · '.$userLabel;
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

    private function leagueHasCurrentMatchround(int $leagueId): bool
    {
        $now = Carbon::now();
        $rounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->get(['matchround_startdate', 'matchround_enddate']);

        foreach ($rounds as $round) {
            if ($this->matchroundPeriod($round, $now) === 'current') {
                return true;
            }
        }

        return false;
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

    private function teamHasJersey(?string $teamKey, string $nationality, ?string $leagueKey): bool
    {
        return TeamShirt::relativePath($teamKey, $nationality, $leagueKey) !== null;
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
        return LeagueSymbol::exists($league->asset_key !== null ? (string) $league->asset_key : null);
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
     * @return list<array{label: string, detail: string}>
     */
    private function leagueOptionsConsistencyEntries(LeagueOptions $options): array
    {
        $benchMode = trim((string) ($options->options_league_benchmode ?? ''));
        $entries = $this->lineupLimitsConsistencyEntries([
            'max_players' => (int) ($options->options_lineup_max_players ?? 0),
            'max_credits' => (int) ($options->options_lineup_max_credits ?? 0),
            'max_players_team' => (int) ($options->options_lineup_max_players_team ?? 0),
            'min_g' => (int) ($options->options_lineup_min_g ?? 0),
            'max_g' => (int) ($options->options_lineup_max_g ?? 0),
            'min_d' => (int) ($options->options_lineup_min_d ?? 0),
            'max_d' => (int) ($options->options_lineup_max_d ?? 0),
            'min_m' => (int) ($options->options_lineup_min_m ?? 0),
            'max_m' => (int) ($options->options_lineup_max_m ?? 0),
            'min_s' => (int) ($options->options_lineup_min_s ?? 0),
            'max_s' => (int) ($options->options_lineup_max_s ?? 0),
            'min_bench' => (int) ($options->options_lineup_min_bench ?? 0),
            'max_bench' => (int) ($options->options_lineup_max_bench ?? 0),
        ], $benchMode);

        $thresholdLower = (int) ($options->options_score_minutes_threshold_lower ?? 0);
        $thresholdUpper = (int) ($options->options_score_minutes_threshold_upper ?? 0);
        $remindHours = (int) ($options->options_league_remind_hours_before ?? 0);
        $rankMode = trim((string) ($options->options_league_rankmode ?? ''));
        $priceMode = trim((string) ($options->options_league_pricemode ?? ''));
        $lcPointsRaw = trim((string) ($options->options_league_lcpoints ?? ''));

        if ($thresholdLower > $thresholdUpper) {
            $entries[] = [
                'label' => 'Minuten-Schwellen',
                'detail' => 'untere Schwelle ('.$thresholdLower.') > obere Schwelle ('.$thresholdUpper.')',
            ];
        }

        if (! in_array($rankMode, ['lc', 'points'], true)) {
            $entries[] = [
                'label' => 'Rangliste',
                'detail' => 'ungültiger Modus ('.($rankMode !== '' ? $rankMode : 'leer').')',
            ];
        }

        if (! in_array($priceMode, ['dynamic', 'static', 'constant'], true)) {
            $entries[] = [
                'label' => 'Preisberechnung',
                'detail' => 'ungültiger Modus ('.($priceMode !== '' ? $priceMode : 'leer').')',
            ];
        }

        if (! in_array($benchMode, ['', 'cover', 'bestof'], true)) {
            $entries[] = [
                'label' => 'Ersatzbank-Modus',
                'detail' => 'ungültiger Modus ('.$benchMode.')',
            ];
        }

        if ($lcPointsRaw === '') {
            $entries[] = [
                'label' => 'LigaCup Punkte',
                'detail' => 'Liste ist leer',
            ];
        } else {
            $parts = array_map(
                static fn (string $part): string => trim($part),
                explode(',', $lcPointsRaw)
            );
            $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));
            $allIntegers = true;
            $values = [];
            foreach ($parts as $part) {
                if (! preg_match('/^-?\d+$/', $part)) {
                    $allIntegers = false;
                    break;
                }
                $values[] = (int) $part;
            }

            if (! $allIntegers || $parts === []) {
                $entries[] = [
                    'label' => 'LigaCup Punkte',
                    'detail' => 'müssen kommagetrennte Ganzzahlen sein (aktuell '.$lcPointsRaw.')',
                ];
            } else {
                for ($i = 1, $count = count($values); $i < $count; $i++) {
                    if ($values[$i] > $values[$i - 1]) {
                        $entries[] = [
                            'label' => 'LigaCup Punkte',
                            'detail' => 'sind nicht absteigend (aktuell '.$lcPointsRaw.')',
                        ];
                        break;
                    }
                }
            }
        }

        if ($remindHours < 0) {
            $entries[] = [
                'label' => 'Erinnerung (h)',
                'detail' => 'darf nicht negativ sein (aktuell '.$remindHours.')',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array{label: string, detail: string}>
     */
    private function matchroundOptionsConsistencyEntries(MatchroundOptions $options, string $leagueBenchMode): array
    {
        return $this->lineupLimitsConsistencyEntries([
            'max_players' => (int) ($options->matchround_options_lineup_max_players ?? 0),
            'max_credits' => (int) ($options->matchround_options_lineup_max_credits ?? 0),
            'max_players_team' => (int) ($options->matchround_options_lineup_max_players_team ?? 0),
            'min_g' => (int) ($options->matchround_options_lineup_min_g ?? 0),
            'max_g' => (int) ($options->matchround_options_lineup_max_g ?? 0),
            'min_d' => (int) ($options->matchround_options_lineup_min_d ?? 0),
            'max_d' => (int) ($options->matchround_options_lineup_max_d ?? 0),
            'min_m' => (int) ($options->matchround_options_lineup_min_m ?? 0),
            'max_m' => (int) ($options->matchround_options_lineup_max_m ?? 0),
            'min_s' => (int) ($options->matchround_options_lineup_min_s ?? 0),
            'max_s' => (int) ($options->matchround_options_lineup_max_s ?? 0),
            'min_bench' => (int) ($options->matchround_options_lineup_min_bench ?? 0),
            'max_bench' => (int) ($options->matchround_options_lineup_max_bench ?? 0),
        ], $leagueBenchMode, allowZeroBenchMax: true);
    }

    /**
     * @param  array{
     *     max_players: int,
     *     max_credits: int,
     *     max_players_team: int,
     *     min_g: int,
     *     max_g: int,
     *     min_d: int,
     *     max_d: int,
     *     min_m: int,
     *     max_m: int,
     *     min_s: int,
     *     max_s: int,
     *     min_bench: int,
     *     max_bench: int
     * }  $limits
     * @return list<array{label: string, detail: string}>
     */
    private function lineupLimitsConsistencyEntries(array $limits, string $benchMode, bool $allowZeroBenchMax = false): array
    {
        $entries = [];

        $maxPlayers = $limits['max_players'];
        $maxCredits = $limits['max_credits'];
        $maxPlayersTeam = $limits['max_players_team'];
        $minG = $limits['min_g'];
        $maxG = $limits['max_g'];
        $minD = $limits['min_d'];
        $maxD = $limits['max_d'];
        $minM = $limits['min_m'];
        $maxM = $limits['max_m'];
        $minS = $limits['min_s'];
        $maxS = $limits['max_s'];
        $minBench = $limits['min_bench'];
        $maxBench = $limits['max_bench'];

        if ($maxPlayers <= 0) {
            $entries[] = [
                'label' => 'Max. Spieler',
                'detail' => 'muss größer als 0 sein (aktuell '.$maxPlayers.')',
            ];
        }
        if ($maxCredits <= 0) {
            $entries[] = [
                'label' => 'Max. Credits',
                'detail' => 'muss größer als 0 sein (aktuell '.$maxCredits.')',
            ];
        }
        if ($maxPlayersTeam <= 0) {
            $entries[] = [
                'label' => 'Max. Spieler / Team',
                'detail' => 'muss größer als 0 sein (aktuell '.$maxPlayersTeam.')',
            ];
        }

        if ($maxPlayers > 0 && $maxPlayersTeam > $maxPlayers) {
            $entries[] = [
                'label' => 'Max. Spieler / Team',
                'detail' => 'Max. Spieler/Team ('.$maxPlayersTeam.') > Max. Spieler ('.$maxPlayers.')',
            ];
        }

        $lineupBounds = [
            'Tor min' => $minG,
            'Tor max' => $maxG,
            'Abwehr min' => $minD,
            'Abwehr max' => $maxD,
            'Mittelfeld min' => $minM,
            'Mittelfeld max' => $maxM,
            'Angriff min' => $minS,
            'Angriff max' => $maxS,
            'Bank min' => $minBench,
            'Bank max' => $maxBench,
        ];
        $negativeBounds = [];
        foreach ($lineupBounds as $label => $value) {
            if ($value < 0) {
                $negativeBounds[] = $label.' ('.$value.')';
            }
        }
        if ($negativeBounds !== []) {
            $entries[] = [
                'label' => 'Aufstellungslimits',
                'detail' => 'dürfen nicht negativ sein: '.implode(', ', $negativeBounds),
            ];
        }

        $positionPairs = [
            'Tor' => [$minG, $maxG],
            'Abwehr' => [$minD, $maxD],
            'Mittelfeld' => [$minM, $maxM],
            'Angriff' => [$minS, $maxS],
        ];
        foreach ($positionPairs as $position => [$min, $max]) {
            if ($max < $min) {
                $entries[] = [
                    'label' => $position.' (min/max)',
                    'detail' => 'Max ('.$max.') < Min ('.$min.')',
                ];
            }
        }

        $minSum = $minG + $minD + $minM + $minS;
        if ($maxPlayers > 0 && $minSum > $maxPlayers) {
            $entries[] = [
                'label' => 'Positions-Mins',
                'detail' => 'Summe der Positions-Mins ('.$minSum.') > Max. Spieler ('.$maxPlayers.')',
            ];
        }

        $maxSum = $maxG + $maxD + $maxM + $maxS;
        if ($maxPlayers > 0 && $maxSum < $maxPlayers) {
            $entries[] = [
                'label' => 'Positions-Maxs',
                'detail' => 'Summe der Positions-Maxs ('.$maxSum.') < Max. Spieler ('.$maxPlayers.')',
            ];
        }

        if ($minBench > $maxBench) {
            $entries[] = [
                'label' => 'Bank (min/max)',
                'detail' => 'Bank-Min ('.$minBench.') > Bank-Max ('.$maxBench.')',
            ];
        }

        if ($benchMode === '') {
            if ($minBench !== 0 || $maxBench !== 0) {
                $entries[] = [
                    'label' => 'Ersatzbank-Modus',
                    'detail' => 'ohne Bankmodus müssen Bank-Min/Max 0 sein (aktuell '.$minBench.' / '.$maxBench.')',
                ];
            }
        } elseif (in_array($benchMode, ['cover', 'bestof'], true)) {
            // Matchround options may set Bank-Max = 0 to disable substitutes for that round
            // even when the league default allows a bench.
            if ($maxBench < 1 && ! $allowZeroBenchMax) {
                $entries[] = [
                    'label' => 'Ersatzbank-Modus',
                    'detail' => 'bei aktivem Bankmodus muss Bank-Max ≥ 1 sein (aktuell '.$maxBench.')',
                ];
            }
        }

        return $entries;
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
}
