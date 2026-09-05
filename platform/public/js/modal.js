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
            '<div class="ffb-modal-backdrop" data-ffb-modal-close></div>' +
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
            '<img src="' + symbolUrl('delete.png') + '" alt="">' +
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
            '<button type="button" class="' + (active === 'profile' ? 'is-active' : '') + '" data-ffb-profile-tab="profile" data-id="' + userId + '">Profil</button>' +
            '<button type="button" class="' + (active === 'awards' ? 'is-active' : '') + '" data-ffb-profile-tab="awards" data-id="' + userId + '">Auszeichnungen</button>'
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
            rows += profileRow('symbol_profile.png', 'Name:', escapeHtml(user.user_name));
        }
        if (user.user_details_city) {
            rows += profileRow('symbol_home.png', 'kommt aus:', escapeHtml(user.user_details_city));
        }
        if (user.user_details_website) {
            const ws = String(user.user_details_website);
            const label = ws.length > 23 ? 'klicken' : ws;
            rows += profileRow(
                'symbol_globe.png',
                'Website:',
                '<a class="nolink" target="_blank" rel="noopener noreferrer" href="' +
                    escapeHtml(websiteHref(ws)) +
                    '" title="Zur Website gehen">' +
                    escapeHtml(label) +
                    '</a>'
            );
        }
        if (user.user_perm_profile && user.user_details_phone) {
            rows += profileRow('symbol_phone.png', 'Telefon:', escapeHtml(user.user_details_phone));
        }
        if (user.user_date_register) {
            rows += profileRow('calendar.png', 'Mitglied seit:', escapeHtml(user.user_date_register));
        }
        if (user.user_date_llogin) {
            rows += profileRow('stats_time.png', 'letzte Aktivität:', escapeHtml(user.user_date_llogin));
        }
        if (user.favourite_team && user.favourite_team.name) {
            rows += profileRow('symbol_shoes.png', 'Lieblingsteam:', escapeHtml(user.favourite_team.name));
        }

        let table = '';
        if (parts.length) {
            table += '<div class="ffb-profile-section"><h3>Teilnahmen</h3>';
            table += '<table class="ffb-profile-table"><thead><tr>';
            table += '<th><b>Liga</b></th><th><b>von – bis</b></th><th><b>Punkte (WC)</b></th><th><b>Platz</b></th>';
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
                if (p.score_rm === 'wc') {
                    scoreCell = escapeHtml(p.score_points) + ' (<b>' + escapeHtml(p.score_wc) + '</b>)';
                } else {
                    scoreCell = '<b>' + escapeHtml(p.score_points) + '</b> (' + escapeHtml(p.score_wc) + ')';
                }

                let liga = '';
                if (p.game_symbol) {
                    liga += '<img src="' + symbolUrl(p.game_symbol) + '" alt="" width="16" height="16">';
                }
                liga += escapeHtml(p.game_title);

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

    async function openProfile(userId, tab) {
        waitingUi();
        const json = await fetchJson(apiBase + '/popups/user/' + encodeURIComponent(userId));
        const data = json.data;
        const user = data.user;

        setHead(renderProfileHead(user));
        setTabs(renderProfileTabs(user.user_id, tab || 'profile'));

        if (tab === 'awards') {
            setBody(
                '<p class="ffb-profile-awards-stub">Auszeichnungen folgen in einem späteren Schritt.</p>'
            );
            return;
        }

        setBody(renderProfileBody(data));
    }

    function flagUrl(code) {
        const flag = (code || 'na').toLowerCase();
        return legacyBase + 'images/ffb/flags/' + flag + '.gif';
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
            '<img src="' + symbolUrl('info.png') + '" alt="" height="12"></a>'
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
        const owngoals = Number(player.player_playerstats_owngoals) || 0;
        const minutesIn = Number(player.player_playerstats_minute_in) || 0;
        const minutesOut = Number(player.player_playerstats_minute_out) || 0;
        let html = '';

        const changeIn =
            minutesIn > 1
                ? '<img src="' +
                  symbolUrl('stats_change_in.gif') +
                  '" height="12" title="Einwechslung: ' +
                  minutesIn +
                  '. Minute">'
                : '';
        const changeOut =
            minutesOut < matchMinutes && minutesOut !== 0
                ? '<img src="' +
                  symbolUrl('stats_change_out.gif') +
                  '" height="12" title="Auswechslung: ' +
                  minutesOut +
                  '. Minute">'
                : '';

        let cardHtml = '';
        if (card === 'y') {
            cardHtml =
                '<img src="' + symbolUrl('stats_card_y.gif') + '" height="12" title="Gelbe Karte">';
        } else if (card === 'yr') {
            cardHtml =
                '<img src="' +
                symbolUrl('stats_card_yr.gif') +
                '" height="12" title="Gelb-Rote Karte">';
        } else if (card === 'r') {
            cardHtml =
                '<img src="' + symbolUrl('stats_card_r.gif') + '" height="12" title="Rote Karte">';
        }

        const goalHtml = repeatIcon(symbolUrl('stats_goal.gif'), goals, 'Tor');
        const ownHtml = repeatIcon(symbolUrl('stats_owngoal.gif'), owngoals, 'Eigentor');

        if (side === 'home') {
            if (changeIn) {
                html += '&nbsp;' + changeIn;
            }
            if (changeOut) {
                html += '&nbsp;' + changeOut;
            }
            if (cardHtml) {
                html += '&nbsp;' + cardHtml;
            }
            if (goalHtml) {
                html += '&nbsp;' + goalHtml;
            }
            if (ownHtml) {
                html += '&nbsp;' + ownHtml;
            }
        } else {
            if (goalHtml) {
                html += goalHtml + '&nbsp;';
            }
            if (ownHtml) {
                html += ownHtml + '&nbsp;';
            }
            if (cardHtml) {
                html += cardHtml + '&nbsp;';
            }
            if (changeIn) {
                html += changeIn + '&nbsp;';
            }
            if (changeOut) {
                html += changeOut + '&nbsp;';
            }
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
        const homePen = match.match_hometeam_score_penalty;
        const guestPen = match.match_guestteam_score_penalty;
        if (homePen != null && homePen > -1 && guestPen != null && guestPen > -1) {
            return (
                '<span title="nach Elfmeterschießen">' +
                escapeHtml(homePen) +
                ':' +
                escapeHtml(guestPen) +
                ' n.E.</span><br>' +
                '<span class="ffb-match-score-reg" title="nach regulärer Spielzeit">(' +
                escapeHtml(match.match_hometeam_score) +
                ':' +
                escapeHtml(match.match_guestteam_score) +
                ')</span>'
            );
        }
        if (match.match_hometeam_score == null || Number(match.match_hometeam_score) < 0) {
            return '-:-';
        }
        return (
            escapeHtml(match.match_hometeam_score) +
            ':' +
            escapeHtml(match.match_guestteam_score)
        );
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
                      symbolUrl('stats_ps_hit.png') +
                      '" width="16" height="16" alt="getroffen" title="getroffen">'
                    : '<img src="' +
                      symbolUrl('stats_ps_fail.png') +
                      '" width="16" height="16" alt="nicht getroffen" title="nicht getroffen">';
            const flag =
                '<img src="' +
                flagUrl(g.psgoal_team_nationality) +
                '" width="16" height="11" title="' +
                escapeHtml(g.psgoal_team_name) +
                '">';
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

    function renderPrevMatches(prev) {
        if (!prev || !prev.length) {
            return '';
        }
        let html =
            '<div class="ffb-match-section"><h3>Bisherige Partien</h3><ul class="ffb-match-prev">';
        for (let i = 0; i < prev.length; i++) {
            const m = prev[i];
            html +=
                '<li>' +
                '<span class="round">' +
                escapeHtml(m.match_matchround_name) +
                '</span>' +
                '<span class="date">' +
                escapeHtml(m.match_date || '') +
                '</span>' +
                '<span class="home">' +
                escapeHtml(m.match_hometeam_name) +
                '</span>' +
                '<span class="result"><a class="under" href="#" data-modal="match" data-id="' +
                escapeHtml(m.match_id) +
                '" title="Klicken für Matchinfos">' +
                formatPrevResult(m) +
                '</a></span>' +
                '<span class="guest">' +
                escapeHtml(m.match_guestteam_name) +
                '</span></li>';
        }
        html += '</ul></div>';
        return html;
    }

    function renderMatchBody(data) {
        const match = data.match;
        const minutes = Number(match.match_minutes) || 0;

        return (
            '<div class="ffb-match">' +
            '<div class="ffb-match-header">' +
            '<div class="home">' +
            '<img src="' +
            flagUrl(match.match_hometeam_nationality) +
            '" height="20" title="' +
            escapeHtml(match.match_hometeam_nationality) +
            '" alt=""> ' +
            escapeHtml(match.match_hometeam_name) +
            '</div>' +
            '<div class="result">' +
            formatMatchResult(match) +
            '</div>' +
            '<div class="guest">' +
            escapeHtml(match.match_guestteam_name) +
            ' <img src="' +
            flagUrl(match.match_guestteam_nationality) +
            '" height="20" title="' +
            escapeHtml(match.match_guestteam_nationality) +
            '" alt=""></div>' +
            '</div>' +
            '<div class="ffb-match-meta">' +
            escapeHtml(match.match_game_title) +
            ' — ' +
            escapeHtml(match.match_matchround_name) +
            '<br>' +
            escapeHtml(match.match_date || '') +
            '</div>' +
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

            const entry = { type: type, id: id, tab: opts.tab };
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
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && root && !root.hidden) {
            FfbModal.close();
        }
    });

    window.FfbModal = FfbModal;
})();
