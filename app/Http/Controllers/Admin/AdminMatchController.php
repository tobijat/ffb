<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMatchService;
use App\Services\FfbAuth;
use App\Support\RequestJsonArray;
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
        $tab = match ($request->query('tab')) {
            'auto' => 'auto',
            'auto-uefa' => 'auto-uefa',
            default => 'manual',
        };

        // One-shot drafts: keep only for this request (reload / league switch must not reuse them).
        $auto = session()->pull('admin_matches_auto');
        $autoUefa = session()->pull('admin_matches_auto_uefa');

        return $this->render(
            $userId,
            $leagueId,
            is_array($prefill) ? $prefill : null,
            'create',
            is_array($errors) ? $errors : [],
            $tab,
            is_array($auto) ? $auto : null,
            is_array($autoUefa) ? $autoUefa : null,
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

        return redirect()
            ->route('admin.matches', $redirectQuery)
            ->with('admin_matches_auto', $result['auto'])
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAuto(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('league_id', 0);
        $matches = RequestJsonArray::pull($request, 'matches_json', 'matches');

        $sourceName = (string) ($request->input('source_name') ?? '');
        $result = $this->matches->createMatchesFromDraft($matches, $leagueId, $sourceName);

        $redirectQuery = ['tab' => 'auto'];
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        if ($resultLeagueId > 0) {
            $redirectQuery['league_id'] = $resultLeagueId;
        }

        if (! ($result['ok'] ?? false)) {
            $redirect = redirect()
                ->route('admin.matches', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Anlegen fehlgeschlagen.']);

            if (isset($result['auto']) && is_array($result['auto'])) {
                $redirect->with('admin_matches_auto', $result['auto']);
            }

            return $redirect;
        }

        return redirect()
            ->route('admin.matches', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    public function analyzeAutoUefa(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('league_id', 0);
        $result = $this->matches->analyzeUefaMatches($leagueId);

        $redirectQuery = ['tab' => 'auto-uefa'];
        if ($leagueId > 0) {
            $redirectQuery['league_id'] = $leagueId;
        }

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.matches', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Analyse fehlgeschlagen.']);
        }

        return redirect()
            ->route('admin.matches', $redirectQuery)
            ->with('admin_matches_auto_uefa', $result['auto_uefa'])
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAutoUefa(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('league_id', 0);
        $rows = RequestJsonArray::pull($request, 'rows_json', 'rows');

        $sourceName = (string) ($request->input('source_name') ?? '');
        $result = $this->matches->saveUefaMatches($rows, $leagueId, $sourceName);

        $redirectQuery = ['tab' => 'auto-uefa'];
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        if ($resultLeagueId > 0) {
            $redirectQuery['league_id'] = $resultLeagueId;
        }

        if (! ($result['ok'] ?? false)) {
            $redirect = redirect()
                ->route('admin.matches', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);

            if (isset($result['auto_uefa']) && is_array($result['auto_uefa'])) {
                $redirect->with('admin_matches_auto_uefa', $result['auto_uefa']);
            }

            return $redirect;
        }

        return redirect()
            ->route('admin.matches', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  list<string>  $errors
     * @param  array<string, mixed>|null  $auto
     * @param  array<string, mixed>|null  $autoUefa
     */
    private function render(
        int $userId,
        int $leagueId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
        string $tab = 'manual',
        ?array $auto = null,
        ?array $autoUefa = null,
    ): View {
        return view('admin.matches', [
            'data' => $this->matches->pagePayload($userId, $leagueId, $form, $mode, $tab, $auto, $autoUefa),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
