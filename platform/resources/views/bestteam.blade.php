<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top / Flop Teams — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/modal.css?v=5">
    <link rel="stylesheet" href="css/myteam.css?v=6">
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

    <main class="dash-main myteam-layout">
        <section class="panel myteam-pitch" aria-label="Top und Flop Teams">
            <div class="myteam-info">
                <p class="myteam-round" id="round-meta">Lade Spielrunden…</p>
                <div class="myteam-stats">
                    <div class="myteam-stat myteam-stat-score">
                        <img
                            src="{{ $legacyBase }}images/ffb/symbols/symbol_score.png"
                            alt=""
                            width="28"
                            height="28"
                        >
                        <div>
                            <span class="label">Punkte</span>
                            <strong id="team-score">–</strong>
                        </div>
                    </div>
                    <p class="myteam-user" id="selected-team"></p>
                    <div class="myteam-stat myteam-stat-credits">
                        <img
                            src="{{ $legacyBase }}images/ffb/symbols/symbol_credits.png"
                            alt=""
                            width="28"
                            height="28"
                        >
                        <div>
                            <span class="label">Credits</span>
                            <strong id="team-price">–</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div
                id="soccer-field"
                class="soccer-field"
                style="background-image:url({{ $legacyBase }}images/ffb/backgrounds/soccer_field_round.png)"
            >
                <div class="field-line field-g"><div id="line-g" class="line-players"></div></div>
                <div class="field-line field-d"><div id="line-d" class="line-players"></div></div>
                <div class="field-line field-m"><div id="line-m" class="line-players"><p class="muted">Lade Team…</p></div></div>
                <div class="field-line field-s"><div id="line-s" class="line-players"></div></div>
            </div>
            <p class="hint" id="pitch-message" hidden></p>
        </section>

        <aside class="myteam-side">
            <div class="panel">
                <label class="round-label" for="matchround_selection">Spielrunde</label>
                <select id="matchround_selection" class="ffb-select" disabled>
                    <option>Lade Spielrunden…</option>
                </select>

                <label class="round-label" for="team_selection">Team</label>
                <select id="team_selection" class="ffb-select" disabled>
                    <option value="top">Top-Team der Runde</option>
                    <option value="flop">Flop-Team der Runde</option>
                </select>
            </div>

            <div class="panel" id="matchlist-panel">
                <div class="myteam-tabs" id="side-tabs" hidden>
                    <button type="button" class="myteam-tab" data-side-tab="matches">Spiele anzeigen</button>
                    <button type="button" class="myteam-tab is-active" data-side-tab="stats">Statistiken anzeigen</button>
                </div>
                <h2 id="side-panel-title">Statistiken</h2>
                <div id="matchlist">
                    <p class="muted">Lade Statistiken…</p>
                </div>
            </div>
        </aside>
    </main>


    @include('partials.footer')

    <script>
        window.FFB_BESTTEAM = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            selectedGameId: @json($data['selected_game_id'] ?? 0),
        };
        window.FFB_MODAL = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            selectedGameId: @json($data['selected_game_id'] ?? 0),
        };
    </script>
    <script src="js/modal.js?v=5" defer></script>
    <script src="js/player-modal.js?v=4" defer></script>
    <script src="js/bestteam.js?v=3" defer></script>
</body>
</html>
