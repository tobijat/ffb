@foreach ($nav as $item)
    @php
        $disabled = ! empty($item['disabled']);
        $title = $disabled
            ? ($item['disabled_title'] ?? $item['name'])
            : $item['name'];
    @endphp
    @if ($disabled)
        <span class="nav-big is-disabled" title="{{ $title }}" aria-disabled="true">
            <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
            <span>{{ $item['name'] }}</span>
        </span>
    @else
        <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
            <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
            <span>{{ $item['name'] }}</span>
        </a>
    @endif
@endforeach
