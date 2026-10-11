<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ !empty($passwordReset) ? 'Passwort zurücksetzen — SoccerSportsfan' : 'SoccerSportsfan — Fantasy Football' }}</title>
    <meta name="description" content="Fantasy Football Manager bei SoccerSportsfan. Kostenlos mitspielen.">
    @php $assetBase = rtrim(config('ffb.home_path'), '/'); @endphp
    <link rel="stylesheet" href="{{ $assetBase }}/css/start.css?v=20">
    <link rel="stylesheet" href="{{ $assetBase }}/css/dashboard.css?v=12">
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
                        <a class="btn btn-ghost" href="{{ $assetBase }}/registration">Kostenlos registrieren</a>
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
                                        <x-flag :code="$result['home_flag']" />
                                        {{ $result['home_team'] }}
                                    </span>
                                    <strong class="score">{!! $result['score_html'] ?? (e($result['home_score']).':'.e($result['guest_score'])) !!}</strong>
                                    <span class="side">
                                        <x-flag :code="$result['guest_flag']" />
                                        {{ $result['guest_team'] }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            <div class="start-col start-col-side">
                @if (!empty($passwordReset))
                    <form class="login-panel" method="post" action="{{ $passwordReset['formAction'] }}" autocomplete="off">
                        @csrf
                        <h2>Passwort zurücksetzen</h2>
                        <p class="muted">Account: <strong>{{ $passwordReset['nickname'] }}</strong></p>

                        @if (!empty($passwordReset['errors']))
                            <div class="feedback feedback-error" role="alert">
                                <strong>Es sind Fehler aufgetreten:</strong><br>
                                {!! implode('<br>', array_map('e', $passwordReset['errors'])) !!}
                            </div>
                        @endif

                        <label>
                            <span>Neues Passwort</span>
                            <input type="password" name="user_password" id="user_password" maxlength="32" required autocomplete="new-password" autofocus>
                        </label>
                        <label>
                            <span>Passwort wiederholen</span>
                            <input type="password" name="user_password_val" id="user_password_val" maxlength="32" required autocomplete="new-password">
                        </label>

                        <button type="submit" class="btn btn-primary">Passwort speichern</button>
                        <p class="login-links">
                            <a class="linkish" href="{{ route('start') }}">Zurück zum Login</a>
                        </p>
                    </form>
                @else
                    <form id="login" class="login-panel" method="post" action="login" novalidate data-mode="login">
                        @csrf
                        <h2 id="login-title">Anmelden</h2>
                        <div id="login-feedback" class="feedback" hidden></div>
                        @if (!empty($accountMessage))
                            @php
                                $accountFeedbackKind = str_contains((string) $accountMessage, 'Fehler') ? 'error' : 'ok';
                            @endphp
                            <div class="feedback feedback-{{ $accountFeedbackKind }}" role="status">{!! $accountMessage !!}</div>
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
                @endif

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
                        <p class="leagues">Aktive Ligen: {{ collect($leagues)->pluck('league_title')->join(' · ') }}</p>
                    @endif
                </section>
            </div>
        </div>
    </main>

    @include('partials.footer')

    @if (empty($passwordReset))
        <script>
            window.FFB_START = {
                loginUrl: new URL('login', window.location.href).pathname,
                passwordUrl: new URL('registration/password', window.location.href).pathname,
            };
        </script>
        <script src="{{ $assetBase }}/js/start.js?v=7" defer></script>
    @endif
</body>
</html>
