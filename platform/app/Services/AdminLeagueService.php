<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameOptions;
use App\Models\Matchround;
use App\Models\News;
use App\Models\Userscore;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AdminLeagueService
{
    private const DEFAULT_SYMBOL = 'symbol_game_na.png';

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

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'selected_game' => $shell['selected_game'],
            'items' => $this->listItems(),
            'form' => $form ?? $this->emptyForm(),
            'mode' => $mode === 'update' ? 'update' : 'create',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(): array
    {
        return array_merge(
            [
                'game_id' => '',
                'game_title' => '',
                'game_description' => '',
                'game_status' => 1,
                'game_visible' => 1,
                'game_archive' => 0,
                'game_countdown' => 0,
                'game_symbol' => self::DEFAULT_SYMBOL,
                'symbol_url' => '/images/ffb/symbols/'.self::DEFAULT_SYMBOL,
            ],
            $this->defaultOptionsForm(),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function formForEdit(int $gameId): ?array
    {
        $game = Game::query()->with('options')->find($gameId);
        if (! $game) {
            return null;
        }

        $symbol = (string) ($game->game_symbol ?: self::DEFAULT_SYMBOL);
        $options = $game->options;
        $optionForm = $options
            ? $this->optionsToForm($options)
            : $this->defaultOptionsForm();

        return array_merge(
            [
                'game_id' => (int) $game->game_id,
                'game_title' => (string) $game->game_title,
                'game_description' => (string) ($game->game_description ?? ''),
                'game_status' => (int) (bool) $game->game_status,
                'game_visible' => (int) (bool) $game->game_visible,
                'game_archive' => (int) (bool) $game->game_archive,
                'game_countdown' => (int) (bool) $game->game_countdown,
                'game_symbol' => $symbol,
                'symbol_url' => '/images/ffb/symbols/'.$symbol,
            ],
            $optionForm,
        );
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function create(array $input, ?UploadedFile $symbolFile = null): array
    {
        $form = $this->normalizeInput($input);
        $errors = $this->validate($form, $symbolFile);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        if ($symbolFile !== null) {
            $uploaded = $this->storeSymbol($symbolFile);
            if ($uploaded === null) {
                return [
                    'ok' => false,
                    'errors' => ['Symbol-Upload fehlgeschlagen.'],
                    'form' => $form,
                ];
            }
            $form['game_symbol'] = $uploaded;
            $form['symbol_url'] = '/images/ffb/symbols/'.$uploaded;
        }

        DB::transaction(function () use ($form): void {
            $game = Game::query()->create([
                'game_title' => $form['game_title'],
                'game_description' => $form['game_description'] !== '' ? $form['game_description'] : null,
                'game_status' => (int) $form['game_status'],
                'game_visible' => (int) $form['game_visible'],
                'game_archive' => (int) $form['game_archive'],
                'game_countdown' => (int) $form['game_countdown'],
                'game_symbol' => $form['game_symbol'],
            ]);

            GameOptions::query()->create(array_merge(
                ['options_game_id' => (int) $game->game_id],
                $this->optionsFromForm($form),
            ));
        });

        return ['ok' => true, 'message' => 'Liga erfolgreich angelegt.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function update(int $gameId, array $input, ?UploadedFile $symbolFile = null): array
    {
        $game = Game::query()->with('options')->find($gameId);
        if (! $game) {
            return [
                'ok' => false,
                'errors' => ['Liga nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['game_id' => $gameId]),
            ];
        }

        $form = $this->normalizeInput($input + [
            'game_id' => $gameId,
            'game_symbol' => (string) ($game->game_symbol ?: self::DEFAULT_SYMBOL),
        ]);
        $errors = $this->validate($form, $symbolFile);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        $oldSymbol = (string) ($game->game_symbol ?: '');
        if ($symbolFile !== null) {
            $uploaded = $this->storeSymbol($symbolFile);
            if ($uploaded === null) {
                return [
                    'ok' => false,
                    'errors' => ['Symbol-Upload fehlgeschlagen.'],
                    'form' => $form,
                ];
            }
            $form['game_symbol'] = $uploaded;
            $form['symbol_url'] = '/images/ffb/symbols/'.$uploaded;
        }

        DB::transaction(function () use ($game, $form, $oldSymbol): void {
            $game->game_title = $form['game_title'];
            $game->game_description = $form['game_description'] !== '' ? $form['game_description'] : null;
            $game->game_status = (int) $form['game_status'];
            $game->game_visible = (int) $form['game_visible'];
            $game->game_archive = (int) $form['game_archive'];
            $game->game_countdown = (int) $form['game_countdown'];
            $game->game_symbol = $form['game_symbol'];
            $game->save();

            $optionsPayload = $this->optionsFromForm($form);
            if ($game->options) {
                $game->options->fill($optionsPayload)->save();
            } else {
                GameOptions::query()->create(array_merge(
                    ['options_game_id' => (int) $game->game_id],
                    $optionsPayload,
                ));
            }

            if (
                $oldSymbol !== ''
                && $oldSymbol !== $form['game_symbol']
                && $oldSymbol !== self::DEFAULT_SYMBOL
            ) {
                $this->deleteSymbolFile($oldSymbol);
            }
        });

        return ['ok' => true, 'message' => 'Liga erfolgreich aktualisiert.'];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function delete(int $gameId): array
    {
        $game = Game::query()->find($gameId);
        if (! $game) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.']];
        }

        if (Matchround::query()->where('matchround_game_id', $gameId)->exists()) {
            return ['ok' => false, 'errors' => ['Löschen nicht möglich: Es gibt zugehörige Spielrunden.']];
        }

        if (News::query()->where('news_game_id', $gameId)->exists()) {
            return ['ok' => false, 'errors' => ['Löschen nicht möglich: Es gibt zugehörige News.']];
        }

        if (Userscore::query()->where('userscore_game_id', $gameId)->exists()) {
            return ['ok' => false, 'errors' => ['Löschen nicht möglich: Es gibt zugehörige Ranglisten-Daten.']];
        }

        $symbol = (string) ($game->game_symbol ?: '');
        $game->delete();

        if ($symbol !== '' && $symbol !== self::DEFAULT_SYMBOL) {
            $this->deleteSymbolFile($symbol);
        }

        return ['ok' => true, 'message' => 'Liga erfolgreich gelöscht.'];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listItems(): array
    {
        return Game::query()
            ->orderBy('game_archive')
            ->orderBy('game_title')
            ->get()
            ->map(function (Game $game) {
                $symbol = (string) ($game->game_symbol ?: self::DEFAULT_SYMBOL);

                return [
                    'game_id' => (int) $game->game_id,
                    'game_title' => (string) $game->game_title,
                    'game_status' => (int) (bool) $game->game_status,
                    'game_visible' => (int) (bool) $game->game_visible,
                    'game_archive' => (int) (bool) $game->game_archive,
                    'game_countdown' => (int) (bool) $game->game_countdown,
                    'symbol_url' => '/images/ffb/symbols/'.$symbol,
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
        $symbol = trim((string) ($input['game_symbol'] ?? self::DEFAULT_SYMBOL));
        if ($symbol === '') {
            $symbol = self::DEFAULT_SYMBOL;
        }

        $form = [
            'game_id' => (string) ($input['game_id'] ?? ''),
            'game_title' => trim((string) ($input['game_title'] ?? '')),
            'game_description' => trim((string) ($input['game_description'] ?? '')),
            'game_status' => (int) ($input['game_status'] ?? 0) === 1 ? 1 : 0,
            'game_visible' => (int) ($input['game_visible'] ?? 0) === 1 ? 1 : 0,
            'game_archive' => (int) ($input['game_archive'] ?? 0) === 1 ? 1 : 0,
            'game_countdown' => (int) ($input['game_countdown'] ?? 0) === 1 ? 1 : 0,
            'game_symbol' => $symbol,
            'symbol_url' => '/images/ffb/symbols/'.$symbol,
        ];

        foreach ($this->optionKeys() as $key) {
            if (str_starts_with($key, 'options_game_') && ! str_ends_with($key, '_before')) {
                $form[$key] = trim((string) ($input[$key] ?? ''));
            } else {
                $raw = $input[$key] ?? 0;
                $form[$key] = is_numeric($raw) ? (int) $raw : $raw;
            }
        }

        return $form;
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validate(array $form, ?UploadedFile $symbolFile): array
    {
        $errors = [];

        if ($form['game_title'] === '') {
            $errors[] = 'Bitte einen Liga-Titel angeben.';
        }

        $rank = (string) ($form['options_game_rankmode'] ?? '');
        if (! in_array($rank, ['wc', 'points'], true)) {
            $errors[] = 'Ungültiger Ranglisten-Modus.';
        }

        $price = (string) ($form['options_game_pricemode'] ?? '');
        if (! in_array($price, ['dynamic', 'static'], true)) {
            $errors[] = 'Ungültiger Preis-Modus.';
        }

        foreach (['options_game_pointsmode', 'options_game_wcpoints'] as $key) {
            if (! in_array((string) ($form[$key] ?? ''), ['new', 'old'], true)) {
                $errors[] = 'Ungültiger Punkte-Modus.';
                break;
            }
        }

        foreach ($this->optionKeys() as $key) {
            if (str_starts_with($key, 'options_game_') && ! str_ends_with($key, '_before')) {
                continue;
            }
            if (! is_numeric($form[$key] ?? null)) {
                $errors[] = 'Optionsfelder müssen Zahlen sein.';
                break;
            }
        }

        if ($symbolFile !== null) {
            $mime = (string) $symbolFile->getMimeType();
            if (! in_array($mime, ['image/png', 'image/jpeg', 'image/gif', 'image/webp'], true)) {
                $errors[] = 'Symbol muss ein Bild sein (PNG, JPEG, GIF oder WebP).';
            }
            if ($symbolFile->getSize() > 2 * 1024 * 1024) {
                $errors[] = 'Symbol darf maximal 2 MB groß sein.';
            }
        }

        return $errors;
    }

    /**
     * @return array<string, int|string>
     */
    private function defaultOptionsForm(): array
    {
        return [
            'options_game_rankmode' => 'wc',
            'options_game_pricemode' => 'dynamic',
            'options_game_pointsmode' => 'new',
            'options_game_wcpoints' => 'new',
            'options_game_remind_hours_before' => 0,
            'options_score_minutes' => 60,
            'options_score_minutes_treshold' => 30,
            'options_score_minutes_gt' => 3,
            'options_score_minutes_lt' => 2,
            'options_score_minutes_lt30' => 1,
            'options_score_goals_g' => 6,
            'options_score_goals_d' => 5,
            'options_score_goals_m' => 4,
            'options_score_goals_s' => 4,
            'options_score_assists' => 3,
            'options_score_owngoals' => -2,
            'options_score_no_oppgoals_g' => 4,
            'options_score_no_oppgoals_d' => 3,
            'options_score_no_oppgoals_m' => 1,
            'options_score_oppgoals_g' => -1,
            'options_score_oppgoals_d' => -1,
            'options_score_card_y' => -2,
            'options_score_card_yr' => -4,
            'options_score_card_r' => -5,
            'options_score_penalty_saved' => 2,
            'options_score_penalty_lost' => -2,
            'options_score_penaltyshootout_save' => 2,
            'options_score_penaltyshootout_lost' => -2,
            'options_score_penaltyshootout_hit' => 2,
            'options_score_high_loss' => 0,
            'options_score_high_win' => 0,
            'options_score_high_win_loss_treshold' => 0,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 2,
            'options_lineup_max_g' => 1,
            'options_lineup_max_d' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_max_s' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_min_m' => 3,
            'options_lineup_min_s' => 1,
            'options_status_error' => 500,
            'options_status_error_validation' => 501,
            'options_status_success' => 200,
            'options_status_success_insert' => 201,
            'options_status_success_update' => 202,
            'options_status_success_delete' => 203,
        ];
    }

    /**
     * @return list<string>
     */
    private function optionKeys(): array
    {
        return array_keys($this->defaultOptionsForm());
    }

    /**
     * @return array<string, mixed>
     */
    private function optionsToForm(GameOptions $options): array
    {
        $form = [];
        foreach ($this->optionKeys() as $key) {
            $form[$key] = $options->{$key};
        }

        return $form;
    }

    /**
     * @param  array<string, mixed>  $form
     * @return array<string, int|string>
     */
    private function optionsFromForm(array $form): array
    {
        $out = [];
        foreach ($this->optionKeys() as $key) {
            if (str_starts_with($key, 'options_game_') && ! str_ends_with($key, '_before')) {
                $out[$key] = (string) $form[$key];
            } else {
                $out[$key] = (int) $form[$key];
            }
        }

        return $out;
    }

    private function storeSymbol(UploadedFile $file): ?string
    {
        $dir = $this->symbolsDir();
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return null;
        }

        $ext = strtolower((string) $file->getClientOriginalExtension());
        if (! in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true)) {
            $ext = 'png';
        }

        $name = 'symbol_game_'.bin2hex(random_bytes(8)).'.'.$ext;
        try {
            $file->move($dir, $name);
        } catch (\Throwable) {
            return null;
        }

        return $name;
    }

    private function deleteSymbolFile(string $filename): void
    {
        if ($filename === '' || str_contains($filename, '/') || str_contains($filename, '\\')) {
            return;
        }

        $path = $this->symbolsDir().DIRECTORY_SEPARATOR.$filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function symbolsDir(): string
    {
        $base = rtrim((string) config('ffb.legacy_images_path'), DIRECTORY_SEPARATOR.'\\/');

        return $base.DIRECTORY_SEPARATOR.'symbols';
    }
}
