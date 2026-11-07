@php
    $faviconPath = (string) ($favicon ?? 'images/ffb/favicon.png');
    $faviconHref = str_starts_with($faviconPath, '/') || preg_match('#^https?://#i', $faviconPath)
        ? $faviconPath
        : url($faviconPath);
@endphp
<link rel="icon" type="image/png" href="{{ $faviconHref }}">
