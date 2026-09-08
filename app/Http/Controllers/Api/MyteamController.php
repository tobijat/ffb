<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MyteamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyteamController extends Controller
{
    public function __construct(
        private readonly MyteamService $myteam,
    ) {
    }

    public function matchrounds(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->myteam->matchrounds($userId);

        return $this->respond($result);
    }

    public function users(Request $request): JsonResponse
    {
        $matchroundId = (int) $request->query('matchround_id', 0);
        $result = $this->myteam->usersWithTeams($matchroundId);

        return $this->respond($result);
    }

    public function team(Request $request): JsonResponse
    {
        $viewerId = (int) $request->attributes->get('ffb_user_id');
        $matchroundId = (int) $request->query('matchround_id', 0);
        $targetUserId = (int) $request->query(
            'userteam_user_id',
            $request->query('target_user_id', $viewerId)
        );
        $viewer = $request->attributes->get('ffb_user');
        $isAdmin = (bool) ($viewer?->user_admin ?? false);

        $result = $this->myteam->teamForRound($viewerId, $targetUserId, $matchroundId, $isAdmin);

        return $this->respond($result);
    }

    public function userStats(Request $request): JsonResponse
    {
        $viewerId = (int) $request->attributes->get('ffb_user_id');
        $matchroundId = (int) $request->query('matchround_id', 0);
        $targetUserId = (int) $request->query(
            'userteam_user_id',
            $request->query('target_user_id', $viewerId)
        );
        $viewer = $request->attributes->get('ffb_user');
        $isAdmin = (bool) ($viewer?->user_admin ?? false);

        $result = $this->myteam->userStats($viewerId, $targetUserId, $matchroundId, $isAdmin);

        return $this->respond($result);
    }

    public function roundStats(Request $request): JsonResponse
    {
        $matchroundId = (int) $request->query('matchround_id', 0);
        $result = $this->myteam->roundStats($matchroundId);

        return $this->respond($result);
    }

    /**
     * @param  array{ok: true, data: array<string, mixed>}|array{ok: false, status: int, error: string}  $result
     */
    private function respond(array $result): JsonResponse
    {
        if (! $result['ok']) {
            return response()->json([
                'status' => $result['status'],
                'error' => $result['error'],
            ], $result['status']);
        }

        return response()->json([
            'status' => 200,
            'data' => $result['data'],
        ]);
    }
}
