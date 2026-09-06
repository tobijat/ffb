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
    let teams = [];
    let matches = [];
    let lineuplist = [];
    let credits = 0;
    let matchesVisible = false;
    const playerCache = {};

    function symbolUrl(name) {
        return legacyBase + 'images/ffb/symbols/' + name;
    }

    function flagUrl(code) {
        const flag = (code && code !== '0' ? String(code) : 'aut').toLowerCase();
        return legacyBase + 'images/ffb/flags/' + flag + '.gif';
    }

    function shirtUrl(code) {
        const nat = code ? String(code) : 'AUT';
        return legacyBase + 'images/ffb/shirts/shirt_' + nat + '.png';
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

    function buildStars(starRating) {
        if (starRating < 1) {
            return (
                '<img src="' +
                symbolUrl('sternzero.gif') +
                '" width="80" alt="" title="Leistung: 0%">'
            );
        }
        if (starRating >= 90) {
            return (
                '<img src="' +
                symbolUrl('allstar.gif') +
                '" width="80" alt="" title="Leistung: ' +
                starRating +
                '%">'
            );
        }
        let html = '';
        let grade = starRating / 2;
        for (let count = 0; count < 5; count++) {
            let src = 'sterntot.gif';
            if (grade >= 5) {
                src = 'sternganz.gif';
            } else if (grade > 0) {
                src = 'sternhalb.gif';
            }
            html +=
                '<img src="' +
                symbolUrl(src) +
                '" width="16" alt="" title="Leistung: ' +
                starRating +
                '%">';
            grade -= 10;
        }
        return html;
    }

    function trendIcon(trend) {
        const t = parseInt(trend, 10) || 0;
        if (t > 0 && t <= 50) {
            return '<img src="' + symbolUrl('trend_u.png') + '" width="10" alt="" title="Tendenz: +' + t + '%">';
        }
        if (t > 50 && t <= 100) {
            return '<img src="' + symbolUrl('trend_uu.png') + '" width="10" alt="" title="Tendenz: +' + t + '%">';
        }
        if (t < 0 && t >= -50) {
            return '<img src="' + symbolUrl('trend_d.png') + '" width="10" alt="" title="Tendenz: ' + t + '%">';
        }
        if (t < -50 && t >= -100) {
            return '<img src="' + symbolUrl('trend_dd.png') + '" width="10" alt="" title="Tendenz: ' + t + '%">';
        }
        return '&nbsp;';
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
            Number(match.match_homescore) >= 0 &&
            Number(match.match_guestscore) >= 0
        ) {
            return escapeHtml(match.match_homescore) + ':' + escapeHtml(match.match_guestscore);
        }
        return escapeHtml(match.match_date || '-:-');
    }

    function hideMatches() {
        matchesVisible = false;
        matchlistEl.innerHTML =
            '<div style="text-align:center;"><a href="#" id="show-matches-link">Spiele einblenden</a></div>';
    }

    function showMatches() {
        matchesVisible = true;
        if (!matches.length) {
            matchlistEl.innerHTML = '<p class="muted">Keine Spiele.</p>';
            return;
        }
        let html =
            '<div style="text-align:center;margin-bottom:4px;"><a href="#" id="hide-matches-link">Spiele ausblenden</a></div>';
        html += '<ul class="match-list">';
        matches.forEach(function (match) {
            html +=
                '<li><span class="home">' +
                escapeHtml(match.match_hometeam_name) +
                ' <img src="' +
                flagUrl(match.match_hometeam_nationality) +
                '" alt=""></span>' +
                '<span class="score"><a class="nolink under" href="#" data-modal="match" data-id="' +
                match.match_id +
                '">' +
                formatMatchScore(match) +
                '</a></span>' +
                '<span class="away"><img src="' +
                flagUrl(match.match_guestteam_nationality) +
                '" alt=""> ' +
                escapeHtml(match.match_guestteam_name) +
                '</span></li>';
        });
        html += '</ul>';
        matchlistEl.innerHTML = html;
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
        const statusOk = Number(player.player_status) === 1;
        const nat = player.playerteam_team_nationality || 'AUT';
        return (
            '<div class="pitch-player">' +
            '<a href="#" data-remove="' +
            player.playerteam_id +
            '" title="Klicken um Spieler zu entfernen">' +
            '<img class="shirt" src="' +
            shirtUrl(nat) +
            '" width="55" height="50" alt=""></a>' +
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
            '<img src="' +
            flagUrl(nat) +
            '" alt="" title="' +
            escapeHtml(player.playerteam_team || '') +
            '" width="16" height="11">' +
            '<img src="' +
            symbolUrl(statusOk ? 'status_pos.png' : 'status_hurt.png') +
            '" width="16" height="16" alt="" title="status: ' +
            escapeHtml(statusOk ? 'Einsatzbereit' : player.player_status_description || '') +
            '">' +
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
        if (options && lineuplist.length === Number(options.lineup_max_players)) {
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
            opt.textContent = team.team_name + ' (Preis: ' + team.team_avg_price + ')';
            teamSelect.appendChild(opt);
        });
        teamSelect.disabled = teams.length === 0;
    }

    function renderPlayerList(players) {
        let html =
            '<div class="playerlist-head"><span title="Tendenz">T.</span><span>Name</span><span>Preis</span><span>St.</span><span>Info</span><span>Leistung</span></div>';
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
                    const statusOk = Number(p.player_status) === 1;
                    html +=
                        '<div class="playerline">' +
                        '<span class="trend">' +
                        trendIcon(p.player_trend) +
                        '</span>' +
                        '<span class="name"><a href="#" data-add="' +
                        p.playerteam_id +
                        '">' +
                        escapeHtml(p.player_fname + ' ' + p.player_lname) +
                        '</a></span>' +
                        '<span class="price">' +
                        escapeHtml(p.playerteam_player_price) +
                        '</span>' +
                        '<span class="status"><img src="' +
                        symbolUrl(statusOk ? 'status_pos.png' : 'status_hurt.png') +
                        '" width="16" height="16" alt="" title="status: ' +
                        escapeHtml(statusOk ? 'ok' : p.player_status_description || '') +
                        '"></span>' +
                        '<span class="info"><a href="#" data-modal="player" data-id="' +
                        p.playerteam_id +
                        '"><img src="' +
                        symbolUrl('info.png') +
                        '" width="16" height="16" alt="Info"></a></span>' +
                        '<span class="grade">' +
                        buildStars(Number(p.player_grade) || 0) +
                        '</span></div>';
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
            '<img src="' +
            flagUrl(team.team_nationality) +
            '" height="20" alt=""> <b>' +
            escapeHtml(team.team_name) +
            '</b> <img src="' +
            shirtUrl(team.team_nationality) +
            '" height="20" alt="">';
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
            const optJson = await fetchJson('lineup/options');
            options = optJson.data;

            const mrJson = await fetchJson('lineup/matchround');
            if (mrJson.data.game_over) {
                gameOverUi();
                return;
            }
            matchround = mrJson.data.matchround;
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
                '<br><span style="font-size:9pt;"><u>Deadline:</u> <em>' +
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
