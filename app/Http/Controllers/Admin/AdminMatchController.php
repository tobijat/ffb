<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMatchService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMatchController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminMatchService $matches,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $gameId = (int) $request->query('game_id', 0);
        if ($gameId <= 0) {
            $gameId = $this->matches->defaultGameId($userId);
        }
        $errors = session('admin_errors');
        $prefill = session('admin_match_prefill');

        return $this->render(
            $userId,
            $gameId,
            is_array($prefill) ? $prefill : null,
            'create',
            is_array($errors) ? $errors : [],
        );
    }

    public function edit(Request $request, int $match): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $edit = $this->matches->formForEdit($match);
        if ($edit === null) {
            return redirect()
                ->route('admin.matches')
                ->with('admin_errors', ['Spiel nicht gefunden.']);
        }

        return $this->render($userId, $edit['game_id'], $edit['form'], 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matches->create($request->all());
        $gameId = (int) ($result['game_id'] ?? 0);

        if ($result['ok']) {
            $redirect = redirect()
                ->route('admin.matches', $gameId > 0 ? ['game_id' => $gameId] : [])
                ->with('admin_message', $result['message']);

            if (! empty($result['next_form']) && is_array($result['next_form'])) {
                $redirect->with('admin_match_prefill', $result['next_form']);
            }

            return $redirect;
        }

        return $this->render(
            $userId,
            $gameId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $match): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matches->update($match, $request->all());
        $gameId = (int) ($result['game_id'] ?? 0);

        if ($result['ok']) {
            return redirect()
                ->route('admin.matches', $gameId > 0 ? ['game_id' => $gameId] : [])
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $gameId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $match): RedirectResponse
    {
        $result = $this->matches->delete($match);
        $gameId = (int) ($result['game_id'] ?? $request->input('game_id', 0));

        $redirect = redirect()->route(
            'admin.matches',
            $gameId > 0 ? ['game_id' => $gameId] : [],
        );

        if ($result['ok']) {
            return $redirect->with('admin_message', $result['message']);
        }

        return $redirect->with('admin_errors', $result['errors'] ?? ['Löschen fehlgeschlagen.']);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  list<string>  $errors
     */
    private function render(
        int $userId,
        int $gameId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
    ): View {
        return view('admin.matches', [
            'data' => $this->matches->pagePayload($userId, $gameId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
