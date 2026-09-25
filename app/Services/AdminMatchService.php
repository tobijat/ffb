<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Team;
use App\Support\Flag;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class AdminMatchService
{
    private const ERROR_IDENTICAL_MATCH = 'Ein Spiel mit dieser Runde, diesem Datum und diesen Teams existiert bereits.';

    public function __construct(
        private readonly AdminCenterService $adminCenter,
        private readonly AdminTeamService $teams,
        private readonly UefaCompApiClient $uefaClient = new UefaCompApiClient,
    ) {}

    public function defaultLeagueId(int $userId): int
    {
        return $this->adminCenter->selectedLeagueId($userId);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  array<string, mixed>|null  $auto
     * @param  array<string, mixed>|null  $autoUefa
     * @return array<string, mixed>
     */
    public function pagePayload(
        int $userId,
        int $selectedLeagueId,
        ?array $form = null,
        string $mode = 'create',
        string $tab = 'manual',
        ?array $auto = null,
        ?array $autoUefa = null,
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

        $resolvedTab = match ($tab) {
            'auto' => 'auto',
            'auto-uefa' => 'auto-uefa',
            default => 'manual',
        };

        $form = $form ?? $this->emptyForm();

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
            'leagues' => $leagues,
            'selected_league_id' => $selectedLeagueId,
            'selected_league_title' => $selectedTitle,
            'uefa_competition_identifier' => $uefaIdentifier,
            'matchrounds' => $selectedLeagueId > 0 ? $this->matchroundOptions($selectedLeagueId) : [],
            'teams' => $selectedLeagueId > 0 ? $this->teamOptions() : [],
            'items' => $selectedLeagueId > 0 && $resolvedTab === 'manual' ? $this->listItems($selectedLeagueId) : [],
            'form' => $form,
            'mode' => $mode === 'update' ? 'update' : 'create',
            'tab' => $resolvedTab,
            'auto' => $auto ?? $this->emptyAutoState(),
            'auto_uefa' => $autoUefa ?? $this->emptyAutoUefaState(),
            'matchplan_files' => $resolvedTab === 'auto' ? $this->teams->matchplanJsonOptions() : [],
        ];
    }

    /**
     * @return array{analyzed: bool, source_name: string, league_id: int, present: list<array<string, mixed>>, matches: list<array<string, mixed>>}
     */
    public function emptyAutoState(): array
    {
        return [
            'analyzed' => false,
            'source_name' => '',
            'league_id' => 0,
            'present' => [],
            'matches' => [],
        ];
    }

    /**
     * @return array{analyzed: bool, source_name: string, league_id: int, rows: list<array<string, mixed>>}
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
     * Build editable Auto-Matches draft rows from a JSON file in public/data/match.
     *
     * @return array{
     *     ok: bool,
     *     errors?: list<string>,
     *     auto?: array{analyzed: bool, source_name: string, league_id: int, present: list<array<string, mixed>>, matches: list<array<string, mixed>>},
     *     message?: string,
     *     league_id?: int
     * }
     */
    public function analyzeMatchroundsFile(int $leagueId, ?string $storedFileName): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'league_id' => 0];
        }

        if (! League::query()->whereKey($leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.'], 'league_id' => $leagueId];
        }

        $parsed = $this->teams->parseMatchroundsStoredFile($storedFileName);
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
        $present = [];
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
                $existingId = $this->identicalMatchId($candidate);
                if ($existingId !== null) {
                    $present[] = [
                        'match_id' => $existingId,
                        'match_round' => $matchroundId,
                        'match_date' => $date,
                        'match_hometeam_id' => $homeId,
                        'match_guestteam_id' => $guestId,
                        'home_name' => $homeName,
                        'guest_name' => $guestName,
                        'spieltag' => $spieltag,
                    ];

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

        if ($draft === [] && $present === []) {
            return [
                'ok' => false,
                'errors' => ['In der JSON-Datei wurden keine gültigen Spiele gefunden.'],
                'league_id' => $leagueId,
            ];
        }

        return [
            'ok' => true,
            'league_id' => $leagueId,
            'auto' => [
                'analyzed' => true,
                'source_name' => $sourceName,
                'league_id' => $leagueId,
                'present' => $present,
                'matches' => $draft,
            ],
            'message' => count($draft).' neue Spiele, '.count($present).' bereits vorhanden.',
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
     *     auto?: array{analyzed: bool, source_name: string, league_id: int, present: list<array<string, mixed>>, matches: list<array<string, mixed>>}
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
                    'present' => [],
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
                    'present' => [],
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
     * Build Auto-Matches (UEFA) rows from the league competition identifier.
     *
     * @return array{
     *     ok: bool,
     *     errors?: list<string>,
     *     auto_uefa?: array{analyzed: bool, source_name: string, league_id: int, rows: list<array<string, mixed>>},
     *     message?: string,
     *     league_id?: int
     * }
     */
    public function analyzeUefaMatches(int $leagueId): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'league_id' => 0];
        }

        $league = League::query()->find($leagueId);
        if (! $league) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.'], 'league_id' => $leagueId];
        }

        $identifier = trim((string) ($league->league_uefa_competition_identifier ?? ''));
        if ($identifier === '') {
            return [
                'ok' => false,
                'errors' => ['Für diese Liga ist kein UEFA-Competition-Identifier hinterlegt (Admin → Ligen).'],
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

        try {
            $api = UefaCompetitionApi::fromIdentifier($identifier, $this->uefaClient);
            $uefaMatches = $api->matches();
        } catch (InvalidArgumentException $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()], 'league_id' => $leagueId];
        } catch (Throwable $e) {
            Log::warning('UEFA auto-matches analyze failed', [
                'league_id' => $leagueId,
                'message' => $e->getMessage(),
            ]);

            return [
                'ok' => false,
                'errors' => ['UEFA-Spiele konnten nicht geladen werden: '.$e->getMessage()],
                'league_id' => $leagueId,
            ];
        }

        if ($uefaMatches === []) {
            return [
                'ok' => false,
                'errors' => ['Für '.$identifier.' wurden keine UEFA-Spiele gefunden.'],
                'league_id' => $leagueId,
            ];
        }

        /** @var array<string, Team> $teamsByUefaId */
        $teamsByUefaId = [];
        foreach (
            Team::query()
                ->where('team_uefa_id', '!=', '')
                ->get(['team_id', 'team_name', 'team_uefa_id']) as $team
        ) {
            $uefaId = trim((string) ($team->team_uefa_id ?? ''));
            if ($uefaId !== '' && ! isset($teamsByUefaId[$uefaId])) {
                $teamsByUefaId[$uefaId] = $team;
            }
        }

        $leagueMatches = $this->leagueMatchesIndexedByTeamsAndDate($leagueId);

        $rows = [];
        $matched = 0;
        $unmapped = 0;
        $missing = 0;

        foreach ($uefaMatches as $uefa) {
            $homeTeam = $teamsByUefaId[$uefa['home_uefa_id']] ?? null;
            $awayTeam = $teamsByUefaId[$uefa['away_uefa_id']] ?? null;
            $homeId = $homeTeam !== null ? (int) $homeTeam->team_id : 0;
            $guestId = $awayTeam !== null ? (int) $awayTeam->team_id : 0;
            $homeName = $homeTeam !== null ? (string) $homeTeam->team_name : $uefa['home_name_de'];
            $guestName = $awayTeam !== null ? (string) $awayTeam->team_name : $uefa['away_name_de'];
            $date = $uefa['date'];
            $matchday = (int) $uefa['matchday'];
            $suggestedRound = $matchday > 0 ? ($this->resolveMatchroundId($matchday, $rounds) ?? 0) : 0;

            if ($homeId <= 0 || $guestId <= 0) {
                $unmapped++;
                $rows[] = [
                    'row_status' => 'unmapped',
                    'match_id' => 0,
                    'match_round' => $suggestedRound,
                    'match_date' => $date,
                    'match_hometeam_id' => $homeId,
                    'match_guestteam_id' => $guestId,
                    'match_status' => '',
                    'home_name' => $homeName,
                    'guest_name' => $guestName,
                    'uefa_match_id' => $uefa['uefa_match_id'],
                    'home_uefa_id' => $uefa['home_uefa_id'],
                    'away_uefa_id' => $uefa['away_uefa_id'],
                    'matchday' => $matchday,
                ];

                continue;
            }

            $existing = $leagueMatches[$this->teamsDateKey($homeId, $guestId, $date)] ?? null;
            if ($existing !== null) {
                $matched++;
                $rows[] = [
                    'row_status' => 'matched',
                    'match_id' => (int) $existing->match_id,
                    'match_round' => (int) $existing->match_round,
                    'match_date' => $date,
                    'match_hometeam_id' => $homeId,
                    'match_guestteam_id' => $guestId,
                    'match_status' => (string) ($existing->match_status ?? ''),
                    'home_name' => $homeName,
                    'guest_name' => $guestName,
                    'uefa_match_id' => $uefa['uefa_match_id'],
                    'home_uefa_id' => $uefa['home_uefa_id'],
                    'away_uefa_id' => $uefa['away_uefa_id'],
                    'matchday' => $matchday,
                ];

                continue;
            }

            $missing++;
            $rows[] = [
                'row_status' => 'new',
                'match_id' => 0,
                'match_round' => $suggestedRound,
                'match_date' => $date,
                'match_hometeam_id' => $homeId,
                'match_guestteam_id' => $guestId,
                'match_status' => '',
                'home_name' => $homeName,
                'guest_name' => $guestName,
                'uefa_match_id' => $uefa['uefa_match_id'],
                'home_uefa_id' => $uefa['home_uefa_id'],
                'away_uefa_id' => $uefa['away_uefa_id'],
                'matchday' => $matchday,
            ];
        }

        return [
            'ok' => true,
            'league_id' => $leagueId,
            'auto_uefa' => [
                'analyzed' => true,
                'source_name' => $api->identifier(),
                'league_id' => $leagueId,
                'rows' => $rows,
            ],
            'message' => count($rows).' UEFA-Spiele geprüft, '.$matched.' vorhanden, '.$missing.' neu'
                .($unmapped > 0 ? ', '.$unmapped.' ohne Team-Zuordnung (team_uefa_id).' : '.'),
        ];
    }

    /**
     * Update matched / create new Auto-Matches (UEFA) rows.
     *
     * @param  list<array<string, mixed>>|array<int, array<string, mixed>>  $rows
     * @return array{
     *     ok: bool,
     *     message?: string,
     *     errors?: list<string>,
     *     league_id?: int,
     *     auto_uefa?: array{analyzed: bool, source_name: string, league_id: int, rows: list<array<string, mixed>>}
     * }
     */
    public function saveUefaMatches(array $rows, int $leagueId, string $sourceName = ''): array
    {
        if ($leagueId <= 0) {
            return ['ok' => false, 'errors' => ['Bitte zuerst eine Liga wählen.'], 'league_id' => 0];
        }

        $normalized = [];
        foreach (array_values($rows) as $row) {
            if (! is_array($row)) {
                continue;
            }

            $form = $this->normalizeInput($row);
            $matchId = (int) ($row['match_id'] ?? ($form['match_id'] ?: 0));
            $rowStatus = (string) ($row['row_status'] ?? ($matchId > 0 ? 'matched' : 'new'));
            if ($rowStatus === 'unmapped') {
                continue;
            }

            $normalized[] = [
                'match_id' => $matchId,
                'row_status' => $matchId > 0 ? 'matched' : 'new',
                'match_round' => $form['match_round'],
                'match_date' => $form['match_date'],
                'match_hometeam_id' => $form['match_hometeam_id'],
                'match_guestteam_id' => $form['match_guestteam_id'],
                'match_status' => $form['match_status'],
                'home_name' => (string) ($row['home_name'] ?? ''),
                'guest_name' => (string) ($row['guest_name'] ?? ''),
                'uefa_match_id' => (string) ($row['uefa_match_id'] ?? ''),
                'home_uefa_id' => (string) ($row['home_uefa_id'] ?? ''),
                'away_uefa_id' => (string) ($row['away_uefa_id'] ?? ''),
                'matchday' => (int) ($row['matchday'] ?? 0),
            ];
        }

        $autoState = [
            'analyzed' => true,
            'source_name' => $sourceName,
            'league_id' => $leagueId,
            'rows' => $normalized,
        ];

        if ($normalized === []) {
            return [
                'ok' => false,
                'errors' => ['Keine Spiele zum Speichern.'],
                'league_id' => $leagueId,
                'auto_uefa' => $autoState,
            ];
        }

        $errors = [];
        foreach ($normalized as $index => $row) {
            $label = $this->uefaRowLabel($row, $index);
            if ((int) ($row['match_round'] ?? 0) <= 0) {
                $errors[] = $label.': Bitte eine Spielrunde wählen.';
            }
        }

        if ($errors !== []) {
            return [
                'ok' => false,
                'errors' => $errors,
                'league_id' => $leagueId,
                'auto_uefa' => $autoState,
            ];
        }

        $created = 0;
        $updated = 0;
        $failedRows = [];

        try {
            DB::transaction(function () use ($normalized, &$created, &$updated, &$errors, &$failedRows): void {
                foreach ($normalized as $index => $row) {
                    $label = $this->uefaRowLabel($row, $index);

                    if ($row['match_id'] > 0) {
                        $result = $this->update((int) $row['match_id'], [
                            'match_id' => $row['match_id'],
                            'match_round' => $row['match_round'],
                            'match_date' => $row['match_date'],
                            'match_hometeam_id' => $row['match_hometeam_id'],
                            'match_guestteam_id' => $row['match_guestteam_id'],
                            'match_status' => $row['match_status'],
                        ]);
                        if ($result['ok'] ?? false) {
                            $updated++;

                            continue;
                        }
                        $errors[] = $label.': '.implode(' ', $result['errors'] ?? ['Aktualisieren fehlgeschlagen.']);
                        $failedRows[] = $row;
                        throw new InvalidArgumentException('UEFA-Match-Speichern abgebrochen.');
                    }

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
                        $updated++;

                        continue;
                    }

                    $errors[] = $label.': '.implode(' ', $rowErrors);
                    $failedRows[] = $row;
                    throw new InvalidArgumentException('UEFA-Match-Speichern abgebrochen.');
                }
            });
        } catch (InvalidArgumentException) {
            return [
                'ok' => false,
                'errors' => $errors !== [] ? $errors : ['Speichern fehlgeschlagen.'],
                'league_id' => $leagueId,
                'auto_uefa' => [
                    'analyzed' => true,
                    'source_name' => $sourceName,
                    'league_id' => $leagueId,
                    'rows' => $failedRows !== [] ? $failedRows : $normalized,
                ],
            ];
        }

        $parts = [];
        if ($updated === 1) {
            $parts[] = '1 Spiel aktualisiert';
        } elseif ($updated > 1) {
            $parts[] = $updated.' Spiele aktualisiert';
        }
        if ($created === 1) {
            $parts[] = '1 Spiel angelegt';
        } elseif ($created > 1) {
            $parts[] = $created.' Spiele angelegt';
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
     * @param  array<string, mixed>  $row
     */
    private function uefaRowLabel(array $row, int $index): string
    {
        $home = (string) ($row['home_name'] ?? '');
        $guest = (string) ($row['guest_name'] ?? '');
        if ($home !== '' && $guest !== '') {
            return $home.' : '.$guest;
        }

        return 'Zeile '.($index + 1);
    }

    /**
     * @return array<string, MatchGame>
     */
    private function leagueMatchesIndexedByTeamsAndDate(int $leagueId): array
    {
        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->all();
        if ($roundIds === []) {
            return [];
        }

        $indexed = [];
        foreach (
            MatchGame::query()
                ->whereIn('match_round', $roundIds)
                ->get([
                    'match_id',
                    'match_round',
                    'match_date',
                    'match_hometeam_id',
                    'match_guestteam_id',
                    'match_status',
                ]) as $match
        ) {
            $dateTs = strtotime((string) $match->match_date);
            $date = $dateTs ? date('Y-m-d', $dateTs) : '';
            if ($date === '') {
                continue;
            }
            $key = $this->teamsDateKey(
                (int) $match->match_hometeam_id,
                (int) $match->match_guestteam_id,
                $date,
            );
            if (! isset($indexed[$key])) {
                $indexed[$key] = $match;
            }
        }

        return $indexed;
    }

    private function teamsDateKey(int $homeId, int $guestId, string $date): string
    {
        return $homeId.'|'.$guestId.'|'.$date;
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
        return $this->identicalMatchId($form) !== null;
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function identicalMatchId(array $form): ?int
    {
        if (
            $form['match_round'] === null
            || $form['match_date'] === ''
            || $form['match_hometeam_id'] === null
            || $form['match_guestteam_id'] === null
        ) {
            return null;
        }

        $matchId = MatchGame::query()
            ->where('match_round', (int) $form['match_round'])
            ->where('match_date', $form['match_date'].' 00:00:00')
            ->where('match_hometeam_id', (int) $form['match_hometeam_id'])
            ->where('match_guestteam_id', (int) $form['match_guestteam_id'])
            ->value('match_id');

        return $matchId !== null ? (int) $matchId : null;
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
