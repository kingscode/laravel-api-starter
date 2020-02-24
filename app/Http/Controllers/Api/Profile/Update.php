<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Profile\UpdateRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

final class Update
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard, UpdateRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $user->update(
            $request->validated()
        );

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
