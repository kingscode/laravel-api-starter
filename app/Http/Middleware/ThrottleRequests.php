<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository;

final class ThrottleRequests extends \Illuminate\Routing\Middleware\ThrottleRequests
{
    private Repository $config;

    public function __construct(RateLimiter $limiter, Repository $config)
    {
        parent::__construct($limiter);

        $this->config = $config;
    }

    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        if ($this->isEnabled()) {
            return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
        }

        return $next($request);
    }

    private function isEnabled(): bool
    {
        return (bool) $this->config->get('app.throttling');
    }
}
