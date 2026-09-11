<?php

namespace App\Services;

use App\Models\League;
use App\Models\LeagueOptions;
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
                'league_id' => '',
                'league_title' => '',
                'league_visible' => 1,
                'league_archive' => 0,
                'league_symbol' => self::DEFAULT_SYMBOL,
                'symbol_url' => '/images/ffb/symbols/'.self::DEFAULT_SYMBOL,
            ],
            $this->defaultOptionsForm(),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function formForEdit(int $leagueId): ?array
    {
        $league = League::query()->with('options')->find($leagueId);
        if (! $league) {
            return null;
        }

        $symbol = (string) ($league->league_symbol ?: self::DEFAULT_SYMBOL);
        $options = $league->options;
        $optionForm = $options
            ? $this->optionsToForm($options)
            : $this->defaultOptionsForm();

        return array_merge(
            [
                'league_id' => (int) $league->league_id,
                'league_title' => (string) $league->league_title,
                'league_visible' => (int) (bool) $league->league_visible,
                'league_archive' => (int) (bool) $league->league_archive,
                'league_symbol' => $symbol,
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
            $form['league_symbol'] = $uploaded;
            $form['symbol_url'] = '/images/ffb/symbols/'.$uploaded;
        }

        DB::transaction(function () use ($form): void {
            $league = League::query()->create([
                'league_title' => $form['league_title'],
                'league_visible' => (int) $form['league_visible'],
                'league_archive' => (int) $form['league_archive'],
                'league_symbol' => $form['league_symbol'],
            ]);

            LeagueOptions::query()->create(array_merge(
                ['options_league_id' => (int) $league->league_id],
                $this->optionsFromForm($form),
            ));
        });

        return ['ok' => true, 'message' => 'Liga erfolgreich angelegt.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function update(int $leagueId, array $input, ?UploadedFile $symbolFile = null): array
    {
        $league = League::query()->with('options')->find($leagueId);
        if (! $league) {
            return [
                'ok' => false,
                'errors' => ['Liga nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['league_id' => $leagueId]),
            ];
        }

        $form = $this->normalizeInput($input + [
            'league_id' => $leagueId,
            'league_symbol' => (string) ($league->league_symbol ?: self::DEFAULT_SYMBOL),
        ]);
        $errors = $this->validate($form, $symbolFile);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        $oldSymbol = (string) ($league->league_symbol ?: '');
        if ($symbolFile !== null) {
            $uploaded = $this->storeSymbol($symbolFile);
            if ($uploaded === null) {
                return [
                    'ok' => false,
                    'errors' => ['Symbol-Upload fehlgeschlagen.'],
                    'form' => $form,
                ];
            }
            $form['league_symbol'] = $uploaded;
            $form['symbol_url'] = '/images/ffb/symbols/'.$uploaded;
        }

        DB::transaction(function () use ($league, $form, $oldSymbol): void {
            $league->league_title = $form['league_title'];
            $league->league_visible = (int) $form['league_visible'];
            $league->league_archive = (int) $form['league_archive'];
            $league->league_symbol = $form['league_symbol'];
            $league->save();

            $optionsPayload = $this->optionsFromForm($form);
            if ($league->options) {
                $league->options->fill($optionsPayload)->save();
            } else {
                LeagueOptions::query()->create(array_merge(
                    ['options_league_id' => (int) $league->league_id],
                    $optionsPayload,
                ));
            }

            if (
                $oldSymbol !== ''
                && $oldSymbol !== $form['league_symbol']
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
    public function delete(int $leagueId): array
    {
        $league = League::query()->find($leagueId);
        if (! $league) {
            return ['ok' => false, 'errors' => ['Liga nicht gefunden.']];
        }

        if (Matchround::query()->where('matchround_league_id', $leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Löschen nicht möglich: Es gibt zugehörige Spielrunden.']];
        }

        if (News::query()->where('news_league_id', $leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Löschen nicht möglich: Es gibt zugehörige News.']];
        }

        if (Userscore::query()->where('userscore_league_id', $leagueId)->exists()) {
            return ['ok' => false, 'errors' => ['Löschen nicht möglich: Es gibt zugehörige Ranglisten-Daten.']];
        }

        $symbol = (string) ($league->league_symbol ?: '');
        $league->delete();

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
        return League::query()
            ->orderBy('league_archive')
            ->orderBy('league_title')
            ->get()
            ->map(function (League $league) {
                $symbol = (string) ($league->league_symbol ?: self::DEFAULT_SYMBOL);

                return [
                    'league_id' => (int) $league->league_id,
                    'league_title' => (string) $league->league_title,
                    'league_visible' => (int) (bool) $league->league_visible,
                    'league_archive' => (int) (bool) $league->league_archive,
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
        $symbol = trim((string) ($input['league_symbol'] ?? self::DEFAULT_SYMBOL));
        if ($symbol === '') {
            $symbol = self::DEFAULT_SYMBOL;
        }

        $form = [
            'league_id' => (string) ($input['league_id'] ?? ''),
            'league_title' => trim((string) ($input['league_title'] ?? '')),
            'league_visible' => (int) ($input['league_visible'] ?? 0) === 1 ? 1 : 0,
            'league_archive' => (int) ($input['league_archive'] ?? 0) === 1 ? 1 : 0,
            'league_symbol' => $symbol,
            'symbol_url' => '/images/ffb/symbols/'.$symbol,
        ];

        foreach ($this->optionKeys() as $key) {
            if (str_starts_with($key, 'options_league_') && ! str_ends_with($key, '_before')) {
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

        if ($form['league_title'] === '') {
            $errors[] = 'Bitte einen Liga-Titel angeben.';
        }

        $rank = (string) ($form['options_league_rankmode'] ?? '');
        if (! in_array($rank, ['lc', 'points'], true)) {
            $errors[] = 'Ungültiger Ranglisten-Modus.';
        }

        $price = (string) ($form['options_league_pricemode'] ?? '');
        if (! in_array($price, ['dynamic', 'static'], true)) {
            $errors[] = 'Ungültiger Preis-Modus.';
        }

        foreach (['options_league_pointsmode', 'options_league_lcpoints'] as $key) {
            if (! in_array((string) ($form[$key] ?? ''), ['new', 'old'], true)) {
                $errors[] = 'Ungültiger Punkte-Modus.';
                break;
            }
        }

        foreach ($this->optionKeys() as $key) {
            if (str_starts_with($key, 'options_league_') && ! str_ends_with($key, '_before')) {
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
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_league_lcpoints' => 'new',
            'options_league_remind_hours_before' => 0,
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
    private function optionsToForm(LeagueOptions $options): array
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
            if (str_starts_with($key, 'options_league_') && ! str_ends_with($key, '_before')) {
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
