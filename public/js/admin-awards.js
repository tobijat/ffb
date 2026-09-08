(function () {
    const root = document.querySelector('.admin-awards');
    if (!root) return;

    const groupUrlTpl = root.dataset.groupUrlTemplate || '';
    const updateGroupUrl = root.dataset.updateGroupUrl || '';
    const createDefineUrl = root.dataset.createDefineUrl || '';
    const updateDefineUrl = root.dataset.updateDefineUrl || '';
    const finishedUrlTpl = root.dataset.finishedUrlTemplate || '';
    const calcDefineUrlTpl = root.dataset.calculateDefineUrlTemplate || '';
    const calcAllUrl = root.dataset.calculateAllUrl || '';
    const deleteFinishedUrlTpl = root.dataset.deleteFinishedUrlTemplate || '';
    const csrf = root.dataset.csrf || '';
    const imagesBase = root.dataset.imagesBase || '/images/ffb/';

    const selectEl = document.getElementById('awardselect');
    const detailEl = document.getElementById('admin-awards-detail');
    const outputEl = document.getElementById('awardoutput');
    const formError = document.getElementById('formerror');
    const formAnswer = document.getElementById('formanswer');
    const calcAllBtn = document.getElementById('admin-awards-calc-all');

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function xsrfToken() {
        const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    async function fetchJson(url, init) {
        const headers = Object.assign(
            { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            (init && init.headers) || {}
        );
        const method = ((init && init.method) || 'GET').toUpperCase();
        if (method !== 'GET') {
            const xsrf = xsrfToken();
            if (xsrf) headers['X-XSRF-TOKEN'] = xsrf;
            if (csrf) headers['X-CSRF-TOKEN'] = csrf;
        }
        const response = await fetch(url, Object.assign({ credentials: 'same-origin' }, init || {}, { headers }));
        const json = await response.json().catch(function () {
            return null;
        });
        if (!response.ok) {
            const err = new Error((json && (json.error || (json.answer && json.answer.text) || json.message)) || 'Request failed');
            err.status = response.status;
            err.payload = json;
            throw err;
        }
        return json;
    }

    function clearFlash() {
        if (formError) formError.innerHTML = '';
        if (formAnswer) formAnswer.innerHTML = '';
    }

    function showError(text) {
        if (formError) {
            formError.innerHTML = '<div class="account-flash account-flash-error">' + escapeHtml(text) + '</div>';
        }
    }

    function showAnswer(text) {
        if (formAnswer) {
            formAnswer.innerHTML = '<div class="account-flash account-flash-ok">' + escapeHtml(text) + '</div>';
        }
    }

    function formToObject(form) {
        const data = {};
        const fd = new FormData(form);
        fd.forEach(function (value, key) {
            data[key] = value;
        });
        if (form.querySelector('[name="award_auto"]')) {
            const cb = form.querySelector('[name="award_auto"]');
            data.award_auto = cb && cb.checked ? '1' : '0';
        }
        return data;
    }

    function renderGroup(payload) {
        const g = payload.userAward || {};
        const defines = payload.userAwardDefines || [];
        let html = '';

        html +=
            '<div class="admin-awards-block">' +
            '<p><u>Auszeichnungs Gruppe: <b>' +
            escapeHtml(g.name) +
            '</b></u></p>' +
            '<form id="awardGroupInfo" class="admin-awards-group-form">' +
            '<input type="hidden" name="award_group_id" value="' +
            escapeHtml(g.id) +
            '">' +
            '<div class="admin-awards-group-row">' +
            '<img class="admin-awards-thumb" width="64" height="64" src="' +
            escapeHtml(imagesBase + String(g.image || '').trim()) +
            '" alt="">' +
            '<div class="admin-ms-filter"><label>Beschreibung</label>' +
            '<textarea name="award_group_description" rows="3">' +
            escapeHtml(String(g.description || '').trim()) +
            '</textarea></div>' +
            '<div class="admin-ms-filter"><label>Bildpfad</label>' +
            '<input type="text" name="award_group_image" value="' +
            escapeHtml(String(g.image || '').trim()) +
            '"></div>' +
            '<div class="admin-actions admin-actions-flush">' +
            '<button type="button" class="admin-submit" data-action="update-group">update</button>' +
            '</div></div></form></div>';

        html +=
            '<hr class="admin-awards-hr"><p><strong>Neue Auszeichnung anlegen:</strong></p>' +
            '<form id="newaward" class="admin-awards-define-form">' +
            '<input type="hidden" name="group_award_id" value="' +
            escapeHtml(g.id) +
            '">' +
            defineFieldsHtml({}) +
            '<div class="admin-actions admin-actions-flush">' +
            '<button type="button" class="admin-submit" data-action="create-define">anlegen</button>' +
            '</div></form>';

        html += '<hr class="admin-awards-hr"><p><u>Vorhandene Auszeichnungen:</u></p>';
        for (let i = 0; i < defines.length; i++) {
            const d = defines[i];
            html +=
                '<form class="admin-awards-define-form admin-awards-existing" data-index="' +
                i +
                '">' +
                '<input type="hidden" name="award_defines_id" value="' +
                escapeHtml(d.id) +
                '">' +
                defineFieldsHtml(d) +
                '<div class="admin-actions admin-actions-flush admin-awards-define-actions">' +
                '<button type="button" class="admin-submit" data-action="update-define">update</button>' +
                '<button type="button" class="admin-submit" data-action="calc-define" data-id="' +
                escapeHtml(d.id) +
                '">berechnen</button>' +
                '<button type="button" class="admin-submit" data-action="show-winners" data-id="' +
                escapeHtml(d.id) +
                '">anzeigen</button>' +
                '<button type="button" class="admin-submit" data-action="delete-define">delete</button>' +
                '</div></form>';
        }

        html += '<div id="awardoutput" class="admin-awards-output" aria-live="polite"></div>';
        if (detailEl) detailEl.innerHTML = html;
    }

    function defineFieldsHtml(d) {
        const autoChecked = String(d.auto || '0') === '1' ? ' checked' : '';
        return (
            '<div class="admin-awards-fields">' +
            '<div class="admin-ms-filter"><label>Rang</label><input type="text" name="award_rank" value="' +
            escapeHtml(String(d.rank || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>Titel</label><input type="text" name="award_name" value="' +
            escapeHtml(String(d.name || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>Kriterium</label><input type="text" name="award_aim" value="' +
            escapeHtml(String(d.aim || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>DB Table</label><input type="text" name="award_dbtable" value="' +
            escapeHtml(String(d.dbtable || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>Operator</label><input type="text" name="award_operator" value="' +
            escapeHtml(String(d.operator || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>Loops</label><input type="text" name="award_count" value="' +
            escapeHtml(String(d.count != null ? d.count : '')) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>AutoAW</label><input type="checkbox" name="award_auto" value="1"' +
            autoChecked +
            '></div>' +
            '<div class="admin-ms-filter"><label>Function</label><input type="text" name="award_function_name" value="' +
            escapeHtml(String(d.function_name || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>Beschreibung</label><input type="text" name="award_description" value="' +
            escapeHtml(String(d.descr || '').trim()) +
            '"></div>' +
            '<div class="admin-ms-filter"><label>Bildpfad</label><input type="text" name="award_image" value="' +
            escapeHtml(String(d.image || '').trim()) +
            '">' +
            (d.image && String(d.image).trim() !== ''
                ? '<img class="admin-awards-mini" src="' +
                  escapeHtml(imagesBase + String(d.image).trim()) +
                  '" alt="" width="18" height="18">'
                : '') +
            '</div></div>'
        );
    }

    async function loadGroup() {
        const id = selectEl ? parseInt(selectEl.value, 10) : 0;
        if (!id) {
            if (detailEl) {
                detailEl.innerHTML =
                    '<p class="muted">Gruppe auswählen, um Auszeichnungen zu bearbeiten.</p>' +
                    '<div id="awardoutput" class="admin-awards-output" aria-live="polite"></div>';
            }
            return;
        }
        try {
            const data = await fetchJson(groupUrlTpl.replace('__ID__', String(id)));
            renderGroup(data);
        } catch (e) {
            showError(e.message || 'Fehler beim Laden.');
        }
    }

    let lastShownDefineId = 0;

    async function showWinners(id) {
        clearFlash();
        lastShownDefineId = id;
        const out = document.getElementById('awardoutput') || outputEl;
        try {
            const data = await fetchJson(finishedUrlTpl.replace('__ID__', String(id)));
            const winners = data.awardWinners || [];
            let html =
                '<div class="admin-awards-winners-head"><span>Name</span><span>Datum</span><span>Optionen</span></div>';
            for (let i = 0; i < winners.length; i++) {
                html +=
                    '<div class="admin-awards-winners-row">' +
                    '<span>' +
                    escapeHtml(winners[i].nick) +
                    '</span><span>' +
                    escapeHtml(winners[i].date) +
                    '</span><span>' +
                    '<button type="button" class="admin-submit" data-action="delete-finished" data-id="' +
                    escapeHtml(winners[i].fid) +
                    '">delete</button></span></div>';
            }
            if (out) out.innerHTML = html || '<p class="muted">Keine Gewinner.</p>';
        } catch (e) {
            showError(e.message || 'Fehler.');
        }
    }

    function renderCalcResult(data) {
        const out = document.getElementById('awardoutput') || outputEl;
        const updates = data.userUpdates || 0;
        const users = data.newAwardUser || [];
        let html = 'Updates insgesamt: <b>' + escapeHtml(updates) + '</b><ul>';
        for (let i = 0; i < users.length; i++) {
            html += '<li>' + escapeHtml(users[i].usernick) + '</li>';
        }
        html += '</ul>';
        if (data.duration && data.duration.length) {
            const last = data.duration[data.duration.length - 1];
            html += '<p>Dauer: ' + escapeHtml(last.duration) + '</p>';
        }
        if (out) out.innerHTML = html;
    }

    if (selectEl) {
        selectEl.addEventListener('change', function () {
            clearFlash();
            loadGroup();
        });
    }

    if (calcAllBtn) {
        calcAllBtn.addEventListener('click', async function () {
            clearFlash();
            const out = document.getElementById('awardoutput') || outputEl;
            if (out) out.innerHTML = 'berechnung läuft...';
            try {
                const data = await fetchJson(calcAllUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: '{}' });
                renderCalcResult(data);
            } catch (e) {
                showError(e.message || 'Fehler.');
            }
        });
    }

    document.addEventListener('click', async function (event) {
        const btn = event.target.closest('[data-action]');
        if (!btn || !root.contains(btn)) return;
        const action = btn.getAttribute('data-action');
        const form = btn.closest('form');

        if (action === 'update-group' && form) {
            clearFlash();
            try {
                const data = await fetchJson(updateGroupUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formToObject(form)),
                });
                if (data.answer && data.answer.status == 201) {
                    showAnswer(data.answer.text);
                    loadGroup();
                } else {
                    showError((data.answer && data.answer.text) || 'Fehler');
                }
            } catch (e) {
                showError((e.payload && e.payload.answer && e.payload.answer.text) || e.message);
            }
            return;
        }

        if (action === 'create-define' && form) {
            clearFlash();
            try {
                const data = await fetchJson(createDefineUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formToObject(form)),
                });
                if (data.answer && data.answer.status == 201) {
                    showAnswer(data.answer.text);
                    loadGroup();
                } else {
                    showError((data.answer && data.answer.text) || 'Fehler');
                }
            } catch (e) {
                showError((e.payload && e.payload.answer && e.payload.answer.text) || e.message);
            }
            return;
        }

        if (action === 'update-define' && form) {
            clearFlash();
            try {
                const data = await fetchJson(updateDefineUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formToObject(form)),
                });
                if (data.answer && data.answer.status == 201) {
                    showAnswer(data.answer.text);
                    loadGroup();
                } else {
                    showError((data.answer && data.answer.text) || 'Fehler');
                }
            } catch (e) {
                showError((e.payload && e.payload.answer && e.payload.answer.text) || e.message);
            }
            return;
        }

        if (action === 'calc-define') {
            clearFlash();
            const id = btn.getAttribute('data-id');
            try {
                const data = await fetchJson(calcDefineUrlTpl.replace('__ID__', String(id)), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: '{}',
                });
                renderCalcResult(data);
            } catch (e) {
                showError(e.message || 'Fehler');
            }
            return;
        }

        if (action === 'show-winners') {
            showWinners(btn.getAttribute('data-id'));
            return;
        }

        if (action === 'delete-define') {
            if (!window.confirm('Auszeichnung wirklich loeschen?')) return;
            window.alert('Löschen von Auszeichnungs-Definitionen ist (wie im Legacy) nicht implementiert.');
            return;
        }

        if (action === 'delete-finished') {
            const id = btn.getAttribute('data-id');
            try {
                await fetchJson(deleteFinishedUrlTpl.replace('__ID__', String(id)), { method: 'DELETE' });
                showAnswer('Fertige Auszeichnung gelöscht.');
                if (lastShownDefineId) {
                    showWinners(lastShownDefineId);
                }
            } catch (e) {
                showError(e.message || 'Fehler');
            }
        }
    });
})();
