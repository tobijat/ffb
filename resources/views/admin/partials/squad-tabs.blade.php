@php
    $rosterQuery = $rosterQuery ?? [];
    $addQuery = $addQuery ?? [];
    $autoQuery = $autoQuery ?? [];
    $autoUefaQuery = $autoUefaQuery ?? [];
    $autoFifaQuery = $autoFifaQuery ?? [];
    $imagesQuery = $imagesQuery ?? [];
    $flashErrors = $flashErrors ?? [];
@endphp
<nav class="admin-squad-tabs ffb-tabs" aria-label="Kader-Bereiche">
    <a
        class="admin-squad-tab ffb-tab{{ $tab === 'roster' ? ' is-active' : '' }}"
        href="{{ route('admin.squad', $rosterQuery) }}"
    >
        Bestand <span class="admin-squad-count" id="squad-tab-roster-count">{{ count($items ?? []) }}</span>
    </a>
    <a
        class="admin-squad-tab ffb-tab{{ $tab === 'add' ? ' is-active' : '' }}"
        href="{{ route('admin.squad', $addQuery) }}"
    >
        Spieler hinzufügen
    </a>
    <a
        class="admin-squad-tab ffb-tab{{ $tab === 'auto' ? ' is-active' : '' }}"
        href="{{ route('admin.squad', $autoQuery) }}"
    >
        Auto-Kader
    </a>
    <a
        class="admin-squad-tab ffb-tab{{ $tab === 'auto-uefa' ? ' is-active' : '' }}"
        href="{{ route('admin.squad', $autoUefaQuery) }}"
    >
        Auto-Kader (UEFA)
    </a>
    <a
        class="admin-squad-tab ffb-tab{{ $tab === 'auto-fifa' ? ' is-active' : '' }}"
        href="{{ route('admin.squad', $autoFifaQuery) }}"
    >
        Auto-Kader (FIFA)
    </a>
    <a
        class="admin-squad-tab ffb-tab{{ $tab === 'images' ? ' is-active' : '' }}"
        href="{{ route('admin.squad', $imagesQuery) }}"
    >
        Auto-Bilder
    </a>
</nav>

@if ($tab !== 'images')
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

    @if (!empty($answer))
        <div class="account-flash account-flash-ok" role="status">
            {{ $answer }}
        </div>
    @endif
@endif
