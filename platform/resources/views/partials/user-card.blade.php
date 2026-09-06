@if (!empty($user))
    <div class="user-card">
        <a class="user-card-photo" href="/platform/profile" title="Profil bearbeiten">
            <img class="user-photo" src="{{ $user['photo_url'] }}" alt="Foto {{ $user['user_nickname'] }}" width="48" height="48">
        </a>
        <div class="user-card-text">
            <p class="hello">Hallo <strong>{{ $user['user_nickname'] }}</strong></p>
            <p class="muted">Du bist angemeldet.</p>
            @if (!empty($user['is_ffb_admin']))
                <p class="user-card-admin">
                    @if (!empty($adminShell))
                        <a href="/platform/">Soccer Sportsfan</a>
                    @else
                        <a href="/platform/admin">Admin Center</a>
                    @endif
                </p>
            @endif
            @if (!empty($showProfileNag) && !empty($user['update_profile_nag']))
                <p class="nag">Dein Profil ist noch leer. <a href="/platform/profile">Profil aktualisieren</a></p>
            @endif
        </div>
        <a class="user-card-logout" href="/platform/logout" title="Ausloggen">
            <img src="{{ $legacyBase ?? '/' }}images/ffb/navigation/nav_logout.png" alt="" width="32" height="32" loading="lazy">
            <span>Ausloggen</span>
        </a>
    </div>
@endif
