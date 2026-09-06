<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aufstellung — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/modal.css?v=5">
    <link rel="stylesheet" href="css/myteam.css?v=6">
    <link rel="stylesheet" href="css/lineup.css?v=2">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
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
            <a href="/platform/profile" title="Profil bearbeiten">
                <img class="user-photo" src="{{ $user['photo_url'] }}" alt="Foto {{ $user['user_nickname'] }}" width="48" height="48">
            </a>
            <div>
                <p class="hello">Hallo <strong>{{ $user['user_nickname'] }}</strong></p>
                <p class="muted">Du bist angemeldet.</p>
            </div>
        </div>
    </header>

    <main class="dash-main lineup-layout">
        <section class="panel myteam-pitch" aria-label="Aufstellung">
            <div class="lineup-info">
                <div class="lineup-info-main">
                    <p class="lineup-round" id="round-meta">Lade Spielrunde…</p>
                    <div class="lineup-actions" id="lineup-actions"></div>
                    <div class="lineup-messages" id="lineup-messages"></div>
                </div>
                <div class="lineup-credits" id="lineup-credits" hidden></div>
            </div>

            <div
                id="soccer-field"
                class="soccer-field"
                style="background-image:url({{ $legacyBase }}images/ffb/backgrounds/soccer_field_round.png)"
            >
                <div class="field-line field-g"><div id="line-g" class="line-players"></div></div>
                <div class="field-line field-d"><div id="line-d" class="line-players"></div></div>
                <div class="field-line field-m"><div id="line-m" class="line-players"><p class="muted">Lade…</p></div></div>
                <div class="field-line field-s"><div id="line-s" class="line-players"></div></div>
            </div>
            <p class="hint" id="pitch-message" hidden></p>
        </section>

        <aside class="myteam-side">
            <div class="panel" id="matchlist-panel">
                <div id="matchlist">
                    <p class="muted">Lade Spiele…</p>
                </div>
            </div>

            <div class="panel" id="picker-panel">
                <label class="round-label" for="team_selection">Mannschaft</label>
                <select id="team_selection" class="ffb-select" disabled>
                    <option>Lade Teams…</option>
                </select>
                <p class="lineup-selected-team" id="selected-team"></p>
                <div id="playerlist" class="playerlist">
                    <p class="muted">Mannschaft wählen…</p>
                </div>
            </div>
        </aside>
    </main>


    @include('partials.footer')

    <script>
        window.FFB_LINEUP = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            selectedGameId: @json($data['selected_game_id'] ?? 0),
            gameOver: @json((bool) ($data['game_over'] ?? false)),
        };
        window.FFB_MODAL = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            selectedGameId: @json($data['selected_game_id'] ?? 0),
        };
    </script>
    <script src="js/modal.js?v=5" defer></script>
    <script src="js/player-modal.js?v=4" defer></script>
    <script src="js/lineup.js?v=2" defer></script>
</body>
</html>
