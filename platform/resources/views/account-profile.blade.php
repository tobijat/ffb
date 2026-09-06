<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/account.css?v=2">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $form = $data['form'];
        $teams = $data['teams'];
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
        <section class="panel account-main" aria-labelledby="profile-title">
            <div class="section-head">
                <h2 id="profile-title">Profildetails bearbeiten</h2>
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

            <form class="account-form" method="post" action="profile" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <div class="account-field">
                    <label for="user_details_city">Wohnort</label>
                    <input id="user_details_city" type="text" name="user_details_city" value="{{ $form['user_details_city'] }}" maxlength="100" data-help="user_details_city">
                </div>

                <div class="account-field">
                    <label for="user_details_zip">Postleitzahl</label>
                    <input id="user_details_zip" type="text" name="user_details_zip" value="{{ $form['user_details_zip'] }}" maxlength="20" data-help="user_details_zip">
                </div>

                <div class="account-field">
                    <label for="user_details_street">Straße und Hausnummer</label>
                    <input id="user_details_street" type="text" name="user_details_street" value="{{ $form['user_details_street'] }}" maxlength="120" data-help="user_details_street">
                </div>

                <div class="account-field">
                    <label for="user_details_phone">Telefonnummer</label>
                    <input id="user_details_phone" type="text" name="user_details_phone" value="{{ $form['user_details_phone'] }}" maxlength="40" data-help="user_details_phone">
                </div>

                <div class="account-field">
                    <label for="user_details_website">Homepage</label>
                    <input id="user_details_website" type="text" name="user_details_website" value="{{ $form['user_details_website'] }}" maxlength="200" data-help="user_details_website">
                </div>

                <div class="account-field">
                    <label for="user_details_ffb_favourite_team">Lieblingsteam</label>
                    <select id="user_details_ffb_favourite_team" name="user_details_ffb_favourite_team" data-help="user_details_ffb_favourite_team">
                        @foreach ($teams as $team)
                            <option value="{{ $team['team_id'] }}" @selected((int) $form['user_details_ffb_favourite_team'] === (int) $team['team_id'])>
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
                        <img class="account-preview" src="{{ $form['user_details_photo_url'] }}" alt="Aktuelles Profilfoto" width="55" height="55">
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
                        <img class="account-preview" src="{{ $form['user_details_avatar_url'] }}" alt="Aktueller Avatar" width="55" height="55">
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
                        <option value="1" @selected((int) $form['user_permissions_ffb_mailservice_reminder'] === 1)>Ja</option>
                        <option value="0" @selected((int) $form['user_permissions_ffb_mailservice_reminder'] === 0)>Nein</option>
                    </select>
                </div>

                <div class="account-field">
                    <label for="user_permissions_ffb_mailservice_info">Infos per Mail erhalten</label>
                    <select id="user_permissions_ffb_mailservice_info" name="user_permissions_ffb_mailservice_info" data-help="user_permissions_ffb_mailservice_info">
                        <option value="1" @selected((int) $form['user_permissions_ffb_mailservice_info'] === 1)>Ja</option>
                        <option value="0" @selected((int) $form['user_permissions_ffb_mailservice_info'] === 0)>Nein</option>
                    </select>
                </div>

                <div class="account-field">
                    <label for="user_permissions_ffb_visible_profile">Gesamtes Profil anzeigen</label>
                    <select id="user_permissions_ffb_visible_profile" name="user_permissions_ffb_visible_profile" data-help="user_permissions_ffb_visible_profile">
                        <option value="1" @selected((int) $form['user_permissions_ffb_visible_profile'] === 1)>Ja</option>
                        <option value="0" @selected((int) $form['user_permissions_ffb_visible_profile'] === 0)>Nein</option>
                    </select>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn" name="users_profile_update" value="1">Änderungen abschicken</button>
                </div>
            </form>
        </section>

        <aside class="panel account-help" aria-labelledby="profile-help-title">
            <h2 id="profile-help-title">Hinweise</h2>
            <p class="hint">Alle Felder sind optional. Klick auf ein Feld, um weitere Hinweise anzuzeigen.</p>
            <div id="account-helptext" class="account-helptext">
                Alle Felder sind optional. Klick auf ein Feld um weitere Hinweise anzuzeigen.
            </div>
        </aside>
    </main>


    @include('partials.footer')

    <script src="js/account.js?v=2" defer></script>
</body>
</html>
