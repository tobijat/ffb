(function (global) {
    const BENCH_ASPECT = 104 / 729;
    let fieldObserver = null;

    function benchLimits(options) {
        const min = Math.max(0, Number(options.lineup_min_bench) || 0);
        const max = Math.max(0, Number(options.lineup_max_bench) || 0);

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

        // Match the goalkeeper row offset exactly (top % on field-g is height-based).
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

    function sync(options, legacyBase) {
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
        const redCount = Math.min(limits.min, limits.max);
        const blankCount = Math.max(0, limits.max - redCount);
        let html = '';
        for (let i = 0; i < redCount; i++) {
            html += blankSlot(legacyBase || '/', true);
        }
        for (let i = 0; i < blankCount; i++) {
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
