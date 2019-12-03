<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

final class Destroy
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(User $user): Response
    {
        return $this->responseFactory->noContent(
            $user->delete() ? Response::HTTP_OK : Response::HTTP_CONFLICT
        );
    }
}
