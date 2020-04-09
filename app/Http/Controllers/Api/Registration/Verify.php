<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Registration;

use App\Auth\Dispensary\Exceptions\TokenExpiredException;
use App\Auth\RegistrationDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Registration\VerifyRequest;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Response;

final class Verify
{
    private ResponseFactory $responseFactory;

    private Hasher $hasher;

    private RegistrationDispensary $dispensary;

    public function __construct(
        ResponseFactory $responseFactory,
        RegistrationDispensary $dispensary,
        Hasher $hasher
    ) {
        $this->responseFactory = $responseFactory;
        $this->hasher = $hasher;
        $this->dispensary = $dispensary;
    }

    /**
     * @param  \App\Http\Requests\Api\Registration\VerifyRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __invoke(VerifyRequest $request): Response
    {
        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        /** @var User $user */
        $user = User::query()->where('email', $email)->first();

        if (! $user instanceof User) {
            return $this->responseFactory->noContent(Response::HTTP_BAD_REQUEST);
        }

        try {
            if ($this->dispensary->verify($user, $token)) {
                $user->update([
                    'password' => $this->hasher->make($password),
                ]);

                return $this->responseFactory->noContent(Response::HTTP_OK);
            }

            return $this->responseFactory->noContent(Response::HTTP_BAD_REQUEST);
        } catch (TokenExpiredException $e) {
            return $this->responseFactory->noContent(Response::HTTP_BAD_REQUEST);
        }
    }
}
