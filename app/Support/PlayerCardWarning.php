<?php

namespace App\Support;

use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Playerteam;
use Illuminate\Support\Collection;

/**
 * Card-based lineup warnings relative to a selected matchround (past league rounds only).
 */
final class PlayerCardWarning
{
    /**
     * @param  Collection<int, Playerteam>  $playerteams
     * @return Collection<int, string|null>
     */
    public static function forPlayerteams(Collection $playerteams, int $matchroundId, int $leagueId): Collection
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
}
