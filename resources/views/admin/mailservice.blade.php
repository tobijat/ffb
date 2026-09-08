@extends('layouts.admin')

@section('title', 'Mailservice')

@section('content')
    @php
        $games = $data['games'] ?? [];
        $mails = $data['mails'] ?? [];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-mailservice-title">
        <div class="section-head">
            <h2 id="admin-mailservice-title">Mailservice</h2>
        </div>
        <p class="hint">
            Empfänger nach Liga / Spielrunde, Userstatus (inkl. ohne Aufstellung bei gewählter Liga)
            und Mailservice-Opt-in filtern,
            Adressliste zusammenstellen und INFO-, REMINDER- oder FORCE-Mails versenden.
            Platzhalter: <code>{*nickname*}</code>
        </p>
        <div id="ms-answers" class="admin-ms-answers" aria-live="polite"></div>
    </section>

    <section
        class="panel admin-main admin-ms"
        aria-label="Mail verfassen"
        data-matchrounds-url="{{ route('admin.mailservice.matchrounds') }}"
        data-users-url="{{ route('admin.mailservice.users') }}"
        data-mail-url-template="{{ url('/admin/mailservice/mails') }}/__ID__"
        data-send-url="{{ route('admin.mailservice.send') }}"
        data-csrf="{{ csrf_token() }}"
        data-symbol-pos="{{ $legacyBase }}images/ffb/symbols/status_pos.png"
        data-symbol-neg="{{ $legacyBase }}images/ffb/symbols/status_neg.png"
        data-symbol-load="{{ $legacyBase }}images/ffb/symbols/change.png"
    >
        <div class="admin-ms-grid">
            <div class="admin-ms-left">
                <div class="admin-ms-filters">
                    <div class="admin-ms-filter">
                        <label for="ms-search-game">Game</label>
                        <select id="ms-search-game">
                            <option value="0">select Game..</option>
                            @foreach ($games as $game)
                                <option value="{{ $game['game_id'] }}">{{ $game['game_title'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-ms-filter" id="ms-search-matchround-wrap">
                        <label for="ms-search-matchround">Matchround</label>
                        <select id="ms-search-matchround" disabled>
                            <option value="0">select Matchround..</option>
                        </select>
                    </div>
                    <div class="admin-ms-filter">
                        <label for="ms-search-userstatus">Userstatus</label>
                        <select id="ms-search-userstatus">
                            <option value="">all Users</option>
                            <option value="active" selected>active Users</option>
                            <option value="inactive">inactive Users</option>
                            <option value="na">N/A Users</option>
                            <option value="no_lineup">never added a lineup</option>
                        </select>
                    </div>
                    <div class="admin-ms-filter">
                        <label for="ms-search-mstype">Mailservice</label>
                        <select id="ms-search-mstype">
                            <option value="">all Users</option>
                            <option value="info_reminder">with INFO &amp; REMINDER</option>
                            <option value="info">with INFO</option>
                            <option value="reminder">with REMINDER</option>
                            <option value="opted_out">without INFO &amp; REMINDER</option>
                        </select>
                    </div>
                    <div class="admin-actions admin-actions-flush">
                        <button type="button" class="admin-submit" id="ms-get-users">Get Users</button>
                    </div>
                </div>
                <div class="admin-ms-userlist" id="ms-search-userlist" aria-live="polite"></div>
            </div>

            <div class="admin-ms-right">
                <div class="admin-ms-address" id="ms-mail-to">Addresslist</div>
                <div class="admin-ms-compose-row">
                    <input id="ms-input-subject" type="text" value="Subject.." aria-label="Subject">
                    <select id="ms-select-mailtype" aria-label="Mailtype">
                        <option disabled selected value="">Mailtype..</option>
                        <option value="info">INFO</option>
                        <option value="reminder">REMINDER</option>
                        <option value="force">FORCE</option>
                    </select>
                </div>
                <textarea id="ms-input-text" rows="20" aria-label="Mail text">Text..</textarea>
                <div class="admin-actions admin-actions-flush admin-ms-send-wrap">
                    <button type="button" class="admin-submit" id="ms-send-mail">Send Mail</button>
                </div>
            </div>
        </div>
    </section>

    <section class="panel admin-main" aria-labelledby="admin-mailservice-history-title">
        <div class="section-head">
            <h2 id="admin-mailservice-history-title">Gesendete Mails</h2>
        </div>
        @if ($mails === [])
            <p class="muted">Noch keine Mails im Log.</p>
        @else
            <div class="admin-ms-history">
                <div class="admin-ms-history-head">
                    <span>Date</span>
                    <span>Subject</span>
                    <span>Reciepients</span>
                    <span>Type</span>
                    <span>Actions</span>
                </div>
                @foreach ($mails as $item)
                    <div class="admin-ms-history-row">
                        <span>{{ $item['mail_date'] }}</span>
                        <span title="{{ $item['mail_subject'] }}">{{ $item['mail_subject'] }}</span>
                        <span>
                            @if ((int) $item['mail_num_reciepients'] === 1)
                                {{ $item['mail_to'] }}
                            @else
                                {{ $item['mail_num_reciepients'] }} Empfänger
                            @endif
                        </span>
                        <span>{{ $item['mail_criteria'] }}</span>
                        <span>
                            <button
                                type="button"
                                class="admin-ms-load"
                                data-mail-id="{{ $item['mail_id'] }}"
                                title="Load Email"
                            >
                                <img src="{{ $legacyBase }}images/ffb/symbols/change.png" alt="" width="16" height="16">
                            </button>
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script src="{{ url('js/admin-mailservice.js') }}?v=2" defer></script>
@endpush
