<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class Show
{
    public function __invoke(Request $request, User $user): JsonResponse
    {
        return (new UserResource($user))->toResponse($request);
    }
}
