<div class="admin-squad-table-wrap">
    <table class="admin-squad-table admin-db-cleanup-player-table">
        <thead>
            <tr>
                <th scope="col">player_id</th>
                <th scope="col">Name</th>
                <th scope="col">Nationalität</th>
                <th scope="col">Status</th>
                <th scope="col">TM-ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr class="{{ (int) $player['player_status'] === 0 ? 'is-inactive' : '' }}">
                    <td>#{{ $player['player_id'] }}</td>
                    <td>
                        <strong>{{ $player['player_lname'] }}</strong>
                        @if ($player['player_fname'] !== '')
                            {{ $player['player_fname'] }}
                        @endif
                    </td>
                    <td>{{ $player['player_nationality'] !== '' ? $player['player_nationality'] : '—' }}</td>
                    <td>{{ (int) $player['player_status'] === 1 ? 'aktiv' : 'inaktiv' }}</td>
                    <td>{{ $player['player_foreign_id'] !== '' ? $player['player_foreign_id'] : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
