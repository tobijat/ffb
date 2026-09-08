<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Start — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $games = $data['games'];
        $selectedId = (int) ($data['selected_game_id'] ?? 0);
        $news = $data['news'];
        $textPoll = $data['polls']['text'] ?? null;
        $selectPoll = $data['polls']['select'] ?? null;
        $nav = $data['navigation'];
        $archive = (bool) ($data['archive'] ?? false);
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @foreach ($nav as $item)
                    <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        @include('partials.user-card', ['showProfileNag' => true])
    </header>

    <main class="dash-main">
        <section class="games-section" aria-labelledby="games-title">
            <div class="section-head">
                <h2 id="games-title">{{ $selectedId === 0 ? 'Spiel auswählen' : 'Verfügbare Spiele' }}</h2>
                <button type="button" class="linkish" id="toggle-archive" data-archive="{{ $archive ? '1' : '0' }}">
                    {{ $archive ? 'zu aktuellen Spielen' : 'zu vergangenen Spielen' }}
                </button>
            </div>
            @if ($selectedId === 0)
                <p class="hint">Klick ein Spiel an, um es auszuwählen.</p>
            @else
                <p class="hint">Das markierte Spiel ist ausgewählt.</p>
            @endif

            <div class="game-grid" id="game-grid">
                @forelse ($games as $game)
                    <button
                        type="button"
                        class="game-tile{{ $selectedId === (int) $game['game_id'] ? ' is-selected' : '' }}"
                        data-game-id="{{ $game['game_id'] }}"
                    >
                        <img src="{{ $game['symbol_url'] }}" alt="" width="56" height="56" loading="lazy">
                        <span>{{ $game['game_title'] }}</span>
                    </button>
                @empty
                    <p class="muted">Keine Spiele gefunden.</p>
                @endforelse
            </div>
        </section>

        <section class="dash-columns">
            <div class="panel" id="news-panel">
                @if ($textPoll)
                    <h2>Umfrage: {{ $textPoll['poll_title'] }}</h2>
                    <div class="text-poll" data-poll-id="{{ $textPoll['poll_id'] }}">
                        @php $answer = $textPoll['poll_answers'][0] ?? null; @endphp
                        @if ($answer)
                            <p><em>{{ $answer['poll_answer_title'] }}</em></p>
                            <textarea id="poll-text-answer" rows="5" placeholder="Deine Antwort…"></textarea>
                            <button
                                type="button"
                                class="btn btn-primary"
                                id="send-text-poll"
                                data-answer-id="{{ $answer['poll_answer_id'] }}"
                            >Abschicken</button>
                        @endif
                    </div>
                @else
                    <h2>News</h2>
                    <div id="news-list">
                        @forelse ($news['items'] as $item)
                            <article class="news-item">
                                <h3>{{ $item['news_title'] }}</h3>
                                <time>{{ $item['news_date'] }}</time>
                                <div class="news-body">{!! $item['news_text'] !!}</div>
                            </article>
                        @empty
                            <p class="muted">Keine News vorhanden.</p>
                        @endforelse
                    </div>
                    @if ($news['pages'] > 1)
                        <div class="pager" id="news-pager" data-page="{{ $news['page'] }}" data-pages="{{ $news['pages'] }}">
                            @for ($i = 1; $i <= $news['pages']; $i++)
                                <button type="button" class="page-btn{{ $i === $news['page'] ? ' is-active' : '' }}" data-page="{{ $i }}">{{ $i }}</button>
                            @endfor
                        </div>
                    @endif
                @endif
            </div>

            <div class="panel" id="poll-panel">
                <h2>Abstimmung</h2>
                @if (! $selectPoll)
                    <p class="muted">Gerade keine Abstimmung aktiv.</p>
                @elseif (($selectPoll['state'] ?? '') === 'open')
                    <div class="select-poll" data-poll-id="{{ $selectPoll['poll_id'] }}">
                        <p class="poll-title">{{ $selectPoll['poll_title'] }}</p>
                        <ul class="poll-answers">
                            @foreach ($selectPoll['poll_answers'] as $answer)
                                <li>
                                    <button type="button" class="poll-vote" data-answer-id="{{ $answer['poll_answer_id'] }}">
                                        {{ $answer['poll_answer_title'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <p class="hint">Läuft bis {{ $selectPoll['poll_end'] }}</p>
                    </div>
                @else
                    <div class="select-poll-result">
                        <p class="poll-title">{{ $selectPoll['poll_title'] }}</p>
                        <ul class="poll-results">
                            @foreach ($selectPoll['poll_result'] as $answer)
                                <li>
                                    <div class="result-label">
                                        <span>{{ $answer['poll_answer_title'] }}</span>
                                        <strong>{{ $answer['poll_answer_percent'] }}%</strong>
                                    </div>
                                    <div class="bar"><span style="width: {{ min(100, (int) $answer['poll_answer_percent_round']) }}%"></span></div>
                                </li>
                            @endforeach
                        </ul>
                        @if (! empty($selectPoll['poll_over']))
                            <p class="hint">Beendet · {{ $selectPoll['poll_num_answers'] }} Stimmen</p>
                        @endif
                    </div>
                @endif
            </div>
        </section>
    </main>


    @include('partials.footer')

    <script>
        window.FFB_DASH = {
            apiBase: 'api',
            archive: @json($archive),
            newsPage: @json($news['page']),
        };
    </script>
    <script src="js/dashboard.js" defer></script>
</body>
</html>
