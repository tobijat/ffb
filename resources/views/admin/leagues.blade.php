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
                    <label for="options_league_rankmode">Modus für Rangliste</label>
                    <select id="options_league_rankmode" name="options_league_rankmode">
                        <option value="lc" @selected($form['options_league_rankmode'] === 'lc')>LC</option>
                        <option value="points" @selected($form['options_league_rankmode'] === 'points')>Punkte</option>
                    </select>
                </div>
                <div class="admin-field">
                    <label for="options_league_pricemode">Preisberechnung</label>
                    <select id="options_league_pricemode" name="options_league_pricemode">
                        <option value="dynamic" @selected($form['options_league_pricemode'] === 'dynamic')>dynamisch</option>
                        <option value="static" @selected($form['options_league_pricemode'] === 'static')>statisch</option>
                    </select>
                </div>
                <div class="admin-field">
                    <label for="options_league_lcpoints">LigaCup Punkteverteilung</label>
                    <input
                        id="options_league_lcpoints"
                        type="text"
                        name="options_league_lcpoints"
                        value="{{ $form['options_league_lcpoints'] }}"
                        maxlength="255"
                        placeholder="z.B. 12,10,8,7,6,5,4,3,2,1"
                        required
                    >
                </div>
                <div class="admin-field">
                    <label for="options_league_remind_hours_before">Aufstellungserinnerung (h)</label>
                    <input id="options_league_remind_hours_before" type="number" name="options_league_remind_hours_before" value="{{ $form['options_league_remind_hours_before'] }}">
                </div>
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Aufstellungslimits</legend>
                <div class="admin-option-grid">
                    @foreach ([
                        'options_lineup_max_players' => 'Max. Spieler / Aufstellung',
                        'options_lineup_max_credits' => 'Max. Credits / Aufstellung',
                        'options_lineup_max_players_team' => 'Max. Spieler vom selben Team',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>
                <div class="admin-lineup-pos-grid">
                    @foreach ([
                        ['options_lineup_min_g', 'Min. Spieler als Goalie', 'options_lineup_max_g', 'Max. Spieler als Goalie'],
                        ['options_lineup_min_d', 'Min. Spieler in Abwehr', 'options_lineup_max_d', 'Max. Spieler in Abwehr'],
                        ['options_lineup_min_m', 'Min. Spieler inMittelfeld', 'options_lineup_max_m', 'Max. Spieler in Mittelfeld'],
                        ['options_lineup_min_s', 'Min. Spieler in Angriff', 'options_lineup_max_s', 'Max. Spieler in Angriff'],
                    ] as [$minName, $minLabel, $maxName, $maxLabel])
                        <div class="admin-option-field">
                            <label for="{{ $minName }}">{{ $minLabel }}</label>
                            <input id="{{ $minName }}" type="number" name="{{ $minName }}" value="{{ $form[$minName] }}">
                        </div>
                        <div class="admin-option-field">
                            <label for="{{ $maxName }}">{{ $maxLabel }}</label>
                            <input id="{{ $maxName }}" type="number" name="{{ $maxName }}" value="{{ $form[$maxName] }}">
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Punktewertung</legend>

                <div class="admin-score-minutes-grid admin-score-minutes-thresholds">
                    @foreach ([
                        'options_score_minutes_threshold_lower' => 'Einsatzminuten: untere Schwelle',
                        'options_score_minutes_threshold_upper' => 'Einsatzminuten: obere Schwelle',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>

                <div class="admin-score-minutes-grid admin-score-minutes-points">
                    @foreach ([
                        'options_score_minutes_low' => 'Einsatz-Punkte: bis untere Schwelle',
                        'options_score_minutes_middle' => 'Einsatz-Punkte: zwischen Schwellen',
                        'options_score_minutes_high' => 'Einsatz-Punkte: ab oberer Schwelle',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>

                <hr class="admin-form-separator">

                <div class="admin-option-grid">
                    @foreach ([
                        'options_score_goals_g' => 'Tor von Goalie',
                        'options_score_goals_d' => 'Tor von Abwehrspieler',
                        'options_score_goals_m' => 'Tor von Mittelfeldspieler',
                        'options_score_goals_s' => 'Tor von Angreifer',
                        'options_score_assists' => 'Tor-Assist',
                        'options_score_owngoals' => 'Eigentor',
                        'options_score_penalty_saved' => 'Elfmeter gehalten',
                        'options_score_penalty_lost' => 'Elfmeter verschossen',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>

                <hr class="admin-form-separator">

                <div class="admin-option-grid">
                    @foreach ([
                        'options_score_no_oppgoals_g' => 'Goalie: kein Gegentor',
                        'options_score_no_oppgoals_d' => 'Abwehr: kein Gegentor',
                        'options_score_no_oppgoals_m' => 'Mittelfeld: kein Gegentor',
                        'options_score_oppgoals_g' => 'pro Gegentor Goalie',
                        'options_score_oppgoals_d' => 'pro Gegentor Abwehr',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>

                <hr class="admin-form-separator">

                <div class="admin-option-grid">
                    @foreach ([
                        'options_score_card_y' => 'Gelbe Karte',
                        'options_score_card_yr' => 'Gelb-Rote Karte',
                        'options_score_card_r' => 'Rote Karte',
                    ] as $name => $label)
                        <div class="admin-option-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <input id="{{ $name }}" type="number" name="{{ $name }}" value="{{ $form[$name] }}">
                        </div>
                    @endforeach
                </div>

                <hr class="admin-form-separator">

                <div class="admin-option-grid">
                    @foreach ([
                        'options_score_penaltyshootout_save' => 'im Elfmeterschießen gehalten',
                        'options_score_penaltyshootout_lost' => 'im Elfmeterschießen verschossen',
                        'options_score_penaltyshootout_hit' => 'im Elfmeterschießen getroffen',
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
