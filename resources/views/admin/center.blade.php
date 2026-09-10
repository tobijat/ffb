@extends('layouts.admin')

@section('title', 'Admin Center')

@section('content')
    @php
        $leagues = $data['leagues'] ?? [];
        $selectedId = (int) ($data['selected_league_id'] ?? 0);
        $flashErrors = $errors ?: [];
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-title">
        <div class="section-head">
            <h2 id="admin-title">Admin Center</h2>
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

        <p class="hint">
            @if ($selectedId === 0)
                Klick eine Liga an, um sie für die Verwaltung auszuwählen.
            @else
                Die markierte Liga ist ausgewählt. Weitere Funktionen über die Navigation oben.
            @endif
        </p>

        <div class="game-grid admin-game-grid" id="admin-game-grid">
            @forelse ($leagues as $league)
                @php $isSelected = $selectedId === (int) $league['league_id']; @endphp
                <form method="post" action="{{ route('admin.leagues.select', ['league' => $league['league_id']]) }}" class="admin-game-tile-form">
                    @csrf
                    <button
                        type="submit"
                        class="game-tile admin-game-tile{{ $isSelected ? ' is-selected' : '' }}"
                        @disabled($isSelected)
                        title="{{ $isSelected ? 'Aktuell ausgewählt' : 'Liga auswählen' }}"
                    >
                        <img src="{{ $league['symbol_url'] }}" alt="" width="56" height="56" loading="lazy">
                        <span class="admin-game-tile-title">{{ $league['league_title'] }}</span>
                        <ul class="admin-game-flags" aria-label="Status">
                            @foreach ($league['flags'] as $flag)
                                <li class="admin-game-flag admin-game-flag-{{ $flag['tone'] }}">{{ $flag['label'] }}</li>
                            @endforeach
                        </ul>
                    </button>
                </form>
            @empty
                <p class="muted">Keine Ligen in der Datenbank.</p>
            @endforelse
        </div>
    </section>
@endsection
