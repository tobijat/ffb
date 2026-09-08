@extends('layouts.admin')

@section('title', 'Spiele')

@section('content')
    @php
        $form = $data['form'];
        $mode = $data['mode'];
        $items = $data['items'];
        $games = $data['games'];
        $matchrounds = $data['matchrounds'];
        $teams = $data['teams'];
        $selectedGameId = (int) $data['selected_game_id'];
        $selectedGameTitle = $data['selected_game_title'];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-matches-title">
        <div class="section-head">
            <h2 id="admin-matches-title">Spiele</h2>
        </div>

        <form class="admin-league-picker" method="get" action="{{ route('admin.matches') }}">
            <label for="game_id">Liga</label>
            <select id="game_id" name="game_id" onchange="this.form.submit()">
                <option value="">— Liga wählen —</option>
                @foreach ($games as $game)
                    <option value="{{ $game['game_id'] }}" @selected($selectedGameId === (int) $game['game_id'])>
                        {{ $game['game_title'] }}@if ($game['game_archive']) (Archiv)@endif
                    </option>
                @endforeach
            </select>
            <noscript>
                <button type="submit" class="admin-submit">Anzeigen</button>
            </noscript>
        </form>

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
                {{ $answer }}
            </div>
        @endif

        @if ($selectedGameId <= 0)
            <p class="hint">Wähle oben eine Liga, um deren Spiele zu verwalten.</p>
        @elseif ($matchrounds === [])
            <p class="hint">Liga: <strong>{{ $selectedGameTitle }}</strong> — noch keine Spielrunden. Lege zuerst unter Spielrunden welche an.</p>
        @else
            <p class="hint">Liga: <strong>{{ $selectedGameTitle }}</strong></p>

            <form
                class="admin-form"
                method="post"
                action="{{ $mode === 'update' ? route('admin.matches.update', ['match' => $form['match_id']]) : route('admin.matches.store') }}"
                accept-charset="UTF-8"
            >
                @csrf
                @if ($mode === 'update')
                    @method('PUT')
                @endif

                <div class="admin-field">
                    <label for="match_round">* Spielrunde</label>
                    <select id="match_round" name="match_round" required>
                        <option value=""></option>
                        @foreach ($matchrounds as $round)
                            <option value="{{ $round['matchround_id'] }}" @selected((string) $form['match_round'] === (string) $round['matchround_id'])>
                                {{ $round['matchround_title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="match_date">* Datum</label>
                    <input id="match_date" type="date" name="match_date" value="{{ $form['match_date'] }}" required>
                </div>

                <div class="admin-field">
                    <label for="match_hometeam_id">* Heimteam</label>
                    <select id="match_hometeam_id" name="match_hometeam_id" required>
                        <option value=""></option>
                        @foreach ($teams as $team)
                            <option value="{{ $team['team_id'] }}" @selected((string) $form['match_hometeam_id'] === (string) $team['team_id'])>
                                {{ $team['team_label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="match_guestteam_id">* Gastteam</label>
                    <select id="match_guestteam_id" name="match_guestteam_id" required>
                        <option value=""></option>
                        @foreach ($teams as $team)
                            <option value="{{ $team['team_id'] }}" @selected((string) $form['match_guestteam_id'] === (string) $team['team_id'])>
                                {{ $team['team_label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="match_status">Status-Hinweis</label>
                    <input id="match_status" type="text" name="match_status" value="{{ $form['match_status'] }}" maxlength="255" placeholder="leer = in Ordnung">
                </div>

                <div class="admin-actions">
                    @if ($mode === 'update')
                        <button type="submit" class="admin-submit">Speichern</button>
                        <a class="admin-cancel" href="{{ route('admin.matches', ['game_id' => $selectedGameId]) }}">Abbrechen</a>
                    @else
                        <button type="submit" class="admin-submit">Hinzufügen</button>
                    @endif
                </div>
            </form>
        @endif
    </section>

    @if ($selectedGameId > 0)
        <section class="panel admin-main" aria-labelledby="admin-matches-list-title">
            <div class="section-head">
                <h2 id="admin-matches-list-title">Vorhandene Spiele</h2>
            </div>

            @forelse ($items as $item)
                <article class="admin-list-item">
                    <div class="admin-list-body">
                        <div class="admin-match-meta">
                            <img
                                src="{{ $legacyBase }}images/ffb/symbols/{{ $item['status_ok'] ? 'status_pos.png' : 'status_neg.png' }}"
                                alt="{{ $item['status_ok'] ? 'OK' : 'Hinweis' }}"
                                width="16"
                                height="16"
                                loading="lazy"
                            >
                            <time>{{ $item['match_date'] }}</time>
                            <span class="muted">({{ $item['match_round_title'] }})</span>
                        </div>
                        <h3 class="admin-list-title admin-match-teams">
                            <span class="admin-match-team">
                                @if ($item['home_flag_url'])
                                    <img src="{{ $item['home_flag_url'] }}" alt="" width="20" height="15" loading="lazy">
                                @endif
                                {{ $item['home_name'] }}
                            </span>
                            <span class="muted">:</span>
                            <span class="admin-match-team">
                                {{ $item['guest_name'] }}
                                @if ($item['guest_flag_url'])
                                    <img src="{{ $item['guest_flag_url'] }}" alt="" width="20" height="15" loading="lazy">
                                @endif
                            </span>
                        </h3>
                        @if ($item['match_status'] !== '')
                            <p class="muted">{{ $item['match_status'] }}</p>
                        @endif
                    </div>
                    <div class="admin-list-actions">
                        <a class="admin-icon-btn" href="{{ route('admin.matches.edit', ['match' => $item['match_id']]) }}" title="Bearbeiten">
                            <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                        </a>
                        <form method="post" action="{{ route('admin.matches.destroy', ['match' => $item['match_id']]) }}" onsubmit="return confirm('Dieses Spiel wirklich löschen?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="game_id" value="{{ $selectedGameId }}">
                            <button type="submit" class="admin-icon-btn" title="Löschen">
                                <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="muted">Noch keine Spiele für diese Liga.</p>
            @endforelse
        </section>
    @endif
@endsection
