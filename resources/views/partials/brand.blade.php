@php
    $league = $brandLeague ?? null;
    $fallbackIcon = $brandIcon ?? 'images/ffb/navigation/nav_start.png';
    $iconSrc = $league['symbol_url']
        ?? ((str_starts_with($fallbackIcon, '/') ? '' : ($legacyBase ?? '/')).$fallbackIcon);
@endphp
<a class="brand" href="{{ $brandHref ?? '/' }}" title="{{ $brandTitle ?? 'Start' }}">
    <img src="{{ $iconSrc }}" alt="" width="40" height="40" loading="lazy">
    <span class="brand-text">
        <span class="brand-name">{{ $brandLabel ?? 'SoccerSportsfan' }}</span>
        @if (!empty($league['league_title']))
            <span class="brand-league">{{ $league['league_title'] }}</span>
        @endif
    </span>
</a>
