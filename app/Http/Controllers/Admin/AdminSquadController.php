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

        return $this->render(
            $userId,
            $teamId,
            $leagueId > 0 ? $leagueId : null,
            is_array($errors) ? $errors : [],
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
     * @param  list<string>  $errors
     */
    private function render(int $userId, int $teamId, ?int $squadLeagueId, array $errors = []): View
    {
        return view('admin.squad', [
            'data' => $this->squad->pagePayload($userId, $teamId, $squadLeagueId),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
