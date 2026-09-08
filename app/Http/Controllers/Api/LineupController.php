<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LineupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LineupController extends Controller
{
    public function __construct(
        private readonly LineupService $lineups,
    ) {
    }

    public function options(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');

        return $this->respond($this->lineups->options($userId));
    }

    public function matchround(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');

        return $this->respond($this->lineups->matchroundAndTeams($userId));
    }

    public function teamPlayers(Request $request, int $teamId): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $matchroundId = (int) $request->query('matchround_id', 0);

        return $this->respond($this->lineups->teamPlayers($userId, $teamId, $matchroundId));
    }

    /**
     * GET /api/lineup?matchround_id=…
     */
    public function show(Request $request): JsonResponse
    {
        $matchroundId = (int) $request->query('matchround_id', 0);
        if ($matchroundId <= 0) {
            return response()->json([
                'status' => 422,
                'error' => 'matchround_id is required',
            ], 422);
        }

        $userId = (int) $request->attributes->get('ffb_user_id');
        $payload = $this->lineups->getForRound($userId, $matchroundId);

        return response()->json([
            'status' => 200,
            'data' => $payload,
        ]);
    }

    /**
     * POST /api/lineup
     */
    public function store(Request $request): JsonResponse
    {
        $matchroundId = (int) $request->input('matchround_id', 0);
        if ($matchroundId <= 0) {
            return response()->json([
                'status' => 422,
                'error' => 'matchround_id is required',
            ], 422);
        }

        $rawIds = $request->input('playerteam_ids', $request->input('lineup', []));
        if (is_string($rawIds)) {
            $rawIds = explode(',', $rawIds);
        }
        if (! is_array($rawIds)) {
            $rawIds = [];
        }

        $userId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->lineups->saveForRound($userId, $matchroundId, $rawIds);

        if (! $result['ok']) {
            return response()->json([
                'status' => $result['status'],
                'error' => $result['error'],
            ], $result['status']);
        }

        return response()->json([
            'status' => 200,
            'message' => $result['message'],
            'data' => $result['data'],
        ]);
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
