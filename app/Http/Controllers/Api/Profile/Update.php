<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Http\Requests\Api\Profile\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;

final class Update
{
    public function __invoke(Guard $guard, UpdateRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $user->update(
            $request->validated()
        );

        return (new UserResource($user))->toResponse($request);
    }
}
