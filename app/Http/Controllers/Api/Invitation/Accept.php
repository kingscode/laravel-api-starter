<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Invitation;

use App\Http\Requests\Api\Invitation\AcceptRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Str;

final class Accept
{
    /**
     * @var \Illuminate\Auth\Passwords\PasswordBrokerManager
     */
    protected $passwordBrokerManager;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $eventDispatcher;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * Reset constructor.
     *
     * @param  \Illuminate\Auth\Passwords\PasswordBrokerManager $passwordBrokerManager
     * @param  \Illuminate\Contracts\Hashing\Hasher             $hasher
     * @param  \Illuminate\Contracts\Events\Dispatcher          $eventDispatcher
     * @param  \Illuminate\Contracts\Routing\ResponseFactory    $responseFactory
     * @param  \Illuminate\Contracts\Translation\Translator     $translator
     */
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

    /**
     * @param  \App\Http\Requests\Api\Password\AcceptRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(AcceptRequest $request)
    {
        $credentials = $request->only(['token', 'email', 'password', 'password_confirmation']);

        $response = $this->getPasswordBroker()->reset($credentials, function (User $user, string $password) {
            $user->setAttribute('password', $this->hasher->make($password));

            $user->setRememberToken(Str::random(60));

            $user->save();

            $this->eventDispatcher->dispatch(new PasswordReset($user));
        });

        return $this->responseFactory->json([
            'message' => $this->translator->trans($response),
        ], PasswordBroker::PASSWORD_RESET === $response ? 200 : 422);
    }

    /**
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('user-invitations');
    }
}
