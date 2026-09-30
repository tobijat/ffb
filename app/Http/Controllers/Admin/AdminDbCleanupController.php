<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDbCleanupService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;
use Throwable;

class AdminDbCleanupController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminDbCleanupService $cleanup,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

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

        try {
            $result = $this->cleanup->runTask($task);
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
