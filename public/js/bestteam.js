(function () {
    const config = window.FFB_BESTTEAM || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';

    const roundSelect = document.getElementById('matchround_selection');
    const roundTitleEl = document.getElementById('round-title');
    const roundDatesEl = document.getElementById('round-dates');
    const teamSelect = document.getElementById('team_selection');
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
    const teamStatsCache = {};

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

    function teamStatsCacheKey(matchroundId, type) {
        return cacheKey(matchroundId, type);
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
        if (teamSideStatsEl) {
            teamSideStatsEl.hidden = true;
        }
    }

    function setSelectsEnabled(enabled) {
        roundSelect.disabled = !enabled;
        teamSelect.disabled = !enabled;
    }

    function teamLabel(type) {
        return type === 'flop' ? 'Flop-Team der Runde' : 'Top-Team der Runde';
    }

    function renderTeamFace() {
        const type = teamType === 'flop' ? 'flop' : 'top';
        selectedTeamEl.textContent = teamLabel(type);
        if (teamSelect) {
            teamSelect.value = type;
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
            if (Number(round.matchround_actual) === 1) {
                opt.selected = true;
                roundIndex = index;
            }
            roundSelect.appendChild(opt);
        });
        if (!roundSelect.querySelector('option[selected]') && matchrounds.length) {
            roundSelect.options[0].selected = true;
            roundIndex = 0;
        }
        roundSelect.disabled = false;
        renderRoundFace();
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
            return '<p class="muted">Keine Statistik verfügbar.</p>';
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

    function renderTeamStatsBlock(stats) {
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
        const type = teamType === 'flop' ? 'flop' : 'top';
        try {
            let roundStats = roundStatsCache[roundId];
            if (!roundStats) {
                const data = await fetchJson(
                    'bestteam/stats/round?matchround_id=' + encodeURIComponent(roundId)
                );
                roundStats = data.stats || null;
                roundStatsCache[roundId] = roundStats;
            }

            const teamKey = teamStatsCacheKey(roundId, type);
            let teamStats;
            if (Object.prototype.hasOwnProperty.call(teamStatsCache, teamKey)) {
                teamStats = teamStatsCache[teamKey];
            } else {
                const data = await fetchJson(
                    'bestteam/stats/team?matchround_id=' +
                        encodeURIComponent(roundId) +
                        '&type=' +
                        encodeURIComponent(type)
                );
                teamStats = data.stats || null;
                teamStatsCache[teamKey] = teamStats;
            }

            if (currentRound() !== round || sideTab !== 'stats' || teamType !== type) {
                return;
            }

            matchlistEl.innerHTML =
                renderRoundStatsBlock(roundStats) + renderTeamStatsBlock(teamStats);
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
            '</div></div>'
        );
    }

    function renderTeam(data) {
        clearPitch();
        renderTeamFace();
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
        updatePitchInfoLayout();
    }

    async function loadTeam() {
        const round = currentRound();
        if (!round) {
            clearPitch();
            setPitchMessage('Noch keine Spielrunden verfügbar.');
            updatePitchInfoLayout();
            return;
        }

        setPitchMessage('');
        lines.m.innerHTML = '<p class="muted">Lade Team…</p>';
        renderTeamFace();
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
                setPitchMessage(teamLabel(teamType) + ' noch nicht verfügbar');
                return;
            }
            renderTeam(data);
        } catch (err) {
            clearPitch();
            setPitchMessage((err && err.message) || 'Team konnte nicht geladen werden.');
        }
    }

    async function init() {
        if (window.FfbPitchInfoBar) {
            window.FfbPitchInfoBar.watch();
        }
        setSelectsEnabled(false);
        try {
            const data = await fetchJson('bestteam/matchrounds');
            matchrounds = data.matchrounds || [];
            if (!matchrounds.length) {
                roundSelect.innerHTML = '<option>Keine Spielrunden</option>';
                clearPitch();
                setPitchMessage('Noch keine Spielrunden verfügbar.');
                matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
                updatePitchInfoLayout();
                return;
            }
            renderRoundSelect();
            teamType = teamSelect.value || 'top';
            setSelectsEnabled(true);
            await loadTeam();
            await renderSidePanel();
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
        loadTeam();
        renderSidePanel();
    });

    teamSelect.addEventListener('change', function () {
        teamType = teamSelect.value || 'top';
        renderTeamFace();
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
