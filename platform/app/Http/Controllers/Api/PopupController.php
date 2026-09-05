<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MatchPopupService;
use App\Services\ProfilePopupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function __construct(
        private readonly ProfilePopupService $profiles,
        private readonly MatchPopupService $matches,
    ) {
    }

    public function user(Request $request, int $userId): JsonResponse
    {
        $viewerId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->profiles->forUser($viewerId, $userId);

        return $this->respond($result);
    }

    public function match(int $matchId): JsonResponse
    {
        $result = $this->matches->forMatch($matchId);

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
