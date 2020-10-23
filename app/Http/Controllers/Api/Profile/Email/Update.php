<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile\Email;

use App\Auth\EmailDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Profile\Email\UpdateRequest;
use App\Models\User;
use App\Notifications\User\Email\CantUpdate;
use App\Notifications\User\Email\VerifyUpdate;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;

final class Update
{
    private EmailDispensary $dispensary;

    private Dispatcher $notificationDispatcher;

    private ResponseFactory $responseFactory;

    public function __construct(
        EmailDispensary $dispensary,
        Dispatcher $notificationDispatcher,
        ResponseFactory $responseFactory
    ) {
        $this->dispensary = $dispensary;
        $this->notificationDispatcher = $notificationDispatcher;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard, UpdateRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $canUpdate = User::query()
            ->whereKeyNot($user->getKey())
            ->where('email', $request->input('email'))
            ->doesntExist();

        if ($canUpdate) {
            $token = $this->dispensary->dispense($user, $request->input('email'));

            $this->notificationDispatcher->send($user,
                new VerifyUpdate($token, $request->input('email'))
            );

            return $this->responseFactory->noContent(Response::HTTP_OK);
        }

        $this->notificationDispatcher->send($user,
            new CantUpdate()
        );

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
