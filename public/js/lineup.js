(function () {
    const config = window.FFB_LINEUP || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';

    const roundMetaEl = document.getElementById('round-meta');
    const actionsEl = document.getElementById('lineup-actions');
    const messagesEl = document.getElementById('lineup-messages');
    const creditsEl = document.getElementById('lineup-credits');
    const matchlistEl = document.getElementById('matchlist');
    const teamSelect = document.getElementById('team_selection');
    const selectedTeamEl = document.getElementById('selected-team');
    const playerlistEl = document.getElementById('playerlist');
    const pickerPanel = document.getElementById('picker-panel');
    const pitchMessageEl = document.getElementById('pitch-message');
    const lines = {
        g: document.getElementById('line-g'),
        d: document.getElementById('line-d'),
        m: document.getElementById('line-m'),
        s: document.getElementById('line-s'),
    };

    let options = null;
    let matchround = null;
    let showRecentPerformance = false;
    let teams = [];
    let matches = [];
    let lineuplist = [];
    let credits = 0;
    let matchesVisible = false;
    const playerCache = {};

    function symbolUrl(name) {
        return legacyBase + 'images/ffb/symbols/' + name;
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

    function apiUrl(path) {
        return apiBase + '/' + path.replace(/^\//, '');
    }

    function xsrfToken() {
        const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    async function fetchJson(path, init) {
        const headers = Object.assign({ Accept: 'application/json' }, (init && init.headers) || {});
        const xsrf = xsrfToken();
        if (xsrf && init && init.method && init.method.toUpperCase() !== 'GET') {
            headers['X-XSRF-TOKEN'] = xsrf;
            headers['X-Requested-With'] = 'XMLHttpRequest';
        }
        const response = await fetch(apiUrl(path), Object.assign({ credentials: 'same-origin' }, init || {}, { headers }));
        const json = await response.json();
        if (!response.ok || json.status !== 200) {
            const err = new Error((json && (json.error || json.message)) || 'Request failed');
            err.status = response.status;
            err.payload = json;
            throw err;
        }
        return json;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function clearMessages() {
        messagesEl.className = 'lineup-messages';
        messagesEl.innerHTML = '';
    }

    function addErrorMessage(error) {
        messagesEl.className = 'lineup-messages is-error';
        messagesEl.innerHTML =
            '<img src="' +
            symbolUrl('symb_err_anim.gif') +
            '" height="11" alt=""> <b>' +
            error +
            '</b> <img src="' +
            symbolUrl('symb_err_anim.gif') +
            '" height="11" alt="">';
    }

    function addOkMessage(answer) {
        messagesEl.className = 'lineup-messages is-ok';
        messagesEl.innerHTML =
            '<img src="' + symbolUrl('ok.png') + '" height="11" alt=""> <b>' + escapeHtml(answer) + '</b>';
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
    }

    function normalizePerformance(rawValue) {
        let value = Number(rawValue);
        if (!Number.isFinite(value)) {
            value = 0;
        }
        return Math.max(-1, Math.min(1, value));
    }

    function performanceColor(value) {
        const intensity = Math.abs(value);
        if (value < 0) {
            // Vivid crimson — stronger magnitude = deeper / more saturated
            const sat = 88 + Math.round(intensity * 12);
            const light = 54 - Math.round(intensity * 16);

            return 'hsl(2 ' + sat + '% ' + light + '%)';
        }

        // Bright lime-green — pops on mint list rows; stronger = more neon
        const sat = 90 + Math.round(intensity * 10);
        const light = 48 - Math.round(intensity * 8);

        return 'hsl(128 ' + sat + '% ' + light + '%)';
    }

    function formatPerformanceLabel(value) {
        const n = Math.round(value * 100);
        if (n === 0) {
            return '0';
        }
        return (n > 0 ? '+' : '') + String(n);
    }

    function buildPerformanceBar(rawValue) {
        const value = normalizePerformance(rawValue);
        const label = formatPerformanceLabel(value);
        const widthPct = Math.abs(value) * 50;
        const fillClass = value < 0 ? 'is-neg' : 'is-pos';
        const fillStyle =
            widthPct > 0
                ? 'width:' + widthPct + '%;background-color:' + performanceColor(value)
                : 'width:0';

        return (
            '<span class="perf-meter" title="' +
            label +
            '" aria-label="' +
            label +
            '">' +
            '<span class="perf-meter-track">' +
            '<span class="perf-meter-mid"></span>' +
            (widthPct > 0
                ? '<span class="perf-meter-fill ' + fillClass + '" style="' + fillStyle + '"></span>'
                : '') +
            '</span></span>'
        );
    }

    function buildPerformanceTrend(rawValue) {
        const value = normalizePerformance(rawValue);
        if (Math.round(value * 100) === 0) {
            return '';
        }

        const dirClass = value < 0 ? 'is-down' : 'is-up';
        const color = performanceColor(value);

        return (
            '<span class="perf-trend ' +
            dirClass +
            '" style="--perf-trend-color:' +
            color +
            '"></span>'
        );
    }

    function buildCardWarning(warning) {
        if (!warning) {
            return '';
        }

        const text = String(warning);

        return (
            '<span class="card-warn" title="' +
            escapeHtml(text) +
            '" aria-label="' +
            escapeHtml(text) +
            '">!</span>'
        );
    }

    function selectionWarningText(player) {
        const note = String(player.playerteam_player_note || '').trim();
        if (note !== '') {
            return note;
        }
        const card = player.card_warning;
        if (card) {
            return String(card);
        }
        return '';
    }

    function buildSelectionWarning(player) {
        return buildCardWarning(selectionWarningText(player) || null);
    }

    function hideMatches() {
        matchesVisible = false;
        matchlistEl.innerHTML =
            '<div style="text-align:center;"><a href="#" id="show-matches-link">Spiele einblenden</a></div>';
    }

    function showMatches() {
        matchesVisible = true;
        if (!matches.length) {
            matchlistEl.innerHTML =
                '<div style="text-align:center;margin-bottom:4px;"><a href="#" id="hide-matches-link">Spiele ausblenden</a></div>' +
                '<p class="muted">Keine Spiele.</p>';
            return;
        }
        const wrap = document.createElement('div');
        wrap.innerHTML =
            '<div style="text-align:center;margin-bottom:4px;"><a href="#" id="hide-matches-link">Spiele ausblenden</a></div>';
        const listHost = document.createElement('div');
        wrap.appendChild(listHost);
        matchlistEl.innerHTML = '';
        matchlistEl.appendChild(wrap);
        if (window.FfbMatchList) {
            window.FfbMatchList.render(listHost, matches, {
                emptyHtml: '<p class="muted">Keine Spiele.</p>',
            });
        }
    }

    function blankSlot(red, label) {
        const src = red ? 'shirt_BLANK_RED.png' : 'shirt_BLANK.png';
        return (
            '<div class="pitch-player pitch-slot">' +
            '<img src="' +
            legacyBase +
            'images/ffb/shirts/' +
            src +
            '" width="55" height="50" alt="">' +
            '<span class="name">' +
            label +
            '</span></div>'
        );
    }

    function playerCard(player) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamId = player.playerteam_team_id;
        const warn = buildSelectionWarning(player);
        return (
            '<div class="pitch-player">' +
            '<a href="#" data-remove="' +
            player.playerteam_id +
            '" title="Klicken um Spieler zu entfernen">' +
            shirtImgTag(teamId, nat, 'width="55" height="50" alt=""') +
            '</a>' +
            '<a class="name" href="#" data-remove="' +
            player.playerteam_id +
            '" title="Klicken um Spieler zu entfernen">' +
            escapeHtml(player.player_fname || '') +
            '<br>' +
            escapeHtml(player.player_lname || '') +
            '</a>' +
            '<div class="meta">' +
            '<span title="Preis: ' +
            player.player_price +
            ' Credits">' +
            player.player_price +
            '</span>' +
            flagHtml(nat, player.playerteam_team || '') +
            warn +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '"><img src="' +
            symbolUrl('info.png') +
            '" width="16" height="16" alt="Info"></a>' +
            '</div></div>'
        );
    }

    function updateLineupDisplay() {
        if (!options) {
            return;
        }
        const grouped = { g: [], d: [], m: [], s: [] };
        const counts = { g: 0, d: 0, m: 0, s: 0 };
        lineuplist.forEach(function (p) {
            const pos = p.playerteam_player_position;
            if (grouped[pos]) {
                grouped[pos].push(p);
                counts[pos]++;
            }
        });

        const labels = { g: 'TOR', d: 'VERTEIDIGUNG', m: 'MITTELFELD', s: 'STURM' };
        const mins = {
            g: Number(options.lineup_min_g),
            d: Number(options.lineup_min_d),
            m: Number(options.lineup_min_m),
            s: Number(options.lineup_min_s),
        };
        const maxs = {
            g: Number(options.lineup_max_g),
            d: Number(options.lineup_max_d),
            m: Number(options.lineup_max_m),
            s: Number(options.lineup_max_s),
        };

        Object.keys(grouped).forEach(function (pos) {
            let html = grouped[pos].map(playerCard).join('');
            if (lineuplist.length < Number(options.lineup_max_players)) {
                const needRed = Math.max(0, mins[pos] - counts[pos]);
                const needBlank = Math.max(0, maxs[pos] - needRed - counts[pos]);
                for (let i = 0; i < needRed; i++) {
                    html += blankSlot(true, labels[pos]);
                }
                for (let i = 0; i < needBlank; i++) {
                    html += blankSlot(false, labels[pos]);
                }
            }
            lines[pos].innerHTML = html;
        });
    }

    function updateCreditsDisplay() {
        if (!options) {
            return;
        }
        creditsEl.hidden = false;
        const rounded = Math.round(credits * 10) / 10;
        creditsEl.classList.toggle('is-over', rounded < 0);
        const needed = Number(options.lineup_max_players) - lineuplist.length;
        let html =
            '<div class="lineup-credits-row"><img src="' +
            symbolUrl('symbol_credits.png') +
            '" alt=""><span>' +
            rounded +
            '</span></div>';
        if (needed > 0) {
            html += '<div>noch <b>' + needed + '</b> Spieler</div>';
        }
        html +=
            '<div title="Du kannst maximal ' +
            options.lineup_max_players_team +
            ' Spieler vom selben Team aufstellen">max. <b>' +
            options.lineup_max_players_team +
            '</b> Sp./Team</div>';
        creditsEl.innerHTML = html;
    }

    function dispActionButtons(saving) {
        if (saving) {
            actionsEl.innerHTML =
                '<button type="button" class="ffb-button-disabled" disabled>Bitte warten…</button>';
            return;
        }
        const complete =
            options && lineuplist.length === Number(options.lineup_max_players);
        const withinBudget = Math.round(credits * 10) / 10 >= 0;
        if (complete && withinBudget) {
            actionsEl.innerHTML =
                '<button type="button" class="ffb-button" id="save-lineup-btn">Aufstellung speichern</button>';
        } else {
            actionsEl.innerHTML =
                '<button type="button" class="ffb-button-disabled" disabled>Aufstellung speichern</button>';
        }
    }

    function checkLineup(player) {
        const maxPlayers = Number(options.lineup_max_players);
        if (lineuplist.length + 1 > maxPlayers) {
            addErrorMessage('Du hast bereits ' + maxPlayers + ' Spieler aufgestellt!');
            return false;
        }
        if (Math.round((credits - player.player_price) * 10) / 10 < 0) {
            addErrorMessage('Du hast zuwenig Credits um diesen Spieler zu kaufen!');
            return false;
        }

        let numG = 0;
        let numD = 0;
        let numM = 0;
        let numS = 0;
        let numTeam = 0;
        for (let i = 0; i < lineuplist.length; i++) {
            if (String(lineuplist[i].playerteam_id) === String(player.playerteam_id)) {
                addErrorMessage('Dieser Spieler befindet sich bereits in deiner Aufstellung!');
                return false;
            }
            const pos = lineuplist[i].playerteam_player_position;
            if (pos === 'g') numG++;
            if (pos === 'd') numD++;
            if (pos === 'm') numM++;
            if (pos === 's') numS++;
            if (String(lineuplist[i].playerteam_team_id) === String(player.playerteam_team_id)) {
                numTeam++;
            }
        }

        const left = maxPlayers - lineuplist.length;
        const pos = player.player_position || player.playerteam_player_position;
        if (pos === 'g' && numG + 1 > Number(options.lineup_max_g)) {
            addErrorMessage('Du hast bereits ' + options.lineup_max_g + ' Spieler im Tor!');
            return false;
        }
        if (pos === 'd' && numD + 1 > Number(options.lineup_max_d)) {
            addErrorMessage('Du hast bereits ' + options.lineup_max_d + ' Spieler in der Verteidigung!');
            return false;
        }
        if (pos === 'm' && numM + 1 > Number(options.lineup_max_m)) {
            addErrorMessage('Du hast bereits ' + options.lineup_max_m + ' Spieler im Mittelfeld!');
            return false;
        }
        if (pos === 's' && numS + 1 > Number(options.lineup_max_s)) {
            addErrorMessage('Du hast bereits ' + options.lineup_max_s + ' Spieler im Sturm!');
            return false;
        }
        if (numTeam + 1 > Number(options.lineup_max_players_team)) {
            addErrorMessage(
                'Du hast bereits ' +
                    options.lineup_max_players_team +
                    ' Spieler von ' +
                    player.playerteam_team +
                    ' aufgestellt!'
            );
            return false;
        }

        if (pos === 'g') {
            if (
                Number(options.lineup_min_d) - numD > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                addErrorMessage('Du benötigst noch Spieler an anderen Positionen!');
                return false;
            }
        }
        if (pos === 'd') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                addErrorMessage('Du benötigst noch Spieler an anderen Positionen!');
                return false;
            }
        }
        if (pos === 'm') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_d) - numD > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                addErrorMessage('Du benötigst noch Spieler an anderen Positionen!');
                return false;
            }
        }
        if (pos === 's') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_d) - numD > left - 1
            ) {
                addErrorMessage('Du benötigst noch Spieler an anderen Positionen!');
                return false;
            }
        }
        return true;
    }

    function addPlayer(player) {
        clearMessages();
        if (!checkLineup(player)) {
            return;
        }
        lineuplist.push({
            player_id: player.player_id,
            player_fname: player.player_fname,
            player_lname: player.player_lname,
            player_nationality: player.player_nationality,
            player_status: player.player_status,
            player_status_description: player.player_status_description,
            playerteam_player_position: player.playerteam_player_position || player.player_position,
            player_price: Number(player.playerteam_player_price != null ? player.playerteam_player_price : player.player_price),
            playerteam_team_id: player.playerteam_team_id,
            playerteam_team: player.playerteam_team,
            playerteam_team_nationality: player.playerteam_team_nationality,
            playerteam_id: player.playerteam_id,
            playerteam_player_note: player.playerteam_player_note || '',
            card_warning: player.card_warning || null,
        });
        credits -= lineuplist[lineuplist.length - 1].player_price;
        updateLineupDisplay();
        updateCreditsDisplay();
        dispActionButtons();
    }

    function removePlayer(playerteamId) {
        clearMessages();
        for (let i = 0; i < lineuplist.length; i++) {
            if (String(lineuplist[i].playerteam_id) === String(playerteamId)) {
                credits += Number(lineuplist[i].player_price);
                lineuplist.splice(i, 1);
                break;
            }
        }
        updateLineupDisplay();
        updateCreditsDisplay();
        dispActionButtons();
    }

    async function saveLineup() {
        clearMessages();
        dispActionButtons(true);
        const ids = lineuplist.map(function (p) {
            return p.playerteam_id;
        });
        try {
            const json = await fetchJson('lineup', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({
                    matchround_id: matchround.matchround_id,
                    playerteam_ids: ids,
                }),
            });
            addOkMessage(json.message || 'Deine Aufstellung wurde gespeichert!');
        } catch (err) {
            addErrorMessage(escapeHtml((err && err.message) || 'Speichern fehlgeschlagen'));
        }
        dispActionButtons();
    }

    function renderTeamSelect() {
        teamSelect.innerHTML = '<option disabled selected>Mannschaft..</option>';
        teams.forEach(function (team, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.className = 'ffb-select-' + (index % 2);
            opt.textContent =
                team.team_price != null && team.team_price !== ''
                    ? team.team_name + ' (Preis: ' + team.team_price + ')'
                    : team.team_name;
            teamSelect.appendChild(opt);
        });
        teamSelect.disabled = teams.length === 0;
    }

    function renderPlayerList(players) {
        playerlistEl.classList.toggle('playerlist--with-perf', showRecentPerformance);

        let html = showRecentPerformance
            ? '<div class="playerlist-head"><span></span><span>Name</span><span>Preis</span><span></span><span>Info</span><span>Leistung</span></div>'
            : '<div class="playerlist-head"><span>Name</span><span>Preis</span><span></span><span>Info</span></div>';
        const sections = [
            { key: 'g', title: 'Torhüter' },
            { key: 'd', title: 'Verteidiger' },
            { key: 'm', title: 'Mittelfeldspieler' },
            { key: 's', title: 'Stürmer' },
        ];
        sections.forEach(function (sec) {
            html += '<div class="playerlist-pos">' + sec.title + '</div>';
            players
                .filter(function (p) {
                    return p.playerteam_player_position === sec.key;
                })
                .forEach(function (p) {
                    html += '<div class="playerline">';
                    if (showRecentPerformance) {
                        html +=
                            '<span class="trend">' +
                            buildPerformanceTrend(p.recent_performance) +
                            '</span>';
                    }
                    html +=
                        '<span class="name"><a href="#" data-add="' +
                        p.playerteam_id +
                        '">' +
                        escapeHtml(p.player_fname + ' ' + p.player_lname) +
                        '</a></span>' +
                        '<span class="price">' +
                        escapeHtml(p.playerteam_player_price) +
                        '</span>' +
                        '<span class="status">' +
                        buildSelectionWarning(p) +
                        '</span>' +
                        '<span class="info"><a href="#" data-modal="player" data-id="' +
                        p.playerteam_id +
                        '"><img src="' +
                        symbolUrl('info.png') +
                        '" width="16" height="16" alt="Info"></a></span>';
                    if (showRecentPerformance) {
                        html +=
                            '<span class="grade">' +
                            buildPerformanceBar(p.recent_performance) +
                            '</span>';
                    }
                    html += '</div>';
                });
        });
        playerlistEl.innerHTML = html;
        playerlistEl._playersById = {};
        players.forEach(function (p) {
            playerlistEl._playersById[String(p.playerteam_id)] = p;
        });
    }

    async function changeTeamSelection() {
        const index = Number(teamSelect.value);
        const team = teams[index];
        if (!team || !matchround) {
            return;
        }
        selectedTeamEl.innerHTML =
            flagHtml(team.team_nationality) +
            ' <b>' +
            escapeHtml(team.team_name) +
            '</b> ' +
            shirtImgTag(team.team_id, team.team_nationality, 'height="20" alt=""');
        playerlistEl.innerHTML = '<p class="muted">Lade Spielerliste…</p>';

        const teamId = team.team_id;
        try {
            let players = playerCache[teamId];
            if (!players) {
                const data = await fetchJson(
                    'lineup/teams/' +
                        encodeURIComponent(teamId) +
                        '/players?matchround_id=' +
                        encodeURIComponent(matchround.matchround_id)
                ).then(function (j) {
                    return j.data;
                });
                players = data.players || [];
                playerCache[teamId] = players;
            }
            renderPlayerList(players);
        } catch (err) {
            playerlistEl.innerHTML =
                '<p class="muted">' + escapeHtml((err && err.message) || 'Spieler konnten nicht geladen werden.') + '</p>';
        }
    }

    async function loadExistingLineup() {
        credits = Number(options.lineup_max_credits);
        lineuplist = [];
        try {
            const data = await fetchJson('lineup?matchround_id=' + encodeURIComponent(matchround.matchround_id)).then(
                function (j) {
                    return j.data;
                }
            );
            if (!data.userteam || !(data.players || []).length) {
                updateLineupDisplay();
                updateCreditsDisplay();
                dispActionButtons();
                return;
            }
            credits -= Number(data.userteam.userteam_price || 0);
            lineuplist = (data.players || []).map(function (p) {
                return {
                    player_id: p.player_id,
                    player_fname: p.player_fname,
                    player_lname: p.player_lname,
                    player_nationality: p.player_nationality,
                    player_status: p.player_status,
                    player_status_description: p.player_status_description,
                    playerteam_player_position: p.playerteam_player_position,
                    player_price: Number(p.playerteam_player_price),
                    playerteam_team_id: p.playerteam_team_id,
                    playerteam_team: p.playerteam_team,
                    playerteam_team_nationality: p.playerteam_team_nationality,
                    playerteam_id: p.playerteam_id,
                    playerteam_player_note: p.playerteam_player_note || '',
                    card_warning: p.card_warning || null,
                };
            });
            updateLineupDisplay();
            updateCreditsDisplay();
            dispActionButtons();
        } catch (err) {
            updateLineupDisplay();
            updateCreditsDisplay();
            dispActionButtons();
            addErrorMessage(escapeHtml((err && err.message) || 'Aufstellung konnte nicht geladen werden.'));
        }
    }

    function blockUi(message) {
        clearPitch();
        setPitchMessage(message);
        creditsEl.hidden = true;
        pickerPanel.hidden = true;
        matchlistEl.innerHTML = '';
        actionsEl.innerHTML = '';
    }

    function gameOverUi() {
        clearPitch();
        lines.m.innerHTML =
            '<img src="' + symbolUrl('gameover.png') + '" width="320" alt="Game Over" style="max-width:100%;">';
        creditsEl.hidden = true;
        pickerPanel.hidden = true;
        matchlistEl.innerHTML = '';
        actionsEl.innerHTML = '';
        roundMetaEl.textContent = '';
    }

    async function init() {
        if (config.gameOver) {
            gameOverUi();
            return;
        }

        try {
            const mrJson = await fetchJson('lineup/matchround');
            if (mrJson.data.game_over) {
                gameOverUi();
                return;
            }
            matchround = mrJson.data.matchround;
            showRecentPerformance = !!mrJson.data.show_recent_performance;
            if (mrJson.data.lineup_options) {
                options = mrJson.data.lineup_options;
            } else {
                const optJson = await fetchJson('lineup/options');
                options = optJson.data;
            }
            if (
                options &&
                String(options.game_pricemode || '') !== 'dynamic'
            ) {
                showRecentPerformance = false;
            }
            if (!matchround) {
                roundMetaEl.textContent = '';
                addErrorMessage('Keine weitere Spielrunde vorhanden! Bitte später nochmal probieren!');
                blockUi('');
                return;
            }

            matches = matchround.matches || [];
            teams = matchround.teams || [];
            roundMetaEl.innerHTML =
                escapeHtml(matchround.matchround_title) +
                '<span class="lineup-deadline"><u>Deadline:</u> <em>' +
                escapeHtml(matchround.matchround_deadline) +
                '</em></span>';

            if (
                Number(matchround.matchround_status) !== 1 ||
                !matches.length ||
                !teams.length
            ) {
                addErrorMessage('Spielrunde noch nicht bereit!');
                blockUi('');
                return;
            }

            hideMatches();
            renderTeamSelect();
            await loadExistingLineup();
        } catch (err) {
            addErrorMessage(escapeHtml((err && err.message) || 'Aufstellung konnte nicht geladen werden.'));
            blockUi('');
        }
    }

    matchlistEl.addEventListener('click', function (event) {
        const show = event.target.closest('#show-matches-link');
        const hide = event.target.closest('#hide-matches-link');
        if (show) {
            event.preventDefault();
            showMatches();
        } else if (hide) {
            event.preventDefault();
            hideMatches();
        }
    });

    teamSelect.addEventListener('change', changeTeamSelection);

    playerlistEl.addEventListener('click', function (event) {
        const add = event.target.closest('[data-add]');
        if (!add) {
            return;
        }
        event.preventDefault();
        const id = add.getAttribute('data-add');
        const player = playerlistEl._playersById && playerlistEl._playersById[id];
        if (player) {
            addPlayer(player);
        }
    });

    document.getElementById('soccer-field').addEventListener('click', function (event) {
        const rem = event.target.closest('[data-remove]');
        if (!rem) {
            return;
        }
        event.preventDefault();
        removePlayer(rem.getAttribute('data-remove'));
    });

    actionsEl.addEventListener('click', function (event) {
        if (event.target.id === 'save-lineup-btn') {
            saveLineup();
        }
    });

    init();
})();
