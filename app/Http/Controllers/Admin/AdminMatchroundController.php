<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMatchroundService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMatchroundController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminMatchroundService $matchrounds,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $leagueId = $this->matchrounds->defaultLeagueId($userId);
        $errors = session('admin_errors');
        $prefill = session('admin_matchround_prefill');

        return $this->render(
            $userId,
            $leagueId,
            is_array($prefill) ? $prefill : null,
            'create',
            is_array($errors) ? $errors : [],
        );
    }

    public function edit(Request $request, int $matchround): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $edit = $this->matchrounds->formForEdit($matchround);
        if ($edit === null) {
            return redirect()
                ->route('admin.matchrounds')
                ->with('admin_errors', ['Spielrunde nicht gefunden.']);
        }

        return $this->render($userId, $edit['league_id'], $edit['form'], 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matchrounds->create($request->all());
        $leagueId = (int) ($result['league_id'] ?? $request->input('matchround_league_id', 0));

        if ($result['ok']) {
            $redirect = redirect()
                ->route('admin.matchrounds', ['league_id' => $leagueId])
                ->with('admin_message', $result['message']);

            if (! empty($result['next_form']) && is_array($result['next_form'])) {
                $redirect->with('admin_matchround_prefill', $result['next_form']);
            }

            return $redirect;
        }

        return $this->render(
            $userId,
            $leagueId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $matchround): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matchrounds->update($matchround, $request->all());
        $leagueId = (int) ($result['league_id'] ?? $request->input('matchround_league_id', 0));

        if ($result['ok']) {
            return redirect()
                ->route('admin.matchrounds', ['league_id' => $leagueId])
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $leagueId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $matchround): RedirectResponse
    {
        $result = $this->matchrounds->delete($matchround);
        $leagueId = (int) ($result['league_id'] ?? $request->input('league_id', 0));

        $redirect = redirect()->route(
            'admin.matchrounds',
            $leagueId > 0 ? ['league_id' => $leagueId] : [],
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
        int $leagueId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
    ): View {
        return view('admin.matchrounds', [
            'data' => $this->matchrounds->pagePayload($userId, $leagueId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
