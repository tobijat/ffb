<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Userteam;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminPlayerService
{
    private const PER_PAGE = 50;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  array{q?: string, nationality?: string, page?: int}  $filters
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId, ?array $form = null, string $mode = 'create', array $filters = []): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $filters = $this->normalizeFilters($filters);

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_game' => $shell['selected_game'],
            'countries' => $this->countryOptions(),
            'filters' => $filters,
            'items' => $this->listItems($filters),
            'form' => $form ?? $this->emptyForm(),
            'mode' => $mode === 'update' ? 'update' : 'create',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(): array
    {
        return [
            'player_id' => '',
            'player_fname' => '',
            'player_lname' => '',
            'player_nationality' => '',
            'player_status' => 1,
            'player_status_description' => '',
            'player_foreign_id' => '',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function formForEdit(int $playerId): ?array
    {
        $item = Player::query()->find($playerId);
        if (! $item) {
            return null;
        }

        $desc = (string) ($item->player_status_description ?? '');
        if (strtoupper($desc) === 'NULL') {
            $desc = '';
        }

        return [
            'player_id' => (int) $item->player_id,
            'player_fname' => (string) $item->player_fname,
            'player_lname' => (string) $item->player_lname,
            'player_nationality' => strtoupper(trim((string) ($item->player_nationality ?? ''))),
            'player_status' => (int) $item->player_status ? 1 : 0,
            'player_status_description' => $desc,
            'player_foreign_id' => (string) ($item->player_foreign_id ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function create(array $input): array
    {
        $form = $this->normalizeInput($input);
        $errors = $this->validate($form, true);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        Player::query()->create([
            'player_foreign_id' => $form['player_foreign_id'],
            'player_fname' => $form['player_fname'],
            'player_lname' => $form['player_lname'],
            'player_nationality' => $form['player_nationality'],
            'player_status' => (int) $form['player_status'],
            'player_status_description' => $form['player_status_description'],
        ]);

        return ['ok' => true, 'message' => 'Spieler erfolgreich hinzugefügt.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function update(int $playerId, array $input): array
    {
        $item = Player::query()->find($playerId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Spieler nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['player_id' => $playerId]),
            ];
        }

        $form = $this->normalizeInput($input + ['player_id' => $playerId]);
        $errors = $this->validate($form, false);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        $item->player_foreign_id = $form['player_foreign_id'];
        $item->player_fname = $form['player_fname'];
        $item->player_lname = $form['player_lname'];
        $item->player_nationality = $form['player_nationality'];
        $item->player_status = (int) $form['player_status'];
        $item->player_status_description = $form['player_status_description'];
        $item->save();

        return ['ok' => true, 'message' => 'Spieler erfolgreich aktualisiert.'];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function delete(int $playerId): array
    {
        $item = Player::query()->find($playerId);
        if (! $item) {
            return ['ok' => false, 'errors' => ['Spieler nicht gefunden! Falsche ID oder Seite neu geladen?']];
        }

        if (Playerteam::query()->where('playerteam_player_id', $playerId)->exists()) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Spieler ist noch einem oder mehreren Teams zugeordnet.'],
            ];
        }

        if ($this->isUsedInUserteams($playerId)) {
            return [
                'ok' => false,
                'errors' => ['Löschen nicht möglich: Spieler ist in Userteams eingesetzt.'],
            ];
        }

        $item->delete();

        return ['ok' => true, 'message' => 'Spieler erfolgreich gelöscht.'];
    }

    /**
     * @param  array{q?: string, nationality?: string, page?: int}  $filters
     * @return array{q: string, nationality: string, page: int}
     */
    private function normalizeFilters(array $filters): array
    {
        return [
            'q' => trim((string) ($filters['q'] ?? '')),
            'nationality' => strtoupper(trim((string) ($filters['nationality'] ?? ''))),
            'page' => max(1, (int) ($filters['page'] ?? 1)),
        ];
    }

    /**
     * @param  array{q: string, nationality: string, page: int}  $filters
     */
    private function listItems(array $filters): LengthAwarePaginator
    {
        $countries = $this->countryOptions();

        $query = Player::query()->orderBy('player_lname')->orderBy('player_fname')->orderBy('player_id');

        if ($filters['q'] !== '') {
            $like = '%'.$filters['q'].'%';
            $query->where(function ($q) use ($like) {
                $q->where('player_fname', 'like', $like)
                    ->orWhere('player_lname', 'like', $like)
                    ->orWhereRaw("CONCAT(player_fname, ' ', player_lname) LIKE ?", [$like])
                    ->orWhereRaw("CONCAT(player_lname, ' ', player_fname) LIKE ?", [$like]);
            });
        }

        if ($filters['nationality'] !== '') {
            $query->whereRaw('UPPER(player_nationality) = ?', [$filters['nationality']]);
        }

        return $query
            ->paginate(self::PER_PAGE, ['*'], 'page', $filters['page'])
            ->withQueryString()
            ->through(function (Player $item) use ($countries) {
                $nat = strtoupper(trim((string) ($item->player_nationality ?? '')));
                $desc = (string) ($item->player_status_description ?? '');
                if (strtoupper($desc) === 'NULL') {
                    $desc = '';
                }

                return [
                    'player_id' => (int) $item->player_id,
                    'player_fname' => (string) $item->player_fname,
                    'player_lname' => (string) $item->player_lname,
                    'player_nationality' => $nat,
                    'player_nationality_label' => $nat !== '' ? ($countries[$nat] ?? $nat) : '',
                    'player_status' => (int) $item->player_status ? 1 : 0,
                    'player_status_description' => $desc,
                    'player_foreign_id' => (string) ($item->player_foreign_id ?? ''),
                    'flag_url' => $nat !== '' ? '/images/ffb/flags/'.strtolower($nat).'.gif' : null,
                ];
            });
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeInput(array $input): array
    {
        $nat = strtoupper(trim((string) ($input['player_nationality'] ?? '')));

        return [
            'player_id' => (string) ($input['player_id'] ?? ''),
            'player_fname' => trim((string) ($input['player_fname'] ?? '')),
            'player_lname' => trim((string) ($input['player_lname'] ?? '')),
            'player_nationality' => $nat,
            'player_status' => ((string) ($input['player_status'] ?? '1') === '0') ? 0 : 1,
            'player_status_description' => trim((string) ($input['player_status_description'] ?? '')),
            'player_foreign_id' => trim((string) ($input['player_foreign_id'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validate(array $form, bool $isCreate): array
    {
        $errors = [];

        if ($form['player_fname'] === '' || $form['player_lname'] === '') {
            $errors[] = 'Bitte Vor- und Nachname ausfüllen.';
        }

        $countries = $this->countryOptions();
        if ($form['player_nationality'] !== '' && ! isset($countries[$form['player_nationality']])) {
            $errors[] = 'Die Nationalität ist ungültig.';
        }

        if ($isCreate && $form['player_fname'] !== '' && $form['player_lname'] !== '') {
            $exists = Player::query()
                ->where('player_fname', $form['player_fname'])
                ->where('player_lname', $form['player_lname'])
                ->whereRaw('UPPER(player_nationality) = ?', [$form['player_nationality']])
                ->exists();
            if ($exists) {
                $errors[] = 'Ein Spieler mit diesem Namen und dieser Nationalität existiert bereits.';
            }
        }

        return $errors;
    }

    private function isUsedInUserteams(int $playerId): bool
    {
        $playerteamIds = Playerteam::query()
            ->where('playerteam_player_id', $playerId)
            ->pluck('playerteam_id')
            ->all();

        if ($playerteamIds === []) {
            return false;
        }

        $query = Userteam::query();
        foreach (Userteam::playerSlotColumns() as $index => $column) {
            if ($index === 0) {
                $query->whereIn($column, $playerteamIds);
            } else {
                $query->orWhereIn($column, $playerteamIds);
            }
        }

        return $query->exists();
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
}
