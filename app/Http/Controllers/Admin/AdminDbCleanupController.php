<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDbCleanupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;
use Throwable;
use Illuminate\Support\Facades\Auth;

class AdminDbCleanupController extends Controller
{
    public function __construct(
        private readonly AdminDbCleanupService $cleanup,
    ) {}

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();

        return view('admin.db-cleanup', [
            'data' => $this->cleanup->pagePayload($userId),
            'errors' => [],
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }

    public function run(Request $request): JsonResponse
    {
        $task = trim((string) $request->input('task', ''));
        $action = trim((string) $request->input('action', 'run'));
        $options = [
            'team_id' => (int) $request->input('team_id', 0),
        ];

        try {
            $result = $this->cleanup->runTask($task, $action, $options);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'Aufgabe fehlgeschlagen.',
            ], 500);
        }

        return response()->json($result);
    }
}
