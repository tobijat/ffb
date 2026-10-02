<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top / Flop Teams — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css?v=23">
    <link rel="stylesheet" href="css/dashboard.css?v=12">
    <link rel="stylesheet" href="css/modal.css?v=15">
    <link rel="stylesheet" href="css/myteam.css?v=27">
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

    <main class="dash-main myteam-layout">
        <section class="panel myteam-pitch" aria-label="Top und Flop Teams">
            <div class="pitch-info">
                <div class="pitch-info-round">
                    <p class="pitch-round" id="round-meta">Lade Spielrunden…</p>
                </div>
                <div class="pitch-info-center">
                    <p class="pitch-user" id="selected-team"></p>
                </div>
                <div class="pitch-stats-group" id="team-side-stats" hidden>
                    <div class="pitch-stats" id="team-score-tile">
                        <div class="pitch-stats-row">
                            <img
                                src="{{ $legacyBase }}images/ffb/symbols/symbol_score.png"
                                alt=""
                                width="28"
                                height="28"
                            >
                            <span id="team-score">–</span>
                        </div>
                    </div>
                    <div class="pitch-stats" id="team-credits">
                        <div class="pitch-stats-row">
                            <img
                                src="{{ $legacyBase }}images/ffb/symbols/symbol_credits.png"
                                alt=""
                                width="28"
                                height="28"
                            >
                            <span id="team-price">–</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pitch-stage" id="pitch-stage">
                <div
                    id="soccer-field"
                    class="soccer-field"
                    style="background-image:url({{ $legacyBase }}images/ffb/backgrounds/soccer_field_cut.svg)"
                >
                    <div class="field-line field-g"><div id="line-g" class="line-players"></div></div>
                    <div class="field-line field-d"><div id="line-d" class="line-players"></div></div>
                    <div class="field-line field-m"><div id="line-m" class="line-players"><p class="muted">Lade Team…</p></div></div>
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
                <div class="myteam-tabs ffb-tabs" id="side-tabs" hidden>
                    <button type="button" class="myteam-tab ffb-tab" data-side-tab="matches">Spiele anzeigen</button>
                    <button type="button" class="myteam-tab ffb-tab is-active" data-side-tab="stats">Statistiken anzeigen</button>
                </div>
                <div id="matchlist">
                    <p class="muted">Lade Statistiken…</p>
                </div>
            </div>
        </aside>
    </main>


    @include('partials.footer')

    @include('partials.ffb-flags-boot')
    <script>
        window.FFB_BESTTEAM = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
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
    <script src="js/pitch-bench.js?v=12" defer></script>
    <script src="js/bestteam.js?v=11" defer></script>
</body>
</html>
