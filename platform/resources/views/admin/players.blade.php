@extends('layouts.admin')

@section('title', 'Spieler')

@section('content')
    @php
        $form = $data['form'];
        $countries = $data['countries'];
        $perPage = (int) ($data['per_page'] ?? 100);
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-players-title">
        <div class="section-head">
            <h2 id="admin-players-title">Spieler</h2>
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
                <label for="player_foreign_id">TM-ID (transfermarkt.at)</label>
                <input id="player_foreign_id" type="text" name="player_foreign_id" value="{{ $form['player_foreign_id'] }}" maxlength="255" placeholder="z. B. 232454/nadiem-amiri">
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
@endsection

@push('scripts')
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
            player_foreign_id: String(item.player_foreign_id || ''),
            picture_url: String(item.picture_url || ''),
            flag_url: String(item.flag_url || ''),
            tm_url: String(item.tm_url || ''),
        };
    }

    function valuesEqual(a, b) {
        return a.player_fname === b.player_fname
            && a.player_lname === b.player_lname
            && a.player_nationality === b.player_nationality
            && a.player_status === b.player_status
            && a.player_status_description === b.player_status_description
            && a.player_foreign_id === b.player_foreign_id;
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
        const flag = initial.flag_url
            ? '<img src="' + escapeHtml(initial.flag_url) + '" alt="" width="18" height="13" loading="lazy">'
            : '';
        const tm = initial.tm_url
            ? '<a class="muted" href="' + escapeHtml(initial.tm_url) + '" target="_blank" rel="noopener noreferrer" title="Transfermarkt">TM</a>'
            : '';

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
                        tm +
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
                    '<label class="admin-squad-compact admin-player-field-tm">' +
                        '<span class="visually-hidden">TM-ID</span>' +
                        '<input type="text" data-field="player_foreign_id" value="' + escapeHtml(values.player_foreign_id) + '" maxlength="255" placeholder="TM-ID" ' + (pendingDelete ? 'disabled' : '') + '>' +
                    '</label>' +
                '</div>' +
                '<div class="admin-player-row-tools">' +
                    '<button type="button" class="admin-icon-btn admin-player-undo-btn" title="Rückgängig"' + (dirty || pendingDelete ? '' : ' hidden') + '>' +
                        '<img src="' + escapeHtml(legacyBase) + 'images/ffb/symbols/change.png" alt="Rückgängig" width="16" height="16">' +
                    '</button>' +
                    '<button type="button" class="admin-icon-btn admin-player-delete-btn" title="Zum Löschen vormerken"' + (pendingDelete ? ' hidden' : '') + '>' +
                        '<img src="' + escapeHtml(legacyBase) + 'images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">' +
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
            player_foreign_id: String((row.querySelector('[data-field="player_foreign_id"]') || {}).value || ''),
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
            player_foreign_id: initial.player_foreign_id,
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
                appendHidden('items[' + id + '][player_foreign_id]', values.player_foreign_id);
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
@endpush
