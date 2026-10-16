<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrieren — SoccerSportsfan</title>
    @php $assetBase = rtrim(config('ffb.home_path'), '/'); @endphp
    <link rel="stylesheet" href="{{ $assetBase }}/css/start.css?v=32">
    <link rel="stylesheet" href="{{ $assetBase }}/css/dashboard.css?v=17">
    <link rel="stylesheet" href="{{ $assetBase }}/css/account.css?v=7">
    <link rel="stylesheet" href="{{ $assetBase }}/css/registration.css?v=3">
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

    @include('partials.dash-top')

    <main class="dash-main account-layout">
        <section class="panel account-main" aria-labelledby="reg-title">
            <div class="section-head">
                <h2 id="reg-title">Account anlegen</h2>
            </div>

            @if ($answer)
                <div class="account-flash account-flash-ok" role="status">
                    {!! $answer !!}
                </div>
            @endif

            @if (!$answer)
            <form class="account-form registration-form" method="post" action="registration" autocomplete="off">
                @csrf

                <div class="account-field">
                    <label for="user_nickname">* Benutzername</label>
                    <input id="user_nickname" type="text" name="user_nickname" value="{{ $form['user_nickname'] }}" minlength="3" maxlength="16" required data-help="user_nickname" data-required-msg="Bitte Benutzername eingeben." data-too-short-msg="Benutzername: min. Länge ist 3, max. Länge ist 16!" data-too-long-msg="Benutzername: min. Länge ist 3, max. Länge ist 16!">
                </div>

                <div class="account-field">
                    <label for="user_password">* Passwort</label>
                    <input id="user_password" type="password" name="user_password" minlength="5" maxlength="32" required data-help="user_password" autocomplete="new-password" data-required-msg="Bitte Passwort eingeben." data-too-short-msg="Passwort: min. Länge ist 5, max. Länge ist 32!" data-too-long-msg="Passwort: min. Länge ist 5, max. Länge ist 32!">
                </div>

                <div class="account-field">
                    <label for="user_password_val">* Passwort wiederholen</label>
                    <input id="user_password_val" type="password" name="user_password_val" minlength="5" maxlength="32" required data-help="user_password_val" autocomplete="new-password" data-required-msg="Bitte Passwort wiederholen." data-too-short-msg="Passwort: min. Länge ist 5, max. Länge ist 32!" data-too-long-msg="Passwort: min. Länge ist 5, max. Länge ist 32!">
                </div>

                <div class="account-field">
                    <label for="user_email">* E-Mail</label>
                    <input id="user_email" type="email" name="user_email" value="{{ $form['user_email'] }}" required data-help="user_email" autocomplete="email" data-required-msg="Bitte E-Mail eingeben.">
                </div>

                <div class="account-field">
                    <label for="user_email_val">* E-Mail wiederholen</label>
                    <input id="user_email_val" type="email" name="user_email_val" value="{{ $form['user_email_val'] }}" required data-help="user_email_val" autocomplete="email" data-required-msg="Bitte E-Mail wiederholen.">
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
                        <input type="checkbox" name="user_tos" value="user_tos_yes" required data-required-msg="Du musst die Bedingungen gelesen und akzeptiert haben!">
                        <span>* Ich habe die <a href="{{ $tosUrl }}" target="_blank" rel="noopener">Bedingungen</a> akzeptiert</span>
                    </label>
                </div>

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

    @if (!empty($errors))
        <script>
            window.FFB_REGISTRATION_ERRORS = @json(array_values($errors));
        </script>
    @endif
    <script src="{{ $assetBase }}/js/registration.js?v=2" defer></script>
    @include('partials.footer')
    @include('partials.guest-login-scripts')
</body>
</html>
