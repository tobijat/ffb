<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/account.css?v=2">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $form = $data['form'];
        $countries = $data['countries'];
        $birthYears = $data['birth_years'];
        $months = ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            <a class="brand" href="/platform/">SoccerSportsfan</a>
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @foreach ($nav as $item)
                    <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="user-card">
            <a href="/platform/profile" title="Profil bearbeiten">
                <img class="user-photo" src="{{ $user['photo_url'] }}" alt="Foto {{ $user['user_nickname'] }}" width="48" height="48">
            </a>
            <div>
                <p class="hello">Hallo <strong>{{ $user['user_nickname'] }}</strong></p>
                <p class="muted">Du bist angemeldet.</p>
            </div>
        </div>
    </header>

    <main class="dash-main account-layout">
        <section class="panel account-main" aria-labelledby="account-title">
            <div class="section-head">
                <h2 id="account-title">Profildaten bearbeiten</h2>
            </div>

            @if (!empty($errors))
                <div class="account-flash account-flash-error" role="alert">
                    <strong>Es sind Fehler aufgetreten:</strong>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($answer)
                <div class="account-flash account-flash-ok" role="status">
                    {!! $answer !!}
                </div>
            @endif

            <form class="account-form" method="post" action="account" autocomplete="off">
                @csrf

                <div class="account-field">
                    <label for="user_nickname">Benutzername</label>
                    <input id="user_nickname" type="text" name="user_nickname" value="{{ $form['user_nickname'] }}" readonly data-help="user_nickname">
                </div>

                <div class="account-field">
                    <label for="user_fname">Vorname</label>
                    <input id="user_fname" type="text" name="user_fname" value="{{ $form['user_fname'] }}" maxlength="100" data-help="user_fname">
                </div>

                <div class="account-field">
                    <label for="user_lname">Nachname</label>
                    <input id="user_lname" type="text" name="user_lname" value="{{ $form['user_lname'] }}" maxlength="100" data-help="user_lname">
                </div>

                <div class="account-field">
                    <span class="account-label" id="birth-label">Geburtsdatum</span>
                    <div class="account-birth" role="group" aria-labelledby="birth-label">
                        <select name="user_birth_day" data-help="user_birthday" aria-label="Tag">
                            <option value=""></option>
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected((int) $form['user_birth_day'] === $i)>{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="user_birth_month" data-help="user_birthday" aria-label="Monat">
                            <option value=""></option>
                            @foreach ($months as $idx => $monthName)
                                <option value="{{ $idx + 1 }}" @selected((int) $form['user_birth_month'] === $idx + 1)>{{ $monthName }}</option>
                            @endforeach
                        </select>
                        <select name="user_birth_year" data-help="user_birthday" aria-label="Jahr">
                            <option value=""></option>
                            @foreach ($birthYears as $year)
                                <option value="{{ $year }}" @selected((int) $form['user_birth_year'] === (int) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="account-field">
                    <label for="user_nationality">Nationalität</label>
                    <select id="user_nationality" name="user_nationality" data-help="user_nationality">
                        <option value="">Land…</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}" @selected($form['user_nationality'] === $code)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="account-field">
                    <label for="user_password_chg">Passwort ändern</label>
                    <input id="user_password_chg" type="password" name="user_password_chg" maxlength="32" data-help="user_password_chg" autocomplete="new-password">
                </div>

                <div class="account-field">
                    <label for="user_password_val_chg">Passwortänderung wiederholen</label>
                    <input id="user_password_val_chg" type="password" name="user_password_val_chg" maxlength="32" data-help="user_password_val_chg" autocomplete="new-password">
                </div>

                <div class="account-field">
                    <label for="user_email">aktuelle E-Mail</label>
                    <input id="user_email" type="text" name="user_email" value="{{ $form['user_email'] }}" readonly data-help="user_email">
                </div>

                <div class="account-field">
                    <label for="user_email_chg">E-Mail ändern</label>
                    <input id="user_email_chg" type="email" name="user_email_chg" value="{{ $form['user_email_chg'] }}" maxlength="120" data-help="user_email_chg" autocomplete="off">
                </div>

                <div class="account-field">
                    <label for="user_email_val_chg">E-Mail Änderung wiederholen</label>
                    <input id="user_email_val_chg" type="email" name="user_email_val_chg" value="{{ $form['user_email_val_chg'] }}" maxlength="120" data-help="user_email_val_chg" autocomplete="off">
                </div>

                <div class="account-field account-field-check">
                    <label for="user_tos">
                        <input id="user_tos" type="checkbox" name="user_tos" value="user_tos_yes" data-help="user_tos">
                        Ich habe die <a href="{{ $legacyBase }}resource/Registrierung.pdf" target="_blank" rel="noopener">Bedingungen</a> akzeptiert
                    </label>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn" name="users_registration_update" value="1">Änderungen abschicken</button>
                </div>
            </form>
        </section>

        <aside class="panel account-help" aria-labelledby="account-help-title">
            <h2 id="account-help-title">Hinweise</h2>
            <p class="hint">Felder mit * sind Pflicht. Klick auf ein Feld, um weitere Hinweise anzuzeigen.</p>
            <div id="account-helptext" class="account-helptext">
                Alle Pflichtfelder müssen ausgefüllt werden. Klick auf ein Feld um weitere Hinweise anzuzeigen.
            </div>
        </aside>
    </main>

    <footer class="foot">
        <span>SoccerSportsfan</span>
        <a href="logout">Ausloggen</a>
    </footer>

    <script src="js/account.js?v=2" defer></script>
</body>
</html>
