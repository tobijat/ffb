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

    /**
     * GET /api/lineup?matchround_id=…
     * Auth: Laravel session (ffb_user_id), optional local X-FFB-User-Id bridge.
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
     * Body: matchround_id + playerteam_ids[11] (or legacy lineup="id,id,...")
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
}
