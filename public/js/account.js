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
        user_details_photo:
            '<b>Profilfoto</b><br>Wenn du möchtest, kannst du hier ein Profilfoto von dir hochladen. Das Bild darf höchstens 1024 Pixel breit bzw. hoch sein und darf maximal 500 Kilobyte groß sein.',
        user_details_avatar:
            '<b>Avatarbild</b><br>Wenn du möchtest, kannst du hier ein Avatarbild für dein Profil hochladen. Das Bild darf höchstens 90 Pixel breit bzw. hoch sein und darf maximal 100 Kilobyte groß sein.',
        user_details_avatar_delete:
            '<b>Avatarbild zurücksetzen</b><br>Wenn du JA auswählst, wird dein Avatarbild auf das Standard-Bild zurückgesetzt.',
        user_details_photo_delete:
            '<b>Profilfoto zurücksetzen</b><br>Wenn du JA auswählst, wird dein Profilfoto auf das Standard-Bild zurückgesetzt.',
        user_permissions_ffb_mailservice_info:
            '<b>Infos per Mail erhalten</b><br>Wenn du JA auswählst, bekommst du wichtige Informationen von Fantasy Football an deine E-Mail Adresse geschickt.',
        user_permissions_ffb_mailservice_reminder:
            '<b>Erinnerungen per Mail erhalten</b><br>Wenn du JA auswählst, bekommst du Aufstellungserinnerungen für die Ligen an denen du teilnimmst an deine E-Mail Adresse geschickt.',
        user_permissions_ffb_visible_profile:
            '<b>Gesamtes Profil anzeigen</b><br>Wenn du JA auswählst, wird dein vollständiges Profil auf der Profil-Seite angezeigt. Zusätzlich zu den normalen Daten werden dann auch <em>Vorname</em>, <em>Nachname</em> und <em>Telefonnummer</em> angezeigt.',
    };

    const target = document.getElementById('account-helptext');
    if (!target) {
        return;
    }

    function showHelp(key) {
        if (!key || !help[key]) {
            return;
        }
        target.innerHTML = help[key];
    }

    document.querySelectorAll('[data-help]').forEach((el) => {
        const key = el.getAttribute('data-help');
        el.addEventListener('focus', () => showHelp(key));
        el.addEventListener('mouseover', () => showHelp(key));
    });
})();
