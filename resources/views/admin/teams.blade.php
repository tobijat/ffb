@extends('layouts.admin')

@section('title', 'Teams')

@section('content')
    @php
        $form = $data['form'];
        $mode = $data['mode'];
        $items = $data['items'];
        $icons = $data['icons'];
        $prices = $data['prices'];
        $selectedSymbol = $data['selected_symbol'] ?? null;
        $usesIconPicker = (bool) ($data['uses_icon_picker'] ?? true);
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $selectedIcon = (string) $form['team_nationality'];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-teams-title">
        <div class="section-head">
            <h2 id="admin-teams-title">Teams</h2>
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
                <label for="team_price">Standardpreis</label>
                <select id="team_price" name="team_price">
                    @foreach ($prices as $price)
                        <option value="{{ $price }}" @selected((int) $form['team_price'] === (int) $price)>
                            {{ $price }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="admin-field">
                <label for="team_status">Status</label>
                <select id="team_status" name="team_status">
                    <option value="1" @selected((int) $form['team_status'] === 1)>aktiv</option>
                    <option value="0" @selected((int) $form['team_status'] === 0)>inaktiv</option>
                </select>
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
                    @if (
                        !$usesIconPicker
                        || !$selectedSymbol
                        || ($selectedSymbol['has_shirt'] ?? false)
                    ) hidden @endif
                >
                    <div class="admin-field admin-field-stack">
                        <label for="team_shirt_file">Trikot hochladen</label>
                        <input id="team_shirt_file" type="file" name="team_shirt_file" accept="image/png,image/jpeg,image/gif,image/webp">
                        <p class="hint">PNG/JPEG/GIF/WebP, max. 2 MB. Wird als <code>shirt_{{ strtoupper($selectedIcon ?: 'KEY') }}.png</code> gespeichert.</p>
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
    </section>

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
                        <span class="muted">Preis {{ $item['team_price'] }}</span>
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
@endsection

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
    if (!picker || !chooser || !toggle || !flagHtml) return;

    function setPreview(option) {
        const key = option ? (option.getAttribute('data-icon-key') || '') : '';
        const label = option ? (option.getAttribute('data-icon-label') || '') : '';
        const html = option ? (option.getAttribute('data-icon-html') || '') : '';
        const shirtUrl = option ? (option.getAttribute('data-shirt-url') || '') : '';
        const hasShirt = option ? option.getAttribute('data-has-shirt') === '1' : false;

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
            if (key && !hasShirt) {
                shirtUpload.hidden = false;
                if (shirtHint) {
                    shirtHint.innerHTML =
                        'PNG/JPEG/GIF/WebP, max. 2 MB. Wird als <code>shirt_' +
                        key.toUpperCase() +
                        '.png</code> gespeichert.';
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
