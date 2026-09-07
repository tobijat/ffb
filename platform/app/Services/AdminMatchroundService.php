<?php

namespace App\Services;

use App\Models\Game;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Userteam;
use DateTimeImmutable;

class AdminMatchroundService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    /**
     * League already chosen elsewhere (legacy admin session, else player selected game).
     */
    public function defaultGameId(int $userId): int
    {
        return $this->adminCenter->selectedGameId($userId);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array<string, mixed>>,
     *     games: list<array{game_id: int, game_title: string, game_archive: int}>,
     *     selected_game_id: int,
     *     selected_game_title: string|null,
     *     items: list<array<string, mixed>>,
     *     form: array<string, mixed>,
     *     mode: string
     * }
     */
    public function pagePayload(int $userId, int $selectedGameId, ?array $form = null, string $mode = 'create'): array
    {
        $shell = $this->adminCenter->pagePayload($userId);
        $games = $this->gameOptions();
        $selectedGameId = $this->resolveSelectedGameId($selectedGameId, $games);
        $selectedTitle = null;
        foreach ($games as $game) {
            if ($game['game_id'] === $selectedGameId) {
                $selectedTitle = $game['game_title'];
                break;
            }
        }

        $form = $form ?? $this->emptyForm($selectedGameId);
        $form['matchround_game_id'] = $selectedGameId > 0
            ? $selectedGameId
            : (int) ($form['matchround_game_id'] ?? 0);

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'games' => $games,
            'selected_game_id' => $selectedGameId,
            'selected_game_title' => $selectedTitle,
            'items' => $selectedGameId > 0 ? $this->listItems($selectedGameId) : [],
            'form' => $form,
            'mode' => $mode === 'update' ? 'update' : 'create',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(int $gameId = 0): array
    {
        return [
            'matchround_id' => '',
            'matchround_game_id' => $gameId,
            'matchround_title' => '',
            'matchround_status' => 1,
            'matchround_startdate' => '',
            'matchround_enddate' => '',
        ];
    }

    /**
     * @return array{form: array<string, mixed>, game_id: int}|null
     */
    public function formForEdit(int $matchroundId): ?array
    {
        $item = Matchround::query()->find($matchroundId);
        if (! $item) {
            return null;
        }

        return [
            'game_id' => (int) $item->matchround_game_id,
            'form' => [
                'matchround_id' => (int) $item->matchround_id,
                'matchround_game_id' => (int) $item->matchround_game_id,
                'matchround_title' => (string) $item->matchround_title,
                'matchround_status' => (int) $item->matchround_status,
                'matchround_startdate' => $this->toDatetimeLocalValue((string) $item->matchround_startdate),
                'matchround_enddate' => $this->toDatetimeLocalValue((string) $item->matchround_enddate),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, game_id?: int, next_form?: array<string, mixed>}
     */
    public function create(array $input): array
    {
        $form = $this->normalizeInput($input);
        $errors = $this->validate($form, true);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form, 'game_id' => (int) $form['matchround_game_id']];
        }

        $startDb = $this->toDbDateTime((string) $form['matchround_startdate']);
        $endDb = $this->toDbDateTime((string) $form['matchround_enddate']);

        Matchround::query()->create([
            'matchround_title' => $form['matchround_title'],
            'matchround_status' => (int) $form['matchround_status'],
            'matchround_game_id' => (int) $form['matchround_game_id'],
            'matchround_startdate' => $startDb,
            'matchround_enddate' => $endDb,
            'matchround_credits' => 0,
            'matchround_max_players_from_team' => 0,
        ]);

        return [
            'ok' => true,
            'message' => 'Spielrunde erfolgreich hinzugefügt.',
            'game_id' => (int) $form['matchround_game_id'],
            'next_form' => $this->nextFormAfterCreate($form, $startDb, $endDb),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, game_id?: int}
     */
    public function update(int $matchroundId, array $input): array
    {
        $item = Matchround::query()->find($matchroundId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Spielrunde nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['matchround_id' => $matchroundId]),
                'game_id' => (int) ($input['matchround_game_id'] ?? 0),
            ];
        }

        $form = $this->normalizeInput($input + [
            'matchround_id' => $matchroundId,
            'matchround_game_id' => (int) $item->matchround_game_id,
        ]);
        $errors = $this->validate($form, false);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form, 'game_id' => (int) $form['matchround_game_id']];
        }

        $item->matchround_title = $form['matchround_title'];
        $item->matchround_status = (int) $form['matchround_status'];
        $item->matchround_startdate = $this->toDbDateTime((string) $form['matchround_startdate']);
        $item->matchround_enddate = $this->toDbDateTime((string) $form['matchround_enddate']);
        $item->save();

        return [
            'ok' => true,
            'message' => 'Spielrunde erfolgreich aktualisiert.',
            'game_id' => (int) $item->matchround_game_id,
        ];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, game_id?: int}
     */
    public function delete(int $matchroundId): array
    {
        $item = Matchround::query()->find($matchroundId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Spielrunde nicht gefunden! Falsche ID oder Seite neu geladen?'],
            ];
        }

        $gameId = (int) $item->matchround_game_id;

        if (Playerstats::query()->where('playerstats_matchround_id', $matchroundId)->exists()) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Es gibt zugehörige Spielerstatistiken.'],
                'game_id' => $gameId,
            ];
        }

        if (Userteam::query()->where('userteam_matchround_id', $matchroundId)->exists()) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Es gibt zugehörige Userteams.'],
                'game_id' => $gameId,
            ];
        }

        if (MatchGame::query()->where('match_round', $matchroundId)->exists()) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Es gibt zugehörige Spiele.'],
                'game_id' => $gameId,
            ];
        }

        $item->delete();

        return [
            'ok' => true,
            'message' => 'Spielrunde erfolgreich gelöscht.',
            'game_id' => $gameId,
        ];
    }

    /**
     * @return list<array{game_id: int, game_title: string, game_archive: int}>
     */
    private function gameOptions(): array
    {
        return Game::query()
            ->orderBy('game_archive')
            ->orderBy('game_title')
            ->get(['game_id', 'game_title', 'game_archive'])
            ->map(fn (Game $game) => [
                'game_id' => (int) $game->game_id,
                'game_title' => (string) $game->game_title,
                'game_archive' => (int) (bool) $game->game_archive,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array{game_id: int, game_title: string, game_archive: int}>  $games
     */
    private function resolveSelectedGameId(int $selectedGameId, array $games): int
    {
        if ($selectedGameId <= 0) {
            return 0;
        }

        foreach ($games as $game) {
            if ($game['game_id'] === $selectedGameId) {
                return $selectedGameId;
            }
        }

        return Game::query()->whereKey($selectedGameId)->exists() ? $selectedGameId : 0;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listItems(int $gameId): array
    {
        return Matchround::query()
            ->where('matchround_game_id', $gameId)
            ->orderByDesc('matchround_startdate')
            ->orderByDesc('matchround_id')
            ->get()
            ->map(function (Matchround $item) {
                $start = strtotime((string) $item->matchround_startdate) ?: 0;
                $end = strtotime((string) $item->matchround_enddate) ?: 0;

                return [
                    'matchround_id' => (int) $item->matchround_id,
                    'matchround_title' => (string) $item->matchround_title,
                    'matchround_status' => (int) $item->matchround_status,
                    'matchround_startdate' => $start ? date('j.n.Y G:i', $start) : '',
                    'matchround_enddate' => $end ? date('j.n.Y G:i', $end) : '',
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
        return [
            'matchround_id' => (string) ($input['matchround_id'] ?? ''),
            'matchround_game_id' => (int) ($input['matchround_game_id'] ?? 0),
            'matchround_title' => trim((string) ($input['matchround_title'] ?? '')),
            'matchround_status' => (int) ($input['matchround_status'] ?? 1) === 0 ? 0 : 1,
            'matchround_startdate' => $this->normalizeDatetimeLocal((string) ($input['matchround_startdate'] ?? '')),
            'matchround_enddate' => $this->normalizeDatetimeLocal((string) ($input['matchround_enddate'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validate(array $form, bool $isCreate): array
    {
        $errors = [];

        if ((int) $form['matchround_game_id'] <= 0) {
            $errors[] = 'Bitte zuerst eine Liga auswählen.';
        }

        if (
            $form['matchround_title'] === ''
            || $form['matchround_startdate'] === ''
            || $form['matchround_enddate'] === ''
        ) {
            $errors[] = 'Bitte alle mit * markierten Felder ausfüllen.';
        }

        $start = $form['matchround_startdate'] !== ''
            ? $this->parseDatetimeLocal((string) $form['matchround_startdate'])
            : null;
        $end = $form['matchround_enddate'] !== ''
            ? $this->parseDatetimeLocal((string) $form['matchround_enddate'])
            : null;

        if ($form['matchround_startdate'] !== '' && $start === null) {
            $errors[] = 'Das Startdatum ist ungültig.';
        }

        if ($form['matchround_enddate'] !== '' && $end === null) {
            $errors[] = 'Das Enddatum ist ungültig.';
        }

        if ($start !== null && $end !== null && $end < $start) {
            $errors[] = 'Das Startdatum darf nicht nach dem Enddatum liegen.';
        }

        if ($isCreate && $errors === [] && $start !== null && $end !== null && (int) $form['matchround_game_id'] > 0) {
            $exists = Matchround::query()
                ->where('matchround_game_id', (int) $form['matchround_game_id'])
                ->where('matchround_startdate', $this->toDbDateTime((string) $form['matchround_startdate']))
                ->where('matchround_enddate', $this->toDbDateTime((string) $form['matchround_enddate']))
                ->exists();
            if ($exists) {
                $errors[] = 'Eine Spielrunde mit diesem Start- und Enddatum existiert bereits.';
            }
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $form
     * @return array<string, mixed>
     */
    private function nextFormAfterCreate(array $form, string $startDb, string $endDb): array
    {
        $start = new DateTimeImmutable($startDb);
        $end = new DateTimeImmutable($endDb);
        $durationSeconds = max(0, $end->getTimestamp() - $start->getTimestamp());
        $nextStart = $end;
        $nextEnd = $nextStart->modify('+'.$durationSeconds.' seconds');

        return [
            'matchround_id' => '',
            'matchround_game_id' => (int) $form['matchround_game_id'],
            'matchround_title' => $this->bumpTitle((string) $form['matchround_title']),
            'matchround_status' => (int) $form['matchround_status'],
            'matchround_startdate' => $nextStart->format('Y-m-d\TH:00'),
            'matchround_enddate' => $nextEnd->format('Y-m-d\TH:00'),
        ];
    }

    private function bumpTitle(string $title): string
    {
        if (preg_match('/^(.*?)(\d+)$/u', $title, $m)) {
            $next = (string) ((int) $m[2] + 1);

            return $m[1].str_pad($next, strlen($m[2]), '0', STR_PAD_LEFT);
        }

        return $title;
    }

    private function normalizeDatetimeLocal(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $parsed = $this->parseDatetimeLocal($value);
        if ($parsed === null) {
            return $value;
        }

        return $parsed->format('Y-m-d\TH:00');
    }

    private function parseDatetimeLocal(string $value): ?DateTimeImmutable
    {
        $value = trim(str_replace(' ', 'T', $value));
        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', 'Y-m-d H:i'];
        foreach ($formats as $format) {
            $dt = DateTimeImmutable::createFromFormat($format, $value);
            if ($dt instanceof DateTimeImmutable) {
                return $dt->setTime((int) $dt->format('G'), 0, 0);
            }
        }

        try {
            $dt = new DateTimeImmutable($value);

            return $dt->setTime((int) $dt->format('G'), 0, 0);
        } catch (\Exception) {
            return null;
        }
    }

    private function toDatetimeLocalValue(string $dbValue): string
    {
        $parsed = $this->parseDatetimeLocal($dbValue);
        if ($parsed === null) {
            return '';
        }

        return $parsed->format('Y-m-d\TH:00');
    }

    private function toDbDateTime(string $datetimeLocal): string
    {
        $parsed = $this->parseDatetimeLocal($datetimeLocal);
        if ($parsed === null) {
            return $datetimeLocal;
        }

        return $parsed->format('Y-m-d H:i:s');
    }
}
