<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SoccerSportsfan — Fantasy Football</title>
    <meta name="description" content="Fantasy Football Manager bei SoccerSportsfan. Kostenlos mitspielen.">
    <link rel="stylesheet" href="css/start.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="/platform/">SoccerSportsfan</a>
        <a class="top-link" href="{{ $legacyBase }}users/registration">Registrieren</a>
    </header>

    <main>
        <section class="hero">
            <div class="hero-copy">
                <p class="brand-mark">SoccerSportsfan</p>
                <h1>Stell dein Team auf.</h1>
                <p class="lede">Fantasy Football mit echten Ligen — aufstellen, punkten, gewinnen.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#login">Anmelden</a>
                    <a class="btn btn-ghost" href="{{ $legacyBase }}users/registration">Kostenlos starten</a>
                </div>
            </div>

            <form id="login" class="login-panel" method="post" action="login" novalidate>
                @csrf
                <h2>Anmelden</h2>
                <div id="login-feedback" class="feedback" hidden></div>
                @if (!empty($accountMessage))
                    <div class="feedback feedback-ok" role="status">{!! $accountMessage !!}</div>
                @endif

                <label>
                    <span>Nickname</span>
                    <input type="text" name="user_nickname" autocomplete="username" required autofocus>
                </label>
                <label>
                    <span>Passwort</span>
                    <input type="password" name="user_password" autocomplete="current-password" required>
                </label>
                <input type="hidden" name="destination" value="{{ $destination }}">

                <button type="submit" class="btn btn-primary btn-block">Anmelden</button>
                <p class="login-links">
                    <button type="button" id="forgot-password" class="linkish">Passwort vergessen?</button>
                    <a href="{{ $legacyBase }}users/registration">Registrieren</a>
                </p>
            </form>
        </section>

        <section class="below" aria-label="Spielinfos">
            <div class="results">
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
                                <strong class="score">{{ $result['home_score'] }}:{{ $result['guest_score'] }}</strong>
                                <span class="side">
                                    <img src="{{ $legacyBase }}images/ffb/flags/{{ $result['guest_flag'] }}.gif" alt="" width="16" height="11" loading="lazy">
                                    {{ $result['guest_team'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="stats">
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
            </div>
        </section>
    </main>

    <footer class="foot">
        <span>SoccerSportsfan</span>
        <a href="{{ $legacyBase }}users/help">Hilfe</a>
    </footer>

    <script>
        window.FFB_START = {
            // Relative to /platform/ or /platform/public/ — avoids Apache 302 breaking POST.
            loginUrl: new URL('login', window.location.href).pathname,
            passwordUrl: @json($legacyBase . 'users/registration/getPassword.xml'),
        };
    </script>
    <script src="js/start.js?v=2" defer></script>
</body>
</html>
