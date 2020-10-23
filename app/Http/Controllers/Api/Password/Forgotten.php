<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Password;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Password\ForgottenRequest;
use App\Models\User;
use App\Notifications\User\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\JsonResponse;

final class Forgotten
{
    private PasswordBrokerManager $passwordBrokerManager;

    private ResponseFactory $responseFactory;

    private Translator $translator;

    private Dispatcher $notificationDispatcher;

    public function __construct(
        PasswordBrokerManager $passwordBrokerManager,
        ResponseFactory $responseFactory,
        Translator $translator,
        Dispatcher $notificationDispatcher
    ) {
        $this->passwordBrokerManager = $passwordBrokerManager;
        $this->responseFactory = $responseFactory;
        $this->translator = $translator;
        $this->notificationDispatcher = $notificationDispatcher;
    }

    public function __invoke(ForgottenRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->input('email'))->first();

        if ($user instanceof User) {
            $this->notificationDispatcher->send($user,
                new PasswordReset($this->getPasswordBroker()->createToken($user))
            );
        }

        // We'll always send a successful response so that an attack can't find
        // what email addresses exist in the application.
        return $this->responseFactory->json([
            'message' => $this->translator->get(PasswordBroker::RESET_LINK_SENT),
        ]);
    }

    private function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('users');
    }
}
