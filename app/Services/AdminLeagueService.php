<?php

namespace App\Services;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\Matchround;
use App\Models\News;
use App\Models\Userscore;
use App\Support\AssetKey;
use App\Support\LeagueSymbol;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AdminLeagueService
{
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
                'league_test' => 0,
                'league_uefa_competition_identifier' => '',
                'league_fifa_competition_identifier' => '',
                'symbol_url' => LeagueSymbol::url(null),
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
                'league_test' => (int) (bool) $league->league_test,
                'league_uefa_competition_identifier' => (string) ($league->league_uefa_competition_identifier ?? ''),
                'league_fifa_competition_identifier' => (string) ($league->league_fifa_competition_identifier ?? ''),
                'symbol_url' => LeagueSymbol::url($this->leagueKey($league)),
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

        $createdId = 0;
        $createdKey = '';
        DB::transaction(function () use ($form, &$createdId, &$createdKey): void {
            $createdKey = AssetKey::generate('ffb_league', (string) $form['league_title']);
            $league = League::query()->create([
                'asset_key' => $createdKey,
                'league_title' => $form['league_title'],
                'league_visible' => (int) $form['league_visible'],
                'league_archive' => (int) $form['league_archive'],
                'league_test' => (int) $form['league_test'],
                'league_uefa_competition_identifier' => $form['league_uefa_competition_identifier'],
                'league_fifa_competition_identifier' => $form['league_fifa_competition_identifier'],
            ]);
            $createdId = (int) $league->league_id;

            LeagueOptions::query()->create(array_merge(
                ['options_league_id' => $createdId],
                $this->optionsFromForm($form, pointsMode: 'new'),
            ));
        });

        if ($symbolFile !== null && $createdId > 0) {
            if (! LeagueSymbol::store($createdKey, $symbolFile)) {
                return [
                    'ok' => false,
                    'errors' => ['Liga angelegt, aber Symbol-Upload fehlgeschlagen.'],
                    'form' => $form,
                ];
            }
        }

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
        ]);
        $form['symbol_url'] = LeagueSymbol::url($this->leagueKey($league));
        $errors = $this->validate($form, $symbolFile);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        if ($symbolFile !== null && ! LeagueSymbol::store($this->leagueKey($league), $symbolFile)) {
            return [
                'ok' => false,
                'errors' => ['Symbol-Upload fehlgeschlagen.'],
                'form' => $form,
            ];
        }

        DB::transaction(function () use ($league, $form): void {
            $league->league_title = $form['league_title'];
            $league->league_visible = (int) $form['league_visible'];
            $league->league_archive = (int) $form['league_archive'];
            $league->league_test = (int) $form['league_test'];
            $league->league_uefa_competition_identifier = $form['league_uefa_competition_identifier'];
            $league->league_fifa_competition_identifier = $form['league_fifa_competition_identifier'];
            $league->save();

            $existingPointsMode = (string) ($league->options?->options_league_pointsmode ?: 'new');
            $optionsPayload = $this->optionsFromForm($form, pointsMode: $existingPointsMode);
            if ($league->options) {
                $league->options->fill($optionsPayload)->save();
            } else {
                LeagueOptions::query()->create(array_merge(
                    ['options_league_id' => (int) $league->league_id],
                    $this->optionsFromForm($form, pointsMode: 'new'),
                ));
            }
        });

        $form['symbol_url'] = LeagueSymbol::url($this->leagueKey($league));

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

        $leagueKey = $this->leagueKey($league);
        $league->delete();
        LeagueSymbol::deleteForLeague($leagueKey);

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
                $leagueId = (int) $league->league_id;

                return [
                    'league_id' => $leagueId,
                    'league_title' => (string) $league->league_title,
                    'league_visible' => (int) (bool) $league->league_visible,
                    'league_archive' => (int) (bool) $league->league_archive,
                    'league_test' => (int) (bool) $league->league_test,
                    'symbol_url' => LeagueSymbol::url($this->leagueKey($league)),
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
        $leagueId = (int) ($input['league_id'] ?? 0);
        $existingLeague = $leagueId > 0 ? League::query()->find($leagueId) : null;

        $form = [
            'league_id' => (string) ($input['league_id'] ?? ''),
            'league_title' => trim((string) ($input['league_title'] ?? '')),
            'league_visible' => (int) ($input['league_visible'] ?? 0) === 1 ? 1 : 0,
            'league_archive' => (int) ($input['league_archive'] ?? 0) === 1 ? 1 : 0,
            'league_test' => (int) ($input['league_test'] ?? 0) === 1 ? 1 : 0,
            'league_uefa_competition_identifier' => trim((string) ($input['league_uefa_competition_identifier'] ?? '')),
            'league_fifa_competition_identifier' => trim((string) ($input['league_fifa_competition_identifier'] ?? '')),
            'symbol_url' => LeagueSymbol::url($existingLeague ? $this->leagueKey($existingLeague) : null),
        ];

        foreach ($this->optionKeys() as $key) {
            if ($key === 'options_league_pointsmode') {
                // Not editable via admin form; create/update set it explicitly.
                $form[$key] = 'new';

                continue;
            }
            if (str_starts_with($key, 'options_league_') && ! str_ends_with($key, '_before')) {
                $form[$key] = trim((string) ($input[$key] ?? ''));
            } else {
                $raw = $input[$key] ?? 0;
                $form[$key] = is_numeric($raw) ? (int) $raw : $raw;
            }
        }

        $form['options_league_lcpoints'] = $this->normalizeLcPointsList(
            (string) ($form['options_league_lcpoints'] ?? '')
        );

        $benchMode = (string) ($form['options_league_benchmode'] ?? '');
        if ($benchMode === '') {
            $form['options_lineup_min_bench'] = 0;
            $form['options_lineup_max_bench'] = 0;
        }

        return $form;
    }

    private function leagueKey(League $league): ?string
    {
        $key = (string) ($league->asset_key ?? '');

        return $key !== '' ? $key : null;
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

        $benchMode = (string) ($form['options_league_benchmode'] ?? '');
        if (! in_array($benchMode, ['', 'cover', 'bestof'], true)) {
            $errors[] = 'Ungültiger Ersatzbank-Modus.';
        }

        if (! $this->isValidLcPointsList((string) ($form['options_league_lcpoints'] ?? ''))) {
            $errors[] = 'LC-Punkte müssen eine kommagetrennte Liste von Zahlen sein (z.B. 12,10,8,7,6,5,4,3,2,1).';
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
            'options_league_benchmode' => '',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
            'options_league_remind_hours_before' => 0,
            'options_score_minutes_threshold_upper' => 60,
            'options_score_minutes_threshold_lower' => 30,
            'options_score_minutes_high' => 3,
            'options_score_minutes_middle' => 2,
            'options_score_minutes_low' => 1,
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
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 0,
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
            $value = $options->{$key};
            if ($key === 'options_league_benchmode') {
                $form[$key] = $value === null ? '' : (string) $value;

                continue;
            }
            $form[$key] = $value;
        }

        return $form;
    }

    /**
     * @param  array<string, mixed>  $form
     * @return array<string, int|string|null>
     */
    private function optionsFromForm(array $form, string $pointsMode): array
    {
        $out = [];
        foreach ($this->optionKeys() as $key) {
            if ($key === 'options_league_pointsmode') {
                $out[$key] = $pointsMode === 'old' ? 'old' : 'new';

                continue;
            }
            if ($key === 'options_league_benchmode') {
                $mode = trim((string) ($form[$key] ?? ''));
                $out[$key] = in_array($mode, ['cover', 'bestof'], true) ? $mode : null;

                continue;
            }
            if (str_starts_with($key, 'options_league_') && ! str_ends_with($key, '_before')) {
                $out[$key] = (string) $form[$key];
            } else {
                $out[$key] = (int) $form[$key];
            }
        }

        return $out;
    }

    private function normalizeLcPointsList(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return '';
        }

        $parts = array_map(
            static fn (string $part): string => trim($part),
            explode(',', $raw)
        );

        return implode(',', array_values(array_filter($parts, static fn (string $part): bool => $part !== '')));
    }

    private function isValidLcPointsList(string $raw): bool
    {
        if ($raw === '') {
            return false;
        }

        foreach (explode(',', $raw) as $part) {
            if ($part === '' || ! preg_match('/^-?\d+$/', $part)) {
                return false;
            }
        }

        return true;
    }
}
