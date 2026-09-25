@extends('layouts.admin')

@section('title', 'Teams')

@php
    $form = $data['form'];
    $mode = $data['mode'];
    $items = $data['items'];
    $icons = $data['icons'];
    $tab = match ($data['tab'] ?? 'manual') {
        'auto' => 'auto',
        'auto-uefa' => 'auto-uefa',
        default => 'manual',
    };
    $auto = $data['auto'] ?? ['analyzed' => false, 'source_name' => '', 'present' => [], 'missing' => []];
    $autoUefa = $data['auto_uefa'] ?? ['analyzed' => false, 'source_name' => '', 'league_id' => 0, 'rows' => []];
    $selectedSymbol = $data['selected_symbol'] ?? null;
    $usesIconPicker = (bool) ($data['uses_icon_picker'] ?? true);
    $flashErrors = $errors ?: (session('admin_errors') ?: []);
    $selectedIcon = (string) $form['team_nationality'];
    $autoPresent = is_array($auto['present'] ?? null) ? $auto['present'] : [];
    $autoMissing = is_array($auto['missing'] ?? null) ? $auto['missing'] : [];
    $autoAnalyzed = (bool) ($auto['analyzed'] ?? false);
    $autoSource = (string) ($auto['source_name'] ?? '');
    $autoUefaAnalyzed = (bool) ($autoUefa['analyzed'] ?? false);
    $autoUefaSource = (string) ($autoUefa['source_name'] ?? '');
    $autoUefaRows = is_array($autoUefa['rows'] ?? null) ? $autoUefa['rows'] : [];
    $uefaIdentifier = (string) ($data['uefa_competition_identifier'] ?? '');
    $selectedLeagueId = (int) ($data['selected_league_id'] ?? 0);
    $teamOptions = is_array($data['team_options'] ?? null) ? $data['team_options'] : [];
@endphp

@section('content')
    <section class="panel admin-main" aria-labelledby="admin-teams-title">
        <div class="section-head">
            <h2 id="admin-teams-title">Teams</h2>
        </div>

        <nav class="admin-squad-tabs ffb-tabs" aria-label="Teams-Bereiche">
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'manual' ? ' is-active' : '' }}"
                href="{{ route('admin.teams') }}"
            >
                Teams
            </a>
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'auto' ? ' is-active' : '' }}"
                href="{{ route('admin.teams', ['tab' => 'auto']) }}"
            >
                Auto-Teams
            </a>
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'auto-uefa' ? ' is-active' : '' }}"
                href="{{ route('admin.teams', ['tab' => 'auto-uefa']) }}"
            >
                Auto-Teams (UEFA)
            </a>
        </nav>

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

        @if ($tab === 'auto')
            @php
                $matchplanFiles = is_array($data['matchplan_files'] ?? null) ? $data['matchplan_files'] : [];
            @endphp

            <form
                class="admin-form admin-auto-teams-upload"
                method="post"
                action="{{ route('admin.teams.auto.analyze') }}"
                accept-charset="UTF-8"
            >
                @csrf
                <div class="admin-field">
                    <label for="matchrounds_json">Spielplan-JSON</label>
                    <select id="matchrounds_json" name="matchrounds_json" required @disabled($matchplanFiles === [])>
                        <option value="">— JSON-Datei wählen —</option>
                        @foreach ($matchplanFiles as $file)
                            <option value="{{ $file['name'] }}">{{ $file['label'] }}</option>
                        @endforeach
                    </select>
                    <p class="hint">
                        Dateien aus <code>public/data/match/*.json</code>.
                        Erwartete Struktur:
                    </p>
                    <pre class="hint admin-json-hint">{
  "spieltage": [
    {
      "spieltag": 1,
      "spiele": [
        {
          "datum": "2026-09-24",
          "heim": "Niederlande",
          "gast": "Deutschland"
        },
        {
          "datum": "2026-09-24",
          "heim": "Serbien",
          "gast": "Griechenland"
        }
      ]
    }
  ]
}</pre>
                </div>
                <div class="admin-actions">
                    <button type="submit" class="admin-submit" @disabled($matchplanFiles === [])>Teams prüfen</button>
                </div>
            </form>

            @if ($matchplanFiles === [])
                <p class="hint">Noch keine JSON-Dateien unter <code>public/data/match</code> gefunden.</p>
            @endif

            @if ($autoAnalyzed)
                <div class="admin-auto-teams-result">
                    @if ($autoSource !== '')
                        <p class="muted">Datei: {{ $autoSource }}</p>
                    @endif

                    <div class="admin-auto-teams-columns">
                        <section class="admin-auto-teams-column" aria-labelledby="admin-auto-present-title">
                            <h3 id="admin-auto-present-title">
                                Bereits vorhanden
                                <span class="admin-squad-count">{{ count($autoPresent) }}</span>
                            </h3>
                            @if (count($autoPresent) === 0)
                                <p class="muted">Keine der Teams aus der Datei sind bereits angelegt.</p>
                            @else
                                <ul class="admin-auto-teams-list">
                                    @foreach ($autoPresent as $present)
                                        <li>
                                            <strong>{{ $present['team_name'] }}</strong>
                                            <span class="muted">#{{ $present['team_id'] }}</span>
                                            @if (($present['team_nationality'] ?? '') !== '')
                                                <span class="muted">{{ $present['team_nationality'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </section>

                        <section class="admin-auto-teams-column" aria-labelledby="admin-auto-missing-title">
                            <h3 id="admin-auto-missing-title">
                                Noch nicht vorhanden
                                <span class="admin-squad-count">{{ count($autoMissing) }}</span>
                            </h3>
                            @if (count($autoMissing) === 0)
                                <p class="muted">Alle Teams aus der Datei sind bereits in der Datenbank.</p>
                            @else
                                <form
                                    class="admin-form"
                                    method="post"
                                    action="{{ route('admin.teams.auto.store') }}"
                                    accept-charset="UTF-8"
                                >
                                    @csrf
                                    <input type="hidden" name="source_name" value="{{ $autoSource }}">

                                    <div class="admin-auto-teams-table-wrap">
                                        <table class="admin-auto-teams-table">
                                            <thead>
                                                <tr>
                                                    <th>Teamname *</th>
                                                    <th>Symbol</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($autoMissing as $index => $missing)
                                                    <tr>
                                                        <td>
                                                            <input
                                                                type="text"
                                                                name="teams[{{ $index }}][team_name]"
                                                                value="{{ $missing['team_name'] ?? '' }}"
                                                                maxlength="255"
                                                                required
                                                                aria-label="Teamname {{ $index + 1 }}"
                                                            >
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="text"
                                                                name="teams[{{ $index }}][team_nationality]"
                                                                value="{{ $missing['team_nationality'] ?? '' }}"
                                                                maxlength="32"
                                                                placeholder="z. B. ger"
                                                                aria-label="Symbol {{ $index + 1 }}"
                                                            >
                                                        </td>
                                                        <td>
                                                            <select
                                                                name="teams[{{ $index }}][team_status]"
                                                                aria-label="Status {{ $index + 1 }}"
                                                            >
                                                                <option value="1" @selected((int) ($missing['team_status'] ?? 1) === 1)>aktiv</option>
                                                                <option value="0" @selected((int) ($missing['team_status'] ?? 1) === 0)>inaktiv</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="admin-actions">
                                        <button type="submit" class="admin-submit">Teams anlegen</button>
                                    </div>
                                </form>
                            @endif
                        </section>
                    </div>
                </div>
            @endif
        @elseif ($tab === 'auto-uefa')
            @php
                $selectedLeagueTitle = (string) (($data['selected_league']['league_title'] ?? '') ?: '');
            @endphp

            <p class="hint">
                Nutzt den UEFA-Competition-Identifier der ausgewählten Liga
                @if ($selectedLeagueTitle !== '')
                    (<strong>{{ $selectedLeagueTitle }}</strong>)
                @endif.
                Format: <code>competitionId=…&amp;seasonYear=…&amp;competitionPhase=TOURNAMENT</code>
            </p>

            @if ($selectedLeagueId <= 0)
                <p class="hint">Bitte zuerst unter <a href="{{ url('/admin') }}">Ligen</a> eine Liga auswählen.</p>
            @elseif ($uefaIdentifier === '')
                <p class="hint">
                    Für diese Liga ist kein Identifier hinterlegt.
                    Bitte unter <a href="{{ route('admin.leagues') }}">Ligen</a> setzen.
                </p>
            @else
                <p class="muted">Identifier: <code>{{ $uefaIdentifier }}</code></p>

                <form
                    class="admin-form admin-auto-teams-upload"
                    method="post"
                    action="{{ route('admin.teams.auto-uefa.analyze') }}"
                    accept-charset="UTF-8"
                >
                    @csrf
                    <input type="hidden" name="league_id" value="{{ $selectedLeagueId }}">
                    <div class="admin-actions">
                        <button type="submit" class="admin-submit">Teams prüfen</button>
                    </div>
                </form>
            @endif

            @if ($autoUefaAnalyzed && count($autoUefaRows) > 0)
                <div class="admin-auto-teams-result">
                    @if ($autoUefaSource !== '')
                        <p class="muted">Quelle: {{ $autoUefaSource }}</p>
                    @endif

                    <form
                        class="admin-form"
                        id="admin-auto-uefa-teams-form"
                        method="post"
                        action="{{ route('admin.teams.auto-uefa.store') }}"
                        accept-charset="UTF-8"
                    >
                        @csrf
                        <input type="hidden" name="source_name" value="{{ $autoUefaSource }}">
                        <input type="hidden" name="league_id" value="{{ (int) ($autoUefa['league_id'] ?? $selectedLeagueId) }}">
                        {{-- One JSON field avoids PHP max_input_vars truncating large team lists. --}}
                        <input type="hidden" name="rows_json" id="admin-auto-uefa-teams-rows-json" value="">

                        <div class="admin-auto-teams-table-wrap admin-auto-uefa-teams-wrap">
                            <table class="admin-auto-teams-table admin-auto-uefa-teams-table" id="admin-auto-uefa-teams-table">
                                <thead>
                                    <tr>
                                        <th>Zuordnung (FFB)</th>
                                        <th>UEFA-Name (DE)</th>
                                        <th>Nat.</th>
                                        <th>UEFA-Code</th>
                                        <th>team_uefa_id</th>
                                        <th>team_team_code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($autoUefaRows as $index => $row)
                                        @php
                                            $isMatched = (string) ($row['match_status'] ?? '') === 'matched';
                                            $createNew = (int) ($row['create_new'] ?? 0) === 1;
                                            $rowTeamId = (int) ($row['team_id'] ?? 0);
                                            $rowPayload = [
                                                'uefa_name' => (string) ($row['uefa_name'] ?? ''),
                                                'uefa_id' => (string) ($row['uefa_id'] ?? ''),
                                                'uefa_team_code' => (string) ($row['uefa_team_code'] ?? ''),
                                                'match_status' => (string) ($row['match_status'] ?? 'unmatched'),
                                                'team_name' => (string) (($row['team_name'] ?? '') !== '' ? $row['team_name'] : ($row['uefa_name'] ?? '')),
                                                'team_id' => $rowTeamId,
                                                'create_new' => $createNew ? '1' : '0',
                                                'team_nationality' => (string) ($row['team_nationality'] ?? ''),
                                                'team_uefa_id' => (string) ($row['team_uefa_id'] ?? ''),
                                                'team_team_code' => (string) ($row['team_team_code'] ?? ''),
                                            ];
                                        @endphp
                                        <tr
                                            class="admin-auto-uefa-row{{ $isMatched ? ' is-matched' : ' is-unmatched' }}"
                                            data-row='@json($rowPayload)'
                                        >
                                            <td class="admin-auto-uefa-match-cell">
                                                <select
                                                    class="admin-auto-uefa-team-id"
                                                    aria-label="FFB-Team {{ $index + 1 }}"
                                                >
                                                    <option value="">— zuordnen —</option>
                                                    <option value="0" data-create-new="1" @selected($createNew)>Neu anlegen</option>
                                                    @foreach ($teamOptions as $option)
                                                        <option
                                                            value="{{ $option['team_id'] }}"
                                                            @selected(! $createNew && $rowTeamId === (int) $option['team_id'])
                                                        >
                                                            {{ $option['team_label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input
                                                    type="hidden"
                                                    value="{{ $createNew ? '1' : '0' }}"
                                                    class="admin-auto-uefa-create-new"
                                                >
                                                <input
                                                    type="hidden"
                                                    value="{{ $rowPayload['match_status'] }}"
                                                    class="admin-auto-uefa-match-status"
                                                >
                                                <input
                                                    type="hidden"
                                                    value="{{ $rowPayload['team_name'] }}"
                                                    class="admin-auto-uefa-team-name"
                                                >
                                            </td>
                                            <td>{{ $row['uefa_name'] ?? '' }}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    value="{{ $row['team_nationality'] ?? '' }}"
                                                    maxlength="32"
                                                    class="admin-auto-uefa-nat"
                                                    aria-label="Nationalität {{ $index + 1 }}"
                                                    readonly
                                                    title="Nur zur Anzeige — wird bei Zuordnung bestehender Teams nicht gespeichert"
                                                >
                                            </td>
                                            <td><code>{{ $row['uefa_team_code'] ?? '' }}</code></td>
                                            <td>
                                                <input
                                                    type="text"
                                                    value="{{ $row['team_uefa_id'] ?? '' }}"
                                                    maxlength="64"
                                                    required
                                                    class="admin-auto-uefa-id"
                                                    aria-label="UEFA-ID {{ $index + 1 }}"
                                                >
                                            </td>
                                            <td>
                                                <input
                                                    type="text"
                                                    value="{{ $row['team_team_code'] ?? '' }}"
                                                    maxlength="16"
                                                    required
                                                    class="admin-auto-uefa-code"
                                                    aria-label="Team-Code {{ $index + 1 }}"
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-actions">
                            <button type="submit" class="admin-submit" id="admin-auto-uefa-save" disabled>
                                Speichern
                            </button>
                            <span class="muted" id="admin-auto-uefa-save-hint">Alle Teams müssen zugeordnet oder als neu markiert sein.</span>
                        </div>
                    </form>
                </div>
            @elseif ($autoUefaAnalyzed)
                <p class="muted">Keine UEFA-Teams gefunden.</p>
            @endif
        @else
        <form
            class="admin-form"
            method="post"
            enctype="multipart/form-data"
            action="{{ $mode === 'update' ? route('admin.teams.update', ['team' => $form['team_id']]) : route('admin.teams.store') }}"
            accept-charset="UTF-8"
        >
            @csrf
            @if ($mode === 'update')
                @method('PUT')
            @endif
            @if (!$usesIconPicker)
                <input type="hidden" name="team_nationality" value="{{ $selectedIcon }}">
            @endif

            <div class="admin-field">
                <label for="team_name">* Teamname</label>
                <input id="team_name" type="text" name="team_name" value="{{ $form['team_name'] }}" maxlength="255" required>
            </div>

            <div class="admin-field">
                <label for="team_status">Status</label>
                <select id="team_status" name="team_status">
                    <option value="1" @selected((int) $form['team_status'] === 1)>aktiv</option>
                    <option value="0" @selected((int) $form['team_status'] === 0)>inaktiv</option>
                </select>
            </div>

            <div class="admin-field">
                <label for="team_uefa_id">UEFA-ID</label>
                <input id="team_uefa_id" type="text" name="team_uefa_id" value="{{ $form['team_uefa_id'] ?? '' }}" maxlength="64">
            </div>

            <div class="admin-field">
                <label for="team_team_code">UEFA Team-Code</label>
                <input id="team_team_code" type="text" name="team_team_code" value="{{ $form['team_team_code'] ?? '' }}" maxlength="16">
            </div>

            <fieldset class="admin-fieldset">
                <legend>Symbol (Flagge / Logo)</legend>

                <div class="admin-symbol-current" id="team-symbol-current">
                    <div class="admin-symbol-preview-pair">
                        <div class="admin-symbol-slot" data-role="flag-slot">
                            <span class="admin-symbol-slot-label">Flagge / Logo</span>
                            <span id="team-symbol-flag-html" @if (!$selectedSymbol) hidden @endif>
                                @if ($selectedSymbol)
                                    {!! $selectedSymbol['html'] !!}
                                @endif
                            </span>
                            <span id="team-symbol-flag-empty" class="muted" @if ($selectedSymbol) hidden @endif>keins</span>
                        </div>
                        <div class="admin-symbol-slot" data-role="shirt-slot">
                            <span class="admin-symbol-slot-label">Trikot</span>
                            <img
                                id="team-symbol-shirt"
                                class="admin-symbol-shirt"
                                src="{{ $selectedSymbol['shirt_url'] ?? '' }}"
                                alt=""
                                height="40"
                                @if (!($selectedSymbol['has_shirt'] ?? false)) hidden @endif
                            >
                            <span
                                id="team-symbol-shirt-empty"
                                class="muted"
                                @if (($selectedSymbol['has_shirt'] ?? false)) hidden @endif
                            >
                                @if ($selectedSymbol)
                                    Kein Trikot vorhanden
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="admin-symbol-current-meta">
                        <strong id="team-symbol-key">{{ $selectedSymbol['key'] ?? 'Kein Symbol gewählt' }}</strong>
                        <span id="team-symbol-label" class="muted">{{ $selectedSymbol['label'] ?? '' }}</span>
                        @if ($usesIconPicker)
                            <button type="button" class="admin-cancel" id="team-icon-toggle">
                                {{ $selectedIcon !== '' ? 'Symbol ändern' : 'Symbol wählen' }}
                            </button>
                        @else
                            <span class="muted">Flagge wird automatisch aus dem Ländercode abgeleitet.</span>
                        @endif
                    </div>
                </div>

                <div
                    class="admin-shirt-upload"
                    id="team-shirt-upload"
                    @if (! $selectedSymbol) hidden @endif
                >
                    <div class="admin-field admin-field-stack">
                        <label for="team_shirt_file">Trikot hochladen</label>
                        <input id="team_shirt_file" type="file" name="team_shirt_file" accept="image/png,image/jpeg,image/gif,image/webp">
                        <p class="hint">PNG/JPEG/GIF/WebP, max. 2 MB. Wird als <code>{{ $selectedSymbol['shirt_path_hint'] ?? ('shirts/<team_id>/'.($selectedIcon ?: 'key').'.png') }}</code> gespeichert.</p>
                    </div>
                </div>

                @if ($usesIconPicker)
                    <div class="admin-icon-chooser" id="team-icon-chooser" hidden>
                        <div class="admin-field">
                            <label for="team_icon_filter">Suchen</label>
                            <input id="team_icon_filter" type="search" value="" placeholder="z. B. aut, rapid, wernberg" autocomplete="off">
                        </div>

                        <div class="admin-icon-picker" id="team-icon-picker" role="radiogroup" aria-label="Team-Symbol">
                            <label class="admin-icon-option @if ($selectedIcon === '') is-selected @endif">
                                <input type="radio" name="team_nationality" value="" @checked($selectedIcon === '')>
                                <span class="admin-icon-none">keins</span>
                            </label>
                            @foreach ($icons as $icon)
                                <label
                                    class="admin-icon-option @if ($selectedIcon === $icon['key']) is-selected @endif"
                                    data-icon-key="{{ $icon['key'] }}"
                                    data-icon-label="{{ $icon['label'] }}"
                                    data-icon-url="{{ $icon['url'] }}"
                                    data-icon-html="{{ $icon['key'] !== '' ? \App\Support\Flag::html($icon['key']) : '' }}"
                                    data-shirt-url="{{ $icon['shirt_url'] ?? '' }}"
                                    data-has-shirt="{{ !empty($icon['has_shirt']) ? '1' : '0' }}"
                                    title="{{ $icon['label'] }} ({{ $icon['key'] }})"
                                >
                                    <input type="radio" name="team_nationality" value="{{ $icon['key'] }}" @checked($selectedIcon === $icon['key'])>
                                    <img src="{{ $icon['url'] }}" alt="" width="28" height="21" loading="lazy">
                                    <span>{{ $icon['key'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($usesIconPicker)
                    <div class="admin-symbol-row admin-icon-upload">
                        <div class="admin-field admin-field-stack">
                            <label for="team_icon_key">Neues Symbol — Dateiname</label>
                            <input id="team_icon_key" type="text" name="team_icon_key" value="{{ $form['team_icon_key'] }}" maxlength="64" placeholder="z. B. sturm-graz">
                        </div>
                        <div class="admin-field admin-field-stack">
                            <label for="team_icon_file">Bild hochladen</label>
                            <input id="team_icon_file" type="file" name="team_icon_file" accept="image/png,image/jpeg,image/gif,image/webp">
                            <p class="hint">PNG/JPEG/GIF/WebP, max. 2 MB. Wird als <code>{name}.gif</code> gespeichert und ausgewählt.</p>
                        </div>
                    </div>
                @endif
            </fieldset>

            <fieldset class="admin-fieldset">
                <legend>Externe IDs</legend>

                <div class="admin-field">
                    <label for="teamfid_fid_tm">TM-ID (transfermarkt.at)</label>
                    <input id="teamfid_fid_tm" type="text" name="teamfid_fid_tm" value="{{ $form['teamfid_fid_tm'] }}" maxlength="255" placeholder="z. B. 170">
                </div>

                <div class="admin-field">
                    <label for="teamfid_name_tm">TM-Slug</label>
                    <input id="teamfid_name_tm" type="text" name="teamfid_name_tm" value="{{ $form['teamfid_name_tm'] }}" maxlength="255" placeholder="z. B. fc-red-bull-salzburg">
                </div>

                <div class="admin-field">
                    <label for="teamfid_name_wf">WF-Slug (weltfussball.de)</label>
                    <input id="teamfid_name_wf" type="text" name="teamfid_name_wf" value="{{ $form['teamfid_name_wf'] }}" maxlength="255" placeholder="z. B. fc-red-bull-salzburg">
                </div>

                <div class="admin-field">
                    <label for="teamfid_url_foe">ÖFB / FOE (vereine.oefb.at)</label>
                    <input id="teamfid_url_foe" type="text" name="teamfid_url_foe" value="{{ $form['teamfid_url_foe'] }}" maxlength="255" placeholder="ID oder volle URL">
                </div>
            </fieldset>

            <div class="admin-actions">
                @if ($mode === 'update')
                    <button type="submit" class="admin-submit">Speichern</button>
                    <a class="admin-cancel" href="{{ route('admin.teams') }}">Abbrechen</a>
                @else
                    <button type="submit" class="admin-submit">Hinzufügen</button>
                @endif
            </div>
        </form>
        @endif
    </section>

    @if ($tab === 'manual')
    <section class="panel admin-main" aria-labelledby="admin-teams-list-title">
        <div class="section-head">
            <h2 id="admin-teams-list-title">Vorhandene Teams</h2>
        </div>

        @forelse ($items as $item)
            <article class="admin-list-item">
                <div class="admin-list-body">
                    <div class="admin-match-meta">
                        <img
                            src="{{ $legacyBase }}images/ffb/symbols/{{ $item['team_status'] ? 'status_pos.png' : 'status_neg.png' }}"
                            alt="{{ $item['team_status'] ? 'aktiv' : 'inaktiv' }}"
                            width="16"
                            height="16"
                            loading="lazy"
                        >
                        @if (($item['flag_html'] ?? '') !== '')
                            {!! $item['flag_html'] !!}
                        @elseif (! empty($item['flag_url']))
                            <img class="ffb-flag ffb-flag-img" src="{{ $item['flag_url'] }}" alt="" width="20" height="15" loading="lazy">
                        @endif
                        <span class="muted">#{{ $item['team_id'] }}</span>
                        @if ($item['team_nationality'] !== '')
                            <span class="muted">{{ $item['team_icon_label'] }}</span>
                        @endif
                    </div>
                    <h3 class="admin-list-title">{{ $item['team_name'] }}</h3>
                    <p class="admin-team-ids muted">
                        @if ($item['teamfid_fid_tm'] !== '' || $item['teamfid_url_tm'] !== '')
                            @if ($item['teamfid_url_tm'] !== '')
                                <a href="{{ $item['teamfid_url_tm'] }}" target="_blank" rel="noopener noreferrer">TM</a>
                            @else
                                <span>TM {{ $item['teamfid_fid_tm'] }}</span>
                            @endif
                        @endif
                        @if ($item['teamfid_name_wf'] !== '' || $item['teamfid_url_wf'] !== '')
                            @if ($item['teamfid_url_wf'] !== '')
                                <a href="{{ $item['teamfid_url_wf'] }}" target="_blank" rel="noopener noreferrer">WF</a>
                            @else
                                <span>WF {{ $item['teamfid_name_wf'] }}</span>
                            @endif
                        @endif
                        @if ($item['teamfid_url_foe'] !== '')
                            <a href="{{ $item['teamfid_url_foe'] }}" target="_blank" rel="noopener noreferrer">ÖFB</a>
                        @endif
                    </p>
                </div>
                <div class="admin-list-actions">
                    <a class="admin-icon-btn" href="{{ route('admin.teams.edit', ['team' => $item['team_id']]) }}" title="Bearbeiten">
                        <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                    </a>
                    <form method="post" action="{{ route('admin.teams.destroy', ['team' => $item['team_id']]) }}" onsubmit="return confirm('Dieses Team wirklich löschen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-icon-btn" title="Löschen">
                            <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">Noch keine Teams.</p>
        @endforelse
    </section>
    @endif
@endsection

@if ($tab === 'manual')
@push('scripts')
<script>
(function () {
    const picker = document.getElementById('team-icon-picker');
    const chooser = document.getElementById('team-icon-chooser');
    const toggle = document.getElementById('team-icon-toggle');
    const filter = document.getElementById('team_icon_filter');
    const flagHtml = document.getElementById('team-symbol-flag-html');
    const flagEmpty = document.getElementById('team-symbol-flag-empty');
    const shirtImg = document.getElementById('team-symbol-shirt');
    const shirtEmpty = document.getElementById('team-symbol-shirt-empty');
    const keyEl = document.getElementById('team-symbol-key');
    const labelEl = document.getElementById('team-symbol-label');
    const shirtUpload = document.getElementById('team-shirt-upload');
    const shirtHint = shirtUpload ? shirtUpload.querySelector('.hint') : null;
    const teamId = @json((string) ($form['team_id'] ?? ''));
    const existingTeamShirtUrl = @json($selectedSymbol['shirt_url'] ?? '');
    if (!picker || !chooser || !toggle || !flagHtml) return;

    function shirtPathHint(key) {
        const nat = key || 'key';
        if (teamId) {
            return 'shirts/' + teamId + '/' + nat + '.png';
        }
        return 'shirts/<team_id>/' + nat + '.png';
    }

    function setPreview(option) {
        const key = option ? (option.getAttribute('data-icon-key') || '') : '';
        const label = option ? (option.getAttribute('data-icon-label') || '') : '';
        const html = option ? (option.getAttribute('data-icon-html') || '') : '';
        const shirtUrl = existingTeamShirtUrl || '';
        const hasShirt = !!shirtUrl;

        if (key && html) {
            flagHtml.innerHTML = html;
            flagHtml.hidden = false;
            flagEmpty.hidden = true;
            keyEl.textContent = key;
            labelEl.textContent = label && label !== key ? label : '';
            toggle.textContent = 'Symbol ändern';
        } else {
            flagHtml.innerHTML = '';
            flagHtml.hidden = true;
            flagEmpty.hidden = false;
            keyEl.textContent = 'Kein Symbol gewählt';
            labelEl.textContent = '';
            toggle.textContent = 'Symbol wählen';
        }

        if (hasShirt && shirtUrl) {
            shirtImg.src = shirtUrl;
            shirtImg.hidden = false;
            shirtEmpty.hidden = true;
        } else {
            shirtImg.removeAttribute('src');
            shirtImg.hidden = true;
            shirtEmpty.hidden = false;
            shirtEmpty.textContent = key ? 'Kein Trikot vorhanden' : '—';
        }

        if (shirtUpload) {
            if (key) {
                shirtUpload.hidden = false;
                if (shirtHint) {
                    shirtHint.innerHTML =
                        'PNG/JPEG/GIF/WebP, max. 2 MB. Wird als <code>' +
                        shirtPathHint(key) +
                        '</code> gespeichert.';
                }
            } else {
                shirtUpload.hidden = true;
                const input = document.getElementById('team_shirt_file');
                if (input) input.value = '';
            }
        }
    }

    toggle.addEventListener('click', function () {
        const open = chooser.hasAttribute('hidden');
        if (open) {
            chooser.removeAttribute('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            if (filter) filter.focus();
        } else {
            chooser.setAttribute('hidden', '');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    picker.addEventListener('change', function (event) {
        const input = event.target;
        if (!(input instanceof HTMLInputElement) || input.name !== 'team_nationality') return;
        picker.querySelectorAll('.admin-icon-option').forEach(function (el) {
            el.classList.toggle('is-selected', el.contains(input) && input.checked);
        });
        const option = input.value === '' ? null : input.closest('.admin-icon-option');
        setPreview(option);
        chooser.setAttribute('hidden', '');
        toggle.setAttribute('aria-expanded', 'false');
    });

    if (filter) {
        filter.addEventListener('input', function () {
            const q = filter.value.trim().toLowerCase();
            picker.querySelectorAll('.admin-icon-option[data-icon-key]').forEach(function (el) {
                const key = (el.getAttribute('data-icon-key') || '').toLowerCase();
                const label = (el.getAttribute('data-icon-label') || '').toLowerCase();
                el.hidden = q !== '' && !key.includes(q) && !label.includes(q);
            });
        });
    }
})();
</script>
@endpush
@endif

@if ($tab === 'auto-uefa')
@push('scripts')
<script src="{{ url('js/admin-bulk-json-form.js') }}"></script>
<script>
(function () {
    const table = document.getElementById('admin-auto-uefa-teams-table');
    const saveBtn = document.getElementById('admin-auto-uefa-save');
    const hint = document.getElementById('admin-auto-uefa-save-hint');
    if (!table || !saveBtn) {
        return;
    }

    function refreshSaveState() {
        const rows = table.querySelectorAll('tr.admin-auto-uefa-row');
        let ready = rows.length > 0;
        rows.forEach(function (row) {
            const select = row.querySelector('select.admin-auto-uefa-team-id');
            if (!select) {
                ready = false;
                return;
            }
            const option = select.options[select.selectedIndex];
            const isCreate = option && option.getAttribute('data-create-new') === '1';
            const teamId = Number(select.value || 0);
            if (!isCreate && teamId <= 0) {
                ready = false;
            }
        });
        saveBtn.disabled = !ready;
        if (hint) {
            hint.hidden = ready;
        }
    }

    table.addEventListener('change', function (event) {
        const select = event.target.closest('select.admin-auto-uefa-team-id');
        if (!select) {
            return;
        }
        const row = select.closest('tr.admin-auto-uefa-row');
        if (!row) {
            return;
        }
        const createNew = row.querySelector('.admin-auto-uefa-create-new');
        const teamName = row.querySelector('.admin-auto-uefa-team-name');
        const matchStatus = row.querySelector('.admin-auto-uefa-match-status');
        const option = select.options[select.selectedIndex];
        const isCreate = option && option.getAttribute('data-create-new') === '1';
        const teamId = Number(select.value || 0);
        if (createNew) {
            createNew.value = isCreate ? '1' : '0';
        }
        if (matchStatus) {
            matchStatus.value = (isCreate || teamId > 0) ? 'matched' : 'unmatched';
        }
        row.classList.toggle('is-matched', isCreate || teamId > 0);
        row.classList.toggle('is-unmatched', !isCreate && teamId <= 0);
        if (teamName && option) {
            if (isCreate) {
                // keep UEFA name for new teams
            } else if (teamId > 0) {
                teamName.value = option.textContent.trim();
            }
        }
        refreshSaveState();
    });

    AdminBulkJsonForm.bind({
        form: '#admin-auto-uefa-teams-form',
        hidden: '#admin-auto-uefa-teams-rows-json',
        rowSelector: 'tr.admin-auto-uefa-row',
        fields: {
            team_id: 'select.admin-auto-uefa-team-id',
            create_new: '.admin-auto-uefa-create-new',
            match_status: '.admin-auto-uefa-match-status',
            team_name: '.admin-auto-uefa-team-name',
            team_nationality: '.admin-auto-uefa-nat',
            team_uefa_id: '.admin-auto-uefa-id',
            team_team_code: '.admin-auto-uefa-code'
        }
    });

    refreshSaveState();
})();
</script>
@endpush
@endif
