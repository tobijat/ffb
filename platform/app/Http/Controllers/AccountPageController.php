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

    public function show(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/account',
            ]);
        }

        $result = $this->accounts->pagePayload($userId);
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('account', [
            'data' => $result['data'],
            'legacyBase' => '/',
            'errors' => [],
            'answer' => null,
        ]);
    }

    public function update(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/account',
            ]);
        }

        $result = $this->accounts->update($userId, $request->all(), $request);

        if ($result['ok'] && ($result['email_changed'] ?? false)) {
            return redirect()->route('start')->with('account_message', $result['message']);
        }

        $payload = $this->accounts->pagePayload(
            $userId,
            $result['form'] ?? null,
        );

        if (! $payload['ok']) {
            return redirect()->route('start');
        }

        return view('account', [
            'data' => $payload['data'],
            'legacyBase' => '/',
            'errors' => $result['ok'] ? [] : ($result['errors'] ?? []),
            'answer' => $result['ok'] ? ($result['message'] ?? null) : null,
        ]);
    }

    public function showProfile(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => '/platform/profile',
            ]);
        }

        $result = $this->accounts->profilePayload($userId);
        if (! $result['ok']) {
            return redirect()->route('start');
        }

        return view('account-profile', [
            'data' => $result['data'],
            'legacyBase' => '/',
            'errors' => [],
            'answer' => null,
        ]);
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

        $payload = $this->accounts->profilePayload(
            $userId,
            $result['form'] ?? null,
        );

        if (! $payload['ok']) {
            return redirect()->route('start');
        }

        return view('account-profile', [
            'data' => $payload['data'],
            'legacyBase' => '/',
            'errors' => $result['ok'] ? [] : ($result['errors'] ?? []),
            'answer' => $result['ok'] ? ($result['message'] ?? null) : null,
        ]);
    }
}
