<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfilePopupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function __construct(
        private readonly ProfilePopupService $profiles,
    ) {
    }

    public function user(Request $request, int $userId): JsonResponse
    {
        $viewerId = (int) $request->attributes->get('ffb_user_id');
        $result = $this->profiles->forUser($viewerId, $userId);

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
