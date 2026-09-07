<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminTeamService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTeamController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminTeamService $teams,
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

    public function edit(Request $request, int $team): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $form = $this->teams->formForEdit($team);
        if ($form === null) {
            return redirect()
                ->route('admin.teams')
                ->with('admin_errors', ['Team nicht gefunden.']);
        }

        return $this->render($userId, $form, 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->teams->create(
            $request->all(),
            $request->file('team_icon_file'),
            $request->file('team_shirt_file'),
        );

        if ($result['ok']) {
            return redirect()
                ->route('admin.teams')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $team): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->teams->update(
            $team,
            $request->all(),
            $request->file('team_icon_file'),
            $request->file('team_shirt_file'),
        );

        if ($result['ok']) {
            return redirect()
                ->route('admin.teams')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $team): RedirectResponse
    {
        $result = $this->teams->delete($team);

        if ($result['ok']) {
            return redirect()
                ->route('admin.teams')
                ->with('admin_message', $result['message']);
        }

        return redirect()
            ->route('admin.teams')
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
        return view('admin.teams', [
            'data' => $this->teams->pagePayload($userId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
