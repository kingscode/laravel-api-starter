<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Password;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Password\ResetRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

final class Reset
{
    private PasswordBrokerManager $passwordBrokerManager;

    private Hasher $hasher;

    private Dispatcher $eventDispatcher;

    private ResponseFactory $responseFactory;

    private Translator $translator;

    public function __construct(
        PasswordBrokerManager $passwordBrokerManager,
        Hasher $hasher,
        Dispatcher $eventDispatcher,
        ResponseFactory $responseFactory,
        Translator $translator
    ) {
        $this->passwordBrokerManager = $passwordBrokerManager;
        $this->hasher = $hasher;
        $this->eventDispatcher = $eventDispatcher;
        $this->responseFactory = $responseFactory;
        $this->translator = $translator;
    }

    public function __invoke(ResetRequest $request): JsonResponse
    {
        $credentials = $request->only(['token', 'email', 'password', 'password_confirmation']);

        $response = $this->getPasswordBroker()->reset($credentials, function (User $user, string $password) {
            $user->setAttribute('password', $this->hasher->make($password));

            $user->setRememberToken(Str::random(60));

            $user->save();

            $this->eventDispatcher->dispatch(new PasswordReset($user));
        });

        if (PasswordBroker::PASSWORD_RESET === $response) {
            return $this->responseFactory->json([
                'message' => $this->translator->get($response),
            ]);
        }

        return $this->responseFactory->json([
            'message' => $this->translator->get(PasswordBroker::INVALID_TOKEN),
        ], Response::HTTP_BAD_REQUEST);
    }

    private function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('users');
    }
}
