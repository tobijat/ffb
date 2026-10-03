(function (global) {
    const BENCH_ASPECT = 104 / 729;
    let fieldObserver = null;

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

    function selectedLeagueId() {
        return (
            Number(
                (global.FFB_LINEUP && global.FFB_LINEUP.selectedLeagueId) ||
                    (global.FFB_MYTEAM && global.FFB_MYTEAM.selectedLeagueId) ||
                    0
            ) || 0
        );
    }

    function blankSlot(legacyBase, red) {
        const shirt =
            global.FfbShirts && typeof global.FfbShirts.blankImg === 'function'
                ? global.FfbShirts.blankImg(
                      legacyBase,
                      red,
                      'width="55" height="50" alt=""',
                      'bench'
                  )
                : '<img class="shirt" src="' +
                  legacyBase +
                  'images/ffb/shirts/' +
                  (red ? 'shirt_bench_BLANK_RED.svg' : 'shirt_bench_BLANK.svg') +
                  '" width="55" height="50" alt="">';
        return (
            '<div class="pitch-player pitch-slot pitch-bench-slot">' +
            shirt +
            '<span class="name">Ersatz</span>' +
            '</div>'
        );
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function shirtImg(legacyBase, teamId, nationality) {
        if (global.FfbShirts && typeof global.FfbShirts.imgTag === 'function') {
            return global.FfbShirts.imgTag(
                legacyBase,
                teamId,
                nationality,
                'width="55" height="50" alt=""',
                selectedLeagueId()
            );
        }
        const blank = legacyBase + 'images/ffb/shirts/shirt_MISSING.svg';
        return (
            '<img class="shirt" src="' +
            blank +
            '" width="55" height="50" alt="">'
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

    function editPlayerCard(player, legacyBase) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamId = player.playerteam_team_id;
        const price = playerPrice(player);

        return (
            '<div class="pitch-player pitch-bench-slot">' +
            '<a href="#" data-remove-bench="' +
            player.playerteam_id +
            '" title="Klicken um Ersatzspieler zu entfernen">' +
            shirtImg(legacyBase, teamId, nat) +
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
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '"><img src="' +
            legacyBase +
            'images/ffb/symbols/info.png" width="16" height="16" alt="Info"></a>' +
            '</div></div>'
        );
    }

    function viewPlayerCard(player, legacyBase, showPrice, matchroundId) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamId = player.playerteam_team_id;
        const price = playerPrice(player);
        const priceHtml = showPrice
            ? '<span title="Preis: ' + price + ' Credits">' + price + '</span>'
            : '';

        return (
            '<div class="pitch-player pitch-bench-slot">' +
            '<a href="#" data-modal="player" data-id="' +
            player.playerteam_id +
            '">' +
            shirtImg(legacyBase, teamId, nat) +
            '</a>' +
            '<span class="name">' +
            escapeHtml(player.player_fname || '') +
            '<br>' +
            escapeHtml(player.player_lname || '') +
            '</span>' +
            '<a class="score" href="#" data-modal="player-points" data-id="' +
            player.playerteam_id +
            '" data-matchround-id="' +
            Number(matchroundId || 0) +
            '">' +
            Number(player.playerstats_score || 0) +
            ' Punkte</a>' +
            '<div class="meta">' +
            priceHtml +
            flagHtml(legacyBase, nat, player.playerteam_team || '') +
            '</div></div>'
        );
    }

    function matchBenchToField() {
        const field = document.getElementById('soccer-field');
        const bench = document.getElementById('soccer-bench');
        const line = document.getElementById('line-bench');
        const fieldG = field ? field.querySelector('.field-g') : null;
        if (!field || !bench || bench.hidden) {
            return;
        }

        const fieldHeight = Math.round(field.getBoundingClientRect().height);
        if (fieldHeight <= 0) {
            return;
        }

        const benchWidth = Math.max(1, Math.round(fieldHeight * BENCH_ASPECT));
        bench.style.height = fieldHeight + 'px';
        bench.style.width = benchWidth + 'px';
        bench.style.flexBasis = benchWidth + 'px';

        if (line && fieldG) {
            const fieldRect = field.getBoundingClientRect();
            const gRect = fieldG.getBoundingClientRect();
            const topPx = Math.max(0, Math.round(gRect.top - fieldRect.top));
            line.style.top = topPx + 'px';
        }
    }

    function watchField() {
        const field = document.getElementById('soccer-field');
        if (!field || typeof ResizeObserver === 'undefined') {
            return;
        }

        if (fieldObserver) {
            return;
        }

        fieldObserver = new ResizeObserver(function () {
            matchBenchToField();
        });
        fieldObserver.observe(field);
    }

    function clearBenchSize(bench) {
        bench.style.removeProperty('height');
        bench.style.removeProperty('width');
        bench.style.removeProperty('flex-basis');
        const line = document.getElementById('line-bench');
        if (line) {
            line.style.removeProperty('top');
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
        requestAnimationFrame(matchBenchToField);
    }

    global.FfbPitchBench = {
        isActive: isActive,
        sync: sync,
        matchBenchToField: matchBenchToField,
    };
})(window);
