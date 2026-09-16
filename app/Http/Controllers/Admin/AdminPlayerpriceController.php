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
        $leagueId = (int) $request->query('price_league_id', 0);
        $matchroundId = (int) $request->query('matchround_id', 0);
        $tab = $this->resolveTab($request->query('tab'));

        return $this->render(
            $request,
            $leagueId > 0 ? $leagueId : null,
            $tab,
            $matchroundId > 0 ? $matchroundId : null,
        );
    }

    public function setMatchroundPlayerPrices(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->calculatePlayerPricesForMatchround($userId, $request->all());

        return $this->redirectFromResult($result, $request)->withInput($request->only([
            'matchround_id',
            'price_margin',
            'price_league_id',
        ]));
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
     * @param  array{ok: bool, message?: string, errors?: list<string>, details?: list<string>, price_league_id?: int, tab?: string, matchround_id?: int}  $result
     */
    private function redirectFromResult(array $result, Request $request): RedirectResponse
    {
        $leagueId = (int) ($result['price_league_id'] ?? $request->input('price_league_id', 0));
        $tab = $this->resolveTab($result['tab'] ?? $request->input('tab'));
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));
        $redirect = redirect()->route('admin.playerprice', array_filter([
            'price_league_id' => $leagueId > 0 ? $leagueId : null,
            'tab' => $tab !== 'teams' ? $tab : null,
            'matchround_id' => ($tab === 'teams' && $matchroundId > 0) ? $matchroundId : null,
        ], static fn ($v) => $v !== null));

        if (! ($result['ok'] ?? false)) {
            return $redirect->with('admin_errors', $result['errors'] ?? ['Unbekannter Fehler.']);
        }

        return $redirect
            ->with('admin_message', $result['message'] ?? 'OK')
            ->with('admin_details', $result['details'] ?? []);
    }

    /**
     * @param  array<string, mixed>|null  $teamPricePreview
     * @param  list<string>  $details
     */
    private function render(
        Request $request,
        ?int $leagueId,
        string $tab,
        ?int $matchroundId = null,
        ?array $teamPricePreview = null,
        ?string $answer = null,
        array $details = [],
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
            ),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => $answer ?? session('admin_message'),
            'details' => $details !== [] ? $details : (is_array($sessionDetails) ? $sessionDetails : []),
            'legacyBase' => '/',
        ]);
    }

    private function resolveTab(mixed $tab): string
    {
        return $tab === 'players' ? 'players' : 'teams';
    }
}
