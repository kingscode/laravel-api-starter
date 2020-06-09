<?php

declare(strict_types=1);

namespace App\Providers;

use App\SPA\UrlGenerator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

final class SPAServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(): void
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $this->app->make(Repository::class);

        $this->app->singleton(UrlGenerator::class, static fn() => new UrlGenerator($config->get('spa.url')));
    }
}
