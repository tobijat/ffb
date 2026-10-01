/**
 * Shared side-panel match list (Aufstellung, Mannschaft, Top/Flop, Rangliste).
 */
window.FfbMatchList = (function () {
    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function flagHtml(code, title) {
        if (window.FfbFlags && typeof window.FfbFlags.html === 'function') {
            return window.FfbFlags.html(code, title ? { title: title } : undefined);
        }
        const flag = (code && code !== '0' ? String(code) : 'na').toLowerCase();
        const legacyBase = (window.FFB_MODAL && window.FFB_MODAL.legacyBase)
            || (window.FFB_MYTEAM && window.FFB_MYTEAM.legacyBase)
            || (window.FFB_BESTTEAM && window.FFB_BESTTEAM.legacyBase)
            || (window.FFB_USERSCORE && window.FFB_USERSCORE.legacyBase)
            || (window.FFB_LINEUP && window.FFB_LINEUP.legacyBase)
            || '/';
        const src = legacyBase + 'images/ffb/flags/' + flag + '.gif';
        const titleAttr = title ? ' title="' + escapeHtml(title) + '"' : '';
        return '<img class="ffb-flag ffb-flag-img" src="' + src + '" alt="" width="16" height="11" loading="lazy"' + titleAttr + '>';
    }

    function hasPenaltyScore(match) {
        const homePen = parseInt(match.match_homescore_penalty, 10);
        const guestPen = parseInt(match.match_guestscore_penalty, 10);
        return !Number.isNaN(homePen) && !Number.isNaN(guestPen) && homePen > -1 && guestPen > -1;
    }

    function hasResultScore(match) {
        return match.match_homescore != null &&
            match.match_guestscore != null &&
            String(match.match_homescore) !== '' &&
            String(match.match_guestscore) !== '' &&
            Number(match.match_homescore) >= 0 &&
            Number(match.match_guestscore) >= 0;
    }

    function hadOvertime(match) {
        return Number(match.match_minutes) === 120;
    }

    /**
     * Centre cell: kickoff date(/time), result, or penalty result with optional ET line.
     */
    function formatScore(match) {
        if (hasPenaltyScore(match)) {
            let html =
                '<span class="score-final">' +
                escapeHtml(match.match_homescore_penalty) +
                ':' +
                escapeHtml(match.match_guestscore_penalty) +
                ' <span class="score-hint" title="nach Elfmeterschießen">n.E.</span></span>';

            if (hasResultScore(match)) {
                html +=
                    '<span class="score-reg">(' +
                    escapeHtml(match.match_homescore) +
                    ':' +
                    escapeHtml(match.match_guestscore) +
                    ' <span class="score-hint" title="nach Verlängerung">n.V.</span>)</span>';
            }
            return html;
        }

        if (hasResultScore(match)) {
            let html =
                escapeHtml(match.match_homescore) + ':' + escapeHtml(match.match_guestscore);
            if (hadOvertime(match)) {
                html +=
                    ' <span class="score-hint" title="nach Verlängerung">n.V.</span>';
            }
            return html;
        }

        const dateLabel = String(match.match_date || '').trim();
        const timeLabel = String(match.match_time || '').trim();
        if (dateLabel === '' && timeLabel === '') {
            return '-:-';
        }

        let html = '<span class="score-kickoff-date">' + escapeHtml(dateLabel !== '' ? dateLabel : '-:-') + '</span>';
        if (timeLabel !== '') {
            html += '<span class="score-kickoff-time">' + escapeHtml(timeLabel) + '</span>';
        }
        return html;
    }

    function matchRowHtml(match) {
        return (
            '<span class="home">' +
            escapeHtml(match.match_hometeam_name) +
            ' ' +
            flagHtml(match.match_hometeam_nationality) +
            '</span>' +
            '<span class="score"><a class="nolink under" href="#" data-modal="match" data-id="' +
            escapeHtml(match.match_id) +
            '" title="Klicken für Matchinfos">' +
            formatScore(match) +
            '</a></span>' +
            '<span class="away">' +
            flagHtml(match.match_guestteam_nationality) +
            ' ' +
            escapeHtml(match.match_guestteam_name) +
            '</span>'
        );
    }

    /**
     * @param {HTMLElement} container
     * @param {Array} matches
     * @param {{ emptyHtml?: string }} [options]
     */
    function render(container, matches, options) {
        if (!container) {
            return;
        }
        const opts = options || {};
        const list = Array.isArray(matches) ? matches : [];
        if (!list.length) {
            container.innerHTML = opts.emptyHtml || '<p class="muted">Keine Spiele in dieser Runde.</p>';
            return;
        }

        const ul = document.createElement('ul');
        ul.className = 'match-list';
        list.forEach(function (match) {
            const li = document.createElement('li');
            li.innerHTML = matchRowHtml(match);
            ul.appendChild(li);
        });
        container.innerHTML = '';
        container.appendChild(ul);
    }

    return {
        escapeHtml: escapeHtml,
        formatScore: formatScore,
        matchRowHtml: matchRowHtml,
        render: render,
        hasPenaltyScore: hasPenaltyScore,
        hasResultScore: hasResultScore,
    };
})();
