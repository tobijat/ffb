(function () {
    const config = window.FFB_USERSCORE || {};
    const apiBase = config.apiBase || 'api';
    const legacyBase = config.legacyBase || '/';
    const userId = Number(config.userId || 0);
    const pageSize = Number(config.pageSize || 75);

    const tableEl = document.getElementById('userscore-table');
    const selectEl = document.getElementById('matchround_selection');
    const metaEl = document.getElementById('round-meta');
    const matchlistEl = document.getElementById('matchlist');

    let matchrounds = [];
    let selectedIndex = 0;
    let entries = [];
    let displayMode = 'points';
    let sortFlag = '';
    let sortDir = 'desc';
    let pageStart = 0;

    function apiUrl(path) {
        return apiBase.replace(/\/$/, '') + '/' + path.replace(/^\//, '');
    }

    async function fetchJson(path) {
        const response = await fetch(apiUrl(path), {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });
        const data = await response.json();
        if (!response.ok || data.status !== 200) {
            throw new Error((data && data.error) || 'Request failed');
        }
        return data.data;
    }

    function flagUrl(code) {
        const flag = (code && code !== '0' ? String(code) : 'aut').toLowerCase();
        return legacyBase + 'images/ffb/flags/' + flag + '.gif';
    }

    function currentRound() {
        return matchrounds[selectedIndex] || {
            matchround_id: 0,
            matchround_title: 'Gesamtrangliste',
            matchround_running: 0,
            matchround_future: 0,
            matches: [],
        };
    }

    function renderMeta() {
        const round = currentRound();
        let text = round.matchround_title || 'Rangliste';
        if (Number(round.matchround_running) === 1) {
            text += ' (aktuelle Runde)';
        } else if (Number(round.matchround_future) === 1) {
            text += ' (nächste Runde)';
        }
        if (round.matchround_id > 0 && round.matchround_startdate) {
            if (round.matchround_startdate === round.matchround_enddate) {
                text += ' · ' + round.matchround_startdate;
            } else {
                text += ' · ' + round.matchround_startdate + ' bis ' + round.matchround_enddate;
            }
        }
        metaEl.textContent = text;
    }

    function renderSelect() {
        selectEl.innerHTML = '';
        matchrounds.forEach(function (round, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = round.matchround_title;
            if (index === selectedIndex) {
                opt.selected = true;
            }
            selectEl.appendChild(opt);
        });
        selectEl.disabled = matchrounds.length === 0;
    }

    function renderMatches() {
        const round = currentRound();
        const matches = round.matches || [];
        if (!round.matchround_id) {
            matchlistEl.innerHTML = '<p class="muted">Gesamtrangliste — keine Einzelspiele.</p>';
            return;
        }
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
            const score = (match.match_homescore != null && match.match_guestscore != null)
                ? (match.match_homescore + ':' + match.match_guestscore)
                : '-:-';
            li.innerHTML =
                '<img src="' + homeFlag + '" alt="">' +
                '<span>' + escapeHtml(match.match_hometeam_name) + '</span>' +
                '<span class="score">' + score + '</span>' +
                '<span>' + escapeHtml(match.match_guestteam_name) +
                ' <img src="' + guestFlag + '" alt=""></span>';
            ul.appendChild(li);
        });
        matchlistEl.innerHTML = '';
        matchlistEl.appendChild(ul);
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function sortLink(flag, dir, title) {
        return '<a href="#" data-sort="' + flag + '" data-dir="' + dir + '" title="' + title + '">' +
            (dir === 'asc' ? '↑' : '↓') + '</a>';
    }

    function renderTable() {
        if (!entries.length) {
            tableEl.innerHTML = '<p class="muted">Noch keine Einträge.</p>';
            return;
        }

        const end = Math.min(pageStart + pageSize, entries.length);
        const pages = Math.ceil(entries.length / pageSize);
        const round = currentRound();
        const highlightPodium = Number(round.matchround_running) !== 1 && Number(round.matchround_future) !== 1;

        let pager = '';
        if (pages > 1) {
            pager = '<div class="userscore-sites">Seite ';
            for (let i = 0; i < pages; i++) {
                const start = i * pageSize;
                const active = start === pageStart ? ' is-active' : '';
                pager += '<button type="button" class="' + active.trim() + '" data-page-start="' + start + '">' + (i + 1) + '</button> ';
            }
            pager += '</div>';
        }

        const scoreHeader = displayMode === 'wc'
            ? '<th class="col-score"><b>WeltCup</b> ' + sortLink('wc', 'asc', 'Aufsteigend') + sortLink('wc', 'desc', 'Absteigend') + '</th>'
            : '<th class="col-score"><b>Punkte</b> ' + sortLink('po', 'asc', 'Aufsteigend') + sortLink('po', 'desc', 'Absteigend') + '</th>';

        let html = pager;
        html += '<table class="userscore-table"><thead><tr>';
        html += '<th class="col-rank"><b>Rang</b> ' + sortLink('r', 'asc', 'Aufsteigend') + sortLink('r', 'desc', 'Absteigend') + '</th>';
        html += '<th class="col-flag"></th>';
        html += '<th><b>Name</b> ' + sortLink('n', 'asc', 'Aufsteigend') + sortLink('n', 'desc', 'Absteigend') + '</th>';
        html += '<th class="col-part"><b>Teiln.</b> ' + sortLink('p', 'asc', 'Aufsteigend') + sortLink('p', 'desc', 'Absteigend') + '</th>';
        html += '<th class="col-wins"><b>Siege</b> ' + sortLink('w', 'asc', 'Aufsteigend') + sortLink('w', 'desc', 'Absteigend') + '</th>';
        html += scoreHeader;
        html += '</tr></thead><tbody>';

        for (let i = pageStart; i < end; i++) {
            const row = entries[i];
            const pointsSum = displayMode === 'wc' ? Number(row.user_wc_points) : Number(row.user_score);
            let cls = i % 2 === 0 ? 'row-even' : 'row-odd';
            if (Number(row.user_id) === userId) {
                cls += ' is-me';
            }
            if (highlightPodium && pointsSum > 0 && Number(row.user_rank) <= 3) {
                cls = 'rank-' + Number(row.user_rank);
                if (Number(row.user_id) === userId) {
                    cls += ' is-me';
                }
            }

            const scoreCell = displayMode === 'wc'
                ? '<span class="subscore">(Punkte: ' + row.user_score + ')</span><b>' + row.user_wc_points + '</b>'
                : '<span class="subscore">(WeltCup: ' + row.user_wc_points + ')</span><b>' + row.user_score + '</b>';

            html += '<tr class="' + cls.trim() + '">';
            html += '<td class="col-rank">' + row.user_rank + '</td>';
            html += '<td class="col-flag"><img src="' + flagUrl(row.user_favourite_team_nationality) + '" alt="" width="16" height="11"></td>';
            html += '<td><a class="nolink" href="' + legacyBase + 'ffb/user/getUserDetails.html?user_id=' + row.user_id + '">' +
                escapeHtml(row.user_nickname) + '</a></td>';
            html += '<td class="col-part">' + row.participations + '</td>';
            html += '<td class="col-wins">' + row.matchround_wins + '</td>';
            html += '<td class="col-score">' + scoreCell + '</td>';
            html += '</tr>';
        }

        html += '</tbody></table>' + pager;
        tableEl.innerHTML = html;
    }

    async function loadRanking() {
        tableEl.innerHTML = '<p class="muted">Lade Rangliste…</p>';
        selectEl.disabled = true;
        const round = currentRound();
        const q = [];
        if (sortFlag) {
            q.push('sort=' + encodeURIComponent(sortFlag));
        }
        if (sortDir) {
            q.push('dir=' + encodeURIComponent(sortDir));
        }
        const qs = q.length ? ('?' + q.join('&')) : '';

        try {
            let data;
            if (Number(round.matchround_id) > 0) {
                data = await fetchJson('userscore/rounds/' + round.matchround_id + qs);
            } else {
                data = await fetchJson('userscore' + qs);
            }
            entries = data.entries || [];
            displayMode = data.display_mode === 'wc' ? 'wc' : 'points';
            pageStart = 0;
            renderTable();
        } catch (err) {
            tableEl.innerHTML = '<p class="muted">Rangliste konnte nicht geladen werden.</p>';
        } finally {
            selectEl.disabled = matchrounds.length === 0;
        }
    }

    async function init() {
        try {
            const data = await fetchJson('userscore/matchrounds');
            const rounds = data.matchrounds || [];
            matchrounds = [{
                matchround_id: 0,
                matchround_title: 'Gesamtrangliste',
                matchround_actual: 1,
                matchround_running: 0,
                matchround_future: 0,
                matchround_startdate: rounds[0] ? rounds[0].matchround_startdate : '',
                matchround_enddate: rounds[0] ? rounds[0].matchround_enddate : '',
                matches: [],
            }].concat(rounds);
            selectedIndex = 0;
            renderSelect();
            renderMeta();
            renderMatches();
            await loadRanking();
        } catch (err) {
            selectEl.innerHTML = '<option>Keine Spielrunden</option>';
            tableEl.innerHTML = '<p class="muted">Noch keine Spielrunden gespielt.</p>';
            metaEl.textContent = '';
        }
    }

    selectEl.addEventListener('change', function () {
        selectedIndex = Number(selectEl.value) || 0;
        sortFlag = '';
        sortDir = 'desc';
        renderMeta();
        renderMatches();
        loadRanking();
    });

    tableEl.addEventListener('click', function (event) {
        const sortBtn = event.target.closest('[data-sort]');
        if (sortBtn) {
            event.preventDefault();
            sortFlag = sortBtn.getAttribute('data-sort') || '';
            // Map UI wc/po to server flags (legacy: default path for score cols)
            if (sortFlag === 'wc' || sortFlag === 'po') {
                sortFlag = '';
            }
            sortDir = sortBtn.getAttribute('data-dir') || 'desc';
            loadRanking();
            return;
        }
        const pageBtn = event.target.closest('[data-page-start]');
        if (pageBtn) {
            event.preventDefault();
            pageStart = Number(pageBtn.getAttribute('data-page-start')) || 0;
            renderTable();
        }
    });

    init();
})();
