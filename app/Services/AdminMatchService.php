<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Team;
use App\Support\Flag;
use DateTimeImmutable;
use Illuminate\Http\UploadedFile;

class AdminMatchService
{
    private const ERROR_IDENTICAL_MATCH = 'Ein Spiel mit dieser Runde, diesem Datum und diesen Teams existiert bereits.';

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly AdminTeamService $teams,
    ) {}

    public function defaultLeagueId(int $userId): int
    {
        return $this->adminCenter->selectedLeagueId($userId);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  array<string, mixed>|null  $auto
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        int $selectedLeagueId,
        ?array $form = null,
        string $mode = 'create',
        string $tab = 'manual',
        ?array $auto = null,
    ): array {
        $shell = $this->adminCenter->shellPayload($userId);
        $leagues = $this->leagueOptions();
        $selectedLeagueId = $this->resolveSelectedLeagueId($selectedLeagueId, $leagues);
        $selectedTitle = null;
        foreach ($leagues as $league) {
            if ($league['league_id'] === $selectedLeagueId) {
                $selectedTitle = $league['league_title'];
                break;
            }
        }

        $form = $form ?? $this->emptyForm();

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'leagues' => $leagues,
            'selected_league_id' => $selectedLeagueId,
            'selected_league_title' => $selectedTitle,
            'matchrounds' => $selectedLeagueId > 0 ? $this->matchroundOptions($selectedLeagueId) : [],
            'teams' => $selectedLeagueId > 0 ? $this->teamOptions() : [],
            'items' => $selectedLeagueId > 0 ? $this->listItems($selectedLeagueId) : [],
            'form' => $form,
            'mode' => $mode === 'update' ? 'update' : 'create',
            'tab' => $tab === 'auto' ? 'auto' : 'manual',
            'auto' => $auto ?? $this->emptyAutoState(),
        ];
    }

    /**
     * @return array{analyzed: bool, source_name: string, league_id: int, matches: list<array<string, mixed>>}
     */
    public function emptyAutoState(): array
    {
        return [
            'analyzed' => false,
            'source_name' => '',
            'league_id' => 0,
            'matches' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(): array
    {
        return [
            'match_id' => '',
            'match_round' => '',
            'match_date' => '',
            'match_hometeam_id' => '',
            'match_guestteam_id' => '',
            'match_status' => '',
        ];
    }

    /**
     * @return array{form: array<string, mixed>, league_id: int}|null
     */
    public function formForEdit(int $matchId): ?array
    {
        $item = MatchGame::query()->with('matchround')->find($matchId);
        if (! $item) {
            return null;
        }

        $date = strtotime((string) $item->match_date);

        return [
            'league_id' => (int) ($item->matchround?->matchround_league_id ?? 0),
            'form' => [
                'match_id' => (int) $item->match_id,
                'match_round' => (int) $item->match_round,
                'match_date' => $date ? date('Y-m-d', $date) : '',
                'match_hometeam_id' => (int) $item->match_hometeam_id,
                'match_guestteam_id' => (int) $item->match_guestteam_id,
                'match_status' => (string) ($item->match_status ?? ''),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, league_id?: int, next_form?: array<string, mixed>}
     */
    public function create(array $input): array
    {
        $form = $this->normalizeInput($input);
        $leagueId = $this->leagueIdForRound((int) ($form['match_round'] ?: 0));
        $errors = $this->validate($form, true);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form, 'league_id' => $leagueId];
        }

        MatchGame::query()->create([
            'match_round' => (int) $form['match_round'],
            'match_date' => $form['match_date'].' 00:00:00',
            'match_hometeam_id' => (int) $form['match_hometeam_id'],
            'match_guestteam_id' => (int) $form['match_guestteam_id'],
            'match_status' => $form['match_status'],
            'match_homescore' => '-1',
            'match_guestscore' => '-1',
            'match_homescore_penalty' => '-1',
            'match_guestscore_penalty' => '-1',
            'match_minutes' => 0,
            'match_url' => '',
        ]);

        return [
            'ok' => true,
            'message' => 'Spiel erfolgreich hinzugefügt.',
            'league_id' => $leagueId,
            'next_form' => [
                'match_id' => '',
                'match_round' => $form['match_round'],
                'match_date' => $form['match_date'],
                'match_hometeam_id' => '',
                'match_guestteam_id' => '',
                'match_status' => '',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, league_id?: int}
     */
    public function update(int $matchId, array $input): array
    {
        $item = MatchGame::query()->with('matchround')->find($matchId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Spiel nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['match_id' => $matchId]),
                'league_id' => 0,
            ];
        }

        $form = $this->normalizeInput($input + ['match_id' => $matchId]);
        $leagueId = $this->leagueIdForRound((int) ($form['match_round'] ?: 0))
            ?: (int) ($item->matchround?->matchround_league_id ?? 0);
        $errors = $this->validate($form, false);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form, 'league_id' => $leagueId];
        }

        $item->match_round = (int) $form['match_round'];
        $item->match_date = $form['match_date'].' 00:00:00';
        $item->match_hometeam_id = (int) $form['match_hometeam_id'];
        $item->match_guestteam_id = (int) $form['match_guestteam_id'];
        $item->match_status = $form['match_status'];
        $item->save();

        return [
            'ok' => true,
            'message' => 'Spiel erfolgreich aktualisiert.',
            'league_id' => $leagueId,
        ];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, league_id?: int}
     */
    public function delete(int $matchId): array
    {
        $item = MatchGame::query()->with('matchround')->find($matchId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Spiel nicht gefunden! Falsche ID oder Seite neu geladen?']];
        }

        $leagueId = (int) ($item->matchround?->matchround_league_id ?? 0);

        if (Playerstats::query()->where('playerstats_match_id', $matchId)->exists()) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Es gibt zugehörige Spielerstatistiken.'],
                'league_id' => $leagueId,
            ];
        }

        $item->delete();

        return [
            'ok' => true,
            'message' => 'Spiel erfolgreich gelöscht.',
            'league_id' => $leagueId,
        ];
    }

    /**
     * Build editable Auto-Matches draft rows from a Spielplan JSON upload.
     *
     * @return array{
     *     ok: bool,
     *     errors?: list<string>,
     *     auto?: array{analyzed: bool, source_name: string, league_id: int, matches: list<array<string, mixed>>},
     *     message?: string,
     *     league_id?: int
     * }
     */
    public function analyzeMatchroundsFile(int $leagueId, ?UploadedFile $file): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'league_id' => 0];
        }

        if (! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.'], 'league_id' => $leagueId];
        }

        $parsed = $this->teams->parseMatchroundsUpload($file);
        if (! ($parsed['ok'] ?? false)) {
            return [
                'ok' => false,
                'errors' => $parsed['errors'] ?? ['Analyse fehlgeschlagen.'],
                'league_id' => $leagueId,
            ];
        }

        /** @var array<string, mixed> $data */
        $data = $parsed['data'];
        $sourceName = (string) $parsed['source_name'];

        $names = $this->teams->extractTeamNamesFromMatchrounds($data);
        if ($names === []) {
            return [
                'ok' => false,
                'errors' => ['In der JSON-Datei wurden keine Spiele gefunden (erwartet: spieltage[].spiele[].heim/gast).'],
                'league_id' => $leagueId,
            ];
        }

        $missingNames = $this->teams->missingTeamNames($names);
        if ($missingNames !== []) {
            return [
                'ok' => false,
                'errors' => [
                    'Es fehlen noch Teams in der Datenbank: '.implode(', ', $missingNames).'. Bitte zuerst unter Auto-Teams anlegen.',
                ],
                'league_id' => $leagueId,
            ];
        }

        $duplicateTeamErrors = $this->findTeamsWithMultipleMatchesPerSpieltag($data);
        if ($duplicateTeamErrors !== []) {
            return [
                'ok' => false,
                'errors' => $duplicateTeamErrors,
                'league_id' => $leagueId,
            ];
        }

        $rounds = $this->matchroundsForMapping($leagueId);
        if ($rounds === []) {
            return [
                'ok' => false,
                'errors' => ['Diese Liga hat noch keine Spielrunden. Bitte zuerst Spielrunden anlegen.'],
                'league_id' => $leagueId,
            ];
        }

        $teamIds = $this->teams->teamIdsByName();
        $draft = [];
        $skippedExisting = 0;
        $unmappedSpieltage = [];

        $spieltage = $data['spieltage'];
        if (! is_array($spieltage)) {
            return [
                'ok' => false,
                'errors' => ['JSON muss ein Array "spieltage" enthalten.'],
                'league_id' => $leagueId,
            ];
        }

        foreach ($spieltage as $round) {
            if (! is_array($round)) {
                continue;
            }

            $spieltag = (int) ($round['spieltag'] ?? 0);
            $spiele = $round['spiele'] ?? null;
            if (! is_array($spiele) || $spiele === []) {
                continue;
            }

            $matchroundId = $spieltag > 0 ? $this->resolveMatchroundId($spieltag, $rounds) : null;
            if ($matchroundId === null) {
                if ($spieltag > 0) {
                    $unmappedSpieltage[$spieltag] = true;
                }

                continue;
            }

            foreach ($spiele as $match) {
                if (! is_array($match)) {
                    continue;
                }

                $homeName = trim((string) ($match['heim'] ?? ''));
                $guestName = trim((string) ($match['gast'] ?? ''));
                $date = trim((string) ($match['datum'] ?? ''));
                if ($homeName === '' || $guestName === '' || $date === '') {
                    continue;
                }

                if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $date, $m)) {
                    $date = $m[1];
                }

                $homeId = $teamIds[mb_strtolower($homeName)] ?? 0;
                $guestId = $teamIds[mb_strtolower($guestName)] ?? 0;
                if ($homeId <= 0 || $guestId <= 0) {
                    continue;
                }

                $candidate = [
                    'match_round' => $matchroundId,
                    'match_date' => $date,
                    'match_hometeam_id' => $homeId,
                    'match_guestteam_id' => $guestId,
                    'match_status' => '',
                ];
                if ($this->identicalMatchExists($candidate)) {
                    $skippedExisting++;

                    continue;
                }

                $draft[] = $candidate + [
                    'home_name' => $homeName,
                    'guest_name' => $guestName,
                    'spieltag' => $spieltag,
                ];
            }
        }

        if ($unmappedSpieltage !== []) {
            $list = implode(', ', array_keys($unmappedSpieltage));

            return [
                'ok' => false,
                'errors' => [
                    'Für folgende Spieltage fehlt eine passende Spielrunde in dieser Liga: '.$list.'.',
                ],
                'league_id' => $leagueId,
            ];
        }

        if ($draft === [] && $skippedExisting === 0) {
            return [
                'ok' => false,
                'errors' => ['In der JSON-Datei wurden keine gültigen Spiele gefunden.'],
                'league_id' => $leagueId,
            ];
        }

        $parts = [];
        if (count($draft) === 1) {
            $parts[] = '1 Spiel bereit zum Anlegen';
        } elseif (count($draft) > 1) {
            $parts[] = count($draft).' Spiele bereit zum Anlegen';
        }
        if ($skippedExisting === 1) {
            $parts[] = '1 bereits vorhanden und ausgeblendet';
        } elseif ($skippedExisting > 1) {
            $parts[] = $skippedExisting.' bereits vorhanden und ausgeblendet';
        }

        return [
            'ok' => true,
            'league_id' => $leagueId,
            'auto' => [
                'analyzed' => true,
                'source_name' => $sourceName,
                'league_id' => $leagueId,
                'matches' => $draft,
            ],
            'message' => implode(', ', $parts).'.',
        ];
    }

    /**
     * Create Auto-Matches draft rows via the same create() path as manual inserts.
     * Identical existing matches are skipped; other validation errors fail those rows.
     *
     * @param  list<array<string, mixed>>|array<int, array<string, mixed>>  $matches
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     league_id?: int,
     *     auto?: array{analyzed: bool, source_name: string, league_id: int, matches: list<array<string, mixed>>}
     * }
     */
    public function createMatchesFromDraft(array $matches, int $leagueId, string $sourceName = ''): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'league_id' => 0];
        }

        $drafts = [];
        foreach (array_values($matches) as $row) {
            if (! is_array($row)) {
                continue;
            }

            $form = $this->normalizeInput($row);
            $drafts[] = [
                'match_round' => $form['match_round'],
                'match_date' => $form['match_date'],
                'match_hometeam_id' => $form['match_hometeam_id'],
                'match_guestteam_id' => $form['match_guestteam_id'],
                'match_status' => $form['match_status'],
                'home_name' => (string) ($row['home_name'] ?? ''),
                'guest_name' => (string) ($row['guest_name'] ?? ''),
                'spieltag' => (int) ($row['spieltag'] ?? 0),
            ];
        }

        if ($drafts === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spiele zum Anlegen.'],
                'league_id' => $leagueId,
                'auto' => [
                    'analyzed' => true,
                    'source_name' => $sourceName,
                    'league_id' => $leagueId,
                    'matches' => [],
                ],
            ];
        }

        $created = 0;
        $skipped = 0;
        $errors = [];
        $failedDrafts = [];

        foreach ($drafts as $index => $row) {
            $result = $this->create([
                'match_round' => $row['match_round'],
                'match_date' => $row['match_date'],
                'match_hometeam_id' => $row['match_hometeam_id'],
                'match_guestteam_id' => $row['match_guestteam_id'],
                'match_status' => $row['match_status'],
            ]);

            if ($result['ok'] ?? false) {
                $created++;

                continue;
            }

            $rowErrors = $result['errors'] ?? ['Anlegen fehlgeschlagen.'];
            if ($this->isOnlyIdenticalMatchError($rowErrors)) {
                $skipped++;

                continue;
            }

            $label = 'Zeile '.($index + 1);
            $home = (string) ($row['home_name'] ?? '');
            $guest = (string) ($row['guest_name'] ?? '');
            if ($home !== '' && $guest !== '') {
                $label = $home.' : '.$guest;
            }
            $errors[] = $label.': '.implode(' ', $rowErrors);
            $failedDrafts[] = $row;
        }

        if ($errors !== []) {
            return [
                'ok' => false,
                'errors' => $errors,
                'league_id' => $leagueId,
                'auto' => [
                    'analyzed' => true,
                    'source_name' => $sourceName,
                    'league_id' => $leagueId,
                    'matches' => $failedDrafts,
                ],
            ];
        }

        $parts = [];
        if ($created === 1) {
            $parts[] = '1 Spiel hinzugefügt';
        } elseif ($created > 1) {
            $parts[] = $created.' Spiele hinzugefügt';
        }
        if ($skipped === 1) {
            $parts[] = '1 bereits vorhanden und übersprungen';
        } elseif ($skipped > 1) {
            $parts[] = $skipped.' bereits vorhanden und übersprungen';
        }
        if ($parts === []) {
            $parts[] = 'Keine Spiele geändert';
        }

        return [
            'ok' => true,
            'message' => implode(', ', $parts).'.',
            'league_id' => $leagueId,
        ];
    }

    /**
     * @return list<array{league_id: int, league_title: string, league_archive: int}>
     */
    private function leagueOptions(): array
    {
        return League::query()
            ->orderBy('league_archive')
            ->orderBy('league_title')
            ->get(['league_id', 'league_title', 'league_archive'])
            ->map(fn (League $league) => [
                'league_id' => (int) $league->league_id,
                'league_title' => (string) $league->league_title,
                'league_archive' => (int) (bool) $league->league_archive,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array{league_id: int, league_title: string, league_archive: int}>  $leagues
     */
    private function resolveSelectedLeagueId(int $selectedLeagueId, array $leagues): int
    {
        if ($selectedLeagueId <= 0) {
            return 0;
        }

        foreach ($leagues as $league) {
            if ($league['league_id'] === $selectedLeagueId) {
                return $selectedLeagueId;
            }
        }

        return League::query()->whereKey($selectedLeagueId)->exists() ? $selectedLeagueId : 0;
    }

    /**
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    private function matchroundOptions(int $leagueId): array
    {
        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderByDesc('matchround_startdate')
            ->get(['matchround_id', 'matchround_title'])
            ->map(fn (Matchround $round) => [
                'matchround_id' => (int) $round->matchround_id,
                'matchround_title' => (string) $round->matchround_title,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{team_id: int, team_label: string}>
     */
    private function teamOptions(): array
    {
        return Team::query()
            ->orderBy('team_name')
            ->get(['team_id', 'team_name', 'team_nationality'])
            ->map(function (Team $team) {
                $name = (string) $team->team_name;
                $nat = trim((string) ($team->team_nationality ?? ''));

                return [
                    'team_id' => (int) $team->team_id,
                    'team_label' => $nat !== '' ? $name.' ('.$nat.')' : $name,
                    'team_nationality' => strtolower($nat),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listItems(int $leagueId): array
    {
        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->all();

        if ($roundIds === []) {
            return [];
        }

        return MatchGame::query()
            ->with(['homeTeam', 'guestTeam', 'matchround'])
            ->whereIn('match_round', $roundIds)
            ->orderByDesc('match_date')
            ->orderByDesc('match_id')
            ->get()
            ->map(function (MatchGame $item) {
                $date = strtotime((string) $item->match_date) ?: 0;
                $homeNat = (string) ($item->homeTeam?->team_nationality ?? '');
                $guestNat = (string) ($item->guestTeam?->team_nationality ?? '');
                $status = trim((string) ($item->match_status ?? ''));

                return [
                    'match_id' => (int) $item->match_id,
                    'match_date' => $date ? date('j.n.Y', $date) : '',
                    'match_round_title' => (string) ($item->matchround?->matchround_title ?? ''),
                    'home_name' => (string) ($item->homeTeam?->team_name ?? ''),
                    'guest_name' => (string) ($item->guestTeam?->team_name ?? ''),
                    'home_flag_html' => $homeNat !== '' ? Flag::html($homeNat) : '',
                    'guest_flag_html' => $guestNat !== '' ? Flag::html($guestNat) : '',
                    'home_flag_url' => $homeNat !== '' ? Flag::imageUrl($homeNat) : null,
                    'guest_flag_url' => $guestNat !== '' ? Flag::imageUrl($guestNat) : null,
                    'match_status' => $status,
                    'status_ok' => $status === '',
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeInput(array $input): array
    {
        $date = trim((string) ($input['match_date'] ?? ''));
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $date, $m)) {
            $date = $m[1];
        }

        return [
            'match_id' => (string) ($input['match_id'] ?? ''),
            'match_round' => $this->nullableInt($input['match_round'] ?? null),
            'match_date' => $date,
            'match_hometeam_id' => $this->nullableInt($input['match_hometeam_id'] ?? null),
            'match_guestteam_id' => $this->nullableInt($input['match_guestteam_id'] ?? null),
            'match_status' => trim((string) ($input['match_status'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validate(array $form, bool $isCreate): array
    {
        $errors = [];

        if (
            $form['match_round'] === null
            || $form['match_date'] === ''
            || $form['match_hometeam_id'] === null
            || $form['match_guestteam_id'] === null
        ) {
            $errors[] = 'Bitte alle mit * markierten Felder ausfüllen.';
        }

        if ($form['match_date'] !== '') {
            $dt = DateTimeImmutable::createFromFormat('Y-m-d', $form['match_date']);
            if (! $dt || $dt->format('Y-m-d') !== $form['match_date']) {
                $errors[] = 'Das Datum ist ungültig.';
            }
        }

        if (
            $form['match_hometeam_id'] !== null
            && $form['match_guestteam_id'] !== null
            && (int) $form['match_hometeam_id'] === (int) $form['match_guestteam_id']
        ) {
            $errors[] = 'Heim- und Gastteam müssen unterschiedlich sein.';
        }

        if (
            $form['match_round'] !== null
            && ! Matchround::query()->whereKey((int) $form['match_round'])->exists()
        ) {
            $errors[] = 'Spielrunde nicht gefunden.';
        }

        if ($errors === [] && $form['match_round'] !== null) {
            if ($isCreate && $this->identicalMatchExists($form)) {
                $errors[] = self::ERROR_IDENTICAL_MATCH;
            } else {
                $ignoreMatchId = null;
                if (! $isCreate) {
                    $matchId = (int) ($form['match_id'] ?: 0);
                    $ignoreMatchId = $matchId > 0 ? $matchId : null;
                }
                $errors = array_merge($errors, $this->teamsAlreadyInRoundErrors($form, $ignoreMatchId));
            }
        }

        return $errors;
    }

    private function leagueIdForRound(int $matchroundId): int
    {
        if ($matchroundId <= 0) {
            return 0;
        }

        return (int) (Matchround::query()->whereKey($matchroundId)->value('matchround_league_id') ?? 0);
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    private function matchroundsForMapping(int $leagueId): array
    {
        return Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->orderBy('matchround_startdate')
            ->orderBy('matchround_id')
            ->get(['matchround_id', 'matchround_title'])
            ->map(fn (Matchround $round) => [
                'matchround_id' => (int) $round->matchround_id,
                'matchround_title' => (string) $round->matchround_title,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array{matchround_id: int, matchround_title: string}>  $rounds
     */
    private function resolveMatchroundId(int $spieltag, array $rounds): ?int
    {
        foreach ($rounds as $round) {
            if ($this->titleMatchesSpieltag($round['matchround_title'], $spieltag)) {
                return $round['matchround_id'];
            }
        }

        $index = $spieltag - 1;
        if (isset($rounds[$index])) {
            return $rounds[$index]['matchround_id'];
        }

        return null;
    }

    private function titleMatchesSpieltag(string $title, int $spieltag): bool
    {
        $title = trim($title);
        if ($title === (string) $spieltag) {
            return true;
        }

        if (preg_match('/^(\d+)\b/u', $title, $matches) === 1 && (int) $matches[1] === $spieltag) {
            return true;
        }

        if (
            preg_match('/\b(?:spieltag|runde)\s*(\d+)\b/ui', $title, $matches) === 1
            && (int) $matches[1] === $spieltag
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function identicalMatchExists(array $form): bool
    {
        if (
            $form['match_round'] === null
            || $form['match_date'] === ''
            || $form['match_hometeam_id'] === null
            || $form['match_guestteam_id'] === null
        ) {
            return false;
        }

        return MatchGame::query()
            ->where('match_round', (int) $form['match_round'])
            ->where('match_date', $form['match_date'].' 00:00:00')
            ->where('match_hometeam_id', (int) $form['match_hometeam_id'])
            ->where('match_guestteam_id', (int) $form['match_guestteam_id'])
            ->exists();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<string>
     */
    private function findTeamsWithMultipleMatchesPerSpieltag(array $data): array
    {
        $spieltage = $data['spieltage'] ?? null;
        if (! is_array($spieltage)) {
            return [];
        }

        $errors = [];
        foreach ($spieltage as $round) {
            if (! is_array($round)) {
                continue;
            }

            $spieltag = (int) ($round['spieltag'] ?? 0);
            $spiele = $round['spiele'] ?? null;
            if (! is_array($spiele)) {
                continue;
            }

            /** @var array<string, string> $seen */
            $seen = [];
            /** @var array<string, string> $duplicates */
            $duplicates = [];

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
                    if (isset($seen[$key])) {
                        $duplicates[$key] = $seen[$key];
                    } else {
                        $seen[$key] = $name;
                    }
                }
            }

            if ($duplicates === []) {
                continue;
            }

            $label = $spieltag > 0 ? 'Spieltag '.$spieltag : 'einem Spieltag';
            $errors[] = 'In '.$label.' kommt ein Team mehrfach vor: '.implode(', ', array_values($duplicates)).'.';
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function teamsAlreadyInRoundErrors(array $form, ?int $ignoreMatchId): array
    {
        $roundId = (int) ($form['match_round'] ?? 0);
        $homeId = (int) ($form['match_hometeam_id'] ?? 0);
        $guestId = (int) ($form['match_guestteam_id'] ?? 0);
        if ($roundId <= 0) {
            return [];
        }

        $errors = [];
        foreach ([$homeId, $guestId] as $teamId) {
            if ($teamId <= 0) {
                continue;
            }

            $query = MatchGame::query()
                ->where('match_round', $roundId)
                ->where(function ($builder) use ($teamId): void {
                    $builder
                        ->where('match_hometeam_id', $teamId)
                        ->orWhere('match_guestteam_id', $teamId);
                });

            if ($ignoreMatchId !== null && $ignoreMatchId > 0) {
                $query->where('match_id', '!=', $ignoreMatchId);
            }

            if ($query->exists()) {
                $name = (string) (Team::query()->whereKey($teamId)->value('team_name') ?? '');
                $label = $name !== '' ? $name : '#'.$teamId;
                $errors[] = 'Team '.$label.' ist in dieser Spielrunde bereits in einem anderen Spiel eingetragen.';
            }
        }

        return array_values(array_unique($errors));
    }

    /**
     * @param  list<string>  $errors
     */
    private function isOnlyIdenticalMatchError(array $errors): bool
    {
        return $errors === [self::ERROR_IDENTICAL_MATCH];
    }
}
