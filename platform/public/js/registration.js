(function () {
    const help = {
        user_nickname: '<b>Benutzername</b><br>Gib hier deinen gewünschten Benutzernamen ein. Er sollte mindestens 3 und maximal 16 Zeichen lang sein.',
        user_password: '<b>Passwort</b><br>Gib hier dein gewünschtes Passwort ein. Es sollte mindestens 5 und maximal 32 Zeichen lang sein.',
        user_password_val: '<b>Passwort wiederholen</b><br>Gib hier dein Passwort noch einmal ein, um es zu bestätigen.',
        user_email: '<b>E-Mail</b><br>Gib hier deine E-Mail Adresse ein. Sie sollte gültig sein — du bekommst ein Aktivierungs-Mail an diese Adresse.',
        user_email_val: '<b>E-Mail wiederholen</b><br>Gib hier deine E-Mail Adresse noch einmal ein, um sie zu bestätigen.',
        user_fname: '<b>Vorname</b><br>Wenn du möchtest, kannst du hier deinen Vornamen eingeben.',
        user_lname: '<b>Nachname</b><br>Wenn du möchtest, kannst du hier deinen Nachnamen eingeben.',
        user_birthday: '<b>Geburtsdatum</b><br>Wenn du möchtest, kannst du hier dein Geburtsdatum angeben.',
        user_nationality: '<b>Nationalität</b><br>Wenn du möchtest, kannst du hier deine Nationalität angeben.',
        user_tos: '<b>Bedingungen</b><br>Hier musst du bestätigen, dass du die Bedingungen gelesen hast und akzeptierst.',
        user_code: '<b>Bestätigungs-Code</b><br>Bitte bestätige, dass du kein Bot bist.',
    };

    const target = document.getElementById('reg_helptext');
    if (!target) {
        return;
    }

    function showHelp(key) {
        if (help[key]) {
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
})();
