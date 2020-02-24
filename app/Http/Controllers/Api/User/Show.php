<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class Show
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, User $user): JsonResponse
    {
        return $this->responseFactory->json([
            'id'    => $user->getKey(),
            'name'  => $user->getAttribute('name'),
            'email' => $user->getAttribute('email'),
        ]);
    }
}
