<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMatchdataService;
use App\Services\WeltfussballProxyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Auth;

class AdminMatchdataController extends Controller
{
    public function __construct(
        private readonly AdminMatchdataService $matchdata,
        private readonly WeltfussballProxyService $wfProxy,
    ) {}

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();
        $tab = match ($request->query('tab')) {
            'uefa' => 'uefa',
            'fifa' => 'fifa',
            default => 'wf',
        };

        return view('admin.matchdata', [
            'data' => $this->matchdata->pagePayload($userId),
            'tab' => $tab,
            'legacyBase' => '/',
        ]);
    }

    public function rounds(Request $request): JsonResponse
    {
        $userId = (int) Auth::id();

        return response()->json([
            'ok' => true,
            'rounds' => $this->matchdata->rounds($userId),
        ]);
    }

    public function matches(int $round): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'matches' => $this->matchdata->matchesForRound($round),
        ]);
    }

    public function mostWanted(int $round): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'teams' => $this->matchdata->mostWanted($round),
        ]);
    }

    public function players(Request $request, int $match, int $team): JsonResponse
    {
        $userId = (int) Auth::id();
        $allPlayers = $request->boolean('all_players');

        return response()->json([
            'ok' => true,
            'players' => $this->matchdata->playersForTeam($userId, $team, $match, $allPlayers),
            'pointsmode' => $this->matchdata->pointsMode($userId),
        ]);
    }

    public function setResult(Request $request, int $match): JsonResponse
    {
        $result = $this->matchdata->setMatchResult($match, $request->all());

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'] ?? null,
            'errors' => $result['errors'] ?? [],
        ], ($result['ok'] ?? false) ? 200 : 422);
    }

    public function savePlayer(Request $request, int $match, int $playerteam): JsonResponse
    {
        $result = $this->matchdata->savePlayerStats($match, $playerteam, $request->all());

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'] ?? null,
            'errors' => $result['errors'] ?? [],
        ], ($result['ok'] ?? false) ? 200 : 422);
    }

    public function scrape(Request $request, int $match): JsonResponse
    {
        $userId = (int) Auth::id();
        $url = (string) $request->input('url', '');
        $cookies = $request->filled('cookies') ? (string) $request->input('cookies') : null;
        $html = $request->filled('html') ? (string) $request->input('html') : null;
        $useCachedHtml = $request->boolean('use_cached_html');
        $result = $this->matchdata->scrapeMatchData($userId, $match, $url, $cookies, $html, $useCachedHtml);

        $status = ($result['ok'] ?? false) || ($result['challenge'] ?? false) ? 200 : 422;

        return response()->json($result, $status);
    }

    public function scrapeUefa(Request $request, int $match): JsonResponse
    {
        $userId = (int) Auth::id();
        $result = $this->matchdata->scrapeUefaMatchData($userId, $match);

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }

    public function scrapeFifa(Request $request, int $match): JsonResponse
    {
        $userId = (int) Auth::id();
        $result = $this->matchdata->scrapeFifaMatchData($userId, $match);

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }

    public function wfProxy(Request $request): SymfonyResponse
    {
        return $this->wfProxy->handleBrowserRequest($request);
    }
}
