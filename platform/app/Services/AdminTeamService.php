<?php

namespace App\Services;

use App\Models\Playerteam;
use App\Models\Team;
use App\Models\Teamfid;
use App\Models\Userteam;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AdminTeamService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @return array<string, mixed>
     */
    public function pagePayload(int $userId, ?array $form = null, string $mode = 'create'): array
    {
        $shell = $this->adminCenter->shellPayload($userId);
        $form = $form ?? $this->emptyForm();

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_game' => $shell['selected_game'],
            'icons' => $this->iconOptions((string) ($form['team_nationality'] ?? '')),
            'prices' => range(1, 15),
            'items' => $this->listItems(),
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
            'team_id' => '',
            'team_name' => '',
            'team_nationality' => '',
            'team_icon_key' => '',
            'team_price' => 5,
            'team_status' => 1,
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
            'team_price' => (int) $item->team_avg_price,
            'team_status' => (int) (bool) $item->team_status,
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
        $prepared = $this->prepareFormWithIcon($form, $iconFile, $shirtFile, true);
        if (! $prepared['ok']) {
            return [
                'ok' => false,
                'errors' => $prepared['errors'],
                'form' => $prepared['form'],
            ];
        }
        $form = $prepared['form'];

        DB::transaction(function () use ($form) {
            $team = Team::query()->create([
                'team_foreign_id' => '',
                'team_name' => $form['team_name'],
                'team_nationality' => $form['team_nationality'],
                'team_avg_price' => (int) $form['team_price'],
                'team_num_players' => 0,
                'team_status' => (int) $form['team_status'],
            ]);

            $this->upsertTeamfid((int) $team->team_id, $form);
        });

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
        $prepared = $this->prepareFormWithIcon($form, $iconFile, $shirtFile, false);
        if (! $prepared['ok']) {
            return [
                'ok' => false,
                'errors' => $prepared['errors'],
                'form' => $prepared['form'],
            ];
        }
        $form = $prepared['form'];

        DB::transaction(function () use ($item, $form) {
            $item->team_name = $form['team_name'];
            $item->team_nationality = $form['team_nationality'];
            $item->team_avg_price = (int) $form['team_price'];
            $item->team_status = (int) $form['team_status'];
            $item->save();

            $this->upsertTeamfid((int) $item->team_id, $form);
        });

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
     * @param  array<string, string>  $countries
     * @return array{key: string, url: string, label: string, shirt_url: string|null, has_shirt: bool}
     */
    private function iconPayload(string $key, array $countries): array
    {
        $upper = strtoupper($key);
        $label = $countries[$upper] ?? $key;
        $shirtFile = $this->shirtFilename($key);

        return [
            'key' => $key,
            'url' => '/images/ffb/flags/'.$key.'.gif',
            'label' => $label,
            'shirt_url' => $shirtFile !== null ? '/images/ffb/shirts/'.$shirtFile : null,
            'has_shirt' => $shirtFile !== null,
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
                    'team_price' => (int) $item->team_avg_price,
                    'team_status' => (int) (bool) $item->team_status,
                    'flag_url' => $icon !== '' ? '/images/ffb/flags/'.$icon.'.gif' : null,
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

        $price = (int) ($input['team_price'] ?? 5);
        if ($price < 1) {
            $price = 1;
        }
        if ($price > 15) {
            $price = 15;
        }

        return [
            'team_id' => (string) ($input['team_id'] ?? ''),
            'team_name' => trim((string) ($input['team_name'] ?? '')),
            'team_nationality' => $this->normalizeIconKey((string) ($input['team_nationality'] ?? '')),
            'team_icon_key' => $this->normalizeIconKey((string) ($input['team_icon_key'] ?? '')),
            'team_price' => $price,
            'team_status' => ((string) ($input['team_status'] ?? '1') === '0') ? 0 : 1,
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
        ?UploadedFile $shirtFile,
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

        if ($shirtFile !== null) {
            if ($form['team_nationality'] === '') {
                $errors[] = 'Trikot-Upload braucht ein gewähltes Symbol.';
            } else {
                $mime = (string) $shirtFile->getMimeType();
                if (! in_array($mime, ['image/png', 'image/jpeg', 'image/gif', 'image/webp'], true)) {
                    $errors[] = 'Trikot muss ein Bild sein (PNG, JPEG, GIF oder WebP).';
                }
                if ($shirtFile->getSize() > 2 * 1024 * 1024) {
                    $errors[] = 'Trikot darf maximal 2 MB groß sein.';
                }
                if ($errors === [] && ! $this->storeShirtFile($shirtFile, $form['team_nationality'])) {
                    $errors[] = 'Trikot konnte nicht gespeichert werden.';
                }
            }
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
            } catch (\Throwable) {
                return false;
            }

            return is_file($target);
        }

        return $this->convertAndStoreImage($file, $mime, $target, 'gif');
    }

    private function storeShirtFile(UploadedFile $file, string $key): bool
    {
        $key = $this->normalizeIconKey($key);
        if ($key === '') {
            return false;
        }

        $dir = $this->shirtsDir();
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return false;
        }

        // Legacy convention: shirt_AUT.png (uppercase key).
        $filename = 'shirt_'.strtoupper($key).'.png';
        $target = $dir.DIRECTORY_SEPARATOR.$filename;
        $mime = (string) $file->getMimeType();

        if ($mime === 'image/png') {
            try {
                $file->move($dir, $filename);
            } catch (\Throwable) {
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

        return is_file($this->flagsDir().DIRECTORY_SEPARATOR.$key.'.gif');
    }

    private function shirtFileExists(string $key): bool
    {
        return $this->shirtFilename($key) !== null;
    }

    /**
     * Legacy shirt files are usually shirt_AUT.png (uppercase key).
     */
    private function shirtFilename(string $key): ?string
    {
        $key = $this->normalizeIconKey($key);
        if ($key === '') {
            return null;
        }

        $dir = $this->shirtsDir();
        foreach ([strtoupper($key), $key] as $variant) {
            $name = 'shirt_'.$variant.'.png';
            if (is_file($dir.DIRECTORY_SEPARATOR.$name)) {
                return $name;
            }
        }

        return null;
    }

    private function normalizeIconKey(string $value): string
    {
        $value = strtolower(trim($value));
        $value = str_replace([' ', '.'], ['_', ''], $value);
        $value = preg_replace('/[^a-z0-9_-]+/', '', $value) ?? '';

        return $value;
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

    private function flagsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'flags';
    }

    private function shirtsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'shirts';
    }

    private function hasPlayersInUserteams(int $teamId): bool
    {
        $playerteamIds = Playerteam::query()
            ->where('playerteam_team_id', $teamId)
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
}
