<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accounts,
        private readonly FfbAuth $auth,
    ) {
    }

    public function show(Request $request): RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/profile?tab=account',
            ]);
        }

        return redirect()->route('profile', ['tab' => 'account']);
    }

    public function update(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/profile?tab=account',
            ]);
        }

        $result = $this->accounts->update($userId, $request->all(), $request);

        if ($result['ok'] && ($result['email_changed'] ?? false)) {
            return redirect()->route('start')->with('account_message', $result['message']);
        }

        return $this->renderProfileHub(
            $userId,
            'account',
            $result['ok'] ? [] : ($result['errors'] ?? []),
            $result['ok'] ? ($result['message'] ?? null) : null,
            $result['form'] ?? null,
            null,
        );
    }

    public function showProfile(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/profile',
            ]);
        }

        $tab = $this->resolveTab($request->query('tab'));

        return $this->renderProfileHub($userId, $tab);
    }

    public function updateProfile(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/profile',
            ]);
        }

        $result = $this->accounts->updateProfile($userId, $request->all(), $request);

        return $this->renderProfileHub(
            $userId,
            'profile',
            $result['ok'] ? [] : ($result['errors'] ?? []),
            $result['ok'] ? ($result['message'] ?? null) : null,
            null,
            $result['form'] ?? null,
        );
    }

    private function resolveTab(mixed $tab): string
    {
        return $tab === 'account' ? 'account' : 'profile';
    }

    /**
     * @param  list<string>  $errors
     * @param  array<string, mixed>|null  $accountFormOverride
     * @param  array<string, mixed>|null  $profileFormOverride
     */
    private function renderProfileHub(
        int $userId,
        string $tab,
        array $errors = [],
        ?string $answer = null,
        ?array $accountFormOverride = null,
        ?array $profileFormOverride = null,
    ): View|RedirectResponse {
        $account = $this->accounts->pagePayload($userId, $accountFormOverride);
        $profile = $this->accounts->profilePayload($userId, $profileFormOverride);

        if (! $account['ok'] || ! $profile['ok']) {
            return redirect()->route('start');
        }

        return view('account-profile', [
            'tab' => $tab,
            'data' => $profile['data'],
            'accountData' => $account['data'],
            'legacyBase' => '/',
            'errors' => $errors,
            'answer' => $answer,
        ]);
    }
}
