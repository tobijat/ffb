(function (global) {
    let fieldObserver = null;
    /** Min side-strip width before switching bench under the field (player tile ≈ 90px). */
    const BENCH_SIDE_MIN = 92;
    const BENCH_SIDE_RATIO = 104 / (647 + 104);

    function benchLimits(options) {
        const min = Math.max(0, Number(options && options.lineup_min_bench) || 0);
        const max = Math.max(0, Number(options && options.lineup_max_bench) || 0);

        return { min: min, max: max };
    }

    function isActive(options) {
        if (!options || !options.league_benchmode) {
            return false;
        }

        return benchLimits(options).max > 0;
    }

    function selectedLeagueAssetKey() {
        return String(
            (global.FFB_LINEUP && global.FFB_LINEUP.selectedLeagueAssetKey) ||
                (global.FFB_MYTEAM && global.FFB_MYTEAM.selectedLeagueAssetKey) ||
                (global.FFB_BESTTEAM && global.FFB_BESTTEAM.selectedLeagueAssetKey) ||
                ''
        );
    }

    function blankSlot(legacyBase, red) {
        const shirt =
            global.FfbShirts && typeof global.FfbShirts.blankImg === 'function'
                ? global.FfbShirts.blankImg(
                      legacyBase,
                      red,
                      'width="64" height="64" alt=""',
                      'bench'
                  )
                : '<img class="shirt" src="' +
                  legacyBase +
                  'images/ffb/shirts/' +
                  (red ? 'shirt_bench_BLANK_RED.svg' : 'shirt_bench_BLANK.svg') +
                  '" width="64" height="64" alt="">';
        return (
            '<div class="pitch-player pitch-slot pitch-bench-slot">' +
            shirt +
            '<span class="name">Ersatz</span>' +
            '<div class="meta" aria-hidden="true"></div></div>'
        );
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function shirtImg(legacyBase, teamKey, nationality) {
        if (global.FfbShirts && typeof global.FfbShirts.imgTag === 'function') {
            return global.FfbShirts.imgTag(
                legacyBase,
                teamKey,
                nationality,
                'width="64" height="64" alt=""',
                selectedLeagueAssetKey()
            );
        }
        const blank = legacyBase + 'images/ffb/shirts/shirt_MISSING.svg';
        return (
            '<img class="shirt" src="' +
            blank +
            '" width="64" height="64" alt="">'
        );
    }

    function flagHtml(legacyBase, code, title) {
        if (window.FfbFlags && typeof window.FfbFlags.html === 'function') {
            return window.FfbFlags.html(code, title ? { title: title } : undefined);
        }
        const flag = (code && code !== '0' ? String(code) : 'na').toLowerCase();
        const src = legacyBase + 'images/ffb/flags/' + flag + '.gif';
        const titleAttr = title ? ' title="' + escapeHtml(title) + '"' : '';
        return (
            '<img class="ffb-flag ffb-flag-img" src="' +
            src +
            '" alt="" width="16" height="11" loading="lazy"' +
            titleAttr +
            '>'
        );
    }

    function playerPrice(player) {
        if (player.playerteam_player_price != null) {
            return Number(player.playerteam_player_price);
        }

        return Number(player.player_price || 0);
    }

    function inactiveSquadWarningText(player) {
        if (Number(player.playerteam_status) === 1) {
            return '';
        }
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

    function playerInfoLinkHtml(player, legacyBase) {
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
            legacyBase +
            'images/ffb/symbols/' +
            icon +
            '" width="16" height="16" alt="Info"></a>'
        );
    }

    function positionLetter(player) {
        const pos = String(
            player.playerteam_player_position || player.player_position || ''
        ).toLowerCase();
        const labels = { g: 'T', d: 'V', m: 'M', s: 'S' };

        return labels[pos] || '';
    }

    function positionTitle(player) {
        const pos = String(
            player.playerteam_player_position || player.player_position || ''
        ).toLowerCase();
        const titles = {
            g: 'Tor',
            d: 'Verteidigung',
            m: 'Mittelfeld',
            s: 'Sturm',
        };

        return titles[pos] || '';
    }

    function positionMetaHtml(player) {
        const letter = positionLetter(player);
        if (!letter) {
            return '';
        }
        const title = positionTitle(player);
        const titleAttr = title ? ' title="' + escapeHtml(title) + '"' : '';

        return '<span class="pos"' + titleAttr + '>' + letter + '</span>';
    }

    function changeIconHtml(player, legacyBase) {
        const change = player && player.change;
        if (!change || (change.type !== 'in' && change.type !== 'out')) {
            return '';
        }
        const related = String(change.related_name || '').trim();
        const title =
            change.type === 'in'
                ? 'Eingewechselt für ' + related
                : 'Ersetzt durch ' + related;
        const icon = change.type === 'in' ? 'stats_change_in.svg' : 'stats_change_out.svg';

        return (
            '<img src="' +
            legacyBase +
            'images/ffb/symbols/' +
            icon +
            '" width="16" height="11" alt="" title="' +
            escapeHtml(title) +
            '">'
        );
    }

    function editPlayerCard(player, legacyBase) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamKey = player.playerteam_team_asset_key || '';
        const price = playerPrice(player);

        return (
            '<div class="pitch-player pitch-bench-slot">' +
            '<a href="#" data-remove-bench="' +
            player.playerteam_id +
            '" title="Klicken um Ersatzspieler zu entfernen">' +
            shirtImg(legacyBase, teamKey, nat) +
            '</a>' +
            '<a class="name" href="#" data-remove-bench="' +
            player.playerteam_id +
            '" title="Klicken um Ersatzspieler zu entfernen">' +
            escapeHtml(player.player_fname || '') +
            '<br>' +
            escapeHtml(player.player_lname || '') +
            '</a>' +
            '<div class="meta">' +
            '<span title="Preis: ' +
            price +
            ' Credits">' +
            price +
            '</span>' +
            flagHtml(legacyBase, nat, player.playerteam_team || '') +
            playerInfoLinkHtml(player, legacyBase) +
            positionMetaHtml(player) +
            '</div></div>'
        );
    }

    function viewPlayerCard(player, legacyBase, showPrice, matchroundId) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamKey = player.playerteam_team_asset_key || '';
        const price = playerPrice(player);
        const priceHtml = showPrice
            ? '<span title="Preis: ' + price + ' Credits">' + price + '</span>'
            : '';

        const roundId = Number(matchroundId || 0);
        const roundAttr = roundId > 0 ? '" data-matchround-id="' + roundId : '';

        return (
            '<div class="pitch-player pitch-bench-slot">' +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            roundAttr +
            '">' +
            shirtImg(legacyBase, teamKey, nat) +
            '</a>' +
            '<span class="name">' +
            escapeHtml(player.player_fname || '') +
            '<br>' +
            escapeHtml(player.player_lname || '') +
            '</span>' +
            '<a class="score" href="#" data-modal="player-points" data-id="' +
            player.playerteam_id +
            '" data-matchround-id="' +
            roundId +
            '">' +
            Number(player.playerstats_score || 0) +
            ' Punkte</a>' +
            '<div class="meta">' +
            priceHtml +
            flagHtml(legacyBase, nat, player.playerteam_team || '') +
            changeIconHtml(player, legacyBase) +
            positionMetaHtml(player) +
            '</div></div>'
        );
    }

    function updateBenchOrientation() {
        const stage = document.getElementById('pitch-stage');
        const bench = document.getElementById('soccer-bench');
        if (!stage || !bench || bench.hidden || !stage.classList.contains('has-bench')) {
            return;
        }

        const gap = 4;
        const stageW = stage.getBoundingClientRect().width;
        const sideW = Math.max(0, (stageW - gap) * BENCH_SIDE_RATIO);
        stage.classList.toggle('bench-below', sideW < BENCH_SIDE_MIN);
    }

    function matchBenchToField() {
        const field = document.getElementById('soccer-field');
        const bench = document.getElementById('soccer-bench');
        const line = document.getElementById('line-bench');
        if (!field || !bench || bench.hidden) {
            return;
        }

        updateBenchOrientation();

        // Cards are in-flow; padding handles top inset — clear any legacy offsets.
        if (line) {
            line.style.removeProperty('top');
        }
        bench.style.removeProperty('height');
        bench.style.removeProperty('width');
        bench.style.removeProperty('flex-basis');
    }

    function watchField() {
        const stage = document.getElementById('pitch-stage');
        const field = document.getElementById('soccer-field');
        if ((!stage && !field) || typeof ResizeObserver === 'undefined') {
            return;
        }

        if (fieldObserver) {
            return;
        }

        fieldObserver = new ResizeObserver(function () {
            matchBenchToField();
        });
        if (stage) {
            fieldObserver.observe(stage);
        }
        if (field) {
            fieldObserver.observe(field);
        }
        const bench = document.getElementById('soccer-bench');
        if (bench) {
            fieldObserver.observe(bench);
        }
    }

    function clearBenchSize(bench) {
        if (!bench) {
            return;
        }
        bench.style.removeProperty('height');
        bench.style.removeProperty('width');
        bench.style.removeProperty('flex-basis');
        const line = document.getElementById('line-bench');
        if (line) {
            line.style.removeProperty('top');
        }
        const stage = document.getElementById('pitch-stage');
        if (stage) {
            stage.classList.remove('bench-below');
        }
    }

    /**
     * @param {object|null} options
     * @param {string} legacyBase
     * @param {Array<object>} [players]
     * @param {{mode?: string, showPrice?: boolean, matchroundId?: number}} [display]
     */
    function sync(options, legacyBase, players, display) {
        const stage = document.getElementById('pitch-stage');
        const bench = document.getElementById('soccer-bench');
        const line = document.getElementById('line-bench');
        if (!stage || !bench || !line) {
            return;
        }

        display = display || {};
        const viewMode = display.mode === 'view';
        const showPrice = display.showPrice !== false;
        const matchroundId = Number(display.matchroundId || 0);

        function hideBench() {
            stage.classList.remove('has-bench');
            bench.hidden = true;
            line.innerHTML = '';
            clearBenchSize(bench);
            if (fieldObserver) {
                fieldObserver.disconnect();
                fieldObserver = null;
            }
        }

        if (!isActive(options)) {
            hideBench();
            return;
        }

        const limits = benchLimits(options);
        const selected = Array.isArray(players) ? players.slice(0, limits.max) : [];

        // View pages only show filled substitute cards (no empty placeholders).
        if (viewMode && selected.length === 0) {
            hideBench();
            return;
        }

        let html = selected
            .map(function (player) {
                return viewMode
                    ? viewPlayerCard(player, legacyBase || '/', showPrice, matchroundId)
                    : editPlayerCard(player, legacyBase || '/');
            })
            .join('');

        if (!viewMode) {
            const needMin = Math.max(0, Math.min(limits.min, limits.max) - selected.length);
            const needBlank = Math.max(0, limits.max - selected.length - needMin);
            for (let i = 0; i < needMin; i++) {
                html += blankSlot(legacyBase || '/', true);
            }
            for (let i = 0; i < needBlank; i++) {
                html += blankSlot(legacyBase || '/', false);
            }
        }

        stage.classList.add('has-bench');
        line.innerHTML = html;
        bench.hidden = false;
        watchField();
        matchBenchToField();
        requestAnimationFrame(function () {
            matchBenchToField();
            requestAnimationFrame(matchBenchToField);
        });
    }

    let pitchInfoLayoutObserver = null;

    function naturalPitchInfoWidth(el) {
        if (!el || el.hidden) {
            return 0;
        }
        const previous = el.style.width;
        el.style.width = 'max-content';
        const width = el.getBoundingClientRect().width;
        el.style.width = previous;
        return width;
    }

    function updatePitchInfoLayout(root) {
        const scope = root && root.querySelector ? root : document;
        const info = scope.querySelector
            ? scope.querySelector('.pitch-info')
            : document.querySelector('.pitch-info');
        if (!info) {
            return;
        }

        info.classList.remove('is-stacked');

        const round = info.querySelector('.pitch-info-round');
        const center = info.querySelector('.pitch-info-center');
        const right =
            info.querySelector(':scope > .pitch-stats-group') ||
            info.querySelector(':scope > .pitch-stats');
        if (!round || !center) {
            return;
        }

        const style = global.getComputedStyle(info);
        const gap = parseFloat(style.columnGap) || 8;
        const roundW = naturalPitchInfoWidth(round);
        const centerW = naturalPitchInfoWidth(center);
        const rightW = naturalPitchInfoWidth(right);
        const parts = [roundW, centerW, rightW].filter(function (w) {
            return w > 0;
        });
        if (parts.length < 2) {
            return;
        }

        const need =
            parts.reduce(function (sum, w) {
                return sum + w;
            }, 0) +
            gap * (parts.length - 1);

        if (need > info.clientWidth + 0.5) {
            info.classList.add('is-stacked');
        }
    }

    function watchPitchInfoLayout(root) {
        const cq =
            (root && root.querySelector && root.querySelector('.pitch-info-cq')) ||
            document.querySelector('.pitch-info-cq');
        if (!cq || typeof ResizeObserver === 'undefined') {
            updatePitchInfoLayout(root || document);
            return;
        }
        if (!pitchInfoLayoutObserver) {
            pitchInfoLayoutObserver = new ResizeObserver(function () {
                updatePitchInfoLayout(document);
            });
        }
        pitchInfoLayoutObserver.observe(cq);
        updatePitchInfoLayout(root || document);
    }

    global.FfbPitchBench = {
        isActive: isActive,
        sync: sync,
        matchBenchToField: matchBenchToField,
    };

    global.FfbPitchInfoBar = {
        update: updatePitchInfoLayout,
        watch: watchPitchInfoLayout,
    };
})(window);
