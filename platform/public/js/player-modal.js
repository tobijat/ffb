(function () {
    'use strict';

    if (!window.FfbModal) {
        return;
    }

    const config = window.FFB_MODAL || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';
    const selectedGameId = Number(config.selectedGameId) || 0;
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

    function renderPlayerChartShell(mode, playerName) {
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
            '<canvas class="ffb-player-chart-canvas" width="480" height="160" aria-label="Spielerchart: ' +
            escapeHtml(playerName) +
            '"></canvas>' +
            caption +
            '</div>'
        );
    }

    function loadSymbol(name) {
        return new Promise(function (resolve) {
            const img = new Image();
            img.onload = function () {
                resolve(img);
            };
            img.onerror = function () {
                resolve(null);
            };
            img.src = symbolUrl(name);
        });
    }

    function drawGraphicChart(canvas, rounds) {
        const count = rounds.length;
        const scoreDelimiter = 30;
        const baseline = 100;
        const height = 151;
        const width = Math.max(400, count * 20);
        const colLen = count > 0 ? Math.round(width / count) : 1;

        canvas.width = width + 1;
        canvas.height = height;
        canvas.style.width = '100%';
        canvas.style.maxWidth = width + 'px';
        canvas.style.height = 'auto';

        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ccffcc';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        const colors = {
            darkGreen: '#236e23',
            yellow: '#f0f014',
            red: '#f00000',
            gray: '#e6e1e1',
            darkGray: '#646464',
            orange: '#ff8000',
            blue: '#1919e1',
            black: '#000000',
        };

        Promise.all([loadSymbol('stats_goal.gif'), loadSymbol('stats_assist.gif')]).then(
            function (imgs) {
                const goalImg = imgs[0];
                const assistImg = imgs[1];
                let prevMinutesY = null;

                for (let index = 0; index < count; index++) {
                    const elem = rounds[index];
                    const x0 = index * colLen;
                    const x1 = (index + 1) * colLen;

                    ctx.strokeStyle = colors.darkGray;
                    ctx.setLineDash([2, 2]);
                    ctx.beginPath();
                    ctx.moveTo(x0, 0);
                    ctx.lineTo(x0, height);
                    ctx.stroke();
                    ctx.setLineDash([]);

                    if (elem.cards === 'y') {
                        ctx.fillStyle = colors.yellow;
                        ctx.fillRect(x0 + 1, 1, colLen - 2, height - 2);
                    } else if (elem.cards === 'r' || elem.cards === 'yr') {
                        ctx.fillStyle = colors.red;
                        ctx.fillRect(x0 + 1, 1, colLen - 2, height - 2);
                    }

                    if (elem.played) {
                        const normalized = Math.round((Number(elem.score) || 0) / (scoreDelimiter / 100));
                        let upDownStart = baseline;
                        let upDownEnd = baseline - normalized;
                        if (normalized < 0) {
                            upDownStart = 101;
                            upDownEnd = 102 + -1 * normalized;
                            ctx.fillStyle = colors.orange;
                        } else {
                            ctx.fillStyle = colors.darkGreen;
                        }
                        const top = Math.min(upDownStart, upDownEnd);
                        const bottom = Math.max(upDownStart, upDownEnd);
                        ctx.fillRect(x0 + 1, top, colLen - 2, Math.max(1, bottom - top));

                        let statsW = Math.min(15, colLen);
                        const goals = Number(elem.goals) || 0;
                        if (goalImg && goals > 0) {
                            for (let g = 1; g <= goals; g++) {
                                ctx.drawImage(
                                    goalImg,
                                    x0 + 2,
                                    upDownStart - 2 - g * 12,
                                    statsW,
                                    Math.round(statsW)
                                );
                            }
                        }
                        const assists = Number(elem.assists) || 0;
                        if (assistImg && assists > 0) {
                            for (let a = 1; a <= assists; a++) {
                                ctx.drawImage(
                                    assistImg,
                                    x0 + 2,
                                    upDownStart - 5 + a * 12,
                                    statsW,
                                    Math.round(statsW * (42 / 38))
                                );
                            }
                        }

                        ctx.fillStyle = colors.black;
                        ctx.font = '12px sans-serif';
                        ctx.fillText(String(elem.score), x0 + colLen / 2 - 4, upDownEnd - 2);

                        const minutesNorm = Math.round(((Number(elem.minutes) || 0) / 1.2 / 100) * baseline);
                        const minutesY = baseline - minutesNorm;
                        ctx.fillStyle = colors.blue;
                        ctx.fillText(String(elem.minutes), x0 + colLen / 2 - 4, minutesY + 10);

                        if (prevMinutesY != null) {
                            ctx.strokeStyle = colors.black;
                            ctx.beginPath();
                            ctx.moveTo(x0 - colLen / 2, prevMinutesY);
                            ctx.lineTo(x0 + colLen / 2, minutesY);
                            ctx.stroke();
                        }
                        prevMinutesY = minutesY;
                    } else {
                        ctx.fillStyle = colors.gray;
                        ctx.fillRect(x0 + 1, 1, colLen - 2, height - 2);
                        prevMinutesY = baseline;
                    }
                }

                ctx.strokeStyle = colors.darkGray;
                ctx.beginPath();
                ctx.moveTo(0, 0);
                ctx.lineTo(width, 0);
                ctx.moveTo(0, height - 1);
                ctx.lineTo(width, height - 1);
                ctx.moveTo(0, 101);
                ctx.lineTo(width, 101);
                ctx.stroke();

                ctx.setLineDash([2, 2]);
                ctx.beginPath();
                ctx.moveTo(width, 0);
                ctx.lineTo(width, height);
                ctx.stroke();
                ctx.setLineDash([]);

                ctx.fillStyle = colors.darkGray;
                ctx.font = '10px sans-serif';
                ctx.fillText('90min', 2, 24);
                ctx.fillStyle = colors.blue;
                ctx.font = '16px sans-serif';
                ctx.fillText('+', width - 12, baseline - 4);
                ctx.fillText('-', width - 12, baseline + 14);
            }
        );
    }

    /** Natural cubic spline Y samples (port of legacy KUBSPLINE). */
    function kubSplineY(xy, distance) {
        const n = xy.length;
        if (n < 2) {
            return xy.map(function (p) {
                return p[1];
            });
        }
        if (n === 2) {
            const out = [];
            for (let j = 0; j <= distance; j++) {
                const t = j / distance;
                out.push(xy[0][1] * (1 - t) + xy[1][1] * t);
            }
            return out;
        }

        const rs = [];
        for (let i = 1; i < n - 1; i++) {
            rs[i] =
                3 *
                ((xy[i + 1][1] - xy[i][1]) / (xy[i + 1][0] - xy[i][0]) -
                    (xy[i][1] - xy[i - 1][1]) / (xy[i][0] - xy[i - 1][0]));
        }

        const dim = n - 2;
        const A = [];
        for (let i = 1; i <= dim; i++) {
            A[i] = [];
            for (let j = 0; j <= dim; j++) {
                A[i][j] = 0;
            }
            const mi = i;
            A[i][i - 1] = xy[mi][0] - xy[mi - 1][0];
            A[i][i] = 2 * (xy[mi + 1][0] - xy[mi - 1][0]);
            A[i][i + 1] = xy[mi + 1][0] - xy[mi][0];
        }

        for (let i = 1; i <= dim; i++) {
            for (let j = i; j <= dim; j++) {
                for (let k = 1; k < i; k++) {
                    A[i][j] -= A[i][k] * A[k][j];
                }
            }
            for (let j = i + 1; j <= dim; j++) {
                for (let k = 1; k < i; k++) {
                    A[j][i] -= A[j][k] * A[k][i];
                }
                if (A[i][i] !== 0) {
                    A[j][i] /= A[i][i];
                }
            }
        }

        const Z = [];
        Z[1] = rs[1];
        for (let i = 2; i <= dim; i++) {
            Z[i] = rs[i] - A[i][i - 1] * Z[i - 1];
        }

        const B = [];
        B[dim] = dim > 0 && A[dim][dim] !== 0 ? Z[dim] / A[dim][dim] : 0;
        for (let i = dim - 1; i >= 1; i--) {
            let tmp = Z[i];
            for (let j = i + 1; j <= dim; j++) {
                tmp -= A[i][j] * B[j];
            }
            B[i] = A[i][i] !== 0 ? tmp / A[i][i] : 0;
        }
        B[0] = 0;
        B[dim + 1] = 0;

        const C = [];
        const AA = [];
        for (let i = 0; i <= dim; i++) {
            const dx = xy[i + 1][0] - xy[i][0];
            C[i] =
                (xy[i + 1][1] - xy[i][1]) / dx -
                ((B[i + 1] - B[i]) * dx) / 3.0 -
                B[i] * dx;
            AA[i] = (B[i + 1] - B[i]) / (3.0 * dx);
        }

        const Y = [];
        for (let i = 0; i < n - 1; i++) {
            for (let j = i * distance; j <= (i + 1) * distance - 1; j++) {
                const dx = j - xy[i][0];
                Y.push(AA[i] * Math.pow(dx, 3) + B[i] * Math.pow(dx, 2) + C[i] * dx + xy[i][1]);
            }
        }
        Y.push(xy[n - 1][1]);
        return Y;
    }

    function drawPriceChart(canvas, points) {
        const count = points.length;
        const scoreDelimiter = 22;
        const baseline = 100;
        const imgLength = 400;
        const imgHeight = 121;
        const imgZeroLine = 101;
        const extraLen = 40;
        const colLen = count > 1 ? Math.round(imgLength / (count - 1)) : imgLength;

        canvas.width = imgLength + extraLen;
        canvas.height = imgHeight + 24;
        canvas.style.width = '100%';
        canvas.style.maxWidth = canvas.width + 'px';
        canvas.style.height = 'auto';

        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ccffcc';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        let sumPrice = 0;
        let sumPower = 0;
        const xyPrice = [];
        const xyPower = [];

        for (let index = 0; index < count; index++) {
            const elem = points[index];
            const avNorm = Math.round(Number(elem.av_power) / (scoreDelimiter / 100));
            ctx.fillStyle = 'rgba(175, 240, 240, 0.55)';
            ctx.fillRect(index * colLen - colLen + 1, baseline - avNorm, colLen - 2, avNorm);

            ctx.strokeStyle = '#646464';
            ctx.setLineDash([2, 2]);
            ctx.beginPath();
            ctx.moveTo(index * colLen, 0);
            ctx.lineTo(index * colLen, imgZeroLine);
            ctx.stroke();
            ctx.setLineDash([]);

            sumPrice += Number(elem.price) || 0;
            sumPower += Number(elem.power) || 0;

            const priceNorm = Math.round(Number(elem.price) / (scoreDelimiter / 100));
            const powerNorm = Math.round(Number(elem.power) / (scoreDelimiter / 100));
            xyPrice.push([colLen * index, priceNorm]);
            xyPower.push([colLen * index, powerNorm]);

            const marginBlack = index % 2 === 0 ? -10 : 2;
            const marginRed = index % 2 === 0 ? 2 : -10;
            ctx.font = '10px sans-serif';
            ctx.fillStyle = '#000';
            ctx.fillText(
                Number(elem.price).toFixed(1),
                index * colLen + 2,
                baseline - priceNorm + marginBlack + 8
            );
            ctx.fillStyle = '#d20000';
            ctx.fillText(
                Number(elem.power).toFixed(1),
                index * colLen + 2,
                baseline - powerNorm + marginRed + 8
            );
        }

        if (count > 1) {
            const yPrice = kubSplineY(xyPrice, colLen);
            const yPower = kubSplineY(xyPower, colLen);
            ctx.lineWidth = 2;
            ctx.strokeStyle = '#000';
            ctx.beginPath();
            for (let i = 0; i < yPrice.length; i++) {
                const y = baseline - yPrice[i];
                if (i === 0) {
                    ctx.moveTo(i, y);
                } else {
                    ctx.lineTo(i, y);
                }
            }
            ctx.stroke();

            ctx.strokeStyle = '#d20000';
            ctx.beginPath();
            for (let i = 0; i < yPower.length; i++) {
                const y = baseline - yPower[i];
                if (i === 0) {
                    ctx.moveTo(i, y);
                } else {
                    ctx.lineTo(i, y);
                }
            }
            ctx.stroke();
            ctx.lineWidth = 1;

            const avPriceY = Math.round(baseline - sumPrice / count / (scoreDelimiter / 100));
            const avPowerY = Math.round(baseline - sumPower / count / (scoreDelimiter / 100));
            for (let i = 0; i < imgLength; i += 10) {
                ctx.strokeStyle = '#000';
                ctx.beginPath();
                ctx.moveTo(i, avPriceY);
                ctx.lineTo(i + 2, avPriceY);
                ctx.stroke();
                ctx.strokeStyle = '#d20000';
                ctx.beginPath();
                ctx.moveTo(i, avPowerY);
                ctx.lineTo(i + 2, avPowerY);
                ctx.stroke();
            }
        }

        ctx.strokeStyle = '#646464';
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(imgLength + 40, 0);
        ctx.moveTo(0, imgHeight - 1);
        ctx.lineTo(imgLength + 40, imgHeight - 1);
        ctx.moveTo(0, imgZeroLine);
        ctx.lineTo(Math.round(imgLength + colLen / 2), imgZeroLine);
        ctx.stroke();

        ctx.fillStyle = '#1919e1';
        ctx.font = '16px sans-serif';
        ctx.fillText('+', imgLength + 10, baseline - 2);
        ctx.fillText('-', imgLength + 10, baseline + 14);
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

        waitingUi();

        if (
            !lastPlayerData ||
            Number(lastPlayerData.player.playerteam_id) !== Number(playerteamId)
        ) {
            const json = await fetchJson(
                apiBase + '/popups/player/' + encodeURIComponent(playerteamId)
            );
            lastPlayerData = json.data;
        }

        const data = lastPlayerData;
        const activeTab = tab === 'price' || tab === 'graphic' ? tab : 'info';

        if (activeTab === 'graphic') {
            if (selectedGameId <= 0) {
                throw new Error('game_id is required');
            }
            const chartJson = await fetchJson(
                apiBase +
                    '/popups/player/' +
                    encodeURIComponent(playerteamId) +
                    '/chart?game_id=' +
                    encodeURIComponent(selectedGameId)
            );
            setModal(
                renderPlayerHead(data.player),
                renderPlayerTabs(playerteamId, data.pricemode, 'graphic'),
                renderPlayerChartShell('graphic', data.player.player_name)
            );
            const canvas = document.querySelector('.ffb-player-chart-canvas');
            if (canvas) {
                drawGraphicChart(canvas, chartJson.data.rounds || []);
            }
            return;
        }

        if (activeTab === 'price') {
            if (selectedGameId <= 0) {
                throw new Error('game_id is required');
            }
            const priceJson = await fetchJson(
                apiBase +
                    '/popups/player/' +
                    encodeURIComponent(playerteamId) +
                    '/prices?game_id=' +
                    encodeURIComponent(selectedGameId)
            );
            setModal(
                renderPlayerHead(data.player),
                renderPlayerTabs(playerteamId, data.pricemode, 'price'),
                renderPlayerChartShell('price', data.player.player_name)
            );
            const canvas = document.querySelector('.ffb-player-chart-canvas');
            if (canvas) {
                drawPriceChart(canvas, priceJson.data.points || []);
            }
            return;
        }

        setModal(
            renderPlayerHead(data.player),
            renderPlayerTabs(playerteamId, data.pricemode, 'info'),
            renderPlayerInfoBody(data, showAll)
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
