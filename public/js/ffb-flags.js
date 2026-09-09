/**
 * Shared nationality / club emblem flags.
 * Nationals: vendored flag-icons SVGs via CSS.
 * Clubs / unmapped codes: GIF fallback.
 * Expects window.FFB_FLAGS = { base: '/', iso: { AUT: 'at', ... } }.
 */
(function (global) {
    function config() {
        var src = global.FFB_FLAGS || {};
        return {
            base: src.base || '/',
            iso: src.iso || {},
        };
    }

    function normalize(code) {
        var raw = String(code == null ? '' : code).trim().toUpperCase();
        if (raw === '' || raw === '0' || raw === 'NA') {
            return '';
        }
        return raw;
    }

    function escapeAttr(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function iso(code) {
        var key = normalize(code);
        if (!key) {
            return null;
        }
        var mapped = config().iso[key];
        return mapped ? String(mapped) : null;
    }

    function imageUrl(code) {
        var raw = String(code == null ? '' : code).trim().toLowerCase();
        if (raw === '' || raw === '0') {
            raw = 'na';
        }
        var base = String(config().base || '/').replace(/\/?$/, '/');
        return base + 'images/ffb/flags/' + raw + '.gif';
    }

    function svgUrl(code) {
        var mapped = iso(code);
        if (!mapped) {
            return null;
        }
        var base = String(config().base || '/').replace(/\/?$/, '/');
        return base + 'vendor/flag-icons/flags/4x3/' + mapped + '.svg';
    }

    /**
     * @param {string|null|undefined} code
     * @param {{ title?: string, className?: string }} [options]
     */
    function html(code, options) {
        options = options || {};
        var title = options.title ? ' title="' + escapeAttr(options.title) + '"' : '';
        var extra = options.className ? ' ' + options.className : '';
        var svg = svgUrl(code);

        if (svg) {
            return (
                '<span class="ffb-flag ffb-flag-svg' +
                extra +
                '" style="--ffb-flag:url(\'' +
                escapeAttr(svg) +
                '\')" role="img" aria-hidden="true"' +
                title +
                '></span>'
            );
        }

        return (
            '<img class="ffb-flag ffb-flag-img' +
            extra +
            '" src="' +
            escapeAttr(imageUrl(code)) +
            '" alt="" width="16" height="11" loading="lazy"' +
            title +
            '>'
        );
    }

    global.FfbFlags = {
        normalize: normalize,
        iso: iso,
        imageUrl: imageUrl,
        svgUrl: svgUrl,
        html: html,
    };
})(window);
