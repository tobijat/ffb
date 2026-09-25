/**
 * Serialize bulk admin table rows into one JSON hidden field on submit.
 * Avoids PHP max_input_vars truncation on large forms.
 *
 * Usage:
 *   AdminBulkJsonForm.bind({
 *     form: '#form-id',
 *     hidden: '#hidden-json-id',
 *     rowSelector: 'tr.row-class',
 *     fields: { team_id: 'select.cls', ... } // optional live field overrides
 *   });
 */
window.AdminBulkJsonForm = window.AdminBulkJsonForm || {
    bind: function (options) {
        const form = typeof options.form === 'string'
            ? document.querySelector(options.form)
            : options.form;
        const hidden = typeof options.hidden === 'string'
            ? document.querySelector(options.hidden)
            : options.hidden;
        if (!form || !hidden) {
            return;
        }

        const rowSelector = options.rowSelector || 'tr[data-row]';
        const fields = options.fields || {};

        form.addEventListener('submit', function () {
            const rows = [];
            form.querySelectorAll(rowSelector).forEach(function (tr) {
                let row;
                try {
                    row = JSON.parse(tr.getAttribute('data-row') || '{}');
                } catch (e) {
                    row = {};
                }
                if (!row || typeof row !== 'object') {
                    row = {};
                }

                Object.keys(fields).forEach(function (key) {
                    const el = tr.querySelector(fields[key]);
                    if (!el) {
                        return;
                    }
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        row[key] = el.checked ? (el.value || '1') : '0';
                    } else {
                        row[key] = el.value;
                    }
                });

                rows.push(row);
            });

            hidden.value = JSON.stringify(rows);
        });
    },

    bindMulti: function (formSelector, groups) {
        const form = document.querySelector(formSelector);
        if (!form) {
            return;
        }

        form.addEventListener('submit', function () {
            (groups || []).forEach(function (group) {
                const hidden = form.querySelector(group.hidden);
                if (!hidden) {
                    return;
                }
                const rows = [];
                form.querySelectorAll(group.rowSelector).forEach(function (tr) {
                    let row;
                    try {
                        row = JSON.parse(tr.getAttribute('data-row') || '{}');
                    } catch (e) {
                        row = {};
                    }
                    if (!row || typeof row !== 'object') {
                        row = {};
                    }
                    const fields = group.fields || {};
                    Object.keys(fields).forEach(function (key) {
                        const el = tr.querySelector(fields[key]);
                        if (!el) {
                            return;
                        }
                        if (el.type === 'checkbox' || el.type === 'radio') {
                            row[key] = el.checked ? (el.value || '1') : '0';
                        } else {
                            row[key] = el.value;
                        }
                    });
                    rows.push(row);
                });
                hidden.value = JSON.stringify(rows);
            });
        });
    }
};
