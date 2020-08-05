<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Invitation;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Invitation\ResendRequest;
use App\Models\User;
use App\Notifications\User\Invitation;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;

final class Resend
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

    public function __invoke(ResendRequest $request): Response
    {
        $user = User::query()->where('email', $request->input('email'))->first();

        if ($user instanceof User) {
            $this->notificationDispatcher->send($user,
                new Invitation($this->getPasswordBroker()->createToken($user))
            );
        }

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }

    private function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('user-invitations');
    }
}
