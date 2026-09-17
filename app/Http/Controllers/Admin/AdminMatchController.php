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
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $leagueId = $this->matches->defaultLeagueId($userId);
        $errors = session('admin_errors');
        $prefill = session('admin_match_prefill');
        $tab = $request->query('tab') === 'auto' ? 'auto' : 'manual';
        $auto = session('admin_matches_auto');

        return $this->render(
            $userId,
            $leagueId,
            is_array($prefill) ? $prefill : null,
            'create',
            is_array($errors) ? $errors : [],
            $tab,
            is_array($auto) ? $auto : null,
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

        return $this->render($userId, $edit['league_id'], $edit['form'], 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matches->create($request->all());
        $leagueId = (int) ($result['league_id'] ?? 0);

        if ($result['ok']) {
            $redirect = redirect()
                ->route('admin.matches', $leagueId > 0 ? ['league_id' => $leagueId] : [])
                ->with('admin_message', $result['message']);

            if (! empty($result['next_form']) && is_array($result['next_form'])) {
                $redirect->with('admin_match_prefill', $result['next_form']);
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

    public function update(Request $request, int $match): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matches->update($match, $request->all());
        $leagueId = (int) ($result['league_id'] ?? 0);

        if ($result['ok']) {
            return redirect()
                ->route('admin.matches', $leagueId > 0 ? ['league_id' => $leagueId] : [])
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

    public function destroy(Request $request, int $match): RedirectResponse
    {
        $result = $this->matches->delete($match);
        $leagueId = (int) ($result['league_id'] ?? $request->input('league_id', 0));

        $redirect = redirect()->route(
            'admin.matches',
            $leagueId > 0 ? ['league_id' => $leagueId] : [],
        );

        if ($result['ok']) {
            return $redirect->with('admin_message', $result['message']);
        }

        return $redirect->with('admin_errors', $result['errors'] ?? ['Löschen fehlgeschlagen.']);
    }

    public function analyzeAuto(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('league_id', 0);
        $result = $this->matches->analyzeMatchroundsFile(
            $leagueId,
            $request->input('matchrounds_json'),
        );

        $redirectQuery = ['tab' => 'auto'];
        if ($leagueId > 0) {
            $redirectQuery['league_id'] = $leagueId;
        }

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.matches', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Analyse fehlgeschlagen.']);
        }

        session(['admin_matches_auto' => $result['auto']]);

        return redirect()
            ->route('admin.matches', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAuto(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('league_id', 0);
        /** @var list<array<string, mixed>>|array<int, array<string, mixed>> $matches */
        $matches = $request->input('matches', []);
        if (! is_array($matches)) {
            $matches = [];
        }

        $sourceName = (string) ($request->input('source_name')
            ?: (session('admin_matches_auto.source_name') ?? ''));
        $result = $this->matches->createMatchesFromDraft($matches, $leagueId, $sourceName);

        $redirectQuery = ['tab' => 'auto'];
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        if ($resultLeagueId > 0) {
            $redirectQuery['league_id'] = $resultLeagueId;
        }

        if (! ($result['ok'] ?? false)) {
            if (isset($result['auto']) && is_array($result['auto'])) {
                $previous = session('admin_matches_auto');
                if (is_array($previous) && isset($previous['present']) && is_array($previous['present'])) {
                    $result['auto']['present'] = $previous['present'];
                }
                session(['admin_matches_auto' => $result['auto']]);
            }

            return redirect()
                ->route('admin.matches', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Anlegen fehlgeschlagen.']);
        }

        session()->forget('admin_matches_auto');

        return redirect()
            ->route('admin.matches', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  list<string>  $errors
     * @param  array<string, mixed>|null  $auto
     */
    private function render(
        int $userId,
        int $leagueId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
        string $tab = 'manual',
        ?array $auto = null,
    ): View {
        return view('admin.matches', [
            'data' => $this->matches->pagePayload($userId, $leagueId, $form, $mode, $tab, $auto),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
