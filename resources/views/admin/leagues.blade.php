@extends('layouts.admin')

@section('title', 'Ligen')

@section('content')
    @php
        $form = $data['form'];
        $mode = $data['mode'];
        $items = $data['items'];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-leagues-title">
        <div class="section-head">
            <h2 id="admin-leagues-title">Ligen</h2>
        </div>

        @if (!empty($flashErrors))
            <div class="account-flash account-flash-error" role="alert">
                <strong>Es sind Fehler aufgetreten:</strong>
                <ul>
                    @foreach ($flashErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($answer)
            <div class="account-flash account-flash-ok" role="status">
                {{ $answer }}
            </div>
        @endif

        <form
            class="admin-form admin-league-form"
            method="post"
            enctype="multipart/form-data"
            action="{{ $mode === 'update' ? route('admin.leagues.update', ['league' => $form['league_id']]) : route('admin.leagues.store') }}"
            accept-charset="UTF-8"
        >
            @csrf
            @if ($mode === 'update')
                @method('PUT')
                <input type="hidden" name="league_id" value="{{ $form['league_id'] }}">
            @endif
            <input type="hidden" name="league_symbol" value="{{ $form['league_symbol'] }}">

            <fieldset class="admin-fieldset">
                <legend>Grunddaten</legend>

                <div class="admin-field">
                    <label for="league_title">* Titel</label>
                    <input id="league_title" type="text" name="league_title" value="{{ $form['league_title'] }}" maxlength="255" required>
                </div>

                <div class="admin-field">
                    <label for="league_visible">Sichtbar</label>
                    <select id="league_visible" name="league_visible">
                        <option value="1" @selected((int) $form['league_visible'] === 1)>ja</option>
                        <option value="0" @selected((int) $form['league_visible'] === 0)>nein</option>
                    </select>
                </div>

                <div class="admin-field">
                    <label for="league_archive">Archiv</label>
                    <select id="league_archive" name="league_archive">
                        <option value="0" @selected((int) $form['league_archive'] === 0)>nein (aktuell)</option>
                        <option value="1" @selected((int) $form['league_archive'] === 1)>ja (archiviert)</option>
                    </select>
                </div>
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Symbol</legend>
                <div class="admin-symbol-row">
                    <img class="admin-symbol-preview" src="{{ $form['symbol_url'] }}" alt="" width="64" height="64" loading="lazy">
                    <div class="admin-field admin-field-stack">
                        <label for="league_symbol_file">Bild hochladen</label>
                        <input id="league_symbol_file" type="file" name="league_symbol_file" accept="image/png,image/jpeg,image/gif,image/webp">
                        <p class="hint">PNG, JPEG, GIF oder WebP, max. 2 MB. Leer lassen, um das aktuelle Symbol zu behalten.</p>
                    </div>
                </div>
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Spielmodi</legend>
                <div class="admin-field">
                    <label for="options_league_rankmode">Rangliste</label>
                    <select id="options_league_rankmode" name="options_league_rankmode">
                        <option value="wc" @selected($form['options_league_rankmode'] === 'wc')>WC</option>
                        <option value="points" @selected($form['options_league_rankmode'] === 'points')>Punkte</option>
                    </select>
                </div>
                <div class="admin-field">
                    <label for="options_league_pricemode">Preise</label>
                    <select id="options_league_pricemode" name="options_league_pricemode">
                        <option value="dynamic" @selected($form['options_league_pricemode'] === 'dynamic')>dynamisch</option>
                        <option value="static" @selected($form['options_league_pricemode'] === 'static')>statisch</option>
                    </select>
                </div>
                <div class="admin-field">
                    <label for="options_league_pointsmode">Punkte</label>
                    <select id="options_league_pointsmode" name="options_league_pointsmode">
                        <option value="new" @selected($form['options_league_pointsmode'] === 'new')>neu</option>
                        <option value="old" @selected($form['options_league_pointsmode'] === 'old')>alt</option>
                    </select>
                </div>
                <div class="admin-field">
                    <label for="options_league_wcpoints">WC-Punkte</label>
                    <select id="options_league_wcpoints" name="options_league_wcpoints">
                        <option value="new" @selected($form['options_league_wcpoints'] === 'new')>neu</option>
                        <option value="old" @selected($form['options_league_wcpoints'] === 'old')>alt</option>
                    </select>
                </div>
                <div class="admin-field">
                    <label for="options_league_remind_hours_before">Erinnerung (h)</label>
                    <input id="options_league_remind_hours_before" type="number" name="options_league_remind_hours_before" value="{{ $form['options_league_remind_hours_before'] }}">
                </div>
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Aufstellungslimits</legend>
                <div class="admin-option-grid">
                    @foreach ([
                        'options_lineup_max_players' => 'Max. Spieler',
                        'options_lineup_max_credits' => 'Max. Credits',
                        'options_lineup_max_players_team' => 'Max. pro Team',
                        'options_lineup_min_g' => 'Min. TW',
                        'options_lineup_max_g' => 'Max. TW',
                        'options_lineup_min_d' => 'Min. AB',
                        'options_lineup_max_d' => 'Max. AB',
                        'options_lineup_min_m' => 'Min. MF',
                        'options_lineup_max_m' => 'Max. MF',
                        'options_lineup_min_s' => 'Min. ST',
                        'options_lineup_max_s' => 'Max. ST',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Punktewertung</legend>
                <div class="admin-option-grid">
                    @foreach ([
                        'options_score_minutes' => 'Minuten',
                        'options_score_minutes_treshold' => 'Minuten-Schwelle',
                        'options_score_minutes_gt' => 'Min. > Schwelle',
                        'options_score_minutes_lt' => 'Min. < Schwelle',
                        'options_score_minutes_lt30' => 'Min. < 30',
                        'options_score_goals_g' => 'Tor TW',
                        'options_score_goals_d' => 'Tor AB',
                        'options_score_goals_m' => 'Tor MF',
                        'options_score_goals_s' => 'Tor ST',
                        'options_score_assists' => 'Assist',
                        'options_score_owngoals' => 'Eigentor',
                        'options_score_no_oppgoals_g' => 'Zu-Null TW',
                        'options_score_no_oppgoals_d' => 'Zu-Null AB',
                        'options_score_no_oppgoals_m' => 'Zu-Null MF',
                        'options_score_oppgoals_g' => 'Gegentor TW',
                        'options_score_oppgoals_d' => 'Gegentor AB',
                        'options_score_card_y' => 'Gelb',
                        'options_score_card_yr' => 'Gelb-Rot',
                        'options_score_card_r' => 'Rot',
                        'options_score_penalty_saved' => 'Elfmeter gehalten',
                        'options_score_penalty_lost' => 'Elfmeter verschossen',
                        'options_score_penaltyshootout_save' => 'Elfmeterschießen gehalten',
                        'options_score_penaltyshootout_lost' => 'Elfmeterschießen verschossen',
                        'options_score_penaltyshootout_hit' => 'Elfmeterschießen getroffen',
                        'options_score_high_win' => 'High-Win',
                        'options_score_high_loss' => 'High-Loss',
                        'options_score_high_win_loss_treshold' => 'High-Schwelle',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <div class="admin-actions admin-actions-flush">
                @if ($mode === 'update')
                    <button type="submit" class="admin-submit">Speichern</button>
                    <a class="admin-cancel" href="{{ route('admin.leagues') }}">Abbrechen</a>
                @else
                    <button type="submit" class="admin-submit">Hinzufügen</button>
                @endif
            </div>
        </form>
    </section>

    <section class="panel admin-main" aria-labelledby="admin-leagues-list-title">
        <div class="section-head">
            <h2 id="admin-leagues-list-title">Vorhandene Ligen</h2>
        </div>

        @forelse ($items as $item)
            <article class="admin-list-item">
                <div class="admin-list-body admin-league-list-body">
                    <img class="admin-league-list-icon" src="{{ $item['symbol_url'] }}" alt="" width="40" height="40" loading="lazy">
                    <div>
                        <h3 class="admin-list-title">
                            {{ $item['league_title'] }}
                            <span class="muted">(ID: {{ $item['league_id'] }})</span>
                        </h3>
                        <ul class="admin-game-flags" aria-label="Status">
                            <li class="admin-game-flag admin-game-flag-{{ $item['league_visible'] ? 'ok' : 'off' }}">{{ $item['league_visible'] ? 'sichtbar' : 'unsichtbar' }}</li>
                            <li class="admin-game-flag admin-game-flag-{{ $item['league_archive'] ? 'warn' : 'ok' }}">{{ $item['league_archive'] ? 'archiviert' : 'aktuell' }}</li>
                        </ul>
                    </div>
                </div>
                <div class="admin-list-actions">
                    <a class="admin-icon-btn" href="{{ route('admin.leagues.edit', ['league' => $item['league_id']]) }}" title="Bearbeiten">
                        <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                    </a>
                    <form method="post" action="{{ route('admin.leagues.destroy', ['league' => $item['league_id']]) }}" onsubmit="return confirm('Diese Liga wirklich löschen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-icon-btn" title="Löschen">
                            <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">Noch keine Ligen vorhanden.</p>
        @endforelse
    </section>
@endsection
