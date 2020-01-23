<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $this->registerDispensary();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerDispensary(): void
    {
        /** @var AuthManager $authManager */
        $authManager = $this->app->make(AuthManager::class);

        $authManager->viaRequest('spa', function (Request $request): User {
            /** @var User|null $user */
            $user = User::query()
                ->whereHas('tokens',
                    fn(Builder $builder) => $builder->where('token', $request->bearerToken())->whereNotNull('token')
                )
                ->firstOr(function () {
                    throw new AuthenticationException();
                });

            return $user;
        });
    }
}
