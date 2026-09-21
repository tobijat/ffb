@extends('layouts.admin')

@section('title', 'Preis')

@section('content')
    @php
        $leagues = $data['leagues'] ?? [];
        $priceLeagueId = (int) ($data['price_league_id'] ?? 0);
        $selectedLeague = $data['selected_league'] ?? null;
        $matchrounds = $data['matchrounds'] ?? [];
        $matchroundId = (int) ($data['matchround_id'] ?? 0);
        $priceMargins = $data['price_margins'] ?? [];
        $lineupMaxCredits = (float) ($data['lineup_max_credits'] ?? 100);
        $lineupMaxPlayersTeam = (int) ($data['lineup_max_players_team'] ?? 3);
        $lineupLimitsSource = (string) ($data['lineup_limits_source'] ?? 'league');
        $teamPricePreview = is_array($data['team_price_preview'] ?? null) ? $data['team_price_preview'] : null;
        $performancePreview = is_array($data['performance_preview'] ?? null) ? $data['performance_preview'] : null;
        $performancePlayers = is_array($performancePreview['players'] ?? null) ? $performancePreview['players'] : [];
        $performancePositions = is_array($performancePreview['positions'] ?? null) ? $performancePreview['positions'] : [];
        $performanceHasTeamprices = ! empty($data['performance_has_teamprices']);
        $performanceIncludeOpponent = (string) old(
            'include_opponent_strength',
            ($performancePreview['include_opponent_strength'] ?? $performanceHasTeamprices) ? '1' : '0',
        ) === '1';
        $performanceOpponentWeight = old(
            'opponent_weight',
            $performancePreview['opponent_weight'] ?? ($data['performance_opponent_weight'] ?? 0.25),
        );
        $previewTeams = is_array($teamPricePreview['teams'] ?? null) ? $teamPricePreview['teams'] : [];
        $previewChecks = is_array($teamPricePreview['checks'] ?? null) ? $teamPricePreview['checks'] : [];
        $previewParams = is_array($teamPricePreview['params'] ?? null) ? $teamPricePreview['params'] : [];
        $previewForm = is_array($teamPricePreview['form'] ?? null) ? $teamPricePreview['form'] : [];
        $eloExponent = old('exponent', $previewForm['exponent'] ?? $data['elo_exponent'] ?? 2);
        $eloDreamTeamRatio = old('dream_team_ratio', $previewForm['dream_team_ratio'] ?? $data['elo_dream_team_ratio'] ?? 1.5);
        $eloMinPrice = old('min_price', $previewForm['min_price'] ?? $data['elo_min_price'] ?? 1);
        $eloMaxCredits = old('max_credits', $previewForm['max_credits'] ?? $lineupMaxCredits);
        $eloMaxPlayersTeam = old('max_players_team', $previewForm['max_players_team'] ?? $lineupMaxPlayersTeam);
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $flashDetails = is_array($details ?? null) ? $details : [];
        $hasLeague = $priceLeagueId > 0;
        $tab = match ($data['tab'] ?? 'teams') {
            'players' => 'players',
            'performance' => 'performance',
            default => 'teams',
        };
        $baseQuery = array_filter([
            'price_league_id' => $hasLeague ? $priceLeagueId : null,
        ], static fn ($v) => $v !== null);
        $teamsQuery = array_filter(
            $baseQuery + [
                'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
            ],
            static fn ($v) => $v !== null,
        );
        $playersQuery = $baseQuery + ['tab' => 'players'];
        $performanceQuery = array_filter(
            $baseQuery + [
                'tab' => 'performance',
                'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
            ],
            static fn ($v) => $v !== null,
        );
        $limitsSourceLabel = match ($lineupLimitsSource) {
            'matchround' => 'Spielrunde',
            'league' => 'Liga',
            default => 'Standard',
        };
        $positionLabels = ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-playerprice-title">
        <div class="section-head">
            <h2 id="admin-playerprice-title">Preise</h2>
        </div>
        <p class="hint">
            Dynamische Spielerpreise und ELO-Teampreise für die im Admin-Center ausgewählte Liga.
        </p>

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

        @if (! $hasLeague)
            <p class="hint">Bitte zuerst unter <a href="{{ url('/admin') }}">Ligen</a> eine Liga auswählen.</p>
        @else
            <p class="muted">Liga: {{ $selectedLeague['league_title'] ?? ('#'.$priceLeagueId) }}</p>
            <nav class="admin-squad-tabs ffb-tabs" aria-label="Preis-Bereiche">
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'teams' ? ' is-active' : '' }}"
                    href="{{ route('admin.playerprice', $teamsQuery) }}"
                >
                    Team-Preis
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'players' ? ' is-active' : '' }}"
                    href="{{ route('admin.playerprice', $playersQuery) }}"
                >
                    Spieler-Preis
                </a>
                <a
                    class="admin-squad-tab ffb-tab{{ $tab === 'performance' ? ' is-active' : '' }}"
                    href="{{ route('admin.playerprice', $performanceQuery) }}"
                >
                    Spieler-Performance
                </a>
            </nav>
        @endif
    </section>

    @if ($hasLeague && $tab === 'players')
        <section class="panel admin-main" aria-labelledby="admin-playerprice-dynamic-title">
            <div class="section-head">
                <h2 id="admin-playerprice-dynamic-title">Spieler-Preis</h2>
            </div>
            <p class="hint">
                Berechnet Preis-Margins aus den letzten Spielen und schreibt
                <code>ffb_playerprice</code> für die gewählte Spielrunde (Dynamic PlayerPrices v2014).
            </p>
            <form class="admin-form" method="post" action="{{ route('admin.playerprice.setMatchroundPlayerPrices') }}" accept-charset="UTF-8">
                @csrf
                <input type="hidden" name="price_league_id" value="{{ $priceLeagueId }}">
                <input type="hidden" name="tab" value="players">
                <div class="admin-field">
                    <label for="pp_dyn_matchround">calculate for</label>
                    <select id="pp_dyn_matchround" name="matchround_id">
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
                    <select id="pp_dyn_margin" name="price_margin">
                        <option value="">price margin..</option>
                        @foreach ($priceMargins as $margin)
                            <option value="{{ $margin }}" @selected((string) old('price_margin') === (string) $margin)>
                                {{ $margin }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-actions">
                    <button type="submit" class="admin-submit" name="set_playerprice_submit" value="1">
                        Set Player Prices
                    </button>
                </div>
                <p class="hint">(do only click once!)</p>
            </form>
        </section>
    @endif

    @if ($hasLeague && $tab === 'performance')
        <section class="panel admin-main" aria-labelledby="admin-playerprice-performance-title">
            <div class="section-head">
                <h2 id="admin-playerprice-performance-title">Spieler-Performance</h2>
            </div>
            <p class="hint">
                Berechnet <code>round_performance</code> aus dem Positions-Rang (0 = schlechteste Punkte,
                n−1 = beste; bei Gleichstand gemittelter Rang), skaliert auf −1…+1 (gerundet auf 1/1000).
                Nur Spieler mit Minuten &gt; 0. Speichern schreibt in
                <code>ffb_playerstats.playerstats_round_performance</code>.
            </p>

            <form class="admin-league-picker" method="get" action="{{ route('admin.playerprice') }}">
                <input type="hidden" name="price_league_id" value="{{ $priceLeagueId }}">
                <input type="hidden" name="tab" value="performance">
                <label for="pp_perf_matchround_pick">Spielrunde</label>
                <select id="pp_perf_matchround_pick" name="matchround_id" onchange="this.form.submit()">
                    <option value="">Spielrunde wählen…</option>
                    @foreach ($matchrounds as $round)
                        <option
                            value="{{ $round['matchround_id'] }}"
                            @selected($matchroundId === (int) $round['matchround_id'])
                        >
                            {{ $round['matchround_title'] }}
                        </option>
                    @endforeach
                </select>
                <noscript>
                    <button type="submit" class="admin-submit">Anzeigen</button>
                </noscript>
            </form>

            @if ($matchroundId > 0)
                <form class="admin-form" method="post" action="{{ route('admin.playerprice.previewMatchroundPerformance') }}" accept-charset="UTF-8">
                    @csrf
                    <input type="hidden" name="price_league_id" value="{{ $priceLeagueId }}">
                    <input type="hidden" name="tab" value="performance">
                    <input type="hidden" name="matchround_id" value="{{ $matchroundId }}">

                    @if ($performanceHasTeamprices)
                        <div class="admin-field">
                            <input type="hidden" name="include_opponent_strength" value="0">
                            <label>
                                <input
                                    type="checkbox"
                                    name="include_opponent_strength"
                                    value="1"
                                    @checked($performanceIncludeOpponent)
                                >
                                Gegnerstärke (Teampreis) einbeziehen
                            </label>
                            <p class="hint">
                                Passt die Rang-Performance um den relativen Teampreis-Unterschied zum Gegner an.
                            </p>
                        </div>
                        <div class="admin-field">
                            <label for="pp_perf_opponent_weight">OPPONENT_WEIGHT</label>
                            <input
                                id="pp_perf_opponent_weight"
                                type="number"
                                name="opponent_weight"
                                value="{{ $performanceOpponentWeight }}"
                                min="0"
                                max="2"
                                step="0.05"
                            >
                            <p class="hint">Gewicht der Gegnerstärke (Standard 0.25).</p>
                        </div>
                    @else
                        <p class="muted">
                            Gegnerstärke ist nicht verfügbar: für diese Spielrunde fehlen Teampreise
                            (oder es gibt keine Teams). Bitte zuerst im Tab Teams befüllen.
                        </p>
                    @endif

                    <div class="admin-actions">
                        <button type="submit" class="admin-submit" name="preview_matchround_performance" value="1">
                            Matchround-Performance berechnen
                        </button>
                        <button
                            type="submit"
                            class="admin-submit"
                            formaction="{{ route('admin.playerprice.saveMatchroundPerformance') }}"
                            name="save_matchround_performance"
                            value="1"
                            @disabled($performancePreview === null)
                            title="{{ $performancePreview === null ? 'Zuerst Performance berechnen' : 'Berechnete Performance speichern' }}"
                        >
                            Speichern
                        </button>
                    </div>
                </form>
            @endif

            @if ($performancePreview !== null)
                @if ($performancePositions !== [])
                    <p class="muted">Spieler je Position (Minuten &gt; 0):</p>
                    <ul class="admin-playerprice-checks">
                        @foreach ($performancePositions as $code => $pos)
                            <li>
                                <strong>{{ strtoupper((string) $code) }}</strong>
                                {{ $positionLabels[$code] ?? $code }}:
                                n={{ $pos['sample_size'] ?? 0 }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if (! empty($performancePreview['include_opponent_strength']))
                    <p class="muted">
                        Gegnerstärke aktiv · OPPONENT_WEIGHT={{ $performancePreview['opponent_weight'] ?? '—' }}
                    </p>
                @endif

                @if ($performancePlayers === [])
                    <p class="muted">Keine Spieler mit Einsatz in dieser Spielrunde gefunden.</p>
                @else
                    <div class="admin-auto-squad-table-wrap">
                        <table class="admin-auto-squad-table admin-playerprice-preview-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Spieler</th>
                                    <th>Team</th>
                                    <th>Pos.</th>
                                    <th>Punkte</th>
                                    <th>Rang</th>
                                    @if (! empty($performancePreview['include_opponent_strength']))
                                        <th>raw</th>
                                        <th>opp_factor</th>
                                    @endif
                                    <th>round_performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($performancePlayers as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row['player_name'] ?? '' }}</td>
                                        <td>{{ $row['team_name'] ?? '' }}</td>
                                        <td>{{ strtoupper((string) ($row['position'] ?? '')) }}</td>
                                        <td>{{ $row['points'] ?? '' }}</td>
                                        <td>{{ $row['rank'] ?? '' }}</td>
                                        @if (! empty($performancePreview['include_opponent_strength']))
                                            <td>{{ $row['raw_round_performance'] ?? '' }}</td>
                                            <td>{{ $row['opponent_factor'] ?? '' }}</td>
                                        @endif
                                        <td><strong>{{ $row['round_performance'] ?? '' }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </section>
    @endif

    @if ($hasLeague && $tab === 'teams')
        <section class="panel admin-main" aria-labelledby="admin-playerprice-elo-title">
            <div class="section-head">
                <h2 id="admin-playerprice-elo-title">ELO Team-Preis</h2>
            </div>
            <p class="hint">
                Berechnet Spieler-Basisteampreise aus ELO-Ratings (eloratings.net).
                Ohne Spielrunde: alle Liga-Teams; Speichern schreibt in alle zukünftigen Spielrunden der Liga.
                Mit Spielrunde: nur Teams der Runde; Speichern nur für diese zukünftige Runde.
                Vergangene Spielrunden sind sichtbar, aber nicht wählbar und werden nicht verändert.
            </p>

            <form class="admin-league-picker" method="get" action="{{ route('admin.playerprice') }}">
                <input type="hidden" name="price_league_id" value="{{ $priceLeagueId }}">
                <input type="hidden" name="tab" value="teams">
                <label for="pp_elo_matchround">Spielrunde</label>
                <select id="pp_elo_matchround" name="matchround_id" onchange="this.form.submit()">
                    <option value="">— Auf alle zukünftigen Spielrunden anwenden —</option>
                    @foreach ($matchrounds as $round)
                        <option
                            value="{{ $round['matchround_id'] }}"
                            @selected($matchroundId === (int) $round['matchround_id'])
                            @disabled(empty($round['is_future']))
                        >
                            {{ $round['matchround_title'] }}
                            @if (empty($round['is_future']))
                                (vergangen)
                            @endif
                        </option>
                    @endforeach
                </select>
                <noscript>
                    <button type="submit" class="admin-submit">Anzeigen</button>
                </noscript>
            </form>
            <p class="hint">
                Limits-Vorschlag aus {{ $limitsSourceLabel }}:
                {{ rtrim(rtrim(number_format($lineupMaxCredits, 1, '.', ''), '0'), '.') }} Credits,
                max. {{ $lineupMaxPlayersTeam }} Spieler / Team.
            </p>

            <form class="admin-form" method="post" action="{{ route('admin.playerprice.previewEloTeamPrices') }}" accept-charset="UTF-8">
                @csrf
                <input type="hidden" name="price_league_id" value="{{ $priceLeagueId }}">
                <input type="hidden" name="tab" value="teams">
                <input type="hidden" name="matchround_id" value="{{ $matchroundId > 0 ? $matchroundId : '' }}">
                <div class="admin-field">
                    <label for="pp_elo_max_credits">Max. Credits / Aufstellung</label>
                    <input
                        id="pp_elo_max_credits"
                        type="number"
                        name="max_credits"
                        value="{{ $eloMaxCredits }}"
                        min="1"
                        max="500"
                        step="0.5"
                        required
                    >
                    <p class="hint">Budget für die Checks (Standard aus {{ $limitsSourceLabel }}).</p>
                </div>
                <div class="admin-field">
                    <label for="pp_elo_max_per_team">Max. Spieler / Team</label>
                    <input
                        id="pp_elo_max_per_team"
                        type="number"
                        name="max_players_team"
                        value="{{ $eloMaxPlayersTeam }}"
                        min="1"
                        max="11"
                        step="1"
                        required
                    >
                    <p class="hint">Obergrenze pro Team im Dream-/Check-Lineup (Standard aus {{ $limitsSourceLabel }}).</p>
                </div>
                <div class="admin-field">
                    <label for="pp_elo_exponent">Exponent</label>
                    <input
                        id="pp_elo_exponent"
                        type="number"
                        name="exponent"
                        value="{{ $eloExponent }}"
                        min="0.1"
                        max="10"
                        step="0.1"
                        required
                    >
                    <p class="hint">Steuert, wie stark starke Teams teurer werden (Standard 2.0).</p>
                </div>
                <div class="admin-field">
                    <label for="pp_elo_dream_ratio">Dream-Team-Ratio</label>
                    <input
                        id="pp_elo_dream_ratio"
                        type="number"
                        name="dream_team_ratio"
                        value="{{ $eloDreamTeamRatio }}"
                        min="0.1"
                        max="5"
                        step="0.05"
                        required
                    >
                    <p class="hint">Zielkosten des Dream-Teams als Vielfaches des Budgets (Standard 1.5 = 150%).</p>
                </div>
                <div class="admin-field">
                    <label for="pp_elo_min_price">Mindestpreis</label>
                    <input
                        id="pp_elo_min_price"
                        type="number"
                        name="min_price"
                        value="{{ $eloMinPrice }}"
                        min="0"
                        max="20"
                        step="0.1"
                        required
                    >
                    <p class="hint">Preis des schwächsten Teams; stärkere Teams liegen darüber (Standard 1.0).</p>
                </div>
                <div class="admin-actions">
                    <button type="submit" class="admin-submit" name="preview_elo_team_prices" value="1">
                        Preise berechnen
                    </button>
                    <button
                        type="submit"
                        class="admin-submit"
                        formaction="{{ route('admin.playerprice.saveEloTeamPrices') }}"
                        name="save_elo_team_prices"
                        value="1"
                        @disabled($teamPricePreview === null)
                        title="{{ $teamPricePreview === null ? 'Zuerst Preise berechnen' : 'Berechnete Preise speichern' }}"
                    >
                        Preise speichern
                    </button>
                </div>
            </form>

            @if ($teamPricePreview !== null)
                @php
                    $skippedTeams = $teamPricePreview['teams_skipped'] ?? [];
                @endphp
                @if (count($skippedTeams) > 0)
                    <div class="admin-playerprice-skipped">
                        <p class="hint">
                            {{ count($skippedTeams) }} Team(s) ohne ELO-Zuordnung wurden übersprungen:
                        </p>
                        <ul>
                            @foreach ($skippedTeams as $skipped)
                                <li>{{ $skipped['team_name'] ?? ('Team #'.($skipped['team_id'] ?? '?')) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($previewParams !== [])
                    <p class="muted">
                        Parameter: Exponent {{ $previewParams['exponent'] ?? '—' }},
                        Dream-Team-Ratio {{ $previewParams['dream_team_ratio'] ?? '—' }},
                        Mindestpreis {{ $previewParams['min_price'] ?? '—' }},
                        Budget {{ $previewParams['budget'] ?? '—' }},
                        Max./Team {{ $previewParams['max_per_team'] ?? '—' }}
                    </p>
                @endif

                @if ($previewChecks !== [])
                    <ul class="admin-playerprice-checks">
                        @foreach ($previewChecks as $check)
                            <li class="{{ ! empty($check['ok']) ? 'is-ok' : 'is-warn' }}">
                                <strong>{{ $check['id'] ?? 'check' }}:</strong>
                                {{ $check['message'] ?? '' }}
                                @if (isset($check['cost']))
                                    <span class="muted">({{ $check['cost'] }} / Ziel {{ $check['target'] ?? '—' }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if (count($previewTeams) > 0)
                    <div class="admin-auto-squad-table-wrap">
                        <table class="admin-auto-squad-table admin-playerprice-preview-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Team</th>
                                    <th>ELO</th>
                                    <th>Normalisiert</th>
                                    <th>Preis / Spieler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previewTeams as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row['team_name'] ?? '' }}</td>
                                        <td>{{ $row['elo_rating'] ?? '' }}</td>
                                        <td>{{ $row['normalized'] ?? '' }}</td>
                                        <td><strong>{{ $row['price'] ?? '' }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </section>
    @endif
@endsection
