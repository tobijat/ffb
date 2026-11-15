<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminPlayerMergeService;
use App\Services\AdminPlayerService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;
use Throwable;

class AdminPlayerController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminPlayerService $players,
        private readonly AdminPlayerMergeService $playerMerge,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');
        $tab = trim((string) $request->query('tab', 'manual'));

        return $this->render(
            $userId,
            null,
            'create',
            is_array($errors) ? $errors : [],
            $tab,
        );
    }

    public function prepareDuplicates(Request $request): JsonResponse
    {
        try {
            $result = $this->players->prepareDuplicateNames(
                (int) $request->input('team_id', 0),
                $this->duplicateScope($request),
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Vorbereitung fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }

    public function checkDuplicate(Request $request): JsonResponse
    {
        try {
            $result = $this->players->checkDuplicateNamePlayer(
                (int) $request->input('team_id', 0),
                (int) $request->input('player_id', 0),
                $this->duplicateScope($request),
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Prüfung fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }

    public function mergeDuplicates(Request $request): JsonResponse
    {
        try {
            $groups = $request->input('groups', []);
            if (! is_array($groups)) {
                $groups = [];
            }
            $result = $this->players->mergeDuplicateNameGroups(
                (int) $request->input('team_id', 0),
                $groups,
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Zusammenführung fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }

    private function duplicateScope(Request $request): string
    {
        return trim((string) $request->input('scope', AdminPlayerService::DUPLICATE_SCOPE_NATIONALITY));
    }

    public function preparePlayerMerge(Request $request): JsonResponse
    {
        try {
            $result = $this->playerMerge->prepare(
                (int) $request->input('player_a_id', 0),
                (int) $request->input('player_b_id', 0),
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Merge-Vorbereitung fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }

    public function previewPlayerMerge(Request $request): JsonResponse
    {
        try {
            $result = $this->playerMerge->preview(
                (int) $request->input('keep_id', 0),
                (int) $request->input('drop_id', 0),
                is_array($request->input('fields')) ? $request->input('fields') : [],
                is_array($request->input('playerteam_merges')) ? $request->input('playerteam_merges') : [],
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Merge-Vorschau fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }

    public function executePlayerMerge(Request $request): JsonResponse
    {
        try {
            $confirm = $request->boolean('confirm');
            $result = $this->playerMerge->execute(
                (int) $request->input('keep_id', 0),
                (int) $request->input('drop_id', 0),
                is_array($request->input('fields')) ? $request->input('fields') : [],
                $confirm,
                is_array($request->input('playerteam_merges')) ? $request->input('playerteam_merges') : [],
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Merge fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->players->search([
            'q' => $request->query('q', ''),
            'nationality' => $request->query('nationality', ''),
            'page' => $request->query('page', 1),
            'exclude_team_id' => $request->query('exclude_team_id', 0),
            'exclude_league_id' => $request->query('exclude_league_id', 0),
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
        string $tab = 'manual',
    ): View {
        return view('admin.players', [
            'data' => $this->players->pagePayload($userId, $form, $mode, $tab),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
