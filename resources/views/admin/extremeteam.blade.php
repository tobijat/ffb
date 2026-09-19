@extends('layouts.admin')

@section('title', 'Top & Flop Teams')

@section('content')
    @php
        $selectedLeague = $data['selected_league'] ?? null;
        $selectedLeagueId = (int) ($data['selected_league_id'] ?? 0);
        $pastRounds = is_array($data['past_matchrounds'] ?? null) ? $data['past_matchrounds'] : [];
        $existing = is_array($data['existing'] ?? null) ? $data['existing'] : [];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $flashDetails = is_array($details ?? null) ? $details : [];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-extremeteam-title">
        <div class="section-head">
            <h2 id="admin-extremeteam-title">Top &amp; Flop Teams</h2>
        </div>
        <p class="hint">
            Berechnet und speichert Top-/Flop-Teams (gleiche Logik wie im Spieler-Bereich)
            in <code>ffb_extremeteam</code> / <code>ffb_extremeteam_slot</code>.
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

    <section class="panel admin-main" aria-labelledby="admin-extremeteam-league-title">
        <div class="section-head">
            <h2 id="admin-extremeteam-league-title">Spielrunden der Liga</h2>
        </div>

        @if ($selectedLeagueId <= 0)
            <p class="muted">Keine Liga gewählt.</p>
        @elseif ($pastRounds === [])
            <p class="muted">Keine vergangenen Spielrunden in dieser Liga.</p>
        @else
            <form class="admin-form" method="post" action="{{ route('admin.extremeteam.populate') }}" accept-charset="UTF-8">
                @csrf
                <input type="hidden" name="scope" value="selected">

                <div class="admin-field">
                    <label>Typen</label>
                    <label class="admin-inline-check">
                        <input type="checkbox" name="include_top" value="1" checked>
                        Top
                    </label>
                    <label class="admin-inline-check">
                        <input type="checkbox" name="include_flop" value="1" checked>
                        Flop
                    </label>
                </div>

                <div class="admin-squad-table-wrap">
                    <table class="admin-squad-table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Runde</th>
                                <th scope="col">Ende</th>
                                <th scope="col">Top</th>
                                <th scope="col">Flop</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pastRounds as $round)
                                @php
                                    $rid = (int) $round['matchround_id'];
                                    $hasTop = isset($existing[$rid]['top']);
                                    $hasFlop = isset($existing[$rid]['flop']);
                                @endphp
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="matchround_ids[]"
                                            value="{{ $rid }}"
                                            aria-label="Runde {{ $round['matchround_title'] }}"
                                        >
                                    </td>
                                    <td>{{ $round['matchround_title'] }} <span class="muted">#{{ $rid }}</span></td>
                                    <td class="muted">{{ $round['matchround_enddate'] }}</td>
                                    <td>
                                        @if ($hasTop)
                                            <span class="muted">Score {{ $existing[$rid]['top'] }}</span>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($hasFlop)
                                            <span class="muted">Score {{ $existing[$rid]['flop'] }}</span>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="admin-actions">
                    <button type="submit" class="admin-submit">Ausgewählte Runden berechnen</button>
                </div>
            </form>

            <form class="admin-form" method="post" action="{{ route('admin.extremeteam.populate') }}" accept-charset="UTF-8" style="margin-top: 1rem;">
                @csrf
                <input type="hidden" name="scope" value="all_past">
                <input type="hidden" name="include_top" value="1">
                <input type="hidden" name="include_flop" value="1">
                <div class="admin-actions">
                    <button type="submit" class="admin-submit">Alle vergangenen Runden dieser Liga</button>
                </div>
            </form>
        @endif
    </section>
@endsection
