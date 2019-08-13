<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Resources\Api\UserResource;
use App\Models\User;

final class Show
{
    /**
     * @param  \App\Models\User $user
     * @return \App\Http\Resources\Api\UserResource
     */
    public function __invoke(User $user)
    {
        return new UserResource($user);
    }
}
