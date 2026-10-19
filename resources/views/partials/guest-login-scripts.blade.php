@php
    $guestScriptsUser = $user ?? ($data['user'] ?? null);
    $assetBase = rtrim(config('ffb.home_path'), '/');
@endphp
@if (empty($guestScriptsUser) && empty($passwordReset))
    <script>
        window.FFB_START = {
            loginUrl: new URL('login', window.location.href).pathname,
            passwordUrl: new URL('registration/password', window.location.href).pathname,
        };
    </script>
    <script src="{{ $assetBase }}/js/start.js?v=15" defer></script>
@endif
