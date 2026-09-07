@extends('layouts.admin')

@section('title', 'Spieler')

@section('content')
    @php
        $form = $data['form'];
        $mode = $data['mode'];
        $items = $data['items'];
        $countries = $data['countries'];
        $filters = $data['filters'];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
        $filterQuery = array_filter([
            'q' => $filters['q'] !== '' ? $filters['q'] : null,
            'nationality' => $filters['nationality'] !== '' ? $filters['nationality'] : null,
        ], static fn ($v) => $v !== null);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-players-title">
        <div class="section-head">
            <h2 id="admin-players-title">Spieler</h2>
        </div>

        @if (!empty($flashErrors))
            <div class="account-flash account-flash-error" role="alert">
                <strong>Es sind Fehler aufgetreten:</strong>
                <ul>
                    @foreach ($flashErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($answer)
            <div class="account-flash account-flash-ok" role="status">
                {{ $answer }}
            </div>
        @endif

        <form
            class="admin-form"
            method="post"
            action="{{ $mode === 'update' ? route('admin.players.update', ['player' => $form['player_id']] + $filterQuery) : route('admin.players.store', $filterQuery) }}"
            accept-charset="UTF-8"
        >
            @csrf
            @if ($mode === 'update')
                @method('PUT')
            @endif
            @foreach ($filterQuery as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <div class="admin-field">
                <label for="player_fname">* Vorname</label>
                <input id="player_fname" type="text" name="player_fname" value="{{ $form['player_fname'] }}" maxlength="255" required>
            </div>

            <div class="admin-field">
                <label for="player_lname">* Nachname</label>
                <input id="player_lname" type="text" name="player_lname" value="{{ $form['player_lname'] }}" maxlength="255" required>
            </div>

            <div class="admin-field">
                <label for="player_nationality">Nationalität</label>
                <select id="player_nationality" name="player_nationality">
                    <option value="">— optional —</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" @selected((string) $form['player_nationality'] === (string) $code)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="admin-field">
                <label for="player_status">Status</label>
                <select id="player_status" name="player_status">
                    <option value="1" @selected((int) $form['player_status'] === 1)>aktiv</option>
                    <option value="0" @selected((int) $form['player_status'] === 0)>inaktiv</option>
                </select>
            </div>

            <div class="admin-field">
                <label for="player_status_description">Status-Hinweis</label>
                <input id="player_status_description" type="text" name="player_status_description" value="{{ $form['player_status_description'] }}" maxlength="255" placeholder="z. B. verletzt, gesperrt">
            </div>

            <div class="admin-field">
                <label for="player_foreign_id">TM-ID (transfermarkt.at)</label>
                <input id="player_foreign_id" type="text" name="player_foreign_id" value="{{ $form['player_foreign_id'] }}" maxlength="255" placeholder="optional">
            </div>

            <div class="admin-actions">
                @if ($mode === 'update')
                    <button type="submit" class="admin-submit">Speichern</button>
                    <a class="admin-cancel" href="{{ route('admin.players', $filterQuery) }}">Abbrechen</a>
                @else
                    <button type="submit" class="admin-submit">Hinzufügen</button>
                @endif
            </div>
        </form>
    </section>

    <section class="panel admin-main" aria-labelledby="admin-players-list-title">
        <div class="section-head">
            <h2 id="admin-players-list-title">Vorhandene Spieler</h2>
        </div>

        <form class="admin-filter-bar" method="get" action="{{ route('admin.players') }}">
            <div class="admin-field">
                <label for="player_filter_q">Name</label>
                <input id="player_filter_q" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Vor- oder Nachname" autocomplete="off">
            </div>
            <div class="admin-field">
                <label for="player_filter_nationality">Nationalität</label>
                <select id="player_filter_nationality" name="nationality">
                    <option value="">— alle —</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" @selected($filters['nationality'] === (string) $code)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-actions admin-actions-flush">
                <button type="submit" class="admin-submit">Filtern</button>
                @if ($filters['q'] !== '' || $filters['nationality'] !== '')
                    <a class="admin-cancel" href="{{ route('admin.players') }}">Zurücksetzen</a>
                @endif
            </div>
        </form>

        <p class="muted">
            {{ $items->total() }} Treffer
            @if ($items->total() > 0)
                — Seite {{ $items->currentPage() }} von {{ $items->lastPage() }}
            @endif
        </p>

        @forelse ($items as $item)
            <article class="admin-list-item">
                <div class="admin-list-body">
                    <div class="admin-match-meta">
                        <img
                            src="{{ $legacyBase }}images/ffb/symbols/{{ $item['player_status'] ? 'status_pos.png' : 'status_neg.png' }}"
                            alt="{{ $item['player_status'] ? 'aktiv' : 'inaktiv' }}"
                            width="16"
                            height="16"
                            loading="lazy"
                        >
                        @if ($item['flag_url'])
                            <img src="{{ $item['flag_url'] }}" alt="" width="20" height="15" loading="lazy">
                        @endif
                        <span class="muted">#{{ $item['player_id'] }}</span>
                        @if ($item['player_nationality_label'] !== '')
                            <span class="muted">{{ $item['player_nationality_label'] }}</span>
                        @endif
                        @if ($item['player_foreign_id'] !== '')
                            <span class="muted">TM {{ $item['player_foreign_id'] }}</span>
                        @endif
                    </div>
                    <h3 class="admin-list-title">{{ $item['player_fname'] }} {{ $item['player_lname'] }}</h3>
                    @if ($item['player_status_description'] !== '')
                        <p class="muted">{{ $item['player_status_description'] }}</p>
                    @endif
                </div>
                <div class="admin-list-actions">
                    <a class="admin-icon-btn" href="{{ route('admin.players.edit', ['player' => $item['player_id']] + $filterQuery) }}" title="Bearbeiten">
                        <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                    </a>
                    <form method="post" action="{{ route('admin.players.destroy', ['player' => $item['player_id']] + $filterQuery) }}" onsubmit="return confirm('Diesen Spieler wirklich löschen?');">
                        @csrf
                        @method('DELETE')
                        @foreach ($filterQuery as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" class="admin-icon-btn" title="Löschen">
                            <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">Keine Spieler gefunden.</p>
        @endforelse

        @if ($items->hasPages())
            <nav class="admin-pagination" aria-label="Spieler-Seiten">
                @if ($items->onFirstPage())
                    <span class="muted">Zurück</span>
                @else
                    <a href="{{ $items->previousPageUrl() }}">Zurück</a>
                @endif
                <span class="muted">{{ $items->currentPage() }} / {{ $items->lastPage() }}</span>
                @if ($items->hasMorePages())
                    <a href="{{ $items->nextPageUrl() }}">Weiter</a>
                @else
                    <span class="muted">Weiter</span>
                @endif
            </nav>
        @endif
    </section>
@endsection
