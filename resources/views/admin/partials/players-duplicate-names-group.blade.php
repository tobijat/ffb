@php
    $teamPlayers = isset($group['players']) && is_array($group['players'])
        ? $group['players']
        : (isset($group['player']) ? [$group['player']] : []);
    $matches = is_array($group['matches'] ?? null) ? $group['matches'] : [];
    $teamSimilarities = is_array($group['team_similarities'] ?? null) ? $group['team_similarities'] : [];
    $matchCount = count($matches);

    $formatPlayerName = static function (array $player): string {
        $last = trim((string) ($player['player_lname'] ?? ''));
        $first = trim((string) ($player['player_fname'] ?? ''));
        if ($last !== '' && $first !== '') {
            return $last.', '.$first;
        }

        return $last !== '' ? $last : $first;
    };
@endphp
<article class="admin-list-item admin-duplicates-group">
    <div class="admin-list-body">
        <div class="admin-duplicates-group-meta">
            <span class="admin-squad-count">{{ count($teamPlayers) }} Team-Spieler</span>
            @if ($matchCount > 0)
                <span class="muted">{{ $matchCount }} Treffer außerhalb</span>
            @elseif ($teamSimilarities !== [])
                <span class="muted">nur Team-interne Ähnlichkeit</span>
            @endif
        </div>

        <section class="admin-duplicates-section" aria-label="Spieler im gewählten Team">
            <h3 class="admin-duplicates-section-title">Im Team „{{ $teamName }}“</h3>
            <ul class="admin-duplicates-player-list">
                @foreach ($teamPlayers as $teamPlayer)
                    <li class="admin-duplicates-player">
                        <div class="admin-duplicates-player-head">
                            <span class="admin-duplicates-player-name">{{ $formatPlayerName($teamPlayer) }}</span>
                            <span class="muted">#{{ $teamPlayer['player_id'] }}</span>
                        </div>

                        @if (trim((string) ($teamPlayer['player_uefa_id'] ?? '')) !== '' || trim((string) ($teamPlayer['player_fifa_id'] ?? '')) !== '')
                            <ul class="admin-duplicates-id-list">
                                @if (trim((string) ($teamPlayer['player_uefa_id'] ?? '')) !== '')
                                    <li>UEFA {{ $teamPlayer['player_uefa_id'] }}</li>
                                @endif
                                @if (trim((string) ($teamPlayer['player_fifa_id'] ?? '')) !== '')
                                    <li>FIFA {{ $teamPlayer['player_fifa_id'] }}</li>
                                @endif
                            </ul>
                        @endif

                        @if (($teamPlayer['entries'] ?? []) !== [])
                            <p class="admin-duplicates-label">Kader</p>
                            <ul class="admin-duplicates-squad-list">
                                @foreach ($teamPlayer['entries'] as $entry)
                                    <li class="{{ (int) ($entry['playerteam_status'] ?? 0) === 0 ? 'is-inactive' : '' }}">
                                        <span>{{ $entry['league_title'] !== '' ? $entry['league_title'] : ('Liga #'.$entry['playerteam_league_id']) }}</span>
                                        <span class="muted">·</span>
                                        <span>{{ strtoupper($entry['playerteam_player_position']) }}
                                            ({{ $positions[$entry['playerteam_player_position']] ?? $entry['playerteam_player_position'] }})</span>
                                        <span class="muted">·</span>
                                        <span>{{ (int) $entry['playerteam_status'] === 1 ? 'aktiv' : 'inaktiv' }}</span>
                                        <span class="muted">· #{{ $entry['playerteam_id'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="muted admin-duplicates-empty">kein Kader-Eintrag</p>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if ($teamSimilarities !== [])
                <p class="admin-duplicates-label">Ähnlichkeit im Team</p>
                <ul class="admin-duplicates-reason-list">
                    @foreach ($teamSimilarities as $similarity)
                        <li>
                            <span>{{ $formatPlayerName([
                                'player_fname' => $similarity['left_fname'],
                                'player_lname' => $similarity['left_lname'],
                            ]) }}</span>
                            <span class="muted">↔</span>
                            <span>{{ $formatPlayerName([
                                'player_fname' => $similarity['right_fname'],
                                'player_lname' => $similarity['right_lname'],
                            ]) }}</span>
                            <span class="admin-auto-squad-badge">{{ $similarity['reason'] }}</span>
                            <button
                                type="button"
                                class="admin-submit admin-duplicates-merge-start"
                                data-player-a="{{ (int) $similarity['left_player_id'] }}"
                                data-player-b="{{ (int) $similarity['right_player_id'] }}"
                            >Start merge</button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @if ($matchCount > 0)
            <section class="admin-duplicates-section" aria-label="Ähnliche Spieler">
                <h3 class="admin-duplicates-section-title">Ähnliche Spieler</h3>
                <ul class="admin-duplicates-player-list">
                    @foreach ($matches as $match)
                        <li class="admin-duplicates-player is-match">
                            <div class="admin-duplicates-player-head">
                                <span class="admin-duplicates-player-name">{{ $formatPlayerName($match) }}</span>
                                <span class="muted">#{{ $match['player_id'] }}</span>
                                @if (($match['reason'] ?? '') !== '')
                                    <span class="admin-auto-squad-badge">{{ $match['reason'] }}</span>
                                @endif
                            </div>

                            @if (trim((string) ($match['player_uefa_id'] ?? '')) !== '' || trim((string) ($match['player_fifa_id'] ?? '')) !== '')
                                <ul class="admin-duplicates-id-list">
                                    @if (trim((string) ($match['player_uefa_id'] ?? '')) !== '')
                                        <li>UEFA {{ $match['player_uefa_id'] }}</li>
                                    @endif
                                    @if (trim((string) ($match['player_fifa_id'] ?? '')) !== '')
                                        <li>FIFA {{ $match['player_fifa_id'] }}</li>
                                    @endif
                                </ul>
                            @endif

                            @if (($match['squads'] ?? []) !== [])
                                <p class="admin-duplicates-label">Kader</p>
                                <ul class="admin-duplicates-squad-list">
                                    @foreach ($match['squads'] as $squad)
                                        <li class="{{ (int) ($squad['playerteam_status'] ?? 0) === 0 ? 'is-inactive' : '' }}">
                                            <span>{{ $squad['team_name'] !== '' ? $squad['team_name'] : ('Team #'.$squad['team_id']) }}</span>
                                            <span class="muted">·</span>
                                            <span>{{ $squad['league_title'] !== '' ? $squad['league_title'] : ('Liga #'.$squad['league_id']) }}</span>
                                            <span class="muted">·</span>
                                            <span>{{ strtoupper($squad['playerteam_player_position']) }}
                                                ({{ $positions[$squad['playerteam_player_position']] ?? $squad['playerteam_player_position'] }})</span>
                                            <span class="muted">·</span>
                                            <span>{{ (int) $squad['playerteam_status'] === 1 ? 'aktiv' : 'inaktiv' }}</span>
                                            <span class="muted">· #{{ $squad['playerteam_id'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="muted admin-duplicates-empty">kein Kader</p>
                            @endif

                            <div class="admin-duplicates-merge-row">
                                @if (count($teamPlayers) > 1)
                                    <label class="admin-duplicates-merge-pick">
                                        <span class="muted">mit Team-Spieler</span>
                                        <select class="admin-duplicates-merge-team-player">
                                            @foreach ($teamPlayers as $teamPlayer)
                                                <option value="{{ (int) $teamPlayer['player_id'] }}">
                                                    {{ $formatPlayerName($teamPlayer) }} #{{ $teamPlayer['player_id'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </label>
                                @endif
                                <button
                                    type="button"
                                    class="admin-submit admin-duplicates-merge-start"
                                    data-player-a="{{ (int) ($teamPlayers[0]['player_id'] ?? 0) }}"
                                    data-player-b="{{ (int) $match['player_id'] }}"
                                    @if (count($teamPlayers) > 1) data-uses-team-select="1" @endif
                                >Start merge</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</article>
