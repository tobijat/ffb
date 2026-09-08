<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminPlayerService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPlayerController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminPlayerService $players,
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

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->players->search([
            'q' => $request->query('q', ''),
            'nationality' => $request->query('nationality', ''),
            'page' => $request->query('page', 1),
            'exclude_team_id' => $request->query('exclude_team_id', 0),
        ]));
    }

    public function edit(Request $request, int $player): RedirectResponse
    {
        return redirect()->route('admin.players');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->players->create($request->all());

        if ($result['ok']) {
            return redirect()
                ->route('admin.players')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $player): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->players->update($player, $request->all());

        if ($result['ok']) {
            return redirect()
                ->route('admin.players')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function batchUpdate(Request $request): RedirectResponse
    {
        $result = $this->players->batchUpdate($request->all());
        $redirect = redirect()->route('admin.players');

        if ($result['ok']) {
            return $redirect->with('admin_message', $result['message']);
        }

        return $redirect->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);
    }

    public function destroy(Request $request, int $player): RedirectResponse
    {
        $result = $this->players->delete($player);

        $redirect = redirect()->route('admin.players');

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
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
    ): View {
        return view('admin.players', [
            'data' => $this->players->pagePayload($userId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
