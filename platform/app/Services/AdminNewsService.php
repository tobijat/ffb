<?php

namespace App\Services;

use App\Models\Game;
use App\Models\News;
use Illuminate\Support\Carbon;

class AdminNewsService
{
    public function __construct(
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @return array{
     *     user: array<string, mixed>,
     *     navigation: list<array<string, mixed>>,
     *     items: list<array<string, mixed>>,
     *     games: list<array{game_id: int, game_title: string}>,
     *     form: array<string, mixed>,
     *     mode: string
     * }
     */
    public function pagePayload(int $userId, ?array $form = null, string $mode = 'create'): array
    {
        $shell = $this->adminCenter->pagePayload($userId);
        $items = $this->listItems();
        $form = $form ?? $this->emptyForm();

        return [
            'user' => $shell['user'],
            'navigation' => $shell['navigation'],
            'items' => $items,
            'games' => $this->gameOptions((int) ($form['news_game_id'] ?? 0)),
            'form' => $form,
            'mode' => $mode === 'update' ? 'update' : 'create',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function emptyForm(): array
    {
        return [
            'news_id' => '',
            'news_game_id' => 0,
            'news_title' => '',
            'news_text' => '',
            'news_symbol' => '',
            'news_priority' => '0',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function formForEdit(int $newsId): ?array
    {
        $item = News::query()->find($newsId);
        if (! $item) {
            return null;
        }

        return [
            'news_id' => (int) $item->news_id,
            'news_game_id' => (int) $item->news_game_id,
            'news_title' => (string) $item->news_title,
            'news_text' => (string) $item->news_text,
            'news_symbol' => (string) ($item->news_symbol ?? ''),
            'news_priority' => (string) (int) $item->news_priority,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function create(array $input): array
    {
        $form = $this->normalizeInput($input);
        $errors = $this->validate($form);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        News::query()->create([
            'news_title' => $form['news_title'],
            'news_text' => $form['news_text'],
            'news_symbol' => $form['news_symbol'],
            'news_priority' => (int) $form['news_priority'],
            'news_game_id' => (int) $form['news_game_id'],
            'news_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return ['ok' => true, 'message' => 'News erfolgreich hinzugefügt.'];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{ok: bool, message?: string, errors?: list<string>, form?: array<string, mixed>}
     */
    public function update(int $newsId, array $input): array
    {
        $item = News::query()->find($newsId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Newseintrag nicht gefunden.'],
                'form' => $this->normalizeInput($input + ['news_id' => $newsId]),
            ];
        }

        $form = $this->normalizeInput($input + ['news_id' => $newsId]);
        $errors = $this->validate($form);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'form' => $form];
        }

        $item->news_title = $form['news_title'];
        $item->news_text = $form['news_text'];
        $item->news_symbol = $form['news_symbol'];
        $item->news_priority = (int) $form['news_priority'];
        $item->news_game_id = (int) $form['news_game_id'];
        $item->save();

        return ['ok' => true, 'message' => 'News erfolgreich aktualisiert.'];
    }

    /**
     * @return array{ok: bool, message?: string, errors?: list<string>}
     */
    public function delete(int $newsId): array
    {
        $item = News::query()->find($newsId);
        if (! $item) {
            return [
                'ok' => false,
                'errors' => ['Newseintrag nicht gefunden! Falsche ID oder Seite neu geladen?'],
            ];
        }

        $item->delete();

        return ['ok' => true, 'message' => 'News erfolgreich gelöscht.'];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listItems(): array
    {
        return News::query()
            ->orderByDesc('news_date')
            ->orderByDesc('news_id')
            ->get()
            ->map(function (News $item) {
                $symbol = (string) ($item->news_symbol ?? '');

                return [
                    'news_id' => (int) $item->news_id,
                    'news_title' => (string) $item->news_title,
                    'news_text_html' => nl2br(e((string) $item->news_text), false),
                    'news_date' => (string) $item->news_date,
                    'news_symbol' => $symbol,
                    'news_symbol_url' => $symbol !== ''
                        ? '/images/ffb/symbols/'.$symbol
                        : null,
                    'news_priority' => (int) $item->news_priority,
                    'news_game_id' => (int) $item->news_game_id,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array{game_id: int, game_title: string}>
     */
    private function gameOptions(int $selectedGameId): array
    {
        $games = Game::query()
            ->where('game_visible', 1)
            ->orderBy('game_title')
            ->get(['game_id', 'game_title']);

        $options = [
            ['game_id' => 0, 'game_title' => 'Global'],
        ];

        foreach ($games as $game) {
            $options[] = [
                'game_id' => (int) $game->game_id,
                'game_title' => (string) $game->game_title,
            ];
        }

        if ($selectedGameId > 0 && ! collect($options)->contains(fn ($g) => $g['game_id'] === $selectedGameId)) {
            $extra = Game::query()->find($selectedGameId);
            if ($extra) {
                $options[] = [
                    'game_id' => (int) $extra->game_id,
                    'game_title' => (string) $extra->game_title,
                ];
            }
        }

        return $options;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeInput(array $input): array
    {
        $priority = trim((string) ($input['news_priority'] ?? ''));

        return [
            'news_id' => (string) ($input['news_id'] ?? ''),
            'news_game_id' => (int) ($input['news_game_id'] ?? 0),
            'news_title' => trim((string) ($input['news_title'] ?? '')),
            'news_text' => trim((string) ($input['news_text'] ?? '')),
            'news_symbol' => trim((string) ($input['news_symbol'] ?? '')),
            'news_priority' => $priority === '' ? '0' : $priority,
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     * @return list<string>
     */
    private function validate(array $form): array
    {
        $errors = [];

        if ($form['news_title'] === '' || $form['news_text'] === '') {
            $errors[] = 'Bitte alle mit * markierten Felder ausfüllen.';
        }

        if (! is_numeric($form['news_priority'])) {
            $errors[] = 'Priorität ist ungültig.';
        }

        return $errors;
    }
}
