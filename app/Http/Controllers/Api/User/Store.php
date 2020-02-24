<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\User\StoreRequest;
use App\Models\User;
use App\Notifications\User\Invitation;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class Store
{
    private Dispatcher $notificationDispatcher;

    private PasswordBrokerManager $passwordBrokerManager;

    private ResponseFactory $responseFactory;

    public function __construct(
        Dispatcher $notificationDispatcher,
        PasswordBrokerManager $passwordBrokerManager,
        ResponseFactory $responseFactory
    ) {
        $this->notificationDispatcher = $notificationDispatcher;
        $this->passwordBrokerManager = $passwordBrokerManager;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(StoreRequest $request): Response
    {
        $attributes = $request->validated();

        // Don't need an actual password yet as we're going to send an invite.
        Arr::set($attributes, 'password', Str::random(64));

        /** @var User $user */
        $user = User::query()->create($attributes);

        $this->notificationDispatcher->send($user,
            new Invitation($this->getPasswordBroker()->createToken($user))
        );

        return $this->responseFactory->noContent(Response::HTTP_CREATED);
    }

    private function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('user-invitations');
    }
}
