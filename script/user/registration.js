var help_array = new Array();
help_array["user_nickname"] = "<b>Benutzername</b><br>Gib hier deinen gew&uuml;nschten Benutzernamen ein. Er sollte mindestens 3 und maximal 16 Zeichen lang sein.";
help_array["user_password"] = "<b>Passwort</b><br>Gib hier deinen gew&uuml;nschten Passwort ein. Es sollte mindestens 5 und maximal 32 Zeichen lang sein.";
help_array["user_password_val"] = "<b>Passwort wiederholen</b><br>Gib hier dein Passwort noch einmal ein, um es zu best&auml;tigen.";
help_array["user_email"] = "<b>E-Mail</b><br>Gib hier deine E-Mail Adresse ein. Sie sollte auf jeden Fall g&uuml;ltig sein und du solltest Zugang dazu haben, denn du bekommst nach der Registrierung ein Aktivierungs-Mail an diese Adresse geschickt. Erst nachdem du den	Link aus dem Aktivierungs-Mail angeklickt hast, ist deine Registrierung abgeschlossen.";
help_array["user_email_val"] = "<b>E-Mail wiederholen</b><br>Gib hier deine E-Mail Adresse noch einmal ein, um sie zu best&auml;tigen.";
help_array["user_fname"] = "<b>Vorname</b><br>Wenn du m&ouml;chtest, kannst du hier deinen Vornamen eingeben.";
help_array["user_lname"] = "<b>Nachname</b><br>Wenn du m&ouml;chtest, kannst du hier deinen Nachnamen eingeben.";
help_array["user_birthday"] = "<b>Geburtsdatum</b><br>Wenn du m&ouml;chtest, kannst du hier dein Geburtsdatum angeben.";
help_array["user_nationality"] = "<b>Nationalit&auml;t</b><br>Wenn du m&ouml;chtest, kannst du hier deine Nationalit&auml;t angeben. Ein entsprechendes Icon wird dann auf deiner Profilseite angezeigt.";
help_array["user_tos"] = "<b>Ich habe die Bedingungen akzeptiert</b><br>Hier musst du best&auml;tigen, dass du die Bedingungen dieser Seite gelesen hast und sie akzeptierst.";
help_array["user_code"] = "<b>Best&auml;tigungs-Code</b><br>Hier musst du noch einen Validierungs-Code eingeben. Ganz nebenbei hilfst du dabei mit, B&uuml;cher zu digitalisieren.";

function dispRegHelp(name){
	var target = document.getElementById("reg_helptext");
	target.style.visibility = "visible";
	dropLineW3("reg_helptext", help_array[name]);
}
