<?php

namespace App\Services;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Userteam;
use App\Support\Flag;
use App\Support\PlayerPicture;
use DateTimeImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminSquadService
{
    private const DEFAULT_TRANSFER = '2008-01-01';

    /** @var list<string> */
    private const POSITIONS = ['g', 'd', 'm', 's'];

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly AdminPlayerService $players,
        private readonly WikimediaPlayerImageService $wikimediaImages,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        int $teamId,
        ?int $squadLeagueId = null,
        string $tab = 'roster',
        ?array $auto = null,
        ?array $images = null,
    ): array {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagueId = $this->resolveSquadLeagueId($squadLeagueId, $shell);
        $teams = $this->teamOptions($leagueId);
        $teamId = $this->resolveTeamId($teamId, $teams);
        $selectedTeam = null;
        foreach ($teams as $team) {
            if ($team['team_id'] === $teamId) {
                $selectedTeam = $team;
                break;
            }
        }

        $items = ($teamId > 0 && $leagueId > 0) ? $this->rosterItems($teamId, $leagueId) : [];
        $resolvedTab = match ($tab) {
            'add' => 'add',
            'auto' => 'auto',
            'images' => 'images',
            default => 'roster',
        };

        $imageState = $images ?? $this->emptyImagesState();
        if ($resolvedTab === 'images' && $teamId > 0 && $leagueId > 0) {
            $imageState['team_id'] = $teamId;
            $imageState['league_id'] = $leagueId;
            // Keep check results when re-rendering after POST; otherwise load fresh roster.
            if (! (bool) ($imageState['checked'] ?? false) || ($imageState['players'] ?? []) === []) {
                $imageState['players'] = $this->squadImagePlayers($teamId, $leagueId);
                $imageState['checked'] = false;
            }
        }

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'squad_league_id' => $leagueId,
            'leagues' => $this->leagueOptions(),
            'countries' => $this->countryOptions(),
            'prices' => range(1, 15),
            'positions' => [
                'g' => 'Tor',
                'd' => 'Abwehr',
                'm' => 'Mittelfeld',
                's' => 'Angriff',
            ],
            'teams' => $teams,
            'selected_team_id' => $teamId,
            'selected_team' => $selectedTeam,
            'items' => $items,
            'roster_active_count' => count(array_filter(
                $items,
                static fn (array $item): bool => (int) $item['playerteam_status'] === 1
            )),
            'per_page' => AdminPlayerService::PER_PAGE,
            'defaults' => [
                'playerteam_status' => 1,
                'playerteam_player_price' => 5,
                'playerteam_player_position' => 'd',
                'playerteam_date_transfer' => self::DEFAULT_TRANSFER,
            ],
            'hint' => 'Position und Preis gelten pro Liga.',
            'tab' => $resolvedTab,
            'auto' => $auto ?? $this->emptyAutoState(),
            'squad_files' => $resolvedTab === 'auto' ? $this->squadJsonOptions() : [],
            'images' => $imageState,
        ];
    }

    /**
     * @return array{
     *     checked: bool,
     *     team_id: int,
     *     league_id: int,
     *     players: list<array<string, mixed>>
     * }
     */
    public function emptyImagesState(): array
    {
        return [
            'checked' => false,
            'team_id' => 0,
            'league_id' => 0,
            'players' => [],
        ];
    }

    /**
     * Resolve Wikimedia portraits for squad players without a local image (no DB/file writes).
     *
     * @param  array<int|string, mixed>  $lookupNames  player_id => lookup name override
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     team_id: int,
     *     league_id: int,
     *     images?: array{checked: bool, team_id: int, league_id: int, players: list<array<string, mixed>>}
     * }
     */
    public function checkWikimediaImagesForSquadPlayers(int $teamId, int $leagueId, array $lookupNames = []): array
    {
        $guard = $this->guardImagesTeamLeague($teamId, $leagueId);
        if ($guard !== null) {
            return $guard;
        }

        $players = $this->squadImagePlayers($teamId, $leagueId);
        $normalizedLookups = [];
        foreach ($lookupNames as $rawId => $rawName) {
            $playerId = (int) $rawId;
            $name = trim((string) $rawName);
            if ($playerId > 0 && $name !== '') {
                $normalizedLookups[$playerId] = $name;
            }
        }

        $namesToResolve = [];
        foreach ($players as $index => $row) {
            if ((bool) ($row['has_picture'] ?? false)) {
                $players[$index]['status'] = 'vorhanden';
                $players[$index]['lookup_name'] = (string) ($row['display_name'] ?? '');
                $players[$index]['commons_file'] = '';

                continue;
            }

            $playerId = (int) ($row['player_id'] ?? 0);
            $lookupName = $normalizedLookups[$playerId]
                ?? (string) ($row['lookup_name'] ?? $row['display_name'] ?? '');
            $players[$index]['lookup_name'] = $lookupName;
            $players[$index]['commons_file'] = '';
            $players[$index]['picture_url'] = '';
            $players[$index]['has_picture'] = false;

            if ($lookupName === '') {
                $players[$index]['status'] = 'nicht_gefunden';

                continue;
            }

            $namesToResolve[] = $lookupName;
        }

        $resolved = $namesToResolve !== []
            ? $this->wikimediaImages->resolveImagesByPlayerNames($namesToResolve)
            : [];

        $foundCount = 0;
        $missingCount = 0;
        foreach ($players as $index => $row) {
            if ((string) ($row['status'] ?? '') === 'vorhanden') {
                continue;
            }

            $lookupName = (string) ($players[$index]['lookup_name'] ?? '');
            $image = $lookupName !== '' ? ($resolved[$lookupName] ?? null) : null;
            if ($image !== null) {
                $players[$index]['status'] = 'gefunden';
                $players[$index]['commons_file'] = $image['commons_file'];
                $players[$index]['picture_url'] = $image['thumbnail_url'];
                $foundCount++;
            } else {
                $players[$index]['status'] = 'nicht_gefunden';
                $players[$index]['commons_file'] = '';
                $players[$index]['picture_url'] = '';
                $missingCount++;
            }
        }

        $parts = [];
        if ($foundCount === 1) {
            $parts[] = '1 Bild gefunden';
        } elseif ($foundCount > 1) {
            $parts[] = $foundCount.' Bilder gefunden';
        } else {
            $parts[] = 'Keine Bilder gefunden';
        }
        if ($missingCount === 1) {
            $parts[] = '1 ohne Treffer';
        } elseif ($missingCount > 1) {
            $parts[] = $missingCount.' ohne Treffer';
        }

        return [
            'ok' => true,
            'team_id' => $teamId,
            'league_id' => $leagueId,
            'message' => implode(' · ', $parts).'.',
            'images' => [
                'checked' => true,
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'players' => $players,
            ],
        ];
    }

    /**
     * Persist Commons portraits for players already resolved as gefunden.
     *
     * @param  list<array<string, mixed>>  $rows
     * @return array{ok: bool, message?: string, errors?: list<string>, team_id: int, league_id: int}
     */
    public function applyWikimediaImagesForSquadPlayers(int $teamId, int $leagueId, array $rows): array
    {
        $guard = $this->guardImagesTeamLeague($teamId, $leagueId);
        if ($guard !== null) {
            return $guard;
        }

        $toAssign = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $playerId = (int) ($row['player_id'] ?? 0);
            $commonsFile = trim((string) ($row['commons_file'] ?? ''));
            if ($playerId <= 0 || $commonsFile === '') {
                continue;
            }
            if (PlayerPicture::exists($teamId, $playerId)) {
                continue;
            }
            $toAssign[] = [
                'player_id' => $playerId,
                'commons_file' => $commonsFile,
                'thumbnail_url' => trim((string) ($row['thumbnail_url'] ?? '')),
            ];
        }

        if ($toAssign === []) {
            return [
                'ok' => true,
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'message' => 'Keine gefundenen Bilder zum Übernehmen.',
            ];
        }

        $result = $this->wikimediaImages->assignImagesToNewSquadPlayers($toAssign, $teamId, $leagueId);
        $stored = (int) ($result['stored'] ?? 0);
        if ($stored === 1) {
            $message = '1 Bild übernommen.';
        } elseif ($stored > 1) {
            $message = $stored.' Bilder übernommen.';
        } else {
            $message = 'Keine Bilder konnten gespeichert werden.';
        }

        return [
            'ok' => true,
            'team_id' => $teamId,
            'league_id' => $leagueId,
            'message' => $message,
        ];
    }

    /**
     * @return array{ok: false, errors: list<string>, team_id: int, league_id: int}|null
     */
    private function guardImagesTeamLeague(int $teamId, int $leagueId): ?array
    {
        if ($teamId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst ein Team wählen.'], 'team_id' => 0, 'league_id' => $leagueId];
        }
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'team_id' => $teamId, 'league_id' => 0];
        }
        if (! Team::query()->whereKey($teamId)->exists()) {
            return ['ok' => false, 'errors' => ['Team nicht gefunden.'], 'team_id' => $teamId, 'league_id' => $leagueId];
        }
        if (! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.'], 'team_id' => $teamId, 'league_id' => $leagueId];
        }

        return null;
    }

    /**
     * All squad players for Auto-Bilder (any playerteam_status), including current picture state.
     *
     * @return list<array<string, mixed>>
     */
    private function squadImagePlayers(int $teamId, int $leagueId): array
    {
        $roster = $this->rosterItems($teamId, $leagueId);

        $playerIds = [];
        foreach ($roster as $item) {
            $playerId = (int) ($item['player_id'] ?? 0);
            if ($playerId > 0) {
                $playerIds[] = $playerId;
            }
        }

        $commonsByPlayerId = [];
        if ($playerIds !== []) {
            $commonsByPlayerId = Player::query()
                ->whereIn('player_id', array_values(array_unique($playerIds)))
                ->pluck('player_commons_image', 'player_id')
                ->all();
        }

        return array_values(array_map(
            function (array $item) use ($commonsByPlayerId): array {
                $playerId = (int) ($item['player_id'] ?? 0);
                $fname = (string) ($item['player_fname'] ?? '');
                $lname = (string) ($item['player_lname'] ?? '');
                $hasPicture = (bool) ($item['has_picture'] ?? false);
                $displayName = $this->wikimediaImages->displayName($fname, $lname);

                return [
                    'playerteam_id' => (int) ($item['playerteam_id'] ?? 0),
                    'player_id' => $playerId,
                    'player_fname' => $fname,
                    'player_lname' => $lname,
                    'display_name' => $displayName,
                    'lookup_name' => $displayName,
                    'player_nationality' => (string) ($item['player_nationality'] ?? ''),
                    'playerteam_player_position' => (string) ($item['playerteam_player_position'] ?? ''),
                    'playerteam_player_price' => (int) ($item['playerteam_player_price'] ?? 0),
                    'player_commons_image' => (string) ($commonsByPlayerId[$playerId] ?? ''),
                    'commons_file' => '',
                    'picture_url' => (string) ($item['picture_url'] ?? ''),
                    'has_picture' => $hasPicture,
                    'status' => $hasPicture ? 'vorhanden' : 'wird_geprueft',
                ];
            },
            $roster,
        ));
    }

    /**
     * @return array{analyzed: bool, source_name: string, team_id: int, league_id: int, fifa_code: string, players: list<array<string, mixed>>, almost: list<array<string, mixed>>}
     */
    public function emptyAutoState(): array
    {
        return [
            'analyzed' => false,
            'source_name' => '',
            'team_id' => 0,
            'league_id' => 0,
            'fifa_code' => '',
            'players' => [],
            'almost' => [],
        ];
    }

    /**
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     team_id?: int,
     *     league_id?: int,
     *     auto?: array{analyzed: bool, source_name: string, team_id: int, league_id: int, fifa_code: string, players: list<array<string, mixed>>}
     * }
     */
    public function analyzeSquadsFile(int $teamId, int $leagueId, ?string $storedFileName): array
    {
        if ($teamId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst ein Team wählen.'], 'team_id' => 0, 'league_id' => $leagueId];
        }
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'team_id' => $teamId, 'league_id' => 0];
        }

        $team = Team::query()->find($teamId);
        if (! $team) {
            return ['ok' => false, 'errors' => ['Team nicht gefunden.'], 'team_id' => $teamId, 'league_id' => $leagueId];
        }
        if (! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.'], 'team_id' => $teamId, 'league_id' => $leagueId];
        }

        $fifaCode = strtoupper(trim((string) ($team->team_nationality ?? '')));
        if ($fifaCode === '') {
            return [
                'ok' => false,
                'errors' => ['Das ausgewählte Team hat keinen FIFA-/Nationalitäts-Code (team_nationality).'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ];
        }

        $parsed = $this->parseSquadsStoredFile($storedFileName);
        if (! ($parsed['ok'] ?? false)) {
            return [
                'ok' => false,
                'errors' => $parsed['errors'] ?? ['Analyse fehlgeschlagen.'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ];
        }

        /** @var list<mixed> $entries */
        $entries = $parsed['data'];
        $sourceName = (string) $parsed['source_name'];
        $squadEntry = $this->findSquadEntryByFifaCode($entries, $fifaCode);
        if ($squadEntry === null) {
            return [
                'ok' => false,
                'errors' => ['In der JSON-Datei wurde kein Kader mit FIFA-Code '.$fifaCode.' gefunden.'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ];
        }

        $rawPlayers = $squadEntry['players'] ?? null;
        if (! is_array($rawPlayers) || $rawPlayers === []) {
            return [
                'ok' => false,
                'errors' => ['Für FIFA-Code '.$fifaCode.' wurden keine Spieler in der JSON-Datei gefunden.'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ];
        }

        $onSquadRows = Playerteam::query()
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_league_id', $leagueId)
            ->get([
                'playerteam_id',
                'playerteam_player_id',
                'playerteam_player_position',
                'playerteam_player_price',
                'playerteam_status',
                'playerteam_date_transfer',
            ])
            ->keyBy(fn (Playerteam $row): int => (int) $row->playerteam_player_id);

        /** @var Collection<int, Player> $candidates */
        $candidates = Player::query()
            ->whereRaw('UPPER(player_nationality) = ?', [$fifaCode])
            ->get([
                'player_id',
                'player_fname',
                'player_lname',
                'player_nationality',
                'player_foreign_id',
            ]);

        $draft = [];
        $almost = [];
        $alreadyOnSquad = 0;
        /** @var array<int, true> $usedPlayerIds */
        $usedPlayerIds = [];

        foreach ($rawPlayers as $rawPlayer) {
            if (! is_array($rawPlayer)) {
                continue;
            }

            $fullName = trim((string) ($rawPlayer['name'] ?? ''));
            if ($fullName === '') {
                continue;
            }

            $jsonSingleName = $this->isSingleTokenName($fullName);
            [$fname, $lname] = $this->splitPlayerName($fullName);
            $position = $this->mapJsonPosition((string) ($rawPlayer['pos'] ?? ''));
            $existing = $this->findExactPlayerAmong($candidates, $fname, $lname, $usedPlayerIds);
            $matchKind = $existing !== null ? 'exact' : null;

            if ($existing === null) {
                $existing = $this->findAlmostPlayerAmong(
                    $candidates,
                    $fname,
                    $lname,
                    $jsonSingleName,
                    $usedPlayerIds,
                );
                if ($existing !== null) {
                    $matchKind = 'almost';
                }
            }

            $playerId = $existing !== null ? (int) $existing->player_id : 0;
            if ($playerId > 0) {
                $usedPlayerIds[$playerId] = true;
            }

            $onSquad = $playerId > 0 && $onSquadRows->has($playerId);
            /** @var Playerteam|null $squadRow */
            $squadRow = $onSquad ? $onSquadRows->get($playerId) : null;

            if ($matchKind === 'almost' && ! $onSquad) {
                $almost[] = [
                    'use_existing' => false,
                    'match_reason' => $this->almostMatchReason(
                        $fname,
                        $lname,
                        $jsonSingleName,
                        (string) $existing->player_fname,
                        (string) $existing->player_lname,
                    ),
                    'json_number' => (int) ($rawPlayer['number'] ?? 0),
                    'json_name' => $fullName,
                    'json_fname' => $fname,
                    'json_lname' => $lname,
                    'json_nationality' => $fifaCode,
                    'json_position' => $position,
                    'db_player_id' => $playerId,
                    'db_fname' => (string) $existing->player_fname,
                    'db_lname' => (string) $existing->player_lname,
                    'db_nationality' => strtoupper(trim((string) ($existing->player_nationality ?? ''))),
                    'db_position' => '',
                    'db_squads' => [],
                    'db_foreign_id' => (string) ($existing->player_foreign_id ?? ''),
                    'playerteam_player_position' => $position,
                    'playerteam_player_price' => 5,
                    'playerteam_status' => 1,
                    'playerteam_date_transfer' => self::DEFAULT_TRANSFER,
                ];

                continue;
            }

            if ($onSquad) {
                $alreadyOnSquad++;
            }

            $transfer = self::DEFAULT_TRANSFER;
            if ($squadRow !== null) {
                $transferTs = strtotime((string) $squadRow->playerteam_date_transfer);
                if ($transferTs) {
                    $transfer = date('Y-m-d', $transferTs);
                }
            }

            $isNew = $existing === null;
            $draft[] = [
                'player_id' => $playerId,
                'playerteam_id' => $squadRow !== null ? (int) $squadRow->playerteam_id : 0,
                'is_new' => $isNew,
                'on_squad' => $onSquad,
                'player_fname' => $isNew ? $fname : (string) $existing->player_fname,
                'player_lname' => $isNew ? $lname : (string) $existing->player_lname,
                'player_nationality' => $isNew
                    ? $fifaCode
                    : strtoupper(trim((string) ($existing->player_nationality ?? ''))),
                'player_status' => 1,
                'player_status_description' => '',
                'player_foreign_id' => $isNew
                    ? ''
                    : (string) ($existing->player_foreign_id ?? ''),
                'playerteam_player_position' => $squadRow !== null
                    ? (string) $squadRow->playerteam_player_position
                    : $position,
                'playerteam_player_price' => $squadRow !== null
                    ? (int) $squadRow->playerteam_player_price
                    : 5,
                'playerteam_status' => $squadRow !== null
                    ? (int) $squadRow->playerteam_status
                    : 1,
                'playerteam_date_transfer' => $transfer,
                'json_number' => (int) ($rawPlayer['number'] ?? 0),
                'json_name' => $fullName,
            ];
        }

        if ($draft === [] && $almost === []) {
            return [
                'ok' => false,
                'errors' => ['In der JSON-Datei wurden keine gültigen Spieler für diesen Kader gefunden.'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ];
        }

        if ($almost !== []) {
            $almost = $this->enrichAlmostMatchesWithSquads($almost, $leagueId);
        }

        $draft = $this->sortAutoDraftByPosition($draft);
        $almost = $this->sortAlmostDraftByPosition($almost);

        $parts = [];
        $readyCount = count($draft);
        if ($readyCount === 1) {
            $parts[] = '1 Spieler bereit zum Übernehmen';
        } elseif ($readyCount > 1) {
            $parts[] = $readyCount.' Spieler bereit zum Übernehmen';
        }
        if ($alreadyOnSquad === 1) {
            $parts[] = 'davon 1 bereits im Kader';
        } elseif ($alreadyOnSquad > 1) {
            $parts[] = 'davon '.$alreadyOnSquad.' bereits im Kader';
        }
        if (count($almost) === 1) {
            $parts[] = '1 Namens-Ähnlichkeit zur Prüfung';
        } elseif (count($almost) > 1) {
            $parts[] = count($almost).' Namens-Ähnlichkeiten zur Prüfung';
        }

        return [
            'ok' => true,
            'team_id' => $teamId,
            'league_id' => $leagueId,
            'auto' => [
                'analyzed' => true,
                'source_name' => $sourceName,
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'fifa_code' => $fifaCode,
                'players' => $draft,
                'almost' => $almost,
            ],
            'message' => implode(', ', $parts).'.',
        ];
    }

    /**
     * Create missing players and add all draft rows to the squad via batchAdd.
     * Almost-matches are resolved via use_existing (reuse DB player) or create from JSON.
     *
     * @param  list<array<string, mixed>>|array<int, array<string, mixed>>  $players
     * @param  list<array<string, mixed>>|array<int, array<string, mixed>>  $almost
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     team_id?: int,
     *     league_id?: int,
     *     auto?: array{analyzed: bool, source_name: string, team_id: int, league_id: int, fifa_code: string, players: list<array<string, mixed>>, almost: list<array<string, mixed>>}
     * }
     */
    public function createSquadFromDraft(
        array $players,
        int $teamId,
        int $leagueId,
        string $sourceName = '',
        string $fifaCode = '',
        array $almost = [],
    ): array {
        if ($teamId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst ein Team wählen.'], 'team_id' => 0, 'league_id' => $leagueId];
        }
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'team_id' => $teamId, 'league_id' => 0];
        }

        $mainDrafts = [];
        foreach (array_values($players) as $row) {
            if (! is_array($row)) {
                continue;
            }
            $mainDrafts[] = $this->normalizeAutoDraftRow($row);
        }

        $almostDrafts = [];
        foreach (array_values($almost) as $row) {
            if (! is_array($row)) {
                continue;
            }
            $almostDrafts[] = $this->normalizeAlmostDraftRow($row);
        }

        $drafts = $mainDrafts;
        foreach ($almostDrafts as $almostRow) {
            $drafts[] = $this->resolveAlmostRowToDraft($almostRow, $teamId, $leagueId);
        }

        $autoState = static function (array $playersState, array $almostState) use ($sourceName, $teamId, $leagueId, $fifaCode): array {
            return [
                'analyzed' => true,
                'source_name' => $sourceName,
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'fifa_code' => $fifaCode,
                'players' => $playersState,
                'almost' => $almostState,
            ];
        };

        if ($drafts === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spieler zum Übernehmen.'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'auto' => $autoState([], $almostDrafts),
            ];
        }

        $errors = [];
        $newNameKeys = [];
        foreach ($drafts as $index => $row) {
            $label = $this->autoDraftLabel($row, $index);
            $roster = $this->normalizeRosterInput($row);
            $rowErrors = $this->validateRosterFields($roster, null);

            if ($row['on_squad']) {
                if ($row['player_id'] <= 0) {
                    $rowErrors[] = 'Spieler-ID fehlt.';
                }
            } elseif ($row['is_new']) {
                $playerErrors = $this->players->validateCreateInput([
                    'player_fname' => $row['player_fname'],
                    'player_lname' => $row['player_lname'],
                    'player_nationality' => $row['player_nationality'],
                    'player_status' => 1,
                    'player_status_description' => '',
                    'player_foreign_id' => $row['player_foreign_id'],
                ]);
                foreach ($playerErrors as $playerError) {
                    $rowErrors[] = $playerError;
                }

                $nameKey = mb_strtolower($row['player_fname'].'|'.$row['player_lname'].'|'.$row['player_nationality']);
                if (isset($newNameKeys[$nameKey])) {
                    $rowErrors[] = 'Doppelter neuer Spieler in dieser Liste.';
                } else {
                    $newNameKeys[$nameKey] = true;
                }
            } elseif ($row['player_id'] <= 0) {
                $rowErrors[] = 'Spieler-ID fehlt.';
            } elseif (! Player::query()->whereKey($row['player_id'])->exists()) {
                $rowErrors[] = 'Spieler nicht gefunden.';
            }

            if ($rowErrors !== []) {
                $errors[] = $label.': '.implode(' ', $rowErrors);
            }
        }

        if ($errors !== []) {
            return [
                'ok' => false,
                'errors' => $errors,
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'auto' => $autoState($mainDrafts, $almostDrafts),
            ];
        }

        $createdPlayers = 0;
        $updatedOnSquad = 0;
        $items = [];
        $playerIds = [];

        foreach ($drafts as $index => $row) {
            $roster = $this->normalizeRosterInput($row);

            if ($row['on_squad']) {
                $squadItem = null;
                if ($row['playerteam_id'] > 0) {
                    $squadItem = Playerteam::query()
                        ->whereKey($row['playerteam_id'])
                        ->where('playerteam_team_id', $teamId)
                        ->where('playerteam_league_id', $leagueId)
                        ->first();
                }
                if ($squadItem === null && $row['player_id'] > 0) {
                    $squadItem = Playerteam::query()
                        ->where('playerteam_player_id', $row['player_id'])
                        ->where('playerteam_team_id', $teamId)
                        ->where('playerteam_league_id', $leagueId)
                        ->first();
                }
                if ($squadItem === null) {
                    $label = $this->autoDraftLabel($row, $index);

                    return [
                        'ok' => false,
                        'errors' => [$label.': Kader-Eintrag nicht gefunden.'],
                        'team_id' => $teamId,
                        'league_id' => $leagueId,
                        'auto' => $autoState($mainDrafts, $almostDrafts),
                    ];
                }

                if (! $this->applyRosterFields($squadItem, $roster, null)) {
                    $label = $this->autoDraftLabel($row, $index);

                    return [
                        'ok' => false,
                        'errors' => [$label.': Kader-Aktualisierung fehlgeschlagen.'],
                        'team_id' => $teamId,
                        'league_id' => $leagueId,
                        'auto' => $autoState($mainDrafts, $almostDrafts),
                    ];
                }
                $updatedOnSquad++;

                continue;
            }

            $playerId = $row['player_id'];
            if ($row['is_new'] || $playerId <= 0) {
                $createResult = $this->players->create([
                    'player_fname' => $row['player_fname'],
                    'player_lname' => $row['player_lname'],
                    'player_nationality' => $row['player_nationality'],
                    'player_status' => 1,
                    'player_status_description' => '',
                    'player_foreign_id' => $row['player_foreign_id'],
                ]);
                if (! ($createResult['ok'] ?? false)) {
                    $label = $this->autoDraftLabel($row, $index);
                    $createErrors = $createResult['errors'] ?? ['Spieler anlegen fehlgeschlagen.'];

                    return [
                        'ok' => false,
                        'errors' => [$label.': '.implode(' ', $createErrors)],
                        'team_id' => $teamId,
                        'league_id' => $leagueId,
                        'auto' => $autoState($mainDrafts, $almostDrafts),
                    ];
                }
                $playerId = (int) ($createResult['player_id'] ?? 0);
                $createdPlayers++;
                $drafts[$index]['player_id'] = $playerId;
                $drafts[$index]['is_new'] = false;
            }

            $playerIds[] = $playerId;
            $items[$playerId] = [
                'playerteam_status' => $row['playerteam_status'],
                'playerteam_player_price' => $row['playerteam_player_price'],
                'playerteam_player_position' => $row['playerteam_player_position'],
                'playerteam_date_transfer' => $row['playerteam_date_transfer'],
            ];
        }

        if ($playerIds === []) {
            $parts = [];
            if ($updatedOnSquad === 1) {
                $parts[] = '1 Kader-Eintrag aktualisiert';
            } elseif ($updatedOnSquad > 1) {
                $parts[] = $updatedOnSquad.' Kader-Einträge aktualisiert';
            } else {
                $parts[] = 'Keine Spieler zum Übernehmen';
            }

            return [
                'ok' => true,
                'message' => implode(', ', $parts).'.',
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ];
        }

        $addResult = $this->batchAdd([
            'team_id' => $teamId,
            'squad_league_id' => $leagueId,
            'items' => $items,
            'player_ids' => $playerIds,
        ]);

        if (! ($addResult['ok'] ?? false)) {
            return [
                'ok' => false,
                'errors' => $addResult['errors'] ?? ['Kader-Übernahme fehlgeschlagen.'],
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'auto' => $autoState($mainDrafts, $almostDrafts),
            ];
        }

        $parts = [];
        if ($createdPlayers === 1) {
            $parts[] = '1 Spieler neu angelegt';
        } elseif ($createdPlayers > 1) {
            $parts[] = $createdPlayers.' Spieler neu angelegt';
        }
        $parts[] = $addResult['message'] ?? (count($playerIds).' Spieler zum Kader hinzugefügt.');
        if ($updatedOnSquad === 1) {
            $parts[] = '1 Kader-Eintrag aktualisiert.';
        } elseif ($updatedOnSquad > 1) {
            $parts[] = $updatedOnSquad.' Kader-Einträge aktualisiert.';
        }

        return [
            'ok' => true,
            'message' => implode(' ', $parts),
            'team_id' => $teamId,
            'league_id' => $leagueId,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, team_id?: int}
     */
    public function update(int $playerteamId, array $input, ?UploadedFile $pictureFile = null): array
    {
        $item = Playerteam::query()->with('player')->find($playerteamId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Kader-Eintrag nicht gefunden.'], 'team_id' => 0];
        }

        $teamId = (int) $item->playerteam_team_id;
        $form = $this->normalizeRosterInput($input);
        $errors = $this->validateRosterFields($form, $pictureFile);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'team_id' => $teamId];
        }

        $this->applyRosterFields($item, $form, $pictureFile);

        return [
            'ok' => true,
            'message' => 'Kader-Eintrag gespeichert.',
            'team_id' => $teamId,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<int, UploadedFile|null>  $pictureFiles  keyed by playerteam_id
     * @return array{ok: bool, message?: string, errors?: list<string>, team_id?: int}
     */
    public function batchUpdate(array $input, array $pictureFiles = []): array
    {
        $teamId = (int) ($input['team_id'] ?? 0);
        $itemsInput = is_array($input['items'] ?? null) ? $input['items'] : [];
        $deleteIds = $input['delete_ids'] ?? [];
        if (! is_array($deleteIds)) {
            $deleteIds = [];
        }
        $deleteIds = array_values(array_unique(array_filter(
            array_map('intval', $deleteIds),
            static fn (int $id): bool => $id > 0
        )));
        $deleteLookup = array_fill_keys($deleteIds, true);

        if ($itemsInput === [] && $deleteIds === []) {
            return ['ok' => false, 'errors' => ['Keine Änderungen zum Speichern.'], 'team_id' => $teamId];
        }

        /** @var list<array{item: Playerteam, form: array<string, mixed>, picture: UploadedFile|null}> $prepared */
        $prepared = [];
        /** @var list<Playerteam> $toDelete */
        $toDelete = [];
        $errors = [];

        foreach ($deleteIds as $playerteamId) {
            $item = Playerteam::query()->with('player')->find($playerteamId);
            if (! $item) {
                $errors[] = 'Kader-Eintrag #'.$playerteamId.' nicht gefunden.';

                continue;
            }

            $itemTeamId = (int) $item->playerteam_team_id;
            if ($teamId > 0 && $itemTeamId !== $teamId) {
                $errors[] = 'Kader-Eintrag #'.$playerteamId.' gehört nicht zum gewählten Team.';

                continue;
            }
            if ($teamId <= 0) {
                $teamId = $itemTeamId;
            }

            $deleteError = $this->deletionBlocker($item);
            if ($deleteError !== null) {
                $label = trim((string) ($item->player?->player_lname ?? '')).' #'.$playerteamId;
                $errors[] = $label.': '.$deleteError;

                continue;
            }

            $toDelete[] = $item;
        }

        foreach ($itemsInput as $rawId => $rowInput) {
            if (! is_array($rowInput)) {
                continue;
            }

            $playerteamId = (int) $rawId;
            if ($playerteamId <= 0 || isset($deleteLookup[$playerteamId])) {
                continue;
            }

            $item = Playerteam::query()->with('player')->find($playerteamId);
            if (! $item) {
                $errors[] = 'Kader-Eintrag #'.$playerteamId.' nicht gefunden.';

                continue;
            }

            $itemTeamId = (int) $item->playerteam_team_id;
            if ($teamId > 0 && $itemTeamId !== $teamId) {
                $errors[] = 'Kader-Eintrag #'.$playerteamId.' gehört nicht zum gewählten Team.';

                continue;
            }
            if ($teamId <= 0) {
                $teamId = $itemTeamId;
            }

            $pictureFile = $pictureFiles[$playerteamId] ?? null;
            $form = $this->normalizeRosterInput($rowInput);
            $rowErrors = $this->validateRosterFields($form, $pictureFile);
            if ($rowErrors !== []) {
                $label = trim((string) ($item->player?->player_lname ?? '')).' #'.$playerteamId;
                foreach ($rowErrors as $rowError) {
                    $errors[] = $label.': '.$rowError;
                }

                continue;
            }

            $prepared[] = [
                'item' => $item,
                'form' => $form,
                'picture' => $pictureFile,
            ];
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'team_id' => $teamId];
        }

        if ($prepared === [] && $toDelete === []) {
            return ['ok' => false, 'errors' => ['Keine Änderungen zum Speichern.'], 'team_id' => $teamId];
        }

        try {
            DB::transaction(function () use ($prepared, $toDelete) {
                foreach ($prepared as $row) {
                    if (! $this->applyRosterFields($row['item'], $row['form'], $row['picture'])) {
                        throw new \RuntimeException('picture');
                    }
                }

                foreach ($toDelete as $item) {
                    $this->performDelete($item);
                }
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'picture') {
                return [
                    'ok' => false,
                    'errors' => ['Mindestens ein Spielerbild konnte nicht gespeichert werden.'],
                    'team_id' => $teamId,
                ];
            }

            return [
                'ok' => false,
                'errors' => [$e->getMessage()],
                'team_id' => $teamId,
            ];
        } catch (\Throwable) {
            return [
                'ok' => false,
                'errors' => ['Speichern fehlgeschlagen.'],
                'team_id' => $teamId,
            ];
        }

        $updated = count($prepared);
        $deleted = count($toDelete);
        $parts = [];
        if ($updated === 1) {
            $parts[] = '1 Eintrag gespeichert';
        } elseif ($updated > 1) {
            $parts[] = $updated.' Einträge gespeichert';
        }
        if ($deleted === 1) {
            $parts[] = '1 Spieler entfernt';
        } elseif ($deleted > 1) {
            $parts[] = $deleted.' Spieler entfernt';
        }

        return [
            'ok' => true,
            'message' => implode(', ', $parts).'.',
            'team_id' => $teamId,
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function applyRosterFields(Playerteam $item, array $form, ?UploadedFile $pictureFile): bool
    {
        $teamId = (int) $item->playerteam_team_id;

        $item->playerteam_status = (int) $form['playerteam_status'];
        $item->playerteam_player_price = (int) $form['playerteam_player_price'];
        $item->playerteam_player_position = $form['playerteam_player_position'];
        $item->playerteam_date_transfer = $form['playerteam_date_transfer'].' 00:00:00';

        if ($pictureFile !== null) {
            if (! $this->storePicture($pictureFile, $teamId, (int) $item->playerteam_player_id)) {
                return false;
            }
            $item->playerteam_player_picture = $teamId.'-'.(int) $item->playerteam_player_id.'.jpg';
        }

        $item->save();

        return true;
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, team_id?: int}
     */
    public function delete(int $playerteamId): array
    {
        $item = Playerteam::query()->with('player')->find($playerteamId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Kader-Eintrag nicht gefunden!'], 'team_id' => 0];
        }

        $teamId = (int) $item->playerteam_team_id;
        $blocker = $this->deletionBlocker($item);
        if ($blocker !== null) {
            return ['ok' => false, 'errors' => [$blocker], 'team_id' => $teamId];
        }

        $this->performDelete($item);

        return [
            'ok' => true,
            'message' => 'Spieler aus dem Kader entfernt.',
            'team_id' => $teamId,
        ];
    }

    private function deletionBlocker(Playerteam $item): ?string
    {
        if ($this->isUsedInUserteams((int) $item->playerteam_id)) {
            return 'Löschen nicht möglich: Spieler ist in Userteams eingesetzt.';
        }

        if (Playerstats::query()->where('playerstats_playerteam_id', $item->playerteam_id)->exists()) {
            return 'Löschen nicht möglich: Es gibt zugehörige Spielstatistiken.';
        }

        return null;
    }

    private function performDelete(Playerteam $item): void
    {
        $teamId = (int) $item->playerteam_team_id;
        $playerteamId = (int) $item->playerteam_id;
        $pictureName = (string) ($item->playerteam_player_picture ?? '');
        $playerId = (int) $item->playerteam_player_id;
        $item->delete();
        if ($pictureName !== '') {
            // Only delete file if no other league row for same team+player remains.
            $stillUsed = Playerteam::query()
                ->where('playerteam_team_id', $teamId)
                ->where('playerteam_player_id', $playerId)
                ->exists();
            if (! $stillUsed) {
                $this->deletePictureFile($teamId, $playerId);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, team_id?: int}
     */
    public function batchAdd(array $input): array
    {
        $teamId = (int) ($input['team_id'] ?? 0);
        $leagueId = (int) ($input['squad_league_id'] ?? $input['playerteam_league_id'] ?? 0);
        if ($teamId <= 0 || ! Team::query()->whereKey($teamId)->exists()) {
            return ['ok' => false, 'errors' => ['Bitte ein Team wählen.'], 'team_id' => $teamId];
        }
        if ($leagueId <= 0 || ! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Bitte eine Liga wählen.'], 'team_id' => $teamId];
        }

        $itemsInput = is_array($input['items'] ?? null) ? $input['items'] : [];
        $playerIds = [];
        if ($itemsInput !== []) {
            foreach (array_keys($itemsInput) as $rawId) {
                $id = (int) $rawId;
                if ($id > 0) {
                    $playerIds[] = $id;
                }
            }
        } else {
            $rawIds = $input['player_ids'] ?? [];
            if (! is_array($rawIds)) {
                $rawIds = [];
            }
            $playerIds = array_map('intval', $rawIds);
        }
        $playerIds = array_values(array_unique(array_filter($playerIds, static fn (int $id) => $id > 0)));

        if ($playerIds === []) {
            return ['ok' => false, 'errors' => ['Bitte mindestens einen Spieler zur Übernahme auswählen.'], 'team_id' => $teamId];
        }

        $fallback = $this->normalizeRosterInput($input);
        /** @var array<int, array<string, mixed>> $prepared */
        $prepared = [];
        $errors = [];

        foreach ($playerIds as $playerId) {
            $rowInput = is_array($itemsInput[$playerId] ?? null)
                ? $itemsInput[$playerId]
                : (is_array($itemsInput[(string) $playerId] ?? null) ? $itemsInput[(string) $playerId] : $fallback);
            $form = $this->normalizeRosterInput(array_merge($fallback, $rowInput));
            $rowErrors = $this->validateRosterFields($form, null);
            if ($rowErrors !== []) {
                foreach ($rowErrors as $rowError) {
                    $errors[] = 'Spieler #'.$playerId.': '.$rowError;
                }

                continue;
            }
            $prepared[$playerId] = $form;
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'team_id' => $teamId];
        }

        $existing = Playerteam::query()
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_league_id', $leagueId)
            ->whereIn('playerteam_player_id', $playerIds)
            ->pluck('playerteam_player_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($existing !== []) {
            return [
                'ok' => false,
                'errors' => ['Mindestens ein Spieler ist diesem Team in dieser Liga bereits zugeordnet.'],
                'team_id' => $teamId,
            ];
        }

        $found = Player::query()->whereIn('player_id', $playerIds)->pluck('player_id')->map(fn ($id) => (int) $id)->all();
        if (count($found) !== count($playerIds)) {
            return ['ok' => false, 'errors' => ['Mindestens ein Spieler wurde nicht gefunden.'], 'team_id' => $teamId];
        }

        DB::transaction(function () use ($prepared, $teamId, $leagueId) {
            foreach ($prepared as $playerId => $form) {
                Playerteam::query()->create([
                    'playerteam_player_id' => $playerId,
                    'playerteam_team_id' => $teamId,
                    'playerteam_league_id' => $leagueId,
                    'playerteam_player_picture' => '',
                    'playerteam_status' => (int) $form['playerteam_status'],
                    'playerteam_player_price' => (int) $form['playerteam_player_price'],
                    'playerteam_player_position' => $form['playerteam_player_position'],
                    'playerteam_date_transfer' => $form['playerteam_date_transfer'].' 00:00:00',
                ]);
            }
        });

        $count = count($prepared);

        return [
            'ok' => true,
            'message' => $count === 1
                ? '1 Spieler zum Kader hinzugefügt.'
                : $count.' Spieler zum Kader hinzugefügt.',
            'team_id' => $teamId,
        ];
    }

    /**
     * @return list<array{team_id: int, team_label: string, team_nationality: string}>
     */
    private function teamOptions(int $leagueId): array
    {
        $teamIds = [];
        if ($leagueId > 0) {
            $teamIds = DB::table('ffb_match as m')
                ->join('ffb_matchround as mr', 'mr.matchround_id', '=', 'm.match_round')
                ->where('mr.matchround_league_id', $leagueId)
                ->select('m.match_hometeam_id', 'm.match_guestteam_id')
                ->get()
                ->flatMap(fn ($row) => [(int) $row->match_hometeam_id, (int) $row->match_guestteam_id])
                ->filter(fn (int $id) => $id > 0)
                ->unique()
                ->values()
                ->all();

            $squadTeamIds = Playerteam::query()
                ->where('playerteam_league_id', $leagueId)
                ->distinct()
                ->pluck('playerteam_team_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $teamIds = array_values(array_unique(array_merge($teamIds, $squadTeamIds)));
        }

        $query = Team::query()->orderBy('team_name');
        if ($teamIds !== []) {
            $query->whereIn('team_id', $teamIds);
        }

        return $query
            ->get(['team_id', 'team_name', 'team_nationality', 'team_status'])
            ->map(function (Team $team) {
                $name = (string) $team->team_name;
                $nat = trim((string) ($team->team_nationality ?? ''));
                $label = $nat !== '' ? $name.' ('.$nat.')' : $name;
                if (! (int) $team->team_status) {
                    $label .= ' [inaktiv]';
                }

                return [
                    'team_id' => (int) $team->team_id,
                    'team_label' => $label,
                    'team_nationality' => strtoupper($nat),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array{league_id: int, league_title: string}>
     */
    private function leagueOptions(): array
    {
        return League::query()
            ->orderByDesc('league_id')
            ->get(['league_id', 'league_title'])
            ->map(fn (League $league): array => [
                'league_id' => (int) $league->league_id,
                'league_title' => (string) $league->league_title,
            ])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $shell
     */
    private function resolveSquadLeagueId(?int $squadLeagueId, array $shell): int
    {
        $userId = (int) ($shell['user']['user_id'] ?? 0);
        if ($userId > 0) {
            $fromAdmin = $this->adminCenter->selectedLeagueId($userId);
            if ($fromAdmin > 0) {
                return $fromAdmin;
            }
        }

        return (int) ($shell['selected_league']['league_id'] ?? 0);
    }

    /**
     * @param  list<array{team_id: int, team_label: string}>  $teams
     */
    private function resolveTeamId(int $teamId, array $teams): int
    {
        if ($teamId <= 0) {
            return 0;
        }
        foreach ($teams as $team) {
            if ($team['team_id'] === $teamId) {
                return $teamId;
            }
        }

        return Team::query()->whereKey($teamId)->exists() ? $teamId : 0;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function rosterItems(int $teamId, int $leagueId): array
    {
        return Playerteam::query()
            ->with('player')
            ->where('playerteam_team_id', $teamId)
            ->where('playerteam_league_id', $leagueId)
            ->orderBy('playerteam_player_position')
            ->orderByDesc('playerteam_player_price')
            ->get()
            ->sort(function (Playerteam $a, Playerteam $b) {
                $pos = strcmp((string) $a->playerteam_player_position, (string) $b->playerteam_player_position);
                if ($pos !== 0) {
                    return $pos;
                }
                $price = ((float) $b->playerteam_player_price) <=> ((float) $a->playerteam_player_price);
                if ($price !== 0) {
                    return $price;
                }
                $ln = strcasecmp((string) ($a->player?->player_lname ?? ''), (string) ($b->player?->player_lname ?? ''));
                if ($ln !== 0) {
                    return $ln;
                }

                return strcasecmp((string) ($a->player?->player_fname ?? ''), (string) ($b->player?->player_fname ?? ''));
            })
            ->values()
            ->map(function (Playerteam $item) use ($teamId) {
                $player = $item->player;
                $nat = strtoupper(trim((string) ($player?->player_nationality ?? '')));
                $transfer = strtotime((string) $item->playerteam_date_transfer);
                $playerId = (int) $item->playerteam_player_id;
                $pictureUrl = PlayerPicture::url($teamId, $playerId);
                $hasPicture = ! str_ends_with($pictureUrl, 'image_na.gif');

                return [
                    'playerteam_id' => (int) $item->playerteam_id,
                    'player_id' => $playerId,
                    'player_fname' => (string) ($player?->player_fname ?? ''),
                    'player_lname' => (string) ($player?->player_lname ?? ''),
                    'player_nationality' => $nat,
                    'player_flag_url' => $nat !== '' ? Flag::imageUrl($nat) : null,
                    'player_flag_html' => $nat !== '' ? Flag::html($nat) : '',
                    'playerteam_status' => (int) $item->playerteam_status ? 1 : 0,
                    'playerteam_player_price' => (int) $item->playerteam_player_price,
                    'playerteam_player_position' => (string) $item->playerteam_player_position,
                    'playerteam_date_transfer' => $transfer ? date('Y-m-d', $transfer) : self::DEFAULT_TRANSFER,
                    'playerteam_league_id' => (int) $item->playerteam_league_id,
                    'picture_url' => $pictureUrl,
                    'has_picture' => $hasPicture,
                ];
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeRosterInput(array $input): array
    {
        $date = trim((string) ($input['playerteam_date_transfer'] ?? self::DEFAULT_TRANSFER));
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $date, $m)) {
            $date = $m[1];
        }
        if ($date === '') {
            $date = self::DEFAULT_TRANSFER;
        }

        $price = (int) ($input['playerteam_player_price'] ?? 5);
        if ($price < 1) {
            $price = 1;
        }
        if ($price > 15) {
            $price = 15;
        }

        $position = strtolower(trim((string) ($input['playerteam_player_position'] ?? 'd')));
        if (! in_array($position, self::POSITIONS, true)) {
            $position = 'd';
        }

        return [
            'playerteam_status' => ((string) ($input['playerteam_status'] ?? '1') === '0') ? 0 : 1,
            'playerteam_player_price' => $price,
            'playerteam_player_position' => $position,
            'playerteam_date_transfer' => $date,
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validateRosterFields(array $form, ?UploadedFile $pictureFile): array
    {
        $errors = [];

        $dt = DateTimeImmutable::createFromFormat('Y-m-d', $form['playerteam_date_transfer']);
        if (! $dt || $dt->format('Y-m-d') !== $form['playerteam_date_transfer']) {
            $errors[] = 'Das Transferdatum ist ungültig.';
        }

        if (! in_array($form['playerteam_player_position'], self::POSITIONS, true)) {
            $errors[] = 'Ungültige Position.';
        }

        if ($pictureFile !== null) {
            $mime = (string) $pictureFile->getMimeType();
            if (! in_array($mime, ['image/png', 'image/jpeg', 'image/gif', 'image/webp'], true)) {
                $errors[] = 'Spielerbild muss ein Bild sein (PNG, JPEG, GIF oder WebP).';
            }
            if ($pictureFile->getSize() > 2 * 1024 * 1024) {
                $errors[] = 'Spielerbild darf maximal 2 MB groß sein.';
            }
        }

        return $errors;
    }

    private function storePicture(UploadedFile $file, int $teamId, int $playerId): bool
    {
        $dir = $this->playersDir($teamId);
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return false;
        }

        $filename = $teamId.'-'.$playerId.'.jpg';
        $target = $dir.DIRECTORY_SEPARATOR.$filename;
        $mime = (string) $file->getMimeType();

        if ($mime === 'image/jpeg') {
            try {
                $file->move($dir, $filename);
            } catch (\Throwable) {
                return false;
            }

            return is_file($target);
        }

        if (! extension_loaded('gd')) {
            return false;
        }

        $sourcePath = $file->getRealPath();
        if ($sourcePath === false) {
            return false;
        }

        $image = match ($mime) {
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/gif' => @imagecreatefromgif($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if ($image === false) {
            return false;
        }

        if (function_exists('imagepalettetotruecolor')) {
            @imagepalettetotruecolor($image);
        }
        $ok = @imagejpeg($image, $target, 90);
        imagedestroy($image);

        return (bool) $ok;
    }

    private function deletePictureFile(int $teamId, int $playerId): void
    {
        $path = PlayerPicture::storagePath($teamId, $playerId);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function playersDir(int $teamId): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'players'.DIRECTORY_SEPARATOR.$teamId;
    }

    private function isUsedInUserteams(int $playerteamId): bool
    {
        return Userteam::queryContainingAnyPlayerteam([$playerteamId])->exists();
    }

    /**
     * @return array<string, string>
     */
    private function countryOptions(): array
    {
        $countries = config('countries', []);
        if (! is_array($countries)) {
            return [];
        }

        /** @var array<string, string> $countries */
        asort($countries, SORT_STRING | SORT_FLAG_CASE);

        return $countries;
    }

    /**
     * Parse a squad JSON file from public/data/squad (basename only; no path traversal).
     *
     * @return array{ok: true, data: list<mixed>, source_name: string}|array{ok: false, errors: list<string>}
     */
    public function parseSquadsStoredFile(?string $fileName): array
    {
        $path = $this->resolveSquadPath($fileName);
        if ($path === null) {
            return ['ok' => false, 'errors' => ['Bitte eine JSON-Datei aus public/data/squad wählen.']];
        }

        if (filesize($path) > 2 * 1024 * 1024) {
            return ['ok' => false, 'errors' => ['JSON-Datei darf maximal 2 MB groß sein.']];
        }

        $raw = @file_get_contents($path);
        if (! is_string($raw) || $raw === '') {
            return ['ok' => false, 'errors' => ['JSON-Datei konnte nicht gelesen werden.']];
        }

        $data = json_decode($raw, true);
        if (! is_array($data)) {
            return ['ok' => false, 'errors' => ['Ungültige JSON-Datei.']];
        }

        if ($data !== [] && ! array_is_list($data)) {
            return ['ok' => false, 'errors' => ['JSON muss ein Array von Teams mit FIFA-Code und Spielern enthalten.']];
        }

        return [
            'ok' => true,
            'data' => $data,
            'source_name' => basename($path),
        ];
    }

    /**
     * @return list<array{name: string, label: string}>
     */
    public function squadJsonOptions(): array
    {
        $dir = public_path('data/squad');
        if (! is_dir($dir)) {
            return [];
        }

        $names = [];
        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            if (! str_ends_with(strtolower($entry), '.json')) {
                continue;
            }
            if (! is_file($dir.DIRECTORY_SEPARATOR.$entry)) {
                continue;
            }
            $names[] = $entry;
        }

        natcasesort($names);

        return array_values(array_map(
            static fn (string $name): array => ['name' => $name, 'label' => $name],
            $names,
        ));
    }

    private function resolveSquadPath(?string $fileName): ?string
    {
        $fileName = basename(str_replace(["\0", '\\', '/'], '', trim((string) $fileName)));
        if ($fileName === '' || ! str_ends_with(strtolower($fileName), '.json')) {
            return null;
        }

        $dir = realpath(public_path('data/squad'));
        if ($dir === false || ! is_dir($dir)) {
            return null;
        }

        $path = realpath($dir.DIRECTORY_SEPARATOR.$fileName);
        if ($path === false || ! is_file($path)) {
            return null;
        }

        $dirPrefix = strtolower($dir.DIRECTORY_SEPARATOR);
        if (! str_starts_with(strtolower($path), $dirPrefix) && strtolower($path) !== strtolower($dir)) {
            return null;
        }

        return $path;
    }

    /**
     * @param  list<mixed>  $entries
     * @return array<string, mixed>|null
     */
    private function findSquadEntryByFifaCode(array $entries, string $fifaCode): ?array
    {
        $wanted = strtoupper($fifaCode);
        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            $code = strtoupper(trim((string) ($entry['fifa_code'] ?? '')));
            if ($code !== '' && $code === $wanted) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitPlayerName(string $fullName): array
    {
        $parts = preg_split('/\s+/u', trim($fullName)) ?: [];
        $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));
        if ($parts === []) {
            return ['', ''];
        }
        if (count($parts) === 1) {
            return [$parts[0], $parts[0]];
        }

        $lname = array_pop($parts);

        return [implode(' ', $parts), (string) $lname];
    }

    private function mapJsonPosition(string $pos): string
    {
        return match (strtoupper(trim($pos))) {
            'GK' => 'g',
            'DF' => 'd',
            'MF' => 'm',
            'FW', 'ST' => 's',
            default => 'd',
        };
    }

    private function isSingleTokenName(string $fullName): bool
    {
        $parts = preg_split('/\s+/u', trim($fullName)) ?: [];
        $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));

        return count($parts) === 1;
    }

    private function foldName(string $value): string
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
        } else {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if (is_string($converted) && $converted !== '') {
                $value = $converted;
            }
        }

        $value = preg_replace('/[^a-z0-9 ]+/i', '', $value) ?? $value;

        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    /**
     * @param  Collection<int, Player>  $candidates
     * @param  array<int, true>  $usedPlayerIds
     */
    private function findExactPlayerAmong($candidates, string $fname, string $lname, array $usedPlayerIds): ?Player
    {
        if ($fname === '' || $lname === '') {
            return null;
        }

        foreach ($candidates as $candidate) {
            $id = (int) $candidate->player_id;
            if (isset($usedPlayerIds[$id])) {
                continue;
            }
            if ((string) $candidate->player_fname === $fname && (string) $candidate->player_lname === $lname) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @param  Collection<int, Player>  $candidates
     * @param  array<int, true>  $usedPlayerIds
     */
    private function findAlmostPlayerAmong(
        $candidates,
        string $fname,
        string $lname,
        bool $jsonSingleName,
        array $usedPlayerIds,
    ): ?Player {
        if ($fname === '' || $lname === '') {
            return null;
        }

        foreach ($candidates as $candidate) {
            $id = (int) $candidate->player_id;
            if (isset($usedPlayerIds[$id])) {
                continue;
            }
            if ($this->isAlmostNameMatch(
                $fname,
                $lname,
                $jsonSingleName,
                (string) $candidate->player_fname,
                (string) $candidate->player_lname,
            )) {
                return $candidate;
            }
        }

        return null;
    }

    private function isAlmostNameMatch(
        string $jsonFname,
        string $jsonLname,
        bool $jsonSingleName,
        string $dbFname,
        string $dbLname,
    ): bool {
        $jF = $this->foldName($jsonFname);
        $jL = $this->foldName($jsonLname);
        $dF = $this->foldName($dbFname);
        $dL = $this->foldName($dbLname);
        if ($jF === '' || $jL === '' || $dF === '' || $dL === '') {
            return false;
        }

        // Exact string match is handled earlier; folded equality covers accents/case.
        if ($jF === $dF && $jL === $dL) {
            return true;
        }

        // First/last name switched.
        if ($jF === $dL && $jL === $dF) {
            return true;
        }

        $dbSingleName = $dF === $dL;
        if ($jsonSingleName && $dbSingleName && $jF === $dF) {
            return true;
        }

        // JSON single token vs DB legacy single-name (fname == lname).
        if ($dbSingleName && ($dF === $jF || $dF === $jL)) {
            return true;
        }

        // DB single name equals folded full JSON name.
        if ($dbSingleName && $dF === $this->foldName(trim($jsonFname.' '.$jsonLname))) {
            return true;
        }

        // JSON single name equals either DB part when DB also uses duplicated single name only —
        // already covered. If JSON is single and DB has fname==lname different fold — false.

        return false;
    }

    private function almostMatchReason(
        string $jsonFname,
        string $jsonLname,
        bool $jsonSingleName,
        string $dbFname,
        string $dbLname,
    ): string {
        $jF = $this->foldName($jsonFname);
        $jL = $this->foldName($jsonLname);
        $dF = $this->foldName($dbFname);
        $dL = $this->foldName($dbLname);
        $dbSingleName = $dF === $dL;
        $jsonFoldedSingle = $jF === $jL;

        if ($jF === $dF && $jL === $dL) {
            if (($jsonSingleName || $jsonFoldedSingle) && $dbSingleName) {
                return 'Einzelnamen-Variante';
            }

            return 'Schreibweise/Akzente';
        }

        if (($jsonSingleName || $dbSingleName) && ($jF === $dF || $jF === $dL || $dF === $jL)) {
            return 'Einzelnamen-Variante';
        }

        if ($jF === $dL && $jL === $dF) {
            return 'Vor-/Nachname vertauscht';
        }

        return 'Ähnlicher Name';
    }

    /**
     * @param  list<array<string, mixed>>  $almost
     * @return list<array<string, mixed>>
     */
    private function enrichAlmostMatchesWithSquads(array $almost, int $leagueId): array
    {
        $playerIds = [];
        foreach ($almost as $row) {
            $id = (int) ($row['db_player_id'] ?? 0);
            if ($id > 0) {
                $playerIds[] = $id;
            }
        }
        $playerIds = array_values(array_unique($playerIds));
        if ($playerIds === []) {
            return $almost;
        }

        $rows = Playerteam::query()
            ->with(['team:team_id,team_name', 'league:league_id,league_title'])
            ->whereIn('playerteam_player_id', $playerIds)
            ->get();

        /** @var array<int, list<string>> $squadsByPlayer */
        $squadsByPlayer = [];
        /** @var array<int, string> $positionByPlayer */
        $positionByPlayer = [];

        foreach ($rows as $row) {
            $playerId = (int) $row->playerteam_player_id;
            $teamName = trim((string) ($row->team?->team_name ?? ''));
            if ($teamName !== '') {
                $leagueTitle = trim((string) ($row->league?->league_title ?? ''));
                $label = $leagueTitle !== '' ? $teamName.' ('.$leagueTitle.')' : $teamName;
                $squadsByPlayer[$playerId] ??= [];
                if (! in_array($label, $squadsByPlayer[$playerId], true)) {
                    $squadsByPlayer[$playerId][] = $label;
                }
            }

            $pos = strtolower(trim((string) $row->playerteam_player_position));
            if ($pos === '' || ! in_array($pos, self::POSITIONS, true)) {
                continue;
            }
            if (! isset($positionByPlayer[$playerId]) || (int) $row->playerteam_league_id === $leagueId) {
                $positionByPlayer[$playerId] = $pos;
            }
        }

        foreach ($almost as $index => $row) {
            $playerId = (int) ($row['db_player_id'] ?? 0);
            $almost[$index]['db_squads'] = $squadsByPlayer[$playerId] ?? [];
            $almost[$index]['db_position'] = $positionByPlayer[$playerId] ?? '';
        }

        return $almost;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function normalizeAlmostDraftRow(array $row): array
    {
        $roster = $this->normalizeRosterInput($row);
        $useExisting = ((string) ($row['use_existing'] ?? '0') === '1' || ($row['use_existing'] ?? false) === true);

        return [
            'use_existing' => $useExisting,
            'match_reason' => trim((string) ($row['match_reason'] ?? '')),
            'json_number' => (int) ($row['json_number'] ?? 0),
            'json_name' => trim((string) ($row['json_name'] ?? '')),
            'json_fname' => trim((string) ($row['json_fname'] ?? '')),
            'json_lname' => trim((string) ($row['json_lname'] ?? '')),
            'json_nationality' => strtoupper(trim((string) ($row['json_nationality'] ?? ''))),
            'json_position' => $this->normalizeRosterInput([
                'playerteam_player_position' => $row['json_position'] ?? $roster['playerteam_player_position'],
            ])['playerteam_player_position'],
            'db_player_id' => (int) ($row['db_player_id'] ?? 0),
            'db_fname' => trim((string) ($row['db_fname'] ?? '')),
            'db_lname' => trim((string) ($row['db_lname'] ?? '')),
            'db_nationality' => strtoupper(trim((string) ($row['db_nationality'] ?? ''))),
            'db_position' => strtolower(trim((string) ($row['db_position'] ?? ''))),
            'db_squads' => is_array($row['db_squads'] ?? null)
                ? array_values(array_map('strval', $row['db_squads']))
                : [],
            'db_foreign_id' => trim((string) ($row['db_foreign_id'] ?? '')),
            'playerteam_player_position' => $roster['playerteam_player_position'],
            'playerteam_player_price' => $roster['playerteam_player_price'],
            'playerteam_status' => $roster['playerteam_status'],
            'playerteam_date_transfer' => $roster['playerteam_date_transfer'],
        ];
    }

    /**
     * @param  array<string, mixed>  $almost
     * @return array<string, mixed>
     */
    private function resolveAlmostRowToDraft(array $almost, int $teamId, int $leagueId): array
    {
        if ($almost['use_existing']) {
            $playerId = (int) $almost['db_player_id'];
            $squadRow = Playerteam::query()
                ->where('playerteam_player_id', $playerId)
                ->where('playerteam_team_id', $teamId)
                ->where('playerteam_league_id', $leagueId)
                ->first();

            return $this->normalizeAutoDraftRow([
                'player_id' => $playerId,
                'playerteam_id' => $squadRow !== null ? (int) $squadRow->playerteam_id : 0,
                'is_new' => false,
                'on_squad' => $squadRow !== null,
                'player_fname' => $almost['db_fname'],
                'player_lname' => $almost['db_lname'],
                'player_nationality' => $almost['db_nationality'],
                'player_foreign_id' => $almost['db_foreign_id'],
                'playerteam_player_position' => $almost['playerteam_player_position'],
                'playerteam_player_price' => $almost['playerteam_player_price'],
                'playerteam_status' => $almost['playerteam_status'],
                'playerteam_date_transfer' => $almost['playerteam_date_transfer'],
                'json_number' => $almost['json_number'],
                'json_name' => $almost['json_name'],
            ]);
        }

        return $this->normalizeAutoDraftRow([
            'player_id' => 0,
            'playerteam_id' => 0,
            'is_new' => true,
            'on_squad' => false,
            'player_fname' => $almost['json_fname'],
            'player_lname' => $almost['json_lname'],
            'player_nationality' => $almost['json_nationality'],
            'player_foreign_id' => '',
            'playerteam_player_position' => $almost['playerteam_player_position'],
            'playerteam_player_price' => $almost['playerteam_player_price'],
            'playerteam_status' => $almost['playerteam_status'],
            'playerteam_date_transfer' => $almost['playerteam_date_transfer'],
            'json_number' => $almost['json_number'],
            'json_name' => $almost['json_name'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function normalizeAutoDraftRow(array $row): array
    {
        $roster = $this->normalizeRosterInput($row);
        $onSquad = ((string) ($row['on_squad'] ?? '0') === '1' || ($row['on_squad'] ?? false) === true);
        $isNew = ! $onSquad && (
            ((string) ($row['is_new'] ?? '0') === '1' || ($row['is_new'] ?? false) === true)
            || (int) ($row['player_id'] ?? 0) <= 0
        );

        return [
            'player_id' => (int) ($row['player_id'] ?? 0),
            'playerteam_id' => (int) ($row['playerteam_id'] ?? 0),
            'is_new' => $isNew,
            'on_squad' => $onSquad,
            'player_fname' => trim((string) ($row['player_fname'] ?? '')),
            'player_lname' => trim((string) ($row['player_lname'] ?? '')),
            'player_nationality' => strtoupper(trim((string) ($row['player_nationality'] ?? ''))),
            'player_status' => 1,
            'player_status_description' => '',
            'player_foreign_id' => trim((string) ($row['player_foreign_id'] ?? '')),
            'playerteam_player_position' => $roster['playerteam_player_position'],
            'playerteam_player_price' => $roster['playerteam_player_price'],
            'playerteam_status' => $roster['playerteam_status'],
            'playerteam_date_transfer' => $roster['playerteam_date_transfer'],
            'json_number' => (int) ($row['json_number'] ?? 0),
            'json_name' => trim((string) ($row['json_name'] ?? '')),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $draft
     * @return list<array<string, mixed>>
     */
    private function sortAutoDraftByPosition(array $draft): array
    {
        $order = ['g' => 0, 'd' => 1, 'm' => 2, 's' => 3];
        usort($draft, static function (array $a, array $b) use ($order): int {
            $posA = $order[(string) ($a['playerteam_player_position'] ?? '')] ?? 99;
            $posB = $order[(string) ($b['playerteam_player_position'] ?? '')] ?? 99;
            if ($posA !== $posB) {
                return $posA <=> $posB;
            }

            return ((int) ($a['json_number'] ?? 0)) <=> ((int) ($b['json_number'] ?? 0));
        });

        return array_values($draft);
    }

    /**
     * @param  list<array<string, mixed>>  $almost
     * @return list<array<string, mixed>>
     */
    private function sortAlmostDraftByPosition(array $almost): array
    {
        $order = ['g' => 0, 'd' => 1, 'm' => 2, 's' => 3];
        usort($almost, static function (array $a, array $b) use ($order): int {
            $posA = $order[(string) ($a['playerteam_player_position'] ?? $a['json_position'] ?? '')] ?? 99;
            $posB = $order[(string) ($b['playerteam_player_position'] ?? $b['json_position'] ?? '')] ?? 99;
            if ($posA !== $posB) {
                return $posA <=> $posB;
            }

            return ((int) ($a['json_number'] ?? 0)) <=> ((int) ($b['json_number'] ?? 0));
        });

        return array_values($almost);
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function autoDraftLabel(array $row, int $index): string
    {
        $name = trim((string) ($row['json_name'] ?? ''));
        if ($name === '') {
            $name = trim((string) ($row['player_fname'] ?? '').' '.(string) ($row['player_lname'] ?? ''));
        }
        if ($name !== '') {
            return $name;
        }

        return 'Zeile '.($index + 1);
    }
}
