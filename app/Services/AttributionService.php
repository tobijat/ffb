<?php

namespace App\Services;

use AttributionGenerator\AttributionGenerator;
use AttributionGenerator\Exception\AttributionException;
use AttributionGenerator\Questionnaire;
use Throwable;

class AttributionService
{
    /**
     * Build attribution for a Wikimedia Commons file page URL.
     *
     * Always returns the commons URL for fallback display. When generation
     * succeeds, attribution_html includes author/image/licence links with
     * target="_blank".
     *
     * @return array{
     *   ok: true,
     *   data: array{
     *     commons_url: string,
     *     attribution_html: string|null,
     *     generated: bool
     *   }
     * }|array{ok: false, status: int, error: string}
     */
    public function forCommonsUrl(string $imageUrl): array
    {
        $imageUrl = trim($imageUrl);
        if ($imageUrl === '' || ! $this->isAllowedCommonsFileUrl($imageUrl)) {
            return ['ok' => false, 'status' => 422, 'error' => 'Invalid Commons URL'];
        }

        $html = $this->generateHtml($imageUrl);

        return [
            'ok' => true,
            'data' => [
                'commons_url' => $imageUrl,
                'attribution_html' => $html,
                'generated' => $html !== null,
            ],
        ];
    }

    private function generateHtml(string $imageUrl): ?string
    {
        try {
            $generator = AttributionGenerator::create();

            try {
                $attribution = $generator->generate($imageUrl, $this->questionnaire());
            } catch (AttributionException $e) {
                if ($e->errorCode() !== 'author-required') {
                    throw $e;
                }
                $attribution = $generator->generate(
                    $imageUrl,
                    $this->questionnaire(['author_unknown' => true]),
                );
            }

            $html = trim($attribution->text);

            return $html !== '' ? $html : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function questionnaire(array $overrides = []): Questionnaire
    {
        return Questionnaire::fromArray(array_merge([
            'type_of_use' => 'online',
            'language' => 'de',
            'edited' => false,
        ], $overrides));
    }

    private function isAllowedCommonsFileUrl(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return false;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = (string) ($parts['path'] ?? '');

        return $host === 'commons.wikimedia.org'
            && (bool) preg_match('#^/wiki/File:.+#i', $path);
    }
}
