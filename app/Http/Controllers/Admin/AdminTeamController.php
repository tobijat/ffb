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
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');
        $tab = $request->query('tab') === 'auto' ? 'auto' : 'manual';
        $auto = session('admin_teams_auto');

        return $this->render(
            $userId,
            null,
            'create',
            is_array($errors) ? $errors : [],
            $tab,
            is_array($auto) ? $auto : null,
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

    public function analyzeAuto(Request $request): RedirectResponse
    {
        $result = $this->teams->analyzeMatchroundsFile($request->input('matchrounds_json'));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.teams', ['tab' => 'auto'])
                ->with('admin_errors', $result['errors'] ?? ['Analyse fehlgeschlagen.']);
        }

        session(['admin_teams_auto' => $result['auto']]);

        return redirect()
            ->route('admin.teams', ['tab' => 'auto'])
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAuto(Request $request): RedirectResponse
    {
        /** @var list<array<string, mixed>>|array<int, array<string, mixed>> $teams */
        $teams = $request->input('teams', []);
        if (! is_array($teams)) {
            $teams = [];
        }

        $sourceName = (string) ($request->input('source_name') ?: (session('admin_teams_auto.source_name') ?? ''));
        $result = $this->teams->createMissingTeams($teams, $sourceName);

        if (! ($result['ok'] ?? false)) {
            if (isset($result['auto']) && is_array($result['auto'])) {
                $previous = session('admin_teams_auto');
                if (is_array($previous) && isset($previous['present']) && is_array($previous['present'])) {
                    $result['auto']['present'] = $previous['present'];
                }
                session(['admin_teams_auto' => $result['auto']]);
            }

            return redirect()
                ->route('admin.teams', ['tab' => 'auto'])
                ->with('admin_errors', $result['errors'] ?? ['Anlegen fehlgeschlagen.']);
        }

        session()->forget('admin_teams_auto');

        return redirect()
            ->route('admin.teams', ['tab' => 'auto'])
            ->with('admin_message', $result['message'] ?? null);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  list<string>  $errors
     * @param  array<string, mixed>|null  $auto
     */
    private function render(
        int $userId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
        string $tab = 'manual',
        ?array $auto = null,
    ): View {
        return view('admin.teams', [
            'data' => $this->teams->pagePayload($userId, $form, $mode, $tab, $auto),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
