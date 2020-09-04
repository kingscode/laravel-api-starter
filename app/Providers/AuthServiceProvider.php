<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $this->registerSpaRequestGuard();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerSpaRequestGuard(): void
    {
        /** @var AuthManager $authManager */
        $authManager = $this->app->make(AuthManager::class);

        $authManager->viaRequest('spa', static function (Request $request): User {
            /** @var UserToken $userToken */
            $userToken = UserToken::query()
                ->with('user')
                ->where('token', $request->bearerToken())
                ->whereNotNull('token')
                ->firstOr(static function () {
                    throw new AuthenticationException();
                });

            $user = $userToken->getUser();

            $user->setCurrentToken($userToken);

            $userToken->touch();

            return $user;
        });
    }
}
