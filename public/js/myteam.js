(function () {
    const config = window.FFB_MYTEAM || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';
    const viewerId = Number(config.userId || 0);
    const isAdmin = !!config.isAdmin;

    const roundSelect = document.getElementById('matchround_selection');
    const roundTitleEl = document.getElementById('round-title');
    const roundDatesEl = document.getElementById('round-dates');
    const userSelect = document.getElementById('user_selection');
    const selectedUserEl = document.getElementById('selected-user');
    const teamScoreEl = document.getElementById('team-score');
    const teamPriceEl = document.getElementById('team-price');
    const teamCreditsEl = document.getElementById('team-credits');
    const teamSideStatsEl = document.getElementById('team-side-stats');
    const matchlistEl = document.getElementById('matchlist');
    const pitchMessageEl = document.getElementById('pitch-message');
    const sideTabsEl = document.getElementById('side-tabs');
    const lines = {
        g: document.getElementById('line-g'),
        d: document.getElementById('line-d'),
        m: document.getElementById('line-m'),
        s: document.getElementById('line-s'),
    };

    let matchrounds = [];
    let roundIndex = 0;
    let users = [];
    let userIndex = -1;
    let sideTab = 'stats'; // matches | stats (legacy default)
    const teamCache = {};
    const userStatsCache = {};
    const roundStatsCache = {};

    function symbolUrl(name) {
        return legacyBase + 'images/ffb/symbols/' + name;
    }
    function apiUrl(path) {
        return apiBase + '/' + path.replace(/^\//, '');
    }

    async function fetchJson(path) {
        const response = await fetch(apiUrl(path), {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });
        const json = await response.json();
        if (!response.ok || json.status !== 200) {
            const err = new Error((json && json.error) || 'Request failed');
            err.status = response.status;
            err.payload = json;
            throw err;
        }
        return json.data;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function flagHtml(code, title) {
        if (window.FfbFlags && typeof window.FfbFlags.html === 'function') {
            return window.FfbFlags.html(code, title ? { title: title } : undefined);
        }
        const flag = (code && code !== '0' ? String(code) : 'na').toLowerCase();
        const src = legacyBase + 'images/ffb/flags/' + flag + '.gif';
        const titleAttr = title ? ' title="' + escapeHtml(title) + '"' : '';
        return '<img class="ffb-flag ffb-flag-img" src="' + src + '" alt="" width="16" height="11" loading="lazy"' + titleAttr + '>';
    }

    const selectedLeagueId = Number(config.selectedLeagueId || 0) || 0;
    const selectedLeagueAssetKey = String(config.selectedLeagueAssetKey || '');

    function shirtImgTag(teamKey, nationality, attrs) {
        if (window.FfbShirts && typeof window.FfbShirts.imgTag === 'function') {
            return window.FfbShirts.imgTag(
                legacyBase,
                teamKey,
                nationality,
                attrs || '',
                selectedLeagueAssetKey
            );
        }
        const blank = legacyBase + 'images/ffb/shirts/shirt_MISSING.svg';
        return '<img class="shirt" src="' + blank + '" ' + (attrs || '') + '>';
    }

    function formatPlayerPrice(value) {
        const num = Number(value);
        if (!Number.isFinite(num)) {
            return '—';
        }
        return num.toFixed(1);
    }

    function roundCredits(value) {
        return Math.round(Number(value) * 10) / 10;
    }

    function playerPricesMatchTeamTotal(players, substitutes, teamPrice) {
        const total = roundCredits(teamPrice);
        if (!Number.isFinite(total) || total <= 0) {
            return false;
        }
        const all = (players || []).concat(substitutes || []);
        const sum = all.reduce(function (acc, player) {
            const price = Number(player.playerteam_player_price);
            return acc + (Number.isFinite(price) ? price : 0);
        }, 0);
        return roundCredits(sum) === total;
    }

    function currentRound() {
        return matchrounds[roundIndex] || null;
    }

    function currentUser() {
        return userIndex >= 0 ? users[userIndex] : null;
    }

    function cacheKey(matchroundId, userId) {
        return String(matchroundId) + ':' + String(userId);
    }

    function setPitchMessage(text) {
        if (!text) {
            pitchMessageEl.hidden = true;
            pitchMessageEl.textContent = '';
            return;
        }
        pitchMessageEl.hidden = false;
        pitchMessageEl.textContent = text;
    }

    function clearPitch() {
        Object.keys(lines).forEach(function (pos) {
            lines[pos].innerHTML = '';
        });
        teamScoreEl.textContent = '–';
        teamPriceEl.textContent = '–';
        if (teamCreditsEl) {
            teamCreditsEl.hidden = true;
        }
        if (teamSideStatsEl) {
            teamSideStatsEl.hidden = true;
        }
        selectedUserEl.textContent = '';
        if (window.FfbPitchBench) {
            window.FfbPitchBench.sync(null, legacyBase, [], { mode: 'view' });
        }
        updatePitchInfoLayout();
    }

    function updatePitchInfoLayout() {
        if (window.FfbPitchInfoBar) {
            window.FfbPitchInfoBar.update();
        }
    }

    function renderRoundFace() {
        const round = currentRound();
        if (!round) {
            roundTitleEl.textContent = 'Keine Spielrunden';
            roundDatesEl.textContent = '';
            roundDatesEl.hidden = true;
            updatePitchInfoLayout();
            return;
        }
        roundTitleEl.textContent = round.matchround_title || '';
        if (round.matchround_startdate) {
            roundDatesEl.textContent =
                round.matchround_startdate === round.matchround_enddate
                    ? String(round.matchround_startdate)
                    : String(round.matchround_startdate) + ' - ' + String(round.matchround_enddate);
            roundDatesEl.hidden = false;
        } else {
            roundDatesEl.textContent = '';
            roundDatesEl.hidden = true;
        }
        updatePitchInfoLayout();
    }

    function renderRoundSelect() {
        roundSelect.innerHTML = '';
        if (!matchrounds.length) {
            roundSelect.innerHTML = '<option>Keine Spielrunden</option>';
            roundSelect.disabled = true;
            renderRoundFace();
            return;
        }
        matchrounds.forEach(function (round, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = round.matchround_title || '';
            opt.className = 'ffb-select-' + (index % 2);
            if (index === roundIndex) {
                opt.selected = true;
            }
            roundSelect.appendChild(opt);
        });
        roundSelect.disabled = false;
        renderRoundFace();
    }

    function renderUserFace() {
        const user = currentUser();
        if (!user) {
            selectedUserEl.textContent = users.length ? '' : 'Keine Mitspieler';
            updatePitchInfoLayout();
            return;
        }
        selectedUserEl.textContent = user.user_nickname || '';
        updatePitchInfoLayout();
    }

    function renderUserSelect() {
        userSelect.innerHTML = '';
        if (!users.length) {
            userSelect.innerHTML = '<option>Keine Mitspieler</option>';
            userSelect.disabled = true;
            userIndex = -1;
            renderUserFace();
            return;
        }

        users.forEach(function (user, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = user.user_nickname;
            if (Number(user.user_id) === viewerId) {
                opt.className = 'ffb-select-marked';
            } else {
                opt.className = 'ffb-select-' + (index % 2);
            }
            if (index === userIndex) {
                opt.selected = true;
            }
            userSelect.appendChild(opt);
        });
        userSelect.disabled = false;
        renderUserFace();
    }

    function renderMatches() {
        const round = currentRound();
        const matches = (round && round.matches) || [];
        if (window.FfbMatchList) {
            window.FfbMatchList.render(matchlistEl, matches);
            return;
        }
        matchlistEl.innerHTML = '<p class="muted">Keine Spiele in dieser Runde.</p>';
    }

    function statsRow(icon, label, valueHtml) {
        return (
            '<div class="stats-row">' +
            '<span class="stats-icon"><img src="' +
            symbolUrl(icon) +
            '" alt="" width="16" height="16"></span>' +
            '<span class="stats-label">' +
            escapeHtml(label) +
            '</span>' +
            '<span class="stats-value"><b>' +
            valueHtml +
            '</b></span>' +
            '</div>'
        );
    }

    function pluralize(count, singular, plural) {
        const n = Number(count);
        const word = Math.abs(n) === 1 ? singular : plural;
        return n + ' ' + word;
    }

    function renderRoundStatsBlock(stats) {
        if (!stats) {
            return '';
        }
        const owngoals = Number(stats.owngoals) || 0;
        const penaltiesSaved = Number(stats.penalties_saved) || 0;
        const penaltiesLost = Number(stats.penalties_lost) || 0;
        let html = '<div class="stats-block">';
        html += '<div class="stats-heading">-- Spielrunden Statistik --</div>';
        html += statsRow('symbol_user.svg', 'Teilnehmer:', pluralize(stats.num_users, 'Mitspieler', 'Mitspieler'));
        html += statsRow('stats_point.svg', 'Anzahl Spiele:', pluralize(stats.num_matches, 'Spiel', 'Spiele'));
        html += statsRow('stats_goal.svg', 'gefallene Tore:', pluralize(stats.goals, 'Tor', 'Tore'));
        html += statsRow('stats_assist.svg', 'Assists:', pluralize(stats.assists, 'Assist', 'Assists'));
        if (owngoals > 0) {
            html += statsRow(
                'stats_owngoal.svg',
                'gefallene Eigentore:',
                pluralize(owngoals, 'Eigentor', 'Eigentore')
            );
        }
        html += statsRow(
            'stats_card_yr.svg',
            'Karten (G/GR/R):',
            Number(stats.cards_y) + '/' + Number(stats.cards_yr) + '/' + Number(stats.cards_r)
        );
        if (penaltiesSaved > 0) {
            html += statsRow(
                'stats_penaltysaved.svg',
                'gehaltene Elfer:',
                pluralize(penaltiesSaved, 'gehaltener Elfer', 'gehaltene Elfer')
            );
        }
        if (penaltiesLost > 0) {
            html += statsRow(
                'stats_penaltylost.svg',
                'verschossene Elfer:',
                pluralize(penaltiesLost, 'verschossener Elfer', 'verschossene Elfer')
            );
        }
        html += statsRow(
            'stats_point.svg',
            'Punkte pro Spieler:',
            pluralize(stats.score_per_player, 'Punkt', 'Punkte')
        );
        html += '</div>';
        return html;
    }

    function renderUserStatsBlock(stats) {
        if (!stats) {
            return '<div class="stats-block"><p class="muted">Keine Teamstatistik verfügbar.</p></div>';
        }
        const owngoals = Number(stats.owngoals) || 0;
        const penaltiesSaved = Number(stats.penalties_saved) || 0;
        const penaltiesLost = Number(stats.penalties_lost) || 0;
        let html = '<div class="stats-block">';
        html += '<div class="stats-heading">-- Team Statistik --</div>';
        html += statsRow('stats_lineup.svg', 'Spielsystem:', escapeHtml(stats.system));
        html += statsRow('stats_goal.svg', 'erzielte Tore:', pluralize(stats.goals, 'Tor', 'Tore'));
        html += statsRow('stats_assist.svg', 'Assists:', pluralize(stats.assists, 'Assist', 'Assists'));
        if (owngoals > 0) {
            html += statsRow(
                'stats_owngoal.svg',
                'erzielte Eigentore:',
                pluralize(owngoals, 'Eigentor', 'Eigentore')
            );
        }
        html += statsRow(
            'stats_card_yr.svg',
            'Karten (G/GR/R):',
            Number(stats.cards_y) + '/' + Number(stats.cards_yr) + '/' + Number(stats.cards_r)
        );
        if (penaltiesSaved > 0) {
            html += statsRow(
                'stats_penaltysaved.svg',
                'gehaltene Elfer:',
                pluralize(penaltiesSaved, 'gehaltener Elfer', 'gehaltene Elfer')
            );
        }
        if (penaltiesLost > 0) {
            html += statsRow(
                'stats_penaltylost.svg',
                'verschossene Elfer:',
                pluralize(penaltiesLost, 'verschossener Elfer', 'verschossene Elfer')
            );
        }
        html += statsRow(
            'stats_point.svg',
            'Punkte Abwehr:',
            pluralize(Number(stats.score_g) + Number(stats.score_d), 'Punkt', 'Punkte')
        );
        html += statsRow(
            'stats_point.svg',
            'Punkte Mittelfeld:',
            pluralize(stats.score_m, 'Punkt', 'Punkte')
        );
        html += statsRow(
            'stats_point.svg',
            'Punkte Angriff:',
            pluralize(stats.score_s, 'Punkt', 'Punkte')
        );
        html += statsRow(
            'stats_point.svg',
            'Punkte pro Spieler:',
            pluralize(stats.score_per_player, 'Punkt', 'Punkte')
        );
        html += statsRow(
            'symbol_credits.svg',
            'Credits pro Punkt:',
            pluralize(stats.credits_per_point, 'Credit', 'Credits')
        );
        html += '</div>';
        return html;
    }

    function updateSideTabs() {
        const round = currentRound();
        const statsAllowed = round && Number(round.matchround_running) !== 1;
        if (!sideTabsEl) {
            return;
        }
        if (!round) {
            sideTabsEl.hidden = true;
            return;
        }
        sideTabsEl.hidden = false;
        sideTabsEl.querySelectorAll('[data-side-tab]').forEach(function (btn) {
            const tab = btn.getAttribute('data-side-tab');
            const isStats = tab === 'stats';
            btn.disabled = isStats && !statsAllowed;
            btn.classList.toggle('is-active', tab === sideTab);
            if (isStats && !statsAllowed && sideTab === 'stats') {
                sideTab = 'matches';
            }
        });
        sideTabsEl.querySelectorAll('[data-side-tab]').forEach(function (btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-side-tab') === sideTab);
        });
    }

    async function renderSidePanel() {
        const round = currentRound();
        updateSideTabs();
        if (!round) {
            matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
            return;
        }

        if (sideTab === 'stats' && Number(round.matchround_running) === 1) {
            sideTab = 'matches';
            updateSideTabs();
        }

        if (sideTab === 'matches') {
            renderMatches();
            return;
        }

        matchlistEl.innerHTML = '<p class="muted">Lade Statistiken…</p>';

        const user = currentUser();
        const roundId = round.matchround_id;
        try {
            let roundStats = roundStatsCache[roundId];
            if (!roundStats) {
                const data = await fetchJson(
                    'myteam/stats/round?matchround_id=' + encodeURIComponent(roundId)
                );
                roundStats = data.stats || null;
                roundStatsCache[roundId] = roundStats;
            }

            let userStats = null;
            if (user) {
                const key = cacheKey(roundId, user.user_id);
                if (userStatsCache[key] !== undefined) {
                    userStats = userStatsCache[key];
                } else {
                    const data = await fetchJson(
                        'myteam/stats/user?matchround_id=' +
                            encodeURIComponent(roundId) +
                            '&userteam_user_id=' +
                            encodeURIComponent(user.user_id)
                    );
                    userStats = data.stats || null;
                    userStatsCache[key] = userStats;
                }
            }

            // Round/user may have changed while loading.
            if (currentRound() !== round || currentUser() !== user || sideTab !== 'stats') {
                return;
            }

            matchlistEl.innerHTML = renderRoundStatsBlock(roundStats) + renderUserStatsBlock(userStats);
        } catch (err) {
            matchlistEl.innerHTML =
                '<p class="muted">' + escapeHtml((err && err.message) || 'Statistiken konnten nicht geladen werden.') + '</p>';
        }
    }

    function playerName(player) {
        return String(player.player_fname || '')
            .trim()
            .concat(' ', String(player.player_lname || '').trim())
            .trim();
    }

    function changeIconHtml(player) {
        const change = player && player.change;
        if (!change || (change.type !== 'in' && change.type !== 'out')) {
            return '';
        }
        const related = String(change.related_name || '').trim();
        const title =
            change.type === 'in'
                ? 'Eingewechselt für ' + related
                : 'Ersetzt durch ' + related;
        const icon = change.type === 'in' ? 'stats_change_in.svg' : 'stats_change_out.svg';

        return (
            '<img src="' +
            symbolUrl(icon) +
            '" width="16" height="11" alt="" title="' +
            escapeHtml(title) +
            '">'
        );
    }

    /**
     * After saved substitutions: put came-on subs on the field and replaced
     * starters onto the corresponding bench slots.
     */
    function applySubstitutionDisplay(players, substitutes) {
        const byId = {};
        (players || []).forEach(function (player) {
            byId[Number(player.playerteam_id)] = player;
        });
        (substitutes || []).forEach(function (player) {
            byId[Number(player.playerteam_id)] = player;
        });

        const replaceByOutId = {};
        (substitutes || []).forEach(function (sub) {
            const outId = Number(sub.replaces_playerteam_id || 0);
            if (outId > 0) {
                replaceByOutId[outId] = sub;
            }
        });

        const field = (players || []).map(function (starter) {
            const sub = replaceByOutId[Number(starter.playerteam_id)];
            if (!sub) {
                return starter;
            }
            const copy = Object.assign({}, sub);
            copy.change = { type: 'in', related_name: playerName(starter) };
            return copy;
        });

        const bench = (substitutes || []).map(function (sub) {
            const outId = Number(sub.replaces_playerteam_id || 0);
            const out = outId > 0 ? byId[outId] : null;
            if (!out) {
                return sub;
            }
            const copy = Object.assign({}, out);
            copy.change = { type: 'out', related_name: playerName(sub) };
            return copy;
        });

        return { field: field, bench: bench };
    }

    function playerCard(player, showPrice) {
        const fname = escapeHtml(player.player_fname || '');
        const lname = escapeHtml(player.player_lname || '');
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamKey = player.playerteam_team_asset_key || '';
        const price = formatPlayerPrice(player.playerteam_player_price);
        const roundId = currentRound() ? Number(currentRound().matchround_id) || 0 : 0;
        const roundAttr = roundId > 0 ? '" data-matchround-id="' + roundId : '';
        return (
            '<div class="pitch-player">' +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            roundAttr +
            '">' +
            shirtImgTag(teamKey, nat, 'alt="" width="64" height="64"') +
            '</a>' +
            '<span class="name">' +
            fname +
            '<br>' +
            lname +
            '</span>' +
            '<a class="score" href="#" data-modal="player-points" data-id="' +
            player.playerteam_id +
            '" data-matchround-id="' +
            roundId +
            '">' +
            Number(player.playerstats_score || 0) +
            ' Punkte</a>' +
            '<div class="meta">' +
            (showPrice
                ? '<span title="Preis: ' + price + ' Credits">' + price + '</span>'
                : '') +
            flagHtml(nat, player.playerteam_team || '') +
            changeIconHtml(player) +
            '</div></div>'
        );
    }

    function renderTeam(data) {
        clearPitch();
        setPitchMessage('');
        const user = currentUser();
        if (user) {
            selectedUserEl.textContent = user.user_nickname;
        } else if (data.user_nickname) {
            selectedUserEl.textContent = data.user_nickname;
        } else {
            renderUserFace();
        }
        const round = currentRound();
        const matchroundId = round ? round.matchround_id : 0;

        if (!data.userteam) {
            setPitchMessage('Keine Aufstellung für diesen Mitspieler in dieser Runde.');
            if (window.FfbPitchBench) {
                window.FfbPitchBench.sync(data.lineup_options || null, legacyBase, [], {
                    mode: 'view',
                    showPrice: false,
                    matchroundId: matchroundId,
                });
            }
            updatePitchInfoLayout();
            return;
        }

        teamScoreEl.textContent = String(data.userteam.userteam_score ?? 0);
        const teamPrice = Number(data.userteam.userteam_price || 0);
        const players = data.players || [];
        const substitutes = data.substitutes || [];
        const display = applySubstitutionDisplay(players, substitutes);
        const showPrices = playerPricesMatchTeamTotal(players, substitutes, teamPrice);
        if (teamSideStatsEl) {
            teamSideStatsEl.hidden = false;
        }
        if (teamCreditsEl) {
            if (teamPrice > 0) {
                teamCreditsEl.hidden = false;
                teamPriceEl.textContent = teamPrice.toFixed(1);
            } else {
                teamCreditsEl.hidden = true;
                teamPriceEl.textContent = '–';
            }
        } else {
            teamPriceEl.textContent = teamPrice.toFixed(1);
        }

        const buckets = { g: '', d: '', m: '', s: '' };
        display.field.forEach(function (player) {
            const pos = String(player.playerteam_player_position || '').toLowerCase();
            if (buckets[pos] !== undefined) {
                buckets[pos] += playerCard(player, showPrices);
            }
        });

        Object.keys(buckets).forEach(function (pos) {
            lines[pos].innerHTML = buckets[pos] || '';
        });

        if (window.FfbPitchBench) {
            window.FfbPitchBench.sync(data.lineup_options || null, legacyBase, display.bench, {
                mode: 'view',
                showPrice: showPrices,
                matchroundId: matchroundId,
            });
        }
        updatePitchInfoLayout();
    }

    async function loadUsers() {
        const round = currentRound();
        if (!round) {
            return;
        }
        userSelect.disabled = true;
        try {
            const data = await fetchJson('myteam/users?matchround_id=' + encodeURIComponent(round.matchround_id));
            users = data.users || [];
            userIndex = users.findIndex(function (u) {
                return Number(u.user_id) === viewerId;
            });
            if (userIndex < 0 && users.length) {
                userIndex = 0;
            }
            renderUserSelect();
            await loadTeam();
            await renderSidePanel();
        } catch (err) {
            users = [];
            userIndex = -1;
            renderUserSelect();
            clearPitch();
            setPitchMessage('Mitspieler konnten nicht geladen werden.');
            await renderSidePanel();
        }
    }

    async function loadTeam() {
        const round = currentRound();
        const user = currentUser();
        if (!round || !user) {
            clearPitch();
            setPitchMessage(users.length ? 'Bitte einen Mitspieler auswählen!' : 'Keine Mitspieler in dieser Spielrunde!');
            return;
        }

        if (Number(round.matchround_running) === 1 && Number(user.user_id) !== viewerId && !isAdmin) {
            clearPitch();
            setPitchMessage('Du kannst fremde Mannschaften erst ansehen wenn die Deadline vorüber ist!');
            return;
        }

        const key = cacheKey(round.matchround_id, user.user_id);
        if (teamCache[key]) {
            renderTeam(teamCache[key]);
            return;
        }

        lines.m.innerHTML = '<p class="muted">Lade Mannschaft…</p>';
        try {
            const data = await fetchJson(
                'myteam/team?matchround_id=' +
                    encodeURIComponent(round.matchround_id) +
                    '&userteam_user_id=' +
                    encodeURIComponent(user.user_id)
            );
            teamCache[key] = data;
            renderTeam(data);
        } catch (err) {
            clearPitch();
            setPitchMessage((err && err.message) || 'Mannschaft konnte nicht geladen werden.');
        }
    }

    async function init() {
        if (window.FfbPitchInfoBar) {
            window.FfbPitchInfoBar.watch();
        }
        try {
            const data = await fetchJson('myteam/matchrounds');
            matchrounds = data.matchrounds || [];
            roundIndex = matchrounds.findIndex(function (r) {
                return Number(r.matchround_actual) === 1;
            });
            if (roundIndex < 0) {
                roundIndex = 0;
            }
            renderRoundSelect();
            if (currentRound() && Number(currentRound().matchround_running) === 1) {
                sideTab = 'matches';
            } else {
                sideTab = 'stats';
            }
            await loadUsers();
            updatePitchInfoLayout();
        } catch (err) {
            roundSelect.innerHTML = '<option>Keine Spielrunden</option>';
            clearPitch();
            setPitchMessage('Noch keine Spielrunden verfügbar.');
            matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
            updatePitchInfoLayout();
        }
    }

    roundSelect.addEventListener('change', function () {
        roundIndex = Number(roundSelect.value) || 0;
        renderRoundFace();
        if (currentRound() && Number(currentRound().matchround_running) === 1) {
            sideTab = 'matches';
        }
        loadUsers();
    });

    userSelect.addEventListener('change', function () {
        userIndex = Number(userSelect.value);
        renderUserFace();
        loadTeam();
        renderSidePanel();
    });

    if (sideTabsEl) {
        sideTabsEl.addEventListener('click', function (event) {
            const btn = event.target.closest('[data-side-tab]');
            if (!btn || btn.disabled) {
                return;
            }
            sideTab = btn.getAttribute('data-side-tab') || 'matches';
            renderSidePanel();
        });
    }

    init();
})();
