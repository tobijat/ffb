@extends('layouts.admin')

@section('title', 'UserScore Settings')

@php
    $selectedLeague = $data['selected_league'] ?? null;
    $tab = ($data['tab'] ?? 'userteam') === 'user' ? 'user' : 'userteam';
    $userteamPreview = is_array($data['userteam_preview'] ?? null) ? $data['userteam_preview'] : null;
    $userPreview = is_array($data['user_preview'] ?? null) ? $data['user_preview'] : null;
    $userteamRows = is_array($userteamPreview['rows'] ?? null) ? $userteamPreview['rows'] : [];
    $userRows = is_array($userPreview['rows'] ?? null) ? $userPreview['rows'] : [];
    $flashErrors = $errors ?: (session('admin_errors') ?: []);
    $flashDetails = is_array($details ?? null) ? $details : [];
@endphp

@section('content')
    <section class="panel admin-main" aria-labelledby="admin-score-title">
        <div class="section-head">
            <h2 id="admin-score-title">UserScore Settings</h2>
        </div>

        <nav class="admin-squad-tabs ffb-tabs" aria-label="Score-Bereiche">
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'userteam' ? ' is-active' : '' }}"
                href="{{ route('admin.score', ['tab' => 'userteam']) }}"
            >
                Userteam Score
            </a>
            <a
                class="admin-squad-tab ffb-tab{{ $tab === 'user' ? ' is-active' : '' }}"
                href="{{ route('admin.score', ['tab' => 'user']) }}"
            >
                User Score
            </a>
        </nav>

        <p class="hint">
            Berechnet Scores für die im Admin-Center ausgewählte Liga
            (entspricht dem Legacy-Menü „UserScore / configuration“).
            Zuerst berechnen, prüfen, dann speichern.
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

        @if ($tab === 'userteam')
            <div class="section-head">
                <h3 id="admin-score-userteam-title">Userteam Score</h3>
            </div>
            <p class="hint">
                Summiert die Spieler-Punkte der Aufstellung je Userteam
                (<code>userteam_score</code>) und vergibt LC-Punkte für beendete Spielrunden.
                Speichern schreibt die berechneten Werte.
            </p>
            <form class="admin-form" method="post" action="{{ route('admin.score.calculateUserteamScores') }}" accept-charset="UTF-8">
                @csrf
                <div class="admin-actions admin-actions-flush">
                    <button type="submit" class="admin-submit" name="calculate_userteamscores" value="1" @disabled(! $selectedLeague)>
                        Score berechnen
                    </button>
                    <button
                        type="submit"
                        class="admin-submit"
                        formaction="{{ route('admin.score.saveUserteamScores') }}"
                        name="save_userteamscores"
                        value="1"
                        @disabled(! $selectedLeague || $userteamPreview === null)
                        title="{{ $userteamPreview === null ? 'Zuerst Score berechnen' : 'Berechnete Scores speichern' }}"
                    >
                        Speichern
                    </button>
                </div>
            </form>

            @if ($userteamPreview !== null)
                @if ($userteamRows === [])
                    <p class="muted">Keine Userteams in dieser Liga.</p>
                @else
                    <div class="admin-squad-table-wrap">
                        <table class="admin-squad-table">
                            <thead>
                                <tr>
                                    <th>Userteam</th>
                                    <th>User</th>
                                    <th>Runde</th>
                                    <th>Score (alt → neu)</th>
                                    <th>LC (alt → neu)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userteamRows as $row)
                                    <tr>
                                        <td>#{{ (int) ($row['userteam_id'] ?? 0) }}</td>
                                        <td>
                                            {{ ($row['user_nickname'] ?? '') !== '' ? $row['user_nickname'] : '—' }}
                                            <span class="muted">(#{{ (int) ($row['user_id'] ?? 0) }})</span>
                                        </td>
                                        <td>#{{ (int) ($row['matchround_id'] ?? 0) }}</td>
                                        <td>
                                            {{ (int) ($row['previous_score'] ?? 0) }}
                                            →
                                            <strong>{{ (int) ($row['score'] ?? 0) }}</strong>
                                        </td>
                                        <td>
                                            {{ (int) ($row['previous_lc_points'] ?? 0) }}
                                            →
                                            <strong>{{ (int) ($row['lc_points'] ?? 0) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        @else
            <div class="section-head">
                <h3 id="admin-score-user-title">User Score</h3>
            </div>
            <p class="hint">
                Summiert die gespeicherten Userteam-Scores und LC-Punkte je User über alle
                Spielrunden der Liga und schreibt <code>ffb_userscore</code>.
                Vorher Userteam Score speichern, falls neu berechnet.
            </p>
            <form class="admin-form" method="post" action="{{ route('admin.score.calculateUserScores') }}" accept-charset="UTF-8">
                @csrf
                <div class="admin-actions admin-actions-flush">
                    <button type="submit" class="admin-submit" name="calculate_userscores" value="1" @disabled(! $selectedLeague)>
                        Score berechnen
                    </button>
                    <button
                        type="submit"
                        class="admin-submit"
                        formaction="{{ route('admin.score.saveUserScores') }}"
                        name="save_userscores"
                        value="1"
                        @disabled(! $selectedLeague || $userPreview === null)
                        title="{{ $userPreview === null ? 'Zuerst Score berechnen' : 'Berechnete Scores speichern' }}"
                    >
                        Speichern
                    </button>
                </div>
            </form>

            @if ($userPreview !== null)
                @if ($userRows === [])
                    <p class="muted">Keine User-Scores in dieser Liga.</p>
                @else
                    <div class="admin-squad-table-wrap">
                        <table class="admin-squad-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Score (alt → neu)</th>
                                    <th>LC (alt → neu)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userRows as $row)
                                    <tr>
                                        <td>
                                            {{ ($row['user_nickname'] ?? '') !== '' ? $row['user_nickname'] : '—' }}
                                            <span class="muted">(#{{ (int) ($row['user_id'] ?? 0) }})</span>
                                        </td>
                                        <td>
                                            {{ $row['previous_score'] === null ? '—' : (int) $row['previous_score'] }}
                                            →
                                            <strong>{{ (int) ($row['score'] ?? 0) }}</strong>
                                        </td>
                                        <td>
                                            {{ $row['previous_lc_points'] === null ? '—' : (int) $row['previous_lc_points'] }}
                                            →
                                            <strong>{{ (int) ($row['lc_points'] ?? 0) }}</strong>
                                        </td>
                                        <td>{{ ! empty($row['is_new']) ? 'neu' : 'update' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        @endif
    </section>
@endsection
