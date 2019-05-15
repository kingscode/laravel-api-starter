<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;

final class Destroy
{
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * Destroy constructor.
     *
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function __invoke(User $user)
    {
        return $this->responseFactory->noContent(
            $user->delete() ? 200 : 409
        );
    }
}
