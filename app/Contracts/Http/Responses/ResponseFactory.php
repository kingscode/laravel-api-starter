<?php

declare(strict_types=1);

namespace App\Contracts\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

interface ResponseFactory
{
    public function make(string $content = '', int $status = 200, array $headers = []): Response;

    public function noContent(int $status = Response::HTTP_NO_CONTENT, array $headers = []): Response;

    /**
     * @param  string|array|object $data
     * @param  int                 $status
     * @param  array               $headers
     * @param  int                 $options
     * @return JsonResponse
     */
    public function json(
        array $data = [],
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse;

    /**
     * @param  \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param  int                                                                                               $status
     * @param  array                                                                                             $headers
     * @param  int                                                                                               $options
     * @return JsonResponse
     */
    public function paginator(
        LengthAwarePaginator $paginator,
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse;

    /**
     * @param  \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param  callable                                                                                          $map
     * @param  int                                                                                               $status
     * @param  array                                                                                             $headers
     * @param  int                                                                                               $options
     * @return JsonResponse
     */
    public function mappedPaginator(
        LengthAwarePaginator $paginator,
        callable $map,
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse;
}
