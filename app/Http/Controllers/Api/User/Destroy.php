<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;

final class Destroy
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(User $user)
    {
        return $this->responseFactory->noContent(
            $user->delete() ? 200 : 409
        );
    }
}
