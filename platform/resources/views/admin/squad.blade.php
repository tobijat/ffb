@extends('layouts.admin')

@section('title', 'Kader')

@section('content')
    @php
        $teams = $data['teams'];
        $selectedTeamId = (int) $data['selected_team_id'];
        $selectedTeam = $data['selected_team'];
        $items = $data['items'];
        $countries = $data['countries'];
        $prices = $data['prices'];
        $positions = $data['positions'];
        $defaults = $data['defaults'];
        $rosterActiveCount = (int) ($data['roster_active_count'] ?? 0);
        $perPage = (int) ($data['per_page'] ?? 100);
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $tab = request()->query('tab') === 'add' ? 'add' : 'roster';
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
            'team_id' => $selectedTeamId > 0 ? $selectedTeamId : null,
        ], static fn ($v) => $v !== null);
        $addQuery = $rosterQuery + ['tab' => 'add'];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-squad-title">
        <div class="section-head">
            <h2 id="admin-squad-title">Kader</h2>
        </div>

        <form class="admin-league-picker" method="get" action="{{ route('admin.squad') }}">
            @if ($tab === 'add')
                <input type="hidden" name="tab" value="add">
            @endif
            <label for="team_id">Team</label>
            <select id="team_id" name="team_id" onchange="this.form.submit()">
                <option value="">— Team wählen —</option>
                @foreach ($teams as $team)
                    <option value="{{ $team['team_id'] }}" @selected($selectedTeamId === (int) $team['team_id'])>
                        {{ $team['team_label'] }}
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

        @if ($selectedTeamId <= 0)
            <p class="hint">Wähle oben ein Team, um dessen Kader zu verwalten.</p>
        @else
            <nav class="admin-squad-tabs" aria-label="Kader-Bereiche">
                <a
                    class="admin-squad-tab{{ $tab === 'roster' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $rosterQuery) }}"
                >
                    Bestand <span class="admin-squad-count" id="squad-tab-roster-count">{{ count($items) }}</span>
                </a>
                <a
                    class="admin-squad-tab{{ $tab === 'add' ? ' is-active' : '' }}"
                    href="{{ route('admin.squad', $addQuery) }}"
                >
                    Spieler hinzufügen
                </a>
            </nav>
        @endif
    </section>

    @if ($selectedTeamId > 0 && $tab === 'roster')
        <section class="panel admin-main" aria-labelledby="admin-squad-roster-title" id="squad-roster-section">
            <div class="section-head admin-squad-roster-head">
                <h2 id="admin-squad-roster-title">{{ $selectedTeam['team_label'] ?? 'Kader' }}</h2>
                @if (count($items) > 0)
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
                                    <th scope="col" class="admin-squad-col-photo">Bild</th>
                                    <th scope="col">Spieler</th>
                                    <th scope="col">Pos.</th>
                                    <th scope="col">Preis</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Transfer</th>
                                </tr>
                            </thead>
                            @foreach ($positionOrder as $posCode)
                                @php $group = $grouped[$posCode] ?? []; @endphp
                                @if (count($group) === 0)
                                    @continue
                                @endif
                                <tbody class="admin-squad-group" data-group-total="{{ count($group) }}">
                                    <tr class="admin-squad-group-head">
                                        <th colspan="6" scope="colgroup">
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
                                            data-initial-price="{{ (int) $item['playerteam_player_price'] }}"
                                            data-initial-status="{{ (int) $item['playerteam_status'] }}"
                                            data-initial-transfer="{{ $item['playerteam_date_transfer'] }}"
                                            data-initial-picture="{{ $item['picture_url'] }}"
                                        >
                                            <td colspan="6" class="admin-squad-player-cell">
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
                                                            @if ($item['player_flag_url'])
                                                                <img src="{{ $item['player_flag_url'] }}" alt="" width="18" height="13" loading="lazy">
                                                            @endif
                                                            <strong>{{ $item['player_lname'] }}</strong>
                                                            <span>{{ $item['player_fname'] }}</span>
                                                        </div>
                                                        <span class="muted admin-squad-ids">#{{ $ptId }}</span>
                                                    </div>

                                                    <label class="admin-squad-compact admin-squad-field-pos">
                                                        <span class="visually-hidden">Position</span>
                                                        <select name="items[{{ $ptId }}][playerteam_player_position]" aria-label="Position" data-field="position">
                                                            @foreach ($positions as $code => $label)
                                                                <option value="{{ $code }}" @selected($item['playerteam_player_position'] === $code)>{{ strtoupper($code) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>

                                                    <label class="admin-squad-compact admin-squad-field-price">
                                                        <span class="visually-hidden">Preis</span>
                                                        <select name="items[{{ $ptId }}][playerteam_player_price]" aria-label="Preis" data-field="price">
                                                            @foreach ($prices as $price)
                                                                <option value="{{ $price }}" @selected((int) $item['playerteam_player_price'] === (int) $price)>{{ $price }}</option>
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

                                                    <label class="admin-squad-compact admin-squad-date admin-squad-field-date">
                                                        <span class="visually-hidden">Transferdatum</span>
                                                        <input
                                                            type="date"
                                                            name="items[{{ $ptId }}][playerteam_date_transfer]"
                                                            value="{{ $item['playerteam_date_transfer'] }}"
                                                            aria-label="Transferdatum"
                                                            data-field="transfer"
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
        </section>
    @endif

    @if ($selectedTeamId > 0 && $tab === 'add')
        <section class="panel admin-main" aria-labelledby="admin-squad-add-title">
            <div class="section-head">
                <h2 id="admin-squad-add-title">Spieler hinzufügen</h2>
            </div>
            <p class="hint">Standardwerte setzen, Spieler vormerken, Werte je Spieler anpassen, dann übernehmen.</p>

            <form
                class="admin-squad-batch"
                method="post"
                action="{{ route('admin.squad.store') }}"
                id="squad-batch-form"
                accept-charset="UTF-8"
                data-legacy-base="{{ $legacyBase }}"
                data-prices='@json($prices)'
                data-positions='@json($positions)'
            >
                @csrf
                <input type="hidden" name="team_id" value="{{ $selectedTeamId }}">

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
                                <span>Preis</span>
                                <select id="batch_price" aria-label="Standard-Preis">
                                    @foreach ($prices as $price)
                                        <option value="{{ $price }}" @selected((int) $defaults['playerteam_player_price'] === (int) $price)>{{ $price }}</option>
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
                            <label class="admin-squad-compact admin-squad-date">
                                <span>Transfer</span>
                                <input id="batch_transfer" type="date" value="{{ $defaults['playerteam_date_transfer'] }}" aria-label="Standard-Transferdatum">
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
        </section>
    @endif
@endsection

@push('scripts')
<script>
(function () {
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
                || fieldValue(row, 'price') !== String(row.getAttribute('data-initial-price') || '')
                || fieldValue(row, 'status') !== String(row.getAttribute('data-initial-status') || '')
                || fieldValue(row, 'transfer') !== String(row.getAttribute('data-initial-transfer') || '');
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
            const price = fieldEl(row, 'price');
            const status = fieldEl(row, 'status');
            const transfer = fieldEl(row, 'transfer');
            const picture = fieldEl(row, 'picture');
            const preview = row.querySelector('[data-field="picture-preview"]');

            if (position) position.value = String(row.getAttribute('data-initial-position') || '');
            if (price) price.value = String(row.getAttribute('data-initial-price') || '');
            if (status) status.value = String(row.getAttribute('data-initial-status') || '');
            if (transfer) transfer.value = String(row.getAttribute('data-initial-transfer') || '');
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
                if (undoBtn) undoBtn.hidden = !queued;

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

    let pickPrices = [];
    let pickPositions = {};
    let pickLegacyBase = '/';
    if (batchForm) {
        pickLegacyBase = batchForm.getAttribute('data-legacy-base') || '/';
        try { pickPrices = JSON.parse(batchForm.getAttribute('data-prices') || '[]') || []; } catch (e) { pickPrices = []; }
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
            price: String((document.getElementById('batch_price') || {}).value || '5'),
            status: String((document.getElementById('batch_status') || {}).value || '1'),
            transfer: String((document.getElementById('batch_transfer') || {}).value || '2008-01-01'),
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

    function priceOptionsHtml(selectedPrice) {
        let html = '';
        pickPrices.forEach(function (price) {
            html += '<option value="' + escapeHtml(price) + '"'
                + (String(selectedPrice) === String(price) ? ' selected' : '')
                + '>' + escapeHtml(price) + '</option>';
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
        const flag = entry.flag_url
            ? '<img src="' + escapeHtml(entry.flag_url) + '" alt="" width="18" height="13" loading="lazy">'
            : '';
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
                        '<span class="visually-hidden">Preis</span>' +
                        '<select name="items[' + id + '][playerteam_player_price]" aria-label="Preis" data-field="price">' +
                            priceOptionsHtml(entry.price) +
                        '</select>' +
                    '</label>' +
                    '<label class="admin-squad-compact">' +
                        '<span class="visually-hidden">Status</span>' +
                        '<select name="items[' + id + '][playerteam_status]" aria-label="Status" data-field="status">' +
                            '<option value="1"' + (String(entry.status) === '1' ? ' selected' : '') + '>aktiv</option>' +
                            '<option value="0"' + (String(entry.status) === '0' ? ' selected' : '') + '>inaktiv</option>' +
                        '</select>' +
                    '</label>' +
                    '<label class="admin-squad-compact admin-squad-date">' +
                        '<span class="visually-hidden">Transferdatum</span>' +
                        '<input type="date" name="items[' + id + '][playerteam_date_transfer]" value="' + escapeHtml(entry.transfer) + '" aria-label="Transferdatum" data-field="transfer">' +
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
            tm_url: String(item.tm_url || ''),
            position: defaults.position,
            price: defaults.price,
            status: defaults.status,
            transfer: defaults.transfer,
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
        const flag = item.flag_url
            ? '<img src="' + escapeHtml(item.flag_url) + '" alt="" width="20" height="15" loading="lazy">'
            : '';
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
