@extends('layouts.admin')

@section('title', 'Spielrunden')

@section('content')
    @php
        $form = $data['form'];
        $mode = $data['mode'];
        $items = $data['items'];
        $leagues = $data['leagues'];
        $selectedLeagueId = (int) $data['selected_league_id'];
        $selectedLeagueTitle = $data['selected_league_title'];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-matchrounds-title">
        <div class="section-head">
            <h2 id="admin-matchrounds-title">Spielrunden</h2>
        </div>

        <form class="admin-league-picker" method="get" action="{{ route('admin.matchrounds') }}">
            <label for="league_id">Liga</label>
            <select id="league_id" name="league_id" onchange="this.form.submit()">
                <option value="">— Liga wählen —</option>
                @foreach ($leagues as $league)
                    <option value="{{ $league['league_id'] }}" @selected($selectedLeagueId === (int) $league['league_id'])>
                        {{ $league['league_title'] }}@if ($league['league_archive']) (Archiv)@endif
                    </option>
                @endforeach
            </select>
            <noscript>
                <button type="submit" class="admin-submit">Anzeigen</button>
            </noscript>
        </form>

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
            <p class="hint">Wähle oben eine Liga, um deren Spielrunden zu verwalten.</p>
        @else
            <p class="hint">Liga: <strong>{{ $selectedLeagueTitle }}</strong></p>

            <form
                class="admin-form"
                method="post"
                action="{{ $mode === 'update' ? route('admin.matchrounds.update', ['matchround' => $form['matchround_id']]) : route('admin.matchrounds.store') }}"
                accept-charset="UTF-8"
            >
                @csrf
                @if ($mode === 'update')
                    @method('PUT')
                @endif
                <input type="hidden" name="matchround_league_id" value="{{ $selectedLeagueId }}">

                <div class="admin-field">
                    <label for="matchround_title">* Titel</label>
                    <input id="matchround_title" type="text" name="matchround_title" value="{{ $form['matchround_title'] }}" maxlength="255" required>
                </div>

                <div class="admin-field">
                    <label for="matchround_startdate">* Start</label>
                    <input
                        id="matchround_startdate"
                        type="datetime-local"
                        name="matchround_startdate"
                        value="{{ $form['matchround_startdate'] }}"
                        step="3600"
                        required
                    >
                </div>

                <div class="admin-field">
                    <label for="matchround_enddate">* Ende</label>
                    <input
                        id="matchround_enddate"
                        type="datetime-local"
                        name="matchround_enddate"
                        value="{{ $form['matchround_enddate'] }}"
                        step="3600"
                        required
                    >
                </div>

                <div class="admin-field">
                    <label for="matchround_status">Status</label>
                    <select id="matchround_status" name="matchround_status">
                        <option value="1" @selected((int) $form['matchround_status'] === 1)>aktiv</option>
                        <option value="0" @selected((int) $form['matchround_status'] === 0)>inaktiv</option>
                    </select>
                </div>

                <fieldset class="admin-fieldset" id="matchround-lineup-overrides" data-lineup-defaults='@json($data['league_lineup_defaults'] ?? [])'>
                    <legend>Aufstellungs-Overrides</legend>
                    <p class="hint">Optional: eigene Limits für diese Spielrunde. Sonst gelten die Liga-Defaults.</p>
                    <div class="admin-field">
                        <label>
                            <input
                                type="checkbox"
                                id="lineup_options_enabled"
                                name="lineup_options_enabled"
                                value="1"
                                @checked((int) ($form['lineup_options_enabled'] ?? 0) === 1)
                            >
                            Eigene Aufstellungslimits verwenden
                        </label>
                    </div>
                    @php
                        $lineupEnabled = (int) ($form['lineup_options_enabled'] ?? 0) === 1;
                    @endphp
                    <div class="admin-option-grid">
                        @foreach ([
                            'matchround_options_lineup_max_players' => 'Max. Spieler / Aufstellung',
                            'matchround_options_lineup_max_credits' => 'Max. Credits / Aufstellung',
                            'matchround_options_lineup_max_players_team' => 'Max. Spieler vom selben Team',
                        ] as $name => $label)
                            <div class="admin-option-field">
                                <label for="{{ $name }}">{{ $label }}</label>
                                <input
                                    id="{{ $name }}"
                                    type="number"
                                    name="{{ $name }}"
                                    value="{{ $form[$name] ?? '' }}"
                                    data-lineup-field
                                    @disabled(! $lineupEnabled)
                                >
                            </div>
                        @endforeach
                    </div>
                    <div class="admin-lineup-pos-grid">
                        @foreach ([
                            ['matchround_options_lineup_min_g', 'Min. Spieler als Goalie', 'matchround_options_lineup_max_g', 'Max. Spieler als Goalie'],
                            ['matchround_options_lineup_min_d', 'Min. Spieler in Abwehr', 'matchround_options_lineup_max_d', 'Max. Spieler in Abwehr'],
                            ['matchround_options_lineup_min_m', 'Min. Spieler in Mittelfeld', 'matchround_options_lineup_max_m', 'Max. Spieler in Mittelfeld'],
                            ['matchround_options_lineup_min_s', 'Min. Spieler in Angriff', 'matchround_options_lineup_max_s', 'Max. Spieler in Angriff'],
                        ] as [$minName, $minLabel, $maxName, $maxLabel])
                            <div class="admin-option-field">
                                <label for="{{ $minName }}">{{ $minLabel }}</label>
                                <input
                                    id="{{ $minName }}"
                                    type="number"
                                    name="{{ $minName }}"
                                    value="{{ $form[$minName] ?? '' }}"
                                    data-lineup-field
                                    @disabled(! $lineupEnabled)
                                >
                            </div>
                            <div class="admin-option-field">
                                <label for="{{ $maxName }}">{{ $maxLabel }}</label>
                                <input
                                    id="{{ $maxName }}"
                                    type="number"
                                    name="{{ $maxName }}"
                                    value="{{ $form[$maxName] ?? '' }}"
                                    data-lineup-field
                                    @disabled(! $lineupEnabled)
                                >
                            </div>
                        @endforeach
                    </div>
                </fieldset>

                <div class="admin-actions">
                    @if ($mode === 'update')
                        <button type="submit" class="admin-submit">Speichern</button>
                        <a class="admin-cancel" href="{{ route('admin.matchrounds', ['league_id' => $selectedLeagueId]) }}">Abbrechen</a>
                    @else
                        <button type="submit" class="admin-submit">Hinzufügen</button>
                    @endif
                </div>
            </form>
        @endif
    </section>

    @if ($selectedLeagueId > 0)
        <section class="panel admin-main" aria-labelledby="admin-matchrounds-list-title">
            <div class="section-head">
                <h2 id="admin-matchrounds-list-title">Vorhandene Spielrunden</h2>
            </div>

            @forelse ($items as $item)
                <article class="admin-list-item">
                    <div class="admin-list-body">
                        <div class="admin-matchround-dates">
                            <div><strong>von:</strong> {{ $item['matchround_startdate'] }}</div>
                            <div><strong>bis:</strong> {{ $item['matchround_enddate'] }}</div>
                        </div>
                        <h3 class="admin-list-title">
                            {{ $item['matchround_title'] }}
                            <span class="muted">— {{ $item['matchround_status'] ? 'aktiv' : 'inaktiv' }}</span>
                        </h3>
                    </div>
                    <div class="admin-list-actions">
                        <a class="admin-icon-btn" href="{{ route('admin.matchrounds.edit', ['matchround' => $item['matchround_id']]) }}" title="Bearbeiten">
                            <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                        </a>
                        <form method="post" action="{{ route('admin.matchrounds.destroy', ['matchround' => $item['matchround_id']]) }}" onsubmit="return confirm('Diese Spielrunde wirklich löschen?');">
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
                <p class="muted">Noch keine Spielrunden für diese Liga.</p>
            @endforelse
        </section>
    @endif
@endsection

@push('scripts')
<script>
(function () {
    var fieldset = document.getElementById('matchround-lineup-overrides');
    var checkbox = document.getElementById('lineup_options_enabled');
    if (!fieldset || !checkbox) {
        return;
    }

    var defaults = {};
    try {
        defaults = JSON.parse(fieldset.getAttribute('data-lineup-defaults') || '{}') || {};
    } catch (e) {
        defaults = {};
    }

    function applyEnabledState() {
        var enabled = checkbox.checked;
        fieldset.querySelectorAll('[data-lineup-field]').forEach(function (input) {
            input.disabled = !enabled;
            if (!enabled && Object.prototype.hasOwnProperty.call(defaults, input.name)) {
                input.value = defaults[input.name];
            }
        });
    }

    checkbox.addEventListener('change', applyEnabledState);
})();
</script>
@endpush
