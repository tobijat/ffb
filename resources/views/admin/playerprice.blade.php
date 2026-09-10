@extends('layouts.admin')

@section('title', 'Preis')

@section('content')
    @php
        $selectedLeague = $data['selected_league'] ?? null;
        $matchrounds = $data['matchrounds'] ?? [];
        $priceMargins = $data['price_margins'] ?? [];
        $priceOptions = $data['price_options'] ?? range(1, 19);
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $flashDetails = is_array($details ?? null) ? $details : [];
        $hasLeague = ! empty($selectedLeague);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-playerprice-title">
        <div class="section-head">
            <h2 id="admin-playerprice-title">PlayerPrice Settings</h2>
        </div>
        <p class="hint">
            Dynamische Spielerpreise und ELO-Basisteampreise für die im Admin-Center ausgewählte Liga
            (entspricht dem Legacy-Menü „PlayerPrice“ / playerprice2014).
        </p>
        @if ($hasLeague)
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

    <section class="panel admin-main" aria-labelledby="admin-playerprice-dynamic-title">
        <div class="section-head">
            <h2 id="admin-playerprice-dynamic-title">Dynamic PlayerPrices v2014</h2>
        </div>
        <p class="hint">
            Berechnet Preis-Margins aus den letzten Spielen und schreibt
            <code>ffb_playerprice</code> für die gewählte Spielrunde.
        </p>
        <form class="admin-form" method="post" action="{{ route('admin.playerprice.setMatchroundPlayerPrices') }}" accept-charset="UTF-8">
            @csrf
            <div class="admin-field">
                <label for="pp_dyn_matchround">calculate for</label>
                <select id="pp_dyn_matchround" name="matchround_id" @disabled(! $hasLeague)>
                    <option value="">Select Matchround..</option>
                    @foreach ($matchrounds as $round)
                        <option value="{{ $round['matchround_id'] }}" @selected((string) old('matchround_id') === (string) $round['matchround_id'])>
                            {{ $round['matchround_title'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-field">
                <label for="pp_dyn_margin">Price Margin</label>
                <select id="pp_dyn_margin" name="price_margin" @disabled(! $hasLeague)>
                    <option value="">price margin..</option>
                    @foreach ($priceMargins as $margin)
                        <option value="{{ $margin }}" @selected((string) old('price_margin') === (string) $margin)>
                            {{ $margin }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-actions">
                <button type="submit" class="admin-submit" name="set_playerprice_submit" value="1" @disabled(! $hasLeague)>
                    Set Player Prices
                </button>
            </div>
            <p class="hint">(do only click once!)</p>
        </form>
    </section>

    <section class="panel admin-main admin-playerprice-warn" aria-labelledby="admin-playerprice-elo-game-title">
        <div class="section-head">
            <h2 id="admin-playerprice-elo-game-title">ELO BasePrices for League</h2>
        </div>
        <p class="hint">
            Setzt <code>team_avg_price</code> und alle Spieler-Basisteampreise für Teams der aktiven Liga
            anhand der aktuellen ELO-Rangliste.
        </p>
        <form class="admin-form" method="post" action="{{ route('admin.playerprice.setLeagueEloTeamPrices') }}" accept-charset="UTF-8">
            @csrf
            <div class="admin-field">
                <label for="pp_game_max">Max Price</label>
                <select id="pp_game_max" name="max_price" @disabled(! $hasLeague)>
                    <option value="">max price..</option>
                    @foreach ($priceOptions as $price)
                        <option value="{{ $price }}" @selected((string) old('max_price') === (string) $price)>
                            {{ $price }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-field">
                <label for="pp_game_min">Min Price</label>
                <select id="pp_game_min" name="min_price" @disabled(! $hasLeague)>
                    <option value="">min price..</option>
                    @foreach ($priceOptions as $price)
                        <option value="{{ $price }}" @selected((string) old('min_price') === (string) $price)>
                            {{ $price }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-actions">
                <button type="submit" class="admin-submit" name="set_teamprice_for_game_submit" value="1" @disabled(! $hasLeague)>
                    Set Team prices
                </button>
            </div>
            <p class="hint"><strong>(do only click once!)<br>(do not update during tournament!)</strong></p>
        </form>
    </section>

    <section class="panel admin-main admin-playerprice-warn" aria-labelledby="admin-playerprice-elo-round-title">
        <div class="section-head">
            <h2 id="admin-playerprice-elo-round-title">ELO BasePrices for Matchround</h2>
        </div>
        <p class="hint">
            Wie oben, aber nur für Teams, die in der gewählten Spielrunde Spiele haben.
        </p>
        <form class="admin-form" method="post" action="{{ route('admin.playerprice.setMatchroundEloTeamPrices') }}" accept-charset="UTF-8">
            @csrf
            <div class="admin-field">
                <label for="pp_round_matchround">calculate for Teams participating in</label>
                <select id="pp_round_matchround" name="matchround_id" @disabled(! $hasLeague)>
                    <option value="">Select Matchround..</option>
                    @foreach ($matchrounds as $round)
                        <option value="{{ $round['matchround_id'] }}" @selected((string) old('matchround_id') === (string) $round['matchround_id'])>
                            {{ $round['matchround_title'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-field">
                <label for="pp_round_max">Max Price</label>
                <select id="pp_round_max" name="max_price" @disabled(! $hasLeague)>
                    <option value="">max price..</option>
                    @foreach ($priceOptions as $price)
                        <option value="{{ $price }}" @selected((string) old('max_price') === (string) $price)>
                            {{ $price }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-field">
                <label for="pp_round_min">Min Price</label>
                <select id="pp_round_min" name="min_price" @disabled(! $hasLeague)>
                    <option value="">min price..</option>
                    @foreach ($priceOptions as $price)
                        <option value="{{ $price }}" @selected((string) old('min_price') === (string) $price)>
                            {{ $price }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-actions">
                <button type="submit" class="admin-submit" name="set_teamprice_for_matchround_submit" value="1" @disabled(! $hasLeague)>
                    Set Team prices
                </button>
            </div>
            <p class="hint"><strong>(do only click once!)<br>(do not update during tournament!)</strong></p>
        </form>
    </section>
@endsection
