/**
 * Team shirt URLs: prefer SVG when present, fall back to PNG, then shirt_MISSING.
 * Missing files advance via onerror (data-shirt-fallbacks).
 * Empty lineup/bench placeholders use blankCandidates (shirt_BLANK / shirt_bench_BLANK).
 */
(function (global) {
    function normalizeBase(base) {
        return String(base == null || base === '' ? '/' : base).replace(/\/?$/, '/');
    }

    function normalizeNat(code) {
        return String(code || 'aut')
            .toLowerCase()
            .trim()
            .replace(/[ .]/g, '_')
            .replace(/[^a-z0-9_-]+/g, '');
    }

    function withSvgPreferred(pathWithoutExt) {
        return [pathWithoutExt + '.svg', pathWithoutExt + '.png'];
    }

    function unique(urls) {
        const seen = {};
        const out = [];
        urls.forEach(function (url) {
            if (!url || seen[url]) {
                return;
            }
            seen[url] = true;
            out.push(url);
        });
        return out;
    }

    function blankCandidates(legacyBase, red, variant) {
        const base = normalizeBase(legacyBase);
        const bench = variant === 'bench';
        const name = bench
            ? red
                ? 'shirt_bench_BLANK_RED'
                : 'shirt_bench_BLANK'
            : red
              ? 'shirt_BLANK_RED'
              : 'shirt_BLANK';

        return withSvgPreferred(base + 'images/ffb/shirts/' + name);
    }

    function missingCandidates(legacyBase) {
        const base = normalizeBase(legacyBase);

        return withSvgPreferred(base + 'images/ffb/shirts/shirt_MISSING');
    }

    /**
     * Order when league id is known:
     *   {teamcode}-{league_id}.svg → {teamcode}.svg → same stems as .png → shirt_MISSING
     * Without league id: {teamcode}.svg → {teamcode}.png → shirt_MISSING
     *
     * @param {string} legacyBase
     * @param {number|string} teamId
     * @param {string} nationality
     * @param {number|string} [gameId]
     * @returns {string[]}
     */
    function candidates(legacyBase, teamId, nationality, gameId) {
        const base = normalizeBase(legacyBase);
        const tid = Number(teamId) || 0;
        const nat = normalizeNat(nationality);
        const gid = Number(gameId) || 0;
        const urls = [];

        if (tid > 0 && nat) {
            const stem = base + 'images/ffb/shirts/' + tid + '/' + nat;
            const leagueStem = gid > 0 ? stem + '-' + gid : null;

            // SVGs first: league-specific, then plain teamcode.
            if (leagueStem) {
                urls.push(leagueStem + '.svg');
            }
            urls.push(stem + '.svg');

            // Legacy PNGs after SVGs.
            if (leagueStem) {
                urls.push(leagueStem + '.png');
            }
            urls.push(stem + '.png');
        }

        urls.push.apply(urls, missingCandidates(legacyBase));

        return unique(urls);
    }

    function encodeFallbacks(urls) {
        return encodeURIComponent(JSON.stringify(urls));
    }

    function advance(img) {
        if (!img) {
            return;
        }
        const raw = img.getAttribute('data-shirt-fallbacks');
        if (!raw) {
            img.onerror = null;
            return;
        }

        let list;
        try {
            list = JSON.parse(decodeURIComponent(raw));
        } catch (e) {
            img.onerror = null;
            img.removeAttribute('data-shirt-fallbacks');
            return;
        }

        if (!Array.isArray(list) || list.length === 0) {
            img.onerror = null;
            img.removeAttribute('data-shirt-fallbacks');
            return;
        }

        const next = list.shift();
        if (!list.length) {
            img.removeAttribute('data-shirt-fallbacks');
        } else {
            img.setAttribute('data-shirt-fallbacks', encodeFallbacks(list));
        }
        img.src = next;
    }

    function imgFromCandidates(urls, attrs) {
        const list = unique(urls || []);
        if (!list.length) {
            return '<img class="shirt" ' + (attrs || '') + ' alt="">';
        }
        const primary = list[0];
        const rest = list.slice(1);
        const fallbackAttr = rest.length
            ? ' data-shirt-fallbacks="' + encodeFallbacks(rest) + '"'
            : '';
        const onerror = rest.length
            ? ' onerror="window.FfbShirts&&window.FfbShirts.advance(this)"'
            : '';

        return (
            '<img class="shirt" src="' +
            primary +
            '" ' +
            (attrs || '') +
            fallbackAttr +
            onerror +
            '>'
        );
    }

    /**
     * @param {string} legacyBase
     * @param {number|string} teamId
     * @param {string} nationality
     * @param {string} [attrs]
     * @param {number|string} [gameId]
     */
    function imgTag(legacyBase, teamId, nationality, attrs, gameId) {
        return imgFromCandidates(candidates(legacyBase, teamId, nationality, gameId), attrs);
    }

    /**
     * Placeholder blank shirt (grey or red).
     *
     * @param {string} legacyBase
     * @param {boolean} [red]
     * @param {string} [attrs]
     * @param {'field'|'bench'} [variant='field']
     */
    function blankImg(legacyBase, red, attrs, variant) {
        return imgFromCandidates(
            blankCandidates(legacyBase, !!red, variant),
            attrs || 'alt=""'
        );
    }

    /** Primary URL only (first candidate) — prefer SVG. */
    function url(legacyBase, teamId, nationality, gameId) {
        return candidates(legacyBase, teamId, nationality, gameId)[0] || '';
    }

    function blankUrl(legacyBase, red, variant) {
        return blankCandidates(legacyBase, !!red, variant)[0] || '';
    }

    global.FfbShirts = {
        candidates: candidates,
        blankCandidates: blankCandidates,
        missingCandidates: missingCandidates,
        imgTag: imgTag,
        blankImg: blankImg,
        url: url,
        blankUrl: blankUrl,
        advance: advance,
        normalizeNat: normalizeNat,
    };
})(window);
