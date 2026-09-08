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
    ) {
    }

    public function show(Request $request): View
    {
        return $this->render($request);
    }

    public function setMatchroundPlayerPrices(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->calculatePlayerPricesForMatchround($userId, $request->all());

        return $this->redirectFromResult($result)->withInput($request->only([
            'matchround_id',
            'price_margin',
        ]));
    }

    public function setGameEloTeamPrices(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->calculateEloTeamPricesForGame($userId, $request->all());

        return $this->redirectFromResult($result)->withInput($request->only([
            'max_price',
            'min_price',
        ]));
    }

    public function setMatchroundEloTeamPrices(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->playerprice->calculateEloTeamPricesForMatchround($userId, $request->all());

        return $this->redirectFromResult($result)->withInput($request->only([
            'matchround_id',
            'max_price',
            'min_price',
        ]));
    }

    /**
     * @param  array{ok: bool, message?: string, errors?: list<string>, details?: list<string>}  $result
     */
    private function redirectFromResult(array $result): RedirectResponse
    {
        $redirect = redirect()->route('admin.playerprice');

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

        return view('admin.playerprice', [
            'data' => $this->playerprice->pagePayload($userId),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => session('admin_message'),
            'details' => session('admin_details') ?: [],
            'legacyBase' => '/',
        ]);
    }
}
