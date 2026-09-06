<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css?v=3">
    <link rel="stylesheet" href="css/account.css?v=3">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $profileForm = $data['form'];
        $teams = $data['teams'];
        $accountForm = $accountData['form'];
        $countries = $accountData['countries'];
        $birthYears = $accountData['birth_years'];
        $months = ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
        $activeTab = ($tab ?? 'profile') === 'account' ? 'account' : 'profile';
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

        @include('partials.user-card')
    </header>

    <main class="dash-main account-layout">
        <section class="panel account-main" aria-labelledby="profile-hub-title">
            <div class="section-head">
                <h2 id="profile-hub-title">Profil</h2>
            </div>

            <div class="account-tabs" role="tablist" aria-label="Profilbereiche">
                <a
                    class="account-tab {{ $activeTab === 'profile' ? 'is-active' : '' }}"
                    href="/platform/profile"
                    role="tab"
                    aria-selected="{{ $activeTab === 'profile' ? 'true' : 'false' }}"
                >Profildetails</a>
                <a
                    class="account-tab {{ $activeTab === 'account' ? 'is-active' : '' }}"
                    href="/platform/profile?tab=account"
                    role="tab"
                    aria-selected="{{ $activeTab === 'account' ? 'true' : 'false' }}"
                >Accountdaten</a>
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

            <div class="account-tab-panel" id="tab-profile" role="tabpanel" @if ($activeTab !== 'profile') hidden @endif>
                <form class="account-form" method="post" action="profile" enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <div class="account-field">
                        <label for="user_details_city">Wohnort</label>
                        <input id="user_details_city" type="text" name="user_details_city" value="{{ $profileForm['user_details_city'] }}" maxlength="100" data-help="user_details_city">
                    </div>

                    <div class="account-field">
                        <label for="user_details_zip">Postleitzahl</label>
                        <input id="user_details_zip" type="text" name="user_details_zip" value="{{ $profileForm['user_details_zip'] }}" maxlength="20" data-help="user_details_zip">
                    </div>

                    <div class="account-field">
                        <label for="user_details_street">Straße und Hausnummer</label>
                        <input id="user_details_street" type="text" name="user_details_street" value="{{ $profileForm['user_details_street'] }}" maxlength="120" data-help="user_details_street">
                    </div>

                    <div class="account-field">
                        <label for="user_details_phone">Telefonnummer</label>
                        <input id="user_details_phone" type="text" name="user_details_phone" value="{{ $profileForm['user_details_phone'] }}" maxlength="40" data-help="user_details_phone">
                    </div>

                    <div class="account-field">
                        <label for="user_details_website">Homepage</label>
                        <input id="user_details_website" type="text" name="user_details_website" value="{{ $profileForm['user_details_website'] }}" maxlength="200" data-help="user_details_website">
                    </div>

                    <div class="account-field">
                        <label for="user_details_ffb_favourite_team">Lieblingsteam</label>
                        <select id="user_details_ffb_favourite_team" name="user_details_ffb_favourite_team" data-help="user_details_ffb_favourite_team">
                            @foreach ($teams as $team)
                                <option value="{{ $team['team_id'] }}" @selected((int) $profileForm['user_details_ffb_favourite_team'] === (int) $team['team_id'])>
                                    {{ $team['team_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="account-sep">

                    <div class="account-field account-field-media">
                        <label for="user_details_photo">Profilfoto</label>
                        <div class="account-media">
                            <input id="user_details_photo" type="file" name="user_details_photo" accept="image/*" data-help="user_details_photo">
                            <img class="account-preview" src="{{ $profileForm['user_details_photo_url'] }}" alt="Aktuelles Profilfoto" width="55" height="55">
                        </div>
                    </div>

                    <div class="account-field">
                        <label for="user_details_photo_delete">Profilfoto zurücksetzen</label>
                        <select id="user_details_photo_delete" name="user_details_photo_delete" data-help="user_details_photo_delete">
                            <option value="0" selected>Nein</option>
                            <option value="1">Ja</option>
                        </select>
                    </div>

                    <div class="account-field account-field-media">
                        <label for="user_details_avatar">Avatarbild</label>
                        <div class="account-media">
                            <input id="user_details_avatar" type="file" name="user_details_avatar" accept="image/*" data-help="user_details_avatar">
                            <img class="account-preview" src="{{ $profileForm['user_details_avatar_url'] }}" alt="Aktueller Avatar" width="55" height="55">
                        </div>
                    </div>

                    <div class="account-field">
                        <label for="user_details_avatar_delete">Avatarbild zurücksetzen</label>
                        <select id="user_details_avatar_delete" name="user_details_avatar_delete" data-help="user_details_avatar_delete">
                            <option value="0" selected>Nein</option>
                            <option value="1">Ja</option>
                        </select>
                    </div>

                    <hr class="account-sep">

                    <div class="account-field">
                        <label for="user_permissions_ffb_mailservice_reminder">Erinnerungen per Mail erhalten</label>
                        <select id="user_permissions_ffb_mailservice_reminder" name="user_permissions_ffb_mailservice_reminder" data-help="user_permissions_ffb_mailservice_reminder">
                            <option value="1" @selected((int) $profileForm['user_permissions_ffb_mailservice_reminder'] === 1)>Ja</option>
                            <option value="0" @selected((int) $profileForm['user_permissions_ffb_mailservice_reminder'] === 0)>Nein</option>
                        </select>
                    </div>

                    <div class="account-field">
                        <label for="user_permissions_ffb_mailservice_info">Infos per Mail erhalten</label>
                        <select id="user_permissions_ffb_mailservice_info" name="user_permissions_ffb_mailservice_info" data-help="user_permissions_ffb_mailservice_info">
                            <option value="1" @selected((int) $profileForm['user_permissions_ffb_mailservice_info'] === 1)>Ja</option>
                            <option value="0" @selected((int) $profileForm['user_permissions_ffb_mailservice_info'] === 0)>Nein</option>
                        </select>
                    </div>

                    <div class="account-field">
                        <label for="user_permissions_ffb_visible_profile">Gesamtes Profil anzeigen</label>
                        <select id="user_permissions_ffb_visible_profile" name="user_permissions_ffb_visible_profile" data-help="user_permissions_ffb_visible_profile">
                            <option value="1" @selected((int) $profileForm['user_permissions_ffb_visible_profile'] === 1)>Ja</option>
                            <option value="0" @selected((int) $profileForm['user_permissions_ffb_visible_profile'] === 0)>Nein</option>
                        </select>
                    </div>

                    <div class="account-actions">
                        <button type="submit" class="btn" name="users_profile_update" value="1">Änderungen abschicken</button>
                    </div>
                </form>
            </div>

            <div class="account-tab-panel" id="tab-account" role="tabpanel" @if ($activeTab !== 'account') hidden @endif>
                <form class="account-form" method="post" action="account" autocomplete="off">
                    @csrf

                    <div class="account-field">
                        <label for="user_nickname">Benutzername</label>
                        <input id="user_nickname" type="text" name="user_nickname" value="{{ $accountForm['user_nickname'] }}" readonly data-help="user_nickname">
                    </div>

                    <div class="account-field">
                        <label for="user_fname">Vorname</label>
                        <input id="user_fname" type="text" name="user_fname" value="{{ $accountForm['user_fname'] }}" maxlength="100" data-help="user_fname">
                    </div>

                    <div class="account-field">
                        <label for="user_lname">Nachname</label>
                        <input id="user_lname" type="text" name="user_lname" value="{{ $accountForm['user_lname'] }}" maxlength="100" data-help="user_lname">
                    </div>

                    <div class="account-field">
                        <span class="account-label" id="birth-label">Geburtsdatum</span>
                        <div class="account-birth" role="group" aria-labelledby="birth-label">
                            <select name="user_birth_day" data-help="user_birthday" aria-label="Tag">
                                <option value=""></option>
                                @for ($i = 1; $i < 32; $i++)
                                    <option value="{{ $i }}" @selected((int) $accountForm['user_birth_day'] === $i)>{{ $i }}</option>
                                @endfor
                            </select>
                            <select name="user_birth_month" data-help="user_birthday" aria-label="Monat">
                                <option value=""></option>
                                @foreach ($months as $idx => $monthName)
                                    <option value="{{ $idx + 1 }}" @selected((int) $accountForm['user_birth_month'] === $idx + 1)>{{ $monthName }}</option>
                                @endforeach
                            </select>
                            <select name="user_birth_year" data-help="user_birthday" aria-label="Jahr">
                                <option value=""></option>
                                @foreach ($birthYears as $year)
                                    <option value="{{ $year }}" @selected((int) $accountForm['user_birth_year'] === (int) $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="account-field">
                        <label for="user_nationality">Nationalität</label>
                        <select id="user_nationality" name="user_nationality" data-help="user_nationality">
                            <option value="">Land…</option>
                            @foreach ($countries as $code => $name)
                                <option value="{{ $code }}" @selected($accountForm['user_nationality'] === $code)>{{ $name }}</option>
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
                        <input id="user_email" type="text" name="user_email" value="{{ $accountForm['user_email'] }}" readonly data-help="user_email">
                    </div>

                    <div class="account-field">
                        <label for="user_email_chg">E-Mail ändern</label>
                        <input id="user_email_chg" type="email" name="user_email_chg" value="{{ $accountForm['user_email_chg'] }}" maxlength="120" data-help="user_email_chg" autocomplete="off">
                    </div>

                    <div class="account-field">
                        <label for="user_email_val_chg">E-Mail Änderung wiederholen</label>
                        <input id="user_email_val_chg" type="email" name="user_email_val_chg" value="{{ $accountForm['user_email_val_chg'] }}" maxlength="120" data-help="user_email_val_chg" autocomplete="off">
                    </div>

                    <div class="account-field account-field-check">
                        <label for="user_tos">
                            <input id="user_tos" type="checkbox" name="user_tos" value="user_tos_yes" data-help="user_tos">
                            Ich habe die <a href="{{ config('ffb.registration_tos_url') }}" target="_blank" rel="noopener">Bedingungen</a> akzeptiert
                        </label>
                    </div>

                    <div class="account-actions">
                        <button type="submit" class="btn" name="users_registration_update" value="1">Änderungen abschicken</button>
                    </div>
                </form>
            </div>
        </section>

        <aside class="panel account-help" aria-labelledby="profile-help-title">
            <h2 id="profile-help-title">Hinweise</h2>
            @if ($activeTab === 'account')
                <p class="hint">Felder mit * sind Pflicht. Klick auf ein Feld, um weitere Hinweise anzuzeigen.</p>
                <div id="account-helptext" class="account-helptext">
                    Alle Pflichtfelder müssen ausgefüllt werden. Klick auf ein Feld um weitere Hinweise anzuzeigen.
                </div>
            @else
                <p class="hint">Alle Felder sind optional. Klick auf ein Feld, um weitere Hinweise anzuzeigen.</p>
                <div id="account-helptext" class="account-helptext">
                    Alle Felder sind optional. Klick auf ein Feld um weitere Hinweise anzuzeigen.
                </div>
            @endif
        </aside>
    </main>

    @include('partials.footer')

    <script src="js/account.js?v=2" defer></script>
</body>
</html>
