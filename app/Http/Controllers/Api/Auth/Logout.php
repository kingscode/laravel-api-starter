<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Http\Responses\ResponseFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

final class Logout
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard): Response
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $token = $user->getCurrentToken();

        $token->delete();

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
