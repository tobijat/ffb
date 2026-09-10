@extends('layouts.admin')

@section('title', 'UserScore Settings')

@section('content')
    @php
        $selectedLeague = $data['selected_league'] ?? null;
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $flashDetails = is_array($details ?? null) ? $details : [];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-score-title">
        <div class="section-head">
            <h2 id="admin-score-title">UserScore Settings</h2>
        </div>
        <p class="hint">
            Berechnet Userteam- und User-Scores für die im Admin-Center ausgewählte Liga
            (entspricht dem Legacy-Menü „UserScore / configuration“).
        </p>
        @if ($selectedLeague)
            <p class="muted">Aktive Liga: {{ $selectedLeague['league_title'] }}</p>
        @else
            <p class="hint">Bitte zuerst unter <a href="{{ url('/admin') }}">Ligen</a> eine Liga auswählen.</p>
        @endif

        @if (!empty($flashErrors))
            <div class="account-flash account-flash-error" role="alert">
                <strong>Es sind Fehler aufgetreten:</strong>
                <ul>
                    @foreach ($flashErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($answer)
            <div class="account-flash account-flash-ok" role="status">
                <strong>{{ $answer }}</strong>
                @if ($flashDetails !== [])
                    <ul class="admin-score-details">
                        @foreach ($flashDetails as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </section>

    <section class="panel admin-main" aria-labelledby="admin-score-userteam-title">
        <div class="section-head">
            <h2 id="admin-score-userteam-title">Userteam Score</h2>
        </div>
        <p class="hint">
            Summiert die Spieler-Punkte der Aufstellung je Userteam und schreibt
            <code>userteam_score</code>. Anschließend werden WC-Punkte für beendete
            Spielrunden neu vergeben.
        </p>
        <form class="admin-form" method="post" action="{{ route('admin.score.setUserteamScores') }}" accept-charset="UTF-8">
            @csrf
            <div class="admin-actions admin-actions-flush">
                <button type="submit" class="admin-submit" name="set_userteamscores_submit" value="1" @disabled(! $selectedLeague)>
                    Set Userteam Score
                </button>
            </div>
            <p class="hint">(do only click once!)</p>
        </form>
    </section>

    <section class="panel admin-main" aria-labelledby="admin-score-user-title">
        <div class="section-head">
            <h2 id="admin-score-user-title">User Score</h2>
        </div>
        <p class="hint">
            Summiert die Userteam-Scores und WC-Punkte je User über alle Spielrunden der Liga
            und schreibt <code>ffb_userscore</code>.
        </p>
        <form class="admin-form" method="post" action="{{ route('admin.score.setUserScores') }}" accept-charset="UTF-8">
            @csrf
            <div class="admin-actions admin-actions-flush">
                <button type="submit" class="admin-submit" name="set_userscores_submit" value="1" @disabled(! $selectedLeague)>
                    Set User Score
                </button>
            </div>
            <p class="hint">(do only click once!)</p>
        </form>
    </section>
@endsection
