<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminScoreService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScoreController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminScoreService $score,
    ) {}

    public function show(Request $request): View
    {
        return $this->render(
            $request,
            (string) $request->query('tab', 'userteam'),
            null,
            [],
            null,
            null,
            null,
            (int) $request->query('matchround_id', 0),
        );
    }

    public function calculateSubstitutions(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->score->calculateSubstitutions($userId, $request->all());
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));
        $tab = (string) ($result['tab'] ?? 'subs');

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.score', array_filter([
                    'tab' => $tab,
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ]))
                ->with('admin_errors', $result['errors'] ?? ['Berechnung fehlgeschlagen.']);
        }

        return $this->render(
            $request,
            'subs',
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            null,
            null,
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
            $matchroundId,
        );
    }

    public function saveSubstitutions(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->score->saveSubstitutions($userId, $request->all());
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));
        $tab = (string) ($result['tab'] ?? 'subs');

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.score', array_filter([
                    'tab' => $tab,
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ]))
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);
        }

        return $this->render(
            $request,
            'subs',
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            null,
            null,
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
            $matchroundId,
        );
    }

    public function calculateUserteamScores(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $input = $request->all();
        $result = $this->score->calculateUserteamScores($userId, $input);
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.score', array_filter([
                    'tab' => 'userteam',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ]))
                ->with('admin_errors', $result['errors'] ?? ['Berechnung fehlgeschlagen.']);
        }

        return $this->render(
            $request,
            'userteam',
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
            null,
            null,
            $matchroundId,
        );
    }

    public function saveUserteamScores(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $input = $request->all();
        $result = $this->score->saveUserteamScores($userId, $input);
        $matchroundId = (int) ($result['matchround_id'] ?? $request->input('matchround_id', 0));

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.score', array_filter([
                    'tab' => 'userteam',
                    'matchround_id' => $matchroundId > 0 ? $matchroundId : null,
                ]))
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);
        }

        return $this->render(
            $request,
            'userteam',
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
            null,
            null,
            $matchroundId,
        );
    }

    public function calculateUserScores(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->score->calculateUserScores($userId);

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.score', ['tab' => 'user'])
                ->with('admin_errors', $result['errors'] ?? ['Berechnung fehlgeschlagen.']);
        }

        return $this->render(
            $request,
            'user',
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            null,
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
        );
    }

    public function saveUserScores(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->score->saveUserScores($userId);

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('admin.score', ['tab' => 'user'])
                ->with('admin_errors', $result['errors'] ?? ['Speichern fehlgeschlagen.']);
        }

        return $this->render(
            $request,
            'user',
            (string) ($result['message'] ?? ''),
            is_array($result['details'] ?? null) ? $result['details'] : [],
            null,
            is_array($result['preview'] ?? null) ? $result['preview'] : null,
        );
    }

    /**
     * @param  list<string>  $details
     * @param  array<string, mixed>|null  $userteamPreview
     * @param  array<string, mixed>|null  $userPreview
     * @param  array<string, mixed>|null  $subsPreview
     */
    private function render(
        Request $request,
        string $tab,
        ?string $answer = null,
        array $details = [],
        ?array $userteamPreview = null,
        ?array $userPreview = null,
        ?array $subsPreview = null,
        ?int $matchroundId = null,
    ): View {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');
        $sessionDetails = session('admin_details');

        return view('admin.score', [
            'data' => $this->score->pagePayload(
                $userId,
                $tab,
                $userteamPreview,
                $userPreview,
                $matchroundId,
                $subsPreview,
            ),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => $answer ?? session('admin_message'),
            'details' => $details !== [] ? $details : (is_array($sessionDetails) ? $sessionDetails : []),
            'legacyBase' => '/',
        ]);
    }
}
