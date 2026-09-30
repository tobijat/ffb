@if ($groupCount === 0)
    <p class="muted">{{ $summary }}</p>
@else
    <p class="muted">{{ $summary }}</p>

    @foreach ($groups as $group)
        <article class="admin-list-item admin-db-cleanup-group">
            <div class="admin-list-body">
                <div class="admin-match-meta">
                    <span class="admin-squad-count">{{ $group['entry_count'] }}×</span>
                    <span class="muted">Spieler #{{ $group['player_id'] }}</span>
                    <span class="muted">Team #{{ $group['team_id'] }}</span>
                    @if (($group['league_id'] ?? null) !== null)
                        <span class="muted">Liga #{{ $group['league_id'] }}</span>
                    @endif
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
