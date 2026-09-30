@if ($count === 0)
    <p class="muted">{{ $summary }}</p>
@else
    <p class="muted">{{ $summary }}</p>
    <div class="admin-squad-table-wrap">
        <table class="admin-squad-table admin-db-cleanup-player-table">
            <thead>
                <tr>
                    <th scope="col">playerteam_id</th>
                    <th scope="col">player_id</th>
                    <th scope="col">Team</th>
                    <th scope="col">Liga</th>
                    <th scope="col">Pos.</th>
                    <th scope="col">Status</th>
                    <th scope="col">Transfer</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr class="{{ (int) $row['playerteam_status'] === 0 ? 'is-inactive' : '' }}">
                        <td>#{{ $row['playerteam_id'] }}</td>
                        <td>#{{ $row['playerteam_player_id'] }} <span class="muted">(fehlt)</span></td>
                        <td>
                            @if ($row['team_name'] !== '')
                                {{ $row['team_name'] }}
                                <span class="muted">#{{ $row['playerteam_team_id'] }}</span>
                            @else
                                #{{ $row['playerteam_team_id'] }}
                            @endif
                        </td>
                        <td>{{ (int) $row['playerteam_league_id'] > 0 ? '#'.$row['playerteam_league_id'] : '—' }}</td>
                        <td>{{ strtoupper($row['playerteam_player_position']) }}
                            <span class="muted">({{ $positions[$row['playerteam_player_position']] ?? $row['playerteam_player_position'] }})</span>
                        </td>
                        <td>{{ (int) $row['playerteam_status'] === 1 ? 'aktiv' : 'inaktiv' }}</td>
                        <td>{{ $row['playerteam_date_transfer'] !== '' ? $row['playerteam_date_transfer'] : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
