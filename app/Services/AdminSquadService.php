<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Userteam;
use App\Support\Flag;
use DateTimeImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AdminSquadService
{
    private const DEFAULT_TRANSFER = '2008-01-01';

    /** @var list<string> */
    private const POSITIONS = ['g', 'd', 'm', 's'];

    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId, int $teamId): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $teams = $this->teamOptions();
        $teamId = $this->resolveTeamId($teamId, $teams);
        $selectedTeam = null;
        foreach ($teams as $team) {
            if ($team['team_id'] === $teamId) {
                $selectedTeam = $team;
                break;
            }
        }

        $items = $teamId > 0 ? $this->rosterItems($teamId) : [];

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_game' => $shell['selected_game'],
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
            if (! $this->storePicture($pictureFile, $teamId, (int) $item->playerteam_id)) {
                return false;
            }
            $item->playerteam_player_picture = $item->playerteam_id.'.jpg';
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
        $item->delete();
        if ($pictureName !== '') {
            $this->deletePictureFile($teamId, $playerteamId);
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, team_id?: int}
     */
    public function batchAdd(array $input): array
    {
        $teamId = (int) ($input['team_id'] ?? 0);
        if ($teamId <= 0 || ! Team::query()->whereKey($teamId)->exists()) {
            return ['ok' => false, 'errors' => ['Bitte ein Team wählen.'], 'team_id' => $teamId];
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
            ->whereIn('playerteam_player_id', $playerIds)
            ->pluck('playerteam_player_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($existing !== []) {
            return [
                'ok' => false,
                'errors' => ['Mindestens ein Spieler ist diesem Team bereits zugeordnet.'],
                'team_id' => $teamId,
            ];
        }

        $found = Player::query()->whereIn('player_id', $playerIds)->pluck('player_id')->map(fn ($id) => (int) $id)->all();
        if (count($found) !== count($playerIds)) {
            return ['ok' => false, 'errors' => ['Mindestens ein Spieler wurde nicht gefunden.'], 'team_id' => $teamId];
        }

        DB::transaction(function () use ($prepared, $teamId) {
            foreach ($prepared as $playerId => $form) {
                Playerteam::query()->create([
                    'playerteam_player_id' => $playerId,
                    'playerteam_team_id' => $teamId,
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
     * @return list<array{team_id: int, team_label: string}>
     */
    private function teamOptions(): array
    {
        return Team::query()
            ->orderBy('team_name')
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
                ];
            })
            ->values()
            ->all();
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
    private function rosterItems(int $teamId): array
    {
        return Playerteam::query()
            ->with('player')
            ->where('playerteam_team_id', $teamId)
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
                $hasPicture = trim((string) ($item->playerteam_player_picture ?? '')) !== '';

                return [
                    'playerteam_id' => (int) $item->playerteam_id,
                    'player_id' => (int) $item->playerteam_player_id,
                    'player_fname' => (string) ($player?->player_fname ?? ''),
                    'player_lname' => (string) ($player?->player_lname ?? ''),
                    'player_nationality' => $nat,
                    'player_flag_url' => $nat !== '' ? Flag::imageUrl($nat) : null,
                    'player_flag_html' => $nat !== '' ? Flag::html($nat) : '',
                    'playerteam_status' => (int) $item->playerteam_status ? 1 : 0,
                    'playerteam_player_price' => (int) $item->playerteam_player_price,
                    'playerteam_player_position' => (string) $item->playerteam_player_position,
                    'playerteam_date_transfer' => $transfer ? date('Y-m-d', $transfer) : self::DEFAULT_TRANSFER,
                    'picture_url' => $hasPicture
                        ? '/images/ffb/players/'.$teamId.'/'.$item->playerteam_id.'.jpg'
                        : '/images/ffb/players/image_na.gif',
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

    private function storePicture(UploadedFile $file, int $teamId, int $playerteamId): bool
    {
        $dir = $this->playersDir($teamId);
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return false;
        }

        $target = $dir.DIRECTORY_SEPARATOR.$playerteamId.'.jpg';
        $mime = (string) $file->getMimeType();

        if ($mime === 'image/jpeg') {
            try {
                $file->move($dir, $playerteamId.'.jpg');
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

    private function deletePictureFile(int $teamId, int $playerteamId): void
    {
        $path = $this->playersDir($teamId).DIRECTORY_SEPARATOR.$playerteamId.'.jpg';
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
        $query = Userteam::query();
        foreach (Userteam::playerSlotColumns() as $index => $column) {
            if ($index === 0) {
                $query->where($column, $playerteamId);
            } else {
                $query->orWhere($column, $playerteamId);
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
