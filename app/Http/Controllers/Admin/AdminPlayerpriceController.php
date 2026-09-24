<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminPlayerpriceService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPlayerpriceController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminPlayerpriceService $playerprice,
    ) {}

    public function show(Request $request): View
    {
        $matchroundId = (int) $request->query('matchround_id', 0);
        $tab = $this->resolveTab($request->query('tab'));

        return $this->render(
            $request,
            null,
            $tab,
            $matchroundId > 0 ? $matchroundId : null,
        );
    }

    public function previewMatchroundPerformance(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->previewMatchroundPerformance($userId, $request->all());
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.playerprice', array_filter([
                    'price_league_id' => $leagueId > 0 ? $leagueId : null,
                    'tab' => 'performance',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ], static fn ($v) => $v !== null))
                ->with('admin_errors', $result['errors'] ?? ['Berechnung fehlgeschlagen.'])
                ->withInput($request->only([
                    'price_league_id',
                    'matchround_id',
                    'include_opponent_strength',
                    'opponent_weight',
                ]));
        }

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            'performance',
            $matchroundId > 0 ? $matchroundId : null,
            null,
            (string) ($result['message'] ?? ''),
            [],
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
        );
    }

    public function saveMatchroundPerformance(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->saveMatchroundPerformance($userId, $request->all());
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));
        $preview = is_array($result['preview'] ?? null) ? $result['preview'] : null;

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.playerprice', array_filter([
                    'price_league_id' => $leagueId > 0 ? $leagueId : null,
                    'tab' => 'performance',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ], static fn ($v) => $v !== null))
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.'])
                ->withInput($request->only([
                    'price_league_id',
                    'matchround_id',
                    'include_opponent_strength',
                    'opponent_weight',
                ]));
        }

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            'performance',
            $matchroundId > 0 ? $matchroundId : null,
            null,
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            $preview,
        );
    }

    public function previewRecentPerformance(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->previewRecentPerformance($userId, $request->all());
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.playerprice', array_filter([
                    'price_league_id' => $leagueId > 0 ? $leagueId : null,
                    'tab' => 'recent',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ], static fn ($v) => $v !== null))
                ->with('admin_errors', $result['errors'] ?? ['Berechnung fehlgeschlagen.'])
                ->withInput($request->only([
                    'price_league_id',
                    'matchround_id',
                    'lookback_rounds',
                    'decay_factor',
                    'max_price_adjustment',
                    'include_external_rounds',
                ]));
        }

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            'recent',
            $matchroundId > 0 ? $matchroundId : null,
            null,
            (string) ($result['message'] ?? ''),
            [],
            null,
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
        );
    }

    public function saveRecentPerformance(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->saveRecentPerformance($userId, $request->all());
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));
        $preview = is_array($result['preview'] ?? null) ? $result['preview'] : null;

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.playerprice', array_filter([
                    'price_league_id' => $leagueId > 0 ? $leagueId : null,
                    'tab' => 'recent',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ], static fn ($v) => $v !== null))
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.'])
                ->withInput($request->only([
                    'price_league_id',
                    'matchround_id',
                    'lookback_rounds',
                    'decay_factor',
                    'max_price_adjustment',
                    'include_external_rounds',
                    'save_player_prices',
                ]));
        }

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            'recent',
            $matchroundId > 0 ? $matchroundId : null,
            null,
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            null,
            $preview,
        );
    }

    public function previewEloTeamPrices(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->previewEloTeamPrices($userId, $request->all());
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.playerprice', array_filter([
                    'price_league_id' => $leagueId > 0 ? $leagueId : null,
                    'tab' => 'teams',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ], static fn ($v) => $v !== null))
                ->with('admin_errors', $result['errors'] ?? ['Berechnung fehlgeschlagen.'])
                ->withInput($request->only([
                    'price_league_id',
                    'matchround_id',
                    'elo_year',
                    'max_credits',
                    'max_players_team',
                    'exponent',
                    'dream_team_ratio',
                    'min_price',
                ]));
        }

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            'teams',
            $matchroundId > 0 ? $matchroundId : null,
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
            (string) ($result['message'] ?? ''),
        );
    }

    public function saveEloTeamPrices(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->saveEloTeamPrices($userId, $request->all());
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));
        $preview = is_array($result['preview'] ?? null) ? $result['preview'] : null;

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.playerprice', array_filter([
                    'price_league_id' => $leagueId > 0 ? $leagueId : null,
                    'tab' => 'teams',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ], static fn ($v) => $v !== null))
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.'])
                ->withInput($request->only([
                    'price_league_id',
                    'matchround_id',
                    'elo_year',
                    'max_credits',
                    'max_players_team',
                    'exponent',
                    'dream_team_ratio',
                    'min_price',
                ]));
        }

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            'teams',
            $matchroundId > 0 ? $matchroundId : null,
            $preview,
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
        );
    }

    /**
     * @param  array<string, mixed>|null  $teamPricePreview
     * @param  list<string>  $details
     * @param  array<string, mixed>|null  $performancePreview
     * @param  array<string, mixed>|null  $recentPerformancePreview
     */
    private function render(
        Request $request,
        ?int $leagueId,
        string $tab,
        ?int $matchroundId = null,
        ?array $teamPricePreview = null,
        ?string $answer = null,
        array $details = [],
        ?array $performancePreview = null,
        ?array $recentPerformancePreview = null,
    ): View {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');
        $sessionDetails = session('admin_details');

        return view('admin.playerprice', [
            'data' => $this->playerprice->pagePayload(
                $userId,
                $leagueId,
                $tab,
                $matchroundId,
                $teamPricePreview,
                $performancePreview,
                $recentPerformancePreview,
            ),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => $answer ?? session('admin_message'),
            'details' => $details !== [] ? $details : (is_array($sessionDetails) ? $sessionDetails : []),
            'legacyBase' => '/',
        ]);
    }

    private function resolveTab(mixed $tab): string
    {
        return match ($tab) {
            'performance' => 'performance',
            'recent' => 'recent',
            default => 'teams',
        };
    }
}
