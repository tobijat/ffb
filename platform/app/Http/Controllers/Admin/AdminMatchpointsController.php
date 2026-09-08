<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCenterService;
use App\Services\AdminMatchpointsService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMatchpointsController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminMatchpointsService $matchpoints,
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

        if ($request->filled('game_id')) {
            $this->adminCenter->selectGame((int) $request->input('game_id'));
        }

        return view('admin.matchpoints', [
            'data' => $this->matchpoints->pagePayload($userId),
            'legacyBase' => '/',
        ]);
    }

    public function rounds(Request $request): JsonResponse
    {
        $userId = $this->auth->userId($request);

        return response()->json([
            'ok' => true,
            'rounds' => $this->matchpoints->rounds($userId),
        ]);
    }

    public function matches(int $round): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'matches' => $this->matchpoints->matchesForRound($round),
        ]);
    }

    public function mostWanted(int $round): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'teams' => $this->matchpoints->mostWanted($round),
        ]);
    }

    public function players(Request $request, int $match, int $team): JsonResponse
    {
        $userId = $this->auth->userId($request);
        $allPlayers = $request->boolean('all_players');

        return response()->json([
            'ok' => true,
            'players' => $this->matchpoints->playersForTeam($userId, $team, $match, $allPlayers),
            'pointsmode' => $this->matchpoints->pointsMode($userId),
        ]);
    }

    public function setResult(Request $request, int $match): JsonResponse
    {
        $result = $this->matchpoints->setMatchResult($match, $request->all());

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'] ?? null,
            'errors' => $result['errors'] ?? [],
        ], ($result['ok'] ?? false) ? 200 : 422);
    }

    public function savePlayer(Request $request, int $match, int $playerteam): JsonResponse
    {
        $result = $this->matchpoints->savePlayerStats($match, $playerteam, $request->all());

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'] ?? null,
            'errors' => $result['errors'] ?? [],
        ], ($result['ok'] ?? false) ? 200 : 422);
    }
}
