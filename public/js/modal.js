(function () {
    'use strict';

    const config = window.FFB_MODAL || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';

    const stack = [];
    let root = null;
    let dialog = null;
    let bodyEl = null;
    let headEl = null;
    let tabsEl = null;
    let openToken = 0;

    function symbolUrl(name) {
        return legacyBase + 'images/ffb/symbols/' + name;
    }

    function imgUrl(path) {
        if (!path) {
            return '';
        }
        if (/^https?:\/\//i.test(path)) {
            return path;
        }
        return legacyBase + String(path).replace(/^\//, '');
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function ensureDom() {
        if (root) {
            return;
        }

        root = document.createElement('div');
        root.className = 'ffb-modal-root';
        root.hidden = true;
        root.setAttribute('role', 'dialog');
        root.setAttribute('aria-modal', 'true');
        root.innerHTML =
            '<div class="ffb-modal-backdrop" data-ffb-modal-close-all></div>' +
            '<div class="ffb-modal-dialog" role="document">' +
            '<div class="ffb-modal-head"></div>' +
            '<div class="ffb-modal-tabs" hidden></div>' +
            '<div class="ffb-modal-body"></div>' +
            '</div>';

        document.body.appendChild(root);
        dialog = root.querySelector('.ffb-modal-dialog');
        headEl = root.querySelector('.ffb-modal-head');
        tabsEl = root.querySelector('.ffb-modal-tabs');
        bodyEl = root.querySelector('.ffb-modal-body');

        root.addEventListener('click', function (e) {
            if (e.target.closest('[data-ffb-modal-close-all]')) {
                FfbModal.closeAll();
                return;
            }
            if (e.target.closest('[data-ffb-modal-close]')) {
                FfbModal.close();
            }
        });
    }

    function setHead(html) {
        headEl.innerHTML = html;
    }

    function setTabs(html) {
        if (!html) {
            tabsEl.hidden = true;
            tabsEl.innerHTML = '';
            return;
        }
        tabsEl.hidden = false;
        tabsEl.innerHTML = html;
    }

    function setBody(html) {
        bodyEl.innerHTML = html;
    }

    function defaultCloseBtn() {
        return (
            '<button type="button" class="ffb-modal-close" data-ffb-modal-close title="Schließen" aria-label="Schließen">' +
            '<img src="' + symbolUrl('delete.svg') + '" alt="">' +
            '</button>'
        );
    }

    function waitingUi() {
        setHead(
            '<div class="ffb-modal-head-title">lade Infos… bitte warten…</div>' +
            defaultCloseBtn()
        );
        setTabs('');
        setBody('<p class="ffb-modal-loading">Wird geladen…</p>');
    }

    async function fetchJson(url) {
        const res = await fetch(url, {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });
        const json = await res.json().catch(function () {
            return null;
        });
        if (!res.ok) {
            const err = (json && json.error) || ('HTTP ' + res.status);
            throw new Error(err);
        }
        return json;
    }

    function renderProfileHead(user) {
        return (
            '<img class="ffb-modal-head-avatar" src="' + escapeHtml(imgUrl(user.avatar_url)) + '" alt="" width="40" height="40">' +
            '<div class="ffb-modal-head-title">' + escapeHtml(user.user_nickname) + '</div>' +
            defaultCloseBtn()
        );
    }

    function renderProfileTabs(userId, active) {
        return (
            '<button type="button" class="ffb-tab' + (active === 'profile' ? ' is-active' : '') + '" data-ffb-profile-tab="profile" data-id="' + userId + '">Profil</button>' +
            '<button type="button" class="ffb-tab' + (active === 'awards' ? ' is-active' : '') + '" data-ffb-profile-tab="awards" data-id="' + userId + '">Auszeichnungen</button>'
        );
    }

    function profileRow(symbol, label, valueHtml) {
        return (
            '<div class="ffb-profile-row">' +
            '<img src="' + symbolUrl(symbol) + '" alt="">' +
            '<span class="label">' + escapeHtml(label) + '</span>' +
            '<span class="value">' + valueHtml + '</span>' +
            '</div>'
        );
    }

    function websiteHref(raw) {
        const s = String(raw || '').trim();
        if (!s) {
            return '#';
        }
        if (/^https?:\/\//i.test(s)) {
            return s;
        }
        return 'https://' + s;
    }

    function renderProfileBody(data) {
        const user = data.user;
        const parts = data.participations || [];
        let rows = '';

        if (user.user_perm_profile && user.user_name) {
            rows += profileRow('symbol_profile.svg', 'Name:', escapeHtml(user.user_name));
        }
        if (user.user_details_city) {
            rows += profileRow('symbol_home.svg', 'kommt aus:', escapeHtml(user.user_details_city));
        }
        if (user.user_details_website) {
            const ws = String(user.user_details_website);
            const label = ws.length > 23 ? 'klicken' : ws;
            rows += profileRow(
                'symbol_globe.svg',
                'Website:',
                '<a class="nolink" target="_blank" rel="noopener noreferrer" href="' +
                    escapeHtml(websiteHref(ws)) +
                    '" title="Zur Website gehen">' +
                    escapeHtml(label) +
                    '</a>'
            );
        }
        if (user.user_perm_profile && user.user_details_phone) {
            rows += profileRow('symbol_phone.svg', 'Telefon:', escapeHtml(user.user_details_phone));
        }
        if (user.user_date_register) {
            rows += profileRow('calendar.svg', 'Mitglied seit:', escapeHtml(user.user_date_register));
        }
        if (user.user_date_llogin) {
            rows += profileRow('stats_time.svg', 'letzte Aktivität:', escapeHtml(user.user_date_llogin));
        }
        if (user.favourite_team && user.favourite_team.name) {
            rows += profileRow('symbol_shoes.svg', 'Lieblingsteam:', escapeHtml(user.favourite_team.name));
        }

        let table = '';
        if (parts.length) {
            table += '<div class="ffb-profile-section"><h3>Teilnahmen</h3>';
            table += '<table class="ffb-profile-table"><thead><tr>';
            table += '<th><b>Liga</b></th><th><b>von – bis</b></th><th><b>Punkte (LC)</b></th><th><b>Platz</b></th>';
            table += '</tr></thead><tbody>';

            for (let i = 0; i < parts.length; i++) {
                const p = parts[i];
                const rank = Number(p.user_rank) || 0;
                let rankCls = '';
                if (rank === 1) {
                    rankCls = ' rank-1';
                } else if (rank === 2) {
                    rankCls = ' rank-2';
                } else if (rank === 3) {
                    rankCls = ' rank-3';
                }

                let scoreCell;
                if (p.score_rm === 'lc') {
                    scoreCell = escapeHtml(p.score_points) + ' (<b>' + escapeHtml(p.score_lc) + '</b>)';
                } else {
                    scoreCell = '<b>' + escapeHtml(p.score_points) + '</b> (' + escapeHtml(p.score_lc) + ')';
                }

                let liga = '';
                if (p.league_symbol_url) {
                    liga +=
                        '<img src="' +
                        escapeHtml(imgUrl(p.league_symbol_url)) +
                        '" alt="" width="16" height="16">';
                } else if (p.league_symbol) {
                    liga += '<img src="' + symbolUrl(p.league_symbol) + '" alt="" width="16" height="16">';
                }
                liga += escapeHtml(p.league_title);

                const range =
                    escapeHtml(p.score_start || '–') + ' – ' + escapeHtml(p.score_end || '–');

                table += '<tr>';
                table += '<td class="liga">' + liga + '</td>';
                table += '<td>' + range + '</td>';
                table += '<td>' + scoreCell + '</td>';
                table += '<td class="' + rankCls.trim() + '"><b>' + escapeHtml(rank) + '</b></td>';
                table += '</tr>';
            }

            table += '</tbody></table></div>';
        }

        return (
            '<div class="ffb-profile">' +
            '<div><img class="ffb-profile-photo" src="' +
            escapeHtml(imgUrl(user.photo_url)) +
            '" alt="" width="100"></div>' +
            '<div class="ffb-profile-rows">' +
            rows +
            '</div></div>' +
            table
        );
    }

    let lastProfileData = null;

    function renderAwardsBody(data) {
        const groups = data.groups || [];
        if (!groups.length) {
            return '<p class="ffb-profile-awards-stub">Keine Auszeichnungen vorhanden.</p>';
        }

        let html =
            '<div class="ffb-awards">' +
            '<div class="ffb-awards-header">' +
            '<div class="ffb-awards-col-symbol"><b>Auszeichnung</b></div>' +
            '<div class="ffb-awards-col-ranks"><b>Rang</b></div>' +
            '</div>';

        for (let i = 0; i < groups.length; i++) {
            const g = groups[i];
            html += '<div class="ffb-awards-row">';
            html +=
                '<div class="ffb-awards-col-symbol">' +
                '<img src="' +
                escapeHtml(imgUrl(g.image_url)) +
                '" width="35" height="35" title="' +
                escapeHtml(g.description || g.name) +
                '" alt="">' +
                '<em>' +
                escapeHtml(g.name) +
                '</em></div>';
            html += '<div class="ffb-awards-col-ranks">';

            const ranks = g.ranks || [];
            for (let j = 0; j < ranks.length; j++) {
                const r = ranks[j];
                const title = r.finished
                    ? 'Ausgezeichnet mit ' + String(r.name).toUpperCase() + '!'
                    : String(r.name).toUpperCase() + ' - ' + (r.description || '');
                html +=
                    '<div class="ffb-awards-rank' +
                    (r.finished ? ' is-earned' : ' is-silhouette') +
                    '">' +
                    '<img src="' +
                    escapeHtml(imgUrl(r.image_url)) +
                    '" height="45" title="' +
                    escapeHtml(title) +
                    '" alt="' +
                    escapeHtml(r.name) +
                    '"></div>';
            }

            html += '</div></div>';
        }

        html += '</div>';
        return html;
    }

    async function openProfile(userId, tab) {
        waitingUi();
        const active = tab || 'profile';

        if (
            !lastProfileData ||
            Number(lastProfileData.user.user_id) !== Number(userId)
        ) {
            const json = await fetchJson(apiBase + '/popups/user/' + encodeURIComponent(userId));
            lastProfileData = json.data;
        }

        const user = lastProfileData.user;
        setHead(renderProfileHead(user));
        setTabs(renderProfileTabs(user.user_id, active));

        if (active === 'awards') {
            const awardsJson = await fetchJson(
                apiBase + '/popups/user/' + encodeURIComponent(userId) + '/awards'
            );
            setBody(renderAwardsBody(awardsJson.data));
            return;
        }

        setBody(renderProfileBody(lastProfileData));
    }

    function flagHtml(code, title) {
        if (window.FfbFlags && typeof window.FfbFlags.html === 'function') {
            return window.FfbFlags.html(code, title ? { title: title } : undefined);
        }
        const flag = (code || 'na').toLowerCase();
        const src = legacyBase + 'images/ffb/flags/' + flag + '.gif';
        const titleAttr = title ? ' title="' + escapeHtml(title) + '"' : '';
        return '<img class="ffb-flag ffb-flag-img" src="' + src + '" alt="" width="16" height="11" loading="lazy"' + titleAttr + '>';
    }

    function flagBackgroundUrl(code) {
        if (window.FfbFlags) {
            if (typeof window.FfbFlags.svgUrl === 'function') {
                const svg = window.FfbFlags.svgUrl(code);
                if (svg) {
                    return svg;
                }
            }
            if (typeof window.FfbFlags.imageUrl === 'function') {
                return window.FfbFlags.imageUrl(code);
            }
        }
        const flag = (!code || code === '0' ? 'na' : String(code)).toLowerCase();
        return legacyBase + 'images/ffb/flags/' + flag + '.gif';
    }

    function cssUrl(url) {
        return (
            "url('" +
            String(url == null ? '' : url)
                .replace(/\\/g, '\\\\')
                .replace(/'/g, "\\'") +
            "')"
        );
    }

    function playerLink(playerteamId, name) {
        return (
            '<a class="nolink" href="#" data-modal="player" data-id="' +
            escapeHtml(playerteamId) +
            '" title="Spielerinfos">' +
            escapeHtml(name) +
            '</a>'
        );
    }

    function playerInfoIcon(playerteamId) {
        return (
            '<a class="nolink ffb-match-info" href="#" data-modal="player" data-id="' +
            escapeHtml(playerteamId) +
            '" title="Klicken für Spielerinfos">' +
            '<img src="' + symbolUrl('info.svg') + '" alt="" height="12"></a>'
        );
    }

    function repeatIcon(src, count, title) {
        let html = '';
        for (let i = 0; i < count; i++) {
            html += '<img src="' + src + '" alt="" height="12" title="' + escapeHtml(title) + '">';
        }
        return html;
    }

    function playerEventIcons(player, matchMinutes, side) {
        const card = player.player_playerstats_cards;
        const goals = Number(player.player_playerstats_goals) || 0;
        const assists = Number(player.player_playerstats_assists) || 0;
        const owngoals = Number(player.player_playerstats_owngoals) || 0;
        const penSaved = Number(player.player_playerstats_penaltiessaved) || 0;
        const penLost = Number(player.player_playerstats_penaltieslost) || 0;
        const psHit = Number(player.player_playerstats_penaltyshootout_hit) || 0;
        const psLost = Number(player.player_playerstats_penaltyshootout_lost) || 0;
        const psSave = Number(player.player_playerstats_penaltyshootout_save) || 0;
        const minutesIn = Number(player.player_playerstats_minute_in) || 0;
        const minutesOut = Number(player.player_playerstats_minute_out) || 0;
        let html = '';

        const changeIn =
            minutesIn > 1
                ? '<img src="' +
                  symbolUrl('stats_change_in.svg') +
                  '" height="12" title="Einwechslung: ' +
                  minutesIn +
                  '. Minute">'
                : '';
        const changeOut =
            minutesOut < matchMinutes && minutesOut !== 0
                ? '<img src="' +
                  symbolUrl('stats_change_out.svg') +
                  '" height="12" title="Auswechslung: ' +
                  minutesOut +
                  '. Minute">'
                : '';

        let cardHtml = '';
        if (card === 'y') {
            cardHtml =
                '<img src="' + symbolUrl('stats_card_y.svg') + '" height="12" title="Gelbe Karte">';
        } else if (card === 'yr') {
            cardHtml =
                '<img src="' +
                symbolUrl('stats_card_yr.svg') +
                '" height="12" title="Gelb-Rote Karte">';
        } else if (card === 'r') {
            cardHtml =
                '<img src="' + symbolUrl('stats_card_r.svg') + '" height="12" title="Rote Karte">';
        }

        const goalHtml = repeatIcon(symbolUrl('stats_goal.svg'), goals, 'Tor');
        const assistHtml = repeatIcon(symbolUrl('stats_assist.svg'), assists, 'Assist');
        const ownHtml = repeatIcon(symbolUrl('stats_owngoal.svg'), owngoals, 'Eigentor');
        const penSavedHtml = repeatIcon(
            symbolUrl('stats_penaltysaved.svg'),
            penSaved,
            'Elfer gehalten'
        );
        const penLostHtml = repeatIcon(
            symbolUrl('stats_penaltylost.svg'),
            penLost,
            'Elfer verschossen'
        );
        const psHitHtml = repeatIcon(
            symbolUrl('stats_ps_hit.svg'),
            psHit,
            'Elfmeterschießen - getroffen'
        );
        const psLostHtml = repeatIcon(
            symbolUrl('stats_ps_fail.svg'),
            psLost,
            'Elfmeterschießen - nicht getroffen'
        );
        const psSaveHtml = repeatIcon(
            symbolUrl('stats_ps_hit.svg'),
            psSave,
            'Elfmeterschießen - gehalten'
        );

        const playEvents = [
            goalHtml,
            assistHtml,
            ownHtml,
            penSavedHtml,
            penLostHtml,
            psHitHtml,
            psLostHtml,
            psSaveHtml,
        ].filter(Boolean);
        const changeEvents = [changeIn, changeOut].filter(Boolean);

        if (side === 'home') {
            changeEvents.forEach(function (part) {
                html += '&nbsp;' + part;
            });
            if (cardHtml) {
                html += '&nbsp;' + cardHtml;
            }
            playEvents.forEach(function (part) {
                html += '&nbsp;' + part;
            });
        } else {
            playEvents.forEach(function (part) {
                html += part + '&nbsp;';
            });
            if (cardHtml) {
                html += cardHtml + '&nbsp;';
            }
            changeEvents.forEach(function (part) {
                html += part + '&nbsp;';
            });
        }

        return html;
    }

    function renderHomePlayers(players, matchMinutes) {
        return (players || [])
            .map(function (p) {
                return (
                    '<div class="ffb-match-player ffb-match-player-home">' +
                    playerInfoIcon(p.player_playerteam_id) +
                    '&nbsp;' +
                    escapeHtml(p.player_name) +
                    playerEventIcons(p, matchMinutes, 'home') +
                    '</div>'
                );
            })
            .join('');
    }

    function renderGuestPlayers(players, matchMinutes) {
        return (players || [])
            .map(function (p) {
                return (
                    '<div class="ffb-match-player ffb-match-player-guest">' +
                    playerEventIcons(p, matchMinutes, 'guest') +
                    escapeHtml(p.player_name) +
                    '&nbsp;' +
                    playerInfoIcon(p.player_playerteam_id) +
                    '</div>'
                );
            })
            .join('');
    }

    function formatMatchResult(match) {
        const listMatch = {
            match_homescore: match.match_hometeam_score,
            match_guestscore: match.match_guestteam_score,
            match_homescore_penalty: match.match_hometeam_score_penalty,
            match_guestscore_penalty: match.match_guestteam_score_penalty,
            match_minutes: match.match_minutes,
        };

        if (window.FfbMatchList && typeof window.FfbMatchList.formatScore === 'function') {
            return window.FfbMatchList.formatScore(listMatch);
        }

        const homePen = listMatch.match_homescore_penalty;
        const guestPen = listMatch.match_guestscore_penalty;
        const hasPenalty =
            homePen != null && homePen > -1 && guestPen != null && guestPen > -1;
        const hasResult =
            listMatch.match_homescore != null &&
            listMatch.match_guestscore != null &&
            Number(listMatch.match_homescore) >= 0 &&
            Number(listMatch.match_guestscore) >= 0;

        if (hasPenalty) {
            let html =
                '<span class="score-final">' +
                escapeHtml(homePen) +
                ':' +
                escapeHtml(guestPen) +
                ' <span class="score-hint" title="nach Elfmeterschießen">n.E.</span></span>';
            if (hasResult) {
                html +=
                    '<span class="score-reg">(' +
                    escapeHtml(listMatch.match_homescore) +
                    ':' +
                    escapeHtml(listMatch.match_guestscore) +
                    ' <span class="score-hint" title="nach Verlängerung">n.V.</span>)</span>';
            }
            return html;
        }

        if (!hasResult) {
            return '-:-';
        }

        let html =
            escapeHtml(listMatch.match_homescore) +
            ':' +
            escapeHtml(listMatch.match_guestscore);
        if (Number(listMatch.match_minutes) === 120) {
            html +=
                ' <span class="score-hint" title="nach Verlängerung">n.V.</span>';
        }
        return html;
    }

    function renderGoalOrder(goals, homeTeamId, guestTeamId) {
        if (!goals || !goals.length) {
            return '';
        }
        let homescore = 0;
        let guestscore = 0;
        let html =
            '<div class="ffb-match-section"><h3>Torfolge</h3><ul class="ffb-match-goals">';

        for (let i = 0; i < goals.length; i++) {
            const g = goals[i];
            const teamId = Number(g.goal_team_id);
            const owngoal = g.goal_owngoal ? 1 : 0;
            if (teamId === Number(homeTeamId)) {
                if (owngoal > 0) {
                    guestscore++;
                } else {
                    homescore++;
                }
            } else if (teamId === Number(guestTeamId)) {
                if (owngoal > 0) {
                    homescore++;
                } else {
                    guestscore++;
                }
            }

            html +=
                '<li><span class="minute">' +
                escapeHtml(g.goal_minute) +
                '. Minute</span>' +
                '<span class="result"><b>' +
                homescore +
                ':' +
                guestscore +
                '</b></span>' +
                '<span class="scorer">(' +
                playerLink(g.goal_playerteam_id, g.goal_player_name) +
                (owngoal > 0 ? ' / ET' : '') +
                ')</span></li>';
        }

        html += '</ul></div>';
        return html;
    }

    function renderPenaltyshootout(psgoals, homeTeamId, guestTeamId) {
        if (!psgoals || !psgoals.length) {
            return '';
        }
        let homeHtml = '';
        let guestHtml = '';

        for (let i = 0; i < psgoals.length; i++) {
            const g = psgoals[i];
            const symbol =
                g.psgoal_hit
                    ? '<img src="' +
                      symbolUrl('stats_ps_hit.svg') +
                      '" width="16" height="16" alt="getroffen" title="getroffen">'
                    : '<img src="' +
                      symbolUrl('stats_ps_fail.svg') +
                      '" width="16" height="16" alt="nicht getroffen" title="nicht getroffen">';
            const flag = flagHtml(g.psgoal_team_nationality, g.psgoal_team_name);
            const name = playerLink(g.psgoal_playerteam_id, g.psgoal_player_name);

            if (Number(g.psgoal_team_id) === Number(homeTeamId)) {
                homeHtml +=
                    '<div class="ffb-match-ps-row">' +
                    symbol +
                    '&ensp;' +
                    flag +
                    '&ensp;' +
                    name +
                    '</div>';
            } else if (Number(g.psgoal_team_id) === Number(guestTeamId)) {
                guestHtml +=
                    '<div class="ffb-match-ps-row">' +
                    name +
                    '&ensp;' +
                    flag +
                    '&ensp;' +
                    symbol +
                    '</div>';
            }
        }

        return (
            '<div class="ffb-match-section"><h3>Elfmeterschießen</h3>' +
            '<div class="ffb-match-ps">' +
            '<div class="home">' +
            homeHtml +
            '</div>' +
            '<div class="guest">' +
            guestHtml +
            '</div></div></div>'
        );
    }

    function formatPrevResult(m) {
        const homePen = m.match_hometeam_score_penalty;
        const guestPen = m.match_guestteam_score_penalty;
        if (homePen != null && homePen > -1 && guestPen != null && guestPen > -1) {
            return escapeHtml(homePen) + ':' + escapeHtml(guestPen) + ' n.E.';
        }
        return (
            escapeHtml(m.match_hometeam_score) + ':' + escapeHtml(m.match_guestteam_score)
        );
    }

    function formatPrevMatchMeta(m) {
        const parts = [];
        const roundLabel = String(m.match_matchround_name || '').trim();
        const dateLabel = String(m.match_date || '').trim();
        if (roundLabel !== '') {
            parts.push(roundLabel);
        }
        if (dateLabel !== '') {
            parts.push(dateLabel);
        }
        return parts.join(' - ');
    }

    function formatMatchHeaderMeta(match) {
        const parts = [];
        const league = String(match.match_league_title || '').trim();
        const round = String(match.match_matchround_name || '').trim();
        const dateLabel = String(match.match_date || '').trim();
        let roundLabel = '';
        if (league !== '' && round !== '') {
            roundLabel = league + ' - ' + round;
        } else {
            roundLabel = league || round;
        }
        if (roundLabel !== '') {
            parts.push(roundLabel);
        }
        if (dateLabel !== '') {
            parts.push(dateLabel);
        }
        return parts.join(' - ');
    }

    function renderPrevMatches(prev) {
        if (!prev || !prev.length) {
            return '';
        }
        let html =
            '<div class="ffb-match-section"><h3>Alle Begegnungen</h3><ul class="match-list ffb-match-prev">';
        for (let i = 0; i < prev.length; i++) {
            const m = prev[i];
            const listMatch = {
                match_id: m.match_id,
                match_hometeam_name: m.match_hometeam_name,
                match_guestteam_name: m.match_guestteam_name,
                match_hometeam_nationality: m.match_hometeam_nationality,
                match_guestteam_nationality: m.match_guestteam_nationality,
                match_homescore: m.match_hometeam_score,
                match_guestscore: m.match_guestteam_score,
                match_homescore_penalty: m.match_hometeam_score_penalty,
                match_guestscore_penalty: m.match_guestteam_score_penalty,
                match_minutes: m.match_minutes,
            };
            const rowHtml =
                window.FfbMatchList && typeof window.FfbMatchList.matchRowHtml === 'function'
                    ? window.FfbMatchList.matchRowHtml(listMatch)
                    : fallbackPrevMatchRow(m);
            html +=
                '<li class="match-list-item">' +
                '<div class="ffb-match-prev-meta">' +
                escapeHtml(formatPrevMatchMeta(m)) +
                '</div>' +
                rowHtml +
                '</li>';
        }
        html += '</ul></div>';
        return html;
    }

    function fallbackPrevMatchRow(m) {
        return (
            '<button type="button" class="match-list-row match-list-row--clickable" data-modal="match" data-id="' +
            escapeHtml(m.match_id) +
            '" title="Klicken für Matchinfos">' +
            '<span class="home">' +
            '<span class="match-team-name">' +
            escapeHtml(m.match_hometeam_name) +
            '</span> ' +
            flagHtml(m.match_hometeam_nationality) +
            '</span>' +
            '<span class="score">' +
            formatPrevResult(m) +
            '</span>' +
            '<span class="away">' +
            flagHtml(m.match_guestteam_nationality) +
            ' <span class="match-team-name">' +
            escapeHtml(m.match_guestteam_name) +
            '</span>' +
            '</span>' +
            '</button>'
        );
    }

    function renderMatchBody(data) {
        const match = data.match;
        const minutes = Number(match.match_minutes) || 0;

        return (
            '<div class="ffb-match">' +
            '<ul class="match-list ffb-match-header-wrap">' +
            '<li class="match-list-item ffb-match-header-card" style="--ffb-flag-home:' +
            cssUrl(flagBackgroundUrl(match.match_hometeam_nationality)) +
            ';--ffb-flag-away:' +
            cssUrl(flagBackgroundUrl(match.match_guestteam_nationality)) +
            '">' +
            '<div class="ffb-match-prev-meta">' +
            escapeHtml(formatMatchHeaderMeta(match)) +
            '</div>' +
            '<div class="match-list-row ffb-match-header">' +
            '<span class="home">' +
            '<span class="match-team-name">' +
            escapeHtml(match.match_hometeam_name) +
            '</span>' +
            '</span>' +
            '<span class="score">' +
            formatMatchResult(match) +
            '</span>' +
            '<span class="away">' +
            '<span class="match-team-name">' +
            escapeHtml(match.match_guestteam_name) +
            '</span>' +
            '</span>' +
            '</div>' +
            '</li>' +
            '</ul>' +
            '<div class="ffb-match-lineups">' +
            '<div class="home">' +
            renderHomePlayers(data.hometeam_players, minutes) +
            '</div>' +
            '<div class="guest">' +
            renderGuestPlayers(data.guestteam_players, minutes) +
            '</div></div>' +
            renderGoalOrder(data.goals, match.match_hometeam_id, match.match_guestteam_id) +
            renderPenaltyshootout(data.psgoals, match.match_hometeam_id, match.match_guestteam_id) +
            renderPrevMatches(data.prev_matches) +
            '</div>'
        );
    }

    async function openMatch(matchId) {
        waitingUi();
        const json = await fetchJson(apiBase + '/popups/match/' + encodeURIComponent(matchId));
        const data = json.data;
        setHead(
            '<div class="ffb-modal-head-title">Matchinfo</div>' + defaultCloseBtn()
        );
        setTabs('');
        setBody(renderMatchBody(data));
    }

    async function openPlayerStub() {
        waitingUi();
        setHead('<div class="ffb-modal-head-title">Spielerinfo</div>' + defaultCloseBtn());
        setTabs('');
        setBody(
            '<p class="ffb-profile-awards-stub">Spielerinfos folgen in einem späteren Schritt.</p>'
        );
    }

    const loaders = {
        profile: function (id, opts) {
            return openProfile(id, (opts && opts.tab) || 'profile');
        },
        match: function (id) {
            return openMatch(id);
        },
        player: function () {
            return openPlayerStub();
        },
        'player-points': function () {
            return openPlayerStub();
        },
    };

    const FfbModal = {
        open: async function (opts) {
            const type = opts && opts.type;
            const id = opts && opts.id;
            if (!type || id == null || id === '') {
                return;
            }

            ensureDom();
            root.hidden = false;
            document.body.style.overflow = 'hidden';

            const entry = {
                type: type,
                id: id,
                tab: opts.tab,
                matchroundId: opts.matchroundId,
                showAll: opts.showAll,
            };
            const top = stack[stack.length - 1];
            if (top && top.type === type && String(top.id) === String(id)) {
                stack[stack.length - 1] = entry;
            } else {
                stack.push(entry);
            }

            const token = ++openToken;
            const loader = loaders[type];
            if (!loader) {
                setHead('<div class="ffb-modal-head-title">Unbekannt</div>' + defaultCloseBtn());
                setTabs('');
                setBody('<p class="ffb-modal-error">Unbekannter Popup-Typ.</p>');
                return;
            }

            try {
                await loader(id, opts);
                if (token !== openToken) {
                    return;
                }
            } catch (err) {
                if (token !== openToken) {
                    return;
                }
                setHead('<div class="ffb-modal-head-title">Fehler</div>' + defaultCloseBtn());
                setTabs('');
                setBody(
                    '<p class="ffb-modal-error">' +
                        escapeHtml(err.message || 'Laden fehlgeschlagen') +
                        '</p>'
                );
            }
        },

        close: function () {
            if (!root) {
                return;
            }
            openToken++;
            stack.pop();
            if (stack.length === 0) {
                root.hidden = true;
                setBody('');
                setTabs('');
                setHead('');
                document.body.style.overflow = '';
                return;
            }
            const prev = stack.pop();
            FfbModal.open(prev);
        },

        closeAll: function () {
            if (!root) {
                return;
            }
            openToken++;
            stack.length = 0;
            root.hidden = true;
            setBody('');
            setTabs('');
            setHead('');
            document.body.style.overflow = '';
        },

        register: function (type, fn) {
            loaders[type] = fn;
        },
    };

    document.addEventListener('click', function (e) {
        const tabBtn = e.target.closest('[data-ffb-profile-tab]');
        if (tabBtn && root && !root.hidden) {
            e.preventDefault();
            const tab = tabBtn.getAttribute('data-ffb-profile-tab');
            const id = tabBtn.getAttribute('data-id');
            FfbModal.open({ type: 'profile', id: id, tab: tab });
            return;
        }

        const trigger = e.target.closest('[data-modal]');
        if (!trigger) {
            return;
        }
        e.preventDefault();
        FfbModal.open({
            type: trigger.getAttribute('data-modal'),
            id: trigger.getAttribute('data-id'),
            tab: trigger.getAttribute('data-tab') || undefined,
            matchroundId: trigger.getAttribute('data-matchround-id') || undefined,
            showAll: trigger.getAttribute('data-show-all') || undefined,
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && root && !root.hidden) {
            FfbModal.close();
        }
    });

    window.FfbModal = FfbModal;
})();
