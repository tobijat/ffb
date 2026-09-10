<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMailserviceService;
use App\Services\FfbAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMailserviceController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminMailserviceService $mailservice,
    ) {}

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);

        return view('admin.mailservice', [
            'data' => $this->mailservice->pagePayload($userId),
            'errors' => [],
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }

    public function matchrounds(Request $request): JsonResponse
    {
        $leagueId = (int) $request->input('league_id', 0);
        $items = $this->mailservice->matchroundsForGame($leagueId);

        return response()->json([
            'numResults' => count($items),
            'matchrounds' => $items,
        ]);
    }

    public function users(Request $request): JsonResponse
    {
        $items = $this->mailservice->users([
            'league_id' => $request->input('league_id', 0),
            'matchround_id' => $request->input('matchround_id', 0),
            'mailservice' => $request->input('mailservice', ''),
            'userstatus' => $request->input('userstatus', ''),
        ]);

        return response()->json([
            'numResults' => count($items),
            'users' => $items,
        ]);
    }

    public function mail(Request $request, int $mail): JsonResponse
    {
        $payload = $this->mailservice->mailById($mail);
        if ($payload === null) {
            return response()->json([
                'ok' => false,
                'error' => 'Mail not found.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'numResults' => count($payload['users']),
            'mail' => $payload['mail'],
            'users' => $payload['users'],
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $userId = $this->auth->userId($request);

        $userIds = $request->input('user_ids', []);
        if (is_string($userIds)) {
            $userIds = array_filter(array_map('trim', explode(',', $userIds)));
        }
        if (! is_array($userIds)) {
            $userIds = [];
        }

        $result = $this->mailservice->send(
            $userIds,
            (string) $request->input('subject', ''),
            (string) $request->input('text', ''),
            (string) $request->input('type', ''),
            $userId,
            (string) $request->getHost(),
        );

        return response()->json([
            'ok' => $result['ok'],
            'status' => $result['ok'] ? 200 : 500,
            'answer' => $result['message'],
            'num_send' => $result['num_send'] ?? 0,
        ], $result['ok'] ? 200 : 500);
    }
}
