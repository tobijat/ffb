(function () {
    const config = window.FFB_START || {};
    const form = document.getElementById('login');
    const feedback = document.getElementById('login-feedback');
    const forgotBtn = document.getElementById('forgot-password');
    const backBtn = document.getElementById('back-to-login');
    const title = document.getElementById('login-title');
    const submitBtn = document.getElementById('login-submit');
    const nicknameLabel = document.getElementById('label-nickname');
    const nicknameInput = document.getElementById('user_nickname');
    const passwordField = document.getElementById('field-password');
    const passwordInput = document.getElementById('user_password');
    const registerLink = document.getElementById('register-link');

    if (!form) {
        return;
    }

    let mode = 'login';

    function showFeedback(html, kind) {
        feedback.hidden = false;
        feedback.className = 'feedback feedback-' + kind;
        feedback.innerHTML = html;
    }

    function collectErrors(data, httpStatus) {
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

    function setMode(next) {
        mode = next;
        form.setAttribute('data-mode', next);
        feedback.hidden = true;

        if (next === 'forgot') {
            title.textContent = 'Passwort vergessen';
            nicknameLabel.textContent = 'Benutzername oder E-Mail';
            nicknameInput.required = true;
            nicknameInput.autocomplete = 'username';
            passwordField.hidden = true;
            passwordInput.required = false;
            passwordInput.disabled = true;
            passwordInput.value = '';
            submitBtn.textContent = 'Reset-Link senden';
            forgotBtn.hidden = true;
            backBtn.hidden = false;
            if (registerLink) {
                registerLink.hidden = true;
            }
            nicknameInput.focus();
            return;
        }

        title.textContent = 'Anmelden';
        nicknameLabel.textContent = 'Nickname';
        nicknameInput.autocomplete = 'username';
        passwordField.hidden = false;
        passwordInput.disabled = false;
        passwordInput.required = true;
        submitBtn.textContent = 'Anmelden';
        forgotBtn.hidden = false;
        backBtn.hidden = true;
        if (registerLink) {
            registerLink.hidden = false;
        }
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
        body.set('identifier', nicknameInput.value.trim());

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

        if (mode === 'forgot') {
            if (!nicknameInput.value.trim()) {
                showFeedback('Bitte Benutzername oder E-Mail eingeben.', 'error');
                nicknameInput.focus();
                return;
            }
            try {
                const result = await postForgotPassword();
                if (result.data && Number(result.data.status) === 200) {
                    showFeedback(result.data.message || 'E-Mail wurde gesendet.', 'ok');
                    setMode('login');
                    return;
                }
                showFeedback('<strong>Es sind Fehler aufgetreten:</strong><br>' + collectErrors(result.data, result.status).join('<br>'), 'error');
            } catch (err) {
                showFeedback('Anfrage fehlgeschlagen. Bitte später erneut versuchen.', 'error');
            }
            return;
        }

        try {
            const result = await postLogin();
            if (result.data && Number(result.data.status) === 200) {
                window.location.href = result.data.destination || '/platform/public/';
                return;
            }
            showFeedback('<strong>Es sind Fehler aufgetreten:</strong><br>' + collectErrors(result.data, result.status).join('<br>'), 'error');
        } catch (err) {
            showFeedback('Login derzeit nicht erreichbar. Bitte später erneut versuchen.', 'error');
        }
    });

    if (forgotBtn) {
        forgotBtn.addEventListener('click', function () {
            setMode('forgot');
        });
    }

    if (backBtn) {
        backBtn.addEventListener('click', function () {
            setMode('login');
        });
    }
})();
