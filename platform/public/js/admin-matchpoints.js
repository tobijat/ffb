(function () {
    const root = document.querySelector('.admin-mp');
    if (!root) return;

    const roundsUrl = root.dataset.roundsUrl || '';
    const matchesTpl = root.dataset.matchesUrlTemplate || '';
    const playersTpl = root.dataset.playersUrlTemplate || '';
    const resultTpl = root.dataset.resultUrlTemplate || '';
    const savePlayerTpl = root.dataset.savePlayerUrlTemplate || '';
    const csrf = root.dataset.csrf || '';
    const imagesBase = root.dataset.imagesBase || '/images/ffb/';
    const symbols = imagesBase + 'symbols/';
    const undoIcon = symbols + 'change.png';

    const leagueSelect = document.getElementById('admin-mp-league');
    const roundSelect = document.getElementById('admin-mp-round');
    const matchSelect = document.getElementById('admin-mp-match');
    const resultEl = document.getElementById('admin-mp-result');
    const legendEl = document.getElementById('admin-mp-legend');
    const loadingEl = document.getElementById('admin-mp-loading');
    const homeSection = document.getElementById('admin-mp-home-section');
    const guestSection = document.getElementById('admin-mp-guest-section');
    const homePlayers = document.getElementById('admin-mp-home-players');
    const guestPlayers = document.getElementById('admin-mp-guest-players');
    const homeHeading = document.getElementById('admin-mp-home-heading');
    const guestHeading = document.getElementById('admin-mp-guest-heading');
    const homeName = document.getElementById('admin-mp-home-name');
    const guestName = document.getElementById('admin-mp-guest-name');
    const homeScore = document.getElementById('admin-mp-homescore');
    const guestScore = document.getElementById('admin-mp-guestscore');
    const homePenalty = document.getElementById('admin-mp-homepenalty');
    const guestPenalty = document.getElementById('admin-mp-guestpenalty');
    const saveBtn = document.getElementById('admin-mp-save');
    const dirtyHint = document.getElementById('admin-mp-dirty-hint');

    let pointsmode = root.dataset.pointsmode || 'new';
    let currentMatchId = 0;
    let displayLock = 0;
    let matchesCache = [];
    const players = { Home: [], Guest: [] };
    const initials = { Home: [], Guest: [] };
    let resultInitial = null;

    const STAT_FIELDS = [
        'minutes', 'goals', 'assists', 'cards', 'owngoals',
        'penaltieslost', 'penaltiessaved',
        'penaltyshootout_save', 'penaltyshootout_lost', 'penaltyshootout_hit',
        'minute_in', 'minute_out', 'playerteam_id',
    ];

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function xsrfToken() {
        const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    async function fetchJson(url, init) {
        const headers = Object.assign(
            { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            (init && init.headers) || {}
        );
        if (init && init.method && init.method.toUpperCase() !== 'GET') {
            headers['X-CSRF-TOKEN'] = csrf;
            headers['X-XSRF-TOKEN'] = xsrfToken();
            if (!(init.body instanceof FormData)) {
                headers['Content-Type'] = headers['Content-Type'] || 'application/json';
            }
        }
        const response = await fetch(url, Object.assign({}, init, { headers, credentials: 'same-origin' }));
        const data = await response.json().catch(() => ({}));
        if (!response.ok || data.ok === false) {
            const msg = (data.errors && data.errors[0]) || data.message || 'Request failed';
            throw new Error(msg);
        }
        return data;
    }

    function fillScoreSelect(selectEl, selected, max, min) {
        let html = '';
        for (let i = min; i <= max; i++) {
            const sel = Number(selected) === i ? ' selected' : '';
            html += `<option value="${i}"${sel}>${i}</option>`;
        }
        selectEl.innerHTML = html;
        if (![...selectEl.options].some((o) => o.selected) && selectEl.options.length) {
            const fallback = [...selectEl.options].find((o) => Number(o.value) === min) || selectEl.options[0];
            fallback.selected = true;
        }
    }

    function snapshotFromPlayer(p) {
        return {
            minutes: String(p.playerstats_minutes ?? ''),
            goals: String(p.playerstats_goals ?? ''),
            assists: String(p.playerstats_assists ?? ''),
            cards: String(p.playerstats_cards || 'n'),
            owngoals: String(p.playerstats_owngoals ?? ''),
            penaltieslost: String(p.playerstats_penaltieslost ?? ''),
            penaltiessaved: String(p.playerstats_penaltiessaved ?? ''),
            penaltyshootout_save: String(p.playerstats_penaltyshootout_save ?? ''),
            penaltyshootout_lost: String(p.playerstats_penaltyshootout_lost ?? ''),
            penaltyshootout_hit: String(p.playerstats_penaltyshootout_hit ?? ''),
            minute_in: String(p.playerstats_minute_in ?? ''),
            minute_out: String(p.playerstats_minute_out ?? ''),
            playerteam_id: String(p.playerteam_id ?? ''),
        };
    }

    function readResultValues() {
        return {
            homescore: String(homeScore.value),
            guestscore: String(guestScore.value),
            homescore_penalty: String(homePenalty.value),
            guestscore_penalty: String(guestPenalty.value),
        };
    }

    function valuesEqual(a, b) {
        return STAT_FIELDS.every((key) => String(a[key] ?? '') === String(b[key] ?? ''));
    }

    function resultEqual(a, b) {
        if (!a || !b) return false;
        return ['homescore', 'guestscore', 'homescore_penalty', 'guestscore_penalty']
            .every((key) => String(a[key] ?? '') === String(b[key] ?? ''));
    }

    function clearMatchUi() {
        currentMatchId = 0;
        players.Home = [];
        players.Guest = [];
        initials.Home = [];
        initials.Guest = [];
        resultInitial = null;
        resultEl.hidden = true;
        legendEl.hidden = true;
        homeSection.hidden = true;
        guestSection.hidden = true;
        homePlayers.innerHTML = '';
        guestPlayers.innerHTML = '';
        refreshSavebar();
    }

    function cardRadios(side, index, cards) {
        return ['y', 'yr', 'r', 'n'].map((v) => {
            const checked = cards === v ? ' checked' : '';
            const label = v === 'n' ? 'N' : v.toUpperCase();
            const img = v === 'n'
                ? ''
                : `<img src="${symbols}stats_card_${v}.gif" width="14" height="16" alt="">`;
            return `<label class="admin-mp-card-opt">${img}<input type="radio" name="cards-${side}-${index}" value="${v}" class="admin-mp-card" data-side="${side}" data-index="${index}"${checked}> ${label}</label>`;
        }).join('');
    }

    function playerRow(side, index, p) {
        const id = `${side}${index}`;
        const name = `<strong>${escapeHtml(p.player_lname)}</strong>, <em>${escapeHtml(p.player_fname)}</em>`;
        const pos = escapeHtml(String(p.playerteam_player_position || '').toUpperCase());
        return `<div class="admin-mp-player" data-side="${side}" data-index="${index}">
            <div class="admin-mp-player-head">
                <div class="admin-mp-player-title">
                    <span>${name} <span class="muted">(${pos})</span></span>
                    <span class="admin-mp-status" id="admin-mp-status-${id}"></span>
                </div>
                <button type="button" class="admin-icon-btn admin-mp-undo-btn" title="Rückgängig" hidden>
                    <img src="${escapeHtml(undoIcon)}" alt="Rückgängig" width="16" height="16">
                </button>
            </div>
            <div class="admin-mp-player-fields" id="admin-mp-fields-${id}">
                <label><img src="${symbols}stats_time.png" width="14" height="14" alt=""> <input type="text" class="admin-mp-input" data-field="minutes" maxlength="3" value="${escapeHtml(p.playerstats_minutes)}"></label>
                <label><img src="${symbols}stats_goal.gif" width="14" height="14" alt=""> <input type="text" class="admin-mp-input admin-mp-input-wide" data-field="goals" value="${escapeHtml(p.playerstats_goals)}"></label>
                <label><img src="${symbols}stats_assist.gif" width="14" height="14" alt=""> <input type="text" class="admin-mp-input" data-field="assists" maxlength="2" value="${escapeHtml(p.playerstats_assists)}"></label>
                <span class="admin-mp-cards">${cardRadios(side, index, p.playerstats_cards || 'n')}<input type="hidden" data-field="cards" value="${escapeHtml(p.playerstats_cards || 'n')}"></span>
                <label><img src="${symbols}stats_owngoal.gif" width="14" height="14" alt=""> <input type="text" class="admin-mp-input admin-mp-input-wide" data-field="owngoals" value="${escapeHtml(p.playerstats_owngoals)}"></label>
                <label><img src="${symbols}stats_penaltylost.png" width="14" height="14" alt=""> <input type="text" class="admin-mp-input" data-field="penaltieslost" value="${escapeHtml(p.playerstats_penaltieslost)}"></label>
                <label><img src="${symbols}stats_penaltysaved.png" width="14" height="14" alt=""> <input type="text" class="admin-mp-input" data-field="penaltiessaved" value="${escapeHtml(p.playerstats_penaltiessaved)}"></label>
                <label title="PS save">PS↓ <input type="text" class="admin-mp-input" data-field="penaltyshootout_save" value="${escapeHtml(p.playerstats_penaltyshootout_save)}"></label>
                <label title="PS lost">PS× <input type="text" class="admin-mp-input" data-field="penaltyshootout_lost" value="${escapeHtml(p.playerstats_penaltyshootout_lost)}"></label>
                <label title="PS hit">PS✓ <input type="text" class="admin-mp-input" data-field="penaltyshootout_hit" value="${escapeHtml(p.playerstats_penaltyshootout_hit)}"></label>
                <label><img src="${symbols}stats_hourglass_add.png" width="14" height="14" alt=""> <input type="text" class="admin-mp-input" data-field="minute_in" value="${escapeHtml(p.playerstats_minute_in)}"></label>
                <label><img src="${symbols}stats_hourglass_delete.png" width="14" height="14" alt=""> <input type="text" class="admin-mp-input" data-field="minute_out" value="${escapeHtml(p.playerstats_minute_out)}"></label>
                <input type="hidden" data-field="playerteam_id" value="${escapeHtml(p.playerteam_id)}">
            </div>
        </div>`;
    }

    function fieldsEl(side, index) {
        return document.getElementById(`admin-mp-fields-${side}${index}`);
    }

    function rowEl(side, index) {
        return document.querySelector(`.admin-mp-player[data-side="${side}"][data-index="${index}"]`);
    }

    function readField(side, index, name) {
        const box = fieldsEl(side, index);
        if (!box) return '';
        const el = box.querySelector(`[data-field="${name}"]`);
        return el ? el.value : '';
    }

    function writeField(side, index, name, value) {
        const box = fieldsEl(side, index);
        if (!box) return;
        const el = box.querySelector(`[data-field="${name}"]`);
        if (el) el.value = value;
    }

    function playerPayload(side, index) {
        const payload = {};
        STAT_FIELDS.forEach((name) => {
            payload[name] = readField(side, index, name);
        });
        return payload;
    }

    function isValidPayload(payload) {
        const time = Number(payload.minutes);
        const assists = Number(payload.assists);
        const penalties = Number(payload.penaltieslost);
        const penaltiessaved = Number(payload.penaltiessaved);
        const p1 = Number(payload.penaltyshootout_lost);
        const p2 = Number(payload.penaltyshootout_hit);
        const p3 = Number(payload.penaltyshootout_save);
        return !isNaN(time) && time <= 200
            && assists >= 0 && penalties >= 0 && penaltiessaved >= 0
            && p1 >= 0 && p2 >= 0 && p3 >= 0;
    }

    function isResultDirty() {
        if (!resultInitial || !currentMatchId) return false;
        return !resultEqual(readResultValues(), resultInitial);
    }

    function isPlayerDirty(side, index) {
        const initial = initials[side][index];
        if (!initial) return false;
        return !valuesEqual(playerPayload(side, index), initial);
    }

    function dirtyPlayerCount() {
        let count = 0;
        ['Home', 'Guest'].forEach((side) => {
            players[side].forEach((_, index) => {
                if (isPlayerDirty(side, index)) count += 1;
            });
        });
        return count;
    }

    function refreshSavebar() {
        const editCount = dirtyPlayerCount();
        const resultDirty = isResultDirty();
        const queued = editCount + (resultDirty ? 1 : 0);

        if (saveBtn) {
            saveBtn.disabled = queued === 0;
            if (queued === 0) {
                saveBtn.textContent = 'Änderungen speichern (0)';
            } else if (queued === 1) {
                saveBtn.textContent = '1 Änderung speichern';
            } else {
                saveBtn.textContent = queued + ' Änderungen speichern';
            }
        }
        if (dirtyHint) {
            if (queued === 0) {
                dirtyHint.textContent = 'Noch keine Änderungen';
            } else {
                const parts = [];
                if (resultDirty) parts.push('Ergebnis');
                if (editCount === 1) parts.push('1 Spieler');
                else if (editCount > 1) parts.push(editCount + ' Spieler');
                dirtyHint.textContent = parts.join(', ');
            }
        }
    }

    function syncPlayerRow(side, index) {
        const row = rowEl(side, index);
        if (!row) return false;
        const payload = playerPayload(side, index);
        const dirty = isPlayerDirty(side, index);
        const valid = isValidPayload(payload);

        row.classList.toggle('is-dirty', dirty && valid);
        row.classList.toggle('is-invalid', dirty && !valid);

        const undoBtn = row.querySelector('.admin-mp-undo-btn');
        if (undoBtn) undoBtn.hidden = !dirty;

        refreshSavebar();
        return valid;
    }

    function applyCardsUi(side, index, cards) {
        const box = fieldsEl(side, index);
        if (!box) return;
        writeField(side, index, 'cards', cards);
        box.querySelectorAll('.admin-mp-card').forEach((radio) => {
            radio.checked = radio.value === cards;
        });
    }

    function undoPlayerRow(side, index) {
        const initial = initials[side][index];
        if (!initial) return;
        STAT_FIELDS.forEach((name) => {
            if (name === 'cards') return;
            writeField(side, index, name, initial[name]);
        });
        applyCardsUi(side, index, initial.cards || 'n');
        const status = document.getElementById(`admin-mp-status-${side}${index}`);
        if (status) status.textContent = '';
        syncPlayerRow(side, index);
    }

    function bindPlayerEvents(side, index) {
        const box = fieldsEl(side, index);
        if (!box) return;
        const row = box.closest('.admin-mp-player');
        box.querySelectorAll('.admin-mp-input').forEach((input) => {
            input.addEventListener('change', () => {
                const field = input.dataset.field;
                if (field === 'minute_in' || field === 'minute_out') {
                    const start = Number(readField(side, index, 'minute_in'));
                    const end = Number(readField(side, index, 'minute_out'));
                    if (start < end && start >= 0) {
                        writeField(side, index, 'minutes', String(end - start + 1));
                    }
                }
                syncPlayerRow(side, index);
            });
            input.addEventListener('input', () => syncPlayerRow(side, index));
        });
        box.querySelectorAll('.admin-mp-card').forEach((radio) => {
            radio.addEventListener('change', () => {
                writeField(side, index, 'cards', radio.value);
                syncPlayerRow(side, index);
            });
        });
        const undoBtn = row && row.querySelector('.admin-mp-undo-btn');
        if (undoBtn) {
            undoBtn.addEventListener('click', () => undoPlayerRow(side, index));
        }
    }

    async function loadPlayers(side, teamId, matchId, lock) {
        const listEl = side === 'Home' ? homePlayers : guestPlayers;
        listEl.innerHTML = '';
        players[side] = [];
        initials[side] = [];
        const url = playersTpl.replace('__MATCH__', String(matchId)).replace('__TEAM__', String(teamId));
        const data = await fetchJson(url);
        if (lock !== displayLock) return;
        if (data.pointsmode) pointsmode = data.pointsmode;
        const rows = data.players || [];
        players[side] = rows;
        initials[side] = rows.map((p) => snapshotFromPlayer(p));
        listEl.innerHTML = rows.map((p, i) => playerRow(side, i, p)).join('');
        rows.forEach((_, i) => bindPlayerEvents(side, i));
    }

    async function loadMatch(match) {
        currentMatchId = Number(match.match_id);
        displayLock += 1;
        const lock = displayLock;
        loadingEl.hidden = false;
        refreshSavebar();

        const homeLabel = `${match.match_hometeam_name} ${match.match_hometeam_nationality || ''}`.trim();
        const guestLabel = `${match.match_guestteam_name} ${match.match_guestteam_nationality || ''}`.trim();
        homeName.textContent = homeLabel;
        guestName.textContent = guestLabel;
        const homeNamePs = document.getElementById('admin-mp-home-name-ps');
        const guestNamePs = document.getElementById('admin-mp-guest-name-ps');
        if (homeNamePs) homeNamePs.textContent = homeLabel;
        if (guestNamePs) guestNamePs.textContent = guestLabel;
        homeHeading.textContent = homeLabel;
        guestHeading.textContent = guestLabel;
        fillScoreSelect(homeScore, match.match_homescore, 49, 0);
        fillScoreSelect(guestScore, match.match_guestscore, 49, 0);
        fillScoreSelect(homePenalty, match.match_homescore_penalty, 99, -1);
        fillScoreSelect(guestPenalty, match.match_guestscore_penalty, 99, -1);
        resultInitial = readResultValues();

        resultEl.hidden = false;
        legendEl.hidden = false;
        homeSection.hidden = false;
        guestSection.hidden = false;

        try {
            await Promise.all([
                loadPlayers('Home', match.match_hometeam_id, match.match_id, lock),
                loadPlayers('Guest', match.match_guestteam_id, match.match_id, lock),
            ]);
            if (lock === displayLock) refreshSavebar();
        } catch (err) {
            alert(err.message || 'Spieler konnten nicht geladen werden.');
        } finally {
            if (lock === displayLock) loadingEl.hidden = true;
        }
    }

    function fillMatchSelect(matches) {
        matchesCache = matches || [];
        matchSelect.innerHTML = '<option value="">— Spiel wählen —</option>';
        matchesCache.forEach((m) => {
            const hs = Number(m.match_homescore) < 0 ? '-' : m.match_homescore;
            const gs = Number(m.match_guestscore) < 0 ? '-' : m.match_guestscore;
            const opt = document.createElement('option');
            opt.value = String(m.match_id);
            opt.textContent = `${m.match_hometeam_name} ${hs}:${gs} ${m.match_guestteam_name}`;
            matchSelect.appendChild(opt);
        });
        matchSelect.disabled = matchesCache.length === 0;
    }

    async function loadRoundData(roundId) {
        clearMatchUi();
        fillMatchSelect([]);
        if (!roundId) return;
        try {
            const data = await fetchJson(matchesTpl.replace('__ID__', String(roundId)));
            fillMatchSelect(data.matches || []);
        } catch (err) {
            alert(err.message || 'Spiele konnten nicht geladen werden.');
        }
    }

    async function loadRounds(selectFirstStarted) {
        roundSelect.innerHTML = '<option value="">— Runde wählen —</option>';
        fillMatchSelect([]);
        clearMatchUi();
        if (root.dataset.hasGame !== '1') {
            roundSelect.disabled = true;
            return;
        }
        roundSelect.disabled = false;
        try {
            const data = await fetchJson(roundsUrl);
            const rounds = data.rounds || [];
            let firstStarted = '';
            rounds.forEach((r) => {
                const opt = document.createElement('option');
                opt.value = String(r.matchround_id);
                opt.textContent = `${r.matchround_title} (${r.matchround_startdate} – ${r.matchround_enddate})`;
                if (r.started) {
                    opt.classList.add('admin-mp-round-started');
                    if (!firstStarted) firstStarted = String(r.matchround_id);
                }
                roundSelect.appendChild(opt);
            });
            if (selectFirstStarted && firstStarted) {
                roundSelect.value = firstStarted;
                await loadRoundData(firstStarted);
            }
        } catch (err) {
            alert(err.message || 'Runden konnten nicht geladen werden.');
        }
    }

    async function saveAll() {
        if (!currentMatchId) {
            alert('Bitte zuerst ein Spiel wählen.');
            return;
        }

        const dirtyPlayers = [];
        ['Home', 'Guest'].forEach((side) => {
            players[side].forEach((_, index) => {
                if (!isPlayerDirty(side, index)) return;
                dirtyPlayers.push({ side, index, payload: playerPayload(side, index) });
            });
        });

        const invalid = dirtyPlayers.find((item) => !isValidPayload(item.payload));
        if (invalid) {
            alert(`${invalid.side} #${invalid.index + 1}: Ungültige Werte.`);
            syncPlayerRow(invalid.side, invalid.index);
            return;
        }

        const resultDirty = isResultDirty();
        if (!resultDirty && dirtyPlayers.length === 0) return;

        saveBtn.disabled = true;
        try {
            if (resultDirty) {
                await fetchJson(resultTpl.replace('__ID__', String(currentMatchId)), {
                    method: 'POST',
                    body: JSON.stringify(readResultValues()),
                });
                resultInitial = readResultValues();
            }

            const jobs = dirtyPlayers.map(({ side, index, payload }) => {
                const status = document.getElementById(`admin-mp-status-${side}${index}`);
                if (status) status.textContent = '…';
                const url = savePlayerTpl
                    .replace('__MATCH__', String(currentMatchId))
                    .replace('__PT__', String(payload.playerteam_id));
                return fetchJson(url, { method: 'POST', body: JSON.stringify(payload) })
                    .then(() => {
                        initials[side][index] = Object.assign({}, payload);
                        if (status) {
                            status.innerHTML = `<img src="${imagesBase}symbols/status_pos.png" width="14" height="14" alt="ok">`;
                        }
                        syncPlayerRow(side, index);
                    })
                    .catch((err) => {
                        if (status) {
                            status.innerHTML = `<img src="${imagesBase}symbols/status_neg.png" width="14" height="14" alt="err">`;
                        }
                        alert(`${side} #${index + 1}: ${err.message}`);
                    });
            });
            await Promise.all(jobs);
        } catch (err) {
            alert(err.message || 'Speichern fehlgeschlagen.');
        } finally {
            refreshSavebar();
        }
    }

    leagueSelect.addEventListener('change', () => {
        const id = leagueSelect.value;
        if (!id) return;
        const url = new URL(window.location.href);
        url.searchParams.set('game_id', id);
        window.location.href = url.toString();
    });

    roundSelect.addEventListener('change', () => loadRoundData(roundSelect.value));

    matchSelect.addEventListener('change', () => {
        const id = Number(matchSelect.value || 0);
        const match = matchesCache.find((m) => Number(m.match_id) === id);
        if (match) loadMatch(match);
        else clearMatchUi();
    });

    [homeScore, guestScore, homePenalty, guestPenalty].forEach((el) => {
        el.addEventListener('change', refreshSavebar);
    });

    saveBtn.addEventListener('click', saveAll);

    refreshSavebar();

    if (root.dataset.hasGame === '1') {
        loadRounds(true);
    }
})();
