(function () {
    const cfg = window.FFB_DASH || { apiBase: 'api', archive: false, newsPage: 1 };
    let archive = !!cfg.archive;
    let newsPage = cfg.newsPage || 1;

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

    document.querySelectorAll('.game-tile').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const gameId = Number(btn.dataset.gameId);
            try {
                await api('POST', 'game/select', { game_id: gameId });
                reload({ newsPage: 1, archive });
            } catch (err) {
                alert(err.message || 'Spiel konnte nicht gewählt werden.');
            }
        });
    });

    const archiveBtn = document.getElementById('toggle-archive');
    if (archiveBtn) {
        archiveBtn.addEventListener('click', () => {
            archive = !archive;
            reload({ newsPage: 1, archive });
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
