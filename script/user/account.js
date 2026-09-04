var help_array = new Array();
help_array["user_nickname"] = "<b>Benutzername</b><br>Der Benutzername kann nachtr&auml;glich nicht mehr ge&auml;ndert werden.";
help_array["user_password_chg"] = "<b>Passwort &auml;ndern</b><br>Wenn du m&ouml;chtest, kannst du hier dein Passwort &auml;ndern.";
help_array["user_password_val_chg"] = "<b>Passwort&auml;nderung wiederholen</b><br>Falls du dein Passwort ge&auml;ndert hast, dann gib hier dein ge&auml;ndertes Passwort noch einmal ein, um es zu best&auml;tigen.";
help_array["user_email"] = "<b>aktuelle E-Mail</b><br>Das ist deine aktuelle E-Mail Adresse. Du solltest immer eine aktive E-Mail Adresse eingetragen haben, um wichtige E-Mails nicht zu vers&auml;umen. Wenn du sie &auml;ndern m&ouml;chtest, dann verwende das Feld \"E-Mail &auml;ndern\".";
help_array["user_email_chg"] = "<b>E-Mail &auml;ndern</b><br>Wenn du m&ouml;chtest, kannst du hier deine E-Mail Adresse &auml;ndern. Sie sollte auf jeden Fall g&uuml;litig sein und du solltest Zugang dazu haben. Dein Account wird nach der &Auml;nderung deaktiviert. Du bekommst dann an die neue Adresse ein Best&auml;tigungsmail geschickt und du musst darin einen Link anklicken, um deinen Account wieder zu aktivieren.";
help_array["user_email_val_chg"] = "<b>E-Mail wiederholen</b><br>Falls du deine E-Mail Adresse ge&auml;ndert hast, dann gib hier deine ge&auml;nderte E-Mail Adresse noch einmal ein, um sie zu best&auml;tigen.";
help_array["user_fname"] = "<b>Vorname</b><br>Wenn du m&ouml;chtest, kannst du hier deinen Vornamen eingeben.";
help_array["user_lname"] = "<b>Nachname</b><br>Wenn du m&ouml;chtest, kannst du hier deinen Nachnamen eingeben.";
help_array["user_birthday"] = "<b>Geburtsdatum</b><br>Wenn du m&ouml;chtest, kannst du hier dein Geburtsdatum angeben.";
help_array["user_nationality"] = "<b>Nationalit&auml;t</b><br>Wenn du m&ouml;chtest, kannst du hier deine Nationalit&auml;t angeben. Ein entsprechendes Icon wird dann auf deiner Profilseite angezeigt.";
help_array["user_tos"] = "<b>Ich habe die Bedingungen akzeptiert</b><br>Hier musst du best&auml;tigen, dass du die Bedingungen dieser Seite gelesen hast und sie akzeptierst.";
help_array["user_code"] = "<b>Best&auml;tigungs-Code</b><br>Hier musst du noch einen Validierungs-Code eingeben. Ganz nebenbei hilfst du dabei mit, B&uuml;cher zu digitalisieren.";
help_array["user_details_city"] = "<b>Wohnort</b><br>Wenn du m&ouml;chtest, kannst du hier deinen Wohnort eingeben.";
help_array["user_details_zip"] = "<b>Postleitzahl</b><br>Wenn du m&ouml;chtest, kannst du hier die Postleitzahl deines Wohnorts eingeben.";
help_array["user_details_street"] = "<b>Stra&szlig;e und Hausnummer</b><br>Wenn du m&ouml;chtest, kannst du hier deine Stra&szlig;e und Hausnummer eingeben.";
help_array["user_details_phone"] = "<b>Telefonnummer</b><br>Wenn du m&ouml;chtest, kannst du hier deine Telefonnummer eingeben.";
help_array["user_details_website"] = "<b>Homepage</b><br>Wenn du m&ouml;chtest, kannst du hier deine pers&ouml;nliche Homepage angeben.";
help_array["user_details_ffb_favourite_team"] = "<b>Lieblingsteam</b><br>Wenn du m&ouml;chtest, kannst du hier dein Lieblingsteam aus allen im Fantasy-Football verf&uuml;gbaren Teams ausw&auml;hlen.";
help_array["user_details_photo"] = "<b>Profilfoto</b><br>Wenn du m&ouml;chtest, kannst du hier ein Profilfoto von dir hochladen. Das Bild darf h&ouml;chstens 1024 Pixel breit bzw. hoch sein und darf maximal 500 Kilobyte gro&szlig; sein.";
help_array["user_details_avatar"] = "<b>Avatarbild</b><br>Wenn du m&ouml;chtest, kannst du hier ein Avatarbild f&uuml;r dein Profil hochladen. Das Bild darf h&ouml;chstens 100 Pixel breit bzw. hoch sein und darf maximal 100 Kilobyte gro&szlig; sein.";
help_array["user_details_avatar_delete"] = "<b>Avatarbild zur&uuml;cksetzen</b><br>Wenn du JA ausw&auml;hlst, wird dein Avatarbild auf das Standard-Bild zur&uuml;ckgesetzt.";
help_array["user_details_photo_delete"] = "<b>Profilfoto zur&uuml;cksetzen</b><br>Wenn du JA ausw&auml;hlst, wird dein Profilfoto auf das Standard-Bild zur&uuml;ckgesetzt.";
help_array["user_permissions_ffb_mailservice_info"] = "<b>Infos per Mail erhalten</b><br>Wenn du JA ausw&auml;hlst, bekommst du wichtige Informationen von Fantasy Football an deine E-Mail Adresse geschickt.";
help_array["user_permissions_ffb_mailservice_reminder"] = "<b>Erinnerungen per Mail erhalten</b><br>Wenn du JA ausw&auml;hlst, bekommst du Aufstellungserinnerungen f&uuml;r die Ligen an denen du teilnimmst an deine E-Mail Adresse geschickt.";
help_array["user_permissions_ffb_visible_profile"] = "<b>Gesamtes Profil anzeigen</b><br>Wenn du JA ausw&auml;hlst, wird dein vollst&auml;ndiges Profil auf der Profil-Seite angezeigt. Zus&auml;tzlich zu den normalen Daten werden dann auch <em>Vorname</em>, <em>Nachname</em> und <em>Telefonnummer</em> angezeigt.";

function dispRegHelp(name){
	var target = document.getElementById("reg_helptext");
	target.style.visibility = "visible";
	dropLineW3("reg_helptext", help_array[name]);
}
