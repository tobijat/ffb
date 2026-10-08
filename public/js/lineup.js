(function () {
    const config = window.FFB_LINEUP || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';

    const roundMetaEl = document.getElementById('round-meta');
    const actionsEl = document.getElementById('lineup-actions');
    const messagesEl = document.getElementById('lineup-messages');
    const creditsEl = document.getElementById('lineup-credits');
    const matchlistEl = document.getElementById('matchlist');
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
    /** @type {Record<string, {min_player_price: number|null, cheapest_by_position: Record<string, {playerteam_id: number, price: number}|null>}>} */
    let teamSelectHints = {};
    let lineuplist = [];
    let benchlist = [];
    let credits = 0;
    let expandedTeamId = 0;
    let expandedMatchId = 0;
    let lineupDirty = false;
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
        const tid = Number(teamId) || 0;
        const nat = String(nationality || 'aut')
            .toLowerCase()
            .trim()
            .replace(/[ .]/g, '_')
            .replace(/[^a-z0-9_-]+/g, '');
        const blank = legacyBase + 'images/ffb/shirts/shirt_MISSING.svg';
        if (tid <= 0 || !nat) {
            return '<img class="shirt" src="' + blank + '" ' + (attrs || '') + '>';
        }
        const src =
            selectedLeagueId > 0
                ? legacyBase + 'images/ffb/shirts/' + tid + '/' + nat + '-' + selectedLeagueId + '.png'
                : legacyBase + 'images/ffb/shirts/' + tid + '/' + nat + '.png';
        return (
            '<img class="shirt" src="' +
            src +
            '" ' +
            (attrs || '') +
            ' onerror="this.onerror=null;this.src=\'' +
            blank +
            '\'">'
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
            '<img src="' + symbolUrl('ok.svg') + '" height="11" alt=""> <b>' + escapeHtml(answer) + '</b>';
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

    function inactiveSquadWarningText(player) {
        if (Number(player.playerteam_status) === 1) {
            return '';
        }
        // Missing status (e.g. selection list) means active / not applicable.
        if (player.playerteam_status == null || player.playerteam_status === '') {
            return '';
        }
        const team = String(player.playerteam_team || '').trim() || 'diesem Team';
        return (
            'Der Spieler befindet sich aktuell nicht im Kader von ' +
            team +
            '. Bitte Aufstellung prüfen!'
        );
    }

    function selectionWarningText(player) {
        const inactive = inactiveSquadWarningText(player);
        if (inactive !== '') {
            return inactive;
        }
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

    function playerInfoLinkHtml(player) {
        const warning = selectionWarningText(player);
        const icon = warning !== '' ? 'info_warning.svg' : 'info.svg';
        const titleAttr =
            warning !== ''
                ? ' title="' +
                  escapeHtml(warning) +
                  '" aria-label="' +
                  escapeHtml(warning) +
                  '"'
                : '';

        return (
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '"' +
            titleAttr +
            '><img src="' +
            symbolUrl(icon) +
            '" width="16" height="16" alt="Info"></a>'
        );
    }

    function formatTeamPrice(value) {
        if (value == null || value === '') {
            return '—';
        }
        const num = Number(value);
        if (!Number.isFinite(num)) {
            return '—';
        }
        return num.toFixed(1);
    }

    function setLineupDirty(dirty) {
        lineupDirty = !!dirty;
    }

    function priceFitsBudget(price, ctx) {
        return Math.round((ctx.credits - Number(price)) * 10) / 10 >= 0;
    }

    function fieldPositionIsAllowed(pos, ctx) {
        if (!options) {
            return false;
        }
        const left = ctx.left;
        const numG = ctx.numG;
        const numD = ctx.numD;
        const numM = ctx.numM;
        const numS = ctx.numS;

        if (pos === 'g' && numG + 1 > Number(options.lineup_max_g)) {
            return false;
        }
        if (pos === 'd' && numD + 1 > Number(options.lineup_max_d)) {
            return false;
        }
        if (pos === 'm' && numM + 1 > Number(options.lineup_max_m)) {
            return false;
        }
        if (pos === 's' && numS + 1 > Number(options.lineup_max_s)) {
            return false;
        }

        if (pos === 'g') {
            if (
                Number(options.lineup_min_d) - numD > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                return false;
            }
        }
        if (pos === 'd') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                return false;
            }
        }
        if (pos === 'm') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_d) - numD > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                return false;
            }
        }
        if (pos === 's') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_d) - numD > left - 1
            ) {
                return false;
            }
        }

        return true;
    }

    function hintEntryUsable(entry, ctx, requireFieldPosition, pos) {
        if (!entry) {
            return false;
        }
        if (ctx.selectedIds[String(entry.playerteam_id)]) {
            return false;
        }
        if (!priceFitsBudget(entry.price, ctx)) {
            return false;
        }
        if (requireFieldPosition && !fieldPositionIsAllowed(pos, ctx)) {
            return false;
        }
        return true;
    }

    function teamHasUsableHint(teamId, ctx) {
        const hint = teamSelectHints[String(teamId)];
        if (!hint) {
            return null;
        }

        if (hint.min_player_price != null && !priceFitsBudget(hint.min_player_price, ctx)) {
            return false;
        }

        const byPos = hint.cheapest_by_position || {};
        const positions = ['g', 'd', 'm', 's'];
        const requireField = !ctx.addingToBench;
        let sawEntry = false;
        let sawUnselectedEntry = false;

        for (let i = 0; i < positions.length; i++) {
            const pos = positions[i];
            const entry = byPos[pos];
            if (!entry) {
                continue;
            }
            sawEntry = true;
            // Cheapest already picked — other squad players may still be usable.
            if (ctx.selectedIds[String(entry.playerteam_id)]) {
                continue;
            }
            sawUnselectedEntry = true;
            if (hintEntryUsable(entry, ctx, requireField, pos)) {
                return true;
            }
        }

        if (!sawEntry || !sawUnselectedEntry) {
            return null;
        }

        return false;
    }

    function teamIsBlocked(teamId, ctx) {
        if (!options) {
            return false;
        }
        ctx = ctx || lineupSelectionContext();

        if (ctx.addingToBench) {
            if (!benchEnabled() || benchlist.length >= maxBenchAllowed()) {
                return true;
            }
        }

        const teamCount = ctx.teamCounts[String(teamId)] || 0;
        if (teamCount >= Number(options.lineup_max_players_team)) {
            return true;
        }

        const cached = playerCache[teamId];
        if (cached && cached.length) {
            for (let i = 0; i < cached.length; i++) {
                if (!lineupBlockReason(cached[i], ctx)) {
                    return false;
                }
            }
            return true;
        }

        const hintUsable = teamHasUsableHint(teamId, ctx);
        if (hintUsable === false) {
            return true;
        }

        return false;
    }

    function teamBlockReason(teamId, teamName, ctx) {
        if (!options) {
            return 'Von diesem Team kannst du aktuell keine weiteren Spieler aufstellen.';
        }
        ctx = ctx || lineupSelectionContext();

        if (lineupFullyFilled()) {
            return 'Deine Aufstellung ist komplett. Speichern nicht vergessen!';
        }

        if (ctx.addingToBench) {
            if (!benchEnabled()) {
                return 'Du hast bereits ' + ctx.maxPlayers + ' Spieler aufgestellt!';
            }
            if (benchlist.length >= maxBenchAllowed()) {
                return 'Du hast bereits ' + maxBenchAllowed() + ' Ersatzspieler aufgestellt!';
            }
        }

        const teamCount = ctx.teamCounts[String(teamId)] || 0;
        if (teamCount >= Number(options.lineup_max_players_team)) {
            return (
                'Du hast bereits ' +
                options.lineup_max_players_team +
                ' Spieler von ' +
                teamName +
                ' aufgestellt!'
            );
        }

        const hint = teamSelectHints[String(teamId)];
        if (hint && hint.min_player_price != null && !priceFitsBudget(hint.min_player_price, ctx)) {
            return 'Du hast zu wenig Credits um Spieler aus diesem Team zu kaufen!';
        }

        return 'Von diesem Team kannst du aktuell keine weiteren Spieler aufstellen.';
    }

    function formatLineupCenter(match) {
        if (window.FfbMatchList) {
            if (window.FfbMatchList.hasPenaltyScore(match) || window.FfbMatchList.hasResultScore(match)) {
                return window.FfbMatchList.formatScore(match);
            }
        }
        return (
            '<span class="lineup-match-prices">' +
            '<span class="lineup-match-price home">' +
            escapeHtml(formatTeamPrice(match.match_hometeam_price)) +
            '</span>' +
            '<img class="lineup-credits-icon" src="' +
            symbolUrl('symbol_credits.svg') +
            '" width="16" height="16" alt="Credits">' +
            '<span class="lineup-match-price away">' +
            escapeHtml(formatTeamPrice(match.match_guestteam_price)) +
            '</span>' +
            '</span>'
        );
    }

    function teamTileHtml(side, match, ctx) {
        const isHome = side === 'home';
        const teamId = isHome ? match.match_hometeam_id : match.match_guestteam_id;
        const name = isHome ? match.match_hometeam_name : match.match_guestteam_name;
        const nat = isHome ? match.match_hometeam_nationality : match.match_guestteam_nationality;
        const isActive =
            Number(expandedTeamId) === Number(teamId) && Number(expandedMatchId) === Number(match.match_id);
        const blocked = teamIsBlocked(teamId, ctx);
        const flag = flagHtml(nat);
        const nameHtml = '<span class="lineup-tile-name">' + escapeHtml(name) + '</span>';
        const inner = isHome ? nameHtml + flag : flag + nameHtml;
        const title = blocked ? teamBlockReason(teamId, name, ctx) : 'Spieler anzeigen';

        return (
            '<button type="button" class="lineup-team-tile ' +
            side +
            (isActive ? ' is-active' : '') +
            (blocked ? ' is-blocked' : '') +
            '" data-select-team="' +
            escapeHtml(teamId) +
            '" data-match-id="' +
            escapeHtml(match.match_id) +
            '" title="' +
            escapeHtml(title) +
            '">' +
            inner +
            '</button>'
        );
    }

    function matchesForDisplay() {
        if (!expandedMatchId || expandedTeamId <= 0) {
            return matches.slice();
        }
        const pinned = [];
        const rest = [];
        matches.forEach(function (match) {
            if (Number(match.match_id) === Number(expandedMatchId)) {
                pinned.push(match);
            } else {
                rest.push(match);
            }
        });
        return pinned.concat(rest);
    }

    function renderLineupMatches() {
        if (!matches.length) {
            matchlistEl.innerHTML =
                '<p class="lineup-pick-hint">Klick auf ein Team um Spieler auszuwählen</p>' +
                '<p class="muted">Keine Spiele.</p>';
            return;
        }

        const hint = document.createElement('p');
        hint.className = 'lineup-pick-hint';
        hint.textContent = 'Klick auf ein Team um Spieler auszuwählen';

        const ctx = options ? lineupSelectionContext() : null;
        const ul = document.createElement('ul');
        ul.className = 'match-list lineup-match-list';
        matchesForDisplay().forEach(function (match) {
            const li = document.createElement('li');
            li.className = 'lineup-match';
            li.setAttribute('data-match-id', String(match.match_id));
            const isExpanded =
                Number(expandedMatchId) === Number(match.match_id) && expandedTeamId > 0;
            li.innerHTML =
                '<div class="match-list-row">' +
                teamTileHtml('home', match, ctx) +
                '<span class="score"><a class="nolink under" href="#" data-modal="match" data-id="' +
                escapeHtml(match.match_id) +
                '" title="Klicken für Matchinfos">' +
                formatLineupCenter(match) +
                '</a></span>' +
                teamTileHtml('away', match, ctx) +
                '</div>' +
                '<div class="lineup-match-players playerlist' +
                (showRecentPerformance ? ' playerlist--with-perf' : '') +
                '"' +
                (isExpanded ? '' : ' hidden') +
                ' data-players-for="' +
                escapeHtml(match.match_id) +
                '"></div>';
            ul.appendChild(li);
        });
        matchlistEl.innerHTML = '';
        matchlistEl.appendChild(hint);
        matchlistEl.appendChild(ul);

        if (expandedTeamId > 0 && expandedMatchId > 0) {
            const panel = matchlistEl.querySelector(
                '.lineup-match-players[data-players-for="' + expandedMatchId + '"]'
            );
            if (panel) {
                fillPlayersPanel(panel, expandedTeamId);
            }
        }
    }

    function syncTeamTileStates() {
        if (!matchlistEl || !options) {
            return;
        }
        const ctx = lineupSelectionContext();
        matchlistEl.querySelectorAll('.lineup-team-tile[data-select-team]').forEach(function (btn) {
            const teamId = btn.getAttribute('data-select-team');
            const matchId = btn.getAttribute('data-match-id');
            const match = matches.find(function (m) {
                return Number(m.match_id) === Number(matchId);
            });
            let teamName = '';
            if (match) {
                teamName =
                    Number(match.match_hometeam_id) === Number(teamId)
                        ? match.match_hometeam_name
                        : match.match_guestteam_name;
            }
            const blocked = teamIsBlocked(teamId, ctx);
            const isActive =
                Number(expandedTeamId) === Number(teamId) &&
                Number(expandedMatchId) === Number(matchId);
            btn.classList.toggle('is-blocked', blocked);
            btn.classList.toggle('is-active', isActive);
            btn.title = blocked ? teamBlockReason(teamId, teamName, ctx) : 'Spieler anzeigen';
        });
    }

    function selectedTeamNationality(teamId, matchId) {
        const match = matches.find(function (m) {
            return Number(m.match_id) === Number(matchId);
        });
        if (!match) {
            return '';
        }
        if (Number(match.match_hometeam_id) === Number(teamId)) {
            return match.match_hometeam_nationality || '';
        }
        if (Number(match.match_guestteam_id) === Number(teamId)) {
            return match.match_guestteam_nationality || '';
        }
        return '';
    }

    function lineupSelectionContext() {
        const maxPlayers = Number(options.lineup_max_players);
        const selectedIds = {};
        const teamCounts = {};
        let numG = 0;
        let numD = 0;
        let numM = 0;
        let numS = 0;

        lineuplist.forEach(function (p) {
            selectedIds[String(p.playerteam_id)] = true;
            const teamId = String(p.playerteam_team_id);
            teamCounts[teamId] = (teamCounts[teamId] || 0) + 1;
            const pos = p.playerteam_player_position;
            if (pos === 'g') {
                numG++;
            } else if (pos === 'd') {
                numD++;
            } else if (pos === 'm') {
                numM++;
            } else if (pos === 's') {
                numS++;
            }
        });
        benchlist.forEach(function (p) {
            selectedIds[String(p.playerteam_id)] = true;
            const teamId = String(p.playerteam_team_id);
            teamCounts[teamId] = (teamCounts[teamId] || 0) + 1;
        });

        return {
            maxPlayers: maxPlayers,
            addingToBench: lineuplist.length >= maxPlayers,
            left: maxPlayers - lineuplist.length,
            selectedIds: selectedIds,
            teamCounts: teamCounts,
            numG: numG,
            numD: numD,
            numM: numM,
            numS: numS,
            credits: credits,
        };
    }

    /**
     * Same rules as checkLineup, but returns the error text (or null if allowed).
     */
    function lineupBlockReason(player, ctx) {
        if (!options || !player) {
            return null;
        }
        ctx = ctx || lineupSelectionContext();
        const maxPlayers = ctx.maxPlayers;

        if (lineupFullyFilled()) {
            return 'Deine Aufstellung ist komplett. Speichern nicht vergessen!';
        }

        if (ctx.addingToBench) {
            if (!benchEnabled()) {
                return 'Du hast bereits ' + maxPlayers + ' Spieler aufgestellt!';
            }
            if (benchlist.length + 1 > maxBenchAllowed()) {
                return 'Du hast bereits ' + maxBenchAllowed() + ' Ersatzspieler aufgestellt!';
            }
        }

        const price = Number(
            player.playerteam_player_price != null
                ? player.playerteam_player_price
                : player.player_price
        );
        if (Math.round((ctx.credits - price) * 10) / 10 < 0) {
            return 'Du hast zuwenig Credits um diesen Spieler zu kaufen!';
        }

        if (ctx.selectedIds[String(player.playerteam_id)]) {
            return 'Dieser Spieler befindet sich bereits in deiner Aufstellung!';
        }

        const teamCount = ctx.teamCounts[String(player.playerteam_team_id)] || 0;
        if (teamCount + 1 > Number(options.lineup_max_players_team)) {
            return (
                'Du hast bereits ' +
                options.lineup_max_players_team +
                ' Spieler von ' +
                player.playerteam_team +
                ' aufgestellt!'
            );
        }

        if (ctx.addingToBench) {
            return null;
        }

        const left = ctx.left;
        const numG = ctx.numG;
        const numD = ctx.numD;
        const numM = ctx.numM;
        const numS = ctx.numS;
        const pos = player.player_position || player.playerteam_player_position;

        if (pos === 'g' && numG + 1 > Number(options.lineup_max_g)) {
            return 'Du hast bereits ' + options.lineup_max_g + ' Spieler im Tor!';
        }
        if (pos === 'd' && numD + 1 > Number(options.lineup_max_d)) {
            return 'Du hast bereits ' + options.lineup_max_d + ' Spieler in der Verteidigung!';
        }
        if (pos === 'm' && numM + 1 > Number(options.lineup_max_m)) {
            return 'Du hast bereits ' + options.lineup_max_m + ' Spieler im Mittelfeld!';
        }
        if (pos === 's' && numS + 1 > Number(options.lineup_max_s)) {
            return 'Du hast bereits ' + options.lineup_max_s + ' Spieler im Sturm!';
        }

        if (pos === 'g') {
            if (
                Number(options.lineup_min_d) - numD > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                return 'Du benötigst noch Spieler an anderen Positionen!';
            }
        }
        if (pos === 'd') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                return 'Du benötigst noch Spieler an anderen Positionen!';
            }
        }
        if (pos === 'm') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_d) - numD > left - 1 ||
                Number(options.lineup_min_s) - numS > left - 1
            ) {
                return 'Du benötigst noch Spieler an anderen Positionen!';
            }
        }
        if (pos === 's') {
            if (
                Number(options.lineup_min_g) - numG > left - 1 ||
                Number(options.lineup_min_m) - numM > left - 1 ||
                Number(options.lineup_min_d) - numD > left - 1
            ) {
                return 'Du benötigst noch Spieler an anderen Positionen!';
            }
        }

        return null;
    }

    function playerListHtml(players, teamNationality) {
        const flag = flagHtml(teamNationality);
        const ctx = options ? lineupSelectionContext() : null;
        let html = showRecentPerformance
            ? '<div class="playerlist-head"><span class="playerlist-team-flag">' +
              flag +
              '</span><span></span><span>Name</span><span>Preis</span><span>Leistung</span></div>'
            : '<div class="playerlist-head"><span class="playerlist-team-flag">' +
              flag +
              '</span><span>Name</span><span>Preis</span></div>';
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
                    const reason = ctx ? lineupBlockReason(p, ctx) : null;
                    const blocked = !!reason;
                    const inLineup = !!(ctx && ctx.selectedIds[String(p.playerteam_id)]);
                    const lineClasses = ['playerline'];
                    if (blocked) {
                        lineClasses.push('is-blocked');
                    }
                    if (inLineup) {
                        lineClasses.push('is-in-lineup');
                    }
                    const nameClasses = [];
                    if (blocked) {
                        nameClasses.push('is-blocked');
                    }
                    if (inLineup) {
                        nameClasses.push('is-in-lineup');
                    }
                    html +=
                        '<div class="' +
                        lineClasses.join(' ') +
                        '">' +
                        '<span class="info">' +
                        playerInfoLinkHtml(p) +
                        '</span>';
                    if (showRecentPerformance) {
                        html +=
                            '<span class="trend">' +
                            buildPerformanceTrend(p.recent_performance) +
                            '</span>';
                    }
                    html +=
                        '<span class="name"><a href="#" data-add="' +
                        p.playerteam_id +
                        '"' +
                        (nameClasses.length ? ' class="' + nameClasses.join(' ') + '"' : '') +
                        (blocked
                            ? ' aria-disabled="true" title="' + escapeHtml(reason) + '"'
                            : '') +
                        '>' +
                        escapeHtml(p.player_fname + ' ' + p.player_lname) +
                        '</a></span>' +
                        '<span class="price">' +
                        escapeHtml(formatTeamPrice(p.playerteam_player_price)) +
                        '</span>';
                    if (showRecentPerformance) {
                        html +=
                            '<span class="grade">' +
                            buildPerformanceBar(p.recent_performance) +
                            '</span>';
                    }
                    html += '</div>';
                });
        });
        return html;
    }

    function refreshOpenPlayersPanel() {
        if (!expandedTeamId || !expandedMatchId || !matchlistEl) {
            syncTeamTileStates();
            return;
        }
        const panel = matchlistEl.querySelector(
            '.lineup-match-players[data-players-for="' + expandedMatchId + '"]'
        );
        const cached = playerCache[expandedTeamId];
        if (!panel || panel.hidden || !cached) {
            syncTeamTileStates();
            return;
        }
        const byId = panel._playersById || {};
        panel.innerHTML = playerListHtml(cached, selectedTeamNationality(expandedTeamId, expandedMatchId));
        panel._playersById = byId;
        if (!panel._playersById || Object.keys(panel._playersById).length === 0) {
            panel._playersById = {};
            cached.forEach(function (p) {
                panel._playersById[String(p.playerteam_id)] = p;
            });
        }
        syncTeamTileStates();
    }

    function fillPlayersPanel(panel, teamId) {
        const nat = selectedTeamNationality(teamId, expandedMatchId);
        const cached = playerCache[teamId];
        if (cached) {
            panel.innerHTML = playerListHtml(cached, nat);
            panel._playersById = {};
            cached.forEach(function (p) {
                panel._playersById[String(p.playerteam_id)] = p;
            });
            panel.hidden = false;
            syncTeamTileStates();
            return Promise.resolve();
        }

        panel.hidden = false;
        panel.innerHTML = '<p class="muted">Lade Spielerliste…</p>';
        panel._playersById = {};

        return fetchJson(
            'lineup/teams/' +
                encodeURIComponent(teamId) +
                '/players?matchround_id=' +
                encodeURIComponent(matchround.matchround_id)
        )
            .then(function (j) {
                return j.data;
            })
            .then(function (data) {
                const players = data.players || [];
                playerCache[teamId] = players;
                if (Number(expandedTeamId) !== Number(teamId)) {
                    syncTeamTileStates();
                    return;
                }
                panel.innerHTML = playerListHtml(players, nat);
                panel._playersById = {};
                players.forEach(function (p) {
                    panel._playersById[String(p.playerteam_id)] = p;
                });
                syncTeamTileStates();
            })
            .catch(function (err) {
                if (Number(expandedTeamId) !== Number(teamId)) {
                    return;
                }
                panel.innerHTML =
                    '<p class="muted">' +
                    escapeHtml((err && err.message) || 'Spieler konnten nicht geladen werden.') +
                    '</p>';
            });
    }

    async function selectTeam(teamId, matchId) {
        teamId = Number(teamId) || 0;
        matchId = Number(matchId) || 0;
        if (teamId <= 0 || matchId <= 0 || !matchround) {
            return;
        }

        if (Number(expandedTeamId) === teamId && Number(expandedMatchId) === matchId) {
            expandedTeamId = 0;
            expandedMatchId = 0;
            renderLineupMatches();
            return;
        }

        expandedTeamId = teamId;
        expandedMatchId = matchId;
        renderLineupMatches();
        const listTop = matchlistEl;
        if (listTop && typeof listTop.scrollIntoView === 'function') {
            listTop.scrollIntoView({ block: 'start', behavior: 'smooth' });
        }
    }

    function blankSlot(red, label) {
        const shirt =
            window.FfbShirts && typeof window.FfbShirts.blankImg === 'function'
                ? window.FfbShirts.blankImg(legacyBase, red, 'width="55" height="50" alt=""')
                : '<img class="shirt" src="' +
                  legacyBase +
                  'images/ffb/shirts/' +
                  (red ? 'shirt_BLANK_RED.png' : 'shirt_BLANK.svg') +
                  '" width="55" height="50" alt="">';
        return (
            '<div class="pitch-player pitch-slot">' +
            shirt +
            '<span class="name">' +
            label +
            '</span></div>'
        );
    }

    function playerCard(player) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamId = player.playerteam_team_id;
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
            playerInfoLinkHtml(player) +
            '</div></div>'
        );
    }

    function positionLabels() {
        return { g: 'TOR', d: 'VERTEIDIGUNG', m: 'MITTELFELD', s: 'STURM' };
    }

    function positionMins() {
        return {
            g: Number(options.lineup_min_g),
            d: Number(options.lineup_min_d),
            m: Number(options.lineup_min_m),
            s: Number(options.lineup_min_s),
        };
    }

    function positionMaxs() {
        return {
            g: Number(options.lineup_max_g),
            d: Number(options.lineup_max_d),
            m: Number(options.lineup_max_m),
            s: Number(options.lineup_max_s),
        };
    }

    function maxFieldPlayers() {
        return Number(options.lineup_max_players);
    }

    function fieldIsComplete() {
        return lineuplist.length >= maxFieldPlayers();
    }

    function playersAtPosition(pos) {
        return lineuplist.filter(function (player) {
            return player.playerteam_player_position === pos;
        });
    }

    function countAtPosition(pos) {
        return playersAtPosition(pos).length;
    }

    function blankHtmlForPosition(pos) {
        if (fieldIsComplete()) {
            return '';
        }
        const labels = positionLabels();
        const mins = positionMins();
        const maxs = positionMaxs();
        const count = countAtPosition(pos);
        const needRed = Math.max(0, mins[pos] - count);
        const needBlank = Math.max(0, maxs[pos] - needRed - count);
        let html = '';
        for (let i = 0; i < needRed; i++) {
            html += blankSlot(true, labels[pos]);
        }
        for (let i = 0; i < needBlank; i++) {
            html += blankSlot(false, labels[pos]);
        }
        return html;
    }

    function renderPositionRow(pos) {
        const line = lines[pos];
        if (!line) {
            return;
        }
        line.innerHTML = playersAtPosition(pos).map(playerCard).join('') + blankHtmlForPosition(pos);
    }

    function refreshRowBlanks(pos) {
        const line = lines[pos];
        if (!line) {
            return;
        }
        line.querySelectorAll('.pitch-slot').forEach(function (el) {
            el.remove();
        });
        const blanks = blankHtmlForPosition(pos);
        if (blanks) {
            line.insertAdjacentHTML('beforeend', blanks);
        }
    }

    function findFieldPlayerEl(playerteamId) {
        const id = String(playerteamId);
        const field = document.getElementById('soccer-field');
        if (!field) {
            return null;
        }
        const link = field.querySelector('.pitch-player:not(.pitch-slot) a[data-remove="' + id + '"]');
        return link ? link.closest('.pitch-player') : null;
    }

    function replaceFirstBlank(pos, player) {
        const line = lines[pos];
        if (!line) {
            return false;
        }
        const blank = line.querySelector('.pitch-slot');
        if (!blank) {
            return false;
        }
        const tmp = document.createElement('div');
        tmp.innerHTML = playerCard(player);
        const card = tmp.firstElementChild;
        if (!card) {
            return false;
        }
        blank.replaceWith(card);
        return true;
    }

    function syncBenchHeight() {
        if (window.FfbPitchBench) {
            window.FfbPitchBench.matchBenchToField();
        }
    }

    function updateFieldDisplay() {
        if (!options) {
            return;
        }
        Object.keys(lines).forEach(function (pos) {
            renderPositionRow(pos);
        });
        syncBenchHeight();
    }

    /**
     * Patch field after adding a starter. While placeholders are visible, only the
     * affected row is touched (blank → player). Filling the XI strips blanks on all rows.
     */
    function patchFieldAfterAdd(player) {
        const pos = player.playerteam_player_position;
        if (fieldIsComplete()) {
            if (!replaceFirstBlank(pos, player)) {
                renderPositionRow(pos);
            }
            Object.keys(lines).forEach(function (rowPos) {
                lines[rowPos].querySelectorAll('.pitch-slot').forEach(function (el) {
                    el.remove();
                });
            });
            syncBenchHeight();
            return;
        }
        if (!replaceFirstBlank(pos, player)) {
            renderPositionRow(pos);
        }
        syncBenchHeight();
    }

    /**
     * Patch field after removing a starter. Incomplete XI: drop that card and refresh
     * blanks on that row only. Leaving a full XI: restore blanks on every row.
     */
    function patchFieldAfterRemove(playerteamId, pos, wasComplete) {
        const card = findFieldPlayerEl(playerteamId);
        if (card) {
            card.remove();
        } else {
            renderPositionRow(pos);
        }

        if (wasComplete) {
            Object.keys(lines).forEach(function (rowPos) {
                refreshRowBlanks(rowPos);
            });
        } else if (!card) {
            // row already rebuilt above
        } else {
            refreshRowBlanks(pos);
        }
        syncBenchHeight();
    }

    function updateBenchDisplay() {
        if (!window.FfbPitchBench) {
            return;
        }
        window.FfbPitchBench.sync(options, legacyBase, benchlist);
    }

    function updateLineupDisplay() {
        updateFieldDisplay();
        updateBenchDisplay();
    }

    function benchEnabled() {
        return !!(
            options &&
            options.league_benchmode &&
            Number(options.lineup_max_bench) > 0
        );
    }

    function minBenchRequired() {
        if (!benchEnabled()) {
            return 0;
        }

        return Math.max(0, Number(options.lineup_min_bench) || 0);
    }

    function maxBenchAllowed() {
        if (!benchEnabled()) {
            return 0;
        }

        return Math.max(0, Number(options.lineup_max_bench) || 0);
    }

    function lineupFullyFilled() {
        if (!options) {
            return false;
        }
        if (lineuplist.length < Number(options.lineup_max_players)) {
            return false;
        }
        if (!benchEnabled()) {
            return true;
        }

        return benchlist.length >= maxBenchAllowed();
    }

    function mapPlayerRecord(player) {
        return {
            player_id: player.player_id,
            player_fname: player.player_fname,
            player_lname: player.player_lname,
            player_nationality: player.player_nationality,
            player_status: player.player_status,
            player_status_description: player.player_status_description,
            playerteam_player_position:
                player.playerteam_player_position || player.player_position,
            player_price: Number(
                player.playerteam_player_price != null
                    ? player.playerteam_player_price
                    : player.player_price
            ),
            playerteam_team_id: player.playerteam_team_id,
            playerteam_team: player.playerteam_team,
            playerteam_team_nationality: player.playerteam_team_nationality,
            playerteam_id: player.playerteam_id,
            playerteam_status:
                player.playerteam_status == null ? 1 : Number(player.playerteam_status) ? 1 : 0,
            playerteam_player_note: player.playerteam_player_note || '',
            card_warning: player.card_warning || null,
        };
    }

    function updateCreditsDisplay() {
        if (!options) {
            return;
        }
        creditsEl.hidden = false;
        const rounded = Math.round(credits * 10) / 10;
        creditsEl.classList.toggle('is-over', rounded < 0);
        const neededStarters = Number(options.lineup_max_players) - lineuplist.length;
        const neededBench = Math.max(0, minBenchRequired() - benchlist.length);
        let html =
            '<div class="pitch-stats-row"><img src="' +
            symbolUrl('symbol_credits.svg') +
            '" alt=""><span>' +
            rounded +
            '</span></div>';
        if (neededStarters > 0) {
            html += '<div>noch <b>' + neededStarters + '</b> Spieler</div>';
        } else if (neededBench > 0) {
            html += '<div>noch <b>' + neededBench + '</b> Ersatz</div>';
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
        const startersComplete =
            options && lineuplist.length === Number(options.lineup_max_players);
        const benchComplete =
            benchlist.length >= minBenchRequired() &&
            benchlist.length <= maxBenchAllowed();
        const withinBudget = Math.round(credits * 10) / 10 >= 0;
        if (startersComplete && benchComplete && withinBudget && lineupDirty) {
            actionsEl.innerHTML =
                '<button type="button" class="ffb-button" id="save-lineup-btn">Aufstellung speichern</button>';
        } else {
            actionsEl.innerHTML =
                '<button type="button" class="ffb-button-disabled" disabled>Aufstellung speichern</button>';
        }
    }

    function checkLineup(player) {
        const reason = lineupBlockReason(player);
        if (reason) {
            addErrorMessage(reason);
            return false;
        }
        return true;
    }

    function addPlayer(player) {
        clearMessages();
        if (!checkLineup(player)) {
            return;
        }
        const record = mapPlayerRecord(player);
        const toBench = lineuplist.length >= maxFieldPlayers();
        if (toBench) {
            benchlist.push(record);
            updateBenchDisplay();
        } else {
            lineuplist.push(record);
            patchFieldAfterAdd(record);
        }
        credits -= record.player_price;
        updateCreditsDisplay();
        setLineupDirty(true);
        dispActionButtons();
        refreshOpenPlayersPanel();
    }

    function removePlayer(playerteamId) {
        clearMessages();
        const wasComplete = fieldIsComplete();
        let removed = null;
        for (let i = 0; i < lineuplist.length; i++) {
            if (String(lineuplist[i].playerteam_id) === String(playerteamId)) {
                removed = lineuplist[i];
                credits += Number(lineuplist[i].player_price);
                lineuplist.splice(i, 1);
                break;
            }
        }
        if (removed) {
            patchFieldAfterRemove(
                removed.playerteam_id,
                removed.playerteam_player_position,
                wasComplete
            );
        }
        updateCreditsDisplay();
        setLineupDirty(true);
        dispActionButtons();
        refreshOpenPlayersPanel();
    }

    function removeBenchPlayer(playerteamId) {
        clearMessages();
        for (let i = 0; i < benchlist.length; i++) {
            if (String(benchlist[i].playerteam_id) === String(playerteamId)) {
                credits += Number(benchlist[i].player_price);
                benchlist.splice(i, 1);
                break;
            }
        }
        updateBenchDisplay();
        updateCreditsDisplay();
        setLineupDirty(true);
        dispActionButtons();
        refreshOpenPlayersPanel();
    }

    async function saveLineup() {
        clearMessages();
        dispActionButtons(true);
        const ids = lineuplist.map(function (p) {
            return p.playerteam_id;
        });
        const substituteIds = benchlist.map(function (p) {
            return p.playerteam_id;
        });
        try {
            const json = await fetchJson('lineup', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({
                    matchround_id: matchround.matchround_id,
                    playerteam_ids: ids,
                    substitute_playerteam_ids: substituteIds,
                }),
            });
            setLineupDirty(false);
            addOkMessage(json.message || 'Deine Aufstellung wurde gespeichert!');
        } catch (err) {
            addErrorMessage(escapeHtml((err && err.message) || 'Speichern fehlgeschlagen'));
        }
        dispActionButtons();
    }

    async function loadExistingLineup() {
        credits = Number(options.lineup_max_credits);
        lineuplist = [];
        benchlist = [];
        try {
            const data = await fetchJson('lineup?matchround_id=' + encodeURIComponent(matchround.matchround_id)).then(
                function (j) {
                    return j.data;
                }
            );
            if (!data.userteam || !(data.players || []).length) {
                updateLineupDisplay();
                updateCreditsDisplay();
                setLineupDirty(false);
                dispActionButtons();
                refreshOpenPlayersPanel();
                return;
            }
            credits -= Number(data.userteam.userteam_price || 0);
            lineuplist = (data.players || []).map(mapPlayerRecord);
            benchlist = (data.substitutes || []).map(mapPlayerRecord);
            updateLineupDisplay();
            updateCreditsDisplay();
            setLineupDirty(false);
            dispActionButtons();
            refreshOpenPlayersPanel();
        } catch (err) {
            updateLineupDisplay();
            updateCreditsDisplay();
            setLineupDirty(false);
            dispActionButtons();
            refreshOpenPlayersPanel();
            addErrorMessage(escapeHtml((err && err.message) || 'Aufstellung konnte nicht geladen werden.'));
        }
    }

    function blockUi(message) {
        clearPitch();
        setPitchMessage(message);
        creditsEl.hidden = true;
        expandedTeamId = 0;
        expandedMatchId = 0;
        matchlistEl.innerHTML = '';
        actionsEl.innerHTML = '';
    }

    function gameOverUi() {
        clearPitch();
        lines.m.innerHTML =
            '<img src="' + symbolUrl('gameover.svg') + '" width="320" alt="Game Over" style="max-width:100%;">';
        creditsEl.hidden = true;
        expandedTeamId = 0;
        expandedMatchId = 0;
        matchlistEl.innerHTML = '';
        actionsEl.innerHTML = '';
        roundMetaEl.textContent = '';
        if (window.FfbPitchBench) {
            window.FfbPitchBench.sync(null, legacyBase, []);
        }
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
            config.matchroundId = matchround ? Number(matchround.matchround_id) || 0 : 0;
            window.FFB_LINEUP = window.FFB_LINEUP || config;
            window.FFB_LINEUP.matchroundId = config.matchroundId;
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
            if (window.FfbPitchBench) {
                window.FfbPitchBench.sync(options, legacyBase, benchlist);
            }
            if (!matchround) {
                roundMetaEl.textContent = '';
                addErrorMessage('Keine weitere Spielrunde vorhanden! Bitte später nochmal probieren!');
                blockUi('');
                return;
            }

            matches = matchround.matches || [];
            teams = matchround.teams || [];
            teamSelectHints = {};
            teams.forEach(function (team) {
                teamSelectHints[String(team.team_id)] = {
                    min_player_price:
                        team.min_player_price != null ? Number(team.min_player_price) : null,
                    cheapest_by_position: team.cheapest_by_position || {
                        g: null,
                        d: null,
                        m: null,
                        s: null,
                    },
                };
            });
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

            renderLineupMatches();
            await loadExistingLineup();
        } catch (err) {
            addErrorMessage(escapeHtml((err && err.message) || 'Aufstellung konnte nicht geladen werden.'));
            blockUi('');
        }
    }

    matchlistEl.addEventListener('click', function (event) {
        const tile = event.target.closest('[data-select-team]');
        if (tile) {
            event.preventDefault();
            selectTeam(tile.getAttribute('data-select-team'), tile.getAttribute('data-match-id'));
            return;
        }

        const add = event.target.closest('[data-add]');
        if (!add) {
            return;
        }
        event.preventDefault();
        const id = add.getAttribute('data-add');
        const panel = add.closest('.lineup-match-players');
        const player = panel && panel._playersById && panel._playersById[id];
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

    const soccerBench = document.getElementById('soccer-bench');
    if (soccerBench) {
        soccerBench.addEventListener('click', function (event) {
            const rem = event.target.closest('[data-remove-bench]');
            if (!rem) {
                return;
            }
            event.preventDefault();
            removeBenchPlayer(rem.getAttribute('data-remove-bench'));
        });
    }

    actionsEl.addEventListener('click', function (event) {
        if (event.target.id === 'save-lineup-btn') {
            saveLineup();
        }
    });

    window.addEventListener('beforeunload', function (event) {
        if (!lineupDirty) {
            return;
        }
        const message =
            'Du hast ungespeicherte Änderungen an deiner Aufstellung. Seite wirklich verlassen?';
        event.preventDefault();
        event.returnValue = message;
        return message;
    });

    init();
})();
