<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Http\Resources\Api\UserResource;
use Illuminate\Contracts\Auth\Guard;

final class Show
{
    /**
     * @param  \Illuminate\Contracts\Auth\Guard $guard
     * @return \App\Http\Resources\Api\UserResource
     */
    public function __invoke(Guard $guard)
    {
        return new UserResource($guard->user());
    }
}
