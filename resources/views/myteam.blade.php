<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mannschaft — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css?v=45">
    <link rel="stylesheet" href="css/dashboard.css?v=49">
    <link rel="stylesheet" href="css/modal.css?v=48">
    <link rel="stylesheet" href="css/myteam.css?v=69">
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
        <section class="panel myteam-pitch" aria-label="Mannschaft">
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
                    <div class="pitch-stats-group" id="team-side-stats" hidden>
                        <div class="pitch-stats" id="team-score-tile">
                            <div class="pitch-stats-row">
                                <img
                                    src="{{ $legacyBase }}images/ffb/symbols/symbol_score.svg"
                                    alt=""
                                    width="28"
                                    height="28"
                                >
                                <span id="team-score">–</span>
                            </div>
                        </div>
                        <div class="pitch-stats" id="team-credits" hidden>
                            <div class="pitch-stats-row">
                                <img
                                    src="{{ $legacyBase }}images/ffb/symbols/symbol_credits.svg"
                                    alt=""
                                    width="28"
                                    height="28"
                                >
                                <span id="team-price">–</span>
                            </div>
                        </div>
                    </div>
                    <div class="pitch-info-center">
                        <label class="pitch-round-picker">
                            <span class="pitch-round-picker-face" aria-hidden="true">
                                <span class="pitch-user" id="selected-user">Lade Mitspieler…</span>
                            </span>
                            <select
                                id="user_selection"
                                class="pitch-round-select"
                                aria-label="Mitspieler"
                                disabled
                            >
                                <option>Lade Mitspieler…</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pitch-stage" id="pitch-stage">
                <div
                    id="soccer-field"
                    class="soccer-field"
                    style="--soccer-field-bg:url({{ $legacyBase }}images/ffb/backgrounds/soccer_field_cut.svg)"
                >
                    <div class="field-line field-g"><div id="line-g" class="line-players"></div></div>
                    <div class="field-line field-d"><div id="line-d" class="line-players"></div></div>
                    <div class="field-line field-m"><div id="line-m" class="line-players"><p class="muted">Lade Mannschaft…</p></div></div>
                    <div class="field-line field-s"><div id="line-s" class="line-players"></div></div>
                </div>
                <aside
                    id="soccer-bench"
                    class="soccer-bench"
                    hidden
                    aria-label="Ersatzbank"
                >
                    <div class="soccer-bench-surface" aria-hidden="true">
                        <img
                            class="soccer-bench-bg"
                            src="{{ $legacyBase }}images/ffb/backgrounds/soccer_field_bench.svg"
                            alt=""
                            decoding="async"
                        >
                    </div>
                    <div id="line-bench" class="bench-players"></div>
                </aside>
            </div>
            <p class="hint" id="pitch-message" hidden></p>
        </section>

        <aside class="myteam-side">
            <div class="panel" id="matchlist-panel">
                <div class="myteam-tabs ffb-tabs" id="side-tabs" hidden>
                    <button type="button" class="myteam-tab ffb-tab is-active" data-side-tab="matches">Spiele anzeigen</button>
                    <button type="button" class="myteam-tab ffb-tab" data-side-tab="stats">Statistiken anzeigen</button>
                </div>
                <div id="matchlist">
                    <p class="muted">Lade Spiele…</p>
                </div>
            </div>
        </aside>
    </main>


    @include('partials.footer')

    @include('partials.ffb-flags-boot')
    <script>
        window.FFB_MYTEAM = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            userId: @json($user['user_id']),
            isAdmin: @json((bool) $user['is_admin']),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
            selectedLeagueAssetKey: @json($data['selected_league_asset_key'] ?? ''),
        };
        window.FFB_MODAL = {
            apiBase: 'api',
            legacyBase: @json($legacyBase),
            selectedLeagueId: @json($data['selected_league_id'] ?? 0),
            selectedLeagueAssetKey: @json($data['selected_league_asset_key'] ?? ''),
        };
    </script>
    <script src="js/modal.js?v=32" defer></script>
    <script src="js/player-modal.js?v=35" defer></script>
    <script src="js/match-list.js?v=4" defer></script>
    <script src="js/pitch-bench.js?v=34" defer></script>
    <script src="js/myteam.js?v=29" defer></script>
</body>
</html>
