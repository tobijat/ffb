<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Center') — SoccerSportsfan</title>
    <link rel="stylesheet" href="{{ url('css/start.css') }}?v=39">
    <link rel="stylesheet" href="{{ url('css/dashboard.css') }}?v=50">
    <link rel="stylesheet" href="{{ url('css/admin.css') }}?v=87">
    @stack('head')
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $normalizeAdminPath = static function (string $path): string {
            $parsed = parse_url($path, PHP_URL_PATH);
            $path = '/'.ltrim(is_string($parsed) && $parsed !== '' ? $parsed : $path, '/');

            return $path === '' ? '/' : $path;
        };
        $currentAdminPath = $normalizeAdminPath('/'.ltrim(request()->path(), '/'));
        $currentAdminPathTrimmed = rtrim($currentAdminPath, '/');
        $dashboardPath = rtrim($normalizeAdminPath(route('admin.leagueDashboard', absolute: false)), '/');
        $adminCenterActive = $currentAdminPathTrimmed === '/admin';
        $dashboardActive = $currentAdminPathTrimmed === $dashboardPath
            || str_starts_with($currentAdminPathTrimmed.'/', $dashboardPath.'/');
    @endphp

    <header class="dash-top admin-top">
        <div class="admin-top-main">
            <div class="admin-brand-wrap">
                @include('partials.brand', [
                    'brandHref' => '/admin',
                    'brandTitle' => 'Admin Center',
                    'brandLabel' => 'AdminCenter',
                    'brandIcon' => 'images/admin/navigation/nav_admin_start.png',
                    'brandLeague' => $data['selected_league'] ?? null,
                    'brandClass' => $adminCenterActive ? 'is-active' : '',
                ])
                @include('partials.brand', [
                    'brandHref' => route('admin.leagueDashboard'),
                    'brandTitle' => 'Dashboard',
                    'brandLabel' => 'Dashboard',
                    'brandIcon' => 'images/admin/navigation/nav_admin_start.png',
                    'brandPreferIcon' => true,
                    'brandClass' => 'admin-brand-dashboard'.($dashboardActive ? ' is-active' : ''),
                ])
            </div>

            <nav class="dash-nav admin-nav" aria-label="Admin-Navigation">
                @foreach ($nav as $item)
                    @php
                        $itemPath = rtrim($normalizeAdminPath($item['link']), '/');
                        $current = rtrim($currentAdminPath, '/');
                        $isActive = $current === $itemPath
                            || str_starts_with($current.'/', $itemPath.'/');
                        $imageDir = $item['image_dir'] ?? 'images/ffb/navigation/';
                    @endphp
                    <a class="nav-big{{ $isActive ? ' is-active' : '' }}" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}{{ $imageDir }}{{ $item['symbol'] }}" alt="" width="28" height="28" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="admin-top-spacer" aria-hidden="true"></div>

        @include('partials.user-card', ['adminShell' => true])
    </header>

    <main class="dash-main admin-layout">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.ffb-flags-boot')
    @stack('scripts')
</body>
</html>
