(function () {
    const help = {
        user_nickname:
            '<b>Benutzername</b><br>Gib hier deinen gewünschten Benutzernamen ein. Er sollte mindestens 3 und maximal 16 Zeichen lang sein.',
        user_password:
            '<b>Passwort</b><br>Gib hier dein gewünschtes Passwort ein. Es sollte mindestens 5 und maximal 32 Zeichen lang sein.',
        user_password_val:
            '<b>Passwort wiederholen</b><br>Gib hier dein Passwort noch einmal ein, um es zu bestätigen.',
        user_email:
            '<b>E-Mail</b><br>Gib hier deine E-Mail Adresse ein. Sie sollte gültig sein — du bekommst ein Aktivierungs-Mail an diese Adresse.',
        user_email_val:
            '<b>E-Mail wiederholen</b><br>Gib hier deine E-Mail Adresse noch einmal ein, um sie zu bestätigen.',
        user_fname: '<b>Vorname</b><br>Wenn du möchtest, kannst du hier deinen Vornamen eingeben.',
        user_lname: '<b>Nachname</b><br>Wenn du möchtest, kannst du hier deinen Nachnamen eingeben.',
        user_birthday:
            '<b>Geburtsdatum</b><br>Wenn du möchtest, kannst du hier dein Geburtsdatum angeben.',
        user_nationality:
            '<b>Nationalität</b><br>Wenn du möchtest, kannst du hier deine Nationalität angeben.',
        user_tos:
            '<b>Bedingungen</b><br>Hier musst du bestätigen, dass du die Bedingungen gelesen hast und akzeptierst.',
        user_code: '<b>Bestätigungs-Code</b><br>Bitte bestätige, dass du kein Bot bist.',
    };

    const target = document.getElementById('reg_helptext');
    const form = document.querySelector('form.registration-form');

    function showHelp(key) {
        if (target && help[key]) {
            target.innerHTML = help[key];
        }
    }

    document.querySelectorAll('[data-help]').forEach(function (el) {
        el.addEventListener('focusin', function () {
            showHelp(el.getAttribute('data-help'));
        });
        el.addEventListener('mouseover', function () {
            showHelp(el.getAttribute('data-help'));
        });
    });

    if (!form) {
        return;
    }

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

    function fieldByName(name) {
        return form.elements.namedItem(name);
    }

    function targetForServerError(message) {
        const lower = String(message || '').toLowerCase();
        if (lower.includes('bedingungen')) {
            return fieldByName('user_tos');
        }
        if (lower.includes('passwort')) {
            return lower.includes('überein') || lower.includes('ueberein')
                ? fieldByName('user_password_val')
                : fieldByName('user_password');
        }
        if (lower.includes('email') || lower.includes('e-mail')) {
            return lower.includes('überein') || lower.includes('ueberein')
                ? fieldByName('user_email_val')
                : fieldByName('user_email');
        }
        if (
            lower.includes('benutzername') ||
            lower.includes('leerzeichen') ||
            lower.includes('sonderzeichen') ||
            lower.includes('existiert bereits')
        ) {
            return fieldByName('user_nickname');
        }
        if (lower.includes('geburt') || lower.includes('alter') || lower.includes('jahr')) {
            return fieldByName('user_birth_day');
        }
        if (lower.includes('felder ausfüllen') || lower.includes('felder ausfuellen')) {
            const required = form.querySelectorAll('[required]');
            for (let i = 0; i < required.length; i++) {
                if (!required[i].checkValidity()) {
                    return required[i];
                }
            }
        }
        return fieldByName('user_nickname') || form.querySelector('[required]');
    }

    const nicknameDoubleSpecialMsg =
        'Sonderzeichen dürfen im Benutzernamen nicht zweimal hintereinander stehen!';

    function nicknameHasConsecutiveSpecials(value) {
        try {
            return /[^\p{L}\p{N}]{2,}/u.test(String(value || ''));
        } catch (err) {
            return /[^0-9A-Za-z\u00C0-\u024F\u0400-\u04FF]{2,}/.test(String(value || ''));
        }
    }

    function refreshNicknameValidity(input) {
        if (!input || input.name !== 'user_nickname') {
            return;
        }
        const value = String(input.value || '');
        if (value !== value.trim()) {
            input.setCustomValidity("Benutzernamen bitte ohne Leerzeichen ' ' am Beginn und Ende!");
            return;
        }
        if (nicknameHasConsecutiveSpecials(value)) {
            input.setCustomValidity(nicknameDoubleSpecialMsg);
            return;
        }
        if (input.validity.valueMissing || input.validity.tooShort || input.validity.tooLong) {
            input.setCustomValidity('');
            return;
        }
        input.setCustomValidity('');
    }

    form.querySelectorAll('input, select').forEach(function (input) {
        input.addEventListener('invalid', function () {
            if (input.name === 'user_nickname') {
                refreshNicknameValidity(input);
            }
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
            refreshNicknameValidity(input);
        });
        input.addEventListener('change', function () {
            clearError(input);
            refreshNicknameValidity(input);
        });
    });

    const serverErrors = Array.isArray(window.FFB_REGISTRATION_ERRORS)
        ? window.FFB_REGISTRATION_ERRORS
        : [];
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
