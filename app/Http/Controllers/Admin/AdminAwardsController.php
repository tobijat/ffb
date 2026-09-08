<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAwardsService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAwardsController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminAwardsService $awards,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');

        return view('admin.awards', [
            'data' => $this->awards->pagePayload($userId),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }

    public function createGroup(Request $request): RedirectResponse
    {
        $result = $this->awards->createGroup($request->all());
        $redirect = redirect()->route('admin.awards');

        if (! ($result['ok'] ?? false)) {
            return $redirect->with('admin_errors', $result['errors'] ?? ['Fehler.']);
        }

        return $redirect->with('admin_message', $result['message'] ?? 'OK');
    }

    public function group(int $group): JsonResponse
    {
        $payload = $this->awards->groupDetails($group);
        if ($payload === null) {
            return response()->json(['ok' => false, 'error' => 'Gruppe nicht gefunden.'], 404);
        }

        return response()->json(['ok' => true, ...$payload]);
    }

    public function updateGroup(Request $request): JsonResponse
    {
        $result = $this->awards->updateGroup($request->all());

        return response()->json([
            'ok' => $result['ok'],
            'answer' => [
                'status' => $result['status'],
                'text' => $result['text'],
            ],
        ], $result['ok'] ? 200 : 500);
    }

    public function createDefine(Request $request): JsonResponse
    {
        $result = $this->awards->createDefine($request->all());

        return response()->json([
            'ok' => $result['ok'],
            'answer' => [
                'status' => $result['status'],
                'text' => $result['text'],
            ],
        ], $result['ok'] ? 200 : 500);
    }

    public function updateDefine(Request $request): JsonResponse
    {
        $result = $this->awards->updateDefine($request->all());

        return response()->json([
            'ok' => $result['ok'],
            'answer' => [
                'status' => $result['status'],
                'text' => $result['text'],
            ],
        ], $result['ok'] ? 200 : 500);
    }

    public function finished(int $define): JsonResponse
    {
        return response()->json($this->awards->finishedForDefine($define));
    }

    public function deleteFinished(int $finished): JsonResponse
    {
        $result = $this->awards->deleteFinished($finished);

        return response()->json($result, $result['ok'] ? 200 : 404);
    }

    public function calculateDefine(Request $request, int $define): JsonResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->awards->calculateDefine($define, $userId);

        return response()->json($result);
    }

    public function calculateAll(Request $request): JsonResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->awards->calculateAll($userId);

        return response()->json($result);
    }
}
