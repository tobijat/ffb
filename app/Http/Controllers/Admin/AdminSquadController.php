<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminSquadService;
use App\Services\FfbAuth;
use App\Support\RequestJsonArray;
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
        $errors = session('admin_errors');
        $tab = match ($request->query('tab')) {
            'add' => 'add',
            'auto' => 'auto',
            'auto-uefa' => 'auto-uefa',
            'auto-fifa' => 'auto-fifa',
            'images' => 'images',
            default => 'roster',
        };
        $autoSessionKey = match ($tab) {
            'auto-uefa' => 'admin_squad_auto_uefa',
            'auto-fifa' => 'admin_squad_auto_fifa',
            default => 'admin_squad_auto',
        };
        $auto = session($autoSessionKey);
        if (is_array($auto)) {
            $autoTeamId = (int) ($auto['team_id'] ?? 0);
            if ($autoTeamId !== $teamId) {
                $auto = null;
            }
        }

        $uefaTeamId = $tab === 'auto-uefa'
            ? (string) $request->query('uefa_team_id', '')
            : null;
        $fifaTeamId = $tab === 'auto-fifa'
            ? (string) $request->query('fifa_team_id', '')
            : null;

        return $this->render(
            $userId,
            $teamId,
            null,
            is_array($errors) ? $errors : [],
            $tab,
            is_array($auto) ? $auto : null,
            null,
            null,
            $uefaTeamId,
            $fifaTeamId,
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
            $request->input('squads_json'),
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
        return $this->storeAutoDraft($request, 'auto');
    }

    public function analyzeAutoUefa(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('squad_league_id', 0);
        $uefaTeamId = (string) $request->input('uefa_team_id', '');
        $result = $this->squad->analyzeSquadsFromUefa($leagueId, $uefaTeamId);

        $resultTeamId = (int) ($result['team_id'] ?? 0);
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        $redirectQuery = $this->autoUefaRedirectParams(
            $resultTeamId,
            $resultLeagueId,
            (string) ($result['auto']['uefa_team_id'] ?? $uefaTeamId),
        );

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Analyse fehlgeschlagen.']);
        }

        session(['admin_squad_auto_uefa' => $result['auto']]);

        return redirect()
            ->route('admin.squad', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAutoUefa(Request $request): RedirectResponse
    {
        return $this->storeAutoDraft($request, 'auto-uefa');
    }

    public function analyzeAutoFifa(Request $request): RedirectResponse
    {
        $leagueId = (int) $request->input('squad_league_id', 0);
        $fifaTeamId = (string) $request->input('fifa_team_id', '');
        $result = $this->squad->analyzeSquadsFromFifa($leagueId, $fifaTeamId);

        $resultTeamId = (int) ($result['team_id'] ?? 0);
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        $redirectQuery = $this->autoFifaRedirectParams(
            $resultTeamId,
            $resultLeagueId,
            (string) ($result['auto']['fifa_team_id'] ?? $fifaTeamId),
        );

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Analyse fehlgeschlagen.']);
        }

        session(['admin_squad_auto_fifa' => $result['auto']]);

        return redirect()
            ->route('admin.squad', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    public function storeAutoFifa(Request $request): RedirectResponse
    {
        return $this->storeAutoDraft($request, 'auto-fifa');
    }

    public function checkImages(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $teamId = (int) $request->input('team_id', 0);
        $leagueId = (int) $request->input('squad_league_id', 0);
        /** @var array<int|string, mixed> $lookupNames */
        $lookupNames = $request->input('lookup_names', []);
        if (! is_array($lookupNames)) {
            $lookupNames = [];
        }

        $result = $this->squad->checkWikimediaImagesForSquadPlayers($teamId, $leagueId, $lookupNames);
        $redirectQuery = $this->imagesRedirectParams(
            (int) ($result['team_id'] ?? $teamId),
            (int) ($result['league_id'] ?? $leagueId),
        );

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Bilder prüfen fehlgeschlagen.']);
        }

        // Re-render immediately (no session) so a normal reload starts fresh.
        return $this->render(
            $userId,
            (int) ($result['team_id'] ?? $teamId),
            (int) ($result['league_id'] ?? $leagueId) > 0 ? (int) ($result['league_id'] ?? $leagueId) : null,
            [],
            'images',
            null,
            is_array($result['images'] ?? null) ? $result['images'] : null,
            (string) ($result['message'] ?? ''),
        );
    }

    public function applyImages(Request $request): RedirectResponse
    {
        $teamId = (int) $request->input('team_id', 0);
        $leagueId = (int) $request->input('squad_league_id', 0);
        /** @var list<array<string, mixed>>|array<int, array<string, mixed>> $rows */
        $rows = $request->input('players', []);
        if (! is_array($rows)) {
            $rows = [];
        }

        $result = $this->squad->applyWikimediaImagesForSquadPlayers($teamId, $leagueId, array_values($rows));
        $redirectQuery = $this->imagesRedirectParams(
            (int) ($result['team_id'] ?? $teamId),
            (int) ($result['league_id'] ?? $leagueId),
        );

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Bilder übernehmen fehlgeschlagen.']);
        }

        return redirect()
            ->route('admin.squad', $redirectQuery)
            ->with('admin_message', $result['message'] ?? null);
    }

    /**
     * @param  'auto'|'auto-uefa'|'auto-fifa'  $tab
     */
    private function storeAutoDraft(Request $request, string $tab): RedirectResponse
    {
        $teamId = (int) $request->input('team_id', 0);
        $leagueId = (int) $request->input('squad_league_id', 0);
        $players = RequestJsonArray::pull($request, 'players_json', 'players');
        $almost = RequestJsonArray::pull($request, 'almost_json', 'almost');

        $sessionKey = match ($tab) {
            'auto-uefa' => 'admin_squad_auto_uefa',
            'auto-fifa' => 'admin_squad_auto_fifa',
            default => 'admin_squad_auto',
        };
        $defaultSourceKind = match ($tab) {
            'auto-uefa' => 'uefa',
            'auto-fifa' => 'fifa',
            default => 'json',
        };
        $sourceName = (string) ($request->input('source_name')
            ?: (session($sessionKey.'.source_name') ?? ''));
        $fifaCode = (string) ($request->input('fifa_code')
            ?: (session($sessionKey.'.fifa_code') ?? ''));
        $sourceKind = (string) ($request->input('source_kind')
            ?: (session($sessionKey.'.source_kind') ?? $defaultSourceKind));
        $uefaTeamId = (string) ($request->input('uefa_team_id')
            ?: (session($sessionKey.'.uefa_team_id') ?? ''));
        $fifaTeamId = (string) ($request->input('fifa_team_id')
            ?: (session($sessionKey.'.fifa_team_id') ?? ''));

        $result = $this->squad->createSquadFromDraft(
            $players,
            $teamId,
            $leagueId,
            $sourceName,
            $fifaCode,
            $almost,
            $sourceKind,
            $uefaTeamId,
            $fifaTeamId,
        );

        $resultTeamId = (int) ($result['team_id'] ?? $teamId);
        $resultLeagueId = (int) ($result['league_id'] ?? $leagueId);
        $redirectQuery = match ($tab) {
            'auto-uefa' => $this->autoUefaRedirectParams($resultTeamId, $resultLeagueId, $uefaTeamId),
            'auto-fifa' => $this->autoFifaRedirectParams($resultTeamId, $resultLeagueId, $fifaTeamId),
            default => $this->autoRedirectParams($resultTeamId, $resultLeagueId),
        };

        if (! ($result['ok'] ?? false)) {
            if (isset($result['auto']) && is_array($result['auto'])) {
                session([$sessionKey => $result['auto']]);
            }

            return redirect()
                ->route('admin.squad', $redirectQuery)
                ->with('admin_errors', $result['errors'] ?? ['Übernehmen fehlgeschlagen.']);
        }

        session()->forget($sessionKey);

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
     * @return array{tab: string, team_id?: int, squad_league_id?: int, uefa_team_id?: string}
     */
    private function autoUefaRedirectParams(int $teamId, int $leagueId, string $uefaTeamId): array
    {
        return array_filter([
            'tab' => 'auto-uefa',
            'team_id' => $teamId > 0 ? $teamId : null,
            'squad_league_id' => $leagueId > 0 ? $leagueId : null,
            'uefa_team_id' => $uefaTeamId !== '' ? $uefaTeamId : null,
        ]);
    }

    /**
     * @return array{tab: string, team_id?: int, squad_league_id?: int, fifa_team_id?: string}
     */
    private function autoFifaRedirectParams(int $teamId, int $leagueId, string $fifaTeamId): array
    {
        return array_filter([
            'tab' => 'auto-fifa',
            'team_id' => $teamId > 0 ? $teamId : null,
            'squad_league_id' => $leagueId > 0 ? $leagueId : null,
            'fifa_team_id' => $fifaTeamId !== '' ? $fifaTeamId : null,
        ]);
    }

    /**
     * @return array{tab: string, team_id?: int, squad_league_id?: int}
     */
    private function imagesRedirectParams(int $teamId, int $leagueId): array
    {
        return array_filter([
            'tab' => 'images',
            'team_id' => $teamId > 0 ? $teamId : null,
            'squad_league_id' => $leagueId > 0 ? $leagueId : null,
        ]);
    }

    /**
     * @param  list<string>  $errors
     * @param  array<string, mixed>|null  $auto
     * @param  array<string, mixed>|null  $images
     */
    private function render(
        int $userId,
        int $teamId,
        ?int $squadLeagueId,
        array $errors = [],
        string $tab = 'roster',
        ?array $auto = null,
        ?array $images = null,
        ?string $answer = null,
        ?string $uefaTeamId = null,
        ?string $fifaTeamId = null,
    ): View {
        return view('admin.squad', [
            'data' => $this->squad->pagePayload(
                $userId,
                $teamId,
                $squadLeagueId,
                $tab,
                $auto,
                $images,
                $uefaTeamId,
                $fifaTeamId,
            ),
            'errors' => $errors,
            'answer' => $answer ?? session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
