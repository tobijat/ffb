<?php

namespace App\Services;

use App\Models\GameOptions;
use App\Models\Matchround;
use App\Models\Playerteam;
use App\Models\UserDetails;
use App\Models\Userscore;
use App\Models\Userteam;
use App\Models\WebUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LineupService
{
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

        $matchround = Matchround::query()->find($matchroundId);
        $priceMode = $this->priceModeForMatchround($matchround);

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

        $dynamicPrices = $this->dynamicPrices($slotIds, $matchroundId, $priceMode);
        $scores = $this->scoresForRound($slotIds, $matchroundId);

        $players = [];
        foreach ($slotIds as $slot => $playerteamId) {
            /** @var Playerteam|null $pt */
            $pt = $playerteams->get($playerteamId);
            if (! $pt || ! $pt->player || ! $pt->team) {
                continue;
            }

            $price = $pt->playerteam_player_price;
            if ($priceMode === 'dynamic' && $dynamicPrices->has($playerteamId)) {
                $price = $dynamicPrices->get($playerteamId);
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
                'playerteam_player_price' => (float) $price,
                'playerstats_score' => (int) ($scores->get($playerteamId) ?? 0),
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
                'userteam_wc_points' => (int) $userteam->userteam_wc_points,
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
        if (count($ids) !== 11) {
            return $this->fail(422, 'Invalid lineup: exactly 11 players are required');
        }

        if (count(array_unique($ids)) !== 11) {
            return $this->fail(422, 'Invalid lineup: duplicate players are not allowed');
        }

        $matchround = Matchround::query()->find($matchroundId);
        if (! $matchround) {
            return $this->fail(422, 'Unknown matchround');
        }

        // Legacy checkMatchround: only allow saves while startdate is still in the future.
        if (! $this->isMatchroundOpen($matchround)) {
            return $this->fail(409, 'The deadline for this matchround has passed; lineup was not saved');
        }

        $options = GameOptions::query()
            ->where('options_game_id', $matchround->matchround_game_id)
            ->first();

        $priceMode = $options?->options_game_pricemode ?: 'constant';
        $playerteams = Playerteam::query()
            ->with(['player', 'team'])
            ->whereIn('playerteam_id', $ids)
            ->get()
            ->keyBy('playerteam_id');

        if ($playerteams->count() !== 11) {
            return $this->fail(422, 'Invalid lineup: one or more players were not found');
        }

        $dynamicPrices = $this->dynamicPrices($ids, $matchroundId, $priceMode);
        $validationError = $this->validateAgainstOptions($ids, $playerteams, $dynamicPrices, $priceMode, $options);
        if ($validationError !== null) {
            return $this->fail(422, $validationError);
        }

        $sumPrice = $this->sumPrices($ids, $playerteams, $dynamicPrices, $priceMode);
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

            foreach (Userteam::playerSlotColumns() as $index => $column) {
                $userteam->{$column} = $ids[$index];
            }

            $userteam->userteam_date = now()->format('Y-m-d H:i:s');
            $userteam->userteam_score = 0;
            $userteam->userteam_wc_points = 0;
            $userteam->userteam_price = $sumPrice;
            $userteam->save();

            $gameId = $this->resolveGameId($userId, (int) $matchround->matchround_game_id);
            Userscore::query()->firstOrCreate(
                [
                    'userscore_user_id' => $userId,
                    'userscore_game_id' => $gameId,
                ],
                [
                    'userscore_total' => 0,
                    'userscore_wc_points' => 0,
                ]
            );
        });

        return [
            'ok' => true,
            'created' => $created,
            'message' => $created ? 'Lineup saved' : 'Lineup updated',
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
     * @param  Collection<int, float|int|string>  $dynamicPrices
     */
    private function validateAgainstOptions(
        array $ids,
        Collection $playerteams,
        Collection $dynamicPrices,
        string $priceMode,
        ?GameOptions $options,
    ): ?string {
        $maxPlayers = (int) ($options?->options_lineup_max_players ?: 11);
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

        if ($options) {
            $maxPerTeam = (int) $options->options_lineup_max_players_team;
            foreach ($perTeam as $count) {
                if ($count > $maxPerTeam) {
                    return "Invalid lineup: at most {$maxPerTeam} players from the same team";
                }
            }

            $rules = [
                'g' => [(int) $options->options_lineup_min_g, (int) $options->options_lineup_max_g],
                'd' => [(int) $options->options_lineup_min_d, (int) $options->options_lineup_max_d],
                'm' => [(int) $options->options_lineup_min_m, (int) $options->options_lineup_max_m],
                's' => [(int) $options->options_lineup_min_s, (int) $options->options_lineup_max_s],
            ];

            foreach ($rules as $position => [$min, $max]) {
                if ($counts[$position] < $min || $counts[$position] > $max) {
                    return "Invalid lineup: position '{$position}' must be between {$min} and {$max}";
                }
            }

            $maxCredits = (float) $options->options_lineup_max_credits;
            $sumPrice = $this->sumPrices($ids, $playerteams, $dynamicPrices, $priceMode);
            if ($sumPrice > $maxCredits) {
                return "Invalid lineup: total price {$sumPrice} exceeds credit limit {$maxCredits}";
            }
        }

        return null;
    }

    /**
     * @param  list<int>  $ids
     * @param  Collection<int, Playerteam>  $playerteams
     * @param  Collection<int, float|int|string>  $dynamicPrices
     */
    private function sumPrices(
        array $ids,
        Collection $playerteams,
        Collection $dynamicPrices,
        string $priceMode,
    ): float {
        $sum = 0.0;
        foreach ($ids as $id) {
            $pt = $playerteams->get($id);
            $price = $pt?->playerteam_player_price ?? 0;
            if ($priceMode === 'dynamic' && $dynamicPrices->has($id)) {
                $price = $dynamicPrices->get($id);
            }
            $sum += (float) $price;
        }

        return round($sum, 1);
    }

    private function resolveGameId(int $userId, int $fallbackGameId): int
    {
        $details = UserDetails::query()->find($userId);
        $selected = (int) ($details?->user_details_ffb_selected_game ?? 0);

        return $selected > 0 ? $selected : $fallbackGameId;
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

    private function priceModeForMatchround(?Matchround $matchround): string
    {
        if (! $matchround) {
            return 'constant';
        }

        $options = GameOptions::query()
            ->where('options_game_id', $matchround->matchround_game_id)
            ->first();

        return $options?->options_game_pricemode ?: 'constant';
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return Collection<int, float|int|string>
     */
    private function dynamicPrices(array $playerteamIds, int $matchroundId, string $priceMode): Collection
    {
        if ($priceMode !== 'dynamic' || $playerteamIds === []) {
            return collect();
        }

        return \App\Models\Playerprice::query()
            ->where('playerprice_matchround_id', $matchroundId)
            ->whereIn('playerprice_playerteam_id', $playerteamIds)
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->playerprice_playerteam_id => $row->playerprice_price]);
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

        return \App\Models\Playerstats::query()
            ->where('playerstats_matchround_id', $matchroundId)
            ->whereIn('playerstats_playerteam_id', $playerteamIds)
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->playerstats_playerteam_id => (int) $row->playerstats_score]);
    }
}
