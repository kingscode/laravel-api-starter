<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile\Email;

use App\Auth\Dispensary\Exceptions\TokenExpiredException;
use App\Auth\EmailDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Profile\Email\VerifyRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

final class Verify
{
    private EmailDispensary $dispensary;

    private ResponseFactory $responseFactory;

    public function __construct(EmailDispensary $dispensary, ResponseFactory $responseFactory)
    {
        $this->dispensary = $dispensary;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard, VerifyRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $email = $request->input('email');

        try {
            if ($this->dispensary->verify($user, $email, $request->input('token'))) {
                $user->update(['email' => $email]);

                return $this->responseFactory->noContent(Response::HTTP_OK);
            }

            return $this->responseFactory->noContent(Response::HTTP_BAD_REQUEST);
        } catch (TokenExpiredException $e) {
            return $this->responseFactory->noContent(Response::HTTP_BAD_REQUEST);
        }
    }
}
