<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;

final class ThrottleRequests
{
    private BaseThrottleRequests $throttleRequests;

    private Repository $config;

    public function __construct(BaseThrottleRequests $throttleRequests, Repository $config)
    {
        $this->throttleRequests = $throttleRequests;
        $this->config = $config;
    }

    public function handle($request, Closure $next, string $limiter)
    {
        if ($this->isEnabled()) {
            return $this->throttleRequests->handle($request, $next, $limiter);
        }

        return $next($request);
    }

    private function isEnabled(): bool
    {
        return (bool) $this->config->get('app.throttling');
    }
}
