(function () {
    const config = window.FFB_START || {};
    const form = document.getElementById('login');
    const feedback = document.getElementById('login-feedback');
    const forgotBtn = document.getElementById('forgot-password');
    const backBtn = document.getElementById('back-to-login');
    const submitBtn = document.getElementById('login-submit');
    const submitLabel = document.getElementById('login-submit-label');
    const nicknameLabel = document.getElementById('label-nickname');
    const nicknameInput = document.getElementById('user_nickname');
    const passwordField = document.getElementById('field-password');
    const passwordInput = document.getElementById('user_password');
    const registerLink = document.getElementById('register-link');

    if (!form) {
        return;
    }

    let mode = 'login';

    function setSubmitLabel(text) {
        if (submitLabel) {
            submitLabel.textContent = text;
        } else if (submitBtn) {
            submitBtn.textContent = text;
        }
        if (submitBtn) {
            submitBtn.title = text;
        }
    }

    function showFeedback(html, kind) {
        if (!feedback) {
            return;
        }
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

    function plainErrorText(errors) {
        return errors
            .map(function (msg) {
                return String(msg || '')
                    .replace(/<br\s*\/?>/gi, ' ')
                    .replace(/<[^>]+>/g, '')
                    .replace(/\s+/g, ' ')
                    .trim();
            })
            .filter(Boolean)
            .join(' ');
    }

    function clearFieldError(input) {
        if (!input) {
            return;
        }
        input.setCustomValidity('');
        input.classList.remove('is-error');
    }

    function clearServerValidity() {
        clearFieldError(nicknameInput);
        clearFieldError(passwordInput);
    }

    function errorTarget(message) {
        if (mode === 'forgot' || !passwordInput || passwordInput.disabled) {
            return nicknameInput;
        }
        const lower = String(message || '').toLowerCase();
        if (lower.includes('passwort') && !lower.includes('nickname und passwort')) {
            return passwordInput;
        }
        return nicknameInput;
    }

    function showErrorBubble(errors) {
        const message = plainErrorText(errors);
        if (!message) {
            return;
        }
        clearServerValidity();
        const target = errorTarget(message);
        target.classList.add('is-error');
        target.focus({ preventScroll: true });
        target.setCustomValidity(message);
        // Defer so focus settles; invalid-handler must not clear customError.
        requestAnimationFrame(function () {
            target.reportValidity();
        });
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
        if (feedback) {
            feedback.hidden = true;
        }

        if (next === 'forgot') {
            nicknameInput.required = true;
            nicknameInput.autocomplete = 'username';
            nicknameInput.placeholder = 'Benutzername oder E-Mail';
            nicknameInput.setAttribute('data-required-msg', 'Bitte Benutzername oder E-Mail eingeben.');
            clearFieldError(nicknameInput);
            if (nicknameLabel) {
                nicknameLabel.textContent = 'Benutzername oder E-Mail';
            }
            if (passwordField) {
                passwordField.hidden = true;
            }
            passwordInput.required = false;
            passwordInput.disabled = true;
            passwordInput.value = '';
            clearFieldError(passwordInput);
            setSubmitLabel('Reset-Link senden');
            if (forgotBtn) {
                forgotBtn.hidden = true;
            }
            if (backBtn) {
                backBtn.hidden = false;
            }
            if (registerLink) {
                registerLink.hidden = true;
            }
            nicknameInput.focus();
            return;
        }

        nicknameInput.autocomplete = 'username';
        nicknameInput.placeholder = 'Benutzername';
        nicknameInput.setAttribute('data-required-msg', 'Bitte Benutzername eingeben.');
        clearFieldError(nicknameInput);
        if (nicknameLabel) {
            nicknameLabel.textContent = 'Benutzername';
        }
        if (passwordField) {
            passwordField.hidden = false;
        }
        passwordInput.disabled = false;
        passwordInput.required = true;
        clearFieldError(passwordInput);
        setSubmitLabel('Anmelden');
        if (forgotBtn) {
            forgotBtn.hidden = false;
        }
        if (backBtn) {
            backBtn.hidden = true;
        }
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
        // Native required bubbles (German via setCustomValidity); only AJAX when valid.
        if (!form.checkValidity()) {
            return;
        }

        event.preventDefault();
        clearServerValidity();
        if (feedback) {
            feedback.hidden = true;
        }

        if (mode === 'forgot') {
            try {
                const result = await postForgotPassword();
                if (result.data && Number(result.data.status) === 200) {
                    const message = result.data.message || 'E-Mail wurde gesendet.';
                    // setMode hides feedback — show success after switching back to login.
                    setMode('login');
                    showFeedback(message, 'ok');
                    return;
                }
                showErrorBubble(collectErrors(result.data, result.status));
            } catch (err) {
                showErrorBubble(['Anfrage fehlgeschlagen. Bitte später erneut versuchen.']);
            }
            return;
        }

        try {
            const result = await postLogin();
            if (result.data && Number(result.data.status) === 200) {
                window.location.href = result.data.destination || '/';
                return;
            }
            showErrorBubble(collectErrors(result.data, result.status));
        } catch (err) {
            showErrorBubble(['Login derzeit nicht erreichbar. Bitte später erneut versuchen.']);
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
