<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMatchpointsConfigService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMatchpointsConfigController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminMatchpointsConfigService $matchpoints,
    ) {
    }

    public function show(Request $request): View
    {
        return $this->render($request);
    }

    public function setUserteamScores(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matchpoints->setUserteamScores($userId);

        return $this->redirectFromResult($result);
    }

    public function setUserScores(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->matchpoints->setUserScores($userId);

        return $this->redirectFromResult($result);
    }

    /**
     * @param  array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}  $result
     */
    private function redirectFromResult(array $result): RedirectResponse
    {
        $redirect = redirect()->route('admin.matchpointsConfig');

        if (! ($result['ok'] ?? false)) {
            return $redirect->with('admin_errors', $result['errors'] ?? ['Unbekannter Fehler.']);
        }

        return $redirect
            ->with('admin_message', $result['message'] ?? 'OK')
            ->with('admin_details', $result['details'] ?? []);
    }

    private function render(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');

        return view('admin.matchpoints-config', [
            'data' => $this->matchpoints->pagePayload($userId),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => session('admin_message'),
            'details' => session('admin_details') ?: [],
            'legacyBase' => '/',
        ]);
    }
}
