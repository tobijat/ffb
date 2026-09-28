<form class="admin-form admin-league-picker" method="get" action="{{ route('admin.squad') }}" accept-charset="UTF-8">
    @if ($tab !== 'roster')
        <input type="hidden" name="tab" value="{{ $tab }}">
    @endif
    @if ($squadLeagueId > 0)
        <input type="hidden" name="squad_league_id" value="{{ $squadLeagueId }}">
    @endif
    <div class="admin-field">
        <label for="{{ $teamSelectId ?? 'team_id' }}">Team</label>
        <select
            id="{{ $teamSelectId ?? 'team_id' }}"
            name="team_id"
            onchange="this.form.submit()"
            @disabled($squadLeagueId <= 0 || ($teams ?? []) === [])
        >
            <option value="">— Team wählen —</option>
            @foreach ($teams as $team)
                <option value="{{ $team['team_id'] }}" @selected($selectedTeamId === (int) $team['team_id'])>
                    {{ $team['team_label'] }} ({{ (int) ($team['active_count'] ?? 0) }})
                </option>
            @endforeach
        </select>
    </div>
    <noscript>
        <div class="admin-actions">
            <button type="submit" class="admin-submit">Anzeigen</button>
        </div>
    </noscript>
</form>
@if ($selectedTeamId <= 0)
    <p class="hint">Bitte ein Team wählen.</p>
@endif
