/**
 * Team shirt URLs: prefer SVG when present, fall back to PNG, then shirt_MISSING.
 * Missing files advance via onerror (data-shirt-fallbacks).
 * Empty lineup/bench placeholders use blankCandidates (shirt_BLANK / shirt_bench_BLANK).
 *
 * Paths use immutable asset keys (not numeric DB ids):
 *   shirts/{team_key}/{nat}-{league_key}.svg|png
 *   shirts/{team_key}/{nat}.svg|png
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

    function normalizeKey(key) {
        const value = String(key == null ? '' : key).trim();
        if (!value || !/^[a-z0-9]+(?:-[a-z0-9]+)*$/i.test(value)) {
            return '';
        }

        return value.toLowerCase();
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
     * @param {string} legacyBase
     * @param {string} teamKey
     * @param {string} nationality
     * @param {string} [leagueKey]
     * @returns {string[]}
     */
    function candidates(legacyBase, teamKey, nationality, leagueKey) {
        const base = normalizeBase(legacyBase);
        const tid = normalizeKey(teamKey);
        const nat = normalizeNat(nationality);
        const lid = normalizeKey(leagueKey);
        const urls = [];

        if (tid && nat) {
            const stem = base + 'images/ffb/shirts/' + tid + '/' + nat;
            const leagueStem = lid ? stem + '-' + lid : null;

            if (leagueStem) {
                urls.push(leagueStem + '.svg');
            }
            urls.push(stem + '.svg');

            if (leagueStem) {
                urls.push(leagueStem + '.png');
            }
            urls.push(stem + '.png');
        }

        urls.push.apply(urls, missingCandidates(legacyBase));

        return unique(urls);
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

    function encodeFallbacks(urls) {
        return encodeURIComponent(JSON.stringify(urls));
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
     * @param {string} teamKey
     * @param {string} nationality
     * @param {string} [attrs]
     * @param {string} [leagueKey]
     */
    function imgTag(legacyBase, teamKey, nationality, attrs, leagueKey) {
        return imgFromCandidates(candidates(legacyBase, teamKey, nationality, leagueKey), attrs);
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
    function url(legacyBase, teamKey, nationality, leagueKey) {
        return candidates(legacyBase, teamKey, nationality, leagueKey)[0] || '';
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
        normalizeKey: normalizeKey,
    };
})(window);
