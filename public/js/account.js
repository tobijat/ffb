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
            '<b>Avatar</b><br>Wähle ein Trikot aus der Liste. Es wird als dein Avatarbild verwendet. Solange du nichts auswählst, bleibt dein bisheriges Avatar unverändert.',
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

    const avatarPreview = document.getElementById('account-avatar-preview');
    document.querySelectorAll('input[name="user_details_avatar_shirt"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (!avatarPreview || !radio.checked) {
                return;
            }
            const url = radio.getAttribute('data-preview-url');
            if (url) {
                avatarPreview.src = url;
            }
        });
    });

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
