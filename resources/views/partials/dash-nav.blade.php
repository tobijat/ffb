@php
    $normalizeNavPath = static function (string $path): string {
        $parsed = parse_url($path, PHP_URL_PATH);
        $path = '/'.ltrim(is_string($parsed) && $parsed !== '' ? $parsed : $path, '/');

        return $path === '' ? '/' : $path;
    };
    $currentNavPath = $normalizeNavPath('/'.ltrim(request()->path(), '/'));
@endphp
@foreach ($nav as $item)
    @php
        $disabled = ! empty($item['disabled']);
        $title = $disabled
            ? ($item['disabled_title'] ?? $item['name'])
            : $item['name'];
        $itemPath = $normalizeNavPath((string) ($item['link'] ?? ''));
        $isActive = ! $disabled && (
            $currentNavPath === $itemPath
            || str_starts_with($currentNavPath.'/', $itemPath.'/')
        );
    @endphp
    @if ($disabled)
        <span class="nav-big is-disabled" title="{{ $title }}" aria-disabled="true">
            <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
            <span>{{ $item['name'] }}</span>
        </span>
    @else
        <a
            class="nav-big{{ $isActive ? ' is-active' : '' }}"
            href="{{ $item['link'] }}"
            title="{{ $item['name'] }}"
            @if ($isActive) aria-current="page" @endif
        >
            <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
            <span>{{ $item['name'] }}</span>
        </a>
    @endif
@endforeach
