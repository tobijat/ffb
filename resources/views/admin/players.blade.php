@extends('layouts.admin')

@section('title', 'Spieler')

@section('content')
    @php
        $form = $data['form'];
        $countries = $data['countries'];
        $perPage = (int) ($data['per_page'] ?? 100);
        $tab = ($data['tab'] ?? 'manual') === 'duplicates' ? 'duplicates' : 'manual';
        $teams = is_array($data['teams'] ?? null) ? $data['teams'] : [];
        $duplicatesPrepareUrl = (string) ($data['duplicates_prepare_url'] ?? route('admin.players.duplicates.prepare'));
        $duplicatesCheckUrl = (string) ($data['duplicates_check_url'] ?? route('admin.players.duplicates.check'));
        $duplicatesMergeUrl = (string) ($data['duplicates_merge_url'] ?? route('admin.players.duplicates.merge'));
        $playerMergePrepareUrl = (string) ($data['player_merge_prepare_url'] ?? route('admin.players.merge.prepare'));
        $playerMergePreviewUrl = (string) ($data['player_merge_preview_url'] ?? route('admin.players.merge.preview'));
        $playerMergeExecuteUrl = (string) ($data['player_merge_execute_url'] ?? route('admin.players.merge.execute'));
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $okSrc = $legacyBase.'images/ffb/symbols/ok.svg';
        $failSrc = $legacyBase.'images/ffb/symbols/delete.svg';
        $idleSrc = $legacyBase.'images/ffb/symbols/info.svg';
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-players-title">
        <div class="section-head">
            <h2 id="admin-players-title">Spieler</h2>
        </div>

        <nav class="admin-squad-tabs ffb-tabs" aria-label="Spieler-Bereiche">
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'manual' ? ' is-active' : '' }}"
                href="{{ route('admin.players') }}"
            >
                Spieler
            </a>
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'duplicates' ? ' is-active' : '' }}"
                href="{{ route('admin.players', ['tab' => 'duplicates']) }}"
            >
                Duplikate
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
    </section>

    @if ($tab === 'duplicates')
    <section
        class="panel admin-main"
        aria-labelledby="admin-players-duplicates-title"
        id="admin-players-duplicates"
        data-prepare-url="{{ $duplicatesPrepareUrl }}"
        data-check-url="{{ $duplicatesCheckUrl }}"
        data-merge-url="{{ $duplicatesMergeUrl }}"
        data-player-merge-prepare-url="{{ $playerMergePrepareUrl }}"
        data-player-merge-preview-url="{{ $playerMergePreviewUrl }}"
        data-player-merge-execute-url="{{ $playerMergeExecuteUrl }}"
        data-csrf="{{ csrf_token() }}"
        data-ok-src="{{ $okSrc }}"
        data-fail-src="{{ $failSrc }}"
        data-idle-src="{{ $idleSrc }}"
    >
        <div class="section-head">
            <h2 id="admin-players-duplicates-title">Duplikate</h2>
        </div>
        <p class="hint">Vergleicht Spieler des gewählten Teams nach Namensregeln wie beim Auto-Kader.</p>
        <div class="admin-db-cleanup-actions">
            <label class="admin-db-cleanup-team">
                <span class="muted">Team</span>
                <select id="admin-players-duplicates-team">
                    <option value="">— Team wählen —</option>
                    @foreach ($teams as $team)
                        <option value="{{ (int) $team['team_id'] }}">{{ $team['team_name'] }}</option>
                    @endforeach
                </select>
            </label>
            <fieldset class="admin-db-cleanup-options" id="admin-players-duplicates-scope">
                <legend class="muted">Vergleich</legend>
                <label class="admin-db-cleanup-option" for="admin-players-duplicates-scope-db">
                    <input
                        type="radio"
                        name="admin-players-duplicates-scope"
                        id="admin-players-duplicates-scope-db"
                        value="db"
                    >
                    komplette DB
                </label>
                <label class="admin-db-cleanup-option" for="admin-players-duplicates-scope-nationality">
                    <input
                        type="radio"
                        name="admin-players-duplicates-scope"
                        id="admin-players-duplicates-scope-nationality"
                        value="nationality"
                        checked
                    >
                    nur gleiche Nationalität
                </label>
                <label class="admin-db-cleanup-option" for="admin-players-duplicates-scope-team">
                    <input
                        type="radio"
                        name="admin-players-duplicates-scope"
                        id="admin-players-duplicates-scope-team"
                        value="team"
                    >
                    nur innerhalb des Teams
                </label>
            </fieldset>
            <button type="button" class="admin-submit" id="admin-players-duplicates-run">Start</button>
            <img
                class="admin-dashboard-status"
                id="admin-players-duplicates-icon"
                src="{{ $idleSrc }}"
                alt="noch nicht geprüft"
                title="noch nicht geprüft"
                width="16"
                height="16"
                loading="lazy"
            >
            <span class="muted admin-db-cleanup-status" id="admin-players-duplicates-status" hidden></span>
        </div>
        <div
            class="admin-duplicates-progress"
            id="admin-players-duplicates-progress"
            hidden
            role="progressbar"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-valuenow="0"
            aria-labelledby="admin-players-duplicates-progress-label"
        >
            <div class="admin-duplicates-progress-track">
                <div class="admin-duplicates-progress-bar" id="admin-players-duplicates-progress-bar"></div>
            </div>
            <span
                class="muted admin-duplicates-progress-label"
                id="admin-players-duplicates-progress-label"
            >0 %</span>
        </div>
        <div class="admin-duplicates-layout" id="admin-players-duplicates-layout" hidden>
            <div class="admin-duplicates-left">
                <div class="admin-db-cleanup-result" id="admin-players-duplicates-result"></div>
            </div>
            <aside
                class="admin-duplicates-right"
                id="admin-players-duplicates-merge-panel"
                aria-label="Spieler zusammenführen"
            >
                <p class="muted" id="admin-players-merge-placeholder">
                    „Start merge“ bei einem Treffer wählen, um Felder und Folgen hier zu prüfen.
                </p>
                <div id="admin-players-merge-body" hidden></div>
            </aside>
        </div>
    </section>
    @else
    <section class="panel admin-main" aria-labelledby="admin-players-create-title">
        <div class="section-head">
            <h2 id="admin-players-create-title">Spieler hinzufügen</h2>
        </div>

        <form
            class="admin-form"
            method="post"
            action="{{ route('admin.players.store') }}"
            accept-charset="UTF-8"
        >
            @csrf

            <div class="admin-field">
                <label for="player_fname">* Vorname</label>
                <input id="player_fname" type="text" name="player_fname" value="{{ $form['player_fname'] }}" maxlength="255" required>
            </div>

            <div class="admin-field">
                <label for="player_lname">* Nachname</label>
                <input id="player_lname" type="text" name="player_lname" value="{{ $form['player_lname'] }}" maxlength="255" required>
            </div>

            <div class="admin-field">
                <label for="player_nationality">Nationalität</label>
                <select id="player_nationality" name="player_nationality">
                    <option value="">— optional —</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" @selected((string) $form['player_nationality'] === (string) $code)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="admin-field">
                <label for="player_status">Status</label>
                <select id="player_status" name="player_status">
                    <option value="1" @selected((int) $form['player_status'] === 1)>aktiv</option>
                    <option value="0" @selected((int) $form['player_status'] === 0)>inaktiv</option>
                </select>
            </div>

            <div class="admin-field">
                <label for="player_status_description">Status-Hinweis</label>
                <input id="player_status_description" type="text" name="player_status_description" value="{{ $form['player_status_description'] }}" maxlength="255" placeholder="z. B. verletzt, gesperrt">
            </div>

            <div class="admin-field">
                <label for="player_uefa_id">UEFA-ID</label>
                <input id="player_uefa_id" type="text" name="player_uefa_id" value="{{ $form['player_uefa_id'] ?? '' }}" maxlength="64" placeholder="optional">
            </div>

            <div class="admin-field">
                <label for="player_fifa_id">FIFA-ID</label>
                <input id="player_fifa_id" type="text" name="player_fifa_id" value="{{ $form['player_fifa_id'] ?? '' }}" maxlength="64" placeholder="optional">
            </div>

            <div class="admin-actions">
                <button type="submit" class="admin-submit">Hinzufügen</button>
            </div>
        </form>
    </section>

    <section
        class="panel admin-main"
        aria-labelledby="admin-players-list-title"
        id="player-list-section"
        data-search-url="{{ route('admin.players.search') }}"
        data-batch-url="{{ route('admin.players.batchUpdate') }}"
        data-legacy-base="{{ $legacyBase }}"
        data-csrf="{{ csrf_token() }}"
        data-per-page="{{ $perPage }}"
        data-countries='@json($countries)'
    >
        <div class="section-head">
            <h2 id="admin-players-list-title">Vorhandene Spieler</h2>
        </div>

        <form
            class="admin-player-batch-form"
            id="player-batch-form"
            method="post"
            action="{{ route('admin.players.batchUpdate') }}"
            accept-charset="UTF-8"
        >
            @csrf
            <div id="player-batch-payload"></div>

            <div class="admin-squad-savebar" id="player-savebar">
                <button type="submit" class="admin-submit" id="player-save-all" disabled>
                    Änderungen speichern (0)
                </button>
                <span class="muted" id="player-dirty-hint">Noch keine Änderungen</span>
            </div>

            <div class="admin-filter-bar" id="player-list-filters">
                <div class="admin-field">
                    <label for="player_filter_q">Suche</label>
                    <input id="player_filter_q" type="search" value="" placeholder="Name oder ID" autocomplete="off">
                </div>
                <div class="admin-field">
                    <label for="player_filter_nationality">Nationalität</label>
                    <select id="player_filter_nationality">
                        <option value="">— alle —</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-squad-toggle" role="group" aria-label="Listenfilter">
                    <button type="button" class="admin-squad-toggle-btn is-active" data-list-mode="all" aria-pressed="true">
                        Alle
                    </button>
                    <button type="button" class="admin-squad-toggle-btn" data-list-mode="pending" aria-pressed="false" disabled>
                        Ausstehend <span class="admin-squad-count" id="player-pending-count">0</span>
                    </button>
                </div>
                <div class="admin-actions admin-actions-flush">
                    <button type="button" class="admin-cancel" id="player-filter-reset" hidden>Zurücksetzen</button>
                </div>
            </div>

            <p class="muted" id="player-list-meta">Lade Spieler…</p>
            <div id="player-list" aria-live="polite"></div>
            <p class="muted" id="player-list-empty" hidden>Keine passenden Spieler für diesen Filter.</p>
            <p class="muted" id="player-list-error" hidden>Spieler konnten nicht geladen werden.</p>

            <nav class="admin-pagination" id="player-list-pager" hidden aria-label="Spieler-Seiten">
                <button type="button" class="admin-cancel" id="player-list-prev">Zurück</button>
                <span class="muted" id="player-list-page-label">1 / 1</span>
                <button type="button" class="admin-cancel" id="player-list-next">Weiter</button>
            </nav>
        </form>
    </section>
    @endif
@endsection

@push('scripts')
@if ($tab === 'duplicates')
<script>
(function () {
    const root = document.getElementById('admin-players-duplicates');
    if (!root) return;

    const prepareUrl = root.getAttribute('data-prepare-url') || '';
    const checkUrl = root.getAttribute('data-check-url') || '';
    const mergeUrl = root.getAttribute('data-merge-url') || '';
    const playerMergePrepareUrl = root.getAttribute('data-player-merge-prepare-url') || '';
    const playerMergePreviewUrl = root.getAttribute('data-player-merge-preview-url') || '';
    const playerMergeExecuteUrl = root.getAttribute('data-player-merge-execute-url') || '';
    const csrf = root.getAttribute('data-csrf') || '';
    const okSrc = root.getAttribute('data-ok-src') || '';
    const failSrc = root.getAttribute('data-fail-src') || '';
    const idleSrc = root.getAttribute('data-idle-src') || '';
    const teamSelect = document.getElementById('admin-players-duplicates-team');
    const scopeRoot = document.getElementById('admin-players-duplicates-scope');
    const runBtn = document.getElementById('admin-players-duplicates-run');
    const statusEl = document.getElementById('admin-players-duplicates-status');
    const layoutEl = document.getElementById('admin-players-duplicates-layout');
    const resultEl = document.getElementById('admin-players-duplicates-result');
    const mergePlaceholder = document.getElementById('admin-players-merge-placeholder');
    const mergeBody = document.getElementById('admin-players-merge-body');
    const iconEl = document.getElementById('admin-players-duplicates-icon');
    const progressEl = document.getElementById('admin-players-duplicates-progress');
    const progressBar = document.getElementById('admin-players-duplicates-progress-bar');
    const progressLabel = document.getElementById('admin-players-duplicates-progress-label');
    let mergeState = null;

    function setStatus(text, isError) {
        if (!statusEl) return;
        statusEl.hidden = !text;
        statusEl.textContent = text || '';
        statusEl.classList.toggle('is-error', !!isError);
    }

    function setIcon(state) {
        if (!iconEl) return;
        if (state === 'ok') {
            iconEl.src = okSrc;
            iconEl.alt = 'ok';
            iconEl.title = 'ok';
        } else if (state === 'fail') {
            iconEl.src = failSrc;
            iconEl.alt = 'offen';
            iconEl.title = 'offen';
        } else {
            iconEl.src = idleSrc;
            iconEl.alt = 'noch nicht geprüft';
            iconEl.title = 'noch nicht geprüft';
        }
    }

    function setProgress(doneChecks, totalChecks) {
        const total = Math.max(0, Number(totalChecks) || 0);
        const done = Math.max(0, Math.min(total, Number(doneChecks) || 0));
        const pct = total > 0 ? Math.round((done / total) * 100) : 0;
        if (progressEl) {
            progressEl.hidden = false;
            progressEl.setAttribute('aria-valuenow', String(pct));
            progressEl.setAttribute('aria-valuemax', '100');
        }
        if (progressBar) {
            progressBar.style.width = pct + '%';
        }
        if (progressLabel) {
            progressLabel.textContent = pct + ' % · ' + done.toLocaleString('de-AT') + ' / ' + total.toLocaleString('de-AT') + ' Vergleiche';
        }
    }

    function hideProgress() {
        if (progressEl) progressEl.hidden = true;
        if (progressBar) progressBar.style.width = '0%';
        if (progressLabel) progressLabel.textContent = '0 %';
    }

    function jsonHeaders() {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        };
    }

    async function postJson(url, body) {
        const response = await fetch(url, {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify(body),
        });
        const payload = await response.json().catch(function () { return null; });
        return { response: response, payload: payload };
    }

    async function runScan() {
        if (!runBtn || !prepareUrl || !checkUrl || !mergeUrl) return;
        const teamId = Number((teamSelect && teamSelect.value) || 0);
        if (teamId <= 0) {
            setStatus('Bitte zuerst ein Team auswählen.', true);
            setIcon('fail');
            return;
        }

        const scope = selectedScope();

        runBtn.disabled = true;
        if (teamSelect) teamSelect.disabled = true;
        setScopeDisabled(true);
        setStatus('Vorbereitung …', false);
        setProgress(0, 0);
        resetMergePanel();
        if (layoutEl) layoutEl.hidden = true;
        if (resultEl) resultEl.innerHTML = '';

        try {
            const prepared = await postJson(prepareUrl, {
                team_id: teamId,
                scope: scope,
            });
            if (!prepared.response.ok || !prepared.payload || !prepared.payload.ok) {
                const message = (prepared.payload && prepared.payload.error)
                    ? String(prepared.payload.error)
                    : 'Vorbereitung fehlgeschlagen.';
                setStatus(message, true);
                setIcon('fail');
                hideProgress();
                return;
            }

            const players = Array.isArray(prepared.payload.players) ? prepared.payload.players : [];
            const totalChecks = Number(prepared.payload.total_checks) || 0;
            let doneChecks = 0;
            const groups = [];

            setProgress(0, totalChecks);
            setStatus('Läuft …', false);

            for (let i = 0; i < players.length; i++) {
                const player = players[i] || {};
                const playerId = Number(player.player_id) || 0;
                if (playerId <= 0) continue;

                const checked = await postJson(checkUrl, {
                    team_id: teamId,
                    player_id: playerId,
                    scope: scope,
                });
                if (!checked.response.ok || !checked.payload || !checked.payload.ok) {
                    const message = (checked.payload && checked.payload.error)
                        ? String(checked.payload.error)
                        : 'Prüfung fehlgeschlagen.';
                    setStatus(message, true);
                    setIcon('fail');
                    return;
                }

                const stepChecks = Number(checked.payload.checks);
                doneChecks += Number.isFinite(stepChecks) ? stepChecks : (Number(player.checks) || 0);
                setProgress(doneChecks, totalChecks);

                if (checked.payload.has_matches && checked.payload.group) {
                    groups.push(checked.payload.group);
                }
            }

            setProgress(totalChecks, totalChecks);
            setStatus('Zusammenführen …', false);

            const merged = await postJson(mergeUrl, {
                team_id: teamId,
                groups: groups,
            });
            if (!merged.response.ok || !merged.payload || !merged.payload.ok) {
                const message = (merged.payload && merged.payload.error)
                    ? String(merged.payload.error)
                    : 'Zusammenführung fehlgeschlagen.';
                setStatus(message, true);
                setIcon('fail');
                return;
            }

            const summary = String(merged.payload.summary || '');
            setStatus(summary, false);
            setIcon(merged.payload.clean ? 'ok' : 'fail');
            if (resultEl) {
                resultEl.innerHTML = String(merged.payload.html || '');
            }
            if (layoutEl) layoutEl.hidden = false;
            bindMergeButtons();
        } catch (err) {
            setStatus('Netzwerkfehler beim Starten der Prüfung.', true);
            setIcon('fail');
            hideProgress();
        } finally {
            runBtn.disabled = false;
            if (teamSelect) teamSelect.disabled = false;
            setScopeDisabled(false);
        }
    }

    function selectedScope() {
        const checked = scopeRoot
            ? scopeRoot.querySelector('input[name="admin-players-duplicates-scope"]:checked')
            : null;
        const value = checked ? String(checked.value || '') : 'nationality';
        if (value === 'db' || value === 'team' || value === 'nationality') {
            return value;
        }
        return 'nationality';
    }

    function setScopeDisabled(disabled) {
        if (!scopeRoot) return;
        scopeRoot.querySelectorAll('input[name="admin-players-duplicates-scope"]').forEach(function (input) {
            input.disabled = !!disabled;
        });
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function resetMergePanel() {
        mergeState = null;
        if (mergePlaceholder) mergePlaceholder.hidden = false;
        if (mergeBody) {
            mergeBody.hidden = true;
            mergeBody.innerHTML = '';
        }
    }

    function bindMergeButtons() {
        if (!resultEl) return;
        resultEl.querySelectorAll('.admin-duplicates-merge-start').forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                let playerA = Number(btn.getAttribute('data-player-a') || 0);
                let playerB = Number(btn.getAttribute('data-player-b') || 0);
                if (btn.getAttribute('data-uses-team-select') === '1') {
                    const row = btn.closest('.admin-duplicates-player');
                    const select = row ? row.querySelector('.admin-duplicates-merge-team-player') : null;
                    const picked = Number((select && select.value) || 0);
                    if (picked > 0) playerA = picked;
                }
                if (playerA <= 0 || playerB <= 0 || playerA === playerB) {
                    setStatus('Ungültiges Merge-Paar.', true);
                    return;
                }
                openMergePanel(playerA, playerB);
            });
        });
    }

    async function openMergePanel(playerAId, playerBId) {
        if (!playerMergePrepareUrl || !mergeBody) return;
        if (mergePlaceholder) mergePlaceholder.hidden = true;
        mergeBody.hidden = false;
        mergeBody.innerHTML = '<p class="muted">Lade Merge-Daten …</p>';

        const prepared = await postJson(playerMergePrepareUrl, {
            player_a_id: playerAId,
            player_b_id: playerBId,
        });
        if (!prepared.response.ok || !prepared.payload || !prepared.payload.ok) {
            const message = (prepared.payload && prepared.payload.error)
                ? String(prepared.payload.error)
                : 'Merge-Vorbereitung fehlgeschlagen.';
            mergeBody.innerHTML = '<p class="admin-db-cleanup-status is-error">' + escapeHtml(message) + '</p>';
            return;
        }

        mergeState = {
            playerA: prepared.payload.player_a,
            playerB: prepared.payload.player_b,
            fields: Array.isArray(prepared.payload.fields) ? prepared.payload.fields : [],
            collapsesIfAKept: Array.isArray(prepared.payload.collapses_if_a_kept) ? prepared.payload.collapses_if_a_kept : [],
            collapsesIfBKept: Array.isArray(prepared.payload.collapses_if_b_kept) ? prepared.payload.collapses_if_b_kept : [],
            fieldChoices: {},
            keepSide: 'a',
            preview: null,
        };
        renderMergeForm();
    }

    function currentCollapses() {
        if (!mergeState) return [];
        return mergeState.keepSide === 'b' ? mergeState.collapsesIfBKept : mergeState.collapsesIfAKept;
    }

    function snapshotFieldChoices() {
        if (!mergeBody || !mergeState) return;
        mergeState.fields.forEach(function (field) {
            const key = String(field.key || '');
            const checked = mergeBody.querySelector('input[name="admin-merge-field-' + key + '"]:checked');
            if (checked) mergeState.fieldChoices[key] = checked.value === 'b' ? 'b' : 'a';
        });
    }

    function renderMergeForm() {
        if (!mergeBody || !mergeState) return;
        const a = mergeState.playerA;
        const b = mergeState.playerB;
        const keepSide = mergeState.keepSide === 'b' ? 'b' : 'a';
        const collapses = currentCollapses();

        let html = '';
        html += '<div class="admin-merge-panel-head">';
        html += '<h3 class="admin-duplicates-section-title">Spieler zusammenführen</h3>';
        html += '<button type="button" class="admin-cancel" id="admin-players-merge-cancel">Schließen</button>';
        html += '</div>';

        html += '<p class="admin-duplicates-label">Spieler behalten</p>';
        html += '<div class="admin-merge-keep-options">';
        html += '<label class="admin-db-cleanup-option"><input type="radio" name="admin-merge-keep" value="a"' + (keepSide === 'a' ? ' checked' : '') + '> #' + a.player_id + ' ' + escapeHtml(a.display_name || '') + '</label>';
        html += '<label class="admin-db-cleanup-option"><input type="radio" name="admin-merge-keep" value="b"' + (keepSide === 'b' ? ' checked' : '') + '> #' + b.player_id + ' ' + escapeHtml(b.display_name || '') + '</label>';
        html += '</div>';

        // Kader/collapse choices first — position conflicts are the common blocker.
        html += renderCollapseChoicesHtml(collapses);

        html += '<p class="admin-duplicates-label">Spieler-Felder</p>';
        html += '<table class="admin-squad-table admin-merge-fields-table"><thead><tr>';
        html += '<th scope="col">Feld</th><th scope="col">A #' + a.player_id + '</th><th scope="col">B #' + b.player_id + '</th>';
        html += '</tr></thead><tbody>';
        mergeState.fields.forEach(function (field) {
            const key = String(field.key || '');
            const side = mergeState.fieldChoices[key]
                || (String(field.default_side || 'a') === 'b' ? 'b' : 'a');
            html += '<tr data-field-key="' + escapeHtml(key) + '">';
            html += '<th scope="row">' + escapeHtml(field.label || key) + '</th>';
            html += '<td>' + renderFieldChoice('a', key, side, field.value_a, field.image_url_a || (key === 'asset_key' ? a.image_url : '')) + '</td>';
            html += '<td>' + renderFieldChoice('b', key, side, field.value_b, field.image_url_b || (key === 'asset_key' ? b.image_url : '')) + '</td>';
            html += '</tr>';
        });
        html += '</tbody></table>';

        html += '<div class="admin-merge-actions">';
        html += '<button type="button" class="admin-submit" id="admin-players-merge-preview">Vorschau</button>';
        html += '</div>';
        html += '<div id="admin-players-merge-preview-box" class="admin-merge-preview" hidden></div>';

        mergeBody.innerHTML = html;

        mergeBody.querySelectorAll('input[name="admin-merge-keep"]').forEach(function (input) {
            input.addEventListener('change', function () {
                snapshotFieldChoices();
                mergeState.keepSide = input.value === 'b' ? 'b' : 'a';
                mergeState.preview = null;
                renderMergeForm();
            });
        });

        const cancelBtn = document.getElementById('admin-players-merge-cancel');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function (event) {
                event.preventDefault();
                resetMergePanel();
            });
        }
        const previewBtn = document.getElementById('admin-players-merge-preview');
        if (previewBtn) {
            previewBtn.addEventListener('click', function (event) {
                event.preventDefault();
                runMergePreview();
            });
        }
    }

    function renderCollapseChoicesHtml(collapses) {
        let html = '<div class="admin-merge-collapses">';
        html += '<p class="admin-duplicates-label">Kader zusammenlegen</p>';
        if (!Array.isArray(collapses) || !collapses.length) {
            html += '<p class="muted">Kein gemeinsamer Team+Liga-Eintrag — Kader wird nur umgehängt, keine Positions-/Preis-Auswahl nötig.</p>';
            html += '</div>';
            return html;
        }
        html += '<p class="muted">Gleiche Team+Liga: Position und Konflikte (Preis/Stats/FIFA-ID) wählen.</p>';
        collapses.forEach(function (collapse, index) {
            const keepPt = Number(collapse.keep_pt || 0);
            const dropPt = Number(collapse.drop_pt || 0);
            const prefix = 'pt-' + keepPt + '-' + dropPt;
            html += '<div class="admin-merge-collapse-block" data-keep-pt="' + keepPt + '" data-drop-pt="' + dropPt + '">';
            html += '<p class="admin-merge-collapse-title"><strong>Kader #' + (index + 1) + '</strong> ';
            html += escapeHtml(collapse.team_name || ('Team #' + Number(collapse.team_id || 0)));
            html += ' · ' + escapeHtml(collapse.league_title || ('Liga #' + Number(collapse.league_id || 0)));
            html += ' · PT #' + keepPt + ' ← #' + dropPt + '</p>';

            const fields = Array.isArray(collapse.fields) ? collapse.fields : [];
            if (fields.length) {
                html += '<table class="admin-squad-table admin-merge-fields-table"><thead><tr>';
                html += '<th scope="col">Kader-Feld</th><th scope="col">Behalten PT #' + keepPt + '</th><th scope="col">Verlierer PT #' + dropPt + '</th>';
                html += '</tr></thead><tbody>';
                fields.forEach(function (field) {
                    const key = String(field.key || '');
                    const side = String(field.default_side || 'keep') === 'drop' ? 'drop' : 'keep';
                    const name = prefix + '-field-' + key;
                    html += '<tr>';
                    html += '<th scope="row">' + escapeHtml(field.label || key) + '</th>';
                    html += '<td>' + renderKeepDropChoice(name, 'keep', side, field.value_keep) + '</td>';
                    html += '<td>' + renderKeepDropChoice(name, 'drop', side, field.value_drop) + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
            }

            const children = collapse.child_conflicts || {};
            html += renderChildConflictsHtml(prefix, 'playerprice', 'Preise (gleiche Runde)', children.playerprice);
            html += renderChildConflictsHtml(prefix, 'playerstats', 'Stats (gleiche Runde)', children.playerstats);
            html += renderChildConflictsHtml(prefix, 'playerfid', 'FIFA-ID / Name', children.playerfid);
            html += '</div>';
        });
        html += '</div>';
        return html;
    }

    function renderChildConflictsHtml(prefix, kind, label, rows) {
        if (!Array.isArray(rows) || !rows.length) return '';
        let html = '<p class="admin-duplicates-label">' + escapeHtml(label) + '</p>';
        html += '<table class="admin-squad-table admin-merge-fields-table"><thead><tr>';
        html += '<th scope="col">Konflikt</th><th scope="col">Behalten</th><th scope="col">Verlierer</th>';
        html += '</tr></thead><tbody>';
        rows.forEach(function (row) {
            const conflictKey = row.matchround_id != null ? String(row.matchround_id) : String(row.key || 'name');
            const name = prefix + '-child-' + kind + '-' + conflictKey;
            const side = String(row.default_side || 'keep') === 'drop' ? 'drop' : 'keep';
            const title = row.matchround_id != null
                ? ('Runde #' + Number(row.matchround_id))
                : escapeHtml(String(row.key || 'name'));
            html += '<tr>';
            html += '<th scope="row">' + title + '</th>';
            html += '<td>' + renderKeepDropChoice(name, 'keep', side, row.value_keep) + '</td>';
            html += '<td>' + renderKeepDropChoice(name, 'drop', side, row.value_drop) + '</td>';
            html += '</tr>';
        });
        html += '</tbody></table>';
        return html;
    }

    function formatFieldValue(value) {
        if (value == null || value === '') return '—';
        return String(value);
    }

    function renderFieldChoice(side, key, selectedSide, value, imageUrl) {
        let html = '<label class="admin-db-cleanup-option admin-merge-field-choice">';
        html += '<input type="radio" name="admin-merge-field-' + escapeHtml(key) + '" value="' + side + '"' + (selectedSide === side ? ' checked' : '') + '>';
        html += '<span>' + escapeHtml(formatFieldValue(value)) + '</span>';
        html += '</label>';
        if (key === 'asset_key' && imageUrl) {
            html += '<div class="admin-merge-image-preview"><img src="' + escapeHtml(imageUrl) + '" alt="" width="48" height="48" loading="lazy"></div>';
        }
        return html;
    }

    function renderKeepDropChoice(name, side, selectedSide, value) {
        let html = '<label class="admin-db-cleanup-option admin-merge-field-choice">';
        html += '<input type="radio" name="' + escapeHtml(name) + '" value="' + side + '"' + (selectedSide === side ? ' checked' : '') + '>';
        html += '<span>' + escapeHtml(formatFieldValue(value)) + '</span>';
        html += '</label>';
        return html;
    }

    function currentKeepDropIds() {
        const keepSide = mergeState && mergeState.keepSide === 'b' ? 'b' : 'a';
        const keep = keepSide === 'b' ? mergeState.playerB : mergeState.playerA;
        const drop = keepSide === 'b' ? mergeState.playerA : mergeState.playerB;
        return {
            keep_id: Number(keep.player_id) || 0,
            drop_id: Number(drop.player_id) || 0,
            player_a_id: Number(mergeState.playerA.player_id) || 0,
            player_b_id: Number(mergeState.playerB.player_id) || 0,
        };
    }

    function collectFieldChoices() {
        const fields = {
            _player_a_id: Number(mergeState.playerA.player_id) || 0,
            _player_b_id: Number(mergeState.playerB.player_id) || 0,
        };
        mergeState.fields.forEach(function (field) {
            const key = String(field.key || '');
            const checked = mergeBody.querySelector('input[name="admin-merge-field-' + key + '"]:checked');
            fields[key] = checked && checked.value === 'b' ? 'b' : 'a';
            mergeState.fieldChoices[key] = fields[key];
        });
        return fields;
    }

    function collectPlayerteamMerges() {
        const merges = [];
        if (!mergeBody) return merges;
        currentCollapses().forEach(function (collapse) {
            const keepPt = Number(collapse.keep_pt || 0);
            const dropPt = Number(collapse.drop_pt || 0);
            const prefix = 'pt-' + keepPt + '-' + dropPt;
            const fields = {};
            (Array.isArray(collapse.fields) ? collapse.fields : []).forEach(function (field) {
                const key = String(field.key || '');
                const checked = mergeBody.querySelector('input[name="' + prefix + '-field-' + key + '"]:checked');
                fields[key] = checked && checked.value === 'drop' ? 'drop' : 'keep';
            });
            const children = { playerprice: {}, playerstats: {}, playerfid: {} };
            const childConflicts = collapse.child_conflicts || {};
            ['playerprice', 'playerstats', 'playerfid'].forEach(function (kind) {
                const rows = Array.isArray(childConflicts[kind]) ? childConflicts[kind] : [];
                rows.forEach(function (row) {
                    const conflictKey = row.matchround_id != null ? String(row.matchround_id) : String(row.key || 'name');
                    const checked = mergeBody.querySelector('input[name="' + prefix + '-child-' + kind + '-' + conflictKey + '"]:checked');
                    children[kind][conflictKey] = checked && checked.value === 'drop' ? 'drop' : 'keep';
                });
            });
            merges.push({
                keep_pt: keepPt,
                drop_pt: dropPt,
                fields: fields,
                children: children,
            });
        });
        return merges;
    }

    async function runMergePreview() {
        if (!mergeState || !playerMergePreviewUrl) return;
        const ids = currentKeepDropIds();
        const box = document.getElementById('admin-players-merge-preview-box');
        if (!box) return;
        box.hidden = false;
        box.innerHTML = '<p class="muted">Berechne Auswirkungen …</p>';

        const preview = await postJson(playerMergePreviewUrl, {
            keep_id: ids.keep_id,
            drop_id: ids.drop_id,
            fields: collectFieldChoices(),
            playerteam_merges: collectPlayerteamMerges(),
        });
        if (!preview.response.ok || !preview.payload || !preview.payload.ok) {
            const message = (preview.payload && preview.payload.error)
                ? String(preview.payload.error)
                : 'Vorschau fehlgeschlagen.';
            box.innerHTML = '<p class="admin-db-cleanup-status is-error">' + escapeHtml(message) + '</p>';
            return;
        }

        mergeState.preview = preview.payload;
        box.innerHTML = renderPreviewHtml(preview.payload);
        const confirmBtn = document.getElementById('admin-players-merge-execute');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function (event) {
                event.preventDefault();
                runMergeExecute();
            });
        }
    }

    function renderPreviewHtml(payload) {
        const impact = payload.impact || {};
        let html = '<p><strong>' + escapeHtml(payload.summary || '') + '</strong></p>';

        if (Array.isArray(payload.blockers) && payload.blockers.length) {
            html += '<div class="account-flash account-flash-error"><strong>Blockiert</strong><ul>';
            payload.blockers.forEach(function (blocker) {
                html += '<li>' + escapeHtml(blocker) + '</li>';
            });
            html += '</ul></div>';
        }

        html += '<ul class="admin-duplicates-squad-list">';
        html += '<li>Kader umhängen: ' + Number(impact.playerteams_repointed || 0) + '</li>';
        html += '<li>Kader zusammenlegen: ' + Number(impact.playerteams_collapsed || 0) + '</li>';
        html += '<li>Betroffene Userteams/User: ' + Number(impact.affected_users_count || 0) + '</li>';
        html += '<li>Spieler löschen: #' + Number(impact.drop_player_id || 0) + '</li>';
        html += '</ul>';

        const image = impact.image || {};
        if (image.chosen_asset_key || image.note) {
            html += '<p class="admin-duplicates-label">Bild / Asset-Key</p>';
            html += '<ul class="admin-duplicates-squad-list">';
            html += '<li>Behalten: <code>' + escapeHtml(image.chosen_asset_key || '—') + '</code></li>';
            if (image.abandoned_asset_key) {
                html += '<li>Verwerfen: <code>' + escapeHtml(image.abandoned_asset_key) + '</code></li>';
            }
            if (image.note) {
                html += '<li class="muted">' + escapeHtml(image.note) + '</li>';
            }
            html += '</ul>';
        }

        const kaderChanges = Array.isArray(impact.kader_changes) ? impact.kader_changes : [];
        if (kaderChanges.length) {
            html += '<p class="admin-duplicates-label">Kader-Änderungen</p><ul class="admin-duplicates-squad-list">';
            kaderChanges.forEach(function (row) {
                html += '<li><strong>' + escapeHtml(row.label || row.action || '') + '</strong>: ';
                html += escapeHtml(row.team_name || '') + ' · ' + escapeHtml(row.league_title || '');
                if (row.position) html += ' · ' + escapeHtml(String(row.position).toUpperCase());
                html += '<br><span class="muted">' + escapeHtml(row.detail || '') + '</span></li>';
            });
            html += '</ul>';
        }

        const users = Array.isArray(impact.affected_users) ? impact.affected_users : [];
        if (users.length) {
            html += '<p class="admin-duplicates-label">Betroffene User</p><ul class="admin-duplicates-squad-list">';
            users.forEach(function (row) {
                const nick = row.user_nickname ? String(row.user_nickname) : ('User #' + Number(row.user_id || 0));
                html += '<li>' + escapeHtml(nick);
                html += ' · Userteam #' + Number(row.userteam_id || 0);
                if (row.matchround_id) html += ' · Runde #' + Number(row.matchround_id);
                html += ' · ' + escapeHtml(row.role || '');
                html += ' · PT #' + Number(row.playerteam_id || 0);
                html += ' · Slot ' + Number(row.slot || 0);
                html += '</li>';
            });
            html += '</ul>';
        }

        const extremes = Array.isArray(impact.affected_extremeteams) ? impact.affected_extremeteams : [];
        if (extremes.length) {
            html += '<p class="admin-duplicates-label">Top/Flop-Teams</p><ul class="admin-duplicates-squad-list">';
            extremes.forEach(function (row) {
                html += '<li>#' + Number(row.extremeteam_id || 0);
                if (row.top_or_flop) html += ' · ' + escapeHtml(row.top_or_flop);
                if (row.matchround_id) html += ' · Runde #' + Number(row.matchround_id);
                html += ' · PT #' + Number(row.playerteam_id || 0);
                html += '</li>';
            });
            html += '</ul>';
        }

        const updated = impact.rows_updated_by_table || {};
        const discarded = impact.rows_discarded_by_table || {};
        const tables = Object.keys(updated).concat(Object.keys(discarded)).filter(function (key, index, all) {
            return all.indexOf(key) === index;
        }).sort();
        if (tables.length) {
            html += '<p class="admin-duplicates-label">Abhängige Tabellen</p><ul class="admin-duplicates-squad-list">';
            tables.forEach(function (table) {
                const parts = [];
                if (updated[table]) parts.push(updated[table] + ' aktualisieren');
                if (discarded[table]) parts.push(discarded[table] + ' verwerfen');
                html += '<li><code>' + escapeHtml(table) + '</code>: ' + escapeHtml(parts.join(', ')) + '</li>';
            });
            html += '</ul>';
        }

        if (payload.can_execute) {
            html += '<label class="admin-db-cleanup-option admin-merge-confirm-label">';
            html += '<input type="checkbox" id="admin-players-merge-confirm"> Ich habe die Auswirkungen geprüft und will den Merge jetzt ausführen.';
            html += '</label>';
            html += '<div class="admin-merge-actions">';
            html += '<button type="button" class="admin-submit admin-submit-danger" id="admin-players-merge-execute">Merge ausführen</button>';
            html += '</div>';
        }

        return html;
    }

    async function runMergeExecute() {
        if (!mergeState || !playerMergeExecuteUrl) return;
        const confirmEl = document.getElementById('admin-players-merge-confirm');
        if (!confirmEl || !confirmEl.checked) {
            setStatus('Bitte die Bestätigung aktivieren.', true);
            return;
        }
        const ids = currentKeepDropIds();
        const executeBtn = document.getElementById('admin-players-merge-execute');
        if (executeBtn) executeBtn.disabled = true;

        const result = await postJson(playerMergeExecuteUrl, {
            keep_id: ids.keep_id,
            drop_id: ids.drop_id,
            fields: collectFieldChoices(),
            playerteam_merges: collectPlayerteamMerges(),
            confirm: true,
        });
        if (!result.response.ok || !result.payload || !result.payload.ok) {
            const message = (result.payload && result.payload.error)
                ? String(result.payload.error)
                : 'Merge fehlgeschlagen.';
            setStatus(message, true);
            if (executeBtn) executeBtn.disabled = false;
            return;
        }

        setStatus(String(result.payload.message || 'Merge abgeschlossen.'), false);
        setIcon('ok');
        resetMergePanel();
        if (mergePlaceholder) {
            mergePlaceholder.hidden = false;
            mergePlaceholder.textContent = 'Merge abgeschlossen. Bitte Duplikate erneut starten, um die Liste zu aktualisieren.';
        }
    }

    if (runBtn) {
        runBtn.addEventListener('click', function (event) {
            event.preventDefault();
            runScan();
        });
    }
})();
</script>
@else
<script>
(function () {
    const section = document.getElementById('player-list-section');
    if (!section) return;

    const searchUrl = section.getAttribute('data-search-url');
    const legacyBase = section.getAttribute('data-legacy-base') || '/';
    let countries = {};
    try {
        countries = JSON.parse(section.getAttribute('data-countries') || '{}') || {};
    } catch (e) {
        countries = {};
    }

    const filterQ = document.getElementById('player_filter_q');
    const filterNat = document.getElementById('player_filter_nationality');
    const filterReset = document.getElementById('player-filter-reset');
    const meta = document.getElementById('player-list-meta');
    const list = document.getElementById('player-list');
    const emptyEl = document.getElementById('player-list-empty');
    const errorEl = document.getElementById('player-list-error');
    const pager = document.getElementById('player-list-pager');
    const prevBtn = document.getElementById('player-list-prev');
    const nextBtn = document.getElementById('player-list-next');
    const pageLabel = document.getElementById('player-list-page-label');
    const batchForm = document.getElementById('player-batch-form');
    const payloadBox = document.getElementById('player-batch-payload');
    const saveBtn = document.getElementById('player-save-all');
    const dirtyHint = document.getElementById('player-dirty-hint');
    const modeBtns = document.querySelectorAll('[data-list-mode]');
    const pendingCountEl = document.getElementById('player-pending-count');

    let page = 1;
    let lastPage = 1;
    let requestId = 0;
    let debounce = null;
    let listMode = 'all';
    /** @type {object|null} */
    let lastPayload = null;

    /** @type {Map<number, object>} */
    const initials = new Map();
    /** @type {Map<number, object>} */
    const edits = new Map();
    /** @type {Set<number>} */
    const deletes = new Set();
    /** @type {number[]} */
    const queueOrder = [];

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function snapshotFromItem(item) {
        return {
            player_fname: String(item.player_fname || ''),
            player_lname: String(item.player_lname || ''),
            player_nationality: String(item.player_nationality || ''),
            player_status: String(Number(item.player_status) ? 1 : 0),
            player_status_description: String(item.player_status_description || ''),
            player_uefa_id: String(item.player_uefa_id || ''),
            player_fifa_id: String(item.player_fifa_id || ''),
            picture_url: String(item.picture_url || ''),
            flag_url: String(item.flag_url || ''),
            flag_html: String(item.flag_html || ''),
        };
    }

    function valuesEqual(a, b) {
        return a.player_fname === b.player_fname
            && a.player_lname === b.player_lname
            && a.player_nationality === b.player_nationality
            && a.player_status === b.player_status
            && a.player_status_description === b.player_status_description
            && a.player_uefa_id === b.player_uefa_id
            && a.player_fifa_id === b.player_fifa_id;
    }

    function isQueued(id) {
        return deletes.has(id) || edits.has(id);
    }

    function queuedCount() {
        let count = deletes.size;
        edits.forEach(function (_value, id) {
            if (!deletes.has(id)) count += 1;
        });
        return count;
    }

    function touchQueue(id) {
        if (queueOrder.indexOf(id) === -1) queueOrder.push(id);
    }

    function removeFromQueueOrder(id) {
        const index = queueOrder.indexOf(id);
        if (index >= 0) queueOrder.splice(index, 1);
    }

    function queuedIds() {
        const active = [];
        queueOrder.forEach(function (id) {
            if (isQueued(id) && initials.has(id)) active.push(id);
        });
        edits.forEach(function (_value, id) {
            if (!deletes.has(id) && initials.has(id) && active.indexOf(id) === -1) active.push(id);
        });
        deletes.forEach(function (id) {
            if (initials.has(id) && active.indexOf(id) === -1) active.push(id);
        });
        return active;
    }

    function currentValues(id) {
        if (edits.has(id)) return edits.get(id);
        return initials.get(id);
    }

    function matchesClientFilters(values, id) {
        const q = (filterQ && filterQ.value ? filterQ.value : '').trim().toLowerCase();
        const nat = filterNat ? filterNat.value : '';
        if (nat !== '' && String(values.player_nationality) !== String(nat)) return false;
        if (q !== '') {
            const hay = (
                String(values.player_fname || '') + ' ' +
                String(values.player_lname || '') + ' ' +
                String(id)
            ).toLowerCase();
            if (hay.indexOf(q) === -1) return false;
        }
        return true;
    }

    function nationalityOptions(selected) {
        let html = '<option value="">— optional —</option>';
        Object.keys(countries).forEach(function (code) {
            html += '<option value="' + escapeHtml(code) + '"'
                + (String(selected) === String(code) ? ' selected' : '')
                + '>' + escapeHtml(countries[code]) + '</option>';
        });
        return html;
    }

    function renderItem(item) {
        const id = Number(item.player_id);
        if (!initials.has(id)) {
            initials.set(id, snapshotFromItem(item));
        }

        const initial = initials.get(id);
        const values = currentValues(id) || initial;
        const pendingDelete = deletes.has(id);
        const dirty = !pendingDelete && edits.has(id);
        const statusActive = String(values.player_status) === '1';
        const photoSrc = initial.picture_url || (legacyBase + 'images/ffb/players/image_na.gif');
        const flag = initial.flag_html
            ? initial.flag_html
            : (initial.flag_url
                ? '<img class="ffb-flag ffb-flag-img" src="' + escapeHtml(initial.flag_url) + '" alt="" width="18" height="13" loading="lazy">'
                : '');

        const classes = ['admin-list-item', 'admin-player-row'];
        if (!statusActive) classes.push('is-inactive');
        if (dirty) classes.push('is-dirty');
        if (pendingDelete) classes.push('is-pending-delete');

        return (
            '<article class="' + classes.join(' ') + '" data-player-id="' + id + '">' +
                '<img class="admin-player-photo" src="' + escapeHtml(photoSrc) + '" alt="" width="40" height="40" loading="lazy">' +
                '<div class="admin-player-edit-grid">' +
                    '<div class="admin-player-id-meta">' +
                        flag +
                        '<span class="muted">#' + id + '</span>' +
                    '</div>' +
                    '<label class="admin-squad-compact admin-player-field-fname">' +
                        '<span class="visually-hidden">Vorname</span>' +
                        '<input type="text" data-field="player_fname" value="' + escapeHtml(values.player_fname) + '" maxlength="255" ' + (pendingDelete ? 'disabled' : '') + '>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-player-field-lname">' +
                        '<span class="visually-hidden">Nachname</span>' +
                        '<input type="text" data-field="player_lname" value="' + escapeHtml(values.player_lname) + '" maxlength="255" ' + (pendingDelete ? 'disabled' : '') + '>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-player-field-nat">' +
                        '<span class="visually-hidden">Nationalität</span>' +
                        '<select data-field="player_nationality" ' + (pendingDelete ? 'disabled' : '') + '>' +
                            nationalityOptions(values.player_nationality) +
                        '</select>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-player-field-status">' +
                        '<span class="visually-hidden">Status</span>' +
                        '<select data-field="player_status" ' + (pendingDelete ? 'disabled' : '') + '>' +
                            '<option value="1"' + (statusActive ? ' selected' : '') + '>aktiv</option>' +
                            '<option value="0"' + (!statusActive ? ' selected' : '') + '>inaktiv</option>' +
                        '</select>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-player-field-desc">' +
                        '<span class="visually-hidden">Status-Hinweis</span>' +
                        '<input type="text" data-field="player_status_description" value="' + escapeHtml(values.player_status_description) + '" maxlength="255" placeholder="Hinweis" ' + (pendingDelete ? 'disabled' : '') + '>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-player-field-uefa">' +
                        '<span class="visually-hidden">UEFA-ID</span>' +
                        '<input type="text" data-field="player_uefa_id" value="' + escapeHtml(values.player_uefa_id) + '" maxlength="64" placeholder="UEFA-ID" ' + (pendingDelete ? 'disabled' : '') + '>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-player-field-fifa">' +
                        '<span class="visually-hidden">FIFA-ID</span>' +
                        '<input type="text" data-field="player_fifa_id" value="' + escapeHtml(values.player_fifa_id) + '" maxlength="64" placeholder="FIFA-ID" ' + (pendingDelete ? 'disabled' : '') + '>' +
                    '</label>' +
                '</div>' +
                '<div class="admin-player-row-tools">' +
                    '<button type="button" class="admin-icon-btn admin-player-undo-btn" title="Rückgängig"' + (dirty || pendingDelete ? '' : ' hidden') + '>' +
                        '<img src="' + escapeHtml(legacyBase) + 'images/ffb/symbols/change.svg" alt="Rückgängig" width="16" height="16">' +
                    '</button>' +
                    '<button type="button" class="admin-icon-btn admin-player-delete-btn" title="Zum Löschen vormerken"' + (pendingDelete ? ' hidden' : '') + '>' +
                        '<img src="' + escapeHtml(legacyBase) + 'images/ffb/symbols/delete.svg" alt="Löschen" width="16" height="16">' +
                    '</button>' +
                '</div>' +
            '</article>'
        );
    }

    function updatePendingToggle() {
        const count = queuedCount();
        if (pendingCountEl) pendingCountEl.textContent = String(count);
        modeBtns.forEach(function (btn) {
            const mode = btn.getAttribute('data-list-mode');
            const active = mode === listMode;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            if (mode === 'pending') {
                btn.disabled = count === 0 && listMode !== 'pending';
            }
        });
    }

    function refreshSavebar() {
        let editCount = 0;
        let deleteCount = 0;
        edits.forEach(function (_value, id) {
            if (!deletes.has(id)) editCount += 1;
        });
        deleteCount = deletes.size;
        const queued = editCount + deleteCount;

        if (saveBtn) {
            saveBtn.disabled = queued === 0;
            saveBtn.textContent = queued === 1
                ? '1 Änderung speichern'
                : queued + ' Änderungen speichern';
        }
        if (dirtyHint) {
            if (queued === 0) {
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
        updatePendingToggle();
    }

    function renderPendingList() {
        const totalQueued = queuedCount();
        const ids = queuedIds().filter(function (id) {
            const values = currentValues(id);
            return values && matchesClientFilters(values, id);
        });

        list.innerHTML = ids.map(function (id) {
            return renderItem(Object.assign({ player_id: id }, initials.get(id)));
        }).join('');

        if (meta) {
            meta.textContent = totalQueued === 0
                ? 'Keine ausstehenden Änderungen'
                : (ids.length === totalQueued
                    ? (totalQueued === 1 ? '1 ausstehend' : totalQueued + ' ausstehend')
                    : (ids.length + ' von ' + totalQueued + ' ausstehend'));
        }
        if (emptyEl) {
            emptyEl.textContent = totalQueued === 0
                ? 'Keine ausstehenden Änderungen.'
                : 'Keine passenden Spieler für diesen Filter.';
            emptyEl.hidden = ids.length > 0;
        }
        if (errorEl) errorEl.hidden = true;
        if (pager) pager.hidden = true;
        refreshSavebar();
    }

    function setListMode(mode) {
        if (mode !== 'all' && mode !== 'pending') return;
        if (mode === 'pending' && queuedCount() === 0) return;
        listMode = mode;
        updatePendingToggle();
        if (listMode === 'pending') {
            renderPendingList();
            return;
        }
        if (lastPayload) {
            applyPayload(lastPayload);
        } else {
            loadPlayers(false);
        }
    }

    function readRowValues(row) {
        return {
            player_fname: String((row.querySelector('[data-field="player_fname"]') || {}).value || ''),
            player_lname: String((row.querySelector('[data-field="player_lname"]') || {}).value || ''),
            player_nationality: String((row.querySelector('[data-field="player_nationality"]') || {}).value || ''),
            player_status: String((row.querySelector('[data-field="player_status"]') || {}).value || '1'),
            player_status_description: String((row.querySelector('[data-field="player_status_description"]') || {}).value || ''),
            player_uefa_id: String((row.querySelector('[data-field="player_uefa_id"]') || {}).value || ''),
            player_fifa_id: String((row.querySelector('[data-field="player_fifa_id"]') || {}).value || ''),
        };
    }

    function syncRowFromDom(row) {
        const id = Number(row.getAttribute('data-player-id'));
        if (!id || deletes.has(id) || !initials.has(id)) return;

        const values = readRowValues(row);
        const initial = initials.get(id);
        if (valuesEqual(values, {
            player_fname: initial.player_fname,
            player_lname: initial.player_lname,
            player_nationality: initial.player_nationality,
            player_status: initial.player_status,
            player_status_description: initial.player_status_description,
            player_uefa_id: initial.player_uefa_id,
            player_fifa_id: initial.player_fifa_id,
        })) {
            edits.delete(id);
            removeFromQueueOrder(id);
            if (listMode === 'pending') {
                refreshSavebar();
                if (queuedCount() === 0) {
                    setListMode('all');
                } else {
                    renderPendingList();
                }
                return;
            }
            row.classList.remove('is-dirty');
        } else {
            edits.set(id, Object.assign({}, initial, values));
            touchQueue(id);
            row.classList.add('is-dirty');
        }

        const active = values.player_status === '1';
        row.classList.toggle('is-inactive', !active);

        const undoBtn = row.querySelector('.admin-player-undo-btn');
        if (undoBtn) undoBtn.hidden = !edits.has(id) && !deletes.has(id);
        refreshSavebar();
    }

    function undoRow(row) {
        const id = Number(row.getAttribute('data-player-id'));
        if (!id || !initials.has(id)) return;
        edits.delete(id);
        deletes.delete(id);
        removeFromQueueOrder(id);
        refreshSavebar();

        if (listMode === 'pending') {
            if (queuedCount() === 0) {
                setListMode('all');
            } else {
                renderPendingList();
            }
            return;
        }

        const item = Object.assign({ player_id: id }, initials.get(id));
        row.outerHTML = renderItem(item);
    }

    function markDelete(row) {
        const id = Number(row.getAttribute('data-player-id'));
        if (!id) return;
        deletes.add(id);
        touchQueue(id);
        row.classList.add('is-pending-delete');
        row.classList.remove('is-dirty');
        row.querySelectorAll('input, select').forEach(function (el) {
            el.disabled = true;
        });
        const undoBtn = row.querySelector('.admin-player-undo-btn');
        const deleteBtn = row.querySelector('.admin-player-delete-btn');
        if (undoBtn) undoBtn.hidden = false;
        if (deleteBtn) deleteBtn.hidden = true;
        refreshSavebar();
        if (listMode === 'pending') renderPendingList();
    }

    function updateMeta(payload) {
        const total = payload.total || 0;
        const current = payload.page || 1;
        const pages = payload.last_page || 1;
        const q = payload.q || '';
        const nat = payload.nationality || '';
        const filtered = q !== '' || nat !== '';

        if (total === 0) {
            meta.textContent = filtered ? '0 Treffer' : '0 Spieler';
            return;
        }

        const from = ((current - 1) * payload.per_page) + 1;
        const to = Math.min(current * payload.per_page, total);
        const scope = filtered ? (total + ' Treffer') : (total + ' Spieler');
        meta.textContent = pages > 1
            ? (scope + ' — ' + from + '–' + to)
            : scope;
    }

    function applyPayload(payload) {
        lastPayload = payload;
        const items = Array.isArray(payload.items) ? payload.items : [];
        page = payload.page || 1;
        lastPage = payload.last_page || 1;

        items.forEach(function (item) {
            const id = Number(item.player_id);
            if (!initials.has(id)) {
                initials.set(id, snapshotFromItem(item));
            }
        });

        if (listMode === 'pending') {
            renderPendingList();
            if (filterReset) {
                filterReset.hidden = (payload.q || '') === '' && (payload.nationality || '') === '';
            }
            return;
        }

        list.innerHTML = items.map(renderItem).join('');
        updateMeta(payload);
        refreshSavebar();

        if (emptyEl) {
            emptyEl.textContent = 'Keine passenden Spieler für diesen Filter.';
            emptyEl.hidden = items.length > 0;
        }
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
        if (page > 1) params.set('page', String(page));

        if (meta && listMode === 'all') meta.textContent = 'Lade Spieler…';
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
                if (listMode === 'pending') return;
                list.innerHTML = '';
                if (meta) meta.textContent = '';
                if (emptyEl) emptyEl.hidden = true;
                if (pager) pager.hidden = true;
                if (errorEl) errorEl.hidden = false;
            });
    }

    function appendHidden(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = String(value);
        payloadBox.appendChild(input);
    }

    function onFiltersChanged() {
        if (listMode === 'pending') {
            renderPendingList();
            if (filterReset) {
                const q = (filterQ && filterQ.value ? filterQ.value : '').trim();
                const nat = filterNat ? filterNat.value : '';
                filterReset.hidden = q === '' && nat === '';
            }
            return;
        }
        loadPlayers(true);
    }

    modeBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            setListMode(btn.getAttribute('data-list-mode') || 'all');
        });
    });

    if (list) {
        list.addEventListener('change', function (event) {
            const row = event.target.closest('.admin-player-row');
            if (row) syncRowFromDom(row);
        });
        list.addEventListener('input', function (event) {
            const row = event.target.closest('.admin-player-row');
            if (row) syncRowFromDom(row);
        });
        list.addEventListener('click', function (event) {
            const undoBtn = event.target.closest('.admin-player-undo-btn');
            if (undoBtn) {
                const row = undoBtn.closest('.admin-player-row');
                if (row) undoRow(row);
                return;
            }
            const deleteBtn = event.target.closest('.admin-player-delete-btn');
            if (deleteBtn) {
                const row = deleteBtn.closest('.admin-player-row');
                if (row) markDelete(row);
            }
        });
    }

    if (batchForm) {
        batchForm.addEventListener('submit', function (event) {
            if (!payloadBox) return;
            payloadBox.innerHTML = '';

            let queued = 0;
            edits.forEach(function (values, id) {
                if (deletes.has(id)) return;
                queued += 1;
                appendHidden('items[' + id + '][player_fname]', values.player_fname);
                appendHidden('items[' + id + '][player_lname]', values.player_lname);
                appendHidden('items[' + id + '][player_nationality]', values.player_nationality);
                appendHidden('items[' + id + '][player_status]', values.player_status);
                appendHidden('items[' + id + '][player_status_description]', values.player_status_description);
                appendHidden('items[' + id + '][player_uefa_id]', values.player_uefa_id);
                appendHidden('items[' + id + '][player_fifa_id]', values.player_fifa_id);
            });
            deletes.forEach(function (id) {
                queued += 1;
                appendHidden('delete_ids[]', id);
            });

            if (queued === 0) {
                event.preventDefault();
                refreshSavebar();
            }
        });
    }

    if (filterQ) {
        filterQ.addEventListener('input', function () {
            window.clearTimeout(debounce);
            debounce = window.setTimeout(onFiltersChanged, 250);
        });
    }
    if (filterNat) {
        filterNat.addEventListener('change', onFiltersChanged);
    }
    if (filterReset) {
        filterReset.addEventListener('click', function () {
            if (filterQ) filterQ.value = '';
            if (filterNat) filterNat.value = '';
            onFiltersChanged();
            if (filterQ) filterQ.focus();
        });
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            if (listMode === 'pending' || page <= 1) return;
            page -= 1;
            loadPlayers(false);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            if (listMode === 'pending' || page >= lastPage) return;
            page += 1;
            loadPlayers(false);
        });
    }

    refreshSavebar();
    loadPlayers(true);
})();
</script>
@endif
@endpush
