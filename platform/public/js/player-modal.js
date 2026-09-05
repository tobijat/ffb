(function () {
    'use strict';

    if (!window.FfbModal) {
        return;
    }

    const config = window.FFB_MODAL || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';
    const MAX_ROUNDS = 15;
    let lastPlayerData = null;

    function symbolUrl(name) {
        return legacyBase + 'images/ffb/symbols/' + name;
    }

    function flagUrl(code) {
        return legacyBase + 'images/ffb/flags/' + (code || 'na').toLowerCase() + '.gif';
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

    async function fetchJson(url) {
        const res = await fetch(url, {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });
        const json = await res.json().catch(function () {
            return null;
        });
        if (!res.ok) {
            throw new Error((json && json.error) || ('HTTP ' + res.status));
        }
        return json;
    }

    function defaultCloseBtn() {
        return (
            '<button type="button" class="ffb-modal-close" data-ffb-modal-close title="Schließen" aria-label="Schließen">' +
            '<img src="' +
            symbolUrl('delete.png') +
            '" alt=""></button>'
        );
    }

    function profileRow(symbol, label, valueHtml) {
        return (
            '<div class="ffb-profile-row">' +
            '<img src="' +
            symbolUrl(symbol) +
            '" alt="">' +
            '<span class="label">' +
            escapeHtml(label) +
            '</span>' +
            '<span class="value">' +
            valueHtml +
            '</span></div>'
        );
    }

    function renderPlayerHead(player) {
        return (
            '<img class="ffb-modal-head-flag" src="' +
            flagUrl(player.player_team_nationality) +
            '" width="16" height="11" title="' +
            escapeHtml(player.player_team_nationality) +
            '" alt="">' +
            '<div class="ffb-modal-head-title">' +
            escapeHtml(player.player_name) +
            ' — <em>' +
            escapeHtml(player.player_team_name) +
            '</em></div>' +
            defaultCloseBtn()
        );
    }

    function renderPlayerTabs(playerteamId, pricemode, active) {
        let html =
            '<button type="button" class="' +
            (active === 'info' ? 'is-active' : '') +
            '" data-ffb-player-tab="info" data-id="' +
            playerteamId +
            '">Info</button>' +
            '<button type="button" class="' +
            (active === 'graphic' ? 'is-active' : '') +
            '" data-ffb-player-tab="graphic" data-id="' +
            playerteamId +
            '">Grafik</button>';
        if (pricemode === 'dynamic') {
            html +=
                '<button type="button" class="' +
                (active === 'price' ? 'is-active' : '') +
                '" data-ffb-player-tab="price" data-id="' +
                playerteamId +
                '">Preisverlauf</button>';
        }
        return html;
    }

    function formatRoundResult(row) {
        if (!row.match_id || Number(row.match_id) <= 0) {
            return '<em>nicht eingesetzt</em>';
        }
        const homePen = row.matchround_hometeam_score_penalty;
        const guestPen = row.matchround_guestteam_score_penalty;
        let score;
        if (homePen != null && homePen > -1 && guestPen != null && guestPen > -1) {
            score = escapeHtml(homePen) + ':' + escapeHtml(guestPen) + ' n.E.';
        } else {
            const hs =
                row.matchround_hometeam_score == null || Number(row.matchround_hometeam_score) < 0
                    ? '-'
                    : escapeHtml(row.matchround_hometeam_score);
            const gs =
                row.matchround_guestteam_score == null || Number(row.matchround_guestteam_score) < 0
                    ? '-'
                    : escapeHtml(row.matchround_guestteam_score);
            score = hs + ':' + gs;
        }
        return (
            escapeHtml(row.matchround_hometeam_name || '') +
            ' <a class="under" href="#" data-modal="match" data-id="' +
            escapeHtml(row.match_id) +
            '">' +
            score +
            '</a> ' +
            escapeHtml(row.matchround_guestteam_name || '')
        );
    }

    function cardCell(card) {
        if (card === 'y') {
            return '<img src="' + symbolUrl('stats_card_y.gif') + '" width="16" height="16" alt="Gelb">';
        }
        if (card === 'yr') {
            return '<img src="' + symbolUrl('stats_card_yr.gif') + '" width="16" height="16" alt="Gelb-Rot">';
        }
        if (card === 'r') {
            return '<img src="' + symbolUrl('stats_card_r.gif') + '" width="16" height="16" alt="Rot">';
        }
        return '-';
    }

    function renderRoundsTable(rows, playerteamId, showAll, limitable) {
        if (!rows || !rows.length) {
            return '';
        }
        let html =
            '<table class="ffb-profile-table ffb-player-rounds"><thead><tr>' +
            '<th><b>Runde</b></th><th><b>Ergebnis</b></th>' +
            '<th><img src="' +
            symbolUrl('stats_lineup.png') +
            '" width="16" height="16" title="Anzahl Aufstellungen" alt=""></th>' +
            '<th><img src="' +
            symbolUrl('stats_time.png') +
            '" width="16" height="16" title="Einsatz" alt=""></th>' +
            '<th><img src="' +
            symbolUrl('stats_goal.gif') +
            '" width="16" height="16" title="Tore" alt=""></th>' +
            '<th><img src="' +
            symbolUrl('stats_assist.gif') +
            '" width="16" height="16" title="Assists" alt=""></th>' +
            '<th><img src="' +
            symbolUrl('stats_card_yr.gif') +
            '" width="16" height="16" title="Karten" alt=""></th>' +
            '<th><img src="' +
            symbolUrl('stats_point.png') +
            '" width="16" height="16" title="Punkte" alt=""></th>' +
            '</tr></thead><tbody>';

        const max = limitable && !showAll ? MAX_ROUNDS : rows.length;
        for (let i = 0; i < rows.length && i < max; i++) {
            const r = rows[i];
            const title = 'Datum: ' + (r.match_date || '–');
            const roundCls = Number(r.matchround_running) === 1 ? ' style="color:#c00;"' : '';
            html +=
                '<tr><td' +
                roundCls +
                ' title="' +
                escapeHtml(title) +
                '">' +
                escapeHtml(r.matchround_title) +
                '</td>' +
                '<td class="result-cell">' +
                formatRoundResult(r) +
                '</td>' +
                '<td>' +
                escapeHtml(r.matchround_num_lineups) +
                '</td>' +
                '<td>' +
                escapeHtml(r.matchround_minutes_played) +
                '</td>' +
                '<td>' +
                escapeHtml(r.matchround_goals) +
                '</td>' +
                '<td>' +
                escapeHtml(r.matchround_assists) +
                '</td>' +
                '<td>' +
                cardCell(r.matchround_cards) +
                '</td>' +
                '<td><a class="nolink" href="#" data-modal="player-points" data-id="' +
                escapeHtml(playerteamId) +
                '" data-matchround-id="' +
                escapeHtml(r.matchround_id) +
                '"><b><u>' +
                escapeHtml(r.matchround_score) +
                '</u></b></a></td></tr>';
        }
        html += '</tbody></table>';
        if (limitable && !showAll && rows.length > MAX_ROUNDS) {
            html +=
                '<p class="ffb-player-more"><a href="#" data-modal="player" data-id="' +
                escapeHtml(playerteamId) +
                '" data-tab="info" data-show-all="1">alle Runden anzeigen</a></p>';
        }
        return html;
    }

    function renderPlayerInfoBody(data, showAll) {
        const player = data.player;
        const s = data.stats;
        const efficiency =
            s.sum_minutes > 0 ? Math.round((s.sum_score / s.sum_minutes) * 10000) / 100 : '-';

        let rows = '';
        rows += profileRow('stats_lineup.png', 'Aufstellungen gesamt:', escapeHtml(s.num_lineups) + 'x');
        rows += profileRow(
            'stats_point.png',
            'Punkte gesamt/Ø:',
            escapeHtml(s.sum_score) + '/' + escapeHtml(s.av_score) + ' Punkte'
        );
        rows += profileRow(
            'stats_goal.gif',
            'Tore gesamt/Ø:',
            escapeHtml(s.sum_goals) + '/' + escapeHtml(s.av_goals) + ' Tore'
        );
        rows += profileRow(
            'stats_assist.gif',
            'Assists gesamt/Ø:',
            escapeHtml(s.sum_assists) + '/' + escapeHtml(s.av_assists) + ' Assists'
        );
        rows += profileRow(
            'stats_card_yr.gif',
            'Karten (G/GR/R):',
            escapeHtml(s.sum_cards_y) +
                '/' +
                escapeHtml(s.sum_cards_yr) +
                '/' +
                escapeHtml(s.sum_cards_r) +
                ' Karten'
        );
        rows += profileRow(
            'stats_time.png',
            'Einsatz gesamt/Ø:',
            escapeHtml(s.sum_minutes) + '/' + escapeHtml(s.av_minutes) + ' Minuten'
        );
        rows += profileRow('symbol_effectivity.png', 'Effektivität:', escapeHtml(efficiency) + ' Punkte');

        let tables = renderRoundsTable(data.matchrounds, player.playerteam_id, showAll, true);
        if (data.pastmatches && data.pastmatches.length) {
            tables +=
                '<div class="ffb-match-section"><h3>Vergangene Spiele</h3>' +
                renderRoundsTable(data.pastmatches, player.playerteam_id, true, false) +
                '</div>';
        }

        return (
            '<div class="ffb-profile ffb-player">' +
            '<div><img class="ffb-profile-photo" src="' +
            escapeHtml(imgUrl(player.player_picture_url)) +
            '" alt="" width="100"></div>' +
            '<div class="ffb-profile-rows">' +
            rows +
            '</div></div>' +
            '<div class="ffb-player-tables">' +
            tables +
            '</div>'
        );
    }

    function renderPlayerChartBody(player, mode) {
        const chartUrl =
            legacyBase +
            'ffb/player/getPlayerInfoImg.img?playerteam_id=' +
            encodeURIComponent(player.playerteam_id) +
            (mode === 'price' ? '&type=dynamic' : '') +
            '&rnr=' +
            Math.floor(Math.random() * 11);

        let caption;
        if (mode === 'price') {
            caption =
                '<div class="ffb-player-caption">' +
                '<div><span class="cap-curve"></span> Preiskurve / <b>Ø - - -</b></div>' +
                '<div><span class="cap-red"></span> Leistungskurve / <b style="color:#c00">Ø - - -</b></div>' +
                '<div><span class="cap-avg">Ø</span> Leistung selbe Position</div></div>';
        } else {
            caption =
                '<div class="ffb-player-caption">' +
                '<div><span class="cap-points"></span><span class="cap-yellow"></span><span class="cap-red"></span> FFB Punkte &amp; gelbe/rote Karte</div>' +
                '<div><span class="cap-curve"></span> Spielminuten</div>' +
                '<div><img src="' +
                symbolUrl('stats_goal.gif') +
                '" width="14" alt=""> Tore</div>' +
                '<div><img src="' +
                symbolUrl('stats_assist.gif') +
                '" width="14" alt=""> Assists</div>' +
                '<div><span class="cap-gray"></span> nicht gespielt</div></div>';
        }

        return (
            '<div class="ffb-player-chart">' +
            '<img src="' +
            escapeHtml(chartUrl) +
            '" alt="Spielerchart: ' +
            escapeHtml(player.player_name) +
            '">' +
            caption +
            '<p class="ffb-player-chart-note">Hinweis: Chart kommt noch vom Legacy-Endpunkt und braucht ggf. Legacy-Login.</p>' +
            '</div>'
        );
    }

    function statsLine(symbol, label, amount, points, title) {
        const pts = points == null || points === '' ? '' : '<span class="pts"><b>' + points + '</b></span>';
        return (
            '<div class="ffb-player-statline"' +
            (title ? ' title="' + escapeHtml(title) + '"' : '') +
            '>' +
            (symbol
                ? '<img src="' + symbolUrl(symbol) + '" width="16" height="16" alt="">'
                : '<span class="sym-spacer"></span>') +
            '<span class="label">' +
            label +
            '</span>' +
            '<span class="amount">' +
            (amount == null ? '' : amount) +
            '</span>' +
            pts +
            '</div>'
        );
    }

    function renderPlayerRoundBody(data) {
        const player = data.player;
        if (!data.played || !data.stats) {
            return (
                '<div class="ffb-profile ffb-player">' +
                '<div><img class="ffb-profile-photo" src="' +
                escapeHtml(imgUrl(player.player_picture_url)) +
                '" alt="" width="100"></div>' +
                '<div class="ffb-profile-rows"><p>nicht eingesetzt</p></div></div>'
            );
        }

        const s = data.stats;
        let lines = '';
        let timeLabel = 'Zeit: ';
        if (s.playerstats_minute_in > 0 && s.playerstats_minute_out > 0) {
            timeLabel +=
                '<img src="' +
                symbolUrl('stats_change_in.gif') +
                '" width="16" height="11" alt=""> ' +
                s.playerstats_minute_in +
                '. ' +
                '<img src="' +
                symbolUrl('stats_change_out.gif') +
                '" width="16" height="11" alt=""> ' +
                s.playerstats_minute_out +
                '.';
        }
        timeLabel += ':&nbsp;';
        lines += statsLine(
            'stats_time.png',
            timeLabel,
            s.playerstats_minutes + ' Min',
            '+' + s.playerstats_score_minutes + ' Punkte'
        );

        if (s.playerstats_goals > 0) {
            lines += statsLine(
                'stats_goal.gif',
                'Tore:&nbsp;',
                s.playerstats_goals,
                '+' + s.playerstats_score_goals + ' Punkte'
            );
        }
        if (s.playerstats_assists > 0) {
            lines += statsLine(
                'stats_assist.gif',
                'Assists:&nbsp;',
                s.playerstats_assists,
                '+' + s.playerstats_score_assists + ' Punkte'
            );
        }
        if (s.playerstats_penaltiessaved > 0) {
            lines += statsLine(
                'stats_penaltysaved.png',
                'Elfer gehalten:&nbsp;',
                s.playerstats_penaltiessaved,
                '+' + s.playerstats_score_penaltiessaved + ' Punkte'
            );
        }
        if (s.playerstats_penaltyshootout_save > 0) {
            lines += statsLine(
                'stats_ps_hit.png',
                'Elfmeterschießen:&nbsp;',
                s.playerstats_penaltyshootout_save,
                '+' + s.playerstats_score_penaltyshootout_save + ' Punkte',
                'Elfmeterschießen - gehalten'
            );
        }
        if (s.playerstats_penaltyshootout_hit > 0) {
            lines += statsLine(
                'stats_ps_hit.png',
                'Elfmeterschießen:&nbsp;',
                s.playerstats_penaltyshootout_hit,
                '+' + s.playerstats_score_penaltyshootout_hit + ' Punkte',
                'Elfmeterschießen - getroffen'
            );
        }
        if (Number(s.playerstats_score_nooppgoals) !== 0) {
            lines += statsLine(
                'stats_oppgoal.gif',
                'Gegentore:&nbsp;',
                s.playerstats_oppgoals,
                '+' + s.playerstats_score_nooppgoals + ' Punkte'
            );
        }
        if (Number(s.playerstats_score_oppgoals) !== 0) {
            const title = s.playerstats_player_oppgoals_string
                ? 'Minuten: ' + s.playerstats_player_oppgoals_string
                : '';
            lines += statsLine(
                'stats_oppgoal.gif',
                'Gegentore:&nbsp;',
                s.playerstats_player_oppgoals,
                s.playerstats_score_oppgoals + ' Punkte',
                title
            );
        }
        if (s.playerstats_cards && s.playerstats_cards !== 'n') {
            let cardLabel = 'GELB';
            let cardSym = 'stats_card_y.gif';
            if (s.playerstats_cards === 'r') {
                cardLabel = 'ROT';
                cardSym = 'stats_card_r.gif';
            } else if (s.playerstats_cards === 'yr') {
                cardLabel = 'GELB-ROT';
                cardSym = 'stats_card_yr.gif';
            }
            lines += statsLine(cardSym, 'Karten:&nbsp;', cardLabel, s.playerstats_score_cards + ' Punkte');
        }
        if (s.playerstats_owngoals > 0) {
            lines += statsLine(
                'stats_owngoal.gif',
                'Eigentore:&nbsp;',
                s.playerstats_owngoals,
                s.playerstats_score_owngoals + ' Punkte'
            );
        }
        if (s.playerstats_penaltieslost > 0) {
            lines += statsLine(
                'stats_penaltylost.png',
                'Elfer verschossen:&nbsp;',
                s.playerstats_penaltieslost,
                s.playerstats_score_penaltieslost + ' Punkte'
            );
        }
        if (s.playerstats_penaltyshootout_lost > 0) {
            lines += statsLine(
                'stats_ps_fail.png',
                'Elfmeterschießen:&nbsp;',
                s.playerstats_penaltyshootout_lost,
                s.playerstats_score_penaltyshootout_lost + ' Punkte',
                'Elfmeterschießen - nicht getroffen'
            );
        }

        const sumPts = (s.playerstats_score >= 0 ? '+' : '') + s.playerstats_score + ' Punkte';
        lines += statsLine(null, '<b>Summe</b>', '', sumPts);

        return (
            '<div class="ffb-profile ffb-player">' +
            '<div><img class="ffb-profile-photo" src="' +
            escapeHtml(imgUrl(player.player_picture_url)) +
            '" alt="" width="100"></div>' +
            '<div class="ffb-profile-rows ffb-player-round-stats">' +
            lines +
            '</div></div>'
        );
    }

    function setModal(head, tabs, body) {
        const root = document.querySelector('.ffb-modal-root');
        if (!root) {
            return;
        }
        root.querySelector('.ffb-modal-head').innerHTML = head;
        const tabsEl = root.querySelector('.ffb-modal-tabs');
        if (!tabs) {
            tabsEl.hidden = true;
            tabsEl.innerHTML = '';
        } else {
            tabsEl.hidden = false;
            tabsEl.innerHTML = tabs;
        }
        root.querySelector('.ffb-modal-body').innerHTML = body;
    }

    function waitingUi() {
        setModal(
            '<div class="ffb-modal-head-title">lade Infos… bitte warten…</div>' + defaultCloseBtn(),
            '',
            '<p class="ffb-modal-loading">Wird geladen…</p>'
        );
    }

    async function openPlayer(playerteamId, opts) {
        const tab = (opts && opts.tab) || 'info';
        const showAll = !!(opts && (opts.showAll || opts.show_all || opts['show-all']));

        if (
            (tab === 'graphic' || tab === 'price') &&
            lastPlayerData &&
            Number(lastPlayerData.player.playerteam_id) === Number(playerteamId)
        ) {
            setModal(
                renderPlayerHead(lastPlayerData.player),
                renderPlayerTabs(playerteamId, lastPlayerData.pricemode, tab),
                renderPlayerChartBody(lastPlayerData.player, tab)
            );
            return;
        }

        waitingUi();
        const json = await fetchJson(apiBase + '/popups/player/' + encodeURIComponent(playerteamId));
        lastPlayerData = json.data;
        const data = lastPlayerData;
        const activeTab = tab === 'price' || tab === 'graphic' ? tab : 'info';

        setModal(
            renderPlayerHead(data.player),
            renderPlayerTabs(playerteamId, data.pricemode, activeTab),
            tab === 'graphic' || tab === 'price'
                ? renderPlayerChartBody(data.player, tab)
                : renderPlayerInfoBody(data, showAll)
        );
    }

    async function openPlayerPoints(playerteamId, opts) {
        const matchroundId = opts && (opts.matchroundId || opts.matchround_id || opts['matchround-id']);
        if (!matchroundId) {
            throw new Error('matchround_id is required');
        }

        waitingUi();
        const json = await fetchJson(
            apiBase +
                '/popups/player/' +
                encodeURIComponent(playerteamId) +
                '/rounds/' +
                encodeURIComponent(matchroundId)
        );
        const data = json.data;
        setModal(renderPlayerHead(data.player), '', renderPlayerRoundBody(data));
    }

    window.FfbModal.register('player', function (id, opts) {
        return openPlayer(id, opts || {});
    });

    window.FfbModal.register('player-points', function (id, opts) {
        return openPlayerPoints(id, opts || {});
    });

    document.addEventListener('click', function (e) {
        const tabBtn = e.target.closest('[data-ffb-player-tab]');
        if (tabBtn) {
            e.preventDefault();
            window.FfbModal.open({
                type: 'player',
                id: tabBtn.getAttribute('data-id'),
                tab: tabBtn.getAttribute('data-ffb-player-tab'),
            });
        }
    });
})();
