@php
    $headerUser = $user ?? ($data['user'] ?? null);
    $headerNav = $nav ?? ($data['navigation'] ?? []);
    $guestTagline = 'Fantasy Football mit echten Ligen — aufstellen, punkten, gewinnen.';
    // Guests (incl. password-reset) always get the marketing tagline unless overridden.
    if (empty($headerUser) && empty($brandSubtitle)) {
        $brandSubtitle = $guestTagline;
    }
    $brandInclude = [];
    if (! empty($brandSubtitle)) {
        $brandInclude['brandSubtitle'] = $brandSubtitle;
    }
@endphp
<header class="dash-top{{ empty($headerUser) ? ' dash-top-guest' : '' }}">
    <div class="dash-top-main">
        @include('partials.brand', $brandInclude)
        @if (! empty($headerUser) && ! empty($headerNav))
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @include('partials.dash-nav', ['nav' => $headerNav])
            </nav>
        @endif
    </div>

    @if (! empty($headerUser))
        @include('partials.user-card', [
            'user' => $headerUser,
            'showProfileNag' => $showProfileNag ?? false,
        ])
    @else
        @include('partials.login-card')
    @endif
</header>
