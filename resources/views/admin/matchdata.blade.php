@extends('layouts.admin')

@section('title', 'Spieldaten')

@push('scripts')
    <script src="{{ url('js/admin-matchdata.js') }}?v=14" defer></script>
@endpush

@section('content')
    @php
        $leagues = $data['leagues'] ?? [];
        $selectedLeagueId = (int) ($data['selected_league_id'] ?? 0);
        $pointsmode = $data['pointsmode'] ?? 'new';
    @endphp

    <section
        class="panel admin-main admin-mp"
        aria-labelledby="admin-matchdata-title"
        data-rounds-url="{{ route('admin.matchdata.rounds') }}"
        data-matches-url-template="{{ url('/admin/matchdata/rounds') }}/__ID__/matches"
        data-players-url-template="{{ url('/admin/matchdata/matches') }}/__MATCH__/teams/__TEAM__/players"
        data-result-url-template="{{ url('/admin/matchdata/matches') }}/__ID__/result"
        data-save-player-url-template="{{ url('/admin/matchdata/matches') }}/__MATCH__/players/__PT__"
        data-scrape-url-template="{{ url('/admin/matchdata/matches') }}/__ID__/scrape"
        data-wf-proxy-url="{{ url('/admin/matchdata/wf-proxy') }}"
        data-csrf="{{ csrf_token() }}"
        data-images-base="{{ $legacyBase }}images/ffb/"
        data-pointsmode="{{ $pointsmode }}"
        data-has-game="{{ $selectedLeagueId > 0 ? '1' : '0' }}"
    >
        <div class="section-head">
            <h2 id="admin-matchdata-title">Spieldaten</h2>
        </div>

        <div class="admin-mp-select-row">
            <div class="admin-mp-select-field">
                <label for="admin-mp-league">Liga</label>
                <select id="admin-mp-league" class="admin-mp-select">
                    <option value="">— Liga wählen —</option>
                    @foreach ($leagues as $league)
                        <option value="{{ $league['league_id'] }}" @selected($selectedLeagueId === (int) $league['league_id'])>
                            {{ $league['league_title'] }}@if (!empty($league['league_archive'])) (Archiv)@endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-mp-select-field">
                <label for="admin-mp-round">Spielrunde</label>
                <select id="admin-mp-round" class="admin-mp-select" @disabled($selectedLeagueId <= 0)>
                    <option value="">— Runde wählen —</option>
                </select>
            </div>
            <div class="admin-mp-select-field">
                <label for="admin-mp-match">Spiel</label>
                <select id="admin-mp-match" class="admin-mp-select" disabled>
                    <option value="">— Spiel wählen —</option>
                </select>
            </div>
        </div>

        @if ($selectedLeagueId <= 0)
            <p class="hint">Wähle oben eine Liga, um Spielerpunkte zu erfassen.</p>
        @else
            <p class="hint">Punkte-Modus: <strong>{{ $pointsmode }}</strong></p>
        @endif

        <div class="admin-mp-result" id="admin-mp-result" hidden>
            <div class="admin-mp-result-row admin-mp-result-main">
                <div class="admin-mp-result-main-left">
                    <div class="admin-mp-result-teams">
                        <div class="admin-mp-result-side">
                            <span class="admin-mp-result-name" id="admin-mp-home-name">Heim</span>
                            <select id="admin-mp-homescore" aria-label="Heimtore"></select>
                        </div>
                        <span class="admin-mp-result-sep">:</span>
                        <div class="admin-mp-result-side">
                            <select id="admin-mp-guestscore" aria-label="Gasttore"></select>
                            <span class="admin-mp-result-name" id="admin-mp-guest-name">Gast</span>
                        </div>
                    </div>
                    <select id="admin-mp-match-minutes" class="admin-mp-select admin-mp-minutes-select" aria-label="Spieldauer" title="Spielzeit">
                        <option value="90">90 min</option>
                        <option value="120">120 min</option>
                    </select>
                </div>
                <div class="admin-actions admin-actions-flush admin-mp-savebar">
                    <button type="button" id="admin-mp-save" class="admin-submit" disabled>Änderungen speichern (0)</button>
                    <span class="muted" id="admin-mp-dirty-hint">Noch keine Änderungen</span>
                </div>
            </div>
            <div class="admin-mp-result-row admin-mp-result-penalty-row">
                <label class="admin-mp-penalty-check" for="admin-mp-penalty-enable">
                    <input type="checkbox" id="admin-mp-penalty-enable">
                    Elfmeterschießen
                </label>
                <div class="admin-mp-result-teams" id="admin-mp-penalty-row" hidden>
                    <div class="admin-mp-result-side">
                        <span class="admin-mp-result-name admin-mp-result-name-muted" id="admin-mp-home-name-ps">Heim</span>
                        <select id="admin-mp-homepenalty" aria-label="Heim Elfmeterschießen"></select>
                    </div>
                    <span class="admin-mp-result-sep">:</span>
                    <div class="admin-mp-result-side">
                        <select id="admin-mp-guestpenalty" aria-label="Gast Elfmeterschießen"></select>
                        <span class="admin-mp-result-name admin-mp-result-name-muted" id="admin-mp-guest-name-ps">Gast</span>
                    </div>
                </div>
            </div>
            <div class="admin-mp-result-row admin-mp-result-url">
                <label class="admin-mp-result-label" for="admin-mp-url">Externe Seite</label>
                <div class="admin-mp-url-row">
                    <input
                        type="url"
                        id="admin-mp-url"
                        class="admin-mp-url-input"
                        placeholder="https://www.weltfussball.at/spielbericht/…"
                        autocomplete="off"
                    >
                    <button type="button" id="admin-mp-scrape" class="admin-submit" disabled>Spieldaten laden</button>
                </div>
            </div>
            <iframe
                id="admin-mp-cf-frame"
                class="admin-mp-cf-frame is-hidden"
                title="Weltfussball Loader"
                sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
                aria-hidden="true"
            ></iframe>
            <p class="hint admin-mp-scrape-hint" id="admin-mp-scrape-hint" hidden></p>
        </div>

        <div class="admin-mp-legend" id="admin-mp-legend" hidden>
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_time.png" width="15" height="15" alt=""> Min
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_goal.gif" width="15" height="15" alt=""> Tore
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_assist.gif" width="15" height="15" alt=""> Assists
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_card_y.gif" width="15" height="18" alt=""> Karten
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_owngoal.gif" width="15" height="15" alt=""> ET
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_penaltylost.png" width="15" height="15" alt=""> Elfm. verschossen
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_penaltysaved.png" width="15" height="15" alt=""> Elfm. gehalten
            · PS save/lost/hit
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_hourglass_add.png" width="16" height="16" alt=""> rein
            <img src="{{ $legacyBase }}images/ffb/symbols/stats_hourglass_delete.png" width="16" height="16" alt=""> raus
        </div>

        <div id="admin-mp-loading" class="admin-mp-loading" hidden>Spieler werden geladen…</div>

        <div class="admin-mp-section" id="admin-mp-home-section" hidden>
            <h3 class="admin-mp-section-title" id="admin-mp-home-heading">Heim</h3>
            <div class="admin-mp-players" id="admin-mp-home-players"></div>
        </div>

        <div class="admin-mp-section" id="admin-mp-guest-section" hidden>
            <h3 class="admin-mp-section-title" id="admin-mp-guest-heading">Gast</h3>
            <div class="admin-mp-players" id="admin-mp-guest-players"></div>
        </div>
    </section>
@endsection
