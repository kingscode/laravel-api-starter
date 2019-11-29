<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class Update
{
    public function __invoke(UpdateRequest $request, User $user): JsonResponse
    {
        $user->update(
            $request->validated()
        );

        return (new UserResource($user))->toResponse($request);
    }
}
