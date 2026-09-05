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

    async function postForm(url) {
        const body = new URLSearchParams(new FormData(form));
        const response = await fetch(url, {
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
            const xml = await postForm(config.loginUrl);
            const status = firstText(xml, 'administration_status');
            if (status === '200') {
                window.location.href = firstText(xml, 'administration_destination') || '/ffb';
                return;
            }
            const errors = collectErrors(xml);
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
                const xml = await postForm(config.passwordUrl);
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
