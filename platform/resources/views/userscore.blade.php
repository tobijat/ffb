<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rangliste — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/userscore.css?v=7">
    <link rel="stylesheet" href="css/modal.css?v=4">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            <a class="brand" href="/platform/">SoccerSportsfan</a>
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @foreach ($nav as $item)
                    <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="user-card">
            <a href="{{ $legacyBase }}users/account/accountDetails.html" title="Profil bearbeiten">
                <img class="user-photo" src="{{ $user['photo_url'] }}" alt="Foto {{ $user['user_nickname'] }}" width="48" height="48">
            </a>
            <div>
                <p class="hello">Hallo <strong>{{ $user['user_nickname'] }}</strong></p>
                <p class="muted">Du bist angemeldet.</p>
            </div>
        </div>
    </header>

    <main class="dash-main userscore-layout">
        <section class="panel userscore-main" aria-labelledby="userscore-title">
            <div class="section-head">
                <h2 id="userscore-title">Rangliste</h2>
                <p class="hint" id="round-meta">Gesamtrangliste</p>
            </div>
            <div id="userscore-table" class="userscore-table-wrap">
                <p class="muted">Lade Rangliste…</p>
            </div>
        </section>

        <aside class="userscore-side">
            <div class="panel">
                <p class="hint tip">
                    <strong>Tipp:</strong> Klick in der Rangliste auf einen Nickname, um das Profil zu öffnen.
                </p>
                <label class="round-label" for="matchround_selection">Spielrunde</label>
                <select id="matchround_selection" class="ffb-select" disabled>
                    <option>Lade Spielrunden…</option>
                </select>
            </div>

            <div class="panel" id="matchlist-panel">
                <h2>Spiele</h2>
                <div id="matchlist">
                    <p class="muted">Gesamtrangliste — keine Einzelspiele.</p>
                </div>
            </div>
        </aside>
    </main>

    <footer class="foot">
        <span>SoccerSportsfan</span>
        <a href="logout">Ausloggen</a>
    </footer>

    <script>
        window.FFB_USERSCORE = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            pageSize: 75,
        };
        window.FFB_MODAL = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
        };
    </script>
    <script src="js/modal.js?v=4" defer></script>
    <script src="js/player-modal.js?v=2" defer></script>
    <script src="js/userscore.js?v=8" defer></script>
</body>
</html>
