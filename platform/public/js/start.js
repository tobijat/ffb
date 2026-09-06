(function () {
    const config = window.FFB_START || {};
    const form = document.getElementById('login');
    const feedback = document.getElementById('login-feedback');
    const forgotBtn = document.getElementById('forgot-password');
    const forgotEmailWrap = document.getElementById('forgot-email-wrap');
    const forgotEmail = document.getElementById('forgot-email');

    if (!form) {
        return;
    }

    function showFeedback(html, kind) {
        feedback.hidden = false;
        feedback.className = 'feedback feedback-' + kind;
        feedback.innerHTML = html;
    }

    function collectLoginErrors(data, httpStatus) {
        if (data && Array.isArray(data.errors) && data.errors.length) {
            return data.errors;
        }
        if (data && typeof data.message === 'string' && data.message !== '') {
            return [data.message];
        }
        if (httpStatus === 419) {
            return ['Sitzung abgelaufen. Bitte Seite neu laden und erneut versuchen.'];
        }
        return ['Unbekannter Fehler. (HTTP ' + httpStatus + ')'];
    }

    function xsrfToken() {
        const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    function jsonHeaders() {
        const headers = {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };
        const xsrf = xsrfToken();
        if (xsrf) {
            headers['X-XSRF-TOKEN'] = xsrf;
        }
        return headers;
    }

    async function postLogin() {
        const body = new URLSearchParams(new FormData(form));
        const response = await fetch(config.loginUrl || 'login', {
            method: 'POST',
            headers: jsonHeaders(),
            body: body.toString(),
            credentials: 'same-origin',
            redirect: 'error',
        });

        const text = await response.text();
        let data = null;
        try {
            data = text ? JSON.parse(text) : null;
        } catch (err) {
            throw new Error('Non-JSON login response (HTTP ' + response.status + ')');
        }
        return { ok: response.ok, status: response.status, data };
    }

    async function postForgotPassword() {
        const body = new URLSearchParams();
        body.set('user_nickname', form.user_nickname.value.trim());
        body.set('user_email', (forgotEmail && forgotEmail.value || '').trim());
        body.set('users_registration_getpassword', '1');

        const response = await fetch(config.passwordUrl || 'registration/password', {
            method: 'POST',
            headers: jsonHeaders(),
            body: body.toString(),
            credentials: 'same-origin',
        });
        const text = await response.text();
        let data = null;
        try {
            data = text ? JSON.parse(text) : null;
        } catch (err) {
            throw new Error('Non-JSON password response (HTTP ' + response.status + ')');
        }
        return { ok: response.ok, status: response.status, data };
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        feedback.hidden = true;

        try {
            const result = await postLogin();
            if (result.data && Number(result.data.status) === 200) {
                window.location.href = result.data.destination || '/platform/public/';
                return;
            }
            const errors = collectLoginErrors(result.data, result.status);
            showFeedback('<strong>Es sind Fehler aufgetreten:</strong><br>' + errors.join('<br>'), 'error');
        } catch (err) {
            showFeedback('Login derzeit nicht erreichbar. Bitte später erneut versuchen.', 'error');
        }
    });

    if (forgotBtn) {
        forgotBtn.addEventListener('click', async function () {
            feedback.hidden = true;
            const nickname = form.user_nickname.value.trim();
            if (!nickname) {
                showFeedback('Bitte Nickname eingeben, dann Passwort vergessen wählen.', 'error');
                form.user_nickname.focus();
                return;
            }

            if (forgotEmailWrap && forgotEmailWrap.hidden) {
                forgotEmailWrap.hidden = false;
                if (forgotEmail) {
                    forgotEmail.focus();
                }
                showFeedback('Bitte E-Mail eingeben und erneut auf „Passwort vergessen?“ klicken.', 'ok');
                return;
            }

            if (!forgotEmail || !forgotEmail.value.trim()) {
                showFeedback('Bitte E-Mail-Adresse eingeben.', 'error');
                if (forgotEmail) {
                    forgotEmail.focus();
                }
                return;
            }

            try {
                const result = await postForgotPassword();
                if (result.data && Number(result.data.status) === 200) {
                    showFeedback(result.data.message || 'E-Mail wurde gesendet.', 'ok');
                    return;
                }
                const errors = collectLoginErrors(result.data, result.status);
                showFeedback('<strong>Es sind Fehler aufgetreten:</strong><br>' + errors.join('<br>'), 'error');
            } catch (err) {
                showFeedback('Anfrage fehlgeschlagen. Bitte später erneut versuchen.', 'error');
            }
        });
    }
})();
