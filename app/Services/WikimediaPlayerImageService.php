<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerteam;
use App\Support\PlayerPicture;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Resolve football-player portrait images from Wikidata / Wikimedia Commons.
 *
 * Reusable by admin flows (Auto-Kader, players, etc.).
 */
class WikimediaPlayerImageService
{
    public const DEFAULT_THUMBNAIL_WIDTH = 200;

    public function __construct(
        private readonly ?string $sparqlUrl = null,
        private readonly ?string $commonsApiUrl = null,
        private readonly ?string $userAgent = null,
        private readonly ?int $thumbnailWidth = null,
        private readonly ?int $timeoutSeconds = null,
        private readonly ?string $caBundle = null,
    ) {}

    /**
     * Look up Commons files + thumbnail URLs for English player labels.
     *
     * @param  list<string>  $names
     * @return array<string, array{commons_file: string, thumbnail_url: string, wikidata_name: string}>
     *                                                                                                  Keys are the requested names (original spelling).
     */
    public function resolveImagesByPlayerNames(array $names): array
    {
        return $this->diagnoseImagesByPlayerNames($names)['resolved'];
    }

    /**
     * Same as resolveImagesByPlayerNames, but includes raw HTTP responses for debugging.
     *
     * @param  list<string>  $names
     * @return array{
     *     names: list<string>,
     *     user_agent: string,
     *     sparql: array{url: string, query: string, status: int|null, headers: array<string, list<string>>, body: mixed, error: string|null},
     *     commons: list<array{url: string, titles: string, status: int|null, headers: array<string, list<string>>, body: mixed, error: string|null}>,
     *     bindings_count: int,
     *     files: array<string, string>,
     *     thumbnails: array<string, string>,
     *     resolved: array<string, array{commons_file: string, thumbnail_url: string, wikidata_name: string}>
     * }
     */
    public function diagnoseImagesByPlayerNames(array $names): array
    {
        $unique = [];
        foreach ($names as $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            $unique[$this->foldLabel($name)] = $name;
        }

        $diagnosis = [
            'names' => array_values($unique),
            'user_agent' => $this->userAgent(),
            'sparql' => [
                'url' => $this->sparqlUrl(),
                'query' => '',
                'status' => null,
                'headers' => [],
                'body' => null,
                'error' => null,
            ],
            'commons' => [],
            'bindings_count' => 0,
            'files' => [],
            'thumbnails' => [],
            'resolved' => [],
        ];

        if ($unique === []) {
            return $diagnosis;
        }

        $sparql = $this->buildSparql(array_values($unique));
        $diagnosis['sparql']['query'] = $sparql;
        if ($sparql === '') {
            return $diagnosis;
        }

        try {
            $response = $this->http()
                ->accept('application/sparql-results+json')
                ->asForm()
                ->post($this->sparqlUrl(), [
                    'query' => $sparql,
                    'format' => 'json',
                ]);
            $diagnosis['sparql']['status'] = $response->status();
            $diagnosis['sparql']['headers'] = $response->headers();
            $diagnosis['sparql']['body'] = $response->json() ?? $response->body();
            $this->debugLog('Wikidata SPARQL response', $diagnosis['sparql']);
        } catch (ConnectionException $e) {
            $diagnosis['sparql']['error'] = $e->getMessage();
            Log::warning('Wikidata SPARQL connection failed.', ['error' => $e->getMessage()]);

            return $diagnosis;
        } catch (Throwable $e) {
            $diagnosis['sparql']['error'] = $e->getMessage();
            Log::warning('Wikidata SPARQL request failed.', ['error' => $e->getMessage()]);

            return $diagnosis;
        }

        if (! $response->successful()) {
            Log::warning('Wikidata SPARQL returned non-success.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $diagnosis;
        }

        $bindings = $response->json('results.bindings');
        if (! is_array($bindings)) {
            return $diagnosis;
        }

        /** @var list<array<string, array{type?: string, value?: string}>> $bindings */
        $bindings = array_values(array_filter($bindings, static fn ($row): bool => is_array($row)));
        $diagnosis['bindings_count'] = count($bindings);

        /** @var array<string, string> $fileByFoldedName */
        $fileByFoldedName = [];
        /** @var array<string, string> $wikidataNameByFolded */
        $wikidataNameByFolded = [];

        foreach ($bindings as $binding) {
            $label = trim((string) ($binding['name']['value'] ?? ''));
            $imageUrl = trim((string) ($binding['image']['value'] ?? ''));
            if ($label === '' || $imageUrl === '') {
                continue;
            }

            $file = $this->commonsFilenameFromUrl($imageUrl);
            if ($file === null) {
                continue;
            }

            $folded = $this->foldLabel($label);
            // Multiple Wikidata images for one player: keep the first binding only.
            if ($folded === '' || isset($fileByFoldedName[$folded])) {
                continue;
            }

            $fileByFoldedName[$folded] = $file;
            $wikidataNameByFolded[$folded] = $label;
        }

        $diagnosis['files'] = $fileByFoldedName;
        if ($fileByFoldedName === []) {
            return $diagnosis;
        }

        $files = array_values(array_unique(array_values($fileByFoldedName)));
        $thumbnails = [];
        foreach (array_chunk($files, 40) as $chunk) {
            $commonsDiag = $this->requestCommonsThumbnails($chunk);
            $diagnosis['commons'][] = $commonsDiag['meta'];
            foreach ($commonsDiag['thumbnails'] as $file => $url) {
                $thumbnails[$file] = $url;
            }
        }
        $diagnosis['thumbnails'] = $thumbnails;

        $resolved = [];
        foreach ($unique as $folded => $requestedName) {
            $file = $fileByFoldedName[$folded] ?? null;
            if ($file === null) {
                continue;
            }
            $thumb = $thumbnails[$file] ?? null;
            if ($thumb === null || $thumb === '') {
                continue;
            }

            $resolved[$requestedName] = [
                'commons_file' => $file,
                'thumbnail_url' => $thumb,
                'wikidata_name' => $wikidataNameByFolded[$folded] ?? $requestedName,
            ];
        }

        $diagnosis['resolved'] = $resolved;
        $this->debugLog('Wikimedia resolve summary', [
            'requested' => array_values($unique),
            'bindings_count' => $diagnosis['bindings_count'],
            'files' => $fileByFoldedName,
            'resolved' => $resolved,
        ]);

        return $diagnosis;
    }

    /**
     * Extract the Commons file name from a Special:FilePath or File: wiki URL.
     */
    public function commonsFilenameFromUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return null;
        }

        $path = rawurldecode($path);
        if (preg_match('#/(?:wiki/)?Special:FilePath/(.+)$#i', $path, $m)) {
            return $this->normalizeCommonsFilename($m[1]);
        }
        if (preg_match('#/(?:wiki/)?File:(.+)$#i', $path, $m)) {
            return $this->normalizeCommonsFilename($m[1]);
        }

        return null;
    }

    /**
     * @param  list<string>  $commonsFiles
     * @return array<string, string> commons_file => thumbnail URL
     */
    public function thumbnailUrlsForFiles(array $commonsFiles, ?int $width = null): array
    {
        $width = $width ?? $this->thumbnailWidth();
        $files = [];
        foreach ($commonsFiles as $file) {
            $normalized = $this->normalizeCommonsFilename((string) $file);
            if ($normalized !== null) {
                $files[$normalized] = true;
            }
        }
        $files = array_keys($files);
        if ($files === []) {
            return [];
        }

        $result = [];

        foreach (array_chunk($files, 40) as $chunk) {
            $commonsDiag = $this->requestCommonsThumbnails($chunk, $width);
            foreach ($commonsDiag['thumbnails'] as $file => $url) {
                $result[$file] = $url;
            }
        }

        return $result;
    }

    /**
     * Download a Commons thumbnail and store it as JPEG at $absolutePath.
     */
    public function downloadThumbnail(string $commonsFile, string $absolutePath, ?int $width = null): bool
    {
        $file = $this->normalizeCommonsFilename($commonsFile);
        if ($file === null) {
            return false;
        }

        $urls = $this->thumbnailUrlsForFiles([$file], $width);
        $thumbUrl = $urls[$file] ?? null;
        if ($thumbUrl === null) {
            return false;
        }

        return $this->downloadThumbnailFromUrl($thumbUrl, $absolutePath, $file);
    }

    /**
     * For newly created players: persist Commons handle and store squad portrait.
     * Prefers an already-resolved commons_file (from analyze); otherwise looks up by name.
     * Thumbnail URLs are resolved in one batched Commons API call (not one request per player).
     *
     * @param  list<array{player_id: int, name?: string, commons_file?: string, thumbnail_url?: string}>  $players
     * @return array{resolved: int, stored: int}
     */
    public function assignImagesToNewSquadPlayers(array $players, int $teamId, int $leagueId): array
    {
        if ($teamId <= 0 || $players === []) {
            return ['resolved' => 0, 'stored' => 0];
        }

        /** @var array<int, string> $commonsByPlayerId */
        $commonsByPlayerId = [];
        /** @var array<string, string> $thumbnailByCommonsFile */
        $thumbnailByCommonsFile = [];
        /** @var array<string, list<int>> $needResolveByName */
        $needResolveByName = [];

        foreach ($players as $row) {
            $playerId = (int) ($row['player_id'] ?? 0);
            if ($playerId <= 0) {
                continue;
            }

            $commonsFile = $this->normalizeCommonsFilename((string) ($row['commons_file'] ?? ''));
            if ($commonsFile !== null) {
                $commonsByPlayerId[$playerId] = $commonsFile;
                $thumbUrl = trim((string) ($row['thumbnail_url'] ?? ''));
                if ($thumbUrl !== '' && ! isset($thumbnailByCommonsFile[$commonsFile])) {
                    $thumbnailByCommonsFile[$commonsFile] = $thumbUrl;
                }

                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $needResolveByName[$name] ??= [];
            $needResolveByName[$name][] = $playerId;
        }

        if ($needResolveByName !== []) {
            $resolved = $this->resolveImagesByPlayerNames(array_keys($needResolveByName));
            foreach ($resolved as $name => $image) {
                foreach ($needResolveByName[$name] ?? [] as $playerId) {
                    if (! isset($commonsByPlayerId[$playerId])) {
                        $commonsByPlayerId[$playerId] = $image['commons_file'];
                    }
                    if (! isset($thumbnailByCommonsFile[$image['commons_file']])) {
                        $thumbnailByCommonsFile[$image['commons_file']] = $image['thumbnail_url'];
                    }
                }
            }
        }

        if ($commonsByPlayerId === []) {
            return ['resolved' => 0, 'stored' => 0];
        }

        $missingThumbFiles = [];
        foreach ($commonsByPlayerId as $commonsFile) {
            if (! isset($thumbnailByCommonsFile[$commonsFile])) {
                $missingThumbFiles[$commonsFile] = true;
            }
        }
        if ($missingThumbFiles !== []) {
            foreach ($this->thumbnailUrlsForFiles(array_keys($missingThumbFiles)) as $file => $url) {
                $thumbnailByCommonsFile[$file] = $url;
            }
        }

        $stored = 0;
        foreach ($commonsByPlayerId as $playerId => $commonsFile) {
            $player = Player::query()->find($playerId);
            if (! $player) {
                continue;
            }

            $player->player_commons_image = $commonsFile;
            $player->save();

            $thumbUrl = $thumbnailByCommonsFile[$commonsFile] ?? null;
            if ($thumbUrl === null) {
                continue;
            }

            $path = PlayerPicture::storagePath($teamId, $playerId);
            if (! $this->downloadThumbnailFromUrl($thumbUrl, $path, $commonsFile)) {
                continue;
            }

            $pictureName = $teamId.'-'.$playerId.'.jpg';
            $query = Playerteam::query()
                ->where('playerteam_player_id', $playerId)
                ->where('playerteam_team_id', $teamId);
            if ($leagueId > 0) {
                $query->where('playerteam_league_id', $leagueId);
            }
            $query->update(['playerteam_player_picture' => $pictureName]);
            $stored++;
        }

        return [
            'resolved' => count($commonsByPlayerId),
            'stored' => $stored,
        ];
    }

    /**
     * Download image bytes from a known thumbnail URL and store as JPEG.
     */
    private function downloadThumbnailFromUrl(string $thumbUrl, string $absolutePath, ?string $commonsFile = null): bool
    {
        try {
            $response = $this->http()->get($thumbUrl);
        } catch (Throwable $e) {
            Log::warning('Wikimedia thumbnail download failed.', [
                'file' => $commonsFile,
                'url' => $thumbUrl,
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        if (! $response->successful()) {
            return false;
        }

        $bytes = $response->body();
        if ($bytes === '') {
            return false;
        }

        $dir = dirname($absolutePath);
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return false;
        }

        return $this->storeJpegBytes($bytes, $absolutePath);
    }

    /**
     * Build a Wikidata-friendly display name from first/last name.
     */
    public function displayName(string $fname, string $lname): string
    {
        $fname = trim($fname);
        $lname = trim($lname);
        if ($fname === '' && $lname === '') {
            return '';
        }
        if ($fname === '' || $fname === $lname) {
            return $lname !== '' ? $lname : $fname;
        }
        if ($lname === '') {
            return $fname;
        }

        return $fname.' '.$lname;
    }

    /**
     * @param  list<string>  $names
     */
    private function buildSparql(array $names): string
    {
        $values = [];
        foreach ($names as $name) {
            $escaped = $this->escapeSparqlString($name);
            if ($escaped === '') {
                continue;
            }
            $values[] = '    "'.$escaped.'"@en';
        }

        if ($values === []) {
            return '';
        }

        return "SELECT ?name ?image WHERE {\n"
            ."  VALUES ?name {\n"
            .implode("\n", $values)."\n"
            ."  }\n"
            ."  ?player wdt:P106 wd:Q937857 ;\n"
            ."          rdfs:label ?name ;\n"
            ."          wdt:P18 ?image .\n"
            .'}';
    }

    /**
     * @param  list<string>  $commonsFiles  Normalized Commons file names (no File: prefix)
     * @return array{
     *     meta: array{url: string, titles: string, status: int|null, headers: array<string, list<string>>, body: mixed, error: string|null},
     *     thumbnails: array<string, string>
     * }
     */
    private function requestCommonsThumbnails(array $commonsFiles, ?int $width = null): array
    {
        $width = $width ?? $this->thumbnailWidth();
        $titles = implode('|', array_map(static fn (string $file): string => 'File:'.$file, $commonsFiles));
        $meta = [
            'url' => $this->commonsApiUrl(),
            'titles' => $titles,
            'status' => null,
            'headers' => [],
            'body' => null,
            'error' => null,
        ];
        $thumbnails = [];

        try {
            $response = $this->http()
                ->get($this->commonsApiUrl(), [
                    'action' => 'query',
                    'format' => 'json',
                    'formatversion' => 2,
                    'prop' => 'imageinfo',
                    'iiprop' => 'url',
                    'iiurlwidth' => $width,
                    'titles' => $titles,
                ]);
            $meta['status'] = $response->status();
            $meta['headers'] = $response->headers();
            $meta['body'] = $response->json() ?? $response->body();
            $this->debugLog('Wikimedia Commons API response', $meta);
        } catch (Throwable $e) {
            $meta['error'] = $e->getMessage();
            Log::warning('Wikimedia Commons API request failed.', [
                'error' => $e->getMessage(),
            ]);

            return ['meta' => $meta, 'thumbnails' => []];
        }

        if (! $response->successful()) {
            Log::warning('Wikimedia Commons API returned non-success.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['meta' => $meta, 'thumbnails' => []];
        }

        $pages = $response->json('query.pages');
        if (! is_array($pages)) {
            return ['meta' => $meta, 'thumbnails' => []];
        }

        foreach ($pages as $page) {
            if (! is_array($page)) {
                continue;
            }
            $title = (string) ($page['title'] ?? '');
            $file = $this->normalizeCommonsFilename(preg_replace('#^File:#i', '', $title) ?? '');
            if ($file === null) {
                continue;
            }
            $info = $page['imageinfo'][0] ?? null;
            if (! is_array($info)) {
                continue;
            }
            $thumb = trim((string) ($info['thumburl'] ?? $info['url'] ?? ''));
            if ($thumb !== '') {
                $thumbnails[$file] = $thumb;
            }
        }

        return ['meta' => $meta, 'thumbnails' => $thumbnails];
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function debugLog(string $message, array $context): void
    {
        if (! $this->debugEnabled()) {
            return;
        }

        Log::debug($message, $context);
    }

    private function debugEnabled(): bool
    {
        return (bool) config('services.wikimedia.debug', false);
    }

    private function escapeSparqlString(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        return str_replace(
            ['\\', '"', "\r", "\n", "\t"],
            ['\\\\', '\\"', '\\r', '\\n', '\\t'],
            $value,
        );
    }

    private function normalizeCommonsFilename(?string $file): ?string
    {
        $file = trim((string) $file);
        if ($file === '') {
            return null;
        }
        $file = str_replace(' ', '_', $file);
        $file = ltrim($file, '/');
        if (str_starts_with(strtolower($file), 'file:')) {
            $file = substr($file, 5);
        }

        return $file !== '' ? $file : null;
    }

    private function foldLabel(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
        if ($value === '') {
            return '';
        }

        if (class_exists(\Normalizer::class)) {
            $normalized = \Normalizer::normalize($value, \Normalizer::FORM_D);
            if (is_string($normalized) && $normalized !== '') {
                $value = $normalized;
            }
            $value = preg_replace('/\p{Mn}+/u', '', $value) ?? $value;
        }

        return trim($value);
    }

    private function storeJpegBytes(string $bytes, string $absolutePath): bool
    {
        if (! extension_loaded('gd')) {
            return @file_put_contents($absolutePath, $bytes) !== false;
        }

        $image = @imagecreatefromstring($bytes);
        if ($image === false) {
            return @file_put_contents($absolutePath, $bytes) !== false;
        }

        if (function_exists('imagepalettetotruecolor')) {
            @imagepalettetotruecolor($image);
        }
        $ok = @imagejpeg($image, $absolutePath, 90);
        imagedestroy($image);

        return (bool) $ok;
    }

    private function http(): PendingRequest
    {
        $request = Http::withHeaders([
            'User-Agent' => $this->userAgent(),
            'Accept' => 'application/json',
        ])
            ->connectTimeout(5)
            ->timeout($this->timeoutSeconds())
            ->retry([200, 500], 0, function (Throwable $exception): bool {
                return $exception instanceof ConnectionException;
            });

        $caBundle = $this->caBundlePath();
        if ($caBundle !== null) {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        return $request;
    }

    private function caBundlePath(): ?string
    {
        $configured = $this->caBundle
            ?? config('services.wikimedia.ca_bundle');
        if (! is_string($configured) || $configured === '') {
            return null;
        }

        return is_file($configured) ? $configured : null;
    }

    private function sparqlUrl(): string
    {
        return $this->sparqlUrl
            ?? (string) config('services.wikimedia.sparql_url', 'https://query.wikidata.org/sparql');
    }

    private function commonsApiUrl(): string
    {
        return $this->commonsApiUrl
            ?? (string) config('services.wikimedia.commons_api_url', 'https://commons.wikimedia.org/w/api.php');
    }

    private function userAgent(): string
    {
        return $this->userAgent
            ?? (string) config('services.wikimedia.user_agent', 'SoccerSportsfan');
    }

    private function thumbnailWidth(): int
    {
        $width = $this->thumbnailWidth
            ?? (int) config('services.wikimedia.thumbnail_width', self::DEFAULT_THUMBNAIL_WIDTH);

        return $width > 0 ? $width : self::DEFAULT_THUMBNAIL_WIDTH;
    }

    private function timeoutSeconds(): int
    {
        $timeout = $this->timeoutSeconds
            ?? (int) config('services.wikimedia.timeout', 30);

        return $timeout > 0 ? $timeout : 30;
    }
}
