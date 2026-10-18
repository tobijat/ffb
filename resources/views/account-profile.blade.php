<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil — SoccerSportsfan</title>
    @php $assetBase = rtrim(config('ffb.home_path'), '/'); @endphp
    <link rel="stylesheet" href="{{ $assetBase }}/css/start.css?v=32">
    <link rel="stylesheet" href="{{ $assetBase }}/css/dashboard.css?v=18">
    <link rel="stylesheet" href="{{ $assetBase }}/css/account.css?v=12">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $profileForm = $data['form'];
        $teams = $data['teams'];
        $avatarShirts = $data['avatar_shirts'] ?? [];
        $accountForm = $accountData['form'];
        $countries = $accountData['countries'];
        $birthYears = $accountData['birth_years'];
        $months = ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
        $activeTab = match ($tab ?? 'profile') {
            'account' => 'account',
            'avatar' => 'avatar',
            default => 'profile',
        };
        $currentAvatar = (string) ($profileForm['user_details_avatar'] ?? 'avatar_na.png');
        $avatarModeReady = \App\Support\UserAvatar::isShirtPath($currentAvatar)
            || \App\Support\UserAvatar::isDefault($currentAvatar);
        $kitKitchenUrl = (string) config('ffb.kit_kitchen_random_url');
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @include('partials.dash-nav')
            </nav>
        </div>

        @include('partials.user-card')
    </header>

    <main class="dash-main account-layout">
        <section class="panel account-main" aria-labelledby="profile-hub-title">
            <div class="section-head">
                <h2 id="profile-hub-title">Profil</h2>
            </div>

            <div class="account-tabs ffb-tabs" role="tablist" aria-label="Profilbereiche">
                <a
                    class="account-tab ffb-tab {{ $activeTab === 'profile' ? 'is-active' : '' }}"
                    href="/profile"
                    role="tab"
                    aria-selected="{{ $activeTab === 'profile' ? 'true' : 'false' }}"
                >Profil</a>
                <a
                    class="account-tab ffb-tab {{ $activeTab === 'avatar' ? 'is-active' : '' }}"
                    href="/profile?tab=avatar"
                    role="tab"
                    aria-selected="{{ $activeTab === 'avatar' ? 'true' : 'false' }}"
                >Avatar</a>
                <a
                    class="account-tab ffb-tab {{ $activeTab === 'account' ? 'is-active' : '' }}"
                    href="/profile?tab=account"
                    role="tab"
                    aria-selected="{{ $activeTab === 'account' ? 'true' : 'false' }}"
                >Account</a>
            </div>

            @if ($answer)
                <div class="account-flash account-flash-ok" role="status">
                    {!! $answer !!}
                </div>
            @endif

            <div class="account-tab-panel" id="tab-profile" role="tabpanel" @if ($activeTab !== 'profile') hidden @endif>
                <form class="account-form" method="post" action="profile" autocomplete="off">
                    @csrf
                    <input type="hidden" name="profile_section" value="profile">

                    <div class="account-field">
                        <label for="user_fname">Vorname</label>
                        <input id="user_fname" type="text" name="user_fname" value="{{ $profileForm['user_fname'] ?? '' }}" maxlength="100" data-help="user_fname">
                    </div>

                    <div class="account-field">
                        <label for="user_lname">Nachname</label>
                        <input id="user_lname" type="text" name="user_lname" value="{{ $profileForm['user_lname'] ?? '' }}" maxlength="100" data-help="user_lname">
                    </div>

                    <div class="account-field">
                        <span class="account-label" id="birth-label">Geburtsdatum</span>
                        <div class="account-birth" role="group" aria-labelledby="birth-label">
                            <select name="user_birth_day" data-help="user_birthday" aria-label="Tag">
                                <option value=""></option>
                                @for ($i = 1; $i < 32; $i++)
                                    <option value="{{ $i }}" @selected((int) ($profileForm['user_birth_day'] ?? 0) === $i)>{{ $i }}</option>
                                @endfor
                            </select>
                            <select name="user_birth_month" data-help="user_birthday" aria-label="Monat">
                                <option value=""></option>
                                @foreach ($months as $idx => $monthName)
                                    <option value="{{ $idx + 1 }}" @selected((int) ($profileForm['user_birth_month'] ?? 0) === $idx + 1)>{{ $monthName }}</option>
                                @endforeach
                            </select>
                            <select name="user_birth_year" data-help="user_birthday" aria-label="Jahr">
                                <option value=""></option>
                                @foreach ($birthYears as $year)
                                    <option value="{{ $year }}" @selected((int) ($profileForm['user_birth_year'] ?? 0) === (int) $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="account-field">
                        <label for="user_nationality">Nationalität</label>
                        <select id="user_nationality" name="user_nationality" data-help="user_nationality">
                            <option value="">Land…</option>
                            @foreach ($countries as $code => $name)
                                <option value="{{ $code }}" @selected(($profileForm['user_nationality'] ?? '') === $code)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="account-sep">

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
                        <button type="submit" class="btn" name="users_profile_update" value="1">Speichern</button>
                    </div>
                </form>
            </div>

            <div
                class="account-tab-panel"
                id="tab-avatar"
                role="tabpanel"
                @if ($activeTab !== 'avatar') hidden @endif
                data-kit-kitchen-url="{{ $kitKitchenUrl }}"
                data-current-avatar="{{ $currentAvatar }}"
                data-current-avatar-url="{{ $profileForm['user_details_avatar_url'] }}"
            >
                <div class="avatar-mode-switch" role="radiogroup" aria-label="Avatar-Quelle">
                    <label class="avatar-mode-option">
                        <input
                            type="radio"
                            name="avatar_mode"
                            value="ready"
                            @checked($avatarModeReady)
                            data-help="avatar_mode_ready"
                        >
                        <span>Fertige Trikots</span>
                    </label>
                    <label class="avatar-mode-option">
                        <input
                            type="radio"
                            name="avatar_mode"
                            value="custom"
                            @checked(! $avatarModeReady)
                            data-help="avatar_mode_custom"
                        >
                        <span>Eigenes Trikot</span>
                    </label>
                </div>

                <div id="avatar-mode-ready" @if (! $avatarModeReady) hidden @endif>
                    <form class="account-form" id="avatar-ready-form" method="post" action="profile" autocomplete="off">
                        @csrf
                        <input type="hidden" name="profile_section" value="avatar">

                        <div class="account-field account-field-media">
                            <span class="account-label" id="avatar-shirt-label">Avatar</span>
                            <div class="account-media account-avatar-picker" data-help="user_details_avatar_shirt">
                                <img
                                    id="account-avatar-preview"
                                    class="account-preview account-preview-avatar account-preview-custom"
                                    src="{{ $profileForm['user_details_avatar_url'] }}"
                                    alt="Aktueller Avatar"
                                    width="128"
                                    height="128"
                                >
                                <div
                                    class="avatar-shirt-grid"
                                    role="radiogroup"
                                    aria-labelledby="avatar-shirt-label"
                                >
                                    @foreach ($avatarShirts as $shirt)
                                        <label class="avatar-shirt-option">
                                            <input
                                                type="radio"
                                                name="user_details_avatar_shirt"
                                                value="{{ $shirt['path'] }}"
                                                @checked($currentAvatar === $shirt['path'])
                                                data-help="user_details_avatar_shirt"
                                                data-preview-url="{{ $shirt['url'] }}"
                                            >
                                            <img src="{{ $shirt['url'] }}" alt="" width="40" height="40">
                                            <span class="avatar-shirt-name">{{ $shirt['team_name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="account-actions">
                            <button type="submit" class="btn" id="avatar-ready-save" name="users_avatar_update" value="1" disabled>
                                Als Avatar speichern
                            </button>
                        </div>
                    </form>
                </div>

                <div id="avatar-mode-custom" @if ($avatarModeReady) hidden @endif>
                    <form
                        class="account-form"
                        id="avatar-custom-form"
                        method="post"
                        action="profile"
                        autocomplete="off"
                    >
                        @csrf
                        <input type="hidden" name="profile_section" value="avatar_custom">
                        <textarea name="avatar_svg" id="avatar-svg-payload" hidden></textarea>

                        <div class="account-field account-field-media">
                            <span class="account-label">Eigenes Trikot</span>
                            <div class="account-media account-avatar-custom" data-help="avatar_mode_custom">
                                <img
                                    id="avatar-custom-preview"
                                    class="account-preview account-preview-avatar account-preview-custom"
                                    src="{{ $profileForm['user_details_avatar_url'] }}"
                                    alt="Aktueller Avatar"
                                    width="128"
                                    height="128"
                                >
                            </div>
                        </div>

                        <div class="account-actions account-actions-row">
                            <button type="button" class="btn btn-secondary" id="avatar-regenerate" aria-live="polite">
                                Neu generieren
                            </button>
                            <button type="submit" class="btn" id="avatar-custom-save" name="users_avatar_custom_update" value="1" disabled>
                                Als Avatar speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="account-tab-panel" id="tab-account" role="tabpanel" @if ($activeTab !== 'account') hidden @endif>
                <form class="account-form" method="post" action="account" autocomplete="off">
                    @csrf

                    <div class="account-field">
                        <label for="user_nickname">Benutzername</label>
                        <input id="user_nickname" type="text" name="user_nickname" value="{{ $accountForm['user_nickname'] }}" readonly data-help="user_nickname">
                    </div>

                    <div class="account-field">
                        <label for="user_password_chg">Passwort ändern</label>
                        <input id="user_password_chg" type="password" name="user_password_chg" minlength="5" maxlength="32" data-help="user_password_chg" autocomplete="new-password" data-too-short-msg="Passwortänderung: min. Länge ist 5, max. Länge ist 32!" data-too-long-msg="Passwortänderung: min. Länge ist 5, max. Länge ist 32!">
                    </div>

                    <div class="account-field">
                        <label for="user_password_val_chg">Passwortänderung wiederholen</label>
                        <input id="user_password_val_chg" type="password" name="user_password_val_chg" minlength="5" maxlength="32" data-help="user_password_val_chg" autocomplete="new-password" data-too-short-msg="Passwortänderung: min. Länge ist 5, max. Länge ist 32!" data-too-long-msg="Passwortänderung: min. Länge ist 5, max. Länge ist 32!">
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
                            <input id="user_tos" type="checkbox" name="user_tos" value="user_tos_yes" data-help="user_tos" data-required-msg="Du musst die Bedingungen gelesen und akzeptiert haben!">
                            Ich habe die <a href="{{ config('ffb.registration_tos_url') }}" target="_blank" rel="noopener">Bedingungen</a> akzeptiert
                        </label>
                    </div>

                    <div class="account-actions">
                        <button type="submit" class="btn" name="users_registration_update" value="1">Speichern</button>
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
            @elseif ($activeTab === 'avatar')
                <p class="hint">Wähle ein Trikot als Avatar. Klick auf ein Feld, um weitere Hinweise anzuzeigen.</p>
                <div id="account-helptext" class="account-helptext">
                    Wähle ein Trikot aus der Liste. Es wird als dein Avatarbild verwendet.
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

    @if (!empty($errors))
        <script>
            window.FFB_ACCOUNT_ERRORS = @json(array_values($errors));
        </script>
    @endif
    <script src="{{ $assetBase }}/js/account.js?v=8" defer></script>
</body>
</html>
