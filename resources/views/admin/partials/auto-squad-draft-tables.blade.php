@php
    $isUefaAuto = ($autoSourceLabel ?? '') === 'UEFA';
    $isFifaAuto = ($autoSourceLabel ?? '') === 'FIFA';
    $externalIdLabel = $isUefaAuto ? 'UEFA-ID' : ($isFifaAuto ? 'FIFA-ID' : 'TM-ID');
@endphp
                        @if (count($autoPlayers) > 0)
                            <div class="admin-auto-squad-table-wrap">
                                <table class="admin-auto-squad-table" id="admin-auto-squad-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vorname *</th>
                                            <th>Nachname *</th>
                                            <th>Nat.</th>
                                            <th>{{ $externalIdLabel }}</th>
                                            <th>Pos. *</th>
                                            <th>Kader-Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($autoPlayers as $index => $player)
                                            @php
                                                $isNew = (bool) ($player['is_new'] ?? false);
                                                $onSquad = (bool) ($player['on_squad'] ?? false);
                                                $notInJson = (bool) ($player['not_in_json'] ?? false);
                                                $playerId = (int) ($player['player_id'] ?? 0);
                                                $canEditIdentity = $isNew && ! $onSquad;
                                                $rowClass = 'admin-auto-squad-row';
                                                if ($notInJson) {
                                                    $rowClass .= ' is-not-in-json is-on-squad';
                                                } elseif ($onSquad) {
                                                    $rowClass .= ' is-on-squad';
                                                } elseif ($isNew) {
                                                    $rowClass .= ' is-new';
                                                } else {
                                                    $rowClass .= ' is-existing';
                                                }
                                                $rowPayload = [
                                                    'player_id' => $playerId,
                                                    'playerteam_id' => (int) ($player['playerteam_id'] ?? 0),
                                                    'is_new' => $isNew ? '1' : '0',
                                                    'on_squad' => $onSquad ? '1' : '0',
                                                    'not_in_json' => $notInJson ? '1' : '0',
                                                    'json_number' => $player['json_number'] ?? 0,
                                                    'json_name' => (string) ($player['json_name'] ?? ''),
                                                    'player_status' => 1,
                                                    'player_status_description' => '',
                                                    'player_fname' => (string) ($player['player_fname'] ?? ''),
                                                    'player_lname' => (string) ($player['player_lname'] ?? ''),
                                                    'player_nationality' => (string) ($player['player_nationality'] ?? ''),
                                                    'player_foreign_id' => (string) ($player['player_foreign_id'] ?? ''),
                                                    'player_uefa_id' => (string) ($player['player_uefa_id'] ?? ''),
                                                    'player_fifa_id' => (string) ($player['player_fifa_id'] ?? ''),
                                                    'playerteam_player_position' => (string) ($player['playerteam_player_position'] ?? ''),
                                                    'playerteam_status' => (int) ($player['playerteam_status'] ?? 1),
                                                ];
                                            @endphp
                                            <tr class="{{ $rowClass }}" data-row='@json($rowPayload)'>
                                                <td class="admin-auto-squad-num">
                                                    @if ($notInJson)
                                                        <span class="muted">—</span>
                                                        <span class="admin-auto-squad-badge" title="Aktiv im Kader, aber nicht in {{ $autoSourceLabel }}">nicht in {{ $autoSourceLabel }}</span>
                                                    @else
                                                        {{ $player['json_number'] ?? '' }}
                                                        @if ($onSquad)
                                                            <span class="admin-auto-squad-badge" title="Bereits im Kader">im Kader</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($canEditIdentity)
                                                        <input
                                                            type="text"
                                                            class="admin-auto-squad-fname"
                                                            value="{{ $player['player_fname'] ?? '' }}"
                                                            maxlength="255"
                                                            required
                                                            aria-label="Vorname {{ $index + 1 }}"
                                                        >
                                                    @else
                                                        <span class="admin-auto-squad-readonly">{{ $player['player_fname'] ?? '' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($canEditIdentity)
                                                        <input
                                                            type="text"
                                                            class="admin-auto-squad-lname"
                                                            value="{{ $player['player_lname'] ?? '' }}"
                                                            maxlength="255"
                                                            required
                                                            aria-label="Nachname {{ $index + 1 }}"
                                                        >
                                                    @else
                                                        <span class="admin-auto-squad-readonly">{{ $player['player_lname'] ?? '' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($canEditIdentity)
                                                        <select class="admin-auto-squad-nationality" aria-label="Nationalität {{ $index + 1 }}">
                                                            <option value=""></option>
                                                            @foreach ($countries as $code => $name)
                                                                <option value="{{ $code }}" @selected(($player['player_nationality'] ?? '') === $code)>{{ $code }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <span class="admin-auto-squad-readonly">{{ $player['player_nationality'] ?? '' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($isUefaAuto)
                                                        <span class="admin-auto-squad-readonly">{{ ($player['player_uefa_id'] ?? '') !== '' ? $player['player_uefa_id'] : '—' }}</span>
                                                    @elseif ($isFifaAuto)
                                                        <span class="admin-auto-squad-readonly">{{ ($player['player_fifa_id'] ?? '') !== '' ? $player['player_fifa_id'] : '—' }}</span>
                                                    @elseif ($canEditIdentity)
                                                        <input
                                                            type="text"
                                                            class="admin-auto-squad-foreign-id"
                                                            value="{{ $player['player_foreign_id'] ?? '' }}"
                                                            maxlength="255"
                                                            placeholder="TM-ID"
                                                            aria-label="TM-ID {{ $index + 1 }}"
                                                        >
                                                    @else
                                                        <span class="admin-auto-squad-readonly">{{ $player['player_foreign_id'] ?: '—' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select class="admin-auto-squad-position" required aria-label="Position {{ $index + 1 }}">
                                                        @foreach ($positions as $code => $label)
                                                            <option value="{{ $code }}" @selected(($player['playerteam_player_position'] ?? '') === $code)>{{ strtoupper($code) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="admin-auto-squad-status" aria-label="Kader-Status {{ $index + 1 }}">
                                                        <option value="1" @selected((int) ($player['playerteam_status'] ?? 1) === 1)>aktiv</option>
                                                        <option value="0" @selected((int) ($player['playerteam_status'] ?? 1) === 0)>inaktiv</option>
                                                    </select>
                                                </td>
                                                <td class="admin-auto-squad-actions">
                                                    <button
                                                        type="button"
                                                        class="admin-icon-btn admin-auto-squad-discard"
                                                        title="Zeile verwerfen"
                                                        aria-label="Zeile verwerfen"
                                                    >
                                                        <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="" width="16" height="16">
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if (count($autoAlmost) > 0)
                            <div class="admin-auto-squad-almost">
                                <h3>Mögliche Namens-Übereinstimmungen</h3>
                                <p class="hint">
                                    Haken bei <strong>Übernehmen</strong>: bestehenden DB-Spieler verwenden.
                                    Ohne Haken: neuen Spieler aus {{ $autoSourceLabel }}-Daten anlegen.
                                </p>
                                <div class="admin-auto-squad-table-wrap">
                                    <table class="admin-auto-squad-almost-table" id="admin-auto-squad-almost-table">
                                        <thead>
                                            <tr>
                                                <th>Übernehmen</th>
                                                <th>{{ $autoSourceLabel }}</th>
                                                <th>Datenbank</th>
                                                <th>Pos. *</th>
                                                <th>Kader-Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($autoAlmost as $index => $row)
                                                @php
                                                    $jsonName = trim(($row['json_fname'] ?? '').' '.($row['json_lname'] ?? ''));
                                                    if ($jsonName === '') {
                                                        $jsonName = (string) ($row['json_name'] ?? '');
                                                    }
                                                    $dbName = trim(($row['db_fname'] ?? '').' '.($row['db_lname'] ?? ''));
                                                    $jsonPos = strtoupper((string) ($row['json_position'] ?? ''));
                                                    $dbPos = strtoupper((string) ($row['db_position'] ?? ''));
                                                    $dbSquads = is_array($row['db_squads'] ?? null) ? $row['db_squads'] : [];
                                                    $almostPayload = [
                                                        'use_existing' => ! empty($row['use_existing']) ? '1' : '0',
                                                        'match_reason' => (string) ($row['match_reason'] ?? ''),
                                                        'json_number' => $row['json_number'] ?? 0,
                                                        'json_name' => (string) ($row['json_name'] ?? ''),
                                                        'json_fname' => (string) ($row['json_fname'] ?? ''),
                                                        'json_lname' => (string) ($row['json_lname'] ?? ''),
                                                        'json_nationality' => (string) ($row['json_nationality'] ?? ''),
                                                        'json_position' => (string) ($row['json_position'] ?? ''),
                                                        'db_player_id' => (int) ($row['db_player_id'] ?? 0),
                                                        'db_fname' => (string) ($row['db_fname'] ?? ''),
                                                        'db_lname' => (string) ($row['db_lname'] ?? ''),
                                                        'db_nationality' => (string) ($row['db_nationality'] ?? ''),
                                                        'db_position' => (string) ($row['db_position'] ?? ''),
                                                        'db_foreign_id' => (string) ($row['db_foreign_id'] ?? ''),
                                                        'player_uefa_id' => (string) ($row['player_uefa_id'] ?? ''),
                                                        'player_fifa_id' => (string) ($row['player_fifa_id'] ?? ''),
                                                        'db_squads' => $dbSquads,
                                                        'playerteam_player_position' => (string) ($row['playerteam_player_position'] ?? ''),
                                                        'playerteam_status' => (int) ($row['playerteam_status'] ?? 1),
                                                    ];
                                                @endphp
                                                <tr class="admin-auto-squad-almost-row" data-row='@json($almostPayload)'>
                                                    <td class="admin-auto-squad-almost-check">
                                                        <label>
                                                            <input
                                                                type="checkbox"
                                                                class="admin-auto-squad-almost-use-existing"
                                                                value="1"
                                                                @checked(! empty($row['use_existing']))
                                                            >
                                                            <span>Übernehmen</span>
                                                        </label>
                                                        @if (! empty($row['match_reason']))
                                                            <span class="muted admin-auto-squad-almost-reason">{{ $row['match_reason'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="admin-auto-squad-almost-side">
                                                            <strong>{{ $jsonName }}</strong>
                                                            <span>{{ $row['json_nationality'] ?? '' }}</span>
                                                            <span>{{ $jsonPos !== '' ? $jsonPos : '—' }}</span>
                                                            @if ($isUefaAuto && ($row['player_uefa_id'] ?? '') !== '')
                                                                <span class="muted">UEFA {{ $row['player_uefa_id'] }}</span>
                                                            @elseif ($isFifaAuto && ($row['player_fifa_id'] ?? '') !== '')
                                                                <span class="muted">FIFA {{ $row['player_fifa_id'] }}</span>
                                                            @else
                                                                <span class="muted">—</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="admin-auto-squad-almost-side">
                                                            <strong>{{ $dbName }}</strong>
                                                            <span>{{ $row['db_nationality'] ?? '' }}</span>
                                                            <span>{{ $dbPos !== '' ? $dbPos : '—' }}</span>
                                                            <span class="muted">
                                                                @if (count($dbSquads) > 0)
                                                                    {{ implode(', ', $dbSquads) }}
                                                                @else
                                                                    keine Kader
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="admin-auto-squad-almost-position" required aria-label="Position Ähnlichkeit {{ $index + 1 }}">
                                                            @foreach ($positions as $code => $label)
                                                                <option value="{{ $code }}" @selected(($row['playerteam_player_position'] ?? '') === $code)>{{ strtoupper($code) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="admin-auto-squad-almost-status" aria-label="Kader-Status Ähnlichkeit {{ $index + 1 }}">
                                                            <option value="1" @selected((int) ($row['playerteam_status'] ?? 1) === 1)>aktiv</option>
                                                            <option value="0" @selected((int) ($row['playerteam_status'] ?? 1) === 0)>inaktiv</option>
                                                        </select>
                                                    </td>
                                                    <td class="admin-auto-squad-actions">
                                                        <button
                                                            type="button"
                                                            class="admin-icon-btn admin-auto-squad-almost-discard"
                                                            title="Zeile verwerfen"
                                                            aria-label="Zeile verwerfen"
                                                        >
                                                            <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="" width="16" height="16">
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @php
                            $submitCount = count($autoPlayers) + count($autoAlmost);
                        @endphp
                        <div class="admin-actions">
                            <button type="submit" class="admin-submit" id="admin-auto-squad-submit" @disabled($submitCount <= 0)>
                                Alle übernehmen ({{ $submitCount }})
                            </button>
                        </div>
