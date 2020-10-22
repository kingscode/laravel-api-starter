<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Tests\TestCase;

final class ThrottleRequestsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /** @var RateLimiter $rateLimiter */
        $rateLimiter = $this->app->make(RateLimiter::class);

        $rateLimiter->for('test', function (Request $request) {
            return new Limit($request->ip(), 1, 1);
        });

        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->middleware('throttle:test')->get('test', function () {
            return new Response('', 200);
        });
    }

    public function testWhenDisabled()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $this->app->make(Repository::class);

        $config->set('app.throttling', false);

        $response = $this->call('get', 'test');

        $response->assertOk();

        $response = $this->call('get', 'test');

        $response->assertOk();
    }

    public function testWhenEnabled()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $this->app->make(Repository::class);

        $config->set('app.throttling', true);

        $response = $this->call('get', 'test');

        $response->assertOk();

        $response = $this->call('get', 'test');

        $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }
}
