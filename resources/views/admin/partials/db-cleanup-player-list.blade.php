@if ($count === 0)
    <p class="muted">{{ $emptyMessage }}</p>
@else
    <p class="muted">{{ $summary }}</p>
    @include('admin.partials.db-cleanup-player-table', ['players' => $players])
@endif
