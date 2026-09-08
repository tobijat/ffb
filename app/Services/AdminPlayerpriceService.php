<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerprice;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Port of legacy modules/administration/playerprice2014.php.
 */
class AdminPlayerpriceService
{
    private const HISTORY_LENGTH = 10;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly EloRatingClient $eloRating,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $gameId = (int) ($shell['selected_game_id'] ?? 0);

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_game_id' => $gameId,
            'selected_game' => $shell['selected_game'],
            'matchrounds' => $gameId > 0 ? $this->matchrounds($gameId) : [],
            'price_margins' => $this->priceMargins(),
            'price_options' => range(1, 19),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function calculatePlayerPricesForMatchround(int $userId, array $input): array
    {
        $gameId = $this->adminCenter->selectedGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        $priceMargin = isset($input['price_margin']) && $input['price_margin'] !== ''
            ? (float) $input['price_margin']
            : 0.0;

        if ($matchroundId <= 0) {
            return ['ok' => false, 'errors' => ['Please select a Matchround!']];
        }
        if ($priceMargin <= 0) {
            return ['ok' => false, 'errors' => ['Please select Price Margin!']];
        }
        if (! $this->matchroundBelongsToGame($matchroundId, $gameId)) {
            return ['ok' => false, 'errors' => ['Ungültige Spielrunde für die aktive Liga.']];
        }

        try {
            $details = [];
            $teamList = $this->teamIdsForMatchround($matchroundId);
            foreach ($teamList as $teamId) {
                $margins = $this->calculatePlayerPriceMarginsForTeam($teamId, $priceMargin);
                array_push($details, ...$this->updatePlayerPrices($margins, $matchroundId));
            }

            return [
                'ok' => true,
                'message' => 'Dynamic PlayerPrices aktualisiert.',
                'details' => $details,
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function calculateEloTeamPricesForGame(int $userId, array $input): array
    {
        $gameId = $this->adminCenter->selectedGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $maxPrice = (int) ($input['max_price'] ?? 0);
        $minPrice = (int) ($input['min_price'] ?? 0);

        if ($maxPrice <= 0) {
            return ['ok' => false, 'errors' => ['Please select max price!']];
        }
        if ($minPrice <= 0) {
            return ['ok' => false, 'errors' => ['Please select min price!']];
        }
        if ($maxPrice <= $minPrice) {
            return ['ok' => false, 'errors' => ['Max price needs to be greater than min price!']];
        }

        try {
            $teamList = $this->teamIdsForGame($gameId);
            $teamPrices = $this->getTeamPrices($teamList, $maxPrice, $minPrice);
            $details = [];
            foreach ($teamPrices as $teamId => $teamPrice) {
                $details[] = $this->updateBasePriceForTeamAndPlayers((int) $teamId, (float) $teamPrice);
            }

            return [
                'ok' => true,
                'message' => 'ELO BasePrices für Liga-Teams aktualisiert.',
                'details' => $details,
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}
     */
    public function calculateEloTeamPricesForMatchround(int $userId, array $input): array
    {
        $gameId = $this->adminCenter->selectedGameId($userId);
        if ($gameId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga auswählen.']];
        }

        $matchroundId = (int) ($input['matchround_id'] ?? 0);
        $maxPrice = (int) ($input['max_price'] ?? 0);
        $minPrice = (int) ($input['min_price'] ?? 0);

        if ($matchroundId <= 0) {
            return ['ok' => false, 'errors' => ['Please select a Matchround!']];
        }
        if ($maxPrice <= 0) {
            return ['ok' => false, 'errors' => ['Please select max price!']];
        }
        if ($minPrice <= 0) {
            return ['ok' => false, 'errors' => ['Please select min price!']];
        }
        if ($maxPrice < $minPrice) {
            return ['ok' => false, 'errors' => ['Max price needs to be greater than min price!']];
        }
        if (! $this->matchroundBelongsToGame($matchroundId, $gameId)) {
            return ['ok' => false, 'errors' => ['Ungültige Spielrunde für die aktive Liga.']];
        }

        try {
            $teamList = $this->teamIdsForMatchround($matchroundId);
            $teamPrices = $this->getTeamPrices($teamList, $maxPrice, $minPrice);
            $details = [];
            foreach ($teamPrices as $teamId => $teamPrice) {
                $details[] = $this->updateBasePriceForTeamAndPlayers((int) $teamId, (float) $teamPrice);
            }

            return [
                'ok' => true,
                'message' => 'ELO BasePrices für Spielrunden-Teams aktualisiert.',
                'details' => $details,
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    private function matchrounds(int $gameId): array
    {
        return Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->orderBy('matchround_startdate')
            ->get(['matchround_id', 'matchround_title'])
            ->map(static fn (Matchround $r): array => [
                'matchround_id' => (int) $r->matchround_id,
                'matchround_title' => (string) $r->matchround_title,
            ])
            ->all();
    }

    /**
     * @return list<float>
     */
    private function priceMargins(): array
    {
        $margins = [];
        for ($i = 0.5; $i <= 3; $i += 0.5) {
            $margins[] = round($i, 1);
        }

        return $margins;
    }

    private function matchroundBelongsToGame(int $matchroundId, int $gameId): bool
    {
        return Matchround::query()
            ->where('matchround_id', $matchroundId)
            ->where('matchround_game_id', $gameId)
            ->exists();
    }

    /**
     * @return list<int>
     */
    private function teamIdsForMatchround(int $matchroundId): array
    {
        $matches = MatchGame::query()
            ->where('match_round', $matchroundId)
            ->get(['match_hometeam_id', 'match_guestteam_id']);

        $ids = [];
        foreach ($matches as $match) {
            $ids[] = (int) $match->match_hometeam_id;
            $ids[] = (int) $match->match_guestteam_id;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * @return list<int>
     */
    private function teamIdsForGame(int $gameId): array
    {
        $matches = MatchGame::query()
            ->join('ffb_matchround', 'ffb_match.match_round', '=', 'ffb_matchround.matchround_id')
            ->where('ffb_matchround.matchround_game_id', $gameId)
            ->get(['ffb_match.match_hometeam_id', 'ffb_match.match_guestteam_id']);

        $ids = [];
        foreach ($matches as $match) {
            $ids[] = (int) $match->match_hometeam_id;
            $ids[] = (int) $match->match_guestteam_id;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * @param  array<int, float>  $playerPriceMargins
     * @return list<string>
     */
    private function updatePlayerPrices(array $playerPriceMargins, int $matchroundId): array
    {
        $details = [];

        DB::transaction(function () use ($playerPriceMargins, $matchroundId, &$details) {
            foreach ($playerPriceMargins as $playerteamId => $priceMargin) {
                $pt = Playerteam::query()->find($playerteamId);
                if (! $pt) {
                    continue;
                }
                $basePrice = (float) $pt->playerteam_player_price;
                $price = $basePrice + (float) $priceMargin;

                $playerprice = Playerprice::query()
                    ->where('playerprice_playerteam_id', $playerteamId)
                    ->where('playerprice_matchround_id', $matchroundId)
                    ->first();

                if (! $playerprice) {
                    $playerprice = new Playerprice();
                    $playerprice->playerprice_playerteam_id = $playerteamId;
                    $playerprice->playerprice_matchround_id = $matchroundId;
                }

                $playerprice->playerprice_price = $price;
                $playerprice->playerprice_av_power = 1;
                $playerprice->playerprice_player_power = 1;
                $playerprice->save();

                $details[] = 'Price updated: '.$playerteamId.': '.$price;
            }
        });

        return $details;
    }

    /**
     * @return array<int, float> playerteam_id => margin
     */
    private function calculatePlayerPriceMarginsForTeam(int $teamId, float $margin): array
    {
        $lastMatches = $this->lastMatches($teamId);
        if ($lastMatches === []) {
            return [];
        }

        $opponents = $this->opponents($teamId, $lastMatches);
        $teamsIdList = array_map(static fn (Team $t): int => (int) $t->team_id, $opponents);
        $teamsIdList[] = $teamId;
        $teamPrices = $this->getTeamPrices($teamsIdList, 13, 3);

        $avgPositionPoints = [
            'g' => $this->avgPositionPoints($lastMatches, 'g'),
            'd' => $this->avgPositionPoints($lastMatches, 'd'),
            'm' => $this->avgPositionPoints($lastMatches, 'm'),
            's' => $this->avgPositionPoints($lastMatches, 's'),
        ];

        $players = Playerteam::query()
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_status', 1)
            ->get();

        $playerPriceMargins = [];
        $matchCount = count($lastMatches);

        foreach ($players as $player) {
            $mpSum = 0.0;
            $position = (string) $player->playerteam_player_position;
            $positionAvg = (float) ($avgPositionPoints[$position] ?? 0);

            foreach ($lastMatches as $match) {
                $opponentId = $this->opponentId($match, $teamId);
                $opponentPrice = (float) ($teamPrices[$opponentId] ?? 0);
                $teamPrice = (float) ($teamPrices[$teamId] ?? 0);
                $priceDiff = $opponentPrice - $teamPrice;
                if ($priceDiff < 0) {
                    $priceDiff = 1 - (($priceDiff * -1) / 10);
                } else {
                    $priceDiff = 1 + ($priceDiff / 10);
                }

                $playerstats = Playerstats::query()
                    ->where('playerstats_match_id', (int) $match->match_id)
                    ->where('playerstats_playerteam_id', (int) $player->playerteam_id)
                    ->first();

                if ($playerstats && $positionAvg != 0.0) {
                    $score = (float) $playerstats->playerstats_score;
                    $percentageScore = ($score / $positionAvg) - 1;
                    $mp = (10 * $percentageScore) * $priceDiff;
                    if ($mp > 10) {
                        $mp = 10;
                    }
                    if ($mp < -10) {
                        $mp = -10;
                    }
                } else {
                    $mp = 0;
                }
                $mpSum += $mp;
            }

            $playerStrength = $mpSum / $matchCount;
            if ($playerStrength > $margin) {
                $playerStrength = $margin;
            }
            if ($playerStrength < (-1 * $margin)) {
                $playerStrength = (-1 * $margin);
            }
            $playerPriceMargins[(int) $player->playerteam_id] = round($playerStrength, 1);
        }

        return $playerPriceMargins;
    }

    private function opponentId(MatchGame $match, int $teamId): int
    {
        if ($teamId === (int) $match->match_hometeam_id) {
            return (int) $match->match_guestteam_id;
        }

        return (int) $match->match_hometeam_id;
    }

    /**
     * @param  list<MatchGame>  $matches
     * @return list<Team>
     */
    private function opponents(int $teamId, array $matches): array
    {
        $opponents = [];
        foreach ($matches as $match) {
            if ((int) $match->match_hometeam_id === $teamId) {
                $guest = $match->guestTeam;
                if ($guest) {
                    $opponents[(int) $guest->team_id] = $guest;
                }
            }
            if ((int) $match->match_guestteam_id === $teamId) {
                $home = $match->homeTeam;
                if ($home) {
                    $opponents[(int) $home->team_id] = $home;
                }
            }
        }

        return array_values($opponents);
    }

    /**
     * @return list<MatchGame>
     */
    private function lastMatches(int $teamId): array
    {
        return MatchGame::query()
            ->with(['homeTeam', 'guestTeam'])
            ->where('match_minutes', '>', 0)
            ->where(function ($q) use ($teamId) {
                $q->where('match_guestteam_id', $teamId)
                    ->orWhere('match_hometeam_id', $teamId);
            })
            ->orderByDesc('match_date')
            ->limit(self::HISTORY_LENGTH)
            ->get()
            ->all();
    }

    /**
     * @param  list<MatchGame>  $matches
     */
    private function avgPositionPoints(array $matches, string $position): float
    {
        if ($matches === []) {
            return 0.0;
        }

        $matchIds = array_map(static fn (MatchGame $m): int => (int) $m->match_id, $matches);

        $query = Playerstats::query()
            ->join('ffb_playerteam', 'ffb_playerteam.playerteam_id', '=', 'ffb_playerstats.playerstats_playerteam_id')
            ->where('ffb_playerteam.playerteam_player_position', $position)
            ->whereIn('ffb_playerstats.playerstats_match_id', $matchIds);

        $numResults = (clone $query)->count();
        if ($numResults <= 0) {
            return 0.0;
        }

        $sum = (float) (clone $query)->sum('ffb_playerstats.playerstats_score');

        return $sum / $numResults;
    }

    /**
     * @param  list<int>  $teamIdList
     * @return array<int, float> team_id => price
     */
    public function getTeamPrices(array $teamIdList, float $maxPrice, float $minPrice): array
    {
        $teams = $this->eloRating->ratingsForTeamList($teamIdList);
        $numTeams = count($teams);
        if ($numTeams === 0) {
            return [];
        }

        $priceDifference = $maxPrice - $minPrice;
        $priceStep = $priceDifference / $numTeams;
        $teamPrices = [];
        $i = 0;
        foreach ($teams as $team) {
            $teamPrices[(int) $team['team_id']] = round($minPrice + ($i * $priceStep), 1);
            $i++;
        }

        return $teamPrices;
    }

    private function updateBasePriceForTeamAndPlayers(int $teamId, float $price): string
    {
        $team = Team::query()->find($teamId);
        if (! $team) {
            return 'Base price skipped: missing team '.$teamId;
        }

        DB::transaction(function () use ($team, $price) {
            $team->team_avg_price = $price;
            $team->save();

            Playerteam::query()
                ->where('playerteam_team_id', (int) $team->team_id)
                ->update(['playerteam_player_price' => $price]);
        });

        return 'Base price updated: '.$team->team_name.': '.$price;
    }
}
