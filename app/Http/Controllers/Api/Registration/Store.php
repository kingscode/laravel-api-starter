<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Registration;

use App\Auth\RegistrationDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Registration\StoreRequest;
use App\Mail\Registration\AlreadyExists;
use App\Mail\Registration\Verify;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Response;

final class Store
{
    private ResponseFactory $responseFactory;

    private Mailer $mailer;

    private Hasher $hasher;

    private RegistrationDispensary $dispensary;

    public function __construct(
        ResponseFactory $responseFactory,
        RegistrationDispensary $dispensary,
        Mailer $mailer,
        Hasher $hasher
    ) {
        $this->responseFactory = $responseFactory;
        $this->mailer = $mailer;
        $this->hasher = $hasher;
        $this->dispensary = $dispensary;
    }

    public function __invoke(StoreRequest $request): Response
    {
        $email = $request->input('email');

        if (User::query()->where('email', $email)->exists()) {
            $this->mailer->send(
                (new AlreadyExists())->to($email)
            );

            return $this->responseFactory->noContent(Response::HTTP_OK);
        }

        $userData = array_merge($request->validated(), [
            'password' => 'not-logged-in-yet',
        ]);

        /** @var User $user */
        $user = User::query()->create($userData);

        $token = $this->dispensary->dispense($user);

        $this->mailer->send(
            (new Verify($token, $email))->to($email)
        );

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
