<?php

namespace App\Services;

use App\Exceptions\CloudflareChallengeException;
use App\Models\GameOptions;
use App\Models\Goal;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerfid;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Psgoal;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminMatchdataService
{
    private ?GameOptions $optionsCache = null;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly WeltfussballMatchScraper $weltfussball,
        private readonly WeltfussballProxyService $wfProxy,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId): array
    {
        $admin = $this->adminCenter->pagePayload($userId);

        return [
            'user' => $admin['user'],
            'navigation' => $admin['navigation'],
            'selected_game_id' => $admin['selected_game_id'],
            'selected_game' => $admin['selected_game'],
            'games' => $admin['games'],
            'pointsmode' => $this->pointsMode($userId),
        ];
    }

    /**
     * @return list<array{
     *     matchround_id: int,
     *     matchround_title: string,
     *     matchround_startdate: string,
     *     matchround_enddate: string,
     *     started: bool
     * }>
     */
    public function rounds(int $userId): array
    {
        $gameId = $this->adminCenter->selectedGameId($userId);
        if ($gameId <= 0) {
            return [];
        }

        $now = time();

        return Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->orderByDesc('matchround_startdate')
            ->get()
            ->map(function (Matchround $round) use ($now) {
                $start = strtotime((string) $round->matchround_startdate) ?: 0;
                $end = strtotime((string) $round->matchround_enddate) ?: 0;

                return [
                    'matchround_id' => (int) $round->matchround_id,
                    'matchround_title' => (string) $round->matchround_title,
                    'matchround_startdate' => date('j.n.Y G:i', $start),
                    'matchround_enddate' => date('j.n.Y G:i', $end),
                    'started' => $start <= $now,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function matchesForRound(int $matchroundId): array
    {
        return MatchGame::query()
            ->with(['homeTeam', 'guestTeam', 'matchround'])
            ->where('match_round', $matchroundId)
            ->orderBy('match_date')
            ->orderBy('match_id')
            ->get()
            ->map(function (MatchGame $item) {
                $home = $this->teamInfo($item->homeTeam);
                $guest = $this->teamInfo($item->guestTeam);

                return [
                    'match_id' => (int) $item->match_id,
                    'match_round_name' => (string) ($item->matchround?->matchround_title ?? ''),
                    'match_hometeam_id' => (int) $item->match_hometeam_id,
                    'match_hometeam_name' => $home['name'],
                    'match_hometeam_nationality' => $home['nationality'],
                    'match_homescore' => (int) $item->match_homescore,
                    'match_guestteam_id' => (int) $item->match_guestteam_id,
                    'match_guestteam_name' => $guest['name'],
                    'match_guestteam_nationality' => $guest['nationality'],
                    'match_guestscore' => (int) $item->match_guestscore,
                    'match_homescore_penalty' => (int) $item->match_homescore_penalty,
                    'match_guestscore_penalty' => (int) $item->match_guestscore_penalty,
                    'match_url' => (string) ($item->match_url ?? ''),
                    'match_minutes' => (int) ($item->match_minutes ?: 90) === 120 ? 120 : 90,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Teams ranked by how many of their players are used in the lineups of a round.
     *
     * @return list<array{teamname: string, players: int}>
     */
    public function mostWanted(int $matchroundId): array
    {
        $sql = 'SELECT ffb_team.team_id, ffb_team.team_name, COUNT(ffb_playerteam.playerteam_id) AS plnum '
            .'FROM ffb_team '
            .'INNER JOIN ffb_playerteam ON ffb_team.team_id = ffb_playerteam.playerteam_team_id '
            .'INNER JOIN ffb_userteam ON ('
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id1 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id2 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id3 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id4 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id5 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id6 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id7 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id8 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id9 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id10 OR '
            .'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id11'
            .') '
            .'WHERE ffb_userteam.userteam_matchround_id = ? '
            .'GROUP BY ffb_team.team_id, ffb_team.team_name '
            .'ORDER BY plnum DESC';

        $teams = [];
        foreach (DB::select($sql, [$matchroundId]) as $row) {
            $teams[] = [
                'teamname' => (string) $row->team_name,
                'players' => (int) $row->plnum,
            ];
        }

        return $teams;
    }

    /**
     * Squad of a team with the stats already stored for a match.
     *
     * @return list<array<string, mixed>>
     */
    public function playersForTeam(int $userId, int $teamId, int $matchId, bool $allPlayers = false): array
    {
        if ($teamId <= 0) {
            return [];
        }

        $pm = $this->pointsMode($userId);

        $query = Playerteam::query()
            ->with('player')
            ->join('ffb_player', 'ffb_player.player_id', '=', 'ffb_playerteam.playerteam_player_id')
            ->where('ffb_playerteam.playerteam_team_id', $teamId);

        if (! $allPlayers) {
            $query->where('ffb_playerteam.playerteam_status', 1);
        }

        $playerteams = $query
            ->orderBy('ffb_player.player_lname')
            ->orderBy('ffb_player.player_fname')
            ->select('ffb_playerteam.*')
            ->get();

        if ($playerteams->isEmpty()) {
            return [];
        }

        $playerteamIds = $playerteams
            ->map(fn (Playerteam $item) => (int) $item->playerteam_id)
            ->all();

        $stats = Playerstats::query()
            ->where('playerstats_match_id', $matchId)
            ->whereIn('playerstats_playerteam_id', $playerteamIds)
            ->get()
            ->groupBy('playerstats_playerteam_id');

        $wfNames = Playerfid::query()
            ->whereIn('playerfid_playerteam_id', $playerteamIds)
            ->pluck('playerfid_name_wf', 'playerfid_playerteam_id');

        $goalMinutes = $pm === 'new'
            ? $this->goalMinutesByPlayerteam($matchId, $playerteamIds)
            : [];

        $players = [];
        foreach ($playerteams as $playerteam) {
            $playerteamId = (int) $playerteam->playerteam_id;
            $player = $playerteam->player;
            $wfName = trim((string) ($wfNames[$playerteamId] ?? ''));

            $row = [
                'player_id' => (int) ($player?->player_id ?? 0),
                'player_fname' => (string) ($player?->player_fname ?? ''),
                'player_lname' => (string) ($player?->player_lname ?? ''),
                'playerteam_id' => $playerteamId,
                'playerteam_player_position' => (string) ($playerteam->playerteam_player_position ?: ''),
                'player_name_fid_wf' => $wfName !== '' && $wfName !== '0'
                    ? $wfName
                    : trim(($player?->player_lname ?? '').' '.($player?->player_fname ?? '')),
            ];

            /** @var Playerstats|null $stat */
            $stat = $stats->get($playerteamId)?->first();

            if ($stat) {
                $row['playerstats_goals'] = $pm === 'new'
                    ? $this->goalString((int) $stat->playerstats_goals, $goalMinutes[0][$playerteamId] ?? [])
                    : (int) $stat->playerstats_goals;
                $row['playerstats_owngoals'] = $pm === 'new'
                    ? $this->goalString((int) $stat->playerstats_owngoals, $goalMinutes[1][$playerteamId] ?? [])
                    : (int) $stat->playerstats_owngoals;
                $row['playerstats_assists'] = (int) $stat->playerstats_assists;
                $row['playerstats_minutes'] = (int) $stat->playerstats_minutes;
                $row['playerstats_minute_in'] = (int) $stat->playerstats_minute_in;
                $row['playerstats_minute_out'] = (int) $stat->playerstats_minute_out;
                $row['playerstats_cards'] = (string) ($stat->playerstats_cards ?: 'n');
                $row['playerstats_penaltieslost'] = (int) $stat->playerstats_penaltieslost;
                $row['playerstats_penaltiessaved'] = (int) $stat->playerstats_penaltiessaved;
                $row['playerstats_penaltyshootout_save'] = (int) $stat->playerstats_penaltyshootout_save;
                $row['playerstats_penaltyshootout_lost'] = (int) $stat->playerstats_penaltyshootout_lost;
                $row['playerstats_penaltyshootout_hit'] = (int) $stat->playerstats_penaltyshootout_hit;
            } else {
                $row['playerstats_goals'] = 0;
                $row['playerstats_owngoals'] = 0;
                $row['playerstats_assists'] = 0;
                $row['playerstats_minutes'] = 0;
                $row['playerstats_minute_in'] = 0;
                $row['playerstats_minute_out'] = 0;
                $row['playerstats_cards'] = 'n';
                $row['playerstats_penaltieslost'] = 0;
                $row['playerstats_penaltiessaved'] = 0;
                $row['playerstats_penaltyshootout_save'] = 0;
                $row['playerstats_penaltyshootout_lost'] = 0;
                $row['playerstats_penaltyshootout_hit'] = 0;
            }

            $players[] = $row;
        }

        return $players;
    }

    /**
     * Fetch and map a Weltfussball spielbericht onto the selected match squads.
     *
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     url?: string,
     *     match_minutes?: int,
     *     result?: array{
     *         homescore: int,
     *         guestscore: int,
     *         homescore_penalty: int,
     *         guestscore_penalty: int
     *     },
     *     players?: array<string, array<string, int|string>>,
     *     unmatched?: list<string>,
     *     matched?: int
     * }
     */
    public function scrapeMatchData(
        int $userId,
        int $matchId,
        string $url,
        ?string $cookies = null,
        ?string $html = null,
        bool $useCachedHtml = false,
    ): array {
        $url = trim($url);
        if ($matchId <= 0) {
            return ['ok' => false, 'errors' => ['Kein Spiel gewählt.']];
        }
        if ($url === '') {
            return ['ok' => false, 'errors' => ['Bitte eine URL angeben.']];
        }
        if (! preg_match('#^https?://#i', $url)) {
            return ['ok' => false, 'errors' => ['URL muss mit http:// oder https:// beginnen.']];
        }

        $match = MatchGame::query()->find($matchId);
        if (! $match) {
            return ['ok' => false, 'errors' => ['Spiel nicht gefunden.']];
        }

        try {
            if (is_string($html) && trim($html) !== '') {
                $parsed = $this->weltfussball->parse($html);
            } elseif ($useCachedHtml) {
                $cached = $this->wfProxy->cachedMatchHtml($url);
                if ($cached === null) {
                    return [
                        'ok' => false,
                        'challenge' => true,
                        'proxy_url' => $this->wfProxy->proxyUrl($url),
                        'url' => $url,
                        'errors' => ['Kein Spielbericht im Frame-Cache. Bitte Challenge lösen oder Seite im Frame neu laden.'],
                    ];
                }
                $parsed = $this->weltfussball->parse($cached);
            } else {
                $parsed = $this->weltfussball->fetchAndParse($url, $cookies);
            }
        } catch (CloudflareChallengeException $e) {
            $target = $e->targetUrl !== '' ? $e->targetUrl : $url;

            return [
                'ok' => false,
                'challenge' => true,
                'proxy_url' => $this->wfProxy->proxyUrl($target),
                'url' => $target,
                'errors' => [$e->getMessage()],
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        }

        $pm = $this->pointsMode($userId);
        $homeDb = $this->playersForTeam($userId, (int) $match->match_hometeam_id, $matchId);
        $guestDb = $this->playersForTeam($userId, (int) $match->match_guestteam_id, $matchId);

        [$homeMapped, $homeUnmatched, $homeScore, $homePs] = $this->mapScrapedSide($parsed['home'], $homeDb, $pm);
        [$guestMapped, $guestUnmatched, $guestScore, $guestPs] = $this->mapScrapedSide($parsed['guest'], $guestDb, $pm);

        // Own goals count for the opponent.
        $homeOwn = $this->sumScrapedOwngoals($parsed['home']);
        $guestOwn = $this->sumScrapedOwngoals($parsed['guest']);

        $players = $homeMapped + $guestMapped;
        $unmatched = array_values(array_unique(array_merge($homeUnmatched, $guestUnmatched)));

        return [
            'ok' => true,
            'message' => count($players) === 1
                ? '1 Spieler zugeordnet.'
                : count($players).' Spieler zugeordnet.',
            'url' => $url,
            'match_minutes' => (int) $parsed['match_minutes'],
            'result' => [
                'homescore' => $homeScore + $guestOwn,
                'guestscore' => $guestScore + $homeOwn,
                'homescore_penalty' => $homePs > 0 || $guestPs > 0 ? $homePs : -1,
                'guestscore_penalty' => $homePs > 0 || $guestPs > 0 ? $guestPs : -1,
            ],
            'players' => $players,
            'unmatched' => $unmatched,
            'matched' => count($players),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $scraped
     * @param  list<array<string, mixed>>  $dbPlayers
     * @return array{0: array<string, array<string, int|string>>, 1: list<string>, 2: int, 3: int}
     */
    private function mapScrapedSide(array $scraped, array $dbPlayers, string $pm): array
    {
        $mapped = [];
        $unmatched = [];
        $goals = 0;
        $psHits = 0;
        $usedPt = [];

        foreach ($scraped as $sp) {
            $name = trim((string) ($sp['player_name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $goals += (int) ($sp['player_num_goals'] ?? 0);
            $psHits += (int) ($sp['player_penalties_hit'] ?? 0);

            $ptId = $this->findMatchingPlayerteamId($name, $dbPlayers, $usedPt);
            if ($ptId === null) {
                $unmatched[] = $name;
                continue;
            }
            $usedPt[$ptId] = true;

            $cards = strtoupper((string) ($sp['player_cards'] ?? '0'));
            $card = match ($cards) {
                'Y' => 'y',
                'YR' => 'yr',
                'R' => 'r',
                default => 'n',
            };

            $goalValue = $pm === 'new'
                ? (string) (($sp['player_goal'] ?? '0') === '0' ? '0' : $sp['player_goal'])
                : (int) ($sp['player_num_goals'] ?? 0);
            $owngoalValue = $pm === 'new'
                ? (string) (($sp['player_owngoal'] ?? '0') === '0' ? '0' : $sp['player_owngoal'])
                : (int) ($sp['player_num_owngoals'] ?? 0);

            $mapped[(string) $ptId] = [
                'minutes' => (int) ($sp['player_minutes'] ?? 0),
                'minute_in' => (int) ($sp['player_change_in'] ?? 0),
                'minute_out' => (int) ($sp['player_change_out'] ?? 0),
                'goals' => $goalValue,
                'owngoals' => $owngoalValue,
                'assists' => (int) ($sp['player_num_assists'] ?? 0),
                'cards' => $card,
                'penaltieslost' => 0,
                'penaltiessaved' => 0,
                'penaltyshootout_save' => 0,
                'penaltyshootout_lost' => (int) ($sp['player_penalties_fail'] ?? 0),
                'penaltyshootout_hit' => (int) ($sp['player_penalties_hit'] ?? 0),
            ];
        }

        return [$mapped, $unmatched, $goals, $psHits];
    }

    /**
     * @param  list<array<string, mixed>>  $dbPlayers
     * @param  array<int, true>  $usedPt
     */
    private function findMatchingPlayerteamId(string $scrapedName, array $dbPlayers, array $usedPt): ?int
    {
        $needle = mb_strtolower(trim($scrapedName));
        foreach ($dbPlayers as $db) {
            $ptId = (int) ($db['playerteam_id'] ?? 0);
            if ($ptId <= 0 || isset($usedPt[$ptId])) {
                continue;
            }
            $candidates = array_filter([
                (string) ($db['player_name_fid_wf'] ?? ''),
                trim(($db['player_fname'] ?? '').' '.($db['player_lname'] ?? '')),
                trim(($db['player_lname'] ?? '').' '.($db['player_fname'] ?? '')),
            ]);
            foreach ($candidates as $candidate) {
                if ($candidate !== '' && mb_strtolower($candidate) === $needle) {
                    return $ptId;
                }
            }
        }

        return null;
    }

    /**
     * @param  list<array<string, mixed>>  $scraped
     */
    private function sumScrapedOwngoals(array $scraped): int
    {
        $sum = 0;
        foreach ($scraped as $sp) {
            $sum += (int) ($sp['player_num_owngoals'] ?? 0);
        }

        return $sum;
    }

    /**
     * Store the result of a match and re-apply the result dependent score parts.
     *
     * @param  array{
     *     homescore: int|string,
     *     guestscore: int|string,
     *     homescore_penalty?: int|string,
     *     guestscore_penalty?: int|string,
     *     minutes?: int|string,
     *     url?: string
     * }  $input
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function setMatchResult(int $matchId, array $input): array
    {
        if ($matchId <= 0) {
            return ['ok' => false, 'errors' => ['No matchID given!']];
        }

        $match = MatchGame::query()->find($matchId);
        if (! $match) {
            return ['ok' => false, 'errors' => ['No Match for this ID was found!']];
        }

        $pm = $this->pointsMode();

        $homescore = $this->intOrDefault($input['homescore'] ?? null, -1);
        $guestscore = $this->intOrDefault($input['guestscore'] ?? null, -1);
        $homescorePenalty = $this->intOrDefault($input['homescore_penalty'] ?? null, -1);
        $guestscorePenalty = $this->intOrDefault($input['guestscore_penalty'] ?? null, -1);
        $minutes = trim((string) ($input['minutes'] ?? ''));
        $url = trim((string) ($input['url'] ?? ''));

        if ($minutes !== '' && ! in_array((int) $minutes, [90, 120], true)) {
            return ['ok' => false, 'errors' => ['Spielzeit muss 90 oder 120 Minuten sein.']];
        }

        DB::transaction(function () use ($match, $matchId, $pm, $homescore, $guestscore, $homescorePenalty, $guestscorePenalty, $minutes, $url, $input) {
            $match->match_homescore = $homescore;
            $match->match_guestscore = $guestscore;
            $match->match_homescore_penalty = $homescorePenalty;
            $match->match_guestscore_penalty = $guestscorePenalty;
            if (array_key_exists('url', $input)) {
                $match->match_url = $url;
            }
            if ($minutes !== '') {
                $match->match_minutes = (int) $minutes;
            }

            $homeTeamId = (int) $match->match_hometeam_id;
            $guestTeamId = (int) $match->match_guestteam_id;

            $playerteams = Playerteam::query()
                ->whereIn('playerteam_team_id', [$homeTeamId, $guestTeamId])
                ->get()
                ->keyBy(fn (Playerteam $item) => (int) $item->playerteam_id);

            if ($playerteams->isNotEmpty()) {
                $statsByPlayerteam = Playerstats::query()
                    ->where('playerstats_match_id', $matchId)
                    ->whereIn('playerstats_playerteam_id', $playerteams->keys()->all())
                    ->orderBy('playerstats_id')
                    ->get()
                    ->groupBy('playerstats_playerteam_id');

                foreach ($statsByPlayerteam as $playerteamId => $group) {
                    /** @var Playerstats $stat */
                    $stat = $group->first();
                    $playerteam = $playerteams->get((int) $playerteamId);
                    if (! $playerteam) {
                        continue;
                    }

                    $position = (string) ($playerteam->playerteam_player_position ?: '');
                    $statMinutes = (int) $stat->playerstats_minutes;
                    $teamId = (int) $playerteam->playerteam_team_id;

                    if ($teamId === $homeTeamId) {
                        $oppScore = $guestscore;
                        $winMargin = $homescore - $guestscore;
                        $lossMargin = $guestscore - $homescore;
                    } elseif ($teamId === $guestTeamId) {
                        $oppScore = $homescore;
                        $winMargin = $guestscore - $homescore;
                        $lossMargin = $homescore - $guestscore;
                    } else {
                        continue;
                    }

                    $scoreNooppgoals = $this->calcScoreOppGoalsNo($oppScore, $position, $statMinutes);
                    $scoreHighWin = $this->calcScoreHighWin($winMargin, $statMinutes);
                    $scoreHighLoss = $this->calcScoreHighLoss($lossMargin, $statMinutes);

                    // Legacy quirk: the new high win/loss values are stored but never added back
                    // into the total, while the old ones are subtracted. New mode additionally
                    // leaves the oppgoals part untouched (it is goal minute dependent).
                    if ($pm === 'new') {
                        $score = (int) $stat->playerstats_score
                            - (int) $stat->playerstats_score_nooppgoals
                            - (int) $stat->playerstats_score_high_loss
                            - (int) $stat->playerstats_score_high_win
                            + $scoreNooppgoals;
                    } else {
                        $scoreOppgoals = $this->calcScoreOppGoals($oppScore, $position, $statMinutes);
                        $score = (int) $stat->playerstats_score
                            - (int) $stat->playerstats_score_oppgoals
                            - (int) $stat->playerstats_score_nooppgoals
                            - (int) $stat->playerstats_score_high_loss
                            - (int) $stat->playerstats_score_high_win
                            + $scoreOppgoals
                            + $scoreNooppgoals;
                        $stat->playerstats_score_oppgoals = $scoreOppgoals;
                    }

                    $stat->playerstats_score_nooppgoals = $scoreNooppgoals;
                    $stat->playerstats_score_high_win = $scoreHighWin;
                    $stat->playerstats_score_high_loss = $scoreHighLoss;
                    $stat->playerstats_score = $score;
                    $stat->save();
                }
            }

            $match->save();
        });

        return ['ok' => true, 'message' => 'Match: Result successfully updated!'];
    }

    /**
     * Store goals/penalty shootout goals and the playerstats of one player in one go.
     *
     * @param  array{
     *     minutes: mixed,
     *     goals: mixed,
     *     assists: mixed,
     *     cards: mixed,
     *     owngoals: mixed,
     *     penaltieslost: mixed,
     *     penaltiessaved: mixed,
     *     penaltyshootout_save: mixed,
     *     penaltyshootout_lost: mixed,
     *     penaltyshootout_hit: mixed,
     *     minute_in: mixed,
     *     minute_out: mixed
     * }  $input
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function savePlayerStats(int $matchId, int $playerteamId, array $input): array
    {
        if ($matchId <= 0 || $playerteamId <= 0) {
            return ['ok' => false, 'errors' => ['No player/match ID given!']];
        }

        $playerteam = Playerteam::query()->find($playerteamId);
        $match = MatchGame::query()->find($matchId);
        if (! $playerteam || ! $match) {
            return ['ok' => false, 'errors' => ['No player/match found!']];
        }

        $pm = $this->pointsMode();
        $position = (string) ($playerteam->playerteam_player_position ?: '');
        $teamId = (int) $playerteam->playerteam_team_id;

        $goalsInput = trim((string) ($input['goals'] ?? ''));
        $owngoalsInput = trim((string) ($input['owngoals'] ?? ''));
        $psHit = (int) ($input['penaltyshootout_hit'] ?? 0);
        $psLost = (int) ($input['penaltyshootout_lost'] ?? 0);
        $psSave = (int) ($input['penaltyshootout_save'] ?? 0);
        $minutes = (int) ($input['minutes'] ?? 0);
        $minuteIn = (int) ($input['minute_in'] ?? 0);
        $minuteOut = (int) ($input['minute_out'] ?? 0);
        $assists = (int) ($input['assists'] ?? 0);
        $penaltiesLost = (int) ($input['penaltieslost'] ?? 0);
        $penaltiesSaved = (int) ($input['penaltiessaved'] ?? 0);
        $cards = (string) ($input['cards'] ?? 'n');
        $cards = in_array($cards, ['y', 'yr', 'r'], true) ? $cards : 'n';

        return DB::transaction(function () use (
            $match,
            $matchId,
            $playerteamId,
            $pm,
            $position,
            $teamId,
            $goalsInput,
            $owngoalsInput,
            $psHit,
            $psLost,
            $psSave,
            $minutes,
            $minuteIn,
            $minuteOut,
            $assists,
            $penaltiesLost,
            $penaltiesSaved,
            $cards
        ) {
            if ($pm === 'new') {
                $this->deleteGoals($matchId, $playerteamId, false);
                if ($this->hasGoalInput($goalsInput)) {
                    $this->insertGoals($goalsInput, $matchId, $playerteamId, false);
                }

                $this->deleteGoals($matchId, $playerteamId, true);
                if ($this->hasGoalInput($owngoalsInput)) {
                    $this->insertGoals($owngoalsInput, $matchId, $playerteamId, true);
                }

                $this->deletePsGoals($matchId, $playerteamId, false);
                if ($psHit > 0) {
                    $this->insertPsGoals($psHit, $matchId, $playerteamId, false);
                }

                $this->deletePsGoals($matchId, $playerteamId, true);
                if ($psLost > 0) {
                    $this->insertPsGoals($psLost, $matchId, $playerteamId, true);
                }
            }

            $stat = Playerstats::query()
                ->where('playerstats_playerteam_id', $playerteamId)
                ->where('playerstats_match_id', $matchId)
                ->orderBy('playerstats_id')
                ->first();

            if ($stat) {
                $message = 'Existing Playerstats successfully updated!';
                if ($minutes === 0) {
                    $stat->delete();

                    return ['ok' => true, 'message' => $message];
                }
            } else {
                $stat = new Playerstats;
                $stat->playerstats_match_id = $matchId;
                $stat->playerstats_playerteam_id = $playerteamId;
                $stat->playerstats_matchround_id = (int) $match->match_round;
                $message = 'New Playerstats successfully added!';
            }

            if ($pm === 'new') {
                $numGoals = $this->goalCount($goalsInput);
                $numOwngoals = $this->goalCount($owngoalsInput);
            } else {
                $numGoals = is_numeric($goalsInput) ? (int) $goalsInput : 0;
                $numOwngoals = is_numeric($owngoalsInput) ? (int) $owngoalsInput : 0;
            }

            $stat->playerstats_goals = $numGoals;
            $stat->playerstats_assists = $assists;
            $stat->playerstats_minutes = $minutes;
            $stat->playerstats_minute_in = $minuteIn;
            $stat->playerstats_minute_out = $minuteOut;
            $stat->playerstats_cards = $cards;
            $stat->playerstats_owngoals = $numOwngoals;
            $stat->playerstats_penaltieslost = $penaltiesLost;
            $stat->playerstats_penaltiessaved = $penaltiesSaved;
            $stat->playerstats_penaltyshootout_save = $psSave;
            $stat->playerstats_penaltyshootout_lost = $psLost;
            $stat->playerstats_penaltyshootout_hit = $psHit;

            $scoreGoals = $this->calcScoreGoals($numGoals, $position);
            $scoreAssists = $this->calcScoreAssists($assists);
            $scoreMinutes = $this->calcScoreMinutes($minutes);
            $scoreCards = $this->calcScoreCards($cards);
            $scoreOwngoals = $this->calcScoreOwngoals($numOwngoals);
            $scorePenaltyLost = $this->calcScorePenaltyLost($penaltiesLost);
            $scorePenaltySaved = $this->calcScorePenaltySaved($penaltiesSaved);
            $scorePsSave = $this->calcScorePenaltyshootoutSave($psSave);
            $scorePsLost = $this->calcScorePenaltyshootoutLost($psLost);
            $scorePsHit = $this->calcScorePenaltyshootoutHit($psHit);

            $homescore = (int) $match->match_homescore;
            $guestscore = (int) $match->match_guestscore;
            $homeTeamId = (int) $match->match_hometeam_id;
            $guestTeamId = (int) $match->match_guestteam_id;
            $matchMinutes = (int) $match->match_minutes;

            $scoreOppgoals = 0;
            $scoreNooppgoals = 0;

            if ($homescore >= 0 && $guestscore >= 0) {
                if ($teamId === $homeTeamId) {
                    $scoreOppgoals = $pm === 'new'
                        ? $this->calcScoreOppGoalsNew(
                            $this->goalsAgainst($matchId, $guestTeamId, $homeTeamId),
                            $position,
                            $minutes,
                            $minuteIn,
                            $minuteOut,
                            $matchMinutes
                        )
                        : $this->calcScoreOppGoals($guestscore, $position, $minutes);
                    $scoreNooppgoals = $this->calcScoreOppGoalsNo($guestscore, $position, $minutes);
                } elseif ($teamId === $guestTeamId) {
                    $scoreOppgoals = $pm === 'new'
                        ? $this->calcScoreOppGoalsNew(
                            $this->goalsAgainst($matchId, $homeTeamId, $guestTeamId),
                            $position,
                            $minutes,
                            $minuteIn,
                            $minuteOut,
                            $matchMinutes
                        )
                        : $this->calcScoreOppGoals($homescore, $position, $minutes);
                    $scoreNooppgoals = $this->calcScoreOppGoalsNo($homescore, $position, $minutes);
                }
            }

            // Legacy quirk: high win/loss are calculated in setMatchresult only and are
            // deliberately left out of the total that is written here.
            $scoreTotal = $scoreGoals + $scoreAssists + $scoreMinutes + $scoreCards + $scoreOwngoals
                + $scorePenaltyLost + $scorePenaltySaved + $scoreOppgoals + $scoreNooppgoals
                + $scorePsLost + $scorePsSave + $scorePsHit;

            $stat->playerstats_score_goals = $scoreGoals;
            $stat->playerstats_score_assists = $scoreAssists;
            $stat->playerstats_score_minutes = $scoreMinutes;
            $stat->playerstats_score_cards = $scoreCards;
            $stat->playerstats_score_owngoals = $scoreOwngoals;
            $stat->playerstats_score_penaltieslost = $scorePenaltyLost;
            $stat->playerstats_score_penaltiessaved = $scorePenaltySaved;
            $stat->playerstats_score_penaltyshootout_save = $scorePsSave;
            $stat->playerstats_score_penaltyshootout_lost = $scorePsLost;
            $stat->playerstats_score_penaltyshootout_hit = $scorePsHit;
            $stat->playerstats_score_oppgoals = $scoreOppgoals;
            $stat->playerstats_score_nooppgoals = $scoreNooppgoals;
            $stat->playerstats_score = $scoreTotal;
            $stat->save();

            return ['ok' => true, 'message' => $message];
        });
    }

    private function deleteGoals(int $matchId, int $playerteamId, bool $own): void
    {
        Goal::query()
            ->where('goal_match_id', $matchId)
            ->where('goal_playerteam_id', $playerteamId)
            ->where('goal_owngoal', $own ? 1 : 0)
            ->delete();
    }

    private function insertGoals(string $goalsList, int $matchId, int $playerteamId, bool $own): void
    {
        foreach (explode(';', trim($goalsList)) as $minute) {
            if (! is_numeric($minute)) {
                continue;
            }

            $goal = new Goal;
            $goal->goal_match_id = $matchId;
            $goal->goal_playerteam_id = $playerteamId;
            $goal->goal_minute = (int) $minute;
            $goal->goal_owngoal = $own ? 1 : 0;
            $goal->goal_penalty = 0;
            $goal->goal_penaltyshootout = 0;
            $goal->save();
        }
    }

    private function deletePsGoals(int $matchId, int $playerteamId, bool $fail): void
    {
        $query = Psgoal::query()
            ->where('psgoal_match_id', $matchId)
            ->where('psgoal_playerteam_id', $playerteamId);

        if ($fail) {
            $query->where('psgoal_fail', 1);
        } else {
            $query->where('psgoal_hit', 1);
        }

        $query->delete();
    }

    private function insertPsGoals(int $numGoals, int $matchId, int $playerteamId, bool $fail): void
    {
        for ($i = 0; $i < $numGoals; $i++) {
            $psgoal = new Psgoal;
            $psgoal->psgoal_match_id = $matchId;
            $psgoal->psgoal_playerteam_id = $playerteamId;
            $psgoal->psgoal_minute = 120;
            $psgoal->psgoal_hit = $fail ? 0 : 1;
            $psgoal->psgoal_fail = $fail ? 1 : 0;
            $psgoal->save();
        }
    }

    /**
     * Minutes of the goals conceded by a team: regular goals of the opposite team
     * plus own goals of the team itself.
     *
     * @return list<int>
     */
    private function goalsAgainst(int $matchId, int $oppositeTeamId, int $ownTeamId): array
    {
        return Goal::query()
            ->join('ffb_playerteam', 'ffb_playerteam.playerteam_id', '=', 'ffb_goal.goal_playerteam_id')
            ->where('ffb_goal.goal_match_id', $matchId)
            ->where(function (Builder $query) use ($oppositeTeamId, $ownTeamId) {
                $query
                    ->where(function (Builder $sub) use ($oppositeTeamId) {
                        $sub->where('ffb_playerteam.playerteam_team_id', $oppositeTeamId)
                            ->where('ffb_goal.goal_owngoal', 0);
                    })
                    ->orWhere(function (Builder $sub) use ($ownTeamId) {
                        $sub->where('ffb_playerteam.playerteam_team_id', $ownTeamId)
                            ->where('ffb_goal.goal_owngoal', 1);
                    });
            })
            ->pluck('ffb_goal.goal_minute')
            ->map(fn ($minute) => (int) $minute)
            ->values()
            ->all();
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return array<int, array<int, list<string>>> owngoal flag => playerteam id => minutes
     */
    private function goalMinutesByPlayerteam(int $matchId, array $playerteamIds): array
    {
        $minutes = [0 => [], 1 => []];

        $goals = Goal::query()
            ->where('goal_match_id', $matchId)
            ->whereIn('goal_playerteam_id', $playerteamIds)
            ->orderBy('goal_id')
            ->get();

        foreach ($goals as $goal) {
            $own = (int) $goal->goal_owngoal ? 1 : 0;
            $minutes[$own][(int) $goal->goal_playerteam_id][] = (string) (int) $goal->goal_minute;
        }

        return $minutes;
    }

    /**
     * @param  list<string>  $minutes
     */
    private function goalString(int $count, array $minutes): string|int
    {
        if ($count <= 0) {
            return 0;
        }

        return $minutes === [] ? '0' : implode(';', $minutes);
    }

    private function hasGoalInput(string $value): bool
    {
        if ($value === '') {
            return false;
        }

        return ! is_numeric($value) || (float) $value != 0.0;
    }

    private function goalCount(string $value): int
    {
        if (! $this->hasGoalInput($value)) {
            return 0;
        }

        return count(explode(';', trim($value)));
    }

    private function calcScoreGoals(int $num, string $pos): int
    {
        return match ($pos) {
            'g' => (int) $this->options()->options_score_goals_g * $num,
            'd' => (int) $this->options()->options_score_goals_d * $num,
            'm' => (int) $this->options()->options_score_goals_m * $num,
            's' => (int) $this->options()->options_score_goals_s * $num,
            default => 0,
        };
    }

    private function calcScoreOwngoals(int $num): int
    {
        return (int) $this->options()->options_score_owngoals * $num;
    }

    private function calcScoreAssists(int $num): int
    {
        return (int) $this->options()->options_score_assists * $num;
    }

    private function calcScoreMinutes(int $num): int
    {
        if ($num === 0) {
            return 0;
        }

        $options = $this->options();
        $full = (int) $options->options_score_minutes;
        $treshold = (int) $options->options_score_minutes_treshold;

        if ($this->pointsMode() === 'new') {
            if ($num < $treshold) {
                return (int) $options->options_score_minutes_lt30;
            }
            if ($num < $full) {
                return (int) $options->options_score_minutes_lt;
            }

            return (int) $options->options_score_minutes_gt;
        }

        return $num < $full
            ? (int) $options->options_score_minutes_lt
            : (int) $options->options_score_minutes_gt;
    }

    private function calcScoreCards(string $type): int
    {
        return match ($type) {
            'y' => (int) $this->options()->options_score_card_y,
            'r' => (int) $this->options()->options_score_card_r,
            'yr' => (int) $this->options()->options_score_card_yr,
            default => 0,
        };
    }

    /**
     * Result dependent conceded goals (pointsmode "old"); $minutes is unused by design.
     */
    private function calcScoreOppGoals(int $num, string $pos, int $minutes): int
    {
        return match ($pos) {
            'g' => (int) $this->options()->options_score_oppgoals_g * (int) floor($num / 2),
            'd' => (int) $this->options()->options_score_oppgoals_d * (int) floor($num / 2),
            default => 0,
        };
    }

    /**
     * Goal minute dependent conceded goals (pointsmode "new").
     *
     * @param  list<int>  $goalMinutes
     */
    private function calcScoreOppGoalsNew(
        array $goalMinutes,
        string $pos,
        int $minutes,
        int $minuteIn,
        int $minuteOut,
        int $matchMinutes
    ): int {
        if ($goalMinutes === [] || ($pos !== 'g' && $pos !== 'd')) {
            return 0;
        }

        $numGoals = 0;
        foreach ($goalMinutes as $minute) {
            if (($minute >= $minuteIn && $minute <= $minuteOut) || ($minute >= $matchMinutes && $minuteOut >= $matchMinutes)) {
                $numGoals++;
            }
        }

        return $pos === 'g'
            ? (int) $this->options()->options_score_oppgoals_g * (int) floor($numGoals / 2)
            : (int) $this->options()->options_score_oppgoals_d * (int) floor($numGoals / 2);
    }

    private function calcScoreOppGoalsNo(int $num, string $pos, int $minutes): int
    {
        $options = $this->options();
        $pm = $this->pointsMode();
        $clean = ($pm === 'new' && $num === 0 && $minutes >= (int) $options->options_score_minutes_treshold)
            || ($pm === 'old' && $num === 0);

        if (! $clean) {
            return 0;
        }

        return match ($pos) {
            'g' => (int) $options->options_score_no_oppgoals_g,
            'd' => (int) $options->options_score_no_oppgoals_d,
            'm' => (int) $options->options_score_no_oppgoals_m,
            default => 0,
        };
    }

    private function calcScorePenaltyLost(int $num): int
    {
        return (int) $this->options()->options_score_penalty_lost * $num;
    }

    private function calcScorePenaltySaved(int $num): int
    {
        return (int) $this->options()->options_score_penalty_saved * $num;
    }

    private function calcScoreHighLoss(int $num, int $minutes): int
    {
        $options = $this->options();
        if (
            $minutes >= (int) $options->options_score_minutes_treshold
            && $num >= (int) $options->options_score_high_win_loss_treshold
        ) {
            return (int) $options->options_score_high_loss;
        }

        return 0;
    }

    private function calcScoreHighWin(int $num, int $minutes): int
    {
        $options = $this->options();
        if (
            $minutes >= (int) $options->options_score_minutes_treshold
            && $num >= (int) $options->options_score_high_win_loss_treshold
        ) {
            return (int) $options->options_score_high_win;
        }

        return 0;
    }

    private function calcScorePenaltyshootoutSave(int $num): int
    {
        return (int) $this->options()->options_score_penaltyshootout_save * $num;
    }

    private function calcScorePenaltyshootoutLost(int $num): int
    {
        return (int) $this->options()->options_score_penaltyshootout_lost * $num;
    }

    private function calcScorePenaltyshootoutHit(int $num): int
    {
        return (int) $this->options()->options_score_penaltyshootout_hit * $num;
    }

    public function pointsMode(int $userId = 0): string
    {
        return (string) ($this->options($userId)->options_game_pointsmode ?: 'new');
    }

    /**
     * Scoring options of the league currently selected in the admin center.
     */
    private function options(int $userId = 0): GameOptions
    {
        if ($this->optionsCache instanceof GameOptions) {
            return $this->optionsCache;
        }

        $gameId = $this->adminCenter->selectedGameId($userId);

        return $this->optionsCache = GameOptions::query()->where('options_game_id', $gameId)->first()
            ?? GameOptions::query()->where('options_game_id', 0)->first()
            ?? new GameOptions;
    }

    /**
     * @return array{name: string, nationality: string}
     */
    private function teamInfo(?Team $team): array
    {
        return [
            'name' => (string) ($team?->team_name ?? ''),
            'nationality' => (string) ($team?->team_nationality ?? ''),
        ];
    }

    private function intOrDefault(mixed $value, int $default): int
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return (int) $value;
    }
}
