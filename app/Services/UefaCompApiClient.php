<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * Read-only client for UEFA competition API (comp.uefa.com/v2).
 */
class UefaCompApiClient
{
    public function __construct(
        private readonly ?string $baseUrl = null,
    ) {}

    /**
     * Hardcoded Auto-Kader (UEFA) competition presets.
     *
     * @return list<array{
     *     key: string,
     *     label: string,
     *     competition_id: int,
     *     season_year: int,
     *     round_orders: list<int>
     * }>
     */
    public function competitionOptions(): array
    {
        $configured = config('services.uefa.competitions', []);
        if (! is_array($configured)) {
            return [];
        }

        $options = [];
        foreach ($configured as $key => $row) {
            if (! is_array($row)) {
                continue;
            }
            $competitionId = (int) ($row['competition_id'] ?? 0);
            $seasonYear = (int) ($row['season_year'] ?? 0);
            $label = trim((string) ($row['label'] ?? ''));
            $roundOrders = $row['round_orders'] ?? [];
            if ($competitionId <= 0 || $seasonYear <= 0 || $label === '' || ! is_array($roundOrders)) {
                continue;
            }

            $options[] = [
                'key' => (string) $key,
                'label' => $label,
                'competition_id' => $competitionId,
                'season_year' => $seasonYear,
                'round_orders' => array_values(array_map(
                    static fn (mixed $order): int => (int) $order,
                    $roundOrders,
                )),
            ];
        }

        return $options;
    }

    /**
     * @return array{
     *     key: string,
     *     label: string,
     *     competition_id: int,
     *     season_year: int,
     *     round_orders: list<int>
     * }|null
     */
    public function competitionByKey(string $key): ?array
    {
        $key = trim($key);
        if ($key === '') {
            return null;
        }

        foreach ($this->competitionOptions() as $option) {
            if ($option['key'] === $key) {
                return $option;
            }
        }

        return null;
    }

    /**
     * Team IDs enrolled in the selected round orders (union).
     *
     * @param  list<int>  $roundOrders
     * @return list<string>
     */
    public function enrolledTeamIds(int $competitionId, int $seasonYear, array $roundOrders): array
    {
        $wanted = [];
        foreach ($roundOrders as $order) {
            $order = (int) $order;
            if ($order > 0) {
                $wanted[$order] = true;
            }
        }
        if ($wanted === []) {
            return [];
        }

        $ids = [];
        foreach ($this->rounds($competitionId, $seasonYear) as $round) {
            $order = (int) ($round['orderInCompetition'] ?? 0);
            if (! isset($wanted[$order])) {
                continue;
            }
            $teams = $round['teams'] ?? [];
            if (! is_array($teams)) {
                continue;
            }
            foreach ($teams as $teamId) {
                $teamId = trim((string) $teamId);
                if ($teamId !== '') {
                    $ids[$teamId] = true;
                }
            }
        }

        return array_map(static fn (string|int $id): string => (string) $id, array_keys($ids));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function rounds(int $competitionId, int $seasonYear): array
    {
        $response = $this->getJson('/rounds', [
            'competitionId' => $competitionId,
            'seasonYear' => $seasonYear,
        ]);

        return array_values(array_filter(
            $response,
            static fn (mixed $row): bool => is_array($row),
        ));
    }

    /**
     * @param  list<string|int>  $teamIds
     * @return list<array<string, mixed>>
     */
    public function teams(array $teamIds): array
    {
        $ids = [];
        foreach ($teamIds as $teamId) {
            $teamId = trim((string) $teamId);
            if ($teamId !== '') {
                $ids[$teamId] = true;
            }
        }
        if ($ids === []) {
            return [];
        }

        $chunks = array_chunk(array_keys($ids), 40);
        $rows = [];
        foreach ($chunks as $chunk) {
            $response = $this->getJson('/teams', [
                'teamIds' => implode(',', $chunk),
            ]);
            foreach ($response as $row) {
                if (is_array($row)) {
                    $rows[] = $row;
                }
            }
        }

        return $rows;
    }

    /**
     * All players for a competition/season (paginated).
     *
     * @return list<array<string, mixed>>
     */
    public function players(int $competitionId, int $seasonYear): array
    {
        $limit = max(1, (int) config('services.uefa.page_limit', 500));
        $offset = 0;
        $maxPages = max(1, (int) config('services.uefa.max_pages', 40));
        $all = [];

        for ($page = 0; $page < $maxPages; $page++) {
            $batch = $this->getJson('/players', [
                'competitionId' => $competitionId,
                'seasonYear' => $seasonYear,
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $count = 0;
            foreach ($batch as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $all[] = $row;
                $count++;
            }

            if ($count < $limit) {
                break;
            }
            $offset += $limit;
        }

        return $all;
    }

    /**
     * @param  array<string, scalar>  $query
     * @return list<mixed>
     */
    private function getJson(string $path, array $query = []): array
    {
        $url = rtrim($this->baseUrl(), '/').'/'.ltrim($path, '/');
        $timeout = max(1, (int) config('services.uefa.timeout', 20));
        $connectTimeout = max(1, (int) config('services.uefa.connect_timeout', 5));

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
            throw new RuntimeException('UEFA API nicht erreichbar: '.$e->getMessage(), 0, $e);
        }

        if (! $response->successful()) {
            throw new RuntimeException(
                'UEFA API Fehler (HTTP '.$response->status().') für '.$path.'.'
            );
        }

        $json = $response->json();
        if (! is_array($json)) {
            throw new RuntimeException('UEFA API lieferte ungültiges JSON für '.$path.'.');
        }

        return array_is_list($json) ? $json : [];
    }

    private function baseUrl(): string
    {
        $configured = $this->baseUrl ?? (string) config('services.uefa.base_url', 'https://comp.uefa.com/v2');

        return rtrim($configured, '/');
    }

    private function caBundlePath(): ?string
    {
        $configured = config('services.uefa.ca_bundle');
        if (! is_string($configured) || $configured === '') {
            return null;
        }
        if (! is_file($configured) || ! is_readable($configured)) {
            return null;
        }

        return $configured;
    }
}
