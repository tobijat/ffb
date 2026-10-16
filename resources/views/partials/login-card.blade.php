@php
    $assetBase = rtrim(config('ffb.home_path'), '/');
    $isReset = ! empty($passwordReset);
@endphp

@if ($isReset)
    <form class="user-card user-card-login" method="post" action="{{ $passwordReset['formAction'] }}" autocomplete="off">
        @csrf
        <div class="user-card-text">
            <p class="hello">Passwort zurücksetzen: <strong>{{ $passwordReset['nickname'] }}</strong></p>

            @if (! empty($passwordReset['errors']))
                <div class="feedback feedback-error" role="alert">
                    <strong>Es sind Fehler aufgetreten:</strong><br>
                    {!! implode('<br>', array_map('e', $passwordReset['errors'])) !!}
                </div>
            @endif

            <label>
                <span class="visually-hidden">Neues Passwort</span>
                <input type="password" name="user_password" id="user_password" maxlength="32" placeholder="Neues Passwort" required autocomplete="new-password" autofocus data-required-msg="Bitte neues Passwort eingeben.">
            </label>
            <label>
                <span class="visually-hidden">Passwort wiederholen</span>
                <input type="password" name="user_password_val" id="user_password_val" maxlength="32" placeholder="Passwort wiederholen" required autocomplete="new-password" data-required-msg="Bitte Passwort wiederholen.">
            </label>

            <p class="login-links">
                <a class="linkish" href="{{ route('start') }}">Zurück zum Login</a>
            </p>
        </div>
        <button type="submit" class="user-card-logout user-card-login-submit" title="Passwort speichern">
            <img src="{{ $assetBase }}/images/ffb/navigation/nav_login.png" alt="" width="40" height="40" loading="lazy">
            <span>Speichern</span>
        </button>
    </form>
@else
    <form id="login" class="user-card user-card-login" method="post" action="login" data-mode="login" autocomplete="on">
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
                <span class="visually-hidden" id="label-nickname">Benutzername</span>
                <input type="text" name="user_nickname" id="user_nickname" placeholder="Benutzername" autocomplete="username" required data-required-msg="Bitte Benutzername eingeben.">
            </label>
            <label id="field-password">
                <span class="visually-hidden">Passwort</span>
                <input type="password" name="user_password" id="user_password" placeholder="Passwort" autocomplete="current-password" required data-required-msg="Bitte Passwort eingeben.">
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

<script>
(function () {
    document.querySelectorAll('.user-card-login [data-required-msg]').forEach(function (input) {
        input.addEventListener('invalid', function () {
            // Only translate empty-field errors. Do not clear customError here —
            // server-side login errors set custom validity and call reportValidity().
            if (input.validity.valueMissing) {
                input.setCustomValidity(input.getAttribute('data-required-msg') || 'Bitte fülle dieses Feld aus.');
            }
            if (input.validity.valueMissing || input.validity.customError) {
                input.classList.add('is-error');
            }
        });
        input.addEventListener('input', function () {
            input.setCustomValidity('');
            input.classList.remove('is-error');
        });
    });
})();
</script>
