<?php

namespace App\Services;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamprice;
use App\Models\UserDetails;
use App\Models\Userscore;
use App\Models\Userteam;
use App\Models\WebUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LineupService
{
    public function __construct(
        private readonly LineupOptionsResolver $lineupOptions,
    ) {}

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function pagePayload(int $userId): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return ['ok' => false, 'status' => 401, 'error' => 'Unknown user'];
        }

        $details = $user->details;
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
        $leagueId = (int) ($details?->user_details_ffb_selected_league ?? 0);

        if ($leagueId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $league = League::query()->find($leagueId);

        return [
            'ok' => true,
            'data' => [
                'user' => [
                    'user_id' => (int) $user->user_id,
                    'user_nickname' => (string) $user->user_nickname,
                    'photo_url' => '/images/ffb/profiles/photo/'.$photo,
                    'is_admin' => (bool) ($user->user_admin ?? false),
                    'is_ffb_admin' => app(FfbAdminAccess::class)->isAdmin((int) $user->user_id),
                ],
                'selected_league_id' => $leagueId,
                'game_over' => $league ? (int) ($league->league_archive ?? 0) !== 0 : false,
                'navigation' => app(DashboardService::class)->navigation($userId),
            ],
        ];
    }

    /**
     * League-default lineup limits (optional matchround_id applies override).
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function options(int $userId, int $matchroundId = 0): array
    {
        $leagueId = $this->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $leagueOptions = LeagueOptions::query()->where('options_league_id', $leagueId)->first();
        if (! $leagueOptions && $matchroundId <= 0) {
            return ['ok' => false, 'status' => 404, 'error' => 'Keine Lineup-Optionen gefunden.'];
        }

        $dynamicError = $this->requireDynamicPriceMode($leagueOptions);
        if ($dynamicError !== null) {
            return $dynamicError;
        }

        $resolved = $matchroundId > 0
            ? $this->lineupOptions->forMatchround($matchroundId)
            : $this->lineupOptions->forLeague($leagueId);

        return [
            'ok' => true,
            'data' => [
                'lineup_max_players' => $resolved['lineup_max_players'],
                'lineup_max_credits' => $resolved['lineup_max_credits'],
                'lineup_max_players_team' => $resolved['lineup_max_players_team'],
                'lineup_min_g' => $resolved['lineup_min_g'],
                'lineup_min_d' => $resolved['lineup_min_d'],
                'lineup_min_m' => $resolved['lineup_min_m'],
                'lineup_min_s' => $resolved['lineup_min_s'],
                'lineup_max_g' => $resolved['lineup_max_g'],
                'lineup_max_d' => $resolved['lineup_max_d'],
                'lineup_max_m' => $resolved['lineup_max_m'],
                'lineup_max_s' => $resolved['lineup_max_s'],
                'game_pricemode' => 'dynamic',
                'source' => $resolved['source'],
            ],
        ];
    }

    /**
     * Next upcoming matchround + teams for the lineup picker.
     *
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function matchroundAndTeams(int $userId): array
    {
        $leagueId = $this->selectedLeagueId($userId);
        if ($leagueId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'Kein Spiel ausgewählt.'];
        }

        $leagueOptions = LeagueOptions::query()->where('options_league_id', $leagueId)->first();
        $dynamicError = $this->requireDynamicPriceMode($leagueOptions);
        if ($dynamicError !== null) {
            return $dynamicError;
        }

        $league = League::query()->find($leagueId);
        $gameOver = $league ? (int) ($league->league_archive ?? 0) !== 0 : false;

        $round = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_startdate', '>', now())
            ->orderBy('matchround_startdate')
            ->first();

        if (! $round) {
            return [
                'ok' => true,
                'data' => [
                    'game_over' => $gameOver,
                    'show_recent_performance' => false,
                    'matchround' => null,
                ],
            ];
        }

        $allMatches = MatchGame::query()
            ->with(['homeTeam', 'guestTeam'])
            ->where('match_round', $round->matchround_id)
            ->orderBy('match_date')
            ->get();

        $teamSourceMatches = MatchGame::query()
            ->where('match_round', $round->matchround_id)
            ->where(function ($q) {
                $q->where('match_status', '')->orWhereNull('match_status');
            })
            ->get();

        $teamIds = [];
        foreach ($allMatches as $match) {
            $teamIds[] = (int) $match->match_hometeam_id;
            $teamIds[] = (int) $match->match_guestteam_id;
        }
        $teamIds = array_values(array_unique(array_filter($teamIds)));

        $pricesByTeamId = [];
        if ($teamIds !== []) {
            $pricesByTeamId = Teamprice::query()
                ->where('teamprice_matchround_id', (int) $round->matchround_id)
                ->whereIn('teamprice_team_id', $teamIds)
                ->pluck('teamprice_price', 'teamprice_team_id')
                ->all();
        }

        $teams = [];
        if ($teamIds !== []) {
            // Team picker / tiles: only teams from unfinished matches (empty status).
            $pickerTeamIds = [];
            foreach ($teamSourceMatches as $match) {
                $pickerTeamIds[] = (int) $match->match_hometeam_id;
                $pickerTeamIds[] = (int) $match->match_guestteam_id;
            }
            $pickerTeamIds = array_values(array_unique(array_filter($pickerTeamIds)));

            $teams = $pickerTeamIds === []
                ? []
                : Team::query()
                    ->whereIn('team_id', $pickerTeamIds)
                    ->orderBy('team_name')
                    ->get()
                    ->map(function (Team $t) use ($pricesByTeamId): array {
                        $teamId = (int) $t->team_id;
                        $row = [
                            'team_id' => $teamId,
                            'team_name' => (string) $t->team_name,
                            'team_nationality' => (string) $t->team_nationality,
                            'team_status' => (int) ($t->team_status ?? 0),
                            'team_price' => null,
                        ];

                        if (array_key_exists($teamId, $pricesByTeamId)) {
                            $row['team_price'] = round((float) $pricesByTeamId[$teamId], 1);
                        }

                        return $row;
                    })
                    ->all();
        }

        $matches = $allMatches->map(function (MatchGame $match) use ($pricesByTeamId): array {
            $payload = $match->toSideListPayload();
            $homeId = (int) $match->match_hometeam_id;
            $guestId = (int) $match->match_guestteam_id;
            $payload['match_hometeam_price'] = array_key_exists($homeId, $pricesByTeamId)
                ? round((float) $pricesByTeamId[$homeId], 1)
                : null;
            $payload['match_guestteam_price'] = array_key_exists($guestId, $pricesByTeamId)
                ? round((float) $pricesByTeamId[$guestId], 1)
                : null;

            return $payload;
        })->all();

        return [
            'ok' => true,
            'data' => [
                'game_over' => $gameOver,
                'show_recent_performance' => $this->shouldShowRecentPerformance(
                    $leagueOptions,
                    (int) $round->matchround_id,
                ),
                'matchround' => [
                    'matchround_id' => (int) $round->matchround_id,
                    'matchround_title' => (string) $round->matchround_title,
                    'matchround_status' => (int) $round->matchround_status,
                    'matchround_startdate' => date('j.n.Y', strtotime((string) $round->matchround_startdate)),
                    'matchround_enddate' => date('j.n.Y', strtotime((string) $round->matchround_enddate)),
                    'matchround_deadline' => date('j.n.Y G:i', strtotime((string) $round->matchround_startdate)),
                    'matches' => $matches,
                    'teams' => $teams,
                ],
                'lineup_options' => $this->lineupOptionsPayload((int) $round->matchround_id, $leagueId),
            ],
        ];
    }

    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function teamPlayers(int $userId, int $teamId, int $matchroundId): array
    {
        if ($teamId <= 0) {
            return ['ok' => false, 'status' => 422, 'error' => 'team_id is required'];
        }

        $matchround = Matchround::query()->find($matchroundId);
        $leagueId = (int) ($matchround?->matchround_league_id ?? 0);
        if ($leagueId <= 0) {
            $leagueId = $this->selectedLeagueId($userId);
        }

        $options = $leagueId > 0
            ? LeagueOptions::query()->where('options_league_id', $leagueId)->first()
            : null;
        $dynamicError = $this->requireDynamicPriceMode($options);
        if ($dynamicError !== null) {
            return $dynamicError;
        }

        $playerteams = Playerteam::query()
            ->with(['player', 'team'])
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_status', 1)
            ->when($leagueId > 0, fn ($q) => $q->forLeague($leagueId))
            ->orderBy('playerteam_player_position')
            ->get()
            ->keyBy(fn (Playerteam $pt): int => (int) $pt->playerteam_id);

        $ptIds = $playerteams->keys()->map(fn ($id) => (int) $id)->all();
        $prices = $this->resolvePlayerPrices($ptIds, $matchroundId, $playerteams);
        $recentByPt = $this->resolveRecentPerformances($ptIds, $matchroundId);
        $cardWarnings = $this->resolveCardWarnings($playerteams, $matchroundId, $leagueId);

        $playerteams = $playerteams->sort(function (Playerteam $a, Playerteam $b): int {
            $pos = strcmp((string) $a->playerteam_player_position, (string) $b->playerteam_player_position);
            if ($pos !== 0) {
                return $pos;
            }

            $ln = strcasecmp((string) ($a->player?->player_lname ?? ''), (string) ($b->player?->player_lname ?? ''));
            if ($ln !== 0) {
                return $ln;
            }

            return strcasecmp((string) ($a->player?->player_fname ?? ''), (string) ($b->player?->player_fname ?? ''));
        })->values();

        $players = [];
        foreach ($playerteams as $pt) {
            if (! $pt->player || ! $pt->team) {
                continue;
            }
            $ptId = (int) $pt->playerteam_id;
            if (! $prices->has($ptId)) {
                continue;
            }

            $players[] = [
                'player_id' => (int) $pt->player->player_id,
                'player_fname' => (string) $pt->player->player_fname,
                'player_lname' => (string) $pt->player->player_lname,
                'player_nationality' => (string) ($pt->player->player_nationality ?: ''),
                'player_status' => (int) ($pt->player->player_status ?? 0),
                'player_status_description' => (string) ($pt->player->player_status_description ?: '0'),
                'playerteam_id' => $ptId,
                'playerteam_team_id' => (int) $pt->playerteam_team_id,
                'playerteam_team' => (string) $pt->team->team_name,
                'playerteam_team_nationality' => (string) $pt->team->team_nationality,
                'playerteam_player_position' => (string) $pt->playerteam_player_position,
                'playerteam_player_picture' => (string) ($pt->playerteam_player_picture ?: ''),
                'playerteam_player_price' => (float) $prices->get($ptId),
                'playerteam_player_note' => (string) ($pt->playerteam_player_note ?? ''),
                'recent_performance' => (float) ($recentByPt->get($ptId) ?? 0.0),
                'card_warning' => $cardWarnings->get($ptId),
            ];
        }

        return [
            'ok' => true,
            'data' => [
                'team_id' => $teamId,
                'matchround_id' => $matchroundId,
                'numResults' => count($players),
                'players' => $players,
            ],
        ];
    }

    /**
     * Load a user's lineup for a matchround (JSON-shaped, close to legacy XML fields).
     *
     * @return array<string, mixed>
     */
    public function getForRound(int $userId, int $matchroundId): array
    {
        $user = WebUser::query()->find($userId);
        if (! $user) {
            return [
                'user_id' => $userId,
                'matchround_id' => $matchroundId,
                'userteam' => null,
                'players' => [],
            ];
        }

        $userteam = Userteam::query()
            ->where('userteam_user_id', $userId)
            ->where('userteam_matchround_id', $matchroundId)
            ->first();

        if (! $userteam) {
            return [
                'user_id' => $userId,
                'user_nickname' => $user->user_nickname,
                'matchround_id' => $matchroundId,
                'userteam' => null,
                'players' => [],
            ];
        }

        $slotIds = $userteam->playerteamIdsInSlotOrder();
        $playerteams = Playerteam::query()
            ->with(['player', 'team'])
            ->whereIn('playerteam_id', $slotIds)
            ->get()
            ->keyBy('playerteam_id');

        $prices = $this->resolvePlayerPrices($slotIds, $matchroundId, $playerteams);
        $scores = $this->scoresForRound($slotIds, $matchroundId);
        $leagueId = (int) (Matchround::query()->whereKey($matchroundId)->value('matchround_league_id') ?? 0);
        $cardWarnings = $this->resolveCardWarnings($playerteams, $matchroundId, $leagueId);

        $players = [];
        foreach ($slotIds as $slot => $playerteamId) {
            /** @var Playerteam|null $pt */
            $pt = $playerteams->get($playerteamId);
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }

            $players[] = [
                'slot' => $slot + 1,
                'player_id' => (int) $pt->player->player_id,
                'player_fname' => (string) $pt->player->player_fname,
                'player_lname' => (string) $pt->player->player_lname,
                'player_nationality' => (string) $pt->player->player_nationality,
                'player_status' => (int) ($pt->player->player_status ?? 0),
                'player_status_description' => (string) ($pt->player->player_status_description ?: ''),
                'playerteam_id' => (int) $pt->playerteam_id,
                'playerteam_team_id' => (int) $pt->playerteam_team_id,
                'playerteam_team' => (string) $pt->team->team_name,
                'playerteam_team_nationality' => (string) $pt->team->team_nationality,
                'playerteam_player_position' => (string) $pt->playerteam_player_position,
                'playerteam_player_picture' => (string) ($pt->playerteam_player_picture ?: ''),
                'playerteam_status' => (int) ($pt->playerteam_status ? 1 : 0),
                'playerteam_player_price' => (float) ($prices->get($playerteamId) ?? 0),
                'playerteam_player_note' => (string) ($pt->playerteam_player_note ?? ''),
                'playerstats_score' => (int) ($scores->get($playerteamId) ?? 0),
                'card_warning' => $cardWarnings->get((int) $pt->playerteam_id),
            ];
        }

        return [
            'user_id' => $userId,
            'user_nickname' => $user->user_nickname,
            'matchround_id' => $matchroundId,
            'userteam' => [
                'userteam_id' => (int) $userteam->userteam_id,
                'userteam_matchround_id' => (int) $userteam->userteam_matchround_id,
                'userteam_score' => (int) $userteam->userteam_score,
                'userteam_price' => (float) $userteam->userteam_price,
                'userteam_lc_points' => (int) $userteam->userteam_lc_points,
                'userteam_username' => (string) $user->user_nickname,
            ],
            'players' => $players,
        ];
    }

    /**
     * Save / update a lineup (mirrors ffb/teammanagement/saveLineup.xml, with server-side rules).
     *
     * @param  list<int|string>  $playerteamIds
     * @return array{ok: true, created: bool, message: string, data: array<string, mixed>}|array{ok: false, status: int, error: string}
     */
    public function saveForRound(int $userId, int $matchroundId, array $playerteamIds): array
    {
        $ids = $this->normalizePlayerteamIds($playerteamIds);

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return $this->fail(422, 'Unknown matchround');
        }

        // Legacy checkMatchround: only allow saves while startdate is still in the future.
        if (! $this->isMatchroundOpen($matchround)) {
            return $this->fail(409, 'Die Deadline für diese Spielrunde ist bereits vorüber! Deine Aufstellung wurde nicht gespeichert!');
        }

        $lineupRules = $this->lineupOptions->forMatchround($matchroundId);
        $maxPlayers = (int) $lineupRules['lineup_max_players'];
        if (count($ids) !== $maxPlayers) {
            return $this->fail(422, "Invalid lineup: exactly {$maxPlayers} players are required");
        }

        if (count(array_unique($ids)) !== $maxPlayers) {
            return $this->fail(422, 'Invalid lineup: duplicate players are not allowed');
        }

        $options = LeagueOptions::query()
            ->where('options_league_id', $matchround->matchround_league_id)
            ->first();

        $dynamicError = $this->requireDynamicPriceMode($options);
        if ($dynamicError !== null) {
            return $dynamicError;
        }

        $leagueId = (int) $matchround->matchround_league_id;
        $playerteams = Playerteam::query()
            ->with(['player', 'team'])
            ->whereIn('playerteam_id', $ids)
            ->get()
            ->keyBy('playerteam_id');

        if ($playerteams->count() !== $maxPlayers) {
            return $this->fail(422, 'Invalid lineup: one or more players were not found');
        }

        foreach ($playerteams as $pt) {
            if ((int) ($pt->playerteam_league_id ?? 0) !== $leagueId) {
                return $this->fail(422, 'Invalid lineup: player does not belong to this league');
            }
            if ((int) $pt->playerteam_status !== 1) {
                return $this->fail(422, 'Invalid lineup: inactive players are not allowed');
            }
        }

        $prices = $this->resolvePlayerPrices($ids, $matchroundId, $playerteams);
        foreach ($ids as $id) {
            if (! $prices->has($id)) {
                return $this->fail(422, 'Invalid lineup: Spielerpreis fehlt (Playerprice/Teamprice) für diese Spielrunde.');
            }
        }

        $validationError = $this->validateAgainstOptions($ids, $playerteams, $prices, $lineupRules);
        if ($validationError !== null) {
            return $this->fail(422, $validationError);
        }

        $sumPrice = $this->sumPrices($ids, $prices);
        $created = false;

        DB::transaction(function () use ($userId, $matchroundId, $ids, $sumPrice, $matchround, &$created) {
            $userteam = Userteam::query()
                ->where('userteam_user_id', $userId)
                ->where('userteam_matchround_id', $matchroundId)
                ->first();

            if (! $userteam) {
                $userteam = new Userteam;
                $userteam->userteam_user_id = $userId;
                $userteam->userteam_matchround_id = $matchroundId;
                $created = true;
            }

            $userteam->userteam_date = now()->format('Y-m-d H:i:s');
            $userteam->userteam_score = null;
            $userteam->userteam_lc_points = null;
            $userteam->userteam_price = $sumPrice;
            $userteam->save();
            $userteam->syncSlots($ids);

            $leagueId = $this->resolveLeagueId($userId, (int) $matchround->matchround_league_id);
            Userscore::query()->firstOrCreate(
                [
                    'userscore_user_id' => $userId,
                    'userscore_league_id' => $leagueId,
                ],
                [
                    'userscore_total' => 0,
                    'userscore_lc_points' => 0,
                ]
            );
        });

        return [
            'ok' => true,
            'created' => $created,
            'message' => $created
                ? 'Deine Aufstellung wurde gespeichert!'
                : 'Deine Aufstellung wurde aktualisiert!',
            'data' => $this->getForRound($userId, $matchroundId),
        ];
    }

    /**
     * @param  list<int|string>  $playerteamIds
     * @return list<int>
     */
    public function normalizePlayerteamIds(array $playerteamIds): array
    {
        $ids = [];
        foreach ($playerteamIds as $id) {
            if (is_string($id) && str_contains($id, ',')) {
                foreach (explode(',', $id) as $part) {
                    $part = trim($part);
                    if ($part !== '' && is_numeric($part)) {
                        $ids[] = (int) $part;
                    }
                }

                continue;
            }

            if (is_numeric($id) && (int) $id > 0) {
                $ids[] = (int) $id;
            }
        }

        return $ids;
    }

    private function isMatchroundOpen(Matchround $matchround): bool
    {
        $start = $matchround->matchround_startdate;
        if ($start === null || $start === '') {
            return false;
        }

        return strtotime((string) $start) > time();
    }

    /**
     * @param  list<int>  $ids
     * @param  Collection<int, Playerteam>  $playerteams
     * @param  Collection<int, float|int|string>  $prices
     * @param  array{
     *     lineup_max_players: int,
     *     lineup_max_credits: float,
     *     lineup_max_players_team: int,
     *     lineup_min_g: int,
     *     lineup_min_d: int,
     *     lineup_min_m: int,
     *     lineup_min_s: int,
     *     lineup_max_g: int,
     *     lineup_max_d: int,
     *     lineup_max_m: int,
     *     lineup_max_s: int
     * }  $options
     */
    private function validateAgainstOptions(
        array $ids,
        Collection $playerteams,
        Collection $prices,
        array $options,
    ): ?string {
        $maxPlayers = (int) ($options['lineup_max_players'] ?: 11);
        if (count($ids) !== $maxPlayers) {
            return "Invalid lineup: exactly {$maxPlayers} players are required";
        }

        $counts = ['g' => 0, 'd' => 0, 'm' => 0, 's' => 0];
        $perTeam = [];

        foreach ($ids as $id) {
            $pt = $playerteams->get($id);
            if (! $pt) {
                return 'Invalid lineup: one or more players were not found';
            }

            if (! $pt->playerteam_status) {
                return 'Invalid lineup: inactive players are not allowed';
            }

            $position = strtolower((string) $pt->playerteam_player_position);
            if (! isset($counts[$position])) {
                return 'Invalid lineup: unknown player position';
            }
            $counts[$position]++;

            $teamId = (int) $pt->playerteam_team_id;
            $perTeam[$teamId] = ($perTeam[$teamId] ?? 0) + 1;
        }

        $maxPerTeam = (int) $options['lineup_max_players_team'];
        foreach ($perTeam as $count) {
            if ($count > $maxPerTeam) {
                return "Invalid lineup: at most {$maxPerTeam} players from the same team";
            }
        }

        $rules = [
            'g' => [(int) $options['lineup_min_g'], (int) $options['lineup_max_g']],
            'd' => [(int) $options['lineup_min_d'], (int) $options['lineup_max_d']],
            'm' => [(int) $options['lineup_min_m'], (int) $options['lineup_max_m']],
            's' => [(int) $options['lineup_min_s'], (int) $options['lineup_max_s']],
        ];

        foreach ($rules as $position => [$min, $max]) {
            if ($counts[$position] < $min || $counts[$position] > $max) {
                return "Invalid lineup: position '{$position}' must be between {$min} and {$max}";
            }
        }

        $maxCredits = (float) $options['lineup_max_credits'];
        $sumPrice = $this->sumPrices($ids, $prices);
        if ($sumPrice > $maxCredits) {
            return "Invalid lineup: total price {$sumPrice} exceeds credit limit {$maxCredits}";
        }

        return null;
    }

    /**
     * @return array<string, int|float|string>
     */
    private function lineupOptionsPayload(int $matchroundId, int $leagueId): array
    {
        $resolved = $matchroundId > 0
            ? $this->lineupOptions->forMatchround($matchroundId)
            : $this->lineupOptions->forLeague($leagueId);

        return [
            'lineup_max_players' => $resolved['lineup_max_players'],
            'lineup_max_credits' => $resolved['lineup_max_credits'],
            'lineup_max_players_team' => $resolved['lineup_max_players_team'],
            'lineup_min_g' => $resolved['lineup_min_g'],
            'lineup_min_d' => $resolved['lineup_min_d'],
            'lineup_min_m' => $resolved['lineup_min_m'],
            'lineup_min_s' => $resolved['lineup_min_s'],
            'lineup_max_g' => $resolved['lineup_max_g'],
            'lineup_max_d' => $resolved['lineup_max_d'],
            'lineup_max_m' => $resolved['lineup_max_m'],
            'lineup_max_s' => $resolved['lineup_max_s'],
            'game_pricemode' => 'dynamic',
            'source' => $resolved['source'],
        ];
    }

    /**
     * @param  list<int>  $ids
     * @param  Collection<int, float|int|string>  $prices
     */
    private function sumPrices(array $ids, Collection $prices): float
    {
        $sum = 0.0;
        foreach ($ids as $id) {
            $sum += (float) ($prices->get($id) ?? 0);
        }

        return round($sum, 1);
    }

    private function selectedLeagueId(int $userId): int
    {
        $details = UserDetails::query()->find($userId);

        return (int) ($details?->user_details_ffb_selected_league ?? 0);
    }

    private function resolveLeagueId(int $userId, int $fallbackLeagueId): int
    {
        $selected = $this->selectedLeagueId($userId);

        return $selected > 0 ? $selected : $fallbackLeagueId;
    }

    /**
     * @return array{ok: false, status: int, error: string}
     */
    private function fail(int $status, string $error): array
    {
        return [
            'ok' => false,
            'status' => $status,
            'error' => $error,
        ];
    }

    /**
     * @return array{ok: false, status: int, error: string}|null
     */
    private function requireDynamicPriceMode(?LeagueOptions $options): ?array
    {
        $mode = (string) ($options?->options_league_pricemode ?: 'constant');
        if ($mode !== 'dynamic') {
            return $this->fail(422, 'Aufstellungen erfordern das dynamische Preismodell.');
        }

        return null;
    }

    /**
     * Resolve lineup credits: ffb_playerprice, else ffb_teamprice for the player's team.
     *
     * @param  list<int>  $playerteamIds
     * @param  Collection<int, Playerteam>  $playerteams
     * @return Collection<int, float>
     */
    private function resolvePlayerPrices(array $playerteamIds, int $matchroundId, Collection $playerteams): Collection
    {
        if ($playerteamIds === [] || $matchroundId <= 0) {
            return collect();
        }

        $fromPlayer = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereIn('playerprice_playerteam_id', $playerteamIds)
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->playerprice_playerteam_id => (float) $row->playerprice_price]);

        $teamIds = $playerteams
            ->map(fn (Playerteam $pt): int => (int) $pt->playerteam_team_id)
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        $fromTeam = $teamIds === []
            ? collect()
            : Teamprice::query()
                ->where('teamprice_matchround_id', $matchroundId)
                ->whereIn('teamprice_team_id', $teamIds)
                ->get()
                ->mapWithKeys(fn ($row) => [(int) $row->teamprice_team_id => (float) $row->teamprice_price]);

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

    /**
     * Recent performance (−1…+1) from ffb_playerprice; missing/null → 0.
     *
     * @param  list<int>  $playerteamIds
     * @return Collection<int, float>
     */
    private function resolveRecentPerformances(array $playerteamIds, int $matchroundId): Collection
    {
        if ($playerteamIds === [] || $matchroundId <= 0) {
            return collect();
        }

        $rows = Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereIn('playerprice_playerteam_id', $playerteamIds)
            ->get(['playerprice_playerteam_id', 'playerprice_recent_performance']);

        $resolved = collect();
        foreach ($playerteamIds as $ptId) {
            $resolved->put($ptId, 0.0);
        }

        foreach ($rows as $row) {
            $ptId = (int) $row->playerprice_playerteam_id;
            $raw = $row->playerprice_recent_performance;
            if ($raw === null) {
                continue;
            }

            $value = max(-1.0, min(1.0, (float) $raw));
            $resolved->put($ptId, $value);
        }

        return $resolved;
    }

    /**
     * Card-based lineup warnings for the selected matchround (past league rounds only).
     *
     * @param  Collection<int, Playerteam>  $playerteams
     * @return Collection<int, string|null>
     */
    private function resolveCardWarnings(Collection $playerteams, int $matchroundId, int $leagueId): Collection
    {
        $warnings = collect();
        foreach ($playerteams as $ptId => $pt) {
            $warnings->put((int) $ptId, null);
        }

        if ($playerteams->isEmpty() || $matchroundId <= 0 || $leagueId <= 0) {
            return $warnings;
        }

        $selected = Matchround::query()->find($matchroundId);
        if (! $selected || $selected->matchround_startdate === null) {
            return $warnings;
        }

        $pastRounds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->where('matchround_startdate', '<', $selected->matchround_startdate)
            ->orderByDesc('matchround_startdate')
            ->limit(3)
            ->get(['matchround_id', 'matchround_title'])
            ->values();

        if ($pastRounds->isEmpty()) {
            return $warnings;
        }

        $playerIds = $playerteams
            ->map(static fn (Playerteam $pt): int => (int) $pt->playerteam_player_id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($playerIds === []) {
            return $warnings;
        }

        $allPtIds = Playerteam::query()
            ->whereIn('playerteam_player_id', $playerIds)
            ->pluck('playerteam_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        $roundIds = $pastRounds->pluck('matchround_id')->map(static fn ($id): int => (int) $id)->all();
        $titlesByRound = $pastRounds->mapWithKeys(
            static fn (Matchround $round): array => [
                (int) $round->matchround_id => (string) $round->matchround_title,
            ],
        );

        $ptToPlayer = Playerteam::query()
            ->whereIn('playerteam_id', $allPtIds)
            ->get(['playerteam_id', 'playerteam_player_id'])
            ->mapWithKeys(static fn (Playerteam $pt): array => [
                (int) $pt->playerteam_id => (int) $pt->playerteam_player_id,
            ]);

        /** @var array<int, array<int, string>> $cardsByPlayerRound */
        $cardsByPlayerRound = [];
        foreach (
            Playerstats::query()
                ->whereIn('playerstats_matchround_id', $roundIds)
                ->whereIn('playerstats_playerteam_id', $allPtIds)
                ->get(['playerstats_matchround_id', 'playerstats_playerteam_id', 'playerstats_cards']) as $stat
        ) {
            $playerId = (int) ($ptToPlayer->get((int) $stat->playerstats_playerteam_id) ?? 0);
            if ($playerId <= 0) {
                continue;
            }

            $roundId = (int) $stat->playerstats_matchround_id;
            $card = strtolower((string) ($stat->playerstats_cards ?: 'n'));
            if (! in_array($card, ['y', 'yr', 'r'], true)) {
                continue;
            }

            $cardsByPlayerRound[$playerId][$roundId] = $card;
        }

        foreach ($playerteams as $ptId => $pt) {
            $playerId = (int) $pt->playerteam_player_id;
            $cards = $cardsByPlayerRound[$playerId] ?? [];
            $warning = null;

            foreach ($roundIds as $roundId) {
                if (($cards[$roundId] ?? null) === 'r') {
                    $title = (string) ($titlesByRound->get($roundId) ?? ('#'.$roundId));
                    $warning = 'Rot in '.$title.'.';
                    break;
                }
            }

            if ($warning === null && isset($roundIds[0]) && ($cards[$roundIds[0]] ?? null) === 'yr') {
                $warning = 'Gelb-Rot im vorhergehenden Spiel.';
            }

            if (
                $warning === null
                && isset($roundIds[0], $roundIds[1])
                && ($cards[$roundIds[0]] ?? null) === 'y'
                && ($cards[$roundIds[1]] ?? null) === 'y'
            ) {
                $warning = '2 gelbe Karten in den beiden vorhergehenden Spielen.';
            }

            $warnings->put((int) $ptId, $warning);
        }

        return $warnings;
    }

    private function shouldShowRecentPerformance(?LeagueOptions $options, int $matchroundId): bool
    {
        $mode = (string) ($options?->options_league_pricemode ?: 'constant');
        if ($mode !== 'dynamic') {
            return false;
        }

        return $this->matchroundHasRecentPerformance($matchroundId);
    }

    private function matchroundHasRecentPerformance(int $matchroundId): bool
    {
        if ($matchroundId <= 0) {
            return false;
        }

        return Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereNotNull('playerprice_recent_performance')
            ->exists();
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return Collection<int, int>
     */
    private function scoresForRound(array $playerteamIds, int $matchroundId): Collection
    {
        if ($playerteamIds === []) {
            return collect();
        }

        return Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->whereIn('playerstats_playerteam_id', $playerteamIds)
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->playerstats_playerteam_id => (int) $row->playerstats_score]);
    }
}
