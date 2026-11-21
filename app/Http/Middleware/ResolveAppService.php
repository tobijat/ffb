<?php

namespace App\Http\Middleware;

use App\Services\AppHostService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

// AppHostService imported for SERVICE_* constants used below.

/**
 * Attach the current product service (ffb|pictory) and align absolute URLs with the request host.
 */
class ResolveAppService
{
    public function __construct(
        private readonly AppHostService $hosts,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $service = $this->hosts->serviceFor($request);
        $request->attributes->set('app_service', $service);
        app()->instance('app.service', $service);

        $host = strtolower((string) $request->getHost());
        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            $host = explode(':', $host, 2)[0];
        }

        // Domain-routed pictory.* names require {pictoryDomain} when generating URLs.
        if ($service === AppHostService::SERVICE_PICTORY && $host !== '') {
            URL::defaults(['pictoryDomain' => $host]);
        }

        $root = $request->getSchemeAndHttpHost();
        $path = rtrim((string) (parse_url((string) config('app.url'), PHP_URL_PATH) ?: ''), '/');
        URL::forceRootUrl($path !== '' ? $root.$path : $root);

        return $next($request);
    }
}
