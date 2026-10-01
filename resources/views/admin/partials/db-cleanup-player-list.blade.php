@php
    $showTeams = (bool) ($showTeams ?? false);
    $allowDelete = (bool) ($allowDelete ?? false);
    $deleteTask = (string) ($deleteTask ?? '');
@endphp
@if ($count === 0)
    <p class="muted">{{ $emptyMessage }}</p>
@else
    <p class="muted">{{ $summary }}</p>
    @include('admin.partials.db-cleanup-player-table', [
        'players' => $players,
        'showTeams' => $showTeams,
    ])
    @if ($allowDelete && $deleteTask !== '')
        <div class="admin-db-cleanup-actions">
            <button
                type="button"
                class="admin-submit admin-submit-danger"
                data-delete-task="{{ $deleteTask }}"
                data-delete-count="{{ $count }}"
            >Delete</button>
        </div>
    @endif
@endif
