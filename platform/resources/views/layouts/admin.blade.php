<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Center') — SoccerSportsfan</title>
    <link rel="stylesheet" href="{{ url('css/start.css') }}">
    <link rel="stylesheet" href="{{ url('css/dashboard.css') }}?v=4">
    <link rel="stylesheet" href="{{ url('css/admin.css') }}?v=2">
    @stack('head')
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $normalizeAdminPath = static function (string $path): string {
            $parsed = parse_url($path, PHP_URL_PATH);
            $path = '/'.ltrim(is_string($parsed) && $parsed !== '' ? $parsed : $path, '/');
            $path = (string) preg_replace('#^/platform/public#', '', $path);
            $path = (string) preg_replace('#^/platform#', '', $path);

            return $path === '' ? '/' : $path;
        };
        $currentAdminPath = $normalizeAdminPath('/'.ltrim(request()->path(), '/'));
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
            <nav class="dash-nav" aria-label="Admin-Navigation">
                @foreach ($nav as $item)
                    @php
                        $itemPath = rtrim($normalizeAdminPath($item['link']), '/');
                        $current = rtrim($currentAdminPath, '/');
                        $isActive = $current === $itemPath
                            || str_starts_with($current.'/', $itemPath.'/');
                        $imageDir = $item['image_dir'] ?? 'images/ffb/navigation/';
                    @endphp
                    <a class="nav-big{{ $isActive ? ' is-active' : '' }}" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}{{ $imageDir }}{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        @include('partials.user-card', ['adminShell' => true])
    </header>

    <main class="dash-main admin-layout">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
