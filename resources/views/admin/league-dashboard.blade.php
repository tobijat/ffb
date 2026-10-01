@extends('layouts.admin')

@section('title', 'League Dashboard')

@php
    $selectedLeagueId = (int) ($data['selected_league_id'] ?? 0);
    $sections = is_array($data['sections'] ?? null) ? $data['sections'] : [];
    $flashErrors = $errors ?: [];
@endphp

@section('content')
    @if (! empty($flashErrors))
        <div class="account-flash account-flash-error" role="alert">
            <strong>Es sind Fehler aufgetreten:</strong>
            <ul>
                @foreach ($flashErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($selectedLeagueId <= 0)
        <section class="panel admin-main">
            <p class="hint">Bitte zuerst unter <a href="{{ url('/admin') }}">Ligen</a> eine Liga auswählen.</p>
        </section>
    @else
        @foreach ($sections as $section)
            @php
                $ok = (bool) ($section['ok'] ?? false);
                $statusSrc = $ok
                    ? $legacyBase.'images/ffb/symbols/ok.png'
                    : $legacyBase.'images/ffb/symbols/delete.png';
                $statusLabel = $ok ? 'ok' : 'offen';
                $checklist = is_array($section['checklist'] ?? null) ? $section['checklist'] : [];
                $groups = is_array($section['groups'] ?? null) ? $section['groups'] : [];
                $sectionOpenAttr = $ok ? '' : ' open';
            @endphp
            <section class="panel admin-main admin-dashboard-section" data-section="{{ $section['key'] }}">
                <details class="admin-dashboard-details"{{ $sectionOpenAttr }}>
                    <summary class="admin-dashboard-summary">
                        <img
                            class="admin-dashboard-status"
                            src="{{ $statusSrc }}"
                            alt="{{ $statusLabel }}"
                            title="{{ $statusLabel }}"
                            width="16"
                            height="16"
                            loading="lazy"
                        >
                        <h2 class="admin-dashboard-title">{{ $section['title'] }}</h2>
                    </summary>
                    <div class="admin-dashboard-body">
                        @if ($checklist !== [])
                            <ul class="admin-dashboard-checklist">
                                @foreach ($checklist as $item)
                                    @php
                                        $itemOk = (bool) ($item['ok'] ?? false);
                                        $itemSrc = $itemOk
                                            ? $legacyBase.'images/ffb/symbols/ok.png'
                                            : $legacyBase.'images/ffb/symbols/delete.png';
                                        $itemLabel = $itemOk ? 'ja' : 'nein';
                                        $optionsOverview = is_array($item['options_overview'] ?? null)
                                            ? $item['options_overview']
                                            : [];
                                        $matchList = is_array($item['match_list'] ?? null)
                                            ? $item['match_list']
                                            : [];
                                        $matchListSummary = (string) ($item['match_list_summary'] ?? 'Betroffene Spiele');
                                        $infoList = is_array($item['info_list'] ?? null)
                                            ? $item['info_list']
                                            : [];
                                        $infoListSummary = (string) ($item['info_list_summary'] ?? 'Hinweise');
                                    @endphp
                                    <li class="admin-dashboard-check-item">
                                        <img
                                            class="admin-dashboard-check-icon"
                                            src="{{ $itemSrc }}"
                                            alt="{{ $itemLabel }}"
                                            title="{{ $itemLabel }}"
                                            width="14"
                                            height="14"
                                            loading="lazy"
                                        >
                                        <span>{{ $item['label'] }}: <strong>{{ $itemLabel }}</strong></span>

                                        @if ($optionsOverview !== [])
                                            <details class="admin-dashboard-options">
                                                <summary>Optionen-Übersicht</summary>
                                                <div class="admin-dashboard-options-body">
                                                    @foreach ($optionsOverview as $group)
                                                        <div class="admin-dashboard-options-group">
                                                            <h3>{{ $group['title'] }}</h3>
                                                            <dl>
                                                                @foreach (($group['items'] ?? []) as $row)
                                                                    <div class="admin-dashboard-options-row">
                                                                        <dt>{{ $row['label'] }}</dt>
                                                                        <dd>{{ $row['value'] !== '' ? $row['value'] : '—' }}</dd>
                                                                    </div>
                                                                @endforeach
                                                            </dl>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        @endif

                                        @if ($matchList !== [])
                                            <details class="admin-dashboard-options">
                                                <summary>{{ $matchListSummary }} ({{ count($matchList) }})</summary>
                                                <ul class="admin-dashboard-match-list">
                                                    @foreach ($matchList as $entry)
                                                        <li>
                                                            <span>{{ $entry['label'] }}</span>
                                                            @if (($entry['detail'] ?? '') !== '')
                                                                <span class="muted">— {{ $entry['detail'] }}</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </details>
                                        @endif

                                        @if ($infoList !== [])
                                            <details class="admin-dashboard-options">
                                                <summary>{{ $infoListSummary }} ({{ count($infoList) }})</summary>
                                                <ul class="admin-dashboard-match-list">
                                                    @foreach ($infoList as $entry)
                                                        @php
                                                            $entryLineup = is_array($entry['lineup'] ?? null)
                                                                ? $entry['lineup']
                                                                : [];
                                                        @endphp
                                                        <li>
                                                            <span>{{ $entry['label'] }}</span>
                                                            @if (($entry['detail'] ?? '') !== '')
                                                                <span class="muted">— {{ $entry['detail'] }}</span>
                                                            @endif
                                                            @if ($entryLineup !== [])
                                                                <ul class="admin-dashboard-lineup-list">
                                                                    @foreach ($entryLineup as $player)
                                                                        <li>
                                                                            <span>{{ $player['label'] }}</span>
                                                                            @if (($player['detail'] ?? '') !== '')
                                                                                <span class="muted">— {{ $player['detail'] }}</span>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </details>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($groups !== [])
                            <div class="admin-dashboard-groups">
                                @foreach ($groups as $group)
                                    @php
                                        $rounds = is_array($group['rounds'] ?? null) ? $group['rounds'] : [];
                                    @endphp
                                    <div class="admin-dashboard-group" data-group="{{ $group['key'] }}">
                                        <h3 class="admin-dashboard-group-title">
                                            {{ $group['title'] }}
                                            <span class="muted">({{ count($rounds) }})</span>
                                        </h3>

                                        @if ($rounds === [])
                                            <p class="hint">Keine Spielrunden.</p>
                                        @else
                                            <ul class="admin-dashboard-rounds">
                                                @foreach ($rounds as $round)
                                                    @php
                                                        $roundHasMatches = (bool) ($round['has_matches'] ?? false);
                                                        $roundActive = (bool) ($round['active'] ?? false);
                                                        $hasLineupOptions = (bool) ($round['has_lineup_options'] ?? false);
                                                        $lineupOptions = is_array($round['lineup_options'] ?? null)
                                                            ? $round['lineup_options']
                                                            : [];
                                                    @endphp
                                                    <li class="admin-dashboard-round">
                                                        <div class="admin-dashboard-round-head">
                                                            <strong>{{ $round['title'] }}</strong>
                                                            <span class="muted">
                                                                {{ $round['startdate'] }}
                                                                @if (($round['enddate'] ?? '') !== '')
                                                                    – {{ $round['enddate'] }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="admin-dashboard-round-meta">
                                                            <span>Spiele: {{ (int) ($round['match_count'] ?? 0) }} ({{ $roundHasMatches ? 'ja' : 'nein' }})</span>
                                                            <span>Status: {{ $roundActive ? 'aktiv' : 'inaktiv' }}</span>
                                                            <span>Runden-Optionen: {{ $hasLineupOptions ? 'ja' : 'nein' }}</span>
                                                        </div>
                                                        @if ($hasLineupOptions && $lineupOptions !== [])
                                                            <details class="admin-dashboard-options">
                                                                <summary>Aufstellungslimits (Runde)</summary>
                                                                <div class="admin-dashboard-options-body">
                                                                    @foreach ($lineupOptions as $optionsGroup)
                                                                        <div class="admin-dashboard-options-group">
                                                                            <h3>{{ $optionsGroup['title'] }}</h3>
                                                                            <dl>
                                                                                @foreach (($optionsGroup['items'] ?? []) as $row)
                                                                                    <div class="admin-dashboard-options-row">
                                                                                        <dt>{{ $row['label'] }}</dt>
                                                                                        <dd>{{ $row['value'] !== '' ? $row['value'] : '—' }}</dd>
                                                                                    </div>
                                                                                @endforeach
                                                                            </dl>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </details>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </details>
            </section>
        @endforeach
    @endif
@endsection
