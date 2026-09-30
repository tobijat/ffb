@if ($count === 0)
    <p class="muted">{{ $summary }}</p>
@else
    <p class="muted">{{ $summary }}</p>

    @foreach ($groups as $group)
        @if (count($group['rows']) === 0)
            @continue
        @endif

        <h3 class="admin-db-cleanup-subtitle">{{ $group['label'] }} <span class="muted">({{ $group['table'] }} · {{ count($group['rows']) }})</span></h3>
        <div class="admin-squad-table-wrap">
            <table class="admin-squad-table admin-db-cleanup-player-table">
                <thead>
                    <tr>
                        <th scope="col">{{ $group['id_column'] }}</th>
                        <th scope="col">{{ $group['column'] }}</th>
                        @foreach ($group['context_columns'] as $contextColumn)
                            <th scope="col">{{ $contextColumn }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group['rows'] as $row)
                        <tr>
                            <td>#{{ $row['row_id'] }}</td>
                            <td>#{{ $row['playerteam_id'] }} <span class="muted">(fehlt)</span></td>
                            @foreach ($group['context_columns'] as $contextColumn)
                                @php $value = $row[$contextColumn] ?? null; @endphp
                                <td>
                                    @if ($value === null || $value === '')
                                        —
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
@endif
