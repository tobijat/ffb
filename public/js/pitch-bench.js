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

    function blankSlot(legacyBase, red) {
        const src = red ? 'shirt_BLANK_RED.png' : 'shirt_BLANK.png';
        return (
            '<div class="pitch-player pitch-slot pitch-bench-slot">' +
            '<img class="shirt" src="' +
            legacyBase +
            'images/ffb/shirts/' +
            src +
            '" width="55" height="50" alt="">' +
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

    function shirtUrl(legacyBase, teamId, nationality) {
        const tid = Number(teamId) || 0;
        const nat = String(nationality || 'AUT').toUpperCase();
        if (tid <= 0) {
            return legacyBase + 'images/ffb/shirts/shirt_BLANK.png';
        }

        return legacyBase + 'images/ffb/shirts/' + tid + '/' + nat + '.png';
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

    function playerCard(player, legacyBase) {
        const nat = player.playerteam_team_nationality || 'AUT';
        const teamId = player.playerteam_team_id;

        return (
            '<div class="pitch-player pitch-bench-slot">' +
            '<a href="#" data-remove-bench="' +
            player.playerteam_id +
            '" title="Klicken um Ersatzspieler zu entfernen">' +
            '<img class="shirt" src="' +
            shirtUrl(legacyBase, teamId, nat) +
            '" width="55" height="50" alt="" ' +
            'onerror="this.onerror=null;this.src=\'' +
            legacyBase +
            'images/ffb/shirts/shirt_BLANK.png\';">' +
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
            player.player_price +
            ' Credits">' +
            player.player_price +
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
            fieldObserver.disconnect();
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
     */
    function sync(options, legacyBase, players) {
        const stage = document.getElementById('pitch-stage');
        const bench = document.getElementById('soccer-bench');
        const line = document.getElementById('line-bench');
        if (!stage || !bench || !line) {
            return;
        }

        if (!isActive(options)) {
            stage.classList.remove('has-bench');
            bench.hidden = true;
            line.innerHTML = '';
            clearBenchSize(bench);
            if (fieldObserver) {
                fieldObserver.disconnect();
                fieldObserver = null;
            }
            return;
        }

        const limits = benchLimits(options);
        const selected = Array.isArray(players) ? players.slice(0, limits.max) : [];
        const needMin = Math.max(0, Math.min(limits.min, limits.max) - selected.length);
        const needBlank = Math.max(0, limits.max - selected.length - needMin);
        let html = selected
            .map(function (player) {
                return playerCard(player, legacyBase || '/');
            })
            .join('');
        for (let i = 0; i < needMin; i++) {
            html += blankSlot(legacyBase || '/', true);
        }
        for (let i = 0; i < needBlank; i++) {
            html += blankSlot(legacyBase || '/', false);
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
