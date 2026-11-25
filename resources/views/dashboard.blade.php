<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Start — SoccerSportsfan</title>
    @include('partials.favicon')
    <link rel="stylesheet" href="css/start.css?v=45">
    <link rel="stylesheet" href="css/modal.css?v=50">
    <link rel="stylesheet" href="css/dashboard.css?v=58">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $leagues = $data['leagues'];
        $archivedLeagues = $data['archived_leagues'] ?? [];
        $selectedId = (int) ($data['selected_league_id'] ?? 0);
        $emailUnverified = ! empty($data['email_unverified']);
        $news = $data['news'];
        $textPoll = $emailUnverified ? null : ($data['polls']['text'] ?? null);
        $selectPoll = $emailUnverified ? null : ($data['polls']['select'] ?? null);
        $nav = $data['navigation'];
        $archive = (bool) ($data['archive'] ?? false);
        $accountMessage = $accountMessage ?? null;
        $showActivationModal = ! empty($showActivationModal);
    @endphp

    @include('partials.dash-top')

    <main class="dash-main">
        @if (! empty($accountMessage))
            @php
                $accountFeedbackKind = str_contains((string) $accountMessage, 'Fehler') ? 'error' : 'ok';
            @endphp
            <div class="dash-flash feedback feedback-{{ $accountFeedbackKind }}" role="status">{!! $accountMessage !!}</div>
        @endif

        <section class="games-section{{ $emailUnverified ? ' is-unverified' : '' }}" aria-labelledby="games-title">
            <div class="section-head">
                <h2 id="games-title">{{ $selectedId === 0 ? 'Liga auswählen' : 'Verfügbare Ligen' }}</h2>
            </div>
            @if ($emailUnverified)
                <p class="hint">Bestätige deine E-Mail Adresse, um eine Liga auszuwählen.</p>
            @elseif ($selectedId === 0)
                <p class="hint">Klick eine Liga an, um sie auszuwählen.</p>
            @else
                <p class="hint">Die markierte Liga ist ausgewählt.</p>
            @endif

            <div class="game-grid" id="game-grid">
                @forelse ($leagues as $league)
                    <button
                        type="button"
                        class="game-tile{{ $selectedId === (int) $league['league_id'] ? ' is-selected' : '' }}{{ ! empty($league['is_faded']) ? ' is-faded' : '' }}{{ $emailUnverified ? ' is-unverified' : '' }}{{ ! empty($league['league_test']) ? ' is-test' : '' }}"
                        data-league-id="{{ $league['league_id'] }}"
                        @if (! empty($league['league_test'])) title="Testliga" @endif
                    >
                        <img src="{{ $league['symbol_url'] }}" alt="" width="56" height="56" loading="lazy">
                        <span>{{ $league['league_title'] }}</span>
                    </button>
                @empty
                    <p class="muted">Keine Ligen gefunden.</p>
                @endforelse
            </div>

            @if (count($archivedLeagues) > 0)
                <button
                    type="button"
                    class="archive-toggle{{ $emailUnverified ? ' is-unverified' : '' }}"
                    id="toggle-archive"
                    data-archive="{{ $archive ? '1' : '0' }}"
                    aria-expanded="{{ $archive ? 'true' : 'false' }}"
                    aria-controls="archive-grid"
                >
                    {{ $archive ? 'vergangene Ligen ausblenden' : 'vergangene Ligen einblenden' }}
                </button>
                <div
                    class="game-grid archive-grid"
                    id="archive-grid"
                    @if (! $archive) hidden @endif
                >
                    @foreach ($archivedLeagues as $league)
                        <button
                            type="button"
                            class="game-tile{{ $selectedId === (int) $league['league_id'] ? ' is-selected' : '' }}{{ ! empty($league['is_faded']) ? ' is-faded' : '' }}{{ $emailUnverified ? ' is-unverified' : '' }}{{ ! empty($league['league_test']) ? ' is-test' : '' }}"
                            data-league-id="{{ $league['league_id'] }}"
                            @if (! empty($league['league_test'])) title="Testliga" @endif
                        >
                            <img src="{{ $league['symbol_url'] }}" alt="" width="56" height="56" loading="lazy">
                            <span>{{ $league['league_title'] }}</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="dash-columns{{ $emailUnverified ? ' is-single' : '' }}">
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

            @unless ($emailUnverified)
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
            @endunless
        </section>
    </main>

    <div
        id="activation-modal"
        class="ffb-modal-root activation-modal"
        hidden
        role="dialog"
        aria-modal="true"
        aria-labelledby="activation-modal-title"
    >
        <div class="ffb-modal-backdrop" data-activation-close></div>
        <div class="ffb-modal-dialog" role="document">
            <div class="ffb-modal-head">
                <div class="ffb-modal-head-title ffb-modal-head-title--start" id="activation-modal-title">E-Mail Adresse bestätigen</div>
                <button
                    type="button"
                    class="ffb-modal-close"
                    data-activation-close
                    title="Schließen"
                    aria-label="Schließen"
                >
                    <img src="images/ffb/symbols/delete.svg" alt="">
                </button>
            </div>
            <div class="ffb-modal-body">
                <p><b>Bestätige deine E-Mail Adresse.</b></p>
                <p>
                    Dein Account wurde angelegt und du bist jetzt angemeldet. Ein Aktivierungs-Link wurde an
                    <b>{{ $user['user_email'] ?? '' }}</b> gesendet.
                    Du musst den Link anklicken um deine E-Mail Adresse einmalig zu bestätigen. Danach kannst du hier an den Spielen teilnehmen.
                </p>
                <p id="activation-modal-feedback" class="activation-modal-feedback" hidden></p>
                <div class="activation-modal-actions">
                    <button type="button" class="btn" id="activation-resend">Aktivierungs-Link erneut senden</button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script>
        window.FFB_DASH = {
            apiBase: 'api',
            archive: @json($archive),
            newsPage: @json($news['page']),
            emailUnverified: @json($emailUnverified),
            showActivationModal: @json($showActivationModal),
            resendUrl: @json(route('registration.activation.resend', absolute: false)),
            csrfToken: @json(csrf_token()),
        };
    </script>
    <script src="js/dashboard.js?v=5" defer></script>
</body>
</html>
