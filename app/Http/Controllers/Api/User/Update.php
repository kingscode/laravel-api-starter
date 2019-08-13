<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;

final class Update
{
    /**
     * @param \App\Http\Requests\Api\User\UpdateRequest $request
     * @param \App\Models\User                          $user
     * @return \App\Http\Resources\Api\UserResource
     */
    public function __invoke(UpdateRequest $request, User $user)
    {
        $user->update(
            $request->validated()
        );

        return new UserResource($user);
    }
}
