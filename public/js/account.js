(function () {
    const help = {
        user_nickname:
            '<b>Benutzername</b><br>Der Benutzername kann nachträglich nicht mehr geändert werden.',
        user_password_chg:
            '<b>Passwort ändern</b><br>Wenn du möchtest, kannst du hier dein Passwort ändern.',
        user_password_val_chg:
            '<b>Passwortänderung wiederholen</b><br>Falls du dein Passwort geändert hast, dann gib hier dein geändertes Passwort noch einmal ein, um es zu bestätigen.',
        user_email:
            '<b>aktuelle E-Mail</b><br>Das ist deine aktuelle E-Mail Adresse. Du solltest immer eine aktive E-Mail Adresse eingetragen haben, um wichtige E-Mails nicht zu versäumen. Wenn du sie ändern möchtest, dann verwende das Feld „E-Mail ändern“.',
        user_email_chg:
            '<b>E-Mail ändern</b><br>Wenn du möchtest, kannst du hier deine E-Mail Adresse ändern. Sie sollte auf jeden Fall gültig sein und du solltest Zugang dazu haben. Dein Account wird nach der Änderung deaktiviert. Du bekommst dann an die neue Adresse ein Bestätigungsmail geschickt und du musst darin einen Link anklicken, um deinen Account wieder zu aktivieren.',
        user_email_val_chg:
            '<b>E-Mail wiederholen</b><br>Falls du deine E-Mail Adresse geändert hast, dann gib hier deine geänderte E-Mail Adresse noch einmal ein, um sie zu bestätigen.',
        user_fname: '<b>Vorname</b><br>Wenn du möchtest, kannst du hier deinen Vornamen eingeben.',
        user_lname: '<b>Nachname</b><br>Wenn du möchtest, kannst du hier deinen Nachnamen eingeben.',
        user_birthday:
            '<b>Geburtsdatum</b><br>Wenn du möchtest, kannst du hier dein Geburtsdatum angeben.',
        user_nationality:
            '<b>Nationalität</b><br>Wenn du möchtest, kannst du hier deine Nationalität angeben. Ein entsprechendes Icon wird dann auf deiner Profilseite angezeigt.',
        user_tos:
            '<b>Ich habe die Bedingungen akzeptiert</b><br>Hier musst du bestätigen, dass du die Bedingungen dieser Seite gelesen hast und sie akzeptierst.',
        user_details_city:
            '<b>Wohnort</b><br>Wenn du möchtest, kannst du hier deinen Wohnort eingeben.',
        user_details_zip:
            '<b>Postleitzahl</b><br>Wenn du möchtest, kannst du hier die Postleitzahl deines Wohnorts eingeben.',
        user_details_street:
            '<b>Straße und Hausnummer</b><br>Wenn du möchtest, kannst du hier deine Straße und Hausnummer eingeben.',
        user_details_phone:
            '<b>Telefonnummer</b><br>Wenn du möchtest, kannst du hier deine Telefonnummer eingeben.',
        user_details_website:
            '<b>Homepage</b><br>Wenn du möchtest, kannst du hier deine persönliche Homepage angeben.',
        user_details_ffb_favourite_team:
            '<b>Lieblingsteam</b><br>Wenn du möchtest, kannst du hier dein Lieblingsteam aus allen im Fantasy-Football verfügbaren Teams auswählen.',
        user_details_avatar_shirt:
            '<b>Avatar</b><br>Wähle ein vorhandenes Teamtrikot aus der Liste.',
        avatar_mode_ready:
            '<b>Fertige Trikots</b><br>Wähle ein vorhandenes Teamtrikot als Avatar.',
        avatar_mode_custom:
            '<b>Eigenes Trikot</b><br>Lass dir ein zufälliges Trikot generieren und pass es mit Marke, Sterne, Nummer und Rücketext an.',
        user_permissions_ffb_mailservice_info:
            '<b>Infos per Mail erhalten</b><br>Wenn du JA auswählst, bekommst du wichtige Informationen von Fantasy Football an deine E-Mail Adresse geschickt.',
        user_permissions_ffb_mailservice_reminder:
            '<b>Erinnerungen per Mail erhalten</b><br>Wenn du JA auswählst, bekommst du Aufstellungserinnerungen für die Ligen an denen du teilnimmst an deine E-Mail Adresse geschickt.',
        user_permissions_ffb_visible_profile:
            '<b>Gesamtes Profil anzeigen</b><br>Wenn du JA auswählst, wird dein vollständiges Profil auf der Profil-Seite angezeigt. Zusätzlich zu den normalen Daten werden dann auch <em>Vorname</em>, <em>Nachname</em> und <em>Telefonnummer</em> angezeigt.',
    };

    const target = document.getElementById('account-helptext');

    function showHelp(key) {
        if (!target || !key || !help[key]) {
            return;
        }
        target.innerHTML = help[key];
    }

    document.querySelectorAll('[data-help]').forEach(function (el) {
        const key = el.getAttribute('data-help');
        el.addEventListener('focus', function () {
            showHelp(key);
        });
        el.addEventListener('mouseover', function () {
            showHelp(key);
        });
    });

    function markError(el) {
        if (!el) {
            return;
        }
        el.classList.add('is-error');
        const checkWrap = el.closest('.account-field-check');
        if (checkWrap) {
            checkWrap.classList.add('is-error');
        }
    }

    function clearError(el) {
        if (!el) {
            return;
        }
        el.classList.remove('is-error');
        el.setCustomValidity('');
        const checkWrap = el.closest('.account-field-check');
        if (checkWrap) {
            checkWrap.classList.remove('is-error');
        }
    }

    function fieldInForms(name) {
        const forms = document.querySelectorAll('form.account-form');
        for (let i = 0; i < forms.length; i++) {
            const el = forms[i].elements.namedItem(name);
            if (el) {
                return el;
            }
        }
        return null;
    }

    function targetForServerError(message) {
        const lower = String(message || '').toLowerCase();
        if (lower.includes('bedingungen')) {
            return fieldInForms('user_tos');
        }
        if (lower.includes('passwort')) {
            return lower.includes('überein') || lower.includes('ueberein')
                ? fieldInForms('user_password_val_chg')
                : fieldInForms('user_password_chg');
        }
        if (lower.includes('e-mail') || lower.includes('email')) {
            return lower.includes('überein') || lower.includes('ueberein')
                ? fieldInForms('user_email_val_chg')
                : fieldInForms('user_email_chg');
        }
        if (lower.includes('geburt')) {
            return fieldInForms('user_birth_day');
        }
        if (lower.includes('avatar') || lower.includes('trikot')) {
            return document.querySelector('input[name="user_details_avatar_shirt"]');
        }
        return (
            fieldInForms('user_password_chg') ||
            fieldInForms('user_details_city') ||
            document.querySelector('form.account-form input, form.account-form select')
        );
    }

    document.querySelectorAll('form.account-form').forEach(function (form) {
        form.querySelectorAll('input, select').forEach(function (input) {
            input.addEventListener('invalid', function () {
                if (input.validity.valueMissing) {
                    input.setCustomValidity(
                        input.getAttribute('data-required-msg') || 'Bitte fülle dieses Feld aus.'
                    );
                } else if (input.validity.typeMismatch && input.type === 'email') {
                    input.setCustomValidity('Bitte eine gültige E-Mail-Adresse eingeben.');
                } else if (input.validity.tooShort) {
                    input.setCustomValidity(
                        input.getAttribute('data-too-short-msg') || 'Eingabe ist zu kurz.'
                    );
                } else if (input.validity.tooLong) {
                    input.setCustomValidity(
                        input.getAttribute('data-too-long-msg') || 'Eingabe ist zu lang.'
                    );
                }
                if (!input.validity.valid) {
                    markError(input);
                }
            });
            input.addEventListener('input', function () {
                clearError(input);
            });
            input.addEventListener('change', function () {
                clearError(input);
            });
        });
    });

    const avatarTab = document.getElementById('tab-avatar');
    const avatarPreview = document.getElementById('account-avatar-preview');
    const readyForm = document.getElementById('avatar-ready-form');
    const readySaveBtn = document.getElementById('avatar-ready-save');
    const modeReady = document.getElementById('avatar-mode-ready');
    const modeCustom = document.getElementById('avatar-mode-custom');
    const customPreview = document.getElementById('avatar-custom-preview');
    const customPreviewBack = document.getElementById('avatar-custom-preview-back');
    const customPayload = document.getElementById('avatar-svg-payload');
    const customBackPayload = document.getElementById('avatar-svg-back-payload');
    const customSeedPayload = document.getElementById('avatar-seed-payload');
    const customForm = document.getElementById('avatar-custom-form');
    const regenerateBtn = document.getElementById('avatar-regenerate');
    const customSaveBtn = document.getElementById('avatar-custom-save');
    const kitPrevBtn = document.getElementById('avatar-kit-prev');
    const kitNextBtn = document.getElementById('avatar-kit-next');
    const kitMaker = document.getElementById('avatar-kit-maker');
    const kitStars = document.getElementById('avatar-kit-stars');
    const kitNumber = document.getElementById('avatar-kit-number');
    const kitText = document.getElementById('avatar-kit-text');
    const kitMcolor = document.getElementById('avatar-kit-mcolor');
    const kitNcolor = document.getElementById('avatar-kit-ncolor');
    const kitTcolor = document.getElementById('avatar-kit-tcolor');
    const kitScolor = document.getElementById('avatar-kit-scolor');
    const kitColorInputs = [kitMcolor, kitNcolor, kitTcolor, kitScolor].filter(Boolean);
    const kitColorDefaults = {
        mcolor: '#ffffff',
        ncolor: '#ffffff',
        tcolor: '#ffffff',
        scolor: '#ffd700',
    };
    const savedAvatar = avatarTab ? (avatarTab.getAttribute('data-current-avatar') || '').trim() : '';
    const savedAvatarUrl = avatarTab ? avatarTab.getAttribute('data-current-avatar-url') || '' : '';
    const savedAvatarBackUrl = avatarTab
        ? avatarTab.getAttribute('data-current-avatar-back-url') || savedAvatarUrl
        : '';
    const savedAvatarSeed = avatarTab
        ? (avatarTab.getAttribute('data-current-avatar-seed') || '').trim()
        : '';
    const savedAvatarIsShirt = avatarTab
        ? avatarTab.getAttribute('data-current-avatar-is-shirt') === '1'
        : false;
    const kitFromShirtUrl = avatarTab
        ? (avatarTab.getAttribute('data-kit-from-shirt-url') || '').trim()
        : '';
    const regenerateLabel = 'Neu generieren';
    const regenerateLoadingLabel = 'Trikot wird geladen…';
    const kitSeedPattern = /^[A-Za-z0-9_-]{1,512}$/;
    let customSvg = '';
    let customSvgBack = '';
    let customSeed = '';
    let customBlobUrl = '';
    let customBlobUrlBack = '';
    let customDirty = false;
    let readyDirty = false;
    let kitHistory = [];
    let kitIndex = -1;
    let kitBusy = false;
    let kitRestoringOptions = false;
    let kitBootstrappedFromShirt = false;

    function xsrfToken() {
        const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    function isKitSeed(value) {
        return kitSeedPattern.test(String(value || '').trim());
    }

    function setAvatarMode(mode) {
        const isCustom = mode === 'custom';
        if (modeReady) {
            modeReady.hidden = isCustom;
        }
        if (modeCustom) {
            modeCustom.hidden = !isCustom;
        }
    }

    function updateReadySaveState() {
        if (!readySaveBtn) {
            return;
        }
        const checked = document.querySelector('input[name="user_details_avatar_shirt"]:checked');
        readyDirty = !!(checked && checked.value !== savedAvatar);
        readySaveBtn.disabled = !readyDirty;
    }

    function updateKitNavState() {
        if (kitPrevBtn) {
            kitPrevBtn.disabled = kitBusy || kitIndex <= 0;
        }
        if (kitNextBtn) {
            kitNextBtn.disabled = kitBusy || kitIndex < 0 || kitIndex >= kitHistory.length - 1;
        }
    }

    function updateCustomSaveState(busy) {
        kitBusy = !!busy;
        if (regenerateBtn) {
            regenerateBtn.disabled = kitBusy;
            regenerateBtn.textContent = kitBusy ? regenerateLoadingLabel : regenerateLabel;
        }
        if (customSaveBtn) {
            customSaveBtn.disabled =
                kitBusy || !customDirty || !customSvg || !customSvgBack || !customSeed;
        }
        if (kitMaker) {
            kitMaker.disabled = kitBusy;
        }
        if (kitStars) {
            kitStars.disabled = kitBusy;
        }
        if (kitNumber) {
            kitNumber.disabled = kitBusy;
        }
        if (kitText) {
            kitText.disabled = kitBusy;
        }
        kitColorInputs.forEach(function (input) {
            input.disabled = kitBusy;
        });
        updateKitNavState();
    }

    function normalizeKitColor(value) {
        const raw = String(value || '')
            .trim()
            .toLowerCase();
        if (!raw) {
            return '';
        }
        const match = raw.match(/^#?([0-9a-f]{3}|[0-9a-f]{6})$/);
        if (!match) {
            return '';
        }
        let hex = match[1];
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        return '#' + hex;
    }

    function readKitColor(input) {
        if (!input || input.classList.contains('is-auto')) {
            return '';
        }
        return normalizeKitColor(input.value);
    }

    function setKitColorInput(input, value) {
        if (!input) {
            return;
        }
        const key = input.getAttribute('data-kit-color') || '';
        const normalized = normalizeKitColor(value);
        if (normalized) {
            input.value = normalized;
            input.classList.remove('is-auto');
        } else {
            input.value = kitColorDefaults[key] || '#ffffff';
            input.classList.add('is-auto');
        }
    }

    function emptyKitOptions() {
        return {
            maker: '',
            stars: 0,
            number: '',
            text: '',
            mcolor: '',
            ncolor: '',
            tcolor: '',
            scolor: '',
        };
    }

    function readKitOptions() {
        let stars = 0;
        if (kitStars) {
            stars = parseInt(kitStars.value, 10);
            if (isNaN(stars) || stars < 0 || stars > 9) {
                stars = 0;
            }
        }
        let number = kitNumber ? String(kitNumber.value || '').trim() : '';
        if (number !== '' && !/^\d{1,2}$/.test(number)) {
            number = '';
        }
        let maker = kitMaker ? String(kitMaker.value || '').trim().toLowerCase() : '';
        let text = '';
        if (kitText) {
            text = String(kitText.value || '')
                .replace(/[\u0000-\u001F\u007F]+/g, '')
                .trim()
                .slice(0, 16);
        }
        return {
            maker: maker,
            stars: stars,
            number: number,
            text: text,
            mcolor: readKitColor(kitMcolor),
            ncolor: readKitColor(kitNcolor),
            tcolor: readKitColor(kitTcolor),
            scolor: readKitColor(kitScolor),
        };
    }

    function applyKitOptionsToForm(opts) {
        kitRestoringOptions = true;
        if (kitMaker) {
            kitMaker.value = opts.maker || '';
        }
        if (kitStars) {
            kitStars.value = String(opts.stars != null ? opts.stars : 0);
        }
        if (kitNumber) {
            kitNumber.value = opts.number || '';
        }
        if (kitText) {
            kitText.value = opts.text || '';
        }
        setKitColorInput(kitMcolor, opts.mcolor || '');
        setKitColorInput(kitNcolor, opts.ncolor || '');
        setKitColorInput(kitTcolor, opts.tcolor || '');
        setKitColorInput(kitScolor, opts.scolor || '');
        kitRestoringOptions = false;
    }

    function appendKitColorParams(params, opts) {
        ['mcolor', 'ncolor', 'tcolor', 'scolor'].forEach(function (key) {
            const color = normalizeKitColor(opts[key] || '');
            if (color) {
                params.set(key, color.slice(1));
            }
        });
    }

    function buildKitQuery(extra) {
        const opts = readKitOptions();
        const params = new URLSearchParams();
        params.set('stars', String(opts.stars));
        if (opts.maker) {
            params.set('maker', opts.maker);
        }
        if (opts.number) {
            params.set('number', opts.number);
        }
        if (opts.text) {
            params.set('text', opts.text);
        }
        appendKitColorParams(params, opts);
        if (extra && extra.seed) {
            params.set('seed', String(extra.seed));
        }
        params.set('_', String(Date.now()));
        return params;
    }

    function revokeKitBlobs() {
        if (customBlobUrl) {
            URL.revokeObjectURL(customBlobUrl);
            customBlobUrl = '';
        }
        if (customBlobUrlBack) {
            URL.revokeObjectURL(customBlobUrlBack);
            customBlobUrlBack = '';
        }
    }

    function showCurrentAvatarInCustomPreview() {
        revokeKitBlobs();
        if (customPreview && savedAvatarUrl) {
            customPreview.src = savedAvatarUrl;
            customPreview.alt = 'Vorderansicht';
        }
        if (customPreviewBack) {
            customPreviewBack.src = savedAvatarBackUrl || savedAvatarUrl;
            customPreviewBack.alt = 'Rückansicht';
        }
    }

    function showCustomEntry(entry, restoreOptions) {
        customSvg = entry.svg;
        customSvgBack = entry.svgBack || '';
        customSeed = entry.seed;
        customDirty = !entry.isSaved;
        if (customPayload) {
            customPayload.value = entry.svg;
        }
        if (customBackPayload) {
            customBackPayload.value = customSvgBack;
        }
        if (customSeedPayload) {
            customSeedPayload.value = entry.seed;
        }
        revokeKitBlobs();
        if (customPreview) {
            customBlobUrl = URL.createObjectURL(
                new Blob([entry.svg], { type: 'image/svg+xml' })
            );
            customPreview.src = customBlobUrl;
            customPreview.alt = 'Vorderansicht';
        }
        if (customPreviewBack && customSvgBack) {
            customBlobUrlBack = URL.createObjectURL(
                new Blob([customSvgBack], { type: 'image/svg+xml' })
            );
            customPreviewBack.src = customBlobUrlBack;
            customPreviewBack.alt = 'Rückansicht';
        }
        if (restoreOptions !== false) {
            applyKitOptionsToForm(entry.options || emptyKitOptions());
        }
        updateCustomSaveState(false);
    }

    function pushKitHistoryEntry(entry, replaceCurrent) {
        if (replaceCurrent && kitIndex >= 0 && kitIndex < kitHistory.length) {
            kitHistory[kitIndex] = entry;
        } else {
            if (kitIndex < kitHistory.length - 1) {
                kitHistory = kitHistory.slice(0, kitIndex + 1);
            }
            kitHistory.push(entry);
            kitIndex = kitHistory.length - 1;
        }
        showCustomEntry(entry, false);
    }

    function bootstrapFromReadyShirt() {
        if (
            kitBootstrappedFromShirt ||
            kitBusy ||
            !savedAvatarIsShirt ||
            !kitFromShirtUrl ||
            !avatarTab
        ) {
            return;
        }
        kitBootstrappedFromShirt = true;
        updateCustomSaveState(true);
        const requestOptions = readKitOptions();
        const body = new URLSearchParams();
        body.set('shirt', savedAvatar);
        body.set('stars', String(requestOptions.stars));
        if (requestOptions.maker) {
            body.set('maker', requestOptions.maker);
        }
        if (requestOptions.number) {
            body.set('number', requestOptions.number);
        }
        if (requestOptions.text) {
            body.set('text', requestOptions.text);
        }
        appendKitColorParams(body, requestOptions);
        const headers = {
            Accept: 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest',
        };
        const xsrf = xsrfToken();
        if (xsrf) {
            headers['X-XSRF-TOKEN'] = xsrf;
        }
        fetch(kitFromShirtUrl, {
            method: 'POST',
            cache: 'no-store',
            credentials: 'same-origin',
            headers: headers,
            body: body.toString(),
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('HTTP ' + res.status);
                }
                return res.json();
            })
            .then(function (data) {
                const svg = String((data && data.svg) || '').trim();
                const svgBack = String((data && data.svg_back) || '').trim();
                const seed = String((data && data.seed) || '').trim();
                if (!/<svg\b/i.test(svg) || !/<svg\b/i.test(svgBack) || !isKitSeed(seed)) {
                    throw new Error('Ungültige Antwort');
                }
                kitHistory = [];
                kitIndex = -1;
                pushKitHistoryEntry(
                    {
                        seed: seed,
                        svg: svg,
                        svgBack: svgBack,
                        isSaved: false,
                        options: {
                            maker: requestOptions.maker,
                            stars: requestOptions.stars,
                            number: requestOptions.number,
                            text: requestOptions.text,
                            mcolor: requestOptions.mcolor,
                            ncolor: requestOptions.ncolor,
                            tcolor: requestOptions.tcolor,
                            scolor: requestOptions.scolor,
                        },
                    },
                    false
                );
            })
            .catch(function () {
                kitBootstrappedFromShirt = false;
                updateCustomSaveState(false);
                showCurrentAvatarInCustomPreview();
            });
    }

    function initHistoryFromSavedCustom() {
        if (!savedAvatarSeed || !savedAvatarUrl || !isKitSeed(savedAvatarSeed)) {
            return;
        }
        const frontReq = fetch(savedAvatarUrl, {
            credentials: 'same-origin',
            cache: 'no-store',
        }).then(function (res) {
            if (!res.ok) {
                throw new Error('front');
            }
            return res.text();
        });
        const backReq = fetch(savedAvatarBackUrl || savedAvatarUrl, {
            credentials: 'same-origin',
            cache: 'no-store',
        }).then(function (res) {
            if (!res.ok) {
                throw new Error('back');
            }
            return res.text();
        });
        Promise.all([frontReq, backReq])
            .then(function (parts) {
                const svg = String(parts[0] || '').trim();
                const svgBack = String(parts[1] || '').trim();
                if (!/<svg\b/i.test(svg) || !/<svg\b/i.test(svgBack)) {
                    throw new Error('svg');
                }
                kitHistory = [
                    {
                        seed: savedAvatarSeed,
                        svg: svg,
                        svgBack: svgBack,
                        isSaved: true,
                        options: emptyKitOptions(),
                    },
                ];
                kitIndex = 0;
                showCustomEntry(kitHistory[0], true);
            })
            .catch(function () {
                // Keep empty history; static preview URLs already show the saved kit.
            });
    }

    function showHistoryEntry(index) {
        if (index < 0 || index >= kitHistory.length) {
            return;
        }
        kitIndex = index;
        showCustomEntry(kitHistory[index], true);
    }

    function loadKit(options) {
        options = options || {};
        if (!avatarTab || kitBusy) {
            return;
        }
        const baseUrl = avatarTab.getAttribute('data-kit-random-url') || '';
        if (!baseUrl) {
            updateCustomSaveState(false);
            return;
        }
        const keepSeed = options.seed || null;
        const replaceCurrent = !!options.replaceCurrent;
        updateCustomSaveState(true);
        const params = buildKitQuery(keepSeed ? { seed: keepSeed } : null);
        const joiner = baseUrl.indexOf('?') >= 0 ? '&' : '?';
        const url = baseUrl + joiner + params.toString();
        const requestOptions = readKitOptions();
        fetch(url, {
            cache: 'no-store',
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('HTTP ' + res.status);
                }
                return res.json();
            })
            .then(function (data) {
                const svg = String((data && data.svg) || '').trim();
                const svgBack = String((data && data.svg_back) || '').trim();
                const seed = String((data && data.seed) || '').trim();
                if (!/<svg\b/i.test(svg) || !/<svg\b/i.test(svgBack) || !isKitSeed(seed)) {
                    throw new Error('Ungültige Antwort');
                }
                pushKitHistoryEntry(
                    {
                        seed: seed,
                        svg: svg,
                        svgBack: svgBack,
                        isSaved: false,
                        options: {
                            maker: requestOptions.maker,
                            stars: requestOptions.stars,
                            number: requestOptions.number,
                            text: requestOptions.text,
                            mcolor: requestOptions.mcolor,
                            ncolor: requestOptions.ncolor,
                            tcolor: requestOptions.tcolor,
                            scolor: requestOptions.scolor,
                        },
                    },
                    replaceCurrent
                );
            })
            .catch(function () {
                if (kitHistory.length === 0) {
                    customSvg = '';
                    customSvgBack = '';
                    customSeed = '';
                    customDirty = false;
                    if (customPayload) {
                        customPayload.value = '';
                    }
                    if (customBackPayload) {
                        customBackPayload.value = '';
                    }
                    if (customSeedPayload) {
                        customSeedPayload.value = '';
                    }
                    showCurrentAvatarInCustomPreview();
                } else {
                    showHistoryEntry(kitIndex);
                    return;
                }
                updateCustomSaveState(false);
            });
    }

    function onKitOptionsChanged() {
        if (kitRestoringOptions || kitBusy) {
            return;
        }
        if (kitIndex >= 0 && kitHistory[kitIndex] && kitHistory[kitIndex].seed) {
            loadKit({ seed: kitHistory[kitIndex].seed, replaceCurrent: true });
        }
    }

    document.querySelectorAll('input[name="user_details_avatar_shirt"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (!radio.checked) {
                return;
            }
            const url = radio.getAttribute('data-preview-url');
            if (avatarPreview && url) {
                avatarPreview.src = url;
            }
            updateReadySaveState();
        });
    });

    document.querySelectorAll('input[name="avatar_mode"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (!radio.checked) {
                return;
            }
            setAvatarMode(radio.value);
            if (radio.value === 'custom') {
                bootstrapFromReadyShirt();
            }
        });
    });

    if (regenerateBtn) {
        regenerateBtn.addEventListener('click', function () {
            loadKit();
        });
    }

    if (kitPrevBtn) {
        kitPrevBtn.addEventListener('click', function () {
            if (kitBusy || kitIndex <= 0) {
                return;
            }
            showHistoryEntry(kitIndex - 1);
        });
    }

    if (kitNextBtn) {
        kitNextBtn.addEventListener('click', function () {
            if (kitBusy || kitIndex < 0 || kitIndex >= kitHistory.length - 1) {
                return;
            }
            showHistoryEntry(kitIndex + 1);
        });
    }

    if (kitMaker) {
        kitMaker.addEventListener('change', onKitOptionsChanged);
    }
    if (kitStars) {
        kitStars.addEventListener('change', onKitOptionsChanged);
    }
    if (kitNumber) {
        kitNumber.addEventListener('change', function () {
            if (kitNumber.value !== '' && !/^\d{1,2}$/.test(kitNumber.value.trim())) {
                kitNumber.value = '';
            }
            onKitOptionsChanged();
        });
        kitNumber.addEventListener('input', function () {
            kitNumber.value = kitNumber.value.replace(/\D/g, '').slice(0, 2);
        });
    }
    if (kitText) {
        kitText.addEventListener('change', onKitOptionsChanged);
    }
    kitColorInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            input.classList.remove('is-auto');
            onKitOptionsChanged();
        });
        input.addEventListener('change', function () {
            input.classList.remove('is-auto');
            onKitOptionsChanged();
        });
        input.addEventListener('dblclick', function (event) {
            event.preventDefault();
            setKitColorInput(input, '');
            onKitOptionsChanged();
        });
    });

    if (readyForm) {
        readyForm.addEventListener('submit', function (event) {
            updateReadySaveState();
            if (!readyDirty) {
                event.preventDefault();
            }
        });
    }

    if (customForm) {
        customForm.addEventListener('submit', function (event) {
            if (!customDirty || !customSvg || !customSvgBack || !customSeed) {
                event.preventDefault();
                return;
            }
            if (customPayload) {
                customPayload.value = customSvg;
            }
            if (customBackPayload) {
                customBackPayload.value = customSvgBack;
            }
            if (customSeedPayload) {
                customSeedPayload.value = customSeed;
            }
        });
    }

    updateReadySaveState();
    updateCustomSaveState(false);
    if (avatarTab) {
        const checkedMode = document.querySelector('input[name="avatar_mode"]:checked');
        const mode = checkedMode ? checkedMode.value : 'ready';
        setAvatarMode(mode);
        initHistoryFromSavedCustom();
        if (mode === 'custom') {
            bootstrapFromReadyShirt();
        }
    }

    const serverErrors = Array.isArray(window.FFB_ACCOUNT_ERRORS) ? window.FFB_ACCOUNT_ERRORS : [];
    if (serverErrors.length) {
        const message = String(serverErrors[0] || '').trim();
        if (message) {
            const el = targetForServerError(message);
            if (el) {
                el.setCustomValidity(message);
                markError(el);
                requestAnimationFrame(function () {
                    el.focus({ preventScroll: true });
                    el.reportValidity();
                });
            }
        }
    }
})();
