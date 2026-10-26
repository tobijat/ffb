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
            'mcolor' => KitKitchenClient::normalizeColor($request->query('mcolor')),
            'ncolor' => KitKitchenClient::normalizeColor($request->query('ncolor')),
            'tcolor' => KitKitchenClient::normalizeColor($request->query('tcolor')),
            'scolor' => KitKitchenClient::normalizeColor($request->query('scolor')),
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
            'options' => $this->kitOptionsForUi($shared, $text, $front['query'] ?? [], $back['query'] ?? []),
        ]);
    }

    /**
     * Import a shirt SVG into Kit Kitchen by POST (config or stored custom front),
     * returning front/back/seed and UI options from X-Kit-Query.
     */
    public function kitFromShirt(Request $request, KitKitchenClient $kits): JsonResponse
    {
        $userId = $this->auth->userId($request);
        if ($userId <= 0) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $configSvg = trim((string) $request->input('svg', ''));
        $backSvgBody = trim((string) $request->input('svg_back', ''));
        if ($configSvg !== '') {
            if (! CustomAvatarSvg::isAcceptable($configSvg)) {
                return response()->json(['error' => 'Trikot-SVG ungültig.'], 422);
            }
            if ($backSvgBody !== '' && ! CustomAvatarSvg::isAcceptable($backSvgBody)) {
                return response()->json(['error' => 'Trikot-Rückseite ungültig.'], 422);
            }
        } else {
            $shirt = trim((string) $request->input('shirt', ''));
            if ($shirt === '') {
                $shirt = trim((string) (UserDetails::query()
                    ->where('user_id', $userId)
                    ->value('user_details_avatar') ?? ''));
            }

            if (CustomAvatarSvg::isCustomKitFilename($shirt)) {
                $customPath = CustomAvatarSvg::absolutePath($shirt);
                if ($customPath === null) {
                    return response()->json(['error' => 'Eigenes Trikot nicht gefunden.'], 404);
                }
                $configSvg = (string) file_get_contents($customPath);
                if (! CustomAvatarSvg::isAcceptable($configSvg)) {
                    return response()->json(['error' => 'Eigenes Trikot ungültig.'], 502);
                }
            } elseif (UserAvatar::isShirtPath($shirt)) {
                $configPath = TeamShirt::configSvgPathFromShirtRelative($shirt);
                if ($configPath === null) {
                    return response()->json(['error' => 'Trikot-Config nicht gefunden.'], 404);
                }

                $configSvg = @file_get_contents($configPath);
                if (! is_string($configSvg) || ! CustomAvatarSvg::isAcceptable($configSvg)) {
                    return response()->json(['error' => 'Trikot-Config ungültig.'], 502);
                }
            } else {
                return response()->json(['error' => 'Kein Trikot zum Importieren gesetzt.'], 422);
            }
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
        $colorFrom = static function (string $key) use ($request): ?string {
            return KitKitchenClient::normalizeColor(
                $request->input($key, $request->query($key))
            );
        };
        $shared = [
            'stars' => $starsInt,
            'number' => $number !== '' ? $number : null,
            'maker' => $maker,
            'mcolor' => $colorFrom('mcolor'),
            'ncolor' => $colorFrom('ncolor'),
            'tcolor' => $colorFrom('tcolor'),
            'scolor' => $colorFrom('scolor'),
        ];

        $front = $kits->fetch([
            ...$shared,
            'svg_body' => $configSvg,
        ]);
        if ($front === null) {
            return response()->json(['error' => 'Kit konnte nicht geladen werden.'], 502);
        }

        // POST SVG with backside=1 so X-Kit-Query includes Rückentext baked into
        // the shirt (a seed-only GET omits text unless the client sent it).
        $back = $kits->fetch([
            ...$shared,
            'svg_body' => $backSvgBody !== '' ? $backSvgBody : $configSvg,
            'seed' => $front['seed'],
            'backside' => true,
            'text' => $text !== '' ? $text : null,
        ]);
        if ($back === null) {
            return response()->json(['error' => 'Kit konnte nicht geladen werden.'], 502);
        }

        return response()->json([
            'seed' => $front['seed'],
            'svg' => $front['svg'],
            'svg_back' => $back['svg'],
            'options' => $this->kitOptionsForUi($shared, $text, $front['query'] ?? [], $back['query'] ?? []),
        ]);
    }

    /**
     * Merge request options with kit-kitchen X-Kit-Query echoes for the UI form.
     *
     * @param  array<string, mixed>  $shared
     * @param  array<string, mixed>  $frontQuery
     * @param  array<string, mixed>  $backQuery
     * @return array{
     *   maker: string,
     *   stars: int,
     *   number: string,
     *   text: string,
     *   mcolor: string,
     *   ncolor: string,
     *   tcolor: string,
     *   scolor: string
     * }
     */
    private function kitOptionsForUi(array $shared, string $requestedText, array $frontQuery, array $backQuery): array
    {
        $merged = array_merge($frontQuery, $backQuery);

        $maker = KitKitchenClient::normalizeMaker($merged['maker'] ?? ($shared['maker'] ?? null));
        $stars = $merged['stars'] ?? ($shared['stars'] ?? 0);
        $starsInt = is_numeric($stars) ? (int) $stars : 0;
        if ($starsInt < 0 || $starsInt > 9) {
            $starsInt = 0;
        }

        $number = trim((string) ($merged['number'] ?? ($shared['number'] ?? '')));
        if ($number !== '' && ! preg_match('/^\d{1,2}$/', $number)) {
            $number = '';
        }

        // Prefer non-empty text: backside query, then front (shirt POST often
        // echoes Rückentext only on one of the two X-Kit-Query headers), then request.
        $text = '';
        foreach ([$backQuery['text'] ?? null, $frontQuery['text'] ?? null, $requestedText] as $candidate) {
            if ($candidate === null) {
                continue;
            }
            $normalized = preg_replace('/[\x00-\x1F\x7F]+/u', '', trim((string) $candidate)) ?? '';
            if ($normalized !== '') {
                $text = $normalized;
                break;
            }
        }
        if (function_exists('mb_substr')) {
            $text = mb_substr($text, 0, 16);
        } else {
            $text = substr($text, 0, 16);
        }

        $color = static function (string $key) use ($merged, $shared): string {
            $normalized = KitKitchenClient::normalizeColor($merged[$key] ?? ($shared[$key] ?? null));

            return $normalized ?? '';
        };

        return [
            'maker' => $maker ?? '',
            'stars' => $starsInt,
            'number' => $number,
            'text' => $text,
            'mcolor' => $color('mcolor'),
            'ncolor' => $color('ncolor'),
            'tcolor' => $color('tcolor'),
            'scolor' => $color('scolor'),
        ];
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
