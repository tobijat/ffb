<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Userteam;
use App\Support\Flag;
use App\Support\PlayerPicture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminPlayerService
{
    public const PER_PAGE = 100;

    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    /**
     * @param  array<string, mixed>|null  $form
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId, ?array $form = null, string $mode = 'create'): array
    {
        $shell = $this->adminCenter->shellPayload($userId);

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_league' => $shell['selected_league'],
            'countries' => $this->countryOptions(),
            'form' => $form ?? $this->emptyForm(),
            'mode' => $mode === 'update' ? 'update' : 'create',
            'per_page' => self::PER_PAGE,
        ];
    }

    /**
     * @param  array{q?: string, nationality?: string, page?: int, exclude_team_id?: int, exclude_league_id?: int}  $filters
     * @return array{
     *     items: list<array<string, mixed>>,
     *     total: int,
     *     page: int,
     *     per_page: int,
     *     last_page: int,
     *     q: string,
     *     nationality: string
     * }
     */
    public function search(array $filters = []): array
    {
        $filters = $this->normalizeFilters($filters);
        $paginator = $this->listItems($filters);
        /** @var list<array<string, mixed>> $items */
        $items = array_values($paginator->items());
        $items = $this->attachPictureUrls($items);

        return [
            'items' => $items,
            'total' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
            'q' => $filters['q'],
            'nationality' => $filters['nationality'],
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

        $this->applyPlayerFields($item, $form);

        return ['ok' => true, 'message' => 'Spieler erfolgreich aktualisiert.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function batchUpdate(array $input): array
    {
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
            return ['ok' => false, 'errors' => ['Keine Änderungen zum Speichern.']];
        }

        /** @var list<array{item: Player, form: array<string, mixed>}> $prepared */
        $prepared = [];
        /** @var list<Player> $toDelete */
        $toDelete = [];
        $errors = [];

        foreach ($deleteIds as $playerId) {
            $item = Player::query()->find($playerId);
            if (! $item) {
                $errors[] = 'Spieler #'.$playerId.' nicht gefunden.';

                continue;
            }

            $blocker = $this->deletionBlocker($playerId);
            if ($blocker !== null) {
                $label = trim((string) $item->player_lname).' #'.$playerId;
                $errors[] = $label.': '.$blocker;

                continue;
            }

            $toDelete[] = $item;
        }

        foreach ($itemsInput as $rawId => $rowInput) {
            if (! is_array($rowInput)) {
                continue;
            }

            $playerId = (int) $rawId;
            if ($playerId <= 0 || isset($deleteLookup[$playerId])) {
                continue;
            }

            $item = Player::query()->find($playerId);
            if (! $item) {
                $errors[] = 'Spieler #'.$playerId.' nicht gefunden.';

                continue;
            }

            $form = $this->normalizeInput($rowInput + ['player_id' => $playerId]);
            $rowErrors = $this->validate($form, false);
            if ($rowErrors !== []) {
                $label = trim($form['player_lname'] !== '' ? $form['player_lname'] : (string) $item->player_lname).' #'.$playerId;
                foreach ($rowErrors as $rowError) {
                    $errors[] = $label.': '.$rowError;
                }

                continue;
            }

            $prepared[] = [
                'item' => $item,
                'form' => $form,
            ];
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        if ($prepared === [] && $toDelete === []) {
            return ['ok' => false, 'errors' => ['Keine Änderungen zum Speichern.']];
        }

        foreach ($prepared as $row) {
            $this->applyPlayerFields($row['item'], $row['form']);
        }
        foreach ($toDelete as $item) {
            $item->delete();
        }

        $updated = count($prepared);
        $deleted = count($toDelete);
        $parts = [];
        if ($updated === 1) {
            $parts[] = '1 Spieler gespeichert';
        } elseif ($updated > 1) {
            $parts[] = $updated.' Spieler gespeichert';
        }
        if ($deleted === 1) {
            $parts[] = '1 Spieler gelöscht';
        } elseif ($deleted > 1) {
            $parts[] = $deleted.' Spieler gelöscht';
        }

        return [
            'ok' => true,
            'message' => implode(', ', $parts).'.',
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function applyPlayerFields(Player $item, array $form): void
    {
        $item->player_foreign_id = $form['player_foreign_id'];
        $item->player_fname = $form['player_fname'];
        $item->player_lname = $form['player_lname'];
        $item->player_nationality = $form['player_nationality'];
        $item->player_status = (int) $form['player_status'];
        $item->player_status_description = $form['player_status_description'];
        $item->save();
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

        $blocker = $this->deletionBlocker($playerId);
        if ($blocker !== null) {
            return ['ok' => false, 'errors' => [$blocker]];
        }

        $item->delete();

        return ['ok' => true, 'message' => 'Spieler erfolgreich gelöscht.'];
    }

    private function deletionBlocker(int $playerId): ?string
    {
        if (Playerteam::query()->where('playerteam_player_id', $playerId)->exists()) {
            return 'Löschen nicht möglich: Spieler ist noch einem oder mehreren Teams zugeordnet.';
        }

        if ($this->isUsedInUserteams($playerId)) {
            return 'Löschen nicht möglich: Spieler ist in Userteams eingesetzt.';
        }

        return null;
    }

    /**
     * @param  array{q?: string, nationality?: string, page?: int, exclude_team_id?: int, exclude_league_id?: int}  $filters
     * @return array{q: string, nationality: string, page: int, exclude_team_id: int, exclude_league_id: int}
     */
    private function normalizeFilters(array $filters): array
    {
        return [
            'q' => trim((string) ($filters['q'] ?? '')),
            'nationality' => strtoupper(trim((string) ($filters['nationality'] ?? ''))),
            'page' => max(1, (int) ($filters['page'] ?? 1)),
            'exclude_team_id' => max(0, (int) ($filters['exclude_team_id'] ?? 0)),
            'exclude_league_id' => max(0, (int) ($filters['exclude_league_id'] ?? 0)),
        ];
    }

    /**
     * @param  array{q: string, nationality: string, page: int, exclude_team_id: int, exclude_league_id: int}  $filters
     */
    private function listItems(array $filters): LengthAwarePaginator
    {
        $countries = $this->countryOptions();

        $query = Player::query()
            ->orderBy('player_lname')
            ->orderBy('player_fname')
            ->orderBy('player_id');

        if ($filters['exclude_team_id'] > 0) {
            $onTeam = Playerteam::query()
                ->where('playerteam_team_id', $filters['exclude_team_id']);
            if ($filters['exclude_league_id'] > 0) {
                $onTeam->where('playerteam_league_id', $filters['exclude_league_id']);
            }
            $onTeamIds = $onTeam->pluck('playerteam_player_id')->all();
            if ($onTeamIds !== []) {
                $query->whereNotIn('player_id', $onTeamIds);
            }
        }

        if ($filters['q'] !== '') {
            $q = $filters['q'];
            $like = '%'.$q.'%';
            $query->where(function ($builder) use ($q, $like) {
                $builder->where('player_fname', 'like', $like)
                    ->orWhere('player_lname', 'like', $like)
                    ->orWhereRaw("CONCAT(player_fname, ' ', player_lname) LIKE ?", [$like])
                    ->orWhereRaw("CONCAT(player_lname, ' ', player_fname) LIKE ?", [$like]);

                if (ctype_digit($q)) {
                    $builder->orWhere('player_id', (int) $q)
                        ->orWhereRaw('CAST(player_id AS CHAR) LIKE ?', [$q.'%']);
                }
            });
        }

        if ($filters['nationality'] !== '') {
            $query->whereRaw('UPPER(player_nationality) = ?', [$filters['nationality']]);
        }

        return $query
            ->paginate(self::PER_PAGE, ['*'], 'page', $filters['page'])
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
                    'tm_url' => $this->transfermarktUrl((string) ($item->player_foreign_id ?? '')),
                    'picture_url' => null,
                    'flag_url' => $nat !== '' ? Flag::imageUrl($nat) : null,
                    'flag_html' => $nat !== '' ? Flag::html($nat) : '',
                ];
            });
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private function attachPictureUrls(array $items): array
    {
        if ($items === []) {
            return $items;
        }

        $playerIds = array_values(array_unique(array_map(
            static fn (array $item): int => (int) $item['player_id'],
            $items
        )));

        $pictures = Playerteam::query()
            ->whereIn('playerteam_player_id', $playerIds)
            ->orderByDesc('playerteam_id')
            ->get(['playerteam_player_id', 'playerteam_team_id', 'playerteam_id']);

        $byPlayer = [];
        foreach ($pictures as $row) {
            $playerId = (int) $row->playerteam_player_id;
            if (isset($byPlayer[$playerId])) {
                continue;
            }
            $url = PlayerPicture::url((int) $row->playerteam_team_id, $playerId);
            if (! str_ends_with($url, 'image_na.gif')) {
                $byPlayer[$playerId] = $url;
            }
        }

        foreach ($items as &$item) {
            $item['picture_url'] = $byPlayer[(int) $item['player_id']] ?? null;
        }
        unset($item);

        return $items;
    }

    private function transfermarktUrl(string $foreignId): ?string
    {
        $foreignId = trim($foreignId);
        if ($foreignId === '' || $foreignId === '0') {
            return null;
        }

        // Stored as "{id}/{slug}", e.g. "232454/nadiem-amiri"
        if (preg_match('#^(\d+)/([A-Za-z0-9][A-Za-z0-9\-]*)$#', $foreignId, $m)) {
            return 'https://www.transfermarkt.at/'.$m[2].'/profil/spieler/'.$m[1];
        }

        // Also accept "{slug}/{id}"
        if (preg_match('#^([A-Za-z0-9][A-Za-z0-9\-]*)/(\d+)$#', $foreignId, $m)) {
            return 'https://www.transfermarkt.at/'.$m[1].'/profil/spieler/'.$m[2];
        }

        return null;
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
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($playerteamIds === []) {
            return false;
        }

        return Userteam::queryContainingAnyPlayerteam($playerteamIds)->exists();
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
