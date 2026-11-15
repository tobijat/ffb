(function (global) {
    'use strict';

    function trim(value) {
        return String(value == null ? '' : value).trim();
    }

    function defaultFromParts(fname, lname) {
        fname = trim(fname);
        lname = trim(lname);
        if (!fname && !lname) return '';
        if (fname === lname) return fname || lname;
        if (!fname) return lname;
        if (!lname) return fname;
        return fname + ' ' + lname;
    }

    function full(player) {
        player = player || {};
        const stored = trim(player.player_display_name);
        if (stored) return stored;
        return defaultFromParts(player.player_fname, player.player_lname);
    }

    /**
     * Two-line pitch layout: first token / remainder (fallback to fname/lname).
     * @returns {{line1: string, line2: string}}
     */
    function lines(player) {
        player = player || {};
        const stored = trim(player.player_display_name);
        if (stored) {
            const parts = stored.split(/\s+/).filter(Boolean);
            if (parts.length <= 1) {
                return { line1: stored, line2: '' };
            }
            return { line1: parts[0], line2: parts.slice(1).join(' ') };
        }
        return {
            line1: trim(player.player_fname),
            line2: trim(player.player_lname) === trim(player.player_fname)
                ? ''
                : trim(player.player_lname),
        };
    }

    function linesHtml(player, escapeHtml) {
        const escape = typeof escapeHtml === 'function' ? escapeHtml : function (v) {
            return String(v == null ? '' : v);
        };
        const parts = lines(player);
        if (!parts.line2) {
            return escape(parts.line1);
        }
        return escape(parts.line1) + '<br>' + escape(parts.line2);
    }

    global.FfbPlayerName = {
        full: full,
        lines: lines,
        linesHtml: linesHtml,
        defaultFromParts: defaultFromParts,
    };
})(window);
