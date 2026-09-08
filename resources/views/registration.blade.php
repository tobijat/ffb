<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrieren — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/account.css?v=2">
    <link rel="stylesheet" href="css/registration.css?v=1">
    @if (!empty($data['recaptcha_enabled']))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body class="dash-body">
    @php
        $nav = $data['navigation'];
        $form = $data['form'];
        $countries = $data['countries'];
        $birthYears = $data['birth_years'];
        $months = ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
        $tosUrl = $data['tos_url'];
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @foreach ($nav as $item)
                    <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    <main class="dash-main account-layout">
        <section class="panel account-main" aria-labelledby="reg-title">
            <div class="section-head">
                <h2 id="reg-title">Account anlegen</h2>
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

            @if (!$answer)
            <form class="account-form" method="post" action="registration" autocomplete="off">
                @csrf

                <div class="account-field">
                    <label for="user_nickname">* Benutzername</label>
                    <input id="user_nickname" type="text" name="user_nickname" value="{{ $form['user_nickname'] }}" maxlength="16" required data-help="user_nickname">
                </div>

                <div class="account-field">
                    <label for="user_password">* Passwort</label>
                    <input id="user_password" type="password" name="user_password" maxlength="32" required data-help="user_password" autocomplete="new-password">
                </div>

                <div class="account-field">
                    <label for="user_password_val">* Passwort wiederholen</label>
                    <input id="user_password_val" type="password" name="user_password_val" maxlength="32" required data-help="user_password_val" autocomplete="new-password">
                </div>

                <div class="account-field">
                    <label for="user_email">* E-Mail</label>
                    <input id="user_email" type="email" name="user_email" value="{{ $form['user_email'] }}" required data-help="user_email" autocomplete="email">
                </div>

                <div class="account-field">
                    <label for="user_email_val">* E-Mail wiederholen</label>
                    <input id="user_email_val" type="email" name="user_email_val" value="{{ $form['user_email_val'] }}" required data-help="user_email_val" autocomplete="email">
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
                    <span class="account-label">Geburtsdatum</span>
                    <div class="account-birth" data-help="user_birthday">
                        <select name="user_birth_day" aria-label="Tag">
                            <option value=""></option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" @selected((int) $form['user_birth_day'] === $i)>{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="user_birth_month" aria-label="Monat">
                            <option value=""></option>
                            @foreach ($months as $idx => $name)
                                <option value="{{ $idx + 1 }}" @selected((int) $form['user_birth_month'] === $idx + 1)>{{ $name }}</option>
                            @endforeach
                        </select>
                        <select name="user_birth_year" aria-label="Jahr">
                            <option value=""></option>
                            @foreach ($birthYears as $year)
                                <option value="{{ $year }}" @selected((int) $form['user_birth_year'] === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="account-field">
                    <label for="user_nationality">Nationalität</label>
                    <select id="user_nationality" name="user_nationality" data-help="user_nationality">
                        <option value="">Land...</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}" @selected($form['user_nationality'] === $code)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="account-field account-field-check">
                    <label data-help="user_tos">
                        <input type="checkbox" name="user_tos" value="user_tos_yes" required>
                        <span>* Ich habe die <a href="{{ $tosUrl }}" target="_blank" rel="noopener">Bedingungen</a> akzeptiert</span>
                    </label>
                </div>

                @if (!empty($data['recaptcha_enabled']))
                    <div class="account-field account-field-check" data-help="user_code">
                        <div class="g-recaptcha" data-sitekey="{{ $data['recaptcha_site_key'] }}"></div>
                    </div>
                @endif

                <div class="account-actions">
                    <button type="submit" class="btn" name="users_registration_insert" value="1">Registrieren</button>
                </div>
            </form>
            @endif
        </section>

        <aside class="panel account-side" aria-label="Hinweise">
            <div class="section-head">
                <h2>Hinweise</h2>
            </div>
            <div id="reg_helptext" class="account-helptext">
                Alle Felder die mit einem * markiert sind, müssen ausgefüllt werden. Klick auf ein Feld um weitere Hinweise anzuzeigen.
            </div>
        </aside>
    </main>

    <script src="js/registration.js?v=1" defer></script>
    @include('partials.footer')
</body>
</html>
