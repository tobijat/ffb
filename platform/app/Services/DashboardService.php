<?php

namespace App\Services;

use App\Models\Game;
use App\Models\News;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollResult;
use App\Models\UserDetails;
use App\Models\WebUser;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public const NEWS_PER_PAGE = 3;

    /**
     * @return list<array{symbol: string, name: string, link: string, style: string}>
     */
    public function navigation(): array
    {
        return [
            ['symbol' => 'nav_start.png', 'name' => 'Start', 'link' => '/platform/', 'style' => 'big'],
            ['symbol' => 'nav_team.png', 'name' => 'Aufstellung', 'link' => '/ffb/lineup', 'style' => 'big'],
            ['symbol' => 'nav_player.png', 'name' => 'Mannschaft', 'link' => '/platform/myteam', 'style' => 'big'],
            ['symbol' => 'nav_topflop.png', 'name' => 'Top&Flop', 'link' => '/platform/bestteam', 'style' => 'big'],
            ['symbol' => 'nav_results.png', 'name' => 'Rangliste', 'link' => '/platform/userscore', 'style' => 'big'],
            ['symbol' => 'nav_help.png', 'name' => 'Regeln', 'link' => '/users/help', 'style' => 'big'],
            ['symbol' => 'nav_user.png', 'name' => 'Account', 'link' => '/platform/account', 'style' => 'big'],
            ['symbol' => 'nav_profile.png', 'name' => 'Profil', 'link' => '/platform/profile', 'style' => 'big'],
            ['symbol' => 'nav_logout.png', 'name' => 'Ausloggen', 'link' => 'logout', 'style' => 'big'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(int $userId, int $newsPage = 1, bool $archiveGames = false): array
    {
        $user = WebUser::query()->with('details')->find($userId);
        if (! $user) {
            return [
                'user' => null,
                'games' => [],
                'news' => ['items' => [], 'page' => 1, 'pages' => 0],
                'polls' => ['text' => null, 'select' => null],
                'navigation' => $this->navigation(),
            ];
        }

        $details = $user->details;
        $selectedGameId = (int) ($details?->user_details_ffb_selected_game ?? 0);

        return [
            'user' => $this->userPayload($user, $details),
            'selected_game_id' => $selectedGameId,
            'games' => $this->games($archiveGames),
            'archive' => $archiveGames,
            'news' => $this->news($selectedGameId, $newsPage),
            'polls' => [
                'text' => $this->textPoll($userId, $selectedGameId),
                'select' => $this->selectPoll($userId, $selectedGameId),
            ],
            'navigation' => $this->navigation(),
        ];
    }

    /**
     * @return array{ok: true, selected_game_id: int, game_title: string}|array{ok: false, status: int, error: string}
     */
    public function selectGame(int $userId, int $gameId): array
    {
        $game = Game::query()->find($gameId);
        if (! $game || ! $game->game_visible || ! $game->game_status) {
            return ['ok' => false, 'status' => 422, 'error' => 'Game not available'];
        }

        $details = UserDetails::query()->find($userId);
        if (! $details) {
            return ['ok' => false, 'status' => 404, 'error' => 'User details not found'];
        }

        $details->user_details_ffb_selected_game = $gameId;
        $details->save();

        return [
            'ok' => true,
            'selected_game_id' => $gameId,
            'game_title' => (string) $game->game_title,
        ];
    }

    /**
     * @return array{ok: true}|array{ok: false, status: int, error: string}
     */
    public function voteSelect(int $userId, int $pollId, int $answerId): array
    {
        return $this->storeVote($userId, $pollId, $answerId, '');
    }

    /**
     * @return array{ok: true}|array{ok: false, status: int, error: string}
     */
    public function voteText(int $userId, int $pollId, int $answerId, string $text): array
    {
        $text = trim($text);
        if ($text === '') {
            return ['ok' => false, 'status' => 422, 'error' => 'Answer text is required'];
        }

        return $this->storeVote($userId, $pollId, $answerId, $text);
    }

    /**
     * @return array{ok: true}|array{ok: false, status: int, error: string}
     */
    private function storeVote(int $userId, int $pollId, int $answerId, string $text): array
    {
        $poll = Poll::query()->find($pollId);
        $answer = PollAnswer::query()->find($answerId);
        if (! $poll || ! $answer || (int) $answer->poll_answer_poll_id !== $pollId) {
            return ['ok' => false, 'status' => 422, 'error' => 'Invalid poll answer'];
        }

        $already = PollResult::query()
            ->where('poll_result_user_id', $userId)
            ->where('poll_result_poll_id', $pollId)
            ->exists();
        if ($already) {
            return ['ok' => false, 'status' => 409, 'error' => 'Already voted'];
        }

        DB::transaction(function () use ($userId, $pollId, $answerId, $text, $answer) {
            $answer->poll_answer_count = (int) $answer->poll_answer_count + 1;
            $answer->save();

            PollResult::query()->create([
                'poll_result_user_id' => $userId,
                'poll_result_poll_id' => $pollId,
                'poll_result_poll_answer_id' => $answerId,
                'poll_result_text' => $text,
            ]);
        });

        return ['ok' => true];
    }

    /**
     * @return array<string, mixed>
     */
    private function userPayload(WebUser $user, ?UserDetails $details): array
    {
        $photo = (string) ($details?->user_details_photo ?: 'profile_na.png');
        $avatar = (string) ($details?->user_details_avatar ?: 'avatar_na.png');
        $emptyProfile = $avatar === 'avatar_na.png'
            && $photo === 'profile_na.png'
            && ! $details?->user_details_zip
            && ! $details?->user_details_city
            && ! $details?->user_details_street
            && ! $details?->user_details_phone
            && ! $details?->user_details_website
            && ! $user->user_fname
            && ! $user->user_lname;

        return [
            'user_id' => (int) $user->user_id,
            'user_nickname' => (string) $user->user_nickname,
            'user_name' => trim(($user->user_fname ?? '').' '.($user->user_lname ?? '')),
            'user_photo' => $photo,
            'user_avatar' => $avatar,
            'photo_url' => '/images/ffb/profiles/photo/'.$photo,
            'update_profile_nag' => $emptyProfile,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function games(bool $archive): array
    {
        return Game::query()
            ->where('game_visible', 1)
            ->where('game_archive', $archive ? 1 : 0)
            ->where('game_status', 1)
            ->whereHas('matchrounds')
            ->orderBy('game_title')
            ->get()
            ->map(fn (Game $game) => [
                'game_id' => (int) $game->game_id,
                'game_title' => (string) $game->game_title,
                'game_symbol' => (string) ($game->game_symbol ?: 'symbol_game_na.png'),
                'game_archive' => (int) (bool) $game->game_archive,
                'game_visible' => (int) (bool) $game->game_visible,
                'game_countdown' => (int) (bool) $game->game_countdown,
                'game_status' => (int) (bool) $game->game_status,
                'symbol_url' => '/images/ffb/symbols/'.($game->game_symbol ?: 'symbol_game_na.png'),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array{items: list<array<string, mixed>>, page: int, pages: int}
     */
    private function news(int $selectedGameId, int $page): array
    {
        $page = max(1, $page);
        $query = News::query()
            ->where(function ($q) use ($selectedGameId) {
                $q->where('news_game_id', 0);
                if ($selectedGameId > 0) {
                    $q->orWhere('news_game_id', $selectedGameId);
                }
            })
            ->orderByDesc('news_id');

        $total = (clone $query)->count();
        $pages = (int) ceil($total / self::NEWS_PER_PAGE);
        $items = $query
            ->forPage($page, self::NEWS_PER_PAGE)
            ->get()
            ->map(fn (News $item) => [
                'news_id' => (int) $item->news_id,
                'news_title' => (string) $item->news_title,
                'news_date' => date('d.m.Y H:i', strtotime((string) $item->news_date)),
                'news_text' => nl2br((string) $item->news_text),
                'news_symbol' => $item->news_symbol ?: null,
            ])
            ->values()
            ->all();

        return [
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function textPoll(int $userId, int $gameId): ?array
    {
        $now = now()->format('Y-m-d H:i:s');
        $polls = Poll::query()
            ->with('answers')
            ->where('poll_type', 'text')
            ->where('poll_visible', 1)
            ->where('poll_start', '<', $now)
            ->where('poll_end', '>', $now)
            ->where(function ($q) use ($gameId) {
                $q->where('poll_game_id', 0);
                if ($gameId > 0) {
                    $q->orWhere('poll_game_id', $gameId);
                }
            })
            ->get();

        if ($polls->isEmpty()) {
            return null;
        }

        /** @var Poll $poll */
        $poll = $polls->random();
        if ($this->userHasVoted($userId, (int) $poll->poll_id)) {
            return null;
        }

        return $this->pollOpenPayload($poll);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function selectPoll(int $userId, int $gameId): ?array
    {
        $now = now()->format('Y-m-d H:i:s');
        $poll = Poll::query()
            ->with(['answers', 'results'])
            ->where('poll_type', 'select')
            ->where('poll_visible', 1)
            ->where('poll_start', '<', $now)
            ->where('poll_end', '>', $now)
            ->where(function ($q) use ($gameId) {
                $q->where('poll_game_id', 0);
                if ($gameId > 0) {
                    $q->orWhere('poll_game_id', $gameId);
                }
            })
            ->orderBy('poll_end')
            ->first();

        if (! $poll) {
            $poll = Poll::query()
                ->with(['answers', 'results'])
                ->where('poll_type', 'select')
                ->where('poll_visible', 1)
                ->where('poll_end', '<', $now)
                ->where(function ($q) use ($gameId) {
                    $q->where('poll_game_id', 0);
                    if ($gameId > 0) {
                        $q->orWhere('poll_game_id', $gameId);
                    }
                })
                ->orderByDesc('poll_end')
                ->first();
        }

        if (! $poll) {
            return null;
        }

        if ($this->userHasVoted($userId, (int) $poll->poll_id) || strtotime((string) $poll->poll_end) <= time()) {
            return $this->pollResultPayload($poll);
        }

        return $this->pollOpenPayload($poll);
    }

    private function userHasVoted(int $userId, int $pollId): bool
    {
        return PollResult::query()
            ->where('poll_result_user_id', $userId)
            ->where('poll_result_poll_id', $pollId)
            ->exists();
    }

    /**
     * @return array<string, mixed>
     */
    private function pollOpenPayload(Poll $poll): array
    {
        return [
            'poll_id' => (int) $poll->poll_id,
            'poll_title' => (string) $poll->poll_title,
            'poll_start' => date('d.m.Y H:i', strtotime((string) $poll->poll_start)),
            'poll_end' => date('d.m.Y H:i', strtotime((string) $poll->poll_end)),
            'poll_type' => (string) $poll->poll_type,
            'poll_location' => (string) $poll->poll_location,
            'state' => 'open',
            'poll_answers' => $poll->answers->map(fn (PollAnswer $a) => [
                'poll_answer_id' => (int) $a->poll_answer_id,
                'poll_answer_title' => (string) $a->poll_answer_title,
                'poll_answer_count' => (int) $a->poll_answer_count,
            ])->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function pollResultPayload(Poll $poll): array
    {
        $numResults = $poll->results->count();
        $over = strtotime((string) $poll->poll_end) < time();

        return [
            'poll_id' => (int) $poll->poll_id,
            'poll_title' => (string) $poll->poll_title,
            'poll_start' => date('d.m.Y H:i', strtotime((string) $poll->poll_start)),
            'poll_end' => date('d.m.Y H:i', strtotime((string) $poll->poll_end)),
            'poll_type' => (string) $poll->poll_type,
            'state' => 'result',
            'poll_over' => $over ? 1 : 0,
            'poll_num_answers' => $over ? $numResults : 0,
            'poll_answers' => [],
            'poll_result' => $poll->answers->map(function (PollAnswer $a) use ($numResults) {
                $pct = $numResults > 0 ? round($a->poll_answer_count / $numResults * 100, 1) : 0.0;

                return [
                    'poll_answer_id' => (int) $a->poll_answer_id,
                    'poll_answer_title' => (string) $a->poll_answer_title,
                    'poll_answer_count' => (int) $a->poll_answer_count,
                    'poll_answer_percent' => $pct,
                    'poll_answer_percent_round' => (int) round($pct),
                ];
            })->values()->all(),
        ];
    }
}
