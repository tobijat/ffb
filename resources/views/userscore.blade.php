<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rangliste — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css?v=45">
    <link rel="stylesheet" href="css/dashboard.css?v=50">
    <link rel="stylesheet" href="css/myteam.css?v=69">
    <link rel="stylesheet" href="css/userscore.css?v=18">
    <link rel="stylesheet" href="css/modal.css?v=48">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
    @endphp

    @include('partials.dash-top')

    <main class="dash-main userscore-layout">
        <section class="panel userscore-main" aria-label="Rangliste">
            <div class="pitch-info-cq">
                <div class="pitch-info">
                    <div class="pitch-info-round">
                        <label class="pitch-round-picker">
                            <span class="pitch-round-picker-face" aria-hidden="true">
                                <span class="pitch-round-title" id="round-title">Lade Spielrunden…</span>
                                <span class="pitch-round-dates" id="round-dates" hidden></span>
                            </span>
                            <select
                                id="matchround_selection"
                                class="pitch-round-select"
                                aria-label="Spielrunde"
                                disabled
                            >
                                <option>Lade Spielrunden…</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            <div id="userscore-table" class="userscore-table-wrap">
                <p class="muted">Lade Rangliste…</p>
            </div>
        </section>

        <aside class="userscore-side is-empty">
            <div class="panel" id="matchlist-panel">
                <div id="matchlist"></div>
            </div>
        </aside>
    </main>


    @include('partials.footer')

    @include('partials.ffb-flags-boot')
    <script>
        window.FFB_USERSCORE = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
            pageSize: 75,
        };
        window.FFB_MODAL = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
        };
    </script>
    <script src="js/modal.js?v=32" defer></script>
    <script src="js/player-modal.js?v=35" defer></script>
    <script src="js/match-list.js?v=4" defer></script>
    <script src="js/userscore.js?v=16" defer></script>
</body>
</html>
