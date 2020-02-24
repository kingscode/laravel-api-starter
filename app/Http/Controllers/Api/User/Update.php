<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Response;

final class Update
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(UpdateRequest $request, User $user): Response
    {
        $user->update(
            $request->validated()
        );

        return $this->responseFactory->noContent(Response::HTTP_OK);
    }
}
