@extends('layouts.admin')

@section('title', 'Kader')

@section('content')
    @php
        $teams = $data['teams'];
        $leagues = $data['leagues'] ?? [];
        $squadLeagueId = (int) ($data['squad_league_id'] ?? 0);
        $selectedTeamId = (int) $data['selected_team_id'];
        $selectedTeam = $data['selected_team'];
        $items = $data['items'];
        $countries = $data['countries'];
        $positions = $data['positions'];
        $defaults = $data['defaults'];
        $rosterActiveCount = (int) ($data['roster_active_count'] ?? 0);
        $perPage = (int) ($data['per_page'] ?? 100);
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $tab = match ($data['tab'] ?? request()->query('tab')) {
            'add' => 'add',
            'auto' => 'auto',
            'auto-uefa' => 'auto-uefa',
            'auto-fifa' => 'auto-fifa',
            'images' => 'images',
            default => 'roster',
        };
        $auto = is_array($data['auto'] ?? null) ? $data['auto'] : [];
        $autoAnalyzed = (bool) ($auto['analyzed'] ?? false);
        $autoSource = (string) ($auto['source_name'] ?? '');
        $autoSourceKind = (string) ($auto['source_kind'] ?? match ($tab) {
            'auto-uefa' => 'uefa',
            'auto-fifa' => 'fifa',
            default => 'json',
        });
        $autoFifa = (string) ($auto['fifa_code'] ?? '');
        $autoPlayers = is_array($auto['players'] ?? null) ? $auto['players'] : [];
        $autoAlmost = is_array($auto['almost'] ?? null) ? $auto['almost'] : [];
        $autoHasRows = count($autoPlayers) > 0 || count($autoAlmost) > 0;
        $uefaIdentifier = (string) ($data['uefa_competition_identifier'] ?? '');
        $uefaTeams = is_array($data['uefa_teams'] ?? null) ? $data['uefa_teams'] : [];
        $uefaTeamId = (string) ($data['uefa_team_id'] ?? '');
        $fifaIdentifier = (string) ($data['fifa_competition_identifier'] ?? '');
        $fifaTeams = is_array($data['fifa_teams'] ?? null) ? $data['fifa_teams'] : [];
        $fifaTeamId = (string) ($data['fifa_team_id'] ?? '');
        $images = is_array($data['images'] ?? null) ? $data['images'] : [];
        $imagesChecked = (bool) ($images['checked'] ?? false);
        $imagePlayers = is_array($images['players'] ?? null) ? $images['players'] : [];
        $imagesCheckCount = count(array_filter(
            $imagePlayers,
            static fn (array $row): bool => (string) ($row['status'] ?? '') !== 'vorhanden',
        ));
        $imagesFoundCount = count(array_filter(
            $imagePlayers,
            static fn (array $row): bool => (string) ($row['status'] ?? '') === 'gefunden',
        ));
        $positionOrder = ['g', 'd', 'm', 's'];
        $grouped = [];
        foreach ($positionOrder as $code) {
            $grouped[$code] = [];
        }
        foreach ($items as $item) {
            $code = $item['playerteam_player_position'];
            if (! isset($grouped[$code])) {
                $grouped[$code] = [];
            }
            $grouped[$code][] = $item;
        }
        $rosterQuery = array_filter([
            'squad_league_id' => $squadLeagueId > 0 ? $squadLeagueId : null,
            'team_id' => $selectedTeamId > 0 ? $selectedTeamId : null,
        ], static fn ($v) => $v !== null);
        $addQuery = $rosterQuery + ['tab' => 'add'];
        $autoQuery = $rosterQuery + ['tab' => 'auto'];
        $autoUefaQuery = array_filter([
            'tab' => 'auto-uefa',
            'squad_league_id' => $squadLeagueId > 0 ? $squadLeagueId : null,
            'uefa_team_id' => $uefaTeamId !== '' ? $uefaTeamId : null,
            'team_id' => $selectedTeamId > 0 ? $selectedTeamId : null,
        ], static fn ($v) => $v !== null);
        $autoFifaQuery = array_filter([
            'tab' => 'auto-fifa',
            'squad_league_id' => $squadLeagueId > 0 ? $squadLeagueId : null,
            'fifa_team_id' => $fifaTeamId !== '' ? $fifaTeamId : null,
            'team_id' => $selectedTeamId > 0 ? $selectedTeamId : null,
        ], static fn ($v) => $v !== null);
        $imagesQuery = $rosterQuery + ['tab' => 'images'];
        $selectedTeamNat = strtoupper(trim((string) ($selectedTeam['team_nationality'] ?? '')));
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-squad-title">
        <div class="section-head">
            <h2 id="admin-squad-title">Kader</h2>
        </div>

        @php
            $squadLeagueTitle = (string) ($data['selected_league']['league_title'] ?? '');
            if ($squadLeagueTitle === '') {
                foreach ($leagues as $league) {
                    if ((int) $league['league_id'] === $squadLeagueId) {
                        $squadLeagueTitle = (string) $league['league_title'];
                        break;
                    }
                }
            }
        @endphp

        @if ($squadLeagueId <= 0)
            <p class="hint">Bitte zuerst unter <a href="{{ url('/admin') }}">Ligen</a> eine Liga auswählen.</p>
        @else
            <p class="muted">Liga: {{ $squadLeagueTitle }}</p>
        @endif

        @if (!empty($data['hint']))
            <p class="hint">{{ $data['hint'] }}</p>
        @endif

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

        @if ($squadLeagueId > 0)
            <nav class="admin-squad-tabs ffb-tabs" aria-label="Kader-Bereiche">
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'roster' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $rosterQuery) }}"
                >
                    Bestand <span class="admin-squad-count" id="squad-tab-roster-count">{{ count($items) }}</span>
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'add' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $addQuery) }}"
                >
                    Spieler hinzufügen
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'auto' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $autoQuery) }}"
                >
                    Auto-Kader
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'auto-uefa' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $autoUefaQuery) }}"
                >
                    Auto-Kader (UEFA)
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'auto-fifa' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $autoFifaQuery) }}"
                >
                    Auto-Kader (FIFA)
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'images' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $imagesQuery) }}"
                >
                    Auto-Bilder
                </a>
            </nav>
        @endif
    </section>

    @if ($tab === 'roster' && $squadLeagueId > 0)
        <section class="panel admin-main" aria-labelledby="admin-squad-roster-title" id="squad-roster-section">
            <div class="section-head admin-squad-roster-head">
                <h2 id="admin-squad-roster-title">{{ $selectedTeamId > 0 ? ($selectedTeam['team_label'] ?? 'Kader') : 'Bestand' }}</h2>
                @if ($selectedTeamId > 0 && count($items) > 0)
                    <div class="admin-squad-toggle" role="group" aria-label="Kader-Anzeige">
                        <button type="button" class="admin-squad-toggle-btn is-active" data-roster-filter="active" aria-pressed="true">
                            Aktiv <span class="admin-squad-count">{{ $rosterActiveCount }}</span>
                        </button>
                        <button type="button" class="admin-squad-toggle-btn" data-roster-filter="all" aria-pressed="false">
                            Alle <span class="admin-squad-count">{{ count($items) }}</span>
                        </button>
                    </div>
                @endif
            </div>

            @include('admin.partials.squad-team-picker', [
                'tab' => $tab,
                'teams' => $teams,
                'selectedTeamId' => $selectedTeamId,
                'squadLeagueId' => $squadLeagueId,
                'teamSelectId' => 'team_id',
            ])

            @if ($selectedTeamId > 0)
            @if (count($items) === 0)
                <p class="muted">Noch keine Spieler in diesem Kader.</p>
                <p>
                    <a class="admin-submit" href="{{ route('admin.squad', $addQuery) }}" style="display:inline-block;text-decoration:none;">
                        Spieler hinzufügen
                    </a>
                </p>
            @else
                <form
                    class="admin-squad-roster-form"
                    id="squad-roster-form"
                    method="post"
                    enctype="multipart/form-data"
                    action="{{ route('admin.squad.batchUpdate') }}"
                    accept-charset="UTF-8"
                >
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                    <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                    <div id="squad-delete-ids"></div>

                    <div class="admin-squad-savebar" id="squad-savebar">
                        <button type="submit" class="admin-submit" id="squad-save-all" disabled>
                            Änderungen speichern (0)
                        </button>
                        <span class="muted" id="squad-dirty-hint">Noch keine Änderungen</span>
                    </div>

                    <p class="muted admin-squad-roster-meta" id="squad-roster-meta"></p>
                    <div class="admin-squad-table-wrap">
                        <table class="admin-squad-table" id="squad-roster-table">
                            <thead>
                                <tr>
                                    <th colspan="5" scope="colgroup" class="admin-squad-head-cell">
                                        <div class="admin-squad-grid admin-squad-head-grid">
                                            <span class="admin-squad-col-photo">Bild</span>
                                            <span>Spieler</span>
                                            <span>Pos.</span>
                                            <span>Status</span>
                                            <span>Notiz</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            @foreach ($positionOrder as $posCode)
                                @php $group = $grouped[$posCode] ?? []; @endphp
                                @if (count($group) === 0)
                                    @continue
                                @endif
                                <tbody class="admin-squad-group" data-group-total="{{ count($group) }}">
                                    <tr class="admin-squad-group-head">
                                        <th colspan="5" scope="colgroup">
                                            {{ $positions[$posCode] ?? strtoupper($posCode) }}
                                            <span class="admin-squad-count admin-squad-group-count">{{ count($group) }}</span>
                                        </th>
                                    </tr>
                                    @foreach ($group as $item)
                                        @php $ptId = (int) $item['playerteam_id']; @endphp
                                        <tr
                                            class="admin-squad-player{{ (int) $item['playerteam_status'] === 0 ? ' is-inactive' : '' }}"
                                            data-status="{{ (int) $item['playerteam_status'] === 1 ? 'active' : 'inactive' }}"
                                            data-playerteam-id="{{ $ptId }}"
                                            data-initial-position="{{ $item['playerteam_player_position'] }}"
                                            data-initial-status="{{ (int) $item['playerteam_status'] }}"
                                            data-initial-note="{{ $item['playerteam_player_note'] ?? '' }}"
                                            data-initial-picture="{{ $item['picture_url'] }}"
                                        >
                                            <td colspan="5" class="admin-squad-player-cell">
                                                <div class="admin-squad-grid">
                                                    <div class="admin-squad-photo-cell">
                                                        <img
                                                            class="admin-squad-photo"
                                                            id="preview-{{ $ptId }}"
                                                            src="{{ $item['picture_url'] }}"
                                                            alt=""
                                                            width="40"
                                                            height="40"
                                                            loading="lazy"
                                                            data-field="picture-preview"
                                                        >
                                                        <label class="admin-squad-photo-btn" for="pic-{{ $ptId }}">Ändern</label>
                                                        <input
                                                            class="admin-squad-photo-input"
                                                            id="pic-{{ $ptId }}"
                                                            type="file"
                                                            name="items[{{ $ptId }}][playerteam_picture_file]"
                                                            accept="image/png,image/jpeg,image/gif,image/webp"
                                                            data-preview="preview-{{ $ptId }}"
                                                            data-field="picture"
                                                        >
                                                    </div>

                                                    <div class="admin-squad-name-cell">
                                                        <div class="admin-squad-name">
                                                            @if (($item['player_flag_html'] ?? '') !== '')
                                                                {!! $item['player_flag_html'] !!}
                                                            @elseif (! empty($item['player_flag_url']))
                                                                <img class="ffb-flag ffb-flag-img" src="{{ $item['player_flag_url'] }}" alt="" width="18" height="13" loading="lazy">
                                                            @endif
                                                            <strong>{{ $item['player_lname'] }}</strong>
                                                            <span>{{ $item['player_fname'] }}</span>
                                                        </div>
                                                        @php
                                                            $idParts = [
                                                                'P-ID: '.(int) $item['player_id'],
                                                                'PT-ID: '.$ptId,
                                                            ];
                                                            $uefaId = trim((string) ($item['player_uefa_id'] ?? ''));
                                                            $fifaId = trim((string) ($item['player_fifa_id'] ?? ''));
                                                            if ($uefaId !== '') {
                                                                $idParts[] = 'UEFA: '.$uefaId;
                                                            }
                                                            if ($fifaId !== '') {
                                                                $idParts[] = 'FIFA: '.$fifaId;
                                                            }
                                                        @endphp
                                                        <span class="muted admin-squad-ids">{{ implode(' | ', $idParts) }}</span>
                                                    </div>

                                                    <label class="admin-squad-compact admin-squad-field-pos">
                                                        <span class="visually-hidden">Position</span>
                                                        <select name="items[{{ $ptId }}][playerteam_player_position]" aria-label="Position" data-field="position">
                                                            @foreach ($positions as $code => $label)
                                                                <option value="{{ $code }}" @selected($item['playerteam_player_position'] === $code)>{{ strtoupper($code) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>

                                                    <label class="admin-squad-compact admin-squad-field-status">
                                                        <span class="visually-hidden">Status</span>
                                                        <select name="items[{{ $ptId }}][playerteam_status]" aria-label="Status" data-field="status">
                                                            <option value="1" @selected((int) $item['playerteam_status'] === 1)>aktiv</option>
                                                            <option value="0" @selected((int) $item['playerteam_status'] === 0)>inaktiv</option>
                                                        </select>
                                                    </label>

                                                    <label class="admin-squad-compact admin-squad-field-note">
                                                        <span class="visually-hidden">Notiz</span>
                                                        <input
                                                            type="text"
                                                            name="items[{{ $ptId }}][playerteam_player_note]"
                                                            value="{{ $item['playerteam_player_note'] ?? '' }}"
                                                            maxlength="255"
                                                            aria-label="Notiz"
                                                            placeholder="Notiz"
                                                            data-field="note"
                                                        >
                                                    </label>
                                                </div>

                                                <div class="admin-squad-row-tools">
                                                    <button
                                                        type="button"
                                                        class="admin-icon-btn admin-squad-undo-btn"
                                                        title="Rückgängig"
                                                        hidden
                                                    >
                                                        <img src="{{ $legacyBase }}images/ffb/symbols/change.png" alt="Rückgängig" width="16" height="16">
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="admin-icon-btn admin-squad-delete-btn"
                                                        title="Zum Löschen vormerken"
                                                    >
                                                        <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                    <p class="muted" id="squad-roster-empty" hidden>Keine aktiven Spieler in diesem Kader.</p>
                </form>
            @endif
            @endif
        </section>
    @endif

    @if ($tab === 'add' && $squadLeagueId > 0)
        <section class="panel admin-main" aria-labelledby="admin-squad-add-title">
            <div class="section-head">
                <h2 id="admin-squad-add-title">Spieler hinzufügen</h2>
            </div>

            @include('admin.partials.squad-team-picker', [
                'tab' => $tab,
                'teams' => $teams,
                'selectedTeamId' => $selectedTeamId,
                'squadLeagueId' => $squadLeagueId,
                'teamSelectId' => 'team_id_add',
            ])

            @if ($selectedTeamId > 0)
            <p class="hint">Standardwerte setzen, Spieler vormerken, Werte je Spieler anpassen, dann übernehmen.</p>

            <form
                class="admin-squad-batch"
                method="post"
                action="{{ route('admin.squad.store') }}"
                id="squad-batch-form"
                accept-charset="UTF-8"
                data-legacy-base="{{ $legacyBase }}"
                data-positions='@json($positions)'
            >
                @csrf
                <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">

                <div class="admin-squad-savebar" id="squad-add-savebar">
                    <button type="submit" class="admin-submit" id="squad-batch-submit" disabled>
                        Auswahl übernehmen (0)
                    </button>
                    <button type="button" class="admin-cancel" id="squad-staging-clear" hidden>Auswahl leeren</button>
                    <span class="muted" id="squad-staging-hint">Noch keine Spieler vorgemerkt</span>
                </div>

                <div class="admin-squad-pick-block">
                    <article class="admin-list-item admin-squad-pick-row admin-squad-defaults-row" id="squad-defaults-row">
                        <div class="admin-squad-pick-label">
                            <strong>Standardwerte</strong>
                            <span class="muted">gelten für neu vorgemerkte Spieler</span>
                        </div>
                        <div class="admin-squad-pick-fields">
                            <label class="admin-squad-compact">
                                <span>Pos.</span>
                                <select id="batch_pos" aria-label="Standard-Position">
                                    @foreach ($positions as $code => $label)
                                        <option value="{{ $code }}" @selected($defaults['playerteam_player_position'] === $code)>{{ strtoupper($code) }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="admin-squad-compact">
                                <span>Status</span>
                                <select id="batch_status" aria-label="Standard-Status">
                                    <option value="1" @selected((int) $defaults['playerteam_status'] === 1)>aktiv</option>
                                    <option value="0" @selected((int) $defaults['playerteam_status'] === 0)>inaktiv</option>
                                </select>
                            </label>
                        </div>
                    </article>

                    <div id="squad-selected-list" class="admin-squad-selected-list"></div>
                    <p class="muted" id="squad-staging-empty">Noch keine Spieler vorgemerkt.</p>
                </div>
            </form>

            <div
                id="squad-candidate-section"
                data-search-url="{{ route('admin.players.search') }}"
                data-exclude-team-id="{{ $selectedTeamId }}"
                data-exclude-league-id="{{ $squadLeagueId }}"
                data-legacy-base="{{ $legacyBase }}"
                data-per-page="{{ $perPage }}"
            >
                <div class="admin-filter-bar" id="squad-candidate-filters">
                    <div class="admin-field">
                        <label for="squad_filter_q">Suche</label>
                        <input id="squad_filter_q" type="search" value="" placeholder="Name oder ID" autocomplete="off">
                    </div>
                    <div class="admin-field">
                        <label for="squad_filter_nationality">Nationalität</label>
                        <select id="squad_filter_nationality">
                            <option value="">— alle —</option>
                            @foreach ($countries as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-actions admin-actions-flush">
                        <button type="button" class="admin-cancel" id="squad-filter-reset" hidden>Zurücksetzen</button>
                    </div>
                </div>

                <p class="muted admin-squad-candidate-meta" id="squad-candidate-meta">Lade Spieler…</p>
                <div id="squad-candidate-list" aria-live="polite"></div>
                <p class="muted" id="squad-candidate-empty" hidden>Keine passenden Spieler zum Hinzufügen.</p>
                <p class="muted" id="squad-candidate-error" hidden>Spieler konnten nicht geladen werden.</p>

                <nav class="admin-pagination" id="squad-candidate-pager" hidden aria-label="Spieler-Seiten">
                    <button type="button" class="admin-cancel" id="squad-candidate-prev">Zurück</button>
                    <span class="muted" id="squad-candidate-page-label">1 / 1</span>
                    <button type="button" class="admin-cancel" id="squad-candidate-next">Weiter</button>
                </nav>
            </div>
            @endif
        </section>
    @endif

    @if ($tab === 'auto-uefa' && $squadLeagueId > 0)
        @php
            $autoSourceLabel = 'UEFA';
            $selectedUefaLabel = '';
            foreach ($uefaTeams as $option) {
                if (($option['uefa_id'] ?? '') === $uefaTeamId) {
                    $selectedUefaLabel = (string) ($option['label'] ?? '');
                    break;
                }
            }
        @endphp
        <section class="panel admin-main" aria-labelledby="admin-squad-auto-uefa-title">
            <div class="section-head">
                <h2 id="admin-squad-auto-uefa-title">Auto-Kader (UEFA)</h2>
            </div>

            <p class="hint">
                Wähle unten ein UEFA-Team der Competition — das ersetzt den FFB-Team-Picker.
                Zuordnung über <code>team_uefa_id</code> / Team-Code / Nationalität.
                Die UEFA-Players-API liefert Kader vor allem für UEFA-Verbände;
                Nicht-UEFA-Teams (z.&nbsp;B. ARG, EGY bei der WM) haben dort oft noch keine Spieler.
                Aktive Kader-Spieler, die nicht bei UEFA stehen, erscheinen mit Status
                <strong>inaktiv</strong> und werden beim Speichern deaktiviert.
            </p>

            @if ($uefaIdentifier === '')
                <p class="hint">
                    Für diese Liga ist kein UEFA-Competition-Identifier hinterlegt.
                    Bitte unter <a href="{{ route('admin.leagues') }}">Ligen</a> setzen.
                </p>
            @else
                <p class="muted">Identifier: <code>{{ $uefaIdentifier }}</code></p>

                <form
                    class="admin-form admin-auto-squad-upload"
                    method="get"
                    action="{{ route('admin.squad') }}"
                    accept-charset="UTF-8"
                >
                    <input type="hidden" name="tab" value="auto-uefa">
                    <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                    <div class="admin-field">
                        <label for="uefa_team_id">UEFA-Team</label>
                        <select
                            id="uefa_team_id"
                            name="uefa_team_id"
                            data-squad-reload-on-change
                            @disabled($uefaTeams === [])
                        >
                            <option value="">— Team wählen —</option>
                            @foreach ($uefaTeams as $option)
                                <option
                                    value="{{ $option['uefa_id'] }}"
                                    @selected($uefaTeamId === (string) $option['uefa_id'])
                                >
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @if ($uefaTeams === [])
                            <p class="hint">Keine UEFA-Teams für diesen Identifier geladen.</p>
                        @endif
                    </div>
                    <noscript>
                        <div class="admin-actions">
                            <button type="submit" class="admin-submit">Anzeigen</button>
                        </div>
                    </noscript>
                </form>

                <form
                    class="admin-form admin-auto-squad-upload"
                    method="post"
                    action="{{ route('admin.squad.auto-uefa.analyze') }}"
                    accept-charset="UTF-8"
                >
                    @csrf
                    <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                    <input type="hidden" name="uefa_team_id" value="{{ $uefaTeamId }}">
                    <div class="admin-actions">
                        <button
                            type="submit"
                            class="admin-submit"
                            data-squad-analyze-btn
                            @disabled($uefaTeamId === '')
                        >
                            Kader prüfen
                        </button>
                    </div>
                </form>

                @if ($uefaTeamId === '')
                    <p class="hint">Bitte zuerst ein UEFA-Team wählen.</p>
                @elseif ($selectedUefaLabel !== '')
                    <p class="muted">Gewählt: {{ $selectedUefaLabel }}</p>
                @endif
            @endif

            @if ($autoAnalyzed && $autoHasRows)
                <div class="admin-auto-squad-result">
                    @if ($autoSource !== '')
                        <p class="muted">Quelle: {{ $autoSource }}@if ($autoFifa !== '') · FIFA: {{ $autoFifa }}@endif</p>
                    @endif

                    <form
                        class="admin-form admin-auto-squad-form"
                        id="admin-auto-squad-form"
                        method="post"
                        action="{{ route('admin.squad.auto-uefa.store') }}"
                        accept-charset="UTF-8"
                    >
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                        <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                        <input type="hidden" name="source_name" value="{{ $autoSource }}">
                        <input type="hidden" name="source_kind" value="uefa">
                        <input type="hidden" name="fifa_code" value="{{ $autoFifa }}">
                        <input type="hidden" name="uefa_team_id" value="{{ $uefaTeamId }}">
                        <input type="hidden" name="players_json" id="admin-auto-squad-players-json" value="">
                        <input type="hidden" name="almost_json" id="admin-auto-squad-almost-json" value="">

                        @include('admin.partials.auto-squad-draft-tables', [
                            'autoPlayers' => $autoPlayers,
                            'autoAlmost' => $autoAlmost,
                            'autoSourceLabel' => $autoSourceLabel,
                            'countries' => $countries,
                            'positions' => $positions,
                            'defaults' => $defaults,
                            'legacyBase' => $legacyBase,
                        ])
                    </form>
                </div>
            @elseif ($autoAnalyzed)
                <p class="muted">Keine Spieler bei UEFA für dieses Team.</p>
            @endif
        </section>
    @endif

    @if ($tab === 'auto-fifa' && $squadLeagueId > 0)
        @php
            $autoSourceLabel = 'FIFA';
            $selectedFifaLabel = '';
            foreach ($fifaTeams as $option) {
                if (($option['fifa_id'] ?? '') === $fifaTeamId) {
                    $selectedFifaLabel = (string) ($option['label'] ?? '');
                    break;
                }
            }
        @endphp
        <section class="panel admin-main" aria-labelledby="admin-squad-auto-fifa-title">
            <div class="section-head">
                <h2 id="admin-squad-auto-fifa-title">Auto-Kader (FIFA)</h2>
            </div>

            <p class="hint">
                Wähle unten ein FIFA-Team der Competition — das ersetzt den FFB-Team-Picker.
                Zuordnung über Team-Code / Nationalität / Namen.
                Aktive Kader-Spieler, die nicht bei FIFA stehen, erscheinen mit Status
                <strong>inaktiv</strong> und werden beim Speichern deaktiviert.
            </p>

            @if ($fifaIdentifier === '')
                <p class="hint">
                    Für diese Liga ist kein FIFA-Competition-Identifier hinterlegt.
                    Bitte unter <a href="{{ route('admin.leagues') }}">Ligen</a> setzen
                    (z.&nbsp;B. <code>idCompetition=17&amp;idSeason=285023</code> für WM 2026).
                </p>
            @else
                <p class="muted">Identifier: <code>{{ $fifaIdentifier }}</code></p>

                <form
                    class="admin-form admin-auto-squad-upload"
                    method="get"
                    action="{{ route('admin.squad') }}"
                    accept-charset="UTF-8"
                >
                    <input type="hidden" name="tab" value="auto-fifa">
                    <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                    <div class="admin-field">
                        <label for="fifa_team_id">FIFA-Team</label>
                        <select
                            id="fifa_team_id"
                            name="fifa_team_id"
                            data-squad-reload-on-change
                            @disabled($fifaTeams === [])
                        >
                            <option value="">— Team wählen —</option>
                            @foreach ($fifaTeams as $option)
                                <option
                                    value="{{ $option['fifa_id'] }}"
                                    @selected($fifaTeamId === (string) $option['fifa_id'])
                                >
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @if ($fifaTeams === [])
                            <p class="hint">Keine FIFA-Teams für diesen Identifier geladen.</p>
                        @endif
                    </div>
                    <noscript>
                        <div class="admin-actions">
                            <button type="submit" class="admin-submit">Anzeigen</button>
                        </div>
                    </noscript>
                </form>

                <form
                    class="admin-form admin-auto-squad-upload"
                    method="post"
                    action="{{ route('admin.squad.auto-fifa.analyze') }}"
                    accept-charset="UTF-8"
                >
                    @csrf
                    <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                    <input type="hidden" name="fifa_team_id" value="{{ $fifaTeamId }}">
                    <div class="admin-actions">
                        <button
                            type="submit"
                            class="admin-submit"
                            data-squad-analyze-btn
                            @disabled($fifaTeamId === '')
                        >
                            Kader prüfen
                        </button>
                    </div>
                </form>

                @if ($fifaTeamId === '')
                    <p class="hint">Bitte zuerst ein FIFA-Team wählen.</p>
                @elseif ($selectedFifaLabel !== '')
                    <p class="muted">Gewählt: {{ $selectedFifaLabel }}</p>
                @endif
            @endif

            @if ($autoAnalyzed && $autoHasRows)
                <div class="admin-auto-squad-result">
                    @if ($autoSource !== '')
                        <p class="muted">Quelle: {{ $autoSource }}@if ($autoFifa !== '') · FIFA: {{ $autoFifa }}@endif</p>
                    @endif

                    <form
                        class="admin-form admin-auto-squad-form"
                        id="admin-auto-squad-form"
                        method="post"
                        action="{{ route('admin.squad.auto-fifa.store') }}"
                        accept-charset="UTF-8"
                    >
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                        <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                        <input type="hidden" name="source_name" value="{{ $autoSource }}">
                        <input type="hidden" name="source_kind" value="fifa">
                        <input type="hidden" name="fifa_code" value="{{ $autoFifa }}">
                        <input type="hidden" name="fifa_team_id" value="{{ $fifaTeamId }}">
                        <input type="hidden" name="players_json" id="admin-auto-squad-players-json" value="">
                        <input type="hidden" name="almost_json" id="admin-auto-squad-almost-json" value="">

                        @include('admin.partials.auto-squad-draft-tables', [
                            'autoPlayers' => $autoPlayers,
                            'autoAlmost' => $autoAlmost,
                            'autoSourceLabel' => $autoSourceLabel,
                            'countries' => $countries,
                            'positions' => $positions,
                            'defaults' => $defaults,
                            'legacyBase' => $legacyBase,
                        ])
                    </form>
                </div>
            @elseif ($autoAnalyzed)
                <p class="muted">Keine Spieler bei FIFA für dieses Team.</p>
            @endif
        </section>
    @endif

    @if ($tab === 'auto' && $squadLeagueId > 0)
        @php
            $autoSourceLabel = 'JSON';
        @endphp
        <section class="panel admin-main" aria-labelledby="admin-squad-auto-title">
            <div class="section-head">
                <h2 id="admin-squad-auto-title">Auto-Kader</h2>
            </div>

            @include('admin.partials.squad-team-picker', [
                'tab' => $tab,
                'teams' => $teams,
                'selectedTeamId' => $selectedTeamId,
                'squadLeagueId' => $squadLeagueId,
                'teamSelectId' => 'team_id_auto',
            ])

            @if ($selectedTeamId > 0)
            <p class="hint">
                Team: <strong>{{ $selectedTeam['team_label'] ?? '' }}</strong>
                @if ($selectedTeamNat !== '')
                    · FIFA-Code: <strong>{{ $selectedTeamNat }}</strong>
                @endif
                · Aktive Kader-Spieler, die nicht in JSON stehen, erscheinen mit Status
                <strong>inaktiv</strong> und werden beim Speichern deaktiviert.
            </p>

            @php
                $squadFiles = is_array($data['squad_files'] ?? null) ? $data['squad_files'] : [];
            @endphp

            <form
                class="admin-form admin-auto-squad-upload"
                method="post"
                action="{{ route('admin.squad.auto.analyze') }}"
                accept-charset="UTF-8"
            >
                @csrf
                <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                <div class="admin-field">
                    <label for="squads_json">Kader-JSON</label>
                    <select id="squads_json" name="squads_json" required @disabled($squadFiles === [])>
                        <option value="">— JSON-Datei wählen —</option>
                        @foreach ($squadFiles as $file)
                            <option value="{{ $file['name'] }}">{{ $file['label'] }}</option>
                        @endforeach
                    </select>
                    <p class="hint">
                        Dateien aus <code>public/data/squad/*.json</code>.
                        Es wird der Kader zum FIFA-Code des gewählten Teams geladen.
                        Erwartete Struktur:
                    </p>
                    <pre class="hint admin-json-hint">[
  {
    "name": "Czech Republic",
    "fifa_code": "CZE",
    "players": [
      {
        "number": 1,
        "pos": "GK",
        "name": "Matěj Kovář"
      },
      {
        "number": 10,
        "pos": "FW",
        "name": "Patrik Schick"
      }
    ]
  }
]</pre>
                </div>
                <div class="admin-actions">
                    <button
                        type="submit"
                        class="admin-submit"
                        data-squad-analyze-btn
                        @disabled($squadFiles === [])
                    >Kader prüfen</button>
                </div>
            </form>

            @if ($squadFiles === [])
                <p class="hint">Noch keine JSON-Dateien unter <code>public/data/squad</code> gefunden.</p>
            @endif

            @if ($autoAnalyzed && $autoHasRows)
                <div class="admin-auto-squad-result">
                    @if ($autoSource !== '')
                        <p class="muted">Datei: {{ $autoSource }}@if ($autoFifa !== '') · FIFA: {{ $autoFifa }}@endif</p>
                    @endif

                    <form
                        class="admin-form admin-auto-squad-form"
                        id="admin-auto-squad-form"
                        method="post"
                        action="{{ route('admin.squad.auto.store') }}"
                        accept-charset="UTF-8"
                    >
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                        <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
                        <input type="hidden" name="source_name" value="{{ $autoSource }}">
                        <input type="hidden" name="source_kind" value="json">
                        <input type="hidden" name="fifa_code" value="{{ $autoFifa }}">
                        <input type="hidden" name="players_json" id="admin-auto-squad-players-json" value="">
                        <input type="hidden" name="almost_json" id="admin-auto-squad-almost-json" value="">

                        @include('admin.partials.auto-squad-draft-tables', [
                            'autoPlayers' => $autoPlayers,
                            'autoAlmost' => $autoAlmost,
                            'autoSourceLabel' => $autoSourceLabel,
                            'countries' => $countries,
                            'positions' => $positions,
                            'defaults' => $defaults,
                            'legacyBase' => $legacyBase,
                        ])
                    </form>
                </div>
            @elseif ($autoAnalyzed)
                <p class="muted">Keine Spieler in JSON für diesen FIFA-Code.</p>
            @endif
            @endif
        </section>
    @endif

    @if ($tab === 'images' && $squadLeagueId > 0)
        <section class="panel admin-main" aria-labelledby="admin-squad-images-title">
            <div class="section-head">
                <h2 id="admin-squad-images-title">Auto-Bilder</h2>
            </div>

            @include('admin.partials.squad-team-picker', [
                'tab' => $tab,
                'teams' => $teams,
                'selectedTeamId' => $selectedTeamId,
                'squadLeagueId' => $squadLeagueId,
                'teamSelectId' => 'team_id_images',
            ])

            @if ($selectedTeamId > 0)
            <p class="hint">
                Alle Spieler von {{ $selectedTeam['team_label'] ?? 'diesem Team' }} in der gewählten Liga.
                Namen ohne Bild kannst du vor der Suche anpassen (nur für die Wikimedia-Abfrage, nicht in der DB).
            </p>

            @if (count($imagePlayers) === 0)
                <p class="muted">Keine Spieler in diesem Kader.</p>
            @else
                <form
                    class="admin-form admin-squad-images-form"
                    method="post"
                    accept-charset="UTF-8"
                >
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">
                    <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">

                    <div class="admin-auto-squad-table-wrap">
                        <table class="admin-auto-squad-table admin-squad-images-table">
                            <thead>
                                <tr>
                                    <th>Bild</th>
                                    <th>Name (Wikimedia)</th>
                                    <th>Pos.</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($imagePlayers as $index => $player)
                                    @php
                                        $status = (string) ($player['status'] ?? 'wird_geprueft');
                                        $pictureUrl = trim((string) ($player['picture_url'] ?? ''));
                                        $lookupName = (string) ($player['lookup_name'] ?? $player['display_name'] ?? '');
                                        $playerId = (int) ($player['player_id'] ?? 0);
                                        $isVorhanden = $status === 'vorhanden';
                                        $showPicture = $pictureUrl !== '' && in_array($status, ['vorhanden', 'gefunden'], true);
                                    @endphp
                                    <tr class="status-{{ $status }}">
                                        <td class="admin-auto-squad-photo">
                                            @if ($showPicture)
                                                <img src="{{ $pictureUrl }}" alt="" width="40" height="40" loading="lazy">
                                            @else
                                                <span class="muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isVorhanden)
                                                <span class="admin-auto-squad-readonly">{{ $lookupName }}</span>
                                            @else
                                                <input type="hidden" name="players[{{ $index }}][player_id]" value="{{ $playerId }}">
                                                <input type="hidden" name="players[{{ $index }}][commons_file]" value="{{ $player['commons_file'] ?? '' }}">
                                                <input type="hidden" name="players[{{ $index }}][thumbnail_url]" value="{{ $pictureUrl }}">
                                                <input
                                                    type="text"
                                                    name="lookup_names[{{ $playerId }}]"
                                                    value="{{ $lookupName }}"
                                                    maxlength="255"
                                                    aria-label="Wikimedia-Name {{ $index + 1 }}"
                                                >
                                            @endif
                                        </td>
                                        <td>{{ strtoupper((string) ($player['playerteam_player_position'] ?? '')) }}</td>
                                        <td>
                                            @switch ($status)
                                                @case ('vorhanden')
                                                    <span class="admin-auto-squad-badge">vorhanden</span>
                                                    @break
                                                @case ('gefunden')
                                                    <span class="admin-auto-squad-badge is-found">gefunden</span>
                                                    @break
                                                @case ('nicht_gefunden')
                                                    <span class="muted">nicht gefunden</span>
                                                    @break
                                                @default
                                                    <span class="muted">wird geprüft</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="admin-actions admin-squad-images-actions">
                        <button
                            type="submit"
                            class="admin-submit"
                            formaction="{{ route('admin.squad.images.check') }}"
                            @disabled($imagesCheckCount <= 0)
                        >
                            Bilder von Wikimedia laden
                            @if ($imagesCheckCount > 0)
                                ({{ $imagesCheckCount }})
                            @endif
                        </button>
                        <button
                            type="submit"
                            class="admin-submit"
                            formaction="{{ route('admin.squad.images.apply') }}"
                            @disabled(! $imagesChecked || $imagesFoundCount <= 0)
                        >
                            Bilder übernehmen
                            @if ($imagesFoundCount > 0)
                                ({{ $imagesFoundCount }})
                            @endif
                        </button>
                    </div>
                </form>
            @endif
            @endif
        </section>
    @endif
@endsection

@push('scripts')
<script src="{{ url('js/admin-bulk-json-form.js') }}"></script>
<script>
(function () {
    document.querySelectorAll('[data-squad-reload-on-change]').forEach(function (select) {
        select.addEventListener('change', function () {
            document.querySelectorAll('[data-squad-analyze-btn]').forEach(function (btn) {
                btn.disabled = true;
            });
            if (select.form) {
                select.form.submit();
            }
        });
    });

    const autoTable = document.getElementById('admin-auto-squad-table');
    const autoAlmostTable = document.getElementById('admin-auto-squad-almost-table');
    const autoSubmit = document.getElementById('admin-auto-squad-submit');

    function refreshAutoSubmitCount() {
        if (!autoSubmit) return;
        const main = autoTable ? autoTable.querySelectorAll('tr.admin-auto-squad-row').length : 0;
        const almost = autoAlmostTable ? autoAlmostTable.querySelectorAll('tr.admin-auto-squad-almost-row').length : 0;
        const count = main + almost;
        autoSubmit.textContent = 'Alle übernehmen (' + count + ')';
        autoSubmit.disabled = count === 0;
    }

    if (autoTable) {
        autoTable.addEventListener('click', function (event) {
            const button = event.target.closest('.admin-auto-squad-discard');
            if (!button) return;
            const row = button.closest('tr.admin-auto-squad-row');
            if (row) {
                row.remove();
            }
            refreshAutoSubmitCount();
        });
    }

    if (autoAlmostTable) {
        autoAlmostTable.addEventListener('click', function (event) {
            const button = event.target.closest('.admin-auto-squad-almost-discard');
            if (!button) return;
            const row = button.closest('tr.admin-auto-squad-almost-row');
            if (row) {
                row.remove();
            }
            refreshAutoSubmitCount();
        });
    }

    AdminBulkJsonForm.bindMulti('#admin-auto-squad-form', [
        {
            hidden: '#admin-auto-squad-players-json',
            rowSelector: 'tr.admin-auto-squad-row',
            fields: {
                player_fname: '.admin-auto-squad-fname',
                player_lname: '.admin-auto-squad-lname',
                player_nationality: 'select.admin-auto-squad-nationality',
                player_foreign_id: '.admin-auto-squad-foreign-id',
                playerteam_player_position: 'select.admin-auto-squad-position',
                playerteam_status: 'select.admin-auto-squad-status'
            }
        },
        {
            hidden: '#admin-auto-squad-almost-json',
            rowSelector: 'tr.admin-auto-squad-almost-row',
            fields: {
                use_existing: '.admin-auto-squad-almost-use-existing',
                playerteam_player_position: 'select.admin-auto-squad-almost-position',
                playerteam_status: 'select.admin-auto-squad-almost-status'
            }
        }
    ]);

    document.querySelectorAll('.admin-squad-photo-input').forEach(function (input) {
        input.addEventListener('change', function () {
            const previewId = input.getAttribute('data-preview');
            const preview = previewId ? document.getElementById(previewId) : null;
            const file = input.files && input.files[0];
            if (!preview || !file) return;
            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.onload = function () {
                URL.revokeObjectURL(url);
            };
        });
    });

    const rosterTable = document.getElementById('squad-roster-table');
    const rosterForm = document.getElementById('squad-roster-form');
    if (rosterTable) {
        const toggleBtns = document.querySelectorAll('[data-roster-filter]');
        const meta = document.getElementById('squad-roster-meta');
        const emptyHint = document.getElementById('squad-roster-empty');
        const saveBtn = document.getElementById('squad-save-all');
        const dirtyHint = document.getElementById('squad-dirty-hint');
        const deleteIdsBox = document.getElementById('squad-delete-ids');
        let rosterFilter = 'active';

        function fieldEl(row, field) {
            return row.querySelector('[data-field="' + field + '"]');
        }

        function fieldValue(row, field) {
            const el = fieldEl(row, field);
            if (!el) return '';
            if (el.type === 'file') {
                return (el.files && el.files.length > 0) ? '1' : '';
            }
            return String(el.value || '');
        }

        function isPendingDelete(row) {
            return row.classList.contains('is-pending-delete');
        }

        function isRowEdited(row) {
            if (fieldValue(row, 'picture') === '1') return true;
            return fieldValue(row, 'position') !== String(row.getAttribute('data-initial-position') || '')
                || fieldValue(row, 'status') !== String(row.getAttribute('data-initial-status') || '')
                || fieldValue(row, 'note') !== String(row.getAttribute('data-initial-note') || '');
        }

        function isRowQueued(row) {
            return isPendingDelete(row) || isRowEdited(row);
        }

        function setRowControlsEnabled(row, enabled) {
            row.querySelectorAll('input, select, textarea').forEach(function (el) {
                el.disabled = !enabled;
            });
            const photoBtn = row.querySelector('.admin-squad-photo-btn');
            if (photoBtn) {
                photoBtn.classList.toggle('is-disabled', !enabled);
                if (enabled) {
                    photoBtn.removeAttribute('aria-disabled');
                } else {
                    photoBtn.setAttribute('aria-disabled', 'true');
                }
            }
        }

        function restoreRow(row) {
            const position = fieldEl(row, 'position');
            const status = fieldEl(row, 'status');
            const note = fieldEl(row, 'note');
            const picture = fieldEl(row, 'picture');
            const preview = row.querySelector('[data-field="picture-preview"]');

            if (position) position.value = String(row.getAttribute('data-initial-position') || '');
            if (status) status.value = String(row.getAttribute('data-initial-status') || '');
            if (note) note.value = String(row.getAttribute('data-initial-note') || '');
            if (picture) picture.value = '';
            if (preview) preview.src = String(row.getAttribute('data-initial-picture') || preview.src);

            const active = String(row.getAttribute('data-initial-status') || '1') === '1';
            row.setAttribute('data-status', active ? 'active' : 'inactive');
            row.classList.toggle('is-inactive', !active);
            row.classList.remove('is-pending-delete');
            setRowControlsEnabled(row, true);
        }

        function markPendingDelete(row) {
            row.classList.add('is-pending-delete');
            setRowControlsEnabled(row, false);
        }

        function refreshDirtyState() {
            let queuedCount = 0;
            let editCount = 0;
            let deleteCount = 0;

            if (deleteIdsBox) deleteIdsBox.innerHTML = '';

            rosterTable.querySelectorAll('.admin-squad-player').forEach(function (row) {
                const pendingDelete = isPendingDelete(row);
                const edited = !pendingDelete && isRowEdited(row);
                const queued = pendingDelete || edited;

                row.classList.toggle('is-dirty', edited);
                row.classList.toggle('is-pending-delete', pendingDelete);

                const undoBtn = row.querySelector('.admin-squad-undo-btn');
                if (undoBtn) {
                    undoBtn.hidden = !queued;
                }

                const deleteBtn = row.querySelector('.admin-squad-delete-btn');
                if (deleteBtn) deleteBtn.hidden = pendingDelete;

                if (pendingDelete) {
                    queuedCount += 1;
                    deleteCount += 1;
                    if (deleteIdsBox) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_ids[]';
                        input.value = String(row.getAttribute('data-playerteam-id') || '');
                        deleteIdsBox.appendChild(input);
                    }
                } else if (edited) {
                    queuedCount += 1;
                    editCount += 1;
                }
            });

            if (saveBtn) {
                saveBtn.disabled = queuedCount === 0;
                saveBtn.textContent = queuedCount === 1
                    ? '1 Änderung speichern'
                    : queuedCount + ' Änderungen speichern';
            }
            if (dirtyHint) {
                if (queuedCount === 0) {
                    dirtyHint.textContent = 'Noch keine Änderungen';
                } else {
                    const parts = [];
                    if (editCount === 1) parts.push('1 geändert');
                    else if (editCount > 1) parts.push(editCount + ' geändert');
                    if (deleteCount === 1) parts.push('1 zum Löschen');
                    else if (deleteCount > 1) parts.push(deleteCount + ' zum Löschen');
                    dirtyHint.textContent = parts.join(', ');
                }
            }
        }

        function applyRosterFilter() {
            let visible = 0;
            document.querySelectorAll('#squad-roster-table .admin-squad-group').forEach(function (group) {
                let groupVisible = 0;
                group.querySelectorAll('.admin-squad-player').forEach(function (row) {
                    const status = row.getAttribute('data-status');
                    const pendingDelete = isPendingDelete(row);
                    const show = pendingDelete || rosterFilter === 'all' || status === 'active';
                    row.hidden = !show;
                    if (show) {
                        groupVisible += 1;
                        visible += 1;
                    }
                });
                group.hidden = groupVisible === 0;
                const countEl = group.querySelector('.admin-squad-group-count');
                if (countEl) countEl.textContent = String(groupVisible);
            });

            if (meta) {
                meta.textContent = rosterFilter === 'active'
                    ? (visible + ' aktive Spieler')
                    : (visible + ' Spieler insgesamt');
            }
            if (emptyHint) emptyHint.hidden = visible > 0;
            rosterTable.hidden = visible === 0;
        }

        toggleBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                rosterFilter = btn.getAttribute('data-roster-filter') || 'active';
                toggleBtns.forEach(function (other) {
                    const active = other === btn;
                    other.classList.toggle('is-active', active);
                    other.setAttribute('aria-pressed', active ? 'true' : 'false');
                });
                applyRosterFilter();
            });
        });

        rosterTable.addEventListener('click', function (event) {
            const undoBtn = event.target.closest('.admin-squad-undo-btn');
            if (undoBtn) {
                const row = undoBtn.closest('.admin-squad-player');
                if (!row) return;
                restoreRow(row);
                applyRosterFilter();
                refreshDirtyState();
                return;
            }

            const deleteBtn = event.target.closest('.admin-squad-delete-btn');
            if (deleteBtn) {
                const row = deleteBtn.closest('.admin-squad-player');
                if (!row) return;
                markPendingDelete(row);
                applyRosterFilter();
                refreshDirtyState();
            }
        });

        rosterTable.addEventListener('change', function (event) {
            const row = event.target.closest('.admin-squad-player');
            if (!row || isPendingDelete(row)) return;
            if (event.target.matches('[data-field="status"]')) {
                const active = String(event.target.value) === '1';
                row.setAttribute('data-status', active ? 'active' : 'inactive');
                row.classList.toggle('is-inactive', !active);
                applyRosterFilter();
            }
            refreshDirtyState();
        });
        rosterTable.addEventListener('input', function (event) {
            const row = event.target.closest('.admin-squad-player');
            if (row && !isPendingDelete(row)) {
                refreshDirtyState();
            }
        });

        if (rosterForm) {
            rosterForm.addEventListener('submit', function (event) {
                let queued = 0;
                rosterTable.querySelectorAll('.admin-squad-player').forEach(function (row) {
                    const pendingDelete = isPendingDelete(row);
                    const edited = !pendingDelete && isRowEdited(row);
                    if (pendingDelete || edited) {
                        queued += 1;
                        if (pendingDelete) {
                            row.querySelectorAll('input, select, textarea').forEach(function (el) {
                                el.disabled = true;
                            });
                        }
                    } else {
                        row.querySelectorAll('input, select, textarea').forEach(function (el) {
                            el.disabled = true;
                        });
                    }
                });
                if (queued === 0) {
                    event.preventDefault();
                    rosterTable.querySelectorAll('.admin-squad-player').forEach(function (row) {
                        if (!isPendingDelete(row)) {
                            setRowControlsEnabled(row, true);
                        }
                    });
                    refreshDirtyState();
                }
            });
        }

        applyRosterFilter();
        refreshDirtyState();
    }

    const batchForm = document.getElementById('squad-batch-form');
    const selectedList = document.getElementById('squad-selected-list');
    const submitBtn = document.getElementById('squad-batch-submit');
    const clearBtn = document.getElementById('squad-staging-clear');
    const stagingEmpty = document.getElementById('squad-staging-empty');
    const stagingHint = document.getElementById('squad-staging-hint');
    const candidateSection = document.getElementById('squad-candidate-section');
    /** @type {Map<number, object>} */
    const selected = new Map();

    let pickPositions = {};
    let pickLegacyBase = '/';
    if (batchForm) {
        pickLegacyBase = batchForm.getAttribute('data-legacy-base') || '/';
        try { pickPositions = JSON.parse(batchForm.getAttribute('data-positions') || '{}') || {}; } catch (e) { pickPositions = {}; }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function getDefaults() {
        return {
            position: String((document.getElementById('batch_pos') || {}).value || 'd'),
            status: String((document.getElementById('batch_status') || {}).value || '1'),
        };
    }

    function positionOptionsHtml(selectedCode) {
        let html = '';
        Object.keys(pickPositions).forEach(function (code) {
            html += '<option value="' + escapeHtml(code) + '"'
                + (String(selectedCode) === String(code) ? ' selected' : '')
                + '>' + escapeHtml(String(code).toUpperCase()) + '</option>';
        });
        return html;
    }

    function refreshSelectionChrome() {
        const count = selected.size;
        if (submitBtn) {
            submitBtn.disabled = count === 0;
            submitBtn.textContent = count === 1
                ? '1 Spieler übernehmen'
                : count + ' Spieler übernehmen';
        }
        if (clearBtn) clearBtn.hidden = count === 0;
        if (stagingEmpty) stagingEmpty.hidden = count > 0;
        if (stagingHint) {
            stagingHint.textContent = count === 0
                ? 'Noch keine Spieler vorgemerkt'
                : (count === 1 ? '1 Spieler vorgemerkt' : count + ' Spieler vorgemerkt');
        }

        document.querySelectorAll('.admin-squad-add-btn').forEach(function (btn) {
            const id = Number(btn.getAttribute('data-player-id'));
            const marked = selected.has(id);
            btn.disabled = marked;
            btn.textContent = marked ? 'Vorgemerkt' : 'Vormerken';
            btn.classList.toggle('is-marked', marked);
        });
    }

    function renderSelectedRow(entry) {
        const id = entry.player_id;
        const photoSrc = entry.picture_url || (pickLegacyBase + 'images/ffb/players/image_na.gif');
        const flag = entry.flag_html
            ? entry.flag_html
            : (entry.flag_url
                ? '<img class="ffb-flag ffb-flag-img" src="' + escapeHtml(entry.flag_url) + '" alt="" width="18" height="13" loading="lazy">'
                : '');
        const tm = entry.tm_url
            ? '<a class="muted" href="' + escapeHtml(entry.tm_url) + '" target="_blank" rel="noopener noreferrer" title="Transfermarkt">TM</a>'
            : '';
        const inactive = String(entry.status) === '0';

        return (
            '<article class="admin-list-item admin-squad-pick-row' + (inactive ? ' is-inactive' : '') + '" data-player-id="' + id + '">' +
                '<img class="admin-player-photo" src="' + escapeHtml(photoSrc) + '" alt="" width="40" height="40" loading="lazy">' +
                '<div class="admin-squad-pick-identity">' +
                    '<div class="admin-squad-pick-meta">' +
                        flag +
                        '<span class="muted">#' + id + '</span>' +
                        tm +
                    '</div>' +
                    '<strong>' + escapeHtml(entry.player_lname || '') + '</strong> ' +
                    '<span>' + escapeHtml(entry.player_fname || '') + '</span>' +
                '</div>' +
                '<div class="admin-squad-pick-fields">' +
                    '<label class="admin-squad-compact">' +
                        '<span class="visually-hidden">Position</span>' +
                        '<select name="items[' + id + '][playerteam_player_position]" aria-label="Position" data-field="position">' +
                            positionOptionsHtml(entry.position) +
                        '</select>' +
                    '</label>' +
                    '<label class="admin-squad-compact">' +
                        '<span class="visually-hidden">Status</span>' +
                        '<select name="items[' + id + '][playerteam_status]" aria-label="Status" data-field="status">' +
                            '<option value="1"' + (String(entry.status) === '1' ? ' selected' : '') + '>aktiv</option>' +
                            '<option value="0"' + (String(entry.status) === '0' ? ' selected' : '') + '>inaktiv</option>' +
                        '</select>' +
                    '</label>' +
                '</div>' +
                '<button type="button" class="admin-icon-btn admin-squad-pick-remove" title="Aus Auswahl entfernen" data-remove-id="' + id + '">' +
                    '<img src="' + escapeHtml(pickLegacyBase) + 'images/ffb/symbols/delete.png" alt="Entfernen" width="16" height="16">' +
                '</button>' +
            '</article>'
        );
    }

    function removeSelected(id) {
        selected.delete(id);
        if (selectedList) {
            const row = selectedList.querySelector('.admin-squad-pick-row[data-player-id="' + id + '"]');
            if (row) row.remove();
        }
        refreshSelectionChrome();
    }

    function addSelected(item) {
        const id = Number(item.player_id);
        if (!id || selected.has(id)) return;
        const defaults = getDefaults();
        const entry = {
            player_id: id,
            player_fname: String(item.player_fname || ''),
            player_lname: String(item.player_lname || ''),
            picture_url: String(item.picture_url || ''),
            flag_url: String(item.flag_url || ''),
            flag_html: String(item.flag_html || ''),
            tm_url: String(item.tm_url || ''),
            position: defaults.position,
            status: defaults.status,
        };
        selected.set(id, entry);
        if (selectedList) {
            selectedList.insertAdjacentHTML('beforeend', renderSelectedRow(entry));
        }
        refreshSelectionChrome();
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            selected.clear();
            if (selectedList) selectedList.innerHTML = '';
            refreshSelectionChrome();
        });
    }

    if (selectedList) {
        selectedList.addEventListener('click', function (event) {
            const btn = event.target.closest('[data-remove-id]');
            if (!btn) return;
            removeSelected(Number(btn.getAttribute('data-remove-id')));
        });
        selectedList.addEventListener('change', function (event) {
            const row = event.target.closest('.admin-squad-pick-row');
            if (!row || !event.target.matches('[data-field="status"]')) return;
            row.classList.toggle('is-inactive', String(event.target.value) === '0');
            const id = Number(row.getAttribute('data-player-id'));
            const entry = selected.get(id);
            if (entry) entry.status = String(event.target.value);
        });
    }

    if (batchForm) {
        batchForm.addEventListener('submit', function (event) {
            if (selected.size === 0) {
                event.preventDefault();
                refreshSelectionChrome();
            }
        });
    }

    if (!candidateSection) {
        refreshSelectionChrome();
        return;
    }

    const searchUrl = candidateSection.getAttribute('data-search-url');
    const excludeTeamId = candidateSection.getAttribute('data-exclude-team-id') || '';
    const excludeLeagueId = candidateSection.getAttribute('data-exclude-league-id') || '';
    const legacyBase = candidateSection.getAttribute('data-legacy-base') || pickLegacyBase;
    const filterQ = document.getElementById('squad_filter_q');
    const filterNat = document.getElementById('squad_filter_nationality');
    const filterReset = document.getElementById('squad-filter-reset');
    const meta = document.getElementById('squad-candidate-meta');
    const list = document.getElementById('squad-candidate-list');
    const emptyEl = document.getElementById('squad-candidate-empty');
    const errorEl = document.getElementById('squad-candidate-error');
    const pager = document.getElementById('squad-candidate-pager');
    const prevBtn = document.getElementById('squad-candidate-prev');
    const nextBtn = document.getElementById('squad-candidate-next');
    const pageLabel = document.getElementById('squad-candidate-page-label');

    let page = 1;
    let lastPage = 1;
    let requestId = 0;
    let debounce = null;

    function renderItem(item) {
        const id = item.player_id;
        const statusIcon = item.player_status ? 'status_pos.png' : 'status_neg.png';
        const statusAlt = item.player_status ? 'aktiv' : 'inaktiv';
        const photoSrc = item.picture_url || (legacyBase + 'images/ffb/players/image_na.gif');
        const photo = '<img class="admin-player-photo" src="' + escapeHtml(photoSrc) + '" alt="" width="40" height="40" loading="lazy">';
        const flag = item.flag_html
            ? item.flag_html
            : (item.flag_url
                ? '<img class="ffb-flag ffb-flag-img" src="' + escapeHtml(item.flag_url) + '" alt="" width="20" height="15" loading="lazy">'
                : '');
        const nat = item.player_nationality_label
            ? '<span class="muted">' + escapeHtml(item.player_nationality_label) + '</span>'
            : '';
        const tm = item.tm_url
            ? '<a class="muted" href="' + escapeHtml(item.tm_url) + '" target="_blank" rel="noopener noreferrer" title="Transfermarkt">TM</a>'
            : '';
        const desc = item.player_status_description
            ? '<p class="muted">' + escapeHtml(item.player_status_description) + '</p>'
            : '';
        const marked = selected.has(Number(id));

        return (
            '<article class="admin-list-item admin-player-row">' +
                photo +
                '<div class="admin-list-body">' +
                    '<div class="admin-match-meta">' +
                        '<img src="' + escapeHtml(legacyBase) + 'images/ffb/symbols/' + statusIcon + '" alt="' + statusAlt + '" width="16" height="16" loading="lazy">' +
                        flag +
                        '<span class="muted">#' + escapeHtml(id) + '</span>' +
                        nat +
                        tm +
                    '</div>' +
                    '<h3 class="admin-list-title">' + escapeHtml(item.player_fname) + ' ' + escapeHtml(item.player_lname) + '</h3>' +
                    desc +
                '</div>' +
                '<div class="admin-list-actions admin-player-row-actions">' +
                    '<button type="button" class="admin-submit admin-squad-add-btn' + (marked ? ' is-marked' : '') + '"' +
                        ' data-player-id="' + escapeHtml(id) + '"' +
                        (marked ? ' disabled' : '') +
                    '>' + (marked ? 'Vorgemerkt' : 'Vormerken') + '</button>' +
                '</div>' +
            '</article>'
        );
    }

    function updateMeta(payload) {
        const total = payload.total || 0;
        const current = payload.page || 1;
        const pages = payload.last_page || 1;
        const q = payload.q || '';
        const nat = payload.nationality || '';
        const filtered = q !== '' || nat !== '';

        if (total === 0) {
            meta.textContent = filtered ? '0 Treffer' : '0 verfügbare Spieler';
            return;
        }

        const from = ((current - 1) * payload.per_page) + 1;
        const to = Math.min(current * payload.per_page, total);
        const scope = filtered ? (total + ' Treffer') : (total + ' verfügbare Spieler');
        meta.textContent = pages > 1
            ? (scope + ' — ' + from + '–' + to)
            : scope;
    }

    function bindAddButtons(items) {
        const byId = {};
        items.forEach(function (item) {
            byId[Number(item.player_id)] = item;
        });
        list.querySelectorAll('.admin-squad-add-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = Number(btn.getAttribute('data-player-id'));
                if (!id || selected.has(id) || !byId[id]) return;
                addSelected(byId[id]);
            });
        });
    }

    function applyPayload(payload) {
        const items = Array.isArray(payload.items) ? payload.items : [];
        page = payload.page || 1;
        lastPage = payload.last_page || 1;

        list.innerHTML = items.map(renderItem).join('');
        bindAddButtons(items);
        updateMeta(payload);
        refreshSelectionChrome();

        if (emptyEl) emptyEl.hidden = items.length > 0;
        if (errorEl) errorEl.hidden = true;
        if (filterReset) {
            filterReset.hidden = (payload.q || '') === '' && (payload.nationality || '') === '';
        }
        if (pager) {
            pager.hidden = lastPage <= 1;
            if (pageLabel) pageLabel.textContent = page + ' / ' + lastPage;
            if (prevBtn) prevBtn.disabled = page <= 1;
            if (nextBtn) nextBtn.disabled = page >= lastPage;
        }
    }

    function loadPlayers(resetPage) {
        if (resetPage) page = 1;
        const myId = ++requestId;
        const params = new URLSearchParams();
        const q = (filterQ && filterQ.value ? filterQ.value : '').trim();
        const nat = filterNat ? filterNat.value : '';
        if (q !== '') params.set('q', q);
        if (nat !== '') params.set('nationality', nat);
        if (excludeTeamId) params.set('exclude_team_id', excludeTeamId);
        if (excludeLeagueId) params.set('exclude_league_id', excludeLeagueId);
        if (page > 1) params.set('page', String(page));

        if (meta) meta.textContent = 'Lade Spieler…';
        if (errorEl) errorEl.hidden = true;

        fetch(searchUrl + (params.toString() ? ('?' + params.toString()) : ''), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(function (payload) {
                if (myId !== requestId) return;
                applyPayload(payload);
            })
            .catch(function () {
                if (myId !== requestId) return;
                list.innerHTML = '';
                if (meta) meta.textContent = '';
                if (emptyEl) emptyEl.hidden = true;
                if (pager) pager.hidden = true;
                if (errorEl) errorEl.hidden = false;
            });
    }

    if (filterQ) {
        filterQ.addEventListener('input', function () {
            window.clearTimeout(debounce);
            debounce = window.setTimeout(function () {
                loadPlayers(true);
            }, 250);
        });
    }
    if (filterNat) {
        filterNat.addEventListener('change', function () {
            loadPlayers(true);
        });
    }
    if (filterReset) {
        filterReset.addEventListener('click', function () {
            if (filterQ) filterQ.value = '';
            if (filterNat) filterNat.value = '';
            loadPlayers(true);
            if (filterQ) filterQ.focus();
        });
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            if (page <= 1) return;
            page -= 1;
            loadPlayers(false);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            if (page >= lastPage) return;
            page += 1;
            loadPlayers(false);
        });
    }

    refreshSelectionChrome();
    loadPlayers(true);
})();
</script>
@endpush
