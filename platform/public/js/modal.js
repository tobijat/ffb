(function () {
    'use strict';

    const config = window.FFB_MODAL || {};
    const apiBase = (config.apiBase || 'api').replace(/\/$/, '');
    const legacyBase = config.legacyBase || '/';

    const stack = [];
    let root = null;
    let dialog = null;
    let bodyEl = null;
    let headEl = null;
    let tabsEl = null;
    let openToken = 0;

    function symbolUrl(name) {
        return legacyBase + 'images/ffb/symbols/' + name;
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

    function ensureDom() {
        if (root) {
            return;
        }

        root = document.createElement('div');
        root.className = 'ffb-modal-root';
        root.hidden = true;
        root.setAttribute('role', 'dialog');
        root.setAttribute('aria-modal', 'true');
        root.innerHTML =
            '<div class="ffb-modal-backdrop" data-ffb-modal-close></div>' +
            '<div class="ffb-modal-dialog" role="document">' +
            '<div class="ffb-modal-head"></div>' +
            '<div class="ffb-modal-tabs" hidden></div>' +
            '<div class="ffb-modal-body"></div>' +
            '</div>';

        document.body.appendChild(root);
        dialog = root.querySelector('.ffb-modal-dialog');
        headEl = root.querySelector('.ffb-modal-head');
        tabsEl = root.querySelector('.ffb-modal-tabs');
        bodyEl = root.querySelector('.ffb-modal-body');

        root.addEventListener('click', function (e) {
            if (e.target.closest('[data-ffb-modal-close]')) {
                FfbModal.close();
            }
        });
    }

    function setHead(html) {
        headEl.innerHTML = html;
    }

    function setTabs(html) {
        if (!html) {
            tabsEl.hidden = true;
            tabsEl.innerHTML = '';
            return;
        }
        tabsEl.hidden = false;
        tabsEl.innerHTML = html;
    }

    function setBody(html) {
        bodyEl.innerHTML = html;
    }

    function defaultCloseBtn() {
        return (
            '<button type="button" class="ffb-modal-close" data-ffb-modal-close title="Schließen" aria-label="Schließen">' +
            '<img src="' + symbolUrl('delete.png') + '" alt="">' +
            '</button>'
        );
    }

    function waitingUi() {
        setHead(
            '<div class="ffb-modal-head-title">lade Infos… bitte warten…</div>' +
            defaultCloseBtn()
        );
        setTabs('');
        setBody('<p class="ffb-modal-loading">Wird geladen…</p>');
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
            const err = (json && json.error) || ('HTTP ' + res.status);
            throw new Error(err);
        }
        return json;
    }

    function renderProfileHead(user) {
        return (
            '<img class="ffb-modal-head-avatar" src="' + escapeHtml(imgUrl(user.avatar_url)) + '" alt="" width="40" height="40">' +
            '<div class="ffb-modal-head-title">' + escapeHtml(user.user_nickname) + '</div>' +
            defaultCloseBtn()
        );
    }

    function renderProfileTabs(userId, active) {
        return (
            '<button type="button" class="' + (active === 'profile' ? 'is-active' : '') + '" data-ffb-profile-tab="profile" data-id="' + userId + '">Profil</button>' +
            '<button type="button" class="' + (active === 'awards' ? 'is-active' : '') + '" data-ffb-profile-tab="awards" data-id="' + userId + '">Auszeichnungen</button>'
        );
    }

    function profileRow(symbol, label, valueHtml) {
        return (
            '<div class="ffb-profile-row">' +
            '<img src="' + symbolUrl(symbol) + '" alt="">' +
            '<span class="label">' + escapeHtml(label) + '</span>' +
            '<span class="value">' + valueHtml + '</span>' +
            '</div>'
        );
    }

    function websiteHref(raw) {
        const s = String(raw || '').trim();
        if (!s) {
            return '#';
        }
        if (/^https?:\/\//i.test(s)) {
            return s;
        }
        return 'https://' + s;
    }

    function renderProfileBody(data) {
        const user = data.user;
        const parts = data.participations || [];
        let rows = '';

        if (user.user_perm_profile && user.user_name) {
            rows += profileRow('symbol_profile.png', 'Name:', escapeHtml(user.user_name));
        }
        if (user.user_details_city) {
            rows += profileRow('symbol_home.png', 'kommt aus:', escapeHtml(user.user_details_city));
        }
        if (user.user_details_website) {
            const ws = String(user.user_details_website);
            const label = ws.length > 23 ? 'klicken' : ws;
            rows += profileRow(
                'symbol_globe.png',
                'Website:',
                '<a class="nolink" target="_blank" rel="noopener noreferrer" href="' +
                    escapeHtml(websiteHref(ws)) +
                    '" title="Zur Website gehen">' +
                    escapeHtml(label) +
                    '</a>'
            );
        }
        if (user.user_perm_profile && user.user_details_phone) {
            rows += profileRow('symbol_phone.png', 'Telefon:', escapeHtml(user.user_details_phone));
        }
        if (user.user_date_register) {
            rows += profileRow('calendar.png', 'Mitglied seit:', escapeHtml(user.user_date_register));
        }
        if (user.user_date_llogin) {
            rows += profileRow('stats_time.png', 'letzte Aktivität:', escapeHtml(user.user_date_llogin));
        }
        if (user.favourite_team && user.favourite_team.name) {
            rows += profileRow('symbol_shoes.png', 'Lieblingsteam:', escapeHtml(user.favourite_team.name));
        }

        let table = '';
        if (parts.length) {
            table += '<div class="ffb-profile-section"><h3>Teilnahmen</h3>';
            table += '<table class="ffb-profile-table"><thead><tr>';
            table += '<th><b>Liga</b></th><th><b>von – bis</b></th><th><b>Punkte (WC)</b></th><th><b>Platz</b></th>';
            table += '</tr></thead><tbody>';

            for (let i = 0; i < parts.length; i++) {
                const p = parts[i];
                const rank = Number(p.user_rank) || 0;
                let rankCls = '';
                if (rank === 1) {
                    rankCls = ' rank-1';
                } else if (rank === 2) {
                    rankCls = ' rank-2';
                } else if (rank === 3) {
                    rankCls = ' rank-3';
                }

                let scoreCell;
                if (p.score_rm === 'wc') {
                    scoreCell = escapeHtml(p.score_points) + ' (<b>' + escapeHtml(p.score_wc) + '</b>)';
                } else {
                    scoreCell = '<b>' + escapeHtml(p.score_points) + '</b> (' + escapeHtml(p.score_wc) + ')';
                }

                let liga = '';
                if (p.game_symbol) {
                    liga += '<img src="' + symbolUrl(p.game_symbol) + '" alt="" width="16" height="16">';
                }
                liga += escapeHtml(p.game_title);

                const range =
                    escapeHtml(p.score_start || '–') + ' – ' + escapeHtml(p.score_end || '–');

                table += '<tr>';
                table += '<td class="liga">' + liga + '</td>';
                table += '<td>' + range + '</td>';
                table += '<td>' + scoreCell + '</td>';
                table += '<td class="' + rankCls.trim() + '"><b>' + escapeHtml(rank) + '</b></td>';
                table += '</tr>';
            }

            table += '</tbody></table></div>';
        }

        return (
            '<div class="ffb-profile">' +
            '<div><img class="ffb-profile-photo" src="' +
            escapeHtml(imgUrl(user.photo_url)) +
            '" alt="" width="100"></div>' +
            '<div class="ffb-profile-rows">' +
            rows +
            '</div></div>' +
            table
        );
    }

    async function openProfile(userId, tab) {
        waitingUi();
        const json = await fetchJson(apiBase + '/popups/user/' + encodeURIComponent(userId));
        const data = json.data;
        const user = data.user;

        setHead(renderProfileHead(user));
        setTabs(renderProfileTabs(user.user_id, tab || 'profile'));

        if (tab === 'awards') {
            setBody(
                '<p class="ffb-profile-awards-stub">Auszeichnungen folgen in einem späteren Schritt.</p>'
            );
            return;
        }

        setBody(renderProfileBody(data));
    }

    const loaders = {
        profile: function (id, opts) {
            return openProfile(id, (opts && opts.tab) || 'profile');
        },
    };

    const FfbModal = {
        open: async function (opts) {
            const type = opts && opts.type;
            const id = opts && opts.id;
            if (!type || id == null || id === '') {
                return;
            }

            ensureDom();
            root.hidden = false;
            document.body.style.overflow = 'hidden';

            const entry = { type: type, id: id, tab: opts.tab };
            const top = stack[stack.length - 1];
            if (top && top.type === type && String(top.id) === String(id)) {
                stack[stack.length - 1] = entry;
            } else {
                stack.push(entry);
            }

            const token = ++openToken;
            const loader = loaders[type];
            if (!loader) {
                setHead('<div class="ffb-modal-head-title">Unbekannt</div>' + defaultCloseBtn());
                setTabs('');
                setBody('<p class="ffb-modal-error">Unbekannter Popup-Typ.</p>');
                return;
            }

            try {
                await loader(id, opts);
                if (token !== openToken) {
                    return;
                }
            } catch (err) {
                if (token !== openToken) {
                    return;
                }
                setHead('<div class="ffb-modal-head-title">Fehler</div>' + defaultCloseBtn());
                setTabs('');
                setBody(
                    '<p class="ffb-modal-error">' +
                        escapeHtml(err.message || 'Laden fehlgeschlagen') +
                        '</p>'
                );
            }
        },

        close: function () {
            if (!root) {
                return;
            }
            openToken++;
            stack.pop();
            if (stack.length === 0) {
                root.hidden = true;
                setBody('');
                setTabs('');
                setHead('');
                document.body.style.overflow = '';
                return;
            }
            const prev = stack.pop();
            FfbModal.open(prev);
        },

        register: function (type, fn) {
            loaders[type] = fn;
        },
    };

    document.addEventListener('click', function (e) {
        const tabBtn = e.target.closest('[data-ffb-profile-tab]');
        if (tabBtn && root && !root.hidden) {
            e.preventDefault();
            const tab = tabBtn.getAttribute('data-ffb-profile-tab');
            const id = tabBtn.getAttribute('data-id');
            FfbModal.open({ type: 'profile', id: id, tab: tab });
            return;
        }

        const trigger = e.target.closest('[data-modal]');
        if (!trigger) {
            return;
        }
        e.preventDefault();
        FfbModal.open({
            type: trigger.getAttribute('data-modal'),
            id: trigger.getAttribute('data-id'),
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && root && !root.hidden) {
            FfbModal.close();
        }
    });

    window.FfbModal = FfbModal;
})();
