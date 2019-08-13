<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile\Password;

use App\Http\Requests\Api\Profile\Password\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;

final class Update
{
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Update constructor.
     *
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Contracts\Auth\Guard                      $guard
     * @param \App\Http\Requests\Api\Profile\Password\UpdateRequest $request
     * @return \App\Http\Resources\Api\UserResource
     */
    public function __invoke(Guard $guard, UpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $guard->user();

        $user->update([
            'password' => $this->hasher->make($request->input('password')),
        ]);

        return new UserResource($user);
    }
}
