@extends('layouts.admin')

@section('title', 'Spiele')

@php
    $form = $data['form'];
    $mode = $data['mode'];
    $items = $data['items'];
    $leagues = $data['leagues'];
    $matchrounds = $data['matchrounds'];
    $teams = $data['teams'];
    $selectedLeagueId = (int) $data['selected_league_id'];
    $selectedLeagueTitle = $data['selected_league_title'];
    $tab = match ($data['tab'] ?? 'manual') {
        'auto' => 'auto',
        'auto-uefa' => 'auto-uefa',
        default => 'manual',
    };
    $auto = $data['auto'] ?? ['analyzed' => false, 'source_name' => '', 'league_id' => 0, 'present' => [], 'matches' => []];
    $autoUefa = $data['auto_uefa'] ?? ['analyzed' => false, 'source_name' => '', 'league_id' => 0, 'rows' => []];
    $uefaIdentifier = (string) ($data['uefa_competition_identifier'] ?? '');
    $flashErrors = $errors ?: (session('admin_errors') ?: []);
    $autoPresent = is_array($auto['present'] ?? null) ? $auto['present'] : [];
    $autoMatches = is_array($auto['matches'] ?? null) ? $auto['matches'] : [];
    $autoAnalyzed = (bool) ($auto['analyzed'] ?? false);
    $autoSource = (string) ($auto['source_name'] ?? '');
    $autoUefaAnalyzed = (bool) ($autoUefa['analyzed'] ?? false);
    $autoUefaSource = (string) ($autoUefa['source_name'] ?? '');
    $autoUefaRows = is_array($autoUefa['rows'] ?? null) ? $autoUefa['rows'] : [];
@endphp

@section('content')
    <section class="panel admin-main" aria-labelledby="admin-matches-title">
        <div class="section-head">
            <h2 id="admin-matches-title">Spiele</h2>
        </div>

        <nav class="admin-squad-tabs ffb-tabs" aria-label="Spiele-Bereiche">
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'manual' ? ' is-active' : '' }}"
                href="{{ route('admin.matches', array_filter(['league_id' => $selectedLeagueId > 0 ? $selectedLeagueId : null])) }}"
            >
                Spiele
            </a>
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'auto' ? ' is-active' : '' }}"
                href="{{ route('admin.matches', array_filter(['tab' => 'auto', 'league_id' => $selectedLeagueId > 0 ? $selectedLeagueId : null])) }}"
            >
                Auto-Matches
            </a>
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'auto-uefa' ? ' is-active' : '' }}"
                href="{{ route('admin.matches', array_filter(['tab' => 'auto-uefa', 'league_id' => $selectedLeagueId > 0 ? $selectedLeagueId : null])) }}"
            >
                Auto-Matches (UEFA)
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

        @if ($selectedLeagueId <= 0)
            <p class="hint">Bitte zuerst unter <a href="{{ url('/admin') }}">Ligen</a> eine Liga auswählen.</p>
        @elseif ($tab === 'auto')
            <p class="muted">Liga: {{ $selectedLeagueTitle }}</p>

            @php
                $matchplanFiles = is_array($data['matchplan_files'] ?? null) ? $data['matchplan_files'] : [];
            @endphp

            <form
                class="admin-form admin-auto-matches-upload"
                method="post"
                action="{{ route('admin.matches.auto.analyze') }}"
                accept-charset="UTF-8"
            >
                @csrf
                <input type="hidden" name="league_id" value="{{ $selectedLeagueId }}">
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
                        Alle Teams müssen bereits in der DB existieren.
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
                    <button type="submit" class="admin-submit" @disabled($matchplanFiles === [])>Spiele prüfen</button>
                </div>
            </form>

            @if ($matchplanFiles === [])
                <p class="hint">Noch keine JSON-Dateien unter <code>public/data/match</code> gefunden.</p>
            @endif

            @if ($autoAnalyzed)
                <div class="admin-auto-matches-result">
                    @if ($autoSource !== '')
                        <p class="muted">Datei: {{ $autoSource }}</p>
                    @endif

                    <div class="admin-auto-matches-columns">
                        <section class="admin-auto-matches-column" aria-labelledby="admin-auto-present-matches-title">
                            <h3 id="admin-auto-present-matches-title">
                                Bereits vorhanden
                                <span class="admin-squad-count">{{ count($autoPresent) }}</span>
                            </h3>
                            @if (count($autoPresent) === 0)
                                <p class="muted">Keine der Spiele aus der Datei sind bereits angelegt.</p>
                            @else
                                <ul class="admin-auto-matches-list">
                                    @foreach ($autoPresent as $present)
                                        <li>
                                            <strong>{{ $present['home_name'] ?? '' }} : {{ $present['guest_name'] ?? '' }}</strong>
                                            @if (! empty($present['match_id']))
                                                <span class="muted">#{{ $present['match_id'] }}</span>
                                            @endif
                                            @if (($present['match_date'] ?? '') !== '')
                                                <span class="muted">{{ $present['match_date'] }}</span>
                                            @endif
                                            @if ((int) ($present['spieltag'] ?? 0) > 0)
                                                <span class="muted">Spieltag {{ $present['spieltag'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </section>

                        <section class="admin-auto-matches-column" aria-labelledby="admin-auto-missing-matches-title">
                            <h3 id="admin-auto-missing-matches-title">
                                Noch nicht vorhanden
                                <span class="admin-squad-count">{{ count($autoMatches) }}</span>
                            </h3>
                            @if (count($autoMatches) === 0)
                                <p class="muted">Alle Spiele aus der Datei sind bereits in der Datenbank.</p>
                            @else
                                <form
                                    class="admin-form admin-auto-matches-form"
                                    id="admin-auto-matches-form"
                                    method="post"
                                    action="{{ route('admin.matches.auto.store') }}"
                                    accept-charset="UTF-8"
                                >
                                    @csrf
                                    <input type="hidden" name="league_id" value="{{ $selectedLeagueId }}">
                                    <input type="hidden" name="source_name" value="{{ $autoSource }}">

                                    <div class="admin-auto-matches-table-wrap">
                                        <table class="admin-auto-matches-table" id="admin-auto-matches-table">
                                            <thead>
                                                <tr>
                                                    <th>Spielrunde *</th>
                                                    <th>Datum *</th>
                                                    <th>Heim *</th>
                                                    <th>Gast *</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($autoMatches as $index => $match)
                                                    <tr class="admin-auto-match-row">
                                                        <td>
                                                            <input type="hidden" name="matches[{{ $index }}][home_name]" value="{{ $match['home_name'] ?? '' }}">
                                                            <input type="hidden" name="matches[{{ $index }}][guest_name]" value="{{ $match['guest_name'] ?? '' }}">
                                                            <input type="hidden" name="matches[{{ $index }}][spieltag]" value="{{ $match['spieltag'] ?? '' }}">
                                                            <select
                                                                name="matches[{{ $index }}][match_round]"
                                                                required
                                                                aria-label="Spielrunde {{ $index + 1 }}"
                                                            >
                                                                <option value=""></option>
                                                                @foreach ($matchrounds as $round)
                                                                    <option
                                                                        value="{{ $round['matchround_id'] }}"
                                                                        @selected((string) ($match['match_round'] ?? '') === (string) $round['matchround_id'])
                                                                    >
                                                                        {{ $round['matchround_title'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="date"
                                                                name="matches[{{ $index }}][match_date]"
                                                                value="{{ $match['match_date'] ?? '' }}"
                                                                required
                                                                aria-label="Datum {{ $index + 1 }}"
                                                            >
                                                        </td>
                                                        <td>
                                                            <select
                                                                name="matches[{{ $index }}][match_hometeam_id]"
                                                                required
                                                                aria-label="Heimteam {{ $index + 1 }}"
                                                            >
                                                                <option value=""></option>
                                                                @foreach ($teams as $team)
                                                                    <option
                                                                        value="{{ $team['team_id'] }}"
                                                                        @selected((string) ($match['match_hometeam_id'] ?? '') === (string) $team['team_id'])
                                                                    >
                                                                        {{ $team['team_label'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select
                                                                name="matches[{{ $index }}][match_guestteam_id]"
                                                                required
                                                                aria-label="Gastteam {{ $index + 1 }}"
                                                            >
                                                                <option value=""></option>
                                                                @foreach ($teams as $team)
                                                                    <option
                                                                        value="{{ $team['team_id'] }}"
                                                                        @selected((string) ($match['match_guestteam_id'] ?? '') === (string) $team['team_id'])
                                                                    >
                                                                        {{ $team['team_label'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="text"
                                                                name="matches[{{ $index }}][match_status]"
                                                                value="{{ $match['match_status'] ?? '' }}"
                                                                maxlength="255"
                                                                placeholder="leer = OK"
                                                                aria-label="Status {{ $index + 1 }}"
                                                            >
                                                        </td>
                                                        <td class="admin-auto-match-actions">
                                                            <button
                                                                type="button"
                                                                class="admin-icon-btn admin-auto-match-discard"
                                                                title="Zeile verwerfen"
                                                                aria-label="Zeile verwerfen"
                                                            >
                                                                <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="" width="16" height="16">
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="admin-actions">
                                        <button type="submit" class="admin-submit">Spiele anlegen</button>
                                    </div>
                                </form>
                            @endif
                        </section>
                    </div>
                </div>
            @endif
        @elseif ($tab === 'auto-uefa')
            <p class="muted">Liga: {{ $selectedLeagueTitle }}</p>
            <p class="hint">
                Nutzt den UEFA-Competition-Identifier der ausgewählten Liga
                (<code>{{ $uefaIdentifier !== '' ? $uefaIdentifier : '—' }}</code>).
                Vorhandene Spiele werden über Heim-/Gast-Team (<code>team_uefa_id</code>) und Datum erkannt.
            </p>

            @if ($uefaIdentifier === '')
                <p class="hint">
                    Für diese Liga ist kein UEFA-Competition-Identifier hinterlegt.
                    Bitte zuerst unter <a href="{{ url('/admin/leagues') }}">Ligen</a> setzen.
                </p>
            @elseif ($matchrounds === [])
                <p class="hint">Noch keine Spielrunden. Lege zuerst unter Spielrunden welche an.</p>
            @else
                <form
                    class="admin-form"
                    method="post"
                    action="{{ route('admin.matches.auto-uefa.analyze') }}"
                    accept-charset="UTF-8"
                >
                    @csrf
                    <input type="hidden" name="league_id" value="{{ $selectedLeagueId }}">
                    <div class="admin-actions">
                        <button type="submit" class="admin-submit">UEFA-Spiele prüfen</button>
                    </div>
                </form>
            @endif

            @if ($autoUefaAnalyzed && count($autoUefaRows) > 0)
                <div class="admin-auto-matches-result">
                    @if ($autoUefaSource !== '')
                        <p class="muted">Quelle: {{ $autoUefaSource }}</p>
                    @endif

                    <form
                        class="admin-form admin-auto-matches-form"
                        id="admin-auto-uefa-matches-form"
                        method="post"
                        action="{{ route('admin.matches.auto-uefa.store') }}"
                        accept-charset="UTF-8"
                    >
                        @csrf
                        <input type="hidden" name="league_id" value="{{ $selectedLeagueId }}">
                        <input type="hidden" name="source_name" value="{{ $autoUefaSource }}">
                        {{-- One JSON field avoids PHP max_input_vars truncating large match lists. --}}
                        <input type="hidden" name="rows_json" id="admin-auto-uefa-rows-json" value="">

                        <div class="admin-auto-matches-table-wrap admin-auto-uefa-matches-wrap">
                            <table class="admin-auto-matches-table admin-auto-uefa-matches-table" id="admin-auto-uefa-matches-table">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Spielrunde *</th>
                                        <th>Datum</th>
                                        <th>Heim</th>
                                        <th>Gast</th>
                                        <th>MD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($autoUefaRows as $index => $row)
                                        @php
                                            $rowStatus = (string) ($row['row_status'] ?? 'new');
                                            $isUnmapped = $rowStatus === 'unmapped';
                                            $isMatched = $rowStatus === 'matched';
                                            $rowPayload = [
                                                'row_status' => $rowStatus,
                                                'match_id' => (int) ($row['match_id'] ?? 0),
                                                'match_round' => (int) ($row['match_round'] ?? 0),
                                                'match_date' => (string) ($row['match_date'] ?? ''),
                                                'match_hometeam_id' => (int) ($row['match_hometeam_id'] ?? 0),
                                                'match_guestteam_id' => (int) ($row['match_guestteam_id'] ?? 0),
                                                'match_status' => (string) ($row['match_status'] ?? ''),
                                                'home_name' => (string) ($row['home_name'] ?? ''),
                                                'guest_name' => (string) ($row['guest_name'] ?? ''),
                                                'uefa_match_id' => (string) ($row['uefa_match_id'] ?? ''),
                                                'home_uefa_id' => (string) ($row['home_uefa_id'] ?? ''),
                                                'away_uefa_id' => (string) ($row['away_uefa_id'] ?? ''),
                                                'matchday' => (int) ($row['matchday'] ?? 0),
                                            ];
                                        @endphp
                                        <tr
                                            class="admin-auto-uefa-match-row is-{{ $rowStatus }}"
                                            data-row='@json($rowPayload)'
                                        >
                                            <td>
                                                @if ($isMatched)
                                                    vorhanden
                                                    @if ((int) ($row['match_id'] ?? 0) > 0)
                                                        <span class="muted">#{{ (int) $row['match_id'] }}</span>
                                                    @endif
                                                @elseif ($isUnmapped)
                                                    <span class="admin-auto-uefa-warn">Team fehlt</span>
                                                @else
                                                    neu
                                                @endif
                                            </td>
                                            <td>
                                                @if ($isUnmapped)
                                                    <span class="muted">—</span>
                                                @else
                                                    <select
                                                        class="admin-auto-uefa-match-round"
                                                        required
                                                        aria-label="Spielrunde {{ $index + 1 }}"
                                                    >
                                                        <option value="">— wählen —</option>
                                                        @foreach ($matchrounds as $round)
                                                            <option
                                                                value="{{ $round['matchround_id'] }}"
                                                                @selected((string) ($row['match_round'] ?? '') === (string) $round['matchround_id'])
                                                            >
                                                                {{ $round['matchround_title'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                            <td>{{ $row['match_date'] ?? '' }}</td>
                                            <td>
                                                {{ $row['home_name'] ?? '' }}
                                                @if (($row['home_uefa_id'] ?? '') !== '')
                                                    <span class="muted">({{ $row['home_uefa_id'] }})</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $row['guest_name'] ?? '' }}
                                                @if (($row['away_uefa_id'] ?? '') !== '')
                                                    <span class="muted">({{ $row['away_uefa_id'] }})</span>
                                                @endif
                                            </td>
                                            <td>{{ (int) ($row['matchday'] ?? 0) > 0 ? (int) $row['matchday'] : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-actions">
                            <button type="submit" class="admin-submit" id="admin-auto-uefa-matches-save">
                                Speichern
                            </button>
                            <span class="muted">Vorhandene Spiele werden aktualisiert, neue angelegt. Zeilen ohne Team-Zuordnung werden übersprungen.</span>
                        </div>
                    </form>
                </div>
            @elseif ($autoUefaAnalyzed)
                <p class="muted">Keine UEFA-Spiele gefunden.</p>
            @endif
        @elseif ($matchrounds === [])
            <p class="muted">Liga: {{ $selectedLeagueTitle }} — noch keine Spielrunden. Lege zuerst unter Spielrunden welche an.</p>
        @else
            <p class="muted">Liga: {{ $selectedLeagueTitle }}</p>

            <form
                class="admin-form"
                method="post"
                action="{{ $mode === 'update' ? route('admin.matches.update', ['match' => $form['match_id']]) : route('admin.matches.store') }}"
                accept-charset="UTF-8"
            >
                @csrf
                @if ($mode === 'update')
                    @method('PUT')
                @endif

                <div class="admin-field">
                    <label for="match_round">* Spielrunde</label>
                    <select id="match_round" name="match_round" required>
                        <option value=""></option>
                        @foreach ($matchrounds as $round)
                            <option value="{{ $round['matchround_id'] }}" @selected((string) $form['match_round'] === (string) $round['matchround_id'])>
                                {{ $round['matchround_title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="match_date">* Datum</label>
                    <input id="match_date" type="date" name="match_date" value="{{ $form['match_date'] }}" required>
                </div>

                <div class="admin-field">
                    <label for="match_hometeam_id">* Heimteam</label>
                    <select id="match_hometeam_id" name="match_hometeam_id" required>
                        <option value=""></option>
                        @foreach ($teams as $team)
                            <option value="{{ $team['team_id'] }}" @selected((string) $form['match_hometeam_id'] === (string) $team['team_id'])>
                                {{ $team['team_label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="match_guestteam_id">* Gastteam</label>
                    <select id="match_guestteam_id" name="match_guestteam_id" required>
                        <option value=""></option>
                        @foreach ($teams as $team)
                            <option value="{{ $team['team_id'] }}" @selected((string) $form['match_guestteam_id'] === (string) $team['team_id'])>
                                {{ $team['team_label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="match_status">Status-Hinweis</label>
                    <input id="match_status" type="text" name="match_status" value="{{ $form['match_status'] }}" maxlength="255" placeholder="leer = in Ordnung">
                </div>

                <div class="admin-actions">
                    @if ($mode === 'update')
                        <button type="submit" class="admin-submit">Speichern</button>
                        <a class="admin-cancel" href="{{ route('admin.matches', ['league_id' => $selectedLeagueId]) }}">Abbrechen</a>
                    @else
                        <button type="submit" class="admin-submit">Hinzufügen</button>
                    @endif
                </div>
            </form>
        @endif
    </section>

    @if ($tab === 'manual' && $selectedLeagueId > 0)
        <section class="panel admin-main" aria-labelledby="admin-matches-list-title">
            <div class="section-head">
                <h2 id="admin-matches-list-title">Vorhandene Spiele</h2>
            </div>

            @forelse ($items as $item)
                <article class="admin-list-item">
                    <div class="admin-list-body">
                        <div class="admin-match-meta">
                            <img
                                src="{{ $legacyBase }}images/ffb/symbols/{{ $item['status_ok'] ? 'status_pos.png' : 'status_neg.png' }}"
                                alt="{{ $item['status_ok'] ? 'OK' : 'Hinweis' }}"
                                width="16"
                                height="16"
                                loading="lazy"
                            >
                            <time>{{ $item['match_date'] }}</time>
                            <span class="muted">({{ $item['match_round_title'] }})</span>
                        </div>
                        <h3 class="admin-list-title admin-match-teams">
                            <span class="admin-match-team">
                                @if (($item['home_flag_html'] ?? '') !== '')
                                    {!! $item['home_flag_html'] !!}
                                @elseif (! empty($item['home_flag_url']))
                                    <img class="ffb-flag ffb-flag-img" src="{{ $item['home_flag_url'] }}" alt="" width="20" height="15" loading="lazy">
                                @endif
                                {{ $item['home_name'] }}
                            </span>
                            <span class="muted">:</span>
                            <span class="admin-match-team">
                                {{ $item['guest_name'] }}
                                @if (($item['guest_flag_html'] ?? '') !== '')
                                    {!! $item['guest_flag_html'] !!}
                                @elseif (! empty($item['guest_flag_url']))
                                    <img class="ffb-flag ffb-flag-img" src="{{ $item['guest_flag_url'] }}" alt="" width="20" height="15" loading="lazy">
                                @endif
                            </span>
                        </h3>
                        @if ($item['match_status'] !== '')
                            <p class="muted">{{ $item['match_status'] }}</p>
                        @endif
                    </div>
                    <div class="admin-list-actions">
                        <a class="admin-icon-btn" href="{{ route('admin.matches.edit', ['match' => $item['match_id']]) }}" title="Bearbeiten">
                            <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                        </a>
                        <form method="post" action="{{ route('admin.matches.destroy', ['match' => $item['match_id']]) }}" onsubmit="return confirm('Dieses Spiel wirklich löschen?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="league_id" value="{{ $selectedLeagueId }}">
                            <button type="submit" class="admin-icon-btn" title="Löschen">
                                <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="muted">Noch keine Spiele für diese Liga.</p>
            @endforelse
        </section>
    @endif
@endsection

@if ($tab === 'auto')
@push('scripts')
<script>
(function () {
    const table = document.getElementById('admin-auto-matches-table');
    if (!table) return;

    table.addEventListener('click', function (event) {
        const button = event.target.closest('.admin-auto-match-discard');
        if (!button) return;
        const row = button.closest('tr.admin-auto-match-row');
        if (row) {
            row.remove();
        }
    });
})();
</script>
@endpush
@endif

@if ($tab === 'auto-uefa')
@push('scripts')
<script>
(function () {
    const form = document.getElementById('admin-auto-uefa-matches-form');
    const table = document.getElementById('admin-auto-uefa-matches-table');
    const rowsJson = document.getElementById('admin-auto-uefa-rows-json');
    if (!form || !table || !rowsJson) {
        return;
    }

    form.addEventListener('submit', function () {
        const rows = [];
        table.querySelectorAll('tr.admin-auto-uefa-match-row').forEach(function (tr) {
            let row;
            try {
                row = JSON.parse(tr.getAttribute('data-row') || '{}');
            } catch (e) {
                row = {};
            }
            if (!row || typeof row !== 'object') {
                row = {};
            }
            const select = tr.querySelector('select.admin-auto-uefa-match-round');
            if (select) {
                row.match_round = select.value;
            }
            rows.push(row);
        });
        rowsJson.value = JSON.stringify(rows);
    });
})();
</script>
@endpush
@endif
