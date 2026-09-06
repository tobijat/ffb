<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Center — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css?v=4">
    <link rel="stylesheet" href="css/admin.css?v=1">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
            <nav class="dash-nav" aria-label="Admin-Navigation">
                @foreach ($nav as $item)
                    <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        @include('partials.user-card', ['adminShell' => true])
    </header>

    <main class="dash-main admin-layout">
        <section class="panel admin-main" aria-labelledby="admin-title">
            <div class="section-head">
                <h2 id="admin-title">Admin Center</h2>
            </div>
            <p class="hint">Hier entstehen die neuen Verwaltungsfunktionen. Bisher ist dieses Panel noch leer.</p>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
