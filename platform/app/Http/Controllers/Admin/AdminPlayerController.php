<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminPlayerService;
use App\Services\FfbAuth;
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
            $request,
            null,
            'create',
            is_array($errors) ? $errors : [],
        );
    }

    public function edit(Request $request, int $player): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $form = $this->players->formForEdit($player);
        if ($form === null) {
            return redirect()
                ->route('admin.players', $this->filterQuery($request))
                ->with('admin_errors', ['Spieler nicht gefunden.']);
        }

        return $this->render($userId, $request, $form, 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->players->create($request->all());

        if ($result['ok']) {
            return redirect()
                ->route('admin.players', $this->filterQuery($request))
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $request,
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
                ->route('admin.players', $this->filterQuery($request))
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $request,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $player): RedirectResponse
    {
        $result = $this->players->delete($player);

        $redirect = redirect()->route('admin.players', $this->filterQuery($request));

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
        Request $request,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
    ): View {
        return view('admin.players', [
            'data' => $this->players->pagePayload(
                $userId,
                $form,
                $mode,
                $this->filterQuery($request),
            ),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }

    /**
     * @return array{q?: string, nationality?: string, page?: int}
     */
    private function filterQuery(Request $request): array
    {
        $query = [];
        $q = trim((string) $request->query('q', $request->input('q', '')));
        $nationality = trim((string) $request->query('nationality', $request->input('nationality', '')));
        $page = (int) $request->query('page', 0);

        if ($q !== '') {
            $query['q'] = $q;
        }
        if ($nationality !== '') {
            $query['nationality'] = $nationality;
        }
        if ($page > 1) {
            $query['page'] = $page;
        }

        return $query;
    }
}
