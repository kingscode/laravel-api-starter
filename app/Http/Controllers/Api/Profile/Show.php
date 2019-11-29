<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Http\Resources\Api\UserResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

final class Show
{
    public function __invoke(Guard $guard, Request $request)
    {
        return (new UserResource($guard->user()))->toResponse($request);
    }
}
