<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * Read-only client for FIFA competition API (api.fifa.com/api/v3).
 */
class FifaCompApiClient
{
    public function __construct(
        private readonly ?string $baseUrl = null,
    ) {}

    /**
     * Teams enrolled in a FIFA season (e.g. World Cup 2026 season 285023).
     *
     * @return list<array<string, mixed>>
     */
    public function competitionTeams(int $seasonId, string $language = 'de'): array
    {
        if ($seasonId <= 0) {
            return [];
        }

        $json = $this->requestJson('/competitions/teams/'.$seasonId, [
            'language' => $language,
        ]);

        $results = $json['Results'] ?? null;
        if (! is_array($results)) {
            return [];
        }

        return array_values(array_filter(
            $results,
            static fn (mixed $row): bool => is_array($row),
        ));
    }

    /**
     * Squad for one team in a competition/season.
     *
     * @return list<array<string, mixed>>
     */
    public function teamSquad(int $teamId, int $competitionId, int $seasonId, string $language = 'de'): array
    {
        if ($teamId <= 0 || $competitionId <= 0 || $seasonId <= 0) {
            return [];
        }

        $json = $this->requestJson('/teams/'.$teamId.'/squad', [
            'idCompetition' => $competitionId,
            'idSeason' => $seasonId,
            'language' => $language,
        ]);

        $players = $json['Players'] ?? null;
        if (! is_array($players)) {
            return [];
        }

        return array_values(array_filter(
            $players,
            static fn (mixed $row): bool => is_array($row),
        ));
    }

    /**
     * Calendar fixtures for a competition/season.
     * FIFA pagination is unreliable — pass a high $count for a full list.
     *
     * @return list<array<string, mixed>>
     */
    public function calendarMatches(
        int $competitionId,
        int $seasonId,
        int $count = 500,
        string $language = 'de',
    ): array {
        if ($competitionId <= 0 || $seasonId <= 0) {
            return [];
        }

        $json = $this->requestJson('/calendar/matches', [
            'idCompetition' => $competitionId,
            'idSeason' => $seasonId,
            'count' => max(1, $count),
            'language' => $language,
        ]);

        $results = $json['Results'] ?? null;
        if (! is_array($results)) {
            return [];
        }

        return array_values(array_filter(
            $results,
            static fn (mixed $row): bool => is_array($row),
        ));
    }

    /**
     * Live/finished match detail including lineups, goals, bookings, substitutions.
     *
     * @return array<string, mixed>
     */
    public function liveMatch(
        int $competitionId,
        int $seasonId,
        string $stageId,
        string $matchId,
        string $language = 'de',
    ): array {
        $stageId = trim($stageId);
        $matchId = trim($matchId);
        if ($competitionId <= 0 || $seasonId <= 0 || $stageId === '' || $matchId === '') {
            throw new RuntimeException('FIFA live match requires competition, season, stage und match id.');
        }

        return $this->requestJson(
            '/live/football/'.$competitionId.'/'.$seasonId.'/'.$stageId.'/'.$matchId,
            ['language' => $language],
        );
    }

    /**
     * Match event timeline (goals, cards, substitutions, penalty shootout, …).
     *
     * @return array<string, mixed>
     */
    public function matchTimeline(
        int $competitionId,
        int $seasonId,
        string $stageId,
        string $matchId,
        string $language = 'de',
    ): array {
        $stageId = trim($stageId);
        $matchId = trim($matchId);
        if ($competitionId <= 0 || $seasonId <= 0 || $stageId === '' || $matchId === '') {
            throw new RuntimeException('FIFA timeline requires competition, season, stage und match id.');
        }

        return $this->requestJson(
            '/timelines/'.$competitionId.'/'.$seasonId.'/'.$stageId.'/'.$matchId,
            ['language' => $language],
        );
    }

    /**
     * @param  array<string, scalar>  $query
     * @return array<string, mixed>
     */
    private function requestJson(string $path, array $query = []): array
    {
        $url = rtrim($this->baseUrl(), '/').'/'.ltrim($path, '/');
        $timeout = max(1, (int) config('services.fifa.timeout', 20));
        $connectTimeout = max(1, (int) config('services.fifa.connect_timeout', 5));

        try {
            $request = Http::acceptJson()
                ->connectTimeout($connectTimeout)
                ->timeout($timeout)
                ->retry(2, 200, function (Throwable $exception): bool {
                    return $exception instanceof ConnectionException;
                });

            $caBundle = $this->caBundlePath();
            if ($caBundle !== null) {
                $request = $request->withOptions(['verify' => $caBundle]);
            }

            $response = $request->get($url, $query);
        } catch (Throwable $e) {
            throw new RuntimeException('FIFA API nicht erreichbar: '.$e->getMessage(), 0, $e);
        }

        if (! $response->successful()) {
            throw new RuntimeException(
                'FIFA API Fehler (HTTP '.$response->status().') für '.$path.'.'
            );
        }

        $json = $response->json();
        if (! is_array($json)) {
            throw new RuntimeException('FIFA API lieferte ungültiges JSON für '.$path.'.');
        }

        /** @var array<string, mixed> $json */
        return $json;
    }

    private function baseUrl(): string
    {
        $configured = $this->baseUrl ?? (string) config('services.fifa.base_url', 'https://api.fifa.com/api/v3');

        return rtrim($configured, '/');
    }

    private function caBundlePath(): ?string
    {
        $configured = config('services.fifa.ca_bundle');
        if (! is_string($configured) || $configured === '') {
            return null;
        }
        if (! is_file($configured) || ! is_readable($configured)) {
            return null;
        }

        return $configured;
    }
}
