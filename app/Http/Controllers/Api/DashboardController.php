<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $page = max(1, (int) $request->query('news_page', 1));
        $archive = (bool) $request->boolean('archive');

        return response()->json([
            'status' => 200,
            'data' => $this->dashboard->payload($userId, $page, $archive),
        ]);
    }

    public function selectLeague(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $leagueId = (int) $request->input('league_id', 0);
        $result = $this->dashboard->selectLeague($userId, $leagueId);

        if (! $result['ok']) {
            return response()->json([
                'status' => $result['status'],
                'error' => $result['error'],
            ], $result['status']);
        }

        return response()->json([
            'status' => 200,
            'message' => 'League selected',
            'data' => [
                'selected_league_id' => $result['selected_league_id'],
                'league_title' => $result['league_title'],
            ],
        ]);
    }

    public function votePoll(Request $request): JsonResponse
    {
        $userId = (int) $request->attributes->get('ffb_user_id');
        $pollId = (int) $request->input('poll_id', 0);
        $answerId = (int) $request->input('poll_answer_id', 0);
        $text = (string) $request->input('poll_answer', '');
        $type = (string) $request->input('poll_type', 'select');

        $result = $type === 'text'
            ? $this->dashboard->voteText($userId, $pollId, $answerId, $text)
            : $this->dashboard->voteSelect($userId, $pollId, $answerId);

        if (! $result['ok']) {
            return response()->json([
                'status' => $result['status'],
                'error' => $result['error'],
            ], $result['status']);
        }

        $page = max(1, (int) $request->input('news_page', 1));
        $archive = (bool) $request->boolean('archive');

        return response()->json([
            'status' => 200,
            'message' => 'Vote saved',
            'data' => $this->dashboard->payload($userId, $page, $archive),
        ]);
    }
}
