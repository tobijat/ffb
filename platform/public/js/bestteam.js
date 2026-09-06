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
    const matchlistEl = document.getElementById('matchlist');
    const pitchMessageEl = document.getElementById('pitch-message');
    const sideTabsEl = document.getElementById('side-tabs');
    const sideTitleEl = document.getElementById('side-panel-title');
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

    function flagUrl(code) {
        const flag = (code && code !== '0' ? String(code) : 'aut').toLowerCase();
        return legacyBase + 'images/ffb/flags/' + flag + '.gif';
    }

    function shirtUrl(code) {
        const nat = code ? String(code) : 'AUT';
        return legacyBase + 'images/ffb/shirts/shirt_' + nat + '.png';
    }

    function statusIcon(player) {
        if (Number(player.player_status) === 1) {
            return {
                src: legacyBase + 'images/ffb/symbols/status_pos.png',
                title: 'status: Einsatzbereit',
            };
        }
        return {
            src: legacyBase + 'images/ffb/symbols/status_hurt.png',
            title: 'status: ' + (player.player_status_description || 'verletzt'),
        };
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
        if (round.matchround_startdate === round.matchround_enddate) {
            html += '<br><em>' + escapeHtml(round.matchround_startdate) + '</em>';
        } else {
            html +=
                '<br><em>' +
                escapeHtml(round.matchround_startdate) +
                ' bis ' +
                escapeHtml(round.matchround_enddate) +
                '</em>';
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

    function hasPenaltyScore(match) {
        const homePen = parseInt(match.match_homescore_penalty, 10);
        const guestPen = parseInt(match.match_guestscore_penalty, 10);
        return !Number.isNaN(homePen) && !Number.isNaN(guestPen) && homePen > -1 && guestPen > -1;
    }

    function formatMatchScore(match) {
        if (hasPenaltyScore(match)) {
            let html =
                '<span class="score-final">' +
                escapeHtml(match.match_homescore_penalty) +
                ':' +
                escapeHtml(match.match_guestscore_penalty) +
                ' <span class="score-hint" title="nach Elfmeterschießen">n.E.</span></span>';

            if (
                match.match_homescore != null &&
                match.match_guestscore != null &&
                String(match.match_homescore) !== '' &&
                String(match.match_guestscore) !== '' &&
                Number(match.match_homescore) >= 0 &&
                Number(match.match_guestscore) >= 0
            ) {
                html +=
                    '<span class="score-reg">(' +
                    escapeHtml(match.match_homescore) +
                    ':' +
                    escapeHtml(match.match_guestscore) +
                    ' <span class="score-hint" title="nach Verlängerung">n.V.</span>)</span>';
            }
            return html;
        }
        if (
            match.match_homescore != null &&
            match.match_guestscore != null &&
            String(match.match_homescore) !== '' &&
            String(match.match_guestscore) !== '' &&
            Number(match.match_homescore) >= 0 &&
            Number(match.match_guestscore) >= 0
        ) {
            return escapeHtml(match.match_homescore) + ':' + escapeHtml(match.match_guestscore);
        }
        return '-:-';
    }

    function renderMatches() {
        const round = currentRound();
        const matches = (round && round.matches) || [];
        if (!matches.length) {
            matchlistEl.innerHTML = '<p class="muted">Keine Spiele in dieser Runde.</p>';
            return;
        }
        const ul = document.createElement('ul');
        ul.className = 'match-list';
        matches.forEach(function (match) {
            const li = document.createElement('li');
            const homeFlag = flagUrl(match.match_hometeam_nationality);
            const guestFlag = flagUrl(match.match_guestteam_nationality);
            li.innerHTML =
                '<span class="home">' +
                escapeHtml(match.match_hometeam_name) +
                ' <img src="' +
                homeFlag +
                '" alt=""></span>' +
                '<span class="score"><a class="nolink under" href="#" data-modal="match" data-id="' +
                match.match_id +
                '" title="Klicken für Matchinfos">' +
                formatMatchScore(match) +
                '</a></span>' +
                '<span class="away"><img src="' +
                guestFlag +
                '" alt=""> ' +
                escapeHtml(match.match_guestteam_name) +
                '</span>';
            ul.appendChild(li);
        });
        matchlistEl.innerHTML = '';
        matchlistEl.appendChild(ul);
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

        if (stats.top_of_round) {
            const top = stats.top_of_round;
            html += '<div class="stats-subheading"><u>Der TOP Spieler der Runde</u></div>';
            html +=
                '<div class="stats-highlight"><img src="' +
                symbolUrl('stats_top.png') +
                '" alt="" width="16" height="16"> ' +
                '<a href="#" data-modal="player" data-id="' +
                top.top_playerteam_id +
                '"><b>' +
                escapeHtml(top.top_player_name) +
                '</b></a> (<em>' +
                escapeHtml(top.top_team_name) +
                '</em>, ' +
                Number(top.top_score) +
                ' Punkte)</div>';
        }

        if (stats.flop_of_round) {
            const flop = stats.flop_of_round;
            html += '<div class="stats-subheading"><u>Der FLOP Spieler der Runde</u></div>';
            html +=
                '<div class="stats-highlight"><img src="' +
                symbolUrl('stats_flop.png') +
                '" alt="" width="16" height="16"> ' +
                '<a href="#" data-modal="player" data-id="' +
                flop.flop_playerteam_id +
                '"><b>' +
                escapeHtml(flop.flop_player_name) +
                '</b></a> (<em>' +
                escapeHtml(flop.flop_team_name) +
                '</em>, ' +
                Number(flop.flop_score) +
                ' Punkte)</div>';
        }

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
            if (sideTitleEl) {
                sideTitleEl.textContent = 'Spiele';
            }
            matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
            return;
        }

        if (sideTab === 'matches') {
            if (sideTitleEl) {
                sideTitleEl.textContent = 'Spiele';
            }
            renderMatches();
            return;
        }

        if (sideTitleEl) {
            sideTitleEl.textContent = 'Statistiken';
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

    function playerCard(player) {
        const status = statusIcon(player);
        const fname = escapeHtml(player.player_fname || '');
        const lname = escapeHtml(player.player_lname || '');
        const nat = player.playerteam_team_nationality || 'AUT';
        return (
            '<div class="pitch-player">' +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '">' +
            '<img class="shirt" src="' +
            shirtUrl(nat) +
            '" alt="" width="55" height="50">' +
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
            '<img src="' +
            flagUrl(nat) +
            '" alt="' +
            escapeHtml(player.playerteam_team || '') +
            '" title="' +
            escapeHtml(player.playerteam_team || '') +
            '" width="16" height="11">' +
            '<img src="' +
            status.src +
            '" alt="" title="' +
            escapeHtml(status.title) +
            '" width="16" height="16">' +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '"><img src="' +
            legacyBase +
            'images/ffb/symbols/info.png" alt="Info" width="16" height="16"></a>' +
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
        teamPriceEl.textContent = price != null ? String(price) : '–';

        const grouped = { g: [], d: [], m: [], s: [] };
        (data.players || []).forEach(function (player) {
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
            lines[pos].innerHTML = grouped[pos].map(playerCard).join('');
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
