<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Contracts\Http\Responses\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

final class ResponseFactory implements ResponseFactoryContract
{
    /**
     * @inheritDoc
     */
    public function make(string $content = '', int $status = Response::HTTP_OK, array $headers = []): Response
    {
        return new Response($content, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function noContent(int $status = Response::HTTP_NO_CONTENT, array $headers = []): Response
    {
        return $this->make('', $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function json(
        array $data = [],
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        if (! array_key_exists('data', $data)) {
            $data = ['data' => $data];
        }

        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function paginator(
        LengthAwarePaginator $paginator,
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return $this->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function mappedPaginator(
        LengthAwarePaginator $paginator,
        callable $map,
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        $paginator->setCollection($paginator->getCollection()->map($map));

        return $this->paginator($paginator, $status, $headers, $options);
    }
}
