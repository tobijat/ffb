<p class="muted">{{ $summary }}</p>

@if (count($userteamSlots) === 0 && count($extremeSlots) === 0)
    {{-- summary already covers empty state --}}
@else
    @if (count($userteamSlots) > 0)
        <h3 class="admin-db-cleanup-subtitle">Userteam-Slots ({{ count($userteamSlots) }})</h3>
        <div class="admin-squad-table-wrap">
            <table class="admin-squad-table admin-db-cleanup-player-table">
                <thead>
                    <tr>
                        <th scope="col">userteam_id</th>
                        <th scope="col">user_id</th>
                        <th scope="col">matchround_id</th>
                        <th scope="col">Slot</th>
                        <th scope="col">playerteam_id</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userteamSlots as $row)
                        <tr>
                            <td>#{{ $row['userteam_id'] }}</td>
                            <td>{{ (int) $row['user_id'] > 0 ? '#'.$row['user_id'] : '—' }}</td>
                            <td>{{ (int) $row['matchround_id'] > 0 ? '#'.$row['matchround_id'] : '—' }}</td>
                            <td>{{ $row['slot'] }}</td>
                            <td>#{{ $row['playerteam_id'] }} <span class="muted">(fehlt)</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if (count($extremeSlots) > 0)
        <h3 class="admin-db-cleanup-subtitle">Top/Flop-Slots ({{ count($extremeSlots) }})</h3>
        <div class="admin-squad-table-wrap">
            <table class="admin-squad-table admin-db-cleanup-player-table">
                <thead>
                    <tr>
                        <th scope="col">extremeteam_id</th>
                        <th scope="col">Typ</th>
                        <th scope="col">Spielrunde</th>
                        <th scope="col">Slot</th>
                        <th scope="col">playerteam_id</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($extremeSlots as $row)
                        <tr>
                            <td>#{{ $row['extremeteam_id'] }}</td>
                            <td>{{ $row['type'] !== '' ? $row['type'] : '—' }}</td>
                            <td>
                                @if ($row['matchround_title'] !== '')
                                    {{ $row['matchround_title'] }}
                                    <span class="muted">#{{ $row['matchround_id'] }}</span>
                                @elseif ((int) $row['matchround_id'] > 0)
                                    #{{ $row['matchround_id'] }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $row['slot'] }}</td>
                            <td>#{{ $row['playerteam_id'] }} <span class="muted">(fehlt)</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif
