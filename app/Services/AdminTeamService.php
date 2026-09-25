<?php

namespace App\Services;

use App\Models\League;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamfid;
use App\Models\Userteam;
use App\Support\Flag;
use App\Support\TeamShirt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class AdminTeamService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly UefaCompApiClient $uefaClient = new UefaCompApiClient,
    ) {}

    /**
     * @param  array<string, mixed>|null  $form
     * @param  array<string, mixed>|null  $auto
     * @param  array<string, mixed>|null  $autoUefa
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        ?array $form = null,
        string $mode = 'create',
        string $tab = 'manual',
        ?array $auto = null,
        ?array $autoUefa = null,
    ): array {
        $shell = $this->adminCenter->shellPayload($userId);
        $form = $form ?? $this->emptyForm();
        $selectedKey = (string) ($form['team_nationality'] ?? '');
        $teamId = (int) ($form['team_id'] ?? 0);
        $resolvedTab = match ($tab) {
            'auto' => 'auto',
            'auto-uefa' => 'auto-uefa',
            default => 'manual',
        };

        $selectedLeagueId = (int) ($shell['selected_league_id'] ?? 0);
        $uefaIdentifier = '';
        if ($selectedLeagueId > 0) {
            $uefaIdentifier = (string) (League::query()
                ->whereKey($selectedLeagueId)
                ->value('league_uefa_competition_identifier') ?? '');
        }

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'selected_league_id' => $selectedLeagueId,
            'uefa_competition_identifier' => $uefaIdentifier,
            'icons' => $this->iconOptions($selectedKey),
            'selected_symbol' => $this->selectedSymbol($selectedKey, $teamId),
            'uses_icon_picker' => ! $this->isMappedNation($selectedKey),
            'items' => $this->listItems(),
            'team_options' => $resolvedTab === 'auto-uefa' ? $this->teamSelectOptions() : [],
            'form' => $form,
            'mode' => $mode === 'update' ? 'update' : 'create',
            'tab' => $resolvedTab,
            'auto' => $auto ?? $this->emptyAutoState(),
            'auto_uefa' => $autoUefa ?? $this->emptyAutoUefaState(),
            'matchplan_files' => $resolvedTab === 'auto' ? $this->matchplanJsonOptions() : [],
        ];
    }

    /**
     * @return array{analyzed: bool, source_name: string, present: list<array<string, mixed>>, missing: list<array<string, mixed>>}
     */
    public function emptyAutoState(): array
    {
        return [
            'analyzed' => false,
            'source_name' => '',
            'present' => [],
            'missing' => [],
        ];
    }

    /**
     * @return array{
     *     analyzed: bool,
     *     source_name: string,
     *     league_id: int,
     *     rows: list<array<string, mixed>>
     * }
     */
    public function emptyAutoUefaState(): array
    {
        return [
            'analyzed' => false,
            'source_name' => '',
            'league_id' => 0,
            'rows' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(): array
    {
        return [
            'team_id' => '',
            'team_name' => '',
            'team_nationality' => '',
            'team_icon_key' => '',
            'team_status' => 1,
            'team_uefa_id' => '',
            'team_team_code' => '',
            'teamfid_fid_tm' => '',
            'teamfid_name_tm' => '',
            'teamfid_name_wf' => '',
            'teamfid_url_foe' => '',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function formForEdit(int $teamId): ?array
    {
        $item = Team::query()->with('teamfid')->find($teamId);
        if (! $item) {
            return null;
        }

        $fid = $item->teamfid;
        $icon = $this->normalizeIconKey((string) ($item->team_nationality ?? ''));

        return [
            'team_id' => (int) $item->team_id,
            'team_name' => (string) $item->team_name,
            'team_nationality' => $icon,
            'team_icon_key' => '',
            'team_status' => (int) (bool) $item->team_status,
            'team_uefa_id' => (string) ($item->team_uefa_id ?? ''),
            'team_team_code' => (string) ($item->team_team_code ?? ''),
            'teamfid_fid_tm' => (string) ($fid?->teamfid_fid_tm ?? ''),
            'teamfid_name_tm' => (string) ($fid?->teamfid_name_tm ?? ''),
            'teamfid_name_wf' => (string) ($fid?->teamfid_name_wf ?? ''),
            'teamfid_url_foe' => (string) ($fid?->teamfid_url_foe ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function create(array $input, ?UploadedFile $iconFile = null, ?UploadedFile $shirtFile = null): array
    {
        $form = $this->normalizeInput($input);
        $prepared = $this->prepareFormWithIcon($form, $iconFile, true);
        if (! $prepared['ok']) {
            return [
                'ok' => false,
                'errors' => $prepared['errors'],
                'form' => $prepared['form'],
            ];
        }
        $form = $prepared['form'];

        $shirtErrors = $this->validateShirtUpload($shirtFile, $form['team_nationality']);
        if ($shirtErrors !== []) {
            return [
                'ok' => false,
                'errors' => $shirtErrors,
                'form' => $form,
            ];
        }

        $createdTeamId = 0;
        DB::transaction(function () use ($form, &$createdTeamId) {
            $team = Team::query()->create([
                'team_foreign_id' => '',
                'team_name' => $form['team_name'],
                'team_nationality' => $form['team_nationality'],
                'team_num_players' => 0,
                'team_status' => (int) $form['team_status'],
                'team_uefa_id' => $form['team_uefa_id'],
                'team_team_code' => $form['team_team_code'],
            ]);
            $createdTeamId = (int) $team->team_id;

            $this->upsertTeamfid($createdTeamId, $form);
        });

        if ($shirtFile !== null && $createdTeamId > 0) {
            if (! $this->storeShirtFile($shirtFile, $createdTeamId, $form['team_nationality'])) {
                return [
                    'ok' => false,
                    'errors' => ['Team wurde angelegt, aber das Trikot konnte nicht gespeichert werden.'],
                    'form' => $form + ['team_id' => $createdTeamId],
                ];
            }
        }

        return ['ok' => true, 'message' => 'Team erfolgreich hinzugefügt.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function update(int $teamId, array $input, ?UploadedFile $iconFile = null, ?UploadedFile $shirtFile = null): array
    {
        $item = Team::query()->find($teamId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Team nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['team_id' => $teamId]),
            ];
        }

        $form = $this->normalizeInput($input + ['team_id' => $teamId]);
        $prepared = $this->prepareFormWithIcon($form, $iconFile, false);
        if (! $prepared['ok']) {
            return [
                'ok' => false,
                'errors' => $prepared['errors'],
                'form' => $prepared['form'],
            ];
        }
        $form = $prepared['form'];

        $shirtErrors = $this->validateShirtUpload($shirtFile, $form['team_nationality']);
        if ($shirtErrors !== []) {
            return [
                'ok' => false,
                'errors' => $shirtErrors,
                'form' => $form,
            ];
        }

        DB::transaction(function () use ($item, $form) {
            $item->team_name = $form['team_name'];
            $item->team_nationality = $form['team_nationality'];
            $item->team_status = (int) $form['team_status'];
            $item->team_uefa_id = $form['team_uefa_id'];
            $item->team_team_code = $form['team_team_code'];
            $item->save();

            $this->upsertTeamfid((int) $item->team_id, $form);
        });

        if ($shirtFile !== null) {
            if (! $this->storeShirtFile($shirtFile, $teamId, $form['team_nationality'])) {
                return [
                    'ok' => false,
                    'errors' => ['Team wurde aktualisiert, aber das Trikot konnte nicht gespeichert werden.'],
                    'form' => $form,
                ];
            }
        }

        return ['ok' => true, 'message' => 'Team erfolgreich aktualisiert.'];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function delete(int $teamId): array
    {
        $item = Team::query()->find($teamId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Team nicht gefunden! Falsche ID oder Seite neu geladen?']];
        }

        if ($this->hasPlayersInUserteams($teamId)) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Spieler dieses Teams sind in Userteams eingesetzt.'],
            ];
        }

        DB::transaction(function () use ($item) {
            Teamfid::query()->where('teamfid_team_id', $item->team_id)->delete();
            $item->delete();
        });

        return ['ok' => true, 'message' => 'Team erfolgreich gelöscht.'];
    }

    /**
     * Compare teams from a Spielplan JSON file in public/data/match against ffb_team.
     *
     * @return array{
     *     ok: bool,
     *     errors?: list<string>,
     *     auto?: array{analyzed: bool, source_name: string, present: list<array<string, mixed>>, missing: list<array<string, mixed>>},
     *     message?: string
     * }
     */
    public function analyzeMatchroundsFile(?string $storedFileName): array
    {
        $parsed = $this->parseMatchroundsStoredFile($storedFileName);
        if (! ($parsed['ok'] ?? false)) {
            return ['ok' => false, 'errors' => $parsed['errors'] ?? ['Analyse fehlgeschlagen.']];
        }

        /** @var array<string, mixed> $data */
        $data = $parsed['data'];
        $names = $this->extractTeamNamesFromMatchrounds($data);
        if ($names === []) {
            return ['ok' => false, 'errors' => ['In der JSON-Datei wurden keine Teams gefunden (erwartet: spieltage[].spiele[].heim/gast).']];
        }

        $auto = $this->compareTeamNames($names, (string) $parsed['source_name']);

        return [
            'ok' => true,
            'auto' => $auto,
            'message' => count($auto['missing']).' neue Teams, '.count($auto['present']).' bereits vorhanden.',
        ];
    }

    /**
     * Parse a spielplan JSON file from public/data/match (basename only; no path traversal).
     *
     * @return array{ok: true, data: array<string, mixed>, source_name: string}|array{ok: false, errors: list<string>}
     */
    public function parseMatchroundsStoredFile(?string $fileName): array
    {
        $path = $this->resolveMatchplanPath($fileName);
        if ($path === null) {
            return ['ok' => false, 'errors' => ['Bitte eine JSON-Datei aus public/data/match wählen.']];
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

        if (! isset($data['spieltage']) || ! is_array($data['spieltage'])) {
            return ['ok' => false, 'errors' => ['JSON muss ein Array "spieltage" enthalten.']];
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
    public function matchplanJsonOptions(): array
    {
        $dir = public_path('data/match');
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

    private function resolveMatchplanPath(?string $fileName): ?string
    {
        $fileName = basename(str_replace(["\0", '\\', '/'], '', trim((string) $fileName)));
        if ($fileName === '' || ! str_ends_with(strtolower($fileName), '.json')) {
            return null;
        }

        $dir = realpath(public_path('data/match'));
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
     * @param  array<string, mixed>  $data
     * @return list<string>
     */
    public function extractTeamNamesFromMatchrounds(array $data): array
    {
        $spieltage = $data['spieltage'] ?? null;
        if (! is_array($spieltage)) {
            return [];
        }

        $unique = [];
        foreach ($spieltage as $round) {
            if (! is_array($round)) {
                continue;
            }

            $spiele = $round['spiele'] ?? null;
            if (! is_array($spiele)) {
                continue;
            }

            foreach ($spiele as $match) {
                if (! is_array($match)) {
                    continue;
                }

                foreach (['heim', 'gast'] as $side) {
                    $name = trim((string) ($match[$side] ?? ''));
                    if ($name === '') {
                        continue;
                    }

                    $key = mb_strtolower($name);
                    if (! isset($unique[$key])) {
                        $unique[$key] = $name;
                    }
                }
            }
        }

        $names = array_values($unique);
        natcasesort($names);

        return array_values($names);
    }

    /**
     * @param  list<string>  $names
     * @return list<string>
     */
    public function missingTeamNames(array $names): array
    {
        $comparison = $this->compareTeamNames($names, '');

        return array_values(array_map(
            static fn (array $row): string => (string) $row['team_name'],
            $comparison['missing'],
        ));
    }

    /**
     * @return array<string, int> lowercase team name => team_id
     */
    public function teamIdsByName(): array
    {
        $map = [];
        foreach (Team::query()->get(['team_id', 'team_name']) as $team) {
            $key = mb_strtolower(trim((string) $team->team_name));
            if ($key === '' || isset($map[$key])) {
                continue;
            }
            $map[$key] = (int) $team->team_id;
        }

        return $map;
    }

    /**
     * Create missing teams from the Auto-Teams draft rows.
     *
     * @param  list<array<string, mixed>>|array<int, array<string, mixed>>  $teams
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     auto?: array{analyzed: bool, source_name: string, present: list<array<string, mixed>>, missing: list<array<string, mixed>>}
     * }
     */
    public function createMissingTeams(array $teams, string $sourceName = ''): array
    {
        $drafts = [];
        $errors = [];
        $seenNames = [];

        foreach (array_values($teams) as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $form = $this->normalizeInput($row);
            $prepared = $this->prepareFormWithIcon($form, null, true);
            if (! $prepared['ok']) {
                $label = $form['team_name'] !== '' ? $form['team_name'] : 'Zeile '.($index + 1);
                $errors[] = $label.': '.implode(' ', $prepared['errors']);
                $drafts[] = [
                    'team_name' => $form['team_name'],
                    'team_nationality' => $form['team_nationality'],
                    'team_status' => (int) $form['team_status'],
                ];

                continue;
            }

            $nameKey = mb_strtolower($prepared['form']['team_name']);
            if (isset($seenNames[$nameKey])) {
                $errors[] = $prepared['form']['team_name'].': Teamname kommt mehrfach in der Liste vor.';
                $drafts[] = [
                    'team_name' => $prepared['form']['team_name'],
                    'team_nationality' => $prepared['form']['team_nationality'],
                    'team_status' => (int) $prepared['form']['team_status'],
                ];

                continue;
            }
            $seenNames[$nameKey] = true;

            $drafts[] = [
                'team_name' => $prepared['form']['team_name'],
                'team_nationality' => $prepared['form']['team_nationality'],
                'team_status' => (int) $prepared['form']['team_status'],
                '_form' => $prepared['form'],
            ];
        }

        if ($drafts === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Teams zum Anlegen.'],
                'auto' => [
                    'analyzed' => true,
                    'source_name' => $sourceName,
                    'present' => [],
                    'missing' => [],
                ],
            ];
        }

        if ($errors !== []) {
            return [
                'ok' => false,
                'errors' => $errors,
                'auto' => [
                    'analyzed' => true,
                    'source_name' => $sourceName,
                    'present' => [],
                    'missing' => array_map(static function (array $row): array {
                        unset($row['_form']);

                        return $row;
                    }, $drafts),
                ],
            ];
        }

        $created = 0;
        DB::transaction(function () use ($drafts, &$created): void {
            foreach ($drafts as $row) {
                /** @var array<string, mixed> $form */
                $form = $row['_form'];
                $team = Team::query()->create([
                    'team_foreign_id' => '',
                    'team_name' => $form['team_name'],
                    'team_nationality' => $form['team_nationality'],
                    'team_num_players' => 0,
                    'team_status' => (int) $form['team_status'],
                ]);
                $this->upsertTeamfid((int) $team->team_id, $form);
                $created++;
            }
        });

        return [
            'ok' => true,
            'message' => $created === 1
                ? '1 Team erfolgreich hinzugefügt.'
                : $created.' Teams erfolgreich hinzugefügt.',
        ];
    }

    /**
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     auto_uefa?: array<string, mixed>
     * }
     */
    public function analyzeUefaTeams(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst unter Ligen eine Liga auswählen.']];
        }

        $league = League::query()->find($leagueId);
        if (! $league) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.']];
        }

        $identifier = trim((string) ($league->league_uefa_competition_identifier ?? ''));
        if ($identifier === '') {
            return [
                'ok' => false,
                'errors' => ['Für diese Liga ist kein UEFA-Competition-Identifier hinterlegt (Admin → Ligen).'],
            ];
        }

        try {
            $api = UefaCompetitionApi::fromIdentifier($identifier, $this->uefaClient);
            $uefaTeams = $api->teams();
        } catch (InvalidArgumentException $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        } catch (Throwable $e) {
            Log::warning('UEFA auto-teams analyze failed', [
                'league_id' => $leagueId,
                'message' => $e->getMessage(),
            ]);

            return ['ok' => false, 'errors' => ['UEFA-Teams konnten nicht geladen werden: '.$e->getMessage()]];
        }

        if ($uefaTeams === []) {
            return [
                'ok' => false,
                'errors' => ['Für '.$identifier.' wurden keine UEFA-Teams gefunden.'],
            ];
        }

        $ffbTeams = Team::query()
            ->orderBy('team_name')
            ->get([
                'team_id',
                'team_name',
                'team_nationality',
                'team_uefa_id',
                'team_team_code',
                'team_status',
            ]);

        /** @var array<string, Team> $byUefaId */
        $byUefaId = [];
        /** @var array<string, Team> $byTeamCode */
        $byTeamCode = [];
        foreach ($ffbTeams as $team) {
            $uefaId = trim((string) ($team->team_uefa_id ?? ''));
            if ($uefaId !== '' && ! isset($byUefaId[$uefaId])) {
                $byUefaId[$uefaId] = $team;
            }
            $code = strtoupper(trim((string) ($team->team_team_code ?? '')));
            if ($code !== '' && ! isset($byTeamCode[$code])) {
                $byTeamCode[$code] = $team;
            }
        }

        $rows = [];
        $matched = 0;
        /** @var array<int, true> $usedTeamIds */
        $usedTeamIds = [];

        foreach ($uefaTeams as $uefa) {
            $match = null;
            $uefaId = (string) $uefa['uefa_id'];
            $teamCode = $uefa['team_code'] !== '' ? $uefa['team_code'] : $uefa['country_code'];
            $teamCode = strtoupper(trim((string) $teamCode));

            // Prefer an existing DB link via team_uefa_id; fall back only when none exists.
            if (isset($byUefaId[$uefaId]) && ! isset($usedTeamIds[(int) $byUefaId[$uefaId]->team_id])) {
                $match = $byUefaId[$uefaId];
            } elseif ($teamCode !== '') {
                $match = $this->firstUnusedFallbackTeam($byTeamCode[$teamCode] ?? null, $usedTeamIds)
                    ?? $this->matchByNationalityFallback(
                        $teamCode,
                        (string) $uefa['name_de'],
                        $ffbTeams,
                        $usedTeamIds,
                    );
            }

            $teamId = $match !== null ? (int) $match->team_id : 0;
            if ($teamId > 0) {
                $usedTeamIds[$teamId] = true;
                $matched++;
            }

            $rows[] = [
                'match_status' => $teamId > 0 ? 'matched' : 'unmatched',
                'team_id' => $teamId,
                'create_new' => 0,
                'team_name' => $match !== null ? (string) $match->team_name : '',
                'team_nationality' => $match !== null
                    ? (string) $match->team_nationality
                    : strtolower($teamCode),
                'uefa_name' => $uefa['name_de'],
                'uefa_id' => $uefaId,
                'uefa_team_code' => $teamCode,
                'team_uefa_id' => $uefaId,
                'team_team_code' => $teamCode,
            ];
        }

        $unmatched = count($rows) - $matched;

        return [
            'ok' => true,
            'auto_uefa' => [
                'analyzed' => true,
                'source_name' => $api->identifier(),
                'league_id' => $leagueId,
                'rows' => $rows,
            ],
            'message' => count($rows).' UEFA-Teams geprüft, '.$matched.' automatisch zugeordnet'
                .($unmatched > 0 ? ', '.$unmatched.' ohne Treffer.' : '.'),
        ];
    }

    /**
     * @param  list<array<string, mixed>>|array<int, array<string, mixed>>  $rows
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     auto_uefa?: array<string, mixed>
     * }
     */
    public function saveUefaTeams(array $rows, string $sourceName = '', int $leagueId = 0): array
    {
        $normalized = [];
        $errors = [];
        /** @var array<int, true> $usedTeamIds */
        $usedTeamIds = [];

        foreach (array_values($rows) as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $label = trim((string) ($row['uefa_name'] ?? '')) !== ''
                ? (string) $row['uefa_name']
                : 'Zeile '.($index + 1);

            $uefaId = trim((string) ($row['team_uefa_id'] ?? ($row['uefa_id'] ?? '')));
            $teamCode = strtoupper(trim((string) ($row['team_team_code'] ?? ($row['uefa_team_code'] ?? ''))));
            $createNew = (int) ($row['create_new'] ?? 0) === 1;
            $teamId = (int) ($row['team_id'] ?? 0);
            $teamName = trim((string) ($row['team_name'] ?? ''));
            $nationality = $this->normalizeIconKey((string) ($row['team_nationality'] ?? ''));
            if ($nationality === '' && $teamCode !== '') {
                $nationality = strtolower($teamCode);
            }

            if ($uefaId === '') {
                $errors[] = $label.': UEFA-ID fehlt.';
            }
            if ($teamCode === '') {
                $errors[] = $label.': Team-Code fehlt.';
            }

            if (! $createNew && $teamId <= 0) {
                $errors[] = $label.': Bitte ein FFB-Team zuordnen oder „Neu anlegen“ wählen.';
            }

            if ($createNew) {
                $teamId = 0;
                if ($teamName === '') {
                    $teamName = trim((string) ($row['uefa_name'] ?? ''));
                }
                if ($teamName === '') {
                    $errors[] = $label.': Teamname für Neuanlage fehlt.';
                }
            } elseif ($teamId > 0) {
                if (isset($usedTeamIds[$teamId])) {
                    $errors[] = $label.': FFB-Team #'.$teamId.' ist mehrfach zugeordnet.';
                }
                $usedTeamIds[$teamId] = true;
            }

            $normalized[] = [
                'team_id' => $teamId,
                'create_new' => $createNew ? 1 : 0,
                'team_name' => $teamName,
                'team_nationality' => $nationality,
                'uefa_name' => (string) ($row['uefa_name'] ?? ''),
                'uefa_id' => (string) ($row['uefa_id'] ?? $uefaId),
                'uefa_team_code' => (string) ($row['uefa_team_code'] ?? $teamCode),
                'team_uefa_id' => $uefaId,
                'team_team_code' => $teamCode,
                'match_status' => ($createNew || $teamId > 0) ? 'matched' : 'unmatched',
            ];
        }

        $autoState = [
            'analyzed' => true,
            'source_name' => $sourceName,
            'league_id' => $leagueId,
            'rows' => $normalized,
        ];

        if ($normalized === []) {
            return ['ok' => false, 'errors' => ['Keine Teams zum Speichern.'], 'auto_uefa' => $autoState];
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'auto_uefa' => $autoState];
        }

        $updated = 0;
        $created = 0;

        try {
            DB::transaction(function () use ($normalized, &$updated, &$created): void {
                foreach ($normalized as $row) {
                    if ((int) $row['create_new'] === 1) {
                        Team::query()->create([
                            'team_foreign_id' => '',
                            'team_name' => $row['team_name'],
                            'team_nationality' => $row['team_nationality'],
                            'team_num_players' => 0,
                            'team_status' => 1,
                            'team_uefa_id' => $row['team_uefa_id'],
                            'team_team_code' => $row['team_team_code'],
                        ]);
                        $created++;

                        continue;
                    }

                    $teamId = (int) $row['team_id'];
                    $team = Team::query()->find($teamId);
                    if (! $team) {
                        throw new InvalidArgumentException('Team #'.$teamId.' nicht gefunden.');
                    }

                    // Existing teams: only persist UEFA link fields — never name/nationality.
                    $affected = Team::query()
                        ->whereKey($teamId)
                        ->update([
                            'team_uefa_id' => $row['team_uefa_id'],
                            'team_team_code' => $row['team_team_code'],
                        ]);

                    if ($affected === 0 && (
                        (string) $team->team_uefa_id !== (string) $row['team_uefa_id']
                        || (string) $team->team_team_code !== (string) $row['team_team_code']
                    )) {
                        throw new InvalidArgumentException(
                            'Team #'.$teamId.': team_uefa_id/team_team_code konnten nicht gespeichert werden (Spalten ggf. nicht schreibbar).'
                        );
                    }

                    $updated++;
                }
            });
        } catch (InvalidArgumentException $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()], 'auto_uefa' => $autoState];
        }

        $parts = [];
        if ($updated > 0) {
            $parts[] = $updated === 1 ? '1 Team aktualisiert' : $updated.' Teams aktualisiert';
        }
        if ($created > 0) {
            $parts[] = $created === 1 ? '1 Team angelegt' : $created.' Teams angelegt';
        }

        return [
            'ok' => true,
            'message' => ($parts !== [] ? implode(', ', $parts) : 'Keine Änderungen').'.',
        ];
    }

    /**
     * Fallback match candidate: unused FFB team that is not already linked to another UEFA id.
     *
     * @param  array<int, true>  $usedTeamIds
     */
    private function firstUnusedFallbackTeam(?Team $candidate, array $usedTeamIds): ?Team
    {
        if ($candidate === null) {
            return null;
        }

        $teamId = (int) $candidate->team_id;
        if ($teamId <= 0 || isset($usedTeamIds[$teamId])) {
            return null;
        }

        // Already linked via team_uefa_id → only matchable by that id, not by code/nationality.
        if (trim((string) ($candidate->team_uefa_id ?? '')) !== '') {
            return null;
        }

        return $candidate;
    }

    /**
     * Nationality fallback: prefer same name; only use a lone candidate when nationality is unique.
     * Avoids matching club teams (e.g. Bad Bleiberg/AUT) to national sides (Österreich).
     *
     * @param  Collection<int, Team>|iterable<int, Team>  $ffbTeams
     * @param  array<int, true>  $usedTeamIds
     */
    private function matchByNationalityFallback(
        string $teamCode,
        string $uefaNameDe,
        iterable $ffbTeams,
        array $usedTeamIds,
    ): ?Team {
        $teamCode = strtoupper(trim($teamCode));
        if ($teamCode === '') {
            return null;
        }

        /** @var list<Team> $candidates */
        $candidates = [];
        foreach ($ffbTeams as $team) {
            $teamId = (int) $team->team_id;
            if ($teamId <= 0 || isset($usedTeamIds[$teamId])) {
                continue;
            }
            if (trim((string) ($team->team_uefa_id ?? '')) !== '') {
                continue;
            }
            $nat = strtoupper(trim((string) ($team->team_nationality ?? '')));
            if ($nat !== $teamCode) {
                continue;
            }
            $candidates[] = $team;
        }

        if ($candidates === []) {
            return null;
        }

        $normalizedUefaName = $this->normalizeMatchName($uefaNameDe);
        foreach ($candidates as $team) {
            if ($this->normalizeMatchName((string) $team->team_name) === $normalizedUefaName) {
                return $team;
            }
        }

        return count($candidates) === 1 ? $candidates[0] : null;
    }

    private function normalizeMatchName(string $name): string
    {
        $name = mb_strtolower(trim($name));
        $name = str_replace(['ä', 'ö', 'ü', 'ß'], ['ae', 'oe', 'ue', 'ss'], $name);

        return preg_replace('/[^a-z0-9]+/', '', $name) ?? '';
    }

    /**
     * @return list<array{team_id: int, team_label: string}>
     */
    private function teamSelectOptions(): array
    {
        return Team::query()
            ->orderBy('team_name')
            ->get(['team_id', 'team_name', 'team_nationality'])
            ->map(static function (Team $team): array {
                $nat = strtoupper(trim((string) ($team->team_nationality ?? '')));
                $label = (string) $team->team_name;
                if ($nat !== '') {
                    $label .= ' ('.$nat.')';
                }

                return [
                    'team_id' => (int) $team->team_id,
                    'team_label' => $label,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $names
     * @return array{analyzed: bool, source_name: string, present: list<array<string, mixed>>, missing: list<array<string, mixed>>}
     */
    private function compareTeamNames(array $names, string $sourceName): array
    {
        $existing = Team::query()
            ->orderBy('team_name')
            ->get(['team_id', 'team_name', 'team_nationality', 'team_status']);

        $byName = [];
        foreach ($existing as $team) {
            $key = mb_strtolower(trim((string) $team->team_name));
            if ($key === '' || isset($byName[$key])) {
                continue;
            }
            $byName[$key] = $team;
        }

        $present = [];
        $missing = [];
        foreach ($names as $name) {
            $key = mb_strtolower($name);
            if (isset($byName[$key])) {
                $team = $byName[$key];
                $present[] = [
                    'team_id' => (int) $team->team_id,
                    'team_name' => (string) $team->team_name,
                    'team_nationality' => $this->normalizeIconKey((string) ($team->team_nationality ?? '')),
                    'team_status' => (int) (bool) $team->team_status,
                ];

                continue;
            }

            $missing[] = [
                'team_name' => $name,
                'team_nationality' => $this->suggestNationality($name),
                'team_status' => 1,
            ];
        }

        return [
            'analyzed' => true,
            'source_name' => $sourceName,
            'present' => $present,
            'missing' => $missing,
        ];
    }

    private function suggestNationality(string $teamName): string
    {
        $needle = mb_strtolower(trim($teamName));
        if ($needle === '') {
            return '';
        }

        $aliases = [
            'niederlande' => 'ned',
            'holland' => 'ned',
            'nordmazedonien' => 'mkd',
            'mazedonien' => 'mkd',
            'bosnien und herzegowina' => 'bih',
            'bosnien-herzegowina' => 'bih',
            'tschechische republik' => 'cze',
            'republik moldau' => 'mda',
            'moldawien' => 'mda',
            'weißrussland' => 'blr',
            'weissrussland' => 'blr',
            'belarus' => 'blr',
            'kosovo' => 'kos',
        ];

        $code = $aliases[$needle] ?? '';
        if ($code === '') {
            foreach ($this->countryLabels() as $countryCode => $label) {
                if (mb_strtolower(trim((string) $label)) === $needle) {
                    $code = (string) $countryCode;
                    break;
                }
            }
        }

        $normalized = $this->normalizeIconKey($code);
        $remap = [
            'rks' => 'kos',
        ];
        $normalized = $remap[$normalized] ?? $normalized;

        if ($normalized !== '' && $this->iconFileExists($normalized)) {
            return $normalized;
        }

        return '';
    }

    /**
     * @return list<array{key: string, url: string, label: string, shirt_url: string|null, has_shirt: bool}>
     */
    private function iconOptions(string $selectedKey = ''): array
    {
        $countries = $this->countryLabels();
        $selectedKey = $this->normalizeIconKey($selectedKey);
        $seen = [];
        $icons = [];

        $dir = $this->flagsDir();
        if (is_dir($dir)) {
            foreach (glob($dir.DIRECTORY_SEPARATOR.'*.gif') ?: [] as $path) {
                $key = $this->normalizeIconKey((string) pathinfo($path, PATHINFO_FILENAME));
                if ($key === '' || isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $icons[] = $this->iconPayload($key, $countries);
            }
        }

        if ($selectedKey !== '' && ! isset($seen[$selectedKey])) {
            $icons[] = $this->iconPayload($selectedKey, $countries);
        }

        usort($icons, static fn (array $a, array $b): int => strcasecmp($a['key'], $b['key']));

        return $icons;
    }

    /**
     * @return array{key: string, url: string|null, html: string, label: string, shirt_url: string|null, has_shirt: bool, shirt_path_hint: string}|null
     */
    private function selectedSymbol(string $selectedKey, int $teamId = 0): ?array
    {
        $selectedKey = $this->normalizeIconKey($selectedKey);
        if ($selectedKey === '') {
            return null;
        }

        $countries = $this->countryLabels();
        $upper = strtoupper($selectedKey);
        $shirtUrl = $teamId > 0 ? TeamShirt::url($teamId, $selectedKey) : null;
        $pathHint = $teamId > 0
            ? 'shirts/'.$teamId.'/'.$selectedKey.'.png'
            : 'shirts/<team_id>/'.$selectedKey.'.png';

        return [
            'key' => $selectedKey,
            'url' => $this->isMappedNation($selectedKey) ? null : Flag::imageUrl($selectedKey),
            'html' => Flag::html($selectedKey),
            'label' => $countries[$upper] ?? $selectedKey,
            'shirt_url' => $shirtUrl,
            'has_shirt' => $shirtUrl !== null,
            'shirt_path_hint' => $pathHint,
        ];
    }

    /**
     * @param  array<string, string>  $countries
     * @return array{key: string, url: string, label: string, shirt_url: string|null, has_shirt: bool}
     */
    private function iconPayload(string $key, array $countries): array
    {
        $upper = strtoupper($key);
        $label = $countries[$upper] ?? $key;

        return [
            'key' => $key,
            'url' => Flag::imageUrl($key),
            'label' => $label,
            'shirt_url' => null,
            'has_shirt' => false,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listItems(): array
    {
        $countries = $this->countryLabels();

        return Team::query()
            ->with('teamfid')
            ->orderBy('team_name')
            ->get()
            ->map(function (Team $item) use ($countries) {
                $icon = $this->normalizeIconKey((string) ($item->team_nationality ?? ''));
                $fid = $item->teamfid;
                $upper = strtoupper($icon);

                return [
                    'team_id' => (int) $item->team_id,
                    'team_name' => (string) $item->team_name,
                    'team_nationality' => $icon,
                    'team_icon_label' => $icon !== '' ? ($countries[$upper] ?? $icon) : '',
                    'team_status' => (int) (bool) $item->team_status,
                    'flag_url' => $icon !== '' ? Flag::imageUrl($icon) : null,
                    'flag_html' => $icon !== '' ? Flag::html($icon) : '',
                    'teamfid_fid_tm' => (string) ($fid?->teamfid_fid_tm ?? ''),
                    'teamfid_name_tm' => (string) ($fid?->teamfid_name_tm ?? ''),
                    'teamfid_url_tm' => (string) ($fid?->teamfid_url_tm ?? ''),
                    'teamfid_name_wf' => (string) ($fid?->teamfid_name_wf ?? ''),
                    'teamfid_url_wf' => (string) ($fid?->teamfid_url_wf ?? ''),
                    'teamfid_url_foe' => (string) ($fid?->teamfid_url_foe ?? ''),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function upsertTeamfid(int $teamId, array $form): void
    {
        $urls = $this->buildExternalUrls($form);
        $payload = [
            'teamfid_fid_foe' => '',
            'teamfid_fid_tm' => $form['teamfid_fid_tm'],
            'teamfid_fid_wf' => '',
            'teamfid_name_foe' => '',
            'teamfid_name_tm' => $form['teamfid_name_tm'],
            'teamfid_name_wf' => $form['teamfid_name_wf'],
            'teamfid_url_foe' => $form['teamfid_url_foe'],
            'teamfid_url_tm' => $urls['tm'],
            'teamfid_url_wf' => $urls['wf'],
        ];

        $existing = Teamfid::query()->where('teamfid_team_id', $teamId)->first();
        if ($existing) {
            $existing->fill($payload);
            $existing->save();

            return;
        }

        Teamfid::query()->create($payload + ['teamfid_team_id' => $teamId]);
    }

    /**
     * @param  array<string, mixed>  $form
     * @return array{tm: string, wf: string}
     */
    private function buildExternalUrls(array $form): array
    {
        $tmId = $form['teamfid_fid_tm'];
        $tmName = $form['teamfid_name_tm'];
        $wfName = $form['teamfid_name_wf'];

        $tmUrl = '';
        if ($tmId !== '' && $tmName !== '') {
            $tmUrl = 'https://www.transfermarkt.at/'.$tmName.'/startseite/verein/'.$tmId;
        }

        $wfUrl = '';
        if ($wfName !== '') {
            $wfUrl = 'https://www.weltfussball.de/teams/'.$wfName.'/';
        }

        return ['tm' => $tmUrl, 'wf' => $wfUrl];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeInput(array $input): array
    {
        $foe = trim((string) ($input['teamfid_url_foe'] ?? ''));
        if ($foe !== '' && ! preg_match('#^https?://#i', $foe) && ! str_contains($foe, '/')) {
            $foe = 'https://vereine.oefb.at/'.$foe;
        } elseif ($foe !== '' && ! preg_match('#^https?://#i', $foe)) {
            $foe = 'https://vereine.oefb.at/'.ltrim($foe, '/');
        }

        return [
            'team_id' => (string) ($input['team_id'] ?? ''),
            'team_name' => trim((string) ($input['team_name'] ?? '')),
            'team_nationality' => $this->normalizeIconKey((string) ($input['team_nationality'] ?? '')),
            'team_icon_key' => $this->normalizeIconKey((string) ($input['team_icon_key'] ?? '')),
            'team_status' => ((string) ($input['team_status'] ?? '1') === '0') ? 0 : 1,
            'team_uefa_id' => trim((string) ($input['team_uefa_id'] ?? '')),
            'team_team_code' => strtoupper(trim((string) ($input['team_team_code'] ?? ''))),
            'teamfid_fid_tm' => trim((string) ($input['teamfid_fid_tm'] ?? '')),
            'teamfid_name_tm' => trim((string) ($input['teamfid_name_tm'] ?? '')),
            'teamfid_name_wf' => trim((string) ($input['teamfid_name_wf'] ?? '')),
            'teamfid_url_foe' => $foe,
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return array{ok: bool, errors: list<string>, form: array<string, mixed>}
     */
    private function prepareFormWithIcon(
        array $form,
        ?UploadedFile $iconFile,
        bool $isCreate,
    ): array {
        $errors = [];

        if ($form['team_name'] === '') {
            $errors[] = 'Bitte alle mit * markierten Felder ausfüllen.';
        }

        if ($iconFile !== null) {
            $mime = (string) $iconFile->getMimeType();
            if (! in_array($mime, ['image/png', 'image/jpeg', 'image/gif', 'image/webp'], true)) {
                $errors[] = 'Symbol muss ein Bild sein (PNG, JPEG, GIF oder WebP).';
            }
            if ($iconFile->getSize() > 2 * 1024 * 1024) {
                $errors[] = 'Symbol darf maximal 2 MB groß sein.';
            }

            $uploadKey = $form['team_icon_key'] !== ''
                ? $form['team_icon_key']
                : $this->normalizeIconKey((string) pathinfo((string) $iconFile->getClientOriginalName(), PATHINFO_FILENAME));

            if ($uploadKey === '') {
                $errors[] = 'Bitte einen Dateinamen/Schlüssel für das neue Symbol angeben.';
            } elseif (! preg_match('/^[a-z0-9][a-z0-9_-]*$/', $uploadKey)) {
                $errors[] = 'Symbol-Schlüssel: nur Kleinbuchstaben, Zahlen, _ und -.';
            }

            if ($errors === []) {
                if (! $this->storeIconFile($iconFile, $uploadKey)) {
                    $errors[] = 'Symbol konnte nicht gespeichert werden.';
                } else {
                    $form['team_nationality'] = $uploadKey;
                    $form['team_icon_key'] = '';
                }
            }
        } elseif (
            $form['team_nationality'] !== ''
            && ! $this->iconFileExists($form['team_nationality'])
        ) {
            $errors[] = 'Das gewählte Symbol existiert nicht im Flags-Ordner.';
        }

        if ($errors === [] && $isCreate && $form['team_name'] !== '') {
            $exists = Team::query()
                ->where('team_name', $form['team_name'])
                ->whereRaw('LOWER(team_nationality) = ?', [$form['team_nationality']])
                ->exists();
            if ($exists) {
                $errors[] = 'Ein Team mit diesem Namen und diesem Symbol existiert bereits.';
            }
        }

        return [
            'ok' => $errors === [],
            'errors' => $errors,
            'form' => $form,
        ];
    }

    /**
     * @return list<string>
     */
    private function validateShirtUpload(?UploadedFile $shirtFile, string $nationality): array
    {
        if ($shirtFile === null) {
            return [];
        }

        $errors = [];
        if ($nationality === '') {
            $errors[] = 'Trikot-Upload braucht ein gewähltes Symbol.';

            return $errors;
        }

        $mime = (string) $shirtFile->getMimeType();
        if (! in_array($mime, ['image/png', 'image/jpeg', 'image/gif', 'image/webp'], true)) {
            $errors[] = 'Trikot muss ein Bild sein (PNG, JPEG, GIF oder WebP).';
        }
        if ($shirtFile->getSize() > 2 * 1024 * 1024) {
            $errors[] = 'Trikot darf maximal 2 MB groß sein.';
        }

        return $errors;
    }

    private function storeIconFile(UploadedFile $file, string $key): bool
    {
        $dir = $this->flagsDir();
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return false;
        }

        $target = $dir.DIRECTORY_SEPARATOR.$key.'.gif';
        $mime = (string) $file->getMimeType();

        if ($mime === 'image/gif') {
            try {
                $file->move($dir, $key.'.gif');
            } catch (Throwable) {
                return false;
            }

            return is_file($target);
        }

        return $this->convertAndStoreImage($file, $mime, $target, 'gif');
    }

    private function storeShirtFile(UploadedFile $file, int $teamId, string $nationality): bool
    {
        $nat = TeamShirt::normalizeNationality($nationality);
        if ($teamId <= 0 || $nat === '') {
            return false;
        }

        $target = TeamShirt::defaultStoragePath($teamId, $nat);
        $dir = dirname($target);
        File::ensureDirectoryExists($dir);

        $mime = (string) $file->getMimeType();
        $filename = basename($target);

        if ($mime === 'image/png') {
            try {
                $file->move($dir, $filename);
            } catch (Throwable) {
                return false;
            }

            return is_file($target);
        }

        return $this->convertAndStoreImage($file, $mime, $target, 'png');
    }

    private function convertAndStoreImage(UploadedFile $file, string $mime, string $target, string $format): bool
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        $sourcePath = $file->getRealPath();
        if ($sourcePath === false) {
            return false;
        }

        $image = match ($mime) {
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
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

        if ($format === 'png') {
            if (function_exists('imagealphablending')) {
                imagealphablending($image, false);
            }
            if (function_exists('imagesavealpha')) {
                imagesavealpha($image, true);
            }
            $ok = @imagepng($image, $target);
        } else {
            if (function_exists('imagealphablending')) {
                imagealphablending($image, true);
            }
            if (function_exists('imagesavealpha')) {
                imagesavealpha($image, false);
            }
            $ok = @imagegif($image, $target);
        }

        imagedestroy($image);

        return (bool) $ok;
    }

    private function iconFileExists(string $key): bool
    {
        $key = $this->normalizeIconKey($key);
        if ($key === '') {
            return false;
        }

        if ($this->isMappedNation($key)) {
            return true;
        }

        return is_file($this->flagsDir().DIRECTORY_SEPARATOR.$key.'.gif');
    }

    private function normalizeIconKey(string $value): string
    {
        return TeamShirt::normalizeNationality($value);
    }

    /**
     * @return array<string, string>
     */
    private function countryLabels(): array
    {
        $countries = config('countries', []);
        if (! is_array($countries)) {
            return [];
        }

        /** @var array<string, string> $countries */
        return $countries;
    }

    private function isMappedNation(string $key): bool
    {
        return Flag::iso($key) !== null;
    }

    private function flagsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'flags';
    }

    private function hasPlayersInUserteams(int $teamId): bool
    {
        $playerteamIds = Playerteam::query()
            ->where('playerteam_team_id', $teamId)
            ->pluck('playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($playerteamIds === []) {
            return false;
        }

        return Userteam::queryContainingAnyPlayerteam($playerteamIds)->exists();
    }
}
