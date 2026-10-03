(function () {
    const config = window.FFB_BESTTEAM || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';

    const roundSelect = document.getElementById('matchround_selection');
    const teamSelect = document.getElementById('team_selection');
    const metaEl = document.getElementById('round-meta');
    const selectedTeamEl = document.getElementById('selected-team');
    const teamScoreEl = document.getElementById('team-score');
    const teamPriceEl = document.getElementById('team-price');
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
    let teamType = 'top';
    let sideTab = 'stats';
    const teamCache = {};
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

    function shirtImgTag(teamId, nationality, attrs) {
        if (window.FfbShirts && typeof window.FfbShirts.imgTag === 'function') {
            return window.FfbShirts.imgTag(
                legacyBase,
                teamId,
                nationality,
                attrs || '',
                selectedLeagueId
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

    function cacheKey(matchroundId, type) {
        return String(matchroundId) + ':' + String(type);
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
        selectedTeamEl.textContent = '';
        if (teamSideStatsEl) {
            teamSideStatsEl.hidden = true;
        }
    }

    function setSelectsEnabled(enabled) {
        roundSelect.disabled = !enabled;
        teamSelect.disabled = !enabled;
    }

    function renderRoundMeta() {
        const round = currentRound();
        if (!round) {
            metaEl.textContent = 'Keine Spielrunden';
            return;
        }
        let html = escapeHtml(round.matchround_title);
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
        matchrounds.forEach(function (round, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = round.matchround_title;
            opt.className = 'ffb-select-' + (index % 2);
            if (Number(round.matchround_actual) === 1) {
                opt.selected = true;
                roundIndex = index;
            }
            roundSelect.appendChild(opt);
        });
        roundSelect.disabled = matchrounds.length === 0;
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
            return '<p class="muted">Keine Statistik verfügbar.</p>';
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

    function updateSideTabs() {
        const round = currentRound();
        if (!sideTabsEl) {
            return;
        }
        if (!round) {
            sideTabsEl.hidden = true;
            return;
        }
        sideTabsEl.hidden = false;
        sideTabsEl.querySelectorAll('[data-side-tab]').forEach(function (btn) {
            btn.disabled = false;
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

        if (sideTab === 'matches') {
            renderMatches();
            return;
        }

        matchlistEl.innerHTML = '<p class="muted">Lade Statistiken…</p>';

        const roundId = round.matchround_id;
        try {
            let roundStats = roundStatsCache[roundId];
            if (!roundStats) {
                const data = await fetchJson(
                    'bestteam/stats/round?matchround_id=' + encodeURIComponent(roundId)
                );
                roundStats = data.stats || null;
                roundStatsCache[roundId] = roundStats;
            }

            if (currentRound() !== round || sideTab !== 'stats') {
                return;
            }

            matchlistEl.innerHTML = renderRoundStatsBlock(roundStats);
        } catch (err) {
            matchlistEl.innerHTML =
                '<p class="muted">' +
                escapeHtml((err && err.message) || 'Statistiken konnten nicht geladen werden.') +
                '</p>';
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
        selectedTeamEl.textContent =
            teamType === 'flop' ? 'FLOP Team der Runde' : 'TOP Team der Runde';
        const ut = data.userteam || {};
        teamScoreEl.textContent = String(ut.userteam_score != null ? ut.userteam_score : '–');
        const price = ut.userteam_price != null ? Math.round(Number(ut.userteam_price) * 10) / 10 : null;
        teamPriceEl.textContent = price != null ? price.toFixed(1) : '–';
        if (teamSideStatsEl) {
            teamSideStatsEl.hidden = false;
        }

        const players = data.players || [];
        const showPrices = playerPricesMatchTeamTotal(players, ut.userteam_price);
        const grouped = { g: [], d: [], m: [], s: [] };
        players.forEach(function (player) {
            const pos = player.playerteam_player_position;
            if (grouped[pos]) {
                grouped[pos].push(player);
            }
        });

        Object.keys(grouped).forEach(function (pos) {
            if (!grouped[pos].length) {
                lines[pos].innerHTML = '';
                return;
            }
            lines[pos].innerHTML = grouped[pos]
                .map(function (player) {
                    return playerCard(player, showPrices);
                })
                .join('');
        });
    }

    async function loadTeam() {
        const round = currentRound();
        if (!round) {
            clearPitch();
            setPitchMessage('Noch keine Spielrunden verfügbar.');
            return;
        }

        setPitchMessage('');
        lines.m.innerHTML = '<p class="muted">Lade Team…</p>';
        selectedTeamEl.textContent = teamType === 'flop' ? 'FLOP Team der Runde' : 'TOP Team der Runde';
        teamScoreEl.textContent = '–';
        teamPriceEl.textContent = '–';
        if (teamSideStatsEl) {
            teamSideStatsEl.hidden = true;
        }

        const key = cacheKey(round.matchround_id, teamType);
        try {
            let data = teamCache[key];
            if (!data) {
                data = await fetchJson(
                    'bestteam/team?matchround_id=' +
                        encodeURIComponent(round.matchround_id) +
                        '&type=' +
                        encodeURIComponent(teamType)
                );
                teamCache[key] = data;
            }
            if (currentRound() !== round || teamType !== (data.type || teamType)) {
                return;
            }
            if (data.available === false) {
                clearPitch();
                setPitchMessage(
                    teamType === 'flop'
                        ? 'Flop-Team der Runde noch nicht verfügbar'
                        : 'Top-Team der Runde noch nicht verfügbar'
                );
                return;
            }
            renderTeam(data);
        } catch (err) {
            clearPitch();
            setPitchMessage((err && err.message) || 'Team konnte nicht geladen werden.');
        }
    }

    async function init() {
        setSelectsEnabled(false);
        try {
            const data = await fetchJson('bestteam/matchrounds');
            matchrounds = data.matchrounds || [];
            if (!matchrounds.length) {
                roundSelect.innerHTML = '<option>Keine Spielrunden</option>';
                clearPitch();
                setPitchMessage('Noch keine Spielrunden verfügbar.');
                matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
                return;
            }
            renderRoundSelect();
            renderRoundMeta();
            teamType = teamSelect.value || 'top';
            setSelectsEnabled(true);
            await loadTeam();
            await renderSidePanel();
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
        loadTeam();
        renderSidePanel();
    });

    teamSelect.addEventListener('change', function () {
        teamType = teamSelect.value || 'top';
        loadTeam();
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
