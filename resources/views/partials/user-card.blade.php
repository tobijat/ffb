@if (!empty($user))
    @php
        $avatarUrl = (string) ($user['photo_url'] ?? '');
        $avatarPath = (string) (parse_url($avatarUrl, PHP_URL_PATH) ?: $avatarUrl);
        $avatarBackUrl = \App\Support\CustomAvatarSvg::publicBackUrl(basename($avatarPath));
    @endphp
    <div class="user-card">
        <a
            class="user-card-photo user-avatar-shine"
            href="/profile?tab=avatar"
            title="Avatar bearbeiten"
            style="--user-avatar-mask: url('{{ $avatarUrl }}')"
            data-avatar-front="{{ $avatarUrl }}"
            @if ($avatarBackUrl) data-avatar-back="{{ $avatarBackUrl }}" @endif
        >
            <img class="user-photo user-photo-avatar" src="{{ $avatarUrl }}" alt="Avatar {{ $user['user_nickname'] }}" width="48" height="48">
        </a>
        <div class="user-card-text">
            <p class="hello">Hallo <a class="user-card-nickname" href="/profile" title="Profil bearbeiten"><strong>{{ $user['user_nickname'] }}</strong></a></p>
            <p class="muted">Du bist angemeldet.</p>
            @if (!empty($user['is_ffb_admin']))
                <p class="user-card-admin">
                    @if (!empty($adminShell))
                        <a href="/">Soccer Sportsfan</a>
                    @else
                        <a href="/admin">Admin Center</a>
                    @endif
                </p>
            @endif
        </div>
        <a class="user-card-logout" href="/logout" title="Ausloggen">
            <img src="{{ $legacyBase ?? '/' }}images/ffb/navigation/nav_logout.png" alt="" width="32" height="32" loading="lazy">
            <span>Ausloggen</span>
        </a>
    </div>
    @once
        <script src="{{ rtrim($legacyBase ?? '/', '/').'/' }}js/user-card.js?v=4" defer></script>
    @endonce
@endif
