<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ !empty($passwordReset) ? 'Passwort zurücksetzen — SoccerSportsfan' : 'SoccerSportsfan — Fantasy Football' }}</title>
    <meta name="description" content="Fantasy Football Manager bei SoccerSportsfan. Kostenlos mitspielen.">
    @php $assetBase = rtrim(config('ffb.home_path'), '/'); @endphp
    <link rel="stylesheet" href="{{ $assetBase }}/css/start.css?v=45">
    <link rel="stylesheet" href="{{ $assetBase }}/css/dashboard.css?v=49">
</head>
<body class="start-page dash-body">
    @include('partials.dash-top', [
        'brandSubtitle' => 'Fantasy Football mit echten Ligen — aufstellen, punkten, gewinnen.',
    ])

    <main>
        <div class="start-layout start-layout-solo">
            <section class="games-section start-leagues" aria-labelledby="start-leagues-title">
                <div class="section-head">
                    <h2 id="start-leagues-title">Aktuelle Ligen</h2>
                </div>
                <div class="game-grid">
                    @forelse ($leagues as $league)
                        <div class="game-tile start-league-tile">
                            <img src="{{ $league['symbol_url'] }}" alt="" width="56" height="56" loading="lazy">
                            <span>{{ $league['league_title'] }}</span>
                        </div>
                    @empty
                        <p class="muted">Keine aktiven Ligen.</p>
                    @endforelse
                </div>
            </section>

            <section class="results" aria-labelledby="start-results-title">
                <div class="section-head">
                    <h2 id="start-results-title">Letzte Ergebnisse</h2>
                </div>
                @if (count($results) === 0)
                    <p class="muted">Noch keine Ergebnisse.</p>
                @else
                    <ul class="result-list">
                        @foreach ($results as $result)
                            <li
                                class="result-card"
                                style="--ffb-flag-home: {{ $result['home_flag_css'] }}; --ffb-flag-away: {{ $result['guest_flag_css'] }};"
                            >
                                <div class="result-row">
                                    <span class="home">
                                        <span class="match-team-name">{{ $result['home_team'] }}</span>
                                    </span>
                                    <span class="score">{!! $result['score_html'] ?? (e($result['home_score']).':'.e($result['guest_score'])) !!}</span>
                                    <span class="away">
                                        <span class="match-team-name">{{ $result['guest_team'] }}</span>
                                    </span>
                                </div>
                                @if (!empty($result['round_label']))
                                    <div class="result-meta">{{ $result['round_label'] }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </main>

    @include('partials.footer')

    @include('partials.guest-login-scripts')
</body>
</html>
