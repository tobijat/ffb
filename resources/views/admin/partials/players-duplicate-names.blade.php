@if ($groupCount === 0)
    <p class="muted">{{ $summary }}</p>
@else
    <p class="muted">{{ $summary }}</p>

    @foreach ($groups as $group)
        @include('admin.partials.players-duplicate-names-group', [
            'group' => $group,
            'teamName' => $teamName,
            'positions' => $positions,
        ])
    @endforeach
@endif
