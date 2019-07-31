<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Routing\UrlGenerator;

final class Authenticate extends Middleware
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
}
