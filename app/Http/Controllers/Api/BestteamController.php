<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BestteamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BestteamController extends Controller
{
    public function __construct(
        private readonly BestteamService $bestteam,
    ) {
    }

    public function matchrounds(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->bestteam->matchrounds($userId);

        return $this->respond($result);
    }

    public function team(Request $request): JsonResponse
    {
        $matchroundId = (int) $request->query('matchround_id', 0);
        $type = (string) $request->query('type', 'top');
        $result = $this->bestteam->bestTeam($matchroundId, $type);

        return $this->respond($result);
    }

    public function roundStats(Request $request): JsonResponse
    {
        $matchroundId = (int) $request->query('matchround_id', 0);
        $result = $this->bestteam->roundStats($matchroundId);

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
