<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminSquadService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSquadController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminSquadService $squad,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $teamId = (int) $request->query('team_id', 0);
        $leagueId = (int) $request->query('squad_league_id', 0);
        $errors = session('admin_errors');
        $tab = match ($request->query('tab')) {
            'add' => 'add',
            'auto' => 'auto',
            default => 'roster',
        };
        $auto = session('admin_squad_auto');
        if (is_array($auto)) {
            $autoTeamId = (int) ($auto['team_id'] ?? 0);
            $autoLeagueId = (int) ($auto['league_id'] ?? 0);
            if ($autoTeamId !== $teamId || ($leagueId > 0 && $autoLeagueId !== $leagueId)) {
                $auto = null;
            }
        }

        return $this->render(
            $userId,
            $teamId,
            $leagueId > 0 ? $leagueId : null,
            is_array($errors) ? $errors : [],
            $tab,
            is_array($auto) ? $auto : null,
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $result = $this->squad->batchAdd($request->all());
        $params = $this->squadRedirectParams($request, $result);

        if ($result['ok']) {
            return redirect()->route('admin.squad', $params)
                ->with('admin_message', $result['message']);
        }

        return redirect()->route(
            'admin.squad',
            $params + ['tab' => 'add'],
        )->with('admin_errors', $result['errors'] ?? ['Hinzufügen fehlgeschlagen.']);
    }

    public function update(Request $request, int $playerteam): RedirectResponse
    {
        $result = $this->squad->update(
            $playerteam,
            $request->all(),
            $request->file('playerteam_picture_file'),
        );
        $redirect = redirect()->route('admin.squad', $this->squadRedirectParams($request, $result));

        if ($result['ok']) {
            return $redirect->with('admin_message', $result['message']);
        }

        return $redirect->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);
    }

    public function batchUpdate(Request $request): RedirectResponse
    {
        $items = $request->input('items', []);
        $pictureFiles = [];
        if (is_array($items)) {
            foreach (array_keys($items) as $rawId) {
                $id = (int) $rawId;
                if ($id > 0) {
                    $pictureFiles[$id] = $request->file('items.'.$id.'.playerteam_picture_file');
                }
            }
        }

        $result = $this->squad->batchUpdate($request->all(), $pictureFiles);
        $redirect = redirect()->route('admin.squad', $this->squadRedirectParams($request, $result));

        if ($result['ok']) {
            return $redirect->with('admin_message', $result['message']);
        }

        return $redirect->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);
    }

    public function destroy(Request $request, int $playerteam): RedirectResponse
    {
        $result = $this->squad->delete($playerteam);
        $redirect = redirect()->route('admin.squad', $this->squadRedirectParams($request, $result));

        if ($result['ok']) {
            return $redirect->with('admin_message', $result['message']);
        }

        return $redirect->with('admin_errors', $result['errors'] ?? ['Löschen fehlgeschlagen.']);
    }

    public function analyzeAuto(Request $request): RedirectResponse
    {
        $teamId = (int) $request->input('team_id', 0);
        $leagueId = (int) $request->input('squad_league_id', 0);
        $result = $this->squad->analyzeSquadsFile(
            $teamId,
            $leagueId,
            $request->file('squads_json'),
        );

        $redirectQuery = $this->autoRedirectParams($teamId, $leagueId);

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Analyse fehlgeschlagen.']);
        }

        session(['admin_squad_auto' => $result['auto']]);

        return redirect()
            ->route('admin.squad', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAuto(Request $request): RedirectResponse
    {
        $teamId = (int) $request->input('team_id', 0);
        $leagueId = (int) $request->input('squad_league_id', 0);
        /** @var list<array<string, mixed>>|array<int, array<string, mixed>> $players */
        $players = $request->input('players', []);
        if (! is_array($players)) {
            $players = [];
        }
        /** @var list<array<string, mixed>>|array<int, array<string, mixed>> $almost */
        $almost = $request->input('almost', []);
        if (! is_array($almost)) {
            $almost = [];
        }

        $sourceName = (string) ($request->input('source_name')
            ?: (session('admin_squad_auto.source_name') ?? ''));
        $fifaCode = (string) ($request->input('fifa_code')
            ?: (session('admin_squad_auto.fifa_code') ?? ''));
        $result = $this->squad->createSquadFromDraft(
            $players,
            $teamId,
            $leagueId,
            $sourceName,
            $fifaCode,
            $almost,
        );

        $resultTeamId = (int) ($result['team_id'] ?? $teamId);
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        $redirectQuery = $this->autoRedirectParams($resultTeamId, $resultLeagueId);

        if (! ($result['ok'] ?? false)) {
            if (isset($result['auto']) && is_array($result['auto'])) {
                session(['admin_squad_auto' => $result['auto']]);
            }

            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Übernehmen fehlgeschlagen.']);
        }

        session()->forget('admin_squad_auto');

        return redirect()
            ->route('admin.squad', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    /**
     * @param  array{team_id?: int}  $result
     * @return array<string, int>
     */
    private function squadRedirectParams(Request $request, array $result): array
    {
        $teamId = (int) ($result['team_id'] ?? $request->input('team_id', 0));
        $leagueId = (int) $request->input('squad_league_id', $request->query('squad_league_id', 0));

        return array_filter([
            'team_id' => $teamId > 0 ? $teamId : null,
            'squad_league_id' => $leagueId > 0 ? $leagueId : null,
        ]);
    }

    /**
     * @return array{tab: string, team_id?: int, squad_league_id?: int}
     */
    private function autoRedirectParams(int $teamId, int $leagueId): array
    {
        return array_filter([
            'tab' => 'auto',
            'team_id' => $teamId > 0 ? $teamId : null,
            'squad_league_id' => $leagueId > 0 ? $leagueId : null,
        ]);
    }

    /**
     * @param  list<string>  $errors
     * @param  array<string, mixed>|null  $auto
     */
    private function render(
        int $userId,
        int $teamId,
        ?int $squadLeagueId,
        array $errors = [],
        string $tab = 'roster',
        ?array $auto = null,
    ): View {
        return view('admin.squad', [
            'data' => $this->squad->pagePayload($userId, $teamId, $squadLeagueId, $tab, $auto),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
