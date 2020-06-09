<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Http\Responses\ResponseFactory as ResponseFactoryContract;
use App\Http\Responses\ResponseFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function map(): void
    {
        $router = $this->app->make(Router::class);

        $this->mapApiRoutes($router);

        $this->mapWebRoutes($router);
    }

    public function register()
    {
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
    protected function mapWebRoutes(Router $router): void
    {
        $router->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    protected function mapApiRoutes(Router $router): void
    {
        $router->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
