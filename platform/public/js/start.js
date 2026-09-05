(function () {
    const config = window.FFB_START || {};
    const form = document.getElementById('login');
    const feedback = document.getElementById('login-feedback');
    const forgotBtn = document.getElementById('forgot-password');

    if (!form) {
        return;
    }

    function showFeedback(html, kind) {
        feedback.hidden = false;
        feedback.className = 'feedback feedback-' + kind;
        feedback.innerHTML = html;
    }

    function parseXml(text) {
        return new DOMParser().parseFromString(text, 'application/xml');
    }

    function firstText(xml, tag) {
        const node = xml.getElementsByTagName(tag)[0];
        return node && node.firstChild ? node.firstChild.nodeValue : '';
    }

    function collectErrors(xml) {
        const errorsRoot = xml.getElementsByTagName('errors')[0];
        if (!errorsRoot) {
            return ['Unbekannter Fehler.'];
        }
        const tags = errorsRoot.getElementsByTagName('XML_Serializer_Tag');
        const out = [];
        for (let i = 0; i < tags.length; i++) {
            if (tags[i].firstChild) {
                out.push(tags[i].firstChild.nodeValue);
            }
        }
        return out.length ? out : ['Unbekannter Fehler.'];
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

    async function postLogin() {
        const body = new URLSearchParams(new FormData(form));
        const headers = {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };
        const xsrf = xsrfToken();
        if (xsrf) {
            headers['X-XSRF-TOKEN'] = xsrf;
        }

        const response = await fetch(config.loginUrl || 'login', {
            method: 'POST',
            headers: headers,
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
        const body = new URLSearchParams(new FormData(form));
        const response = await fetch(config.passwordUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                Accept: 'application/xml, text/xml, */*',
            },
            body: body.toString(),
            credentials: 'same-origin',
        });
        const text = await response.text();
        return parseXml(text);
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

            try {
                const xml = await postForgotPassword();
                const status = firstText(xml, 'user_status');
                if (status === '200') {
                    showFeedback(firstText(xml, 'user_answer') || 'E-Mail wurde gesendet.', 'ok');
                    return;
                }
                const errors = collectErrors(xml);
                showFeedback('<strong>Es sind Fehler aufgetreten:</strong><br>' + errors.join('<br>'), 'error');
            } catch (err) {
                showFeedback('Anfrage fehlgeschlagen. Bitte später erneut versuchen.', 'error');
            }
        });
    }
})();
