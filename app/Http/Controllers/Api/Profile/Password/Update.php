<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile\Password;

use App\Http\Requests\Api\Profile\Password\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;

final class Update
{
    private Hasher $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function __invoke(Guard $guard, UpdateRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $user->update([
            'password' => $this->hasher->make($request->input('password')),
        ]);

        return (new UserResource($user))->toResponse($request);
    }
}
