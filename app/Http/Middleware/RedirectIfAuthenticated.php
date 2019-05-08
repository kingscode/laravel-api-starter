<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;

class RedirectIfAuthenticated
{
    /**
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * RedirectIfAuthenticated constructor.
     *
     * @param  \Illuminate\Contracts\Auth\Factory            $auth
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $responseFactory
     */
    public function __construct(Factory $auth, ResponseFactory $responseFactory)
    {
        $this->auth = $auth;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $request->expectsJson()) {
            if ($this->auth->guard($guard)->check()) {
                return $this->responseFactory->redirectTo('/');
            }
        }

        return $next($request);
    }
}
