@php
    $assetBase = rtrim(config('ffb.home_path'), '/');
    $isReset = ! empty($passwordReset);
@endphp

@if ($isReset)
    <form class="user-card user-card-login" method="post" action="{{ $passwordReset['formAction'] }}" autocomplete="off">
        @csrf
        <div class="user-card-text">
            <p class="hello">Passwort zurücksetzen</p>
            <p class="muted">Account: <strong>{{ $passwordReset['nickname'] }}</strong></p>

            @if (! empty($passwordReset['errors']))
                <div class="feedback feedback-error" role="alert">
                    <strong>Es sind Fehler aufgetreten:</strong><br>
                    {!! implode('<br>', array_map('e', $passwordReset['errors'])) !!}
                </div>
            @endif

            <label>
                <span>Neues Passwort</span>
                <input type="password" name="user_password" id="user_password" maxlength="32" required autocomplete="new-password" autofocus>
            </label>
            <label>
                <span>Passwort wiederholen</span>
                <input type="password" name="user_password_val" id="user_password_val" maxlength="32" required autocomplete="new-password">
            </label>

            <p class="login-links">
                <a class="linkish" href="{{ route('start') }}">Zurück zum Login</a>
            </p>
        </div>
        <button type="submit" class="user-card-logout" title="Passwort speichern">
            <img src="{{ $assetBase }}/images/ffb/navigation/nav_login.png" alt="" width="32" height="32" loading="lazy">
            <span>Speichern</span>
        </button>
    </form>
@else
    <form id="login" class="user-card user-card-login" method="post" action="login" novalidate data-mode="login" autocomplete="on">
        @csrf
        <div class="user-card-text">
            <div id="login-feedback" class="feedback" hidden></div>
            @if (! empty($accountMessage))
                @php
                    $accountFeedbackKind = str_contains((string) $accountMessage, 'Fehler') ? 'error' : 'ok';
                @endphp
                <div class="feedback feedback-{{ $accountFeedbackKind }}" role="status">{!! $accountMessage !!}</div>
            @endif

            <label id="field-nickname">
                <span class="visually-hidden" id="label-nickname">Nickname</span>
                <input type="text" name="user_nickname" id="user_nickname" placeholder="Nickname" autocomplete="username" required autofocus>
            </label>
            <label id="field-password">
                <span class="visually-hidden">Passwort</span>
                <input type="password" name="user_password" id="user_password" placeholder="Passwort" autocomplete="current-password" required>
            </label>
            <input type="hidden" name="destination" value="{{ $destination ?? '' }}">

            <p class="login-links">
                <a class="linkish" id="register-link" href="{{ $assetBase }}/registration">Registrieren</a>
                <button type="button" id="forgot-password" class="linkish">Passwort vergessen?</button>
                <button type="button" id="back-to-login" class="linkish" hidden>Zurück zum Login</button>
            </p>
        </div>
        <button type="submit" class="user-card-logout user-card-login-submit" id="login-submit" title="Anmelden">
            <img src="{{ $assetBase }}/images/ffb/navigation/nav_login.png" alt="" width="40" height="40" loading="lazy">
            <span id="login-submit-label">Anmelden</span>
        </button>
    </form>
@endif
