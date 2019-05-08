<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Routing\UrlGenerator;

class Authenticate extends Middleware
{
    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Authenticate constructor.
     *
     * @param  \Illuminate\Contracts\Auth\Factory         $auth
     * @param  \Illuminate\Contracts\Routing\UrlGenerator $urlGenerator
     */
    public function __construct(Factory $auth, UrlGenerator $urlGenerator)
    {
        parent::__construct($auth);

        $this->auth = $auth;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return $this->urlGenerator->route('login');
        }
    }
}
