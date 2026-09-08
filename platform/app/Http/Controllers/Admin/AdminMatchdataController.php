<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCenterService;
use App\Services\AdminMatchdataService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMatchdataController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminMatchdataService $matchdata,
        private readonly AdminCenterService $adminCenter,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

        if ($request->filled('game_id')) {
            $this->adminCenter->selectGame((int) $request->input('game_id'));
        }

        return view('admin.matchdata', [
            'data' => $this->matchdata->pagePayload($userId),
            'legacyBase' => '/',
        ]);
    }

    public function rounds(Request $request): JsonResponse
    {
        $userId = $this->auth->userId($request);

        return response()->json([
            'ok' => true,
            'rounds' => $this->matchdata->rounds($userId),
        ]);
    }

    public function matches(int $round): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'matches' => $this->matchdata->matchesForRound($round),
        ]);
    }

    public function mostWanted(int $round): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'teams' => $this->matchdata->mostWanted($round),
        ]);
    }

    public function players(Request $request, int $match, int $team): JsonResponse
    {
        $userId = $this->auth->userId($request);
        $allPlayers = $request->boolean('all_players');

        return response()->json([
            'ok' => true,
            'players' => $this->matchdata->playersForTeam($userId, $team, $match, $allPlayers),
            'pointsmode' => $this->matchdata->pointsMode($userId),
        ]);
    }

    public function setResult(Request $request, int $match): JsonResponse
    {
        $result = $this->matchdata->setMatchResult($match, $request->all());

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'] ?? null,
            'errors' => $result['errors'] ?? [],
        ], ($result['ok'] ?? false) ? 200 : 422);
    }

    public function savePlayer(Request $request, int $match, int $playerteam): JsonResponse
    {
        $result = $this->matchdata->savePlayerStats($match, $playerteam, $request->all());

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'] ?? null,
            'errors' => $result['errors'] ?? [],
        ], ($result['ok'] ?? false) ? 200 : 422);
    }
}
