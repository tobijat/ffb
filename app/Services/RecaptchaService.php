<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function enabled(): bool
    {
        if (! (bool) config('ffb.recaptcha.enabled', false)) {
            return false;
        }

        $site = trim((string) config('ffb.recaptcha.site_key', ''));
        $secret = trim((string) config('ffb.recaptcha.secret_key', ''));

        return $site !== '' && $secret !== '';
    }

    public function siteKey(): string
    {
        return trim((string) config('ffb.recaptcha.site_key', ''));
    }

    /**
     * Verify a reCAPTCHA v2 response token. When captcha is disabled, always passes.
     */
    public function verify(?string $response, ?string $remoteIp = null): bool
    {
        if (! $this->enabled()) {
            return true;
        }

        $response = trim((string) $response);
        if ($response === '') {
            return false;
        }

        $payload = [
            'secret' => (string) config('ffb.recaptcha.secret_key'),
            'response' => $response,
        ];
        if ($remoteIp) {
            $payload['remoteip'] = $remoteIp;
        }

        try {
            $result = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', $payload)
                ->json();
        } catch (\Throwable) {
            return false;
        }

        return (bool) ($result['success'] ?? false);
    }
}
