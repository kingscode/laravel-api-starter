<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Http\Responses\ResponseFactory as ResponseFactoryContract;
use App\Http\Responses\ResponseFactory;
use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use RuntimeException;
use function sha1;

final class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = '\\App\\Http\\Controllers\\';

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $router = $this->app->make(Router::class);

            $this->mapApiRoutes($router);

            $this->mapWebRoutes($router);
        });
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(ResponseFactoryContract::class, static function (Container $container) {
            return $container->make(ResponseFactory::class);
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    private function mapWebRoutes(Router $router): void
    {
        $router->middleware('web')->namespace($this->namespace)->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    private function mapApiRoutes(Router $router): void
    {
        $router->middleware('api')->namespace($this->namespace)->group(base_path('routes/api.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function configureRateLimiting()
    {
        /** @var RateLimiter $rateLimiter */
        $rateLimiter = $this->app->make(RateLimiter::class);

        $rateLimiter->for('spa_login_lock', function (Request $request) {
            return new Limit($this->resolveRequestSignature($request), 15, 5);
        });

        $rateLimiter->for('spa_invitation_lock', function (Request $request) {
            return new Limit($this->resolveRequestSignature($request), 15, 5);
        });

        $rateLimiter->for('spa_password_reset_lock', function (Request $request) {
            return new Limit($this->resolveRequestSignature($request), 15, 5);
        });
    }

    private function resolveRequestSignature(Request $request)
    {
        if ($user = $request->user()) {
            return sha1($user->getAuthIdentifier());
        } elseif ($route = $request->route()) {
            return sha1($route->getDomain() . '|' . $request->ip());
        }

        throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
    }
}
