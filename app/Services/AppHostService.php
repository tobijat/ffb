<?php

namespace App\Services;

use Illuminate\Http\Request;

class AppHostService
{
    public const SERVICE_FFB = 'ffb';

    public const SERVICE_PICTORY = 'pictory';

    public function serviceFor(?Request $request = null): string
    {
        $host = $this->normalizeHost($request?->getHost() ?? '');

        $map = config('services_hosts', []);
        if ($host !== '' && isset($map[$host])) {
            return (string) $map[$host];
        }

        return self::SERVICE_FFB;
    }

    public function is(string $service, ?Request $request = null): bool
    {
        return $this->serviceFor($request) === $service;
    }

    public function isPictory(?Request $request = null): bool
    {
        return $this->is(self::SERVICE_PICTORY, $request);
    }

    public function isFfb(?Request $request = null): bool
    {
        return $this->is(self::SERVICE_FFB, $request);
    }

    /**
     * @return list<string>
     */
    public function hostsForService(string $service): array
    {
        $hosts = [];
        foreach (config('services_hosts', []) as $host => $mapped) {
            if ($mapped === $service) {
                $hosts[] = (string) $host;
            }
        }

        return $hosts;
    }

    private function normalizeHost(string $host): string
    {
        $host = strtolower(trim($host));
        if ($host === '') {
            return '';
        }

        // Strip port if present (e.g. pictory.test:8000).
        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            $host = explode(':', $host, 2)[0];
        }

        return $host;
    }
}
