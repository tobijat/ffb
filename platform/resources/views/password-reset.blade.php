<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Passwort zurücksetzen — SoccerSportsfan</title>
    <link rel="stylesheet" href="{{ rtrim(config('ffb.home_path'), '/') }}/css/start.css">
    <link rel="stylesheet" href="{{ rtrim(config('ffb.home_path'), '/') }}/css/dashboard.css">
    <link rel="stylesheet" href="{{ rtrim(config('ffb.home_path'), '/') }}/css/account.css?v=2">
</head>
<body class="dash-body">
    @php $nav = $data['navigation']; @endphp

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
    </header>

    <main class="dash-main">
        <section class="panel account-main" style="max-width: 32rem; margin: 0 auto;">
            <div class="section-head">
                <h2>Passwort zurücksetzen</h2>
            </div>
            <p class="muted">Account: <strong>{{ $nickname }}</strong></p>

            @if (!empty($errors))
                <div class="account-flash account-flash-error" role="alert">
                    <strong>Es sind Fehler aufgetreten:</strong>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="account-form" method="post" action="{{ $formAction }}" autocomplete="off">
                @csrf
                <div class="account-field">
                    <label for="user_password">* Neues Passwort</label>
                    <input id="user_password" type="password" name="user_password" maxlength="32" required autocomplete="new-password">
                </div>
                <div class="account-field">
                    <label for="user_password_val">* Passwort wiederholen</label>
                    <input id="user_password_val" type="password" name="user_password_val" maxlength="32" required autocomplete="new-password">
                </div>
                <div class="account-actions">
                    <button type="submit" class="btn">Passwort speichern</button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
