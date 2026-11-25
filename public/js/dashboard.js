(function () {
    const cfg = window.FFB_DASH || {
        apiBase: 'api',
        archive: false,
        newsPage: 1,
        emailUnverified: false,
        showActivationModal: false,
        resendUrl: '/registration/activation/resend',
        csrfToken: '',
    };
    let archive = !!cfg.archive;
    let newsPage = cfg.newsPage || 1;
    const emailUnverified = !!cfg.emailUnverified;

    function apiUrl(path, params) {
        const url = new URL(cfg.apiBase.replace(/\/$/, '') + '/' + path.replace(/^\//, ''), window.location.href);
        if (params) {
            Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, String(v)));
        }
        return url.toString();
    }

    async function api(method, path, body, params) {
        const options = {
            method,
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        };
        if (body) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(body);
        }
        const response = await fetch(apiUrl(path, params), options);
        const json = await response.json();
        if (!response.ok) {
            throw new Error(json.error || 'Request failed');
        }
        return json;
    }

    function syncArchiveUrl() {
        const url = new URL(window.location.href);
        if (archive) {
            url.searchParams.set('archive', '1');
        } else {
            url.searchParams.delete('archive');
        }
        window.history.replaceState({}, '', url.toString());
    }

    function reload(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('news_page', String(params.newsPage || newsPage));
        if (params.archive) {
            url.searchParams.set('archive', '1');
        } else {
            url.searchParams.delete('archive');
        }
        window.location.href = url.toString();
    }

    const activationModal = document.getElementById('activation-modal');
    const activationFeedback = document.getElementById('activation-modal-feedback');
    const activationResend = document.getElementById('activation-resend');

    function openActivationModal() {
        if (!activationModal) {
            return;
        }
        if (activationFeedback) {
            activationFeedback.hidden = true;
            activationFeedback.textContent = '';
            activationFeedback.className = 'activation-modal-feedback';
        }
        activationModal.hidden = false;
        document.body.classList.add('activation-modal-open');
    }

    function closeActivationModal() {
        if (!activationModal) {
            return;
        }
        activationModal.hidden = true;
        document.body.classList.remove('activation-modal-open');
    }

    window.FFB_DASH.openActivationModal = openActivationModal;

    if (activationModal) {
        activationModal.querySelectorAll('[data-activation-close]').forEach((el) => {
            el.addEventListener('click', closeActivationModal);
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !activationModal.hidden) {
                closeActivationModal();
            }
        });
    }

    if (activationResend) {
        activationResend.addEventListener('click', async () => {
            activationResend.disabled = true;
            try {
                const response = await fetch(cfg.resendUrl || '/registration/activation/resend', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': cfg.csrfToken || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: '{}',
                });
                const json = await response.json().catch(() => ({}));
                if (activationFeedback) {
                    activationFeedback.hidden = false;
                    if (response.ok) {
                        activationFeedback.className = 'activation-modal-feedback is-ok';
                        activationFeedback.textContent =
                            json.message || 'Die Aktivierungs-Mail wurde erneut gesendet.';
                    } else {
                        activationFeedback.className = 'activation-modal-feedback is-error';
                        const errors = Array.isArray(json.errors) ? json.errors : [];
                        activationFeedback.textContent =
                            errors[0] || json.error || 'Senden fehlgeschlagen.';
                    }
                }
            } catch (err) {
                if (activationFeedback) {
                    activationFeedback.hidden = false;
                    activationFeedback.className = 'activation-modal-feedback is-error';
                    activationFeedback.textContent = 'Senden fehlgeschlagen.';
                }
            } finally {
                activationResend.disabled = false;
            }
        });
    }

    if (cfg.showActivationModal && emailUnverified) {
        openActivationModal();
    }

    document.querySelectorAll('.game-tile').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (emailUnverified) {
                openActivationModal();
                return;
            }
            const leagueId = Number(btn.dataset.leagueId);
            try {
                await api('POST', 'league/select', { league_id: leagueId });
                reload({ newsPage: 1, archive: false });
            } catch (err) {
                alert(err.message || 'Liga konnte nicht gewählt werden.');
            }
        });
    });

    const archiveBtn = document.getElementById('toggle-archive');
    const archiveGrid = document.getElementById('archive-grid');
    if (archiveBtn && archiveGrid) {
        archiveBtn.addEventListener('click', () => {
            if (emailUnverified) {
                openActivationModal();
                return;
            }
            archive = !archive;
            archiveGrid.hidden = !archive;
            archiveBtn.dataset.archive = archive ? '1' : '0';
            archiveBtn.setAttribute('aria-expanded', archive ? 'true' : 'false');
            archiveBtn.textContent = archive
                ? 'vergangene Ligen ausblenden'
                : 'vergangene Ligen einblenden';
            syncArchiveUrl();
        });
    }

    document.querySelectorAll('#news-pager .page-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            newsPage = Number(btn.dataset.page);
            reload({ newsPage, archive });
        });
    });

    document.querySelectorAll('.poll-vote').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const panel = btn.closest('.select-poll');
            const pollId = Number(panel.dataset.pollId);
            const answerId = Number(btn.dataset.answerId);
            try {
                await api('POST', 'poll/vote', {
                    poll_id: pollId,
                    poll_answer_id: answerId,
                    poll_type: 'select',
                    news_page: newsPage,
                    archive: archive ? 1 : 0,
                });
                reload({ newsPage, archive });
            } catch (err) {
                alert(err.message || 'Stimme konnte nicht gespeichert werden.');
            }
        });
    });

    const sendText = document.getElementById('send-text-poll');
    if (sendText) {
        sendText.addEventListener('click', async () => {
            const wrap = document.querySelector('.text-poll');
            const pollId = Number(wrap.dataset.pollId);
            const answerId = Number(sendText.dataset.answerId);
            const text = (document.getElementById('poll-text-answer') || {}).value || '';
            try {
                await api('POST', 'poll/vote', {
                    poll_id: pollId,
                    poll_answer_id: answerId,
                    poll_answer: text,
                    poll_type: 'text',
                    news_page: newsPage,
                    archive: archive ? 1 : 0,
                });
                reload({ newsPage, archive });
            } catch (err) {
                alert(err.message || 'Antwort konnte nicht gespeichert werden.');
            }
        });
    }
})();
