<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile\Password;

use App\Http\Requests\Api\Profile\Password\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Contracts\Auth\Guard;

final class Update
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Guard                      $guard
     * @param  \App\Http\Requests\Api\Profile\Password\UpdateRequest $request
     * @return \App\Http\Resources\Api\UserResource
     */
    public function __invoke(Guard $guard, UpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $user->update(
            $request->validated()
        );

        return new UserResource($user);
    }
}
