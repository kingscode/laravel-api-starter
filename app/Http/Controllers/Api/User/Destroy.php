<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

final class Destroy
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard, User $user): Response
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = $guard->user();

        if ($user->is($authenticatedUser)) {
            return $this->responseFactory->noContent(Response::HTTP_CONFLICT);
        }

        $user->delete();

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
