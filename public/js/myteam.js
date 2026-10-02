(function () {
    const config = window.FFB_MYTEAM || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';
    const viewerId = Number(config.userId || 0);
    const isAdmin = !!config.isAdmin;

    const roundSelect = document.getElementById('matchround_selection');
    const userSelect = document.getElementById('user_selection');
    const metaEl = document.getElementById('round-meta');
    const selectedUserEl = document.getElementById('selected-user');
    const teamScoreEl = document.getElementById('team-score');
    const teamPriceEl = document.getElementById('team-price');
    const teamCreditsEl = document.getElementById('team-credits');
    const teamSideStatsEl = document.getElementById('team-side-stats');
    const matchlistEl = document.getElementById('matchlist');
    const profileLinkEl = document.getElementById('user-profile-link');
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

    function normalizeShirtNat(code) {
        return String(code || 'aut')
            .toLowerCase()
            .trim()
            .replace(/[ .]/g, '_')
            .replace(/[^a-z0-9_-]+/g, '');
    }

    function shirtUrl(teamId, nationality, gameId) {
        const tid = Number(teamId) || 0;
        const nat = normalizeShirtNat(nationality);
        const gid = Number(gameId ?? selectedLeagueId) || 0;
        if (tid <= 0 || !nat) {
            return legacyBase + 'images/ffb/shirts/shirt_BLANK.png';
        }
        if (gid > 0) {
            return legacyBase + 'images/ffb/shirts/' + tid + '/' + nat + '-' + gid + '.png';
        }
        return legacyBase + 'images/ffb/shirts/' + tid + '/' + nat + '.png';
    }

    function shirtFallbackUrl(teamId, nationality) {
        const tid = Number(teamId) || 0;
        const nat = normalizeShirtNat(nationality);
        if (tid <= 0 || !nat) {
            return legacyBase + 'images/ffb/shirts/shirt_BLANK.png';
        }
        return legacyBase + 'images/ffb/shirts/' + tid + '/' + nat + '.png';
    }

    function shirtImgTag(teamId, nationality, attrs) {
        const gid = Number(selectedLeagueId) || 0;
        const primary = shirtUrl(teamId, nationality, gid);
        const fallback = shirtFallbackUrl(teamId, nationality);
        const blank = legacyBase + 'images/ffb/shirts/shirt_BLANK.png';
        const onerror =
            primary !== fallback
                ? "this.onerror=function(){this.onerror=null;this.src='" +
                  blank +
                  "';};this.src='" +
                  fallback +
                  "';"
                : "this.onerror=null;this.src='" + blank + "';";
        return (
            '<img class="shirt" src="' +
            primary +
            '" ' +
            (attrs || '') +
            ' onerror="' +
            onerror +
            '">'
        );
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

    function playerPricesMatchTeamTotal(players, teamPrice) {
        const total = roundCredits(teamPrice);
        if (!Number.isFinite(total) || total <= 0) {
            return false;
        }
        const sum = (players || []).reduce(function (acc, player) {
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
    }

    function renderRoundMeta() {
        const round = currentRound();
        if (!round) {
            metaEl.textContent = 'Keine Spielrunden';
            return;
        }
        let html = escapeHtml(round.matchround_title || '');
        if (Number(round.matchround_running) === 1) {
            html += ' <em>(Deadline offen)</em>';
        }
        if (round.matchround_startdate) {
            const dates =
                round.matchround_startdate === round.matchround_enddate
                    ? escapeHtml(round.matchround_startdate)
                    : escapeHtml(round.matchround_startdate) +
                      ' - ' +
                      escapeHtml(round.matchround_enddate);
            html += '<span class="pitch-round-dates">' + dates + '</span>';
        }
        metaEl.innerHTML = html;
    }

    function renderRoundSelect() {
        roundSelect.innerHTML = '';
        if (!matchrounds.length) {
            roundSelect.innerHTML = '<option>Keine Spielrunden</option>';
            roundSelect.disabled = true;
            return;
        }
        matchrounds.forEach(function (round, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = round.matchround_title;
            opt.className = 'ffb-select-' + (index % 2);
            if (index === roundIndex) {
                opt.selected = true;
            }
            roundSelect.appendChild(opt);
        });
        roundSelect.disabled = false;
    }

    function renderUserSelect() {
        userSelect.innerHTML = '';
        profileLinkEl.innerHTML = '';
        if (!users.length) {
            userSelect.innerHTML = '<option>Keine Mitspieler</option>';
            userSelect.disabled = true;
            userIndex = -1;
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
        updateProfileLink();
    }

    function updateProfileLink() {
        const user = currentUser();
        if (!user) {
            profileLinkEl.innerHTML = '';
            return;
        }
        profileLinkEl.innerHTML =
            '<a href="#" data-modal="profile" data-id="' +
            user.user_id +
            '">Profil von ' +
            escapeHtml(user.user_nickname) +
            '</a>';
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

    function renderRoundStatsBlock(stats) {
        if (!stats) {
            return '';
        }
        let html = '<div class="stats-block">';
        html += '<div class="stats-heading">-- Spielrunden Statistik --</div>';

        html += '<div class="stats-subheading"><u>Statistik</u></div>';
        html += statsRow('symbol_user.png', 'Teilnehmer:', Number(stats.num_users) + ' Mitspieler');
        html += statsRow('stats_point.png', 'Anzahl Spiele:', Number(stats.num_matches) + ' Spiele');
        html += statsRow('stats_goal.gif', 'gefallene Tore:', Number(stats.goals) + ' Tore');
        html += statsRow('stats_owngoal.gif', 'gefallene Eigentore:', Number(stats.owngoals) + ' Tore');
        html += statsRow(
            'stats_card_yr.gif',
            'Karten (G/GR/R):',
            Number(stats.cards_y) + '/' + Number(stats.cards_yr) + '/' + Number(stats.cards_r)
        );
        html += statsRow('stats_point.png', 'Punkte pro Spieler:', Number(stats.score_per_player) + ' Punkte');
        html += '</div>';
        return html;
    }

    function renderUserStatsBlock(stats) {
        if (!stats) {
            return '<div class="stats-block"><p class="muted">Keine Benutzerstatistik verfügbar.</p></div>';
        }
        let html = '<div class="stats-block">';
        html += '<div class="stats-heading">-- Benutzer Statistik --</div>';
        html += statsRow('stats_lineup.png', 'Spielsystem:', escapeHtml(stats.system));
        html += statsRow('stats_goal.gif', 'erzielte Tore:', Number(stats.goals) + ' Tore');
        html += statsRow('stats_owngoal.gif', 'erzielte Eigentore:', Number(stats.owngoals) + ' Tore');
        html += statsRow(
            'stats_card_yr.gif',
            'Karten (G/GR/R):',
            Number(stats.cards_y) + '/' + Number(stats.cards_yr) + '/' + Number(stats.cards_r)
        );
        html += statsRow(
            'stats_point.png',
            'Punkte Abwehr:',
            Number(stats.score_g) + Number(stats.score_d) + ' Punkte'
        );
        html += statsRow('stats_point.png', 'Punkte Mittelfeld:', Number(stats.score_m) + ' Punkte');
        html += statsRow('stats_point.png', 'Punkte Angriff:', Number(stats.score_s) + ' Punkte');
        html += statsRow('stats_point.png', 'Punkte pro Spieler:', Number(stats.score_per_player) + ' Punkte');
        html += statsRow('symbol_credits.png', 'Credits pro Punkt:', Number(stats.credits_per_point) + ' Credits');
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

    function playerCard(player, showPrice) {
        const fname = escapeHtml(player.player_fname || '');
        const lname = escapeHtml(player.player_lname || '');
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamId = player.playerteam_team_id;
        const price = formatPlayerPrice(player.playerteam_player_price);
        return (
            '<div class="pitch-player">' +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '">' +
            shirtImgTag(teamId, nat, 'alt="" width="55" height="50"') +
            '</a>' +
            '<span class="name">' +
            fname +
            '<br>' +
            lname +
            '</span>' +
            '<a class="score" href="#" data-modal="player-points" data-id="' +
            player.playerteam_id +
            '" data-matchround-id="' +
            (currentRound() ? currentRound().matchround_id : 0) +
            '">' +
            Number(player.playerstats_score || 0) +
            ' Punkte</a>' +
            '<div class="meta">' +
            (showPrice
                ? '<span title="Preis: ' + price + ' Credits">' + price + '</span>'
                : '') +
            flagHtml(nat, player.playerteam_team || '') +
            '</div></div>'
        );
    }

    function renderTeam(data) {
        clearPitch();
        setPitchMessage('');
        const user = currentUser();
        selectedUserEl.textContent = user ? user.user_nickname : data.user_nickname || '';

        if (!data.userteam) {
            setPitchMessage('Keine Aufstellung für diesen Mitspieler in dieser Runde.');
            return;
        }

        teamScoreEl.textContent = String(data.userteam.userteam_score ?? 0);
        const teamPrice = Number(data.userteam.userteam_price || 0);
        const players = data.players || [];
        const showPrices = playerPricesMatchTeamTotal(players, teamPrice);
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
        players.forEach(function (player) {
            const pos = String(player.playerteam_player_position || '').toLowerCase();
            if (buckets[pos] !== undefined) {
                buckets[pos] += playerCard(player, showPrices);
            }
        });

        Object.keys(buckets).forEach(function (pos) {
            lines[pos].innerHTML = buckets[pos] || '';
        });
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
            renderRoundMeta();
            if (currentRound() && Number(currentRound().matchround_running) === 1) {
                sideTab = 'matches';
            } else {
                sideTab = 'stats';
            }
            await loadUsers();
        } catch (err) {
            roundSelect.innerHTML = '<option>Keine Spielrunden</option>';
            clearPitch();
            setPitchMessage('Noch keine Spielrunden verfügbar.');
            matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
        }
    }

    roundSelect.addEventListener('change', function () {
        roundIndex = Number(roundSelect.value) || 0;
        renderRoundMeta();
        if (currentRound() && Number(currentRound().matchround_running) === 1) {
            sideTab = 'matches';
        }
        loadUsers();
    });

    userSelect.addEventListener('change', function () {
        userIndex = Number(userSelect.value);
        updateProfileLink();
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
