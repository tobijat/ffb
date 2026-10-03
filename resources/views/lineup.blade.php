<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aufstellung — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css?v=23">
    <link rel="stylesheet" href="css/dashboard.css?v=12">
    <link rel="stylesheet" href="css/modal.css?v=15">
    <link rel="stylesheet" href="css/myteam.css?v=30">
    <link rel="stylesheet" href="css/lineup.css?v=22">
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
                @include('partials.dash-nav')
            </nav>
        </div>

        @include('partials.user-card')
    </header>

    <main class="dash-main lineup-layout">
        <section class="panel myteam-pitch" aria-label="Aufstellung">
            <div class="pitch-info">
                <div class="pitch-info-round">
                    <p class="pitch-round" id="round-meta">Lade Spielrunde…</p>
                </div>
                <div class="pitch-info-center">
                    <div class="lineup-actions" id="lineup-actions"></div>
                    <div class="lineup-messages" id="lineup-messages"></div>
                </div>
                <div class="pitch-stats lineup-credits" id="lineup-credits" hidden></div>
            </div>

            <div class="pitch-stage" id="pitch-stage">
                <div
                    id="soccer-field"
                    class="soccer-field"
                    style="--soccer-field-bg:url({{ $legacyBase }}images/ffb/backgrounds/soccer_field_cut.svg)"
                >
                    <div class="field-line field-g"><div id="line-g" class="line-players"></div></div>
                    <div class="field-line field-d"><div id="line-d" class="line-players"></div></div>
                    <div class="field-line field-m"><div id="line-m" class="line-players"><p class="muted">Lade…</p></div></div>
                    <div class="field-line field-s"><div id="line-s" class="line-players"></div></div>
                </div>
                <aside
                    id="soccer-bench"
                    class="soccer-bench"
                    hidden
                    aria-label="Ersatzbank"
                >
                    <img
                        class="soccer-bench-bg"
                        src="{{ $legacyBase }}images/ffb/backgrounds/soccer_field_bench.svg"
                        alt=""
                        aria-hidden="true"
                        decoding="async"
                    >
                    <div id="line-bench" class="bench-players"></div>
                </aside>
            </div>
            <p class="hint" id="pitch-message" hidden></p>
        </section>

        <aside class="myteam-side">
            <div class="panel" id="matchlist-panel">
                <div id="matchlist">
                    <p class="muted">Lade Spiele…</p>
                </div>
            </div>
        </aside>
    </main>


    @include('partials.footer')

    @include('partials.ffb-flags-boot')
    <script>
        window.FFB_LINEUP = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
            gameOver: @json((bool) ($data['game_over'] ?? false)),
        };
        window.FFB_MODAL = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
        };
    </script>
    <script src="js/modal.js?v=12" defer></script>
    <script src="js/player-modal.js?v=11" defer></script>
    <script src="js/match-list.js?v=4" defer></script>
    <script src="js/pitch-bench.js?v=16" defer></script>
    <script src="js/lineup.js?v=29" defer></script>
</body>
</html>
