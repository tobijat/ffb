<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminLeagueService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLeagueController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminLeagueService $leagues,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');

        return $this->render(
            $userId,
            null,
            'create',
            is_array($errors) ? $errors : [],
        );
    }

    public function edit(Request $request, int $game): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $form = $this->leagues->formForEdit($game);
        if ($form === null) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_errors', ['Liga nicht gefunden.']);
        }

        return $this->render($userId, $form, 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->leagues->create(
            $request->all(),
            $request->file('game_symbol_file'),
        );

        if ($result['ok']) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $game): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->leagues->update(
            $game,
            $request->all(),
            $request->file('game_symbol_file'),
        );

        if ($result['ok']) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $game): RedirectResponse
    {
        $result = $this->leagues->delete($game);

        if ($result['ok']) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_message', $result['message']);
        }

        return redirect()
            ->route('admin.leagues')
            ->with('admin_errors', $result['errors'] ?? ['Löschen fehlgeschlagen.']);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  list<string>  $errors
     */
    private function render(
        int $userId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
    ): View {
        return view('admin.leagues', [
            'data' => $this->leagues->pagePayload($userId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
