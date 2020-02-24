<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile\Password;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Profile\Password\UpdateRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Response;

final class Update
{
    private ResponseFactory $responseFactory;

    private Hasher $hasher;

    public function __construct(ResponseFactory $responseFactory, Hasher $hasher)
    {
        $this->hasher = $hasher;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard, UpdateRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $user->update([
            'password' => $this->hasher->make($request->input('password')),
        ]);

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
