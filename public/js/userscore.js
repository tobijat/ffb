(function () {
    const config = window.FFB_USERSCORE || {};
    const apiBase = config.apiBase || 'api';
    const legacyBase = config.legacyBase || '/';
    const userId = Number(config.userId || 0);
    const pageSize = Number(config.pageSize || 75);

    const tableEl = document.getElementById('userscore-table');
    const selectEl = document.getElementById('matchround_selection');
    const roundTitleEl = document.getElementById('round-title');
    const roundDatesEl = document.getElementById('round-dates');
    const matchlistEl = document.getElementById('matchlist');
    const sideEl = document.querySelector('.userscore-side');

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

    function flagHtml(code, title) {
        if (window.FfbFlags && typeof window.FfbFlags.html === 'function') {
            return window.FfbFlags.html(code, title ? { title: title } : undefined);
        }
        const flag = (code && code !== '0' ? String(code) : 'na').toLowerCase();
        const src = legacyBase + 'images/ffb/flags/' + flag + '.gif';
        const titleAttr = title ? ' title="' + escapeHtml(title) + '"' : '';
        return '<img class="ffb-flag ffb-flag-img" src="' + src + '" alt="" width="16" height="11" loading="lazy"' + titleAttr + '>';
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

    function renderRoundFace() {
        const round = currentRound();
        if (!round) {
            roundTitleEl.textContent = 'Keine Spielrunden';
            roundDatesEl.textContent = '';
            roundDatesEl.hidden = true;
            return;
        }

        let title = round.matchround_title || 'Rangliste';
        if (Number(round.matchround_running) === 1) {
            title += ' (aktuelle Runde)';
        } else if (Number(round.matchround_future) === 1) {
            title += ' (nächste Runde)';
        }
        roundTitleEl.textContent = title;

        if (Number(round.matchround_id) > 0 && round.matchround_startdate) {
            roundDatesEl.textContent =
                round.matchround_startdate === round.matchround_enddate
                    ? String(round.matchround_startdate)
                    : String(round.matchround_startdate) + ' - ' + String(round.matchround_enddate);
            roundDatesEl.hidden = false;
        } else {
            roundDatesEl.textContent = '';
            roundDatesEl.hidden = true;
        }
    }

    function renderSelect() {
        selectEl.innerHTML = '';
        if (!matchrounds.length) {
            selectEl.innerHTML = '<option>Keine Spielrunden</option>';
            selectEl.disabled = true;
            renderRoundFace();
            return;
        }
        matchrounds.forEach(function (round, index) {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = round.matchround_title;
            opt.className = 'ffb-select-' + (index % 2);
            if (index === selectedIndex) {
                opt.selected = true;
            }
            selectEl.appendChild(opt);
        });
        selectEl.disabled = false;
        renderRoundFace();
    }

    function renderMatches() {
        const round = currentRound();
        if (!round.matchround_id) {
            matchlistEl.innerHTML = '';
            if (sideEl) {
                sideEl.classList.add('is-empty');
            }
            return;
        }
        if (sideEl) {
            sideEl.classList.remove('is-empty');
        }
        const matches = round.matches || [];
        if (window.FfbMatchList) {
            window.FfbMatchList.render(matchlistEl, matches);
            return;
        }
        matchlistEl.innerHTML = '<p class="muted">Keine Spiele in dieser Runde.</p>';
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function activeSortKey() {
        if (sortFlag === '') {
            return displayMode === 'lc' ? 'lc' : 'po';
        }
        return sortFlag;
    }

    function thHead(label, flag) {
        const isActive = activeSortKey() === flag;
        const tri = isActive
            ? '<span class="sort-tri sort-' + sortDir + '" aria-hidden="true"></span>'
            : '';
        return '<button type="button" class="th-sort' + (isActive ? ' is-active' : '') +
            '" data-sort="' + flag + '" title="Sortieren" aria-label="' + label + ' sortieren">' +
            '<span class="th-label">' + label + '</span>' + tri + '</button>';
    }

    function thCell(cls, label, flag) {
        const isActive = activeSortKey() === flag;
        const ariaSort = isActive ? (sortDir === 'asc' ? 'ascending' : 'descending') : 'none';
        return '<th class="' + cls + '" aria-sort="' + ariaSort + '">' + thHead(label, flag) + '</th>';
    }

    function renderTable() {
        if (!entries.length) {
            tableEl.innerHTML = '<p class="muted">Keine Mitspieler in dieser Runde.</p>';
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

        let html = pager;
        html += '<table class="userscore-table"><thead><tr>';
        html += thCell('col-rank', 'Rang', 'r');
        html += thCell('col-name', 'Name', 'n');
        html += thCell('col-part', 'Runden', 'p');
        html += thCell('col-wins', 'Siege', 'w');
        html += displayMode === 'lc'
            ? thCell('col-score', 'LigaCup', 'lc')
            : thCell('col-score', 'Punkte', 'po');
        html += '</tr></thead><tbody>';

        for (let i = pageStart; i < end; i++) {
            const row = entries[i];
            const pointsSum = displayMode === 'lc' ? Number(row.user_lc_points) : Number(row.user_score);
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

            const scoreCell = displayMode === 'lc'
                ? '<span class="subscore">(Punkte: ' + row.user_score + ')</span><b>' + row.user_lc_points + '</b>'
                : '<span class="subscore">(LigaCup: ' + row.user_lc_points + ')</span><b>' + row.user_score + '</b>';

            html += '<tr class="' + cls.trim() + '">';
            html += '<td class="col-rank">' + row.user_rank + '</td>';
            html += '<td class="col-name"><span class="name-cell">' +
                flagHtml(row.user_favourite_team_nationality) +
                '<a class="nolink" href="#" data-modal="profile" data-id="' + row.user_id + '">' +
                escapeHtml(row.user_nickname) + '</a></span></td>';
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
            displayMode = data.display_mode === 'lc' ? 'lc' : 'points';
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
            renderMatches();
            await loadRanking();
        } catch (err) {
            selectEl.innerHTML = '<option>Keine Spielrunden</option>';
            selectEl.disabled = true;
            tableEl.innerHTML = '<p class="muted">Noch keine Spielrunden gespielt.</p>';
            roundTitleEl.textContent = 'Keine Spielrunden';
            roundDatesEl.textContent = '';
            roundDatesEl.hidden = true;
        }
    }

    selectEl.addEventListener('change', function () {
        selectedIndex = Number(selectEl.value) || 0;
        sortFlag = '';
        sortDir = 'desc';
        renderRoundFace();
        renderMatches();
        loadRanking();
    });

    tableEl.addEventListener('click', function (event) {
        const sortBtn = event.target.closest('[data-sort]');
        if (sortBtn) {
            event.preventDefault();
            const flag = sortBtn.getAttribute('data-sort') || '';
            if (activeSortKey() === flag) {
                sortDir = sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                sortFlag = (flag === 'lc' || flag === 'po') ? '' : flag;
                sortDir = (flag === 'n' || flag === 'r') ? 'asc' : 'desc';
            }
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
