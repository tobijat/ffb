@php
    $league = $brandLeague ?? null;
    $fallbackIcon = $brandIcon ?? 'images/ffb/navigation/nav_start.png';
    $iconSrc = (! empty($brandPreferIcon) ? null : ($league['symbol_url'] ?? null))
        ?? ((str_starts_with($fallbackIcon, '/') ? '' : ($legacyBase ?? '/')).$fallbackIcon);
    $hasSubtitle = ! empty($brandSubtitle) && empty($league['league_title']);
    $brandExtraClass = trim((string) ($brandClass ?? ''));
@endphp
<a class="brand{{ $hasSubtitle ? ' brand-stacked' : '' }}{{ $brandExtraClass !== '' ? ' '.$brandExtraClass : '' }}" href="{{ $brandHref ?? '/' }}" title="{{ $brandTitle ?? 'Start' }}">
    @if ($hasSubtitle)
        <span class="brand-row">
            <img src="{{ $iconSrc }}" alt="" width="40" height="40" loading="lazy">
            <span class="brand-name">{{ $brandLabel ?? 'SoccerSportsfan' }}</span>
        </span>
        <span class="brand-league brand-tagline">{{ $brandSubtitle }}</span>
    @else
        <span class="brand-icon">
            <img src="{{ $iconSrc }}" alt="" width="40" height="40" loading="lazy">
        </span>
        <span class="brand-text">
            <span class="brand-name">{{ $brandLabel ?? 'SoccerSportsfan' }}</span>
            <span class="brand-league">{{ $league['league_title'] ?? '' }}</span>
        </span>
    @endif
</a>
