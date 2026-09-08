<?php

namespace App\Services;

use App\Models\Game;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Playerstats;
use App\Models\Team;
use DateTimeImmutable;

class AdminMatchService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    public function defaultGameId(int $userId): int
    {
        return $this->adminCenter->selectedGameId($userId);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId, int $selectedGameId, ?array $form = null, string $mode = 'create'): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $games = $this->gameOptions();
        $selectedGameId = $this->resolveSelectedGameId($selectedGameId, $games);
        $selectedTitle = null;
        foreach ($games as $game) {
            if ($game['game_id'] === $selectedGameId) {
                $selectedTitle = $game['game_title'];
                break;
            }
        }

        $form = $form ?? $this->emptyForm();

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_game' => $shell['selected_game'],
            'games' => $games,
            'selected_game_id' => $selectedGameId,
            'selected_game_title' => $selectedTitle,
            'matchrounds' => $selectedGameId > 0 ? $this->matchroundOptions($selectedGameId) : [],
            'teams' => $selectedGameId > 0 ? $this->teamOptions() : [],
            'items' => $selectedGameId > 0 ? $this->listItems($selectedGameId) : [],
            'form' => $form,
            'mode' => $mode === 'update' ? 'update' : 'create',
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
     * @return array{form: array<string, mixed>, game_id: int}|null
     */
    public function formForEdit(int $matchId): ?array
    {
        $item = MatchGame::query()->with('matchround')->find($matchId);
        if (! $item) {
            return null;
        }

        $date = strtotime((string) $item->match_date);

        return [
            'game_id' => (int) ($item->matchround?->matchround_game_id ?? 0),
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
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, game_id?: int, next_form?: array<string, mixed>}
     */
    public function create(array $input): array
    {
        $form = $this->normalizeInput($input);
        $gameId = $this->gameIdForRound((int) ($form['match_round'] ?: 0));
        $errors = $this->validate($form, true);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form, 'game_id' => $gameId];
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
            'game_id' => $gameId,
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
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>, game_id?: int}
     */
    public function update(int $matchId, array $input): array
    {
        $item = MatchGame::query()->with('matchround')->find($matchId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Spiel nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['match_id' => $matchId]),
                'game_id' => 0,
            ];
        }

        $form = $this->normalizeInput($input + ['match_id' => $matchId]);
        $gameId = $this->gameIdForRound((int) ($form['match_round'] ?: 0))
            ?: (int) ($item->matchround?->matchround_game_id ?? 0);
        $errors = $this->validate($form, false);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form, 'game_id' => $gameId];
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
            'game_id' => $gameId,
        ];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>, game_id?: int}
     */
    public function delete(int $matchId): array
    {
        $item = MatchGame::query()->with('matchround')->find($matchId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Spiel nicht gefunden! Falsche ID oder Seite neu geladen?']];
        }

        $gameId = (int) ($item->matchround?->matchround_game_id ?? 0);

        if (Playerstats::query()->where('playerstats_match_id', $matchId)->exists()) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Es gibt zugehörige Spielerstatistiken.'],
                'game_id' => $gameId,
            ];
        }

        $item->delete();

        return [
            'ok' => true,
            'message' => 'Spiel erfolgreich gelöscht.',
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
     * @return list<array{matchround_id: int, matchround_title: string}>
     */
    private function matchroundOptions(int $gameId): array
    {
        return Matchround::query()
            ->where('matchround_game_id', $gameId)
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
    private function listItems(int $gameId): array
    {
        $roundIds = Matchround::query()
            ->where('matchround_game_id', $gameId)
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
                $homeNat = strtolower((string) ($item->homeTeam?->team_nationality ?? ''));
                $guestNat = strtolower((string) ($item->guestTeam?->team_nationality ?? ''));
                $status = trim((string) ($item->match_status ?? ''));

                return [
                    'match_id' => (int) $item->match_id,
                    'match_date' => $date ? date('j.n.Y', $date) : '',
                    'match_round_title' => (string) ($item->matchround?->matchround_title ?? ''),
                    'home_name' => (string) ($item->homeTeam?->team_name ?? ''),
                    'guest_name' => (string) ($item->guestTeam?->team_name ?? ''),
                    'home_flag_url' => $homeNat !== '' ? '/images/ffb/flags/'.$homeNat.'.gif' : null,
                    'guest_flag_url' => $guestNat !== '' ? '/images/ffb/flags/'.$guestNat.'.gif' : null,
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

        if ($isCreate && $errors === [] && $form['match_round'] !== null) {
            $exists = MatchGame::query()
                ->where('match_round', (int) $form['match_round'])
                ->where('match_date', $form['match_date'].' 00:00:00')
                ->where('match_hometeam_id', (int) $form['match_hometeam_id'])
                ->where('match_guestteam_id', (int) $form['match_guestteam_id'])
                ->exists();
            if ($exists) {
                $errors[] = 'Ein Spiel mit dieser Runde, diesem Datum und diesen Teams existiert bereits.';
            }
        }

        return $errors;
    }

    private function gameIdForRound(int $matchroundId): int
    {
        if ($matchroundId <= 0) {
            return 0;
        }

        return (int) (Matchround::query()->whereKey($matchroundId)->value('matchround_game_id') ?? 0);
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
