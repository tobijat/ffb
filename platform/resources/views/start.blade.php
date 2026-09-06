<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SoccerSportsfan — Fantasy Football</title>
    <meta name="description" content="Fantasy Football Manager bei SoccerSportsfan. Kostenlos mitspielen.">
    <link rel="stylesheet" href="css/start.css?v=9">
    <link rel="stylesheet" href="css/dashboard.css?v=2">
</head>
<body class="start-page dash-body">
    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
        </div>
    </header>

    <main>
        <div class="start-layout">
            <div class="start-col start-col-main">
                <section class="hero-copy">
                    <p class="brand-mark">SoccerSportsfan</p>
                    <h1>Stell dein Team auf.</h1>
                    <p class="lede">Fantasy Football mit echten Ligen — aufstellen, punkten, gewinnen.</p>
                    <div class="hero-actions">
                        <a class="btn btn-ghost" href="/platform/registration">Kostenlos registrieren</a>
                    </div>
                </section>

                <section class="results" aria-label="Letzte Ergebnisse">
                    <h2>Letzte Ergebnisse</h2>
                    @if (count($results) === 0)
                        <p class="muted">Noch keine Ergebnisse.</p>
                    @else
                        <ul class="result-list">
                            @foreach ($results as $result)
                                <li>
                                    <time>{{ $result['date'] }}</time>
                                    <span class="side">
                                        <img src="{{ $legacyBase }}images/ffb/flags/{{ $result['home_flag'] }}.gif" alt="" width="16" height="11" loading="lazy">
                                        {{ $result['home_team'] }}
                                    </span>
                                    <strong class="score">{!! $result['score_html'] ?? (e($result['home_score']).':'.e($result['guest_score'])) !!}</strong>
                                    <span class="side">
                                        <img src="{{ $legacyBase }}images/ffb/flags/{{ $result['guest_flag'] }}.gif" alt="" width="16" height="11" loading="lazy">
                                        {{ $result['guest_team'] }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            <div class="start-col start-col-side">
                <form id="login" class="login-panel" method="post" action="login" novalidate data-mode="login">
                    @csrf
                    <h2 id="login-title">Anmelden</h2>
                    <div id="login-feedback" class="feedback" hidden></div>
                    @if (!empty($accountMessage))
                        <div class="feedback feedback-ok" role="status">{!! $accountMessage !!}</div>
                    @endif

                    <label id="field-nickname">
                        <span id="label-nickname">Nickname</span>
                        <input type="text" name="user_nickname" id="user_nickname" autocomplete="username" required autofocus>
                    </label>
                    <label id="field-password">
                        <span>Passwort</span>
                        <input type="password" name="user_password" id="user_password" autocomplete="current-password" required>
                    </label>
                    <input type="hidden" name="destination" value="{{ $destination }}">

                    <button type="submit" class="btn btn-primary" id="login-submit">Anmelden</button>
                    <p class="login-links">
                        <button type="button" id="forgot-password" class="linkish">Passwort vergessen?</button>
                        <button type="button" id="back-to-login" class="linkish" hidden>Zurück zum Login</button>
                    </p>
                </form>

                <section class="stats" aria-label="Community">
                    <h2>Community</h2>
                    <dl>
                        <div><dt>Registrierte User</dt><dd>{{ number_format($stats['users_total'], 0, ',', '.') }}</dd></div>
                        <div><dt>Heute eingeloggt</dt><dd>{{ number_format($stats['users_today'], 0, ',', '.') }}</dd></div>
                        <div><dt>Aufgestellte Teams</dt><dd>{{ number_format($stats['lineups'], 0, ',', '.') }}</dd></div>
                        <div><dt>Matchrunden</dt><dd>{{ number_format($stats['matchrounds_played'], 0, ',', '.') }}</dd></div>
                        <div><dt>Ø Punkte / Aufstellung</dt><dd>{{ number_format($stats['score_avg'], 2, ',', '.') }}</dd></div>
                    </dl>
                    @if (count($leagues))
                        <p class="leagues">Aktive Ligen: {{ collect($leagues)->pluck('game_title')->join(' · ') }}</p>
                    @endif
                </section>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        window.FFB_START = {
            loginUrl: new URL('login', window.location.href).pathname,
            passwordUrl: new URL('registration/password', window.location.href).pathname,
        };
    </script>
    <script src="js/start.js?v=6" defer></script>
</body>
</html>
