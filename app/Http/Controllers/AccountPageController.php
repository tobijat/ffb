<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use App\Services\AccountService;
use App\Services\FfbAuth;
use App\Support\CustomAvatarSvg;
use App\Support\KitKitchenClient;
use App\Support\TeamShirt;
use App\Support\UserAvatar;
use Illuminate\Http\JsonResponse;
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
                'destination' => 'profile?tab=account',
            ]);
        }

        return redirect()->route('profile', ['tab' => 'account']);
    }

    public function update(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return redirect()->route('start', [
                'destination' => 'profile?tab=account',
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
                'destination' => 'profile',
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
                'destination' => 'profile',
            ]);
        }

        $result = $this->accounts->updateProfile($userId, $request->all(), $request);
        $tab = $this->resolveTab($request->input('profile_section', $request->query('tab')));

        return $this->renderProfileHub(
            $userId,
            $tab,
            $result['ok'] ? [] : ($result['errors'] ?? []),
            $result['ok'] ? ($result['message'] ?? null) : null,
            null,
            $result['form'] ?? null,
        );
    }

    public function randomKitAvatar(Request $request, KitKitchenClient $kits): JsonResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $stars = $request->query('stars');
        $starsInt = is_numeric($stars) ? (int) $stars : null;
        if ($starsInt !== null && ($starsInt < 0 || $starsInt > 9)) {
            $starsInt = null;
        }

        $number = trim((string) $request->query('number', ''));
        if ($number !== '' && ! preg_match('/^\d{1,2}$/', $number)) {
            $number = '';
        }

        $text = (string) $request->query('text', '');
        $maker = KitKitchenClient::normalizeMaker($request->query('maker'));
        $seed = CustomAvatarSvg::normalizeSeed($request->query('seed'));
        $shared = [
            'seed' => $seed,
            'stars' => $starsInt,
            'number' => $number !== '' ? $number : null,
            'maker' => $maker,
        ];

        $front = $kits->fetch($shared);
        if ($front === null) {
            return response()->json(['error' => 'Kit konnte nicht geladen werden.'], 502);
        }

        $back = $kits->fetch([
            ...$shared,
            'seed' => $front['seed'],
            'backside' => true,
            'text' => $text,
        ]);
        if ($back === null) {
            return response()->json(['error' => 'Kit konnte nicht geladen werden.'], 502);
        }

        return response()->json([
            'seed' => $front['seed'],
            'svg' => $front['svg'],
            'svg_back' => $back['svg'],
        ]);
    }

    /**
     * Import a saved ready-made team shirt into Kit Kitchen by POSTing its
     * config SVG, returning front/back/seed like kit-random.
     */
    public function kitFromShirt(Request $request, KitKitchenClient $kits): JsonResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $shirt = trim((string) $request->input('shirt', ''));
        if ($shirt === '') {
            $shirt = trim((string) (UserDetails::query()
                ->where('user_id', $userId)
                ->value('user_details_avatar') ?? ''));
        }

        if (! UserAvatar::isShirtPath($shirt)) {
            return response()->json(['error' => 'Kein fertiges Trikot als Avatar gesetzt.'], 422);
        }

        $configPath = TeamShirt::configSvgPathFromShirtRelative($shirt);
        if ($configPath === null) {
            return response()->json(['error' => 'Trikot-Config nicht gefunden.'], 404);
        }

        $configSvg = @file_get_contents($configPath);
        if (! is_string($configSvg) || ! CustomAvatarSvg::isAcceptable($configSvg)) {
            return response()->json(['error' => 'Trikot-Config ungültig.'], 502);
        }

        $stars = $request->input('stars', $request->query('stars'));
        $starsInt = is_numeric($stars) ? (int) $stars : null;
        if ($starsInt !== null && ($starsInt < 0 || $starsInt > 9)) {
            $starsInt = null;
        }

        $number = trim((string) $request->input('number', $request->query('number', '')));
        if ($number !== '' && ! preg_match('/^\d{1,2}$/', $number)) {
            $number = '';
        }

        $text = (string) $request->input('text', $request->query('text', ''));
        $maker = KitKitchenClient::normalizeMaker(
            $request->input('maker', $request->query('maker'))
        );
        $shared = [
            'stars' => $starsInt,
            'number' => $number !== '' ? $number : null,
            'maker' => $maker,
        ];

        $front = $kits->fetch([
            ...$shared,
            'svg_body' => $configSvg,
        ]);
        if ($front === null) {
            return response()->json(['error' => 'Kit konnte nicht geladen werden.'], 502);
        }

        $back = $kits->fetch([
            ...$shared,
            'seed' => $front['seed'],
            'backside' => true,
            'text' => $text,
        ]);
        if ($back === null) {
            return response()->json(['error' => 'Kit konnte nicht geladen werden.'], 502);
        }

        return response()->json([
            'seed' => $front['seed'],
            'svg' => $front['svg'],
            'svg_back' => $back['svg'],
        ]);
    }

    private function resolveTab(mixed $tab): string
    {
        return match ($tab) {
            'account' => 'account',
            'avatar', 'avatar_custom' => 'avatar',
            default => 'profile',
        };
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
