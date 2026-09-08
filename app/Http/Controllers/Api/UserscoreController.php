<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserscoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserscoreController extends Controller
{
    public function __construct(
        private readonly UserscoreService $userscores,
    ) {
    }

    public function matchrounds(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->userscores->matchrounds($userId);

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

    public function overall(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->userscores->overall(
            $userId,
            (string) $request->query('sort', ''),
            (string) $request->query('dir', 'desc'),
        );

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

    public function round(Request $request, int $matchroundId): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->userscores->forRound(
            $userId,
            $matchroundId,
            (string) $request->query('sort', ''),
            (string) $request->query('dir', 'desc'),
        );

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
