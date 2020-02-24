<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Contracts\Http\Responses\ResponseFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class Show
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Guard $guard, Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        return $this->responseFactory->json([
            'id'    => $user->getKey(),
            'name'  => $user->getAttribute('name'),
            'email' => $user->getAttribute('email'),
        ]);
    }
}
