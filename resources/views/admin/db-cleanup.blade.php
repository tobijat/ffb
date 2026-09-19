@extends('layouts.admin')

@section('title', 'DB Cleanup')

@section('content')
    @php
        $groups = $data['duplicate_playerteam_groups'] ?? [];
        $groupCount = (int) ($data['duplicate_playerteam_group_count'] ?? 0);
        $entryCount = (int) ($data['duplicate_playerteam_entry_count'] ?? 0);
        $playersWithoutTeam = $data['players_without_playerteam'] ?? [];
        $playersWithoutTeamCount = (int) ($data['players_without_playerteam_count'] ?? 0);
        $playersWithoutStats = $data['players_without_playerstats'] ?? [];
        $playersWithoutStatsCount = (int) ($data['players_without_playerstats_count'] ?? 0);
        $positions = ['g' => 'Tor', 'd' => 'Abwehr', 'm' => 'Mittelfeld', 's' => 'Angriff'];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-db-cleanup-title">
        <div class="section-head">
            <h2 id="admin-db-cleanup-title">DB Cleanup</h2>
        </div>
        <p class="hint">Werkzeuge zur Prüfung und Bereinigung der Datenbank.</p>
    </section>

    <section class="panel admin-main" aria-labelledby="admin-db-cleanup-dupes-title">
        <div class="section-head">
            <h2 id="admin-db-cleanup-dupes-title">Doppelte Kader-Einträge</h2>
        </div>
        <p class="hint">
            Spieler mit mehr als einem <code>ffb_playerteam</code>-Eintrag für dasselbe Team
            (gleiche <code>playerteam_player_id</code> und <code>playerteam_team_id</code>).
        </p>

        @if ($groupCount === 0)
            <p class="muted">Keine doppelten Spieler–Team-Zuordnungen gefunden.</p>
        @else
            <p class="muted">
                {{ $groupCount }} {{ $groupCount === 1 ? 'Doppelgruppe' : 'Doppelgruppen' }}
                · {{ $entryCount }} Einträge insgesamt
            </p>

            @foreach ($groups as $group)
                <article class="admin-list-item admin-db-cleanup-group">
                    <div class="admin-list-body">
                        <div class="admin-match-meta">
                            <span class="admin-squad-count">{{ $group['entry_count'] }}×</span>
                            <span class="muted">Spieler #{{ $group['player_id'] }}</span>
                            <span class="muted">Team #{{ $group['team_id'] }}</span>
                        </div>
                        <h3 class="admin-list-title">
                            {{ $group['player_lname'] }}{{ $group['player_fname'] !== '' ? ', '.$group['player_fname'] : '' }}
                            <span class="muted">@ {{ $group['team_name'] !== '' ? $group['team_name'] : ('Team #'.$group['team_id']) }}</span>
                        </h3>

                        <div class="admin-squad-table-wrap">
                            <table class="admin-squad-table">
                                <thead>
                                    <tr>
                                        <th scope="col">playerteam_id</th>
                                        <th scope="col">Pos.</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Transfer</th>
                                        <th scope="col">Bild</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['entries'] as $entry)
                                        <tr class="{{ (int) $entry['playerteam_status'] === 0 ? 'is-inactive' : '' }}">
                                            <td>#{{ $entry['playerteam_id'] }}</td>
                                            <td>{{ strtoupper($entry['playerteam_player_position']) }}
                                                <span class="muted">({{ $positions[$entry['playerteam_player_position']] ?? $entry['playerteam_player_position'] }})</span>
                                            </td>
                                            <td>{{ (int) $entry['playerteam_status'] === 1 ? 'aktiv' : 'inaktiv' }}</td>
                                            <td>{{ $entry['playerteam_date_transfer'] !== '' ? $entry['playerteam_date_transfer'] : '—' }}</td>
                                            <td>{{ $entry['playerteam_player_picture'] !== '' ? $entry['playerteam_player_picture'] : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <section class="panel admin-main" aria-labelledby="admin-db-cleanup-no-team-title">
        <div class="section-head">
            <h2 id="admin-db-cleanup-no-team-title">Spieler ohne Kader-Zuordnung</h2>
        </div>
        <p class="hint">
            Einträge in <code>ffb_player</code>, die in keinem <code>ffb_playerteam</code> vorkommen.
        </p>

        @if ($playersWithoutTeamCount === 0)
            <p class="muted">Alle Spieler sind mindestens einem Team zugeordnet.</p>
        @else
            <p class="muted">{{ $playersWithoutTeamCount }} Spieler</p>
            @include('admin.partials.db-cleanup-player-table', ['players' => $playersWithoutTeam])
        @endif
    </section>

    <section class="panel admin-main" aria-labelledby="admin-db-cleanup-no-stats-title">
        <div class="section-head">
            <h2 id="admin-db-cleanup-no-stats-title">Spieler ohne Spielstatistiken</h2>
        </div>
        <p class="hint">
            Spieler ohne Einträge in <code>ffb_playerstats</code>
            und ohne Verwendung in <code>ffb_userteam</code>
            (über <code>playerteam_id</code>-Slots; inkl. Spieler ohne Team).
        </p>

        @if ($playersWithoutStatsCount === 0)
            <p class="muted">Jeder Spieler hat mindestens eine Spielstatistik.</p>
        @else
            <p class="muted">{{ $playersWithoutStatsCount }} Spieler</p>
            @include('admin.partials.db-cleanup-player-table', ['players' => $playersWithoutStats])
        @endif
    </section>
@endsection
