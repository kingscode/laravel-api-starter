<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\Api\User\StoreRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Notifications\User\Invitation;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class Store
{
    private Dispatcher $notificationDispatcher;

    private PasswordBrokerManager $passwordBrokerManager;

    public function __construct(Dispatcher $notificationDispatcher, PasswordBrokerManager $passwordBrokerManager)
    {
        $this->notificationDispatcher = $notificationDispatcher;
        $this->passwordBrokerManager = $passwordBrokerManager;
    }

    public function __invoke(StoreRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        // Don't need an actual password yet as we're going to send an invite.
        Arr::set($attributes, 'password', Str::random(64));

        /** @var User $user */
        $user = User::query()->create($attributes);

        $this->notificationDispatcher->send($user,
            new Invitation($this->getPasswordBroker()->createToken($user))
        );

        return (new UserResource($user))->toResponse($request);
    }

    private function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('user-invitations');
    }
}
