(function () {
    const root = document.querySelector('.admin-ms');
    if (!root) return;

    const matchroundsUrl = root.dataset.matchroundsUrl || '';
    const usersUrl = root.dataset.usersUrl || '';
    const mailUrlTemplate = root.dataset.mailUrlTemplate || '';
    const sendUrl = root.dataset.sendUrl || '';
    const csrf = root.dataset.csrf || '';
    const symbolPos = root.dataset.symbolPos || '';
    const symbolNeg = root.dataset.symbolNeg || '';

    const gameSelect = document.getElementById('ms-search-game');
    const matchroundSelect = document.getElementById('ms-search-matchround');
    const userstatusSelect = document.getElementById('ms-search-userstatus');
    const mstypeSelect = document.getElementById('ms-search-mstype');
    const userlistEl = document.getElementById('ms-search-userlist');
    const addressEl = document.getElementById('ms-mail-to');
    const subjectInput = document.getElementById('ms-input-subject');
    const textInput = document.getElementById('ms-input-text');
    const typeSelect = document.getElementById('ms-select-mailtype');
    const answersEl = document.getElementById('ms-answers');
    const getUsersBtn = document.getElementById('ms-get-users');
    const sendBtn = document.getElementById('ms-send-mail');

    let gameId = 0;
    let matchroundId = 0;
    const userlist = [];
    const flaglist = {};

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
            const err = new Error((json && (json.error || json.answer || json.message)) || 'Request failed');
            err.status = response.status;
            err.payload = json;
            throw err;
        }
        return json;
    }

    function clearUserlistDisplay() {
        for (let i = 0; i < userlist.length; i++) {
            userlist[i].display = 0;
        }
    }

    function mergeUsers(users) {
        let added = 0;
        for (let i = 0; i < users.length; i++) {
            const user = users[i];
            const uid = String(user.user_id);
            if (!flaglist[uid]) {
                const index = userlist.length;
                userlist.push({
                    user_id: uid,
                    user_nickname: user.user_nickname || '',
                    user_email: user.user_email || '',
                    display: 1,
                    send: 0,
                });
                flaglist[uid] = { index: index };
                added++;
            } else if (userlist[flaglist[uid].index].send === 0) {
                userlist[flaglist[uid].index].display = 1;
            }
        }
        return added;
    }

    function updateDisplay() {
        let uString = '';
        let aString = '';
        let uCount = 0;
        let aCount = 0;

        for (let i = 0; i < userlist.length; i++) {
            const row = userlist[i];
            if (row.send === 1) {
                aCount++;
                aString +=
                    '<a title="' +
                    escapeHtml(row.user_email) +
                    '" href="#" data-rem-user="' +
                    i +
                    '">' +
                    escapeHtml(row.user_nickname) +
                    '</a>; ';
            }
            if (row.display === 1) {
                uCount++;
                uString +=
                    '<a title="' +
                    escapeHtml(row.user_email) +
                    '" href="#" data-add-user="' +
                    i +
                    '">' +
                    escapeHtml(row.user_nickname) +
                    '</a><br>';
            }
        }

        const aTitle =
            '<a title="Remove all Users" href="#" data-rem-all="1"><img border="0" src="' +
            escapeHtml(symbolNeg) +
            '" alt=""></a>&ensp;To (' +
            aCount +
            '): ';
        const uTitle =
            '<b>' +
            uCount +
            ' Available Users</b>&ensp;<a title="Add all Users" href="#" data-add-all="1"><img border="0" src="' +
            escapeHtml(symbolPos) +
            '" alt=""></a><br>';

        if (addressEl) addressEl.innerHTML = aTitle + aString;
        if (userlistEl) userlistEl.innerHTML = uTitle + uString;
    }

    function resetMatchrounds() {
        matchroundId = 0;
        if (!matchroundSelect) return;
        matchroundSelect.innerHTML = '<option value="0">select Matchround..</option>';
        matchroundSelect.disabled = true;
    }

    async function loadMatchrounds(id) {
        if (!matchroundsUrl || !id) {
            resetMatchrounds();
            return;
        }
        try {
            const data = await fetchJson(matchroundsUrl + '?game_id=' + encodeURIComponent(id));
            const rounds = data.matchrounds || [];
            let html = '<option value="0">select Matchround..</option>';
            for (let i = 0; i < rounds.length; i++) {
                html +=
                    '<option value="' +
                    escapeHtml(rounds[i].matchround_id) +
                    '">' +
                    escapeHtml(rounds[i].matchround_title) +
                    '</option>';
            }
            matchroundSelect.innerHTML = html;
            matchroundSelect.disabled = false;
            matchroundId = 0;
        } catch (e) {
            resetMatchrounds();
            window.alert("Oops, there's been an error.");
        }
    }

    async function retrieveUsers() {
        clearUserlistDisplay();
        const params = new URLSearchParams();
        if (gameId > 0) params.set('game_id', String(gameId));
        if (matchroundId > 0) params.set('matchround_id', String(matchroundId));
        params.set('userstatus', userstatusSelect ? userstatusSelect.value : '');
        params.set('mailservice', mstypeSelect ? mstypeSelect.value : '');

        try {
            const data = await fetchJson(usersUrl + '?' + params.toString());
            mergeUsers(data.users || []);
            updateDisplay();
        } catch (e) {
            updateDisplay();
            window.alert("Oops, there's been an error.");
        }
    }

    async function loadMail(mailId) {
        clearUserlistDisplay();
        const url = mailUrlTemplate.replace('__ID__', encodeURIComponent(String(mailId)));
        try {
            const data = await fetchJson(url);
            if (subjectInput) subjectInput.value = data.mail.mail_subject || '';
            if (textInput) textInput.value = data.mail.mail_text || '';
            if (typeSelect) {
                const criteria = data.mail.mail_criteria || '';
                for (let i = 0; i < typeSelect.options.length; i++) {
                    typeSelect.options[i].selected = typeSelect.options[i].value === criteria;
                }
            }
            mergeUsers(data.users || []);
            // Loaded recipients go straight to address list (legacy: display via loadUserList then user clicks add;
            // but getMailById loads users into available list with display=1). Keep same as legacy.
            updateDisplay();
        } catch (e) {
            window.alert("Oops, there's been an error.");
        }
    }

    async function sendMail(userIds, subject, text, type) {
        try {
            const data = await fetchJson(sendUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_ids: userIds,
                    subject: subject,
                    text: text,
                    type: type,
                }),
            });
            if (answersEl) {
                answersEl.innerHTML =
                    '<div class="account-flash account-flash-ok" role="status">' +
                    escapeHtml(data.answer || 'OK') +
                    '</div>';
            }
        } catch (e) {
            const msg =
                (e.payload && (e.payload.answer || e.payload.error || e.payload.message)) ||
                e.message ||
                'The email could not be send to any user.';
            if (answersEl) {
                answersEl.innerHTML =
                    '<div class="account-flash account-flash-error" role="alert">' +
                    escapeHtml(msg) +
                    '</div>';
            }
        }
    }

    function checkMailSend() {
        const ids = [];
        for (let i = 0; i < userlist.length; i++) {
            if (userlist[i].send === 1) ids.push(userlist[i].user_id);
        }
        const subject = subjectInput ? subjectInput.value : '';
        const text = textInput ? textInput.value : '';
        const type = typeSelect ? typeSelect.value : '';

        if (!subject || subject === 'Subject..' || !text || text === 'Text..') {
            window.alert('You have to provide Subject and Text!');
            return;
        }
        if (!type) {
            window.alert('You have to provide Subject and Text!');
            return;
        }
        if (ids.length === 0) {
            window.alert('You have to add Users to the Addresslist!');
            return;
        }
        if (!window.confirm('You are going to send this Email to ' + ids.length + ' Users. Really?')) {
            return;
        }
        sendMail(ids, subject, text, type);
    }

    if (gameSelect) {
        gameSelect.addEventListener('change', function () {
            gameId = parseInt(gameSelect.value, 10) || 0;
            if (gameId > 0) {
                loadMatchrounds(gameId);
            } else {
                resetMatchrounds();
            }
        });
    }

    if (matchroundSelect) {
        matchroundSelect.addEventListener('change', function () {
            matchroundId = parseInt(matchroundSelect.value, 10) || 0;
        });
    }

    if (getUsersBtn) {
        getUsersBtn.addEventListener('click', function () {
            retrieveUsers();
        });
    }

    if (sendBtn) {
        sendBtn.addEventListener('click', function () {
            checkMailSend();
        });
    }

    document.addEventListener('click', function (event) {
        const loadBtn = event.target.closest('.admin-ms-load');
        if (loadBtn) {
            event.preventDefault();
            const id = parseInt(loadBtn.getAttribute('data-mail-id') || '0', 10);
            if (id > 0) loadMail(id);
            return;
        }

        const addAll = event.target.closest('[data-add-all]');
        if (addAll) {
            event.preventDefault();
            for (let i = 0; i < userlist.length; i++) {
                if (userlist[i].display === 1) {
                    userlist[i].display = 0;
                    userlist[i].send = 1;
                }
            }
            updateDisplay();
            return;
        }

        const remAll = event.target.closest('[data-rem-all]');
        if (remAll) {
            event.preventDefault();
            for (let i = 0; i < userlist.length; i++) {
                if (userlist[i].send === 1) {
                    userlist[i].display = 1;
                    userlist[i].send = 0;
                }
            }
            updateDisplay();
            return;
        }

        const addUser = event.target.closest('[data-add-user]');
        if (addUser) {
            event.preventDefault();
            const index = parseInt(addUser.getAttribute('data-add-user') || '-1', 10);
            if (index >= 0 && userlist[index]) {
                userlist[index].display = 0;
                userlist[index].send = 1;
                updateDisplay();
            }
            return;
        }

        const remUser = event.target.closest('[data-rem-user]');
        if (remUser) {
            event.preventDefault();
            const index = parseInt(remUser.getAttribute('data-rem-user') || '-1', 10);
            if (index >= 0 && userlist[index]) {
                userlist[index].display = 1;
                userlist[index].send = 0;
                updateDisplay();
            }
        }
    });

    if (subjectInput) {
        subjectInput.addEventListener('focus', function () {
            if (subjectInput.value === 'Subject..') subjectInput.value = '';
        });
    }
    if (textInput) {
        textInput.addEventListener('focus', function () {
            if (textInput.value === 'Text..') textInput.value = '';
        });
    }

    updateDisplay();
})();
