<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Password;

use App\Http\Requests\Api\Password\ForgottenRequest;
use App\Models\User;
use App\Notifications\User\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Translation\Translator;

final class Forgotten
{
    /**
     * @var \Illuminate\Auth\Passwords\PasswordBrokerManager
     */
    protected $passwordBrokerManager;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * @var \Illuminate\Contracts\Notifications\Dispatcher
     */
    protected $notificationDispatcher;

    /**
     * Reset constructor.
     *
     * @param  \Illuminate\Auth\Passwords\PasswordBrokerManager $passwordBrokerManager
     * @param  \Illuminate\Contracts\Routing\ResponseFactory    $responseFactory
     * @param  \Illuminate\Contracts\Translation\Translator     $translator
     * @param  \Illuminate\Contracts\Notifications\Dispatcher   $notificationDispatcher
     */
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

    /**
     * @param  \App\Http\Requests\Api\Password\ForgottenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ForgottenRequest $request)
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
            'message' => $this->translator->trans(PasswordBroker::RESET_LINK_SENT),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Auth\PasswordBroker|\Illuminate\Auth\Passwords\PasswordBroker
     */
    protected function getPasswordBroker(): PasswordBroker
    {
        return $this->passwordBrokerManager->broker('users');
    }
}
