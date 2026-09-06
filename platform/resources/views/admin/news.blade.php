@extends('layouts.admin')

@section('title', 'News')

@section('content')
    @php
        $form = $data['form'];
        $mode = $data['mode'];
        $items = $data['items'];
        $games = $data['games'];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-news-title">
        <div class="section-head">
            <h2 id="admin-news-title">News</h2>
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
            action="{{ $mode === 'update' ? route('admin.news.update', ['news' => $form['news_id']]) : route('admin.news.store') }}"
            accept-charset="UTF-8"
        >
            @csrf
            @if ($mode === 'update')
                @method('PUT')
            @endif

            <div class="admin-field">
                <label for="news_game_id">* Spiel</label>
                <select id="news_game_id" name="news_game_id">
                    @foreach ($games as $game)
                        <option value="{{ $game['game_id'] }}" @selected((int) $form['news_game_id'] === (int) $game['game_id'])>
                            {{ $game['game_title'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="admin-field">
                <label for="news_title">* Titel</label>
                <input id="news_title" type="text" name="news_title" value="{{ $form['news_title'] }}" maxlength="255" required>
            </div>

            <div class="admin-field admin-field-top">
                <label for="news_text">* Text</label>
                <textarea id="news_text" name="news_text" rows="6" required>{{ $form['news_text'] }}</textarea>
            </div>

            <div class="admin-field">
                <label for="news_symbol">Symbol</label>
                <input id="news_symbol" type="text" name="news_symbol" value="{{ $form['news_symbol'] }}" maxlength="255" placeholder="z. B. news.png">
            </div>

            <div class="admin-field">
                <label for="news_priority">Priorität</label>
                <input id="news_priority" type="text" name="news_priority" value="{{ $form['news_priority'] }}" inputmode="numeric">
            </div>

            <div class="admin-actions">
                @if ($mode === 'update')
                    <button type="submit" class="admin-submit">Speichern</button>
                    <a class="admin-cancel" href="{{ route('admin.news') }}">Abbrechen</a>
                @else
                    <button type="submit" class="admin-submit">Hinzufügen</button>
                @endif
            </div>
        </form>
    </section>

    <section class="panel admin-main" aria-labelledby="admin-news-list-title">
        <div class="section-head">
            <h2 id="admin-news-list-title">Vorhandene News</h2>
        </div>

        @forelse ($items as $item)
            <article class="admin-list-item">
                <div class="admin-list-body">
                    <h3 class="admin-list-title">
                        @if ($item['news_symbol_url'])
                            <img src="{{ $item['news_symbol_url'] }}" alt="" height="18" loading="lazy">
                        @endif
                        {{ $item['news_title'] }}
                        <span class="muted">(ID: {{ $item['news_id'] }})</span>
                    </h3>
                    <time class="muted">{{ $item['news_date'] }}</time>
                    <div class="admin-list-text">{!! $item['news_text_html'] !!}</div>
                </div>
                <div class="admin-list-actions">
                    <a class="admin-icon-btn" href="{{ route('admin.news.edit', ['news' => $item['news_id']]) }}" title="Bearbeiten">
                        <img src="{{ $legacyBase }}images/ffb/symbols/edit.png" alt="Bearbeiten" width="16" height="16">
                    </a>
                    <form method="post" action="{{ route('admin.news.destroy', ['news' => $item['news_id']]) }}" onsubmit="return confirm('Diesen Newseintrag wirklich löschen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-icon-btn" title="Löschen">
                            <img src="{{ $legacyBase }}images/ffb/symbols/delete.png" alt="Löschen" width="16" height="16">
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">Noch keine News vorhanden.</p>
        @endforelse
    </section>
@endsection
